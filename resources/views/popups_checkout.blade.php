<?php startfile("popups_checkout"); ?>

<div class="list-group-item">
    <h2 class="mr-auto align-left"> My Order</h2>
    <button class="btn-sm dont-show text-muted ml-auto align-right bg-transparent" ONCLICK="confirmclearorder();" id="confirmclearorder"><i class="fa fa-close"></i></button>
</div>

<div id="myorder"></div>

<button id="checkout-btn" class="list-padding btn btn-primary btn-block" onclick="showcheckout();">
    <i class="fa fa-shopping-basket mr-2"></i> CHECKOUT
</button>

<div class="modal" id="checkoutmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="myModalLabel">Hi, <SPAN CLASS="session_name"></SPAN></h2>
                <button data-dismiss="modal" data-popup-close="checkoutmodal" class="btn btn-sm ml-auto align-middle bg-transparent"><i class="fa fa-close"></i></button>
            </div>
            <div class="modal-body" style="padding: 0 !important;">
                <FORM ID="orderinfo" name="orderinfo">
                    <div class="input_left_icon" id="red_address"><i style="font-size: 1.1rem !important;" class="fa fa-map-marker"></i></div>
                    <div class="input_right">
                        <div class="clear_loggedout addressdropdown proper-height" id="checkoutaddress"></div>
                        <?php
                            if (read("id")) {
                                echo view("popups_address", array("dontincludeAPI" => true, "style" => 1, "saveaddress" => true, "form" => false, "findclosest" => true, "autored" => "red_address"))->render();
                            }
                        ?>
                    </div>

                    <div class="input_left_icon" id="red_rest"><i class="fa fa-cutlery"></i></div>
                    <div class="input_right">
                        <SELECT class="form-control" ID="restaurant" ONCHANGE="restchange();">
                            <OPTION VALUE="0" SELECTED>Restaurant</OPTION>
                        </SELECT>
                    </div>

                    <div class="input_left_icon"><i  style="font-size: 1.1rem !important;" c class="fa fa-clock-o"></i></div>
                    <div class="input_right">
                        <div>
                            <SELECT id="deliverytime" TITLE="Delivery Time" class="form-control"/>
                                <OPTION>Deliver ASAP</OPTION>
                            </SELECT>
                        </div>
                    </div>

                    @if(!session()->get('session_phone'))
                        <div class="input_left_icon" id="red_phone"><i class="fa fa-mobile-phone" style="font-size: 1.5rem !important;"></i></div>
                        <div class="input_right">
                                <?= view("popups_edituser", array("email" => false, "profile1" => false, "password" => false, "phone" => true,
                                "required" => true, "icons" => false, "autored" => "red_phone"))->render(); ?>
                        </div>
                    @endif

                    <div class="input_left_icon" id="red_card"><i class="fa fa-credit-card-alt" style="padding-top: 12px;"></i></div>
                    <div class="input_right">
                        <DIV ID="credit-info"></DIV>
                    </div>

                    <div class="input_left_icon"></div>
                    <div class="input_right">
                        <input type="text" size="20" class="form-control credit-info" autored="red_card" data-stripe="number" placeholder="Card Number">
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
                            <input type="text" size="4" data-stripe="cvc" CLASS="credit-info form-control" autored="red_card" PLACEHOLDER="CVC" style="padding: .54rem .75rem;">
                            <INPUT class="credit-info" TYPE="hidden" name="istest" id="istest">
                            @if(!islive()) <a class="credit-info pull-right btn" onclick="testcard();" TITLE="Don't remove this, I need it!">Test Card</a> @endif
                            <div class="clearfix"></div>
                        </div>
                    </div>


                    <div class="input_left_icon"></div>
                    <div class="input_right">
                        <textarea placeholder="Order Notes" id="cookingnotes" class="form-control" maxlength="255"></textarea>
                        <button style="padding-left: 1rem !important;padding-right: 1rem !important;" class="pull-right radius0 btn btn-primary text-white payfororder" onclick="payfororder();return false;"> <i class="fa fa-check mr-2"></i> ORDER</button>
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
            //$("#restaurant").addClass("red");
            $("#red_rest").addClass("redhighlite");
        } else {
            //$("#restaurant").removeClass("red");
            $("#red_rest").removeClass("redhighlite");
        }
        if(closest.length>0) {
            GenerateHours(closest[index].hours);//GenerateHours(closest[index]["hours"]);
        }
    }

    function fffa() {
        $("#ffaddress").text($("#formatted_address").val());
        $('#checkoutmodal').modal('show');
        $("#firefoxandroid").hide();
    }

    $('#orderinfo input').each(function() {
        $(this).click(function(){refreshform(this)}).blur(function(){refreshform(this)});
        refreshform(this);
    });

    function refreshform(t){
        var ID = t;
        var value = $(t).val();
        var classname = "red";
        if ($(t).hasAttr("autored")){
            ID = "#" + $(t).attr("autored").replaceAll('"', "");
            classname = "redhighlite";
        }
        if ($(t).hasAttr("autored") || $(t).hasClass("autored")){
            if (value) {
                $(ID).removeClass(classname);
            } else {
                $(ID).addClass(classname);
            }
        }
    }
</SCRIPT>
<?php endfile("popups_checkout"); ?>

<DIV ID="firefoxandroid" class="fullscreen grey-backdrop dont-show">
    <DIV CLASS="centered firefox-child bg-white">
        <i class="fa fa-firefox"></i> Firefox Address editor
        <DIV ID="gmapffac" class="bg-white"></DIV>
        <BUTTON ONCLICK="fffa();" CLASS="btn btn-primary radius0 btn-full pull-down-right">OK</BUTTON>
    </DIV>
</DIV>