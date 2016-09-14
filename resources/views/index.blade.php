@extends('layouts.app')
@section('content')
    <style>
        .select2-results {
            max-height: 500px;
        }

        .menuitem.disabled {
            display: none;
            visibility: hidden;
        }

        .menuitem.disabled:hover {
            text-decoration: none !important;
        }

        .select2-container-multi .select2-choices .select2-search-field input {
            margin: 0 !important;
        }

        input {
            border-top: 0 !important;
        }

        .list-group-item {
            padding: 0rem;
        }

        a {
            text-decoration: none !important;
            cursor: pointer !important;
        }

        * {
            box-shadow: none !important;
            border-radius: 0 !important;
        }

        .clearfix {
            clear: both !important;
        }

        .select2-container-multi .select2-choices {
            background-image: none;
            border: 1px solid rgba(0, 0, 0, .15);
            color: #55595c;
            background-color: #fff;
            -webkit-background-clip: padding-box;
        }

        .select2-container-multi .select2-choices .select2-search-choice {
            color: #555555;
            background: white;
        }

        .select2 {
            border: 0 !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Roboto Slab', serif;
            font-weight: 600;
0        }

        a {
            color: #373a3c;
        }

        body {
            font-family: 'Roboto', serif;
        }

        .card {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .26), 0 5px 10px 0 rgba(0, 0, 0, .12) !important;
        }

        @media (max-width: 978px) {
            .modal-dialog {
                padding: 0;
                margin: 0;
            }
        }


    </style>
    <div class="row">
        <div class="col-md-8">
            <div class="card ">
                <div class="card-block bg-danger" style="padding-top:.75rem !important;padding-bottom:.75rem !important;">
                    <h4 class="pull-left"><i class="fa fa-home" aria-hidden="true"></i> Pizza Delivery<input type="TEXT" id="search" class="searchbox" placeholder="Search" oninput="search(this, event);"></h4>
                </div>
                <div class="card-block">
                    <div class="row">
                        <div class="col-md-4">
                            <?php
                            $tables = array("toppings", "wings_sauce");
                            $qualifiers = array("DEFAULT" => array("1/2", "1x", "2x", "3x"));
                            $categories = Query("SELECT * FROM menu GROUP BY category ORDER BY id", true);
                            $isfree = collapsearray(Query("SELECT * FROM additional_toppings", true), "price", "size");
                            $a = 0;

                            function getsize($itemname, &$isfree){
                                $currentsize = "";
                                foreach($isfree as $size => $cost){
                                    if(!is_array($cost)){
                                        if( textcontains($itemname, $size) && strlen($size) > strlen($currentsize)){
                                            $currentsize = $size;
                                        }
                                    }
                                }
                                return $currentsize;
                            }

                            function textcontains($text, $searchfor){
                                return strpos(strtolower($text), strtolower($searchfor)) !== false;
                            }

                            function getaddons($Table, &$isfree, &$qualifiers){
                                $toppings = Query("SELECT * FROM " . $Table . " ORDER BY type ASC, name ASC", true);
                                $toppings_display = '';
                                $currentsection = "";
                                $isfree[$Table] = array();
                                foreach ($toppings as $ID => $topping) {
                                    if($currentsection != $topping["type"]){
                                        if($toppings_display){$toppings_display .= '</optgroup>';}
                                        $toppings_display .= '<optgroup label="' . $topping["type"] . '">';
                                        $currentsection = $topping["type"];
                                    }

                                    $addons[$Table][$topping["type"]][$topping["name"]] = explodetrim($topping["qualifiers"]);
                                    $topping["displayname"] = $topping["name"];
                                    if($topping["isfree"]){
                                        $isfree[$Table][] = $topping["name"];
                                        $topping["displayname"] .= " (free)";
                                    }
                                    if($topping["qualifiers"]){
                                        $qualifiers[$Table][$topping["name"]] = explodetrim($topping["qualifiers"]);
                                    }
                                    if($topping["isall"]){
                                        $isfree["isall"][$Table][] = $topping["name"];
                                    }
                                    $toppings_display .= '<option value="' . $topping["id"] . '" type="' . $topping["type"] . '">' . $topping["displayname"] . '</option>';
                                }
                                return $toppings_display . '</optgroup>';
                            }

                            function explodetrim($text, $delimiter = ",", $dotrim = true){
                                if(is_array($text)){return $text;}
                                $text = explode($delimiter, $text);
                                if(!$dotrim){return $text;}
                                foreach($text as $ID => $Word){
                                    $text[$ID] = trim($Word);
                                }
                                return $text;
                            }

                            $toppings_display = getaddons("toppings", $isfree, $qualifiers);
                            $wings_display = getaddons("wings_sauce", $isfree, $qualifiers);

                            foreach ($categories as $category) {
                            if($a == 3) {
                                $a = 0;
                                ?>
                                </div>
                                <div class="col-md-4">
                            <? } ?>

                            <h5 class="text-danger" data-toggle="collapse" href="#collapse{{$category["id"]}}_cat">{{$category['category']}}</h5>
                            <div class="collapse list-group in m-b-1" id="collapse{{$category['id']}}_cat">
                                <?
                                $menuitems = Query("SELECT * FROM menu WHERE category = '" . $category['category'] . "'", true);
                                foreach ($menuitems as $menuitem) {
                                    $menuitem["price"] = number_format($menuitem["price"], 2);
                                    ?>
                                    <div class="clearfix"></div>
                                    <div class="menuitem" itemid="{{$menuitem["id"]}}" itemname="{{$menuitem['item']}}" itemprice="{{$menuitem['price']}}" itemsize="{{ getsize($menuitem['item'], $isfree) }}"                                           <?php
                                            $total = 0;
                                            foreach($tables as $table){
                                                echo $table . '="' . $menuitem[$table] . '" ';
                                                $total += $menuitem[$table];
                                            }
                                            if($total){
                                                $HTML = 'data-toggle="modal" data-target="#menumodal" onclick="loadmodal(this);"';
                                            } else {
                                                $HTML = 'onclick="additemtoorder(this);"';
                                            }
                                        ?>
                                    >
                                        <a class="text-xs-left btn-block" <?= $HTML; ?> >
                                            <i class="pull-left fa fa-pie-chart text-warning"></i>
                                            <span class="pull-left itemname">{{$menuitem['item']}}</span>
                                            <span class="pull-right"> ${{$menuitem['price']}}</span>
                                            <div class="clearfix"></div>
                                        </a>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <?
                            $a++;
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-block bg-danger"
                     style="padding-top:.75rem !important;padding-bottom:.75rem !important;">
                    <h4 class="pull-left"> My Order <label class="m-y-0">$45.66</label></h4>
                    <h4 class="pull-right"><i class="fa fa-user no-padding-margin"></i></h4>
                </div>
                <div class="card-block">

                    <div>
                        <span class="pull-left"> <i class="fa fa-pie-chart text-warning"></i> 2 Small Pizza</span>
                        <span class="pull-right"> $9.99 <i class="fa fa-close"></i></span>
                        <div class="clearfix"></div>

                        Pizza 1st: Ham, Bacon<br>
                        Pizza 2nd: Extra Cheese, Bacon<br>
                        <span class="pull-left"> <i class="fa fa-pie-chart text-warning"></i>  2 lbs Wings </span>
                        <span class="pull-right"> $9.99 <i class="fa fa-pencil"></i><i class="fa fa-close"></i></span>
                        <div class="clearfix"></div>
                        1st lb - hot
                        <br>
                        2nd lb - mild
                        <br>
                        <br>
                        <span class="pull-right"> <strong>Subtotal: $34.99</strong></span><br>
                        <span class="pull-right"> <strong>HST: $4.99</strong></span><br>
                        <span class="pull-right"> <strong>Delivery: $4.99</strong></span><br>
                        <span class="pull-right"> <strong>Total: $44.99</strong></span>

                    </div>
                    <div class="clearfix p-t-1"></div>
                    <button data-toggle="collapse" class="btn btn-block btn-warning"
                            href="#collapseCheckout">CHECKOUT
                    </button>
                    <div class="collapse" id="collapseCheckout">
                        <input type="text" class="form-control" placeholder="name"/>
                        <input type="text" class="form-control" placeholder="address"/>
                        <input type="text" class="form-control" placeholder="email"/>
                        <input type="text" class="form-control" placeholder="cell"/>
                        <input type="text" class="form-control" placeholder="delivery time"/>
                        <input type="text" class="form-control" placeholder="Restaurant select"/>
                        <input type="text" class="form-control" placeholder="payment"/>
                        <input type="text" class="form-control" placeholder="notes"/>
                        <button class="btn btn-warning btn-block">PLACE ORDER</button>
                    </div>

                </div>
            </div>
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
                        <h4 class="modal-title" id="myModalLabel"><SPAN ID="modal-itemname"></SPAN> $<SPAN ID="modal-itemprice"></SPAN></h4>
                        <SPAN ID="modal-itemid" style="display:none;"></SPAN>
                        <SPAN ID="modal-toppingcost" style="display:none;"></SPAN>
                    </div>

                    <div class="form-group" ID="modal-wings-sauce">
                        <select class="form-control select2" multiple="multiple" data-placeholder="Wings Sauce" type="wings_sauce">
                            <option value="blank"></option>
                            <?= $wings_display; ?>
                            <optgroup label="Options">
                                <option value="AZ">Well Done</option>
                                <option value="CO">Lightly Done</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="form-group" ID="modal-toppings-original">
                        <DIV class="text-muted">Pizza #<span class="index">1</span></div>
                        <select class="form-control select2 toppings" data-placeholder="Add Toppings: $[price]" multiple="multiple" type="toppings">
                            <option value="blank"></option>
                            <?= $toppings_display; ?>
                            <optgroup label="Options">
                                <option value="AZ">Well Done</option>
                                <option value="CO">Lightly Done</option>
                            </optgroup>
                        </select>
                    </div>
                    <DIV ID="modal-toppings-clones"></DIV>

                    <button data-dismiss="modal" class="btn btn-block  btn-warning" onclick="additemtoorder();">
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
        var toppingsouterhtml;

        String.prototype.isEqual = function (str){
            if(isUndefined(str)){return false;}
            return this.toUpperCase()==str.toUpperCase();
        };
        function isUndefined(variable){
            return typeof variable === 'undefined';
        }
        function isArray(variable){
            return Array.isArray(variable);
        }
        String.prototype.contains = function (str){
            return this.toLowerCase().indexOf(str.toLowerCase()) > -1;
        };

        //replaces all instances of $search within a string with $replacement
        String.prototype.replaceAll = function (search, replacement) {
            var target = this;
            if(isArray(search)){
                for(var i=0; i<search.length; i++){
                    if(isArray(replacement)){
                        target = target.replaceAll( search[i], replacement[i] );
                    } else {
                        target = target.replaceAll( search[i], replacement );
                    }
                }
                return target;
            }
            return target.replace(new RegExp(search, 'g'), replacement);
        };

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
        }

        function loadmodal(element){
            element = $(element).parent();
            $("#modal-itemname").text($(element).attr("itemname"));
            $("#modal-itemprice").text($(element).attr("itemprice"));
            $("#modal-itemid").text($(element).attr("itemid"));

            var size = $(element).attr("itemsize");
            var toppingcost = 0.00;
            if(size){
                toppingcost = Number(freetoppings[size]).toFixed(2);
                $(".toppings").attr("data-placeholder", "Add Toppings: $" + toppingcost);
                $(".toppings_price").text(toppingcost);
            }
            $("#modal-toppingcost").text(toppingcost);

            initSelect2(".select2", true);

            visible("#modal-wings-sauce", $(element).attr("wings_sauce") > 0);
            sendintheclones("#modal-toppings-clones", "#modal-toppings-original", $(element).attr("toppings"), toppingsouterhtml.replace('[price]', toppingcost));
            initSelect2(".select2clones");
        }

        function initSelect2(selector, reset){
            if(!isUndefined(reset)){
                $('select').select2("val", null);
            }
            if(!isUndefined(selector)){
                $('select' + selector).select2({
                    maximumSelectionSize: 4,
                    placeholder: function () {$(this).data('placeholder');},
                    allowClear: true
                }).change();
            }
        }

        function visible(selector, status){
            if(isUndefined(status)){status = false;}
            if(status){
                $(selector).show();
            } else {
                $(selector).hide();
            }
        }

        function sendintheclones(destinationID, sourceID, count, sourceHTML){
            var HTML = "";
            visible(sourceID, count>0);
            if(count){
                if(isUndefined(sourceHTML)) {
                    var sourceHTML = outerHTML(sourceID).replace('form-control select2', 'form-control select2 select2clones');
                }
                for (var index=2; index<= count; index++){
                    HTML += sourceHTML.replace('<span class="index">1</span>', '<SPAN CLASS="index">' + index + '</SPAN>');
                }
            }
            $(destinationID).html(HTML);
        }

        function outerHTML(selector){
            return $(selector)[0].outerHTML;
        }

        function additemtoorder(element){
            var itemid = 0, itemname = "", itemprice = 0.00, itemaddons = new Array, itemtype = "";
            if(isUndefined(element)){//modal with addons
                itemtype = "modal";
                itemid = $("#modal-itemid").text();
                itemname = $("#modal-itemname").text();
                itemprice = $("#modal-itemprice").text();
                itemaddons = getaddons();
            } else {//direct link, no addons
                itemtype = "direct";
                element = $(element).parent();
                itemid = $(element).attr("itemid");
                itemname = $(element).attr("itemname");
                itemprice = $(element).attr("itemprice");
            }
            alert(itemid + " " + itemtype + ": " + itemname + " = $" + itemprice + " - " + JSON.stringify(itemaddons));
        }

        function getaddons(){
            var itemaddons = new Array;
            for(var tableid = 0; tableid < tables.length; tableid++){
                var table = tables[tableid];
                console.log("TESTING: " + table);
                $('.select2.' + table).each(function(index){
                    if(!$(this).hasClass("select2-offscreen")) {
                        var addons = $(this).select2('data');
                        for(var addid = 0; addid < addons.length; addid++){
                            delete addons[addid]["element"];
                            delete addons[addid]["locked"];
                            delete addons[addid]["disabled"];
                            addons[addid]["isfree"] = isaddon_free(table, addons[addid]["text"]);
                        }
                        itemaddons.push({tablename: table, addons: addons});
                    }
                });
            }
            return itemaddons;
        }

        function getsize(Itemname) {
            var sizes = Object.keys(freetoppings);
            var size = "";
            for(var i=0; i<sizes.length; i++){
                if(!isArray(freetoppings[sizes[i]])){
                    if(Itemname.contains(sizes[i]) && sizes[i].length > size.length){
                        size = sizes[i];
                    }
                }
            }
            return size;
        }

        function isaddon_free(Table, Addon){
            return freetoppings[Table].indexOf(Addon) > -1;
        }
        function isaddon_onall(Table, Addon){
            return freetoppings["isall"][Table].indexOf(Addon) > -1;
        }

        $(document).ready(function() {
            toppingsouterhtml = outerHTML("#modal-toppings-original").replace('form-control select2', 'form-control select2 select2clones');
        });
    </script>

@endsection