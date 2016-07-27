<?php
    function firstword($Text){
        $Space = strpos($Text, " ");
        if($Space === false){return $Text;}
        return left($Text, $Space);
    }

    if(!isset($_GET["search"]) || !trim($_GET["search"])){
        $_GET["search"] = "";
        $results["SortColumn"] = get("SortColumn", "keywords");
        $results["SortDirection"] = get("SortDirection", "DESC");
    } else{
        $con = connectdb("keywordtest");

        $results = array("SQL" => array(), "VARS" => array());
        $words = strtolower(str_replace(" ", "|", $_GET["search"]));

        $plurals = explode("|", $words);//automatically check for non-pluralized words
        foreach($plurals as $plural){
            if (strlen($plural) > 2 && right($plural, 1) == "s"){
                $words.= "|" . left($plural, strlen($plural)-1);
            }
        }

        $result = Query("SELECT * FROM keywords WHERE synonyms REGEXP '" . $words . "';");

        while ($row = mysqli_fetch_array($result)){
            $results[ $row["id"] ] = array(
                "word" => firstword($row["synonyms"]),
                "weight" => $row["weight"]
            );
            $results["SQL"][] = $row["id"];
            $results["VARS"][] = "SET @" . $results[ $row["id"] ]["word"] . " = " . $row["weight"] . ";";
        }

        $results["SQL"] = implode(",", $results["SQL"]);
        $results["VARS"] = implode(" ", $results["VARS"]);
        $results["SortColumn"] = get("SortColumn", "keywords");
        $results["SortDirection"] = get("SortDirection", "DESC");

        $results["SQL"] = "SELECT *, count(DISTINCT keyword_id) as keywords, CAST(SUM(weight)/count(*) AS UNSIGNED) as weight, GROUP_CONCAT(DISTINCT addons.name SEPARATOR '|') as addons, GROUP_CONCAT(DISTINCT synonyms SEPARATOR '|') as synonyms

              FROM (
                  SELECT items.*, items.id AS menuid, items.name as itemname, items.price as itemprice, keywords.id as wordid, menukeywords.id as mkid, menuitem_id, keyword_id, synonyms, weight
                  FROM items, menukeywords, keywords
                  HAVING menuid=menuitem_id
                  AND keyword_id IN (" . $results["SQL"] . ")
                  AND keyword_id = wordid
              ) results
              INNER JOIN addon_categories ON addon_categories.item_id = results.menuid
              INNER JOIN addons           ON addons.addon_category_id = addon_categories.id
              GROUP BY menuid ORDER BY " . $results["SortColumn"] . " " . $results["SortDirection"];

        var_dump($results);

        $result = Query($results["SQL"]);

        if($result) {
            $FirstResult = true;
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $row["id"] = $row["menuitem_id"];
                $row = removekeys($row, array("category_id", "name", "price", "display_order", "has_addon", "wordid", "mkid", "keyword_id", "req_opt", "sing_mul", "exact_upto", "exact_upto_qty", "created_at", "updated_at", "addon_category_id", "image", "menuitem_id", "item_id", "menuid"));//just to clean up the results
                $row["actions"] = '<INPUT TYPE="BUTTON" ONCLICK="runtest(this);" VALUE="' . $row["id"] . '" STYLE="width: 100%;" TITLE="Assimilate"><BR><A HREF="edititem.php?id=' . $row["id"] . '">Edit</A>';

                printrow($row, $FirstResult);
            }
            if (!$FirstResult) {echo '</TABLE><P>';}
        } else {
            echo "No keywords found in '" . $_GET["search"] . "'<P>";
        }
    }
?>
<form method="get" id="formmain">
    <input type="text" id="textsearch" name="search" size=60 value="<?= $_GET["search"]; ?>">
    <input type="button" id="startspeech" style="display:none;" value="Click to Speak" onclick="startButton(event);">
    <INPUT TYPE="submit" ID="submit" value="Search">
    <input type="button" value="In-depth search" ONCLICK="indepth();">
    <BR>
    Sorting by:
    <?php
        //<INPUT TYPE="text" NAME="SortColumn" readonly VALUE="<?= $results["SortColumn"];">
        //<INPUT TYPE="text" NAME="SortDirection" readonly VALUE="<?= $results["SortDirection"]; ">
        $Columns = array("restaurant_id", "itemprice", "weight", "keywords");
        printoptions("SortColumn", $Columns, $results["SortColumn"]);
        echo ' Direction: ';
        printoptions("SortDirection", array("ASC", "DESC"), $results["SortDirection"]);
        ?>
    Click a numerical column to sort by it. Click it again to change the sorting direction
