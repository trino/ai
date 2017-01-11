@extends('layouts_app')
@section('content')

    <?php
        startfile("index");
        if(!read("id")){
            echo view("popups_login")->render();
        } else {
    ?>

    <div class="row" style="border:10px solid #fff !important;">
            <?php
                if (islive()) {
                    function like_match($pattern, $subject)
                    {
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
                $doCache = false;//disabled for development
                $menucache_filename = resource_path() . "/menucache.html";
                $menublade_filename = resource_path() . "/views/popups_menu.blade.php";
                $menucache_uptodate = isFileUpToDate("menucache", $menucache_filename) && !isFileUpToDate("menucache", $menublade_filename);
                if ($menucache_uptodate && $doCache) {
                    echo '<!-- menu cache pre-generated at: ' . filemtime($menucache_filename) . ' --> ' . file_get_contents($menucache_filename);
                } else {
                    $menu = view("popups_menu");
                    if ($doCache) {
                        file_put_contents($menucache_filename, $menu);
                        setsetting("menucache", filemtime($menucache_filename));
                    }
                    echo '<!-- menu cache generated at: ' . now() . ' --> ' . $menu;
                }
            ?>

        <div class="col-md-3" style="margin-left: 15px;">
            @include("popups_checkout")
        </div>

    </div>
    @include("popups_editprofile_modal")
    <?php }
        endfile("index");
    ?>

@endsection