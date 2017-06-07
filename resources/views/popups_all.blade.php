<?php
    startfile("popups_" . $popup);
    switch($popup){
        case "menu":
            if (!function_exists("getsize")) {
                //gets the size of the pizza
                function getsize($itemname, &$isfree){
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
                function textcontains($text, $searchfor){
                    return strpos(strtolower($text), strtolower($searchfor)) !== false;
                }

                //process addons, generating the option group dropdown HTML, enumerating free toppings and qualifiers
                function getaddons($Table, &$isfree, &$qualifiers, &$addons, &$groups){
                    $toppings = Query("SELECT * FROM " . $Table . " WHERE enabled = 1 ORDER BY id asc, type ASC, name ASC", true);
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
                        $addons[$Table][$topping["type"]][] = explodetrim($topping["name"]);
                        $addons[$Table . "_id"][$topping["id"]] = $topping["name"];
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
                        if ($topping["groupid"] > 0) {
                            $groups[$Table][$topping["name"]] = $topping["groupid"];
                        }
                        $toppings_display .= '<option value="' . $topping["id"] . '" type="' . $topping["type"] . '">' . $topping["displayname"] . '</option>';
                    }
                    return $toppings_display . '</optgroup>';
                }

                //same as explode, but makes sure each cell is trimmed
                function explodetrim($text, $delimiter = ",", $dotrim = true){
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
                function toclass($text){
                    $text = strtolower(str_replace(" ", "_", trim($text)));
                    return $text;
                }

                function endwith($Text, $WithWhat){
                    return strtolower(right($Text, strlen($WithWhat))) == strtolower($WithWhat);
                }
            }

            $qualifiers = array("DEFAULT" => array("1/2", "1x", "2x", "3x"));
            $categories = Query("SELECT * FROM menu GROUP BY category ORDER BY id", true);
            $isfree = collapsearray(Query("SELECT * FROM additional_toppings", true), "price", "size");
            $deliveryfee = $isfree["Delivery"];
            $minimum = $isfree["Minimum"];
            $addons = array();
            $classlist = array();
            $groups = array();
            $toppings_display = getaddons("toppings", $isfree, $qualifiers, $addons, $groups);
            $wings_display = getaddons("wings_sauce", $isfree, $qualifiers, $addons, $groups);

            $tables = array("toppings", "wings_sauce");
            $totalmenuitems = countSQL("menu");
            $maxmenuitemspercol = $totalmenuitems / 3; //17
            $itemsInCol = 0;
            $CurrentCol = 1;
            $CurrentCat = 0;
            ?>
            <div class="col-lg-3 col-md-12 bg-white">
                @foreach ($categories as $category)
                    <?php
                        $toppings_extra = '+';
                        $catclass = toclass($category['category']);
                        $classlist[] = $catclass;
                        $menuitems = Query("SELECT * FROM menu WHERE category = '" . $category['category'] . "' AND enabled = 1 order by id", true);
                        $menuitemcount = count($menuitems);
                        if ($itemsInCol + $menuitemcount > $maxmenuitemspercol && $CurrentCol < 3) {
                            $itemsInCol = 0;
                            $CurrentCol += 1;
                            //echo '</DIV><div class="col-md-4" style="background:white;">';
                        }
                        $itemsInCol += $menuitemcount;
                        echo '<div class="border-category text-danger strong list-group-item" ID="category_' . $CurrentCat . '"><h2>' . $category['category'] . '</h2></div>';
                        $CurrentCat +=1;
                    ?>
                    @foreach ($menuitems as $menuitem)
                        <button class="cursor-pointer list-group-item list-group-item-action hoveritem d-flex justify-content-start item_{{ $catclass }}"
                                itemid="{{$menuitem["id"]}}"
                                itemname="{{trim($menuitem['item'])}}"
                                itemprice="{{$menuitem['price']}}"
                                itemsize="{{getsize($menuitem['item'], $isfree)}}"
                                itemcat="{{$menuitem['category']}}"
                                calories="{{$menuitem['calories']}}"
                                allergens="{{$menuitem['allergens']}}"
                        <?php
                                $itemclass = $catclass;
                                if ($itemclass == "sides") {
                                    $itemclass = str_replace("_", "-", toclass($menuitem['item']));
                                    if (endwith($itemclass, "lasagna")) {
                                        $itemclass = "lasagna";
                                    } else if (endwith($itemclass, "chicken-nuggets")) {
                                        $itemclass = "chicken-nuggets";
                                    } else if (endwith($itemclass, "salad")) {
                                        $itemclass = "salad";
                                    } else if ($itemclass == "panzerotti") {
                                        $icon = $toppings_extra;
                                    }
                                } else if ($itemclass == "drinks") {
                                    $itemclass .= " sprite-" . str_replace(".", "", str_replace("_", "-", toclass($menuitem['item'])));
                                } else if ($itemclass == "pizza") {
                                    if (left($menuitem['item'], 1) == "2") {
                                        $itemclass = "241_pizza";
                                    }
                                    $icon = $toppings_extra;
                                }

                                $total = 0;
                                foreach ($tables as $table) {
                                    echo $table . '="' . $menuitem[$table] . '" ';
                                    $total += $menuitem[$table];
                                }
                                if ($total) {
                                    $HTML = ' data-toggle="modal" data-backdrop="static" data-target="#menumodal" onclick="loadmodal(this);"';
                                } else {
                                    $HTML = ' onclick="additemtoorder(this, -1);"';
                                    $icon = '';
                                }
                                echo $HTML;
                                ?>
                            >

                            <span class="align-middle item-icon rounded-circle bg-warning sprite sprite-{{$itemclass}} sprite-medium"></span>
                            <span class="align-middle item-name">{{$menuitem['item']}} </span>
                            <span class="text-muted ml-auto align-middle btn-sm-padding item-cost"> ${{number_format($menuitem["price"], 2)}}<?= $icon; ?></span>
                        </button>
                    @endforeach
                    @if($catclass=="dips" || $catclass=="sides")
            </div>

            <div class="col-lg-3 col-md-12 bg-white">
                @endif
                @endforeach
            </div>

            <!-- order menu item Modal -->
            <div class="modal modal-fullscreen force-fullscreen" id="menumodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">

                        <div class="dont-show" id="modal-hiddendata">
                            <SPAN ID="modal-itemprice"></SPAN>
                            <SPAN ID="modal-itemid"></SPAN>
                            <SPAN ID="modal-itemsize"></SPAN>
                            <SPAN ID="modal-itemcat"></SPAN>
                        </div>

                        <div class="list-group-item" style="background: #fff !important; border-bottom: 0px solid #d9534f !important;" >
                            <h2 class="text-normal" id="myModalLabel">
                                <SPAN ID="modal-itemname"></SPAN><br>
                                <small ID="toppingcost" class="nowrap text-muted">$<SPAN id="modal-toppingcost"></SPAN> per topping</small>
                            </h2>
                            <button data-dismiss="modal" class="btn btn-sm ml-auto bg-transparent text-normal"><i class="fa fa-close"></i></button>
                        </div>

                        <div class="modal-body" style="padding: 0 !important;">
                            <DIV ID="addonlist" class="addonlist"></DIV>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                var tables = <?= json_encode($tables); ?>;
                var alladdons = <?= json_encode($addons); ?>;
                var freetoppings = <?= json_encode($isfree); ?>;
                var qualifiers = <?= json_encode($qualifiers); ?>;
                var groups = <?= json_encode($groups); ?>;
                var theorder = new Array;
                var deliveryfee = <?= $deliveryfee; ?>;
                var minimumfee = <?= $minimum; ?>;
                var classlist = <?= json_encode($classlist); ?>;
                var ordinals = ["1st", "2nd", "3rd", "4th", "5th", "6th", "7th", "8th", "9th", "10th"];

                $(".hoveritem").hover(
                        function(e){
                            var calories = $(this).attr("calories");
                            var allergens = $(this).attr("allergens");
                            if(calories || allergens) {
                                var position = $(this).position();
                                position.left = $(this).offset().left - $("#category_0").offset().left;
                                var height = $(this).outerHeight();
                                var bottom = position.top + height;
                                var tooltipheight = height * 2 - 1;
                                var onecolumnwidth = $(this).outerWidth();
                                var twocolumnwidth = onecolumnwidth * 2 - 2;
                                var containerwidth = $(".container-fluid").width();

                                if(containerwidth < onecolumnwidth){//rare mobile device with hover ability
                                    position.top = position.top + $(this).parent().position().top;
                                    bottom = position.top + height;
                                    twocolumnwidth = onecolumnwidth - 1;
                                } else if (position.left > 400) {//last col
                                    position.left = position.left - onecolumnwidth;
                                }
                                if (isbelowhalf(bottom)) {//below middle of screen
                                    bottom = position.top - tooltipheight - 1;
                                }
                                $("#nutritiontooltip").css({
                                    position: "absolute",
                                    left: position.left,
                                    top: bottom,
                                    width: twocolumnwidth,
                                    height: tooltipheight
                                }).stop().show(100);
                                var HTML = "";
                                if(calories){
                                    HTML += "Calories: " + calories;
                                }
                                if(allergens){
                                    allergens = allergens.split(",");
                                    for(var i=0;i<allergens.length;i++){
                                        var allergen = allergens[i];
                                        var quantity = false;
                                        var indexOf = allergen.indexOf("=");
                                        if(indexOf > -1){
                                            quantity = allergen.right( allergen.length - indexOf - 1);
                                            allergen = allergen.left(indexOf);
                                        }
                                        if(HTML){HTML += ", ";}
                                        HTML += ucfirst(allergen);
                                        if(quantity) {
                                            HTML += ": " + quantity;
                                        }
                                    }
                                }
                                $("#nutritioninfo").html(HTML);
                                visible("#nutritionnote", $(this).attr("calories"));
                            }
                        },
                        function(e){
                            $("#nutritiontooltip").hide();
                        }
                );

                function isbelowhalf(Y){
                    return Y - $(window).scrollTop() > $( window ).height() * 0.5;
                }
            </script>

            <DIV ID="nutritiontooltip" class="custom-tooltip">
                <DIV ID="nutritioninfo"></DIV>
                <SPAN CLASS="nutritionnote">2,000 calories a day is used for general nutrition advice, but calorie needs vary</SPAN>
            </DIV>
            <!-- end order menu item Modal -->
            <!-- end menu cache -->
            <?php
            break;
        case "googlemaps":
            ?>
            <style>
                #map {
                    height: 500px;
                    border-color: red;
                }
            </style>
            <script>
                var map, infowindow, service, markers = new Array();
                $(window).load(function () {
                    <?php
                        if(isset($latitude) && isset($longitude)){
                            echo 'initMap(' . $latitude . ', ' . $longitude . ');';
                        }
                    ?>
                });

                function initMap(latitude, longitude) {
                    if(isUndefined(latitude) || isUndefined(longitude)){log("FAILURE TO INIT MAPS"); return;}
                    log("initMap: " + latitude + ", " + longitude);
                    map = new google.maps.Map(document.getElementById('map'), {
                        center: {lat: latitude, lng: longitude},
                        zoom: 15
                    });

                    infowindow = new google.maps.InfoWindow();
                    service = new google.maps.places.PlacesService(map);
                }

                function addmarker(Name, Latitude, Longitude){
                    var myLatlng = new google.maps.LatLng(parseFloat(Latitude),parseFloat(Longitude));
                    var marker = new google.maps.Marker({
                        map: map,
                        position: myLatlng
                    });
                    google.maps.event.addListener(marker, 'click', function() {
                        infowindow.setContent(Name);
                        infowindow.open(map, this);
                    });
                }

                function addmarker2(Name, Latitude, Longitude, Delimiter, Prepend, Append){
                    if(isUndefined(Name)){
                        for(var id = 0; id < markers.length; id++){
                            addmarker(markers[id]["Prepend"] + markers[id]["Name"] + markers[id]["Append"], markers[id]["Latitude"], markers[id]["Longitude"]);
                        }
                    } else {
                        if(isUndefined(Prepend)){Prepend="";}
                        if(isUndefined(Append)){Append="";}
                        if(isUndefined(Delimiter)){Delimiter="<BR>";}
                        for(var id = 0; id < markers.length; id++){
                            if(markers[id]["Latitude"] == Latitude && markers[id]["Longitude"] == Longitude){
                                markers[id]["Name"] += Delimiter + Name;
                                return id;
                            }
                        }
                        markers.push({Name: Name, Latitude: Latitude, Longitude: Longitude, Prepend: Prepend, Append: Append});
                    }
                }
            </script>
            <div id="map"></div>
            @if(!isset($includeapi))
                <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBWSUc8EbZYVKF37jWVCb3lpBQwWqXUZw8&signed_in=true&libraries=places&callback=initMap" async defer></script>
            @endif
            <?php
            break;
        case "editprofile_modal":
            ?>
                <div class="modal" id="profilemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2 id="myModalLabel" class="align-middle">My Profile</h2>
                                <button data-popup-close="profilemodal" data-dismiss="modal" class="btn btn-sm ml-auto align-middle bg-transparent"><i class="fa fa-close"></i></button>
                            </div>

                            <div class="modal-body">
                                <FORM NAME="user" id="userform">
                                    <?= popups_view("edituser", array("showpass" => true, "email" => false, "icons" => true)); ?>

                                    <div class="input_left_icon"><span class="fa-stack fa-2x"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-envelope text-white fa-stack-1x"></i></span></div>
                                    <div class="input_right"><input type="text" readonly class="form-control session_email_val"></div>

                                    @if(read("profiletype"))
                                        <div class="input_left_icon"><span class="fa-stack fa-2x"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-user-plus text-white fa-stack-1x"></i></span></div>
                                        <div class="input_right"><input type="text" readonly class="form-control" value="<?= array("User", "Admin", "Restaurant")[read("profiletype")]; ?>"></div>
                                    @endif

                                    <DIV class="clearfix mt-1"></DIV>
                                    <DIV CLASS="error" id="edituser_error"></DIV>
                                    <DIV class="clearfix mt-1"></DIV>

                                    <BUTTON CLASS="btn-link pull-right" onclick="return userform_submit(true);">SAVE</BUTTON>
                                </FORM>


                                <div CLASS="editprofilediv">
                                    <DIV ID="addresslist"></DIV>
                                </div>
                                <hr>
                                <div CLASS="editprofilediv">
                                    <DIV ID="cardlist"></DIV>
                                </div>

                                <div class="alert alert-info mt-3 mb-0" style="font-size: .85rem">
                                    > Add a new address on checkout
                                    <br> > Add a new credit/debit card on checkout
                                    <br> > <a href="help" class="btn-link">MORE INFO</a>
                                </div>
                                <div CLASS="editprofilediv mt-2 dont-show">
                                    <button ONCLICK="handlelogin('logout');" CLASS="btn btn-primary pull-left" href="#">LOG OUT</button>
                                    <button ONCLICK="orders();" CLASS="btn btn-primary pull-right" href="#">PAST ORDERS</button>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            break;
        case "checkout":
            ?>
                <div class="list-group-item">
                    <h2 class="mr-auto align-left"> My Order</h2>
                    <button class="btn-sm dont-show ml-auto align-right bg-transparent" ONCLICK="confirmclearorder();" id="confirmclearorder"><i class="fa fa-close"></i></button>
                </div>

                <div id="myorder"></div>

                @if(read("id"))
                    <button id="checkout-btn" class="list-padding btn btn-primary btn-block" onclick="showcheckout();">
                        <i class="fa fa-shopping-basket mr-2"></i> CHECKOUT
                    </button>
                @else
                    <button id="checkout-btn" class="list-padding btn btn-primary btn-block" onclick="scrolltotop();">
                        <i class="fa fa-sign-in mr-2"></i> LOG IN TO CHECKOUT
                    </button>
                @endif

                <div class="modal" id="checkoutmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2 id="myModalLabel" style="text-transform: uppercase;">Hi, <SPAN CLASS="session_name"></SPAN></h2>
                                <button data-dismiss="modal" data-popup-close="checkoutmodal" class="btn btn-sm ml-auto align-middle bg-transparent"><i class="fa fa-close"></i></button>
                            </div>
                            <div class="modal-body" style="padding: 0 !important;">
                                <FORM ID="orderinfo" name="orderinfo">
                                    <div class="input_left_icon" id="red_address">
                                        <span class="fa-stack fa-2x">
                                          <i class="fa fa-circle fa-stack-2x"></i>
                                          <i class="fa fa-map-marker text-white fa-stack-1x"></i>
                                        </span>
                                    </div>

                                    <div class="input_right">
                                        <div class="clear_loggedout addressdropdown proper-height" id="checkoutaddress"></div>
                                        <?php
                                            if (read("id")) {
                                                echo popups_view("address", array("dontincludeAPI" => true, "style" => 1, "saveaddress" => true, "form" => false, "findclosest" => true, "autored" => "red_address"));
                                            }
                                        ?>
                                    </div>

                                    <div class="input_left_icon" id="red_rest">
                                        <span class="fa-stack fa-2x">
                                          <i class="fa fa-circle fa-stack-2x"></i>
                                          <i class="fa fa-cutlery text-white fa-stack-1x" style="font-size: .9rem !important;"></i>
                                        </span>
                                    </div>

                                    <div class="input_right">
                                        <SELECT class="form-control" ID="restaurant" ONCHANGE="restchange();">
                                            <OPTION VALUE="0" SELECTED>Select Restaurant</OPTION>
                                        </SELECT>
                                    </div>

                                    <div class="input_left_icon">
                                        <span class="fa-stack fa-2x">
                                          <i class="fa fa-circle fa-stack-2x"></i>
                                          <i class="fa fa-clock-o text-white fa-stack-1x"></i>
                                        </span>
                                    </div>

                                    <div class="input_right">
                                        <div>
                                            <SELECT id="deliverytime" TITLE="Delivery Time" class="form-control"/>
                                                <OPTION>Deliver ASAP</OPTION>
                                            </SELECT>
                                        </div>
                                    </div>

                                    @if(!read('phone'))
                                        <div class="input_left_icon redhighlite" id="red_phone">
                                            <span class="fa-stack fa-2x">
                                              <i class="fa fa-circle fa-stack-2x"></i>
                                              <i class="fa fa-mobile-phone text-white fa-stack-1x" style="font-size: 1.5rem !important;"></i>
                                            </span>
                                        </div>
                                        <div class="input_right">
                                            <input type="tel" name="phone" id="reg_phone" class="form-control session_phone_val" placeholder="Cell Phone" required="true" autored="red_phone" aria-required="true">
                                        </div>
                                    @endif

                                    <div class="input_left_icon" id="red_card">
                                        <span class="fa-stack fa-2x">
                                          <i class="fa fa-circle fa-stack-2x"></i>
                                          <i class="fa fa-credit-card-alt text-white fa-stack-1x" style="font-size: .9rem !important;"></i>
                                        </span>
                                    </div>

                                    <div class="input_right">
                                        <DIV ID="credit-info"></DIV>
                                    </div>

                                    <div class="input_right">
                                        <input type="text" size="20" class="form-control credit-info" autored="red_card" data-stripe="number" placeholder="Card Number">
                                    </div>

                                    <div class="input_left_icon"></div>
                                    <div class="input_right">
                                        <div class="thirdwidth">
                                            <SELECT  style="margin-top: 0 !important;" CLASS="credit-info form-control" data-stripe="exp_month">
                                                <OPTION VALUE="01">01/Jan</OPTION>
                                                <OPTION VALUE="02">02/Feb</OPTION>
                                                <OPTION VALUE="03">03/Mar</OPTION>
                                                <OPTION VALUE="04">04/Apr</OPTION>
                                                <OPTION VALUE="05">05/May</OPTION>
                                                <OPTION VALUE="06">06/Jun</OPTION>
                                                <OPTION VALUE="07">07/Jul</OPTION>
                                                <OPTION VALUE="08">08/Aug</OPTION>
                                                <OPTION VALUE="09">09/Sep</OPTION>
                                                <OPTION VALUE="10">10/Oct</OPTION>
                                                <OPTION VALUE="11">11/Nov</OPTION>
                                                <OPTION VALUE="12">12/Dec</OPTION>
                                            </SELECT>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="thirdwidth">
                                            <SELECT style="margin-top: 0 !important;" CLASS="credit-info form-control" data-stripe="exp_year">
                                                <?php
                                                    $CURRENT_YEAR = date("Y");
                                                    $TOTAL_YEARS = 6;
                                                    for ($year = $CURRENT_YEAR; $year < $CURRENT_YEAR + $TOTAL_YEARS; $year++) {
                                                        echo '<OPTION VALUE="' . right($year, 2) . '">' . $year . '</OPTION>';
                                                    }
                                                ?>
                                            </SELECT>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="thirdwidth">
                                            <input type="text" size="4" data-stripe="cvc" CLASS="credit-info form-control" autored="red_card" PLACEHOLDER="CVC" style="padding: 0 !important;">
                                            <INPUT class="credit-info" TYPE="hidden" name="istest" id="istest">
                                            @if(!islive()) <a class="credit-info pull-right btn" onclick="testcard();" TITLE="Don't remove this, I need it!">Test Card</a> @endif
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="input_left_icon">
                                        <span class="fa-stack fa-2x">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-pencil text-white fa-stack-1x"></i>
                                        </span>
                                    </div>
                                    <div class="input_right">
                                        <textarea placeholder="Order Notes" id="cookingnotes" class="form-control" maxlength="255"></textarea>
                                    </div>

                                    <button class="btn-block list-padding radius0 btn btn-primary text-white payfororder" onclick="payfororder(); return false;"><i class="fa fa-check mr-2"></i> ORDER</button>
                                    <span class="payment-errors error"></span>
                                    <div class="clearfix"></div>

                                </FORM>
                            </div>
                        </div>
                    </div>
                </div>
                <script type="text/javascript" src="https://js.stripe.com/v2/"></script>

                <DIV ID="firefoxandroid" class="fullscreen grey-backdrop dont-show">
                    <DIV CLASS="centered firefox-child bg-white">
                        <i class="fa fa-firefox"></i> Firefox Address editor
                        <DIV ID="gmapffac" class="bg-white"></DIV>
                        <BUTTON ONCLICK="fffa();" CLASS="btn btn-primary radius0 btn-full pull-down-right">OK</BUTTON>
                    </DIV>
                </DIV>
            <?php
            break;
        case "login":
            $Additional_Toppings = first("SELECT * FROM additional_toppings", false);
            function getVal($Additional_Toppings, $size) {
                $it = getiterator($Additional_Toppings, "size", $size, false);
                return $Additional_Toppings[$it]["price"];
            }
            $minimum = number_format(getVal($Additional_Toppings, "Minimum"), 2);
            $delivery = number_format(getVal($Additional_Toppings, "Delivery"), 2);
            $time = getVal($Additional_Toppings, "DeliveryTime");
            $hours = first("SELECT * FROM hours WHERE restaurant_id = 0");
            $useaddress = false;//cant have the address popup showing more than once!!!
            ?>
                <div class="row">
                    <DIV CLASS="col-lg-4 col-md-5 bg-white">
                        <DIV CLASS="btn-sm-padding bg-white" style="padding-bottom: 1rem !important;padding-top: .5rem !important;">
                            <ul class="nav nav-tabs mb-1" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#profile" role="tab" data-toggle="tab" id="logintab" onclick="skiploadingscreen = false;" style="font-weight: bold">LOG IN</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#buzz" role="tab" data-toggle="tab" id="signuptab" onclick="skiploadingscreen = true;" style="font-weight: bold">SIGN UP</a>
                                </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade in active" id="profile">
                                    <div class="input_left_icon"><span class="fa-stack fa-2x"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-envelope text-white fa-stack-1x"></i></span></div>
                                    <div class="input_right">
                                        <INPUT TYPE="text" id="login_email" placeholder="Email" class="form-control session_email_val" onkeydown="enterkey(event, '#login_password');" required>
                                    </div>
                                    <div class="input_left_icon"><span class="fa-stack fa-2x"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-lock text-white fa-stack-1x"></i></span></div>
                                    <div class="input_right">
                                        <INPUT TYPE="password" id="login_password" placeholder="Password" class="form-control" onkeydown="enterkey(event, 'login');" required>
                                    </div>
                                    <div class="clearfix py-2"></div>
                                    <BUTTON CLASS="btn-block btn btn-primary" href="#" onclick="handlelogin('login');">LOG IN</BUTTON>
                                    <div class="clearfix py-2"></div>

                                    <BUTTON CLASS="btn-block btn-sm btn btn-link" href="#" style="color: #dadada !important;" onclick="handlelogin('forgotpassword');">FORGOT PASSWORD</BUTTON>
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="buzz">
                                    <FORM id="addform">
                                        <?php
                                            if (!read("id") && $useaddress) {
                                                echo popups_view("address", array("style" => 1, "required" => true, "icons" => true, "firefox" => false));
                                            }
                                        ?>
                                    </FORM>
                                    <FORM Name="regform" id="regform">
                                        <?= popups_view("edituser", array("phone" => false, "autocomplete" => "new-password", "required" => true, "icons" => true)); ?>
                                    </FORM>
                                    <div class="clearfix py-2"></div>

                                    <button class="btn btn-block btn-primary" onclick="register();">
                                        SIGN UP
                                    </button>
                                </div>
                            </div>
                            <DIV CLASS="clearfix"></DIV>
                        </DIV>

                        <div class="py-3">
                            <center>
                                <img src="<?= webroot("images/delivery.jpg"); ?>" style="width: 50%;"/>
                                <h2 class="text-danger" style="text-align: center;">Only the Best Pizza in <?= cityname; ?></h2>
                                ${{ $minimum }} Minimum<br>
                                ${{ $delivery }} Delivery<br>
                                Credit/Debit Only
                            </center>
                        </div>

                    </DIV>
                    <div class="col-lg-8 col-md-7 bg-white bg-inverse pt-3">
                        <div class="btn-sm-padding col-md-10 offset-md-1 mt-3">

                            <span style=";font-size: 3rem; font-weight: bold;line-height: 3.1rem;"> <?= strtoupper(cityname); ?> PIZZA DELIVERY</span>
                            <br>
                            <br>
                            <p>The art of delivery is in the team, local restaurants at your footstep in <?= $time; ?> minutes.</p>
                            <p class="lead strong">HOURS OF OPERATION</p>
                            <TABLE>
                                <?php
                                    $daysofweek = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                                    for ($day = 0; $day < 7; $day++) {
                                        echo '<TR><TD>' . $daysofweek[$day] . "&nbsp;&nbsp;&nbsp; </TD>";
                                        $open = $hours[$day . "_open"];
                                        $close = $hours[$day . "_close"];
                                        if ($open == "-1" || $close == "-1") {
                                            echo '<TD COLSPAN="2"">Closed';
                                        } else {
                                            echo '<TD>' . GenerateTime($open) . ' to </TD><TD>' . GenerateTime($close);
                                        }
                                        echo '</TD></TR>';
                                    }
                                ?>
                            </TABLE>
                            <br>
                            <i class="lead text-danger strong">"FASTER THAN PICKING UP THE PHONE!"</i><br><br>
                            <a class="btn-link" href="<?= webroot("help"); ?>" role="button">LEARN MORE</a><br><br>
                        </div>
                    </div>

                    <DIV CLASS="col-lg-12 bg-warning text-white strong list-group-item container-fluid shadow ">DELIVERY MENU</DIV>
                </div>


                <SCRIPT>
                    redirectonlogin = true;
                    var minlength = 5;
                    var getcloseststore = false;
                    lockloading = true;
                    blockerror = true;

                    function register() {
                        @if($useaddress)
                            if (isvalidaddress()) {
                            $("#reg_address-error").remove();
                        } else if ($("#reg_address-error").length == 0) {
                            $('<label id="reg_address-error" class="error" for="reg_name">Please check your address</label>').insertAfter("#formatted_address");
                        }
                        @endif
                        redirectonlogin = false;
                        $('#regform').submit();
                        loading(true, "register");
                    }

                    $(".session_email_val").on("keydown", function (e) {
                        return e.which !== 32;
                    });

                    $(function () {
                        $("form[name='regform']").validate({
                            rules: {
                                name: "required",
                                @if($useaddress)
                                    formatted_address: {
                                    validaddress: true,
                                    required: true
                                },
                                @endif
                                email: {
                                    required: true,
                                    email: true,
                                    remote: {
                                        url: '<?= webroot('public/user/info'); ?>',
                                        type: "post",
                                        async: false,
                                        data: {
                                            action: "testemail",
                                            _token: token,
                                            email: function () {
                                                return $('#reg_email').val();
                                            },
                                            user_id: "0"
                                        }
                                    }
                                },
                                password: {
                                    minlength: minlength
                                },
                                /*phone: {
                                 phonenumber: true
                                 }*/
                            },
                            messages: {
                                name: "Please enter your name",
                                password: {
                                    required: "Please provide a password",
                                    minlength: "Your new password must be at least " + minlength + " characters long"
                                },
                                email: {
                                    required: "Please enter an email address",
                                    email: "Please enter a valid email address",
                                    remote: "Please enter a unique email address"
                                }/*,
                                 phone: {
                                 required: "Please enter a cell phone number",
                                 phonenumber: "Please enter a valid cell phone number"
                                 }*/
                            },
                            submitHandler: function (form) {
                                @if($useaddress)
                                    if (!isvalidaddress()) {
                                        return false;
                                    }
                                @endif
                                var formdata = getform("#regform");
                                formdata["action"] = "registration";
                                formdata["_token"] = token;
                                @if($useaddress)
                                    formdata["address"] = getform("#addform");
                                @endif
                                $.post(webroot + "auth/login", formdata, function (result) {
                                    if (result) {
                                        try {
                                            var data = JSON.parse(result);
                                            $("#logintab").trigger("click");
                                            $("#login_email").val(formdata["email"]);
                                            $("#login_password").val(formdata["password"]);
                                            redirectonlogin = true;
                                            handlelogin('login');
                                        } catch (e) {
                                            alert(result, "Registration");
                                        }
                                    }
                                });
                                return false;
                            }
                        });
                    });
                    $(document).ready(function () {
                        $("#profile").removeClass("fade").removeClass("in");
                    });
                </SCRIPT>
            <?php
            break;
        case "edituser":
            $currentURL = webroot("public/user/info");
            if (isset($user_id)) {
                $user = first("SELECT * FROM users WHERE id=" . $user_id);
                echo '<INPUT TYPE="HIDDEN" NAME="id" VALUE="' . $user_id . '">';
                if (!isset($name)) {$name = "user";}
            } else {
                $user = array("name" => "", "phone" => "", "email" => "");
                if (!isset($name)) {$name = "reg";}
            }
            if (!function_exists("printarow")) {
                function printarow($Name, $Prepend, $field) {
                    //if ($field["type"] != "hidden") {echo '';}
                    if($GLOBALS["icons"]){
                        if(!isset($field["icon"])){$field["icon"] = "fa-user";}
                        echo '<div class="input_left_icon"><span class="fa-stack fa-2x"><i class="fa fa-circle fa-stack-2x"></i><i class="fa ' . $field["icon"] . ' text-white fa-stack-1x"></i></span></div><div class="input_right">';
                    }
                    echo '<INPUT TYPE="' . $field["type"] . '" NAME="' . $field["name"] . '" ID="' . $Prepend . '_' . $field["name"] . '"';
                    if (isset($field["class"]))                                     {echo ' CLASS="' . trim($field["class"]) . '" ';}
                    if (isset($field["value"]))                                     {echo ' value="' . $field["value"] . '" ';}
                    if (isset($field["min"]))                                       {echo ' min="' . $field["min"] . '" ';}
                    if (isset($field["maxlen"]))                                    {echo ' min="' . $field["maxlen"] . '" ';}
                    if (isset($field["max"]))                                       {echo ' max="' . $field["max"] . '" ';}
                    if (isset($field["readonly"]))                                  {echo ' readonly';}
                    if (isset($field["autocomplete"]) && $field["autocomplete"])    {echo ' autocomplete="' . $field["autocomplete"] . '"';}
                    if (isset($field["placeholder"]))                               {echo ' placeholder="' . $field["placeholder"] . '" ';}
                    if (isset($field["corner"]))                                    {echo ' STYLE="border-' . $field["corner"] . '-radius: 5px;"';}
                    if (isset($field["required"]) && $field["required"])            {echo ' REQUIRED';}
                    echo '>';
                    if($GLOBALS["icons"]){ echo '</DIV>';}
                }
            }

            if (!isset($password)) {$password = true;}
            if (!isset($email)) {$email = true;}
            if (!isset($autocomplete)) {$autocomplete = "";}
            if (!isset($required)) {$required = false;}
            $GLOBALS["icons"] = isset($icons) && $icons;
            if(isset($class)){$class .= " form-control ";} else {$class = "form-control ";}

            echo '<DIV>';
            if (!isset($profile1) || $profile1) {
                printarow("Name", $name, array("name" => "name", "value" => $user["name"], "type" => "text", "placeholder" => "Name", "class" => $class . "session_name_val", "required" => $required));
            }
            if (!isset($phone) || $phone) {
                if (!isset($phone)) {$phone = false;}
                printarow("Phone", $name, array("name" => "phone", "value" => formatphone($user["phone"]), "type" => "tel", "placeholder" => "Cell Phone", "class" => $class . "session_phone_val", "required" => $phone || $required, "icon" => "fa-15 fa-mobile-phone"));
            }
            if ($email) {
                printarow("Email", $name, array("name" => "email", "value" => $user["email"], "type" => "email", "placeholder" => "Email", "class" => $class . "session_email_val", "required" => $required, "icon" => "fa-envelope"));
            }
            if (isset($user_id) || isset($showpass)) {
                printarow("Old Password", $name, array("name" => "oldpassword", "type" => "password", "class" => $class, "placeholder" => "Old Password", "autocomplete" => $autocomplete, "required" => $required, "icon" => "fa-lock"));
                printarow("New Password", $name, array("name" => "newpassword", "type" => "password", "class" => $class, "placeholder" => "New Password", "autocomplete" => $autocomplete, "required" => $required, "icon" => "fa-lock"));
            } else if ($password) {
                printarow("Password", $name, array("name" => "password", "type" => "password", "class" => $class, "placeholder" => "Password", "autocomplete" => $autocomplete, "required" => $required, "icon" => "fa-lock"));
            }
            if (isset($address) && $address) {
                echo popups_view("address", array("style" => 1));
            }
            echo '</DIV>';
        ?>
            <SCRIPT>
                var minlength = 5;
                redirectonlogout = true;

                function userform_submit(isSelf) {
                    var formdata = getform("#userform");
                    $("#edituser_error").text("");
                    var keys = ["name", "phone"];//"email",
                    for (var keyid = 0; keyid < keys.length; keyid++) {
                        var key = keys[keyid];
                        var val = formdata[key];
                        createCookieValue("session_" + key, val);
                        $(".session_" + key).text(val);
                        $(".session_" + key + "_val").val(val);
                    }
                    $.post("<?= $currentURL; ?>", {
                        action: "saveitem",
                        _token: token,
                        value: formdata
                    }, function (result) {
                        if (result) {
                            if(result == "Data saved"){result = "Changes to your profile have been saved";}
                            alert(result);
                            return true;
                        }
                    });
                    return false;
                }

                $(function () {
                    $("form[name='user']").validate({
                        rules: {
                            name: "required",
                            phone: {
                                phonenumber: true,
                                required: <?= $phone == "required" ? "true" : "false"; ?>
                            },
                            email: {
                                required: true,
                                email: true,
                                remote: {
                                    url: "<?= $currentURL; ?>",
                                    type: "post",
                                    data: {
                                        action: "testemail",
                                        _token: token,
                                        email: function () {
                                            return $('#user_email').val();
                                        },
                                        user_id: userdetails["id"]
                                    }
                                }
                            },
                            oldpassword: {
                                required: function (element) {
                                    return $("form[name='user']").find("input[name=newpassword]").val().length > 0;
                                },
                                minlength: minlength
                            },
                            newpassword: {
                                required: function (element) {
                                    return $("form[name='user']").find("input[name=oldpassword]").val().length > 0;
                                },
                                minlength: minlength
                            }
                        },
                        messages: {
                            name: "Please enter your name",
                            phone: {
                                required: "Please provide an up-to-date phone number",
                                phonenumber: "Please provide a valid phone number"
                            },
                            oldpassword: {
                                required: "Please provide your old password",
                                minlength: "Your old password is at least " + minlength + " characters long"
                            },
                            newpassword: {
                                required: "Please provide a new password",
                                minlength: "Your new password must be at least " + minlength + " characters long"
                            },
                            email: {
                                required: "Please enter an email address",
                                email: "Please enter a valid email address",
                                remote: "Please enter a unique email address"
                            }
                        }
                    });

                    $("#orderinfo").validate({
                        rules: {
                            name: "required",
                            phone: {
                                phonenumber: true,
                                required: <?= $phone == "required" ? "true" : "false"; ?>
                            }
                        },
                        messages: {
                            name: "Please enter your name",
                            phone: "Please enter a valid phone number"
                        }
                    });
                });

                $(document).ready(function () {
                    setTimeout(function () {
                        $("#orderinfo").removeAttr("novalidate");
                    }, 100);
                });
            </SCRIPT>
            <?php
            break;
        case "alljs":
            $CURRENT_YEAR = date("Y");
            $STREET_FORMAT = "[number] [street], [city] [postalcode]";
            //["id", "value", "user_id", "number", "unit", "buzzcode", "street", "postalcode", "city", "province", "latitude", "longitude", "phone"];
            $nprog = "#F0AD4E";
            ?>
                    <STYLE>
                        /* STOP MOVING THIS TO THE CSS, IT WON'T WORK! */
                        #oldloadingmodal {
                            display: none;
                            position: fixed;
                            z-index: 1000;
                            top: 0;
                            left: 0;
                            height: 100%;
                            width: 100%;
                            background: rgba(0, 0, 0, .6) url('<?= webroot("public/images/slice.gif"); ?>') 50% 50% no-repeat;
                        }

                        #loading {z-index: 9999;}
                        #nprogress{pointer-events:none;}
                        #nprogress .bar{background:<?= $nprog; ?>;position:fixed;z-index:10000;top:0;left:0;width:100%;height:10px;}
                        #nprogress .peg{display:block;position:absolute;right:0px;width:100px;height:100%;box-shadow:0 0 10px <?= $nprog; ?>,0 0 5px <?= $nprog; ?>;opacity:1.0;-webkit-transform:rotate(3deg) translate(0px,-4px);-ms-transform:rotate(3deg) translate(0px,-4px);transform:rotate(3deg) translate(0px,-4px);}
                        #nprogress .spinner{display:block;position:fixed;z-index:10000;top:15px;right:15px;}
                        #nprogress .spinner-icon{width:18px;height:18px;box-sizing:border-box;border:solid 2px transparent;border-top-color:<?= $nprog; ?>;border-left-color:<?= $nprog; ?>;border-radius:50%;-webkit-animation:nprogress-spinner 400ms linear infinite;animation:nprogress-spinner 400ms linear infinite;}
                        .nprogress-custom-parent{overflow:hidden;position:relative;}
                        .nprogress-custom-parent #nprogress .spinner,.nprogress-custom-parent #nprogress .bar{position:absolute;}
                        @-webkit-keyframes nprogress-spinner{0%{-webkit-transform:rotate(0deg);}100%{-webkit-transform:rotate(360deg);}}
                        @keyframes nprogress-spinner{0%{transform:rotate(0deg);}100%{transform:rotate(360deg);}}
                    </STYLE>

                    <script type="text/javascript">
                        var timerStart = Date.now();
                        var currentURL = "<?= Request::url(); ?>";
                        var token = "<?= csrf_token(); ?>";
                        var webroot = "<?= webroot("public/"); ?>";
                        var redirectonlogout = false;
                        var redirectonlogin = false;
                        var addresskeys = ["id", "value", "user_id", "number", "unit", "buzzcode", "street", "postalcode", "city", "province", "latitude", "longitude", "phone"];
                        var userdetails = false;
                        var currentRoute = "<?= Route::getCurrentRoute()->getPath(); ?>";
                        var settings = <?= json_encode($GLOBALS["settings"]); ?>;
                        var is_firefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
                        var is_android = navigator.userAgent.toLowerCase().indexOf('android') > -1;
                        var is_firefox_for_android = is_firefox && is_android;
                        var currentitemID = -1;
                        var MAX_DISTANCE = <?= $GLOBALS["settings"]["maxdistance_" . iif(islive(), "live", "local") ] ?>;//km
                        var debugmode = false;//'<?= !islive(); ?>' == '1';

                        String.prototype.isEqual = function (str) {
                            if (isUndefined(str)) {
                                return false;
                            }
                            if (isNumeric(str) || isNumeric(this)) {
                                return this == str;
                            }
                            return this.toUpperCase().trim() == str.toUpperCase().trim();
                        };

                        function isUndefined(variable) {
                            return typeof variable === 'undefined';
                        }

                        function isArray(variable) {
                            return Array.isArray(variable);
                        }

                        //returns true if $variable appears to be a valid number
                        function isNumeric(variable) {
                            return !isNaN(Number(variable));
                        }

                        //returns true if $variable appears to be a valid object
                        //typename (optional): the $variable would also need to be of the same object type (case-sensitive)
                        function isObject(variable, typename) {
                            if (typeof variable == "object") {
                                if (isUndefined(typename)) {
                                    return true;
                                }
                                return variable.getName().toLowerCase() == typename.toLowerCase();
                            }
                            return false;
                        }

                        String.prototype.contains = function (str) {
                            return this.toLowerCase().indexOf(str.toLowerCase()) > -1;
                        };

                        //returns true if the string starts with str
                        String.prototype.startswith = function (str) {
                            return this.substring(0, str.length).isEqual(str);
                        };
                        String.prototype.endswith = function (str) {
                            return this.right(str.length).isEqual(str);
                        };
                        //returns the left $n characters of a string

                        String.prototype.left = function (n) {
                            return this.substring(0, n);
                        };

                        String.prototype.mid = function (start, length) {
                            return this.substring(start, start + length);
                        };

                        Number.prototype.pad = function (size, rightside) {
                            var s = String(this);
                            if (isUndefined(rightside)) {
                                rightside = false;
                            }
                            while (s.length < (size || 2)) {
                                if (rightside) {
                                    s = s + "0";
                                } else {
                                    s = "0" + s;
                                }
                            }
                            return s;
                        };

                        //returns the right $n characters of a string
                        String.prototype.right = function (n) {
                            return this.substring(this.length - n);
                        };

                        function getKeyByValue(object, value) {
                            return Object.keys(object).find(key => object[key] === value);
                        }

                        //Period: year, month, day, hour, minute, second, millisecond
                        Date.prototype.add = function (Period, Increment){
                            switch(Period){
                                case "year":        this.setYear(this.getYear() + Increment); break;
                                case "month":       this.setMonth(this.getMonth() + Increment); break;
                                case "day":         this.setDate(this.getDate() + Increment); break;
                                case "hour":        this.setHours(this.getHours() + Increment); break;
                                case "minute":      this.setMinutes(this.getMinutes() + Increment); break;
                                case "second":      this.setSeconds(this.getSeconds() + Increment); break;
                                case "millisecond": this.setMilliseconds(this.getMilliseconds() + Increment); break;
                            }
                            return this;
                        };

                        function right(text, length) {
                            return String(text).right(length);
                        }

                        //returns true if $variable appears to be a valid function
                        function isFunction(variable) {
                            var getType = {};
                            return variable && getType.toString.call(variable) === '[object Function]';
                        }

                        //replaces all instances of $search within a string with $replacement
                        String.prototype.replaceAll = function (search, replacement) {
                            var target = this;
                            if (isArray(search)) {
                                for (var i = 0; i < search.length; i++) {
                                    if (isArray(replacement)) {
                                        target = target.replaceAll(search[i], replacement[i]);
                                    } else {
                                        target = target.replaceAll(search[i], replacement);
                                    }
                                }
                                return target;
                            }
                            return target.replace(new RegExp(search, 'g'), replacement);
                        };

                        String.prototype.between = function (leftside, rightside) {
                            var target = this;
                            var start = target.indexOf(leftside);
                            if (start > -1) {
                                var finish = target.indexOf(rightside, start);
                                if (finish > -1) {
                                    return target.substring(start + leftside.length, finish);
                                }
                            }
                        };

                        function storageAvailable(type) {
                            try {//types: sessionStorage, localStorage
                                var storage = window[type], x = '__storage_test__';
                                storage.setItem(x, x);
                                storage.removeItem(x);
                                return true;
                            } catch(e) {
                                return false;
                            }
                        }
                        var uselocalstorage = storageAvailable('localStorage');
                        log("Local storage is available: " + iif(uselocalstorage, "Yes", "No (use cookie instead)"));
                        function hasItem(c_name){
                            if(uselocalstorage){
                                return window['localStorage'].getItem(c_name) !== null;
                            }
                            return false;
                        }

                        function setCookie(c_name, value, exdays) {
                            if(uselocalstorage){
                                window['localStorage'].setItem(c_name, value);
                            } else {
                                var exdate = new Date();
                                exdate.setDate(exdate.getDate() + exdays);
                                var c_value = value + ((exdays == null) ? "" : "; expires=" + exdate.toUTCString());
                                c_value = c_name + "=" + c_value + ";path=/;";
                                document.cookie = c_value;
                            }
                        }

                        //gets a cookie value
                        function getCookie(c_name) {
                            if(hasItem(c_name)){
                                return window['localStorage'].getItem(c_name);
                            }
                            var i, x, y, ARRcookies = document.cookie.split(";");
                            for (i = 0; i < ARRcookies.length; i++) {
                                x = ARRcookies[i].substr(0, ARRcookies[i].indexOf("="));
                                y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
                                x = x.replace(/^\s+|\s+$/g, "");
                                if (x == c_name) {
                                    return unescape(y);
                                }
                            }
                        }

                        //deletes a cookie value
                        function removeCookie(cname, forcecookie) {
                            if(isUndefined(forcecookie)){forcecookie = false;}
                            if (isUndefined(cname)) {//erase all cookies
                                var cookies = document.cookie.split(";");
                                for (var i = 0; i < cookies.length; i++) {
                                    var cookie = cookies[i];
                                    var eqPos = cookie.indexOf("=");
                                    var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
                                    removeCookie(name, true);
                                }
                                if(uselocalstorage) {
                                    cookies = Object.keys(window['localStorage']);
                                    for (var i = 0; i < cookies.length; i++) {
                                        removeCookie(cookies[i]);
                                    }
                                }
                            } else if(hasItem(cname) && !forcecookie){
                                window['localStorage'].removeItem(cname);
                            } else {
                                document.cookie = cname + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/;";
                            }
                        }

                        //creates a cookie value that expires in 1 year
                        function createCookieValue(cname, cvalue) {
                            //log("Creating cookie value: '" + cname + "' with: " + cvalue);
                            setCookie(cname, cvalue, 365);
                        }

                        function log(text) {
                            console.log(text);
                        }

                        function getform(ID) {
                            var data = $(ID).serializeArray();
                            var ret = {};
                            for (var i = 0; i < data.length; i++) {
                                ret[data[i].name] = data[i].value.trim();
                            }
                            return ret;
                        }

                        function inputbox2(Text, Title, Default, retfnc) {
                            Text += '<INPUT TYPE="TEXT" ID="modal_inputbox" CLASS="form-control margin-top-15px" VALUE="' + Default + '">';
                            confirm2(Text, Title, function () {
                                retfnc($("#modal_inputbox").val());
                            });
                        }

                        function confirm2() {
                            var Title = "Confirm";
                            var action = function () {};
                            $('#alert-confirm').unbind('click');
                            if (arguments.length > 1) {
                                for (var index = 0; index < arguments.length; index++) {
                                    if (isFunction(arguments[index])) {
                                        action = arguments[index];
                                    } else {
                                        Title = arguments[index];
                                    }
                                }
                            }
                            alert(arguments[0], Title);
                            $("#alert-cancel").show();
                            $("#alert-confirm").click(action);
                        }

                        function removeindex(arr, index, count, delimiter) {
                            if (!isArray(arr)) {
                                if (isUndefined(delimiter)) {delimiter = " ";}
                                arr = removeindex(arr.split(delimiter), index, count, delimiter).join(delimiter);
                            } else {
                                if (isNaN(index)) {
                                    index = hasword(arr, index);
                                }
                                if (index > -1 && index < arr.length) {
                                    if (isUndefined(count)) {
                                        count = 1;
                                    }
                                    arr.splice(index, count);
                                }
                            }
                            return arr;
                        }

                        function visible(selector, status) {
                            if (isUndefined(status)) {status = false;}
                            if (status) {
                                $(selector).show();
                            } else {
                                $(selector).hide();
                            }
                        }

                        $.fn.hasAttr = function (name) {
                            return this.attr(name) !== undefined;
                        };

                        $.validator.addMethod('email', function(value, element){ //override email with django email validator regex - fringe cases: "user@admin.state.in..us" or "name@website.a"
                            return this.optional(element) || /(^[-!#$%&'*+/=?^_`{}|~0-9A-Z]+(\.[-!#$%&'*+/=?^_`{}|~0-9A-Z]+)*|^"([\001-\010\013\014\016-\037!#-\[\]-\177]|\\[\001-\011\013\014\016-\177])*")@((?:[A-Z0-9](?:[A-Z0-9-]{0,61}[A-Z0-9])?\.)+(?:[A-Z]{2,6}\.?|[A-Z0-9-]{2,}\.?)$)|\[(25[0-5]|2[0-4]\d|[0-1]?\d?\d)(\.(25[0-5]|2[0-4]\d|[0-1]?\d?\d)){3}\]$/i.test(value);
                        }, 'Please enter a valid email address.');

                        $.validator.addMethod('phonenumber', function (Data, element) {
                            Data = Data.replace(/\D/g, "");
                            if (Data.substr(0, 1) == "0") {
                                return false;
                            }
                            return Data.length == 10;
                        }, "Invalid phone number");

                        $.validator.addMethod('validaddress', function (Data, element) {
                            log("TESTING ADDRESS");
                        }, "Please check your address");

                        function isvalidaddress() {
                            var fields = ["formatted_address", "add_latitude", "add_longitude"];//, "add_postalcode"
                            //if ($("#add_city").val().toLowerCase() != "london") {return false;}
                            for (i = 0; i < fields.length; i++) {
                                var value = $("#" + fields[i]).val();
                                log(fields[i] + ": " + value.length + " chars = " + value);
                                if (value.length == 0 || (value.indexOf("[") > -1 && value.indexOf("]") > -1)) {
                                    return false;
                                }
                            }
                            return true;
                        }

                        function findwhere(data, key, value) {
                            for (var i = 0; i < data.length; i++) {
                                if (data[i][key].isEqual(value)) {
                                    return i;
                                }
                            }
                            return -1
                        }

                        $(document).on('touchend', function () {
                            $(".select2-search, .select2-focusser").remove();
                        });

                        //generates the order menu item modal
                        var currentitem;

                        function loadmodal(element, notparent) {
                            if (isUndefined(notparent)) {
                                element = $(element);
                            }
                            var items = ["name", "price", "id", "size", "cat"];
                            for (var i = 0; i < items.length; i++) {
                                $("#modal-item" + items[i]).text($(element).attr("item" + items[i]));
                            }
                            var itemname = $(element).attr("itemname");
                            var itemcost = $(element).attr("itemprice");
                            var size = $(element).attr("itemsize");
                            var toppingcost = 0.00;
                            if (size) {
                                toppingcost = Number(freetoppings[size]).toFixed(2);
                                $(".toppings").attr("data-placeholder", "Add Toppings: $" + toppingcost);
                                $(".toppings_price").text(toppingcost);
                            }
                            $("#modal-toppingcost").text(toppingcost);
                            if (toppingcost > 0) {
                                $("#toppingcost").show();
                            } else {
                                $("#toppingcost").hide();
                            }
                            currentitem = {itemname: itemname, itemcost: itemcost, size: size, toppingcost: toppingcost};

                            for (var tableid = 0; tableid < tables.length; tableid++) {
                                var table = tables[tableid];
                                var Quantity = Number($(element).attr(table));
                                if (Quantity > 0) {
                                    list_addons_quantity(Quantity, table, false, itemname, itemcost, toppingcost);
                                    tableid = tables.length;
                                }
                            }
                            currentitemID = -1;
                            var title = "<div class='pull-left'><i class='fa fa-check'></i></div><div class='pull-right'>$<SPAN ID='modal-itemtotalprice'></SPAN></div>";
                            if (!isUndefined(notparent)) {
                                $("#menumodal").modal("show");
                                refreshremovebutton();
                            }
                            // $("#removelist").text("");
                            $("#additemtoorder").html(title);
                            $("#modal-itemtotalprice").text(itemcost);
                        }

                        function refreshremovebutton() {
                            if (currentaddonlist[currentitemindex].length == 0) {
                                //   $(".removeitemarrow").fadeTo("fast", 0.50);
                                //   $("#removeitemfromorder").attr("title", "").attr("onclick", "").attr("style", "cursor: not-allowed");
                            } else {
                                var index = currentaddonlist[currentitemindex].length - 1;
                                var lastitem = currentaddonlist[currentitemindex][index];
                                $(".removeitemarrow").fadeTo("fast", 1.00);
                                $("#removeitemfromorder").attr("title", "Remove: " + lastitem.name + " from " + $("#item_" + currentitemindex).text()).attr("onclick", "removelistitem(" + currentitemindex + ", " + index + ");").attr("style", "");
                            }
                        }

                        //get the data from the modal and add it to the order
                        function additemtoorder(element, Index) {
                            var itemid = 0, itemname = "", itemprice = 0.00, itemaddons = new Array, itemsize = "", toppingcost = 0.00, toppingscount = 0, itemcat = "", oldcost = "";
                            if (!isUndefined(Index)) {currentitemID = Index;}
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
                                element = $(element);
                                itemid = $(element).attr("itemid");
                                itemname = $(element).attr("itemname");
                                itemprice = $(element).attr("itemprice");
                                itemcat = $(element).attr("itemcat");
                                for (var index = 0; index < theorder.length; index++) {
                                    if (theorder[index].itemid == itemid) {
                                        oldcost = $('#cost_' + index).text();
                                        break;
                                    }
                                }
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
                                itemaddons: itemaddons,
                                isnew: true
                            };
                            if (currentitemID == -1) {
                                theorder.push(data);
                                var ret = theorder.length - 1;
                            } else {
                                theorder[currentitemID] = data;
                                var ret = currentitemID;
                            }
                            generatereceipt(true);
                            $("#receipt_item_" + ret).hide().fadeIn("fast");
                            if (oldcost) {
                                refreshcost(index, oldcost);
                            }
                            return ret;
                        }

                        function unclone() {
                            for (var itemid = 0; itemid < theorder.length; itemid++) {
                                delete theorder[itemid]["clone"];
                            }
                            generatereceipt(true);
                        }

                        function cloneitem(me, itemid) {
                            var clone = JSON.parse(JSON.stringify(theorder[itemid]));
                            clone.clone = true;
                            theorder.push(clone);
                            var oldcost = $('#cost_' + itemid).text();
                            generatereceipt(true);
                            refreshcost(itemid, oldcost);
                        }

                        function refreshcost(itemid, oldcost) {
                            var newcost = $('#cost_' + itemid).text();
                            $('#cost_' + itemid).show().text(oldcost).fadeOut(
                                    function () {
                                        $('#cost_' + itemid).text(newcost).fadeIn();
                                    }
                            );
                        }

                        //convert the order to an HTML receipt
                        function generatereceipt(forcefade) {
                            if ($("#myorder").length == 0) {
                                return false;
                            }
                            var HTML = '<div class="clearfix"></div>', tempHTML = "", subtotal = 0, fadein = false, oldvalues = "";
                            if (isUndefined(forcefade)) {
                                forcefade = false;
                            }
                            if ($("#newvalues").length > 0) {
                                oldvalues = $("#newvalues").html();
                            }
                            $("#oldvalues").stop().html("").hide().remove();
                            $("#newvalues").stop().html("").hide().remove();
                            var itemnames = {toppings: "pizza", wings_sauce: "lb"};
                            var nonames = {toppings: "toppings", wings_sauce: "sauce"};
                            for (var itemid = 0; itemid < theorder.length; itemid++) {
                                var item = theorder[itemid];
                                var hasaddons = item.hasOwnProperty("itemaddons") && item["itemaddons"].length > 0;
                                if (!item.hasOwnProperty("clone")) {
                                    var quantity = 1;
                                    if (!hasaddons) {
                                        for (var seconditemid = itemid + 1; seconditemid < theorder.length; seconditemid++) {
                                            var clone = theorder[seconditemid];
                                            if (item.itemid == clone.itemid) {
                                                theorder[seconditemid].clone = true;
                                                quantity += 1;
                                            }
                                        }
                                        theorder[itemid].quantity = quantity;
                                    }
                                    var totalcost = ((Number(item["itemprice"]) + (Number(item["toppingcost"]) * Number(item["toppingcount"]))) * quantity).toFixed(2);
                                    var category = "pizza";
                                    var sprite = "pizza";
                                    if (item.hasOwnProperty("category")) {
                                        category = item["category"].toLowerCase().replaceAll(" ", "_");
                                        sprite = category.trim();
                                        if (category.endswith("pizza")) {
                                            category = "pizza";
                                            if (item["itemname"].startswith("2")) {
                                                sprite = "241_pizza";
                                            }
                                        }
                                    }
                                    if (item.hasOwnProperty("isnew")) {
                                        if (item["isnew"]) {
                                            item["isnew"] = false;
                                            fadein = "#receipt_item_" + itemid;
                                        }
                                    }
                                    subtotal += Number(totalcost);

                                    if (sprite == "sides") {
                                        sprite = toclassname(item["itemname"].trim()).replaceAll("_", "-");
                                        if (sprite.endswith("lasagna")) {
                                            sprite = "lasagna";
                                        } else if (sprite.endswith("chicken-nuggets")) {
                                            sprite = "chicken-nuggets";
                                        } else if (sprite.endswith("salad")) {
                                            sprite = "salad";
                                        }
                                    } else if (sprite == "drinks") {
                                        sprite += " sprite-" + toclassname(item["itemname"].trim()).replaceAll("_", "-").replace(/\./g, '');
                                    }

                                    tempHTML = '<DIV ID="receipt_item_' + itemid + '" style="padding-top:0 !important;padding-bottom:0 !important;" class="receipt_item list-group-item">';

                                    if(quantity > 1) {
                                        tempHTML += '<SPAN CLASS="item_qty">' + quantity + ' x&nbsp;</SPAN> ';
                                    }

                                    tempHTML += ' <span class="receipt-itemname">' + item["itemname"] + '</SPAN> <span class="ml-auto align-middle">';
                                    tempHTML += '<span id="cost_' + itemid + '">$' + totalcost +'</span>';
                                    tempHTML += '<button class="bg-transparent text-normal fa fa-minus btn-sm" onclick="removeorderitem(' + itemid + ', ' + quantity + ');"></button>';
                                    if (hasaddons) {
                                        tempHTML += '<button class="bg-transparent text-normal fa fa-pencil btn-sm" onclick="edititem(this, ' + itemid + ');"></button>';
                                    } else {
                                        tempHTML += '<button class="bg-transparent text-normal fa fa-plus btn-sm" onclick="cloneitem(this, ' + itemid + ');"></button>';
                                    }
                                    tempHTML += '</SPAN></div>';

                                    var itemname = "";
                                    if (hasaddons) {
                                        tempHTML += '<DIV class="btn-sm-padding text-muted item_addons list-group-item">';
                                        var tablename = item["itemaddons"][0]["tablename"];
                                        if (item["itemaddons"].length > 1) {
                                            itemname = itemnames[tablename];
                                        }
                                        for (var currentitem = 0; currentitem < item["itemaddons"].length; currentitem++) {
                                            var addons = item["itemaddons"][currentitem];
                                            if (itemname) {
                                                tempHTML += '<DIV CLASS="col-md-12 item_title">' + ordinals[currentitem] + " " + itemname + ': ';
                                            } else {
                                                tempHTML += '<DIV>';
                                            }
                                            if(addons.hasOwnProperty("addons")) {
                                                if (addons["addons"].length == 0) {
                                                    tempHTML += 'no ' + nonames[tablename] + '';
                                                } else {
                                                    for (var addonid = 0; addonid < addons["addons"].length; addonid++) {
                                                        if (addonid > 0) {
                                                            tempHTML += ", ";
                                                        }
                                                        var addonname = addons["addons"][addonid]["text"];
                                                        var isfree = isaddon_free(tablename, addonname);
                                                        if (isfree) {
                                                            tempHTML += '<I TITLE="Free addon">' + addonname + '</I>';
                                                        } else {
                                                            tempHTML += addonname;
                                                        }
                                                    }
                                                }
                                            }
                                            tempHTML += '</DIV><DIV CLASS="clearfix"></DIV>';
                                        }
                                        tempHTML += '</DIV>';
                                    }
                                    HTML += tempHTML;
                                }
                            }
                            var taxes = (subtotal + deliveryfee) * 0.13;//ontario only
                            totalcost = subtotal + deliveryfee + taxes;

                            visible("#checkout", userdetails);
                            createCookieValue("theorder", JSON.stringify(theorder));

                            if (theorder.length == 0) {
                                HTML = '<div CLASS="list-padding py-3 btn-block radius0"><div class="d-flex justify-content-center"><i class="fa fa-shopping-basket empty-shopping-cart fa-2x pb-1 text-muted"></i></div><div class="d-flex justify-content-center text-muted">Empty</div></div>';
                                $("#checkout").hide();
                                $("#checkoutbutton").hide();
                                $("#confirmclearorder").hide();
                                removeCookie("theorder");
                                collapsecheckout();
                                $("#checkout-btn").hide();
                                $("#checkout-total").text('$0.00');
                            } else {
                                tempHTML = "";
                                tempHTML += '<DIV id="newvalues"';
                                if (fadein || forcefade) {
                                    tempHTML += 'class="dont-show"';
                                }
                                tempHTML += '><div class="pull-right text-normal py-1"><TABLE><TR><TD>Sub-total $</TD><TD>' + subtotal.toFixed(2) + '</TD></TR>';
                                var discount = getdiscount(subtotal);
                                if(discount){
                                    if(discount.left(1) == "$"){
                                        discount = Number(discount.right(discount.length-1));
                                    } else if (discount.right(1) == "%"){
                                        tempHTML += '<TR><TD>Discount&nbsp;&nbsp;</TD><TD>' + discount + '</TD></TR>';
                                        discount = Number(discount.left(discount.length-1)) * 0.01 * subtotal;
                                    }
                                    tempHTML += '<TR><TD>Discount $</TD><TD>' + discount.toFixed(2) + '</TD></TR>';
                                    subtotal -= discount;
                                }
                                tempHTML += '<TR><TD>Delivery $</TD><TD>' + deliveryfee.toFixed(2) + '</TD></TR>';
                                tempHTML += '<TR><TD>Tax $</TD><TD>' + taxes.toFixed(2) + '</TD></TR>';
                                tempHTML += '<TR><TD class="strong">Total $</TD><TD class="strong">' + totalcost.toFixed(2) + '</TD></TR>';
                                tempHTML += '</TABLE><div class="clearfix py-2"></div></DIV></DIV>';
                                $("#confirmclearorder").show();
                                $("#checkout-total").text('$' + totalcost.toFixed(2));
                            }
                            if (fadein || forcefade) {
                                tempHTML += '<DIV id="oldvalues">' + oldvalues + '</div>';
                            }
                            if (theorder.length > 0) {
                                if (totalcost >= minimumfee) {
                                    $("#checkout-btn").show();
                                } else {
                                    $("#checkout-btn").hide();
                                    tempHTML += '<button CLASS="list-padding bg-secondary btn-block text-normal no-icon">Minimum $' + minimumfee + ' to Order</button>';
                                }
                            }
                            $("#myorder").html(HTML + tempHTML);
                            if (fadein || forcefade) {
                                if (fadein) {
                                    $(fadein).hide().fadeIn();
                                }
                                $("#oldvalues").show().fadeOut("slow", function () {$("#newvalues").fadeIn();});
                            }
                        }

                        function getdiscount(subtotal){
                            subtotal = Math.floor(subtotal/10) * 10;
                            for(var dollars = subtotal; dollars > 0; dollars-=10){
                                var keyname = "over$" + dollars;
                                if(settings.hasOwnProperty(keyname)){
                                    return settings[keyname];
                                }
                            }
                            return false;
                        }

                        //hides the checkout form
                        function collapsecheckout() {
                            if ($("#collapseCheckout").attr("aria-expanded") == "true") {
                                $("#checkout").trigger("click");
                            }
                        }

                        function confirmclearorder() {
                            if (theorder.length > 0) {
                                confirm2("", makestring("{clear_order}"), function () {
                                    clearorder();
                                });
                            }
                        }

                        function clearorder() {
                            theorder = new Array;
                            removeorderitemdisabled = true;
                            $(".receipt_item").fadeOut("fast", function () {
                                removeorderitemdisabled = false;
                                generatereceipt();
                            });
                        }

                        function edititem(element, Index) {
                            var theitem = theorder[Index];
                            if (!$(element).hasAttr("itemname")) {
                                $(element).attr("itemname", theitem.itemname);
                                $(element).attr("itemprice", theitem.itemprice);
                                $(element).attr("itemid", theitem.itemid);
                                $(element).attr("itemsize", theitem.itemsize);
                                $(element).attr("itemcat", theitem.category);
                                for (var i = 0; i < tables.length; i++) {
                                    $(element).attr(tables[i], 0);
                                }
                                $(element).attr(theitem.itemaddons[0].tablename, theitem.itemaddons.length);
                            }
                            loadmodal(element, true);
                            currentitemID = Index;
                            for (var i = 0; i < theitem.itemaddons.length; i++) {
                                var tablename = theitem.itemaddons[i].tablename;
                                for (var i2 = 0; i2 < theitem.itemaddons[i].addons.length; i2++) {
                                    var theaddon = theitem.itemaddons[i].addons[i2].text;
                                    currentaddonlist[i].push({name: theaddon, qual: 1, side: 1, type: tablename, group: getaddon_group(tablename, theaddon)});
                                }
                            }
                            generateaddons();
                        }

                        //gets the addons from each dropdown
                        function getaddons() {
                            var itemaddons = new Array;
                            for (var tableid = 0; tableid < tables.length; tableid++) {
                                var table = tables[tableid];
                                if (table == currentaddontype) {
                                    for (var itemid = 0; itemid < currentaddonlist.length; itemid++) {
                                        var addonlist = currentaddonlist[itemid];
                                        var addons = new Array;
                                        var toppings = 0;
                                        for (var addonid = 0; addonid < addonlist.length; addonid++) {
                                            var name = addonlist[addonid].name;
                                            var isfree = isaddon_free(table, name);
                                            addons.push({
                                                text: name,
                                                isfree: isfree
                                            });
                                            if (!isfree) {
                                                toppings++;
                                            }
                                        }
                                        itemaddons.push({tablename: table, addons: addons, count: toppings});
                                    }
                                }
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

                        function getaddon_group(Table, Addon) {
                            if (groups.hasOwnProperty(Table)) {
                                if (groups[Table].hasOwnProperty(Addon)) {
                                    return Number(groups[Table][Addon]);
                                }
                            }
                            return 0;
                        }

                        //checks if an addon is free
                        function isaddon_free(Table, Addon) {
                            switch (Addon.toLowerCase()) {
                                case "lightly done": case "well done": return true; break;
                                default: return freetoppings[Table].indexOf(Addon) > -1;
                            }
                        }

                        //checks if an addon is on the whole pizza (for when we implement halves)
                        function isaddon_onall(Table, Addon) {
                            return freetoppings["isall"][Table].indexOf(Addon) > -1;
                        }

                        //remove an item from the order
                        var removeorderitemdisabled = false;
                        function removeorderitem(index, quantity) {
                            if (removeorderitemdisabled) {return;}
                            if (quantity == 1) {
                                removeindex(theorder, index);
                                removeorderitemdisabled = true;
                                $("#receipt_item_" + index).fadeOut("fast", function () {
                                    removeorderitemdisabled = false;
                                    generatereceipt();
                                });
                            } else {
                                var original = theorder[index];
                                for (var i = index + 1; i < theorder.length; i++) {
                                    if (original.itemid == theorder[i].itemid) {
                                        removeindex(theorder, i);
                                        i = theorder.length;
                                    }
                                }
                                var oldcost = $('#cost_' + index).text();
                                unclone();
                                refreshcost(index, oldcost);
                            }
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

                        function validaddress() {
                            var savedaddress = $("#saveaddresses").val();
                            if (savedaddress == 0) {return false;}
                            if (savedaddress == "addaddress") {return isvalidaddress();}
                            return true;
                        }

                        function isvalidcreditcard(CardNumber, Month, Year, CVV) {
                            var nCheck = 0, value = $("#saved-credit-info").val();
                            if(value.length > 0){
                                value = $("#card_" + value).html().right(7);
                                CVV = 100;
                                Month = value.left(2);
                                Year = value.right(2);
                            } else {
                                if (isUndefined(CardNumber)) {CardNumber = $("[data-stripe=number]").val();}
                                if (isUndefined(Month)) {Month = $("[data-stripe=exp_month]").val();}
                                if (isUndefined(Year)) {Year = $("[data-stripe=exp_year]").val();}
                                if (isUndefined(CVV)) {CVV = $("[data-stripe=cvc]").val();}
                                CardNumber = CardNumber.replace(/\D/g, '');
                                var nDigit = 0, bEven = false;
                                for (var n = CardNumber.length - 1; n >= 0; n--) {
                                    var cDigit = CardNumber.charAt(n);
                                    var nDigit = parseInt(cDigit, 10);
                                    if (bEven) {
                                        if ((nDigit *= 2) > 9) {
                                            nDigit -= 9;
                                        }
                                    }
                                    nCheck += nDigit;
                                    bEven = !bEven;
                                }
                            }
                            if ((nCheck % 10) == 0) {
                                var ExpiryDate = Number(Year) * 100 + Number(Month);
                                var d = new Date();
                                var CurrentDate = (d.getYear() % 100) * 100 + d.getMonth();
                                if (ExpiryDate > CurrentDate) {
                                    return Number(CVV) > 99;
                                } else {
                                    log("Failed expiry date check: " + ExpiryDate + " <= " + CurrentDate);
                                }
                            } else {
                                log("Failed card number check: " + CardNumber);
                            }
                        }

                        function canplaceanorder() {
                            var valid_creditcard = true;
                            if (!$("#saved-credit-info").val() && !isvalidcreditcard()) {valid_creditcard = false;}
                            var visible_errors = $(".error:visible").text().length == 0;
                            var selected_rest = $("#restaurant").val() > 0;
                            var phone_number = $("#reg_phone").val().length > 0;
                            var valid_address = validaddress();
                            var reasons = new Array();
                            if (!valid_creditcard) {reasons.push("valid credit card");}
                            if (!visible_errors) {reasons.push("errors in form");}
                            if (!selected_rest) {reasons.push("no selected restaurant");}
                            if (!phone_number) {reasons.push("phone number missing");}
                            if (!valid_address) {reasons.push("valid address");}
                            if (reasons.length > 0) {
                                log("canplaceanorder: " + reasons.join(", "));
                                return false;
                            }
                            return true;
                        }

                        //send an order to the server
                        var countdown;//receipt timer
                        function placeorder(StripeResponse) {
                            if (!canplaceanorder()) {
                                return cantplaceorder();
                            }
                            if (isUndefined(StripeResponse)) {
                                StripeResponse = "";
                            }
                            if (isObject(userdetails)) {
                                var addressinfo = getform("#orderinfo");//i don't know why the below 2 won't get included. this forces them to be
                                addressinfo["cookingnotes"] = $("#cookingnotes").val();
                                addressinfo["deliverytime"] = $("#deliverytime").val();
                                addressinfo["restaurant_id"] = $("#restaurant").val();
                                $.post(webroot + "placeorder", {
                                    _token: token,
                                    info: addressinfo,
                                    stripe: StripeResponse,
                                    order: theorder,
                                    name: $("#reg_name").val(),
                                    phone: $("#reg_phone").val()
                                }, function (result) {
                                    paydisabled=false;
                                    $("#checkoutmodal").modal("hide");
                                    if (result.contains("ordersuccess")) {
                                        handleresult(result, "ORDER RECEIPT");
                                        //countdown = window.setTimeout(function () {incrementtime()}, 1000);
                                        if ($("#saveaddresses").val() == "addaddress") {
                                            var Address = {
                                                id: $(".ordersuccess").attr("addressid"),
                                                buzzcode: "",
                                                city: $("#add_city").val(),
                                                latitude: $("#add_latitude").val(),
                                                longitude: $("#add_longitude").val(),
                                                number: $("#add_number").val(),
                                                phone: $("#reg_phone").val(),
                                                postalcode: $("#add_postalcode").val(),
                                                province: $("#add_province").val(),
                                                street: $("#add_street").val(),
                                                unit: $("#add_unit").val(),
                                                user_id: $("#add_user_id").val()
                                            };
                                            userdetails.Addresses.push(Address);
                                            $("#addaddress").remove();
                                            $("#saveaddresses").append(AddressToOption(Address) + '<OPTION VALUE="addaddress" ID="addaddress">never shows ADD ADDRESS</OPTION>');
                                        }
                                        userdetails["Orders"].unshift({
                                            id: $("#receipt_id").text(),
                                            placed_at: $("#receipt_placed_at").text(),
                                        });
                                        clearorder();
                                    } else {
                                        alert("Error:" + result, makestring("{not_placed}"));
                                    }
                                });
                            } else {
                                $("#loginmodal").modal("show");
                            }
                        }

                        if (!Date.now) {
                            Date.now = function () {
                                return new Date().getTime();
                            }
                        }

                        var modalID = "", skipone = 0;

                        $(window).on('shown.bs.modal', function () {
                            modalID = $(".modal:visible").attr("id");
                            $("#" + modalID).hide().fadeIn("fast");
                            skipone = Date.now() + 100;//blocks delete button for 1/10 of a second
                            switch (modalID) {
                                case "profilemodal":
                                    $("#addresslist").html(addresses());
                                    $("#cardlist").html(creditcards()); break;
                            }
                            window.location.hash = "modal";
                        });

                        $(window).on('hashchange', function (event) {//delete button closes modal
                            if (window.location.hash != "#modal" && window.location.hash != "#loading" && !is_firefox_for_android) {
                                if (skipone > Date.now()) {
                                    return;
                                }
                                $('#' + modalID).modal('hide');
                                log("AUTOHIDE " + modalID);
                            }
                        });

                        //generate a list of addresses and send it to the alert modal
                        function addresses() {
                            var HTML = '<DIV CLASS="section"><div class="clearfix mt-1"></div><h2>Address</h2>';
                            var number = $("#add_number").val();
                            var street = $("#add_street").val();
                            var city = $("#add_city").val();
                            var AddNew = false;//number && street && city;
                            $("#saveaddresses option").each(function () {
                                var ID = $(this).val();
                                if (ID > 0) {
                                    HTML += '<DIV ID="add_' + ID + '"><A TITLE="Delete this address" onclick="deleteaddress(' + ID + ');" class="cursor-pointer"><i class="fa fa-fw fa-times error"></i></A> ';
                                    HTML += $(this).text() + '</DIV>';
                                    AddNew = true;
                                }
                            });
                            if (!AddNew) {
                                HTML += 'No Addresses';
                            }
                            return HTML + "</DIV>";
                        }

                        function creditcards() {
                            var Cards = loadsavedcreditinfo();
                            var HTML = '<DIV CLASS="section"><div class="clearfix mt-1"></div><h2>Credit Card';
                            if(Cards != 1){HTML += 's';}
                            HTML += '</h2>';
                            if(Cards) {
                                for (var i = 0; i < userdetails.Stripe.length; i++) {
                                    var card = userdetails.Stripe[i];
                                    //id,object=card,brand,country,customer,cvc_check=pass,exp_month,exp_year=2018,funding=credit,last4=4242
                                    HTML += '<DIV id="card_' + i + '"><A ONCLICK="deletecard(' + i + ", '" + card.id + "', " + card.last4 + ", '" + card.exp_month.pad(2) + "', " + right(card.exp_year, 2) + ');" CLASS="cursor-pointer">';
                                    HTML += '<i class="fa fa-fw fa-times error"></i></A> ' + card.brand + ' x-' + card.last4 + ' Expires: ' + card.exp_month.pad(2) + '/20' + right(card.exp_year, 2) + '</DIV>';
                                }
                                return HTML + '</DIV>';
                            } else {
                                return HTML + "No Credit Cards";
                            }
                        }

                        function deletecard(Index, ID, last4, month, year) {
                            confirm2("Are you sure you want to delete credit card:<br>x- " + last4 + " Expiring on " + month + "/" + year + "?", 'Delete Credit Card', function () {
                                $.post(webroot + "placeorder", {
                                    _token: token,
                                    action: "deletecard",
                                    cardid: ID
                                }, function (result) {
                                    $("#card_" + Index).fadeOut("fast", function () {
                                        $("#card_" + Index).remove();
                                    });
                                    removeindex(userdetails.Stripe, Index);//remove it from userdetails
                                });
                            });
                        }
                        //handles the orders list modal
                        function orders(ID, getJSON) {
                            if (isUndefined(getJSON)) {
                                getJSON = false;
                            }
                            if (isUndefined(ID)) {//no ID specified, get a list of order IDs from the user's profile and make buttons
                                $("#profilemodal").modal("hide");
                                var HTML = '<ul class="list-group">';
                                var First = new Array();
                                for (var i = 0; i < userdetails["Orders"].length; i++) {
                                    var order = userdetails["Orders"][i];
                                    ID = order["id"];
                                    HTML += '<li ONCLICK="orders(' + ID + ');"><span class="text-danger strong">ORDER # ' + ID + ' </span><br>' + order["placed_at"] + '<DIV ID="pastreceipt' + ID + '">';
                                    if(userdetails["Orders"][i].hasOwnProperty("Contents")) {
                                        HTML += userdetails["Orders"][i]["Contents"];
                                    } else {
                                        First.push(ID);
                                    }
                                    HTML += '</DIV></li>';
                                }
                                HTML += '</ul>';
                                if (First.length) {
                                    orders(First)
                                } else {
                                    HTML = "No orders placed yet";
                                }
                                alert(HTML, "Orders");
                            } else if (getJSON) {
                                $.post("<?= webroot('public/list/orders'); ?>", {
                                    _token: token,
                                    action: "getreceipt",
                                    orderid: ID,
                                    JSON: getJSON
                                }, function (result) {
                                    result = JSON.parse(result);
                                    theorder = result["Order"];
                                    $("#cookingnotes").val(result["cookingnotes"]);
                                    generatereceipt();
                                    $("#alertmodal").modal('hide');
                                    scrolltobottom();
                                });
                            } else if (isArray(ID)) {
                                $.post("<?= webroot('public/list/orders'); ?>", {
                                    _token: token,
                                    action: "getreceipts",
                                    orderids: ID,
                                    JSON: false
                                }, function (result) {
                                    result = JSON.parse(result);
                                    var IDs = Object.keys(result);
                                    for(var i=0; i<IDs.length; i++){
                                        ID = IDs[i];
                                        $("#pastreceipt" + ID).html(result[ID]);
                                        var index = getOrderIndex(ID);
                                        if (index > -1) {
                                            userdetails["Orders"][index]["Contents"] = result[ID];
                                        }
                                    }
                                });
                            }
                        }

                        function getOrderIndex(OrderID){
                            return getIterator(userdetails["Orders"], "id", OrderID);
                        }

                        function getIterator(arr, key, value) {
                            for (var i = 0; i < arr.length; i++) {
                                if (arr[i][key] == value) {
                                    return i;
                                }
                            }
                            return -1;
                        }

                        $(document).ready(function () {
                            loading(false, "page");
                            if (getCookie("theorder")) {
                                theorder = JSON.parse(getCookie("theorder"));
                            }
                            generatereceipt();
                            @if(!read("id"))
                                $("#loginmodal").modal("show");
                            @endif

                            $('[data-popup-close]').on('click', function (e) {
                                        var targeted_popup_class = jQuery(this).attr('data-popup-close');
                                        $('#' + targeted_popup_class).modal("hide");
                                    });
                        });

                        function enterkey(e, action) {
                            var keycode = event.which || event.keyCode;
                            if (keycode == 13) {
                                if (action.left(1) == "#") {
                                    $(action).focus();
                                } else {
                                    log("Handle action " + action);
                                    handlelogin(action);
                                }
                            }
                        }

                        function handlelogin(action) {
                            if (isUndefined(action)) {
                                action = "verify";
                            }
                            if (!$("#login_email").val() && action !== "logout" && action !== "depossess") {
                                alert(makestring("{email_needed}"));
                                return;
                            }
                            $.post(webroot + "auth/login", {
                                action: action,
                                _token: token,
                                email: $("#login_email").val(),
                                password: $("#login_password").val()
                            }, function (result) {
                                try {
                                    var data = JSON.parse(result);
                                    if (data["Status"] == "false" || !data["Status"]) {
                                        data["Reason"] = data["Reason"].replace('[verify]', '<A onclick="handlelogin();" CLASS="hyperlink" TITLE="Click here to resend the email">verify</A>');
                                        alert(data["Reason"], makestring("{error_login}"));
                                    } else {
                                        switch (action) {
                                            case "login": case "depossess":
                                                token = data["Token"];
                                                if (!login(data["User"], true)) {
                                                    redirectonlogin = false;
                                                }
                                                $("#loginmodal").modal("hide");
                                                if (redirectonlogin || action == "depossess") {
                                                    log("Login reload");
                                                    location.reload();
                                                }
                                            break;
                                            case "forgotpassword":
                                            case "verify":
                                                alert(data["Reason"], "Login");
                                                break;
                                            case "logout":
                                                removeCookie();
                                                $('[class^="session_"]').text("");
                                                $(".loggedin").hide();
                                                $(".loggedout").show();
                                                $(".clear_loggedout").html("");
                                                $(".profiletype").hide();
                                                userdetails = false;
                                                if (redirectonlogout) {
                                                    log("Logout reload");
                                                    window.location = "<?= webroot(""); ?>";
                                                } else {
                                                    switch (currentRoute) {
                                                        case "index"://resave order as it's deleted in removeCookie();
                                                            if (!isUndefined(theorder)) {
                                                                if (theorder.length > 0) {
                                                                    createCookieValue("theorder", JSON.stringify(theorder));
                                                                }
                                                            }
                                                            break;
                                                    }
                                                }
                                                if (!isUndefined(collapsecheckout)) {
                                                    collapsecheckout();
                                                }
                                                break;
                                        }
                                    }
                                } catch (err) {
                                    alert(err.message + "<BR>" + result, makestring("{error_login}"));
                                }
                            });
                        }

                        var skiploadingscreen = false;
                        var skipunloadingscreen = false;
                        //overwrites javascript's alert and use the modal popup
                        (function () {
                            var proxied = window.alert;
                            window.alert = function () {
                                var title = "Alert";
                                if (arguments.length > 1) {
                                    title = arguments[1];
                                }
                                $("#alert-cancel").hide();
                                $("#alert-ok").off("click");
                                $("#alert-confirm").off("click");
                                $("#alertmodalbody").html(arguments[0]);
                                $("#alertmodallabel").text(title);
                                $("#alertmodal").modal('show');
                            };
                        })();

                        var generalhours = <?= json_encode(gethours()) ?>;

                        var lockloading = false, previoushash = "", $body = "";

                        $(document).ready(function () {
                            //make every AJAX request show the loading animation
                            $body = $("body");

                            $('.modal').on('hidden.bs.modal', function () {
                                history.pushState("", document.title, window.location.pathname);//clean #modal from url
                            });

                            $(document).on({
                                ajaxStart: function () {
                                    //ajaxSend: function ( event, jqxhr, settings ) {log("settings.url: " + settings.url);//use this event if you need the URL
                                    if (skiploadingscreen) {
                                        if (!lockloading) {
                                            skiploadingscreen = false;
                                        }
                                    } else {
                                        loading(true, "ajaxStart");
                                        previoushash = window.location.hash;
                                        window.history.pushState({}, document.title, '#loading');
                                    }
                                },
                                ajaxStop: function () {
                                    if (skipunloadingscreen) {
                                        skipunloadingscreen = false;
                                    } else {
                                        loading(false, "ajaxStop");
                                        if (previoushash) {
                                            if(previoushash.left(1) != "#"){previoushash = "#" + previoushash;}
                                            window.history.pushState({}, document.title, previoushash);
                                        } else {
                                            history.pushState("", document.title, window.location.pathname);
                                        }
                                    }
                                    skipone = Date.now() + 100;//
                                }
                            });

                            @if(isset($user) && $user)
                                login(<?= json_encode($user); ?>, false); //user is already logged in, use the data
                                    @endif

                                    var HTML = '';
                            var todaysdate = isopen(generalhours);
                            if (todaysdate == -1) {
                                HTML = 'Currently closed';
                                todaysdate = getToday();
                                if (generalhours[todaysdate].open > now()) {
                                    HTML = 'Opens at: ' + GenerateTime(generalhours[todaysdate].open);
                                }
                            } else {
                                HTML = 'Open until: ' + GenerateTime(generalhours[todaysdate].close);
                            }
                            GenerateHours(generalhours);
                            $("#openingtime").html(HTML);
                        });

                        //handle a user login
                        function login(user, isJSON) {
                            userdetails = user;
                            var keys = Object.keys(user);
                            for (var i = 0; i < keys.length; i++) {
                                var key = keys[i];
                                var val = user[key];
                                createCookieValue("session_" + key, val);//save data to cookie
                                $(".session_" + key).text(val);//set elements text to data
                                $(".session_" + key + "_val").val(val);//set elements value to data
                            }
                            $(".loggedin").show();//show loggedin class
                            $(".loggedout").hide();//hide loggedout class
                            $(".profiletype").hide();//hide all profile type clasdses
                            $(".profiletype" + user["profiletype"]).show();//show classes for this profile type

                            $(".profiletype_not").show();
                            $(".profiletype_not" + user["profiletype"]).hide();

                            var HTML = 'form-control saveaddresses" id="saveaddresses" onchange="addresschanged(' + "'saveaddress'" + ');"><OPTION value="0">Select Delivery Address</OPTION>';
                            var FirstAddress = false;

                            if (user["Addresses"].length > 0) {
                                HTML = '<SELECT class="' + HTML;
                                addresskeys = Object.keys(user["Addresses"][0]);
                                for (i = 0; i < user["Addresses"].length; i++) {
                                    if (!FirstAddress) {
                                        FirstAddress = user["Addresses"][i]["id"];
                                    }
                                    HTML += AddressToOption(user["Addresses"][i], addresskeys);
                                }
                                HTML += '</SELECT>';
                            } else {
                                HTML = '<SELECT class="dont-show ' + HTML + '</SELECT>';
                            }
                            $(".addressdropdown").html(HTML);
                            if (user["profiletype"] == 2) {
                                user["restaurant_id"] = FirstAddress;
                                var URL = '<?= webroot("public/list/orders"); ?>';
                                if (window.location.href != URL && isJSON) {
                                    redirectonlogin = false;
                                    window.location.href = URL;
                                    return false;
                                }
                            }
                            return true;
                        }

                        //convert an address to a dropdown option
                        function AddressToOption(address, addresskeys) {
                            if (isUndefined(addresskeys)) {
                                addresskeys = ["id", "value", "user_id", "number", "unit", "buzzcode", "street", "postalcode", "city", "province", "latitude", "longitude", "phone"];
                            }
                            var tempHTML = '<OPTION';
                            var streetformat = "<?= $STREET_FORMAT; ?>";
                            if (address["unit"].trim()) {
                                streetformat = streetformat + " - [unit]";
                            }
                            for (var keyID = 0; keyID < addresskeys.length; keyID++) {
                                var keyname = addresskeys[keyID];
                                if (address.hasOwnProperty(keyname)) {
                                    var value = address[keyname];
                                    streetformat = streetformat.replace("[" + keyname + "]", value);
                                    if (keyname == "id") {
                                        keyname = "value";
                                    }
                                    tempHTML += ' ' + keyname + '="' + value + '"'
                                }
                            }
                            return tempHTML + '>' + streetformat + '</OPTION>';
                        }

                        function clearphone() {
                            $('#reg_phone').attr("style", "");
                            $(".payment-errors").text("");
                        }

                        //address dropdown changed
                        function addresschanged(why) {
                            clearphone();
                            var Selected = $("#saveaddresses option:selected");
                            var SelectedVal = $(Selected).val();
                            var Text = '<?= $STREET_FORMAT; ?>';
                            visible_address(false);
                            $("#add_unit").hide();
                            if (addresskeys.length == 0) {
                                addresskeys = ["id", "value", "user_id", "number", "unit", "buzzcode", "street", "postalcode", "city", "province", "latitude", "longitude", "phone"];
                            }
                            for (var keyID = 0; keyID < addresskeys.length; keyID++) {
                                var keyname = addresskeys[keyID];
                                if (SelectedVal == 0) {
                                    var keyvalue = "";
                                } else {
                                    var keyvalue = $(Selected).attr(keyname);
                                }
                                Text = Text.replace("[" + keyname + "]", keyvalue);
                                $("#add_" + keyname).val(keyvalue);
                            }
                            $("#ffaddress").hide();
                            refreshform("#saveaddresses").trigger("click");
                            if (SelectedVal == 0) {
                                Text = '';
                            } else {
                                //$("#saveaddresses").removeClass("red");
                                //$("#red_address").removeClass("redhighlite");
                                $("#formatted_address").hide();
                                if (SelectedVal == "addaddress") {
                                    visible_address(true);
                                    //refreshform("#formatted_address");
                                    $("#add_unit").show();
                                    Text = "";
                                    handlefirefox("addresschanged:" + why);
                                }
                            }
                            $("#formatted_address").val(Text);
                            $("#restaurant").html('<OPTION VALUE="0" SELECTED>Restaurant</OPTION>');//.addClass("red");
                            $("#red_rest").addClass("redhighlite");
                            addresshaschanged();
                        }

                        function handlefirefox(why){
                            if(why == "addresschanged:showcheckout"){return false;}
                            if(is_firefox_for_android){
                                log("handlefirefox Why: " + why);
                                $("#ffaddress").show();
                                $("#formatted_address").show();
                                $("#checkoutmodal").modal("hide");
                                $("#firefoxandroid").show();
                            }
                        }

                        //universal AJAX error handling
                        var blockerror = false;
                        $(document).ajaxComplete(function (event, request, settings) {
                            if (skipunloadingscreen) {
                                skipunloadingscreen = false;
                            } else {
                                loading(false, "ajaxComplete");
                            }
                            if (request.status != 200 && request.status > 0 && !blockerror) {//not OK, or aborted
                                var text = request.responseText;
                                if (text.indexOf('Whoops, looks like something went wrong.') > -1 && text.indexOf('<span class="exception_title">') > -1) {
                                    text = text.between('<span class="exception_title">', '</h2>');
                                    text = text.replace(/<(?:.|\n)*?>/gm, '');
                                    if (text.indexOf('TokenMismatchException') > -1) {
                                        text = "Your session has expired. Starting a new one.";
                                        $.get(webroot + "auth/gettoken", function (data) {
                                            token = data;
                                        });
                                    }
                                } else {
                                    text = request.statusText;
                                }
                                alert(text + "<BR><BR>URL: " + settings.url, "AJAX error code: " + request.status);
                            }
                            blockerror = false;
                        });

                        function rnd(min, max) {
                            return Math.round(Math.random() * (max - min) + min);
                        }

                        function cantplaceorder() {
                            $(".payment-errors").text("");
                            $(".red").removeClass("red");
                            $("#red_card").removeClass("redhighlite");
                            if (!validaddress()) {
                                //$("#saveaddresses").addClass("red");
                                $("#red_address").addClass("redhighlite");
                                $(".payment-errors").text("Please check your address");
                            } else if (!$("#saved-credit-info").val()) {
                                if (!isvalidcreditcard()) {
                                    $("#red_card").addClass("redhighlite");
                                    $("[data-stripe=number]").addClass("red");
                                    $(".payment-errors").text("Please select or enter a valid credit card");
                                    return false;
                                }
                            }
                            if ($("#reg_phone").val().length == 0) {
                                $('#reg_phone').attr('style', 'border-bottom: 1px solid red !important;');
                                $(".payment-errors").text("Cell Phone Required");
                            }
                        }

                        function testcard() {
                            $('input[data-stripe=number]').val('4242424242424242').trigger("click");
                            $('input[data-stripe=address_zip]').val('L8L6V6').trigger("click");
                            $('input[data-stripe=cvc]').val(rnd(100, 999)).trigger("click");
                            $('select[data-stripe=exp_year]').val({{ right($CURRENT_YEAR,2) }} +1).trigger("click");
                            @if(islive())
                                log("Changing stripe key");
                            $("#istest").val("true");
                            setPublishableKey('pk_rlgl8pX7nDG2JA8O3jwrtqKpaDIVf', "test");
                            log("Stripe key changed");
                            @endif
                            changecredit();
                        }

                        function flash(delay){
                            $('.redhighlite').fadeTo(delay, 0.3, function() { $(this).fadeTo(delay, 1.0).fadeTo(delay, 0.3, function() { $(this).fadeTo(delay, 1.0).fadeTo(delay, 0.3, function() { $(this).fadeTo(delay, 1.0); }); }); });
                        }

                        var paydisabled = false;
                        function payfororder() {
                            $(".payment-errors").html("");
                            if(alertshortage()){return false;}
                            if (!canplaceanorder()) {
                                flash(500);
                                return cantplaceorder();
                            }
                            if ($("#orderinfo").find(".error:visible[for]").length > 0) {
                                flash(500);
                                return false;
                            }
                            if(paydisabled){
                                log("Already placing an order");
                                return false;
                            }
                            paydisabled=true;
                            var $form = $('#orderinfo');
                            log("Attempt to pay: " + changecredit());
                            if (!changecredit()) {//new card
                                log("Stripe data");
                                Stripe.card.createToken($form, stripeResponseHandler);
                                log("Stripe data - complete");
                            } else {//saved card
                                log("Use saved data");
                                placeorder("");//no stripe token, use customer ID on the server side
                            }
                            $(".saveaddresses").removeClass("dont-show");
                        }

                        function stripeResponseHandler(status, response) {
                            var errormessage = "";
                            log("Stripe response");
                            switch (status) {
                                case 400: errormessage = "Bad Request:<BR>The request was unacceptable, often due to missing a required parameter."; break;
                                case 401: errormessage = "Unauthorized:<BR>No valid API key provided."; break;
                                case 402: errormessage = "Request Failed:<BR>The parameters were valid but the request failed."; break;
                                case 404: errormessage = "Not Found:<BR>The requested resource doesn't exist."; break;
                                case 409: errormessage = "Conflict:<BR>The request conflicts with another request (perhaps due to using the same idempotent key)."; break;
                                case 429: errormessage = "Too Many Requests:<BR>Too many requests hit the API too quickly. We recommend an exponential backoff of your requests."; break;
                                case 500: case 502: case 503: case 504: errormessage = "Server Errors:<BR>Something went wrong on Stripe's end."; break;
                                case 200:// - OK	Everything worked as expected.
                                    if (response.error) {
                                        $('.payment-errors').html(response.error.message);
                                    } else {
                                        log("Stripe successful");
                                        if (!changecredit()) {//save new card to userdetails
                                            if (!isArray(userdetails.Stripe)) {
                                                userdetails.Stripe = new Array();
                                            }//check to be sure
                                            userdetails.Stripe.push(getnewcard(response.id));
                                        }
                                        placeorder(response.id);
                                    } break;
                            }
                            if (errormessage) {
                                //$(".payment-errors").html(errormessage + "<BR><BR>" + response["error"]["type"] + ":<BR>" + response["error"]["message"]);
                                $(".payment-errors").html(response["error"]["message"]);
                            }
                        }

                        function getnewcard(ID) {
                            var card_number = $("input[data-stripe=number]").val().replace(/\D/g, '');
                            var card_brand = "Unknown (" + card_number.left(1) + ")";
                            switch (card_number.left(1)) {
                                case "3": card_brand = "American Express"; break;
                                case "4": card_brand = "Visa"; break;
                                case "5": card_brand = "Master Card"; break;
                            }
                            return {
                                id: ID,
                                brand: card_brand,
                                last4: card_number.right(4),
                                exp_month: Number($("select[data-stripe=exp_month]").val()),
                                exp_year: "20" + $("select[data-stripe=exp_year]").val(),
                                cvc: $("input[data-stripe=cvc]").val()
                            };
                        }

                        var closest = false;
                        function addresshaschanged(place) {
                            if (!getcloseststore) {return;}
                            var HTML = '<OPTION VALUE="0">No restaurant is within range</OPTION>';
                            if(isUndefined(place)) {
                                var value = $("#saveaddresses").val();
                                if (value == "0" || value == "addaddress"){
                                    $("#restaurant").html(HTML).val(0);
                                    return;
                                }
                                var formdata = getform("#orderinfo");
                            } else {//needs latitude and longitude, radius and limit optional
                                var formdata = {latitude:  place.geometry.location.lat, longitude:  place.geometry.location.lng};
                            }
                            formdata.limit = 10;
                            if (!formdata.latitude || !formdata.longitude) {return;}
                            if (!debugmode) {formdata.radius = MAX_DISTANCE;}
                            //skiploadingscreen = true;
                            //canplaceorder = false;
                            $.post(webroot + "placeorder", {
                                _token: token,
                                info: formdata,
                                action: "closestrestaurant"
                            }, function (result) {
                                if (handleresult(result)) {
                                    closest = JSON.parse(result)["closest"];
                                    var smallest = "0";
                                    if (closest.length > 0) {//} closest.hasOwnProperty("id")) {
                                        HTML = '';
                                        var distance = -1;
                                        for (var i = 0; i < closest.length; i++) {
                                            var restaurant = closest[i];
                                            closest[i].restid = restaurant.restaurant.id;
                                            restaurant.distance = parseFloat(restaurant.distance);
                                            var distancetext = "";
                                            if (restaurant.distance <= MAX_DISTANCE || debugmode) {
                                                if (restaurant.distance >= MAX_DISTANCE) {
                                                    restaurant.restaurant.name += " [DEBUG]"
                                                }
                                                if (distance == -1 || distance > restaurant.distance) {
                                                    smallest = restaurant.restaurant.id;
                                                    distance = restaurant.distance;
                                                    distancetext = ' (' + restaurant.distance.toFixed(2) + ' km)';
                                                }
                                                HTML += '<OPTION VALUE="' + restaurant.restaurant.id + '">' + restaurant.restaurant.name + '</OPTION>';
                                            }
                                        }
                                    }
                                    if (!smallest) {
                                        smallest = 0;
                                    }
                                    $("#restaurant").html(HTML).val(smallest);
                                    restchange();
                                }
                            });
                        }

                        function testclosest() {
                            var formdata = getform("#orderinfo");
                            formdata.limit = 10;
                            if (!formdata.latitude || !formdata.longitude) {
                                alert(makestring("{long_lat}"));
                                return;
                            }
                            $.post(webroot + "placeorder", {
                                _token: token,
                                info: formdata,
                                action: "closestrestaurant"
                            }, function (result) {
                                if (handleresult(result)) {
                                    alert(result, makestring("{ten_closest}"));
                                }
                            });
                        }

                        function loadsavedcreditinfo() {
                            if (userdetails.stripecustid.length > 0 && userdetails.hasOwnProperty("Stripe")) {
                                return userdetails.Stripe.length;
                            }
                            return 0;
                        }

                        function changecredit() {
                            $(".payment-errors").html("");
                            $("#saved-credit-info").removeClass("red");
                            $("[data-stripe=number]").removeClass("red");
                            var val = $("#saved-credit-info").val();
                            $("#red_card").removeClass("redhighlite");
                            if (!val) {
                                if (!isvalidcreditcard()) {
                                    $("#red_card").addClass("redhighlite");
                                }
                                $(".credit-info").show();//let cust edit the card
                            } else {
                                $(".credit-info").hide();//use saved card info
                            }
                            return val;
                        }

                        function showcheckout() {
                            //canplaceorder=false;
                            if (userdetails["Addresses"].length == 0) {
                                setTimeout(function () {
                                    $("#saveaddresses").val("addaddress");
                                    addresschanged("showcheckout");
                                }, 100);
                            } else {
                                $("#saveaddresses").val(0);
                            }
                            addresschanged("showcheckout");
                            var HTML = $("#checkoutaddress").html();
                            HTML = HTML.replace('class="', 'class="corner-top ');
                            var needscreditrefresh = false;
                            if (loadsavedcreditinfo()) {
                                $(".credit-info").hide();
                                var creditHTML = '<SELECT ID="saved-credit-info" name="creditcard" onchange="changecredit();" class="form-control proper-height">';
                                for (var i = 0; i < userdetails.Stripe.length; i++) {
                                    var card = userdetails.Stripe[i];
                                    creditHTML += '<OPTION value="' + card.id + '" id="card_' + card.id + '"';
                                    if (i == userdetails.Stripe.length - 1) {
                                        creditHTML += ' SELECTED';
                                    }
                                    creditHTML += '>' + card.brand + ' x-' + card.last4 + ' Expires: ' + card.exp_month.pad(2) + '/20' + right(card.exp_year, 2) + '</OPTION><OPTION value="">Add Card</OPTION>';
                                }
                                $("#credit-info").html(creditHTML + '</SELECT>');
                            } else {
                                $("#credit-info").html('<INPUT TYPE="hidden" VALUE="" ID="saved-credit-info">');
                                needscreditrefresh = true;
                            }
                            $("#checkoutaddress").html(HTML);
                            $("#deliverytime").val($("#deliverytime option:first").val());
                            $("#checkoutmodal").modal("show");
                            $(function () {
                                $("#orderinfo").validate({
                                    submitHandler: function (form) {
                                        //handled by placeorder
                                    }
                                });
                            });
                            $("#restaurant").html('<option value="0">Select Restaurant</option>').val("0");
                            $("#saveaddresses").attr("autored", "red_address");
                            refreshform("#saveaddresses");
                            changecredit();
                            orderinfocheck();
                        }

                        var daysofweek = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                        var monthnames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

                        function now() {
                            if(newtime > -1){return newtime;}
                            var now = new Date();
                            return now.getHours() * 100 + now.getMinutes();
                        }

                        function getToday() {
                            return getNow(3);//doesn't take into account <= because it takes more than 1 minute to place an order
                        }

                        function GenerateTime(time) {
                            var minutes = time % 100;
                            var thehours = Math.floor(time / 100);
                            var hoursAMPM = thehours % 12;
                            if (hoursAMPM == 0) {
                                hoursAMPM = 12;
                            }
                            var tempstr = hoursAMPM + ":";
                            if (minutes < 10) {
                                tempstr += "0" + minutes;
                            } else {
                                tempstr += minutes;
                            }
                            var extra = "";
                            if (time == 0) {
                                extra = " (Midnight)";
                            } else if (time == 1200) {
                                extra = " (Noon)";
                            }
                            if (time < 1200) {
                                return tempstr + " AM" + extra;
                            } else {
                                return tempstr + " PM" + extra;
                            }
                        }

                        function gettime(now, IncrementBig, IncrementSmall, OldTime){
                            var minutes = now.getMinutes();
                            minutes = minutes + IncrementBig;
                            minutes = minutes + (IncrementSmall - (minutes % IncrementSmall));
                            now.setMinutes(minutes);
                            var time = now.getHours() * 100 + now.getMinutes();
                            return [now, time, OldTime, IncrementBig, IncrementSmall];
                        }

                        function addtotime(time, increments){
                            time = time + increments;
                            if (time % 100 >= 60) {
                                return (Math.floor(time / 100) + 1) * 100;
                            }
                            return time;
                        }

                        //Index: 0=hour, 1=minute, 2=24hr time, 3=day of week(0-6), 4=date, 5=tomorrow
                        function getNow(Index){
                            if(isUndefined(Index)){
                                return Math.floor(Date.now() / 1000);//reduce to seconds
                            }
                            var now = new Date();
                            switch (Index){
                                case 0: //hour
                                    if(newtime > -1){return Math.floor(newtime / 100);}
                                    return now.getHours();
                                    break;
                                case 1: //minute
                                    if(newtime > -1){return Math.floor(newtime % 100);}
                                    return now.getMinutes();
                                    break;
                                case 2://hour+minute(24 hour)
                                    if(newtime > -1){return newtime;}
                                    return now.getHours() * 100 + now.getMinutes();
                                    break;
                                case 3: //day of week
                                    if(newday > -1){return newday;}
                                    return now.getDay();
                                    break;
                                case 4: case 5: //date
                                if(newtime > -1){
                                    now.setHours(Math.floor(newtime / 100));
                                    now.setMinutes(Math.floor(newtime % 100));
                                }
                                if(newday > -1){
                                    var currentday = now.getDay();
                                    if(currentday > newday){
                                        now.add("day", 6 - currentday + newday);
                                    } else if (currentday < newday){
                                        now.add("day", newday - currentday);
                                    }
                                }
                                if(Index == 5){
                                    return now.add("day", 1);
                                }
                                return now;
                                break;
                            }
                        }

                        function GenerateHours(hours, increments) {
                            //doesn't take into account <= because it takes more than 1 minute to place an order
                            //now.setMinutes(now.getMinutes() + minutes);//start 40 minutes ahead
                            if (isUndefined(increments)) {increments = 15;}
                            var minutes = <?= getdeliverytime(); ?>;
                            var dayofweek = getNow(3);
                            var minutesinaday = 1440;
                            var totaldays = 2;
                            var dayselapsed = 0;
                            var today = dayofweek;
                            var tomorrow = (today + 1) % 7;
                            var now = getNow(4);
                            var tomorrowdate = getNow(5);//new Date().add("day", 1);
                            var today_text = "Today (" + monthnames[now.getMonth()] + " " + now.getDate() + ")";
                            var tomor_text = "Tomorrow (" + monthnames[tomorrowdate.getMonth()] + " " + tomorrowdate.getDate() + ")";

                            var time = getNow(2);
                            time = time + (increments - (time % increments));
                            var oldValue = $("#deliverytime").val();
                            var HTML = '';
                            var temp = gettime(now, minutes, 15, time);
                            log(temp);
                            now = temp[0];
                            time = temp[1];
                            if (isopen(hours, dayofweek, temp[2]) > -1) {
                                HTML = '<option value="Deliver Now">Deliver Now (' + GenerateTime(time) + ')</option>';
                                time = addtotime(time, increments);
                            }
                            var totalInc = (minutesinaday * totaldays) / increments;
                            for (var i = 0; i < totalInc; i++) {
                                if (isopen(hours, dayofweek, time) > -1) {
                                    var minutes = time % 100;
                                    if (minutes < 60) {
                                        var thetime = GenerateTime(time);
                                        var thedayname = daysofweek[dayofweek];
                                        var thedate = monthnames[now.getMonth()] + " " + now.getDate();
                                        if (dayofweek == today) {
                                            thedayname = today_text;
                                        } else if (dayofweek == tomorrow) {
                                            thedayname = tomor_text;
                                        } else {
                                            thedayname += " " + thedate;
                                        }
                                        var tempstr = '<OPTION VALUE="' + thedate + " at " + time.pad(4) + '">' + thedayname + " at " + thetime;
                                        HTML += tempstr + '</OPTION>';
                                    }
                                }
                                time = addtotime(time, increments);
                                if (time >= 2400) {
                                    time = time % 2400;
                                    dayselapsed += 1;
                                    dayofweek = (dayofweek + 1) % 7;
                                    now = new Date(now.getTime() + 24 * 60 * 60 * 1000);
                                    if (dayofweek == today || dayselapsed == totaldays) {
                                        i = totalInc;
                                    }
                                }
                            }

                            $("#deliverytimealias").html(HTML);
                            $("#deliverytime").html(HTML).val(oldValue);
                        }

                        //getNow(Index){Index: 0=hour, 1=minute, 2=24hr time, 3=day of week(0-6), 4=date, 5=tomorrow
                        function isopen(hours, dayofweek, time) {
                            var now = getNow(4);//doesn't take into account <= because it takes more than 1 minute to place an order
                            if (isUndefined(dayofweek)) {dayofweek = getNow(3);}
                            if (isUndefined(time)) {time = getNow(2);}//now.getHours() * 100 + now.getMinutes();
                            var today = hours[dayofweek];
                            if (!today.hasOwnProperty("open")){return false;}
                            var yesterday = dayofweek - 1;
                            if (yesterday < 0) {
                                yesterday = 6;
                            }
                            var yesterdaysdate = yesterday;
                            yesterday = hours[yesterday];
                            today.open = Number(today.open);
                            today.close = Number(today.close);
                            yesterday.open = Number(yesterday.open);
                            yesterday.close = Number(yesterday.close);
                            if (yesterday.open > -1 && yesterday.close > -1 && yesterday.close < yesterday.open) {
                                if (yesterday.close > time) {
                                    return yesterdaysdate;
                                }
                            }
                            if (today.open > -1 && today.close > -1) {
                                if (today.close < today.open) {
                                    if (time >= today.open || time < today.close) {
                                        return dayofweek;
                                    }
                                } else {
                                    if (time >= today.open && time < today.close) {
                                        return dayofweek;
                                    }
                                }
                            }
                            return -1;//closed
                        }

                        function visiblemodals() {
                            return $('.modal:visible').map(function () {
                                return this.id;
                            }).get();
                        }

                        if (isUndefined(unikeys)) {
                            var unikeys = {
                                exists_already: "'[name]' already exists",
                                cat_name: "What name would you like the category to be?\r\nIt will only be saved when you add an item to the category",
                                not_placed: "Order was not placed!",
                                error_login: "Error logging in",
                                email_needed: "Please enter an email address",
                                long_lat: "Longitude and/or latitude missing",
                                ten_closest: "10 closest restaurants",
                                clear_order: "Clear your order?"
                            };
                        }

                        function makestring(Text, Variables) {
                            if (Text.startswith("{") && Text.endswith("}")) {
                                Text = unikeys[Text.mid(1, Text.length - 2)];
                            }
                            if (!isUndefined(Variables)) {
                                if(isObject(Variables)) {
                                    var keys = Object.keys(Variables);
                                    for (var i = 0; i < keys.length; i++) {
                                        var key = keys[i];
                                        var value = Variables[key];
                                        Text = Text.replaceAll("\\[" + key + "\\]", value);
                                    }
                                } else {
                                    if(!isArray(Variables)){Variables = [Variables];}
                                    for (var i = 0; i < Variables.length; i++) {
                                        var value = Variables[i];
                                        Text = Text.replaceAll("\\[" + i + "\\]", value);
                                    }
                                }
                            }
                            return Text;
                        }

                        var oneclick = true, currentstyle = 1, currentbasecost = 0, currentaddoncost = 0;
                        var currentaddontype = "", currentside = "", currentqualifier = "", addonname = "", item_name = "", hashalves = true;
                        var currentaddonlist = new Array, currentitemindex = 0, currentitemname = "";

                        function toclassname(text) {
                            return text.toLowerCase().replaceAll(" ", "_");
                        }

                        function generateaddons(ItemIndex, ToppingIndex) {
                            var HTML = '';
                            var totaltoppings = 0;
                            if (isUndefined(ItemIndex)) {
                                ItemIndex = -1;
                                ToppingIndex = -1;
                            }
                            switch (currentaddontype) {
                                case "toppings":
                                    addonname = "Toppings";
                                    item_name = "Pizza ";
                                    break;
                                case "wings_sauce":
                                    addonname = "Sauce";
                                    item_name = "Lb";
                                    break;
                                default:
                                    addonname = "Error: " + currentaddontype;
                            }

                            var thisside = ' CLASS="thisside">';

                            for (var itemindex = 0; itemindex < currentaddonlist.length; itemindex++) {
                                var freetoppings = 0;
                                var paidtoppings = 0;
                                var tempstr = '';
                                var classname = 'itemcontents itemcontents' + itemindex;

                                HTML += '<DIV ONCLICK="selectitem(event, ' + itemindex + ');" CLASS="list-group-item receipt-addons currentitem currentitem' + itemindex;
                                if (currentitemindex == itemindex) {
                                    HTML += ' thisside';
                                }
                                HTML += '">' + '<strong class="pr-3" id="item_' + itemindex + '">' + ucfirst(item_name) + ' #' + (itemindex + 1) + '</strong>';

                                if(currentaddonlist[itemindex].length == 0){
                                    tempstr += ' No ' + addonname;
                                }
                                for (var i = 0; i < currentaddonlist[itemindex].length; i++) {
                                    var currentaddon = currentaddonlist[itemindex][i];
                                    var qualifier = "";
                                    tempstr += '<DIV CLASS="pr-3 ' + classname + '" id="topping_' + itemindex + '_' + i + '">' + currentaddon.name;
                                    @if($GLOBALS["settings"]["deletetopping"])
                                        tempstr += '<span ONCLICK="removelistitem(' + itemindex + ', ' + i + ');">&nbsp; <i CLASS="fa fa-times"></i> </span>';
                                    @endif
                                    tempstr += '</div>';
                                    qualifier = currentaddon.qual;
                                    if (qualifier == 0) {
                                        qualifier = 0.5;
                                    } else if (currentaddon.side != 1) {
                                        qualifier = qualifier * 0.5;
                                    }
                                    if (isaddon_free(currentaddontype, currentaddon.name)) {
                                        freetoppings += qualifier;
                                    } else {
                                        paidtoppings += qualifier;
                                    }
                                }
                                totaltoppings += Math.ceil(paidtoppings);
                                if (debugmode) {
                                    HTML += " (Paid: " + paidtoppings + " Free: " + freetoppings + ')';
                                }
                                HTML += tempstr + '</DIV>';
                            }

                            var totalcost = getcost(totaltoppings);
                            $("#modal-itemtotalprice").text(totalcost);
                            $("#theaddons").html(HTML);
                            $(".currentitem.thisside").trigger("click");
                            refreshremovebutton();
                            if (ItemIndex > -1) {
                                $("#topping_" + ItemIndex + "_" + ToppingIndex).hide().fadeTo('fast', 1);
                            }
                            showcursor(currentitemindex);
                        }

                        function getcost(Toppings) {
                            //itemcost, itemname, size, toppingcost
                            if (currentitem.toppingcost) {
                                var itemcost = parseFloat(currentitem.itemcost.replace("$", ""));
                                itemcost += parseFloat(currentitem.toppingcost) * Number(Toppings);
                                return itemcost.toFixed(2);// + " (" + Toppings + " addons)";
                            }
                            return $("#modal-itemprice").text();
                        }

                        function list_addons_quantity(quantity, tablename, halves, name, basecost, addoncost) {
                            currentaddonlist = new Array();
                            currentitemindex = 0;
                            for (var i = 0; i < quantity; i++) {
                                currentaddonlist.push([]);
                            }
                            currentitemname = name;
                            currentbasecost = basecost;
                            currentaddoncost = addoncost;
                            list_addons(tablename, halves);
                        }

                        function list_addons(table, halves) {
                            currentaddontype = table;
                            var HTML = '<DIV class="receipt-addons-list"><DIV id="theaddons"></DIV></DIV>';
                            if (currentstyle == 0) {
                                HTML += '<DIV CLASS="addonlist" style="border:3px solid green !important;" ID="addontypes">';
                            }
                            var types = Object.keys(alladdons[table]);
                            if (currentstyle == 0) {
                                $("#addonlist").html(HTML + '</DIV>');
                            } else {
                                HTML += '<div style="border:0px solid blue !important;position: absolute; bottom: 0;width:100%">';

                                var breaker_green = false, breaker_red = false;
                                for (var i = 0; i < types.length; i++) {
                                    for (var i2 = 0; i2 < alladdons[currentaddontype][types[i]].length; i2++) {
                                        var addon = alladdons[currentaddontype][types[i]][i2];
                                        var title = "";
                                        var breaker_color = "";
                                        if(types[i] == 'Vegetable' && !breaker_green){
                                            breaker_color = 'note_green';
                                            breaker_green=true;
                                        }
                                        if(types[i] == 'Meat' && !breaker_red){
                                            breaker_color = 'note_red';
                                            breaker_red=true;
                                        }

                                        HTML += '<button class="fourthwidth bg-white bg-' + types[i] + ' ' + breaker_color + ' addon-addon list-group-item-action toppings_btn';
                                        if (isaddon_free(String(currentaddontype), String(addon))) {
                                            title = "Free addon";
                                        }
                                        HTML += '" TITLE="' + title + '">' + addon +'</button>';
                                    }
                                }

                                HTML += '<button class="fourthwidth toppings_btn list-group-item-action bg-white" id="removeitemfromorder"><i class="fa fa-arrow-left removeitemarrow"></i></button>' +
                                        '<button class="btn-primary fourthwidth toppings_btn strong" data-popup-close="menumodal" data-dismiss="modal" id="additemtoorder" onclick="additemtoorder();">ADD</button>';

                                $("#addonlist").html(HTML);
                                $(".addon-addon").click(
                                        function (event) {
                                            list_addon_addon(event);
                                        }
                                );
                            }
                            $(".addon-type").click(
                                    function (event) {
                                        list_addon_type(event);
                                    }
                            );
                            hashalves = halves;
                            generateaddons();
                        }

                        function list_addon_type(e) {
                            $(".addon-type").removeClass("addon-selected");
                            $(e.target).addClass("addon-selected");
                            $("#addonall").remove();
                            $("#addonedit").remove();
                            var HTML = '<DIV ID="addonall">';
                            var addontype = $(e.target).text();
                            for (var i = 0; i < alladdons[currentaddontype][addontype].length; i++) {
                                var addon = alladdons[currentaddontype][addontype][i];
                                HTML += '<DIV class="addon-addon">' + addon + '</DIV>';
                            }
                            $(e.target).after(HTML + '</DIV>');
                            $(".addon-addon").click(
                                    function () {
                                        list_addon_addon(event);
                                    }
                            );
                        }

                        function list_addon_addon(e) {
                            addonname = $(e.target).text();
                            if (oneclick) {
                                currentqualifier = 1;
                                return addtoitem();
                            }
                            $(".addon-addon").removeClass("addon-selected");
                            $(e.target).addClass("addon-selected");
                            $("#addonedit").remove();
                            var HTML = '<DIV ID="addonedit">';
                            if (isaddon_free(currentaddontype, addonname)) {
                                HTML += '<DIV>This is a free addon</DIV>';
                            }
                            if (hashalves) {
                                if (isaddon_onall(currentaddontype, addonname)) {
                                    HTML += '<DIV>This addon goes on the whole item</DIV>';
                                    currentside = 1;
                                } else {
                                    HTML += makelist("Side", "addon-side", ["Left", "Whole", "Right"], 1);
                                }
                            }

                            if (qualifiers[currentaddontype].hasOwnProperty(addonname)) {
                                HTML += makelist("Qualifier", "addon-qualifier", qualifiers[currentaddontype][addonname], 1);
                            } else {
                                HTML += makelist("Qualifier", "addon-qualifier", qualifiers["DEFAULT"], 1);
                            }

                            HTML += '<BUTTON ONCLICK="addtoitem();"">Add to item</BUTTON>';
                            $(e.target).after(HTML + '</DIV>');
                        }

                        function makelist(Title, classname, data, defaultindex) {
                            var HTML = '<DIV><DIV>' + Title + ':</DIV>';
                            var selected;
                            for (var i = 0; i < data.length; i++) {
                                selected = "";
                                if (i == defaultindex) {
                                    selected = " addon-selected";
                                }
                                HTML += '<DIV CLASS="addon-list ' + classname + selected + '" ONCLICK="list_addon_list(event, ' + "'" + classname + "', " + i + ');">' + data[i] + '</DIV>';
                            }
                            switch (classname) {
                                case "addon-qualifier":
                                    currentqualifier = defaultindex; break;
                                case "addon-side":
                                    currentside = defaultindex; break;
                            }
                            return HTML + '</DIV>';
                        }

                        function list_addon_list(e, classname, index) {
                            var listitemname = $(e.target).text();
                            //if(classname == "addon-qualifier" && index == 0){index = "0.5";}
                            $("." + classname).removeClass("addon-selected");
                            $(e.target).addClass("addon-selected");
                            switch (classname) {
                                case "addon-qualifier":
                                    currentqualifier = index; break;
                                case "addon-side":
                                    currentside = index; break;
                            }
                            log(classname + "." + listitemname + "=" + index);
                        }

                        function addtoitem() {
                            if (!hashalves) {
                                currentside = 1;
                            }
                            var removed = "";
                            var group = getaddon_group(currentaddontype, addonname);
                            currentaddonlist[currentitemindex].push({
                                name: addonname,
                                side: currentside,
                                qual: currentqualifier,
                                type: currentaddontype,
                                group: group
                            });
                            if (group > 0) {
                                for (var i = currentaddonlist[currentitemindex].length - 2; i > -1; i--) {
                                    if (currentaddonlist[currentitemindex][i]["group"] == group) {
                                        removed = currentaddonlist[currentitemindex][i]["name"];
                                        if (removed == addonname) {
                                            removed = "";
                                        }
                                        removelistitem(currentitemindex, i);
                                    }
                                }
                            }
                            if (!oneclick) {
                                $(".addon-selected").removeClass("addon-selected");
                                $("#addonall").remove();
                                $("#addonedit").remove();
                            }
                            if (removed) {
                                removed += " was removed";
                            }
                            // $("#removelist").text(removed);
                            generateaddons(currentitemindex, currentaddonlist[currentitemindex].length - 1);
                        }

                        function selectitem(e, index) {
                            $(".currentitem").removeClass("thisside");
                            $(".currentitem" + index).addClass("thisside");
                            currentitemindex = index;
                            refreshremovebutton();
                        }
                        function showcursor(Index){
                            $(".blinking-cursor").hide();
                            $("#cursor" + Index).show();
                        }

                        function removelistitem(index, subindex) {
                            if (isUndefined(subindex)) {
                                removeindex(currentaddonlist, index);
                            } else {
                                removeindex(currentaddonlist[index], subindex);
                            }
                            generateaddons();
                        }

                        function makeplural(value, singular, plural){
                            if(value == 1){return singular;}
                            if(isUndefined(plural)){return singular + "s";}
                            return plural;
                        }

                        function ucfirst(text) {
                            return text.left(1).toUpperCase() + text.right(text.length - 1);
                        }

                        function visible_address(state) {
                            visible("#formatted_address", state);
                            visible("#add_unit", state);
                        }

                        function iif(value, iftrue, iffalse) {
                            if (value) {return iftrue;}
                            if (isUndefined(iffalse)) {return "";}
                            return iffalse;
                        }

                        @if(read("id"))
                            $(document).ready(function () {
                                <?php
                                    if (islive() || $GLOBALS["testlive"]) {
                                        echo "setPublishableKey('pk_vnR0dLVmyF34VAqSegbpBvhfhaLNi', 'live')";
                                    } else {
                                        echo "setPublishableKey('pk_rlgl8pX7nDG2JA8O3jwrtqKpaDIVf', 'test');";
                                    }
                                ?>
                            });

                            $(document).keyup(function(e) {
                                if (e.keyCode == 27) {//escape key
                                    $(".modal:visible").modal("hide");
                                }
                            });

                            function backtotime(timestamp) {
                                var d = new Date(timestamp * 1000);
                                return d.getHours() + ":" + d.getMinutes();
                            }

                            function incrementtime() {
                                if (!$(".countdown").hasAttr("timestamp")) {
                                    var seconds = Number($(".countdown").attr("seconds"));
                                    var minutes = Number($(".countdown").attr("minutes"));
                                    var timestamp = getNow();
                                    $(".countdown").attr("startingtime", backtotime(timestamp));
                                    timestamp += (minutes * 60) + seconds;
                                    $(".countdown").attr("endingtime", backtotime(timestamp));
                                    $(".countdown").attr("timestamp", timestamp);
                                } else {
                                    var timestamp = $(".countdown").attr("timestamp");
                                    var seconds = timestamp - getNow();
                                    var minutes = Math.floor(seconds / 60);
                                    seconds = seconds % 60;
                                }
                                var hours = Math.floor(minutes / 60);

                                var result = false;
                                if (seconds == 0) {
                                    if (minutes == 0) {
                                        result = "[EXPIRED]";
                                        window.clearInterval(countdown);
                                    } else {
                                        minutes -= 1;
                                    }
                                    seconds = 59;
                                } else {
                                    seconds -= 1;
                                }
                                if (!result) {
                                    if (hours == 0) {
                                        result = minutes;
                                    } else {
                                        result = hours + "h:" + minpad(minutes % 60);
                                    }
                                    result += "m:" + minpad(seconds) + "s";
                                }
                                $(".countdown").text(result);
                                countdown = window.setTimeout(function () {
                                    incrementtime()
                                }, 1000);
                            }

                            function minpad(time) {
                                if (time < 10) {
                                    return "0" + time;
                                }
                                return time;
                            }

                            function setPublishableKey(Key, mode) {
                                try {
                                    Stripe.setPublishableKey(Key);
                                    @if(!islive())
                                        log(mode + " stripe mode");
                                    @endif
                                } catch (error) {
                                    log("Stripe not available on this page");
                                }
                            }
                        @endif

                        function scrolltotop(){
                            $('html,body').animate({scrollTop: 0}, "slow");
                        }
                        function scrolltobottom() {
                            $('html,body').animate({scrollTop: document.body.scrollHeight}, "slow");
                        }

                        /* checkout */
                        @if(read("id"))
                            $(document).ready(function () {
                                    getcloseststore = true;
                                    visible_address(false);
                                    $("#saveaddresses").append('<OPTION VALUE="addaddress" ID="addaddress">Add Address</OPTION>');
                                    $(".credit-info").on('keyup', function () {
                                        changecredit();
                                        if (isvalidcreditcard()) {
                                            $(".payment-errors").text("");
                                        }
                                    });
                                });
                            $('#reg_phone').keypress(function () {
                                if ($('#reg_phone').valid()) {
                                    clearphone();
                                }
                            });
                        @endif

                        var shortitems = [];
                        function restchange() {
                            var value = $("#restaurant").val();
                            var index = findwhere(closest, "restid", value);
                            if (value == 0) {
                                $("#red_rest").addClass("redhighlite");
                            } else {
                                $("#red_rest").removeClass("redhighlite");
                            }
                            if (closest.length > 0) {
                                GenerateHours(closest[index].hours);
                                shortitems = CheckforShortage(closest[index].shortage);
                                alertshortage();
                            }
                        }

                        function alertshortage() {
                            if (shortitems.length) {
                                var otherstores = " or select a different restaurant to continue";
                                if (closest.length == 1) {
                                    otherstores = "";
                                }
                                alert("Sorry, but this restaurant is currently out of:<BR><UL><LI>" + shortitems.join("</LI><LI>") + "</LI></UL><BR>Please remove them from your order" + otherstores, "Product Shortage");
                                return true;
                            }
                            return false;
                        }

                        function CheckforShortage(shortage) {
                            var shortitems = [];
                            for (var i = 0; i < theorder.length; i++) {
                                if (isShort(shortage, "menu", theorder[i].itemid)) {
                                    shortitems.push(theorder[i].itemname);
                                }
                                if (theorder[i].hasOwnProperty("itemaddons")) {
                                    for (var subitem = 0; subitem < theorder[i].itemaddons.length; subitem++) {
                                        var addons = theorder[i].itemaddons[subitem].addons;
                                        var tablename = theorder[i].itemaddons[subitem].tablename;
                                        for (var addon = 0; addon < addons.length; addon++) {
                                            if (isShort(shortage, tablename, addons[addon].text)) {
                                                shortitems.push("'" + addons[addon].text + "' for the '" + theorder[i].itemname + "'");
                                            }
                                        }
                                    }
                                }
                            }
                            return shortitems;
                        }

                        function isShort(shortage, tablename, ID) {
                            if (tablename != "menu") {
                                ID = getKeyByValue(alladdons[tablename + "_id"], ID);
                            }
                            for (var i = 0; i < shortage.length; i++) {
                                if (shortage[i].item_id == ID && shortage[i].tablename == tablename) {
                                    return true;
                                }
                            }
                            return false;
                        }

                        function fffa() {
                            $("#ffaddress").text($("#formatted_address").val());
                            $('#checkoutmodal').modal('show');
                            $("#firefoxandroid").hide();
                        }

                        var haschecked = false;
                        function orderinfocheck() {
                            if(haschecked){return;}
                            haschecked=true;
                            $('#orderinfo input').each(function () {
                                $(this).click(function () {
                                    refreshform(this)
                                }).blur(function () {
                                    refreshform(this)
                                });
                                log("Autored: " + refreshform(this).attr("id"));
                            });
                        }

                        function refreshform(t) {
                            var ID = t;
                            if (!$(t).is(":visible")) {
                                return $(ID);
                            }
                            var ActualID = $(t).attr("id");
                            var value = $(t).val();
                            var tagname = $(t).prop("tagName").toUpperCase();
                            if (tagname == "SELECT" && value == 0) {
                                value = false;
                            }
                            switch (tagname + "." + ActualID) {
                                case "SELECT.saveaddresses":
                                    if (value == "addaddress") {
                                        value = false;
                                    }
                                    break;
                            }
                            var classname = "red";
                            if ($(t).hasAttr("autored")) {
                                ID = "#" + $(t).attr("autored").replaceAll('"', "");
                                classname = "redhighlite";
                            }
                            if ($(t).hasAttr("autored") || $(t).hasClass("autored")) {
                                if (value) {
                                    $(ID).removeClass(classname);
                                } else {
                                    value = "[EMPTY]";
                                    $(ID).addClass(classname);
                                }
                                log(tagname + "." + ActualID + " Autored value: " + value);
                            }
                            return $(ID);
                        }
                        /* end checkout */

                        //NProgress.start(); NProgress.set(0.4); NProgress.inc(); NProgress.done(); http://ricostacruz.com/nprogress/
                        ;(function(root,factory){if(typeof define==='function'&&define.amd){define(factory);}else if(typeof exports==='object'){module.exports=factory();}else{root.NProgress=factory();}})(this,function(){var NProgress={};NProgress.version='0.2.0';var Settings=NProgress.settings={minimum:0.08,easing:'ease',positionUsing:'',speed:200,trickle:true,trickleRate:0.02,trickleSpeed:800,showSpinner:true,barSelector:'[role="bar"]',spinnerSelector:'[role="spinner"]',parent:'body',template:'<div class="bar" role="bar"><div class="peg"></div></div><div class="spinner" role="spinner"><div class="spinner-icon"></div></div>'};NProgress.configure=function(options){var key,value;for(key in options){value=options[key];if(value!==undefined&&options.hasOwnProperty(key))Settings[key]=value;}
                            return this;};NProgress.status=null;NProgress.set=function(n){var started=NProgress.isStarted();n=clamp(n,Settings.minimum,1);NProgress.status=(n===1?null:n);var progress=NProgress.render(!started),bar=progress.querySelector(Settings.barSelector),speed=Settings.speed,ease=Settings.easing;progress.offsetWidth;queue(function(next){if(Settings.positionUsing==='')Settings.positionUsing=NProgress.getPositioningCSS();css(bar,barPositionCSS(n,speed,ease));if(n===1){css(progress,{transition:'none',opacity:1});progress.offsetWidth;setTimeout(function(){css(progress,{transition:'all '+ speed+'ms linear',opacity:0});setTimeout(function(){NProgress.remove();next();},speed);},speed);}else{setTimeout(next,speed);}});return this;};NProgress.isStarted=function(){return typeof NProgress.status==='number';};NProgress.start=function(){$("#loading").show();if(!NProgress.status)NProgress.set(0);var work=function(){setTimeout(function(){if(!NProgress.status)return;NProgress.trickle();work();},Settings.trickleSpeed);};if(Settings.trickle)work();return this;};NProgress.done=function(force){$("#loading").hide();if(!force&&!NProgress.status)return this;return NProgress.inc(0.3+ 0.5*Math.random()).set(1);};NProgress.inc=function(amount){var n=NProgress.status;if(!n){return NProgress.start();}else{if(typeof amount!=='number'){amount=(1- n)*clamp(Math.random()*n,0.1,0.95);}
                            n=clamp(n+ amount,0,0.994);return NProgress.set(n);}};NProgress.trickle=function(){return NProgress.inc(Math.random()*Settings.trickleRate);};(function(){var initial=0,current=0;NProgress.promise=function($promise){if(!$promise||$promise.state()==="resolved"){return this;}
                            if(current===0){NProgress.start();}
                            initial++;current++;$promise.always(function(){current--;if(current===0){initial=0;NProgress.done();}else{NProgress.set((initial- current)/ initial);
                            }});return this;};})();NProgress.render=function(fromStart){if(NProgress.isRendered())return document.getElementById('nprogress');addClass(document.documentElement,'nprogress-busy');var progress=document.createElement('div');progress.id='nprogress';progress.innerHTML=Settings.template;var bar=progress.querySelector(Settings.barSelector),perc=fromStart?'-100':toBarPerc(NProgress.status||0),parent=document.querySelector(Settings.parent),spinner;css(bar,{transition:'all 0 linear',transform:'translate3d('+ perc+'%,0,0)'});if(!Settings.showSpinner){spinner=progress.querySelector(Settings.spinnerSelector);spinner&&removeElement(spinner);}
                            if(parent!=document.body){addClass(parent,'nprogress-custom-parent');}
                            parent.appendChild(progress);return progress;};NProgress.remove=function(){removeClass(document.documentElement,'nprogress-busy');removeClass(document.querySelector(Settings.parent),'nprogress-custom-parent');var progress=document.getElementById('nprogress');progress&&removeElement(progress);};NProgress.isRendered=function(){return!!document.getElementById('nprogress');};NProgress.getPositioningCSS=function(){var bodyStyle=document.body.style;var vendorPrefix=('WebkitTransform'in bodyStyle)?'Webkit':('MozTransform'in bodyStyle)?'Moz':('msTransform'in bodyStyle)?'ms':('OTransform'in bodyStyle)?'O':'';if(vendorPrefix+'Perspective'in bodyStyle){return'translate3d';}else if(vendorPrefix+'Transform'in bodyStyle){return'translate';}else{return'margin';}};function clamp(n,min,max){if(n<min)return min;if(n>max)return max;return n;}
                            function toBarPerc(n){return(-1+ n)*100;}
                            function barPositionCSS(n,speed,ease){var barCSS;if(Settings.positionUsing==='translate3d'){barCSS={transform:'translate3d('+toBarPerc(n)+'%,0,0)'};}else if(Settings.positionUsing==='translate'){barCSS={transform:'translate('+toBarPerc(n)+'%,0)'};}else{barCSS={'margin-left':toBarPerc(n)+'%'};}
                                barCSS.transition='all '+speed+'ms '+ease;return barCSS;}
                            var queue=(function(){var pending=[];function next(){var fn=pending.shift();if(fn){fn(next);}}
                                return function(fn){pending.push(fn);if(pending.length==1)next();};})();var css=(function(){var cssPrefixes=['Webkit','O','Moz','ms'],cssProps={};function camelCase(string){return string.replace(/^-ms-/,'ms-').replace(/-([\da-z])/gi,function(match,letter){return letter.toUpperCase();});}
                                function getVendorProp(name){var style=document.body.style;if(name in style)return name;var i=cssPrefixes.length,capName=name.charAt(0).toUpperCase()+ name.slice(1),vendorName;while(i--){vendorName=cssPrefixes[i]+ capName;if(vendorName in style)return vendorName;}
                                    return name;}
                                function getStyleProp(name){name=camelCase(name);return cssProps[name]||(cssProps[name]=getVendorProp(name));}
                                function applyCss(element,prop,value){prop=getStyleProp(prop);element.style[prop]=value;}
                                return function(element,properties){var args=arguments,prop,value;if(args.length==2){for(prop in properties){value=properties[prop];if(value!==undefined&&properties.hasOwnProperty(prop))applyCss(element,prop,value);}}else{applyCss(element,args[1],args[2]);}}})();function hasClass(element,name){var list=typeof element=='string'?element:classList(element);return list.indexOf(' '+ name+' ')>=0;}
                            function addClass(element,name){var oldList=classList(element),newList=oldList+ name;if(hasClass(oldList,name))return;element.className=newList.substring(1);}
                            function removeClass(element,name){var oldList=classList(element),newList;if(!hasClass(element,name))return;newList=oldList.replace(' '+ name+' ',' ');element.className=newList.substring(1,newList.length- 1);}
                            function classList(element){return(' '+(element.className||'')+' ').replace(/\s+/gi,' ');}
                            function removeElement(element){element&&element.parentNode&&element.parentNode.removeChild(element);}
                            return NProgress;});

                        function loading(state, where) {
                            if (state) {
                                log("loading start");
                                NProgress.start();
                            } else {
                                log("loading end");
                                NProgress.done();
                            }
                        }
                        loading(true, "page");

                        $.ajaxSetup({ xhr: function () {
                            var xhr = new window.XMLHttpRequest();
                            xhr.upload.addEventListener("progress", function(evt) {
                                if (evt.lengthComputable) {
                                    var percentComplete = evt.loaded / evt.total * 0.9;//stop event will handle 100%
                                    NProgress.set(percentComplete);
                                }
                            }, false);

                            xhr.addEventListener("progress", function(evt) {
                                if (evt.lengthComputable) {
                                    var percentComplete = evt.loaded / evt.total * 0.9;//stop event will handle 100%
                                    NProgress.set(percentComplete);
                                }
                            }, false);
                            return xhr;
                        } });

                        function checkblock(e) {
                            var checked = $(e.target).is(':checked');
                            BeforeUnload(checked);
                        }
                        function BeforeUnload(enable) {
                            if (enable) {
                                window.onbeforeunload = function (e) {
                                    return "Discard changes?";
                                };
                                log("Page transitions blocked");
                            } else {
                                window.onbeforeunload = null;
                                log("Page transitions allowed");
                            }
                        }
                    </SCRIPT>

                    <div class="modal z-index-9999" id="alertmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2 class="modal-title" id="alertmodallabel">Title</h2>
                                    <button data-dismiss="modal" class="btn btn-sm ml-auto bg-transparent align-middle"><i class="fa fa-close"></i></button>
                                </div>
                                <div class="modal-body">
                                    <DIV ID="alertmodalbody"></DIV>
                                    <div CLASS="pull-right">
                                        <button class="btn btn-link text-muted" id="alert-cancel" data-dismiss="modal">
                                            CANCEL
                                        </button>
                                        <button class="btn btn-link" id="alert-confirm" data-dismiss="modal">
                                            OK
                                        </button>
                                    </div>
                                    <DIV CLASS="clearfix"></DIV>
                                </div>
                            </div>
                        </div>
                    </DIV>

                    <?php
            break;
        case "address":
                    if (!isset($style))     {$style = 0;}
                    if (!isset($firefox))   {$firefox = true;}
                    if (!isset($required))  {$required = "";} else {$required = " required";}
                    if (!isset($class))     {$class = "";} else {$class = " " . $class;}
                    if (!isset($icons))     {$icons = false;}
                    if (isset($autored))    {$required .= ' autored="' . $autored . '"';}

                    switch ($style) {
                        case 0:
                            echo '<DIV CLASS="row"><DIV CLASS="col-md-2">Address</DIV><DIV CLASS="col-md-10" ID="gmapc">';
                            echo '<INPUT class="form-control" TYPE="text" ID="formatted_address" ' . $required . ' name="formatted_address"></div></DIV>';
                            break;
                        case 1:
                            if($icons) {echo '<div class="input_left_icon"><span class="fa-stack fa-2x"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-home text-white fa-stack-1x"></i></span></div><div class="input_right">';}
                            echo '<SPAN ID="gmapc"><INPUT TYPE="text" ID="formatted_address" PLACEHOLDER="Start by Typing Address" CLASS="form-control formatted_address' . $class . '"' . $required . ' name="formatted_address"></SPAN>';
                            if($icons) {echo '</div>';}
                            echo '<STYLE>.address:focus{z-index: 999;}</STYLE>';
                            break;
                    }
                    if (!isset($user_id)) {$user_id = read("id");}
                    if (!isset($form)) {$form = true;}
                ?>
                        <STYLE>
                            .pac-container {
                                z-index: 99999999999 !important;
                            }
                        </STYLE>
                        @if($form) <FORM ID="googleaddress"> @endif
                        @if($icons) <div class="input_left_icon"><span class="fa-stack fa-2x"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-pencil text-white fa-stack-1x"></i></span></div><div class="input_right"> @endif
                        <INPUT TYPE="text" NAME="unit" ID="add_unit" PLACEHOLDER="Address Notes" CLASS="form-control address" TITLE="ie: Apt/Unit, buzz code, which door to go to">
                        @if($icons) </div> @endif
                        <INPUT TYPE="text" NAME="number" ID="add_number" PLACEHOLDER="Street Number" {{ $required }} CLASS="form-control street_number address dont-show">
                        <INPUT TYPE="text" NAME="street" ID="add_street" PLACEHOLDER="Street" {{ $required }} CLASS="form-control route address dont-show">
                        <INPUT TYPE="text" NAME="city" ID="add_city" PLACEHOLDER="City" {{ $required }} CLASS="form-control locality address dont-show">
                        <INPUT TYPE="text" NAME="province" ID="add_province" PLACEHOLDER="Province" {{ $required }} CLASS="form-control administrative_area_level_1 address dont-show">
                        <INPUT TYPE="text" NAME="postalcode" ID="add_postalcode" PLACEHOLDER="Postal Code" {{ $required }} CLASS="form-control postal_code address dont-show">
                        <INPUT TYPE="text" NAME="latitude" ID="add_latitude" PLACEHOLDER="Latitude" {{ $required }} CLASS="form-control latitude address dont-show">
                        <INPUT TYPE="text" NAME="longitude" ID="add_longitude" PLACEHOLDER="Longitude" {{ $required }} CLASS="form-control longitude address dont-show">
                        <INPUT TYPE="hidden" NAME="user_id" ID="add_user_id" PLACEHOLDER="user_id" {{ $required }} CLASS="form-control session_id_val address" value="{{$user_id}}">
                        @if($form) </FORM> @endif

                        <SCRIPT>
                            if(is_firefox_for_android) {
                                $(window).load(function () {
                                    var HTML = $("#gmapc").html();
                                    HTML = HTML.replaceAll("style=", "oldstyle=");
                                    log("Moving: " + HTML);
                                    $("#gmapffac").html(HTML);
                                    $("#gmapc").html('<DIV CLASS="fake-form-control"><SPAN CLASS="address fake-address" ID="ffaddress"></SPAN><BUTTON CLASS="btn btn-sm btn-primary radius0 pull-right full-height" ONCLICK="handlefirefox();return false;">EDIT</BUTTON></DIV><DIV CLASS="separator"></DIV>');
                                    initAutocomplete();
                                });
                            }

                            function editaddresses() {
                                $("#checkoutmodal").modal("hide");
                                $("#profilemodal").modal("show");
                            }

                            function isnewaddress(number, street, city) {
                                var AddNew = number && street && city;
                                $("#saveaddresses option").each(function () {
                                    var ID = $(this).val();
                                    if (ID > 0) {
                                        if (number.isEqual($(this).attr("number")) && street.isEqual($(this).attr("street")) && city.isEqual($(this).attr("city"))) {
                                            return false;
                                        }
                                    }
                                });
                                return AddNew;
                            }

                            function deleteaddress(ID) {
                                if (ID < 0) {//add new address
                                    var address = getform("#orderinfo");
                                    $.post(webroot + "placeorder", {
                                        _token: token,
                                        info: address
                                    }, function (result) {
                                        address["id"] = result;
                                        var HTML = AddressToOption(address);
                                        $(".saveaddresses").append(HTML);
                                        if (ID == -1) {
                                            addresses();
                                        }
                                    });
                                } else {
                                    confirm2("Are you sure you want to delete '" + $("#add_" + ID).text().trim() + "'?", 'Delete Address', function () {
                                        $.post("<?= webroot("public/list/useraddresses"); ?>", {
                                            _token: token,
                                            action: "deleteitem",
                                            id: ID
                                        }, function (result) {
                                            if (handleresult(result)) {
                                                $("#add_" + ID).fadeOut(500, function () {
                                                    $("#add_" + ID).remove();
                                                });
                                                $(".saveaddresses option[value=" + ID + "]").remove();
                                            }
                                        });
                                    });
                                }
                            }

                            function initAutocomplete() {
                                var cityBounds = new google.maps.LatLngBounds(
                                        new google.maps.LatLng(42.873863, -81.501312),//southWest
                                        new google.maps.LatLng(43.043212, -81.092071)//northEast
                                );//london ontario boundaries

                                formatted_address = new google.maps.places.Autocomplete(
                                        /** @type {!HTMLInputElement} */(document.getElementById('formatted_address')), {
                                            bounds: cityBounds,//limit to London Ontario
                                            types: ['geocode'],
                                            componentRestrictions: {country: "ca"}
                                        });
                                formatted_address.addListener('place_changed', fillInAddress);
                            }

                            function fillInAddress() {
                                // Get the place details from the formatted_address object.
                                var place = formatted_address.getPlace();
                                log(JSON.stringify(place));
                                var lat = place.geometry.location.lat();
                                var lng = place.geometry.location.lng();

                                var addressdata = {};

                                $('.formatted_fordb').val(place.formatted_address); // this formatted_address is a google maps object
                                $('.latitude').val(lat);
                                $('.longitude').val(lng);

                                var componentForm = {
                                    street_number: 'short_name',
                                    //route: 'long_name',//street name
                                    route: 'short_name',//street name
                                    locality: 'long_name',//ON Canada
                                    administrative_area_level_1: 'long_name',
                                    country: 'long_name',
                                    postal_code: 'short_name'
                                };
                                //2396 Kingsway, locality: Vancouver, administrative_area_level_1: British Columbia, country: Canada, postal_code: V5R 5G9
                                var streetformat = "[street_number] [route], [locality], [administrative_area_level_1_s]";// [postal_code]";
                                for (var i = 0; i < place.address_components.length; i++) {
                                    var addressType = place.address_components[i].types[0];
                                    if (componentForm[addressType]) {
                                        var val = place.address_components[i][componentForm[addressType]];
                                        addressdata[addressType] = val;
                                        streetformat = streetformat.replace("[" + addressType + "]", val);
                                        $('.' + addressType).val(val);

                                        val = place.address_components[i]['short_name'];
                                        streetformat = streetformat.replace("[" + addressType + "_s]", val);
                                        val = place.address_components[i]['long_name'];
                                        streetformat = streetformat.replace("[" + addressType + "_l]", val);
                                    }
                                }
                                if (isnewaddress(addressdata["street_number"], addressdata["route"], addressdata["locality"])) {
                                    $("#saveaddressbtn").removeAttr("disabled");
                                } else {
                                    $("#saveaddressbtn").attr("disabled", true);
                                }
                                $('.formatted_address').val(streetformat);
                                place.formatted_address = streetformat;
                                @if(isset($findclosest))
                                    if (isFunction(addresshaschanged)) {
                                    addresshaschanged(place);
                                }
                                @endif
                                return place;
                            }
                        </SCRIPT>
                        <?php
                            if (!isset($dontincludeGoogle)) {
                                echo '<script src="https://maps.googleapis.com/maps/api/js?signed_in=true&libraries=places&callback=initAutocomplete&key=AIzaSyBWSUc8EbZYVKF37jWVCb3lpBQwWqXUZw8"></script>';
                            } else {
                        ?>
                        <SCRIPT LANGUAGE="JavaScript">
                            window.onload = function () {
                                log("init autocomplete");
                                initAutocomplete();
                            };
                        </SCRIPT>
                        <?php
                    }
            break;
        default:
            echo view("popups_" . $popup, get_defined_vars())->render();
    }
    endfile("popups_" . $popup);
?>