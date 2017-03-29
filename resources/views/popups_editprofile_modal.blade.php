<!-- edit profile Modal -->
<div class="modal" id="profilemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="myModalLabel">My Account</h2>
                <button data-popup-close="profilemodal" data-dismiss="modal" class="btn btn-sm"><i class="fa fa-close"></i></button>
            </div>

            <div class="modal-body">
                <FORM NAME="user" id="userform">
                    @include("popups_edituser", array("showpass" => true, "email" => false, "icons" => true))
                    <DIV class="clearfix mt-1"></DIV>
                    <DIV CLASS="error" id="edituser_error"></DIV>
                    <DIV class="clearfix mt-1"></DIV>

                    <BUTTON CLASS="btn-link pull-right" onclick="return userform_submit(true);">SAVE</BUTTON>
                </FORM>

                <div CLASS="editprofilediv">
                    <DIV ID="addresslist"></DIV>
                </div>
                <div CLASS="editprofilediv">
                    <DIV ID="cardlist"></DIV>
                </div>
                <div class="alert alert-info mt-3" style="font-size: .85rem">
                    > Past orders can be found in your email<br>
                    > Add New Address on Checkout
                    <br> > Add New Credit/Debit Card on Checkout
                    <br>
                    > <a href="help" class="btn-link">More Info</a>
                </div>
                <!--div CLASS="editprofilediv mt-2">
                    <button ONCLICK="orders();" CLASS="btn btn-primary" href="#">PAST ORDERS</button>
                </div>
                <div CLASS="editprofilediv mt-2">
                    <button ONCLICK="handlelogin('logout');" CLASS="btn btn-primary pull-left" href="#">LOG OUT</button>
                    <div class="clearfix"></div>
                </div-->
            </div>
        </div>
    </div>
</div>
<!-- end edit profile Modal -->