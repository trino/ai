@extends(isset($isGET) ? 'layouts_app' : 'layouts_blank')
@section('content')
<?php
    //http://localhost/ai/public/list/orders?action=getreceipt&orderid=224
    startfile("popups_receipt");
    $debugmode = !islive();
    $debug = "";
    $Order = first("SELECT orders.*, users.name, users.id as userid, users.email FROM orders, users WHERE orders.id = " . $orderid . " HAVING user_id = users.id");
    $filename = resource_path("orders") . "/" . $orderid . ".json";
    if (!isset($includeextradata)) {
        $includeextradata = false;
    }
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
            $includeextradata = true;
            $colspan = 6;
            if (!$debugmode) {
                $colspan -= 1;
            }
            break;
        case 2:
            $colspan = 4;
            $ordinals = array("1st", "2nd", "3rd", "4th", "5th", "6th", "7th", "8th", "9th", "10th");
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
        function tomin($time){
            return left($time, strlen($time) - 2) * 60 + right($time, 2);
        }

        function minpad($time){
            if ($time < 10) {
                return "0" . $time;
            }
            return $time;
        }

        function inline($Class, $OnlyInline = false){
            if ($GLOBALS["inline"]) {
                $Style = array();
                $Class = explode(" ", $Class);
                foreach ($Class as $Classname) {
                    switch (strtolower($Classname)) {
                        //table-sm
                        case "table":
                            $Style[] = "";
                            break;
                        case "table-bordered":
                            $Style[] = "";
                            break;
                        case "bg-primary":
                            $Style[] = "";
                            break;
                        case "table-inverse":
                            $Style[] = "";
                            break;
                    }
                }
                return ' style="' . implode(" ", $Style) . '"';
            } else if (!$OnlyInline) {
                return ' class="' . $Class . '"';
            }
        }

        function parsetime($Time){
            return strtotime(date("j F Y ") . left($Time, 1) . " hours " . right($Time, 2) . " minutes");
        }

        function roundTime($timestamp, $increment = 15){
            $BEFOREminutes = date('i', $timestamp);
            $AFTERminutes = $increment + $BEFOREminutes - ($BEFOREminutes % $increment);
            $DIFFERENCEminutes = $AFTERminutes - $BEFOREminutes;
            return $timestamp + ($DIFFERENCEminutes * 60);
        }

        function GenerateDate($Date){
            $today = date("F j");
            if (isset($_GET["day"]) && is_numeric($_GET["day"]) && $_GET["day"] >= 0 && $_GET["day"] <= 6) {

            }
            $Date = str_replace($today, "Today (" . $today . ")", $Date);
            $tomorrow = date("F j", strtotime("+ 1 day"));
            $Date = str_replace($tomorrow, "Tomorrow (" . $tomorrow . ")", $Date);
            return $Date;
        }

        function todate($timestamp){
            return date("F j, Y G:i", $timestamp);
        }
    }
    //edit countdown timer duration
    $minutes = getdeliverytime();
    $seconds = 0;
    $hours = 0;
    $duration = "";
    $timer = $place != "email";
    $day_of_week = date("w");
    if (isset($_GET["day"]) && is_numeric($_GET["day"]) && $_GET["day"] >= 0 && $_GET["day"] < 7) {
        $day_of_week = $_GET["day"];
    }

    if ($Order["deliverytime"]) {
        $duration = $Order["deliverytime"];
        $Time = trim(right($Order["deliverytime"], 4));//1500
        if (is_numeric($Time)) {
            $CurrentTime = date("Gi");
            if (isset($_GET["time"]) && is_numeric($_GET["time"]) && $_GET["time"] >= 0 && $_GET["time"] < 2400) {
                $CurrentTime = $_GET["time"];
            }
            $date = str_replace(" at ", "", left($Order["deliverytime"], strlen($Order["deliverytime"]) - 4));
            $duration = GenerateDate($date) . " at " . GenerateTime(intval($Time));
            $tomorrow = date("F j", strtotime("+ 1 day"));
            if ($date == $tomorrow) {
                $DeliveryTime = strtotime("midnight tomorrow") + tomin($Time) * 60;
                $minutes = ceil(($DeliveryTime - time()) / 60);
            } else if ($CurrentTime <= $Time && $timer) {
                $minutes = tomin($Time) - tomin($CurrentTime) + 1;
            } else {
                $timer = false;
            }
        } else if ($Order["deliverytime"] == "Deliver Now") {
            $time = strtotime($Order["placed_at"]) + ($minutes * 60);
            $open = parsetime(gethours($Order["restaurant_id"])[$day_of_week]["open"]) + ($minutes * 60);
            if ($time < $open && date("F j", $time) == date("F j", $open)) {
                $time = $open;
            }
            $time = roundTime($time);
            $duration = GenerateDate(date("F j ", $time)) . "at " . date("g:i A", $time);
            if (time() > $time) {
                $timer = false;//expired
            } else {
                $minutes = ceil(($time - time()) / 60);
            }
        } else {
            $timer = false;is_numeric()
@endif

@if($includeextradata)
    @if($party != "restaurant")
        <h2 class="mt-2">Questions about your order?</h2>
        Please contact the restaurant directly
        <DIV CLASS="clearfix my-2"></DIV>
    @endif
    <br>
    <a class="pull-left btn-link btn pl-0" href="<?= webroot("help"); ?>">MORE INFO</a>
@endif
@endsection