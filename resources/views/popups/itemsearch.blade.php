<DIV STYLE="border: 1px solid red;">
<?php
    if(isset($keywordids) && is_array($keywordids)){$keywordids = implode(",", $keywordids);}
    if(!isset($isKeyword)){$isKeyword = true;}
    $quantities = ["next", "first", "second", "third", "fourth", "then"];
    $wordstoignore = ["the", "with", "and", "times", "on", "one"];

    function containswords($text, $words){
        $ret = array();
        if(!is_array($text)){$text = explode(" ", $text);}
        if(!is_array($words)){$words = array(normalizetext($words));} else {$words = normalizetext($words);}

        var_dump($text);
        var_dump($words);

        foreach($text as $index => $text_word){
            if(in_array(normalizetext($text_word), $words)){
                $ret[] = $index;
            }
        }
        return $ret;
    }

    function removewords($text, $words){
        if(!is_array($text)){$text = explode(" ", $text);}
        $words = containswords($text, $words);
        foreach($words as $index){
            unset($text[$index]);
        }
        return implode(" ", $text);
    }

    function getwordsbetween($text, $leftword, $rightword = false){
        if(!is_array($text)){
            return implode(" ", getwordsbetween(explode(" ", $text), $leftword, $rightword));
        }
        $length = NULL;
        $leftword = $leftword + 1;
        if($rightword){$length = $rightword - $leftword;}
        return array_slice($text, $leftword, $length);
    }

    function normalizetext($text){
        if(is_array($text)){
            foreach($text as $index => $word){
                $text[$index] = normalizetext($word);
            }
            return $text;
        }
        return strtolower(trim($text));
    }

    if(isset($searchstring) && !$isKeyword){
        $searchstring = explode(" ", $searchstring);
        $weights = array();

        foreach($searchstring as $word){
            $word = strtolower($word);
            if (right($word,1) == "s" && strlen($word) > 2){
                $searchstring[] = left($word, strlen($word)-1);
                $weights[] = "1";
            }
            $weights[] = "1";
        }

        $weights = implode("|", $weights);
        $searchcol = "{col} LIKE '%" . implode("%' OR {col} LIKE '%", $searchstring) . "%'";

        $SQL = "SELECT *, 1 as keywords, 1 as weight, '" . implode("|", $searchstring) . "' as synonyms, '" . $weights . "' as weights, menu.*, menu.id AS menuid, menu.item as itemname, menu.price as                      itemprice FROM menu WHERE " . str_replace("{col}", "item", $searchcol) . " OR " . str_replace("{col}", "category", $searchcol);
    } else {
        $SQL = "SELECT *, count(DISTINCT keyword_id) as keywords, SUM(weight) as weight, GROUP_CONCAT(DISTINCT synonyms SEPARATOR '|') as synonyms, GROUP_CONCAT(DISTINCT weight SEPARATOR '|') as weights
              FROM (
                  SELECT menu.*, menu.id AS menuid, menu.item as itemname, menu.price as itemprice, keywords.id as wordid, menukeywords.id as mkid, menuitem_id, keyword_id, synonyms, weight
                  FROM menu, menukeywords, keywords
                  HAVING (menuid=menuitem_id OR -menu.category_id = menuitem_id)
                  AND keyword_id = wordid
                  ";

        if (isset($keywordids)){
            $SQL .= "AND keyword_id IN (" . $keywordids . ")";
        }
        $SQL .= ") results GROUP BY menuid";
    }
    $SQL .= " ORDER BY " . $SortColumn . " " . $SortDirection;

    $result = Query($SQL);

    function lastkey($array){
        $keys = array_keys($array);
        return last($keys);
    }

    function multireplace($Text, $Data){
        foreach($Data as $Key => $Value){
            $Text = str_replace($Key, $Value, $Text);
        }
        return $Text;
    }

    if($result) {
        $FirstResult = true;
        $quantity = containswords($text, $quantities);
        $buttontext= "";
        $buttonstarttext = ' <BUTTON ID="assimilate[rowid]-[itemid]" CLASS="assimilate assimilate[rowid]" IID="[rowid]" TITLE="Assimilate" ONCLICK="[script]" VALUE="[text]" toppings="[toppings]" wings_sauce="[wings_sauce]" [style]>Item: [itemid]</BUTTON>';
        if($quantity){
            $lastkey = lastkey($quantity);
        }

        if($quantity){
            $buttonstarttext = multireplace($buttonstarttext, array("[script]" => 'runtest2(this);', "[style]" => ""));
            foreach($quantity as $i => $word){
                $rightword=false;
                if($i != $lastkey){
                    $rightword = $quantity[$i+1];
                }
                $resulttext = getwordsbetween($text, $quantity[$i], $rightword);
                $buttontext .= multireplace($buttonstarttext, array("[text]" => $resulttext, "[itemid]" => $i+1));
            }
        } else {
            $buttontext = multireplace($buttonstarttext, array("[script]" => 'runtest(this);', "[itemid]" => 1, "[style]" => ' STYLE="width: 100%;"', "[text]" => "[rowid]"));
        }

        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $row["id"] = $row["menuid"];
            $row = removekeys($row, array("name", "price", "display_order", "has_addon", "wordid", "mkid", "keyword_id", "req_opt", "sing_mul", "exact_upto", "exact_upto_qty", "created_at", "updated_at", "addon_category_id", "image", "menuitem_id", "item_id", "menuid"));//just to clean up the results
            $row["actions"] = '<A HREF="edit?id=' . $row["id"] . '">Edit</A><BR>';
            $row["actions"] .= multireplace($buttontext, array("[rowid]" => $row["id"], "[wings_sauce]" => $row["wings_sauce"], "[toppings]" => $row["toppings"]));
            printrow($row, $FirstResult);
        }
        if (!$FirstResult) {echo '</TABLE>';}
    } else {
        echo "No keywords found in '" . $_GET["search"] . "'";
    }
?>
</DIV>
<SCRIPT>
    var wordstoignore = <?= json_encode($wordstoignore) ?>;
    var quantities = <?= json_encode($quantities) ?>;
</SCRIPT>