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
        }

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
                <div class="card-block bg-danger"
                     style="padding-top:.75rem !important;padding-bottom:.75rem !important;">
                    <h4 class="pull-left"><i class="fa fa-home" aria-hidden="true"></i> Pizza Delivery
                        <input type="TEXT" id="search" class="searchbox" placeholder="Search"
                               oninput="search(this, event);"></h4>

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

                            <h5 class="text-danger" data-toggle="collapse"
                                href="#collapse{{$category["id"]}}_cat">{{$category['category']}}</h5>
                            <div class="collapse list-group in m-b-1" id="collapse{{$category['id']}}_cat">
                                <?


                                $toppings_display = "";
                                $toppings = Query("SELECT * FROM toppings", true);
                                foreach ($toppings as $ID => $topping) {
                                    $toppings_display = $toppings_display . "  <option value='" . $topping["name"] . "''>" . $topping["name"] . "</option> ";
                                }
                                $wings_display = "";
                                $sauces = Query("SELECT * FROM wings_sauce", true);
                                foreach ($sauces as $ID => $sauce) {
                                    $wings_display = $wings_display . "  <option value='" . $sauce["name"] . "''>" . $sauce["name"] . "</option> ";
                                }
                                ?>
                                <?

                                $menuitems = Query("SELECT * FROM menu WHERE category = '" . $category['category'] . "'", true);
                                foreach ($menuitems as $menuitem) {
                                /*
                                * KEYWORDS
                                */
                                /*
                                $keywords = Query("SELECT * FROM keywords, menukeywords WHERE menuitem_id = " . $menuitem["id"] . " OR -menuitem_id = " . $menuitem["category_id"] . " HAVING keywords.id = keyword_id", true);
                                foreach ($keywords as $ID => $keyword) {
                                    $keywords[$ID] = '<SPAN TITLE="Weight: ' . $keyword["weight"] . '">' . ($keyword["synonyms"]) . '</SPAN>';
                                }
                                $menuitem["keywords"] = implode(", ", $keywords);
                                //$menuitem["Actions"] = '<A HREF="?id=' . $menuitem["id"] . '">Edit</A>';
                                */
                                $menuitem["price"] = number_format($menuitem["price"], 2);
                                ?>
                                <div class="clearfix"></div>
                                <div class="menuitem" menuitem="{{$menuitem["id"]}}">

                                    <a class="text-xs-left btn-block" data-toggle="modal"
                                       data-target="#myModal{{$menuitem['id']}}">
                                        <i class="pull-left fa fa-pie-chart text-warning"></i>
                                        <span class="pull-left itemname">{{$menuitem['item']}}</span>
                                        <span class="pull-right"> ${{$menuitem['price']}}</span>
                                        <div class="clearfix"></div>
                                    </a>

                                    <!-- Modal -->
                                    <div class="modal" id="myModal{{$menuitem['id']}}" tabindex="-1" role="dialog"
                                         aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    <div class="form-group">

                                                        <h4 class="modal-title" id="myModalLabel">{{$menuitem['item']}}  ${{$menuitem['price']}}</h4>
                                                    </div>
                                                    @if($menuitem['wings_sauce']>0)
                                                        <div class="form-group">
                                                            <select class="form-control select2" multiple="multiple"
                                                                    data-placeholder="Wings Sauce">
                                                                <option></option>
                                                                <optgroup label="Sauce">
                                                                    <?php echo $wings_display; ?>
                                                                </optgroup>
                                                                <optgroup label="Options">
                                                                    <option value="AZ">Well Done</option>
                                                                    <option value="CO">Lightly Done</option>
                                                                </optgroup>
                                                            </select></div>
                                                    @endif


                                                    @if($menuitem['toppings']>0)

                                                        @for ($i = 0; $i < $menuitem['toppings']; $i++)
                                                            <div class="form-group">
                                                                <div class="text-muted"></div>
                                                                <select class="form-control select2"  data-placeholder="Pizza Toppings #{{$i+1}}"
                                                                        multiple="multiple">
                                                                    <option></option>
                                                                    <optgroup label="Toppings">
                                                                        <?php echo $toppings_display; ?>
                                                                    </optgroup>
                                                                    <optgroup label="Options">
                                                                        <option value="AZ">Well Done</option>
                                                                        <option value="CO">Lightly Done</option>
                                                                    </optgroup>
                                                                </select>
                                                            </div>
                                                        @endfor

                                                    @endif

                                                    <button data-dismiss="modal" class="btn btn-block  btn-warning"
                                                            data-toggle="collapse"
                                                            href="#collapse{{$menuitem["id"]}}">
                                                        ADD TO ORDER
                                                    </button>


                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <!--div class="collapse" id="collapse{{$menuitem['id']}}">
                                    </div-->
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

    <script>

        $(".select2").select2({
            maximumSelectionSize: 4, placeholder: function () {
                $(this).data('placeholder')
            }
        });

        function search(element) {
            var searchtext = element.value.toLowerCase();
            $(".menuitem").each(function (index) {
                var ismatch = false;
                if (searchtext) {
                    var itemtext = $(this).find(".itemname").text().toLowerCase();
                    if (itemtext.indexOf(searchtext) > -1) {
                        ismatch = true;
                    }
                } else {
                    ismatch = true;
                }
                if (ismatch) {
                    //    $(this).removeClass("disabled");
                } else {
                    $(this).addClass("disabled");
                }
            });
        }


        /*
         $( ":checkbox" ).on( "click", function() {
         $( this ).parent().nextAll( "select" ).select2( "enable", this.checked );
         });
         $( "#demonstrations" ).select2( { placeholder: "Select2 version", minimumResultsForSearch: -1 } ).on( "change", function() {
         document.location = $( this ).find( ":selected" ).val();
         } );
         $( "button[data-select2-open]" ).click( function() {
         $( "#" + $( this ).data( "select2-open" ) ).select2( "open" );
         });
         */
    </script>

@endsection