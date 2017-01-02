<?php
startfile("popups_menu");

///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists("getsize")) {
//gets the size of the pizza
    function getsize($itemname, &$isfree)
    {
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
    function textcontains($text, $searchfor)
    {
        return strpos(strtolower($text), strtolower($searchfor)) !== false;
    }

//process addons, generating the option group dropdown HTML, enumerating free toppings and qualifiers
    function getaddons($Table, &$isfree, &$qualifiers, &$addons, &$groups)
    {
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
            if ($topping["group"] > 0) {
                $groups[$Table][$topping["name"]] = $topping["group"];
            }
            $toppings_display .= '<option value="' . $topping["id"] . '" type="' . $topping["type"] . '">' . $topping["displayname"] . '</option>';
        }
        return $toppings_display . '</optgroup>';
    }

//same as explode, but makes sure each cell is trimmed
    function explodetrim($text, $delimiter = ",", $dotrim = true)
    {
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
    function toclass($text)
    {
        return strtolower(str_replace(" ", "_", $text));
    }
}

$qualifiers = array("DEFAULT" => array("1/2", "1x", "2x", "3x"));
$categories = Query("SELECT * FROM menu GROUP BY category ORDER BY id", true);
$isfree = collapsearray(Query("SELECT * FROM additional_toppings", true), "price", "size");
$deliveryfee = $isfree["Delivery"];
$addons = array();
$classlist = array();
$groups = array();


///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////


$toppings_display = getaddons("toppings", $isfree, $qualifiers, $addons, $groups);
$wings_display = getaddons("wings_sauce", $isfree, $qualifiers, $addons, $groups);
$tables = array("toppings", "wings_sauce");
?>

<div class="col-md-9" style="background:white;padding:0 0 1rem 0 !important;">
    <div class="col-md-4" style="background:white;">
        @foreach ($categories as $category)

            <div class="list-group">
                <div class="btn-block text-danger text-xs-center text-uppercase" style="font-weight: bold;padding-top:1rem !important;">  {{$category['category']}}  </div>

                <?php
                $catclass = toclass($category['category']);
                $classlist[] = $catclass;
                $menuitems = Query("SELECT * FROM menu WHERE category = '" . $category['category'] . "'", true);
                ?>

                @foreach ($menuitems as $menuitem)

                    <div style="" class="list-group-item-action item_{{ $catclass }}"
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
                        if ($total) {
                            $HTML = ' data-toggle="modal" data-backdrop="static" data-target="#menumodal" onclick="loadmodal(this);"';
                            $icon = '+';
                        } else {
                            $HTML = ' onclick="additemtoorder(this, -1);"';
                            $icon = '';
                        }
                        echo $HTML;
                        ?>
                    >
                        <div class="bg-warning pull-left sprite sprite-<?= $catclass; ?> sprite-medium"></div>
                        <span style="padding-top:.45rem !important;" class="pull-left itemname">{{$menuitem['item']}} </span>
                        <span style="padding-top:.45rem !important;" class="pull-right text-muted itemname"> ${{number_format($menuitem["price"], 2)}}<?= $icon; ?></span>
                        <div class="clearfix"></div>

                    </div>

                @endforeach


            </div>

            @if($catclass=="dips" || $catclass=="sides")
    </div>
    <div class="col-md-4" style="background:white;">
        @endif



        @endforeach
    </div>
</div>

<!-- order menu item Modal -->
<!-- order menu item Modal -->
<!-- order menu item Modal -->
<!-- order menu item Modal -->
<!-- order menu item Modal -->
<!-- order menu item Modal -->
<!-- order menu item Modal -->
<!-- order menu item Modal -->
<!-- order menu item Modal -->
<!-- order menu item Modal -->


<div class="modal" id="menumodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div style="display: none;" id="modal-hiddendata">
                <SPAN ID="modal-itemprice"></SPAN>
                <SPAN ID="modal-itemid"></SPAN>
                <SPAN ID="modal-toppingcost"></SPAN>
                <SPAN ID="modal-itemsize"></SPAN>
                <SPAN ID="modal-itemcat"></SPAN>
            </div>

            <div class="modal-header">
                <button type="button" class="close" data-popup-close="menumodal" old-data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
                <strong id="myModalLabel"> <SPAN ID="modal-itemname"></SPAN> </strong>
            </div>

            <div class="modal-body" style="padding: 0 !important;">
                <DIV ID="addonlist" class="addonlist"></DIV>
                <div class="clearfix"></div>
            </div>

            <div class="modal-footer row">
                <div class="col-md-5">
                <DIV ID="removelist" style="color: red;"></div>

                </div>                <div class="col-md-7">

                <button data-popup-close="menumodal" old-data-dismiss="modal" id="additemtoorder" class="btn btn-warning pull-right" onclick="additemtoorder();"> ADD TO ORDER</button>
                <div class="pull-right btn dont"> $<SPAN ID="modal-itemtotalprice"></SPAN></div>
            </div>
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