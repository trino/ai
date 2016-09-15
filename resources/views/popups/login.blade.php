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
                    <DIV CLASS="col-md-6">
                        <button class="btn btn-block btn-warning" onclick="handlelogin('login');">
                            LOGIN
                        </button>
                    </DIV>
                    <DIV CLASS="col-md-6">
                        <button class="btn btn-block btn-danger" onclick="handlelogin('forgotpassword');">
                            FORGOT PASSWORD
                        </button>
                    </DIV>
                </DIV>
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