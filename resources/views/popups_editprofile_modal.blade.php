<!-- edit profile Modal -->
<div class="modal" id="profilemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
data-keyboard="false" data-backdrop="static">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-popup-close="profilemodal" old-data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
<strong id="myModalLabel">My Account</strong>
</div>

<div class="modal-body">
<FORM NAME="user" id="userform">
@include("popups_edituser", array("showpass" => true, "email" => false))
<DIV class="clearfix mt-1"></DIV><BUTTON CLASS="btn btn-primary" onclick="userform_submit();">SAVE</BUTTON></FORM>
</div>
<div class="" style="padding: 15px; border-top: 1px solid #e5e5e5" ><DIV ID="addresslist"></DIV></div>
<div class="" style="padding: 15px; border-top: 1px solid #e5e5e5" ><DIV ID="cardlist"></DIV></div>
<div class="" style="padding: 15px; border-top: 1px solid #e5e5e5" >
    <button ONCLICK="handlelogin('logout');" CLASS="btn btn-primary pull-left" href="#">LOG OUT</button>
<div class="clearfix"></div>
</div>
</div>
</div>
</div>
<!-- end edit profile Modal -->
