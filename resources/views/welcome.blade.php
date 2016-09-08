@extends('layouts.app')
@section('content')

    <style>
        * {
            BORDER-RADIUS: 0 !important;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Roboto Slab', serif;
            font-weight: 600;
        }

        a {
            color: #373a3c;
        }

        body {
            font-family: 'Roboto', serif;
        }

        button,.card {
            box-shadow: 0 2px 5px 0 rgba(0, 0, 0, .36), 0 5px 10px 0 rgba(0, 0, 0, .22);
            border:0 !important;
        }

        .searchbox {
            position: absolute;
            right: 13px;
            top: 13px;
        }

        .menuitem.disabled{
            color: lightgray;
            cursor: default;
        }

        .menuitem.disabled:hover{
            text-decoration: none !important;
        }

        .addtoorder{
            margin-top:.5rem;margin-bottom:.5rem;
        }
        .addtoorder:before{
            content: "ADD TO ORDER";
        }

        input[type=text][list]{
            width: 100%;
            height: 23px;
            position: relative;
            top: -1px;
        }

        .plusbtn{
            width: 100%;
            height: 23px;
            padding-top: 0px;
        }

        hr{
            margin-top: 2px;
            margin-bottom: 2px;
        }

        .invalid{
            outline: none;
            border-color: red;
            box-shadow: 0 0 10px red;
        }

        select{
            width: 100%;
        }
    </style>


    <div class="row m-t-1">
        <div class="col-md-8">
            <div class="card">
                <div class="card-block bg-danger" style="padding-top:.75rem !important;padding-bottom:.75rem !important;">

                <h4 class="pull-left">
                    <i class="fa fa-home" aria-hidden="true"></i> Pizza Delivery
                    <INPUT TYPE="TEXT" ID="search" CLASS="searchbox" placeholder="Search" oninput="search(this, event);">
                </h4>

                </div>
                <div class="card-block">
                    <div class="row">
                        <div class="col-md-4">
                            <?php
                                $tables = array("toppings", "wings_sauce");
                                $qualifiers = array("DEFAULT" => array("1/2", "1x", "2x", "3x"));
                                function makedatalist($name, $items){
                                    if(!is_array($items)){$items = explode(",", $items);}
                                    $ret = '<datalist id="' . $name . '">';
                                    foreach($items as $index => $item){
                                        $ret .= '<OPTION VALUE="' . $item . '"';
                                        if($index == 1){
                                            $ret .= ' SELECTED';
                                        }
                                        $ret .= '></OPTION>';
                                    }
                                    return $ret . '</datalist>';
                                }

                                function getaddons($Table, &$isfree, &$qualifiers){
                                    $toppings = Query("SELECT * FROM " . $Table . " ORDER BY name ASC", true);
                                    $toppings_display = '<datalist id="addons-' . $Table . '">';
                                    $isfree[$Table] = array();
                                    foreach ($toppings as $ID => $topping) {
                                        $rightside = "";
                                        $addons[$Table][$topping["type"]][$topping["name"]] = explodetrim($topping["qualifiers"]);
                                        if($topping["isfree"]){
                                            $isfree[$Table][] = $topping["name"];
                                            $rightside = "FREE";
                                        }
                                        if($topping["qualifiers"]){
                                            $qualifiers[$Table][$topping["name"]] = explodetrim($topping["qualifiers"]);
                                        }
                                        if($topping["isall"]){
                                            $isfree["isall"][$Table][] = $topping["name"];
                                        }
                                        $toppings_display .= '<option value="' . $topping["name"] . '" type="' . $topping["type"] . '" ID=' . $topping["id"] . '>' . $rightside . '</option>';
                                    }
                                    return $toppings_display . '</datalist>';
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

                                $isfree = collapsearray(Query("SELECT * FROM additional_toppings", true), "price", "size");
                                foreach($tables as $table){
                                    echo getaddons($table, $isfree, $qualifiers);
                                }

                                function addaddons(&$menuitem, $Table, $toppingcost, $qualifiers){
                                    $Cache = '<TABLE CLASS="alladdons"><TR class="firstitem"><TD WIDTH="50%"><input type="text" list="addons-' . $Table . '" class="addon" menuitem="' . $menuitem["id"] . '" table="' . $Table . '" oninput="addonchanged(this);"';
                                    $toppings_display = "";
                                    for ($i = 0; $i < $menuitem[$Table]; $i++) {
                                        if($i){$toppings_display .= '<HR>';}
                                        $toppings_display .= $Cache . ' item="' . $i . '" PLACEHOLDER="Item: ' . ($i+1) . " $" . number_format($toppingcost, 2) . '"></TD>';
                                        $toppings_display .= '<TD WIDTH="22%">' . printoptions("qualifiers", $qualifiers, $qualifiers[1], true) . '</TD>';
                                        $toppings_display .= '<TD WIDTH="22%">' . printoptions("sides", array("LEFT", "ALL", "RIGHT"), "ALL") . '</TD>';
                                        $toppings_display .= '<TD WIDTH="5%"><BUTTON CLASS="plusbtn" ONCLICK="additem(this);">+</BUTTON></TD></TR></TABLE>';
                                    }
                                    $menuitem[$Table] = $toppings_display;
                                }

                                function textcontains($text, $searchfor){
                                    return strpos(strtolower($text), strtolower($searchfor)) !== false;
                                }

                                function getsize($itemname, &$isfree){
                                    foreach($isfree as $size => $cost){
                                        if(!is_array($cost)){
                                            if( textcontains($itemname, $size) ){
                                                return $size;
                                            }
                                        }
                                    }
                                }

                                $con = connectdb("ai");
                                $categories = Query("select * from menu group by category order by id", true);
                                $a = 0;
                                foreach ($categories as $category) {
                                    if($a == 3){
                                        $a = 0;
                                        ?>
                                        </div>
                                        <div class="col-md-4">
                                    <? } ?>
                                    <h4 class="text-danger" data-toggle="collapse" href="#collapse{{$category["id"]}}_cat">{{$category['category']}}</h4>
                                    <div class="collapse in" id="collapse{{$category['id']}}_cat">
                                        <?
                                            $menuitems = Query("SELECT * FROM menu WHERE category = '" . $category['category'] . "'", true);
                                            foreach ($menuitems as $menuitem) {
                                                //these should not be inside the loop since they return the same results they should be cached
                                                $span = "";
                                                $size = getsize($menuitem['item'], $isfree);
                                                $toppingcost = 0;
                                                if($size){$toppingcost = $isfree[$size];}
                                                foreach($tables as $table){
                                                    $span .= $table . '=' . $menuitem[$table] . ' ';
                                                    addaddons($menuitem, $table, $toppingcost, $qualifiers["DEFAULT"]);
                                                }

                                                $keywords = Query("SELECT * FROM menukeywords WHERE menuitem_id = " . $menuitem["id"] . " OR -menuitem_id = " . $menuitem["category_id"], true);
                                                foreach ($keywords as $ID => $keyword) {
                                                    $keywords[$ID] = $keyword["keyword_id"];
                                                }
                                                $keywords = implode(",", $keywords);
                                                $menuitem["price"] = number_format($menuitem["price"], 2);
                                                ?>
                                                <SPAN CLASS="menuparent" menuitem="{{$menuitem["id"]}}" {{$span}} keywords="{{ $keywords }}">
                                                    <a class="text-xs-left clearfix menuitem" data-toggle="collapse" href="#collapse{{$menuitem["id"]}}">
                                                        <i class="fa fa-pie-chart text-warning"></i>
                                                        <SPAN CLASS="itemname">{{$menuitem['item']}}</SPAN>
                                                        <span class="pull-right itemcost"> ${{$menuitem['price']}}</span>
                                                    </a>

                                                    <div class="collapse addons" id="collapse{{$menuitem['id']}}">
                                                        <?
                                                            echo $menuitem['toppings'];
                                                            echo $menuitem['wings_sauce'];
                                                        ?>
                                                        <button class="btn btn-block btn-sm btn-warning addtoorder" data-toggle="collapse" href="#collapse{{$menuitem["id"]}}" onclick="addtoorder(this);"></button>
                                                    </div>
                                                </SPAN>
                                                <?php
                                            }
                                            $a++;
                                        ?>
                                    </div>
                                    <?
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-block bg-danger" style="padding-top:.75rem !important;padding-bottom:.75rem !important;">
                    <h4 class="pull-left">  My Order <label class="m-y-0">$45.66</label></h4>
                    <h4 class="pull-right"><i class="fa fa-user no-padding-margin"></i></h4>
                </div>
                <div class="card-block">

                    <div class="alert-info alert">
                        Your cart is empty
                    </div>
                    <div>
                        <span class="pull-left"><i class="fa fa-pie-chart text-warning"></i> 2 Small Pizza</span>
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
                        <span class="pull-left"> <i class="fa fa-pie-chart text-warning"></i> Ginger Ale </span>
                        <span class="pull-right"> $1.99 <i class="fa fa-close"></i></span>
                        <br>
                        <span class="pull-left"> <i class="fa fa-pie-chart text-warning"></i> Bottle Water </span>
                        <span class="pull-right"> $1.99 <i class="fa fa-close"></i></span>
                        <br>
                        <br>
                        <span class="pull-right"> <strong>Subtotal: $34.99</strong></span><br>
                        <span class="pull-right"> <strong>HST: $4.99</strong></span><br>
                        <span class="pull-right"> <strong>Delivery: $4.99</strong></span><br>
                        <span class="pull-right"> <strong>Total: $44.99</strong></span>

                    </div>

                    <button data-toggle="collapse" class="btn btn-block btn-warning" href="#collapseCheckout">CHECKOUT</button>
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

    <SCRIPT>
        var tables = <?= json_encode($tables); ?>;
        var freetoppings = <?= json_encode($isfree); ?>;
        var qualifiers = <?= json_encode($qualifiers); ?>;

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

        function search(element){
            var searchtext = element.value.toLowerCase();
            $(".menuitem").each(function( index ) {
                var ismatch = false;
                if(searchtext){
                    var itemtext = $(this).find(".itemname").text().toLowerCase();
                    if (itemtext.indexOf(searchtext) > -1){
                        ismatch = true;
                    }
                } else {
                    ismatch = true;
                }
                if(ismatch) {
                    $(this).removeClass("disabled");
                } else {
                    $(this).addClass("disabled");
                }
            });
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

        function addtoorder(element){
            var root = $(element).parent().parent();
            var menuitemroot = $(root).find(".menuitem");
            var toppingslist = new Array;
            var menuitem = $(root).attr("menuitem");
            var itemtext = $(menuitemroot).find(".itemname").text();
            var itemcost = Number($(menuitemroot).find(".itemcost").text().toLowerCase().trim().replace("$", ""));
            var toppings = 0;
            var size = getsize(itemtext);
            var addoncost = 0;
            if(size){
                addoncost = freetoppings[size];
            }

            $(root).find(".addons").find(".addon").each(function(index){
                var thiselement = $(this)[0];
                var parent = $(thiselement).parent().parent();
                var qualifier = $(parent).find("select[name=qualifiers]").val();
                if(qualifier == 0){qualifier = 0.5;}//half topping
                var sides = $(parent).find("select[name=sides]").val();
                if(list_hasoption(thiselement)) {
                    var itemid = $(thiselement).attr("item");
                    var table = $(thiselement).attr("table");
                    var isfree = isaddon_free(table, thiselement.value);
                    if(isUndefined(toppingslist[itemid])){toppingslist.push(new Array);}
                    toppingslist[itemid].push({
                        addon: thiselement.value,
                        table: table,
                        qualifier: qualifier,
                        sides: sides,
                        isfree: isfree
                    });
                    if(!isfree){
                        if(!sides.isEqual("ALL")){qualifier = qualifier * 0.5;}
                        toppings += Number(qualifier);
                    }
                }
            });
            toppings = Math.round(toppings);
            var totalcost = (Number(itemcost) + (toppings * Number(addoncost))).toFixed(2);

            var thisitem = {id: menuitem, quantity: 1, item: itemtext, basecost: itemcost, addon_list: toppingslist, addon_quantity: toppings, addon_cost: addoncost, size: size, totalcost: totalcost};

            alert(JSON.stringify(thisitem));

            $(".cloneitem").remove();
        }

        function addonchanged(element){
            var table = element.getAttribute("table");
            var addon = element.value;
            var optionexists = list_hasoption(element);
            var thequalifiers = qualifiers["DEFAULT"];
            if(optionexists) {
                if (qualifiers[table].hasOwnProperty(addon)) {
                    thequalifiers = qualifiers[table][addon];
                }
                var HTML = "";

                var select = $(element).parent().parent().find("select[name=qualifiers]");
                var selected = $(select).val();
                for(var i = 0; i < thequalifiers.length; i++){
                    HTML += '<OPTION VALUE="' + i + '"';
                    if(i == selected){HTML += ' SELECTED';}
                    HTML += '>' + thequalifiers[i] + '</OPTION>';
                }
                $(select).html(HTML);

                select = $(element).parent().parent().find("select[name=sides]");
                $(select).removeAttr("DISABLED");
                if(isaddon_onall(table, addon)){
                    $(select).attr("DISABLED", true);
                    $(select).val("ALL");
                }
            }
            $(element).removeClass("invalid");
            if(element.value && !optionexists && !$(element).hasClass("invalid")) {
                $(element).addClass("invalid");
            }
        }

        function additem(element){
            var row = $(element).parent().parent();
            var HTML = $(row)[0].outerHTML;
            HTML = HTML.replace('additem(this)', 'removeitem(this)').replace('>+<', '>-<').replace('"firstitem"', '"cloneitem"');
            $(row).after(HTML);
        }
        function removeitem(element){
            $(element).parent().parent().remove();
        }

        function list_hasoption(element, value){
            var datalist = element.list;
            if(isUndefined(value)){value = element.value;}
            for (var j = 0; j < datalist.options.length; j++) {
                if (value.isEqual(datalist.options[j].value)) {
                    return true;
                }
            }
            return false;
        }
    </SCRIPT>
@endsection