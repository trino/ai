<?php
$style = 2;
if($style == 1){
?>
<div class="modal" id="loginmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
     data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-close"></i></button>
                <div>
                    <h4 class="modal-title" id="myModalLabel">Login</h4>
                </div>

                <INPUT TYPE="TEXT" ID="login_email" PlACEHOLDER="Email Address"
                       onkeydown="enterkey(event, '#login_password');">
                <INPUT TYPE="PASSWORD" ID="login_password" PLACEHOLDER="Password" onkeydown="enterkey(event, 'login');">

                <DIV ID="loginmessage"></DIV>


                <DIV CLASS="col-md-4">
                    <button class="btn btn-block btn-warning" onclick="handlelogin('login');">
                        Login
                    </button>
                </DIV>
                <DIV CLASS="col-md-4">
                    <button class="btn btn-block btn-secondary" onclick="handlelogin('forgotpassword');">
                        Forgot Password
                    </button>
                </DIV>
                <DIV CLASS="col-md-4">
                    <button class="btn btn-block btn-primary" data-dismiss="modal" data-toggle="modal"
                            data-target="#registermodal">
                        Register
                    </button>
                </DIV>

                <DIV CLASS="clearfix"></DIV>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="registermodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
     data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-close"></i>
                </button>
                <div>
                    <h4 class="modal-title" id="myModalLabel">Register</h4>
                </div>
                <FORM Name="regform" id="regform">
                    <?= view("popups_edituser")->render(); ?>
                    <DIV STYLE="margin-top: 15px;">
                        <DIV CLASS="col-md-12">
                            <button class="btn btn-block btn-primary">
                                Register
                            </button>
                        </DIV>
                    </DIV>
                </FORM>
                <DIV CLASS="clearfix"></DIV>
            </div>
        </div>
    </div>
</div>
<?php } else { ?>
<div class="modal" id="loginmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
     data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="">
                    <DIV CLASS="col-md-12">

                        <h3>Precise Pizza Delivery</h3>

                        <h4 class="pt-1 ">
                            Login
                        </h4>
                        <INPUT TYPE="text" id="login_email" placeholder="Email" class="form-control"
                               onkeydown="enterkey(event, '#login_password');">
                        <INPUT TYPE="password" id="login_password" placeholder="Password" class="form-control"
                               onkeydown="enterkey(event, 'login');">
                        <div class="pt-1 "></div>
                        <BUTTON CLASS="btn btn-warning pull-right" onclick="handlelogin('login');">Log In</BUTTON>
                        <BUTTON CLASS="btn btn-secondary pull-left" onclick="handlelogin('forgotpassword');">Forgot
                            Password
                        </BUTTON>

                        <div class="clearfix"></div>
                        <h4 class="pt-1">
                            Sign Up
                        </h4>

                        <FORM Name="regform" id="regform">
                            <?= view("popups_edituser", array("phone" => false))->render(); ?>
                        </FORM>

                        <FORM id="addform">
                            <?php
                            if (!read("id")) {
                                echo view("popups_address", array("style" => 1))->render();
                            }
                            ?>
                        </FORM>

                        <div class="pt-1"></div>
                        <button class="btn btn-warning pull-right" onclick="$('#regform').submit();">
                            Sign Up
                        </button>


                    </DIV>
                </div>

                <DIV CLASS="clearfix"></DIV>
            </div>
        </div>
    </div>
</div>

<?php } ?>

<SCRIPT>
    @if($style == 2)
            redirectonlogin = true;
    @endif

    function enterkey(e, action) {
        var keycode = event.which || event.keyCode;
        if (keycode == 13) {
            if (action.left(1) == "#") {
                $(action).focus();
            } else {
                handlelogin(action);
            }
        }
    }

    function handlelogin(action) {
        if (isUndefined(action)) {
            action = "verify";
        }
        $.post(webroot + "auth/login", {
            action: action,
            _token: token,
            email: $("#login_email").val(),
            password: $("#login_password").val()
        }, function (result) {
            try {
                var data = JSON.parse(result);
                if (data["Status"] == "false" || !data["Status"]) {
                    data["Reason"] = data["Reason"].replace('[verify]', '<A onclick="handlelogin();" CLASS="hyperlink" TITLE="Click here to resend the email">verify</A>');
                    alert(data["Reason"], "Error logging in");
                } else {
                    switch (action) {
                        case "login":
                            token = data["Token"];
                            login(data["User"], true);
                            $("#loginmodal").modal("hide");
                            if (redirectonlogin) {
                                location.reload();
                            }
                            break;
                        case "forgotpassword":
                        case "verify":
                            alert(data["Reason"], "Login");
                            break;
                        case "logout":
                            removeCookie();
                            $('[class^="session_"]').text("");
                            $(".loggedin").hide();
                            $(".loggedout").show();
                            $(".clear_loggedout").html("");
                            $(".profiletype").hide();
                            userdetails = false;
                            if (redirectonlogout) {
                                window.location = "<?= webroot("public/index"); ?>";
                            } else {
                                switch (currentRoute) {
                                    case "index"://resave order as it's deleted in removeCookie();
                                        if (!isUndefined(theorder)) {
                                            if (theorder.length > 0) {
                                                createCookieValue("theorder", JSON.stringify(theorder));
                                            }
                                        }
                                        break;
                                }
                            }
                            if (!isUndefined(collapsecheckout)) {
                                collapsecheckout();
                            }
                            break;
                    }
                }
            } catch (err) {
                alert(err.message + "<BR>" + result, "Login Error");
            }
        });
    }

    var minlength = 5;
    var getcloseststore = false;

    $(function () {
        $("form[name='regform']").validate({
            rules: {
                name: "required",
                //phone: "phonenumber",
                email: {
                    required: true,
                    email: true,
                    remote: {
                        url: '<?= webroot('public/user/info'); ?>',
                        type: "post",
                        data: {
                            action: "testemail",
                            _token: token,
                            email: function () {
                                return $('#reg_email').val();
                            },
                            user_id: "0"
                        }
                    }
                },
                password: {
                    minlength: minlength
                }
            },
            messages: {
                name: "Please enter your name",
                password: {
                    required: "Please provide a password",
                    minlength: "Your new password must be at least " + minlength + " characters long"
                },
                email: "Please enter a valid and unique email address"
            },
            submitHandler: function (form) {
                var formdata = getform("#regform");
                formdata["action"] = "registration";
                formdata["_token"] = token;
                formdata["address"] = getform("#addform");
                $.post(webroot + "auth/login", formdata, function (result) {
                    if (result) {
                        try {
                            var data = JSON.parse(result);
                            alert(data["Reason"], "Registration");
                        } catch (e) {
                            alert(result, "Registration");
                        }
                    }
                });
                return false;
            }
        });
    });
</SCRIPT>