<?php
    $quantities = array("next", "first", "second", "third", "fourth", "then", "other");
    $superquantities = array("all", "both");
    $wordstoignore = array("the", "with", "and", "times", "on", "an", "of", "just"); //discard these words
    $defaultsizes = array("pizza" => "large");
    $otherdefaults = array(
            "drink" => array("regular", "diet")//adds regular to drinks if regular and diet are not found
    );
    $Tables = array("toppings", "wings_sauce");
    $WordsBefore = 5; //similar_text

    function explodetrim($text, $delimiter = ",", $dotrim = true){
        if(is_array($text)){return $text;}
        $text = explode($delimiter, $text);
        if(!$dotrim){return $text;}
        foreach($text as $ID => $Word){
            $text[$ID] = trim($Word);
        }
        return $text;
    }

    if(isset($_POST["action"])){
        function firstword($Text){
            $Space = strpos($Text, " ");
            if($Space === false){return $Text;}
            return left($Text, $Space);
        }

        function getsynonymsandweights($text, $keywords, $removeduplicates = true){
            $newsearch = explodetrim($text, " ", false);
            foreach($newsearch as $index => $word){
                $synonymID = findsynonym($word, $keywords);
                $newsearch[$index] = array("word" => $word, "synonymid" => $synonymID);
                if($synonymID > -1 && $index > 0 && $removeduplicates){ //remove duplicates as it'll confuse the system
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
            if(!is_array($text)){$text = explodetrim($text, " ", false);}
            $words = containswords($text, $words);
            foreach($words as $index){
                unset($text[$index]);
            }
            return implode(" ", $text);
        }

        // gets the words between leftword and rightword or the end of the string
        function getwordsbetween($text, $leftword, $rightword = false){
            if(!is_array($text)){
                return implode(" ", getwordsbetween(explodetrim($text, " ", false), $leftword, $rightword));
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
            return implode(",", array_unique($keywordids));
        }

        function getstartandend($newsearch, $keywords, $primarykeyid, &$startingIndex, &$endingIndex, $realstartingindex=-1){
            $endingIndex = -1;
            $startingIndex = -1;
            foreach($newsearch as $index => $word){
                if($index >= $realstartingindex){
                    $synonymID = $word["synonymid"];
                    if($synonymID > -1){
                        $synonym = $keywords[$synonymID];
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

        function separatealphanumerics($string){
            preg_match_all('/([0-9]+|[a-zA-Z]+)/',$string,$matches);
            if(!isset($matches[0])){return $string;}
            return implode(" ", $matches[0]);
        }

        function addkeyword(&$results, $keyword){
            $keyword = strtolower($keyword);
            foreach($results["keywords"] as $ID => $Value){
                if(strpos($Value["synonyms"], $keyword) !== false){return $ID;}
            }
            $one = select_field_where("keywords", "synonyms LIKE '%" . $keyword . "%'");
            $results["keywords"][$one["id"]] = array("word" => firstword($one["synonyms"]), "synonyms" => $one["synonyms"], "weight" => $one["weight"], "type" => $one["keywordtype"]);
            return $one["id"];
        }

        switch(strtolower(trim($_POST["action"]))){
            case "keywordsearch":
                $results = array("status" => false, "stages" => array($_POST["search"]), "searches" => array());
                $one = select_field_where("keywords", "keywordtype = 1 AND synonyms LIKE '%1%'");
                $_POST["search"] = trim(filterduplicates(filternonalphanumeric($_POST["search"]))); //remove non-alphanumeric and double-spaces
                $_POST["search"] = str_replace(array(" for 1", " for one"), "", $_POST["search"]);
                $words = explodetrim($_POST["search"], " ", false);
                foreach($words as $ID => $word){ //reduce extra "one"s
                    $word = normalizetext($word);
                    if($word == "1" || $word == "one"){
                        $firstword="";
                        $lastword="";
                        if($ID < lastkey($words)){$lastword = $words[$ID+1];}
                        if($ID > 0){$firstword = $words[$ID-1];}
                        $DOIT = count(containswords(array($firstword, $lastword), $quantities)) > 0;
                        if(!$DOIT){if(count(containswords(array($firstword, $lastword), array("with")))){$DOIT = 2;}}
                        if($DOIT == 1){$words[$ID] = "";} else if ($DOIT == 2){$words[$ID] = $quantities[0];}
                    }
                }
                $_POST["search"] = filterduplicates(implode(" ", $words));
                $results["stages"][] = $_POST["search"];

                $_POST["search"] = separatealphanumerics($_POST["search"]);
                $results["stages"]["numbers"] = $_POST["search"];

                $words = strtolower(str_replace(" ", "|", $_POST["search"]));
                $plurals = explodetrim($words, "|", false); //automatically check for non-pluralized words
                foreach($plurals as $index => $plural){
                    $plural = trim(strtolower($plural));
                    if(in_array($plural, $wordstoignore) || !$plural) {
                        unset($plurals[$index]); //discard words from $wordstoignore
                    } else if (strlen($plural) > 2 && right($plural, 1) == "s"){ //if the last letter of the word is an 's', remove it and add the result as a new word to handle plurals
                        $plurals[$index] = $plural;
                        $plurals[] = left($plural, strlen($plural)-1);
                    }
                }

                $results["stages"]["plurals"] = implode(" ", $plurals);

                //if the primary synonym isn't found, but the "words" are, add the primary synonym in to force it to return a weight-5 result
                $primarysynoynms = array(
                        "wing" => array(
                                "words" => array("chicken", array("pound", "lbl", "lb")), //sub-array acts as an OR
                                "normalizationmode" => 8, //numbers removed
                                "all" => true
                        ),
                        "drink" => array(
                                "words" => array("pepsi", "cola", "coke") //should contain entire list of drink names...
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
                    $results["keywordids"] = array();
                    while ($row = mysqli_fetch_array($result)){
                        $word = firstword($row["synonyms"]);
                        $results["keywords"][ $row["id"] ] = array(
                                "word" => $word,
                                "synonyms" => $row["synonyms"],
                                "weight" => $row["weight"],
                                "type" => $row["keywordtype"]
                        );
                        if($row["weight"] == 5){
                            $results["is5keywords"][] = $row["id"]; //is a weight 5 keyword, indicating a new search needs to be run
                        } else {
                            $results["non5keywords"][] = $row["id"]; //is a weight of less than 5
                        }
                        $results["keywordids"][] = $row["id"];
                    }
                    $results["keywordids"] = implode(",", $results["keywordids"]);
                    $results["SortColumn"] = get("SortColumn", "keywords");
                    $results["SortDirection"] = get("SortDirection", "DESC");
                    $results["limit"] = get("limit", "5");

                    if(count($results["is5keywords"]) > 0){ //run a search for each weight-5 keyword, with only 1 weight-5 keyword per search
                        $newsearch = getsynonymsandweights($_POST["search"], $results["keywords"], false);

                        //remove the weight 5 keywords next to a qualifier (ie: 'first pizza' becomes 'pizza')
                        $hasremoved = false;
                        $wasquantity=false;
                        foreach($newsearch as $ID => $value){
                            $synonymID = $value["synonymid"];
                            if($synonymID > -1){
                                if($wasquantity) {
                                    if($results["keywords"][$synonymID]["weight"] == 5){ //is a primary next to a quantity
                                        unset($newsearch[$ID]);
                                        $hasremoved=true;
                                    }
                                    $wasquantity = false;
                                }
                            } else if (in_array($value["word"], $quantities)) { //is a qualifier
                                $wasquantity=true;
                            } else { //is irrelevant
                                $wasquantity=false;
                            }
                        }
                        if($hasremoved){ //reassemble
                            $text = reassemble_text($newsearch, $results["keywords"]);
                            $results["stages"]["weight5adj"] = $text;
                            $keywordids = reassemble_keywordIDs($newsearch);
                        }
                        //done

                        foreach($results["is5keywords"] as $keywordid => $primaryKeyID){
                            $keywordids = array_merge(array($primaryKeyID), $results["non5keywords"]);
                            $indexes = countsynonyms($newsearch, $primaryKeyID);
                            if(count($indexes) > 1){ //search contains multiple of the same weight-5 keyword, split it into multiple searches
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
                            } else { //only 1 weight-5 keyword
                                $results["searches"][] = array(
                                        "stage" => "1.w5",
                                        "text" => $_POST["search"],
                                        "keywordids" => $keywordids,
                                        "primarykeyid" => $primaryKeyID,
                                );
                            }
                        }
                    } else if ($results["non5keywords"]){ //no weight-5 keywords found, run a single search of all the keywords
                        $results["searches"][] = array(
                                "stage" => "1.w0",
                                "text" => $_POST["search"],
                                "keywordids" => $results["non5keywords"],
                        );
                    }
                }

                function array2string($arr){
                    return str_replace("\n", '<BR>', print_r($arr, true));
                }

                foreach($results["searches"] as $SearchID => $VALUE){
                    $keywordids = $VALUE["keywordids"];
                    $text = $VALUE["text"];
                    unset($primarykeyid);
                    if(isset($VALUE["primarykeyid"])){
                        $primarykeyid = $VALUE["primarykeyid"];
                        $primaryword = $results["keywords"][$primarykeyid]["word"];
                        if(count($results["is5keywords"]) > 1){ //is part of a multiple item search, reducing each item into it's own search
                            $newsearch = getsynonymsandweights($text, $results["keywords"], false);
                            $quantity = containswords($text, $quantities); //check if the search contains multiple items, instead of just one
                            $itemlist = array(); //if word to the right of a quantity is weight = 5, then erase the weight 5
                            if($quantity){
                                foreach($quantity as $i => $word){
                                    $rightword=false;
                                    if(isset($newsearch[$quantity[$i]+1])){
                                        $rightword = $newsearch[$quantity[$i]+1]; //if it's not the last key, then get the next one
                                        if($rightword["synonymid"] > -1){
                                            $synonym = $results["keywords"][$rightword["synonymid"]];
                                            if($synonym["weight"] == 5){
                                                unset($newsearch[$quantity[$i]+1]);
                                            }
                                        }
                                    }
                                }
                                $results["searches"][$SearchID]["stage"] .= " [w5 next to quantity]";
                            }

                            $text = reassemble_text($newsearch, $results["keywords"]);
                            $results["stages"]["remove w5s " . $SearchID] = $text;
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
                            $results["searches"][$SearchID]["stage"] .= " [1.1:area reduction]";
                            $text = reassemble_text($newsearch, $results["keywords"], $startingIndex, $endingIndex);
                            $keywordids = reassemble_keywordIDs($newsearch, $startingIndex, $endingIndex);
                            $results["stages"]["[1.1]" . $SearchID] = $text . "(" . $keywordids . ")";
                        }
                    }

                    //allow only one quantity word per search
                    $newsearch = getsynonymsandweights($text, $results["keywords"], false);
                    $startremoving = false;
                    $hasremoved = false;
                    $quantityID = false;
                    $size="";
                    $sizeid=0;
                    $lastkey = lastkey($newsearch);
                    foreach($newsearch as $ID => $value){
                        $synonymID = $value["synonymid"];
                        if($synonymID > -1){
                            switch($results["keywords"][$synonymID]["type"]){
                                case 1://quantity
                                    if($startremoving){
                                        unset($newsearch[$ID]);
                                        $hasremoved = true;
                                    }
                                    $startremoving = true;
                                    $quantityID = $synonymID;
                                    break;
                                case 2://size
                                    $sizeid=$synonymID;
                                    $size = $results["keywords"][$synonymID]["word"];
                                    break;
                            }
                        }
                    }

                    if($hasremoved){
                        $results["searches"][$SearchID]["stage"] .= " [1.2:removed quantities]";
                        $text = reassemble_text($newsearch, $results["keywords"]);
                        $keywordids = reassemble_keywordIDs($newsearch);
                    } else if(!$startremoving){
                        $results["searches"][$SearchID]["stage"] .= " [1.2:only 1 quantity]";
                        $results["keywords"][$one["id"]] = array("word" => "1", "synonyms" => $one["synonyms"], "weight" => $one["weight"], "type" => $one["keywordtype"]);
                        $keywordids = addtodelstring($keywordids, $one["id"]);
                    }

                    if(!$size && isset($primarykeyid)){
                        if(isset($defaultsizes[$primaryword])){
                            $results["searches"][$SearchID]["stage"] .= " [defsize]";
                            $size = $defaultsizes[$primaryword];
                            $sizeid = addkeyword($results, $defaultsizes[$primaryword]);
                            $text .= " " . $size;
                            $keywordids = addtodelstring($keywordids, $sizeid);
                        }
                    }
                    $results["searches"][$SearchID]["size"] = $size;
                    $results["searches"][$SearchID]["sizeid"] = $sizeid;

                    $results["searches"][$SearchID]["text"] = $text;
                    $results["searches"][$SearchID]["keywordids"] = $keywordids;

                    $quantity = containswords($text, $quantities); //check if the search contains multiple items, instead of just one
                    $itemlist = array();
                    if($quantity){
                        $lastkey = lastkey($quantity);
                        foreach($quantity as $i => $word){
                            $rightword=false;
                            if($i != $lastkey){$rightword = $quantity[$i+1];}
                            $resulttext = trim(getwordsbetween($text, $quantity[$i], $rightword));
                            $resulttext = removewords($resulttext, $wordstoignore);
                            $itemlist[] = $resulttext;
                        }
                        $results["searches"][$SearchID]["stage"] .= " [1.3:split]";
                    } else {
                        $itemlist[] = trim($text);
                    }
                    $results["searches"][$SearchID]["originalitems"] = $itemlist;
                    $results["searches"][$SearchID]["items"] = $itemlist;

                    if(isset($primaryword) && isset($otherdefaults[$primaryword])){
                        $found = false;
                        $keywordids = explode(",", $results["searches"][$SearchID]["keywordids"]);
                        foreach($keywordids as $keywordid){
                            $keyword = $results["keywords"][$keywordid];
                            if(in_array( $keyword["word"], $otherdefaults[$primaryword])){
                                $found = true;
                                break;
                            }
                        }
                        if(!$found){
                            $results["searches"][$SearchID]["stage"] .= " [other defaults]";
                            foreach($results["searches"][$SearchID]["items"] as $id => $item){
                                $results["searches"][$SearchID]["items"][$id] .= $otherdefaults[$primaryword][0];
                            }
                            $keywordids[] = addkeyword($results, $otherdefaults[$primaryword][0]);
                            $results["searches"][$SearchID]["keywordids"] = implode(",", $keywordids);
                        }
                    }

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
                    $SQL .= "AND keyword_id IN (" . $keywordids . ")"; //only return results from the keyword search
                    $SQL .= ") results GROUP BY menuid ORDER BY " . $results["SortColumn"] . " " . $results["SortDirection"] . " LIMIT " . $results["limit"];

                    $SQLresults = Query($SQL, true);
                    foreach($SQLresults as $menuitemID => $row){
                        $row["id"] = $row["menuid"];
                        $row["price"] = number_format($row["price"], 2);

                        //get quantity of item
                        $itemtype = 0;
                        $quantityID = false;
                        if(is_array($row["keywordids"])){
                            $keywordIDs = $row["keywordids"];
                        } else {
                            $keywordIDs = explodetrim($row["keywordids"], "|", false);
                        }
                        $thesekeywords = array_combine ( $keywordIDs , explodetrim($row["types"], "|", false) );
                        $thesekeywordw = array_combine ( $keywordIDs , explodetrim($row["weights"], "|", false) );
                        foreach($thesekeywords as $ID => $type){
                            $weight = $thesekeywordw[$ID];
                            if($type == 1){ //quantity found
                                $quantityID = $ID;
                                $row["quantity"] = filternonnumeric(firstword($results["keywords"][$ID]["synonyms"]));
                            } else if($weight == 5){
                                $itemtype = $ID;
                            }
                        }
                        if(!$quantityID){$row["quantity"] = firstword($one["synonyms"]);}
                        foreach(array("synonyms", "keywordids", "weights", "types") as $column){
                            $row[$column] = explodetrim($row[$column], "|", false);
                        }

                        $row = removekeys($row, array("itemname", "menuid", "itemprice", "wordid", "keywordtype", "mkid", "menuitem_id", "keyword_id")); //just to clean up the results
                        unset($row["menuid"]);

                        $results["searches"][$SearchID]["menuitems"][] = $row;
                    }
                }

                echo json_encode($results, JSON_PRETTY_PRINT);
                break;
        }
    } else {
        if(!isset($_GET["search"])){$_GET["search"]="";}
        if(!isset($_POST["search"])){$_POST["search"]=$_GET["search"];}
        $addons = array();
        foreach($Tables as $table){
            $results = Query("SELECT * FROM " . $table, true);
            foreach($results as $result){
                $addons[$table][$result["type"]][$result["name"]] = explodetrim($result["qualifiers"]);
            }
        }

        $allkeywords = collapsearray(Query("SELECT * FROM keywords", true), "synonyms", " ");
        $presets = Query("SELECT * FROM presets", true);
        $presetsnames = collapsearray($presets, "name");
?>
    <!--script src="<?= webroot("resources/assets/scripts/api.js"); ?>"></script>
    <script src="<?= webroot("resources/assets/scripts/nui.js"); ?>"></script>
    <script src="<?= webroot("resources/assets/scripts/receipt.js"); ?>"></script-->
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
        .selectedbutton{
            background-color: #4CAF50; /* Green */
        }

        hr{
            margin-top: 1px;
            margin-bottom: 1px;
        }

        a.button {
            appearance: button;
            -moz-appearance: button;
            -webkit-appearance: button;
            padding: 1px 6px;
            color: inherit;
            text-decoration:none;
        }

        a{
            color: blue;
            cursor: pointer;
            text-decoration:underline;
        }

        .editmenu{
            height:19px;
            margin-right: 10px;
            position: relative;
            top: 3px;
        }
    </STYLE>
    <DIV id="formmain" class="red">
        <input type="text" id="textsearch" name="search" style="width:100%" on_old_input="submitform();" onKeyUp="handlebutton(event);" value="<?= $_POST["search"]; ?>" TITLE="Press 'Space' or 'Enter' to search">
        <input type="button" id="startspeech" style="display:none;" value="Click to Speak" onclick="startButton(event);" TITLE="Use voice recognition">
        <input type="button" id="clearform" value="Clear Search" onclick="clearform();">
        <BR>
        Sorting by:
        <?php
            $Columns = array("restaurant_id", "itemprice", "weight", "keywords");
            echo printoptions("SortColumn", $Columns, "weight");
            echo ' Direction: ' . printoptions("SortDirection", array("ASC", "DESC"), "DESC");
            echo ' <LABEL><INPUT TYPE="checkbox" ID="showjson" ' . iif(isset($_GET["showjson"]) && $_GET["showjson"] == "true", 'checked="true" ')  . 'ONCLICK="handlejson();"> Show JSON</LABEL>';
            echo '<SPAN STYLE="float:right;">Test: ';
            foreach(array("2 medium pepperoni pizza", "2 bacon pizza", "4 large pizzas", "1 large pizza with pepperoni bacon and ham", "2 medium pepperoni pizza with 2lbs chicken bbq sauce", "1 pizza plane, 1 cheddar dip and 2 cokes", "2 for 1 pizza combo with ice tea first pizza pepperni bacon and ham, second pizza just bacon", "tripple bacon pizza", "1 large pepperoni pizza and 1 medium pizza ham", "pizza with extra cheese", "2 large pizza 1 with bacon the next one with ham") as $INDEX => $teststring){
                echo '<BUTTON VALUE="' . $teststring . '" TITLE="Test with: ' . $teststring . '" ONCLICK="testwith(this);">' . $INDEX . '</BUTTON>';
            }
            echo '</SPAN>';
        ?>
    </DIV>
    <DIV ID="searchresults" CLASS="red">
        Files used:
        <UL>
            <LI>api.php: Used site-wide</LI>
            <LI>clipi.blade.php: This file</LI>
            <LI>getjs.blade.php: combines all javascript into a single file and minimizes it (needs to be separate because it checks if itself was updated. If it's merged with clipi it would regenerate the cache a lot more often) also lets .JS files be injected directly into the PHP without needing another HTTP request</LI>
            <LI>api-nui-min.js: Cache of the combined and minimized copies of nui.js and api.js</LI>
            <LI>*api.js: The mini-jquery clone (used in other parts of the site)</LI>
            <LI>*nui.js: All API related to the ordering system, including receipt generation</LI>
            <LI>(If api.js and nui.js files are not present, api-nui-min.js will be used if it exists)</LI>
        </UL>
    </DIV>
    <SCRIPT>
        var currentURL = "<?= Request::url(); ?>";
        var token = "<?= csrf_token(); ?>";
        var wordstoignore = <?= json_encode($wordstoignore); ?>;
        var quantities = <?= json_encode($quantities); ?>;
        var addons = <?= json_encode($addons); ?>;
        var presets = <?= json_encode($presets); ?>;
        var presetnames = <?= json_encode($presetsnames); ?>;
        var allkeywords = <?= json_encode($allkeywords) ?>;

        var DoPerfectlyFormed = false;

        function replacemultiplewordsynonyms(text, synonyms, cutoff){
            var originaltext = text.toLowerCase();
            text = text.split(" ");
            for(var i = 0; i < synonyms.length; i++){
                var synonym = synonyms[i].split(" ");
                var words = synonym.length;
                var currentCutoff = cutoff;
                var wordindex = -1;
                var wordstoreplace = 0;
                var originalword = "";

                for(var v = 0; v < text.length - (words-1); v++){
                    var distance = levenshteinWeighted(synonyms[i], text[v].trim());
                    if(distance < currentCutoff){
                        originalword = text[v].trim();
                        currentCutoff = distance;
                        wordindex = v;
                        wordstoreplace = 1;
                    }

                    var currentword = grabcells(text, v, words).join(" ").trim();
                    var distance = levenshteinWeighted(synonyms[i], currentword);
                    if(distance < currentCutoff){
                        originalword = currentword;
                        currentCutoff = distance;
                        wordindex = v;
                        wordstoreplace = words;
                    }
                }
                if(wordindex > -1){originaltext = originaltext.replaceAll(originalword, synonyms[i]);}
            }
            return originaltext; //removemultiples(text.join(" "), "  ", " ");
        }

        function grabcells(arr, start, length){
            return arr.slice(start, start+length);
        }

        function gettheaddons(text){
            //check for presets
            assimilate_enabled = false;
            var aftertext = replacemultiplewordsynonyms(text, presetnames, 1);
            for(var i = 0; i < presets.length; i++){
                aftertext = aftertext.replaceAll(presets[i].name, presets[i].toppings);
            }
            text = stringifyaddons(assimilateaddons(0, aftertext), DoPerfectlyFormed);
            if(isNumeric(lastquantity)){text += ",quantity|" + lastquantity;}
            assimilate_enabled = true;
            return text;
        }

        function handlebutton(evt){
            var charCode = (evt.which) ? evt.which : event.keyCode;
            if(charCode == 32 || charCode == 13){return submitform();}//32 is space, 13 is enter
            console.log(charCode);
        }

        function submitform(whendone){
            updateURL();
            var searchstring = value("#textsearch");
            post(currentURL, {
                action: "keywordsearch",
                search: searchstring,
                SortColumn: value("#SortColumn"),
                SortDirection: value("#SortDirection"),
                limit: 5,
                _token: token
            }, function(result, success){
                if(!success){
                    alert("ERROR: " + result);
                    return false;
                }
                try {
                    var data = JSON.parse(result);
                } catch (e){
                    innerHTML("#searchresults", e + " NON-JSON DETECTED: " + result);
                    return false;
                }
                var HTML = "TIME STAMP: " + Date.now(true) + "<BR>";

                searchstring = data.stages.final.split(" ");
                for(var searchindex = 0; searchindex < searchstring.length; searchindex++){
                    var closestword = findsynonym(searchstring[searchindex], allkeywords, 1);
                    //[0=synonym parent ID, 1=synonym child ID of the closest match, 2=distance to the match, 3=closest word]
                    if(closestword[2] > 0){
                        HTML += '<A onclick="typo(this);" originalword="' + searchstring[searchindex] + '" suggestion="' + closestword[3] + '">' + searchstring[searchindex] + " wasn't found, did you mean " + closestword[3] + '?</A><BR>';
                    }
                }

                if( data.is5keywords.length == 0 ){
                    HTML += "No weight 5 keywords found. Search for something like 'pizza' or 'wings'<BR>";
                } else {
                    for( var i = 0; i < data.searches.length; i++ ){
                        var currentsearch = data.searches[i];
                        if(!isUndefined( currentsearch.primarykeyid )){
                            HTML += "<BR>Item found: " + data.keywords[currentsearch.primarykeyid].word + "<BR>";

                            var ButtonHTML = '<BUTTON CLASS="assimilateall order123ID123" onclick="orderitem(this);" TYPE="' + data.keywords[currentsearch.primarykeyid].word + '" typeid="' + currentsearch.primarykeyid + '"';

                            if( currentsearch.items.length == 0) {
                                HTML += "No addons found<BR>";
                            } else {
                                ButtonHTML += ' itemcount="' + currentsearch.items.length + '"';
                                for (var v = 0; v < currentsearch.items.length; v++) {
                                    currentsearch.items[v] = gettheaddons(currentsearch.items[v]);
                                    if(!isNumeric(lastquantity)){lastquantity=1;}
                                    currentsearch.quantity = lastquantity;
                                    data.searches[i].items[v] = currentsearch.items[v];
                                    ButtonHTML += ' item' + v + '="' + currentsearch.items[v].replace(/<(?:.|\n)*?>/gm, '') + '"';
                                    var item = currentsearch.items[v];
                                    if(DoPerfectlyFormed){
                                        item = "<I>" + item.replaceAll("\\|", "</I> ").replaceAll(",", "</I>, <I>");
                                    }
                                    HTML += "Addons for sub-item " + (v + 1) + ": " + item + "<BR>";
                                }

                                if( currentsearch.menuitems.length > 0 ){
                                    var expectedquantity = currentsearch.menuitems[0].quantity;
                                    if(currentsearch.items.length < expectedquantity){
                                        HTML += "Addons for " + currentsearch.items.length + " items found. Expected " + expectedquantity + " items. Use these words to split up lists of addons: " + quantities.join(", ") + " <BR>";
                                        if(currentsearch.items.length == 1){
                                            HTML += "If you don't specify any other items, the first set will be used for all items ordered<BR>";
                                        }
                                    }
                                }
                            }

                            for(var i2 = 0; i2 < currentsearch.menuitems.length; i2++){
                                var quantity = 1;
                                var itemtitle = i2;
                                var currentItem = currentsearch.menuitems[i2];
                                currentItem.quantity = 0;
                                for(var i3 = 0; i3 < tables.length; i3++){
                                    currentItem.quantity = Number(currentItem.quantity) + Number(currentItem[tables[i3]]);
                                }
                                if (currentItem.quantity <= 1){//if quantity = 1, then set it to the desired quantity
                                    quantity = currentsearch.quantity;
                                } else {//check if it's easily divisible, if it is, use the result
                                    var qty = currentsearch.quantity / currentItem.quantity;
                                    if( isInteger(qty) ) { quantity = qty; }//is a whole number
                                }

                                var currentButtonHTML = ButtonHTML + 'value="' + currentItem.id + '" itemname="' + currentItem.item + '" price="' + currentItem.price + '" quantity="' + quantity + '"';
                                currentButtonHTML = currentButtonHTML.replace("123ID123", i2);
                                if(i2 == 0){
                                    currentButtonHTML = currentButtonHTML.replace('CLASS="', 'CLASS="selectedbutton ');
                                }
                                for(var v = 0; v < tables.length; v++){
                                    currentButtonHTML += " " + tables[v] + '="' +  currentItem[tables[v]] + '"';
                                }
                                if(i2==0){itemtitle += " (***BEST CANDIDATE***)";}

                                var itemname = currentItem.item;
                                var itemprice = currentItem.price;
                                if(quantity > 1){
                                    itemname = quantity + "x " + itemname;
                                    if(!itemname.endswith("s")){itemname += "s"}
                                    itemprice += " ($" + Number(itemprice * quantity).toFixed(2) + ")";
                                }
                                HTML += currentButtonHTML + ' TITLE="Item: ' + itemtitle + '">Order: ' + itemname + " for: $" + itemprice + '</BUTTON>' +
                                        '<A CLASS="button editmenu" HREF="edit?id=' + currentItem.id + '" target="_new" TITLE="Edit: ' + currentItem.item + '">&#10096;Edit</A>';
                            }
                        }
                    }
                    HTML += '<HR>';
                }

                //innerHTML("#searchresults", HTML);
                result = JSON.stringify(data, null, 2); //isn't needed
                innerHTML("#searchresults", HTML + '<pre CLASS="blue jsonresult" TITLE="JSON result">' + result + "</pre>"); //<PRE>result</PRE> isn't needed
                handlejson();
                if(isFunction(whendone)){whendone();}
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

        function testwith(element){
            order = new Array;
            value("#textsearch", value(element));
            submitform(function(){
               trigger(".order0", "click");
            });
        }

        function handlejson(){
            setvisible(".jsonresult", checked("#showjson"));
            updateURL();
        }

        function clearform(){
            value('#textsearch', '');
            innerHTML("#searchresults", "");
            updateURL();
        }

        function updateURL(){
            ChangeUrl("CLIPi", currentURL + "?search=" + value("#textsearch") + "&SortColumn=" + value("#SortColumn") + "&SortDirection=" + value("#SortDirection") + "&showjson=" + checked("#showjson"));
        }

        function typo(element){
            var originalword = element.getAttribute("originalword");
            var searchstring = value("#textsearch").split(" ");
            for(var i=0; i<searchstring.length; i++){
                if(searchstring[i].isEqual(originalword)){
                    searchstring[i] = element.getAttribute("suggestion");
                }
            }
            value("#textsearch", searchstring.join(" "));
            submitform();
        }

        <?= view("home.getjs", array("files" => "api,nui")); ?>

        <?php if(trim($_POST["search"])){echo 'submitform();';} ?>
    </SCRIPT>
<?php
    foreach($Tables as $table){
        echo "\r\n" . '<!-- BEGIN ' . $table . '--!>' . view("popups.addons", array("table" => $table)) . '<!-- END ' . $table . '--!>';
        //makeaddons($table);
    }
}

        /*
function endtoppings($table, $start = false){
    if($start){
        $start = strtolower($start);
        echo '<TABLE CLASS="cat cat-' . $table . '" ID="cat-' . $table . "-" . str_replace(" ", "-", $start) . '" STYLE="display: none;">';
        return $start;
    } else {
        echo '</TABLE>';
    }
}

function makeaddons($table = "toppings"){
    $categories = Query("SELECT DISTINCT type FROM `$table` ORDER BY type ASC", true);
    $toppings = select_field_where($table, "1=1", "ALL()", "type");
    $width = round(100 / count($categories));

    echo "\r\n" . '<!-- BEGIN ' . $table . '--!><STYLE>.td-' . $table . '-name {vertical-align: bottom; padding-left: 10px;} .cat-header-' . $table .'{width: ' . $width . '%; display: inline-block; cursor: pointer;}</STYLE>';

    echo '<DIV CLASS="addons-' . $table . ' red" STYLE="display: none;">';
    foreach($categories as $index => $category){
        echo '<SPAN CLASS="cat-header cat-headerid-' . $table . '-' . $index .' cat-header-' . $table . '" NAME="cat-' . $table . '-' . strtolower(str_replace(" ", "-", $category["type"]));
        echo '" align="center">' . $category["type"] . '</SPAN>';
    }

    $CurrentType = "";
    foreach($toppings as $topping){
        if($CurrentType != strtolower($topping["type"])){
            if($CurrentType){$CurrentType = endtoppings($table);}
            $CurrentType = endtoppings($table, $topping["type"]);
        }

        echo '<TR ID="tr-addon-' . $table . '-' . $topping["id"] . '" CLASS="tr-addon tr-addon-' . $table . '" TABLE="' . $table . '" SELECTED="" TOPPINGID="' . $topping["id"] . '" NAME="' . $topping["name"] .'"';
        if($topping["isfree"]){echo ' ISFREE="true"';}
        echo '>';
        $qualifiers = array("half" => "Easy", "single" => "Single", "double" => "Double", "triple" => "Triple");
        if($topping["qualifiers"]){
            $name = explode(",", $topping["qualifiers"]);
            $index = 0;
            foreach($qualifiers as $qualifier => $discard){
                $qualifiers[$qualifier] = "";
                if(isset($name[$index])){
                    $qualifiers[$qualifier] = trim($name[$index]);
                }
                $index++;
            }
        }
        foreach($qualifiers as $qualifier => $name){
            if($name){
                $class = " addon-" . $table . "-" . str_replace(" ", "-", strtolower($name)) . "-" . str_replace(" ", "-", strtolower($topping["name"])) ;
                if($qualifier != strtolower($name)){
                    $class .= " addon-" . $table . "-" . $qualifier . "-" . str_replace(" ", "-", strtolower($topping["name"])) ;
                }
                echo '<TD><LABEL><INPUT TYPE="RADIO" TABLE="' . $table . '" NAME="addon-' . $table . '-' . $topping["id"] . '" CLASS="addon addon-' . $table . $class . '" VALUE="' . $qualifier . '">' . $name . '</LABEL></TD>';
            }
        }

        echo '<TD CLASS="td-' . $table . '-name">' . $topping["name"] . '</TD></TR>';
    }
    endtoppings($table);
    echo '</DIV>';
    ?>
    <!--SCRIPT>
        addlistener(".addon-<?= $table ?>", "click", function(){
            var thevalue = this.value;
            var selected = attr( "#tr-" + this.name, "selected");
            if(selected == thevalue) {
                console.log( removeattr("input[name='" + this.name + "']", "checked") );
                attr("input[name='" + this.name + "']", "checked", false);
                thevalue = "";
            }
            attr("#tr-" + this.name, "selected", thevalue);
            if(!assimilate_enabled){return false;}
            innerHTML("#toppings", getaddons("", true));
            if(!thevalue) {return false;}
        });

        addlistener(".cat-header-<?= $table ?>", "click", function(){
            hide(".cat-<?= $table ?>");
            show("#" + attr(this, "name"));
        });

        trigger(".cat-headerid-<?= $table; ?>-0", "click");
    </SCRIPT-->
    <?php
    echo '<!-- END ' . $table . '--!>';
}
*/
?>
