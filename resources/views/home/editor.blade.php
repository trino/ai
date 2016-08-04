<?php
    $con = connectdb("ai");

    if(!isset($_GET["action"])){
        if(isset($_POST["action"])){
            $_GET = $_POST;
        } else {
            $_GET["action"] = "main";
        }
    }

    function keywordresult($keyword, $Actions = "", &$firstresult = false){
        switch($Actions){
            case "suggestions": case "keywordsfound":
                $keyword["Assign"] = '<BUTTON STYLE="width:100%;" VALUE="' . $keyword["id"] . '" ONCLICK="assign(this, false);" CLASS="assign">To Item</BUTTON>';
                $keyword["Assign"] .= '<BUTTON STYLE="width:100%;" VALUE="' . $keyword["id"] . '" ONCLICK="assign(this, true);" CLASS="assign">To Category</BUTTON>';
                break;
            case "keywords":
                $keyword["Assigned to"] = iif($keyword["menuitem_id"] > 0, "Item", "Category");
                $keyword["Actions"] = '<BUTTON VALUE="' . $keyword["id"] . '" ONCLICK="deletekeyword(this);">Delete</BUTTON>';
                break;
        }
        printrow($keyword, $firstresult, "id", $Actions);
    }

    function findarraywhere($ARR, $Key, $Value){
        foreach($ARR as $Index => $Cell){
            if($Cell[$Key] == $Value){
                return $Index;
            }
        }
    }

    switch($_GET["action"]){
        case "main":
            echo '<script src="' . webroot() . '/resources/assets/scripts/api.js"></script>';
            echo '<button onclick="window.history.back();">Go Back</button> <button onclick="location.reload();">Refresh</button>';

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
            if($keywords) {
                foreach ($keywords as $keyword) {
                    keywordresult($keyword, "keywords", $firstresult);
                    $key = findarraywhere($suggestions, "id", $keyword["keyword_id"]);
                    if(strlen($key)){
                        unset($suggestions[ $key ]);
                    }
                }
                echo '</TABLE>';
            } else {
                echo '<table border="1" id="keywords"><tr><th id="keywords-col0">id</th><th id="keywords-col1">menuitem_id</th><th id="keywords-col2">keyword_id</th><th id="keywords-col3">synonyms</th><th id="keywords-col4">weight</th><th id="keywords-col5">Actions</th></tr></table>';
            }

            if($suggestions) {
                $firstresult = true;
                echo "<P>Suggested Keywords:";
                foreach ($suggestions as $keyword) {
                    keywordresult($keyword, "suggestions", $firstresult);
                }
            }

            echo "<P>Keyword Search:";
            echo '<TABLE BORDER="1"><THEAD><TR><TH>ID</TH><TH>Synonyms</TH><TH>Weight</TH><TH>Actions</TH></TR><TR><TD></TD><TD>';
            echo '<INPUT TYPE="TEXT" NAME="searchtext" ID="searchtext" TITLE="Do not use commas to delimit it, do not add a keyword that exists already"></TD><TD>';
            echo '<INPUT TYPE="NUMBER" MIN="1" MAX="5" VALUE="1" NAME="weight" ID="weight" STYLE="text-align:right;" TITLE="A weight of 5 counts as an item important enough to run a new search">';
            echo '</TD><TD><BUTTON ONCLICK="search();" STYLE="width:100%;">Search</BUTTON>';
            echo '</TD></TR></THEAD><TBODY ID="searchbody"></TBODY></TABLE>';

            break;

        case "deleteitemkeyword":
            deleterow("menukeywords", "id = " . $_GET["id"]);
            break;

        case "searchkeyword":
            $keywords = select_field_where("keywords", "synonyms LIKE '%" . $_GET["text"] . "%'", "ALL()");
            $firstresult = false;
            if($keywords) {
                foreach ($keywords as $keyword) {
                    keywordresult($keyword, "keywordsfound");
                }
            } else {
                echo '<TR><TD COLSPAN="3">No results found for: ' . $_GET["text"] . '</TD><TD><BUTTON ONCLICK="create();" STYLE="width:100%;" ID="create">Create</BUTTON></TD></TR>';
            }
            break;

        case "createkeyword":
            $_GET["keyword_id"] = insertdb("keywords", array("synonyms" => $_GET["synonyms"], "weight" => $_GET["weight"]));
        case "assignkeyword":
            if(!select_field_where("menukeywords", "menuitem_id = " . $_GET["menuitem_id"] . " AND keyword_id = " . $_GET["keyword_id"], "COUNT()")) {
                $ID = insertdb("menukeywords", array("menuitem_id" => $_GET["menuitem_id"], "keyword_id" => $_GET["keyword_id"]));
                $keyword = select_field_where("keywords", "id = " . $_GET["keyword_id"]);
                $firstresult = false;
                keywordresult(array("id" => $ID, "menuitem_id" => $_GET["menuitem_id"], "keyword_id" => $_GET["keyword_id"], "synonyms" => $keyword["synonyms"], "weight" => $keyword["weight"]), "keywords");
            }
            break;




        default:
            echo "'" . $_GET["action"] ."' is unhandled";
    }
    if($_GET["action"] != "main"){die();}
?>
<SCRIPT>
    var itemID = Number("<?= $_GET["id"]; ?>");
    var categoryID = -Number(text("#itemrow" + itemID + "-category_id"));
    var itemName = text("#itemrow" + itemID + "-item");
    var thisURL = "<?= Request::url(); ?>";
    var token = "<?= csrf_token(); ?>";

    function getID(iscategory){
        if(isUndefined(iscategory)){iscategory = false;}
        if(iscategory){return categoryID;}
        return itemID;
    }

    function deletekeyword(t){
        var ID = t.getAttribute("value");
        var menuitem_id = text("#keywordsrow" + ID + "-menuitem_id");
        if (confirm("Are you sure you want to delete '" + text("#keywordsrow" + ID + "-synonyms") + "' from '" + itemName + "'?")){
            post(thisURL, {
                action: "deleteitemkeyword",
                id: ID,
                itemid: menuitem_id,
                _token: token
            }, function (result) {
                fadeoutanddelete("#keywordsrow" + ID);
                if(result){alert(result);}
            });
        }
    }

    function fadeoutanddelete(Selector){
        fadeOut(Selector, 500, function () {
            remove(Selector);
        });
    }

    function search(){
        post(thisURL, {
            action: "searchkeyword",
            text: value("#searchtext"),
            _token: token
        }, function (result) {
            innerHTML("#searchbody", result);
        });
    }

    function assign(t, iscategory){
        var ID = t.getAttribute("value");
        var Name = text("#keywordsfoundrow" + ID + "-synonyms");
        post(thisURL, {
            action: "assignkeyword",
            keyword_id: ID,
            menuitem_id: getID(iscategory),
            _token: token
        }, function (result) {
            if(result){
                console.log(result);
                append("#keywords", result);
                fadeoutanddelete("#keywordsfoundrow" + ID);
                fadeoutanddelete("#suggestionsrow" + ID);
            } else {
                alert("Error, unable to add '" + Name + "'. It possibly exists in '" + itemName + "' already");
            }
        });
    }

    function assignall(){
        trigger("#suggestions .assign :first", "click");
    }

    function create(){
        var Name = value("#searchtext");
        var Weight = value("#weight");
        value("#searchtext", "");
        value("#weight", "1");

        post(thisURL, {
            action: "createkeyword",
            synonyms: Name,
            weight: Weight,
            menuitem_id: itemID,
            _token: token
        }, function (result) {
            append("#keywords", result);
            fadeoutanddelete("#create");
        });
    }
</SCRIPT>
