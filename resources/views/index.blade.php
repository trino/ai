@extends('layouts_app')
@section('content')
    <div class="row">

        <?php
            //menu caching
            $menucache_db = getsetting("menucache", 1);
            $menucache_filename = resource_path() . "/menucache.html";
            $menucache_filedate = 0;
            if(file_exists($menucache_filename)){$menucache_filedate = filemtime($menucache_filename);}
            if($menucache_filedate < $menucache_db){
                $menu = view("popups_menu");
                file_put_contents($menucache_filename, $menu);
                echo '<!-- menu cache generated at : ' . now() . ' --> ';
            } else {
                $menu = '<!-- menu cache saved from: ' . $menucache_filedate . ' --> ' . file_get_contents($menucache_filename);
            }
            echo $menu . ' <!-- end menu cache -->';
        ?>
        <div class="col-md-4 ">
            <div class="card">
                <div class="card-block bg-danger"
                     style="padding-top:.75rem !important;padding-bottom:.75rem !important;">
                    <h5 class="pull-left" style="margin-top: .5rem;">
                        My Order
                        <a ONCLICK="confirm2('Are you sure you want to clear your order?', 'Clear Order', function(){clearorder();});">
                            <i class="fa fa-close"></i>
                        </a>
                    </h5>
                    <div class="pull-right">
                        <ul class="nav navbar-nav pull-lg-right">
                            <li class="nav-item dropdown">
                                <a style="color:white;" href="#" class="dropdown-toggle nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="fa fa-user no-padding-margin"></i>
                                </a>
                                <ul class="dropdown-menu  dropdown-menu-right">

                                    <SPAN class="loggedin profiletype profiletype1">
                                        <?php
                                            //administration lists
                                            foreach (array("users", "restaurants", "useraddresses", "orders", "additional_toppings") as $table) {
                                                echo '<LI><A HREF="' . webroot("public/list/" . $table) . '" CLASS="dropdown-item"><i class="fa fa-user-plus"></i> ' . ucfirst($table) . ' list</A></LI>';
                                            }
                                        ?>
                                        <li><A HREF="<?= webroot("public/editmenu"); ?>" CLASS="dropdown-item"><i class="fa fa-user-plus"></i> Edit Menu</A></li>
                                        <li><A HREF="<?= webroot("public/list/debug"); ?>" CLASS="dropdown-item"><i class="fa fa-user-plus"></i> Debug log</A></li>
                                        <HR>
                                    </SPAN>

                                    <li>
                                        <SPAN class="dropdown-item"><i class="fa fa-home"></i> <SPAN CLASS="session_name"></SPAN></SPAN>
                                    </li>

                                    <SPAN class="loggedin">
                                        <li>
                                            <A ONCLICK="orders();" class="dropdown-item"> <i class="fa fa-home"></i> Past Orders</A>
                                        </li>
                                        <li>
                                            <A data-toggle="modal" data-target="#profilemodal" class="dropdown-item"><i class="fa fa-home"></i> Profile</A>
                                        </li>
                                    </SPAN>

                                    <li>
                                        <A ONCLICK="handlelogin('logout');" CLASS="hyperlink dropdown-item loggedin"> <i class="fa fa-home"></i> Log out</A>
                                        <A CLASS="loggedout dropdown-item hyperlink" data-toggle="modal" data-target="#loginmodal"> <i class="fa fa-home"></i> Log In</A>
                                    </li>

                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-block">
                    <div id="myorder"></div>
                    <SPAN ID="checkoutbutton">
                        <button class="btn btn-block btn-warning loggedin" id="checkout" onclick="showcheckout();">
                            CHECKOUT
                        </button>
                    </SPAN>

                    @include("popups_checkout")
                </div>
            </div>
            <div class=" m-b-3 p-t-3"></div>
        </div>
    </div>

    <!-- edit profile Modal -->
    <div class="modal" id="profilemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <div class="form-group">
                        <h5 class="modal-title" id="myModalLabel">Edit Profile</h5>
                    </div>

                    <FORM NAME="user" id="userform">
                        @include("popups_edituser", array("showpass" => true))
                    </FORM>

                    <DIV ID="addresslist"></DIV>

                    <DIV CLASS="row">
                        <DIV CLASS="col-md-12" align="center">
                            <BUTTON CLASS="btn btn-primary" onclick="userform_submit();">Save</BUTTON>
                        </DIV>
                    </DIV>
                </div>
            </div>
        </div>
    </div>
    <!-- end edit profile Modal -->
    <script>
        //handles the search text box
        function search(element) {
            var searchtext = element.value.toLowerCase();
            $(".menuitem").each(function (index) {
                var ismatch = false;
                if (searchtext) {
                    var itemtext = $(this).attr("itemname").toLowerCase();
                    if (itemtext.indexOf(searchtext) > -1) {
                        ismatch = true;
                    }
                } else {
                    ismatch = true;
                }
                if (ismatch) {
                    $(this).removeClass("disabled");
                } else {
                    $(this).addClass("disabled");
                }
            });
            for (var i = 0; i < classlist.length; i++) {
                var classname = classlist[i];
                var visible = $(".item_" + classname + ":visible").length;
                if (visible) {
                    $(".head_" + classname).show();
                } else {
                    $(".head_" + classname).hide();
                }
            }
        }

        //generates the order menu item modal
        function loadmodal(element) {
            element = $(element).parent().parent();
            var items = ["name", "price", "id", "size", "cat"];
            for(var i=0; i<items.length; i++){
                $("#modal-item" + items[i]).text($(element).attr("item" + items[i]));
            }
            var size = $(element).attr("itemsize");
            var toppingcost = 0.00;
            if (size) {
                toppingcost = Number(freetoppings[size]).toFixed(2);
                $(".toppings").attr("data-placeholder", "Add Toppings: $" + toppingcost);
                $(".toppings_price").text(toppingcost);
            }
            $("#modal-toppingcost").text(toppingcost);

            //clones the addon dropdowns
            initSelect2(".select2", true);
            sendintheclones("#modal-wings-clones", "#modal-wings-original", $(element).attr("wings_sauce"), wingsauceouterhtml);
            sendintheclones("#modal-toppings-clones", "#modal-toppings-original", $(element).attr("toppings"), toppingsouterhtml.replace('[price]', toppingcost));
            initSelect2(".select2clones");
        }

        function initSelect2(selector, reset) {
            if (!isUndefined(reset)) {$('select').select2("val", null);}
            if (!isUndefined(selector)) {
                $('select' + selector).select2({
                    maximumSelectionSize: 4,
                    minimumResultsForSearch: -1,
                    placeholder: function () {$(this).data('placeholder');},
                    allowClear: true
                }).change();
            }
        }

        //makes HTML clones of a dropdown
        function sendintheclones(destinationID, sourceID, count, sourceHTML) {
            var HTML = "";
            visible(sourceID, count > 0);
            if (count) {
                if (isUndefined(sourceHTML)) {
                    var sourceHTML = outerHTML(sourceID).replace('form-control select2', 'form-control select2 select2clones');
                }
                if (count == 1) {
                    $(sourceID + "-ordinal").hide();
                } else {
                    $(sourceID + "-ordinal").show();
                }
                for (var index = 2; index <= count; index++) {
                    HTML += sourceHTML.replace('First', ordinals[index - 1]);
                }
            }
            $(destinationID).html(HTML);
        }

        //get the data from the modal and add it to the order
        function additemtoorder(element) {
            var itemid = 0, itemname = "", itemprice = 0.00, itemaddons = new Array, itemsize = "", toppingcost = 0.00, toppingscount = 0, itemcat = "";
            if (isUndefined(element)) {//modal with addons
                itemid = $("#modal-itemid").text();
                itemname = $("#modal-itemname").text();
                itemprice = $("#modal-itemprice").text();
                itemsize = $("#modal-itemsize").text();
                itemcat = $("#modal-itemcat").text();
                itemaddons = getaddons();
                if (itemsize) {
                    toppingcost = Number(freetoppings[itemsize]).toFixed(2);
                }
                for (var i = 0; i < itemaddons.length; i++) {
                    toppingscount += itemaddons[i]["count"];
                }
            } else {//direct link, no addons
                element = $(element).parent().parent();
                itemid = $(element).attr("itemid");
                itemname = $(element).attr("itemname");
                itemprice = $(element).attr("itemprice");
                itemcat = $(element).attr("itemcat");
            }

            data = {
                quantity: 1,
                itemid: itemid,
                itemname: itemname,
                itemprice: itemprice,
                itemsize: itemsize,
                category: itemcat,
                toppingcost: toppingcost,
                toppingcount: toppingscount,
                itemaddons: itemaddons
            };
            theorder.push(data);
            generatereceipt();
        }

        //convert the order to an HTML receipt
        function generatereceipt() {
            var HTML = '', tempHTML = "", subtotal = 0;
            var itemnames = {toppings: "Pizza", wings_sauce: "Pound"};
            for (var itemid = 0; itemid < theorder.length; itemid++) {
                var item = theorder[itemid];
                var totalcost = (Number(item["itemprice"]) + (Number(item["toppingcost"]) * Number(item["toppingcount"]))).toFixed(2);
                var category = "pizza";
                if(item.hasOwnProperty("category")) {
                    category = item["category"].toLowerCase().replaceAll(" ", "_");
                    if (category.endswith("pizza")) {category = "pizza";}
                }

                subtotal += Number(totalcost);
                //tempHTML = '<span class="pull-left"> <img class="pull-left" onerror="this.src=' + "'pizza.png'" + '" src="' + category + '.png" style="width:22px;margin-right:5px;"/> ' + item["itemname"] + '</span>';
                tempHTML = '<span class="pull-left"> <DIV CLASS="sprite sprite-' + category + ' sprite-tiny"></DIV> ' + item["itemname"] + '</span>';
                tempHTML += '<span class="pull-right" title="Base cost: ' + item["itemprice"] + ' Non-free Toppings: ' + item["toppingcount"] + ' Topping cost: $' + item["toppingcost"] + '"> $' + totalcost + ' <i class="text-muted fa fa-close" onclick="removeorderitem(' + itemid + ');"></i></span><div class="clearfix"></div>';

                var itemname = "";
                if (item.hasOwnProperty("itemaddons") && item["itemaddons"].length > 0) {
                    var tablename = item["itemaddons"][0]["tablename"];
                    if (item["itemaddons"].length > 1) {itemname = itemnames[tablename];}
                    for (var currentitem = 0; currentitem < item["itemaddons"].length; currentitem++) {
                        var addons = item["itemaddons"][currentitem];
                        if (itemname) {tempHTML += ordinals[currentitem] + " " + itemname + ": ";}
                        if(addons["addons"].length == 0){
                            tempHTML += '[No addons]';
                        } else {
                            for (var addonid = 0; addonid < addons["addons"].length; addonid++) {
                                if (addonid > 0) {tempHTML += ", ";}
                                var addonname = addons["addons"][addonid]["text"];
                                var isfree = isaddon_free(tablename, addonname);
                                if (isfree) {
                                    tempHTML += '<I TITLE="Free addon">' + addonname + '</I>';
                                } else {
                                    tempHTML += addonname;
                                }
                            }
                        }
                        tempHTML += '<BR>';
                    }
                }
                HTML += tempHTML;
            }
            var taxes = (subtotal + deliveryfee) * 0.13;//ontario only
            totalcost = subtotal + deliveryfee + taxes;

            $("#checkoutbutton").show();
            visible("#checkout", userdetails);

            createCookieValue("theorder", JSON.stringify(theorder));
            if (theorder.length == 0) {
                HTML = '<span class="pull-left">Order is empty</SPAN><BR>';
                $("#checkout").hide();
                $("#checkoutbutton").hide();
                removeCookie("theorder");
                collapsecheckout();
                $("#checkout-btn").hide();
            } else {
                tempHTML = '<span class="pull-right"> Sub-total: $' + subtotal.toFixed(2) + '</span><br>';
                tempHTML += '<span class="pull-right"> Delivery: $' + deliveryfee.toFixed(2) + '</span><br>';
                tempHTML += '<span class="pull-right"> Tax: $' + taxes.toFixed(2) + '</span><br>';
                tempHTML += '<span class="pull-right"> Total: $' + totalcost.toFixed(2) + '</span>';
                $("#checkout-total").text('$' + totalcost.toFixed(2));
                $("#checkout-btn").show();
            }
            $("#myorder").html(HTML + tempHTML);
        }

        //hides the checkout form
        function collapsecheckout() {
            if ($("#collapseCheckout").attr("aria-expanded") == "true") {$("#checkout").trigger("click");}
        }

        function clearorder() {
            theorder = new Array;
            generatereceipt();
        }

        //gets the addons from each dropdown
        function getaddons() {
            var itemaddons = new Array;
            for (var tableid = 0; tableid < tables.length; tableid++) {
                var table = tables[tableid];
                $('.select2.' + table + ":visible").each(function (index) {
                    if (!$(this).hasClass("select2-offscreen")) {
                        var addons = $(this).select2('data');
                        var toppings = 0;
                        for (var addid = 0; addid < addons.length; addid++) {
                            delete addons[addid]["element"];
                            delete addons[addid]["locked"];
                            delete addons[addid]["disabled"];
                            if (addons[addid]["text"].endswith("(free)")) {
                                addons[addid]["text"] = addons[addid]["text"].left(addons[addid]["text"].length - 6).trim();
                            }
                            addons[addid]["isfree"] = isaddon_free(table, addons[addid]["text"]);
                            if (!addons[addid]["isfree"]) {toppings++;}
                        }
                        itemaddons.push({tablename: table, addons: addons, count: toppings});
                    }
                });
            }
            return itemaddons;
        }

        //get the size of a pizza
        function getsize(Itemname) {
            var sizes = Object.keys(freetoppings);
            var size = "";
            for (var i = 0; i < sizes.length; i++) {
                if (!isArray(freetoppings[sizes[i]])) {
                    if (Itemname.contains(sizes[i]) && sizes[i].length > size.length) {size = sizes[i];}
                }
            }
            return size;
        }

        //checks if an addon is free
        function isaddon_free(Table, Addon) {
            switch(Addon.toLowerCase()){
                case "lightly done":case "well done": return true; break;
                default: return freetoppings[Table].indexOf(Addon) > -1;
            }
        }

        /*checks if an addon is on the whole pizza (for when we implement halves)
        function isaddon_onall(Table, Addon) {
            return freetoppings["isall"][Table].indexOf(Addon) > -1;
        }*/

        //remove an item from the order
        function removeorderitem(index) {
            removeindex(theorder, index);
            generatereceipt();
        }

        //checks if the result is JSON, and processes the Status and Reasons
        function handleresult(result, title) {
            try {
                var data = JSON.parse(result);
                if (data["Status"] == "false" || !data["Status"]) {
                    alert(data["Reason"], title);
                } else {
                    return true;
                }
            } catch (e) {
                alert(result, title);
            }
            return false;
        }

        //send an order to the server
        function placeorder() {
            if(!canplaceorder){return false;}
            if (isObject(userdetails)) {
                var addressinfo = getform("#orderinfo");//i don't know why the below 2 won't get included. this forces them to be
                addressinfo["cookingnotes"] = $("#cookingnotes").val();
                addressinfo["deliverytime"] = $("#deliverytime").val();
                $.post(webroot + "placeorder", {
                    _token: token,
                    info: addressinfo,
                    order: theorder,
                    name: $("#reg_name").val(),
                    phone: $("#reg_phone").val()
                }, function (result) {
                    $("#checkoutmodal").modal("hide");
                    if (result.contains("ordersuccess")) {
                        clearorder();
                    }
                    handleresult(result, "Order Placed Successfully!");
                });
            } else {
                $("#loginmodal").modal("show");
            }
        }

        $(window).on('shown.bs.modal', function() {
            var modalID = $(".modal:visible").attr("id");
            if(modalID == "profilemodal"){$("#addresslist").html(addresses());}
        });

        //generate a list of addresses and send it to the alert modal
        function addresses() {
            var HTML = '<h5>Addresses</h5>';
            var number = $("#add_number").val();
            var street = $("#add_street").val();
            var city = $("#add_city").val();
            var AddNew = number && street && city;
            $("#saveaddresses option").each(function () {
                var ID = $(this).val();
                if (ID > 0) {
                    if (userdetails["cc_addressid"] == ID) {
                        HTML += '<A TITLE="This address is used for your credit card and cannot be deleted"><i class="fa fa-fw fa-credit-card"></i> ';
                    } else {
                        HTML += '<A ID="add_' + ID + '" TITLE="Delete this address" onclick="deleteaddress(' + ID + ');" class="hyperlink"><i style="color:red" class="fa fa-fw fa-times"></i> ';
                    }
                    HTML += $(this).text() + '</A><BR>';
                    if (number.isEqual($(this).attr("number")) && street.isEqual($(this).attr("street")) && city.isEqual($(this).attr("city"))) {
                        AddNew = false;
                    }
                }
            });
            if (AddNew) {
                HTML += '<A ONCLICK="deleteaddress(-1);" CLASS="hyperlink">Add ' + "'" + number + " " + street + ", " + city + "' to the list</A>";
            } else {
                HTML += 'Enter a new address in the checkout form if you want to add it to your profile';
            }
            return HTML;
        }

        //handles the orders list modal
        function orders(ID, getJSON) {
            if (isUndefined(ID)) {//no ID specified, get a list of order IDs from the user's profile and make buttons
                var HTML = '<ul class="list-group">';
                for (var i = 0; i < userdetails["Orders"].length; i++) {
                    var order = userdetails["Orders"][i];
                    ID = order["id"];
                    //HTML += '<BUTTON CLASS="btn btn-primary" ONCLICK="orders(' + ID + ');">' + ID + ": " + order["placed_at"] + '</BUTTON>';
                    HTML += '<li class="list-group-item" ONCLICK="orders(' + ID + ');"><span class="tag tag-default tag-pill pull-xs-right">ID: ' + ID + '</span>' + order["placed_at"] + '</li>';
                }
                HTML += '</ul><P><DIV ID="pastreceipt" CLASS="pastreceipt">Click an order to view the contents</DIV><P>';
                alert(HTML, "Orders");
            } else {
                if (isUndefined(getJSON)) {
                    getJSON = false;
                }
                $.post("<?= webroot('public/list/orders'); ?>", {
                    _token: token,
                    action: "getreceipt",
                    orderid: ID,
                    JSON: getJSON
                }, function (result) {
                    if (getJSON) {//JSON recieved, put it in the order
                        result = JSON.parse(result);
                        theorder = result["Order"];
                        $("#cookingnotes").val(result["cookingnotes"]);
                        generatereceipt();
                        $("#alertmodal").modal('hide');
                    } else {//HTML recieved, put it in the pastreceipt element
                        $("#pastreceipt").html(result);
                    }
                });
            }
        }

        $(document).ready(function () {
            toppingsouterhtml = outerHTML("#modal-toppings-original").replace('form-control select2', 'form-control select2 select2clones');
            wingsauceouterhtml = outerHTML("#modal-wings-original").replace('form-control select2', 'form-control select2 select2clones');
            if (getCookie("theorder")) {
                theorder = JSON.parse(getCookie("theorder"));
            }
            generatereceipt();
            @if(!read("id"))
                $("#loginmodal").modal("show");
            @endif
        });
    </script>
@endsection