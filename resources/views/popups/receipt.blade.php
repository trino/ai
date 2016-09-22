<TABLE BORDER="1" WIDTH="100%">
    <THEAD>
        <TR>
            <TD COLSPAN="8" ALIGN="center">
               <B>Order ID: {{ $orderid }}</B>
            </TD>
        </TR>
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
    <?php
        $integrity=true;

        function findkey($arr, $key, $value){
            return array_search($value, array_column($arr, $key));
        }

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

        //check all data again, do not trust the prices from the user!!
        $tables = array("toppings", "wings_sauce", "additional_toppings");
        foreach($tables as $ID => $table){
            $tables[$table] = Query("SELECT * FROM " . $table, true);
            unset($tables[$ID]);
        }

        $items = json_decode(file_get_contents(resource_path("orders") . "/" . $orderid . ".json"));
        $itemIDs = array();
        foreach($items as $item){
            $itemIDs[] = $item->itemid;
        }
        $itemIDs = implode(",", array_unique($itemIDs));
        if(!$itemIDs){die("Order is empty");}

        $menu = Query("SELECT * FROM menu WHERE id IN(" . $itemIDs . ")", true);

        $subtotal = 0;
        foreach($items as $ID => $item){
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

            echo '<TR><TD>' . ($ID+1) . '</TD><TD TITLE="' . var_export($item, true) . '">' . $item->itemname . '</TD><TD ALIGN="RIGHT" TITLE="' . print_r($menuitem, true) . '">$' . number_format($menuitem["price"], 2) . '</TD><TD>';
            if(isset($item->itemaddons)){
                echo '<TABLE BORDER="1" WIDTH="100%"><THEAD><TR><TH>#</TH><TH>Addons</TH></THEAD></TR>';
                foreach($item->itemaddons as $addonID => $addon){
                    $tablename = $addon->tablename;
                    switch($tablename){
                        case "toppings": $itemtype = "Pizza #"; break;
                        case "wings_sauce": $itemtype = "Pound #"; break;
                    }
                    $toppings = $addon->addons;
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

                    $itemtitle = $itemtype . ($addonID+1);
                    echo '<TR><TD>' . $itemtitle . '</TD><TD>' . implode(", ", $newtoppings) . '</TD></TR>';
                }
                echo '</TABLE>';
            }

            $toppingscost = $addonscost*$paidtoppings;
            $itemtotal = $menuitem["price"] + $toppingscost;
            echo '</TD><TD>' . $paidtoppings . ' paid, ' . $freetoppings . ' free</TD><TD>' . $size . '</TD><TD ALIGN="RIGHT">$' . number_format($addonscost, 2) . '</TD><TD ALIGN="RIGHT" TITLE="User side: $' . $item->itemprice . '"';
            if (number_format($item->itemprice,2) <> number_format($itemtotal, 2)){
                echo ' STYLE="COLOR: red;"';
                $integrity = false;
            }
            echo '>$' . number_format($itemtotal, 2) . '</TD></TR>';
            $subtotal += $itemtotal;
        }

        $tax_percent = 0.13;
        $tax = ($subtotal + deliveryfee) * $tax_percent;
        $total = $subtotal + deliveryfee + $tax;
        echo '<TR><TD COLSPAN="7" ALIGN="RIGHT">Sub-total</TD><TD ALIGN="RIGHT">$' . number_format($subtotal, 2) . '</TD></TR>';
        echo '<TR><TD COLSPAN="7" ALIGN="RIGHT">Delivery fee</TD><TD ALIGN="RIGHT">$' . number_format(deliveryfee, 2) . '</TD></TR>';
        echo '<TR><TD COLSPAN="7" ALIGN="RIGHT">HST</TD><TD ALIGN="RIGHT">$' . number_format($tax, 2) . '</TD></TR>';
        echo '<TR><TD COLSPAN="7" ALIGN="RIGHT">Total</TD><TD ALIGN="RIGHT">$' . number_format($total, 2) . '</TD></TR>';
        if(!$integrity){
            echo '<TR><TD COLSPAN="7" ALIGN="RIGHT">Integrity check</TD><TD ALIGN="RIGHT" STYLE="color:red;">FAIL</TD></TR>';
        }
    ?>
</TABLE>
