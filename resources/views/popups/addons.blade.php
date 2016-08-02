<?php
    if(!isset($table)){
        $table = "toppings";
    }
    $categories = Query("SELECT DISTINCT type FROM `$table` ORDER BY type ASC", true);
    $toppings = select_field_where($table, "1=1", "ALL()", "type");
    $width = round(100 / count($categories));

    echo '<STYLE>.cat-header-' . $table .'{width: ' . $width . '%; display: inline-block;}</STYLE>';

    echo '<DIV CLASS="addons-' . $table . '" STYLE="display: none;">';
    foreach($categories as $index => $category){
        echo '<SPAN CLASS="cat-header cat-headerid-' . $table . '-' . $index .' cat-header-' . $table . '" NAME="cat-' . $table . '-' . strtolower(str_replace(" ", "-", $category["type"]));
        echo '">' . $category["type"] . '</SPAN>';
    }

    if(!function_exists("endtoppings")){
        function endtoppings($table, $start = false){
            if($start){
                $start = strtolower($start);
                echo '<TABLE CLASS="cat cat-' . $table . '" ID="cat-' . $table . "-" . str_replace(" ", "-", $start) . '" STYLE="display: none;">';
                return $start;
            } else {
                echo '</TABLE>';
            }
        }
    }

    $CurrentType = "";
    foreach($toppings as $topping){
        if($CurrentType != strtolower($topping["type"])){
            if($CurrentType){$CurrentType = endtoppings($table);}
            $CurrentType = endtoppings($table, $topping["type"]);
        }

        echo '<TR ID="tr-addon-' . $table . '-' . $topping["id"] . '" CLASS="tr-addon tr-addon-' . $table . '" SELECTED="" TOPPINGID="' . $topping["id"] . '" NAME="' . $topping["name"] .'"';
        if($topping["isfree"]){echo ' ISFREE="true"';}
        echo '>';
        $qualifiers = array("half" => "Half", "single" => "Single", "double" => "Double");
        if($topping["qualifiers"]){
            $name = explode(",", $topping["qualifiers"]);
            $index = 0;
            foreach($qualifiers as $qualifier => $discard){
                $qualifiers[$qualifier] = trim($name[$index]);
                $index++;
            }
        }
        foreach($qualifiers as $qualifier => $name){
            echo '<TD><LABEL><INPUT TYPE="RADIO" NAME="addon-' . $table . '-' . $topping["id"] . '" CLASS="addon addon-' . $table . '" VALUE="' . $qualifier . '">' . $name . '</LABEL></TD>';
        }

        echo '<TD>' . $topping["name"] . '</TD></TR>';
    }
    endtoppings($table);
    echo '</DIV>';
?>
<SCRIPT>
    addlistener(".addon-<?= $table ?>", "click", function(){
        var thevalue = this.value;
        var selected = attr( "#tr-" + this.name, "selected");
        if(selected == thevalue) {
            console.log( removeattr("input[name='" + this.name + "']", "checked") );
            attr("input[name='" + this.name + "']", "checked", false);
            thevalue = "";
        }
        attr("#tr-" + this.name, "selected", thevalue);

        innerHTML("#toppings", getaddons("", true));

        if(!thevalue) {return false;}
    });

    addlistener(".cat-header-<?= $table ?>", "click", function(){
        hide(".cat-<?= $table ?>");
        show("#" + attr(this, "name"));
    });

    trigger(".cat-headerid-<?= $table; ?>-0", "click");
</SCRIPT>
