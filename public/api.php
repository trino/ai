<?php
$webroot = $_SERVER["REQUEST_URI"];
$start = strpos($webroot, "/", 1) + 1;
$webroot = substr($webroot, 0, $start) . "webroot/";
if ( $_SERVER["SERVER_NAME"] != "localhost"){$webroot = str_replace("application/", "", $webroot);}
$dirroot = getcwd();

error_reporting(E_ERROR | E_PARSE);//suppress warnings
include("../veritas3-1/config/app.php");//config file is not meant to be run without cake, thus error reporting needs to be suppressed
error_reporting(E_ALL);//re-enable warnings
$con = "";//connectdb();

function connectdb($Database = "") {
    global $con, $config;
    $localhost = "localhost";
    if ( $_SERVER["SERVER_NAME"] == "localhost"){$localhost.= ":3306";}
    if(!$Database){$Database = $config['Datasources']['default']['database'];}
    //$con = mysqli_connect($localhost, $config['Datasources']['default']['username'], $config['Datasources']['default']['password'], $Database) or die("Error " . mysqli_connect_error($con));
    $con = mysqli_connect($localhost, "root", "", "ai") or die("Error " . mysqli_connect_error($con));
    return $con;
}

function left($text, $length){
    return substr($text,0,$length);
}
function right($text, $length){
    return substr($text, -$length);
}
function mid($text, $start, $length){
    return substr($text,$start, $length);
}

function insertdb($conn, $Table, $DataArray, $PrimaryKey = "", $Execute = True){
    if (is_object($conn)){$DataArray = escapearray($conn, $DataArray);}
    $query = "INSERT INTO " . $Table . " (" . getarrayasstring($DataArray, True) . ") VALUES (" . getarrayasstring($DataArray, False) . ")";
    if($PrimaryKey) {
        $query.= " ON DUPLICATE KEY UPDATE";
        $delimeter = " ";
        foreach($DataArray as $Key => $Value){
            if($Key != $PrimaryKey){
                $query.= $delimeter . $Key . "='" . $Value . "'";
                $delimeter = ", ";
            }
        }
    }
    $query.=";";
    if($Execute && is_object($conn)) {
        mysqli_query($conn, $query) or die ('Unable to execute query. '. mysqli_error($conn) . "<P>Query: " . $query);
    }
    return $query;
}

function escapearray($conn, $DataArray){
    foreach($DataArray as $Key => $Value) {
        $DataArray[$Key] = mysqli_real_escape_string($conn, $Value);
    }
    return $DataArray;
}

function getarrayasstring($DataArray, $Keys = True){
    if ($Keys) {
        $DataArray = array_keys($DataArray);
        return implode(", ", $DataArray);
    } else {
        $DataArray = array_values($DataArray);
        $DataArray = implode("', '", $DataArray);
        return "'" . $DataArray . "'";
    }
}

function first($query) {
    global $con;
    $result = $con->query($query);
    if($result) {
        while ($row = mysqli_fetch_array($result)) {
            return $row;
        }
    }
}

function get($Key, $default = ""){
    if (isset($_POST[$Key])) { return $_POST[$Key];}
    if (isset($_GET[$Key])) { return $_GET[$Key];}
    return $default;
}

function Query($query){
    global $con;//use while ($row = mysqli_fetch_array($result)) { to get results
    return $con->query($query);
}

function printoption($option, $selected = "", $value = ""){
    $tempstr = "";
    if ($option == $selected) {$tempstr = " selected";}
    if (strlen($value) > 0) {$value = " value='" . $value . "'";}
    return '<option' . $value . $tempstr . ">" . $option . "</option>";
}

function printoptions($name, $valuearray, $selected = "", $optionarray, $isdisabled = ""){
    echo '<SELECT ' . $isdisabled . ' name="' . $name . '" class="form-control member_type" >';
    for ($temp = 0; $temp < count($valuearray); $temp += 1) {
        echo printoption($valuearray[$temp], $selected, $optionarray[$temp]);
    }
    echo '</SELECT>';
}

function cleanit($array){
    foreach($array as $Key => $Value){
        $array[$Key] = str_replace('"', "'", $Value);
    }
    return str_replace("\r\n", "", str_replace('\"', '"', addslashes(implode('", "',$array))));
}

function debug_string_backtrace() {
    $BACK = debug_backtrace(0);
    $BACK[2]["line"] = $BACK[1]["line"];
    return $BACK[2];
}

function debug($Iterator, $DoStacktrace = true){
    if($DoStacktrace) {
        $Backtrace = debug_string_backtrace();
        if(isset( $Backtrace["file"]) && isset($Backtrace["function"]) ) {
            echo '<B>' . $Backtrace["file"] . ' (line ' . $Backtrace["line"] . ') From function: ' . $Backtrace["function"] . '();</B> ';
        } else {
            echo '<B>UNKNOWN FILE (line ' . $Backtrace["line"] . ') From function: UNKNOWN FUNCTION();</B> ';
        }
    }

    if(is_array($Iterator)){
        echo '(array)<BR>';
        var_dump($Iterator);
    } else if (is_object($Iterator)) {
        if(is_iterable($Iterator)) {
            echo '(object array)<BR>';
            foreach ($Iterator as $It) {
                debug($It, false);
            }
        } else {
            echo '(object)<BR>';
            var_dump($Iterator);
        }
    } else {
        echo '(value)<BR>';
        echo $Iterator;
    }
}

function is_iterable($var) {
    return (is_array($var) || $var instanceof Traversable);
}
?>