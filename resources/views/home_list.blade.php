<?php
    $RestaurantID= "";
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

    //sets permissions, SQL, fields for each whitelisted table
    $namefield = "name";//which field acts as the name for the confirm delete popup
    $where = "";
    $inlineedit = true;//allow inline editing of a row
    if(isset($_POST["query"])){$_GET = $_POST["query"];}
    $adminsonly=true;//sets if you must be an admin to access the table
    $datafields=true;
    $SQL=false;
    switch($table){
        case "all":case "debug"://system value
            $datafields=false;
            break;
        case "users":
            $faicon = "user";
            $fields = array("id", "name", "phone", "profiletype", "email");
            break;
        case "restaurants":
            $fields = array("id", "name", "phone", "email", "address_id", "number", "street", "postalcode", "city", "province", "latitude", "longitude", "user_phone");
            $SQL='SELECT restaurants.id, restaurants.name, restaurants.phone, restaurants.email, restaurants.address_id, useraddresses.number, useraddresses.street, useraddresses.postalcode, useraddresses.city, useraddresses.province, useraddresses.latitude, useraddresses.longitude, useraddresses.phone as user_phone FROM useraddresses AS useraddresses RIGHT JOIN restaurants ON restaurants.address_id = useraddresses.id';
            break;
        case "orders":
            $fields=array("id", "user_id", "placed_at", "restaurant_id", "number", "unit", "street", "postalcode", "city", "province", "longitude", "latitude");
            $namefield="placed_at";
            $faicon = "dollar";
            if(isset($_GET["user_id"])){
                $where = "user_id = " . $_GET["user_id"];
                if($_GET["user_id"] == read("id")){$adminsonly=false;}
            }
            if(isset($_GET["restaurant"])){
                $where = "restaurant_id = " . $_GET["restaurant"];
            }
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
            if(isset($_GET["user_id"]) && read("profiletype") == 1){
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
        switch($_POST["action"]){
            case "getpage"://get a page of data via AJAX
                if($_POST["makenew"] == "true"){
                    Query("INSERT INTO " . $table . " () VALUES();");
                    debugprint("Inserted into " . $table );
                }
                if(!isset($fields)){$fields[] = "id";}
                if($where){$where = " WHERE " . $where;}
                if(!$SQL){$SQL= "SELECT " . implode(", ", $fields) . " FROM " . $table;}
                $results["SQL"] =   $SQL . $where . " LIMIT " . $_POST["itemsperpage"] . " OFFSET " . ($_POST["itemsperpage"] * $_POST["page"]);
                $results["table"] = Query($results["SQL"], true);
                $results["count"] = first("SELECT COUNT(*) as count FROM " . $table)["count"];
                break;

            case "deleteitem"://delete a row
                switch($table){
                    case "orders":
                        deletefile(resource_path("orders") . "/" . $_POST["id"] . ".json");//deletes the order file
                        break;
                    case "useraddresses":
                        Query("UPDATE restaurants SET address_id = 0 WHERE address_id = " . $_POST["id"]);//unbinds any restaurant from this address
                        break;
                }
                deleterow($table, "id=" . $_POST["id"]);
                break;

            case "deletetable"://delete all rows
                Query("TRUNCATE " . $table);
                break;

            case "edititem"://edit a single column in a row
                insertdb($table, array("id" => $_POST["id"], $_POST["key"] => $_POST["value"]));
                break;

            case "saveitem"://edit all columns in a row
                insertdb($table, $_POST["value"]);
                break;

            case "getreceipt"://get an order receipt
                die(view("popups_receipt", $_POST)->render());
                break;

            case "deletedebug"://delete the debug file
                deletefile("royslog.txt");
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
            </STYLE>
            <div class="row m-t-1">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-block bg-danger" style="padding-top:.75rem !important;padding-bottom:.75rem !important;">
                            <h4 class="pull-left">
                                <A HREF="<?= webroot("public/list/all"); ?>"><i class="fa fa-{{ $faicon }}" aria-hidden="true"></i></A> {{ ucfirst($table) }} list
                            </h4>
                            <H4 CLASS="pull-right spacing">
                                @if($table != "all" && read("profiletype") == 1)
                                    @if($table == "debug")
                                        <A onclick="deletedebug();" TITLE="Delete the debug log" class="hyperlink" id="deletedebug"><i class="fa fa-trash-o"></i></A>
                                    @else
                                        <A onclick="deletetable();" TITLE="Delete the table data" class="hyperlink" id="deletetable"><i class="fa fa-trash-o"></i></A>
                                    @endif
                                    <A HREF="{{ webroot("public/list/all") }}" TITLE="Back"><i class="fa fa-arrow-left"></i></A>
                                @endif
                            </H4>
                        </div>
                        <div class="card-block">
                            <div class="row">
                                <div class="col-md-12">
                                    @if(read("profiletype") != 1 && $adminsonly)
                                        You are not authorized to view this page
                                        <a class="loggedout dropdown-item hyperlink" data-toggle="modal" data-target="#loginmodal"> <i class="fa fa-home"></i> Log In</a>
                                    @elseif($table == "all")
                                        <?php
                                            //show all administratable tables
                                            foreach(array("users", "restaurants", "useraddresses", "orders") as $table){
                                                echo '<A HREF="' . webroot("public/list/" . $table) . '">' . ucfirst($table) . ' list</A><BR>';
                                            }
                                        ?>
                                        <HR>
                                        <A HREF="<?= webroot("public/editmenu"); ?>">Edit Menu</A><BR>
                                        <A HREF="<?= webroot("public/list/debug"); ?>">Debug log</A>
                                    @elseif($table == "debug")
                                        <PRE id="debuglogcontents"><?php
                                            //show debug file
                                            $Contents = "";
                                            if (file_exists("royslog.txt")){
                                                $Contents = file_get_contents("royslog.txt");
                                            }
                                            if(!$Contents) {$Contents = "The debug log is empty";}
                                            echo $Contents;
                                        ?></PRE>
                                    @else
                                        <TABLE WIDTH="100%" class="table table-bordered" ID="data">
                                            <THEAD>
                                                <TR>
                                                    <TH CLASS="th-left">ID</TH>
                                                    <?php
                                                        if(isset($fields)){
                                                            $last = lastkey($fields);
                                                            foreach($fields as $field){
                                                                if($field != "id"){
                                                                    echo '<TH CLASS="th-left">' . formatfield($field) . '</TH>';
                                                                }
                                                            }
                                                        }
                                                    ?>
                                                    <TH>Actions</TH>
                                                </TR>
                                            </THEAD>
                                            <TBODY></TBODY>
                                            <TFOOT><TR><TD COLSPAN="{{ count($fields)+1 }}" ID="pages"></TD></TR></TFOOT>
                                        </TABLE>
                                        <DIV ID="body">
                                            <?php
                                                switch($table){
                                                    case "useraddresses":
                                                        echo '<A ONCLICK="saveaddress(0);" CLASS="btn btn-sm btn-primary">New</A> ';
                                                        echo '<A ONCLICK="saveaddress(selecteditem);" CLASS="btn btn-sm btn-secondary" id="saveaddress" DISABLED>Save</A>';
                                                        echo view("popups_address", $_GET)->render();
                                                        break;
                                                    case "restaurants":
                                                        echo '<DIV ID="addressdropdown" STYLE="display: none;" class="addressdropdown"></DIV>';
                                                        break;
                                                    case "orders":
                                                        if(isset($_GET["restaurant"]) && $_GET["restaurant"]){
                                                            $RestaurantID = $_GET["restaurant"];
                                                            $Restaurant = first("SELECT * FROM restaurants WHERE id=" . $_GET["restaurant"]);
                                                            $Address = first("SELECT * FROM useraddresses WHERE id=" . $Restaurant["address_id"]);
                                                            echo view("popups_googlemaps", $Address);
                                                        }
                                                        break;
                                                }
                                            ?>
                                        </DIV>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(read("profiletype") == 1)
                <SCRIPT>
                    var selecteditem = 0;
                    var itemsperpage = 25;
                    var currentpage = 0;
                    var lastpage = 0;
                    var table = "{{ $table }}";
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

                    $(document).ready(function() {
                        getpage(0);
                    });

                    //gets a page of data from the server, convert it to HTML
                    function getpage(index, makenew){
                        if(index==-1){index = lastpage;}
                        if(isUndefined(makenew)){makenew = false;}
                        if(index<0){index = currentpage;}
                        $.post(currentURL, {
                            action: "getpage",
                            _token: token,
                            itemsperpage: itemsperpage,
                            query: <?= json_encode($_GET); ?>,
                            page: index,
                            makenew: makenew
                        }, function (result) {
                            try {
                                var data = JSON.parse(result);
                                var HTML = "";
                                if(data.table.length>0) {
                                    var fields = Object.keys(data.table[0]);
                                    items = 0;
                                    for (var i = 0; i < data.table.length; i++) {
                                        var Address = "[number] [street]<BR>[city] [province]<BR>[postalcode]";
                                        var ID = data.table[i]["id"];
                                        var tempHTML = '<TR ID="' + table + "_" + ID + '">';
                                        for (var v = 0; v < fields.length; v++) {
                                            tempHTML += '<TD ID="' + table + "_" + ID + "_" + fields[v] + '" class="field" field="' + fields[v] + '" index="' + ID + '">' + data.table[i][fields[v]] + '</TD>';
                                            Address = Address.replace("[" + fields[v] + "]", data.table[i][fields[v]]);
                                        }
                                        tempHTML += '<TD>';
                                        switch(table){
                                            case "users":
                                                tempHTML += '<A CLASS="btn btn-sm btn-primary" href="' + baseURL + 'useraddresses?user_id=' + ID + '">Addresses</A> ';
                                                tempHTML += '<A CLASS="btn btn-sm btn-secondary" href="{{ webroot("public/user/info/") }}' + ID + '">Edit</A> ';
                                                break;
                                            case "useraddresses":
                                                tempHTML += '<A CLASS="btn btn-sm btn-primary" onclick="editaddress(' + ID + ');">Edit</A> ';
                                                break;
                                            case "orders":
                                                tempHTML += '<A CLASS="btn btn-sm btn-primary" onclick="vieworder(' + ID + ');">View</A> ';
                                                if(restaurantID){
                                                    var Name = ID;
                                                    if(data.table[i]["unit"].length > 0){
                                                        Name += " (" + data.table[i]["unit"] + ")";
                                                    }
                                                    addmarker2(Name, data.table[i]["latitude"], data.table[i]["longitude"], ", ", "Order ID: ", "<BR>" + Address);
                                                }
                                                break;
                                            case "restaurants":
                                                tempHTML += '<A CLASS="btn btn-sm btn-primary" HREF="{{ webroot("public/list/orders?restaurant=") }}' + ID + '">View</A> ';
                                                break;
                                        }
                                        HTML += tempHTML + '<A CLASS="btn btn-sm btn-danger" onclick="deleteitem(' + ID + ');">Delete</A></TD></TR>';
                                        items++;
                                    }
                                    addmarker2();
                                } else {
                                    HTML = '<TR><TD COLSPAN="100">No results found</TD></TR>';
                                }
                                currentpage=index;
                                $("#data > TBODY").html(HTML);
                                generatepagelist(data.count, index);

                                $(".field").dblclick(function() {//set field double click handler
                                    var field = $(this).attr("field");
                                    var columnindex = findwhere(datafields, "Field", field);
                                    var column = datafields[columnindex];
                                    var ID = $(this).attr("index");
                                    if (isUndefined(column)) {
                                        switch(table){
                                            case "restaurants":
                                                if(confirm("The restaurant address can not be edited directly from here. Would you like to go to the address editor?")){
                                                    ID = $("#restaurants_" + ID + "_address_id").text();
                                                    window.location = webroot + "list/useraddresses?key=id&value=" + ID;
                                                }
                                                break;
                                            default: alert(table + "." + field + " can not be edited");
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
                                            switch(column["Type"]){
                                                //timestamp (date)
                                                case "tinyint": case "smallint": case "mediumint": case "bigint":case "int":case "double":
                                                case "tinyintunsigned": case "smallintunsigned": case "mediumintunsigned": case "bigintunsigned": case "intunsigned":
                                                    var min = intranges[column["Type"]]["min"];
                                                    var max = intranges[column["Type"]]["max"];
                                                    switch(colname){
                                                        case "users.profiletype":
                                                            isSelect=true;
                                                            HTML = makeselect(ID + "_" + field, "selectfield form-control", colname, HTML, [{value: 0, text: "user"}, {value: 1, text: "admin"}]);
                                                            break;
                                                        case "restaurants.address_id":
                                                            isSelect=true;
                                                            console.log(HTML + " was selected");
                                                            HTML = $("#addressdropdown").html().replace('class="form-control" id="saveaddresses"', 'CLASS="selectfield form-control" ID="' + ID + "_" + field + '" COLNAME="' + colname + '"').replace('value="' + HTML + '"', 'value="' + HTML + '" SELECTED');
                                                            console.log(HTML + " was edited");
                                                            break;
                                                        default:
                                                            HTML = '<INPUT TYPE="NUMBER" ID="' + ID + "_" + field + '" VALUE="' + HTML + '" CLASS="textfield" TITLE="' + title + '" MIN="';
                                                            HTML += min + '" MAX="' + max + '" COLNAME="' + colname + '">';
                                                    }
                                                    break;
                                                default://simple text
                                                    HTML = '<INPUT TYPE="TEXT" ID="' + ID + "_" + field + '" VALUE="' + HTML + '" CLASS="textfield" COLNAME="' + colname;
                                                    HTML += '" maxlength="' + column["Len"] + '" TITLE="' + title + '">';
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
                            } catch (e){
                                $("#body").html(e + " NON-JSON DETECTED: <BR>" + result);
                                return false;
                            }
                        });
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

                    //generates a list of page links
                    function generatepagelist(itemcount, currentpage){
                        currentpage = Number(currentpage);
                        var pages = Math.ceil(Number(itemcount) / itemsperpage);
                        lastpage = pages-1;
                        var HTML = '<BUTTON CLASS="btn btn-sm btn-primary" onclick="newitem();">New</BUTTON><TABLE BORDER="1" CLASS="pull-right"><TR>';
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
                        if(confirm("Are you sure you want to delete the entire " + table + " table?")){
                            if(confirm("Are you really REALLY sure?")){
                                $.post(currentURL, {
                                    action: "deletetable",
                                    _token: token,
                                }, function (result) {
                                    if (handleresult(result)) {
                                        location.reload();
                                    }
                                });
                            }
                        }
                    }

                    //delete a single item in a table
                    function deleteitem(ID){
                        var name = $("#" + table + "_" + ID + "_" + namefield).text();
                        if(confirm("Are you sure you want to delete item ID: " + ID + " (" + name + ") ?")){
                            $.post(currentURL, {
                                action: "deleteitem",
                                _token: token,
                                id: ID
                            }, function (result) {
                                if(handleresult(result)) {
                                    selecteditem=0;
                                    $("#saveaddress").attr("disabled", true);
                                    $("#" + table + "_" + ID).fadeOut(1000, function(){
                                        $("#" + table + "_" + ID).remove();
                                    });
                                    items--;
                                    if(items == 0){
                                        location.reload();
                                    }
                                }
                            });
                        }
                    }

                    //add a new item to the table, load the last page
                    function newitem(){
                        getpage(-1, true);
                    }

                    //delete the debug file
                    function deletedebug(){
                        if(confirm("Are you sure you want to delete the debug log?")){
                            $.post(currentURL, {
                                action: "deletedebug",
                                _token: token
                            }, function (result) {
                                if(handleresult(result)) {
                                    $("#deletedebug").hide();
                                    $("#debuglogcontents").html("The debug log is empty");
                                }
                            });
                        }
                    }

                    //edit a single column in a row, verifying the data
                    function edititem(ID, field, data){
                        var colname = $("#" + ID + "_" + field).attr("COLNAME").toLowerCase();
                        if(data) {
                            var newdata="";
                            var datatype="";
                            switch (colname) {
                                case "users.phone":case "restaurants.phone":
                                    newdata = clean_data(data, "phone");
                                    datatype="phone number";
                                    break;
                                case "users.profiletype":
                                    if(data == "0" || data == "1"){newdata = data;}
                                    datatype="profile type. 0=user, 1=admin";
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
                                    alert("'" + data + "' is not a valid " + datatype);
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
                                $("#" + table + "_" + ID + "_" + field).html(data);
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
                                    if(formdata[key].trim().length == 0){
                                        alert($(".data_" + key).text().replace(":", "") + " can not be empty");
                                        return false;
                                    }
                            }
                        }

                        if(ID){formdata.id = ID;}
                        $.post(currentURL, {
                            action: "saveitem",
                            _token: token,
                            value: formdata
                        }, function (result) {
                            if(handleresult(result)) {
                                getpage(lastpage);
                            }
                        });
                    }

                    //view an order receipt
                    function vieworder(ID){
                        $.post(currentURL, {
                            action: "getreceipt",
                            _token: token,
                            orderid: ID
                        }, function (result) {
                            if(result) {
                                $("#body").html(result);
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
                                    alert("'" + DataType + "' is unhandled");
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
                </SCRIPT>
            @else
                <SCRIPT>
                    redirectonlogout = true;
                </SCRIPT>
            @endif
        @endsection
        <?php
    }
?>