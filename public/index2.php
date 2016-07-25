<?php

function firstword($Text)
{
    $Space = strpos($Text, " ");
    if ($Space === false) {
        return $Text;
    }
    return left($Text, $Space);
}

if (!isset($_GET["search"]) || !trim($_GET["search"])) {
    $_GET["search"] = "";
} else {
    include("api.php");
    $con = connectdb("keywordtest");

    $results = array("SQL" => array(), "VARS" => array());
    $words = str_replace(" ", "|", $_GET["search"]);
    $result = Query("SELECT * FROM keywords WHERE synonyms REGEXP '" . $words . "';");

    while ($row = mysqli_fetch_array($result)) {
        $results[$row["id"]] = array(
            "word" => firstword($row["synonyms"]),
            "weight" => $row["weight"]
        );
        $results["SQL"][] = $row["id"];
        $results["VARS"][] = "SET @" . $results[$row["id"]]["word"] . " = " . $row["weight"] . ";";
    }

    $results["SQL"] = implode(",", $results["SQL"]);
    $results["VARS"] = implode(" ", $results["VARS"]);
    $results["SQL"] = "SELECT *, count(*) as keywords, SUM(weight) as totalweight FROM (SELECT menus.*, menus.id AS menuid, keywords.id as wordid, 
menukeywords.id as mkid, menuitem_id, keyword_id, synonyms, weight FROM menus, menukeywords,  keywords HAVING menuid=menuitem_id AND 
keyword_id IN (" . $results["SQL"] . ") AND keyword_id = wordid) results GROUP BY id;";

    var_dump($results);

    $result = Query($results["SQL"]);

    if ($result) {
        $FirstResult = true;
        while ($row = mysqli_fetch_array($result)) {
            if ($FirstResult) {
                echo '<TABLE BORDER="1"><TR>';
                foreach ($row as $Key => $Value) {
                    if (!is_numeric($Key)) {
                        echo '<TH>' . $Key . '</TH>';
                    }
                }
                echo '</TR>';
                $FirstResult = false;
            }

            echo '<TR>';
            foreach ($row as $Key => $Value) {
                if (!is_numeric($Key)) {
                    echo '<TD>' . $Value . '</TD>';
                }
            }
            echo '</TR>';
        }
        if (!$FirstResult) {
            echo '</TABLE><P>';
        }
    } else {
        echo "No keywords found in '" . $_GET["search"] . "'<P>";
    }
}
?>
<form method="get">
    <input type="search" id="search" name="search" size=60 value="<?= $_GET["search"]; ?>">
    <input type="button" id="startspeech" style="display:none;" value="Click to Speak" onclick="startButton(event);">
    <INPUT TYPE="submit" value="Search">
</form>
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
            document.getElementById("search").value = transcript;
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
            if (callback) {
                callback();
            }
        };

        u.onerror = function (e) {
            if (callback) {
                callback(e);
            }
        };

        speechSynthesis.speak(u);
    }
</script>