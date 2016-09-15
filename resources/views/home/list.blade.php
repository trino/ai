<?php
    $namefield = "name";
    $where = "";
    $inlineedit = true;
    if(isset($_POST["query"])){
        $_GET = $_POST["query"];
    }
    switch($table){
        case "users":
            $faicon = "user";
            $fields = array("id", "name", "phone", "email");
            break;
        case "restaurants":
            $fields = array("id", "name", "phone", "email");
            break;
        case "orders":
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
    if(isset($fields) && !is_array($fields)){
        $fields = collapsearray(describe($table), "Field");
    }
    if(isset($_POST["action"])){
        $results = array();
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

            default: die("'" . $_POST["action"] . "' is unhandled \r\n" . print_r($_POST, true));
        }
        if($results){echo json_encode($results);}
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
                        </div>
                        <div class="card-block">
                            <div class="row">
                                <div class="col-md-12">
                                    <TABLE WIDTH="100%" BORDER="1" ID="data">
                                        <THEAD>
                                            <TR>
                                                <TH>ID</TH>
                                                <?php
                                                    if(isset($fields)){
                                                        foreach($fields as $field){
                                                            if($field != "id"){
                                                                echo '<TH>' . ucfirst($field) . '</TH>';
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                                if(field != "id"){//primary key can't be edited
                                    var ID = $(this).attr("index");
                                    selecteditem = ID;
                                    var HTML = $(this).html();
                                    var isHTML = containsHTML(HTML);
                                    var isText = false;
                                    if(!isHTML && inlineedit){
                                        switch(table + "." + field){

                                            default://simple text
                                                isText=true;
                                                HTML = '<INPUT TYPE="TEXT" ID="' + ID + "_" + field + '" VALUE="' + HTML + '" CLASS="textfield">';
                                        }
                                        $(this).html(HTML);
                                        if(isText) {
                                            $("#" + ID + "_" + field).focus().select().keypress(function (ev) {
                                                var keycode = (ev.keyCode ? ev.keyCode : ev.which);
                                                if (keycode == '13') {
                                                    edititem(ID, field, $(this).val());
                                                }
                                            })
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
                            if(result) {
                                alert(result);
                            } else {
                                selecteditem=0;
                                $("#saveaddress").addAttr("disabled");

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
                    $.post(currentURL, {
                        action: "edititem",
                        _token: token,
                        id: ID,
                        key: field,
                        value: data
                    }, function (result) {
                        if(result) {
                            alert(result);
                        } else {
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
                    if(ID){formdata.id = ID;}
                    $.post(currentURL, {
                        action: "saveitem",
                        _token: token,
                        value: formdata
                    }, function (result) {
                        if(result) {
                            alert(result);
                        } else {
                            getpage(lastpage);
                        }
                    });
                }
            </SCRIPT>
        @endsection
        <?php
    }
?>