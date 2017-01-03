<!DOCTYPE html>
<html lang="en" class="full">
    <head>
        <?php
            $time = microtime(true); // Gets microseconds
            if (read("id")) {
                $user = getuser(read("id"));
                unset($user["password"]);
            }
        ?>
        <script type="text/javascript">
            var timerStart = Date.now();
            var currentURL = "<?= Request::url(); ?>";
            var token = "<?= csrf_token(); ?>";
            var webroot = "<?= webroot("public/"); ?>";
            var redirectonlogout = false;
            var redirectonlogin = false;
            var addresskeys = ["id", "value", "user_id", "number", "unit", "buzzcode", "street", "postalcode", "city", "province", "latitude", "longitude", "phone"];
            var userdetails = false;
            var currentRoute = "<?= Route::getCurrentRoute()->getPath(); ?>";
        </script>

        <meta charset="utf-8">
        <meta name="theme-color" content="#d9534f">
        <meta name="viewport" content="width=device-width, user-scalable=no">
        <meta name="viewport" content="width=device-width, user-scalable=no">
        <meta http-equiv="content-language" content="en-CA">
        <meta name="mobile-web-app-capable" content="yes">

        <link rel="manifest" href="<?= webroot("resources/assets/manifest.json"); ?>">
        <link rel="icon" sizes="128x128" href="<?= webroot("resources/assets/images/pizza128.png"); ?>">
        <link rel="icon" sizes="192x192" href="<?= webroot("resources/assets/images/pizza192.png"); ?>">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Roboto+Slab' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css">

        <link rel="stylesheet" href="<?= webroot("public/custom3.css?v=") . time(); ?>">

        <script src="<?= webroot("resources/views/jquery.min.js"); ?>"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js"></script>
        <SCRIPT SRC="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></SCRIPT>
        @include("popups_alljs")
    </head>

    <body>

    <?= view("popups_navbar")->render(); ?>

    <div class="container" style="background: #d9534f !important;">
        @yield('content')
        <div class="clearfix"></div>
    </div>

    <?= view("popups_sticky_footer")->render(); ?>

    <div class="modal loading" ID="loadingmodal"></div>

    <div id="snackbar">Order Updated</div>

    <div class="pa-1 bg-danger"> &nbsp;</div>

    </body>

    <script type="text/javascript">
        $(window).load(function () {
            var time = Date.now() - timerStart;
            $("#td_loaded").text(time / 1000 + "s");
            console.log("Time until everything loaded: ", time);
        });
        $(document).ready(function () {
            var time = Date.now() - timerStart;
            $("#td_ready").text(time / 1000 + "s");
            console.log("Time until DOMready: ", time);
            $("#navbar-text").text("<?= "" . round((microtime(true) - $time), 5) . "s"; ?>");
        });
    </script>

    <div style="display: none;">
        <?php
        if (isset($GLOBALS["filetimes"])) {
            // && !islive()){
            echo '<TABLE><TR><TH COLSPAN="2">File times</TH></TR>';
            $total = 0;
            foreach ($GLOBALS["filetimes"] as $Index => $Values) {
                echo '<TR><TD>' . $Index . '</TD><TD>';
                if (isset($Values["start"]) && isset($Values["end"])) {
                    $val = round($Values["end"] - $Values["start"], 4);
                    if (strpos($val, ".") === false) {
                        $val .= ".000";
                    } else {
                        $val = str_pad($val, 4, "0");
                    }
                    echo $val . "s";
                    $total += $val;
                } else {
                    echo "Unended";
                }
                echo '</TD></TR>';
            }
            $total = str_pad(round($total, 4), 5, "0");
            echo '<TR><TD>Total</TD><TD>' . $total . 's</TD></TR>';
            echo '<TR><TD>DOM Loaded</TD><TD ID="td_loaded"></TD></TR>';
            echo '<TR><TD>DOM Ready</TD><TD ID="td_ready"></TD></TR>';
            echo '</TABLE>';
        }
        ?>
    </div>
</html>