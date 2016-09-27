<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet'
          type='text/css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/css/bootstrap.min.css">
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://select2.github.io/select2/select2-3.5.2/select2.css">
    <link rel="stylesheet" href="custom.css">

    <script src="http://localhost/ai/resources/assets/scripts/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/js/bootstrap.min.js"></script>
    <script src="http://select2.github.io/select2/select2-3.4.2/select2.js"></script>
    <SCRIPT SRC="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></SCRIPT>
    <script src="http://localhost/ai/resources/assets/scripts/api2.js"></script>
</head>
<body>

<!--nav class="navbar-default navbar-fixed-top navbar navbar-full navbar-dark bg-danger dont-print" style="z-index: 1;padding:.1rem !important;">
</nav-->

<div class="container p-y-1 m-b-3 ">

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


                    <div class="card card-block p-a-0 m-a-0">


                        <a class="head_pizza" data-toggle="collapse"
                           href="#collapse1_cat">
                            <h5 class="text-danger">Pizza
                            </h5>
                        </a>
                        <div class="collapse list-group in  " id="collapse1_cat">
                            <div class="menuitem item_pizza" itemid="1"
                                 itemname="Small Pizza"
                                 itemprice="6.99"
                                 itemsize="Small" toppings="1" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   data-toggle="modal" data-backdrop="static" data-target="#menumodal"
                                   onclick="loadmodal(this);">


                                    <i class="fa fa-chevron-down pull-right text-muted"></i> <img class="pull-left "
                                                                                                  src="pizza.png"
                                                                                                  style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">Small Pizza</span>

                                    <span class="pull-right"> $6.99</span>

                                </a>

                            </div>
                            <div class="menuitem item_pizza" itemid="2"
                                 itemname="Medium Pizza"
                                 itemprice="7.99"
                                 itemsize="Medium" toppings="1" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   data-toggle="modal" data-backdrop="static" data-target="#menumodal"
                                   onclick="loadmodal(this);">


                                    <i class="fa fa-chevron-down pull-right text-muted"></i> <img class="pull-left "
                                                                                                  src="pizza.png"
                                                                                                  style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">Medium Pizza</span>

                                    <span class="pull-right"> $7.99</span>

                                </a>

                            </div>
                            <div class="menuitem item_pizza" itemid="3"
                                 itemname="Large Pizza"
                                 itemprice="8.99"
                                 itemsize="Large" toppings="1" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   data-toggle="modal" data-backdrop="static" data-target="#menumodal"
                                   onclick="loadmodal(this);">


                                    <i class="fa fa-chevron-down pull-right text-muted"></i> <img class="pull-left "
                                                                                                  src="pizza.png"
                                                                                                  style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">Large Pizza</span>

                                    <span class="pull-right"> $8.99</span>

                                </a>

                            </div>
                            <div class="menuitem item_pizza" itemid="4"
                                 itemname="X-Large Pizza"
                                 itemprice="10.99"
                                 itemsize="X-Large" toppings="1" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   data-toggle="modal" data-backdrop="static" data-target="#menumodal"
                                   onclick="loadmodal(this);">


                                    <i class="fa fa-chevron-down pull-right text-muted"></i> <img class="pull-left "
                                                                                                  src="pizza.png"
                                                                                                  style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">X-Large Pizza</span>

                                    <span class="pull-right"> $10.99</span>

                                </a>

                            </div>
                        </div>
                        <div class="">&nbsp;
                        </div>
                    </div>

                    <div class="card card-block p-a-0 m-a-0">


                        <a class="head_2_for_1_pizza" data-toggle="collapse"
                           href="#collapse6_cat">
                            <h5 class="text-danger">2 for 1 Pizza
                            </h5>
                        </a>
                        <div class="collapse list-group in  " id="collapse6_cat">
                            <div class="menuitem item_2_for_1_pizza" itemid="6"
                                 itemname="2 Small Pizza"
                                 itemprice="19.99"
                                 itemsize="Small" toppings="2" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   data-toggle="modal" data-backdrop="static" data-target="#menumodal"
                                   onclick="loadmodal(this);">


                                    <i class="fa fa-chevron-down pull-right text-muted"></i> <img class="pull-left "
                                                                                                  src="pizza.png"
                                                                                                  style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">2 Small Pizza</span>

                                    <span class="pull-right"> $19.99</span>

                                </a>

                            </div>
                            <div class="menuitem item_2_for_1_pizza" itemid="7"
                                 itemname="2 Medium Pizza"
                                 itemprice="21.99"
                                 itemsize="Medium" toppings="2" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   data-toggle="modal" data-backdrop="static" data-target="#menumodal"
                                   onclick="loadmodal(this);">


                                    <i class="fa fa-chevron-down pull-right text-muted"></i> <img class="pull-left "
                                                                                                  src="pizza.png"
                                                                                                  style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">2 Medium Pizza</span>

                                    <span class="pull-right"> $21.99</span>

                                </a>

                            </div>
                            <div class="menuitem item_2_for_1_pizza" itemid="8"
                                 itemname="2 Large Pizza"
                                 itemprice="33.99"
                                 itemsize="Large" toppings="2" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   data-toggle="modal" data-backdrop="static" data-target="#menumodal"
                                   onclick="loadmodal(this);">


                                    <i class="fa fa-chevron-down pull-right text-muted"></i> <img class="pull-left "
                                                                                                  src="pizza.png"
                                                                                                  style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">2 Large Pizza</span>

                                    <span class="pull-right"> $33.99</span>

                                </a>

                            </div>
                            <div class="menuitem item_2_for_1_pizza" itemid="9"
                                 itemname="2 X-Large Pizza"
                                 itemprice="45.99"
                                 itemsize="X-Large" toppings="2" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   data-toggle="modal" data-backdrop="static" data-target="#menumodal"
                                   onclick="loadmodal(this);">


                                    <i class="fa fa-chevron-down pull-right text-muted"></i> <img class="pull-left "
                                                                                                  src="pizza.png"
                                                                                                  style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">2 X-Large Pizza</span>

                                    <span class="pull-right"> $45.99</span>

                                </a>

                            </div>
                        </div>
                        <div class="">&nbsp;
                        </div>
                    </div>

                    <div class="card card-block p-a-0 m-a-0">


                        <a class="head_3_for_1_pizza" data-toggle="collapse"
                           href="#collapse11_cat">
                            <h5 class="text-danger">3 for 1 Pizza
                            </h5>
                        </a>
                        <div class="collapse list-group in  " id="collapse11_cat">
                            <div class="menuitem item_3_for_1_pizza" itemid="11"
                                 itemname="3 Small Pizza"
                                 itemprice="22.99"
                                 itemsize="Small" toppings="3" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   data-toggle="modal" data-backdrop="static" data-target="#menumodal"
                                   onclick="loadmodal(this);">


                                    <i class="fa fa-chevron-down pull-right text-muted"></i> <img class="pull-left "
                                                                                                  src="pizza.png"
                                                                                                  style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">3 Small Pizza</span>

                                    <span class="pull-right"> $22.99</span>

                                </a>

                            </div>
                            <div class="menuitem item_3_for_1_pizza" itemid="12"
                                 itemname="3 Medium Pizza"
                                 itemprice="33.99"
                                 itemsize="Medium" toppings="3" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   data-toggle="modal" data-backdrop="static" data-target="#menumodal"
                                   onclick="loadmodal(this);">


                                    <i class="fa fa-chevron-down pull-right text-muted"></i> <img class="pull-left "
                                                                                                  src="pizza.png"
                                                                                                  style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">3 Medium Pizza</span>

                                    <span class="pull-right"> $33.99</span>

                                </a>

                            </div>
                            <div class="menuitem item_3_for_1_pizza" itemid="13"
                                 itemname="3 Large Pizza"
                                 itemprice="40.99"
                                 itemsize="Large" toppings="3" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   data-toggle="modal" data-backdrop="static" data-target="#menumodal"
                                   onclick="loadmodal(this);">


                                    <i class="fa fa-chevron-down pull-right text-muted"></i> <img class="pull-left "
                                                                                                  src="pizza.png"
                                                                                                  style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">3 Large Pizza</span>

                                    <span class="pull-right"> $40.99</span>

                                </a>

                            </div>
                            <div class="menuitem item_3_for_1_pizza" itemid="14"
                                 itemname="3 X-Large Pizza"
                                 itemprice="44.99"
                                 itemsize="X-Large" toppings="3" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   data-toggle="modal" data-backdrop="static" data-target="#menumodal"
                                   onclick="loadmodal(this);">


                                    <i class="fa fa-chevron-down pull-right text-muted"></i> <img class="pull-left "
                                                                                                  src="pizza.png"
                                                                                                  style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">3 X-Large Pizza</span>

                                    <span class="pull-right"> $44.99</span>

                                </a>

                            </div>
                        </div>
                        <div class="">&nbsp;
                        </div>
                    </div>

                    <div class="card card-block p-a-0 m-a-0">


                        <a class="head_wings" data-toggle="collapse"
                           href="#collapse16_cat">
                            <h5 class="text-danger">Wings
                            </h5>
                        </a>
                        <div class="collapse list-group in  " id="collapse16_cat">
                            <div class="menuitem item_wings" itemid="16"
                                 itemname="1 Pound Wings"
                                 itemprice="19.99"
                                 itemsize="" toppings="0" wings_sauce="1">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   data-toggle="modal" data-backdrop="static" data-target="#menumodal"
                                   onclick="loadmodal(this);">


                                    <i class="fa fa-chevron-down pull-right text-muted"></i> <img class="pull-left "
                                                                                                  src="pizza.png"
                                                                                                  style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">1 Pound Wings</span>

                                    <span class="pull-right"> $19.99</span>

                                </a>

                            </div>
                            <div class="menuitem item_wings" itemid="17"
                                 itemname="2 Pound Wings"
                                 itemprice="21.99"
                                 itemsize="" toppings="0" wings_sauce="2">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   data-toggle="modal" data-backdrop="static" data-target="#menumodal"
                                   onclick="loadmodal(this);">


                                    <i class="fa fa-chevron-down pull-right text-muted"></i> <img class="pull-left "
                                                                                                  src="pizza.png"
                                                                                                  style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">2 Pound Wings</span>

                                    <span class="pull-right"> $21.99</span>

                                </a>

                            </div>
                            <div class="menuitem item_wings" itemid="18"
                                 itemname="3 Pound Wings"
                                 itemprice="33.99"
                                 itemsize="" toppings="0" wings_sauce="3">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   data-toggle="modal" data-backdrop="static" data-target="#menumodal"
                                   onclick="loadmodal(this);">


                                    <i class="fa fa-chevron-down pull-right text-muted"></i> <img class="pull-left "
                                                                                                  src="pizza.png"
                                                                                                  style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">3 Pound Wings</span>

                                    <span class="pull-right"> $33.99</span>

                                </a>

                            </div>
                            <div class="menuitem item_wings" itemid="19"
                                 itemname="4 Pound Wings"
                                 itemprice="45.99"
                                 itemsize="" toppings="0" wings_sauce="4">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   data-toggle="modal" data-backdrop="static" data-target="#menumodal"
                                   onclick="loadmodal(this);">


                                    <i class="fa fa-chevron-down pull-right text-muted"></i> <img class="pull-left "
                                                                                                  src="pizza.png"
                                                                                                  style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">4 Pound Wings</span>

                                    <span class="pull-right"> $45.99</span>

                                </a>

                            </div>
                            <div class="menuitem item_wings" itemid="20"
                                 itemname="5 Pound Wings"
                                 itemprice="45.99"
                                 itemsize="" toppings="0" wings_sauce="5">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   data-toggle="modal" data-backdrop="static" data-target="#menumodal"
                                   onclick="loadmodal(this);">


                                    <i class="fa fa-chevron-down pull-right text-muted"></i> <img class="pull-left "
                                                                                                  src="pizza.png"
                                                                                                  style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">5 Pound Wings</span>

                                    <span class="pull-right"> $45.99</span>

                                </a>

                            </div>
                        </div>
                        <div class="">&nbsp;
                        </div>
                    </div>

                    <div class="card card-block p-a-0 m-a-0">


                        <a class="head_sides" data-toggle="collapse"
                           href="#collapse22_cat">
                            <h5 class="text-danger">Sides
                            </h5>
                        </a>
                        <div class="collapse list-group in  " id="collapse22_cat">
                            <div class="menuitem item_sides" itemid="22"
                                 itemname="Poutine"
                                 itemprice="4.99"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">Poutine</span>

                                    <span class="pull-right"> $4.99</span>

                                </a>

                            </div>
                            <div class="menuitem item_sides" itemid="23"
                                 itemname="Potato Wedges"
                                 itemprice="4.99"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">Potato Wedges</span>

                                    <span class="pull-right"> $4.99</span>

                                </a>

                            </div>
                            <div class="menuitem item_sides" itemid="24"
                                 itemname="Onion Rings"
                                 itemprice="3.99"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">Onion Rings</span>

                                    <span class="pull-right"> $3.99</span>

                                </a>

                            </div>
                            <div class="menuitem item_sides" itemid="25"
                                 itemname="Veggie Sticks"
                                 itemprice="2.00"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">Veggie Sticks</span>

                                    <span class="pull-right"> $2.00</span>

                                </a>

                            </div>
                            <div class="menuitem item_sides" itemid="26"
                                 itemname="Garlic Bread"
                                 itemprice="1.50"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">Garlic Bread</span>

                                    <span class="pull-right"> $1.50</span>

                                </a>

                            </div>
                            <div class="menuitem item_sides" itemid="27"
                                 itemname="French Fries"
                                 itemprice="2.99"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">French Fries</span>

                                    <span class="pull-right"> $2.99</span>

                                </a>

                            </div>
                            <div class="menuitem item_sides" itemid="28"
                                 itemname="Chicken Salad"
                                 itemprice="7.99"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">Chicken Salad</span>

                                    <span class="pull-right"> $7.99</span>

                                </a>

                            </div>
                            <div class="menuitem item_sides" itemid="29"
                                 itemname="Side Salad"
                                 itemprice="3.99"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">Side Salad</span>

                                    <span class="pull-right"> $3.99</span>

                                </a>

                            </div>
                            <div class="menuitem item_sides" itemid="30"
                                 itemname="Caesar Salad"
                                 itemprice="3.99"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">Caesar Salad</span>

                                    <span class="pull-right"> $3.99</span>

                                </a>

                            </div>
                            <div class="menuitem item_sides" itemid="31"
                                 itemname="Greek Salad"
                                 itemprice="3.99"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">Greek Salad</span>

                                    <span class="pull-right"> $3.99</span>

                                </a>

                            </div>
                            <div class="menuitem item_sides" itemid="32"
                                 itemname="Garden Salad"
                                 itemprice="3.99"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">Garden Salad</span>

                                    <span class="pull-right"> $3.99</span>

                                </a>

                            </div>
                            <div class="menuitem item_sides" itemid="69"
                                 itemname="Panzerotti"
                                 itemprice="5.99"
                                 itemsize="" toppings="1" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   data-toggle="modal" data-backdrop="static" data-target="#menumodal"
                                   onclick="loadmodal(this);">


                                    <i class="fa fa-chevron-down pull-right text-muted"></i> <img class="pull-left "
                                                                                                  src="pizza.png"
                                                                                                  style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">Panzerotti</span>

                                    <span class="pull-right"> $5.99</span>

                                </a>

                            </div>
                        </div>
                        <div class="">&nbsp;
                        </div>
                    </div>

                    <div class="card card-block p-a-0 m-a-0">


                        <a class="head_dips" data-toggle="collapse"
                           href="#collapse35_cat">
                            <h5 class="text-danger">Dips
                            </h5>
                        </a>
                        <div class="collapse list-group in  " id="collapse35_cat">
                            <div class="menuitem item_dips" itemid="35"
                                 itemname="Cheddar Jalapeno"
                                 itemprice="0.95"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">Cheddar Jalapeno</span>

                                    <span class="pull-right"> $0.95</span>

                                </a>

                            </div>
                            <div class="menuitem item_dips" itemid="36"
                                 itemname="Marinara"
                                 itemprice="0.95"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">Marinara</span>

                                    <span class="pull-right"> $0.95</span>

                                </a>

                            </div>
                            <div class="menuitem item_dips" itemid="37"
                                 itemname="Garlic Parmesan"
                                 itemprice="0.95"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">Garlic Parmesan</span>

                                    <span class="pull-right"> $0.95</span>

                                </a>

                            </div>
                            <div class="menuitem item_dips" itemid="38"
                                 itemname="BBQ Sauce"
                                 itemprice="0.95"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">BBQ Sauce</span>

                                    <span class="pull-right"> $0.95</span>

                                </a>

                            </div>
                            <div class="menuitem item_dips" itemid="39"
                                 itemname="Cheddar Sauce"
                                 itemprice="0.95"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">Cheddar Sauce</span>

                                    <span class="pull-right"> $0.95</span>

                                </a>

                            </div>
                            <div class="menuitem item_dips" itemid="40"
                                 itemname="Creamy Garlic Sauce"
                                 itemprice="0.95"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">Creamy Garlic Sauce</span>

                                    <span class="pull-right"> $0.95</span>

                                </a>

                            </div>
                            <div class="menuitem item_dips" itemid="41"
                                 itemname="Honey Garlic Sauce"
                                 itemprice="0.95"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">Honey Garlic Sauce</span>

                                    <span class="pull-right"> $0.95</span>

                                </a>

                            </div>
                            <div class="menuitem item_dips" itemid="42"
                                 itemname="Hot Sauce"
                                 itemprice="0.95"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">Hot Sauce</span>

                                    <span class="pull-right"> $0.95</span>

                                </a>

                            </div>
                            <div class="menuitem item_dips" itemid="43"
                                 itemname="Marinara Sauce"
                                 itemprice="0.95"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">Marinara Sauce</span>

                                    <span class="pull-right"> $0.95</span>

                                </a>

                            </div>
                            <div class="menuitem item_dips" itemid="44"
                                 itemname="Medium Sauce"
                                 itemprice="0.95"
                                 itemsize="Medium" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">Medium Sauce</span>

                                    <span class="pull-right"> $0.95</span>

                                </a>

                            </div>
                            <div class="menuitem item_dips" itemid="45"
                                 itemname="Mild Sauce"
                                 itemprice="0.95"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">Mild Sauce</span>

                                    <span class="pull-right"> $0.95</span>

                                </a>

                            </div>
                            <div class="menuitem item_dips" itemid="46"
                                 itemname="Ranch Sauce"
                                 itemprice="0.95"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">Ranch Sauce</span>

                                    <span class="pull-right"> $0.95</span>

                                </a>

                            </div>
                            <div class="menuitem item_dips" itemid="47"
                                 itemname="Spicy Buffalo Sauce"
                                 itemprice="0.95"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">Spicy Buffalo Sauce</span>

                                    <span class="pull-right"> $0.95</span>

                                </a>

                            </div>
                        </div>
                        <div class="">&nbsp;
                        </div>
                    </div>

                    <div class="card card-block p-a-0 m-a-0">


                        <a class="head_drinks" data-toggle="collapse"
                           href="#collapse50_cat">
                            <h5 class="text-danger">Drinks
                            </h5>
                        </a>
                        <div class="collapse list-group in  " id="collapse50_cat">
                            <div class="menuitem item_drinks" itemid="50"
                                 itemname="Diet Pepsi"
                                 itemprice="0.95"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">Diet Pepsi</span>

                                    <span class="pull-right"> $0.95</span>

                                </a>

                            </div>
                            <div class="menuitem item_drinks" itemid="51"
                                 itemname="Pepsi"
                                 itemprice="0.95"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">Pepsi</span>

                                    <span class="pull-right"> $0.95</span>

                                </a>

                            </div>
                            <div class="menuitem item_drinks" itemid="52"
                                 itemname="Coca-Cola"
                                 itemprice="0.95"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">Coca-Cola</span>

                                    <span class="pull-right"> $0.95</span>

                                </a>

                            </div>
                            <div class="menuitem item_drinks" itemid="53"
                                 itemname="Diet Coca-Cola"
                                 itemprice="0.95"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">Diet Coca-Cola</span>

                                    <span class="pull-right"> $0.95</span>

                                </a>

                            </div>
                            <div class="menuitem item_drinks" itemid="54"
                                 itemname="7-up"
                                 itemprice="0.95"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">7-up</span>

                                    <span class="pull-right"> $0.95</span>

                                </a>

                            </div>
                            <div class="menuitem item_drinks" itemid="55"
                                 itemname="Crush Orange"
                                 itemprice="0.95"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">Crush Orange</span>

                                    <span class="pull-right"> $0.95</span>

                                </a>

                            </div>
                            <div class="menuitem item_drinks" itemid="56"
                                 itemname="Dr. Pepper"
                                 itemprice="0.95"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">Dr. Pepper</span>

                                    <span class="pull-right"> $0.95</span>

                                </a>

                            </div>
                            <div class="menuitem item_drinks" itemid="57"
                                 itemname="Ginger Ale"
                                 itemprice="0.95"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">Ginger Ale</span>

                                    <span class="pull-right"> $0.95</span>

                                </a>

                            </div>
                            <div class="menuitem item_drinks" itemid="58"
                                 itemname="Iced Tea"
                                 itemprice="0.95"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">Iced Tea</span>

                                    <span class="pull-right"> $0.95</span>

                                </a>

                            </div>
                            <div class="menuitem item_drinks" itemid="59"
                                 itemname="Water Bottle"
                                 itemprice="0.95"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">Water Bottle</span>

                                    <span class="pull-right"> $0.95</span>

                                </a>

                            </div>
                            <div class="menuitem item_drinks" itemid="60"
                                 itemname="2L Diet Pepsi"
                                 itemprice="2.99"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">2L Diet Pepsi</span>

                                    <span class="pull-right"> $2.99</span>

                                </a>

                            </div>
                            <div class="menuitem item_drinks" itemid="61"
                                 itemname="2L Pepsi"
                                 itemprice="2.99"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">2L Pepsi</span>

                                    <span class="pull-right"> $2.99</span>

                                </a>

                            </div>
                            <div class="menuitem item_drinks" itemid="62"
                                 itemname="2L Coca-Cola"
                                 itemprice="2.99"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">2L Coca-Cola</span>

                                    <span class="pull-right"> $2.99</span>

                                </a>

                            </div>
                            <div class="menuitem item_drinks" itemid="63"
                                 itemname="2L Diet Coca-Cola"
                                 itemprice="2.99"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">2L Diet Coca-Cola</span>

                                    <span class="pull-right"> $2.99</span>

                                </a>

                            </div>
                            <div class="menuitem item_drinks" itemid="64"
                                 itemname="2L 7-up"
                                 itemprice="2.99"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">2L 7-up</span>

                                    <span class="pull-right"> $2.99</span>

                                </a>

                            </div>
                            <div class="menuitem item_drinks" itemid="65"
                                 itemname="2L Crush Orange"
                                 itemprice="2.99"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">2L Crush Orange</span>

                                    <span class="pull-right"> $2.99</span>

                                </a>

                            </div>
                            <div class="menuitem item_drinks" itemid="66"
                                 itemname="2L Dr. Pepper"
                                 itemprice="2.99"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">2L Dr. Pepper</span>

                                    <span class="pull-right"> $2.99</span>

                                </a>

                            </div>
                            <div class="menuitem item_drinks" itemid="67"
                                 itemname="2L Ginger Ale"
                                 itemprice="2.99"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">2L Ginger Ale</span>

                                    <span class="pull-right"> $2.99</span>

                                </a>

                            </div>
                            <div class="menuitem item_drinks" itemid="68"
                                 itemname="2L Iced Tea"
                                 itemprice="2.99"
                                 itemsize="" toppings="0" wings_sauce="0">


                                <a class="btn btn-block"
                                   style="border:0 !important;padding:0 !important;line-height: 1.5rem !important;"
                                   onclick="additemtoorder(this);">


                                    <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/>

                                    <span class="pull-left itemname">2L Iced Tea</span>

                                    <span class="pull-right"> $2.99</span>

                                </a>

                            </div>
                        </div>
                        <div class="">&nbsp;
                        </div>
                    </div>

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


                                    <li>
                                        <A HREF="http://localhost/ai/public/list/users">Users list</A><BR><A
                                                HREF="http://localhost/ai/public/list/restaurants">Restaurants
                                            list</A><BR><A HREF="http://localhost/ai/public/list/useraddresses">Useraddresses
                                            list</A><BR><A HREF="http://localhost/ai/public/list/orders">Orders list</A><BR>
                                        <HR>
                                        <A HREF="http://localhost/ai/public/editmenu">Edit Menu</A><BR>
                                        <A HREF="http://localhost/ai/public/list/debug">Debug log</A>

                                    </li>

                                    <li>
                                        <SPAN CLASS="session_name dropdown-item">
                                            <i class="fa fa-home"></i></SPAN>
                                    </li>
                                    <li>
                                        <A HREF="http://localhost/ai/public/list/all"
                                           CLASS="profiletype  dropdown-item profiletype1"> <i class="fa fa-home"></i>
                                            Admin</A>
                                    </li>
                                    <li>


                                        <A HREF="http://localhost/ai/public/list/useraddresses" class="dropdown-item">
                                            <i
                                                    class="fa fa-home"></i> Addressess</A>
                                    </li>
                                    <li>

                                        <A HREF="http://localhost/ai/public/user/info" class="dropdown-item"> <i
                                                    class="fa fa-home"></i>
                                            Profile</A>

                                    </li>

                                    <li>


                                        <A ONCLICK="handlelogin('logout');" CLASS="hyperlink dropdown-item"> <i
                                                    class="fa fa-home"></i> Log out</A>
                                        <A CLASS="loggedout dropdown-item hyperlink" data-toggle="modal"
                                           data-target="#loginmodal"> <i class="fa fa-home"></i> Log
                                            In</A>


                                    </li>


                                </ul>
                            </li>
                        </ul>
                    </div>


                </div>
                <div class="card-block">
