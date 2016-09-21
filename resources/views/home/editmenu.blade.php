<?php
    if(read("profiletype") == 1 && count($_POST)){
        if(isset($_POST["changes"])){
            foreach($_POST["changes"] as $tablename => $table){
                foreach($table as $row){
                    if(!is_numeric( $row["id"] )){unset($row["id"]);}//is a new entry
                    insertdb($tablename, $row);//save data
                }
            }
        }
        if(isset($_POST["deleted"])){
            foreach($_POST["deleted"] as $row){
                if(is_numeric($row["keyid"])){
                    deleterow($row["table"], "id=" . $row["keyid"]);//deleted item
                }
            }
        }
        die();
    }
?>
@extends('layouts.app')
@section('content')
    <STYLE>
        hr{
            margin-top: 2px;
            margin-bottom: 2px;
        }

        input[type=checkbox]{
            height: 26px;
        }

        .currenttable{
            font-weight: bold;
        }
    </STYLE>
    <div class="row m-t-1">
        <div class="col-md-12">
            <div class="card">
                <div class="card-block bg-danger" style="padding-top:.75rem !important;padding-bottom:.75rem !important;">
                    <h4 class="pull-left">
                        <i class="fa fa-home" aria-hidden="true"></i> Edit menu
                    </h4>
                </div>
                <div class="card-block">
                    <div class="row">
                        <div class="col-md-12">
                            @if(read("profiletype") != 1)
                                You are not authorized to view this page
                            @else
                                <div class="col-md-2">
                                    <UL ID="catlist">
                                        <LI class="main hyperlink" onclick="main_click(this);" table="additional_toppings">Size costs</LI>
                                        <LI class="main hyperlink" onclick="main_click(this);" table="toppings">Pizza Toppings</LI>
                                        <LI class="main hyperlink" onclick="main_click(this);" table="wings_sauce">Wing Sauces</LI>
                                        <HR>
                                        Menu:
                                        <LI class="category hyperlink" onclick="newcategory();">[New Category]</LI>
                                        <?php
                                            $categories = collapsearray(Query('SELECT category FROM `menu` GROUP BY category_id', true), "category");
                                            foreach($categories as $category){
                                                echo '<LI class="category hyperlink" onclick="cat_click(this);">' . $category . '</LI>';
                                            }
                                        ?>
                                    </UL>
                                    <button ID="savechanges" class="btn btn-block btn-primary changes" style="display:none;" onclick="savechanges();">Save Changes</button>
                                    <button ID="discardchanges" class="btn btn-block btn-secondary changes" style="display:none;" onclick="discard(false);">Discard Changes</button>
                                </DIV>
                                <?php
                                    $addon_tables = array("toppings", "wings_sauce");
                                    $tables = array_merge($addon_tables, array("menu", "additional_toppings"));
                                    foreach($tables as $table){
                                        echo '<DIV ID="table_' . $table . '" CLASS="col-md-10 table_main" STYLE="display:none;">Test ' . $table . '</DIV>' . "\r\n";
                                        echo '<datalist ID="categories_' . $table . '"></datalist>' . "\r\n";
                                    }
                                ?>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(read("profiletype") == 1)
        <SCRIPT>
            var changes = {};
            var deleted = new Array;
            var haschanges = false;
            var newsitems = 0;

            function deleteitem(table, keyid){
                var name = $("input[table=" + table + "][keyid=" + keyid + "]").first().val();
                var type = "";
                switch(table){
                    case "additional_toppings":     type = "size"; break;
                    case "toppings":                type = "topping"; break;
                    case "wings_sauce":             type = "sauce"; break;
                    case "menu":                    type = "menu item"; break;
                }
                if(confirm("Are you sure you want to delete the '" + name + "' " + type + "?")) {
                    var index = findwhere(changes[table], "id", keyid);
                    if (index > -1) {removeindex(changes[table], index);}//remove from changes
                    if(!isNaN(keyid)) {
                        haschanged();
                        deleted.push({table: table, keyid: keyid});//send

                        index = findwhere(alldata[table], "id", keyid);
                        if (index > -1) {removeindex(alldata[table], index);}//remove from all data
                    }
                    $("." + table + "_" + keyid).remove();
                }
            }

            function savechanges(){
                $.post(currentURL, {
                    _token: token,
                    changes: changes,
                    deleted: deleted
                }, function (result) {
                    haschanges = false;
                    if(result){
                        alert(result);
                    } else {
                        location.reload(true);
                    }
                });
            }

            function discard(force){
                if(!force) {force = confirm("Are you sure you want to discard all changes you've made?");}
                if(force) {
                    var tables = Object.keys(alldata);
                    for (var tableindex = 0; tableindex < tables.length; tableindex++) {
                        changes[tables[tableindex]] = new Array;
                    }
                    deleted = new Array;
                    haschanges = false;
                    $(".changes").hide();
                    newsitems = 0;
                }
            }

            var alldata = {};
            var categories = {};
            var current_table = "";
            var current_category = "";
            <?php
                $CRLF = ";\r\n\t\t";
                echo 'categories["menu"] = ' . json_encode($categories) . $CRLF;
                foreach($addon_tables as $table){
                    echo 'categories["' . $table . '"] = ' . json_encode(collapsearray(Query('SELECT type FROM ' . $table . ' GROUP BY type', true), "type")) . $CRLF;
                    echo 'makecategories("' . $table . '")' . $CRLF;
                }
                foreach($tables as $table){
                    echo 'alldata["' . $table . '"] = ' . json_encode(Query("SELECT * FROM " . $table, true)) . $CRLF;
                }
            ?>

            function cat_click(element){
                $(".currenttable").removeClass("currenttable");
                $(element).addClass("currenttable");

                current_category = $(element).text();
                current_table="menu";
                $(".table_main").hide();
                var HTML = getmenuitems(current_category);
                $("#table_menu").html(HTML).show();
                $('input.currency').currencyInput();
            }
            function main_click(element){
                $(".currenttable").removeClass("currenttable");
                $(element).addClass("currenttable");

                current_table = $(element).attr("table");
                $(".table_main").hide();
                $("#table_" + current_table).show();
            }

            $( document ).ready(function() {
                processAll();
                $(".main").first().trigger("click");
            });

            function processAll(){
                var tables = Object.keys(alldata);
                for(var tableindex = 0; tableindex < tables.length; tableindex++){
                    var table_name = tables[tableindex];
                    var table_data = alldata[table_name];
                    var HTML = '<button class="btn btn-block btn-warning" onclick="newitem(' + "'" + table_name + "'" + ');">New</button><BR>';
                    for(var dataindex = 0; dataindex < table_data.length; dataindex++){
                        HTML += makeHTML(table_data[dataindex], table_name);
                    }
                    changes[table_name] = new Array;
                    $("#table_" + table_name).html(HTML);
                }
            }

            function autoupdate(element){
                var table  = $(element).attr("table");
                var keyid  = $(element).attr("keyid");
                var column = $(element).attr("column");
                var value  = $(element).val();
                var name   = $(element).attr("name");
                var type   = $(element).attr("type").toLowerCase();
                if(type == "checkbox"){
                    value = $(element).prop("checked");
                } else if (type == "radio"){
                    value = "you need to finish the code for this!";
                }
                console.log(type + ": " + name + " = " + value);

                haschanged();

                var index = findwhere(changes[table], "id", keyid);
                if(index == -1){
                    var data = {id: keyid};
                    data[column] = value;
                    changes[table].push(data)
                } else {
                    changes[table][index][column] = value;
                }
            }

            function haschanged(){
                if(!haschanges) {
                    haschanges = true;
                    $(".changes").show();
                }
            }

            function findwhere(data, key, value){
                for(var i=0; i<data.length; i++){
                    if(data[i][key].isEqual(value)){
                        return i;
                    }
                }
                return -1
            }

            function getmenuitems(category){
                var HTML = '<button class="btn btn-block btn-warning" onclick="newitem(' + "'menu', '" + category + "'" + ');">New</button><BR>';
                for(var index = 0; index< alldata["menu"].length; index++){
                    var data = alldata["menu"][index];
                    if(data["category"].isEqual(category)){
                        HTML += makeHTML(data, "menu");
                    }
                }
                return HTML;
            }

            function newcategory(){
                var catname = prompt("What name would you like the category to be?\r\nIt will only be saved when you add an item to the category").trim();
                if(categoryexists("menu", catname)){
                    alert("'" + catname + "' exists already");
                } else {
                    categories["menu"].push(catname);
                    $("#categories_menu").append('<OPTION VALUE="' + catname + '">');
                    $("#catlist").append('<LI class="category hyperlink" onclick="cat_click(this);">' + catname + '</LI>');
                }
            }
            function categoryexists(cattype, catname){
                for(var i=0; i< categories[cattype].length; i++){
                    if(catname.isEqual(categories[cattype][i])){return true;}
                }
                return false;
            }

            function makecategories(category){
                var alldata = categories[category];
                var HTML = '';
                for(var index=0; index<alldata.length; index++){
                    HTML += '<OPTION VALUE="' + alldata[index] + '">';
                }
                $("#categories_" + category).html(HTML);
            }

            function newitem(table_name, category){
                var data = alldata[table_name][0];
                var keys = Object.keys(data);
                for(var i=0; i<keys.length; i++){
                    data[keys[i]] = "";
                }
                data["id"] = "new" + newsitems;
                switch(table_name) {
                    case 'menu':
                        data["category"] = category;
                        break;
                }
                alldata[table_name].push(data);
                var HTML = makeHTML(data, table_name);
                $("#table_" + table_name).append(HTML);
                newsitems++;
                $("html, body").animate({ scrollTop: $(document).height() }, "slow");
            }

            function makeHTML(data, table_name){
                var HTML = '';
                var HTMLclass = " " + table_name + "_" + data["id"];
                switch(table_name){
                    case 'additional_toppings':
                        HTML = makeinput2(table_name, data, "Size", "size", "text", "This word must be inside the name of the item for it to be detected as this size");
                        HTML += makeinput2(table_name, data, "Price", "price", "price");
                        break;
                    case 'toppings': case 'wings_sauce':
                        HTML = makeinput2(table_name, data, "Name", "name", "text");
                        HTML += makeinput2(table_name, data, "Category", "type", "category");
                        HTML += makeinput2(table_name, data, "Is Free", "isfree", "checkbox", "For free addons like 'well done', or 'easy on the sauce'");
                        break;
                    case 'menu':
                        HTML = makeinput2(table_name, data, "Name", "item", "text");
                        HTML += makeinput2(table_name, data, "Price", "price", "price");
                        <?php
                            foreach($addon_tables as $table){
                                echo 'HTML+= makeinput2(table_name, data, "' . ucfirst($table) . '", "' . $table . '", "number", "How many items (ie: pizzas) does the customer have to customize")' . $CRLF;
                            }
                        ?>
                        break;
                    default:
                        HTML = table_name + " is unhandled";
                }

                return '<DIV CLASS="col-md-11' + HTMLclass +  '">' + HTML + '</DIV><DIV CLASS="col-md-1' + HTMLclass +  '"><BUTTON class="btn btn-danger" TITLE="Delete this item" onclick="deleteitem(' + "'" + table_name + "', " + data["id"] + ');"><i class="fa fa-times"></i></BUTTON></DIV><DIV CLASS="col-md-12' + HTMLclass +  '"><HR></DIV>';
            }

            function makeinput2(table, data, text, column, type, title){
                if(isUndefined(title)){title = "";}
                return makeinput(table, data["id"], text, column, type, data[column], title);
            }
            function makeinput(table, primarykeyID, text, column, type, value, title){
                var HTML = ' onchange="autoupdate(this);" class="autoupdate form-control" table="' + table + '" keyid="' + primarykeyID + '" column="' + column + '" NAME="' + table + '[' +  primarykeyID + "][" + column + ']" TITLE="' + title + '"';
                switch(type){
                    case "price":
                        HTML = '<INPUT TYPE="NUMBER"' + HTML + 'VALUE="' + Number(value).toFixed(2) + '" min="0.01" step="0.05" max="2500.00">';
                        break;
                    case "category":
                        HTML = '<INPUT TYPE="TEXT"' + HTML + 'VALUE="' + value + '" list="categories_' + table + '">';
                        break;
                    case "checkbox":
                        HTML = '<INPUT TYPE="CHECKBOX"' + HTML;
                        if(value.length > 0 && value != "0"){HTML += 'CHECKED';}
                        HTML += '>';
                        break;
                    default:
                        switch(type){
                            case "number":
                                HTML += ' MIN="0"';
                                break;
                        }
                        HTML = '<INPUT TYPE="' + type + '"' + HTML + 'VALUE="' + value + '">';
                }
                return '<DIV CLASS="col-md-2">' + text + ':</DIV><DIV CLASS="col-md-10">' + HTML + '</DIV>';
            }

            (function($) {
                $.fn.currencyInput = function() {
                    this.each(function() {
                        var wrapper = $("<div class='currency-input' />");
                        $(this).wrap(wrapper);
                        $(this).before("<span class='currency-symbol'>$</span>");
                        $(this).change(function() {
                            var min = parseFloat($(this).attr("min"));
                            var max = parseFloat($(this).attr("max"));
                            var value = this.valueAsNumber;
                            if(value < min)
                                value = min;
                            else if(value > max)
                                value = max;
                            $(this).val(value.toFixed(2));
                        });
                    });
                };
            })(jQuery);

            window.onbeforeunload = function (e) {
                if (haschanges) {
                    var message = "You have unsaved changes, are you sure you want to leave?", e = e || window.event;
                    if (e) {
                        e.returnValue = message;
                    }// For IE and Firefox
                    return message;// For Safari
                }
            };
        </SCRIPT>
    @endif
@endsection