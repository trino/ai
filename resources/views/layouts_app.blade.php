<!DOCTYPE html>
<html lang="en">
<head>


    <meta charset="utf-8">
    <meta name="theme-color" content="#5cb85c">
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta content="Didueat,didueat.ca,Online food delivery,Online food order,Canada online food,Canada Restaurants,Ontario Restaurants,Hamilton Restaurants | DiduEat" name="keywords">
    <meta content="Didueat" name="author">
    <meta name="content-language" content="en-CA">
    <meta http-equiv="content-language" content="en-CA">
    <meta content="Having great local food delivered helps us all keep up with our busy lives. By connecting you to local restaurants, Didueat makes great food more accessible, op" name="description">
    <meta property="og:site_name" content="DiduEat">
    <meta property="og:title" content="Local Food Delivery | DiduEat">
    <meta property="og:description" content="Having great local food delivered helps us all keep up with our busy lives. By connecting you to local restaurants, Didueat makes great food more accessible, op">
    <meta property="og:type" content="website">
    <meta property="og:image" content="-CUSTOMER VALUE-">
    <meta property="og:url" content="https://didueat.ca">



    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/css/bootstrap.min.css">
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://select2.github.io/select2/select2-3.5.2/select2.css">
    <link rel="stylesheet" href="<?= webroot("public/custom.css"); ?>">

    <link rel="icon" sizes="128x128" href="<?= webroot("resources/assets/images/pizza128.png"); ?>">
    <link rel="icon" sizes="192x192" href="<?= webroot("resources/assets/images/pizza192.png"); ?>">
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="manifest" href="<?= webroot("resources/assets/manifest.json"); ?>">

    <script src="<?= webroot("resources/assets/scripts/jquery.min.js"); ?>"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/js/bootstrap.min.js"></script>
    <script src="http://select2.github.io/select2/select2-3.4.2/select2.js"></script>
    <SCRIPT SRC="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></SCRIPT>
    <script src="<?= webroot("resources/assets/scripts/api2.js"); ?>"></script>
</head>
<SCRIPT>
    var currentURL = "<?= Request::url(); ?>";
    var token = "<?= csrf_token(); ?>";
    var webroot = "<?= webroot("public/"); ?>";
    var redirectonlogout = false;
    var redirectonlogin = false;
    var addresskeys = new Array;
    var userdetails = false;
    var currentRoute = "<?= Route::getCurrentRoute()->getPath(); ?>";
</SCRIPT>
<STYLE TITLE="These can not be moved to a CSS file!!!">
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

<body  style="overflow-x: hidden !important;">
    <?php
        $start_loading_time = microtime(true);
        if(read("id")){
            $user = getuser(read("id"));
            unset($user["password"]);
        }
    ?>
    <div class="container p-a-0 m-t-1 bodycontainer">
        @yield('content')
    </div>

    <nav class="navbar-default navbar-fixed-bottom navbar navbar-full navbar-dark bg-danger dont-print" style="z-index: 1;">
        <button class="btn btn-warning loggedin pull-right" id="checkout-btn" onclick="showcheckout();" style="display: none;">
            <strong id="checkout-total"></strong> CHECKOUT
        </button>
    </nav>

    <?= view("popups_login")->render(); ?>
    <div class="modal loading" ID="loadingmodal"></div>
</body>



<!--a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    <i class="fa fa-user no-padding-margin"></i>
</a-->

<SCRIPT>
    //overwrites javascript's alert and use the modal popup
    (function () {
        var proxied = window.alert;
        window.alert = function () {
            var title = "Alert";
            if (arguments.length > 1) {
                title = arguments[1];
            }
            $("#alert-cancel").hide();
            $("#alert-ok").click(function(){});
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
                $body.addClass("loading");
            },
            ajaxStop: function () {
                $body.removeClass("loading");
            }
        });

        @if(isset($user))
            login(<?= json_encode($user); ?>);//user is already logged in, use the data
        @endif
    });

    //handle a user login
    function login(user) {
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
        if (user["Addresses"].length > 0) {//generate address dropdown
            HTML += '<SELECT class="form-control saveaddresses" id="saveaddresses" onchange="addresschanged();"><OPTION VALUE="0">Select Address</OPTION>';
            addresskeys = Object.keys(user["Addresses"][0]);
            for (i = 0; i < user["Addresses"].length; i++) {
                HTML += AddressToOption(user["Addresses"][i], addresskeys);
            }
            HTML += '</SELECT>';
        }
        $(".addressdropdown").html(HTML);
    }

    //convert an address to a dropdown option
    function AddressToOption(address, addresskeys){
        if(isUndefined(addresskeys)){addresskeys=["id", "value", "user_id", "number", "unit", "buzzcode", "street", "postalcode", "city", "province", "latitude", "longitude", "phone"];}
        var tempHTML = '<OPTION';
        var streetformat = "[number] [street], [city]";
        if(address["unit"]){
            streetformat += " - Apt/Unit: [unit]";
            if(address["buzzcode"]) {streetformat += ", Buzz code: [buzzcode]";}
        }
        for (var keyID = 0; keyID < addresskeys.length; keyID++) {
            var keyname = addresskeys[keyID];
            if(address.hasOwnProperty(keyname)) {
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
        if ($(Selected).val() == 0) {Text = '';}
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
                <div class="form-group">
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
            </div>
        </div>
    </div>
</DIV>

<div class="modal loading" ID="loadingmodal"></div>
</html>