<?php
    function get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
    function remove_brackets($text){
        return preg_replace('/\(([^()]*+|(?R))*\)\s*/', '', $text);
    }

    $namefield = "name";
    $where = "";
    $inlineedit = true;
    if(isset($_POST["query"])){
        $_GET = $_POST["query"];
    }
    $datafields=true;
    switch($table){
        case "all":case "debug"://system value
            $datafields=false;
            break;
        case "users":
            $faicon = "user";
            $fields = array("id", "name", "phone", "profiletype", "email");
            break;
        case "restaurants":
            $fields = array("id", "name", "phone", "email");
            break;
        case "orders":
            $fields=true;
            $faicon = "dollar";
            break;
        case "useraddresses":
            $inlineedit = false;
            $fields=true;//all fields
            if(isset($_GET["user_id"])){
                $where = "user_id = " . $_GET["user_id"];
            } else {
                $where = "user_id = " . read("id");
            }
            break;
        default: die("This table is not whitelisted");
    }
    if($datafields){
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
        $results = array("Status" => true);
        switch($_POST["action"]){
            case "getpage":
                if(!isset($fields)){$fields[] = "id";}
                if($where){$where = " WHERE " . $where;}
                $results["SQL"] = "SELECT " . implode(", ", $fields) . " FROM " . $table . $where . " LIMIT " . $_POST["itemsperpage"] . " OFFSET " . ($_POST["itemsperpage"] * $_POST["page"]);
                $results["table"] = Query($results["SQL"], true);
                $results["count"] = first("SELECT COUNT(*) as count FROM " . $table)["count"];
                break;

            case "deleteitem":
                deleterow($table, "id=" . $_POST["id"]);
                break;

            case "edititem"://single column
                insertdb($table, array("id" => $_POST["id"], $_POST["key"] => $_POST["value"]));
                break;

            case "saveitem"://all columns
                insertdb($table, $_POST["value"]);
                break;

            case "getreceipt":
                die(view("popups.receipt", $_POST));
                break;

            default:
                $results["Status"] = false;
                $results["Reason"] = "'" . $_POST["action"] . "' is unhandled \r\n" . print_r($_POST, true);
        }
        echo json_encode($results);//must return something
        die();
    } else {
        if(!isset($faicon)){$faicon = "home";}
        ?>
        @extends('layouts.app')
        @section('content')
            <div class="row m-t-1">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-block bg-danger" style="padding-top:.75rem !important;padding-bottom:.75rem !important;">
                            <h4 class="pull-left">
                                <i class="fa fa-{{ $faicon }}" aria-hidden="true"></i> {{ ucfirst($table) }} list
                            </h4>
                            @if($table != "all")
                            <H4 CLASS="pull-right"><A HREF="{{ webroot("public/list/all") }}" TITLE="Back"><i class="fa fa-arrow-left"></i></A></H4>
                            @endif
                        </div>
                        <div class="card-block">
                            <div class="row">
                                <div class="col-md-12">
                                    @if(read("profiletype") != 1)
                                        You are not authorized to view this page
                                    @elseif($table == "all")
                                        <?php
                                            foreach(array("users", "restaurants", "useraddresses", "orders") as $table){
                                                echo '<A HREF="' . webroot("public/list/" . $table) . '">' . ucfirst($table) . ' list</A><BR>';
                                            }
                                        ?>
                                        <HR>
                                        <A HREF="<?= webroot("public/editmenu"); ?>">Edit Menu</A><BR>
                                        <A HREF="<?= webroot("public/list/debug"); ?>">Debug log</A>
                                    @elseif($table == "debug")
                                        <PRE><?php
                                            if (file_exists("royslog.txt")){
                                                echo file_get_contents("royslog.txt");
                                            }
                                        ?></PRE>
                                    @else
                                        <TABLE WIDTH="100%" BORDER="1" ID="data">
                                            <THEAD>
                                                <TR>
                                                    <TH>ID</TH>
                                                    <?php
                                                        if(isset($fields)){
                                                            foreach($fields as $field){
                                                                if($field != "id"){
                                                                    echo '<TH>' . ucfirst(str_replace("_", " ", $field)) . '</TH>';
                                                                }
                                                            }
                                                        }
                                                    ?>
                                                    <TH>Actions</TH>
                                                </TR>
                                            </THEAD>
                                            <TBODY></TBODY>
                                            <TFOOT><TR><TD COLSPAN="{{ count($fields)+1 }}" ALIGN="right" ID="pages"></TD></TR></TFOOT>
                                        </TABLE>
                                        <DIV ID="body">
                                            <?php
                                                switch($table){
                                                    case "useraddresses":
                                                        echo '<A ONCLICK="saveaddress(0);" CLASS="btn btn-sm btn-primary">New</A> ';
                                                        echo '<A ONCLICK="saveaddress(selecteditem);" CLASS="btn btn-sm btn-secondary" id="saveaddress" DISABLED>Save</A>';
                                                        echo view("popups.address", $_GET);
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
                <STYLE>
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
                <SCRIPT>
                    var selecteditem = 0;
                    var itemsperpage = 25;
                    var currentpage = 0;
                    var lastpage = 0;
                    var table = "{{ $table }}";
                    var currentURL = "<?= Request::url(); ?>";
                    var baseURL = currentURL.replace(table, "");
                    var token = "<?= csrf_token(); ?>";
                    var namefield = "{{ $namefield }}";
                    var items = 0;
                    var inlineedit = "{{ $inlineedit }}".length > 0;
                    redirectonlogout = true;
                    var datafields = <?= json_encode($datafields); ?>;
                    var intranges = {
                        tinyint: {min: -128, max: 127}, tinyintunsigned: {min: 0, max: 255},
                        smallint: {min: -32768, max: 32767}, smallintunsigned: {min: 0, max: 65535},
                        mediumint: {min: -8388608, max: 2147483647}, mediumintunsigned: {min: 0, max: 16777215},
                        int: {min: -2147483648, max: 127}, intunsigned: {min: 0, max: 4294967295},
                        bigint: {min: -9223372036854775808, max: 9223372036854775807}, bigintunsigned: {min: 0, max: 18446744073709551615}
                    };

                    $(document).ready(function() {
                        getpage(0);
                    });

                    function getpage(index){
                        if(index<0){index = currentpage;}
                        $.post(currentURL, {
                            action: "getpage",
                            _token: token,
                            itemsperpage: itemsperpage,
                            query: <?= json_encode($_GET); ?>,
                            page: index
                        }, function (result) {
                            try {
                                var data = JSON.parse(result);
                                var HTML = "";
                                if(data.table.length>0) {
                                    var fields = Object.keys(data.table[0]);
                                    items = 0;
                                    for (var i = 0; i < data.table.length; i++) {
                                        var ID = data.table[i]["id"];
                                        var tempHTML = '<TR ID="' + table + "_" + ID + '">';
                                        for (var v = 0; v < fields.length; v++) {
                                            tempHTML += '<TD ID="' + table + "_" + ID + "_" + fields[v] + '" class="field" field="' + fields[v] + '" index="' + ID + '">' + data.table[i][fields[v]] + '</TD>';
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
                                                break;
                                        }
                                        HTML += tempHTML + '<A CLASS="btn btn-sm btn-danger" onclick="deleteitem(' + ID + ');">Delete</A></TD></TR>';
                                        items++;
                                    }
                                } else {
                                    HTML = '<TR><TD COLSPAN="100">No results found</TD></TR>';
                                }
                                currentpage=index;
                                $("#data > TBODY").html(HTML);
                                generatepagelist(data.count, index);

                                $(".field").dblclick(function() {
                                    var field = $(this).attr("field");
                                    var columnindex = findwhere(datafields, "Field", field);
                                    var column = datafields[columnindex];
                                    if(column["Key"] != "PRI"){//primary key can't be edited
                                        var ID = $(this).attr("index");
                                        selecteditem = ID;
                                        var HTML = $(this).html();
                                        var isHTML = containsHTML(HTML);
                                        var isText = false;
                                        var colname = table + "." + field;
                                        if(!isHTML && inlineedit){
                                            isText=true;
                                            var title="";
                                            switch(column["Type"]){
                                                //timestamp (date)
                                                case "tinyint": case "smallint": case "mediumint": case "bigint": case "int":
                                                case "tinyintunsigned": case "smallintunsigned": case "mediumintunsigned": case "bigintunsigned": case "intunsigned":
                                                    var min = intranges[column["Type"]]["min"];
                                                    var max = intranges[column["Type"]]["max"];
                                                    switch(colname){
                                                        case "users.profiletype":
                                                            title="0=user, 1=admin";
                                                            min=0;
                                                            max=1;
                                                            break;
                                                    }
                                                    HTML = '<INPUT TYPE="NUMBER" ID="' + ID + "_" + field + '" VALUE="' + HTML + '" CLASS="textfield" TITLE="' + title + '" MIN="';
                                                    HTML += min + '" MAX="' + max + '" COLNAME="' + colname + '">';
                                                    break;
                                                default://simple text
                                                    HTML = '<INPUT TYPE="TEXT" ID="' + ID + "_" + field + '" VALUE="' + HTML + '" CLASS="textfield" COLNAME="' + colname;
                                                    HTML += '" maxlength="' + column["Len"] + '" TITLE="' + title + '>';
                                            }
                                            $(this).html(HTML);
                                            if(isText) {
                                                $("#" + ID + "_" + field).focus().select().keypress(function (ev) {
                                                    var keycode = (ev.keyCode ? ev.keyCode : ev.which);
                                                    if (keycode == '13') {
                                                        edititem(ID, field, $(this).val());
                                                    }
                                                }).blur(function(){
                                                    edititem(ID, field, $(this).val());
                                                });
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

                    function containsHTML(text){
                        return text.indexOf("<") > -1 && text.indexOf(">") > -1;
                    }

                    function generatepagelist(itemcount, currentpage){
                        currentpage = Number(currentpage);
                        var pages = Math.ceil(Number(itemcount) / itemsperpage);
                        lastpage = pages-1;
                        var HTML = '<TABLE BORDER="1"><TR>';
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

                    function edititem(ID, field, data){
                        var colname = $("#" + ID + "_" + field).attr("COLNAME").toLowerCase();
                        if(data) {
                            var newdata="";
                            var datatype="";
                            switch (colname) {
                                case "users.phone":
                                    newdata = clean_data(data, "phone");
                                    datatype="phone number";
                                    break;
                                case "users.profiletype":
                                    if(data == "0" || data == "1"){newdata = data;}
                                    datatype="profile type. 0=user, 1=admin";
                                    break;
                                case "users.email":
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







                    function validate_data(Data, DataType){
                        if(Data) {
                            //alert("Testing: " + Data + " for " + DataType);
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
                                        Data = Data.replace(/[^0-9+]/g, "");
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