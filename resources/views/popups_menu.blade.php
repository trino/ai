<!-- menu cache saved from: {{ now() }} -->
<div class="col-md-8">
    <div class="card" >

        <div class="card-block card-columns">
            <?php
            $tables = array("toppings", "wings_sauce");
            $qualifiers = array("DEFAULT" => array("1/2", "1x", "2x", "3x"));
            $categories = Query("SELECT * FROM menu GROUP BY category ORDER BY id", true);
            $isfree = collapsearray(Query("SELECT * FROM additional_toppings", true), "price", "size");
            $deliveryfee = $isfree["Delivery"];
            $addons = array();
            $a = 0;

            if (!function_exists("getsize")) {
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
                function getaddons($Table, &$isfree, &$qualifiers, &$addons) {
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

                        $addons[$Table][$topping["type"]][] = explodetrim($topping["name"]);
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
            }

            $toppings_display = getaddons("toppings", $isfree, $qualifiers, $addons);
            $wings_display = getaddons("wings_sauce", $isfree, $qualifiers, $addons);
            $classlist = array();

            foreach ($categories as $category) {
            $catclass = toclass($category['category']);
            $classlist[] = $catclass;
            $imagefile = $catclass;
            //if(right($imagefile, 5) == "pizza" || !file_exists(public_path() . '/' . $imagefile . ".png")){$imagefile="pizza";}
            if (right($imagefile, 5) == "pizza") {
                $imagefile = "pizza";
            }
            $menuitems = Query("SELECT * FROM menu WHERE category = '" . $category['category'] . "'", true);
            ?>
<div class="clearfix"></div>

            <a class=" head_{{ $catclass }}" data-toggle="collapse" href="#collapse{{$category["id"]}}_cat">
                <h5 class="text-danger ">{{$category['category']}}</h5>
            </a>

            <div class="collapse mb-1 in" id="collapse{{$category['id']}}_cat">

                <div class="">
                @foreach ($menuitems as $menuitem)
                    <div
                            style="padding: 0 !important;"
                            class=" menuitem item_{{ $catclass }}"
                            itemid="{{$menuitem["id"]}}"
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
                            $HTML = 'href="#2" data-toggle="modal" data-backdrop="static" data-target="#menumodal" onclick="loadmodal(this);"';
                            $icon = '<i class="fa fa-chevron-right pull-right"></i>';
                        } else {
                            $HTML = 'href="#1" onclick="additemtoorder(this);"';
                            $icon = '';
                        }

                        ?>
                        <SPAN>
                                <a <?= $HTML; ?> class="btn btn-warning waves-effect waves-effect" style="border:0;width: 100%;border-radius:0 !important;">
                                    <DIV CLASS="pull-left sprite sprite-<?= $imagefile; ?> sprite-medium"></DIV>
                                    <span class=" pull-left itemname" style="">{{$menuitem['item']}}</span>
                                    <span class="pull-right"  style="font-size: 75%;"> ${{number_format($menuitem["price"], 2)}}
                                        <?= $icon; ?>

                                    </span>
                                    <div class="clearfix"></div>
                                </a>
                            </SPAN>
                    </div>
                @endforeach
            </div></div>
            <?
            $a++;
            }
            ?>
        </div>



    </div>
</div>

<!-- order menu item Modal -->
<div class="modal" id="menumodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">


                <h4 id="myModalLabel">
                    <button type="button" class="btn-sm btn btn-outline-info   waves-effect btn pull-left" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-arrow-left"></i>
                    </button>
                    <SPAN ID="modal-itemname"></SPAN> $<SPAN ID="modal-itemprice"></SPAN>
                </h4>
                <div class="mt-1"></div>

                <div style="display: none;" id="modal-hiddendata">
                    <SPAN ID="modal-itemid"></SPAN>
                    <SPAN ID="modal-toppingcost"></SPAN>
                    <SPAN ID="modal-itemsize"></SPAN>
                    <SPAN ID="modal-itemcat"></SPAN>
                </div>

                <ul class="list-group">
                    <div ID="modal-wings-original">
                        <select class="form-control select2 wings_sauce" multiple="multiple" data-placeholder="1st Pound" type="wings_sauce">
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
                        <div style="margin-bottom:.1rem;" ID="modal-toppings-original-ordinal">1st Pizza</div>
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
                <div class=""></div>

                <button type="button" class=" btn-secondary waves-effect btn pull-left" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-close"></i>
                </button>


                <button data-dismiss="modal" class="btn-warning btn pull-right" onclick="additemtoorder();">
                    ADD TO ORDER
                </button>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<!-- end order menu item Modal -->

<script>
    var tables = <?= json_encode($tables); ?>;
    var alladdons = <?= json_encode($addons); ?>;
    var freetoppings = <?= json_encode($isfree); ?>;
    var qualifiers = <?= json_encode($qualifiers); ?>;
    var theorder = new Array;
    var toppingsouterhtml, wingsauceouterhtml;
    var deliveryfee = <?= $deliveryfee; ?>;
    var classlist = <?= json_encode($classlist); ?>;
    var ordinals = ["1st", "2nd", "3rd", "4th", "5th", "6th", "7th", "8th", "9th", "10th"];
</script>
<!-- end menu cache -->