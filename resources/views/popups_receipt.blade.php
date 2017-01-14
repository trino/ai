<?php
startfile("popups_receipt");
$debugmode = !islive();
$debug = "";
$Order = first("SELECT orders.*, users.name, users.id as userid, users.email FROM orders, users WHERE orders.id = " . $orderid . " HAVING user_id = users.id");
$filename = resource_path("orders") . "/" . $orderid . ".json";
if (isset($JSON)) {//get raw JSON instead
    $style = 2;
    if ($JSON && $JSON != "false") {
        if (file_exists($filename)) {
            $Order["Order"] = json_decode(file_get_contents($filename));
            echo json_encode($Order);
            die();//only the JSON is desired, send it
        }
        echo json_encode(array("Status" => false, "Reason" => "File not found"));
        die();
    }
} else if (!isset($style)) {
    $style = 1;
}
if (!$Order) {
    echo 'Order not found';
    return false;
}
switch ($style) {
    case 1:
        $colspan = 6;
        break;
    case 2:
        $colspan = 3;
        $ordinals = array("1st", "2nd", "3rd", "4th", "5th", "6th", "7th", "8th", "9th", "10th");
        break;
}
//Hack to put CSS inline for emails
if (!isset($inline)) {
    $inline = false;
}
$GLOBALS["inline"] = $inline;
if (!function_exists("inline")) {
    function inline($Class, $OnlyInline = false)
    {
        if ($GLOBALS["inline"]) {
            $Style = array();
            $Class = explode(" ", $Class);
            foreach ($Class as $Classname) {
                switch (strtolower($Classname)) {
//table-sm
                    case "table":
                        $Style[] = "width: 100%; max-width: 100%; margin-bottom: 0; border-collapse: collapse; background-color: transparent; display: table; border-spacing: 2px;";
                        break;
                    case "table-bordered":
                        $Style[] = "border: 1px solid #eceeef; ";
                        break;
                    case "bg-danger":
                        $Style[] = "background-color: #d9534f!important;";
                        break;
                    case "table-inverse":
                        $Style[] = "border: 0; color: #eceeef; background-color: #373a3c;";
                        break;
                }
            }
            return ' style="' . implode(" ", $Style) . '"';
        } else if (!$OnlyInline) {
            return ' class="' . $Class . '"';
        }
    }
}
//edit countdown timer duration
$minutes = 40;
$seconds = 0;
?>



@if($style==1)


    <div class="card text-xs-center">
        <h2>ARRIVING IN</h2>
        <h2 CLASS="countdown" minutes="<?= $minutes; ?>" seconds="<?= $seconds; ?>">123123</h2>
        @if($Order["deliverytime"])
            <?php
            $Time = right($Order["deliverytime"], 4);
            if (is_numeric($Time)) {
                echo left($Order["deliverytime"], strlen($Order["deliverytime"]) - 4) . GenerateTime(intval($Time));
            } else if ($Order["deliverytime"] == "Deliver Now") {
                echo "ASAP";
            } else {
                echo $Order["deliverytime"];
            }
            ?>
        @endif
    </div>
    <div class="card">
        <h6>Delivery Address</h6>
    <?php
    echo $Order["name"] . "<BR>" . $Order["number"] . " " . $Order["street"] . '<BR>' . $Order["city"] . " "
        . $Order["province"] . " " . $Order["postalcode"] . '<BR>' . $Order["unit"] . '';
    ?>
    <!--TR>
        <TD>Ordered On</TD>
        <TD ID="receipt_placed_at"><?= verbosedate($Order["placed_at"]); ?></TD>
        </TR-->
    </div>





    @if(!isset($JSON))
        <TR>
            <TD>
                <h6>Restaurant</h6><br>
                Order #<span ID="receipt_id"><?= $orderid; ?></span><br>
                <?php
                /*
                $Status = array("Pending", "Confirmed", "Declined", "Delivered", "Canceled");
                $Status = $Status[$Order["status"]];
                */
                $Restaurant = first("SELECT * FROM restaurants WHERE id = " . $Order["restaurant_id"]);
                $Raddress = first("SELECT * FROM useraddresses WHERE id = " . $Restaurant["address_id"]);
                echo $Restaurant["name"] . "<BR>" . $Raddress["number"] . " " . $Raddress["street"] . "<br>" . $Raddress["city"] . " " . $Raddress["province"] . " "
                    . $Raddress["postalcode"] . '<BR>' . $Raddress["unit"] . " " . $Restaurant["phone"];
                echo '<INPUT TYPE="HIDDEN" ID="cust_latitude" VALUE="' . $Order["latitude"] . '">
