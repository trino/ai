<!-- edit profile Modal -->
<div class="modal" id="profilemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
     data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-close"></i></button>

                <div>
                    <h5 class="modal-title" id="myModalLabel">Edit Profile</h5>
                </div>

                <FORM NAME="user" id="userform">
                    @include("popups_edituser", array("showpass" => true))
                </FORM>



                <DIV ID="addresslist"></DIV>

                <DIV CLASS="row">
                    <DIV CLASS="col-md-12" align="center">
                        <BUTTON CLASS="btn btn-primary pull-right" onclick="userform_submit();">Save</BUTTON>
                    </DIV>
                </DIV>
            </div>
        </div>
    </div>
</div>
<!-- end edit profile Modal -->