</form>
<BR>Simulating menu item order popup<BR>
<DIV ID="thepopup" STYLE="border: 1px solid black;">Click the button in the ID column to assimilate your search string into the menu item. This cannnot be done in SQL as it's too complex, so it must by done in Javascript</DIV>

<DIM class="colheader" name="thisisatestcol" id="testingcol">TEST col</DIM>

<script type="text/javascript">
    if ('webkitSpeechRecognition' in window) {
        document.getElementById("startspeech").style = "display: inline;";

        var recognition = new webkitSpeechRecognition();
        recognition.continuous = true;
        recognition.interimResults = true;
        recognition.lang = 'en-CA';

        //onstart onresult onerror onend

        recognition.onresult = function (event) {
            var transcript = '';
            for (var i = event.resultIndex; i < event.results.length; ++i) {
                transcript += event.results[i][0].transcript;
            }
            document.getElementById("textsearch").value = transcript;
        };
    } else {
        console.log("Speech recognition was not found");
    }

    function startButton(event) {
        recognition.start();
    }

    //speak('Jon likes Iced Tea!');
    function speak(text, callback) {
        var u = new SpeechSynthesisUtterance();
        u.text = text;
        u.lang = 'en-US';

        u.onend = function () {
            if (callback) {callback();}
        };

        u.onerror = function (e) {
            if (callback) {callback(e);}
        };

        speechSynthesis.speak(u);
    }

    function runtest(t) {
        var ID = t.getAttribute("value");
        var toppings = document.getElementById("row" + ID + "-addons").innerHTML.split("|");
        var keywords = document.getElementById("row" + ID + "-synonyms").innerHTML.replaceAll("[|]", " ");
        var itemname = document.getElementById("row" + ID + "-itemname").innerHTML;
        var select = '<SPAN ID="searchfor"></SPAN><BR>Item Title: <SPAN ID="itemtitle' + ID + '">' + itemname + '</SPAN> (Converts to: ' + replacesynonyms(itemname) + ')<BR>';
        select = select + 'Quantity: <SELECT ID="select' + ID + '">';
        for (var i = 1; i <= 10; i++) {
            select = select + '<OPTION>' + i + '</OPTION>';
        }
        select = select + '</SELECT><TABLE><TR>';

        var col = 0
        for (var i = 0; i < toppings.length; i++) {
            toppings[i] = toppings[i].replaceAll("Jalape?o", "Jalapeno");
            select = select + '<TD WIDTH="20%"><LABEL><INPUT TYPE="CHECKBOX"><SPAN CLASS="ver">' + toppings[i] + '</SPAN></LABEL></TD>';
            col++;
            if (col == 5) {
                select = select + '</TR><TR>';
                col = 0;
            }
        }
        select = select + "</TABLE>";

        innerHTML("#thepopup", '<DIV ID="product-pop-up_' + ID + '">' + select + '</DIV>');
        select = assimilate(ID);

        for(i=0; i< select[1].length; i++){
            if(keywords.indexOf(select[1][i]) > -1 ){
                select[1][i] = strike(select[1][i], 'This keyword was used to find the menu item');
            } else if( wordstoignore.indexOf(select[1][i]) > -1 ){
                select[1][i] = strike(select[1][i], 'This keyword can not be used to find food and is better off ignored');
            }
        }
        log(select[1].join(", "));

        innerHTML("#searchfor", "Searching string: " + select[0] + "<BR>Keywords not found: " + select[1].join(", ") + " (Words that are <STRIKE>struck out</STRIKE> are not useful)");
    }

    function strike(Text, Reason){
        return '<STRIKE TITLE="' + Reason + '">' + Text + '</STRIKE>';
    }

    function log(text){
        console.log(text);
    }

    function indepth(){
        window.location = "<?= Request::url(); ?>?search=" + value("#textsearch");
    }

    addlistener(".colheader", "click", function(){
        var clicked = innerHTML(this);
        var selected = value("#SortColumn");
        if(selected == clicked){
            var direction = value("#SortDirection");
            if(direction == "ASC"){
                value("#SortDirection", "DESC");
            } else {
                value("#SortDirection", "ASC");
            }
        } else if (SelectHasOptionValue("#SortColumn", clicked)) {
            value("#SortColumn", clicked);
        } else {
            return false;
        }
        trigger("#submit", "click");
    });

    function SelectHasOptionValue(Select, Value){
        return Select(Select + " option[value='" + Value + "']").length > 0;
    }
    function SelectHasOptionText(Select, Option){
        return Select(Select + " option:contains('" + Option + "')").length > 0;
    }
</script>