<!--div class="list-group row col-xs-12">
<div class="list-group-item">2  pizza $35, September 22 @ 8pm</div>
<div class=" list-group-item">2  pizza $35, September 22 @ 8pm</div>
<div class=" list-group-item">2  pizza $35, September 22 @ 8pm</div>
<div class=" list-group-item">2  pizza $35, September 22 @ 8pm</div></div-->
                    <div id="myorder"></div>
                    <button data-toggle="collapse" class="btn btn-block btn-warning" id="checkout"
                            href="#collapseCheckout">
                        CHECKOUT
                    </button>
                    <div class="collapse p-t-1" id="collapseCheckout">


                        <FORM ID="orderinfo">

                            <div style="border:1px solid #dadada; padding:10px;">


                                <i class="fa fa-pencil pull-right text-muted"></i>

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

                                </div>


                                <div ID="addressdropdown" class="clear_loggedout"></div>
                                <INPUT TYPE="text" ID="formatted_address" PLACEHOLDER="Address" class="form-control">
                                <STYLE>.address.form-control:focus {
                                        z-index: 999;
                                    }</STYLE>
                                <FORM ID="googleaddress">
                                    <INPUT TYPE="text" NAME="number" ID="add_number" PLACEHOLDER="Street Number"
                                           CLASS="street_number address form-control" readonly><INPUT TYPE="text"
                                                                                                      NAME="street"
                                                                                                      ID="add_street"
                                                                                                      PLACEHOLDER="Street"
                                                                                                      CLASS="route address form-control"
                                                                                                      readonly><INPUT
                                            TYPE="text" NAME="unit" ID="add_unit" PLACEHOLDER="Unit/Apt"
                                            CLASS=" address form-control"><INPUT TYPE="text" NAME="buzzcode"
                                                                                 ID="add_buzzcode"
                                                                                 PLACEHOLDER="Buzz code"
                                                                                 CLASS=" address form-control"><INPUT
                                            TYPE="text" NAME="city" ID="add_city" PLACEHOLDER="City"
                                            CLASS="locality address form-control" readonly><INPUT TYPE="text"
                                                                                                  NAME="province"
                                                                                                  ID="add_province"
                                                                                                  PLACEHOLDER="Province"
                                                                                                  CLASS="administrative_area_level_1 address form-control"
                                                                                                  readonly><INPUT
                                            TYPE="text" NAME="postalcode" ID="add_postalcode" PLACEHOLDER="Postal Code"
                                            CLASS="postal_code address form-control" readonly><INPUT TYPE="text"
                                                                                                     NAME="latitude"
                                                                                                     ID="add_latitude"
                                                                                                     PLACEHOLDER="Latitude"
                                                                                                     CLASS="latitude address form-control"
                                                                                                     readonly><INPUT
                                            TYPE="text" NAME="longitude" ID="add_longitude" PLACEHOLDER="Longitude"
                                            CLASS="longitude address form-control" readonly><INPUT TYPE="hidden"
                                                                                                   NAME="user_id"
                                                                                                   ID="add_user_id"
                                                                                                   PLACEHOLDER="user_id"
                                                                                                   CLASS="session_id_val address form-control">
                                </FORM>
                                <input type="text" class="form-control" placeholder="Credit Card"/>

                                <div class="input-group">
                        <span class="input-group-btn" style="width: 50% !important;">
                        <input type="text" class="form-control" placeholder="Exp. Month"/>
                        </span>
                                    <span class="input-group-btn" style="width: 50% !important;">
                        <input type="text" class="form-control" placeholder="Exp. Year"/>
                        </span>
                                </div>


                            </div>

                            <input type="text" class="form-control"
                                   placeholder="Restaurant (Only Fabulous Pizza at first)"/>
                            <input type="text" class="form-control" placeholder="Delivery Time"/>
                            <input type="text" class="form-control" placeholder="Notes for the Cook"/>


                            <SCRIPT>
                                function initAutocomplete() {
                                    formatted_address = new google.maps.places.Autocomplete(
                                            /** @type  {!HTMLInputElement} */(document.getElementById('formatted_address')), {
                                                types: ['geocode'],
                                                componentRestrictions: {country: "ca"}
                                            });
                                    formatted_address.addListener('place_changed', fillInAddress);
                                }

                                function fillInAddress() {
                                    // Get the place details from the formatted_address object.
                                    var place = formatted_address.getPlace();
                                    var lat = place.geometry.location.lat();
                                    var lng = place.geometry.location.lng();

                                    $('.formatted_fordb').val(place.formatted_address); // this formatted_address is a google maps object
                                    $('.latitude').val(lat);
                                    $('.longitude').val(lng);

                                    var componentForm = {
                                        street_number: 'short_name',
                                        route: 'long_name',
                                        locality: 'long_name',
                                        administrative_area_level_1: 'long_name',
                                        country: 'long_name',
                                        postal_code: 'short_name'
                                    };
                                    $('#city').val('');
                                    //$('#formatted_address').val('');
                                    $('#postal_code').val('');
                                    $("#province").val('');
                                    var streetformat = "[street_number] [route], [locality]";

                                    for (var i = 0; i < place.address_components.length; i++) {
                                        var addressType = place.address_components[i].types[0];
                                        if (componentForm[addressType]) {
                                            var val = place.address_components[i][componentForm[addressType]];
                                            log(addressType + " = " + val);
                                            streetformat = streetformat.replace("[" + addressType + "]", val);
                                            $('.' + addressType).val(val);
                                        }
                                    }

                                    $('.formatted_address').val(streetformat);
                                    return place;
                                }
                            </SCRIPT>
                            <script src="https://maps.googleapis.com/maps/api/js?signed_in=true&libraries=places&callback=initAutocomplete&key=AIzaSyBWSUc8EbZYVKF37jWVCb3lpBQwWqXUZw8"></script>

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
                        <h5 class="modal-title" id="myModalLabel"><SPAN ID="modal-itemname"></SPAN> $<SPAN
                                    ID="modal-itemprice"></SPAN></h5>
                    </div>

                    <div style="display: none;" id="modal-hiddendata">
                        <SPAN ID="modal-itemid"></SPAN>
                        <SPAN ID="modal-toppingcost"></SPAN>
                        <SPAN ID="modal-itemsize"></SPAN>
                    </div>

                    <ul class="list-group">


                        <div ID="modal-wings-original">
                            <select class="form-control select2 wings_sauce" multiple="multiple"
                                    data-placeholder="Pound #1"
                                    type="wings_sauce">
                                <option value="blank"></option>
                                <optgroup label="Preparation">
                                    <option value="7" type="Preparation">Cooked (free)</option>
                                </optgroup>
                                <optgroup label="Sauce">
                                    <option value="5" type="Sauce">BBQ</option>
                                    <option value="6" type="Sauce">Honey Garlic</option>
                                    <option value="3" type="Sauce">Hot</option>
                                    <option value="2" type="Sauce">Medium</option>
                                    <option value="1" type="Sauce">Mild</option>
                                    <option value="4" type="Sauce">Suicide</option>
                                </optgroup>
                                <optgroup label="Options">
                                    <option value="AZ">Well Done</option>
                                    <option value="CO">Lightly Done</option>
                                </optgroup>
                            </select>
                        </div>


                        <div ID="modal-wings-clones"></div>


                        <div ID="modal-toppings-original" style="">
                            <div style="margin-bottom:.1rem;">Pizza #<span class="index">1</span></div>
                            <select style="border: 0 !important;" class="form-control select2 toppings"
                                    data-placeholder="Add Toppings: $[price]"
                                    multiple="multiple" type="toppings">
                                <!--option value="blank"></option-->
                                <optgroup label="Cheese">
                                    <option value="7" type="Cheese">Cheddar</option>
                                    <option value="8" type="Cheese">Cheese</option>
                                    <option value="11" type="Cheese">Feta Cheese</option>
                                    <option value="25" type="Cheese">Mixed Cheese</option>
                                    <option value="26" type="Cheese">Mozzarella Cheese</option>
                                    <option value="29" type="Cheese">Parmesan Cheese</option>
                                </optgroup>
                                <optgroup label="Meat">
                                    <option value="1" type="Meat">Anchovies</option>
                                    <option value="3" type="Meat">Bacon</option>
                                    <option value="4" type="Meat">Beef Salami</option>
                                    <option value="9" type="Meat">Chicken</option>
                                    <option value="15" type="Meat">Ground Beef</option>
                                    <option value="16" type="Meat">Ham</option>
                                    <option value="18" type="Meat">Hot Italian Sausage</option>
                                    <option value="20" type="Meat">Hot Sausage</option>
                                    <option value="21" type="Meat">Italian Sausage</option>
                                    <option value="24" type="Meat">Mild Sausage</option>
                                    <option value="30" type="Meat">Pepperoni</option>
                                    <option value="34" type="Meat">Salami</option>
                                </optgroup>
                                <optgroup label="Preparation">
                                    <option value="38" type="Preparation">Cooked (free)</option>
                                    <option value="22" type="Preparation">Tomato Sauce (free)</option>
                                </optgroup>
                                <optgroup label="Vegetable">
                                    <option value="2" type="Vegetable">Artichoke Heart</option>
                                    <option value="5" type="Vegetable">Black Olives</option>
                                    <option value="6" type="Vegetable">Broccoli</option>
                                    <option value="12" type="Vegetable">Fresh Mushroom</option>
                                    <option value="13" type="Vegetable">Green Olives</option>
                                    <option value="14" type="Vegetable">Green Peppers</option>
                                    <option value="17" type="Vegetable">Hot Banana Peppers</option>
                                    <option value="19" type="Vegetable">Hot Peppers</option>
                                    <option value="23" type="Vegetable">Jalapeno Peppers</option>
                                    <option value="27" type="Vegetable">Mushrooms</option>
                                    <option value="28" type="Vegetable">Onions</option>
                                    <option value="31" type="Vegetable">Pineapple</option>
                                    <option value="32" type="Vegetable">Red Onions</option>
                                    <option value="33" type="Vegetable">Red Peppers</option>
                                    <option value="35" type="Vegetable">Spinach</option>
                                    <option value="36" type="Vegetable">Sundried Tomatoes</option>
                                    <option value="37" type="Vegetable">Tomatoes</option>
                                </optgroup>
                                <optgroup label="Options">
                                    <option value="AZ">Well Done</option>
                                    <option value="CO">Lightly Done</option>
                                </optgroup>
                            </select>
                        </div>

                        <div ID="modal-toppings-clones"></div>
                    </ul>

                    <button data-dismiss="modal" class="btn btn-block m-t-1  btn-warning" onclick="additemtoorder();">
                        ADD TO ORDER
                    </button>

                </div>
            </div>
        </div>
    </div>

    <script>
        var tables = ["toppings", "wings_sauce"];
        var freetoppings = {
            "Small": "1",
            "Medium": "1.25",
            "Large": "1.5",
            "X-Large": "2",
            "toppings": ["Cooked", "Tomato Sauce"],
            "isall": {
                "toppings": ["Cooked"],
                "wings_sauce": ["Cooked", "BBQ", "Honey Garlic", "Hot", "Medium", "Mild", "Suicide"]
            },
            "wings_sauce": ["Cooked"]
        };
        var qualifiers = {
            "DEFAULT": ["1\/2", "1x", "2x", "3x"],
            "toppings": {"Cooked": ["Lightly done", "Regular", "Well done"]},
            "wings_sauce": {"Cooked": ["Lightly done", "Regular", "Well done"]}
        };
        var theorder = new Array;
        var toppingsouterhtml, wingsauceouterhtml;
        var deliveryfee = 3.5;
        var classlist = ["pizza", "2_for_1_pizza", "3_for_1_pizza", "wings", "sides", "dips", "drinks"];

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
                    minimumResultsForSearch: -1
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
                for (var index = 2; index <= count; index++) {
                    HTML += sourceHTML.replace('<span class="index">1</span>', '<SPAN CLASS="index">' + index + '</SPAN>').replaceAll("#1", "#" + index);
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


        function generatereceipt() {
            var HTML = '', tempHTML = "", subtotal = 0;
            for (var itemid = 0; itemid < theorder.length; itemid++) {
                var item = theorder[itemid];
                var totalcost = (Number(item["itemprice"]) + (Number(item["toppingcost"]) * Number(item["toppingcount"]))).toFixed(2);
                subtotal += Number(totalcost);
                tempHTML = '<span class="pull-left"> <img class="pull-left " src="pizza.png" style="width:22px;margin-right:5px;"/> ' + item["itemname"] + '</span>';
                tempHTML += '<span class="pull-right" title="Base cost: ' + item["itemprice"] + ' Non-free Toppings: ' + item["toppingcount"] + ' Topping cost: $' + item["toppingcost"] + '"> $' + totalcost + ' <i class="text-muted fa fa-close" onclick="removeorderitem(' + itemid + ');"></i></span><div class="clearfix"></div>';

                var itemname = "";
                //alert(JSON.stringify(item["itemaddons"]));
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
                        tempHTML += itemname + " #" + (currentitem + 1) + ": ";
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
            }


            tempHTML = '<span class="pull-right"> Sub-total: $' + subtotal.toFixed(2) + '</span><br>';
            tempHTML += '<span class="pull-right"> Delivery: $' + deliveryfee.toFixed(2) + '</span><br>';
            tempHTML += '<span class="pull-right"> Tax: $' + taxes.toFixed(2) + '</span><br>';
            tempHTML += '<span class="pull-right"> Total: $' + totalcost.toFixed(2) + '</span>';

            $("#myorder").html(HTML + tempHTML);
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


</div>


<div class="modal" id="loginmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="form-group">
                    <h4 class="modal-title" id="myModalLabel">Login</h4>
                </div>

                <INPUT TYPE="TEXT" ID="login_email" PlACEHOLDER="Email Address" CLASS="form-control">
                <INPUT TYPE="PASSWORD" ID="login_password" PLACEHOLDER="Password" CLASS="form-control">

                <DIV ID="loginmessage"></DIV>

                <DIV STYLE="margin-top: 15px;">
                    <DIV CLASS="col-md-4">
                        <button class="btn btn-block btn-warning" onclick="handlelogin('login');">
                            Login
                        </button>
                    </DIV>
                    <DIV CLASS="col-md-4">
                        <button class="btn btn-block btn-danger" onclick="handlelogin('forgotpassword');">
                            Forgot Password
                        </button>
                    </DIV>
                    <DIV CLASS="col-md-4">
                        <button class="btn btn-block btn-primary" data-dismiss="modal" data-toggle="modal"
                                data-target="#registermodal">
                            Register
                        </button>
                    </DIV>
                </DIV>
                <DIV CLASS="clearfix"></DIV>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="registermodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="form-group">
                    <h4 class="modal-title" id="myModalLabel">Register</h4>
                </div>
                <FORM Name="regform" id="regform">
                    <DIV CLASS="row">
                        <DIV CLASS="col-md-2">Name:</DIV>
                        <DIV CLASS="col-md-10"><INPUT TYPE="text" NAME="name" ID="reg_name" CLASS="form-control"
                                                      value=""></DIV>
                    </DIV>
                    <DIV CLASS="row">
                        <DIV CLASS="col-md-2">Phone:</DIV>
                        <DIV CLASS="col-md-10"><INPUT TYPE="tel" NAME="phone" ID="reg_phone" CLASS="form-control"
                                                      value=""></DIV>
                    </DIV>
                    <DIV CLASS="row">
                        <DIV CLASS="col-md-2">Email:</DIV>
                        <DIV CLASS="col-md-10"><INPUT TYPE="email" NAME="email" ID="reg_email" CLASS="form-control"
                                                      value=""></DIV>
                    </DIV>
                    <DIV CLASS="row">
                        <DIV CLASS="col-md-2">Password:</DIV>
                        <DIV CLASS="col-md-10"><INPUT TYPE="password" NAME="password" ID="reg_password"
                                                      CLASS="form-control"></DIV>
                    </DIV>
                    <DIV STYLE="margin-top: 15px;">
                        <DIV CLASS="col-md-12">
                            <button class="btn btn-block btn-primary">
                                Register
                            </button>
                        </DIV>
                    </DIV>
                </FORM>
                <DIV CLASS="clearfix"></DIV>
            </div>
        </div>
    </div>
</div>

<SCRIPT>
    function handlelogin(action) {
        if (isUndefined(action)) {
            action = "verify";
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
                    alert(data["Reason"], "Login");
                } else {
                    switch (action) {
                        case "login":
                            token = data["Token"];
                            login(data["User"]);
                            $("#loginmodal").modal("hide");
                            break;
                        case "forgotpassword":
                        case "verify":
                            alert(data["Reason"], "Login");
                            break;
                        case "logout":
                            removeCookie();
                            $(".loggedin").hide();
                            $(".loggedout").show();
                            $(".clear_loggedout").html("");
                            $(".profiletype").hide();
                            userdetails = false;
                            if (redirectonlogout) {
                                window.location = "http://localhost/ai/public/index";
                            } else {
                                switch (currentRoute) {
                                    case "index"://resave order as it's deleted in removeCookie();
                                        if (theorder.length > 0) {
                                            createCookieValue("theorder", JSON.stringify(theorder));
                                        }
                                        break;
                                }
                            }
                            break;
                    }
                }
            } catch (e) {
                alert(result, "Login");
            }
        });
    }

    var minlength = 5;

    $(function () {
        $("form[name='regform']").validate({
            rules: {
                name: "required",
                phone: "phonenumber",
                email: {
                    required: true,
                    email: true,
                    remote: {
                        url: 'http://localhost/ai/public/user/info',
                        type: "post",
                        data: {
                            action: "testemail",
                            email: function () {
                                return $('#reg_email').val();
                            },
                            user_id: "0"
                        }
                    }
                },
                password: {
                    minlength: minlength
                }
            },
            messages: {
                name: "Please enter your name",
                password: {
                    required: "Please provide a password",
                    minlength: "Your new password must be at least " + minlength + " characters long"
                },
                email: "Please enter a valid and unique email address"
            },
            submitHandler: function (form) {
                var formdata = getform("#regform");
                formdata["action"] = "registration";
                formdata["_token"] = token;
                $.post(webroot + "auth/login", formdata, function (result) {
                    if (result) {
                        try {
                            var data = JSON.parse(result);
                            alert(data["Reason"], "Registration");
                        } catch (e) {
                            alert(result, "Registration");
                        }
                    }
                });
                return false;
            }
        });
    });

