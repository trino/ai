<script src="<?= webroot("resources/assets/scripts/api.js"); ?>"></script>
<script src="<?= webroot("resources/assets/scripts/nui.js"); ?>"></script>
<STYLE>
    .selectedbutton{
        background-color: #4CAF50; /* Green */
    }
</STYLE>
<?php
    $con = connectdb("keywordtest");

    function firstword($Text){
        $Space = strpos($Text, " ");
        if($Space === false){return $Text;}
        return left($Text, $Space);
    }

    $isKeyword = get("searchtype", "Keyword search") == "Keyword search";
    $selectedbutton = ' CLASS="selectedbutton"';
    $keywordclass = iif($isKeyword, $selectedbutton);
    $textclass = iif(!$isKeyword, $selectedbutton);

    if(!isset($_GET["search"]) || !trim($_GET["search"]) || !$isKeyword){
        if(!isset($_GET["search"])) {$_GET["search"] = "";}
        $results["SortColumn"] = get("SortColumn", "keywords");
        $results["SortDirection"] = get("SortDirection", "DESC");
        $results["words"] = "";
        echo view("popups.itemsearch", array("SortColumn" => $results["SortColumn"], "SortDirection" => $results["SortDirection"], "isKeyword" => $isKeyword, "searchstring" => $_GET["search"]));
    } else{

        $results = array("SQL" => array());
        $words = strtolower(str_replace(" ", "|", $_GET["search"]));

        $plurals = explode("|", $words);//automatically check for non-pluralized words
        $wordstoignore = array("the", "with", "and", "times", "on", "an");
        foreach($plurals as $index => $plural){
            $plural = trim(strtolower($plural));
            $plurals[$index] = $plural;
            if(in_array($plural, $wordstoignore)) {
                unset($plurals[$index]);
            } else if (strlen($plural) > 2 && right($plural, 1) == "s"){
                $plurals[] = left($plural, strlen($plural)-1);
            }
        }
        $words = implode("|", $plurals);

        $result = Query("SELECT * FROM keywords WHERE synonyms REGEXP '" . $words . "';");
        if($result){
            $results["is5keywords"] = array();
            $results["non5keywords"] = array();
            while ($row = mysqli_fetch_array($result)){
                $word = firstword($row["synonyms"]);
                $results["words"][] = $word;
                $results["keywords"][ $row["id"] ] = array(
                    "word" => $word,
                    "weight" => $row["weight"]
                );
                if($row["weight"] == 5){
                    $results["is5keywords"][] = $row["id"];
                } else {
                    $results["non5keywords"][] = $row["id"];
                }
                $results["SQL"][] = $row["id"];
            }

            $results["keywordids"] = implode(",", $results["SQL"]);
            $results["SortColumn"] = get("SortColumn", "keywords");
            $results["SortDirection"] = get("SortDirection", "DESC");//TESTWEIGHT, CAST(SUM(weight)/count(*) AS UNSIGNED) as

            var_dump($results);

            if(count($results["is5keywords"])){
                foreach($results["is5keywords"] as $primaryKeyID){
                    $keywordids = array_merge(array($primaryKeyID), $results["non5keywords"]);
                    echo view("popups.itemsearch", array("SortColumn" => $results["SortColumn"], "SortDirection" => $results["SortDirection"], "keywordids" => $keywordids));
                }
            } else if ($results["non5keywords"]){
                echo view("popups.itemsearch", array("SortColumn" => $results["SortColumn"], "SortDirection" => $results["SortDirection"], "keywordids" => $results["non5keywords"]));
            }
        } else {
            die("SQL FAILED! " . $words);
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
        var ID = value(t);
        var toppings = innerHTML("#addons").split("|");
        var keywords = innerHTML("#row" + ID + "-synonyms").replaceAll("[|]", " ");
        var itemname = innerHTML("#row" + ID + "-itemname");
        var select = '<SPAN ID="searchfor"></SPAN><BR>Item Title: <SPAN ID="itemtitle' + ID + '">' + itemname + '</SPAN> (Converts to: ' + replacesynonyms(itemname) + ')<BR>';
        select = select + 'Toppings: <SPAN ID="toppings"></SPAN><BR>';
        select = select + 'Quantity: <SELECT ID="select' + ID + '">';
        for (var i = 1; i <= 10; i++) {
            select = select + '<OPTION VALUE="' + i + '">' + i + '</OPTION>';
        }
        select = select + '</SELECT>';

        if(attr(t, "toppings") == 1){show(".addons-toppings");}
        if(attr(t, "wings_sauce") == 1){show(".addons-wings_sauce");}

        /*
         select = select + '<TABLE><TR>';
        var col = 0
        for (var i = 0; i < toppings.length; i++) {
            toppings[i] = toppings[i].replaceAll("Jalape?o", "Jalapeno");
            var toppingID = 'topping' + ID + '-' + i;
            select = select + '<TD WIDTH="20%"><LABEL FOR="' + toppingID + '"><INPUT TYPE="CHECKBOX" ID="' + toppingID + '"><SPAN CLASS="ver" ID="ver' + toppingID + '">' + toppings[i] + '</SPAN></LABEL></TD>';
            col++;
            if (col == 5) {
                select = select + '</TR><TR>';
                col = 0;
            }
        }
         select = select + "</TABLE>";
        */

        innerHTML("#thepopup", '<DIV ID="product-pop-up_' + ID + '">' + select + '</DIV>');

        select = assimilate(ID);

        for(i=0; i< select[1].length; i++){
            if(keywords.indexOf(select[1][i]) > -1 ){
                select[1][i] = strike(select[1][i], 'This keyword was used to find the menu item');
            } else if( wordstoignore.indexOf(select[1][i]) > -1 ){
                select[1][i] = strike(select[1][i], 'This keyword can not be used to find food and is better off ignored');
            } else if( findsynonym(select[1][i], qualifiers)[0] >-1 ) {
                select[1][i] = strike(select[1][i], 'Quantity qualifier');
            }
        }
        select[1].push( strike(wordstoignore.join(", "), 'Discarded words') );
        log(select[1].join(", "));

        innerHTML("#searchfor", "Searching string: " + select[0] + "<BR>Keywords not found: " + select[1].join(", ") + " (Words that are <STRIKE>struck out</STRIKE> are not useful)");
        innerHTML("#toppings", getaddons("", true));
    }

    function strike(Text, Reason){
        return '<STRIKE TITLE="' + Reason + '">' + Text + '</STRIKE>';
    }

    function log(text){
        console.log(text);
    }

    function indepth(usekeywords){
        if(usekeywords){
            usekeywords = keywords;
        } else {
            usekeywords = value("#textsearch");
        }
        window.location = "<?= webroot(); ?>public/index3.php?search=" + usekeywords;
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