<?php startfile("popups_checkout"); ?>

<div class="row">
    <div class="col-md-12  bg-danger text-white pt-1">

        <div class="text-white text-xs-center pb-1">
            <strong>MY ORDER</strong>
            <a class="text-white" ONCLICK="confirmclearorder();">
                <i class="fa fa-close" style="width: 25px;"></i>
            </a>
        </div>
        <div id="myorder" style="font-size: .9rem !important;"></div>
        <button id="checkout-btn" class="btn btn-warning btn-block" onclick="showcheckout();">
            CHECKOUT
        </button>


    </div>
</div>
<div class="modal" id="checkoutmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <FORM ID="orderinfo" name="orderinfo">
                <div class="modal-body pa-0">
                    <div class="row">
                        <DIV CLASS="col-xs-12 " style="padding-bottom: .25rem !important; ">
                            <button type="button" class="close" data-popup-close="checkoutmodal" old-data-dismiss="modal"
                                    aria-label="Close"><i class="fa fa-close"></i></button>
                            <strong id="myModalLabel">CHECKOUT</strong>
                        </DIV>
                        <div class="col-xs-12">
                            <?= view("popups_edituser", array("email" => false, "password" => false, "phone" => false))->render(); ?>
                            <div class="clear_loggedout addressdropdown proper-height" id="checkoutaddress"></div>
                        </div>
                        <div class="col-xs-12">
                            <?php
                            if (read("id")) {
                                echo view("popups_address", array("dontincludeAPI" => true, "style" => 1, "saveaddress" => true, "form" => false))->render();
                            }
                            ?>
                        </DIV>
                        <div class="col-xs-12">
                            <input type="text" class="form-control" ID="restaurant" placeholder="Closest Restaurant" READONLY TITLE="Closest restaurant"/>
                            <input type="text" id="cookingnotes" class="form-control" placeholder="Notes for the Cook" maxlength="255"/>
                        </DIV>
                        <div class="col-xs-12">
                            <DIV ID="credit-info"></DIV>
                            <input type="text" size="20" class="form-control credit-info" data-stripe="number" placeholder="Card Number">
                        </div>
                        <div class="credit-info">
                            <DIV CLASS="col-xs-4 pr-0">
                                <SELECT CLASS="form-control proper-height" data-stripe="exp_month">
                                    <OPTION VALUE="01">01/Jan</OPTION>
                                    <OPTION VALUE="02">02/Feb</OPTION>
                                    <OPTION VALUE="03">03/Mar</OPTION>
                                    <OPTION VALUE="04">04/Apr</OPTION>
                                    <OPTION VALUE="05">05/May</OPTION>
                                    <OPTION VALUE="06">06/Jun</OPTION>
                                    <OPTION VALUE="07">07/Jul</OPTION>
                                    <OPTION VALUE="08">08/Aug</OPTION>
                                    <OPTION VALUE="09">09/Sep</OPTION>
                                    <OPTION VALUE="10">10/Oct</OPTION>
                                    <OPTION VALUE="11">11/Nov</OPTION>
                                    <OPTION VALUE="12">12/Dec</OPTION>
                                </SELECT>
                            </DIV>
                            <DIV CLASS="col-xs-4  px-0">
                                <SELECT CLASS="form-control proper-height" data-stripe="exp_year">
                                    <?php
                                    $CURRENT_YEAR = date("Y");
                                    $TOTAL_YEARS = 6;
                                    for ($year = $CURRENT_YEAR; $year < $CURRENT_YEAR + $TOTAL_YEARS; $year++) {
                                        echo '<OPTION VALUE="' . right($year, 2) . '">' . $year . '</OPTION>';
                                    }
                                    ?>
                                </SELECT>
                            </DIV>
                            <DIV CLASS="col-xs-4  pl-0">
                                <input type="text" size="4" data-stripe="cvc" CLASS="form-control proper-height"
                                       PLACEHOLDER="CVC">
                                <INPUT TYPE="hidden" name="istest" id="istest">
                            </DIV>
                            <a class="pull-right btn" onclick="testcard();">Test CreditCard</a>
                        </div>
                        <div class="col-xs-12">
                            <SELECT id="deliverytime" TITLE="Delivery Time" class="form-control proper-height"/>
                            <OPTION>Deliver Now</OPTION>
                            </SELECT>
                        </div>
                        <div class="col-xs-8">
                            <DIV ID="form_integrity">
                                <DIV class="payment-errors"></DIV>
                            </div>
                        </DIV>
                        <div class="col-xs-4">
                            <a class="btn btn-warning text-white pull-right" onclick="payfororder();">PLACE ORDER</a>
                        </div>
                    </div>
                </div>
            </FORM>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

<SCRIPT>
    //https://stripe.com/docs/custom-form
    @if(read("id"))
    $(document).ready(function () {
        getcloseststore = true;
        visible_address(false);
        $("#saveaddresses").append('<OPTION VALUE="addaddress" ID="addaddress">Add Address</OPTION>');
    });
    @endif
    function visible_address(state) {
        if (state) {
            $("#formatted_address").show();
            $("#add_unit").show();
        } else {
            $("#formatted_address").hide();
            $("#add_unit").hide();
        }
    }
    <?php if (islive() || $GLOBALS["testlive"]) {
        echo "Stripe.setPublishableKey('pk_vnR0dLVmyF34VAqSegbpBvhfhaLNi'); //live";
    } else {
        echo "Stripe.setPublishableKey('pk_rlgl8pX7nDG2JA8O3jwrtqKpaDIVf'); //test";
    }?>
    //uses showcheckout();
</SCRIPT>

<?php endfile("popups_checkout"); ?>