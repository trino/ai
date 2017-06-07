<?php startfile("popups_checkout"); ?>

<div class="list-group-item">
    <h2 class="mr-auto align-left"> My Order</h2>
    <button class="btn-sm dont-show ml-auto align-right bg-transparent" ONCLICK="confirmclearorder();" id="confirmclearorder"><i class="fa fa-close"></i></button>
</div>

<div id="myorder"></div>

@if(read("id"))
    <button id="checkout-btn" class="list-padding btn btn-primary btn-block" onclick="showcheckout();">
        <i class="fa fa-shopping-basket mr-2"></i> CHECKOUT
    </button>
@else
    <button id="checkout-btn" class="list-padding btn btn-primary btn-block" onclick="scrolltotop();">
        <i class="fa fa-sign-in mr-2"></i> LOG IN TO CHECKOUT
    </button>
@endif

<div class="modal" id="checkoutmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="myModalLabel" style="text-transform: uppercase;">Hi, <SPAN CLASS="session_name"></SPAN></h2>
                <button data-dismiss="modal" data-popup-close="checkoutmodal" class="btn btn-sm ml-auto align-middle bg-transparent"><i class="fa fa-close"></i></button>
            </div>
            <div class="modal-body" style="padding: 0 !important;">
                <FORM ID="orderinfo" name="orderinfo">
                    <div class="input_left_icon" id="red_address">
                        <span class="fa-stack fa-2x">
                          <i class="fa fa-circle fa-stack-2x"></i>
                          <i class="fa fa-map-marker text-white fa-stack-1x"></i>
                        </span>
                    </div>

                    <div class="input_right">
                        <div class="clear_loggedout addressdropdown proper-height" id="checkoutaddress"></div>
                        <?php
                        if (read("id")) {
                            echo popups_view("address", array("dontincludeAPI" => true, "style" => 1, "saveaddress" => true, "form" => false, "findclosest" => true, "autored" => "red_address"));
                        }
                        ?>
                    </div>

                    <div class="input_left_icon" id="red_rest">
                        <span class="fa-stack fa-2x">
                          <i class="fa fa-circle fa-stack-2x"></i>
                          <i class="fa fa-cutlery text-white fa-stack-1x" style="font-size: .9rem !important;"></i>
                        </span>
                    </div>

                    <div class="input_right">
                        <SELECT class="form-control" ID="restaurant" ONCHANGE="restchange();">
                            <OPTION VALUE="0" SELECTED>Select Restaurant</OPTION>
                        </SELECT>
                    </div>

                    <div class="input_left_icon">
                        <span class="fa-stack fa-2x">
                          <i class="fa fa-circle fa-stack-2x"></i>
                          <i class="fa fa-clock-o text-white fa-stack-1x"></i>
                        </span>
                    </div>

                    <div class="input_right">
                        <div>
                            <SELECT id="deliverytime" TITLE="Delivery Time" class="form-control"/>
                                <OPTION>Deliver ASAP</OPTION>
                            </SELECT>
                        </div>
                    </div>

                    @if(!read('phone'))
                        <div class="input_left_icon redhighlite" id="red_phone">
                            <span class="fa-stack fa-2x">
                              <i class="fa fa-circle fa-stack-2x"></i>
                              <i class="fa fa-mobile-phone text-white fa-stack-1x" style="font-size: 1.5rem !important;"></i>
                            </span>
                        </div>
                        <div class="input_right">
                            <input type="tel" name="phone" id="reg_phone" class="form-control session_phone_val" placeholder="Cell Phone" required="true" autored="red_phone" aria-required="true">
                        </div>
                    @endif

                    <div class="input_left_icon" id="red_card">
                        <span class="fa-stack fa-2x">
                          <i class="fa fa-circle fa-stack-2x"></i>
                          <i class="fa fa-credit-card-alt text-white fa-stack-1x" style="font-size: .9rem !important;"></i>
                        </span>
                    </div>

                    <div class="input_right">
                        <DIV ID="credit-info"></DIV>
                    </div>

                    <div class="input_right">
                        <input type="text" size="20" class="form-control credit-info" autored="red_card" data-stripe="number" placeholder="Card Number">
                    </div>

                    <div class="input_left_icon"></div>
                    <div class="input_right">
                        <div class="thirdwidth">
                            <SELECT  style="margin-top: 0 !important;" CLASS="credit-info form-control" data-stripe="exp_month">
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
                            <SELECT style="margin-top: 0 !important;" CLASS="credit-info form-control" data-stripe="exp_year">
                                <?php
                                    $CURRENT_YEAR = date("Y");
                                    $TOTAL_YEARS = 6;
                                    for ($year = $CURRENT_YEAR; $year < $CURRENT_YEAR + $TOTAL_YEARS; $year++) {
                                        echo '<OPTION VALUE="' . right($year, 2) . '">' . $year . '</OPTION>';
                                    }
                                ?>
                            </SELECT>
                            <div class="clearfix"></div>
                        </div>
                        <div class="thirdwidth">
                            <input type="text" size="4" data-stripe="cvc" CLASS="credit-info form-control" autored="red_card" PLACEHOLDER="CVC" style="padding: 0 !important;">
                            <INPUT class="credit-info" TYPE="hidden" name="istest" id="istest">
                            @if(!islive()) <a class="credit-info pull-right btn" onclick="testcard();" TITLE="Don't remove this, I need it!">Test Card</a> @endif
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="input_left_icon">
                        <span class="fa-stack fa-2x">
                        <i class="fa fa-circle fa-stack-2x"></i>
                        <i class="fa fa-pencil text-white fa-stack-1x"></i>
                        </span>
                    </div>
                    <div class="input_right">
                        <textarea placeholder="Order Notes" id="cookingnotes" class="form-control" maxlength="255"></textarea>
                    </div>

                    <button class="btn-block list-padding radius0 btn btn-primary text-white payfororder" onclick="payfororder(); return false;"><i class="fa fa-check mr-2"></i> ORDER</button>
                    <span class="payment-errors error"></span>
                    <div class="clearfix"></div>

                </FORM>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

<DIV ID="firefoxandroid" class="fullscreen grey-backdrop dont-show">
    <DIV CLASS="centered firefox-child bg-white">
        <i class="fa fa-firefox"></i> Firefox Address editor
        <DIV ID="gmapffac" class="bg-white"></DIV>
        <BUTTON ONCLICK="fffa();" CLASS="btn btn-primary radius0 btn-full pull-down-right">OK</BUTTON>
    </DIV>
</DIV>

<?php endfile("popups_checkout"); ?>