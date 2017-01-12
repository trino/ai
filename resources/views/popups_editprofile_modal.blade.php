<!-- edit profile Modal -->
<div class="modal" id="profilemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-popup-close="profilemodal" old-data-dismiss="modal" aria-label="Close">&times;</button>
                <strong id="myModalLabel">MY ACCOUNT</strong>
            </div>
            
            <div class="modal-body">
                <FORM NAME="user" id="userform">
                @include("popups_edituser", array("showpass" => true))
                <DIV class="clearfix mt-1"></DIV><BUTTON CLASS="btn btn-success" onclick="userform_submit();">SAVE</BUTTON></FORM></div>
                <div CLASS="editprofilediv"><DIV ID="addresslist"></DIV></div>
                <div CLASS="editprofilediv"><DIV ID="cardlist"></DIV></div>
                <div CLASS="editprofilediv">
                    <button ONCLICK="orders();" CLASS="btn btn-success" href="#">PAST ORDERS</button>
                </div>
                <div CLASS="editprofilediv">
                    <button ONCLICK="handlelogin('logout');" CLASS="btn btn-success pull-left" href="#">LOG OUT</button>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<!-- end edit profile Modal -->
