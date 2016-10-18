<?php
    if (!read("id")) {
        echo view("welcome")->render();
        die();
    }
?>
@extends('layouts_app')
@section('content')
    <div class="row">

        <div class="col-md-8 ">
            <div class="card" style="background: white">
                <div class="card-block bg-danger"
                     style="padding-top:.75rem !important;padding-bottom:.75rem !important;">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="pull-left" style="margin-top: .5rem;">
                                <i class="fa fa-home" aria-hidden="true"></i> London Pizza Delivery
                            </h5>
                        </div>

                        <!--div class="col-md-6" id="custom-search-input">
                            <div class="input-group m-t-0">
                                <input type="text" class="  search-query form-control" id="search" style="padding:4px !important;" oninput="search(this, event);" autocomplete="off"/>
                                <span class="input-group-btn">
                                    <button class="btn btn-danger" type="button">
                                        <span class="fa fa-search"></span>
                                    </button>
                                </span>
                            </div>
                        </div-->
                    </div>
                </div>


                <div class="card-block card-columns">
                    <?php
                        $tables = array("toppings", "wings_sauce");
                        $qualifiers = array("DEFAULT" => array("1/2", "1x", "2x", "3x"));
                        $categories = Query("SELECT * FROM menu GROUP BY category ORDER BY id", true);
                        $isfree = collapsearray(Query("SELECT * FROM additional_toppings", true), "price", "size");
                        $deliveryfee = $isfree["Delivery"];
                        $a = 0;

                        //gets the size of the pizza
                        function getsize($itemname, &$isfree) {
                            $currentsize = "";
                            foreach ($isfree as $size => $cost) {
                                if (!is_array($cost)) {
                                    if (textcontains($itemname, $size) && strlen($size) > strlen($currentsize)) {
                                        $currentsize = $size;
                                    }
                                }
                            }
                            return $currentsize;
                        }
                        //checks if $text contains $searchfor, case insensitive
                        function textcontains($text, $searchfor) {
                            return strpos(strtolower($text), strtolower($searchfor)) !== false;
                        }

                        //process addons, generating the option group dropdown HTML, enumerating free toppings and qualifiers
                        function getaddons($Table, &$isfree, &$qualifiers) {
                            $toppings = Query("SELECT * FROM " . $Table . " ORDER BY type ASC, name ASC", true);
                            $toppings_display = '';
                            $currentsection = "";
                            $isfree[$Table] = array();
                            foreach ($toppings as $ID => $topping) {
                                if ($currentsection != $topping["type"]) {
                                    if ($toppings_display) {
                                        $toppings_display .= '</optgroup>';
                                    }
                                    $toppings_display .= '<optgroup label="' . $topping["type"] . '">';
                                    $currentsection = $topping["type"];
                                }

                                $addons[$Table][$topping["type"]][$topping["name"]] = explodetrim($topping["qualifiers"]);
                                $topping["displayname"] = $topping["name"];
                                if ($topping["isfree"]) {
                                    $isfree[$Table][] = $topping["name"];
                                    $topping["displayname"] .= " (free)";
                                }
                                if ($topping["qualifiers"]) {
                                    $qualifiers[$Table][$topping["name"]] = explodetrim($topping["qualifiers"]);
                                }
                                if ($topping["isall"]) {
                                    $isfree["isall"][$Table][] = $topping["name"];
                                }
                                $toppings_display .= '<option value="' . $topping["id"] . '" type="' . $topping["type"] . '">' . $topping["displayname"] . '</option>';
                            }
                            return $toppings_display . '</optgroup>';
                        }

                        //same as explode, but makes sure each cell is trimmed
                        function explodetrim($text, $delimiter = ",", $dotrim = true) {
                            if (is_array($text)) {
                                return $text;
                            }
                            $text = explode($delimiter, $text);
                            if (!$dotrim) {
                                return $text;
                            }
                            foreach ($text as $ID => $Word) {
                                $text[$ID] = trim($Word);
                            }
                            return $text;
                        }

                        //converts a string to a class name (lowercase, replace spaces with underscores)
                        function toclass($text) {
                            return strtolower(str_replace(" ", "_", $text));
                        }

                        $toppings_display = getaddons("toppings", $isfree, $qualifiers);
                        $wings_display = getaddons("wings_sauce", $isfree, $qualifiers);
                        $classlist = array();

                        foreach ($categories as $category) {
                            $catclass = toclass($category['category']);
                            $classlist[] = $catclass;
                            $imagefile = $catclass;
                            if(right($imagefile, 5) == "pizza" || !file_exists(public_path() . '/' . $imagefile . ".png")){$imagefile="pizza";}
                            ?>

                    <a class="head_{{ $catclass }}" data-toggle="collapse" href="#collapse{{$category["id"]}}_cat">
                        <h5 class="text-danger">{{$category['category']}}</h5>
                    </a>
                    <div class="collapse in" id="collapse{{$category['id']}}_cat">
                        <?
                            $menuitems = Query("SELECT * FROM menu WHERE category = '" . $category['category'] . "'", true);
                        ?>
                        @foreach ($menuitems as $menuitem)

                            <div class="menuitem item_{{ $catclass }}" itemid="{{$menuitem["id"]}}"
                                 itemname="{{$menuitem['item']}}"
                                 itemprice="{{$menuitem['price']}}"
                                 itemsize="{{ getsize($menuitem['item'], $isfree) }}"
                                 itemcat="{{$menuitem['category']}}"
                                <?php
                                    $total = 0;
                                    foreach ($tables as $table) {
                                        echo $table . '="' . $menuitem[$table] . '" ';
                                        $total += $menuitem[$table];
                                    }
                                ?>
                            >
                            <?php
                                    if ($total) {
                                        $HTML = 'data-toggle="modal" data-backdrop="static" data-target="#menumodal" onclick="loadmodal(this);"';
                                        $icon = '<i class="fa fa-chevron-right pull-right text-muted"></i>';
                                    } else {
                                        $HTML = 'onclick="additemtoorder(this);"';
                                        $icon = '';
                                    }
                            ?>

                                <a <?= $HTML; ?> >
                                    <?=$icon?>
                                    <img class="pull-left " src="<?= $imagefile; ?>.png" style="width:22px;margin-right:5px;"/>
                                    <span class="pull-left itemname">{{$menuitem['item']}}</span>
                                    <span class="pull-right"> ${{number_format($menuitem["price"], 2)}}</span>
                                    <div class="clearfix"></div>
                                </a>

                            </div>
                        @endforeach
                    </div>
                    <div>&nbsp;
                    </div>
                    <?
                      $a++;
                    }
                    ?>
                </div>
            </div>
        </div>

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

    <!-- order menu item Modal -->
    <div class="modal" id="menumodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <h5 class="modal-title" id="myModalLabel"><SPAN ID="modal-itemname"></SPAN> $<SPAN ID="modal-itemprice"></SPAN></h5>

                    <div style="display: none;" id="modal-hiddendata">
                        <SPAN ID="modal-itemid"></SPAN>
                        <SPAN ID="modal-toppingcost"></SPAN>
                        <SPAN ID="modal-itemsize"></SPAN>
                        <SPAN ID="modal-itemcat"></SPAN>
                    </div>

                    <ul class="list-group">
                        <div ID="modal-wings-original">
                            <select class="form-control select2 wings_sauce" multiple="multiple" data-placeholder="First Pound" type="wings_sauce">
                                <option value="blank"></option>
                                <?= $wings_display; ?>
                                <optgroup label="Options">
                                    <option value="AZ">Well Done</option>
                                    <option value="CO">Lightly Done</option>
                                </optgroup>
                            </select>
                        </div>
                        <div ID="modal-wings-clones"></div>


                        <div ID="modal-toppings-original" style="">
                            <div style="margin-bottom:.1rem;" ID="modal-toppings-original-ordinal">First Pizza</div>
                            <select style="border: 0 !important;" class="form-control select2 toppings" data-placeholder="Add Toppings: $[price]" multiple="multiple" type="toppings">
                                <!--option value="blank"></option-->
                                <?= $toppings_display; ?>
                                <optgroup label="Options">
                                    <option value="AZ">Well Done</option>
                                    <option value="CO">Lightly Done</option>
                                </optgroup>
                            </select>
                        </div>
                        <div ID="modal-toppings-clones"></div>
                    </ul>

                    <button data-dismiss="modal" class="m-t-1 btn-secondary btn pull-right" onclick="additemtoorder();">
                        ADD TO ORDER
                    </button>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- end order menu item Modal -->

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
        var tables = <?= json_encode($tables); ?>;
        var freetoppings = <?= json_encode($isfree); ?>;
        var qualifiers = <?= json_encode($qualifiers); ?>;
        var theorder = new Array;
        var toppingsouterhtml, wingsauceouterhtml;
        var deliveryfee = <?= $deliveryfee; ?>;
        var classlist = <?= json_encode($classlist); ?>;

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
            element = $(element).parent();
            $("#modal-itemname").text($(element).attr("itemname"));
            $("#modal-itemprice").text($(element).attr("itemprice"));
            $("#modal-itemid").text($(element).attr("itemid"));
            $("#modal-itemsize").text($(element).attr("itemsize"));
            $("#modal-itemcat").text($(element).attr("itemcat"));

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
            if (!isUndefined(reset)) {
                $('select').select2("val", null);
            }
            if (!isUndefined(selector)) {
                $('select' + selector).select2({
                    maximumSelectionSize: 4,
                    minimumResultsForSearch: -1
                    ,
                    placeholder: function () {
                        $(this).data('placeholder');
                    },
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
                    HTML += sourceHTML.replace('First', getordinal(index - 1));
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
                element = $(element).parent();
                itemid = $(element).attr("itemid");
                itemname = $(element).attr("itemname");
                itemprice = $(element).attr("itemprice");
                itemcat = $(element).attr("itemcat");
            }

            theorder.push({
                quantity: 1,
                itemid: itemid,
                itemname: itemname,
                itemprice: itemprice,
                itemsize: itemsize,
                category: itemcat,
                toppingcost: toppingcost,
                toppingcount: toppingscount,
                itemaddons: itemaddons
            });
            generatereceipt();
        }

        //convert numbers to their names, 0 indexed (0=first, 1=second...)
        function getordinal(index) {
            var ordinals = ["1st", "2nd", "3rd", "4th", "5th", "6th", "7th", "8th", "9th", "10th"];
            return ordinals[index];
        }

        //convert the order to an HTML receipt
        function generatereceipt() {
            var HTML = '', tempHTML = "", subtotal = 0;
            for (var itemid = 0; itemid < theorder.length; itemid++) {
                var item = theorder[itemid];
                var totalcost = (Number(item["itemprice"]) + (Number(item["toppingcost"]) * Number(item["toppingcount"]))).toFixed(2);
                var category = "pizza";
                if(item.hasOwnProperty("category")) {
                    category = item["category"].toLowerCase().replaceAll(" ", "_");
                    if (category.endswith("pizza")) {category = "pizza";}
                }

                subtotal += Number(totalcost);
                tempHTML = '<span class="pull-left"> <img class="pull-left" onerror="this.src=' + "'pizza.png'" + '" src="' + category + '.png" style="width:22px;margin-right:5px;"/> ' + item["itemname"] + '</span>';
                tempHTML += '<span class="pull-right" title="Base cost: ' + item["itemprice"] + ' Non-free Toppings: ' + item["toppingcount"] + ' Topping cost: $' + item["toppingcost"] + '"> $' + totalcost + ' <i class="text-muted fa fa-close" onclick="removeorderitem(' + itemid + ');"></i></span><div class="clearfix"></div>';

                var itemname = "";
                if (item.hasOwnProperty("itemaddons") && item["itemaddons"].length > 0) {
                    var tablename = item["itemaddons"][0]["tablename"];
                    if (item["itemaddons"].length > 1) {
                        switch (tablename) {
                            case "toppings":
                                itemname = "Pizza";
                                break;
                            case "wings_sauce":
                                itemname = "Pound";
                                break;
                        }
                    }
                    for (var currentitem = 0; currentitem < item["itemaddons"].length; currentitem++) {
                        var addons = item["itemaddons"][currentitem];
                        if (itemname) {
                            tempHTML += getordinal(currentitem) + " " + itemname + ": ";
                        }
                        if(addons["addons"].length == 0){
                            tempHTML += '[No addons]';
                        } else {
                            for (var addonid = 0; addonid < addons["addons"].length; addonid++) {
                                if (addonid > 0) {
                                    tempHTML += ", ";
                                }
                                var addonname = addons["addons"][addonid]["text"];
                                var isfree = isaddon_free(tablename, addonname);
                                log(isfree + " = " + addonname + " + " + tablename);
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
            //visible("#checkloggedout", !userdetails);

            createCookieValue("theorder", JSON.stringify(theorder));
            if (theorder.length == 0) {
                taxes = 0;
                totalcost = 0;
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
            if ($("#collapseCheckout").attr("aria-expanded") == "true") {
                $("#checkout").trigger("click");
            }
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
                            if (!addons[addid]["isfree"]) {
                                toppings++;
                            }
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
                    if (Itemname.contains(sizes[i]) && sizes[i].length > size.length) {
                        size = sizes[i];
                    }
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

        //checks if an addon is on the whole pizza (for when we implement halves)
        function isaddon_onall(Table, Addon) {
            return freetoppings["isall"][Table].indexOf(Addon) > -1;
        }

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
            if(modalID == "profilemodal"){
                $("#addresslist").html(addresses());
            }
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
                var HTML = '';
                for (var i = 0; i < userdetails["Orders"].length; i++) {
                    ID = userdetails["Orders"][i];
                    HTML += '<BUTTON CLASS="btn btn-primary" ONCLICK="orders(' + ID + ');">' + ID + '</BUTTON>';
                }
                HTML += '<BR><DIV ID="pastreceipt" CLASS="pastreceipt"></DIV>';
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
        });
    </script>
@endsection