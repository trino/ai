<?php
    use App\Http\Controllers\HomeController;

    startfile("home_list");
    $RestaurantID= "";
    $extratitle = "";
    $secondword = "list";
    $filedate = -1;
    //gets text between $start and $end in $string
    function get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
    //removes (this text) from $text
    function remove_brackets($text){
        return preg_replace('/\(([^()]*+|(?R))*\)\s*/', '', $text);
    }
    function deletefile($file){
        if(file_exists($file)){unlink($file);}
    }
    //change a field name into a column name (upper case first letter of each word, switch underscores to a space, add a space before "code")
    function formatfield($field){
        $field = explode(" ", str_replace("code", " code", str_replace("_", " ", $field)));
        foreach($field as $ID => $text){
            $field[$ID] = ucfirst($text);
            if($text == "id"){$field[$ID] = "ID";}
        }
        return implode(" ", $field);
    }
    function touchtable($table){
        setsetting($table, now(true));
        if(in_array($table, array("toppings", "wings_sauce", "menu", "additional_toppings"))){
            setsetting("menucache", now(true));
        }
    }
    function appendSQL($CurrentSQL, $AppendSQL){
        if($CurrentSQL){return $CurrentSQL . " AND " . $AppendSQL;}
        return $AppendSQL;
    }
    function newcol($field, $NoWrap = true){
        $formatted = formatfield($field);
        echo '<TH CLASS="th-left col_' . $field . '"';
        if($NoWrap){ echo ' NOWRAP';}
        echo '><DIV CLASS="pull-center nowrap"><i class="btn btn-sm btn-primary fa fa-arrow-down pull-left desc_' . $field . '" onclick="sort(' . "'" . $field . "', 'DESC'" . ')" TITLE="Sort by ' . $formatted;
        echo ' descending"> </i>' . $formatted . ' <i class="btn btn-sm btn-primary fa fa-arrow-up pull-right asc_' . $field . '" onclick="sort(' . "'" . $field . "', 'ASC'" . ')" TITLE="Sort by ' . $formatted;
        echo ' ascending"></i></DIV></TH>';
    }
    function changeorderstatus($ID, $Status, $Reason){
        //HomeController::placeorder(["action" => "changestatus", "orderid" => $ID, "status" => $Status, "reason" => $Reason]);
        App::make('App\Http\Controllers\HomeController')->placeorder(["action" => "changestatus", "orderid" => $ID, "status" => $Status, "reason" => $Reason]);
        return $ID;
    }

    //sets permissions, SQL, fields for each whitelisted table
    $TableStyle = 0;
    $namefield = "name";//which field acts as the name for the confirm delete popup
    $where = "";
    $inlineedit = true;//allow inline editing of a row
    if(isset($_POST["query"])){$_GET = $_POST["query"];}
    $adminsonly=true;//sets if you must be an admin to access the table
    $datafields=true;
    $SQL=false;
    $specialformats = false;
    $showmap=false;
    $searchcols=false;
    $profiletype = read("profiletype");
    $actionlist = [];
    switch($table){
        case "all":case "debug"://system value
            $datafields=false;
            if($table == "debug"){$secondword = "log";}
            break;
        case "actions":
            $actionlist = Query("SELECT distinct(eventname) FROM actions", true);
            foreach($actionlist as $ID => $Value){
                $actionlist[$ID] = $Value["eventname"];
            }
            $fields=true;
            break;
        case "users":
            $faicon = "user";
            $fields = array("id", "name", "phone", "profiletype", "authcode", "email");
            $searchcols = array("name");
            break;
        case "restaurants":
            $TableStyle=1;
            $fields = array("id", "name", "phone", "email", "address_id", "number", "street", "postalcode", "city", "province", "latitude", "longitude", "user_phone");
            $searchcols = array("name", "email");
            $SQL='SELECT restaurants.id, restaurants.name, restaurants.phone, restaurants.email, restaurants.address_id, useraddresses.number, useraddresses.street, useraddresses.postalcode, useraddresses.city, useraddresses.province, useraddresses.latitude, useraddresses.longitude, useraddresses.phone as user_phone FROM useraddresses AS useraddresses RIGHT JOIN restaurants ON restaurants.address_id = useraddresses.id';
            break;
        case "orders":
            $TableStyle=1;
            $fields=array("id", "user_id", "placed_at", "deliverytime", "status", "restaurant_id", "number", "unit", "street", "postalcode", "city", "province", "longitude", "latitude");
            $specialformats=array("placed_at" => "date");
            $namefield="placed_at";
            $faicon = "dollar";
            if(isset($_GET["user_id"])){
                $where = "user_id = " . $_GET["user_id"];
                if($_GET["user_id"] == read("id")){$adminsonly=false;}
            }
            $user = getuser();
            if($user["profiletype"] == 2){
                if(isset($user["Addresses"][0])){
                    $_GET["restaurant"] = first("SELECT id FROM restaurants WHERE address_id = " . $user["Addresses"][0]["id"]);
                    if(isset($_GET["restaurant"]["id"])){
                        $_GET["restaurant"] = $_GET["restaurant"]["id"];
                    } else {
                        die("Address not found for this restaurant, contact tech support");
                    }
                    $adminsonly=false;
                }
            }
            if(isset($_GET["restaurant"])){
                $where = "restaurant_id = " . $_GET["restaurant"];
                $extratitle = "for restaurant " . $_GET["restaurant"];
            }
            $where = appendSQL($where, "status <> 3");
            break;
        case "additional_toppings":
            $namefield="size";
            $fields = array("id", "size", "price");
            break;
        case "useraddresses":
            $namefield="street";
            $adminsonly=false;
            $inlineedit = false;
            $fields=true;//all fields
            if(isset($_GET["user_id"]) && $profiletype == 1){
                $where = "user_id = " . $_GET["user_id"];
            } else {
                $where = "user_id = " . read("id");
            }
            break;
        default: die("This table is not whitelisted");
    }
    if($datafields){//get all fields
        $datafields = describe($table);
        foreach($datafields as $ID => $datafield){
            $datafields[$ID]["Len"] = get_string_between($datafield["Type"], "(", ")");
            $datafields[$ID]["Type"] = remove_brackets($datafield["Type"]);
        }
        if(isset($fields) && !is_array($fields)){
            $fields = collapsearray($datafields, "Field");
        }
    }

    if(isset($_POST["action"])){
        $results = array("Status" => true, "POST" => $_POST);

        if($_POST["action"] == "saveaddress"){
            $_POST["action"] = "saveitem";
            $table = "useraddresses";
        }

        switch($_POST["action"]){
            case "getpage"://get a page of data via AJAX
                if(!in_array($table, array("all", "debug"))){
                    if($_POST["makenew"] == "true"){
                        Query("INSERT INTO " . $table . " () VALUES();");
                    }
                    if(!isset($fields)){$fields[] = "id";}

                    if($searchcols && $_POST["search"]){
                        foreach($searchcols as $ID => $Column){
                            $searchcols[$ID] = $Column . " LIKE '%" .  $_POST["search"] . "%'";
                        }
                        $searchcols = join(" OR ", $searchcols);
                        $where = appendSQL($where, $searchcols);
                    }
                    if($where){$where = " WHERE " . $where;}
                    if(!$SQL){$SQL= "SELECT " . implode(", ", $fields) . " FROM " . $table;}
                    $sort = "";
                    if($_POST["sort_col"] && $_POST["sort_dir"]){
                        $sort = " ORDER BY " . $_POST["sort_col"] . " " . $_POST["sort_dir"];
                    }
                    $results["SQL"] =  $SQL . $where . $sort . " LIMIT " . $_POST["itemsperpage"] . " OFFSET " . ($_POST["itemsperpage"] * $_POST["page"]);

                    $results["table"] = Query($results["SQL"], true);
                    if(is_array($specialformats)){
                        foreach($results["table"] as $Index => $Data){
                            foreach($specialformats as $Field => $Format){
                                switch($Format){
                                    case "date":
                                        $results["table"][$Index][$Field] = verbosedate($Data[$Field]);
                                        break;
                                }
                            }
                        }
                    }
                    $results["count"] = first("SELECT COUNT(*) as count FROM " . $table)["count"];
                }
                break;

            case "deleteitem"://delete a row
                touchtable($table);
                if(!isset($_POST["ids"]) && isset($_POST["id"])){
                    $IDS = array($_POST["id"]);
                } else {
                    $IDS = $_POST["ids"];
                }
                foreach($IDS as $id){
                    switch($table){
                        case "orders":
                            //$actions = actions("order_declined");
                            changeorderstatus($id, 2, "Order was deleted");//ID gets deleted somehow...
                            deletefile(resource_path("orders") . "/" . $id . ".json");//deletes the order file
                            break;
                        case "useraddresses":
                            Query("UPDATE restaurants SET address_id = 0 WHERE address_id = " . $id);//unbinds any restaurant from this address
                            break;
                    }
                }
                deleterow($table, "id IN(" . implode(",", $IDS) . ")");
                break;

            case "deletetable"://delete all rows
                touchtable($table);
                Query("TRUNCATE " . $table);
                break;

            case "edititem"://edit a single column in a row
                if(isset($_POST["value"])){
                    touchtable($table);
                    switch($table . "." . $_POST["key"]){
                        case "users.password":
                            $_POST["value"] = \Hash::make($_POST["value"]);
                            break;
                    }
                    insertdb($table, array("id" => $_POST["id"], $_POST["key"] => $_POST["value"]));
                }
                break;

            case "saveitem"://edit all columns in a row
                touchtable($table);
                $results["id"] = insertdb($table, $_POST["value"]);
                break;

            case "getreceipt"://get an order receipt
                $_POST["place"] = "getreceipt";
                $_POST["style"] = 2;
                die(view("popups_receipt", $_POST)->render());
                break;

            case "deletedebug"://delete the debug file
                deletefile("royslog.txt");
                break;

            case "checkdebug"://get datetime of debug log
                $results["time"] = 0;
                if (file_exists("royslog.txt")){
                    $results["time"] = filemtime("royslog.txt");
                }
                break;

            default://unhandled, error
                $results["Status"] = false;
                $results["Reason"] = "'" . $_POST["action"] . "' is unhandled \r\n" . print_r($_POST, true);
        }
        echo str_replace(":null", ':""', json_encode($results));//must return something
        die();
    } else {
        if(!isset($faicon)){$faicon = "home";}
        ?>
        @extends("layouts_app")
        @section("content")
            <STYLE>
                #pages > table > tbody > tr > td:nth-child(odd) {
                    border: 2px solid white;
                    background-color: #d9534f;
                    color: white;
                }
                #pages > table > tbody > tr > td:nth-child(even) {
                    border: 2px solid #d9534f;
                    background-color: white;
                    color: #d9534f;
                }

                .spacing * {
                    margin-left: 10px;
                    margin-top: 8px !important;
                }

                .page{
                    cursor: pointer;
                }

                .textfield{
                    width:100%;
                }

                a[disabled]{
                    cursor: not-allowed;
                    opacity: 0.5;
                }

                .table-even {
                    background: lightgrey;
                    border-color: transparent;
                }

                .table-odd{
                    border-color: transparent;
                }

                .overflow-x-scroll{
                    overflow-x: scroll;
                }


                .pull-center>i{
                    padding-top: 3px;
                    cursor: pointer;
                }

                .selected-th{
                    background: lightblue;
                }

                .selected-i{
                    color: blue;
                    background-color: white;
                    border-color: blue;
                }

                .btn{
                    border: 1px solid black !important;
                }

                .nowrap{
                    display: inline-block
                }

                #alllist li{
                    padding-left: 5px;
                }

                #alllist li a{
                    color: black;
                    font-weight: normal;
                }

                .m-t-1{
                    margin-top: 10px;
                    margin-bottom: 10px;
                }

                .margin-10{
                    margin: 10px;
                }

                .card-block{
                    padding-top: 0px;
                    padding-bottom: 0px;
                }

                .h2class{
                    margin-top: 8px !important;
                    margin-bottom: 8px !important;
                }

                #searchtext{
                    width: 150px;
                }

                #searchtext:focus{
                    margin-top: 1px;
                }

                select{
                    -webkit-appearance:menulist;
                }

                #searchtext:not(:focus){
                    border: 1px solid grey !important;
                    padding-left: 2px;
                }

                .dropdown-toggle{
                    padding-bottom: 6px;
                }

                tr, td{
                    padding-top: 4px !important;
                    padding-bottom: 4px !important;;
                }

                .status-confirmed{
                    padding-left: 8px;
                }

                label.btn{
                    margin-bottom: 0px;
                    margin-left: 4px;
                }

                .selitem{
                    position: relative;
                    vertical-align: middle;
                    bottom: 1px;
                }

                .titlecol{
                    padding-left: 4px !important;
                    padding-right: 4px !important;
                    width: 1px;
                    white-space: nowrap;
                }

                .old-btn-xs{
                    border-radius: 0;
                    width: 18px !important;
                    height: 23px;
                    padding-left: 0px;
                    padding-right: 0px;
                    font-size: small !important;
                }

                .btn-toggle{
                    border-radius: 0;
                    width: 18px !important;
                    height: 23px;
                    border: 2px solid white;
                    background-color: #d9534f;
                    color: white;
                    padding-top: 1px;
                    padding-left: 2px;
                    padding-right: 3px !important;

                }
            </STYLE>
            <div class="row m-t-1">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-block bg-danger">
                            <h2 class="pull-left text-white h2class">
                                <div class="dropdown">
                                    <Button class="btn btn-primary dropdown-toggle text-white" type="button" data-toggle="dropdown"><i class="fa fa-{{ $faicon }}"></i>
                                        {{ ucfirst($table) . ' ' . $secondword . ' ' . $extratitle }}</Button>
                                    <ul class="dropdown-menu" id="alllist">
                                        <?php
                                            //show all administratable tables
                                            foreach(array("users" => true, "restaurants" => true, "additional_toppings" => true, "useraddresses" => false, "orders" => $profiletype != 2, "actions" => true) as $thetable => $onlyadmins){
                                                if(($profiletype == 1 || !$onlyadmins) && $table != $thetable){
                                                    echo '<LI><A HREF="' . webroot("public/list/" . $thetable) . '" class="dropdown-item"><i class="fa fa-user-plus"></i> ' . ucfirst($thetable) . ' list</A></LI>';
                                                }
                                            }
                                        ?>
                                        <HR>
                                        <LI><A HREF="<?= webroot("public/editmenu"); ?>" class="dropdown-item"><i class="fa fa-user-plus"></i> Edit Menu</A></LI>
                                        <LI><A HREF="<?= webroot("public/list/debug"); ?>" class="dropdown-item"><i class="fa fa-user-plus"></i> Debug log</A></LI>
                                    </ul>
                                </div>
                            </h2>
                            <h2 CLASS="pull-right spacing">
                                @if($searchcols)
                                    <INPUT TYPE="text" placeholder="Search" id="searchtext" class="textfield" title="Press Enter to search">
                                @else
                                    <INPUT TYPE="hidden" id="searchtext">
                                @endif
                                @if($table != "all" && $profiletype == 1)
                                    @if($table == "debug")
                                        <A onclick="testemail();" TITLE="Send a test email" class="hyperlink" id="testemail" href="#"><i class="fa fa-envelope"></i></A>
                                        <A onclick="deletedebug();" TITLE="Delete the debug log" class="hyperlink" id="deletedebug" href="#"><i class="fa fa-trash-o"></i></A>
                                    @else
                                        <A onclick="selecttableitems(0);" href="#"><i class="fa fa-square-o"></i> Select None</A>
                                        <A onclick="selecttableitems(-1);" href="#"><i class="fa fa-fw fa-check-square-o" id="invert"></i> Invert Selection</A>
                                        <A onclick="selecttableitems(1);" href="#"><i class="fa fa-check-square-o"></i> Select All</A>
                                        <A onclick="deletetableitems();" href="#"><i class="fa fa-trash-o"></i> Delete Selected</A>

                                        <!--A onclick="deletetable();" TITLE="Delete the entire table" class="hyperlink" id="deletetable"><i class="fa fa-trash-o"></i></A-->
                                    @endif
                                    <A HREF="{{ webroot("public/list/all") }}" TITLE="Back"><i class="fa fa-arrow-left"></i></A>
                                @endif
                            </h2>
                        </div>
                        <div class="card-block overflow-x-scroll">
                            <div class="row">
                                <div class="col-md-12">
                                    @if($profiletype != 1 && $adminsonly || !read("id"))
                                        You are not authorized to view this page
                                        <!--a class="loggedout dropdown-item hyperlink" data-toggle="modal" data-target="#loginmodal"> <i class="fa fa-home"></i> Log In</a-->
                                        <?php
                                            if(!read("id")){
                                                echo view("popups_login")->render();
                                            }
                                        ?>
                                    @elseif($table == "debug")
                                        <DIV id="debugmessage"></DIV>
                                        <PRE id="debuglogcontents"><?php
                                            //show debug file
                                            $Contents = "";
                                            $filedate = 0;
                                            if (file_exists("royslog.txt")){
                                                $filedate = filemtime("royslog.txt");
                                                $Contents = file_get_contents("royslog.txt");
                                            }
                                            if(!$Contents) {$Contents = "The debug log is empty";}
                                            echo $Contents;
                                        ?></PRE>
                                    @else
                                        <TABLE WIDTH="100%" BORDER="1" ID="data" class="table not-table-sm not-table-responsive">
                                            <THEAD>
                                                <TR>
                                                    @if($TableStyle == 0)
                                                        <?php
                                                            newcol("id", false);
                                                            if(isset($fields)){
                                                                $last = lastkey($fields);
                                                                foreach($fields as $field){
                                                                    if($field != "id"){
                                                                        newcol($field);
                                                                    }
                                                                }
                                                            }
                                                        ?>
                                                        <TH>Actions</TH>
                                                    @endif
                                                </TR>
                                            </THEAD>
                                            <TBODY></TBODY>
                                            <TFOOT><TR><TD COLSPAN="{{ count($fields)+1 }}" ID="pages"></TD></TR></TFOOT>
                                        </TABLE>
                                        <DIV ID="body"></DIV>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <DIV ID="form" class="margin-10">
                            <?php
                                switch($table){
                                    case "useraddresses":
                                        echo '<A ONCLICK="saveaddress(0);" CLASS="btn btn-sm btn-success">New</A> ';
                                        echo '<A ONCLICK="saveaddress(selecteditem);" CLASS="btn btn-sm btn-secondary" id="saveaddress" DISABLED>Save</A>';
                                        $_GET["dontincludeGoogle"] = true;
                                        echo view("popups_address", $_GET)->render();
                                        break;
                                    case "restaurants":
                                        echo view("popups_address", array("dontincludeGoogle" => true))->render();
                                        echo '<DIV ID="addressdropdown" class="addressdropdown dont-show"></DIV>';
                                        echo '<A ONCLICK="saveaddress(-1);" CLASS="btn btn-sm btn-success m-t-1">Add to dropdowns</A>';
                                        break;
                                    case "orders":
                                        if(isset($_GET["restaurant"]) && $_GET["restaurant"]){
                                            $showmap=true;
                                            $RestaurantID = $_GET["restaurant"];
                                            $Restaurant = first("SELECT * FROM restaurants WHERE id=" . $_GET["restaurant"]);
                                            if($Restaurant){
                                                $Address = first("SELECT * FROM useraddresses WHERE id=" . $Restaurant["address_id"]);
                                                echo view("popups_googlemaps", $Address);
                                            }
                                        }
                                        break;
                                }
                            ?>
                        </DIV>

                    </div>
                </div>
            </div>
            @if($profiletype == 1 || !$adminsonly)
                <SCRIPT>//              0            1           2           3            4
                    var statuses = ["Pending", "Confirmed", "Decline Order", "Delivered", "Canceled"];
                    var usertype = ["Customer", "Admin", "Restaurant"];
                    var profiletype = '<?= $profiletype; ?>';

                    var delivery_time = <?= getdeliverytime(); ?>;
                    var getcloseststore = false;
                    var TableStyle = '<?= $TableStyle; ?>';
                    var selecteditem = 0;
                    var itemsperpage = 25;
                    var currentpage = 0;
                    var lastpage = 0;
                    var table = "<?= $table; ?>"
                    var currentURL = "<?= Request::url(); ?>";
                    var baseURL = currentURL.replace(table, "");
                    var namefield = "{{ $namefield }}";
                    var items = 0;
                    var inlineedit = "{{ $inlineedit }}".length > 0;
                    redirectonlogout = true;
                    var datafields = <?= json_encode($datafields); ?>;
                    var intranges = {
                        double: {min: 0, max: 999999},
                        tinyint: {min: -128, max: 127}, tinyintunsigned: {min: 0, max: 255},
                        smallint: {min: -32768, max: 32767}, smallintunsigned: {min: 0, max: 65535},
                        mediumint: {min: -8388608, max: 8388607}, mediumintunsigned: {min: 0, max: 16777215},
                        int: {min: -2147483648, max: 2147483647}, intunsigned: {min: 0, max: 4294967295},
                        bigint: {min: -9223372036854775808, max: 9223372036854775807}, bigintunsigned: {min: 0, max: 18446744073709551615}
                    };
                    var restaurantID = Number("<?= $RestaurantID; ?>");
                    var debuglogdate = <?= $filedate; ?>;

                    var sort_col = "", sort_dir = "";
                    function sort(col, dir){
                        if(sort_col){
                            $(".selected-th").removeClass("selected-th");
                            $(".selected-i").removeClass("selected-i");
                        }
                        if(col == sort_col && sort_dir == dir) {
                            sort_col = "";
                            sort_dir = "";
                        } else {
                            sort_col = col;
                            sort_dir = dir;
                            $(".col_" + sort_col).addClass("selected-th");
                            $("." + sort_dir.toLowerCase() + "_" + sort_col).addClass("selected-i");
                        }
                        getpage(0);
                    }

                    $(window).load(function () {
                    //$(document).ready(function() {
                        getpage(0);
                    });

                    $("#searchtext").on('keyup', function (e) {
                        if (e.keyCode == 13) {
                            getpage(0);
                        }
                    });

                    function ucfirst(string) {
                        return string.charAt(0).toUpperCase() + string.slice(1);
                    }

                    function tofieldname(name){
                        name = name.replaceAll("_", " ").replace("code", " code").replace("deliverytime", "delivery time").split(" ");
                        for(var i=0; i<name.length; i++){
                            name[i] = ucfirst(name[i]);
                            if(name[i] == "Id"){name[i] = "ID";}
                        }
                        return name.join(" ");
                    }

                    function getdata(field, data){
                        switch(table + "." + field){
                            case "orders.status":                               return statuses[data]; break;
                            case "users.profiletype":case "actions.party":      return usertype[data]; break;
                            case "users.authcode":
                                if(data){
                                    return "Not Authorized";
                                } else {
                                    return "Authorized";
                                }
                                break;

                            case "actions.sms":case "actions.phone":case "actions.email":
                            if(data == 1){
                                return "Yes";
                            } else {
                                return "No";
                            }
                            break;
                        }
                        return data;
                    }

                    function checkheaders(TableID){
                        var requiredwidth = $(".asc_id").outerWidth();
                        //var addtottable = 0;
                        $(TableID + " th div").each(function() {
                            var currentwidth = $( this ).width();
                            while($( this ).height() > 40 ){
                                currentwidth+=requiredwidth;
                                $( this ).width( currentwidth );
                                //addtottable += requiredwidth;
                            }
                        });
                        //$( TableID ).width( $( TableID ).width() + addtottable );
                    }

                    function updatesort(source){
                        if(source == 2) {//button (direction)
                            if($("#direction").hasClass("fa-arrow-down")){
                                $("#direction").removeClass("fa-arrow-down").addClass("fa-arrow-up");
                            } else {
                                $("#direction").removeClass("fa-arrow-up").addClass("fa-arrow-down");
                            }
                        }
                        var column = $("#sortby").val();
                        var direct = "ASC";
                        if($("#direction").hasClass("fa-arrow-down")){
                            direct = "DESC";
                        }
                        sort(column, direct);
                    }

                    //gets a page of data from the server, convert it to HTML
                    function getpage(index, makenew){
                        if(index==-1){index = lastpage;}
                        if(isUndefined(makenew)){makenew = false;}
                        if(index<0){index = currentpage;}
                        blockerror = true;
                        selecteditems = [];//clear selection
                        $.post(currentURL, {
                            action: "getpage",
                            _token: token,
                            itemsperpage: itemsperpage,
                            query: <?= json_encode($_GET); ?>,
                            page: index,
                            makenew: makenew,
                            search: $("#searchtext").val(),
                            sort_col: sort_col,
                            sort_dir: sort_dir
                        }).done(function (result) {
                            try {
                                var data = JSON.parse(result);
                                var HTML = "";
                                var needsAddresses = false;
                                if(data.table.length>0) {
                                    var fields = Object.keys(data.table[0]);
                                    items = 0;
                                    for (var i = 0; i < data.table.length; i++) {
                                        var evenodd = "odd";
                                        if(i % 2 == 0){evenodd = "even";}
                                        var ID = data.table[i]["id"];
                                        evenodd = "item_" + ID + ' table-' + evenodd;
                                        var CurrentDate = "";

                                        var Address = "[number] [street]<BR>[city] [province]<BR>[postalcode]";
                                        var tempHTML = '<TR ID="' + table + "_" + ID + '">';
                                        if(TableStyle == '1'){tempHTML += '<TR><TD COLSPAN="2" CLASS="' + evenodd + '" ALIGN="CENTER"><B>' + data.table[i][namefield] + '</B></TD></TR>';}
                                        for (var v = 0; v < fields.length; v++) {
                                            var field = data.table[i][fields[v]];
                                            field = getdata(fields[v], field);
                                            switch(table + "." + fields[v]){
                                                case "orders.placed_at":
                                                    CurrentDate = field;
                                                    break;
                                                case "orders.deliverytime":
                                                    field = DeliveryTime(field, CurrentDate);
                                                    break;
                                            }
                                            if (fields[v] == "phone"){
                                                field = field.replace(/[^0-9+]/g, "");
                                                if(field.length == 10) {
                                                    field = field.replace(/(\d{3})(\d{3})(\d{4})/, "($1) $2-$3");
                                                }
                                            }
                                            if(TableStyle == '1'){
                                                var formatted = tofieldname(fields[v]);
                                                tempHTML += '<TR><TD NOWRAP CLASS="titlecol ' + evenodd + '"><SPAN CLASS="pull-center"><STRONG>' + formatted + '</STRONG></SPAN></TD>';
                                                /*
                                                if(sort_col == fields[v]){tempHTML += ' selected-th';}
                                                tempHTML += '"><SPAN CLASS="pull-center"><i class="btn btn-xs btn-primary fa fa-arrow-down pull-left desc_' + fields[v];
                                                if(sort_col == fields[v] && sort_dir == "DESC"){tempHTML += ' selected-i';}
                                                tempHTML += '" onclick="sort(' + "'" + fields[v] + "', 'DESC'" + ')" TITLE="Sort by ' + formatted + ' descending"></i><STRONG>' + formatted;
                                                tempHTML += '</STRONG> <i class="btn btn-xs btn-primary fa fa-arrow-up pull-right asc_' + fields[v];
                                                if(sort_col == fields[v] && sort_dir == "ASC"){tempHTML += ' selected-i';}
                                                tempHTML += '" onclick="sort(' + "'" + fields[v] + "', 'ASC'" + ')" TITLE="Sort by ' + formatted + ' ascending"></i></SPAN></TD>';
                                                */
                                            }
                                            tempHTML += '<TD NOWRAP ID="' + table + "_" + ID + "_" + fields[v] + '" class="field ' + evenodd + '" field="' + fields[v] + '" index="' + ID + '">' + field + '</TD>';
                                            if(TableStyle == '1'){tempHTML += '</TR>';}
                                            Address = Address.replace("[" + fields[v] + "]", field);
                                        }
                                        if(TableStyle == '1'){
                                            tempHTML += '<TR><TD CLASS="' + evenodd + '" align="center"><STRONG>Actions</STRONG></TD>';
                                        }
                                        tempHTML += '<TD CLASS="' + evenodd + '" NOWRAP>';
                                        switch(table){
                                            case "users":
                                                tempHTML += '<A CLASS="btn btn-sm btn-success cursor-pointer" href="' + baseURL + 'useraddresses?user_id=' + ID + '">Addresses</A> ';
                                                tempHTML += '<A CLASS="btn btn-sm btn-secondary cursor-pointer" href="{{ webroot("public/user/info/") }}' + ID + '">Edit</A> ';
                                                tempHTML += '<A CLASS="btn btn-sm btn-success cursor-pointer" ONCLICK="changepass(' + ID + ');" TITLE="Change their password">Password2 </A> ';
                                                break;
                                            case "useraddresses":
                                                tempHTML += '<A CLASS="btn btn-sm btn-success cursor-pointer" onclick="editaddress(' + ID + ');">Edit</A> ';
                                                break;
                                            case "orders":
                                                tempHTML += '<A CLASS="btn btn-sm btn-success cursor-pointer" onclick="vieworder(' + ID + ');">View</A> ';
                                                if(restaurantID){
                                                    var Name = ID;
                                                    if(data.table[i]["unit"].length > 0){
                                                        Name += " (" + data.table[i]["unit"] + ")";
                                                    }
                                                    addmarker2(Name, data.table[i]["latitude"], data.table[i]["longitude"], ", ", "Order ID: ", "<BR>" + Address);
                                                    needsAddresses=true;
                                                }
                                                break;
                                            case "restaurants":
                                                tempHTML += '<A CLASS="btn btn-sm btn-success cursor-pointer" HREF="{{ webroot("public/list/orders?restaurant=") }}' + ID + '">View</A> ';
                                                break;
                                        }
                                        if(profiletype == 1) {
                                            tempHTML += '<A CLASS="btn btn-sm btn-danger cursor-pointer" onclick="deleteitem(' + ID + ');">Delete</A>';
                                            tempHTML += '<label CLASS="btn btn-sm cursor-pointer"><input type="checkbox" class="selitem" index="' + ID + '" onclick="selecttableitem(this, ' + ID + ');"> Select</label>';
                                        }
                                        HTML += tempHTML + '</TD></TR>';
                                        items++;
                                        if(TableStyle == '1'){
                                            HTML += '</TR>';
                                        }
                                    }
                                    if(needsAddresses) {addmarker2();}
                                } else {
                                    HTML = '<TR><TD COLSPAN="100">No results found' + result + '</TD></TR>';
                                }
                                currentpage=index;
                                $("#data > TBODY").html(HTML);
                                checkheaders("#data");
                                generatepagelist(data.count, index);
                                if(TableStyle == 1){
                                    HTML = 'Sort by: <SELECT ID="sortby" onchange="updatesort(1);">';
                                    for(var i=0; i<fields.length; i++){
                                        HTML += '<OPTION VALUE="' + fields[i] + '"';
                                        if(sort_col == fields[i]){HTML += ' SELECTED';}
                                        HTML += '>' + tofieldname(fields[i]) + '</OPTION>';
                                    }
                                    HTML += '</SELECT><i ID="direction" class="btn btn-xs btn-primary btn-toggle fa fa-arrow-';
                                    if(sort_dir == "DESC"){HTML += "down";} else {HTML += 'up';}
                                    $("#sortcols").html(HTML + '" onclick="updatesort(2);" TITLE="Change direction"></i>');
                                }

                                @if($profiletype == 1)
                                    $(".field").dblclick(function() {//set field double click handler
                                        var field = $(this).attr("field");
                                        var columnindex = findwhere(datafields, "Field", field);
                                        var column = datafields[columnindex];
                                        var ID = $(this).attr("index");
                                        if (isUndefined(column)) {
                                            switch(table){
                                                case "restaurants":
                                                    confirm2('The restaurant address can not be edited directly from here. Would you like to go to the address editor?', 'Edit Address', function(){
                                                        ID = $("#restaurants_" + ID + "_address_id").text();
                                                        window.location = webroot + "list/useraddresses?key=id&value=" + ID;
                                                    });
                                                    break;
                                                default: alert(makestring("{cant_edit}", {table: table, field: field}));
                                            }
                                        } else if(column["Key"] != "PRI"){//primary key can't be edited
                                            selecteditem = ID;
                                            var HTML = $(this).html();
                                            var isHTML = containsHTML(HTML);
                                            var isText = false;
                                            var colname = table + "." + field;
                                            switch(colname){
                                                case "orders.latitude": case "orders.longitude":
                                                    column["Type"] = "int";
                                                    break;
                                            }
                                            if(!isHTML && inlineedit){//check what datatype the column is, and switch the text with the appropriate input type
                                                isText=true;
                                                var isSelect=false;
                                                var title="";
                                                log("Type clicked: " + column["Type"] + " Colname: " + colname);
                                                switch(column["Type"]){
                                                    //timestamp (date)
                                                    case "tinyint": case "smallint": case "mediumint": case "bigint":case "int":case "double":
                                                    case "tinyintunsigned": case "smallintunsigned": case "mediumintunsigned": case "bigintunsigned": case "intunsigned":
                                                        var min = intranges[column["Type"]]["min"];
                                                        var max = intranges[column["Type"]]["max"];
                                                        switch(colname){//numbers only
                                                            case "orders.placed_at": return; break;
                                                            case "orders.status":
                                                                isSelect=true;
                                                                HTML = makeselect(ID + "_" + field, "selectfield form-control", colname, HTML, arraytooptions(statuses));
                                                                break;

                                                            case "users.profiletype": case "actions.party":
                                                                isSelect=true;
                                                                HTML = makeselect(ID + "_" + field, "selectfield form-control", colname, HTML, arraytooptions(usertype));
                                                                break;

                                                            case "restaurants.address_id":
                                                                isSelect=true;
                                                                console.log(HTML + " was selected");
                                                                HTML = $("#addressdropdown").html().replace('form-control', 'selectfield form-control').replace(' id="saveaddresses"', ' ID="' + ID + "_" + field + '" COLNAME="' + colname + '"').replace('value="' + HTML + '"', 'value="' + HTML + '" SELECTED');
                                                                console.log(HTML + " was edited");
                                                                break;

                                                            case "actions.sms":case "actions.phone":case "actions.email"://boolean values
                                                                isSelect=true;
                                                                HTML = makeselect(ID + "_" + field, "selectfield form-control", colname, HTML, [{value: 0, text: "No"}, {value: 1, text: "Yes"}]   );
                                                                break;

                                                            default:
                                                                title= "Type: " + colname;
                                                                HTML = '<INPUT TYPE="NUMBER" ID="' + ID + "_" + field + '" VALUE="' + HTML + '" CLASS="textfield" TITLE="' + title + '" MIN="';
                                                                HTML += min + '" MAX="' + max + '" COLNAME="' + colname + '">';
                                                        }
                                                        break;
                                                    default://simple text
                                                        switch(colname){
                                                            case "actions.eventname":
                                                                isSelect=true;
                                                                HTML = makeselect(ID + "_eventname", "selectfield form-control", colname, HTML, <?= json_encode($actionlist); ?> );
                                                                break;
                                                            case "users.authcode":
                                                                edititem(ID, "authcode", "");
                                                                alert(makestring("{user_auth}"));
                                                                return;
                                                                break;
                                                            default:
                                                                HTML = '<INPUT TYPE="TEXT" ID="' + ID + "_" + field + '" VALUE="' + HTML + '" CLASS="textfield" COLNAME="' + colname;
                                                                HTML += '" maxlength="' + column["Len"] + '" TITLE="' + title + '">';
                                                        }
                                                }
                                                console.log(HTML);
                                                $(this).html(HTML);
                                                if(isSelect){
                                                    $("#" + ID + "_" + field).focus().change(function () {
                                                        if(table == "restaurants"){
                                                            var Selected = $("#" + ID + "_" + field + " option:selected");
                                                            for(var keyID = 0; keyID < addresskeys.length; keyID++){
                                                                var keyname = addresskeys[keyID];
                                                                var keyvalue= $(Selected).attr(keyname);
                                                                if(keyname == "phone"){keyname="user_phone";}
                                                                var elementID = "#" + table + "_" + ID + "_" + keyname;
                                                                $(elementID).text(keyvalue);
                                                            }
                                                        }
                                                        edititem(ID, field, $(this).val());
                                                    }).blur(function(){
                                                        edititem(ID, field, $(this).val());
                                                    });
                                                } else if(isText) {
                                                    $("#" + ID + "_" + field).focus().select().keypress(function (ev) {
                                                        var keycode = (ev.keyCode ? ev.keyCode : ev.which);
                                                        if (keycode == '13') {
                                                            edititem(ID, field, $(this).val());
                                                        }
                                                    }).blur(function(){
                                                        edititem(ID, field, $(this).val());
                                                    });
                                                }
                                            } else if (!isHTML) {
                                                switch(table){
                                                    case "useraddresses":
                                                        editaddress(ID);
                                                        break;
                                                }
                                            }
                                        }
                                    });
                                @endif
                            } catch (e){
                                $("#body").html(e + " NON-JSON DETECTED: <BR>" + result);
                                return false;
                            }
                        }).fail(function(xhr, status, error) {
                            getpage(index);
                        });
                    }


                    //BEGIN DATE FORMAT (Clones PHP's formatting)           EXAMPLE
                    //DAY
                    //j     day of month                                    (1-31)
                    //d     day of month padded to 2 digits                 (01-31)
                    //N     day of week                                     (1-7)
                    //w     day of week                                     (0-6)
                    //D     day of week short                               (Sun-Sat)
                    //l     day of week long                                (Sunday-Saturday)
                    //S     english suffix, works well with j               (ie: day 1 of the month would be "st", 2 would be "nd", 3 would be "rd")
                    //z     day of the year                                 (0-365)
                    //WEEK
                    //W     week number of year, starting on monday         (0-51)
                    //MONTH
                    //F     long month name                                 (January-December)
                    //M     short month name                                (Jan-Dec)
                    //m     month number padded to 2 digits                 (01-12)
                    //n     month number                                    (1-12)
                    //t     number of days in the month                     (28-31)
                    //YEAR
                    //L     whether it's a leap year                        (0=no, 1=yes)
                    //o     ISO-8601 week-numbering year                    (NOT SUPPORTED!!)
                    //Y     long year                                       (1999 or 2017)
                    //y     short year                                      (99 or 17)
                    //TIME
                    //a     Lowercase Ante meridiem and Post meridiem       (am/pm)
                    //A     Uppercase Ante meridiem and Post meridiem       (AM/PM)
                    //B     Swatch Internet time                            (NOT SUPPORTED!!)
                    //g     12-hour format of an hour without leading zeros	(1-12)
                    //G     24-hour format of an hour without leading zeros	(0-23)
                    //h     12-hour format of an hour with leading zeros	(01-12)
                    //H     24-hour format of an hour with leading zeros	(00-23)
                    //i     Minutes, with leading zeroes                    (00-59)
                    //s     Seconds, with leading zeroes                    (00-59)
                    //u     Microseconds                                    (NOT SUPPORTED!!)
                    //v     Milliseconds                                    (0-999)
                    //TIMEZONE
                    //e     Timezone identifier                             (NOT SUPPORTED!!)
                    //T     Timezone abbreviation                           (NOT SUPPORTED!!)
                    //I     Whether or not the date is in DST               (0=no, 1=yes)
                    //O     Timezone offset (hours then minutes)            (+200)
                    //o     Timezone offset without + (hours then minutes)  (200)
                    //P     Timezone offset (hours:minutes)                 (+2:00)
                    //Z     Timezone offset in seconds                      (-43200 to 50400)
                    //FULL DATE
                    //c     ISO 8601 date                                   (2004-02-12T15:19:21+00:00)
                    //r     RFC 2822 formatted date                         (Thu, 21 Dec 2000 16:01:07 +0200)
                    //U    epoch time (145200000)
                    //NOT SUPPORTED: o, B, u, e, T
                    var days_of_week = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                    var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                    //If timestamp is undefined, the current timestamp will be used
                    function FormatDate(format, timestamp, forcetimezone){
                        if(isUndefined(timestamp)){timestamp = Date.now();}
                        if(isUndefined(format)){format = "j d N w D l S z W F M m n t L o Y y a A B g G h H i s u v e T I O P Z c r O";}
                        format = format.replace("c", "Y-m-dTG:i:sP").replace("r", "D, j M Y G:i:s O");
                        format = format.split('');

                        var the_date = new Date(timestamp);
                        var timezone = the_date.getTimezoneOffset();//offset in minutes
                        if(!isUndefined(forcetimezone)){
                            if(timezone != forcetimezone) {
                                var offset = forcetimezone - timezone;
                                log("Timezone: " + timezone + " Forced: " + forcetimezone + " Offset: " + offset);
                                var offset_hours = Math.floor(offset / 60);
                                var offset_mins  = offset % 60;
                                if(offset_hours != 0){the_date.setHours(the_date.getHours() + offset_hours);}
                                if(offset_mins != 0){the_date.setHours(the_date.getMinutes() + offset_mins);}
                            }
                        }

                        var day_of_month = the_date.getDate();//1-31
                        var day_of_week = the_date.getDay();//0-6
                        var the_month = the_date.getMonth()+1;//1-12
                        var the_year = the_date.getFullYear();//2017
                        var hours = the_date.getHours();//0-23
                        var minutes = the_date.getMinutes();//0-59
                        var seconds = the_date.getSeconds();//0-59
                        var milliseconds = the_date.getMilliseconds();//0-999
                        var antepost = iif(hours < 12, "am", "pm");
                        var timezone_hours = Math.floor(timezone / 60);
                        var timezone_mins  = timezone % 60;

                        //DAY
                        format = format.replace("j", day_of_month).replace("d", day_of_month.pad(2));
                        format = format.replace("N", day_of_week+1).replace("w", day_of_week);
                        format = format.replace("D", days_of_week[day_of_week].left(3)).replace("l", days_of_week[day_of_week]);
                        format = format.replace("S", getSuffix(day_of_month));//suffix for day_of_month, works well with j
                        format = format.replace("z", the_date.getDOY());
                        //WEEK
                        format = format.replace("W", the_date.getWOY());
                        //MONTH
                        format = format.replace("F", months[the_month-1]).replace("M", months[the_month-1].left(3));
                        format = format.replace("m", the_month.pad(2)).replace("n", the_month);
                        format = format.replace("t", the_date.getDIM());
                        //YEAR
                        format = format.replace("L", iif(the_date.isLeapYear(), 1, 0));
                        format = format.replace("Y", the_year).replace("y", the_year % 100);
                        //TIME
                        format = format.replace("a", antepost).replace("A", antepost.toUpperCase());
                        format = format.replace("g", hours % 12 + 1).replace("G", hours);
                        format = format.replace("h", (hours % 12 + 1).pad(2)).replace("H", hours.pad(2));
                        format = format.replace("i", minutes.pad(2)).replace("s", seconds.pad(2)).replace("v", milliseconds);
                        //TIMEZONE
                        format = format.replace("O", iif(timezone_hours>0, "+") + Math.abs(timezone_hours) + "" + timezone_mins.pad(2));
                        format = format.replace("P", iif(timezone_hours>0, "+") + Math.abs(timezone_hours) + ":" + timezone_mins.pad(2));
                        format = format.replace("Z", timezone*60).replace("o", timezone_hours + "" + timezone_mins.pad(2));

                        //FULL DATE
                        format = format.replace("U", Math.floor(Date.now()/1000));//epoch time

                        /*NOT SUPPORTED
                        for(var i = 0; i < format.length; i++){
                            switch(format[i]){
                                case "o": case "B": case "u": case "e": case "T": format[i] = "['" + format[i] + "' IS NOT SUPPORTED]"; break;
                            }
                        }*/
                        return format.join('');
                    }

                    Array.prototype.replace = function(searchfor, replacewith){
                       for(var i = 0; i < this.length; i++){
                           if(this[i] == searchfor){
                               this[i] = replacewith;
                           }
                       }
                        return this;
                    };

                    Date.prototype.isLeapYear = function() {
                        var year = this.getFullYear();
                        if((year & 3) != 0) return false;
                        return ((year % 100) != 0 || (year % 400) == 0);
                    };

                    function getSuffix(Number){
                        switch (Number % 10){
                            case 1: return "st"; break;
                            case 2: return "nd"; break;
                            case 3: return "rd"; break;
                            default: return "th"; break;
                        }
                    }

                    // Get Day of Year
                    Date.prototype.getDOY = function() {
                        var dayCount = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
                        var mn = this.getMonth();
                        var dn = this.getDate();
                        var dayOfYear = dayCount[mn] + dn;
                        if(mn > 1 && this.isLeapYear()) dayOfYear++;
                        return dayOfYear;
                    };

                    //Get Days in month
                    Date.prototype.getDIM = function(){
                        var month = this.getMonth();//0-11
                        var year = this.getFullYear();//2017
                        return new Date(year, month, 0).getDate();
                    };

                    //Get week of year
                    Date.prototype.getWOY = function() {
                        var now = new Date();
                        var onejan = new Date(now.getFullYear(), 0, 1);
                        return Math.ceil((((now - onejan) / 86400000) + onejan.getDay() + 1) / 7);
                    };
                    //END DATE FORMAT




                    function parseverbosedate(TheDate){
                        log("Parsing: " + TheDate);
                        TheDate = TheDate.split(" ");//0=dayofweek 1=month 2=day, 3=year 4=at 5=time 6=AMPM
                        var time = TheDate[5].split(":");
                        if(TheDate[6] == "PM"){
                            time[0] = Number(time[0]) + 12;
                        }
                        var month = months.indexOf(TheDate[1]);
                        var day = TheDate[2].replace(",", "");
                        //return "Year: " + TheDate[3] + " Month: " + month + " (" + TheDate[1] + ") Day: " + day + " Hour: " + time[0] + " Minute: " + time[1];
                        return new Date(TheDate[3], month, day, time[0], time[1], 0, 0);
                    }

                    function DeliveryTime(Delivery_Time, Placed_At, AddBadges){
                        var Original_Delivery_Time = Delivery_Time;
                        if(isUndefined(AddBadges)){AddBadges = true;}
                        //Delivery_Time: "Deliver Now", "February 21 at 1455"
                        //Placed_At: "Tuesday February 21, 2017 @ 2:01 PM"
                        var hrs = FormatDate("o"); //-(new Date().getTimezoneOffset() / 60);//UTC offset
                        Placed_At = parseverbosedate(Placed_At);
                        var ASAP = false;
                        if(Delivery_Time == "Deliver Now"){
                            ASAP = true;
                            Delivery_Time =  new Date(Placed_At);
                            Delivery_Time.setMinutes(Delivery_Time.getMinutes() + delivery_time);
                        } else {
                            Delivery_Time = Delivery_Time.split(" ");//0=Month 1=Day 2=at 3=time
                            //Convert from: [0]=February [1]=21 [2]=at [3]=1455
                            //Convert to:   Tuesday February 21, 2017 @ 2:55 PM
                            var hour = Delivery_Time[3].left( Delivery_Time[3].length -2 );
                            var AMPM = "AM";
                            if (hour > 11){
                                if(hour>12) {hour -= 12;}
                                AMPM = "PM";
                            }
                            Delivery_Time = parseverbosedate("IrrelevantDay " + Delivery_Time[0] + " " + Delivery_Time[1] + ", " + Placed_At.getFullYear() + " @ " + hour + ":" + Delivery_Time[3].right(2) + " " + AMPM);
                        }

                        if(!AddBadges){return ret;}
                        if(ASAP){
                            var ret = toDate(Delivery_Time) + ' <SPAN CLASS="badge badge-pill badge-info">ASAP</SPAN>';
                            Delivery_Time = Date.parse(Delivery_Time);
                        } else {
                            var ret = toDate(Delivery_Time) + ' <SPAN CLASS="badge badge-pill badge-primary">TIMED</SPAN>';//Original time: ' + Delivery_Time;
                        }
                        var Now = Date.now();
                        if(Now>Delivery_Time){
                            ret += ' <SPAN CLASS="badge badge-pill badge-danger">[EXPIRED]</SPAN>';
                        } else {
                            var time_remaining = (Delivery_Time-Now)/1000;//seconds remaining
                            var hours = Math.floor(time_remaining/3600);
                            var minutes = Math.floor(time_remaining % 3600 / 60);
                            var seconds = Math.floor(time_remaining % 60);
                            ret += ' <SPAN CLASS="countdown badge badge-pill badge-success" hours="' + hours + '" minutes="' + minutes + '" seconds="' + seconds + '">' + toRemaining(hours, minutes, seconds) + '</SPAN>';
                        }
                        return ret;
                    }

                    function toRemaining(hours, minutes, seconds){
                        var days = 0;
                        if(seconds == 0 && hours == 0 && minutes == 0){return "[EXPIRED]";}
                        if(minutes>60){
                            hours = hours + Math.floor(minutes / 60);
                            minutes = minutes % 60;
                        }
                        if(hours > 24){
                            days = Math.floor(hours / 24);
                            hours = hours % 24;
                        }
                        var ret = minpad(minutes) + "m:" + minpad(seconds) + "s";
                        if(hours > 0){ret = hours + "h:" + ret;}
                        if(days > 0){ret = days + "d:" + ret;}
                        return ret;
                    }

                    function toDate(UTC){
                        if(!isNaN(UTC)){UTC = Date.parse(UTC);}//returns "Tuesday February 21, 2017 @ 2:01 PM"
                        var d = new Date(UTC);
                        var Hour = d.getHours();
                        var AMPM = "AM";
                        if (Hour > 11){
                            if(Hour>12) {Hour -= 12;}
                            AMPM = "PM";
                        }
                        var Min = minpad(d.getMinutes());
                        return days_of_week[d.getDay()] + " " + months[d.getMonth()] + " " + d.getDate() + ", " + d.getFullYear() + " @ " + Hour + ":" + Min + " " + AMPM;
                    }
                    function minpad(time) {
                        if (time < 10) {
                            return "0" + time;
                        }
                        return time;
                    }
                    //make a SELECT dropdown
                    function makeselect(ID, classnames, colname, selected, kvps){
                        var HTML = '<SELECT ID="' + ID + '" CLASS="'  + classnames + '" COLNAME="' + colname + '">';
                        for(var keyID = 0; keyID<kvps.length; keyID++){
                            var isselected = false;
                            var text = "";
                            var kvp = kvps[keyID];
                            HTML += '<OPTION';
                            if(isObject(kvp)){
                                HTML += ' VALUE="' + kvp["value"] + '"';
                                isselected = selected.isEqual(kvp["value"]);
                                text = kvp["text"];
                            } else {
                                text = kvp;
                            }
                            if(selected.isEqual(text)){isselected = true;}
                            if(isselected){HTML += ' SELECTED';}
                            HTML += '>' + text + '</OPTION>';
                        }
                        return HTML + '</SELECT>';
                    }

                    //checks if a field contains HTML (so we know if it's being edited) or not
                    function containsHTML(text){
                        return text.indexOf("<") > -1 && text.indexOf(">") > -1;
                    }

                    function arraytooptions(arr){
                        var options = new Array;
                        for(var min=0;min<arr.length;min++){
                            options.push({value: min, text: arr[min]});
                        }
                        return options;
                    }

                    //generates a list of page links
                    function generatepagelist(itemcount, currentpage){
                        currentpage = Number(currentpage);
                        var pages = Math.ceil(Number(itemcount) / itemsperpage);
                        lastpage = pages-1;
                        var HTML = '<BUTTON CLASS="btn btn-sm btn-success" onclick="newitem();">New</BUTTON>';
                        if(TableStyle == '1'){HTML = '<SPAN ID="sortcols"></SPAN>';}
                        HTML += '<TABLE BORDER="1" CLASS="pull-right"><TR>';
                        var printpages = 10;
                        if(pages > 1){
                            if(currentpage > 0){HTML += '<TD><A CLASS="page" page="0" title="Page 1 of ' + pages + '"> First </A></TD>';}
                            if(currentpage > 1){HTML += '<TD><A CLASS="page" page="' + (currentpage-1) + '" title="Page ' + currentpage + ' of ' + pages + '"> Prev </A></TD>';}
                            var start = currentpage - (printpages*0.5);
                            for(var i = start; i <= start+printpages; i++){
                                if(i == currentpage){
                                    HTML += '<TD><B TITLE="Current page">[' + (i + 1) + ']</B></TD>';
                                } else if (i < pages - 1 && i > 0) {
                                    HTML += '<TD><A CLASS="page" page="' + i + '" title="Page ' + (i + 1) + ' of ' + pages + '"> ' + (i + 1) + ' </A></TD>';
                                }
                            }

                            if(currentpage < pages-2){HTML += '<TD><A CLASS="page" page="' + (currentpage+1) + '" title="Page ' + (currentpage+2) + ' of ' + pages + '"> Next </A></TD>';}
                            if(currentpage < pages-1){HTML += '<TD><A CLASS="page" page="' + (pages-1) + '" title="Page ' + pages + ' of ' + pages + '"> Last </A></TD>';}
                        } else {
                            HTML += '<TD><B TITLE="Current page">[' + (currentpage + 1) + ']</B></TD>';
                        }
                        $("#pages").html(HTML + '</TR></TABLE>');

                        $(".page").click(function() {
                            var page = $(this).attr("page");
                            getpage(page);
                        });
                    }

                    //delete everything in a table, confirm twice
                    function deletetable(){
                        confirm2("Are you sure you want to delete the entire " + table + " table?", 'Delete Table', function(){
                            $.post(currentURL, {
                                action: "deletetable",
                                _token: token,
                            }, function (result) {
                                if (handleresult(result)) {
                                    location.reload();
                                }
                            });
                        });
                    }

                    //delete a single item in a table
                    function deleteitem(ID){
                        var name = $("#" + table + "_" + ID + "_" + namefield).text();
                        confirm2("Are you sure you want to delete item ID: " + ID + " (" + name + ") ?", "Delete Item", function(){
                            //if(table == "orders"){changeorderstatus(ID, 2);}//done server-side now
                            $.post(currentURL, {
                                action: "deleteitem",
                                _token: token,
                                ids: [ID]
                            }, function (result) {
                                if(handleresult(result)) {
                                    deletetableitem(ID);
                                }
                            });
                        });
                    }
                    function deletetableitem(ID){
                        selecteditem=0;
                        $("#saveaddress").attr("disabled", true);
                        if(TableStyle == '0') {
                            $("#" + table + "_" + ID).fadeOut(500, function () {
                                $("#" + table + "_" + ID).remove();
                            });
                        } else {
                            $(".item_" + ID).fadeOut(500, function () {
                                $(".item_" + ID).remove();
                            });
                        }
                        items--;
                        if(items == 0){
                            location.reload();
                        }
                    }

                    //add a new item to the table, load the last page
                    function newitem(){
                        getpage(-1, true);
                    }

                    function testemail(){
                        $("#debuglogcontents").html("Sending email. Please standby");
                        $.post(currentURL, {
                            action: "testemail",
                            _token: token
                        }, function (result) {
                            if(!result){result = "Email sent successfully!";}
                            $("#debuglogcontents").html(result);
                        });
                    }

                    //delete the debug file
                    function deletedebug(){
                        confirm2("Are you sure you want to delete the debug log?", "Delete Log", function(){
                            $.post(currentURL, {
                                action: "deletedebug",
                                _token: token
                            }, function (result) {
                                if(handleresult(result)) {
                                    $("#deletedebug").hide();
                                    $("#debuglogcontents").html("The debug log is empty");
                                }
                            });
                        });
                    }

                    function changepass(ID){
                        inputbox2(makestring("{new_passw}"), "Change Password", "123abc", function(response){
                            edititem(ID, "password", response);
                            log(ID + "'s password has been updated to " + response);
                        });
                    }

                    //edit a single column in a row, verifying the data
                    function edititem(ID, field, data){
                        var colname = table + "." + field;//$("#" + ID + "_" + field).attr("COLNAME").toLowerCase();
                        var newdata=data;
                        if(data) {
                            var datatype="";
                            newdata = getdata(field, data);
                            switch (colname) {
                                case "users.phone":case "restaurants.phone":
                                    newdata = clean_data(newdata, "phone");
                                    datatype="phone number";
                                    break;
                                case "users.email":case "restaurants.email":
                                    if(validate_data(data, "email")){newdata = clean_data(data, "email");}
                                    datatype="email address";
                                    break;
                            }
                            log("Verifying: " + colname + " = '" + data + "' (" + datatype + ")");
                            if(datatype) {
                                if (newdata) {
                                    data = newdata;
                                } else {
                                    alert(makestring("{not_valid}", {data: data, datatype: datatype}));//alert("'" + data + "' is not a valid " + datatype);
                                    return false;
                                }
                            }
                        }
                        $.post(currentURL, {
                            action: "edititem",
                            _token: token,
                            id: ID,
                            key: field,
                            value: data
                        }, function (result) {
                            if(handleresult(result)) {
                                log(table + "." + field + " became " + newdata);
                                $("#" + table + "_" + ID + "_" + field).html(newdata);
                                $("#formatted_address").show().val("");
                            }
                        });
                    }

                    //edit a restaurant address
                    function editaddress(ID){
                        selecteditem = ID;
                        var streetformat = "[number] [street], [city]";
                        $("#useraddresses_" + ID + " > TD").each(function(){
                            var field = $(this).attr("field");
                            var value = $(this).text();
                            streetformat = streetformat.replace("[" + field + "]", value);
                            $("#add_" + field).val( value );
                        });
                        $("#formatted_address").val(streetformat);
                        $("#saveaddress").removeAttr("disabled");
                    }

                    //save an address
                    function saveaddress(ID){
                        var formdata = getform("#googleaddress");
                        var keys = Object.keys(formdata);
                        for(var i = 0; i<keys.length;i++){
                            var key = keys[i];
                            switch(key) {
                                case "unit": case "buzzcode": break;
                                default:
                                    if(formdata[key].trim().length == 0 && key == "user_id") {formdata[key] = userdetails.id;}
                                    if(formdata[key].trim().length == 0){
                                        alert(makestring("{not_empty}", {data: key}));
                                        return false;
                                    }
                            }
                        }

                        if (ID && ID > -1) {formdata.id = ID;}
                        $.post(currentURL, {
                            action: "saveaddress",
                            _token: token,
                            value: formdata
                        }, function (result) {
                            if (handleresult(result)) {
                                if(ID == -1){
                                    var HTML = AddressToOption(formdata);
                                    $(".saveaddresses").append(HTML);
                                    alert(makestring("{new_addrs}", formdata));
                                } else {
                                    getpage(lastpage);
                                }
                            }
                        });

                    }

                    //view an order receipt
                    function vieworder(ID){
                        $.post(currentURL, {
                            action: "getreceipt",
                            _token: token,
                            orderid: ID,
                            includeextradata: 1
                        }, function (result) {
                            if(result) {
                                var button = '<DIV CLASS="col-md-6"><button data-dismiss="modal" class="width-full btn btn-';
                                var HTML = '<DIV CLASS="row col-md-12" style="padding-left: 25px;">';// + button + 'primary status-confirmed" onclick="changeorderstatus(' + ID + ', 1);">' + statuses[1] + '</button></DIV>';
                                HTML += button + 'secondary pull-center red status-email" onclick="changeorderstatus(' + ID + ');">Email Receipt To Customer</button></DIV>';
                                //HTML += button + 'warning pull-right status-delivered" onclick="changeorderstatus(' + ID + ', 3);">' + statuses[3] + '</button></DIV>';
                                HTML += button + 'danger pull-right status-declined" onclick="changeorderstatus(' + ID + ', 2);">' + statuses[2] + '</button></DIV></DIV>';
                                $("#ordercontents").html(result + HTML);
                                $("#ordermodal").modal("show");
                                @if(!$showmap)
                                    showmap();
                                @endif
                            }
                        });
                    }

                    //universal AJAX error handling
                    function handleresult(result, title){
                        try {
                            var data = JSON.parse(result);
                            if(data["Status"] == "false" || !data["Status"]) {
                                alert(data["Reason"], title);
                            } else {
                                return true;
                            }
                        } catch (e){
                            alert(result, title);
                        }
                        return false;
                    }

                    function changeorderstatus(ID, Status, Reason){
                        //edititem(ID, "status", Status);
                        if(isUndefined(Status)){
                            Status = -1;
                            Reason = "";
                        } else if(isUndefined(Reason)) {
                            inputbox2(makestring("{new_statu}", statuses[Status].toLowerCase()), statuses[Status] + " Order", "Type the reason here", function(response){
                                changeorderstatus(ID, Status, response);
                            });
                            return false;
                        }
                        $.post(webroot + "placeorder", {
                            action: "changestatus",
                            _token: token,
                            orderid: ID,
                            status: Status,
                            reason: Reason
                        }, function (result) {
                            if(handleresult(result)) {
                                var newdata = statuses[Status];
                                $("#" + table + "_" + ID + "_status").html(newdata);
                                result=JSON.parse(result);
                                alert(result["Reason"]);
                            }
                        });
                    }

                    //data vealidation handling
                    function validate_data(Data, DataType){
                        if(Data) {
                            switch (DataType.toLowerCase()) {
                                case "email":
                                    var re = /\S+@\S+\.\S+/;
                                    return re.test(Data);
                                    break;
                                case "postalzip":
                                    return validate_data(Data, "postalcode") || validate_data(Data, "zipcode");
                                    break;
                                case "zipcode"://99577-0727
                                    Data = clean_data(Data, "number");
                                    return Data.length == 5 || Data.length == 9;
                                    break;
                                case "postalcode":
                                    Data = Data.replace(/ /g, '').toUpperCase(); //Postal codes do not include the letters D, F, I, O, Q or U, and the first position also does not make use of the letters W or Z.
                                    var regex = new RegExp(/^[ABCEGHJKLMNPRSTVXY]\d[ABCEGHJKLMNPRSTVWXYZ]?\d[ABCEGHJKLMNPRSTVWXYZ]\d$/i);
                                    return regex.test(Data);
                                    break;
                                case "phone":
                                    return true;//skipping validation for now
                                    var phoneRe = /^[2-9]\d{2}[2-9]\d{2}\d{4}$/;
                                    var regex = /[^\d+]/;
                                    var Data2 = clean_data(Data, "number");
                                    return (Data2.match(phoneRe) !== null || Data2.length > 0);
                                    break;
                                case "sin":
                                    Data = clean_data(Data, "number");
                                    return Data.length == 9;
                                    break;
                                case "number":
                                    Data = clean_data(Data, "number");
                                    return Data && !isNaN(Data);
                                default:
                                    alert(makestring("{unhandled}", {datatype: DataType}));
                            }
                        }
                        return true;
                    }

                    function clean_data(Data, DataType){
                        Data = Data.trim();
                        if(Data) {
                            switch (DataType.toLowerCase()) {
                                case "alphabetic":
                                    Data = Data.replace( /[^a-zA-Z]/, "");
                                    break;
                                case "alphanumeric":
                                    Data = Data.replace(/\W/g, '');
                                    break;
                                case "number":
                                    Data = Data.replace(/\D/g, "");
                                    break;
                                case "email":
                                    Data = Data.toLowerCase().trim();
                                    break;
                                case "postalzip":
                                    if (validate_data(Data, "postalcode")){Data = clean_data(Data, "postalcode");}
                                    if (validate_data(Data, "zipcode")){Data = clean_data(Data, "zipcode");}
                                    break;
                                case "zipcode":
                                    Data = clean_data(Data, "number");
                                    if(Data.length == 9){Data = Data.substring(0,5) + "-" + Data.substring(5,9);}
                                    break;
                                case "postalcode":
                                    Data = clean_data(replaceAll(" ", "", Data.toUpperCase()), "alphanumeric");
                                    Data = Data.substring(0,3) + " " + Data.substring(3);
                                    break;
                                case "phone":
                                    var Data2 = clean_data(Data, "number");
                                    if(Data2.length == 10) {
                                        Data = Data2.replace(/(\d{3})(\d{3})(\d{4})/, "($1) $2-$3");
                                    } else {
                                        Data = "";//Data.replace(/[^0-9+]/g, "");
                                    }
                                    break;
                                case "sin":
                                    Data = clean_data(Data, "number");
                                    Data = Data.substring(0,3) + "-" + Data.substring(3,6)  + "-" + Data.substring(6,9) ;
                                    break;
                            }
                        }
                        return Data;
                    }

                    function showmap(){
                        initMap(parseFloat($("#rest_latitude").val()), parseFloat($("#rest_longitude").val()));
                        addmarker("Customer", parseFloat($("#cust_latitude").val()), parseFloat($("#cust_longitude").val()));
                    }

                    if(table == "orders"){
                        var countdown = window.setTimeout(function () {incrementtime();}, 1000);
                    } else if(debuglogdate > -1){
                        var countdown = checkfordebug(true);
                    }

                    inverticon();
                    function inverticon(){
                        if($("#invert").hasClass("fa-check-square-o")){
                            $("#invert").removeClass("fa-check-square-o").addClass("fa-square-o");
                        } else {
                            $("#invert").removeClass("fa-square-o").addClass("fa-check-square-o");
                        }
                        window.setTimeout(function () {inverticon();}, 1000);
                    }

                    function checkfordebug(isFirst){
                        skiploadingscreen = true;
                        $.post(currentURL, {
                            action: "checkdebug",
                            _token: token
                        }, function (result) {
                            if(result) {
                                result = JSON.parse(result);
                                result = result["time"];
                                if(result > debuglogdate) {
                                    //$("#debugmessage").html('<A HREF="' + currentURL + '">The debug log has changed. Click here to refresh</A>');
                                    location.reload();
                                }
                            }
                        });
                        var countdown = window.setTimeout(function () {checkfordebug(false)}, 10000);//10 seconds
                        if (isFirst) { return countdown;}
                    }

                    function getNow(){
                        return Math.floor(Date.now() / 1000);//reduce to seconds
                    }

                    function backtotime(timestamp){
                        var d = new Date(timestamp * 1000);
                        return d.getHours() + ":" + d.getMinutes();
                    }

                    function incrementtime(element) {
                        if(isUndefined(element)){
                            $(".countdown").each(function() {
                                incrementtime(this);
                            });
                            countdown = window.setTimeout(function () {
                                incrementtime()
                            }, 1000);
                        } else {
                            if (!$(".countdown").hasAttr("timestamp")){
                                var seconds = Number($(element).attr("seconds"));
                                var minutes = Number($(element).attr("minutes"));
                                var hours = Number($(element).attr("hours"));
                                var timestamp = getNow();
                                $(element).attr("startingtime", backtotime(timestamp));
                                timestamp += (hours * 3600) + (minutes * 60) + seconds;
                                $(element).attr("endingtime", backtotime(timestamp));
                                $(element).attr("timestamp", timestamp);
                            } else {
                                var timestamp = $(element).attr("timestamp");
                                var seconds = timestamp - getNow();
                                var minutes = Math.floor(seconds / 60);
                                var hours = Math.floor(minutes / 60);
                                seconds = seconds % 60;
                                minutes = minutes % 60;
                            }

                            var time = hours * 3600 + minutes + 60 + seconds;
                            var result = false;
                            if (time > 0) {
                                if (seconds == 0) {
                                    if (minutes == 0) {
                                        if (hours == 0) {
                                            time = 0;
                                        } else {
                                            hours -= 1;
                                        }
                                    } else {
                                        minutes -= 1;
                                    }
                                    seconds = 59;
                                } else {
                                    seconds -= 1;
                                }
                            }
                            if(time == 0){
                                $(element).removeClass("countdown").removeClass("badge-success").addClass("badge-danger").text("[EXPIRED]");
                            } else {
                                $(element).attr("hours", hours).attr("seconds", seconds).attr("minutes", minutes).text(toRemaining(hours, minutes, seconds));
                            }
                        }
                    }

                    var selecteditems = [];
                    function selecttableitem(t, ID){
                        var checked = $( t ).prop( "checked" );
                        if(checked){
                            selecteditems.push(ID);
                        } else {
                            var i = selecteditems.indexOf(ID);
                            if(i > -1){
                                removeindex(selecteditems, i);
                            }
                        }
                    }
                    //operation: 0=none, -1=invert, 1=all
                    function selecttableitems(operation){
                        $(".selitem").each(function() {
                            var checked = $( this ).prop( "checked" );
                            switch(operation){
                                case 1: //all
                                    if(!checked){$( this ).trigger("click");}
                                    break;
                                case -1: //invert
                                    $( this ).trigger("click");
                                    break;
                                case 0: //none
                                    if(checked){$( this ).trigger("click");}
                                    break;
                            }
                        });
                    }

                    function deletetableitems(){
                        if(selecteditems.length == 0){
                            return alert(makestring("{no_select}"), "Delete Selected");
                        }
                        confirm2("Are you sure you want to delete " + selecteditems.length + makeplural(selecteditems.length, " item") + "?", "Delete Selected", function(){
                            $.post(currentURL, {
                                action: "deleteitem",
                                _token: token,
                                ids: selecteditems
                            }, function (result) {
                                if(handleresult(result)) {
                                    for(var i = 0; i< selecteditems.length; i++){
                                        deletetableitem(selecteditems[i]);
                                    }
                                }
                            });
                        });
                    }

                    unikeys = {
                        cant_edit: "[table].[field] can not be edited",
                        user_auth: "User is now authorized",
                        not_valid: "'[data]' is not a valid [datatype]",
                        not_empty: "[data] can not be empty",
                        unhandled: "'[datatype]' is unhandled",
                        new_addrs: "'[number] [street]' was saved",
                        no_select: "There are no selected items to delete",
                        new_passw: "What would you like this user's new password to be?",
                        new_statu: "What would you like the [0] reason to be?"
                    };
                </SCRIPT>
            @else
                <SCRIPT>
                    redirectonlogout = true;
                </SCRIPT>
            @endif

            <div class="modal" id="ordermodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 id="myModalLabel">View Order</h2>
                            <button data-dismiss="modal" class="btn btn-sm ml-auto align-middle"><i class="fa fa-close"></i></button>
                        </div>

                        <div class="modal-body">
                            <SPAN ID="ordercontents"></SPAN><P>
                            <div class="clearfix"></div>
                            @if(!$showmap)
                                <?= view("popups_googlemaps"); ?>
                            @endif
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>

            <?php endfile("home_list"); ?>
        @endsection
        <?php
    }
?>