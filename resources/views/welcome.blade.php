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
    </style>


    <div class="row m-t-1">
        <div class="col-md-8">
            <div class="card ">
                <div class="card-block bg-danger" style="padding-top:.75rem !important;padding-bottom:.75rem !important;">

                <h4 class="pull-left"><i
                                class="fa fa-home" aria-hidden="true"></i> Pizza Delivery </h4>

                </div>
                <div class="card-block">
                    <div class="row">
                        <div class="col-md-4">
                            <?php
                            $con = connectdb("ai");
                            $categories = Query("select * from menu group by category order by id", true);
                            $a = 0;
                            foreach ($categories as $category) {
                            if($a == 3)
                            {
                            $a = 0;
                            ?>
                        </div>
                        <div class="col-md-4">
                            <? } ?>
                            <h4 class="text-danger" data-toggle="collapse"
                                href="#collapse{{$category["id"]}}_cat">{{$category['category']}}</h4>
                            <div class="collapse in" id="collapse{{$category['id']}}_cat">
                                <?
                                $menuitems = Query("SELECT * FROM menu WHERE category = '" . $category['category'] . "'", true);
                                foreach ($menuitems as $menuitem) {

                                /*
                                * TOPPINGS
                                */
                                if ($menuitem['toppings'] > 0) {
                                    $toppings_display = "";
                                    $toppings = Query("SELECT * FROM toppings", true);
                                    for ($i = 0; $i < $menuitem['toppings']; $i++) {
                                        $toppings_display = $toppings_display . "Pizza #" . ($i + 1) . " <select><option>Toppings</option>";
                                        foreach ($toppings as $ID => $topping) {
                                            $toppings_display = $toppings_display . "  <option>" . $topping["name"] . " $.79</option> ";
                                        }
                                        $toppings_display = $toppings_display . "</select><a class=''> +more</a><br>";
                                    }
                                    $menuitem["toppings"] = $toppings_display;
                                } else {
                                    $menuitem["toppings"] = "";
                                }
                                /*
                                * WINGS
                                */
                                if ($menuitem['wings_sauce'] > 0) {
                                    $wings_sauce = "";
                                    $toppings = Query("SELECT * FROM wings_sauce", true);
                                    for ($i = 0; $i < $menuitem['wings_sauce']; $i++) {
                                        $wings_sauce = $wings_sauce . "Wings #" . ($i + 1) . " <select><option>Sauce</option>";
                                        foreach ($toppings as $ID => $topping) {
                                            $wings_sauce = $wings_sauce . "<option>" . $topping["name"] . "</option>";
                                        }
                                        $wings_sauce = $wings_sauce . "</select><br>";
                                    }
                                    $menuitem["wings_sauce"] = $wings_sauce;
                                } else {
                                    $menuitem["wings_sauce"] = "";
                                }
                                /*
                                * KEYWORDS
                                */
                                $keywords = Query("SELECT * FROM keywords, menukeywords WHERE menuitem_id = " . $menuitem["id"] . " OR -menuitem_id = " . $menuitem["category_id"] . " HAVING keywords.id = keyword_id", true);
                                foreach ($keywords as $ID => $keyword) {
                                    $keywords[$ID] = '<SPAN TITLE="Weight: ' . $keyword["weight"] . '">' . ($keyword["synonyms"]) . '</SPAN>';
                                }
                                $menuitem["keywords"] = implode(", ", $keywords);
                                $menuitem["price"] = number_format($menuitem["price"], 2);
                                $menuitem["Actions"] = '<A HREF="?id=' . $menuitem["id"] . '">Edit</A>';
                                ?>

                                <a class="text-xs-left clearfix" data-toggle="collapse"
                                   href="#collapse{{$menuitem["id"]}}">
                                    <i class="fa fa-pie-chart text-warning"></i> {{$menuitem['item']}} <span
                                            class="pull-right"> ${{$menuitem['price']}}</span>
                                </a>
                                <div class="collapse" id="collapse{{$menuitem['id']}}">
                                    <?
                                    echo $menuitem['toppings'];
                                    echo $menuitem['wings_sauce'];
                                    ?>
                                    <button class="btn btn-block btn-sm btn-warning"
                                            style="margin-top:.5rem;margin-bottom:.5rem;"
                                            data-toggle="collapse"
                                            href="#collapse{{$menuitem["id"]}}">
                                        ADD TO ORDER
                                    </button>
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
                <div class="card-block bg-danger" style="padding-top:.75rem !important;padding-bottom:.75rem !important;">
                    <h4 class="pull-left">  My Order <label class="m-y-0">$45.66</label></h4>
                    <h4 class="pull-right"><i class="fa fa-user no-padding-margin"></i></h4>
                </div>
                <div class="card-block">

                    <div class="alert-info alert">
                        Your cart is empty
                    </div>
<div>
                    <span class="pull-left"> <i class="fa fa-pie-chart text-warning"></i> 2 Small Pizza</span><span
                            class="pull-right"> $9.99 <i
                                class="fa fa-close"></i></span>
                    <div class="clearfix"></div>

                    Pizza 1st: Ham, Bacon<br>
                    Pizza 2nd: Extra Cheese, Bacon<br>
                    <span class="pull-left"> <i class="fa fa-pie-chart text-warning"></i>  2 lbs Wings </span><span
                            class="pull-right"> $9.99 <i
                                class="fa fa-pencil"></i><i
                                class="fa fa-close"></i></span>
                    <div class="clearfix"></div>
                    1st lb - hot
                    <br>
                    2nd lb - mild
                    <br>
                    <span class="pull-left"> <i class="fa fa-pie-chart text-warning"></i> Ginger Ale </span><span
                            class="pull-right"> $1.99 <i
                                class="fa fa-close"></i></span>
                    <br>
                    <span class="pull-left"> <i class="fa fa-pie-chart text-warning"></i> Bottle Water </span><span
                            class="pull-right"> $1.99 <i
                                class="fa fa-close"></i></span>
                    <br>
                    <br>
                    <span class="pull-right"> <strong>Subtotal: $34.99</strong></span><br>
                    <span class="pull-right"> <strong>HST: $4.99</strong></span><br>
                    <span class="pull-right"> <strong>Delivery: $4.99</strong></span><br>
                    <span class="pull-right"> <strong>Total: $44.99</strong></span>

                    </div>
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
@endsection