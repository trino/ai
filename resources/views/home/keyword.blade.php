<script src="<?= webroot("resources/assets/scripts/api.js"); ?>"></script>
<script src="<?= webroot("resources/assets/scripts/nui.js"); ?>"></script>
<STYLE>
    .selectedbutton{
        background-color: #4CAF50; /* Green */
    }
</STYLE>
<?php
    $con = connectdb("keywordtest");
    if(!function_exists("firstword")){
        function firstword($Text){
            $Space = strpos($Text, " ");
            if($Space === false){return $Text;}
            return left($Text, $Space);
        }
    }

    //How the search works:
        //there are 2 stages to a search, the first being the MySQL portion
        //then the javascript attempts to take the addons/toppings from the search and assigns them to the results of the first stage

    //Stage 1.0: for text-only searches:
            //a search string is broken up into individual words, and a LIKE comparison is used to return any menu item that has any of the words found in it's text columns

    //Stage 1.0: for keyword searches:
        //this reduces any text search to it's keyword ID numbers
        //first a text search is split up into individual words
        //then each word is searched for in the keywords table
        //if any term has the word in it's synonym list then it's added to a list
        //a keyword has a list of words it will match against, so a search for soda or cola would match against pop, instead of needing multiple terms
        //either the weight5keywords[] if it's weight is 5, or nonweight5keywords[] if it's less
        //this allows multiple keyword searches to be run from a single text search
        //since keywords tend to be relevant to a specific item, this would allow
        //"large pizza" and "cheddar dip" to return the appropriate items at the top of the list without interference
        //However searching for "large pizza and pop" would return "large pizza" and "large pop" as "large" would be relevant to both items
        //The more keywords a menu item has from the search, the higher weight (sum of the weights of all keywords found) it will have
        //meaning the more relevant a menu item is to the search string, the higher weight it will have

    //itemsearch.blade.php will further process the string/keyword IDs it's given to attempt to reduce it to being more relevant to the primary weight-5 keyword it's searching for
        //Stage 1.1: as well as the keywords limited to the area between the primary weight-5 keyword (-5 words, or the previous weight-5 keyword, whichever comes first) and the next weight-5 keyword or the end
        //Stage 1.2: All quantity descriptors after the first one will be stripped from the search
        //Stage 1.3: if a search string seems to be for multiple items (ie: 2 Pizzas), it will be broken apart into multiple strings for the javascript stage 2

    //Stage 2: Javascript
        //the javascript assimilator will attempt to break apart the search string into quantity of items, as well as each addon and their qualifier (if present)
        //clearaddons();
            //resets the addon form so only regular sauce/cheese/etc are selected
        //originalsearchstring = removewords(originalsearchstring);
            //removes useless words from the search (the, an, and, a, etc)
        //var startsearchstring = replacesynonyms(originalsearchstring);
            //attempts to break originalsearchstring down by word, and finds the first word in the synonym list to match it
        //var itemname = get_itemname(ID);
            //gets the menu item name from the table
        //var searchindex = get_quantity(searchstring, itemname); qualifytoppings(searchindex, searchstring, ID);
            //attempts to get the quantity of the menu item, and selects it from the dropdown
        //var toppings = get_toppings(originalsearchstring, searchstring); searchstring = qualifytoppings(toppings, searchstring);
            //attempts to get the addons from the search string, then selects them
        //var typos = get_typos(itemname, originalsearchstring, searchstring); qualifytoppings(typos, cloneData(searchstring));
            //attempts to get the words not found from the previous step, then finds the closest spelled word from the list of addons and selects them
        //return the results for final processing/display

    $wordstoignore = array("the", "with", "and", "times", "on", "an");//discard these words
    $isKeyword = get("searchtype", "Keyword search") == "Keyword search";
    $selectedbutton = ' CLASS="selectedbutton"';
    $keywordclass = iif($isKeyword, $selectedbutton);
    $textclass = iif(!$isKeyword, $selectedbutton);

    if(!isset($_GET["search"]) || !trim($_GET["search"]) || !$isKeyword){
        //blank search, just show the entire menu
        if(!isset($_GET["search"])) {$_GET["search"] = "";}
        $_GET["search"] = preg_replace("/[^A-Za-z0-9 ]/", '', $_GET["search"]);//remove non-alphanumeric
        $results["SortColumn"] = get("SortColumn", "keywords");
        $results["SortDirection"] = get("SortDirection", "DESC");
        $results["words"] = "";
        echo view("popups.itemsearch", array("SortColumn" => $results["SortColumn"], "SortDirection" => $results["SortDirection"], "isKeyword" => $isKeyword, "searchstring" => $_GET["search"], "wordstoignore" => $wordstoignore));
    } else{
        //text found, reduce the search to keyword ID numbers
        $_GET["search"] = preg_replace("/[^A-Za-z0-9 ]/", '', $_GET["search"]);//remove non-alphanumeric
        $results = array("SQL" => array());
        $words = strtolower(str_replace(" ", "|", $_GET["search"]));

        $plurals = explode("|", $words);//automatically check for non-pluralized words
        foreach($plurals as $index => $plural){
            $plural = trim(strtolower($plural));
            if(in_array($plural, $wordstoignore) || !$plural) {
                unset($plurals[$index]);//discard words from $wordstoignore
            } else if (strlen($plural) > 2 && right($plural, 1) == "s"){//if the last letter of the word is an 's', remove it and add the result as a new word to handle plurals
                $plurals[$index] = $plural;
                $plurals[] = left($plural, strlen($plural)-1);
            }
        }
        $words = implode("|", $plurals);

        //search the keywords table for the search string
        $result = Query("SELECT * FROM keywords WHERE synonyms REGEXP '" . $words . "';");
        if($result){
            $results["is5keywords"] = array();
            $results["non5keywords"] = array();
            while ($row = mysqli_fetch_array($result)){
                $word = firstword($row["synonyms"]);
                $results["words"][] = $word;
                $results["keywords"][ $row["id"] ] = array(
                    "word" => $word,
                    "synonyms" => $row["synonyms"],
                    "weight" => $row["weight"],
                    "type" => $row["keywordtype"]
                );
                if($row["weight"] == 5){
                    $results["is5keywords"][] = $row["id"];//is a weight 5 keyword, indicating a new search needs to be run
                } else {
                    $results["non5keywords"][] = $row["id"];//is a weight of less than 5
                }
                $results["SQL"][] = $row["id"];
            }

            $results["keywordids"] = implode(",", $results["SQL"]);
            $results["SortColumn"] = get("SortColumn", "keywords");
            $results["SortDirection"] = get("SortDirection", "DESC");//TESTWEIGHT, CAST(SUM(weight)/count(*) AS UNSIGNED) as

            echo '<DIV CLASS="red"><B>Stage 1.0: Keyword search</B>';
            echo '<BR>Sort by: ' . $results["SortColumn"] . ' ' . $results["SortDirection"];
            echo "<BR>Keywords:";
            $firstresult = true;
            foreach($results["keywords"] as $ID => $keyword){
                $keyword = array_merge(array("id" => $ID), $keyword);
                if(strlen($keyword["synonyms"]) > strlen($keyword["word"])){
                    $keyword["word"] = "<B>" . $keyword["word"] . '</B> <U>' . trim(right($keyword["synonyms"], strlen($keyword["synonyms"]) - strlen($keyword["word"]))) . '</U>';
                } else {
                    $keyword["word"] = "<B>" . $keyword["word"] . '</B>';
                }
                unset($keyword["synonyms"]);
                $keyword["PRI"] = "";
                $keyword["SEC"] = "";
                if($keyword["weight"] == 5){
                    $keyword["PRI"] = "*";
                } else {
                    $keyword["SEC"] = "*";
                }
                printrow($keyword, $firstresult);
            }

            echo '</TABLE></DIV>';

            if(count($results["is5keywords"])){//run a search for each weight-5 keyword, with only 1 weight-5 keyword per search
                foreach($results["is5keywords"] as $primaryKeyID){
                    $keywordids = array_merge(array($primaryKeyID), $results["non5keywords"]);
                    echo view("popups.itemsearch", array("SortColumn" => $results["SortColumn"], "SortDirection" => $results["SortDirection"], "keywordids" => $keywordids, "text" => $_GET["search"], "wordstoignore" => $wordstoignore, "primarykeyid" => $primaryKeyID, "is5keywords" => $results["is5keywords"], "keywords" => $results["keywords"]));
                }
            } else if ($results["non5keywords"]){//no weight-5 keywords found, run a single search of all the keywords
                echo view("popups.itemsearch", array("SortColumn" => $results["SortColumn"], "SortDirection" => $results["SortDirection"], "keywordids" => $results["non5keywords"], "text" => $_GET["search"], "wordstoignore" => $wordstoignore, "keywords" => $results["keywords"]));
            }
        } else {
            die("SQL FAILED! " . $words);//no keywords found in the search
        }
    }

    if(is_array($results["words"])){
        $results["words"] = trim(implode(" ", $results["words"]));
    }
