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
            if (!$debugmode) {
                $colspan -= 1;
            }
            break;
        case 2:
            $colspan = 3;
            $ordinals = array("11st", "21nd", "31rd", "4th", "5th", "6th", "7th", "8th", "9th", "10th");
            break;
    }
    //Hack to put CSS inline for emails
    if (!isset($inline)) {
        $inline = false;
    }
    if (!isset($timer)) {
        $timer = false;
    }
    $GLOBALS["inline"] = $inline;
    if (!function_exists("inline")) {
        function tomin($time) {
            return left($time, strlen($time) - 2) * 60 + right($time, 2);
        }

        function minpad($time) {
            if ($time < 10) {
                return "0" . $time;
            }
            return $time;
        }

        function inline($Class, $OnlyInline = false) {
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
                        case "bg-primary":
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

        function GenerateDate($Date){
            $today = date("F j");
            $Date = str_replace($today, "Today", $Date);
            $tomorrow = date("F j", strtotime("+ 1 day"));
            $Date = str_replace($tomorrow, "Tomorrow", $Date);
            return $Date;
        }
    }
    //edit countdown timer duration
    $minutes = 40;
    $seconds = 0;
    $duration = "";

    if ($Order["deliverytime"]) {
        $duration = $Order["deliverytime"];
        $Time = trim(right($Order["deliverytime"], 4));//1500
        if (is_numeric($Time)) {
            $CurrentTime = date("Gi");
            $duration = GenerateDate(left($Order["deliverytime"], strlen($Order["deliverytime"]) - 4)) . GenerateTime(intval($Time));
            if ($CurrentTime <= $Time && $timer) {
                $minutes = tomin($Time) - tomin($CurrentTime) + 1;
            } else {
                $timer = false;
            }
        } else if ($Order["deliverytime"] == "Deliver Now") {
            $time = strtotime($Order["placed_at"]);
            $duration = GenerateDate(date("F j", $time)) . " at " . date("g i A", $time+($minutes*60));
        } else {
            $timer = false;
        }
    }
    $time = "";
    if ($timer) {
        if ($minutes < 60) {
            $time = $minutes;
        } else {
            $time = floor($minutes / 60) . " " . minpad($minutes % 60);
        }
        $time .= " " . minpad($seconds);
    }
    $onlydebug = "Only shows in debug mode! - ";
?>

@if($style==1)
    <h2 class="mt-0">Order for {{ $duration }}</h2>
    <div style="font-size:1.25rem;padding:.75rem;" CLASS="countdown badge badge-pill badge-success" minutes="<?= $minutes; ?>" seconds="<?= $seconds; ?>"><?= $time; ?></div>
    <div class="clearfix"></div>
    <h2>Delivery Info</h2>
    <?php
       echo $Order["name"] . "<BR>" . $Order["number"] . " " . $Order["street"] . '<BR>' . $Order["city"] . " " . $Order["province"] . " " . $Order["postalcode"] . '<BR>' . $Order["unit"];
    ?>
    @if(!isset($JSON))
        <h2>Restaurant</h2>
        Order #<span ID="receipt_id"><?= $orderid; ?></span><br>
        <?php
        $Restaurant = first("SELECT * FROM restaurants WHERE id = " . $Order["restaurant_id"]);
        $Raddress = first("SELECT * FROM useraddresses WHERE id = " . $Restaurant["address_id"]);
        echo $Restaurant["name"] . "<BR>" . $Raddress["number"] . " " . $Raddress["street"] . "<br>" .
            $Raddress["city"] . " " . $Raddress["province"] . " " . $Raddress["postalcode"] . '<BR>' . $Raddress["unit"] . " " . $Restaurant["phone"];
        echo '<INPUT TYPE="HIDDEN" ID="cust_latitude" VALUE="' . $Order["latitude"] . '"><INPUT TYPE="HIDDEN" ID="cust_longitude" VALUE="' . $Order["longitude"]
            . '"><INPUT TYPE="HIDDEN" ID="rest_latitude" VALUE="' . $Raddress["latitude"]
            . '"><INPUT TYPE="HIDDEN" ID="rest_longitude" VALUE="' . $Raddress["longitude"] . '">';
        ?>
    @endif
    <h2>Receipt</h2>
    <TABLE <?= inline("table table-sm table-bordered table-responsive"); ?> >
        <TR>
            <TH>#</TH>
            <TH>Item</TH>
            <TH class="text-xs-right"> Sub-total</TH>
            <TH>Addons</TH>
            @if($debugmode)
                <TH TITLE="<?= $onlydebug; ?>">Count</TH>
            @endif
            <th class="text-xs-right">Price</th>
        </TR>
        @else
            <TABLE WIDTH="100%" class="noborder" cellspacing="0" cellpadding="0">
                @endif
                <?php
                    $integrity = true;
                    if (!function_exists("findkey")) {
                        function findkey($arr, $key, $value) {
                            return array_search($value, array_column($arr, $key));
                        }

                        //finds the size of the item
                        function getsize($itemname, $isfree) {
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

                        function textcontains($text, $searchfor) {
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
                                                    $debug = ' TITLE="' . $onlydebug . var_export($item, true) . '"';
                                                }
                                                echo '<TR><TD>' . ($ID + 1) . '</TD><TD' . $debug . '>' . $item->itemname . '</TD>';
                                                if ($debugmode) {
                                                    $debug = ' TITLE="' . $onlydebug . print_r($menuitem, true) . '"';
                                                }
                                                echo '<TD ALIGN="RIGHT"' . $debug . '>$' . number_format($menuitem["price"], 2) . '</TD><TD>';
                                                break;
                                            case 2:
                                                $imagefile = str_replace(" ", "_", strtolower($menuitem["category"]));
                                                if (right($imagefile, 5) == "pizza" || !file_exists(public_path() . '/' . $imagefile . ".png")) {
                                                    $imagefile = "pizza";
                                                }

                                                $imagefile = '<img class="pull-left" src="' . webroot("public/menu/" . $imagefile . ".png") . '" style="width:22px;margin-right:5px;">';
                                                echo '<TR><TD width="1%">' . $imagefile . '</TD><TD valign="middle">' . $item->itemname . '</TD><TD ALIGN="RIGHT" WIDTH="5%">';
                                                break;
                                        }

                                        $HTML = "";
                                        if (isset($item->itemaddons)) {
                                            if ($style == 1) {
                                                $HTML = '<TABLE style="border:1px solid #eceeef;!important;" WIDTH="100%">';
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
                                                            $itemtype = "lb";
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
                                                    }

                                                    $topping = $tables[$tablename][$toppingkey];
                                                    if ($topping["isfree"]) {
                                                        $freetoppings++;
                                                        $topping["name"] = '<I>' . $topping["name"] . '</I>';
                                                    } else {
                                                        $paidtoppings++;
                                                    }
                                                    if ($debugmode) {
                                                        $debug = ' TITLE="' . $onlydebug . print_r($topping, true) . '"';
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
                                            echo '</TD>';
                                            if ($debugmode) {
                                                echo '<TD NOWRAP>';
                                                if ($totaladdons) {
                                                    echo $paidtoppings . ' paid<BR>' . $freetoppings . ' free';
                                                    echo '<BR>$' . number_format($addonscost, 2) . '<BR>each';//'<BR>' . $size .
                                                }
                                                if ($debugmode) {
                                                    $debug = ' TITLE="' . $onlydebug . 'User side: $' . $item->itemprice . '"';
                                                }
                                                echo '</TD>';
                                            }
                                            echo '<TD ALIGN="RIGHT"' . $debug . '>';
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
                                echo '<TR><TD COLSPAN="' . $colspan . '"><B>Notes: </B>' . $Order["cookingnotes"] . '</TD></TR>';
                            }

                            insertdb("orders", array("id" => $orderid, "price" => $total));//saved for stripe
                        } catch (exception $e) {
                            echo 'Caught exception: ', $e->getMessage() . " on line " . $e->getLine() . "<BR>";
                            echo $filename;
                        }
                        if ($style == 2) {
                            echo '<TR><TD COLSPAN="' . $colspan . '">';
                            if (isset($JSON)) {
                                echo '<BUTTON CLASS="btn btn-block btn-primary" ONCLICK="orders(' . $orderid . ', true);">LOAD ORDER</BUTTON>';
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

            @if ($style == 1)
                <div>
                    <h2>Questions about your order?</h2>
                    Please contact the restaurant directly, info can be found on the order receipt.
                </div>
            @endif


            <!--div>
                <br><br> CHECK US OUT ON SOCIAL MEDIA
                <br><br> FOOD DRIVE PROGRAM
                <br><br> EMAIL US
                <br><br> SEARCH GOOGLE
                <br><br>
            </div-->

            @if($timer)
                <SCRIPT>
                    var countdown = window.setTimeout(function () {
                        incrementtime()
                    }, 1000);
                    function incrementtime() {
                        var seconds = $(".countdown").attr("seconds");
                        var minutes = $(".countdown").attr("minutes");
                        var hours = Math.floor(minutes / 60);

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
                            if (hours == 0) {
                                result = minutes;
                            } else {
                                result = hours + "h:" + minpad(minutes % 60);
                            }
                            result += "m " + minpad(seconds)+"s";
                        }
                        $(".countdown").attr("seconds", seconds);
                        $(".countdown").attr("minutes", minutes);
                        $(".countdown").text(result);
                        countdown = window.setTimeout(function () {
                            incrementtime()
                        }, 1000);
                    }

                    function minpad(time) {
                        if (time < 10) {
                            return "0" + time;
                        }
                        return time;
                    }
                </SCRIPT>
@endif