</SCRIPT>
<div class="modal" id="alertmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="form-group">
                    <h4 class="modal-title" id="alertmodallabel">Title</h4>
                </div>
                <DIV ID="alertmodalbody"></DIV>
                <button class="btn btn-block btn-warning" data-dismiss="modal" STYLE="margin-top: 15px;">
                    Ok
                </button>
            </div>
        </div>
    </div>
</div>


<nav class="navbar-default navbar-fixed-bottom navbar navbar-full navbar-dark bg-danger dont-print" style="z-index: 1;">

    <DIV style="" CLASS="text-xs-center">


        <a class="btn pull-left" style="color:white;"
           href="#">
            0.169249 </a>


        <a class="btn btn-warning pull-right" id="checkout"
           href="#collapseCheckout">
            $17.21 |
            CHECKOUT
        </a>


    </DIV>

</nav>

<div class="modal loading" ID="loadingmodal"></div>
</body>

<SCRIPT>
    var currentURL = "http://localhost/ai/public/index";
    var token = "GIkQKtDq0PREn81tSYA5jAfnvAcdGCZ8a7v5Bg0Y";
    var webroot = "http://localhost/ai/public/";
    var redirectonlogout = false;
    var addresskeys = new Array;
    var userdetails = false;
    var currentRoute = "index";

    (function () {
        var proxied = window.alert;
        window.alert = function () {
            var title = "Alert";
            if (arguments.length > 1) {
                title = arguments[1];
            }
            $("#alertmodalbody").html(arguments[0]);
            $("#alertmodallabel").text(title);
            $("#alertmodal").modal('show');
        };
    })();

    $(document).ready(function () {
        $body = $("body");
        $(document).on({
            ajaxStart: function () {
                $body.addClass("loading");
            },
            ajaxStop: function () {
                $body.removeClass("loading");
            }
        });
    });

    function login(user) {
        userdetails = user;
        var keys = Object.keys(user);
        for (var i = 0; i < keys.length; i++) {
            var key = keys[i];
            var val = user[key];
            createCookieValue("session_" + key, val);
            $(".session_" + key).text(val);
            $(".session_" + key + "_val").val(val);
        }
        $(".loggedin").show();
        $(".loggedout").hide();
        $(".profiletype").hide();
        $(".profiletype" + user["profiletype"]).show();

        var HTML = '';
        if (user["Addresses"].length > 0) {
            HTML += '<SELECT style="border-top: 0 !important; " class="form-control" id="saveaddresses" onchange="addresschanged();"><OPTION VALUE="0">Select a saved address</OPTION>';
            addresskeys = Object.keys(user["Addresses"][0]);
            for (i = 0; i < user["Addresses"].length; i++) {
                var tempHTML = '<OPTION';
                var streetformat = "[number] [street], [city]";
                for (var keyID = 0; keyID < addresskeys.length; keyID++) {
                    var keyname = addresskeys[keyID];
                    var value = user["Addresses"][i][keyname];
                    streetformat = streetformat.replace("[" + keyname + "]", value);
                    if (keyname == "id") {
                        keyname = "value";
                    }
                    tempHTML += ' ' + keyname + '="' + value + '"'
                }
                HTML += tempHTML + '>' + streetformat + '</OPTION>';
            }
            HTML += '</SELECT>';
        }
        $("#addressdropdown").html(HTML);
    }

    function addresschanged() {
        var Selected = $("#saveaddresses option:selected");
        for (var keyID = 0; keyID < addresskeys.length; keyID++) {
            var keyname = addresskeys[keyID];
            $("#add_" + keyname).val($(Selected).attr(keyname));
        }
        keyname = $(Selected).text();
        if ($(Selected).val() == 0) {
            keyname = '';
        }
        $("#formatted_address").val(keyname);
    }

    $(document).ajaxComplete(function (event, request, settings) {
        if (request.status != 200 && request.status > 0) {//not OK, or aborted
            alert(request.statusText + "<P>URL: " + settings.url, "AJAX error code: " + request.status);
        }
    });
