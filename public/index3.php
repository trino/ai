<link rel="stylesheet" href="https://cdn.rawgit.com/twbs/bootstrap/v4-dev/dist/css/bootstrap.css">
<script src="https://cdn.rawgit.com/twbs/bootstrap/v4-dev/dist/js/bootstrap.js"></script>
<script src="../resources/assets/scripts/api.js"></script>
<script>
    var client = algoliasearch("J0FIP6YVFA", '9a0c07ff24eb7715a94de82b5a64dfd6');
    var index = client.initIndex('YourIndexName');
    index.search('something', function(success, hits) {
        console.log(success, hits)
    }, { hitsPerPage: 10, page: 0 });
</script>

<form method="get">
    <input type="search" id="search" name="search" size=60 value="<?php if(isset($_GET["search"])){ echo $_GET["search"];} ?>">
    <input type="button" id="startspeech" style="display:none;" value="Click to Speak" onclick="startButton(event);">
    <INPUT TYPE="submit" value="Search">
</form>

<style>
    body{padding:20px;}
    td,tr{border:0 !important;padding-top:0 !important;padding-bottom:0 !important;margin:0 !important;}
    div{border:1px solid #dadada;}
    .table-row{cursor: pointer;}
</style>

<?php
    function firstword($Text) {
        $Space = strpos($Text, " ");
        if ($Space === false) {
            return $Text;
        }
        return left($Text, $Space);
    }

    if (!isset($_GET["search"]) || !trim($_GET["search"])) {
        $_GET["search"] = "";
    } else {
        include("../resources/views/api.php");
        $con = connectdb("ai");

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

        $_GET["search"] = str_replace(" ",  "%' AND COL LIKE '%" , $_GET["search"]);
        $results["SQL"] = "SELECT * FROM menu WHERE item LIKE '%" . str_replace("COL", "item", $_GET["search"]) . "%' OR category LIKE '%" . str_replace("COL", "item", $_GET["search"]) . "%'";

        echo "<div class='col-md-4'>";

        print_r($results);
        echo '<br>------------------------------------<br>';
        $result = Query($results["SQL"]);
        $i = 0;

        if ($result) {
            $FirstResult = true;
            $PrevCategory = "";
            $Columns = array("category", "item", "price", "id", "Actions");

            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $row["Actions"] = '<A HREF="' . webroot("public/edit?id=") . $row["id"] . '">Edit</A>';

                if ($FirstResult) {
                    echo '<TABLE class="table table-sm table-responsive"><TR>';
                    $FirstResult = false;
                    foreach ($row as $Key => $Value) {
                        if (!is_numeric($Key) && in_array($Key, $Columns)) {
                            echo '<TH>' . $Key . '</TH>';
                        }
                    }
                    echo '</TR>';
                }

                echo '<TR>';
                foreach ($row as $Key => $Value) {
                    if (!is_numeric($Key) && in_array($Key, $Columns)){
                        if ($Key == 'category' && $Value != $PrevCategory) {
                            echo ' <TD> ' . $Value . ' </TD> ';
                            $PrevCategory = $Value;
                        } else if ($Key != 'category') {
                            echo ' <TD> ';

                            if ($Key == 'item') {
                                echo '<img src="' . webroot("resources/assets/images/pizza.png") . '"/ width="20"> ';
                            }

                            echo $Value . ' </TD>';
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

        echo "</div></div><div class='col-md-4'>";

        function makeSQL($table){
            $results = "SELECT * FROM " . $table;
            print_r($results);
            echo '<br>------------------------------------<br>';
            $result = Query($results);
            if ($result) {
                $FirstResult=true;
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    if ($FirstResult) {
                        echo '<TABLE cellpadding="5" class="table-' . $table . '" TABLEID="' . $table . '"><TR>';
                        $FirstResult = false;
                        foreach ($row as $Key => $Value) {
                            echo '<TH>' . $Key . '</TH>';
                        }
                        echo '</TR>';
                    }

                    echo '<TR ROWID="' . $row["id"] . '" CLASS="table-row table-' . $table . '-row table-' . $table . '-' . $row["id"] . '">';
                    foreach ($row as $Key => $Value) {
                        echo '<TD CLASS="table-td table-' . $table . '-' . $row["id"] . '-' . $Key . '"';
                        if(is_numeric($Value)){ echo ' ALIGN="RIGHT"'; }
                        echo '>' . $Value . '</TD>';
                    }
                    echo "</TR>";
                }
            }
            echo "</table></div>";
        }

        makeSQL("toppings");

        echo '<div class="col-md-4">';

        makeSQL("wings_sauce");
    }
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

    addlistener(".table-row", "click", function(event){
        var element = closest(event.target, "tr");
        var rowid = attr(element, "rowid");
        var tableid = attr(closest(element, "table"), "tableid");
        window.location = "<?= webroot(); ?>public/edittable?table=" + tableid + "&id=" + rowid;
    });
</script>