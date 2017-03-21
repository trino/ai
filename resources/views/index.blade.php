@extends('layouts_app')
@section('content')

    <?php
        startfile("index");
        if(!read("id")){
            echo view("popups_login")->render();
        } else {
    ?>

    <div class="row shadow">
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

        <div class="col-lg-3 col-md-12 bg-inverse " style="border-right: 0 !important;">
            @include("popups_checkout")
        </div>

    </div>
    @include("popups_editprofile_modal")
    @if(read("id") && read("profiletype") <> 2)
        <div class="fixed-action-btn hidden-lg-up sticky-footer">
            <button class="fab bg-danger" onclick="window.scrollTo(0,document.body.scrollHeight);">
                <span class="white" id="checkout-total"></span>
            </button>
        </div>
    @endif

    @if(false)
        <style>
            * {
                padding: 3px;
            }
            input, select, textarea {
                border: 1px solid green !important;
                background: #dadada !important;
            }
            div {
                border: 1px solid orange !important;
            }
            .row {
                border: 1px solid blue !important;
            }
            div[class^="col-"], div[class*=" col-"] {
                border: 5px solid purple !important;
            }

            table {
                border: 1px solid yellow !important;
            }

            tr {
                border: 1px solid pink !important;
            }

            td {
                border: 1px solid black !important;
            }
        </style>
    @endif



    <?php }
        endfile("index");
    ?>

@endsection
