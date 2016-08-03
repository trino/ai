<TABLE>
    <?php
        $con = connectdb("ai");
        $currentURL = Request::url();
        $backURL = $currentURL;
        if(isset($_GET["table"])){
            echo '<FORM METHOD="GET" ACTION="' . $currentURL . '">';
            $currentURL .= "?table=" . $_GET["table"];
            $query = "SELECT * FROM " . $_GET["table"];
            if(isset($_GET["id"])){
                $query .= " WHERE id = " . $_GET["id"];
                $backURL .= "?table=" . $_GET["table"];
                if(isset($_GET["name"])){
                    $dataarray = $_GET;
                    unset($dataarray["table"]);
                    insertdb($_GET["table"], $dataarray);
                    echo 'Data has been saved to ' . $_GET["table"] . '<BR>';
                }
                echo '<INPUT TYPE="HIDDEN" NAME="id" VALUE="' . $_GET["id"] . '">';
            }
            echo '<A HREF="' . $backURL . '">Back</A> ';
            $results = Query($query, true);
            $firstresult = true;
            echo '<INPUT TYPE="HIDDEN" NAME="table" VALUE="' . $_GET["table"] . '">';
            foreach($results as $result){
                if(isset($_GET["id"])){
                    if(in_array($_GET["table"], array("toppings", "wings_sauce"))){
                        $categories = Query("SELECT DISTINCT(type) FROM " . $_GET["table"],true);
                        foreach($categories as $index => $category){
                            $categories[$index] = '<option value="' . $category["type"] .'">';
                        }
                        $result["name"] = '<INPUT TYPE="TEXT" NAME="name" VALUE="' . $result["name"] . '">';
                        $result["type"] = '<input type="text" name="type" value="' . $result["type"] . '" list="categories"><datalist id="categories">' . implode("\r\n", $categories) . '</datalist>';
                        $result["isfree"] = printoptions("isfree", array("No", "Yes"), $result["isfree"], array(0,1));
                        $result["qualifiers"] = '<INPUT TYPE="TEXT" NAME="qualifiers" VALUE="' . $result["qualifiers"] . '" TITLE="Leave blank for Half/Single/Double, must have 3 items in a comma delimited list if not blank">';
                    } else {
                        foreach($result as $index => $value){
                            if($index != "id"){
                                $result[$index] = '<INPUT TYPE="TEXT" NAME="' . $index . '" VALUE="' . $value . '">';
                            }
                        }
                    }
                    echo '<INPUT TYPE="SUBMIT" VALUE="Save">';
                } else {
                    $result["Actions"] = '<A HREF="' . $currentURL . '&id=' . $result["id"] .'">Edit</A>';
                }
                printrow($result, $firstresult, "id", $_GET["table"]);
            }
        } else {
            foreach(enum_tables() as $table){
                echo '<TR><TD><A HREF="?table=' . $table . '">' . $table . '</A></TD></TR>';
            }
        }
    ?>
</TABLE>