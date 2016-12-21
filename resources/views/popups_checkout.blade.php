<?php startfile("popups_checkout"); ?>
<div class="card text-white" style="">
    <div class="card-block bg-danger">
        <div class="row">
            <div class="col-md-12">
                <a class="pull-right text-white cursor-pointer" ONCLICK="confirmclearorder();">
                    <i class="fa fa-close"></i>
                </a>
                <h4 class="pull-left text-white">
                    My Order
                </h4>
                <div class="clearfix" style="margin:.5rem 0 1rem 0 !Important;"></div>
                <div id="myorder"></div>
            </div>
        </div>

        <div ID="checkoutbutton"></div>

        <div id="checkout-btn" class="row  mt-1">
            <div class="col-md-12">
                <button class="btn btn-warning btn-block" onclick="showcheckout();">
                    CHECKOUT
                </button>
            </div>
        </div>
    </div>







</div>

<div class="modal" id="checkoutmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
     data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-popup-close="checkoutmodal" old-data-dismiss="modal"
                        aria-label="Close"><i class="fa fa-close"></i></button>
                <h4 id="myModalLabel">Checkout </h4>
                <div class="row">
                    <FORM ID="orderinfo" name="orderinfo">


                        <DIV CLASS="col-xs-12">
                        <?= view("popups_edituser", array("email" => false, "password" => false, "phone" => "required"))->render(); ?>
                        <!-- --------------------------------------------------------------------------------- -->

                            <DIV ID="credit-info"></DIV>
                            <input type="text" size="20" class="form-control credit-info" data-stripe="number"
                                   placeholder="Card Number">

                        </DIV>


                        <div class="credit-info">
                            <DIV CLASS="col-xs-4">
                                <SELECT CLASS="form-control proper-height" data-stripe="exp_month">
                                    <!-- Do not remove the month names, trust me on this. Some customers only know their expiry date by it -->
                                    <OPTION VALUE="01">01 - Jan</OPTION>
                                    <OPTION VALUE="02">02 - Feb</OPTION>
                                    <OPTION VALUE="03">03 - Mar</OPTION>
                                    <OPTION VALUE="04">04 - Apr</OPTION>
                                    <OPTION VALUE="05">05 - May</OPTION>
                                    <OPTION VALUE="06">06 - Jun</OPTION>
                                    <OPTION VALUE="07">07 - Jul</OPTION>
                                    <OPTION VALUE="08">08 - Aug</OPTION>
                                    <OPTION VALUE="09">09 - Sep</OPTION>
                                    <OPTION VALUE="10">10 - Oct</OPTION>
                                    <OPTION VALUE="11">11 - Nov</OPTION>
                                    <OPTION VALUE="12">12 - Dec</OPTION>
                                </SELECT>
                            </DIV>
                            <DIV CLASS="col-xs-4">
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
                            <DIV CLASS="col-xs-4">
                                <input type="text" size="4" data-stripe="cvc" CLASS="form-control proper-height"
                                       PLACEHOLDER="CVC">
                                <INPUT TYPE="hidden" name="istest" id="istest">
                            </DIV>

                            <a class="pull-right btn" onclick="testcard();">Test CreditCard</a>



                        </div>


                        <!-- --------------------------------------------------------------------------------- -->


                        <DIV CLASS="col-xs-12">

                            <div class="clear_loggedout addressdropdown proper-height" id="checkoutaddress"></div>
                        <?php
                        if (read("id")) {
                            //can only be included once, and is in the login modal
                            echo view("popups_address", array("dontincludeAPI" => true, "style" => 1, "saveaddress" => true, "form" => false))->render();
                        }
                        ?>


                        <!-- --------------------------------------------------------------------------------- -->



                            <DIV class="payment-errors"></DIV>

                            <?php
                            echo '<input type="text" class="form-control" ID="restaurant" placeholder="Closest Restaurant" READONLY TITLE="Closest restaurant"/>';

                        ?>

                            <input type="text" id="cookingnotes" class="form-control" placeholder="Notes for the Cook"
                                   maxlength="255"/>

                        <?
                            echo '<SELECT id="deliverytime" TITLE="Delivery Time" class="form-control proper-height"/>';



                            echo '<OPTION>Deliver Now</OPTION>';
                            echo '</SELECT>';
                            ?>


                            <a class="btn btn-warning text-white pull-right" onclick="payfororder();">PLACE ORDER</a>
                            <DIV ID="form_integrity"></DIV>
                        </DIV>
                    </FORM>

                </div>
            </div>
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

    <?php if (!islive()) {
        echo "Stripe.setPublishableKey('pk_rlgl8pX7nDG2JA8O3jwrtqKpaDIVf'); //test";
    } else {
        echo "Stripe.setPublishableKey('pk_vnR0dLVmyF34VAqSegbpBvhfhaLNi'); //live";
    }?>

    //uses showcheckout();
</SCRIPT>
<?php endfile("popups_checkout"); ?>