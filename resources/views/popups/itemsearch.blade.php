<DIV STYLE="border: 1px solid red;">
<?php
    if(isset($keywordids) && is_array($keywordids)){$keywordids = implode(",", $keywordids);}
    if(!isset($isKeyword)){$isKeyword = true;}

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

    if($result) {
        $FirstResult = true;
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $row["id"] = $row["menuid"];
            $row = removekeys($row, array("name", "price", "display_order", "has_addon", "wordid", "mkid", "keyword_id", "req_opt", "sing_mul", "exact_upto", "exact_upto_qty", "created_at", "updated_at", "addon_category_id", "image", "menuitem_id", "item_id", "menuid"));//just to clean up the results
            $row["actions"] = '<INPUT TYPE="BUTTON" ONCLICK="runtest(this);" VALUE="' . $row["id"] . '" STYLE="width: 100%;" TITLE="Assimilate" toppings="' . $row["toppings"] . '" wings_sauce="' . $row["wings_sauce"] . '" ID="menu' . $row["id"] .'"><BR><A HREF="edit?id=' . $row["id"] . '">Edit</A>';

            printrow($row, $FirstResult);
        }
        if (!$FirstResult) {echo '</TABLE>';}
    } else {
        echo "No keywords found in '" . $_GET["search"] . "'";
    }
?>
</DIV>
