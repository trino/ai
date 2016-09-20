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
                <FORM ID="registration">
                    <INPUT TYPE="TEXT" ID="name" PlACEHOLDER="Name" CLASS="form-control">
                    <INPUT TYPE="TEXT" ID="email" PlACEHOLDER="Email Address" CLASS="form-control">


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
        $.post(webroot + "auth/login", {
            action: action,
            _token: token,
            email: $("#login_email").val(),
            password: $("#login_password").val()
        }, function (result) {
            try {
                var data = JSON.parse(result);
                if(data["Status"] == "false" || action == "forgotpassword") {
                    alert(data["Reason"]);
                } else {
                    switch (action) {
                        case "login":
                            token = data["Token"];
                            login(data["User"]);
                            $("#loginmodal").modal("hide");
                            break;
                        case "forgotpassword":
                            alert(data["Reason"]);
                            break;
                        case "logout":
                            removeCookie();
                            $(".loggedin").hide();
                            $(".loggedout").show();
                            $(".clear_loggedout").html("");
                            $(".profiletype").hide();

                            if(redirectonlogout){
                                window.location = "<?= webroot("public/index"); ?>";
                            }
                            break;
                    }
                }
            } catch (e){
                alert(result);
            }
        });
    }
</SCRIPT>