<!DOCTYPE html>
<html lang="en">
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
        var addresskeys = new Array;
        var userdetails = false;
        var currentRoute = "<?= Route::getCurrentRoute()->getPath(); ?>";
    </script>

    <meta charset="utf-8">
    <meta name="theme-color" content="#d9534f">
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <meta http-equiv="content-language" content="en-CA">
    <link rel="icon" sizes="128x128" href="<?= webroot("resources/assets/images/pizza128.png"); ?>">
    <link rel="icon" sizes="192x192" href="<?= webroot("resources/assets/images/pizza192.png"); ?>">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet'
          type='text/css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css">
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?= webroot("public/custom2.css"); ?>">
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="manifest" href="<?= webroot("resources/assets/manifest.json"); ?>">
    <script src="<?= webroot("resources/views/jquery.min.js"); ?>"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://select2.github.io/select2/select2-3.5.2/select2.css">
    <script src="http://select2.github.io/select2/select2-3.4.2/select2.js"></script>
    <!--link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.1.1/css/mdb.min.css"-->
    <SCRIPT SRC="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></SCRIPT>

    @include("popups_alljs")

</head>
<body>


<?= view("popups_navbar")->render(); ?>


<div class="container mt-1 " style="display: none;">


    <button class="btn btn-danger  pull-left" onclick="window.scrollTo(0,document.body.scrollHeight);">

        <span id="navbar-text"></span>

    </button>


    @if(!islive())
        <LABEL STYLE="margin-top: 7px; margin-left: 7px;">
            <INPUT TYPE="checkbox" onclick="checkblock(event);">block leaving
        </LABEL>
    @endif

    <div class="card">
        <div class="card-block">

            <?= view("popups_toppings"); ?>



        </div>
    </div>

</div>

<div class="container mt-1">


    @yield('content')


</div>


<?= view("popups_sticky_footer")->render(); ?>


<div class="modal loading" ID="loadingmodal"></div>
<SPAN ID="navbar-text"></SPAN>


</body>

<script type="text/javascript">
    $(window).load(function () {
        console.log("Time until everything loaded: ", Date.now() - timerStart);
    });
    $(document).ready(function () {
        console.log("Time until DOMready: ", Date.now() - timerStart);
        $("#navbar-text").text("<?= "" . round((microtime(true) - $time), 5) . "s"; ?>");
    });
</script>

</html>