<!-- edit profile Modal -->
<div class="modal" id="profilemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
     data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-popup-close="profilemodal" old-data-dismiss="modal"  aria-label="Close"><i class="fa fa-close"></i></button>

                <strong id="myModalLabel">My Profile</strong>

                <FORM NAME="user" id="userform">

                    @include("popups_edituser", array("showpass" => true, "email" => false))

                    <BUTTON CLASS="btn btn-secondary" onclick="userform_submit();">SAVE</BUTTON>

                </FORM>

                <DIV ID="addresslist"></DIV>
                <DIV ID="cardlist"></DIV>

                <button ONCLICK="handlelogin('logout');" CLASS="btn btn-secondary pull-left" href="#">
                    LOG OUT
                </button>
                <DIV class="clearfix"></DIV>

            </div>
        </div>
    </div>
</div>
<!-- end edit profile Modal -->
