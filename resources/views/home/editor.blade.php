<?php
    $con = connectdb("ai");

    if (!isset($_GET["action"])) {
        if (isset($_POST["action"])) {
            $_GET = $_POST;
        } else {
            $_GET["action"] = "list";
            if (isset($_GET["id"]) && $_GET["id"]) {
                $_GET["action"] = "main";
            }
        }
    }

    function keywordresult($keyword, $Actions = "", &$firstresult = false){
        switch ($Actions) {
            case "suggestions":
            case "keywordsfound":
                $keyword["Assign"] = '<BUTTON STYLE="width:100%;" VALUE="' . $keyword["id"] . '" ONCLICK="assign(this, false);" CLASS="assign">To Item</BUTTON>';
                $keyword["Assign"] .= '<BUTTON STYLE="width:100%;" VALUE="' . $keyword["id"] . '" ONCLICK="assign(this, true);" CLASS="assign">To Category</BUTTON>';
                unset($keyword["weight"]);
                unset($keyword["keywordtype"]);
                break;
            case "keywords":
                $keywordtype = "Unknown";
                switch ($keyword["keywordtype"]) {
                    case 1:
                        $keywordtype = "Quantity";
                        break;
                    case 2:
                        $keywordtype = "Size";
                        break;
                }
                if ($keyword["weight"] == 5) {
                    $keyword["keywordtype"] = -5;
                    $keywordtype = "Primary";
                }
                $keyword["Context"] = $keywordtype;
                $keyword["Assigned to"] = iif($keyword["menuitem_id"] > 0, "Item", "Category");
                $keyword["Actions"] = '<BUTTON VALUE="' . $keyword["keyword_id"] . '" ONCLICK="editkeyword(this);">Edit</BUTTON> ';
                $keyword["Actions"] .= '<BUTTON VALUE="' . $keyword["id"] . '" ONCLICK="deletekeyword(this);">Delete</BUTTON>';
                break;
        }
        printrow($keyword, $firstresult, "id", $Actions);
    }

    function findarraywhere($ARR, $Key, $Value){
        foreach ($ARR as $Index => $Cell) {
            if ($Cell[$Key] == $Value) {
                return $Index;
            }
        }
    }

    function keywordsexist($words){
        $words = explode(" ", $words);
        $result = Query("SELECT synonyms FROM keywords");
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $ret = containswords($row["synonyms"], $words);
            if ($ret) {
                $synonyms = explode(" ", $row["synonyms"]);
                return $synonyms[$ret[0]];
            }
        }
    }

    function firstword($Text){
        $Space = strpos($Text, " ");
        if ($Space === false) {
            return $Text;
        }
        return left($Text, $Space);
    }

    $dieafter = true;
    switch ($_GET["action"]) {
        case "list":
            $dieafter = false;
            $_GET["id"] = "";
            echo '<FORM ACTION="' . webroot("public/edit") . '">';
            if (isset($_GET["name"])) {
                $SQL .= " WHERE item LIKE '%" . $_GET["name"] . "%' OR category LIKE '%" . $_GET["name"] . "%'";
                echo '<button onclick="return viewall();">View All</button> ';
            } else {
                $_GET["name"] = "";
            }
            echo '<INPUT TYPE="TEXT" NAME="name" VALUE="' . $_GET["name"] . '"> ';
            echo '<INPUT TYPE="SUBMIT" VALUE="Search"></FORM> ';
            $SQL = "SELECT * FROM menu";

            $menuitems = Query($SQL, true);
            $firstresult = true;
            foreach ($menuitems as $menuitem) {


                /*
                 * TOPPINGS
                 */
                if ($menuitem['toppings'] > 0) {
                    $toppings_display = "";

                    $toppings = Query("SELECT * FROM toppings", true);
                    for ($i = 0; $i < $menuitem['toppings']; $i++) {
                        $toppings_display = $toppings_display . "<select>";
                        foreach ($toppings as $ID => $topping) {
                            $toppings_display = $toppings_display . "<option>" . $topping["id"] . ' ' . $topping["name"] . "</option>";
                        }
                        $toppings_display = $toppings_display . "</select><br>";
                    }
                    $menuitem["toppings"] = $toppings_display;
                } else {
                    $menuitem["toppings"] = "";

                }

                /*
                 * WINGS
                 */
                if ($menuitem['wings_sauce'] > 0) {
                    $wings_sauce = "";

                    $toppings = Query("SELECT * FROM wings_sauce", true);
                    for ($i = 0; $i < $menuitem['wings_sauce']; $i++) {
                        $wings_sauce = $wings_sauce . "<select>";
                        foreach ($toppings as $ID => $topping) {
                            $wings_sauce = $wings_sauce . "<option>" . $topping["id"] . ' ' . $topping["name"] . "</option>";
                        }
                        $wings_sauce = $wings_sauce . "</select><br>";
                    }
                    $menuitem["wings_sauce"] = $wings_sauce;
                } else {
                    $menuitem["wings_sauce"] = "";

                }


                $keywords = Query("SELECT * FROM keywords, menukeywords WHERE menuitem_id = " . $menuitem["id"] . " OR -menuitem_id = " . $menuitem["category_id"] . " HAVING keywords.id = keyword_id", true);
                //id, synonyms, weight, keywordtype, menuitem_id, keyword_id
                foreach ($keywords as $ID => $keyword) {
                    $keywords[$ID] = '<SPAN TITLE="Weight: ' . $keyword["weight"] . '">' . firstword($keyword["synonyms"]) . '</SPAN>';
                }
                $menuitem["price"] = number_format($menuitem["price"], 2);
                $menuitem["keywords"] = implode(", ", $keywords);
                $menuitem["Actions"] = '<A HREF="?id=' . $menuitem["id"] . '">Edit</A>';


                printrow($menuitem, $firstresult);
            }





            echo '</TABLE>';
            break;
        case "main":
            $dieafter = false;
            echo '<button onclick="window.history.back();">Go Back</button> <button onclick="location.reload();">Refresh</button> <button onclick="viewall();">View All</button>';

            $menuitem = select_field_where("menu", "id=" . $_GET["id"]);//was items
            //$menuitem["restaurant_name"] = select_field_where("restaurants", "id=" . $menuitem["restaurant_id"], "name");
            //$menuitem["category_name"] = select_field_where("categories", "id=" . $menuitem["category_id"], "name");

            $keywords = Query("SELECT *, menukeywords.id as id FROM menukeywords INNER JOIN keywords ON keywords.id = menukeywords.keyword_id WHERE menuitem_id = " . $_GET["id"] . " OR -menuitem_id = " . $menuitem["category_id"], true);

            $firstresult = true;
            echo "<P>Menu item data:";
            printrow($menuitem, $firstresult, "id", "item");
            echo '</TABLE>';

            $suggestions = select_field_where("keywords", "synonyms REGEXP '" . str_replace(" ", "|", $menuitem["item"]) . "'", "ALL()");//item was name
            $suggestions2 = select_field_where("keywords", "synonyms REGEXP '" . str_replace(" ", "|", $menuitem["category"]) . "'", "ALL()");//item was name
            $suggestions = array_merge($suggestions, $suggestions2);

            $firstresult = true;
            echo "<P>Assigned Keywords:";
            if ($keywords) {
                foreach ($keywords as $keyword) {
                    keywordresult($keyword, "keywords", $firstresult);
                    $key = findarraywhere($suggestions, "id", $keyword["keyword_id"]);
                    if (strlen($key)) {
                        unset($suggestions[$key]);
                    }
                    $key = findarraywhere($suggestions, "id", $keyword["keyword_id"]);
                    if (strlen($key)) {
                        unset($suggestions[$key]);
                    }
                }
                echo '</TABLE>';
            } else {
                echo '<table border="1" id="keywords"><tr><th id="keywords-col0">id</th><th id="keywords-col1">menuitem_id</th><th id="keywords-col2">keyword_id</th><th id="keywords-col3">synonyms</th><th id="keywords-col4">weight</th><TH>keywordtype</TH><TH>Context</TH><TH>Assigned to</TH><th id="keywords-col5">Actions</th></tr></table>';
            }

            if ($suggestions) {
                $firstresult = true;
                echo "<P>Suggested Keywords:";
                foreach ($suggestions as $keyword) {
                    keywordresult($keyword, "suggestions", $firstresult);
                }
                echo '</TABLE>';
            }

            echo view("popups.keywordsearch");


            break;

        case "deleteitemkeyword":
            deleterow("menukeywords", "id = " . $_GET["id"]);
            break;

        case "searchkeyword":
            $keywords = select_field_where("keywords", "synonyms LIKE '%" . $_GET["text"] . "%'", "ALL()");
            $firstresult = false;
            if ($keywords) {
                foreach ($keywords as $keyword) {
                    keywordresult($keyword, "keywordsfound");
                }
            } else {
                echo '<TR><TD COLSPAN="4">No results found for: ' . $_GET["text"] . '</TD><TD><BUTTON ONCLICK="create();" STYLE="width:100%;" ID="create">Create</BUTTON></TD></TR>';
            }
            break;

        case "createkeyword":
            //error handling
            $wordstoignore = ["the", "with", "and", "times", "on", "one"];
            $_GET["synonyms"] = strtolower(trim(filterduplicates(filternonalphanumeric($_GET["synonyms"]))));
            $exists = keywordsexist($_GET["synonyms"]);
            if ($exists) {
                die("ERROR: '" . $exists . "' already exists");
            }
            $synoynms = explode(" ", $_GET["synonyms"]);
            foreach ($synoynms as $synoynm) {
                if (strlen($synoynm) < 3) {
                    die("ERROR: '" . $synoynm . "' is too small");
                } else if (in_array($synoynm, $wordstoignore)) {
                    die("ERROR: '" . $synoynm . "' is an ignored word");
                }
            }

            $_GET["keyword_id"] = insertdb("keywords", array("synonyms" => $_GET["synonyms"], "weight" => $_GET["weight"], "keywordtype" => $_GET["keywordtype"]));
        case "assignkeyword":
            $keyword = first("SELECT * FROM keywords WHERE id = " . $_GET["keyword_id"]);//$keyword["keywordtype"] 1=quantity, 2=size
            if($keyword["keywordtype"] > 0){
                $mnukeyword = getkeyword($_GET["menuitem_id"], $keyword["keywordtype"]);
                $keywordtypes = array("", "quantity", "size");
                if($mnukeyword){
                    die("ERROR: This item already has a '" . $keywordtypes[$keyword["keywordtype"]] . "' keyword-type assigned");
                }
            }

            if (!select_field_where("menukeywords", "menuitem_id = " . $_GET["menuitem_id"] . " AND keyword_id = " . $_GET["keyword_id"], "COUNT()")) {
                $ID = insertdb("menukeywords", array("menuitem_id" => $_GET["menuitem_id"], "keyword_id" => $_GET["keyword_id"]));
                $keyword = select_field_where("keywords", "id = " . $_GET["keyword_id"]);
                $firstresult = false;
                keywordresult(array("id" => $ID, "menuitem_id" => $_GET["menuitem_id"], "keyword_id" => $_GET["keyword_id"], "synonyms" => $keyword["synonyms"], "weight" => $keyword["weight"], "keywordtype" => $keyword["keywordtype"]), "keywords");
            }
            break;


        default:
            echo "'" . $_GET["action"] . "' is unhandled";
    }
    if ($dieafter) {
        die();
    }
    echo '<script src="' . webroot() . '/resources/assets/scripts/api.js"></script>';
