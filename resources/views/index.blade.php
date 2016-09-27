@extends('layouts.app')
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
                                <input type="text" class="  search-query form-control" id="search"
                                       style="padding:4px !important;"
                                       oninput="search(this, event);" autocomplete="off"/>
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
                    $a = 0;

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
                    function textcontains($text, $searchfor) {
                        return strpos(strtolower($text), strtolower($searchfor)) !== false;
                    }
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
                    function toclass($text) {
                        return strtolower(str_replace(" ", "_", $text));
                    }




                    $toppings_display = getaddons("toppings", $isfree, $qualifiers);
                    $wings_display = getaddons("wings_sauce", $isfree, $qualifiers);
                    $classlist = array();


                    foreach ($categories as $category) {
                    /*
                    if ($a == 999) {
                        $a = 0;
                        echo '</div><div class="col-md-4">';//start a new column
                    }
                    */
                    $catclass = toclass($category['category']);
                    $classlist[] = $catclass;
                    ?>

                    <div class="card card-block p-a-0 m-a-0">


                        <a class="head_{{ $catclass }}" data-toggle="collapse"
                           href="#collapse{{$category["id"]}}_cat">
                            <h5 class="text-danger">{{$category['category']}}</h5>
                        </a>
                        <div class="collapse list-group in  " id="collapse{{$category['id']}}_cat">
                            <?
                            $menuitems = Query("SELECT * FROM menu WHERE category = '" . $category['category'] . "'", true);
                            foreach ($menuitems as $menuitem) {
                                $menuitem["price"] = number_format($menuitem["price"], 2);
                                ?>
                                <div class="menuitem item_{{ $catclass }}" itemid="{{$menuitem["id"]}}"
                                     itemname="{{$menuitem['item']}}"
                                     itemprice="{{$menuitem['price']}}"
                                     itemsize="{{ getsize($menuitem['item'], $isfree) }}" <?php
                                        $total = 0;
                                        foreach ($tables as $table) {
                                            echo $table . '="' . $menuitem[$table] . '" ';
                                            $total += $menuitem[$table];
                                        }
                                        if ($total) {
                                            $HTML = 'data-toggle="modal" data-backdrop="static" data-target="#menumodal" onclick="loadmodal(this);"';
                                            $icon = '<i class="fa fa-chevron-down pull-right text-muted"></i>';
                                        } else {
                                            $HTML = 'onclick="additemtoorder(this);"';
                                            $icon = '';
                                        }
                                        ?>>
                                        <a class="btn btn-block" style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;" <?= $HTML; ?> >
                                        <?=$icon?>
                                        <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                        <span class="pull-left itemname">{{$menuitem['item']}}</span>

                                        <span class="pull-right"> ${{$menuitem['price']}}</span>

                                    </a>

                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <div>&nbsp;</div>
                    </div>
                    <?
                    $a++;
                    } ?>

                </div>
            </div>
        </div>


        <div class="col-md-4 ">
            <div class="card">
                <div class="card-block bg-danger"
                     style="padding-top:.75rem !important;padding-bottom:.75rem !important;">
                    <h5 class="pull-left" style="margin-top: .5rem;">
                        My Order
                        <a ONCLICK="if(confirm('Are you sure you want to clear your order?')){clearorder();}">
                            <i class="fa fa-close"></i>
                        </a>
                    </h5>

                    <div class="pull-right">
                        <ul class="nav navbar-nav pull-lg-right">
                            <li class="nav-item dropdown">
                                <a style="color:white;" href="#" class="dropdown-toggle nav-link"
                                   data-toggle="dropdown"
                                   aria-haspopup="true"
                                   aria-expanded="true">
                                    <i class="fa fa-user no-padding-margin"></i></a>
                                <ul class="dropdown-menu  dropdown-menu-right">


                                    <SPAN class="loggedin profiletype profiletype1">
                                        <?php
                                            foreach (array("users", "restaurants", "useraddresses", "orders") as $table) {
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

                                    <li class="loggedin profiletype profiletype1">
                                        <A HREF="<?= webroot("public/list/all"); ?>" CLASS="dropdown-item"> <i class="fa fa-home"></i> Admin</A>
                                    </li>

                                    <li class="loggedin">
                                        <A HREF="<?= webroot("public/list/useraddresses"); ?>" class="dropdown-item"> <i class="fa fa-home"></i> Addressess</A>
                                    </li>

                                    <li class="loggedin">
                                        <A HREF="<?= webroot("public/user/info"); ?>" class="dropdown-item"> <i class="fa fa-home"></i> Profile</A>
                                    </li>

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
                    <button data-toggle="collapse" class="btn btn-block btn-warning" id="checkout" href="#collapseCheckout">
                        CHECKOUT
                    </button>
                    <div class="collapse" id="collapseCheckout">
                        <FORM ID="orderinfo">
                            <div class="input-group">
                                <span class="input-group-btn" style="width: 50% !important;">
                                    <input type="text" class="form-control" placeholder="Name"/>
                                </span>
                                <span class="input-group-btn" style="width: 50% !important;">
                                    <input type="text" class="form-control" placeholder="Cell"/>
                                </span>
                            </div>

                            <div class="input-group">
                                <span class="input-group-btn" style="width: 50% !important;">
                                    <input type="text" class="form-control" placeholder="Email"/>
                                </span>
                                <span class="input-group-btn" style="width: 50% !important;">
                                    <input type="text" class="form-control" placeholder="Delivery Time"/>
                                </span>
                            </div>

                            <input type="text" class="form-control" placeholder="Restaurant"/>
                            <input type="text" class="form-control" placeholder="Credit Card"/>
                            <input type="text" class="form-control" placeholder="Notes"/>

                            <div ID="addressdropdown" class="clear_loggedout"></div>
                            <?= view("popups.address", array("dontincludeAPI" => true, "style" => 1)); ?>

                            <button class="btn btn-warning btn-block m-t-1" onclick="placeorder();">PLACE ORDER</button>
                        </FORM>
                    </div>

                </div>
            </div>
            <div class=" m-b-3 p-t-3"></div>
        </div>


    </div>







    <!-- Modal -->
    <div class="modal" id="menumodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <div class="form-group">
                        <h5 class="modal-title" id="myModalLabel"><SPAN ID="modal-itemname"></SPAN> $<SPAN ID="modal-itemprice"></SPAN></h5>
                    </div>

                    <div style="display: none;" id="modal-hiddendata">
                        <SPAN ID="modal-itemid"></SPAN>
                        <SPAN ID="modal-toppingcost"></SPAN>
                        <SPAN ID="modal-itemsize"></SPAN>
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


                        <div  ID="modal-toppings-original" style="">
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
                        <div ID="modal-toppings-clones"  ></div>
                    </ul>

                    <button data-dismiss="modal" class="btn btn-block m-t-1  btn-warning" onclick="additemtoorder();">
                        ADD TO ORDER
                    </button>

                </div>
            </div>
        </div>
    </div>

    <script>
        var tables = <?= json_encode($tables); ?>;
        var freetoppings = <?= json_encode($isfree); ?>;
        var qualifiers = <?= json_encode($qualifiers); ?>;
        var theorder = new Array;
        var toppingsouterhtml, wingsauceouterhtml;
        var deliveryfee = <?= deliveryfee; ?>;
        var classlist = <?= json_encode($classlist); ?>;

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

        function loadmodal(element) {
            element = $(element).parent();
            $("#modal-itemname").text($(element).attr("itemname"));
            $("#modal-itemprice").text($(element).attr("itemprice"));
            $("#modal-itemid").text($(element).attr("itemid"));
            $("#modal-itemsize").text($(element).attr("itemsize"));

            var size = $(element).attr("itemsize");
            var toppingcost = 0.00;
            if (size) {
                toppingcost = Number(freetoppings[size]).toFixed(2);
                $(".toppings").attr("data-placeholder", "Add Toppings: $" + toppingcost);
                $(".toppings_price").text(toppingcost);
            }
            $("#modal-toppingcost").text(toppingcost);

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
                    minimumResultsForSearch : -1
,
                placeholder: function () {
                        $(this).data('placeholder');
                    },
                    allowClear: true
                }).change();
            }
        }
        function sendintheclones(destinationID, sourceID, count, sourceHTML) {
            var HTML = "";
            visible(sourceID, count > 0);
            if (count) {
                if (isUndefined(sourceHTML)) {
                    var sourceHTML = outerHTML(sourceID).replace('form-control select2', 'form-control select2 select2clones');
                }
                if(count == 1){
                    $(sourceID + "-ordinal").hide();
                } else {
                    $(sourceID + "-ordinal").show();
                }
                for (var index = 2; index <= count; index++) {
                    HTML += sourceHTML.replace('First', getordinal(index-1));
                }
            }
            $(destinationID).html(HTML);
        }
        function additemtoorder(element) {
            var itemid = 0, itemname = "", itemprice = 0.00, itemaddons = new Array, itemsize = "", toppingcost = 0.00, toppingscount = 0;
            if (isUndefined(element)) {//modal with addons
                itemid = $("#modal-itemid").text();
                itemname = $("#modal-itemname").text();
                itemprice = $("#modal-itemprice").text();
                itemsize = $("#modal-itemsize").text();
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
            }

            theorder.push({
                quantity: 1,
                itemid: itemid,
                itemname: itemname,
                itemprice: itemprice,
                itemsize: itemsize,
                toppingcost: toppingcost,
                toppingcount: toppingscount,
                itemaddons: itemaddons
            });
            generatereceipt();
        }

        function getordinal(index){
            var ordinals = ["First", "Second", "Third", "Fourth", "Fifth", "Sixth", "Seventh", "Eighth", "Ninth", "Tenth"];
            return ordinals[index];
        }

        function generatereceipt() {
            var HTML = '', tempHTML = "", subtotal = 0;
            for (var itemid = 0; itemid < theorder.length; itemid++) {
                var item = theorder[itemid];
                var totalcost = (Number(item["itemprice"]) + (Number(item["toppingcost"]) * Number(item["toppingcount"]))).toFixed(2);
                subtotal += Number(totalcost);
                tempHTML = '<span class="pull-left"> <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/> ' + item["itemname"] + '</span>';
                tempHTML += '<span class="pull-right" title="Base cost: ' + item["itemprice"] + ' Non-free Toppings: ' + item["toppingcount"] + ' Topping cost: $' + item["toppingcost"] + '"> $' + totalcost + ' <i class="text-muted fa fa-close" onclick="removeorderitem(' + itemid + ');"></i></span><div class="clearfix"></div>';

                var itemname = "";
                if (item["itemaddons"].length > 1) {
                    switch (item["itemaddons"][0]["tablename"]) {
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
                        tempHTML += getordinal(currentitem) + " " + itemname + " #" + ": ";
                    }
                    for (var addonid = 0; addonid < addons["addons"].length; addonid++) {
                        if (addonid > 0) {
                            tempHTML += ", ";
                        }
                        tempHTML += addons["addons"][addonid]["text"];
                    }
                    tempHTML += '<BR>';
                }
                HTML += tempHTML;
            }

            var taxes = (subtotal + deliveryfee) * 0.13;//ontario only
            totalcost = subtotal + deliveryfee + taxes;
            $("#checkout").show();
            createCookieValue("theorder", JSON.stringify(theorder));
            if (theorder.length == 0) {
                taxes = 0;
                totalcost = 0;
                HTML = '<span class="pull-left">Order is empty</SPAN><BR>';
                $("#checkout").hide();
                removeCookie("theorder");
                collapsecheckout();
            } else {
                tempHTML = '<span class="pull-right"> Sub-total: $' + subtotal.toFixed(2) + '</span><br>';
                tempHTML += '<span class="pull-right"> Delivery: $' + deliveryfee.toFixed(2) + '</span><br>';
                tempHTML += '<span class="pull-right"> Tax: $' + taxes.toFixed(2) + '</span><br>';
                tempHTML += '<span class="pull-right"> Total: $' + totalcost.toFixed(2) + '</span>';
            }
            $("#myorder").html(HTML + tempHTML);
        }

        function collapsecheckout(){
            if($("#collapseCheckout").attr("aria-expanded") == "true"){
                $("#checkout").trigger("click");
            }
        }

        function clearorder() {
            theorder = new Array;
            generatereceipt();
        }
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

        function isaddon_free(Table, Addon) {
            return freetoppings[Table].indexOf(Addon) > -1;
        }

        function isaddon_onall(Table, Addon) {
            return freetoppings["isall"][Table].indexOf(Addon) > -1;
        }

        function removeorderitem(index) {
            removeindex(theorder, index);
            generatereceipt();
        }
        function placeorder() {
            if (isObject(userdetails)) {
                $.post(webroot + "placeorder", {
                    _token: token,
                    info: getform("#orderinfo"),
                    order: theorder
                }, function (result) {
                    if (result) {
                        alert(result);
                        clearorder();
                    }
                });
            } else {
                $("#loginmodal").modal("show");
            }
        }

        $(document).ready(function () {
            toppingsouterhtml = outerHTML("#modal-toppings-original").replace('form-control select2', 'form-control select2 select2clones');
            wingsauceouterhtml = outerHTML("#modal-wings-original").replace('form-control select2', 'form-control select2 select2clones');
            if (getCookie("theorder")) {
                theorder = JSON.parse(getCookie("theorder"));

                /*
                 if (confirm("The remants of an order were saved, would you like to resume the order?")) {
                 } else if (confirm("Would you like to delete the saved order?")) {
                 removeCookie("theorder");
                 }
                 */
            }
            generatereceipt();
        });
    </script>

@endsection