?>
<form method="get" id="formmain">
    <input type="text" id="textsearch" name="search" size=60 value="<?= $_GET["search"]; ?>" TITLE="Leave blank to search for all items">
    <input type="button" id="startspeech" style="display:none;" value="Click to Speak" onclick="startButton(event);" TITLE="Use voice recognition">
    <INPUT TYPE="submit" ID="submit" name="searchtype" value="Keyword search" <?= $keywordclass; ?> TITLE="Search using assigned keywords/synonyms">
    <INPUT TYPE="submit" ID="submit" name="searchtype" value="Text search" <?= $textclass; ?> TITLE="Search using item/category name">

    <!--input type="button" value="In-depth search" ONCLICK="indepth(false);">
    <input type="button" value="In-depth search (keywords only)" ONCLICK="indepth(true);"-->
    <BR>
    Sorting by:
    <?php
        //<INPUT TYPE="text" NAME="SortColumn" readonly VALUE="<?= $results["SortColumn"];">
        //<INPUT TYPE="text" NAME="SortDirection" readonly VALUE="<?= $results["SortDirection"]; ">
        $Columns = array("restaurant_id", "itemprice", "weight", "keywords");
        echo printoptions("SortColumn", $Columns, $results["SortColumn"]);
        echo ' Direction: ' . printoptions("SortDirection", array("ASC", "DESC"), $results["SortDirection"]);
    ?>
    Click a numerical column to sort by it. Click it again to change the sorting direction
