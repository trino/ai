<?php
    if(isset($user_id)){
        $user = first("SELECT * FROM users WHERE id=" . $user_id);
        echo '<INPUT TYPE="HIDDEN" NAME="id" VALUE="' . $user_id  . '">';
        if(!isset($name)){$name="user";}
    } else {
        $user = array("name" => "", "phone" => "", "email" => "");
        if(!isset($name)){$name="reg";}
    }

    if(!function_exists("printarow")){
        function printarow($Name, $Prepend, $field){
            if($field["type"] != "hidden"){echo '<DIV CLASS="row"><DIV CLASS="col-md-2">' . $Name . ':</DIV><DIV CLASS="col-md-10">';}
            echo '<INPUT TYPE="' . $field["type"] . '" NAME="' . $field["name"] . '" ID="' . $Prepend . '_' . $field["name"] . '"';
            if(isset($field["class"])){echo ' CLASS="' . $field["class"] . '" ';}
            if(isset($field["value"])){echo ' value="' . $field["value"] . '" ';}
            if(isset($field["readonly"])){echo ' readonly';}
            echo '>';
            if($field["type"] != "hidden"){echo '</DIV></DIV>';}
        }
    }

    printarow("Name", $name, array("name" => "name", "value" => $user["name"], "type" => "text", "class" => "form-control"));
    printarow("Phone", $name, array("name" => "phone", "value" => $user["phone"], "type" => "tel", "class" => "form-control"));
    printarow("Email", $name, array("name" => "email", "value" => $user["email"], "type" => "email", "class" => "form-control"));
    if(isset($user_id)){
        printarow("Old Password", $name, array("name" => "oldpassword", "type" => "password", "class" => "form-control"));
        printarow("New Password", $name, array("name" => "newpassword", "type" => "password", "class" => "form-control"));
    } else {
        printarow("Password", $name, array("name" => "password", "type" => "password", "class" => "form-control"));
    }
?>