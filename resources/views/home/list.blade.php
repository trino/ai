<?php
    $namefield = "name";
    if(!isset($table)){$table = $_POST["table"];}
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
        default: die("This table is not whitelisted");
    }
    if(isset($_POST["action"])){
        $results = array();
        switch($_POST["action"]){
            case "getpage":
                if(!isset($fields)){$fields[] = "id";}
                $results["SQL"] = "SELECT " . implode(", ", $fields) . " FROM " . $_POST["table"] . " LIMIT " . $_POST["itemsperpage"] . " OFFSET " . ($_POST["itemsperpage"] * $_POST["page"]);
                $results["table"] = Query($results["SQL"], true);
                $results["count"] = first("SELECT COUNT(*) as count FROM " . $_POST["table"])["count"];
                break;

            case "deleteitem":
                deleterow($_POST["table"], "id=" . $_POST["id"]);
                break;

            default: die("'" . $_POST["action"] . "' is unhandled");
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
                                    <DIV ID="body"></DIV>
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
            </STYLE>
            <SCRIPT>
                var itemsperpage = 25;
                var table = "{{ $table }}";
                var currentURL = "<?= Request::url(); ?>";
                var token = "<?= csrf_token(); ?>";
                var namefield = "{{ $namefield }}";
                var items = 0;

                $(document).ready(function() {
                    getpage(0);
                });

                function getpage(index){
                    $.post(currentURL, {
                        action: "getpage",
                        _token: token,
                        table: table,
                        itemsperpage: itemsperpage,
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
                                        tempHTML += '<TD ID="' + table + "_" + ID + "_" + fields[v] + '">' + data.table[i][fields[v]] + '</TD>';
                                    }
                                    tempHTML += '<TD><A CLASS="btn btn-sm btn-danger" onclick="deleteitem(' + ID + ');">Delete</A></TD>';
                                    HTML += tempHTML + '</TR>';
                                    items++;
                                }
                            } else {
                                HTML = '<TR><TD COLSPAN="100">No results found</TD></TR>';
                            }
                            $("#data > TBODY").html(HTML);
                            generatepagelist(data.count, index);
                        } catch (e){
                            $("#body").html(e + " NON-JSON DETECTED: <BR>" + result);
                            return false;
                        }
                    });
                }

                function generatepagelist(itemcount, currentpage){
                    currentpage = Number(currentpage);
                    var pages = Math.ceil(Number(itemcount) / itemsperpage);
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
                            table: table,
                            id: ID
                        }, function (result) {
                            if(result) {
                                alert(result);
                            } else {
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
            </SCRIPT>
        @endsection
        <?php
    }
?>