<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/css/bootstrap.min.css">
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://select2.github.io/select2/select2-3.5.2/select2.css">
    <link rel="stylesheet" href="custom.css">

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

<body>
<?php
$start_loading_time = microtime(true);
if(read("id")){
    $user = first("SELECT * FROM users WHERE id = " . read("id"));
    unset($user["password"]);
    $user["Addresses"] = Query("SELECT * FROM useraddresses WHERE user_id = " . $user["id"], true);
}
?>
<div class="container p-a-0 m-t-1 bodycontainer">
    @yield('content')
</div>
<?= view("popups.login"); ?>
<div class="modal loading" ID="loadingmodal"></div>
</body>
<!--nav class="navbar-default navbar-fixed-top navbar navbar-full navbar-dark bg-danger dont-print" style="z-index: 1;padding:.1rem !important;"></nav-->

<a style="color:white;margin-top:.25rem;" href="#" class="dropdown-toggle nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    <i class="fa fa-user no-padding-margin"></i>
</a>

<SCRIPT>
    var currentURL = "<?= Request::url(); ?>";
    var token = "<?= csrf_token(); ?>";
    var webroot = "<?= webroot("public/"); ?>";
    var redirectonlogout = false;
    var addresskeys = new Array;
    var userdetails = false;
    var currentRoute = "<?= Route::getCurrentRoute()->getPath(); ?>";

    (function () {
        var proxied = window.alert;
        window.alert = function () {
            var title = "Alert";
            if (arguments.length > 1) {
                title = arguments[1];
            }
            $("#alertmodalbody").html(arguments[0]);
            $("#alertmodallabel").text(title);
            $("#alertmodal").modal('show');
        };
    })();

    $(document).ready(function () {
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
            login(<?= json_encode($user); ?>);
        @endif
    });

    function login(user) {
        userdetails = user;
        var keys = Object.keys(user);
        for (var i = 0; i < keys.length; i++) {
            var key = keys[i];
            var val = user[key];
            createCookieValue("session_" + key, val);
            $(".session_" + key).text(val);
            $(".session_" + key + "_val").val(val);
        }
        $(".loggedin").show();
        $(".loggedout").hide();
        $(".profiletype").hide();
        $(".profiletype" + user["profiletype"]).show();

        var HTML = '';
        if (user["Addresses"].length > 0) {
            HTML += '<SELECT style="border-top: 0 !important; " class="form-control" id="saveaddresses" onchange="addresschanged();"><OPTION VALUE="0">Select a saved address</OPTION>';
            addresskeys = Object.keys(user["Addresses"][0]);
            for (i = 0; i < user["Addresses"].length; i++) {
                var tempHTML = '<OPTION';
                var streetformat = "[number] [street], [city]";
                for (var keyID = 0; keyID < addresskeys.length; keyID++) {
                    var keyname = addresskeys[keyID];
                    var value = user["Addresses"][i][keyname];
                    streetformat = streetformat.replace("[" + keyname + "]", value);
                    if (keyname == "id") {
                        keyname = "value";
                    }
                    tempHTML += ' ' + keyname + '="' + value + '"'
                }
                HTML += tempHTML + '>' + streetformat + '</OPTION>';
            }
            HTML += '</SELECT>';
        }
        $("#addressdropdown").html(HTML);
    }

    function addresschanged() {
        var Selected = $("#saveaddresses option:selected");
        for (var keyID = 0; keyID < addresskeys.length; keyID++) {
            var keyname = addresskeys[keyID];
            $("#add_" + keyname).val($(Selected).attr(keyname));
        }
        keyname = $(Selected).text();
        if ($(Selected).val() == 0) {
            keyname = '';
        }
        $("#formatted_address").val(keyname);
    }

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
                <button class="btn btn-block btn-warning" data-dismiss="modal" STYLE="margin-top: 15px;">
                    Ok
                </button>
            </div>
        </div>
    </div>
</DIV>
<div class="modal loading" ID="loadingmodal"></div>
</html>