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
<body style="overflow-x: hidden !important;">
<nav class="navbar-default navbar-fixed-bottom navbar navbar-dark" style="z-index: 1;background:#fff;">
    <button class="btn btn-danger  pull-left" onclick="window.scrollTo(0,document.body.scrollHeight);">
        <SPAN ID="navbar-text"></SPAN>
    </button>

    @if(!islive())
        <LABEL STYLE="margin-top: 7px; margin-left: 7px;">
            <INPUT TYPE="checkbox" onclick="checkblock(event);">
            Block leaving the page
        </LABEL>
    @endif

    <button class="btn btn-warning pull-right" id="checkout-btn" onclick="showcheckout();" style="display: none;">
        <strong id="checkout-total"></strong> CHECKOUT
    </button>
</nav>


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

    var generalhours = <?= json_encode(gethours()) ?>;

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

        var HTML = '';
        var todaysdate = isopen(generalhours);
        if (todaysdate == -1) {
            HTML = 'Currently closed';
            todaysdate = getToday();
            if (generalhours[todaysdate].open > now()) {
                HTML = 'Opens at: ' + GenerateTime(generalhours[todaysdate].open);
            }
        } else {
            HTML = 'Open until: ' + GenerateTime(generalhours[todaysdate].close);
        }
        GenerateHours(generalhours);
        $("#openingtime").html(HTML);
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
                log("handle login");
                window.location.href = URL;
                die();
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
                //H2 class="block_exception", get span class="exception_title" and class="exception_message"
                alert(request.statusText + "<P>URL: " + settings.url, "AJAX error code: " + request.status);
            }
        });
</SCRIPT>

<div class="modal" id="alertmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
     data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

                    <h4 class="modal-title" id="alertmodallabel">Title</h4>

                <DIV ID="alertmodalbody"></DIV>

                <DIV CLASS="pb-1"></DIV>
                <div class="btn-group" role="group" aria-label="Basic example" style="width: 100%">
                    <button class="btn btn-secondary" style="width: 50%" id="alert-cancel" data-dismiss="modal">
                        Cancel
                    </button>
                    <button class="btn btn-secondary" style="width: 50%" id="alert-confirm" data-dismiss="modal">
                        OK
                    </button>
                </div>

            </div>
        </div>
    </div>
</DIV>

<div class="modal loading" ID="loadingmodal"></div>

<script type="text/javascript">
    function checkblock(e) {
        var checked = $(e.target).is(':checked');
        BeforeUnload(checked);
    }
    function BeforeUnload(enable) {
        if (enable) {
            window.onbeforeunload = function (e) {
                return "Discard changes?";
            };
            log("Page transitions blocked");
        } else {
            window.onbeforeunload = null;
            log("Page transitions allowed");
        }
    }

    $(window).load(function () {
        console.log("Time until everything loaded: ", Date.now() - timerStart);
    });
    $(document).ready(function () {
        console.log("Time until DOMready: ", Date.now() - timerStart);
        $("#navbar-text").text("<?= "" . round((microtime(true) - $time), 5) . "s"; ?>");
    });
</script>

</body>
</head>
<body>
<?= view("popups_navbar")->render(); ?>
<div class="container mt-3">
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