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
                                function getaddons($Table){
                                    $toppings = Query("SELECT * FROM " . $Table . " ORDER BY name ASC", true);
                                    $toppings_display = '<datalist id="addons-' . $Table . '">';
                                    foreach ($toppings as $ID => $topping) {
                                        $toppings_display .= '<option value="' . $topping["name"] . '" type="' . $topping["type"] . '">' . $topping["id"] . '</option>';
                                    }
                                    return $toppings_display . '</datalist>';
                                }
                                echo getaddons("toppings");
                                echo getaddons("wings_sauce");

                                function addaddons(&$menuitem, $Table){
                                    $Cache = '<input type="text" list="addons-' . $Table . '">';
                                    $toppings_display = "";
                                    for ($i = 0; $i < $menuitem[$Table]; $i++) {
                                        $toppings_display .= $Cache;
                                    }
                                    $menuitem[$Table] = $toppings_display;
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
                                                addaddons($menuitem, 'toppings');//TOPPINGS
                                                addaddons($menuitem, 'wings_sauce');//WINGS

                                                // KEYWORDS
                                                $keywords = Query("SELECT * FROM keywords, menukeywords WHERE menuitem_id = " . $menuitem["id"] . " OR -menuitem_id = " . $menuitem["category_id"] . " HAVING keywords.id = keyword_id", true);
                                                foreach ($keywords as $ID => $keyword) {
                                                    $keywords[$ID] = '<SPAN TITLE="Weight: ' . $keyword["weight"] . '">' . ($keyword["synonyms"]) . '</SPAN>';
                                                }
                                                $menuitem["keywords"] = implode(", ", $keywords);
                                                $menuitem["price"] = number_format($menuitem["price"], 2);
                                                $menuitem["Actions"] = '<A HREF="?id=' . $menuitem["id"] . '">Edit</A>';

                                                ?>

                                                <a class="text-xs-left clearfix menuitem" data-toggle="collapse" href="#collapse{{$menuitem["id"]}}">
                                                    <i class="fa fa-pie-chart text-warning"></i>
                                                    <SPAN CLASS="itemname">{{$menuitem['item']}}</SPAN>
                                                    <span class="pull-right"> ${{$menuitem['price']}}</span>
                                                </a>

                                                <div class="collapse" id="collapse{{$menuitem['id']}}">
                                                    <?
                                                        echo $menuitem['toppings'];
                                                        echo $menuitem['wings_sauce'];
                                                    ?>
                                                    <button class="btn btn-block btn-sm btn-warning" style="margin-top:.5rem;margin-bottom:.5rem;" data-toggle="collapse" href="#collapse{{$menuitem["id"]}}">
                                                        ADD TO ORDER
                                                    </button>
                                                </div>
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

        //force datalist validation
        var inputs = document.querySelectorAll('input[list]');
        for (var i = 0; i < inputs.length; i++) {
            // When the value of the input changes...
            inputs[i].addEventListener('change', function() {
                var optionFound = false, datalist = this.list;
                // Determine whether an option exists with the current value of the input.
                for (var j = 0; j < datalist.options.length; j++) {
                    if (this.value == datalist.options[j].value) {
                        optionFound = true;
                        break;
                    }
                }
                // use the setCustomValidity function of the Validation API
                // to provide an user feedback if the value does not exist in the datalist
                if (optionFound) {
                    this.setCustomValidity('');
                } else {
                    this.setCustomValidity('Please select a valid value.');
                }
            });
        }
    </SCRIPT>
@endsection