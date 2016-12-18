<!-- edit profile Modal -->
<div class="modal" id="profilemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
     data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-popup-close="profilemodal" old-data-dismiss="modal"
                        aria-label="Close"><i class="fa fa-close"></i></button>

                <div>
                    <h4 class="modal-title" id="myModalLabel">Edit Profile</h4>
                </div>

                <FORM NAME="user" id="userform">
                    @include("popups_edituser", array("showpass" => true, "email" => false))
                </FORM>

                <DIV ID="addresslist"></DIV>
                <DIV ID="cardlist"></DIV>


                <DIV CLASS="row">
                    <DIV CLASS="col-md-12" align="center">


                        <BUTTON CLASS="btn btn-primary pull-right" onclick="userform_submit();">SAVE</BUTTON>
                        <button ONCLICK="handlelogin('logout');" CLASS="btn btn-secondary pull-left" href="#">
                            LOG OUT
                        </button>

                    </DIV>
                </DIV>
            </div>
        </div>
    </div>
</div>
<!-- end edit profile Modal -->
