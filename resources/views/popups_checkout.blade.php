<?php startfile("popups_checkout"); ?>

<div class="list-group-item">
    <h2 class="mr-auto align-left"> My Order</h2>
    <button class="btn-sm dont-show  text-muted  ml-auto align-right bg-transparent   " ONCLICK="confirmclearorder();" id="confirmclearorder"><i class="fa fa-close"></i></button>
</div>

<div id="myorder"></div>

<button id="checkout-btn" class="list-padding btn btn-primary btn-block" onclick="showcheckout();">
    <i class="fa fa-shopping-basket mr-3"></i> CHECKOUT
</button>

<div class="modal" id="checkoutmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="list-group-item bg-white">
                <h2 class="" id="myModalLabel">Hi, <SPAN CLASS="session_name"></SPAN></h2>
                <button data-dismiss="modal" data-popup-close="checkoutmodal" class="btn btn-sm ml-auto align-middle bg-transparent"><i class="fa fa-close"></i></button>
            </div>
            <div class="modal-body" style="padding: 0 !important;">
                <FORM ID="orderinfo" name="orderinfo">
                    <div class="input_left_icon"><i class="fa fa-home"></i></div>
                    <div class="input_right">
                        <div class="clear_loggedout addressdropdown proper-height" id="checkoutaddress"></div>
                        <?php
                        if (read("id")) {
                            echo view("popups_address", array("dontincludeAPI" => true, "style" => 1, "saveaddress" => true, "form" => false, "findclosest" => true))->render();
                        }
                        ?>
                    </div>
                    <div class="input_left_icon"><i class="fa fa-cutlery"></i></div>
                    <div class="input_right">
                        <SELECT class="form-control red" ID="restaurant" ONCHANGE="restchange();">
                            <OPTION VALUE="0" SELECTED>Restaurant</OPTION>
                        </SELECT>
                    </div>



                    <div class="input_left_icon"><i class="fa fa-clock-o"></i></div>
                    <div class="input_right">
                        <div class="">
                            <SELECT id="deliverytime" TITLE="Delivery Time" class="form-control"/>
                            <OPTION>Deliver ASAP</OPTION>
                            </SELECT>
                        </div>

                    </div>



                    <div class="input_left_icon"><i class="fa fa-pencil"></i></div>
                    <div class="input_right">
                        <input type="text" id="cookingnotes" class="form-control" placeholder="Order Notes" maxlength="255"/>
                    </div>

                    @if(!session()->get('session_phone'))
                    <div class="input_left_icon"><i class="fa fa-user"></i></div>
                    <div class="input_right">
                            <!--?= view("popups_edituser", array("email" => false, "profile1" => true, "password" => false, "phone" => false, "required" => true, "icons" => false))->render(); ?-->


                            <?= view("popups_edituser", array("email" => false, "profile1" => false, "password" => false, "phone" => true, "required" => true, "icons" => false))->render(); ?>

                    </div>

                    @endif


                    <div class="input_left_icon"><i class="fa fa-credit-card-alt"></i></div>
                    <div class="input_right">
                        <DIV ID="credit-info"></DIV>
                    </div>

                    <div class="input_left_icon"></div>
                    <div class="input_right">
                        <input type="text" size="20" class="form-control credit-info" data-stripe="number" placeholder="Card Number">
                    </div>

                    <div class="input_left_icon"></div>
                    <div class="input_right">
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
                            </SELECT>
                            <div class="clearfix"></div>
                        </div>
                        <div class="thirdwidth">
                            <input type="text" size="4" data-stripe="cvc" CLASS="credit-info form-control" PLACEHOLDER="CVC" style="padding: .535rem .75rem;">
                            <INPUT class="credit-info" TYPE="hidden" name="istest" id="istest">
                            @if(!islive()) <a class="credit-info pull-right btn" onclick="testcard();" TITLE="Don't remove this, I need it!">Test Card</a> @endif
                            <div class="clearfix"></div>
                        </div>
                    </div>


                    <div class="input_left_icon"></div>
                    <div class="input_right">
                        <!--<comment> why is it when i change this to a button that clicking on please enter an address closes the modal... order doesn't go through when I change it -->

                        <a style="vertical-align: middle !important;" class="radius0 btn btn-primary text-white pull-right payfororder" onclick="payfororder();"> <i class="fa fa-check mr-2"></i> ORDER
                        </a>
                        <span class="payment-errors error"></span>

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
        $(".credit-info").change(function () {
            if (isvalidcreditcard()) {
                $(".payment-errors").text("");
            }
        });
    });
    $('#reg_phone').keypress(function () {
        if ($('#reg_phone').valid()) {
            clearphone();
        }
    });
    @endif

    function restchange() {
        var value = $("#restaurant").val();
        var index = findwhere(closest, "restid", value);
        if (value == 0) {
            $("#restaurant").addClass("red");
        } else {
            $("#restaurant").removeClass("red");
        }
        GenerateHours(closest[index].hours);//GenerateHours(closest[index]["hours"]);
    }

    function fffa() {
        $("#ffaddress").text($("#formatted_address").val());
        $('#checkoutmodal').modal('show');
        $("#firefoxandroid").hide();
    }
</SCRIPT>
<?php endfile("popups_checkout"); ?>

<DIV ID="firefoxandroid" class="fullscreen grey-backdrop dont-show">
    <DIV CLASS="centered firefox-child bg-white">
        <i class="fa fa-firefox"></i> Firefox Address editor
        <DIV ID="gmapffac" class="bg-white"></DIV>
        <BUTTON ONCLICK="fffa();" CLASS="btn btn-primary radius0 btn-full">OK</BUTTON>
    </DIV>
</DIV>