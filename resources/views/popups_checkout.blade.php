<div class="modal" id="checkoutmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h4 id="myModalLabel">Checkout</h4>

                <FORM ID="orderinfo" name="orderinfo">
                    <?= view("popups_edituser", array("email" => false, "password" => false, "phone" => "required"))->render(); ?>

                    <div class="clear_loggedout addressdropdown" id="checkoutaddress"></div>
                    <?php
                        if (read("id")) {
                            //can only be included once, and is in the login modal
                            echo view("popups_address", array("dontincludeAPI" => true, "style" => 1, "saveaddress" => true, "form" => false))->render();
                        }
                        echo '<input type="text" class="form-control corner-top" ID="restaurant" placeholder="Restaurant Select" TITLE="Closest restaurant"/>';
                        echo '<SELECT id="deliverytime" TITLE="Delivery Time" class="form-control"/>';
                        echo '<OPTION>Deliver ASAP</OPTION>';
                        /*
                        function rounduptoseconds($time, $seconds) {
                            $r = $time % $seconds;
                            return $time + ($seconds - $r);
                        }
                        $mindeliverytime = 30 * 60;//30 minutes
                        $now = rounduptoseconds(time() + $mindeliverytime, 900);

                        echo '<OPTION>Deliver ASAP</OPTION>';
                        for ($i = 0; $i < 10; $i++) {
                            //what is the end time?
                            echo '<OPTION VALUE="' . $now . '">Today at ' . date('g:ia', $now) . '</OPTION>';
                            $now += 15 * 60;
                        }
                        */

                        echo '</SELECT>';
                    ?>
                    <input type="text" id="cookingnotes" class="form-control" placeholder="Notes for the Cook" maxlength="255"/>

                    <DIV class="row">
                        <DIV CLASS="col-md-12">
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#">Card</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Cash on Delivery</a>
                                </li>
                            </ul>
                        </DIV>

                        <DIV CLASS="col-md-12">
                            <a class="btn btn-primary" onclick="testcard();" STYLE="padding-left: 8px;">Test</a>
                        </DIV>

                        <DIV CLASS="col-md-12">
                            <input type="text" size="20" class="form-control" data-stripe="number" placeholder="Card Number">
                        </DIV>


                        <DIV CLASS="col-xs-4">
                            <SELECT CLASS="form-control" data-stripe="exp_month">
                                <!-- Do not remove the month names, trust me on this. Some customers only know their expiry date by it -->
                                <OPTION VALUE="01">01 - January</OPTION>
                                <OPTION VALUE="02">02 - February</OPTION>
                                <OPTION VALUE="03">03 - March</OPTION>
                                <OPTION VALUE="04">04 - April</OPTION>
                                <OPTION VALUE="05">05 - May</OPTION>
                                <OPTION VALUE="06">06 - June</OPTION>
                                <OPTION VALUE="07">07 - July</OPTION>
                                <OPTION VALUE="08">08 - August</OPTION>
                                <OPTION VALUE="09">09 - September</OPTION>
                                <OPTION VALUE="10">10 - October</OPTION>
                                <OPTION VALUE="11">11 - November</OPTION>
                                <OPTION VALUE="12">12 - December</OPTION>
                            </SELECT>
                        </DIV>
                        <DIV CLASS="col-xs-4">
                            <SELECT CLASS="form-control" data-stripe="exp_year">
                                <?php
                                    $CURRENT_YEAR = date("Y");
                                    $TOTAL_YEARS = 6;
                                    for($year = $CURRENT_YEAR; $year<$CURRENT_YEAR+$TOTAL_YEARS; $year++){
                                        echo '<OPTION VALUE="' . right($year,2) . '">' . $year . '</OPTION>';
                                    }
                                ?>
                            </SELECT>
                        </DIV>
                        <DIV CLASS="col-xs-4">
                            <input type="text" size="4" data-stripe="cvc" CLASS="form-control" PLACEHOLDER="CVC">
                        </DIV>
                    </DIV>
<div class="row">
                    <DIV class="col-md-12 payment-errors" style="color:red;"></DIV>
                        <div class="col-xs-6 ">
                            <a type="button" class="btn btn-secondary waves-effect btn-block" data-dismiss="modal" aria-label="Close">
                                CANCEL
                            </a>
                        </div>

                        <div class="col-xs-6">
                            <a class="btn btn-warning btn-block" onclick="payfororder();">PLACE ORDER</a>
                        </div>
                    </div>

                    <DIV ID="form_integrity" style="color:red;"></DIV>
                </FORM>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<SCRIPT>
    //var oldJQueryEventTrigger = jQuery.event.trigger;
    //jQuery.event.trigger = function( event, data, elem, onlyHandlers ) {
    //    console.log( event, data, elem, onlyHandlers );
    //    oldJQueryEventTrigger( event, data, elem, onlyHandlers );
    //}

    //https://stripe.com/docs/custom-form
    var canplaceorder = false;
    @if(read("id"))
        $( document ).ready(function() {
            getcloseststore = true;
        });
    @endif

    <?php if (!islive()) {
        echo "Stripe.setPublishableKey('pk_rlgl8pX7nDG2JA8O3jwrtqKpaDIVf'); //test";
    } else {
        echo "Stripe.setPublishableKey('pk_vnR0dLVmyF34VAqSegbpBvhfhaLNi'); //live";
    }?>
</SCRIPT>