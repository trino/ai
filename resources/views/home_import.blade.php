@extends('layouts_app')
@section('content')
<BR>
<?php
    //http://maps.google.com/maps/api/geocode/json?address=1600+Amphitheatre+Parkway,+Mountain+View,+CA
    $daysofweek = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    function parserestaurant($ID, $Data, $daysofweek){
        $Restaurant = array();
        $Restaurant["ID"] = $ID;
        $Restaurant["Name"] = trim($Data[0]);
        unset($Data[0]);
        $Mode = "";
        foreach($Data as $LineID => $Line){
            $start = strpos($Line, ": ");
            $Line = trim($Line);
            if($start !== false){
                $Mode = left($Line, $start);
                if(strlen($Line) > $start + 2){
                    $Restaurant[$Mode] = right($Line, strlen($Line) - $start - 2);
                }
                unset($Data[$LineID]);
            } else if(is_numeric($Line)){
                $Restaurant["Rating"] = trim($Line);
                unset($Data[$LineID]);
            } else if($Mode == "Hours"){
                $start = strpos($Line, "	");
                if($start !== false){
                    $Day = array_search(left($Line, $start), $daysofweek);
                    $Hour = right($Line, strlen($Line) - $start - 1);
                    if($Hour == "Closed"){
                        $Hours["open"] = -1;
                        $Hours["close"] = -1;
                    } else {
                        if(strpos($Hour, "–") !== false){
                            $Test = explode("–", $Hour);
                        } else {
                            $Test = explode("-", $Hour);
                        }
                        $Hours["open"] = converttime($Test[0], $Test[1]);
                        $Hours["close"] = converttime($Test[1]);
                    }
                    $Restaurant["Hours"][$Day] = $Hours;
                    unset($Data[$LineID]);
                }
            }
        }
        $Restaurant["Data"] = $Data;
        return $Restaurant;
    }
    function converttime($Time, $OtherTime = ""){
        //11:30, 3 or 10PM to 2200
        $AMPM = right($Time, 2);
        if($AMPM != "AM" && $AMPM != "PM"){
            $AMPM = right($OtherTime, 2);
        } else {
            $Time = left($Time, strlen($Time) - 2);
        }
        if(strpos($Time, ":") !== false){
            $Time = str_replace(":", "", $Time);
        } else {
            $Time = $Time . "00";
        }
        if($AMPM == "PM"){
            $Time = $Time + 1200;
        }
        return (int)$Time;
    }

    function downloadJSON($URL){
        $URL = file_get_contents($URL);
        try {
            $URL = json_decode($URL);
        } catch (Exception $e) {
            $URL = array();
        }
        return $URL;
    }

    function printstore($Data, $daysofweek, $IsPicked){
        $RET = '<div class="card"><div class="card-block bg-danger">' . $Data["Name"];
        $exists = "This store seems to exist already. I do not recommend importing it";
        if(isset($Data["Rating"])){
            $RET .= '<SPAN CLASS="pull-right">' . $Data["Rating"] . '<i class="fa fa-star"></i></SPAN>';
        }
        $RET .= '</DIV><TABLE WIDTH="100%">';
        $RET .= '<TR><TD WIDTH="10%">Address:</TD><TD>' . $Data["Address"] . '</TD></TR>';
        $RET .= '<TR><TD>Phone:</TD><TD>' . $Data["Phone"] . '</TD></TR>';
        if($IsPicked){
            $GPS = downloadJSON("http://maps.google.com/maps/api/geocode/json?address=" . str_replace(" ", "+", $Data["Address"]));
            $GPS = $GPS->results[0];
            $AddressComponent = $GPS->address_components;
            $Address = array();
            foreach($AddressComponent as $ID => $Adddata){
                switch($Adddata->types[0]){
                    case "administrative_area_level_1":
                        $Address[$Adddata->types[0]] = $Adddata->long_name;
                        break;
                    default:
                        $Address[$Adddata->types[0]] = $Adddata->short_name;
                }
            }
            $GPS = $GPS->geometry->location;
            $RET .= '<TR><TD>Coordinates:</TD><TD>Latitude: ' . $GPS->lat . " Longitude: " . $GPS->lng . '</TD></TR>';
        }
        if(isset($Data["Hours"])){
            $RET .= '<TR><TD>Hours:</TD><TD>';
            $Delimeter = "";
            foreach($Data["Hours"] as $Day => $Hours){
                $RET .= $Delimeter . $daysofweek[$Day] . " ";
                if($Hours["open"] == "-1" || $Hours["close"] == "-1"){
                    $RET .= "Closed";
                } else {
                    $RET .= $Hours["open"] . "-" . $Hours["close"];
                }
                $Delimeter = ", ";
            }
            $RET .= '</TD></TR>';
        }
        $RET .= '<TR><TD>Actions:</TD><TD>';
        if(emailinuse($Data["Phone"], "users", "phone")){
            $RET .= $exists;
        } if($IsPicked){
            if(storeexists($Address["street_number"], $Address["route"], $Address["postal_code"], $Address["locality"], $Address["administrative_area_level_1"])){
                $RET .= $exists;
            } else {
                $Address = "&" . http_build_query($Address);
                $RET .= '<a class="btn btn-sm btn-success cursor-pointer" href="' . webroot("public/import?index=") . $Data["ID"] . '&lat=' . $GPS->lat . '&lng=' . $GPS->lng . $Address . '">Are you sure you want to import?</a>';
            }
            $RET .= ' <a class="btn btn-sm btn-danger cursor-pointer" href="' . webroot("public/import") . '">Cancel</a>';
        } else if(storeexists($Data["Address"])) {
            $RET .= $exists;
        } else {
            $RET .= '<a class="btn btn-sm btn-success cursor-pointer" href="' . webroot("public/import?index=") . $Data["ID"] . '">Import</a>';
        }
        $RET .= '</TD></TR>';
        return $RET . '</TABLE></DIV><BR>';
    }

    function storeexists($number, $street = "", $postalcode = "", $city = "", $province = ""){
        //return false;
        if($province){
            $Data = array("number" => $number, "street" => $street, "postalcode" => $postalcode, "city" => $city, "province" => $province);
            $SQL = "SELECT * FROM useraddresses WHERE ";
            $Delimeter = "";
            foreach($Data as $Key => $Value){
                if($Key == "postalcode"){
                    $SQL .= $Delimeter . " (postalcode = '" . $Value . "' OR postalcode = '" . left($Value, 3) . "')";
                } else {
                    $SQL .= $Delimeter . $Key . " = '" . $Value . "'";
                }
                $Delimeter = " AND ";
            }
        } else {
            $start = strpos($number, ", ON ");
            if($start !== false){
                $number = left($number, $start) . ", Ontario";
            }
            $number = str_replace(", Stoney Creek, ", ", Hamilton, ", $number);
            $SQL = "SELECT id, CONCAT(number, ' ', street, ', ', city, ', ', province) as address FROM useraddresses HAVING address = '" . $number . "'";//, ' ', postalcode
            $SQL .= " OR address = '" . trim(left($number,strlen($number) - 3)) . "'";
        }
        $address_id = first($SQL);
        if($address_id){
            $restaurant_id = first("SELECT id FROM restaurants WHERE address_id = " . $address_id["id"]);
            if($restaurant_id){return $restaurant_id["id"];}
        }
        return false;
    }

    function emailinuse($emailaddress, $table = "users", $field = "email"){
        if(first("SELECT * FROM " . $table . " WHERE " . $field . " = '" . $emailaddress . "'")){
            return true;
        }
        return false;
    }

    function uniqueemailaddress($before, $key, $domain){
        $append = "";
        $key = "+" . filternonalphanumeric(str_replace(" ", "", $key));
        if(emailinuse($before . $key . $domain)){
            $append = 1;
            while(emailinuse($before . $key . $append . $domain)){
                $append+=1;
            }
        }
        return $before . $key . $append . $domain;
    }

    $Places = array();
    if (file_exists("pizzaplaces.txt")){
        $Contents = explode("\r\n", file_get_contents("pizzaplaces.txt"));
        $Places[] = array();
        $Index = 0;
        foreach($Contents as $Line){
            if(!$Line){
                $Places[] = array();
                $Index+=1;
            } else {
                $Places[$Index][] = $Line;
            }
        }

        if(isset($_GET["index"])){
            $Data = parserestaurant($_GET["index"], $Places[$_GET["index"]], $daysofweek);
            if(isset($_GET["lat"])){
                $user_id=0;
                $address_id=0;
                $restaurant_id=0;
                $doInsert = !storeexists($_GET["street_number"], $_GET["route"], $_GET["postal_code"], $_GET["locality"], $_GET["administrative_area_level_1"]);

                $USER = array(
                    "name" =>           $Data["Name"],
                    "email" =>          uniqueemailaddress("info", "+" . $Data["Name"], "@trinoweb.com"),
                    "password" =>       "$2y$10$440weczzi7gl8OpXQJROPey1Eiyx1BQWk4dFEj9pAHWO2FmagZQ52",
                    "phone" =>          $Data["Phone"],//filternonnumeric($Data["Phone"])
                    "profiletype" =>    2,
                    "created_at" =>     now(),
                    "remember_token" => "",
                    "updated_at" =>     0
                );
                if($doInsert) {$user_id = insertdb("users", $USER);}

                $ADDRESS = array(
                    "user_id" =>        $user_id,
                    "number" =>         $_GET["street_number"],
                    "street" =>         $_GET["route"],
                    "postalcode" =>     $_GET["postal_code"],
                    "city" =>           $_GET["locality"],
                    "province" =>       $_GET["administrative_area_level_1"],
                    "latitude" =>       $_GET["lat"],
                    "longitude" =>      $_GET["lng"],
                );
                if($doInsert) {$address_id = insertdb("useraddresses", $ADDRESS);}
                $RESTAURANT = array(
                    "name" =>           $Data["Name"],
                    "email" =>          $USER["email"],
                    "phone" =>          $USER["phone"],
                    "is_delivery" =>    1,
                    "address_id" =>     $address_id
                );
                if($doInsert) {$restaurant_id = insertdb("restaurants", $RESTAURANT);}
                $HOURS = array(
                    "restaurant_id" => $restaurant_id
                );
                if(isset($Data["Hours"]) && count($Data["Hours"])){
                    foreach($Data["Hours"] as $Day => $Hours){
                        $HOURS[$Day . "_open"] = $Hours["open"];
                        $HOURS[$Day . "_close"] = $Hours["close"];
                    }
                    if($doInsert) {insertdb("hours", $HOURS);}
                }

                if($doInsert) {
                    echo "<BR>IMPORTING:";
                } else {
                    echo "<BR>SKIPPING (EXISTS ALREADY):";
                }
                echo "<BR>USER ID: " . iif($user_id, $user_id, " [SKIPPED]");
                var_dump($USER);
                echo "<BR>ADDRESS ID: " . iif($address_id, $user_id, " [SKIPPED]");
                var_dump($ADDRESS);
                echo "<BR>RESTAURANT ID: " . iif($restaurant_id, $user_id, " [SKIPPED]");
                var_dump($RESTAURANT);
                echo "<BR>HOURS:" . iif(count($HOURS) == 1, " [MISSING]");
                var_dump($HOURS);
                echo ' <a id="backbtn" class="btn btn-sm btn-danger cursor-pointer" href="' . webroot("public/import") . '">Back</a>';
                if($doInsert){echo '<SCRIPT>setTimeout(function(){window.location = "' . webroot("public/import") . '"}, 2000);</SCRIPT>';}
            } else {
                echo printstore($Data, $daysofweek, true);
            }
        } else {
            $Done=false;
            foreach($Places as $ID => $Data){
                $Data = parserestaurant($ID, $Data, $daysofweek);
                $HTML = printstore($Data, $daysofweek, false);
                if(strpos($HTML, "This store seems to exist already") === false){
                    echo $HTML;
                    $Done = true;
                }
            }
            if(!$Done){
                echo "All " . count($Places) . " stores have been added already";
            }
        }
    } else {
        echo "Place list is missing";
    }
?>
@endsection