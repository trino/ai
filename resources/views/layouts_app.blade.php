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
    <script src="<?= webroot("resources/views/api2.js"); ?>"></script>
    <STYLE>
        /* STOP MOVING THIS TO THE CSS, IT WON'T WORK! */
        #loadingmodal {
            display: none;
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: rgba(255, 255, 255, .8) url('<?= webroot("resources/assets/images/slice.gif"); ?>') 50% 50% no-repeat;
        }
    </STYLE>
</head>
<body>
<?= view("popups_navbar")->render(); ?>
<div class="container">
    @yield('content')
</div>
<nav id="checkout-btn" class="navbar-default bg-danger navbar-fixed-bottom navbar hidden-sm-up"
     style="z-index: 1;">
    <button class="btn bg-danger text-white" onclick="window.scrollTo(0,document.body.scrollHeight);">
        <strong id="checkout-total"></strong> View
    </button>
    <button class="btn btn-warning pull-right" onclick="showcheckout();">
        CHECKOUT
    </button>
    <button class="btn bg-black pull-right"
            ONCLICK="confirm2('Are you sure you want to clear your order?', 'Clear Order', function(){clearorder();});">
        CLEAR
    </button>
</nav>

<?= view("popups_login")->render(); ?>
<div class="modal loading" ID="loadingmodal"></div>
<SPAN ID="navbar-text"></SPAN>
@include("popups_alljs")
</body>
</html>