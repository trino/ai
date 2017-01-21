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
        <link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">


        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
        <link rel="stylesheet" href="<?= webroot("resources/views/custom3.css?v=") . time(); ?>">
        <script src="<?= webroot("resources/views/jquery.min.js"); ?>"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
        <SCRIPT SRC="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></SCRIPT>
        @include("popups_alljs")
    </head>

    <body>
        <?= view("popups_navbar")->render(); ?>
        <div class="container-fluid  py-3" style="">
            @yield('content')
        </div>

        <?= view("popups_sticky_footer")->render(); ?>

        <div class="modal loading" ID="loadingmodal"></div>

        <div id="snackbar">Order Updated</div>

            <nav class="navbar shadow fixed-bottom" style="">
            <div class="container-fluid">
                <a href="#" class="text-white">About</a>
                <!--div class="row">
                    <div class="col-6 col-sm-3">
                        <h2 class="text-uppercase">More</h2>
                        <ul class="list-unstyled">
                            <li><a href="//theme.cards" title="Free themes">Theme.cards</a></li>
                            <li><a href="//www.bootply.com" title="Bootstrap Editor">Bootply</a></li>
                            <li><a href="//www.codeply.com" title="Frontend Editor Online">Codeply</a></li>
                            <li><a href="//www.wdstack.com" title="Best Developer Designer Resources">WDStack</a></li>
                        </ul>
                    </div>
                    <div class="col-6 col-sm-3 column">
                        <h2 class="text-uppercase">About</h2>
                        <ul class="list-unstyled">
                            <li><a href="#" data-toggle="modal" data-target="#alertModal">Contact Us</a></li>
                            <li><a href="#" data-toggle="modal" data-target="#alertModal">Delivery Information</a></li>
                            <li><a href="#" data-toggle="modal" data-target="#alertModal">Privacy Policy</a></li>
                            <li><a href="#" data-toggle="modal" data-target="#alertModal">Terms &amp; Conditions</a></li>
                        </ul>
                    </div>
                    <div class="col-12 col-sm-3 column">
                        <h2 class="text-uppercase">Stay Posted</h2>
                        <form>
                            <div class="form-group">
                                <input type="text" class="form-control" title="No spam, we promise!" placeholder="Tell us your email">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#alertModal" type="button">Subscribe for updates</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-12 col-sm-3 text-xs-right">
                        <h2 class="text-uppercase">Follow</h2>
                        <ul class="list-inline">
                            <li class="list-inline-item"><a rel="nofollow" href="" title="Twitter"><i class="icon-lg ion-social-twitter-outline"></i></a>&nbsp;</li>
                            <li class="list-inline-item"><a rel="nofollow" href="" title="Facebook"><i class="icon-lg ion-social-facebook-outline"></i></a></li>
                        </ul>
                    </div>
                </div-->

            </div>
        </nav>

















    @if(false)


    <style>

































































        .form-group {
            margin-bottom: .5rem
        }
        .text-muted{color:#9ca3a9 !important;}
        .pr-05 {
            padding-right: .5rem;
        }
        .btn{line-height: 1rem !important;}
        .pr-025 {
            padding-right: .25rem;
        }

        .mr-05 {
            margin-right: .5rem;
        }

        .py-1 {
            line-height: 1rem;
        }

        .fa-stack-1x, .fa-1x {
            font-size: 1.5rem !important;
        }

        .tag {
            font-weight: normal
        }

        .dropdown-item {
            padding: .5rem 1.25rem !important;
        }

        .navbar, .dropdown-menu, .shadow ,.ui-autocomplete{
            border: 0 !important;
            box-shadow: 0 2px 5px 0 rgba(0, 0, 0, .16), 0 2px 10px 0 rgba(0, 0, 0, .12);
        }

        .navbar {
            line-height: 1rem !important;
            padding: 1rem 1.25rem !important;
            border-radius: 0 !important;
            background: #00467f !important;
        }

        body {
            /*  prevents horizontal scroll for full width container-fluid */
            overflow-x: hidden !important;
            color: #373a3c;
            background: #fbfbfb; font-family: 'Roboto Slab', serif;
            font-family: 'Roboto', sans-serif;

            animation: fadein 1s;
            -moz-animation: fadein 1s; /* Firefox */
            -webkit-animation: fadein 1s; /* Safari and Chrome */
            -o-animation: fadein 1s; /* Opera */
        }

        label {
            /*
            font-family: 'Roboto Slab', serif;
            */
            color: #9ca3a9 !important;
            margin-right: .25rem !important;
            margin-bottom: 0 !important;
            font-size: 80% !important;
        }

        .ellipsis {
            display: block;
            display: -webkit-box;
            -webkit-line-clamp: 30;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis !important;
        }

        .ellipsis-80 {
            max-width: 85%;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }

        .ellipsis-3-line {
            display: block; /* Fallback for non-webkit */
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis !important;
        }

        /*
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        dont change anything below
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        */

        /*
        .card-header:first-child, .list-group-item:first-child {
        border-radius: 0 !important;
        }
        .card-block, .card {
        border-radius: 0;
        }
        */



        .card-footer {
            border-top: 0 !important;
            border-bottom: 1px solid #eceeef;
        }

        .text-secondary {
            color: #ddd;
        }

        .bg-secondary {
            background: #ddd !important;
        }

        .tag-secondary {
            background: #ddd !important;
        }

        .icon-width {
            width: 21px;
            text-align: left;
            margin-right: 5px !important;
        }

        @font-face {
            font-family: Roboto;
            src: url('fonts/Roboto-Regular.ttf');
        }

        @font-face {
            font-family: Roboto Slab;
            src: url('fonts/RobotoSlab-Regular.ttf');
        }

        pre.sf-dump {
            z-index: 100 !important;
            margin-bottom: 0 !important;
            font-size: .75rem !important;
            padding: 1.25rem !important;
        }

        .table thead th {
            /*
            border-bottom: 0 !important;
            font-weight: bold;
            */
        }

        .list-group-item {

            border-color: #eceeef;
        }

        .list-group-item-action {
            border-top: 1px solid #efefef;
        }

        .tab-content {
            border-left: 1px solid #efefef;
            border-right: 1px solid #efefef;
            border-bottom: 1px solid #efefef;
            margin-bottom: 1rem !important;
        }

        .docs_design {
            padding: 1rem !important;
            line-height: 1.5rem;
            font-size: 1rem;
        }

        .nav-tabs .nav-item.open .nav-link, .nav-tabs .nav-item.open .nav-link:focus, .nav-tabs .nav-item.open .nav-link:hover, .nav-tabs .nav-link.active, .nav-tabs .nav-link.active:focus, .nav-tabs .nav-link.active:hover {
            color: #55595c;
            background-color: #fff;
            border-color: #eceeef #eceeef transparent;
        }

        .nav-tabs {
            border-bottom: 1px solid #eceeef;
        }

        .end_date_start_border {
            /*  border-top: 13px solid #fafafa !important;  */
        }

        .media, .media-body {
            overflow: visible !important;
        }

        .modal {
            /*modal scrollbar shift*/
            overflow-y: auto;
        }

        .modal-open {
            overflow: auto;
        }

        .modal-open[style] {
            padding-right: 0px !important;
        }

        a:focus, .btn:focus, select:focus {
            outline: none;
        }

        .alert {
            text-align: center;
            border: 0 !important;
            border-radius: 0 !important;
        }

        .disable_button {
            pointer-events: none;
            cursor: default;
        }

        .no-wrap {
            text-wrap: none;
            white-space: nowrap;
        }

        canvas {
            border-bottom: 0px solid #eceeef !important;
        }

        .error {
            color: #d9534f;
        }

        a, a:hover, a:visited, a:link, a:active {
            text-decoration: none !important;
        }

        .card-columns {
            column-count: 2;
        }

        .activity_border_top {
            border-top: 1px solid #eceeef;
        }

        .fa-chevron-right, .fa-chevron-down {
            color: #dadada !important;
        }

        .fa-circle-o, .fa-dot-circle-o {
            padding-right: 0rem !important;
        }

        a[aria-expanded=true] .fa-chevron-right {
            display: none;
        }

        a[aria-expanded=false] .fa-chevron-down {
            display: none;
        }

        /************************************ radio and checkbox */

        input[type="checkbox"], input[type="radio"] {
            display: none;
        }

        label input[type="radio"] ~ i.fa.fa-circle-o {
            color: #c8c8c8;
            display: inline !important
        }

        label input[type="radio"] ~ i.fa.fa-dot-circle-o {
            display: none !important
        }

        label input[type="radio"]:checked ~ i.fa.fa-circle-o {
            display: none !important
        }

        label input[type="radio"]:checked ~ i.fa.fa-dot-circle-o {
            color: #0275d8;
            display: inline !important
        }

        label:hover input[type="radio"] ~ i.fa {
            color: #0275d8;
        }

        label input[type="checkbox"] ~ i.fa.fa-square-o {
            color: #c8c8c8;
            display: inline !important
        }

        label input[type="checkbox"] ~ i.fa.fa-check-square-o {
            display: none !important;
        }

        label input[type="checkbox"]:checked ~ i.fa.fa-square-o {
            display: none !important
        }

        label input[type="checkbox"]:checked ~ i.fa.fa-check-square-o {
            color: #0275d8;
            display: inline !important
        }

        label:hover input[type="checkbox"] ~ i.fa {
            color: #0275d8;
        }

        .c-input {
            font-size: 1rem !important;
            padding: 0rem !important;
        }

        /************************************ end radio and checkbox */

        /************************************ loader */

        .loader {
            visibility: hidden;
            background-color: #000;
            z-index: 10000;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            position: fixed;
            opacity: .7;
        }

        .loader:after {
            z-index: 10001;
            display: block;
            position: absolute;
            content: "";
            left: -200px;
            width: 200px;
            height: 5px;
            background-color: #023fd8;
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

        /************************************ select2 */

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background: white !important;
            border: 1px solid #eceeef !important;
        }

        .select2-container--default .select2-selection--multiple {
            border: 1px solid #eceeef !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background: white;
        }

        .select2-dropdown--above {
            border: 1px solid #eceeef !important;
            border-bottom: none !important;
        }

        .select2-dropdown--below {
            border: 1px solid #eceeef !important;
            border-top: none !important;
        }

        span.select2-selection--multiple[aria-expanded=true] {
            border-color: #eceeef !important;
        }

        /************************************ end select2 */

        @media print {
            /* on-screen print only */
            * {
                -webkit-print-color-adjust: exact !important;
            }

            ::-webkit-input-placeholder {
                color: white !important;
            }

            :-moz-placeholder {
                color: white !important;
            }

            ::-moz-placeholder {
                color: white !important;
            }

            :-ms-input-placeholder {
                color: white !important;
            }

            .dont-print, .dont-print {
                display: none !important;
                visibility: hidden !important;
            }

            .card, .card-block, .card-header, .card-footer {
                padding: 0 !important;
            }
        }

        .ui-autocomplete {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1000;
            float: left;
            display: none;
            min-width: 160px;
            padding: 4px 0;
            margin: 0 0 10px 25px;
            list-style: none;
            background-color: #ffffff;
            border: 1px solid #eceeef;
        }

        .ui-menu-item-wrapper {
            padding: .25rem;
        }

        .ui-menu-item {

            display: block;
            clear: both;
            font-weight: normal;

            color: #555555;
            white-space: nowrap;
            text-decoration: none;
        }

        .ui-state-hover, .ui-state-active {
            color: #ffffff;
            text-decoration: none;
            background-color: #0088cc;
            cursor: pointer;
        }

        .ui-helper-hidden-accessible {
            display: none;
            visibility: hidden;
        }

        div[style] {
            /*
                    all: unset;
                    animation : none;
                    animation-delay : 0;
                    animation-direction : normal;
                    animation-duration : 0;
                    animation-fill-mode : none;
                    animation-iteration-count : 1;
                    animation-name : none;
                    animation-play-state : running;
                    animation-timing-function : ease;
                    backface-visibility : visible;
                    background : 0;
                    background-attachment : scroll;
                    background-clip : border-box;
                    background-color : transparent;
                    background-image : none;
                    background-origin : padding-box;
                    background-position : 0 0;
                    background-position-x : 0;
                    background-position-y : 0;
                    background-repeat : repeat;
                    background-size : auto auto;
                    border : 0;
                    border-style : none;
                    border-width : medium;
                    border-color : inherit;
                    border-bottom : 0;
                    border-bottom-color : inherit;
                    border-bottom-left-radius : 0;
                    border-bottom-right-radius : 0;
                    border-bottom-style : none;
                    border-bottom-width : medium;
                    border-collapse : separate;
                    border-image : none;
                    border-left : 0;
                    border-left-color : inherit;
                    border-left-style : none;
                    border-left-width : medium;
                    border-radius : 0;
                    border-right : 0;
                    border-right-color : inherit;
                    border-right-style : none;
                    border-right-width : medium;
                    border-spacing : 0;
                    border-top : 0;
                    border-top-color : inherit;
                    border-top-left-radius : 0;
                    border-top-right-radius : 0;
                    border-top-style : none;
                    border-top-width : medium;
                    bottom : auto;
                    box-shadow : none;
                    box-sizing : content-box;
                    caption-side : top;
                    clear : none;
                    clip : auto;
                    color : inherit;
                    columns : auto;
                    column-count : auto;
                    column-fill : balance;
                    column-gap : normal;
                    column-rule : medium none currentColor;
                    column-rule-color : currentColor;
                    column-rule-style : none;
                    column-rule-width : none;
                    column-span : 1;
                    column-width : auto;
                    content : normal;
                    counter-increment : none;
                    counter-reset : none;
                    cursor : auto;
                    direction : ltr;
                    display : inline;
                    empty-cells : show;
                    float : none;
                    font : normal;
                    font-family : inherit;
                    font-size : medium;
                    font-style : normal;
                    font-variant : normal;
                    font-weight : normal;
                    height : auto;
                    hyphens : none;
                    left : auto;
                    letter-spacing : normal;
                    line-height : normal;
                    list-style : none;
                    list-style-image : none;
                    list-style-position : outside;
                    list-style-type : disc;
                    margin : 0;
                    margin-bottom : 0;
                    margin-left : 0;
                    margin-right : 0;
                    margin-top : 0;
                    max-height : none;
                    max-width : none;
                    min-height : 0;
                    min-width : 0;
                    opacity : 1;
                    orphans : 0;
                    outline : 0;
                    outline-color : invert;
                    outline-style : none;
                    outline-width : medium;
                    overflow : visible;
                    overflow-x : visible;
                    overflow-y : visible;
                    padding : 0;
                    padding-bottom : 0;
                    padding-left : 0;
                    padding-right : 0;
                    padding-top : 0;
                    page-break-after : auto;
                    page-break-before : auto;
                    page-break-inside : auto;
                    perspective : none;
                    perspective-origin : 50% 50%;
                    position : static;

                    quotes : '\201C' '\201D' '\2018' '\2019';
                    right : auto;
                    tab-size : 8;
                    table-layout : auto;
                    text-align : inherit;
                    text-align-last : auto;
                    text-decoration : none;
                    text-decoration-color : inherit;
                    text-decoration-line : none;
                    text-decoration-style : solid;
                    text-indent : 0;
                    text-shadow : none;
                    text-transform : none;
                    top : auto;
                    transform : none;
                    transform-style : flat;
                    transition : none;
                    transition-delay : 0s;
                    transition-duration : 0s;
                    transition-property : none;
                    transition-timing-function : ease;
                    unicode-bidi : normal;
                    vertical-align : baseline;
                    visibility : visible;
                    white-space : normal;
                    widows : 0;
                    width : auto;
                    word-spacing : normal;
                    z-index : auto;
            */
        }






    </style>

    @endif
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