</SCRIPT>

<a style="color:white;margin-top:.25rem;" href="#" class="dropdown-toggle nav-link"
   data-toggle="dropdown"
   aria-haspopup="true"
   aria-expanded="true">
    <i class="fa fa-user no-padding-margin"></i></a>
<ul class="dropdown-menu  dropdown-menu-right">


    <li>
        <A HREF="http://localhost/ai/public/list/users">Users list</A><BR><A
                HREF="http://localhost/ai/public/list/restaurants">Restaurants list</A><BR><A
                HREF="http://localhost/ai/public/list/useraddresses">Useraddresses list</A><BR><A
                HREF="http://localhost/ai/public/list/orders">Orders list</A><BR>
        <HR>
        <A HREF="http://localhost/ai/public/editmenu">Edit Menu</A><BR>
        <A HREF="http://localhost/ai/public/list/debug">Debug log</A>

    </li>

    <li>
                                        <SPAN CLASS="session_name dropdown-item">
                                            <i class="fa fa-home"></i></SPAN>
    </li>
    <li>
        <A HREF="http://localhost/ai/public/list/all"
           CLASS="profiletype  dropdown-item profiletype1"> <i class="fa fa-home"></i>
            Admin</A>
    </li>
    <li>


        <A HREF="http://localhost/ai/public/list/useraddresses" class="dropdown-item"> <i
                    class="fa fa-home"></i> Addressess</A>
    </li>
    <li>

        <A HREF="http://localhost/ai/public/user/info" class="dropdown-item"> <i
                    class="fa fa-home"></i>
            Profile</A>

    </li>

    <li>


        <A ONCLICK="handlelogin('logout');" CLASS="hyperlink dropdown-item"> <i
                    class="fa fa-home"></i> Log out</A>
        <A CLASS="loggedout dropdown-item hyperlink" data-toggle="modal"
           data-target="#loginmodal"> <i class="fa fa-home"></i> Log
            In</A>


    </li>


</ul>


</html>