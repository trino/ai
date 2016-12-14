@extends('layouts_app')
@section('content')

    <?php
        startfile("index");
        if(!read("id")){
            echo view("popups_login")->render();
        } else {
    ?>

    <div class="row">
        <div class="col-md-8" style="padding: .75rem !important;">
            <?php
            if (islive()) {
                function like_match($pattern, $subject) {
                    $pattern = str_replace('%', '.*', preg_quote($pattern, '/'));
                    return (bool)preg_match("/^{$pattern}$/i", $subject);
                }

                $allowedIPs = array("24.36.%.%", "216.165.%.%", "45.58.85.42", "38.121.83.92", "184.151.178.135");
                $found = true;
                foreach ($allowedIPs as $pattern) {
                    if (like_match($pattern, $_SERVER["REMOTE_ADDR"])) {
                        $found = true;
                    }
                }
                if (!$found) {
                  //  die("IP " . $_SERVER["REMOTE_ADDR"] . " not recognized");
                }
            }

            //menu caching
            $doCache = true;//disabled for development
            $menucache_filename = resource_path() . "/menucache.html";
            $menucache_uptodate = isFileUpToDate("menucache", $menucache_filename);
            if ($menucache_uptodate && $doCache) {
                echo file_get_contents($menucache_filename);
            } else {
                $menu = view("popups_menu");
                if ($doCache) {
                    file_put_contents($menucache_filename, $menu);
                    setsetting("menucache", filemtime($menucache_filename));
                }
                echo '<!-- menu cache generated at: ' . now() . ' --> ' . $menu;
            }
            ?>
            <div class="row">
                <div class="col-md-12"><br></div>
            </div>
        </div>

        <div class="col-md-4">
            @include("popups_checkout")

            <?= view("popups_toppings"); ?>

        </div>
    </div>
    @include("popups_editprofile_modal")
    <?php }
        endfile("index");
        if(isset($GLOBALS["filetimes"])){// && !islive()){
            echo '<TABLE><TR><TH COLSPAN="2">File times</TH></TR>';
            foreach($GLOBALS["filetimes"] as $Index => $Values){
                echo '<TR><TD>' . $Index . '</TD><TD>';
                if(isset($Values["start"]) && isset($Values["end"])){
                    $val = round($Values["end"] - $Values["start"], 4);
                    if(strpos($val, ".") === false){
                        $val .= ".000";
                    } else {
                        $val = str_pad($val,4,"0");
                    }
                    echo $val . "s";
                } else {
                    echo "Unended";
                }
                echo '</TD></TR>';
            }
            echo '<TR><TD>Loaded</TD><TD ID="td_loaded"></TD></TR>';
            echo '<TR><TD>Ready</TD><TD ID="td_ready"></TD></TR>';
            echo '</TABLE>';
        }
    ?>
@endsection