<?php
$webroot = webroot();

function webroot($file = "") {
    $webroot = $_SERVER["REQUEST_URI"];
    $start = strpos($webroot, "/", 1) + 1;
    $webroot = substr($webroot, 0, $start);
    if ($_SERVER["SERVER_NAME"] != "localhost") {$webroot = str_replace("application/", "", $webroot);}
    return 'http://' . $_SERVER['HTTP_HOST'] . $webroot . $file;
}
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

function insertdb($Table, $DataArray, $PrimaryKey = "id", $Execute = True){
    global $con;
    if (is_object($con)){$DataArray = escapearray($DataArray);}
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
    if($Execute && is_object($con)) {
        mysqli_query($con, $query) or die ('Unable to execute query. '. mysqli_error($con) . "<P>Query: " . $query);
        return $con->insert_id;
    }
    return $query;
}

function escapearray($DataArray){
    global $con;
    foreach($DataArray as $Key => $Value) {
        $DataArray[$Key] = mysqli_real_escape_string($con, $Value);
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

/*
 * $getcol:
 *  blank, returns first query results
 *  false, returns all results after the get(). use while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) { to get results
 *  true, returns the SQL query
 *  "COUNT()" returns the count of the results
 *  "ALL()" all the results in an array
 *  a string returns that specific column
 *  $OrderBy/$Dir/$GroupBy = order by column/direction (ASC/DESC)/group by column
*/
function select_field_where($Table, $Where, $getcol = "", $OrderBy = "", $Dir = "ASC", $GroupBy = "", $LimitBy = 0, $Start = 0){
    $query = "SELECT * FROM " . $Table . " WHERE " . $Where;
    if($OrderBy){$query .= " ORDER BY " . $OrderBy . " " . $Dir;}
    if($GroupBy){$query .= " GROUP BY " . $GroupBy;}
    if($LimitBy){
        $query .= " LIMIT " . $LimitBy;
        if($Start){$query .= " OFFSET " . $Start;}
    }
    if($getcol === true){ return $query; }
    $result = Query($query);
    if($getcol !== false) {
        if ($getcol == "COUNT()") {
            return iterator_count($result);
        } else if ($getcol == "ALL()") {
            return first($result, false);
        } else {
            $result = first($result);
            if ($getcol) {return $result[$getcol];}
        }
    }
    return $result;
}

function deleterow($Table, $Where){
    Query("DELETE FROM $Table WHERE $Where");
}

function first($query, $Only1 = true) {
    global $con;
    if (!is_object($query)){$query = Query($query);}
    if($query) {
        $ret = array();
        while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
            if ($Only1) {return $row;}
            $ret[] = $row;
        }
        return $ret;
    }
}

function get($Key, $default = ""){
    if (isset($_POST[$Key])) { return $_POST[$Key];}
    if (isset($_GET[$Key])) { return $_GET[$Key];}
    return $default;
}

function collapsearray($Arr, $Key = ""){
    foreach($Arr as $index => $value){
        if(!$Key){
            foreach($value as $key2 => $value2){
                $Key = $key2;
                break;
            }
        }
        $Arr[$index] = $value[$Key];
    }
}

function flattenarray($arr, $key){
    foreach($arr as $index => $value){
        $arr[$index] = $value[$key];
    }
    return $arr;
}

function enum_tables(){
    return flattenarray(Query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='ai'", true), "TABLE_NAME");
}

function Query($query, $all=false){
    global $con;//use while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) { to get results
    if($all){
        $result = $con->query($query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    return $con->query($query);
}

function printoption($option, $selected = "", $value = ""){
    $tempstr = "";
    if ($option == $selected || $value == $selected) {$tempstr = " selected";}
    if (strlen($value) > 0) {$value = " value='" . $value . "'";}
    return '<option' . $value . $tempstr . ">" . $option . "</option>";
}

function printoptions($name, $valuearray, $selected = "", $optionarray = false, $isdisabled = ""){
    $tempstr = '<SELECT ' . $isdisabled . ' name="' . $name . '" id="' . $name . '">';
    if(!$optionarray){$optionarray = $valuearray;}
    for ($temp = 0; $temp < count($valuearray); $temp += 1) {
        $tempstr .= printoption($valuearray[$temp], $selected, $optionarray[$temp]);
    }
    $tempstr .= '</SELECT>';
    return $tempstr;
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

function iif($value, $istrue, $isfalse = ""){
    if($value){return $istrue;}
    return $isfalse;
}

function is_iterable($var) {
    return (is_array($var) || $var instanceof Traversable);
}

//$src = source array, $keys = the keys to remove
function removekeys($src, $keys){
    return array_diff_key($src, array_flip($keys));
}

function printrow($row, &$FirstResult = false, $PrimaryKey = "id", $TableID = ""){
    if ($FirstResult) {
        echo '<TABLE BORDER="1" CLASS="autotable"';
        if($TableID){ echo ' ID="' . $TableID . '"';}
        echo '><THEAD><TR>';
        $ID = 0;
        foreach ($row as $Key => $Value) {
            echo '<TH CLASS="' . $TableID . 'colheader" ID="' . $TableID . '-col' . $ID . '">' . $Key . '</TH>';
            $ID++;
        }
        echo '</TR></THEAD>';
        $FirstResult = false;
    }

    echo '<TR ID="' . $TableID . 'row' . $row[$PrimaryKey] . '">';
    foreach ($row as $Key => $Value) {
        echo '<TD TYPE="' . $Key . '" ID="' . $TableID . 'row' . $row[$PrimaryKey] . '-' . $Key . '"';
        if(is_numeric($Value)){echo ' ALIGN="RIGHT"';}
        if($Value == "*"){echo ' ALIGN="CENTER"';}
        echo '>';
        if(is_array($Value)){
            $FirstResult2=true;
            printrow($Value, $FirstResult2, $PrimaryKey, $TableID . $row[$PrimaryKey] . "-" . $Key);
            echo '</TABLE>';
        } else {
            echo $Value;
        }
        echo '</TD>';
    }
    echo '</TR>';
}

function addtodelstring($string, $stringtoadd, $delimiter = ","){
    $keywordids = explode($delimiter, $string);
    $keywordids[] = $stringtoadd;
    return implode($delimiter, $keywordids);
}
?>