?>
<SCRIPT>
    var itemID = Number("<?= $_GET["id"]; ?>");
    var categoryID = -Number(text("#itemrow" + itemID + "-category_id"));
    var itemName = text("#itemrow" + itemID + "-item");
    var thisURL = "<?= Request::url(); ?>";
    var token = "<?= csrf_token(); ?>";

    function keychange() {
        var keywordtype = value("#keywordtype");//in case it's needed
    }

    function getID(iscategory) {
        if (isUndefined(iscategory)) {
            iscategory = false;
        }
        if (iscategory) {
            return categoryID;
        }
        return itemID;
    }

    function editkeyword(t) {
        var ID = t.getAttribute("value");
        window.location = "<?= webroot();?>public/edittable?table=keywords&id=" + ID;
    }

    function deletekeyword(t) {
        var ID = t.getAttribute("value");
        var menuitem_id = text("#keywordsrow" + ID + "-menuitem_id");
        if (confirm("Are you sure you want to unassign '" + text("#keywordsrow" + ID + "-synonyms") + "' from '" + itemName + "'?")) {
            post(thisURL, {
                action: "deleteitemkeyword",
                id: ID,
                itemid: menuitem_id,
                _token: token
            }, function (result) {
                if (isnotanerror(result)) {
                    fadeoutanddelete("#keywordsrow" + ID);
                    if (result) {alert(result);}
                }
            });
        }
    }

    function fadeoutanddelete(Selector) {
        fadeOut(Selector, 500, function () {
            remove(Selector);
        });
    }

    function search() {
        post(thisURL, {
            action: "searchkeyword",
            text: value("#searchtext"),
            _token: token
        }, function (result) {
            if (isnotanerror(result)) {
                innerHTML("#searchbody", result);
                if (result.indexOf("create();") == -1) {
                    hidecols();
                } else {
                    showcols();
                }
            }
        });
    }

    function assign(t, iscategory) {
        var ID = t.getAttribute("value");
        var Name = text("#keywordsfoundrow" + ID + "-synonyms");
        post(thisURL, {
            action: "assignkeyword",
            keyword_id: ID,
            menuitem_id: getID(iscategory),
            _token: token
        }, function (result) {
            if (result) {
                if (isnotanerror(result)) {
                    console.log(result);
                    append("#keywords", result);
                    fadeoutanddelete("#keywordsfoundrow" + ID);
                    fadeoutanddelete("#suggestionsrow" + ID);
                }
            } else {
                alert("Error, unable to add '" + Name + "'. It possibly exists in '" + itemName + "' already");
            }
        });
    }

    function assignall() {
        trigger("#suggestions .assign :first", "click");
    }

    function viewall() {
        return loadUrl('<?= webroot("public/edit"); ?>');
    }

    function create() {
        var Name = value("#searchtext").toLowerCase();
        var Weight = value("#weight");
        var keywordtype = value("#keywordtype");
        hidecols();

        post(thisURL, {
            action: "createkeyword",
            synonyms: Name,
            weight: Weight,
            keywordtype: keywordtype,
            menuitem_id: itemID,
            _token: token
        }, function (result) {
            if (isnotanerror(result)){
                append("#keywords", result);
                fadeoutanddelete("#create");
            }
        });
    }

    function isnotanerror(result){
        if (result.startswith("ERROR:")) {
            alert(result);
            return false;
        }
        return true;
    }

    function hidecols() {
        hide(".hideme");
        value("#searchtext", "");
        value("#weight", "1");
        value("#keywordtype", "0");
    }
    function showcols() {
        show(".hideme");
    }

    hidecols();
</SCRIPT>
