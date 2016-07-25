<link rel="stylesheet" href="https://cdn.rawgit.com/twbs/bootstrap/v4-dev/dist/css/bootstrap.css">
<script src="https://cdn.rawgit.com/twbs/bootstrap/v4-dev/dist/js/bootstrap.js"></script>


<form method="get">
    <input type="search" id="search" name="search" size=60 value="<?= $_GET["search"]; ?>">
    <input type="button" id="startspeech" style="display:none;" value="Click to Speak" onclick="startButton(event);">
    <INPUT TYPE="submit" value="Search">
</form>

<style>
body{padding:20px;}
    td,tr{border:0 !important;padding-top:0 !important;padding-bottom:0 !important;margin:0 !important;}
    div{border:1px solid #dadada;}
</style>
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

    $word = $_GET["search"];
    $words = str_replace(" ", "|", $_GET["search"]);
    /*
      $results["SQL"] = "
      SELECT
      r.name as restaurant_name,
      c.name as category_name,
      i.name as item_name, i.description as description,i.price as price,
      ca.name as category_addon_name,
      a.name as addon_name

      from restaurants r
      inner join categories c         on r.id = c.restaurant_id
      inner join items i              on c.id = i.category_id
      inner join addon_categories ca  on i.id = ca.item_id
      inner join addons a             on ca.id = a.addon_category_id

      where r.name REGEXP '$words'
      or c.name REGEXP '$words'
      or i.name REGEXP '$words'
      or ca.name REGEXP '$words'
      or a.name REGEXP '$words'
    ";
*/
    $results["SQL"] = "SELECT *
 from menu";

    echo "<div class='col-md-4'>";

    print_r($results);
    echo '<br>------------------------------------<br>';
    $result = Query($results["SQL"]);
    $result = Query($results["SQL"]);
    $i = 0;

    if ($result) {
        $FirstResult = true;
        $PrevCategory = "";

        while ($row = mysqli_fetch_array($result)) {
            if ($FirstResult) {
                echo '<TABLE class="table table-sm table-responsive"><TR>';
                $FirstResult = false;
            }

            foreach ($row as $Key => $Value) {

                if (!is_numeric($Key) && ($Key == "category" || $Key == "item" || $Key == "price" || $Key == "id")) {
                    if ($Key == 'category' && $Value != $PrevCategory) {
                        echo ' <TD> ' . $Value . ' </TD> ';
                        $PrevCategory = $Value;
                    } else if ($Key != 'category') {
                        echo ' <TD> ';

                        if ($Key == 'item') {
                            echo '<img src="http://orderpizzaplace.com/SiteFiles/124/Menu/PepperoniPizza350.png"/ width="20"> ';
                        }

                        echo  $Value . ' </TD>';
                    } else {
                        echo ' <TD></TD> ';
                    }
                }
            }
            echo '</TR>';
            $i++;
        }
        if (!$FirstResult) {
            echo '</TABLE>';
        }
    } else {
        echo "No keywords found in '" . $_GET["search"] . "'<P>";
    }

    echo "</div>";

}
echo "</div>";

echo "<div class='col-md-4'>";

$results["SQL"] = "SELECT *
 from toppings";
print_r($results);
echo '<br>------------------------------------<br>';
$result = Query($results["SQL"]);
if ($result) {
    while ($row = mysqli_fetch_array($result)) {
        foreach ($row as $Key => $Value) {
            if (!is_numeric($Key) && ($Key == "topping" || $Key == "id")) {
                echo $Value . ' ';
            }
        }
        echo "<br>";

    }

}
echo "</div>";

echo "<div class='col-md-4'>";

$results["SQL"] = "SELECT *
 from wings_sauce";
print_r($results);
echo '<br>------------------------------------<br>';
$result = Query($results["SQL"]);
$result = Query($results["SQL"]);
if ($result) {
    while ($row = mysqli_fetch_array($result)) {
        foreach ($row as $Key => $Value) {
            if (!is_numeric($Key) && ($Key == "sauce" || $Key == "id")) {
                echo $Value . ' ';
            }
        }
        echo "<br>";
    }

}

echo "</div>";

?>


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