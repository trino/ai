<!-- edit profile Modal -->
<div class="modal" id="profilemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="myModalLabel">MY ACCOUNT</h2>
                <button data-popup-close="profilemodal" data-dismiss="modal" class="btn btn-sm  btn-danger" ><i class="fa fa-close"></i> </button>
            </div>

            <div class="modal-body">
                <FORM NAME="user" id="userform">
                    @include("popups_edituser", array("showpass" => true, "email" => false))
                    <DIV class="clearfix mt-1"></DIV>
                    <DIV CLASS="error" id="edituser_error"></DIV>
                    <BUTTON CLASS="btn btn-primary" onclick="return userform_submit(true);">SAVE</BUTTON>
                </FORM>

                <div CLASS="editprofilediv">
                    <DIV ID="addresslist"></DIV>
                </div>
                <div CLASS="editprofilediv">
                    <DIV ID="cardlist"></DIV>
                </div>
                <div CLASS="editprofilediv mt-2">
                    <button ONCLICK="orders();" CLASS="btn btn-primary" href="#">PAST ORDERS</button>
                </div>
                <div CLASS="editprofilediv mt-2">
                    <button ONCLICK="handlelogin('logout');" CLASS="btn btn-primary pull-left" href="#">LOG OUT</button>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end edit profile Modal -->