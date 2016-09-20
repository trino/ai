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
                                    <UL>
                                        <LI class="main hyperlink" table="additional_toppings">Size costs</LI>
                                        <LI class="main hyperlink" table="toppings">Pizza Toppings</LI>
                                        <LI class="main hyperlink" table="wings_sauce">Wing Sauces</LI>
                                        <HR>
                                        Menu:
                                        <LI class="category hyperlink">[New Category]</LI>
                                        <?php
                                            $categories = collapsearray(Query('SELECT category FROM `menu` GROUP BY category_id', true), "category");
                                            foreach($categories as $category){
                                                echo '<LI class="category hyperlink">' . $category . '</LI>';
                                            }
                                        ?>
                                    </UL>
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
    <SCRIPT>
        var alldata = {};
        var categories = {};
        var current_table = "";
        var current_category = "";
        <?php
            $CRLF = ";\r\n\t\t";
            if(read("profiletype") == 1){
                echo 'categories["menu"] = ' . json_encode($categories) . $CRLF;
                foreach($addon_tables as $table){
                    echo 'categories["' . $table . '"] = ' . json_encode(collapsearray(Query('SELECT type FROM ' . $table . ' GROUP BY type', true), "type")) . $CRLF;
                    echo 'makecategories("' . $table . '")' . $CRLF;
                }
                foreach($tables as $table){
                    echo 'alldata["' . $table . '"] = ' . json_encode(Query("SELECT * FROM " . $table, true)) . $CRLF;
                }
            }
        ?>

        $( document ).ready(function() {
            $(".category").click(function () {
                current_category = $(this).text();
                current_table="menu";
                $(".table_main").hide();
                var HTML = getmenuitems(current_category);
                $("#table_menu").html(HTML).show();
                $('input.currency').currencyInput();
            });

            $(".main").click(function () {
                current_table = $(this).attr("table");
                $(".table_main").hide();
                $("#table_" + current_table).show();
            });

            processAll();
        });

        function processAll(){
            var tables = Object.keys(alldata);
            for(var tableindex = 0; tableindex < tables.length; tableindex++){
                var table_name = tables[tableindex];
                var table_data = alldata[table_name];
                var HTML = "";
                for(var dataindex = 0; dataindex < table_data.length; dataindex++){
                    HTML += makeHTML(table_data[dataindex], table_name);
                }
                $("#table_" + table_name).html(HTML);
            }
        }

        function getmenuitems(category){
            var HTML = '';
            for(var index = 0; index< alldata["menu"].length; index++){
                var data = alldata["menu"][index];
                if(data["category"].isEqual(category)){
                    HTML += makeHTML(data, "menu");
                }
            }
            return HTML;
        }

        function makecategories(category){
            var alldata = categories[category];
            var HTML = '';
            for(var index=0; index<alldata.length; index++){
                HTML += '<OPTION VALUE="' + alldata[index] + '">';
            }
            $("#categories_" + category).html(HTML);
        }

        function makeHTML(data, table_name){
            var HTML = "";
            switch(table_name){
                case 'additional_toppings':
                    HTML = makeinput2(table_name, data, "Size", "size", "text");
                    HTML += makeinput2(table_name, data, "Price", "price", "price");
                    break;
                case 'toppings': case 'wings_sauce':
                    HTML = makeinput2(table_name, data, "Name", "name", "text");
                    HTML += makeinput2(table_name, data, "Category", "type", "category");
                    HTML += makeinput2(table_name, data, "Is Free", "isfree", "checkbox");
                    break;
                case 'menu':
                    //{"id":"1","category_id":"1","category":"Pizza","item":"Small Pizza","price":"6.99","toppings":"1","wings_sauce":"0"}
                    HTML = makeinput2(table_name, data, "Name", "item", "text");
                    HTML += makeinput2(table_name, data, "Price", "price", "price");
                    <?php
                        foreach($addon_tables as $table){
                            echo 'HTML+= makeinput2(table_name, data, "' . ucfirst($table) . '", "' . $table . '", "number")' . $CRLF;
                        }
                    ?>
                    break;
                default:
                    HTML = table_name + " is unhandled";
            }
            return HTML + '<DIV CLASS="col-md-12"><HR></DIV>';
        }

        function makeinput2(table, data, text, column, type){
            return makeinput(table, data["id"], text, column, type, data[column]);
        }
        function makeinput(table, primarykeyID, text, column, type, value){
            var HTML = ' class="autoupdate form-control" table="' + table + '" keyid="' + primarykeyID + '" column="' + column + '" NAME="' + table + '[' +  primarykeyID + "][" + column + ']" ';
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
    </SCRIPT>
@endsection