</form>
<BR>Simulating menu item order popup<BR>
<DIV ID="thepopup" STYLE="border: 1px solid black;">Click the button in the ID column to assimilate your search string into the menu item. This cannnot be done in SQL as it's too complex, so it must by done in Javascript</DIV>

<script type="text/javascript">
    var keywords = "<?= $results["words"]; ?>";
    var results = <?= json_encode($results); ?>;

    //handles speech recognition
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

    //start speech recognition
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

    //used for searches with multiple items in 1 search
    function runtest2(t){
        var value = t.getAttribute("value");
        var id = t.getAttribute("iid");
        runtest(id, value);
    }

    //show debug info in the box
    function runtest(t, searchtext) {
        if(isNumeric(t)){
            var ID = t;
            t = document.getElementById("assimilate" + t + "-1");
        } else {
            var ID = value(t);
        }
        var toppings = innerHTML("#addons").split("|");
        var keywords = innerHTML("#row" + ID + "-synonyms").replaceAll("[|]", " ");
        var itemname = innerHTML("#row" + ID + "-itemname");
        var select = '<SPAN ID="searchfor"></SPAN><BR>Item Title: <SPAN ID="itemtitle' + ID + '">' + itemname + '</SPAN> (Converts to: ' + replacesynonyms(itemname) + ')<BR>';
        select = select + 'Toppings: <SPAN ID="toppings"></SPAN><BR>';
        select = select + 'Quantity: <SELECT ID="select' + ID + '" CLASS="quantityselect">';

        for (var i = 1; i <= 10; i++) {
            select = select + '<OPTION VALUE="' + i + '">' + i + '</OPTION>';
        }
        select = select + '</SELECT>';

        if(attr(t, "toppings") == 1){show(".addons-toppings");}
        if(attr(t, "wings_sauce") == 1){show(".addons-wings_sauce");}

        innerHTML("#thepopup", '<DIV ID="product-pop-up_' + ID + '"><B>Stage 2:</B><BR>ITEM: ' + select + '</DIV>');
        if(isUndefined(searchtext)){
            var searchtext = value("#textsearch");
        }
        select = assimilate(ID, searchtext);

        if(select) {
            select[1] = get_notfound(select[1], keywords);
            innerHTML("#searchfor", "Searching string: " + select[0] + "<BR>Keywords not found: " + select[1] + " (Words that are <STRIKE>struck out</STRIKE> are not useful)");
        }
        innerHTML("#toppings", getaddons("", true));
    }

    //similar to array.indexOf() but also handles plurals
    function indexOf(Arr, toFind){
        toFind = toFind.toLowerCase();
        if(toFind.right(1) == "s"){
            var value = Arr.indexOf(toFind.left( toFind.length-1));
            if(value > -1){return value;}
        }
        return Arr.indexOf(toFind);
    }

    //gets the keywords that weren't found, and why others were discarded
    function get_notfound(select1, keywords){
        for (i = 0; i < select1.length; i++) {
            if (indexOf(keywords, select1[i]) > -1) {
                select1[i] = strike(select1[i], 'This keyword was used to find the menu item');
            } else if (indexOf(wordstoignore, select1[i]) > -1) {
                select1[i] = strike(select1[i], 'This keyword can not be used to find food and is better off ignored');
            } else if (findsynonym(select1[i], qualifiers)[0] > -1) {
                select1[i] = strike(select1[i], 'Quantity qualifier');
            }
        }
        select1.push(strike(wordstoignore.join(", "), 'Discarded words'));
        return select1.join(", ");
    }

    //returns the text, striked out, with a title="$reason"
    function strike(Text, Reason){
        return '<STRIKE TITLE="' + Reason + '">' + Text + '</STRIKE>';
    }

    //shortcut to console.log
    function log(text){
        console.log(text);
    }

    //event listener to handle column sorting
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

    //checks if a select dropdown has the option/value
    function SelectHasOptionValue(Select, Value){
        return select(Select + " option[value='" + Value + "']").length > 0;
    }
    function SelectHasOptionText(Select, Option){
        return select(Select + " option:contains('" + Option + "')").length > 0;
    }
</script>

@if($results["words"])
    @include("popups.addons", array("table" => "toppings"))
    @include("popups.addons", array("table" => "wings_sauce"))
@endif