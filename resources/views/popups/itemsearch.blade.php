<DIV STYLE="border: 1px solid red;">
<?php
    //this is the item search engine, works by either text, or keyword IDs
    if(isset($keywordids) && is_array($keywordids)){$keywordids = implode(",", $keywordids);}
    if(!isset($isKeyword)){$isKeyword = true;}
    $quantities = ["next", "first", "second", "third", "fourth", "then", "other"];//add rank 5 keywords to this, but only the single-word synonyms
    //if(!isset($wordstoignore)) {$wordstoignore = ["the", "with", "and", "times", "on", "one"];}//use copy from keyword.blade instead
    if(count($is5keywords) > 1){//is part of a multiple item search
        $WordsBefore = 5;
        //reduce the $text to X words before the primary keyword, till the next primary keyword or end of the string
        //also reprocess the keywords to only get the keyword IDs between those 2 points as well, making the toppings for example, wings more specific to wings
        var_dump($text);
        $primarykeyid = $keywords[$primarykeyid];
        var_dump($primarykeyid);
        var_dump($is5keywords);
        var_dump($keywords);
    }

    if(!function_exists("containswords")){
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
        $SQL = "SELECT *, count(DISTINCT keyword_id) as keywords, SUM(weight) as weight, GROUP_CONCAT(DISTINCT synonyms SEPARATOR '|') as synonyms, GROUP_CONCAT(DISTINCT weight SEPARATOR '|') as weights
              FROM (
                  SELECT menu.*, menu.id AS menuid, menu.item as itemname, menu.price as itemprice, keywords.id as wordid, menukeywords.id as mkid, menuitem_id, keyword_id, synonyms, weight
                  FROM menu, menukeywords, keywords
                  HAVING (menuid=menuitem_id OR -menu.category_id = menuitem_id)
                  AND keyword_id = wordid
                  ";

        if (isset($keywordids)){
            $SQL .= "AND keyword_id IN (" . $keywordids . ")";//only return results from the keyword search
        }
        $SQL .= ") results GROUP BY menuid";
    }
    $SQL .= " ORDER BY " . $SortColumn . " " . $SortDirection;

    $result = Query($SQL);

    if($result) {
        $FirstResult = true;
        if(!isset($text)){$text="";}
        $quantity = containswords($text, $quantities);//check if the search contains multiple items, instead of just one
        $buttontext= "";
        $buttonstarttext = ' <BUTTON ID="assimilate[rowid]-[itemid]" CLASS="assimilate assimilate[rowid]" IID="[rowid]" TITLE="Assimilate" ONCLICK="[script]" VALUE="[text]" toppings="[toppings]" wings_sauce="[wings_sauce]" [style]>Item: [itemid]</BUTTON>';//base string, replace [text] later on

        if($quantity){//split the search up into it's individual items
            $lastkey = lastkey($quantity);
            $buttonstarttext = multireplace($buttonstarttext, array("[script]" => 'runtest2(this);', "[style]" => ""));
            foreach($quantity as $i => $word){
                $rightword=false;
                if($i != $lastkey){
                    $rightword = $quantity[$i+1];//if it's not the last key, then get the next one
                }
                $resulttext = getwordsbetween($text, $quantity[$i], $rightword);
                $buttontext .= multireplace($buttonstarttext, array("[text]" => $resulttext, "[itemid]" => $i+1));
            }
        } else {//only 1 item, only needs 1 assimilate button
            $buttontext = multireplace($buttonstarttext, array("[script]" => 'runtest(this);', "[itemid]" => 1, "[style]" => ' STYLE="width: 100%;"', "[text]" => "[rowid]"));
        }

        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {//display SQL results
            $row["id"] = $row["menuid"];
            $row = removekeys($row, array("name", "price", "display_order", "has_addon", "wordid", "mkid", "keyword_id", "req_opt", "sing_mul", "exact_upto", "exact_upto_qty", "created_at", "updated_at", "addon_category_id", "image", "menuitem_id", "item_id", "menuid"));//just to clean up the results
            $row["actions"] = '<A HREF="edit?id=' . $row["id"] . '">Edit</A><BR>';
            $row["actions"] .= multireplace($buttontext, array("[rowid]" => $row["id"], "[wings_sauce]" => $row["wings_sauce"], "[toppings]" => $row["toppings"]));
            printrow($row, $FirstResult);
        }
        if (!$FirstResult) {echo '</TABLE>';}
    } else {
        echo "No keywords found in '" . $_GET["search"] . "'";
    }//dump the arrays to javascript, that way only 1 copy needs to be edited
?>
</DIV>
<SCRIPT>
    var wordstoignore = <?= json_encode($wordstoignore) ?>;
    var quantities = <?= json_encode($quantities) ?>;
</SCRIPT>