<INPUT TYPE="HIDDEN" ID="cust_longitude" VALUE="' . $Order["longitude"] . '">
<INPUT TYPE="HIDDEN" ID="rest_latitude" VALUE="' . $Raddress["latitude"] . '">
<INPUT TYPE="HIDDEN" ID="rest_longitude" VALUE="' . $Raddress["longitude"] . '">';
                ?>

            </TD>
        </TR>
        </TABLE>
    @endif





    <TABLE <?= inline("table table-sm table-bordered table-responsive"); ?> >
        <TR>
            <TH>#</TH>
            <TH>Name</TH>
            <TH class="text-xs-right"> Sub-total</TH>
            <TH>Addons</TH>
            <TH>Addon Count</TH>
            <th class="text-xs-right">Total</th>
        </TR>
        @else
            <TABLE WIDTH="100%" class="noborder" cellspacing="0" cellpadding="0">
                @endif
                <?php
                $integrity = true;
                if (!function_exists("findkey")) {
                    function findkey($arr, $key, $value)
                    {
                        return array_search($value, array_column($arr, $key));
                    }

//finds the size of the item
                    function getsize($itemname, $isfree)
                    {
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

                    function textcontains($text, $searchfor)
                    {
                        return strpos(strtolower($text), strtolower($searchfor)) !== false;
                    }
                }

                //check all data again, do not trust the prices from the user!!
                $tables = array("toppings", "wings_sauce", "additional_toppings");
                foreach ($tables as $ID => $table) {
                    $tables[$table] = Query("SELECT * FROM " . $table, true);
                    unset($tables[$ID]);
                }

                $deliveryfee = findkey($tables["additional_toppings"], "size", "Delivery");
                $deliveryfee = $tables["additional_toppings"][$deliveryfee]["price"];
                if (file_exists($filename)) {
                    $filename = file_get_contents($filename);
                    try {
                        $items = json_decode($filename);
                        $itemIDs = array();
                        foreach ($items as $item) {
                            if (isset($item->itemid)) {
                                $itemIDs[] = $item->itemid;
                            }
                        }
                        $itemIDs = implode(",", array_unique($itemIDs));
                        if (!$itemIDs) {
                            die("Order is empty");
                        }

                        $menu = Query("SELECT * FROM menu WHERE id IN(" . $itemIDs . ")", true);

//convert the JSON into an HTML receipt, using only item/addon IDs, reobtaining cost/names from the database for security
                        $subtotal = 0;
                        foreach ($items as $ID => $item) {
                            if (is_object($item)) {
                                $menukey = findkey($menu, "id", $item->itemid);

                                if (true) {
                                    $menuitem = $menu[$menukey];
                                    $size = getsize($menuitem["item"], $tables["additional_toppings"]);
                                    $addonscost = "0.00";
                                    if ($size) {
                                        $addonscost = findkey($tables["additional_toppings"], "size", $size);
                                        $addonscost = $tables["additional_toppings"][$addonscost]["price"];
                                    }
                                    $itemtotal = $menuitem["price"];
                                    $paidtoppings = 0;
                                    $freetoppings = 0;

                                    $totaladdons = 0;
                                    foreach ($tables as $name => $data) {
                                        if (isset($menuitem[$name])) {
                                            $totaladdons += $menuitem[$name];
                                        }
                                    }

                                    switch ($style) {
                                        case 1:
                                            if ($debugmode) {
                                                $debug = ' TITLE="' . var_export($item, true) . '"';
                                            }
                                            echo '<TR><TD>' . ($ID + 1) . '</TD><TD' . $debug . '>' . $item->itemname . '</TD>';
                                            if ($debugmode) {
                                                $debug = ' TITLE="' . print_r($menuitem, true) . '"';
                                            }
                                            echo '<TD ALIGN="RIGHT"' . $debug . '>$' . number_format($menuitem["price"], 2) . '</TD><TD>';
                                            break;
                                        case 2:
                                            $imagefile = str_replace(" ", "_", strtolower($menuitem["category"]));
                                            if (right($imagefile, 5) == "pizza" || !file_exists(public_path() . '/' . $imagefile . ".png")) {
                                                $imagefile = "pizza";
                                            }

                                            $imagefile = '<img class="pull-left" src="' . webroot("public/" . $imagefile . ".png") . '" style="width:22px;margin-right:5px;">';
                                            echo '<TR><TD width="1%">' . $imagefile . '</TD><TD valign="middle">' . $item->itemname . '</TD><TD ALIGN="RIGHT" WIDTH="5%">';
                                            break;
                                    }

                                    $HTML = "";
                                    if (isset($item->itemaddons)) {
                                        if ($style == 1) {
                                            $HTML = '<TABLE BORDER="1" WIDTH="100%">';
                                        }
                                        $addoncount = count($item->itemaddons);
                                        foreach ($item->itemaddons as $addonID => $addon) {
                                            $toppings = array();
                                            if (isset($addon->tablename)) {
                                                $tablename = $addon->tablename;
                                                switch ($tablename) {
                                                    case "toppings":
                                                        $itemtype = "Pizza";
                                                        break;
                                                    case "wings_sauce":
                                                        $itemtype = "Lb";
                                                        break;
                                                }
                                                if (isset($addon->addons)) {
                                                    $toppings = $addon->addons;
                                                }
                                            }
                                            $newtoppings = array();
                                            foreach ($toppings as $topping) {
                                                if (isset($topping->id)) {//search by id
                                                    $id = $topping->id;
                                                    $toppingkey = findkey($tables[$tablename], "id", $topping->id);
                                                } else {//search by name
                                                    $toppingkey = findkey($tables[$tablename], "name", $topping->text);
//$id = $tables[$tablename][$id]["id"];
                                                }

                                                $topping = $tables[$tablename][$toppingkey];
                                                if ($topping["isfree"]) {
                                                    $freetoppings++;
                                                    $topping["name"] = '<I>' . $topping["name"] . '</I>';
                                                } else {
                                                    $paidtoppings++;
                                                }
                                                if ($debugmode) {
                                                    $debug = ' TITLE="' . print_r($topping, true) . '"';
                                                }
                                                $newtoppings[] = '<SPAN' . $debug . '>' . $topping["name"] . '</SPAN>';
                                            }

                                            if ($style == 1) {
                                                $itemtitle = $itemtype . ' #' . ($addonID + 1);
                                                $HTML .= '<TR><TD NOWRAP>' . $itemtitle . '</TD><TD>' . implode(", ", $newtoppings) . '</TD></TR>';
                                            } else {
                                                $itemtitle = "";
                                                if ($addoncount > 1) {
                                                    $itemtitle = $ordinals[$addonID] . " " . $itemtype . ": ";
                                                }
                                                $HTML .= $itemtitle . implode(", ", $newtoppings);
                                            }
                                        }
                                        if ($style == 1) {
                                            echo $HTML . '</TABLE>';
                                        }
                                    }

                                    $toppingscost = $addonscost * $paidtoppings;
                                    $itemtotal = $menuitem["price"] + $toppingscost;

                                    if ($style == 1) {
                                        echo '</TD><TD NOWRAP>';
                                        if ($totaladdons) {
                                            echo $paidtoppings . ' paid<BR>' . $freetoppings . ' free';
                                            echo '<BR>$' . number_format($addonscost, 2) . '<BR>each';//'<BR>' . $size .
                                        }
                                        if ($debugmode) {
                                            $debug = ' TITLE="User side: $' . $item->itemprice . '"';
                                        }
                                        echo '</TD><TD ALIGN="RIGHT"' . $debug . '>';
                                    }
                                    echo '$' . number_format($itemtotal, 2) . '</TD></TR>';
                                    if ($style == 2 && $HTML) {
                                        echo '<TR><TD COLSPAN="' . $colspan . '">' . $HTML . '</TD></TR>';
                                    }
                                    $subtotal += $itemtotal;
                                }
                            }
                        }

                        $tax_percent = 0.13;
                        $tax = ($subtotal + $deliveryfee) * $tax_percent;
                        $total = $subtotal + $deliveryfee + $tax;
                        $colspanminus1 = $colspan - 1;
                        echo '<TR><TD COLSPAN="' . $colspanminus1 . '" ALIGN="RIGHT">Sub-total&nbsp;</TD><TD ALIGN="RIGHT">$' . number_format($subtotal, 2) . '</TD></TR>';
                        echo '<TR><TD COLSPAN="' . $colspanminus1 . '" ALIGN="RIGHT">Delivery&nbsp;</TD><TD ALIGN="RIGHT">$' . number_format($deliveryfee, 2) . '</TD></TR>';
                        echo '<TR><TD COLSPAN="' . $colspanminus1 . '" ALIGN="RIGHT">Tax&nbsp;</TD><TD ALIGN="RIGHT">$' . number_format($tax, 2) . '</TD></TR>';
                        echo '<TR><TD COLSPAN="' . $colspanminus1 . '" ALIGN="RIGHT">Total&nbsp;</TD><TD ALIGN="RIGHT">$' . number_format($total, 2) . '</TD></TR>';
                        if ($Order["cookingnotes"]) {
                            echo '<TR><TD COLSPAN="' . $colspan . '"><B>Notes for the Cook: </B>' . $Order["cookingnotes"] . '</TD></TR>';
                        }

                        insertdb("orders", array("id" => $orderid, "price" => $total));//saved for stripe
                    } catch (exception $e) {
                        echo 'Caught exception: ', $e->getMessage() . " on line " . $e->getLine() . "<BR>";
                        echo $filename;
                    }
                    if ($style == 2) {
                        echo '<TR><TD COLSPAN="' . $colspan . '">';
                        if (isset($JSON)) {
                            echo '<BUTTON CLASS="btn  btn-secondary" ONCLICK="orders(' . $orderid . ', true);">Load Order</BUTTON>';
                        } else {
                            echo $Order["name"] . " - " . $Order["email"] . "<BR>" . $Order["phone"] . " " . $Order["cell"] . "<BR>" . $Order["number"] . " " . $Order["street"] . '<BR>' . $Order["city"] . ", " . $Order["province"] . "<BR>" . $Order["postalcode"] . '<BR>' . $Order["unit"];
                        }
                        echo '</TD></TR>';
                    }
                } else {
                    echo '<TR><TD COLSPAN="' . $colspan . '" ALIGN="CENTER"><B>FILE NOT FOUND</B></TD></TR>';
                }
                endfile("popups_receipt");
                ?>


            </TABLE>

            <SCRIPT>
                var countdown = window.setTimeout(function () {
                    incrementtime()
                }, 1000);
                function incrementtime() {
                    var seconds = $(".countdown").attr("seconds");
                    var minutes = $(".countdown").attr("minutes");
                    var result = false;
                    if (seconds == 0) {
                        if (minutes == 0) {
                            result = "[Time's up.]";
                            window.clearInterval(countdown);
                        } else {
                            minutes -= 1;
                        }
                        seconds = 59;
                    } else {
                        seconds -= 1;
                    }
                    if (!result) {
                        result = minutes + ":";
                        if (seconds < 10) {
                            result += "0" + seconds;
                        } else {
                            result += seconds;
                        }
                    }
                    $(".countdown").attr("seconds", seconds);
                    $(".countdown").attr("minutes", minutes);
                    $(".countdown").text(result);
                    countdown = window.setTimeout(function () {
                        incrementtime()
                    }, 1000);
                }
            </SCRIPT>
