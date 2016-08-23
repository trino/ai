<?php
    $quantities = ["next", "first", "second", "third", "fourth", "then", "other"];
    $wordstoignore = array("the", "with", "and", "times", "on", "an");//discard these words
    $Tables = array("toppings", "wings_sauce");
    $WordsBefore = 5;//similar_text
    if(isset($_POST["action"])){
        $con = connectdb("keywordtest");
        if(!function_exists("firstword")){
            function firstword($Text){
                $Space = strpos($Text, " ");
                if($Space === false){return $Text;}
                return left($Text, $Space);
            }

            function getsynonymsandweights($text, $keywords, $removeduplicates = true){
                $newsearch = explode(" ", $text);
                foreach($newsearch as $index => $word){
                    $synonymID = findsynonym($word, $keywords);
                    $newsearch[$index] = array("word" => $word, "synonymid" => $synonymID);
                    if($synonymID > -1 && $index > 0 && $removeduplicates){//remove duplicates as it'll confuse the system
                        for($i = 0; $i < $index; $i++){
                            if( $newsearch[$i]["synonymid"] == $synonymID){
                                $newsearch[$index] = array("word" => "", "synonymid" => -1);
                                $synonymID = false;
                                $i = $index;
                            }
                        }
                    }
                }
                return $newsearch;
            }

            //find the synonym ID of $text
            function findsynonym($text, $synonyms){
                foreach($synonyms as $ID => $synonym){
                    if(containswords($synonym["synonyms"], $text)){return $ID;}
                }
                if(strtolower(right($text,1)) == "s" && strlen($text) > 1){
                    return findsynonym(left($text, strlen($text) -1), $synonyms);
                }
                return -1;
            }

            function countsynonyms($synsandweights, $synonymID){
                $count = array();
                foreach($synsandweights as $ID => $keyword){
                    if($keyword["synonymid"] == $synonymID){
                        $count[] = $ID;
                    }
                }
                return $count;
            }

            //uses containswords() to check for $words, then removes the cells
            function removewords($text, $words){
                if(!is_array($text)){$text = explode(" ", $text);}
                $words = containswords($text, $words);
                foreach($words as $index){
                    unset($text[$index]);
                }
                return implode(" ", $text);
            }

            //gets the words between $leftword and $rightword. if $rightword isn't specified, it gets all words after $leftword
            function getwordsbetween($text, $leftword, $rightword = false){
                if(!is_array($text)){
                    return implode(" ", getwordsbetween(explode(" ", $text), $leftword, $rightword));
                }
                $length = NULL;
                $leftword = $leftword + 1;
                if($rightword){$length = $rightword - $leftword;}
                return array_slice($text, $leftword, $length);
            }

            //gets the last key of an array
            function lastkey($array){
                $keys = array_keys($array);
                return last($keys);
            }

            //a faster replace using the key of data as the text to find, and the value of data as the text to replace it with
            function multireplace($Text, $Data){
                foreach($Data as $Key => $Value){
                    $Text = str_replace($Key, $Value, $Text);
                }
                return $Text;
            }

            function weightstring($newsearch, $keywords, $wordstoignore){
                $text = array();
                foreach($newsearch as $key => $value){
                    if(containswords($wordstoignore, $value["word"])){
                        $text[] = weightstringoneword($keywords, $value["word"], $key, -2, -2, -2);
                    } else if($value["synonymid"] == -1){
                        $text[] = weightstringoneword($keywords, $value["word"], $key, -1, -1, -1);
                    } else {
                        $text[] = weightstringoneword($keywords, $value["word"], $key, $value["synonymid"], $keywords[$value["synonymid"]]["weight"], $keywords[$value["synonymid"]]["type"]);
                    }
                }
                return implode(" ", $text);
            }

            function weightstringoneword($keywords, $Word, $ID, $SynonymID, $Weight, $Type){
                $TypeString = "Unknown";
                if($Weight == 5){$Type=-5;}
                $BGColor = false;
                $TextColor = false;
                $Style = "";
                switch($Type){
                    case -5:    $TypeString="Primary"; $Word = "<B>" . $Word . "</B>"; $BGColor = "yellow"; break;
                    case -2:    $TypeString="Ignore"; $Word = "<STRIKE>" . $Word . "</STRIKE>"; $BGColor = "grey"; $TextColor = "White"; break;
                    case -1:    $TypeString="Add-on"; $BGColor = "green"; break;
                    case 1:     $TypeString="Quantity"; $BGColor = "magenta"; break;
                    case 2:     $TypeString="Size"; $BGColor = "red"; break;
                }
                if($BGColor){$Style = "background-color: " . $BGColor . ";";}
                if($TextColor){$Style .= "color: " . $TextColor . ";";}
                if($SynonymID > -1){$SynonymID .= " (" . $keywords[$SynonymID]["word"] . ")";}
                return '<SPAN STYLE="' . $Style . '" TITLE="Index:' . $ID . '|Synonym ID:' . $SynonymID . '|Weight:' . $Weight . '|Type:' . $Type . " (" . $TypeString . ')">' . $Word . "</SPAN>";
            }

            function reassemble($newsearch, $startingIndex = 0, $endingIndex = false){
                $keywordids = array();
                if($endingIndex){
                    for($index = $startingIndex; $index <= $endingIndex; $index++){
                        $keywordids[] = $newsearch[$index];
                    }
                } else {
                    foreach($newsearch as $index => $value){
                        $keywordids[] = $value;
                    }
                }
                return $keywordids;
            }

            function reassemble_text($newsearch, $keywords, $startingIndex = 0, $endingIndex = false){
                $text = array();
                if($endingIndex){
                    for($index = $startingIndex; $index <= $endingIndex; $index++){
                        $text[] = reassemble_textID($newsearch, $keywords, $index);
                    }
                } else {
                    foreach($newsearch as $index => $value){
                        $text[] = reassemble_textID($newsearch, $keywords, $index);
                    }
                }
                return implode(" ", $text);
            }
            function reassemble_textID($newsearch, $keywords, $index){
                $ID = $newsearch[$index]["synonymid"];
                if($ID == -1){
                    return $newsearch[$index]["word"];
                } else {
                    return $keywords[$ID]["word"];
                }
            }

            function reassemble_keywordIDs($newsearch, $startingIndex = 0, $endingIndex = false){
                $keywordids = array();
                if($endingIndex){
                    for($index = $startingIndex; $index <= $endingIndex; $index++){
                        $ID = $newsearch[$index]["synonymid"];
                        if($ID > -1){$keywordids[] = $ID;}
                    }
                } else {
                    foreach($newsearch as $index => $value){
                        $ID = $value["synonymid"];
                        if($ID > -1){$keywordids[] = $ID;}
                    }
                }
                return implode(",", $keywordids);
            }

            function getstartandend($newsearch, $keywords, $primarykeyid, &$startingIndex, &$endingIndex, $realstartingindex=-1){
                $endingIndex = -1;
                $startingIndex = -1;
                foreach($newsearch as $index => $word){
                    if($index >= $realstartingindex){
                        $synonymID = $word["synonymid"];
                        if($synonymID > -1){$synonym = $keywords[$synonymID];}
                        if($synonymID){
                            if ($synonymID == $primarykeyid && $startingIndex == -1){
                                $startingIndex = $index;
                            } else if (($startingIndex > -1) && ($endingIndex == -1) && ($synonymID > -1) && ($synonym["weight"] == 5)){
                                $endingIndex = $index - 1;
                            }
                        }
                    }
                }
                if($endingIndex == -1){$endingIndex = lastkey($newsearch);}
                return array($startingIndex, $endingIndex);
            }

            function finditemforquantity($keywords, $newsearch, $quantityindex){
                foreach($newsearch as $index => $keyword){
                    if($index > $quantityindex){
                        if($keyword["synonymid"] >-1){
                            $synonym = $keywords[ $keyword["synonymid"] ];
                            if( $synonym["weight"] == 5 ){
                                return $index;
                            } else if ( $synonym["type"] == 1 ){
                                return false;
                            }
                        }
                    }
                }
                return false;
            }

            //will put it BEFORE $spot, so that $spot will become $item, and old $spot=$spot++
            function array_inject(&$arr, $spot, $item){
                array_splice($arr, $spot, 0, "DUMMY");
                $arr[$spot] = $item;
            }

            function findpreviouskeyword($newsearch, $keywords, $StartingIndex){
                for($index = $StartingIndex-1; $index>-1; $index--){
                    if($newsearch[$index]["synonymid"] > -1){
                        $synonym = $keywords[$newsearch[$index]["synonymid"]];
                        if($synonym["type"] == 1 || $synonym["type"] == 2){
                            return $index;
                        }
                    }
                }
                return $StartingIndex;
            }
        }



        switch(strtolower(trim($_POST["action"]))){
            case "keywordsearch":
                $results = array("status" => false, "stages" => array($_POST["search"]), "searches" => array());
                $one = select_field_where("keywords", "keywordtype = 1 AND synonyms LIKE '%1%'");
                $_POST["search"] = filterduplicates(filternonalphanumeric($_POST["search"]));//remove non-alphanumeric and double-spaces
                $words = explode(" ", $_POST["search"]);
                foreach($words as $ID => $word){
                    $word = normalizetext($word);
                    if($word == "1" || $word == "one"){
                        $firstword="";
                        $lastword="";
                        if($ID < lastkey($words)){$lastword = $words[$ID+1];}
                        if($ID > 0){$firstword = $words[$ID-1];}
                        $DOIT = count(containswords(array($firstword, $lastword), $quantities)) > 0;
                        if(!$DOIT){if(count(containswords(array($firstword, $lastword), array("with")))){$DOIT = 2;}}
                        //echo '<BR>1/one found at index: ' . $ID . " PREV: '" . $firstword . "' NEXT: '" . $lastword . "' DOIT: " . $DOIT;
                        if($DOIT == 1){$words[$ID] = "";} else if ($DOIT == 2){$words[$ID] = $quantities[0];}
                    }
                }
                $_POST["search"] = filterduplicates(implode(" ", $words));
                $results["stages"][] = $_POST["search"];

                $words = strtolower(str_replace(" ", "|", $_POST["search"]));
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

                $results["stages"]["plurals"] = implode(" ", $plurals);

                //HCSC
                $primarysynoynms = array(
                        "wing" => array(
                                "words" => array("chicken", array("pound", "lbl", "lb")),//sub-array acts as an OR
                                "normalizationmode" => 8,//numbers removed
                                "all" => true
                        ),
                        "drink" => array(
                                "words" => array("pepsi", "cola", "coke")//should contain entire list of drink names...
                        )
                );
                foreach($primarysynoynms as $primarykeyword => $parameters){
                    $all = get("all", false, $parameters);
                    $normalizationmode = get("normalizationmode", 0, $parameters);
                    $delimiter = get("delimiter", " ", $parameters);
                    $synonyms = $parameters["words"];
                    $containswords = containswords($plurals, $synonyms, $all, $delimiter, $normalizationmode);
                    if(is_array($containswords)){$containswords = count($containswords);}
                    if($containswords){
                        if(!containswords($plurals, $primarykeyword)){
                            $plurals[] = $primarykeyword;
                        }
                    }
                }

                $results["stages"]["final"] = implode(" ", $plurals);
                $words = implode("|", $plurals);
                
                $result = Query("SELECT * FROM keywords WHERE synonyms REGEXP '" . $words . "';");
                if($result){
                    $results["status"] = true;
                    $results["is5keywords"] = array();
                    $results["non5keywords"] = array();
                    while ($row = mysqli_fetch_array($result)){
                        $word = firstword($row["synonyms"]);
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
                        $results["keywordids"][] = $row["id"];
                    }
                    $results["keywordids"] = implode(",", $results["keywordids"]);
                    $results["SortColumn"] = get("SortColumn", "keywords");
                    $results["SortDirection"] = get("SortDirection", "DESC");
                    $results["limit"] = get("limit", "5");

                    if(count($results["is5keywords"])){//run a search for each weight-5 keyword, with only 1 weight-5 keyword per search
                        $newsearch = getsynonymsandweights($_POST["search"], $results["keywords"], false);
                        foreach($results["is5keywords"] as $primaryKeyID){
                            $keywordids = array_merge(array($primaryKeyID), $results["non5keywords"]);
                            $indexes = countsynonyms($newsearch, $primaryKeyID);
                            if(count($indexes) > 1){
                                $text = weightstring($newsearch, $results["keywords"], $wordstoignore);
                                foreach($indexes as $index){
                                    getstartandend($newsearch, $results["keywords"], $primaryKeyID, $startingIndex, $endingIndex, $index);
                                    $startingIndex = findpreviouskeyword($newsearch, $results["keywords"], $startingIndex);
                                    if($endingIndex<lastkey($newsearch)){
                                        $endingIndex = findpreviouskeyword($newsearch, $results["keywords"], $endingIndex);
                                    }
                                    $newtext = reassemble_text($newsearch, $results["keywords"], $startingIndex, $endingIndex);
                                    $keywordids = reassemble_keywordIDs($newsearch, $startingIndex, $endingIndex);

                                    $results["searches"][] = array(
                                            "stage" => "1.4",
                                            "text" => $newtext,
                                            "keywordids" => $keywordids,
                                            "primarykeyid" => $primaryKeyID,
                                            "startingIndex" => $startingIndex,
                                            "endingIndex" => $endingIndex
                                    );
                                }
                            } else {
                                $results["searches"][] = array(
                                        "stage" => "1.w5",
                                        "text" => $_POST["search"],
                                        "keywordids" => $keywordids,
                                        "primarykeyid" => $primaryKeyID,
                                );
                            }
                        }
                    } else if ($results["non5keywords"]){//no weight-5 keywords found, run a single search of all the keywords
                        $results["searches"][] = array(
                                "stage" => "1.w0",
                                "text" => $_POST["search"],
                                "keywordids" => $results["non5keywords"],
                        );
                    }
                }

                foreach($results["searches"] as $SearchID => $VALUE){
                    $keywordids = $VALUE["keywordids"];
                    $text = $VALUE["text"];
                    unset($primarykeyid);
                    if(isset($VALUE["primarykeyid"])){$primarykeyid = $VALUE["primarykeyid"];}
                    if(count($results["is5keywords"]) > 1){//is part of a multiple item search
                        $newsearch = getsynonymsandweights($text, $results["keywords"], false);
                        getstartandend($newsearch, $results["keywords"], $primarykeyid, $startingIndex, $endingIndex);
                        if($startingIndex == -1){
                            foreach($newsearch as $ID => $searchterm){
                                if($searchterm["synonymid"] > -1){
                                    $keyword = $results["keywords"][ $searchterm["synonymid"] ];
                                    if($keyword["type"] == 1){
                                        $keyword["goeswith"] = finditemforquantity($results["keywords"], $newsearch, $ID);
                                        if(!$keyword["goeswith"]){
                                            array_inject($newsearch, $ID+1, array(
                                                    "word" => $results["keywords"][$primarykeyid]["word"],
                                                    "synonymid" => $primarykeyid
                                            ));
                                            $text = weightstring($newsearch, $results["keywords"], $wordstoignore);
                                            getstartandend($newsearch, $results["keywords"], $primarykeyid, $startingIndex, $endingIndex);
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                        if($endingIndex == -1){$endingIndex = lastkey($newsearch);}

                        //look X words before the desired weight-5 keyword, stopping at the previous weight-5 keyword
                        for($index = 1; $index < $WordsBefore; $index++){
                            $newindex = $startingIndex - $index;
                            if($newindex > -1){
                                if($newsearch[$newindex]["synonymid"] > -1){
                                    $synonym = $results["keywords"][$newsearch[$newindex]["synonymid"]];
                                    if($synonym["weight"] == 5){
                                        $WordsBefore = $index-1;
                                    }
                                }
                            }
                        }

                        $startingIndex = max($startingIndex - $WordsBefore, 0);
                        $results["searches"][$SearchID]["stage"] .= " 1.1";
                        $text = reassemble_text($newsearch, $results["keywords"], $startingIndex, $endingIndex);
                        $keywordids = reassemble_keywordIDs($newsearch, $startingIndex, $endingIndex);
                    }

                    $newsearch = getsynonymsandweights($text, $results["keywords"], false);
                    $startremoving = false;
                    $hasremoved = false;
                    $quantityID = false;
                    foreach($newsearch as $ID => $value){
                        $synonymID = $value["synonymid"];
                        if($synonymID > -1){
                            if($results["keywords"][$synonymID]["type"] == 1){//is a quantity
                                if($startremoving){
                                    unset($newsearch[$ID]);
                                    $hasremoved = true;
                                }
                                $startremoving = true;
                                $quantityID = $synonymID;
                            }
                        }
                    }
                    if($hasremoved){
                        $results["searches"][$SearchID]["stage"] .= " 1.2";
                        $text = reassemble_text($newsearch, $results["keywords"]);
                        $keywordids = reassemble_keywordIDs($newsearch);
                    } else if(!$startremoving){
                        $results["searches"][$SearchID]["stage"] .= " 1.2.1";
                        $results["keywords"][$one["id"]] = array("word" => "1", "synonyms" => $one["synonyms"], "weight" => $one["weight"], "type" => $one["keywordtype"]);
                        $keywordids = addtodelstring($keywordids, $one["id"]);
                    }

                    $results["searches"][$SearchID]["text"] = $text;
                    $results["searches"][$SearchID]["keywordids"] = $keywordids;

                    $quantity = containswords($text, $quantities);//check if the search contains multiple items, instead of just one

                    $itemlist = array();
                    if($quantity){//Stage 1.3: split the search up into it's individual items
                        $lastkey = lastkey($quantity);
                        foreach($quantity as $i => $word){
                            $rightword=false;
                            if($i != $lastkey){
                                $rightword = $quantity[$i+1];//if it's not the last key, then get the next one
                            }
                            $resulttext = trim(getwordsbetween($text, $quantity[$i], $rightword));
                            $resulttext = removewords($resulttext, $wordstoignore);
                            $itemlist[] = $resulttext;
                        }
                        $results["searches"][$SearchID]["stage"] .= " 1.3";
                    } else {
                        //$itemlist[] = trim($text);
                    }
                    $results["searches"][$SearchID]["items"] = $itemlist;

                    $SQL = "SELECT *,
              count(DISTINCT keyword_id) as keywords,
              SUM(weight) as weight,
              GROUP_CONCAT(synonyms SEPARATOR '|') as synonyms,
              GROUP_CONCAT(wordid SEPARATOR '|') as keywordids,
              GROUP_CONCAT(weight SEPARATOR '|') as weights,
              GROUP_CONCAT(keywordtype SEPARATOR '|') as types
              FROM (
                  SELECT menu.*, menu.id AS menuid, menu.item as itemname, menu.price as itemprice, keywords.id as wordid, keywords.keywordtype as keywordtype, menukeywords.id as mkid, menuitem_id, keyword_id, synonyms, weight
                  FROM menu, menukeywords, keywords
                  HAVING (menuid=menuitem_id OR -menu.category_id = menuitem_id)
                  AND keyword_id = wordid
                  ";

                    if(is_array($keywordids)){$keywordids = implode(",", $keywordids);}
                    $SQL .= "AND keyword_id IN (" . $keywordids . ")";//only return results from the keyword search
                    $SQL .= ") results GROUP BY menuid ORDER BY " . $results["SortColumn"] . " " . $results["SortDirection"] . " LIMIT " . $results["limit"];

                    $SQLresults = Query($SQL, true);
                    foreach($SQLresults as $menuitemID => $row){
                        $row["id"] = $row["menuid"];
                        $row["price"] = number_format($row["price"], 2);

                        //get quantity of item
                        $itemtype = 0;
                        $quantityID = false;
                        $keywordIDs = explode("|", $row["keywordids"]);
                        $thesekeywords = array_combine ( $keywordIDs , explode("|", $row["types"]) );
                        $thesekeywordw = array_combine ( $keywordIDs , explode("|", $row["weights"]) );
                        foreach($thesekeywords as $ID => $type){
                            $weight = $thesekeywordw[$ID];
                            if($type == 1){//quantity found
                                $quantityID = $ID;
                                $row["quantity"] = filternonnumeric(firstword($results["keywords"][$ID]["synonyms"]));
                            } else if($weight == 5){
                                $itemtype = $ID;
                            }
                        }
                        if(!$quantityID){$row["quantity"] = firstword($one["synonyms"]);}
                        foreach(array("synonyms", "keywordids", "weights", "types") as $column){
                            $row[$column] = explode("|", $row[$column]);
                        }

                        $row = removekeys($row, array("itemname", "menuid", "itemprice", "wordid", "keywordtype", "mkid", "menuitem_id", "keyword_id"));//just to clean up the results
                        unset($row["menuid"]);

                        $results["searches"][$SearchID]["menuitems"][] = $row;
                    }
                }

                echo json_encode($results, JSON_PRETTY_PRINT);
                break;
        }
    } else {
        if(!isset($_POST["search"])){$_POST["search"]="";}
?>
    <script src="<?= webroot("resources/assets/scripts/api.js"); ?>"></script>
    <script src="<?= webroot("resources/assets/scripts/nui.js"); ?>"></script>
    <script src="<?= webroot("resources/assets/scripts/receipt.js"); ?>"></script>
    <STYLE>
        .blue{
            border: 2px solid blue;margin-left: 5px;margin-bottom: 5px;margin-right: 5px;margin-top: 5px;
        }
        .red{
            border: 1px solid red;
            margin-top: 10px;
        }

        .plus{
            float: right;
        }
    </STYLE>
    <DIV id="formmain" class="red">
        <input type="text" id="textsearch" name="search" style="width:100%" oninput="submitform();" onKeyUp="handlebutton(event);" value="<?= $_POST["search"]; ?>" TITLE="Press 'Space' or 'Enter' to search">
        <input type="button" id="startspeech" style="display:none;" value="Click to Speak" onclick="startButton(event);" TITLE="Use voice recognition">
        <BR>
        Sorting by:
        <?php
            $Columns = array("restaurant_id", "itemprice", "weight", "keywords");
            echo printoptions("SortColumn", $Columns, "weight");
            echo ' Direction: ' . printoptions("SortDirection", array("ASC", "DESC"), "DESC");
        ?>
        Click a numerical column to sort by it. Click it again to change the sorting direction
    </DIV>
    <DIV ID="questions" class="blue"></DIV>
    <DIV ID="searchresults" CLASS="red"></DIV>
    <SCRIPT>
        var currentURL = "<?= Request::url(); ?>";
        var token = "<?= csrf_token(); ?>";
        var wordstoignore = <?= json_encode($wordstoignore) ?>;
        var quantities = <?= json_encode($quantities) ?>;

        function handlebutton(evt){
            var charCode = (evt.which) ? evt.which : event.keyCode;
            if(charCode == 32 || charCode == 13){submitform();}//32 is space, 13 is enter
        }

        function submitform(){
            post(currentURL, {
                action: "keywordsearch",
                search: value("#textsearch"),
                SortColumn: value("#SortColumn"),
                SortDirection: value("#SortDirection"),
                limit: 5,
                _token: token
            }, function(result, success){
                var data = JSON.parse(result);
                var HTML = "TIME STAMP: " + Date.now(true) + "<BR>";

                if( data.is5keywords.length == 0 ){
                    HTML += "No weight 5 keywords found. Search for something like 'pizza' or 'wings'<BR>";
                } else {
                    for( var i = 0; i < data.searches.length; i++ ){
                        var currentsearch = data.searches[i];
                        if(!isUndefined( currentsearch.primarykeyid )){
                            HTML += "Item found: " + data.keywords[currentsearch.primarykeyid].word + "<BR>";
                            if( currentsearch.items.length == 0) {
                                HTML += "No addons found. Use these words to split up a list of addons: '" + quantities.join(", ") + "'<BR>";
                            } else {
                                for (var v = 0; v < currentsearch.items.length; v++) {
                                    HTML += "Addons for sub-item " + (v + 1) + ": " + currentsearch.items[v] + "<BR>";
                                }
                            }
                        }
                    }
                }

                innerHTML("#searchresults", HTML + "<pre>" + result + "</pre>");
            });
        }

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
                submitform();
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
    </SCRIPT>
<?php } ?>
