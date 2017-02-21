<?php startfile("popups_checkout"); ?>

<h2>My Order
    <button class="btn btn-black btn-sm pull-right dont-show" ONCLICK="confirmclearorder();" id="confirmclearorder"><i class="fa fa-close"></i></button>
</h2>
<hr class="myorderhr">

<div id="myorder" class="text-white"></div>

<button id="checkout-btn" class="btn btn-warning btn-lg btn-circle pull-right" onclick="showcheckout();">
    <i class="fa fa-shopping-cart"></i>
</button>

<div class="modal" id="checkoutmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h2 id="myModalLabel">CHECKOUT</h2>
                <button data-dismiss="modal" data-popup-close="checkoutmodal" class="btn btn-sm  btn-danger"><i class="fa fa-close"></i></button>
            </div>

            <div class="modal-body">

                <FORM ID="orderinfo" name="orderinfo" class="row">

                    <div class="col-md-12">

                        <div class="halfwidth">
                            <?= view("popups_edituser", array("email" => false, "profile1" => true, "password" => false, "phone" => false))->render(); ?>
                        </div>
                        <div class="halfwidth">
                            <?= view("popups_edituser", array("email" => false, "profile1" => false, "password" => false, "phone" => true))->render(); ?>
                        </div>

                        <div class="clear_loggedout addressdropdown proper-height" id="checkoutaddress"></div>

                        <?php
                            if (read("id")) {
                                echo view("popups_address", array("dontincludeAPI" => true, "style" => 1, "saveaddress" => true, "form" => false))->render();
                            }
                        ?>


                    </div>
                    <div class="col-md-12">
                        <h2 class="text-danger">Payment</h2>
                        <DIV ID="credit-info"></DIV>
                    </div>
                    <div class="col-md-12">
                        <input type="text" size="20" class="form-control credit-info" data-stripe="number" placeholder="Card Number">
                    </div>
                    <div class="col-md-12">
                        <div class="thirdwidth">
                            <SELECT CLASS="credit-info form-control" data-stripe="exp_month">
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
                            <div class="clearfix"></div>
                        </div>


                        <div class="thirdwidth">
                            <SELECT CLASS="credit-info form-control" data-stripe="exp_year">
                                <?php
                                $CURRENT_YEAR = date("Y");
                                $TOTAL_YEARS = 6;
                                for ($year = $CURRENT_YEAR; $year < $CURRENT_YEAR + $TOTAL_YEARS; $year++) {
                                    echo '<OPTION VALUE="' . right($year, 2) . '">' . $year . '</OPTION>';
                                }
                                ?>
                            </SELECT>                            <div class="clearfix"></div>

                        </div>


                        <div class="thirdwidth">
                            <input type="text" size="4" data-stripe="cvc" CLASS="credit-info form-control" PLACEHOLDER="CVC">
                            <INPUT class="credit-info" TYPE="hidden" name="istest" id="istest">
                            <a class="credit-info pull-right btn" onclick="testcard();">Test Card</a>
                            <div class="clearfix"></div>
                        </div>


                    </div>
                    <div class="col-md-12">
                        <h2 class="text-danger">Restaurant</h2>
                        <SELECT class="form-control" ID="restaurant" ONCHANGE="restchange();">
                            <OPTION VALUE="0" SELECTED></OPTION>
                        </SELECT>
                        <input type="text" id="cookingnotes" class="form-control" placeholder="Additional Notes" maxlength="255"/>
                        <div class="input-group">
                            <SELECT id="deliverytime" TITLE="Delivery Time" class="form-control"/>
                            <OPTION>Deliver ASAP</OPTION>
                            </SELECT>
                            <span class="input-group-btn">
<!--<comment> why is it when i change this to a button that clicking on please neter abn address closes the modal... order doenst go through when i change it -->
<a class="btn btn-primary text-white pull-right payfororder" onclick="payfororder();">ORDER </a>
</span>
                        </div>
                        <div class="pull-right">
                            <span class="payment-errors error"></span>
                        </div>
                        <!--style>
                            option:nth-child(1), option:nth-child(2), option:nth-child(3) {
                                font-weight: bold;
                            }
                        </style-->
                        <div class="clearfix"></div>
                    </div>
                </FORM>
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
    $('#reg_phone').keypress(function () {
        if ($('#reg_phone').valid()) {
            clearphone();
        }
    });
    @endif

    function restchange() {
        var value = $("#restaurant").val();
        var index = findwhere(closest, "id", value);
        GenerateHours(closest[index]["hours"]);
    }
</SCRIPT>
<?php endfile("popups_checkout"); ?>