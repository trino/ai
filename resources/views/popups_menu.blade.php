<div>
    <div>
        <?php
            startfile("popups_menu");
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
                function getaddons($Table, &$isfree, &$qualifiers, &$addons, &$groups) {
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
                        if ($topping["group"] > 0){
                            $groups[$Table][$topping["name"]] = $topping["group"];
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

            $groups = array();
            $toppings_display = getaddons("toppings", $isfree, $qualifiers, $addons, $groups);
            $wings_display = getaddons("wings_sauce", $isfree, $qualifiers, $addons, $groups);
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

        <div class="clearfix">

            <a class=" head_{{ $catclass }}" data-toggle="collapse" href="#collapse{{$category["id"]}}_cat">
                <h5 class="text-danger pt-1 ">{{$category['category']}}</h5>
            </a>

            <div class=" collapse in" id="collapse{{$category['id']}}_cat">

                @foreach ($menuitems as $menuitem)
                    <div
                            style="border-radius: 0 !important;border:0 !important;padding-bottom:.5rem !important;"
                            class="btn btn-secondary col-xs-6 col-md-3 btn-sm item_{{ $catclass }}"
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
                            $icon = '+';
                        } else {
                            $HTML = 'href="#1" onclick="additemtoorder(this, -1);"';
                            $icon = '';
                        }
                    ?>
                    <!-- why is this div required? -->
                        <div>
                            <a <?= $HTML; ?>>
                                <DIV CLASS="pull-left sprite sprite-<?= $imagefile; ?> sprite-medium"></DIV>
                                <span class="pull-left itemname" style="vertical-align: top !important;">{{$menuitem['item']}} </span><br>
                                <span class="pull-left text-muted itemname"> ${{number_format($menuitem["price"], 2)}}<?= $icon; ?></span>


                                <div class="clearfix"></div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <?
        $a++;
        }
        ?>
    </div>
</div>

<!-- order menu item Modal -->
<div class="modal" id="menumodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
     data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-popup-close="menumodal" old-data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>

                <h4 id="myModalLabel">
                    <SPAN ID="modal-itemname"></SPAN> $<SPAN ID="modal-itemtotalprice"></SPAN>
                </h4>
                <div class="mt-1"></div>

                <div style="display: none;" id="modal-hiddendata">
                    <SPAN ID="modal-itemprice"></SPAN>
                    <SPAN ID="modal-itemid"></SPAN>
                    <SPAN ID="modal-toppingcost"></SPAN>
                    <SPAN ID="modal-itemsize"></SPAN>
                    <SPAN ID="modal-itemcat"></SPAN>
                </div>


                <div class="row" style="border-bottom:2px solid #dadada; margin-bottom:.5rem !important;">
                    <div class="col-xs-12">
                        <DIV ID="removelist" style="color: red;"></div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-xs-12">
                        <DIV ID="addonlist" class="addonlist"></DIV>
                    </div>
                </div>

                <button data-popup-close="menumodal" old-data-dismiss="modal" id="additemtoorder" class="btn btn-warning-outline pull-right" onclick="additemtoorder();">
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
    var groups = <?= json_encode($groups); ?>;
    var theorder = new Array;
    var deliveryfee = <?= $deliveryfee; ?>;
    var classlist = <?= json_encode($classlist); ?>;
    var ordinals = ["1st", "2nd", "3rd", "4th", "5th", "6th", "7th", "8th", "9th", "10th"];
</script>
<!-- end menu cache -->
<?php endfile("popups_menu"); ?>