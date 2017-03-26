<!DOCTYPE html>
<html lang="en" class="full">
<head>
    <?php
    $time = microtime(true); // Gets microseconds
    if (read("id")) {
        $user = getuser(false);
        if (!$user) {//check for deleted user
            unset($user);
            write("id", false);
        } else {
            unset($user["password"]);
        }
    }
    $scripts = webroot("public/scripts");
    $css = webroot("public/css");
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
    <link rel="icon" sizes="128x128" href="<?= webroot("public/images/pizza128.png"); ?>">
    <link rel="icon" sizes="192x192" href="<?= webroot("public/images/pizza192.png"); ?>">

    <link href="<?= $css; ?>/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href='<?= $css; ?>/Roboto.css' rel='stylesheet' type='text/css'>
    <link href='<?= $css; ?>/Roboto-slab.css' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" href="<?= $css; ?>/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $css . "/custom3.css?v=" . time(); ?>">


    <script src="<?= $scripts; ?>/jquery.min.js"></script>
    <script src="<?= $scripts; ?>/tether.min.js"></script>
    <script src="<?= $scripts; ?>/bootstrap.min.js"></script>
    <SCRIPT SRC="<?= $scripts; ?>/jquery.validate.min.js"></SCRIPT>
    @include("popups_alljs")
</head>
<body>
<div class="modal loading" ID="loadingmodal"></div>

<div class="container-fluid list-group-item bg-danger " style="border-bottom: 5px solid #dadada !important;">
    <button data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="" class="btn btn-sm pull-right">
        <i class="fa fa-bars text-white"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-left">
        @if(read("id"))
            <SPAN class="loggedin profiletype profiletype1">
                        <?php
                foreach (array("users", "restaurants", "useraddresses", "orders", "additional_toppings", "actions") as $table) {
                    echo '  <li><A HREF="' . webroot("public/list/" . $table) . '" CLASS="dropdown-item"><i class="fa fa-user-plus"></i> ' . ucfirst($table) . ' list</A></li>';
                }
                ?>
                <li><A HREF="<?= webroot("public/editmenu"); ?>" CLASS="dropdown-item"><i class="fa fa-user-plus icon-width"></i> Edit Menu</A></li>
                        <li><A HREF="<?= webroot("public/list/debug"); ?>" CLASS="dropdown-item"><i class="fa fa-user-plus icon-width"></i> Debug log</A></li>
                    </SPAN>
            <SPAN class="loggedin">
                        <li id="profileinfo">
                            <A data-toggle="modal" data-target="#profilemodal" href="#" class="dropdown-item">
                                <i class="fa fa-user icon-width"></i> <SPAN CLASS="session_name"></SPAN>
                            </A>
                        </li>
                        <li class="profiletype_not profiletype_not2"><A ONCLICK="orders();" class="dropdown-item" href="#"><i class="fa fa-clock-o icon-width"></i> Past Orders</A></li>
                        <LI><A ONCLICK="handlelogin('logout');" CLASS="dropdown-item" href="#"><i class="fa fa-home icon-width"></i> Log Out</A></LI>
                    </SPAN>
        @endif
        <SPAN class="loggedout">
                    <LI><A CLASS="" href="<?= webroot(""); ?>"><i class="fa fa-user icon-width text-white"></i> Log In</A></LI>
                </SPAN>
        <LI><A CLASS="dropdown-item" href="help"><i class="fa fa-question-circle icon-width"></i> Info</A></LI>
    </ul>
    <a HREF="<?= webroot("public/index"); ?>" onclick="history.go(0);" class="ml-3 align-middle text-white  pull-left" style="font-weight: bold;font-size: 1rem !important;" href="/">londonpizza.ca</a>

</div>

<div class="container-fluid">
    @yield('content')
</div>

<div class="container-fluid hidden-sm-down list-group-item">
    <div class="row">
        <div class="col-sm-12">
            <a CLASS="btn btn-sm text-white" href="<?= webroot("help"); ?>"> <i style="font-size: 1rem !important;" class="fa fa-question-circle icon-width"></i> Info</a>
        </div>
    </div>
</div>


<!--div class="c-rays">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 335">
        <path fill="#FA0028" d="M.2 218.7l503.5-44.1L.2 0z"></path>
        <path fill="#FF5959" d="M.2 207.7v67.5l503.5-100.6z"></path>
        <path fill="#FF9E16" d="M503.7 174.6l936.3 85.3V88z"></path>
        <path fill="#FFD700" d="M503.7 174.6L1440 88V22.3z"></path>
        <path fill="#95D600" d="M.2 320.1v13.4H218l285.7-158.9z"></path>
        <path fill="#00AC41" d="M.2 271.2v48.9l503.5-145.5z"></path>
        <path fill="#7DCAEC" d="M968.4 333.5H1377L503.7 174.6z"></path>
        <path fill="#2F7DE1" d="M439.5 333.5h323.4L503.7 174.6z"></path>
        <path fill="#E2E71F" d="M184.8 333.5h-36 290.7l64.2-158.9z"></path>
        <path fill="#2BACE4" d="M762.9 333.5h205.5L503.7 174.6z"></path>
        <path fill="#FF5000" d="M1377 333.5h63v-80.6l-936.3-78.3z"></path>
    </svg>
</div-->


</body>
@if(false)
    <style>
        * {
            border: 1px solid orange !important;
        }

        input, select, textarea {
            border: 1px solid green !important;
            background: #dadada !important;
        }

        div {
            border: 1px solid #dadada !important;
        }

        .row {
            border: 1px solid blue !important;
        }

        div[class^="col-"], div[class*=" col-"] {
            border: 1px solid red !important;
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

        * {
            /*
            padding: 4px;
            border-radius: .5rem;
            */
        }
    </style>
@endif

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