<?php
    $filename = resource_path("orders") . "/" . $orderid . ".json";
    if(isset($JSON)){
        $style = 2;
        if($JSON && $JSON != "false"){
            if(file_exists($filename)){
                die(file_get_contents($filename));//only the JSON is desired, send it
            }
            echo json_encode(array("Status" => false, "Reason" => "File not found"));
            die();
        }
    } else if(!isset($style)) {
        $style=1;
    }
    $Order = first("SELECT orders.*, users.name, users.id as userid, users.email FROM orders, users WHERE orders.id = " . $orderid . " HAVING user_id = users.id");
    if(!$Order){ echo 'Order not found'; return false; }
    switch($style){
        case 1: $colspan = 8; break;
        case 2:
            $colspan = 2;
            $imagefile = '<img class="pull-left" src="' . webroot("public/pizza.png") . '" style="width:22px;margin-right:5px;">';
            $ordinals = array("First", "Second", "Third", "Fourth", "Fifth", "Sixth", "Seventh", "Eighth", "Ninth", "Tenth");
            break;
    }
?>
@if($style==1)

    Order #: {{ $orderid }}
    <br>
    Ordered On: {{ $Order["placed_at"] }}



    <TABLE WIDTH="100%" class="table table-sm table-bordered">
        <THEAD>

            <TR>
                <TH>#</TH>
                <TH>Name</TH>
                <TH>Sub-total</TH>
                <TH>Addons</TH>
                <TH>Addon Count</TH>
                <TH>Size</TH>
                <TH>Addon Cost</TH>
                <TH>Total</TH>
            </TR>
        </THEAD>
@else
    <TABLE WIDTH="100%" class="noborder" cellspacing="0" cellpadding="0">
