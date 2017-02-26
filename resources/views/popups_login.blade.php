<?php
startfile("popups_login");
?>
<div class="row py-2 my-1">
    <DIV CLASS="col-lg-3 col-md-4">
        <DIV CLASS="card card-block">
            <h2 class="small-margin">Mobile Pizza Delivery</h2>
            <ul class="nav nav-tabs mb-1" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" href="#profile" role="tab" data-toggle="tab" id="logintab" onclick="skiploadingscreen = false;">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#buzz" role="tab" data-toggle="tab" id="signuptab" onclick="skiploadingscreen = true;">Signup</a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content mt-1">
                <div role="tabpanel" class="tab-pane fade in active" id="profile">
                    <div class="clearfix"></div>
                    <INPUT TYPE="text" id="login_email" placeholder="Email" class="form-control" onkeydown="enterkey(event, '#login_password');">
                    <div class="clearfix"></div>
                    <INPUT TYPE="password" id="login_password" placeholder="Password" class="form-control" onkeydown="enterkey(event, 'login');">
                    <div class="clearfix mt-2"></div>
                    <BUTTON CLASS="btn btn-primary pull-right" onclick="handlelogin('login');">LOG IN</BUTTON>
                    <BUTTON CLASS="btn btn-link pl-0 text-muted pull-left btn-sm" onclick="handlelogin('forgotpassword');">Forgot Password</BUTTON>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="buzz">
                    <div class="alert alert-info"><small>
                        We're only accepting credit/debit payments at this time. Apologies for the inconvenience.</small>
                    </div>
                    <h2>Delivery</h2>
                    <FORM id="addform">
                        <?php
                        if (!read("id")) {
                            echo view("popups_address", array("style" => 1))->render();
                        }
                        ?>
                    </FORM>
                    <h2>Profile</h2>
                    <FORM Name="regform" id="regform">
                        <?= view("popups_edituser", array("phone" => false, "autocomplete" => "new-password"))->render(); ?>
                    </FORM>
                    <div class="clearfix mt-2"></div>
                    <button class="btn btn-primary pull-right" onclick="register();">
                        SIGN UP
                    </button>
                </div>
            </div>
            <DIV CLASS="clearfix"></DIV>
        </DIV>
    </DIV>
    <DIV CLASS="col-sm-6" id="loginpanel">
        <DIV CLASS="clearfix"></DIV>
    </DIV>
</div>
<SCRIPT>
    redirectonlogin = true;
    var minlength = 5;
    var getcloseststore = false;
    lockloading = true;

    function register() {
        if (isvalidaddress()) {
            $("#reg_address-error").remove();
        } else if ($("#reg_address-error").length == 0) {
            $('<label id="reg_address-error" class="error" for="reg_name">Please enter a valid London address</label>').insertAfter("#formatted_address");
        }
        $('#regform').submit();
    }

    $(function () {
        $("form[name='regform']").validate({
            rules: {
                name: "required",
                formatted_address: {
                    validaddress: true,
                    required: true
                },
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
                email: {
                    required: "Please enter an email address",
                    email: "Please enter a valid email address",
                    remote: "Please enter a unique email address"
                }
            },
            submitHandler: function (form) {
                if (!isvalidaddress()) {
                    return false;
                }
                var formdata = getform("#regform");
                formdata["action"] = "registration";
                formdata["_token"] = token;
                formdata["address"] = getform("#addform");
                $.post(webroot + "auth/login", formdata, function (result) {
                    if (result) {
                        try {
                            var data = JSON.parse(result);
                            $("#logintab").trigger("click");
                            $("#login_email").val(formdata["email"]);
                            $("#login_password").val(formdata["password"]);
                            handlelogin('login');
                        } catch (e) {
                            alert(result, "Registration");
                        }
                    }
                });
                return false;
            }
        });
    });
    $(document).ready(function () {
        $("#profile").removeClass("fade").removeClass("in");
    });
</SCRIPT>
<?php endfile("popups_login"); ?>