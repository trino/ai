@extends('layouts_app')
@section('content')

    <?php
        if(!read("id")){
            echo view("popups_login")->render();
        } else {
    ?>

    <div class="row">
        <div class="col-md-8">
            <?php
                if (islive()) {
                    function like_match($pattern, $subject) {
                        $pattern = str_replace('%', '.*', preg_quote($pattern, '/'));
                        return (bool) preg_match("/^{$pattern}$/i", $subject);
                    }
                    $allowedIPs = array("24.36.%.%", "216.165.%.%", "45.58.85.42", "38.121.83.92", "184.151.178.135");
                    $found = false;
                    foreach($allowedIPs as $pattern){
                        if(like_match($pattern, $_SERVER["REMOTE_ADDR"])){
                            $found = true;
                        }
                    }
                    if (!$found) {
                        die("IP " . $_SERVER["REMOTE_ADDR"] . " not recognized");
                    }
                }

                //menu caching
                $doCache = false;//disabled for development
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
        </div>
        <div class="col-md-4">


            <div class="card text-white bg-danger " style="margin: 0 !important;border-radius: 0 !important;">
                <div class="card-block ">
                    <div class="row">
                        <div class="col-md-12">
                            <a class="pull-right text-white "
                               ONCLICK="confirm2('Are you sure you want to clear your order?', 'Clear Order', function(){clearorder();});">
                                <i class="fa fa-close"></i>
                            </a>

                            <h5 class="pull-left text-white">
                                My Order
                            </h5>
                            <div class="clearfix" style="margin:.5rem 0 1rem 0 !Important;"></div>
                            <div id="myorder"></div>
                        </div>
                    </div>

                    <div ID="checkoutbutton"></div>


                    <div id="checkout-btn" class="row  my-1">
                        <div class="col-md-12">
                            <button class="btn btn-warning pull-left" onclick="showcheckout();">
                                CHECKOUT
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            @include("popups_checkout")
            <?= view("popups_toppings"); ?>
        </div>
    </div>
    @include("popups_editprofile_modal")
    <?php } ?>
@endsection