@endif
    <?php
        $integrity=true;
        if(!function_exists("findkey")){
            function findkey($arr, $key, $value){
                return array_search($value, array_column($arr, $key));
            }

            //finds the size of the item
            function getsize($itemname, $isfree){
                $currentsize = "";
                foreach ($isfree as $value) {
                    $size = $value["size"];
                    $cost = $value["price"];
                    if (!is_array($cost)) {
                        if (textcontains($itemname, $size) && strlen($size) > strlen($currentsize)) {
                            $currentsize = $size;
                        }
                    }
                }
                return $currentsize;
            }

            function textcontains($text, $searchfor){
                return strpos(strtolower($text), strtolower($searchfor)) !== false;
            }
        }

        //check all data again, do not trust the prices from the user!!
        $tables = array("toppings", "wings_sauce", "additional_toppings");
        foreach($tables as $ID => $table){
            $tables[$table] = Query("SELECT * FROM " . $table, true);
            unset($tables[$ID]);
        }

        $deliveryfee = findkey($tables["additional_toppings"], "size", "Delivery");
        $deliveryfee = $tables["additional_toppings"][$deliveryfee]["price"];
        if(file_exists($filename)){
            $filename = file_get_contents($filename);
            try{
                $items = json_decode($filename);
                $itemIDs = array();
                foreach($items as $item){
                    if(isset($item->itemid)){
                        $itemIDs[] = $item->itemid;
                    }
                }
                $itemIDs = implode(",", array_unique($itemIDs));
                if(!$itemIDs){die("Order is empty");}

                $menu = Query("SELECT * FROM menu WHERE id IN(" . $itemIDs . ")", true);

                //convert the JSON into an HTML receipt, using only item/addon IDs, reobtaining cost/names from the database for security
                $subtotal = 0;
                foreach($items as $ID => $item){
                    if(is_object($item)){
                        $menukey = findkey($menu, "id", $item->itemid);
                        $menuitem = $menu[$menukey];
                        $size = getsize($menuitem["item"], $tables["additional_toppings"]);
                        $addonscost = "0.00";
                        if($size){
                            $addonscost = findkey($tables["additional_toppings"], "size", $size);
                            $addonscost = $tables["additional_toppings"][$addonscost]["price"];
                        }
                        $itemtotal = $menuitem["price"];
                        $paidtoppings = 0;
                        $freetoppings = 0;

                        $totaladdons=0;
                        foreach($tables as $name => $data){
                            if(isset($menuitem[$name])){
                                $totaladdons += $menuitem[$name];
                            }
                        }

                        switch($style){
                            case 1:
                                echo '<TR><TD>' . ($ID+1) . '</TD><TD TITLE="' . var_export($item, true) . '">' . $item->itemname . '</TD><TD ALIGN="RIGHT" TITLE="' . print_r($menuitem, true) . '">$' . number_format($menuitem["price"], 2) . '</TD><TD>';
                                break;
                            case 2:
                                echo '<TR><TD>' . $imagefile . $item->itemname . '</TD><TD ALIGN="RIGHT" WIDTH="5%">';
                                break;
                        }
                        $HTML="";
                        if(isset($item->itemaddons)){
                            if($style==1){$HTML = '<TABLE BORDER="1" WIDTH="100%"><THEAD><TR><TH WIDTH="5%">#</TH><TH>Addons</TH></THEAD></TR>';}
                            $addoncount = count($item->itemaddons);
                            foreach($item->itemaddons as $addonID => $addon){
                                $toppings=array();
                                if(isset($addon->tablename)){
                                    $tablename = $addon->tablename;
                                    switch($tablename){
                                        case "toppings":    $itemtype = "Pizza"; break;
                                        case "wings_sauce": $itemtype = "Pound"; break;
                                    }
                                    if(isset($addon->addons)){
                                        $toppings = $addon->addons;
                                    }
                                }
                                $newtoppings = array();
                                foreach($toppings as $topping){
                                    $toppingkey = findkey($tables[$tablename], "id", $topping->id);
                                    $topping = $tables[$tablename][$toppingkey];
                                    if($topping["isfree"]){
                                        $freetoppings++;
                                    } else {
                                        $paidtoppings++;
                                    }
                                    $newtoppings[] = '<SPAN TITLE="' . print_r($topping, true) . '">' . $topping["name"] . '</SPAN>';
                                }

                                if($style==1){
                                    $itemtitle = $itemtype . ' #' . ($addonID+1);
                                    $HTML .= '<TR><TD NOWRAP>' . $itemtitle . '</TD><TD>' . implode(", ", $newtoppings) . '</TD></TR>';
                                } else {
                                    $itemtitle = "";
                                    if($addoncount > 1){$itemtitle = $ordinals[$addonID] . " " . $itemtype . ": ";}
                                    $HTML .= $itemtitle . implode(", ", $newtoppings);
                                }
                            }
                            if($style==1){echo $HTML . '</TABLE>';}
                        }

                        $toppingscost = $addonscost*$paidtoppings;
                        $itemtotal = $menuitem["price"] + $toppingscost;

                        if($style==1){
                            echo '</TD><TD>';
                            if($totaladdons){ echo $paidtoppings . ' paid, ' . $freetoppings . ' free';}
                            echo '</TD><TD>' . $size . '</TD><TD ALIGN="RIGHT">$' . number_format($addonscost, 2) . '</TD><TD ALIGN="RIGHT" TITLE="User side: $' . $item->itemprice . '"';
                            if (number_format($item->itemprice,2) <> number_format($itemtotal, 2)){
                                echo ' STYLE="COLOR: red;"';
                                $integrity = false;
                            }
                            echo '>';
                        }
                        echo '$' . number_format($itemtotal, 2) . '</TD></TR>';
                        if($style==2 && $HTML){
                            echo '<TR><TD COLSPAN="' . $colspan . '">' . $HTML . '</TD></TR>';
                        }
                        $subtotal += $itemtotal;
                    }
                }

                $tax_percent = 0.13;
                $tax = ($subtotal + $deliveryfee) * $tax_percent;
                $total = $subtotal + $deliveryfee + $tax;
                $colspanminus1 = $colspan-1;
                echo '<TR><TD COLSPAN="' . $colspanminus1 . '" ALIGN="RIGHT">Sub-total:&nbsp;</TD><TD ALIGN="RIGHT">$' . number_format($subtotal, 2) . '</TD></TR>';
                echo '<TR><TD COLSPAN="' . $colspanminus1 . '" ALIGN="RIGHT">Delivery:&nbsp;</TD><TD ALIGN="RIGHT">$' . number_format($deliveryfee, 2) . '</TD></TR>';
                echo '<TR><TD COLSPAN="' . $colspanminus1 . '" ALIGN="RIGHT">Tax:&nbsp;</TD><TD ALIGN="RIGHT">$' . number_format($tax, 2) . '</TD></TR>';
                echo '<TR><TD COLSPAN="' . $colspanminus1 . '" ALIGN="RIGHT">Total:&nbsp;</TD><TD ALIGN="RIGHT">$' . number_format($total, 2) . '</TD></TR>';
                if(!$integrity){
                    echo '<TR><TD COLSPAN="7" ALIGN="RIGHT">Integrity check</TD><TD ALIGN="RIGHT" STYLE="color:red;">FAIL</TD></TR>';
                }
            } catch (exception $e){
                echo 'Caught exception: ',  $e->getMessage() . " on line " . $e->getLine() . "<BR>";
                echo $filename;
            }
        ?>
        <TFOOT TITLE="{{ $filename }}">
            <TR>
                <TD COLSPAN="{{ $colspan }}">
                    <?php
                        if(isset($JSON)){
                            echo '<BUTTON CLASS="btn btn-secondary form-control" ONCLICK="orders(' . $orderid . ', true);">Load Order</BUTTON>';//viewing order from user's order list popup
                        } else {
                            echo $Order["name"] . " - " . $Order["email"] . "<BR>" . $Order["phone"] . " " . $Order["cell"] . "<BR>" . $Order["number"] . " " . $Order["street"] . '<BR>' . $Order["city"] . ", " . $Order["province"] . "<BR>" . $Order["postalcode"];//viewing order from anywhere else
                        }
                    ?>
                </TD>
            </TR>
        </TFOOT>
    <?php
        } else {
            echo '<TR><TD COLSPAN="' . $colspan . '" ALIGN="CENTER"><B>ORDER FILE NOT FOUND</B></TD></TR>';
        }
    ?>
</TABLE>