<div class="modal" id="loginmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="form-group">
                    <h4 class="modal-title" id="myModalLabel">Login</h4>
                </div>

                <INPUT TYPE="TEXT" ID="login_email" PlACEHOLDER="Email Address" CLASS="form-control">
                <INPUT TYPE="PASSWORD" ID="login_password" PLACEHOLDER="Password" CLASS="form-control">

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
                        <button class="btn btn-block btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#registermodal">
                            Register
                        </button>
                    </DIV>
                </DIV>
                <DIV CLASS="clearfix"></DIV>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="registermodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="form-group">
                    <h4 class="modal-title" id="myModalLabel">Register</h4>
                </div>
                <FORM Name="regform" id="regform">
                    <?= view("popups.edituser"); ?>
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

<SCRIPT>
    function handlelogin(action){
        if(isUndefined(action)){action="verify";}
        $.post(webroot + "auth/login", {
            action: action,
            _token: token,
            email: $("#login_email").val(),
            password: $("#login_password").val()
        }, function (result) {
            try {
                var data = JSON.parse(result);
                if(data["Status"] == "false" || !data["Status"]) {
                    data["Reason"] = data["Reason"].replace('[verify]', '<A onclick="handlelogin();" CLASS="hyperlink" TITLE="Click here to resend the email">verify</A>');
                    alert(data["Reason"], "Login");
                } else {
                    switch (action) {
                        case "login":
                            token = data["Token"];
                            login(data["User"]);
                            $("#loginmodal").modal("hide");
                            break;
                        case "forgotpassword": case "verify":
                            alert(data["Reason"], "Login");
                            break;
                        case "logout":
                            removeCookie();
                            $(".loggedin").hide();
                            $(".loggedout").show();
                            $(".clear_loggedout").html("");
                            $(".profiletype").hide();
                            userdetails=false;
                            if(redirectonlogout){
                                window.location = "<?= webroot("public/index"); ?>";
                            }
                            break;
                    }
                }
            } catch (e){
                alert(result, "Login");
            }
        });
    }

    var minlength = 5;

    $(function() {
        $("form[name='regform']").validate({
            rules: {
                name: "required",
                phone: "phonenumber",
                email: {
                    required: true,
                    email: true,
                    remote: {
                        url: '<?= webroot('public/user/info'); ?>',
                        type: "post",
                        data: {
                            action: "testemail",
                            email: function() {
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
            submitHandler: function(form) {
                var formdata = getform("#regform");
                formdata["action"] = "registration";
                formdata["_token"] = token;
                $.post(webroot + "auth/login", formdata, function (result) {
                    if(result) {
                        try {
                            var data = JSON.parse(result);
                            alert(data["Reason"], "Registration");
                        } catch (e){
                            alert(result, "Registration");
                        }
                    }
                });
                return false;
            }
        });
    });

</SCRIPT>