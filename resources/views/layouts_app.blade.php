<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    $time = microtime(true); // Gets microseconds
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
    <meta name="theme-color" content="#5cb85c">
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
    <link rel="stylesheet" href="<?= webroot("public/custom.123css"); ?>">
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="manifest" href="<?= webroot("resources/assets/manifest.json"); ?>">
    <script src="<?= webroot("resources/views/jquery.min.js"); ?>"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://select2.github.io/select2/select2-3.5.2/select2.css">
    <script src="http://select2.github.io/select2/select2-3.4.2/select2.js"></script>
    <!--link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.1.1/css/mdb.min.css"-->
    <SCRIPT SRC="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></SCRIPT>
    <script src="<?= webroot("resources/views/api2.js"); ?>"></script>

</head>

<STYLE TITLE="These can not be moved to a CSS file!!!">

    * {
        border-radius: 0 !important;
        border: 0 !important;
    }

    .modal-backdrop {
        background-color: white;
        opacity: 1 !important;
    }

    /* puts the google places autocomplete dropdown results above the bootstrap modal 1050 zindex. */
    .pac-container {
        z-index: 1151 !important;
    }

    .list-group-item {
        padding: .2rem;
    }

    .dont-show {
        display: none;
    }

    .sprite {
        background-image: url('grid.png');
        background-repeat: no-repeat;
        width: 64px;
        height: 64px;

        display: inline-block;
    }

    .sprite-dips {
        background-position: -0px -0px;
    }

    .sprite-wings {
        background-position: -66px -0px;
    }

    .sprite-drinks {
        background-position: -132px -0px;
    }

    .sprite-panzerotti {
        background-position: 0px -66px;
    }

    .sprite-pizza {
        background-position: -66px -66px;
    }

    .sprite-sides {
        background-position: -132px -66px;
    }

    .sprite-medium {
        zoom: 0.60;
        -moz-transform: scale(0.60);
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

    @if(read("id"))
            .loggedin {
        display: block;
    }

    .loggedout {
        display: none;
    }

    @else
            .loggedin {
        display: none;
    }

    .loggedout {
        display: block;
    }

    @endif

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
</HEAD>


<body style="overflow-x: hidden !important;">

<?php
if (read("id")) {
    $user = getuser(read("id"));
    unset($user["password"]);
}
?>

<div class="container">
    @yield('content')
</div>

<nav class="navbar-default navbar-fixed-top navbar navbar-full navbar-dark bg-danger" style="z-index: 1;">
</nav>
<nav class="navbar-default navbar-fixed-bottom navbar navbar-full navbar-dark bg-danger dont-print" style="z-index: 1;">
    <SPAN ID="navbar-text"></SPAN>
    <button class="btn btn-warning pull-right" id="checkout-btn" onclick="showcheckout();" style="display: none;">
        <strong id="checkout-total"></strong> CHECKOUT
    </button>
</nav>

<?= view("popups_login")->render(); ?>

<div class="modal loading" ID="loadingmodal"></div>

<SCRIPT>
    var skiploadingscreen = false;
    //overwrites javascript's alert and use the modal popup
    (function () {
        var proxied = window.alert;
        window.alert = function () {
            var title = "Alert";
            if (arguments.length > 1) {
                title = arguments[1];
            }
            $("#alert-cancel").hide();
            $("#alert-ok").click(function () {
            });
            $("#alert-confirm").css("width", "100%");
            $("#alertmodalbody").html(arguments[0]);
            $("#alertmodallabel").text(title);
            $("#alertmodal").modal('show');
        };
    })();

    $(document).ready(function () {
        //make every AJAX request show the loading animation
        $body = $("body");
        $(document).on({
            ajaxStart: function () {
                if (skiploadingscreen) {
                    skiploadingscreen = false;
                } else {
                    $body.addClass("loading");
                }
            },
            ajaxStop: function () {
                $body.removeClass("loading");
            }
        });

        @if(isset($user))
            login(<?= json_encode($user); ?>, false);//user is already logged in, use the data
        @endif
    });

    //handle a user login
    function login(user, isJSON) {
        userdetails = user;
        var keys = Object.keys(user);
        for (var i = 0; i < keys.length; i++) {
            var key = keys[i];
            var val = user[key];
            createCookieValue("session_" + key, val);//save data to cookie
            $(".session_" + key).text(val);//set elements text to data
            $(".session_" + key + "_val").val(val);//set elements value to data
        }
        $(".loggedin").show();//show loggedin class
        $(".loggedout").hide();//hide loggedout class
        $(".profiletype").hide();//hide all profile type clasdses
        $(".profiletype" + user["profiletype"]).show();//show classes for this profile type
        var HTML = '';
        var FirstAddress = false;
        if (user["Addresses"].length > 0) {//generate address dropdown
            HTML += '<SELECT class="form-control saveaddresses" id="saveaddresses" onchange="addresschanged();"><OPTION>Select a saved address</OPTION>';
            addresskeys = Object.keys(user["Addresses"][0]);
            for (i = 0; i < user["Addresses"].length; i++) {
                if (!FirstAddress) {
                    FirstAddress = user["Addresses"][i]["id"];
                }
                HTML += AddressToOption(user["Addresses"][i], addresskeys);
            }
            HTML += '</SELECT>';
        }
        $(".addressdropdown").html(HTML);
        if (user["profiletype"] == 2) {
            user["restaurant_id"] = FirstAddress;
            var URL = '<?= webroot("public/list/orders"); ?>';
            if (window.location.href != URL && isJSON) {
                window.location.href = URL;
                die();
            }
        }
    }

    //convert an address to a dropdown option
    function AddressToOption(address, addresskeys) {
        if (isUndefined(addresskeys)) {
            addresskeys = ["id", "value", "user_id", "number", "unit", "buzzcode", "street", "postalcode", "city", "province", "latitude", "longitude", "phone"];
        }
        var tempHTML = '<OPTION';
        var streetformat = "[number] [street], [city]";
        if (address["unit"]) {
            streetformat += " - Apt/Unit: [unit]";
            if (address["buzzcode"]) {
                streetformat += ", Buzz code: [buzzcode]";
            }
        }
        for (var keyID = 0; keyID < addresskeys.length; keyID++) {
            var keyname = addresskeys[keyID];
            if (address.hasOwnProperty(keyname)) {
                var value = address[keyname];
                streetformat = streetformat.replace("[" + keyname + "]", value);
                if (keyname == "id") {
                    keyname = "value";
                }
                tempHTML += ' ' + keyname + '="' + value + '"'
            }
        }
        return tempHTML + '>' + streetformat + '</OPTION>';
    }

    //address dropdown changed
    function addresschanged() {
        var Selected = $("#saveaddresses option:selected");
        var Text = '[number] [street], [city]';
        for (var keyID = 0; keyID < addresskeys.length; keyID++) {
            var keyname = addresskeys[keyID];
            var keyvalue = $(Selected).attr(keyname);
            Text = Text.replace("[" + keyname + "]", keyvalue);
            $("#add_" + keyname).val(keyvalue);
        }
        if ($(Selected).val() == 0) {
            Text = '';
        }
        $("#formatted_address").val(Text);
        addresshaschanged();
    }

    //universal AJAX error handling
    $(document).ajaxComplete(function (event, request, settings) {
        if (request.status != 200 && request.status > 0) {//not OK, or aborted
            alert(request.statusText + "<P>URL: " + settings.url, "AJAX error code: " + request.status);
        }
    });
</SCRIPT>

<div class="modal" id="alertmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div>
                    <h4 class="modal-title" id="alertmodallabel">Title</h4>
                </div>
                <DIV ID="alertmodalbody"></DIV>
                <DIV STYLE="margin-top: 15px;">
                    <button class="btn btn-secondary" style="width:40%" id="alert-cancel" data-dismiss="modal">
                        Cancel
                    </button>
                    <button class="btn btn-warning pull-right" id="alert-confirm" data-dismiss="modal">
                        OK
                    </button>
                </DIV>
                <DIV CLASS="clearfix"></DIV>
            </div>
        </div>
    </div>
</DIV>

<div class="modal loading" ID="loadingmodal"></div>
<script type="text/javascript">
    $(window).load(function () {
        console.log("Time until everything loaded: ", Date.now() - timerStart);
    });
    $(document).ready(function () {
        console.log("Time until DOMready: ", Date.now() - timerStart);
        $("#navbar-text").text("<?= "" . round((microtime(true) - $time), 5) . "s"; ?>");
    });
</script>
</body>
</html>
















