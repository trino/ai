@extends('layouts_app')
@section('content')
    <?php
        startfile("index");
        if(!read("id")){
            echo popups_view("login");
        }
    ?>
    <div class="row">
        <?php
            //menu caching
            $doCache = $GLOBALS["settings"]["domenucache"];
            $menucache_filename = resource_path() . "/menucache.html";
            $menublade_filename = resource_path() . "/views/popups_menu.blade.php";
            $menucache_uptodate = isFileUpToDate("menucache", $menucache_filename) && !isFileUpToDate("menucache", $menublade_filename);
            if ($menucache_uptodate && $doCache) {
                echo '<!-- menu cache pre-generated at: ' . filemtime($menucache_filename) . ' --> ' . file_get_contents($menucache_filename);
            } else {
                $menu = popups_view("menu");
                if ($doCache) {
                    file_put_contents($menucache_filename, $menu);
                    setsetting("menucache", filemtime($menucache_filename));
                }
                echo '<!-- menu cache generated at: ' . now() . ' --> ' . $menu;
            }
        ?>
        <div class="col-lg-3 col-md-12 bg-inverse" style="background: #dcdcdc !important;">
        </div>
    </div>
    @if(read("id") && read("profiletype") <> 2)
        <div class="fixed-action-btn hidden-lg-up sticky-footer">
            <button class="fab bg-danger" onclick="window.scrollTo(0,document.body.scrollHeight);">
                <span class="white" id="checkout-total"></span>
            </button>
        </div>
    @endif
    <?php
        endfile("index");
    ?>
@endsection