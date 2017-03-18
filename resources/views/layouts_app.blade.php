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


    <style>
        .modal-backdrop {
            background: #000;
            opacity: .9 !important;
        }

        .input_left_icon {
            width: 15%;
            text-align: center;
            padding-top: 5px;
            float: left
        }

        .input_right {
            width: 85%;
            float: left
        }

        input, select, textarea {
            border: 0 !important;
            border-bottom: 1px solid #dadada !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            border-radius: 0 !important;
            -webkit-appearance: none;
            -moz-appearance: none;
        }

        .fab {
            border: none;
            color: white;
            width: 70px;
            height: 70px;
            padding: 1px 1px;
            line-height: 1.33;
            border-radius: 35px;
            margin: auto;
            -webkit-box-shadow: 2px 3px 3px 0px rgba(41, 41, 41, .3);
            -moz-box-shadow: 2px 3px 3px 0px rgba(41, 41, 41, .3);
            box-shadow: 2px 3px 3px 0px rgba(41, 41, 41, .3);
        }

        .fab:hover {
            background-color: #ff4060;
        }

        .fixed-action-btn {
            position: fixed;
            right: 10px;
            bottom: 5px !important;
            padding-top: 15px;
            margin-bottom: 0;
            z-index: 998;
        }

        .btn-circle {
            width: 30px;
            height: 30px;
            text-align: center;
            padding: 6px 0;
            font-size: 12px;
            line-height: 1.428571429;
            border-radius: 15px;
        }

        .btn-circle.btn-lg {
            width: 50px;
            height: 50px;
            padding: 10px 16px;
            font-size: 18px;
            line-height: 1.33;
            border-radius: 25px;
        }

        .btn-circle.btn-xl {
            width: 70px;
            height: 70px;
            padding: 10px 16px;
            font-size: 24px;
            line-height: 1.33;
            border-radius: 35px;
        }

        .dont-show {
            display: none;
        }

        .sprite-none {
            background-position: 110px 110px;
        }

        .sprite-pizza {
            background-position: 0px 0px;
        }

        .sprite-salad {
            background-position: -96px 0px;
        }

        .sprite-potato-wedges {
            background-position: -192px 0px;
        }

        .sprite-panzerotti {
            background-position: -288px 0px;
        }

        .sprite-onion-rings {
            background-position: -384px 0px;
        }

        .sprite-wings {
            background-position: -480px 0px;
        }

        .sprite-241_pizza {
            background-position: -576px 0px;
        }

        .sprite-lasagna {
            background-position: -672px 0px;
        }

        .sprite-dips {
            background-position: -768px 0px;
        }

        .sprite-garlic-bread {
            background-position: -864px 0px;
        }

        .sprite-french-fries {
            background-position: -960px 0px;
        }

        .sprite-drinks {
            background-position: -1056px 0px;
        }

        .sprite-chicken-nuggets {
            background-position: -1152px 0px;
        }

        .sprite-veggie-sticks {
            background-position: -1248px 0px;
        }

        .sprite-pepsi {
            background-position: -1344px 0px;
        }

        .sprite-diet-pepsi {
            background-position: -1440px 0px;
        }

        .sprite-2l-sprite {
            background-position: -1536px 0px;
        }

        .sprite-sprite {
            background-position: -1632px 0px;
        }

        .sprite-water-bottle {
            background-position: -1728px 0px;
        }

        .sprite-2l-coca-cola {
            background-position: -1824px 0px;
        }

        .sprite-crush-orange {
            background-position: -1920px 0px;
        }

        .sprite-ginger-ale {
            background-position: -2016px 0px;
        }

        .sprite-diet-coca-cola {
            background-position: -2112px 0px;
        }

        .sprite-nestea {
            background-position: -2208px 0px;
        }

        .sprite-2l-brisk-iced-tea {
            background-position: -2304px 0px;
        }

        .sprite-dr-pepper {
            background-position: -2400px 0px;
        }

        .sprite-medium {
            zoom: 0.6;
            -moz-transform: scale(0.6);
            -moz-transform-origin: 0 0;
        }

        .sprite-small {
            zoom: 0.29;
            -moz-transform: scale(0.29);
            -moz-transform-origin: 0 0;
        }

        .sprite-tiny {
            zoom: 0.20;
            -moz-transform: scale(0.20);
            -moz-transform-origin: 0 0;
        }

        .c-rays {
            width: 100%;
            padding-top: 40%;
            position: relative;
            overflow: hidden
        }

        @media (min-width: 48em) {
            .c-rays {
                padding-top: 23.5%
            }
        }

        .c-rays svg {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%
        }

        @media (min-width: 48em) {
            .c-rays svg {
                min-width: 100%
            }
        }

        .loader {
            visibility: hidden;
            background-color: #000;
            z-index: 10000;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            position: fixed;
            opacity: .9;
        }

        .loader:after {
            z-index: 10001;
            display: block;
            position: absolute;
            content: "";
            left: -200px;
            width: 200px;
            height: 5px;
            background-color: #000;
            animation: loading 1s linear infinite;
        }

        @keyframes loading {
            from {
                left: -200px;
                width: 30%;
            }
            50% {
                width: 30%;
            }
            70% {
                width: 70%;
            }
            80% {
                left: 50%;
            }
            95% {
                left: 120%;
            }
            to {
                left: 100%;
            }
        }

        /************************************ end loader */

        /************************************ fade in */

        @keyframes fadein {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @-moz-keyframes fadein { /* Firefox */
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @-webkit-keyframes fadein { /* Safari and Chrome */
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @-o-keyframes fadein { /* Opera */
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /************************************ end fade in */

    </style>

    <script src="<?= $scripts; ?>/jquery.min.js"></script>
    <script src="<?= $scripts; ?>/tether.min.js"></script>
    <script src="<?= $scripts; ?>/bootstrap.min.js"></script>
    <SCRIPT SRC="<?= $scripts; ?>/jquery.validate.min.js"></SCRIPT>
    @include("popups_alljs")
</head>

<body>


<div class="modal loading" ID="loadingmodal"></div>


<div class="container-fluid bg-danger list-group-item text-white">
<button data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background: transparent" class="btn btn-sm pull-right ">
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
<li>
<A HREF="<?= webroot("public/editmenu"); ?>" CLASS="dropdown-item"><i class="fa fa-user-plus icon-width"></i> Edit Menu</A>
</li>
<li>
<A HREF="<?= webroot("public/list/debug"); ?>" CLASS="dropdown-item"><i class="fa fa-user-plus icon-width"></i> Debug log</A>
</li>
</SPAN>
<SPAN class="loggedin">
<li id="profileinfo">
<A data-toggle="modal" data-target="#profilemodal" href="#" class="dropdown-item">
<i class="fa fa-user icon-width"></i> <SPAN CLASS="session_name"></SPAN>
</A>
</li>
<li class="profiletype_not profiletype_not2">
<A ONCLICK="orders();" class="dropdown-item" href="#"><i class="fa fa-clock-o icon-width"></i> Past Orders</A>
</li>
<LI>
<A ONCLICK="handlelogin('logout');" CLASS="dropdown-item" href="#"><i class="fa fa-home icon-width"></i> Log Out</A>
</LI>
</SPAN>
@endif
<SPAN class="loggedout">
<LI>
<A CLASS="dropdown-item" href="<?= webroot(""); ?>"><i class="fa fa-user icon-width"></i> Log In</A>
</LI>
</SPAN>
<LI>
<A CLASS="dropdown-item" href="help"><i class="fa fa-question-circle icon-width"></i> More Info</A>
</LI>
</ul>
<a HREF="<?= webroot("public/index"); ?>" onclick="history.go(0);" class="ml-2 align-middle text-white pull-left londonpizza" href="/">
londonpizza.ca
</a>
<span class="align-middle rounded sprite sprite-wings sprite-medium " style="visibility: hidden"></span>
</div>
<div class="container-fluid">
    @yield('content')
</div>

<div class="container-fluid hidden-sm-down">
    <div class="row">
        <div class="col-sm-12 py-3">
            <div class="pull-left">
                <span class="text-white text-muted">&copy; 2017</span>
            </div>
            <div class="pull-left">
                <A CLASS="text-white pl-3 text-muted" href="<?= webroot("help"); ?>">Info</A>
            </div>
        </div>
    </div>
</div>


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