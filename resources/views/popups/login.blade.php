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

                <DIV STYLE="margin-top: 15px;">
                    <DIV CLASS="col-md-6">
                        <button class="btn btn-block btn-warning" onclick="login();">
                            LOGIN
                        </button>
                    </DIV>
                    <DIV CLASS="col-md-6">
                        <button class="btn btn-block btn-danger" onclick="forgotpassword();">
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
    function login(){

    }
    function forgotpassword(){

    }
</SCRIPT>