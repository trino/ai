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
                    <i class="fa fa-close"></i>
                </button>
                <div>
                    <h4 class="modal-title" id="myModalLabel">Login</h4>
                </div>

                <INPUT TYPE="TEXT" ID="login_email" PlACEHOLDER="Email Address"
                       onkeydown="enterkey(event, '#login_password');">
                <INPUT TYPE="PASSWORD" ID="login_password" PLACEHOLDER="Password" onkeydown="enterkey(event, 'login');">

                <DIV ID="loginmessage"></DIV>

                <DIV STYLE="margin-top: 15px;">
                    <DIV CLASS="col-md-4">
                        <button class="btn btn-block btn-warning" onclick="handlelogin('login');">
                            Login
                        </button>
                    </DIV>
                    <DIV CLASS="col-md-4">
                        <button class="btn btn-block btn-danger" onclick="handlelogin('forgotpassword');">
                            Forgot Password
                        </button>
                    </DIV>
                    <DIV CLASS="col-md-4">
                        <button class="btn btn-block btn-primary" data-dismiss="modal" data-toggle="modal"
                                data-target="#registermodal">
                            Register
                        </button>
                    </DIV>
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
                <div class="row">

                    <DIV CLASS="col-md-12">

                    <h3>London Pizza Delivery</h3>
</div>
                    <DIV CLASS="col-md-6">

                        <!-- Bootstrap CSS -->
                        <!-- jQuery first, then Bootstrap JS. -->
                        <!-- Nav tabs -->

                        <ul class="nav nav-tabs mb-1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" href="#profile" role="tab" data-toggle="tab" id="logintab">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#buzz" role="tab" data-toggle="tab">Signup</a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content mt-1">
                            <div role="tabpanel" class="tab-pane fade in active" id="profile">
                                <div class="clearfix"></div>

                                <INPUT TYPE="text" id="login_email" placeholder="Email" class="form-control" onkeydown="enterkey(event, '#login_password');">
                                <div class="clearfix"></div>

                                <INPUT TYPE="password" id="login_password" placeholder="Password" class="form-control" onkeydown="enterkey(event, 'login');">

                                <div class="pb-1 clearfix"></div>
                                <BUTTON CLASS="btn btn-primary pull-right" onclick="handlelogin('login');">Log In</BUTTON>
                                <BUTTON CLASS="btn btn-link text-muted pull-left" onclick="handlelogin('forgotpassword');">Forgot Password</BUTTON>
                            </div>

                            <div role="tabpanel" class="tab-pane fade" id="buzz">

                                <div class="clearfix"></div>
                                <FORM Name="regform" id="regform">
                                    <?= view("popups_edituser", array("phone" => false, "autocomplete" => "new-password"))->render(); ?>
                                </FORM>
                                <div class="clearfix"></div>

                                <FORM id="addform">
                                    <?php
                                        if (!read("id")) {
                                            echo view("popups_address", array("style" => 1))->render();
                                        }
                                    ?>
                                </FORM>

                                <div class="clearfix"></div>

                                <button class="btn btn-primary pull-right" onclick="$('#regform').submit();">
                                    Register
                                </button>
                            </div>
                        </div>

                        <DIV CLASS="clearfix"></DIV>

                    </DIV>

                    <DIV CLASS="col-md-6 text-white bg-danger" id="loginpanel">
                        <i class="fa fa-cutlery btn btn-block" style="font-size: 1rem;"></i>

                        test 123 <br>
                        test 123 <br>
                        test 123 <br>
                        test 123 <br>
                        test 123 <br>
                        test 123 <br>
                        test 123 <br>
                        test 123 <br>
                        test 123 <br>
                        test 123 <br>
                        <DIV CLASS="clearfix"></DIV>

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

    var minlength = 5;
    var getcloseststore = false;
    skiploadingscreen = true;
    lockloading=true;

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
                            //alert(data["Reason"], "Registration");
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
</SCRIPT>