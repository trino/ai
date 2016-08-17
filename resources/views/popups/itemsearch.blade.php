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
<DIV CLASS="red">
<?php
    //this is the item search engine, works by either text, or keyword IDs
    if(isset($keywordids) && is_array($keywordids)){$keywordids = implode(",", $keywordids);}
    if(!isset($isKeyword)){$isKeyword = true;}
    $quantities = ["next", "first", "second", "third", "fourth", "then", "other"];
    //if(!isset($wordstoignore)) {$wordstoignore = ["the", "with", "and", "times", "on", "one"];}//use copy from keyword.blade instead

    if(!function_exists("containswords")){
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

        //explodes $text by space, checks if the cells contain $words and returns the indexes
        function containswords($text, $words){
            $ret = array();
            if(!is_array($text)){$text = explode(" ", $text);}
            if(!is_array($words)){$words = array(normalizetext($words));} else {$words = normalizetext($words);}
            foreach($text as $index => $text_word){
                if(in_array(normalizetext($text_word), $words)){
                    $ret[] = $index;
                }
            }
            return $ret;
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

        //lowercase and trim text for == comparison
        function normalizetext($text){
            if(is_array($text)){
                foreach($text as $index => $word){
                    $text[$index] = normalizetext($word);
                }
                return $text;
            }
            return strtolower(trim($text));
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

        function getsynonymsandweights($text, $keywords){
            $newsearch = explode(" ", $text);
            foreach($newsearch as $index => $word){
                $synonymID = findsynonym($word, $keywords);
                $newsearch[$index] = array("word" => $word, "synonymid" => $synonymID);
                if($synonymID > -1 && $index > 0){//remove duplicates as it'll confuse the system
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
                    //echo "<BR>" . $index . " (" . $value["synonymid"] . " = " . $value["word"] . ") BECOMES " . reassemble_textID($newsearch, $keywords, $index);
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
    }

    $reduced = false;
    if($isKeyword && isset($is5keywords) && count($is5keywords) > 1){//is part of a multiple item search
        echo '<DIV CLASS="blue">';
        $WordsBefore = 5;//similar_text
        //reduce the $text to X words before the primary keyword, till the next primary keyword or end of the string
        //also reprocess the keywords to only get the keyword IDs between those 2 points as well, making the toppings for example, wings more specific to wings

        echo "<B>Stage 1.1: Multiple item search detected. Collapsing search to a smaller area</B>";
        echo "<BR>Primary keyword: " . $primarykeyid . " (" . $keywords[$primarykeyid]["word"] . ")" ;//ID of the primary search key
        echo "<BR>Weight-5 keyword IDs: " . implode(", ", $is5keywords);
        echo "<BR>(BEFORE) All keywords: " . $keywordids;

        $startingIndex = -1;
        $endingIndex = -1;
        $newsearch = getsynonymsandweights($text, $keywords);
        foreach($newsearch as $index => $word){
            $synonymID = $word["synonymid"];
            if($synonymID > -1){$synonym = $keywords[$synonymID];}
            if($synonymID){
                if ($synonymID == $primarykeyid){
                    $startingIndex = $index;
                    echo "<BR>Found primary search key at: " . $index;
                } else if (($startingIndex > -1) && ($endingIndex == -1) && ($synonymID > -1) && ($synonym["weight"] == 5)){

                    $endingIndex = $index - 1;
                    echo "<BR>Found ending at: " . $endingIndex;
                }
            }
        }
        if($endingIndex == -1){$endingIndex = lastkey($newsearch);}

        //look X words before the desired weight-5 keyword, stopping at the previous weight-5 keyword
        for($index = 1; $index < $WordsBefore; $index++){
            $newindex = $startingIndex - $index;
            if($newindex > -1){
                if($newsearch[$newindex]["synonymid"] > -1){
                    $synonym = $keywords[$newsearch[$newindex]["synonymid"]];
                    if($synonym["weight"] == 5){
                        $WordsBefore = $index-1;
                    }
                }
            }
        }

        $text = weightstring($newsearch, $keywords, $wordstoignore);
        echo "<BR>(BEFORE) Search string: " . $text;
        echo "<BR>Get Words and keywordIDs from " . $startingIndex . "(-" . $WordsBefore . ") to " . $endingIndex;
        $startingIndex = max($startingIndex - $WordsBefore, 0);
        $text = reassemble_text($newsearch, $keywords, $startingIndex, $endingIndex);
        $keywordids = reassemble_keywordIDs($newsearch, $startingIndex, $endingIndex);
        $text2 = weightstring(reassemble($newsearch, $startingIndex, $endingIndex), $keywords, $wordstoignore);
        echo "<BR>(AFTER) Search string: " . $text2;
        echo "<BR>(AFTER) All keywords: " . $keywordids;
        echo '</DIV>';
        $reduced = true;
    }

    if($isKeyword){
        $newsearch = getsynonymsandweights($text, $keywords);
        $startremoving = false;
        $hasremoved = false;
        $quantityID = false;
        foreach($newsearch as $ID => $value){
            $synonymID = $value["synonymid"];
            if($synonymID > -1){
                if($keywords[$synonymID]["type"] == 1){//is a quantity
                    if($startremoving){
                        if(!$hasremoved){
                            echo '<DIV CLASS="blue"><B>Stage 1.2: Multiple quantity keywords found. Removing all but the first one from the keyword search</B>';
                            $text = weightstring($newsearch, $keywords, $wordstoignore);
                            echo "<BR>(BEFORE) Search string: " . $text;
                        }
                        unset($newsearch[$ID]);
                        $hasremoved = true;
                    }
                    $startremoving = true;
                    $quantityID = $synonymID;
                }
            }
        }
        if($hasremoved){
            echo "<BR>(BEFORE) All keywords: " . $keywordids;
            $text = weightstring($newsearch, $keywords, $wordstoignore);
            echo "<BR>(AFTER) Search string: " . $text;
            $text = reassemble_text($newsearch, $keywords);
            $keywordids = reassemble_keywordIDs($newsearch);
            echo "<BR>(AFTER) All keywords: " . $keywordids;
            echo '</DIV>';
        } else if(!$startremoving){
            echo '<DIV CLASS="blue">Quantity not found, assuming 1<BR>';
            $quantityID = select_field_where("keywords", "keywordtype = 1 AND synonyms LIKE '%1%'");
            $keywords[$quantityID["id"]] = array("word" => "1", "synonyms" => $quantityID["synonyms"], "weight" => $quantityID["weight"], "type" => $quantityID["keywordtype"]);
            $quantityID = $quantityID["id"];
            $keywordids = addtodelstring($keywordids, $quantityID);
            echo $quantityID . " = " . print_r($keywords[$quantityID], true);
            echo '</DIV>';
        }
    }

    if(isset($searchstring) && !$isKeyword){//search by text

        $searchstring = explode(" ", $searchstring);
        $weights = array();

        foreach($searchstring as $word){
            $word = strtolower($word);
            if (right($word,1) == "s" && strlen($word) > 2){//handle pluralization by removing the 's', and adding the 's'-less word as a new word
                $searchstring[] = left($word, strlen($word)-1);
                $weights[] = "1";
            }
            $weights[] = "1";
        }

        $weights = implode("|", $weights);//search for text that has any of the words from $searchstring
        $searchcol = "{col} LIKE '%" . implode("%' OR {col} LIKE '%", $searchstring) . "%'";//use {col} so the same string can be used for multiple columns by replacing {col} with it

        $SQL = "SELECT *, 1 as keywords, 1 as weight, '" . implode("|", $searchstring) . "' as synonyms, '" . $weights . "' as weights, menu.*, menu.id AS menuid, menu.item as itemname, menu.price as                      itemprice FROM menu WHERE " . str_replace("{col}", "item", $searchcol) . " OR " . str_replace("{col}", "category", $searchcol);
    } else {//search by keywords
        //join the menu with mnukeywords on menu.id=mnukeywords.menuitem_id (assigned to the item) or -menu.category_id = mnukeywords.menuitem_id (assigned to the category), then
        //join that with keywords on mnukeywords.keyword_id = keywords.id
        //concatenate the synonyms and weights to give a '|' delimited list of both, sum the weights to get the total weight of the menu item compared to the search
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

        if (isset($keywordids)){
            $SQL .= "AND keyword_id IN (" . $keywordids . ")";//only return results from the keyword search
        }
        $SQL .= ") results GROUP BY menuid";
    }
    $SQL .= " ORDER BY " . $SortColumn . " " . $SortDirection . " LIMIT 5   ";


    $result = Query($SQL);

    if($result) {
        $FirstResult = true;
        if(!isset($text)){$text="";}
        $quantity = containswords($text, $quantities);//check if the search contains multiple items, instead of just one
        $buttontext= "";
        $Tables = array("toppings", "wings_sauce");
        $tabletext="";
        foreach($Tables as $Table){
            $tabletext .= " " . $Table . '="[' . $Table . ']"';
        }

        $buttonstarttext = ' <BUTTON ID="assimilate[rowid]-[itemid]" CLASS="assimilate assimilate[rowid]" IID="[rowid]" TITLE="[title]" ONCLICK="[script]" VALUE="[text]" [tabletext] [style]>Item: [itemid]</BUTTON>';//base string, replace [text] later on

        $itemlist = array();
        if($quantity){//Stage 1.3: split the search up into it's individual items
            $lastkey = lastkey($quantity);
            $buttonstarttext = multireplace($buttonstarttext, array("[script]" => 'runtest2(this);', "[style]" => "", "[title]" => "Assimilate multiple"));
            foreach($quantity as $i => $word){
                $rightword=false;
                if($i != $lastkey){
                    $rightword = $quantity[$i+1];//if it's not the last key, then get the next one
                }
                $resulttext = trim(getwordsbetween($text, $quantity[$i], $rightword));
                $itemlist[] = $resulttext;
                $buttontext .= multireplace($buttonstarttext, array("[text]" => $resulttext, "[itemid]" => $i+1));
            }
        } else if($reduced){
            $buttontext = multireplace($buttonstarttext, array("[script]" => 'runtest2(this);', "[style]" => "", "[title]" => "Assimilate reduced", "[itemid]" => 1, "[text]" => $text));
            $itemlist[] = trim($text);
        } else {//only 1 item, only needs 1 assimilate button
            $buttontext = multireplace($buttonstarttext, array("[script]" => 'runtest(this);', "[itemid]" => 1, "[style]" => ' STYLE="width: 100%;"', "[text]" => "[rowid]", "[title]" => "Assimilate Single"));
            $itemlist[] = trim($text);
        }


        $ItemID = 0;
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {//display SQL results
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
                    $row["quantity"] = firstword($keywords[$ID]["synonyms"]);
                } else if($weight == 5){
                    $itemtype = $ID;
                }
            }
            if(!$quantityID){//keyword for quantity wasn't in the search, get it manually
                $QTY = Query("SELECT * FROM menukeywords, keywords WHERE menuitem_id = " . $row["id"] . " OR menuitem_id = -" . $row["category_id"] . " AND keywordtype = 1 HAVING keywords.id = menukeywords.keyword_id", true)[0];
                //$row["quantityID"] = $QTY["id"];
                $row["quantity"] = firstword($QTY["synonyms"]);
            }

            $row["actions"] = '<A HREF="edit?id=' . $row["id"] . '">Edit</A>';
            if($quantity){
                $row["actions"] .= " Stage 1.3:";
            }

            foreach($Tables as $TableID => $TableName){
                $tabletext = multireplace($tabletext, array("[" . $TableName . "]" => $row[$TableName]));
            }

            $row["actions"] .= '<BR>' . multireplace($buttontext, array("[rowid]" => $row["id"], "[tabletext]" => $tabletext));

            $row = removekeys($row, array("name", "price", "display_order", "has_addon", "wordid", "mkid", "keyword_id", "req_opt", "sing_mul", "exact_upto", "exact_upto_qty", "created_at", "updated_at", "addon_category_id", "image", "menuitem_id", "item_id", "menuid", "keywordtype", "itemname"));//just to clean up the results
            $row = removekeys($row, $Tables);


            $buttonstarttext = '<BUTTON ID="order' . $ItemID . '" CLASS="assimilateall order' . $ItemID . '" ONCLICK="orderitem(this);" VALUE="' . $row["id"] . '" ' . $tabletext . ' STYLE="width:100%;height:110%;" itemcount="' . count($itemlist) . '" itemname="' . $row["item"] . '" price="' . $row["itemprice"] . '" typeid="' . $itemtype . '"';
            if($itemtype){
                $buttonstarttext .= ' type="' . firstword($keywords[$itemtype]["synonyms"]) . '"';
            }
            foreach($itemlist as $index => $item){
                $buttonstarttext .= " item" . $index . '="' . $item . '"';
            }
            $row["Order"] = $buttonstarttext . '>Order</BUTTON>';

            printrow($row, $FirstResult);
            $ItemID++;
        }
        if (!$FirstResult) {echo '</TABLE>';}
    } else {
        echo "No keywords found in '" . $_GET["search"] . "'";
        echo '<BR>SQL: <B>' . $SQL . '</B>';
    }//dump the arrays to javascript, that way only 1 copy needs to be edited
?>
</DIV>
<SCRIPT>
    var wordstoignore = <?= json_encode($wordstoignore) ?>;
    var quantities = <?= json_encode($quantities) ?>;
</SCRIPT>