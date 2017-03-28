<?php
    startfile("popups_menu");
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
            $toppings = Query("SELECT * FROM " . $Table . " ORDER BY id asc, type ASC, name ASC", true);
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
?>
<div class="col-lg-3 col-md-12 bg-white">
    @foreach ($categories as $category)
        <?php
            $toppings_extra = '<SPAN TITLE="Addons are extra">+</SPAN>';
            $catclass = toclass($category['category']);
            $classlist[] = $catclass;
            $menuitems = Query("SELECT * FROM menu WHERE category = '" . $category['category'] . "' order by id", true);
            $menuitemcount = count($menuitems);
            if ($itemsInCol + $menuitemcount > $maxmenuitemspercol && $CurrentCol < 3) {
                $itemsInCol = 0;
                $CurrentCol += 1;
                //echo '</DIV><div class="col-md-4" style="background:white;">';
            }
            $itemsInCol += $menuitemcount;
        ?>
        <div class="bg-inverse list-group-item">
            <h2>   {{$category['category']}}</h2>
        </div>
        @foreach ($menuitems as $menuitem)
            <button class="cursor-pointer list-group-item list-group-item-action d-flex justify-content-start item_{{ $catclass }}"
                 itemid="{{$menuitem["id"]}}"
                 itemname="{{trim($menuitem['item'])}}"
                 itemprice="{{$menuitem['price']}}"
                 itemsize="{{ getsize($menuitem['item'], $isfree) }}"
                 itemcat="{{$menuitem['category']}}"
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

                <span class="align-middle rounded-circle bg-warning sprite sprite-{{$itemclass}} sprite-medium"></span>
                <span class="align-middle">{{$menuitem['item']}} </span>
                <span class="ml-auto align-middle btn-sm-padding"> ${{number_format($menuitem["price"], 2)}}<?= $icon; ?></span>
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

            <div class="list-group-item bg-white">
                <button data-dismiss="modal" class="btn btn-sm mr-3 bg-transparent"><i class="fa fa-close"></i></button>
                <h2 class="" id="myModalLabel">
                    <SPAN ID="modal-itemname"></SPAN><br>
                    <small ID="toppingcost" class="nowrap text-muted">+$<SPAN id="modal-toppingcost"></SPAN> per topping</small>
                </h2>
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
</script>

<!-- end order menu item Modal -->
<!-- end menu cache -->
<?php endfile("popups_menu"); ?>




