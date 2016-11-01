<div class="modal" id="checkoutmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    &times;
                </button>

                <h5 id="myModalLabel">Checkout</h5>

                <?= view("popups_edituser", array("email" => false, "password" => false))->render(); ?>

                <FORM ID="orderinfo" name="orderinfo">
                    <div class="clear_loggedout addressdropdown" id="checkoutaddress"></div>
                        <?php
                            if (read("id")) {
                                //can only be included once, and is in the login modal
                                echo view("popups_address", array("dontincludeAPI" => true, "style" => 1, "saveaddress" => true))->render();
                            }
                            echo '<input type="text" class="form-control corner-top" ID="restaurant" readonly placeholder="Restaurant Select" TITLE="Closest restaurant"/>';
                            echo '<SELECT id="deliverytime" TITLE="Delivery Time" class="form-control"/>';
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
                            echo '</SELECT>';
                        ?>
                        <input type="text" id="cookingnotes" class="form-control" placeholder="Notes for the Cook" maxlength="255"/>

                    <DIV STYLE="margin-top: 15px;">
                        <span class="payment-errors"></span>
                        <?php
                            $cols=8;
                            if(!islive()){
                                $cols=7;
                                echo '<DIV CLASS="col-md-1"><BUTTON ONCLICK="testcard();" CLASS="form-control btn btn-primary" STYLE="padding-left: 8px;">Test</BUTTON></DIV>';
                            }
                        ?>
                        <DIV CLASS="col-md-{{ $cols }}">
                            <input type="text" size="20" class="form-control" data-stripe="number" placeholder="Card Number">
                        </DIV>
                        <DIV CLASS="col-md-4">
                            <input type="text" size="6" data-stripe="address_zip" CLASS="form-control" placeholder="Billing Postal Code">
                        </DIV>
                        <DIV CLASS="col-md-4">
                            <SELECT CLASS="form-control" data-stripe="exp_month">
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
                        <DIV CLASS="col-md-4">
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
                        <DIV CLASS="col-md-4">
                            <input type="text" size="4" data-stripe="cvc" CLASS="form-control" PLACEHOLDER="CVC">
                        </DIV>
                    </DIV>

                    <button class="m-b-1 btn btn-warning btn-block" onclick="payfororder();">PLACE ORDER</button>
                    <DIV ID="form_integrity" style="color:red;"></DIV>
                </FORM>
                <div class="clearfix"></div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<SCRIPT>
    var canplaceorder = false;

    function rnd(min, max){
        return Math.round(Math.random() * (max - min) + min);
    }

    @if(!islive())
        function testcard(){
            $('input[data-stripe=number]').val('4242424242424242');
            $('input[data-stripe=address_zip]').val('L8L6V6');
            $('input[data-stripe=cvc]').val(rnd(100,999));
            $('select[data-stripe=exp_year]').val({{ right($CURRENT_YEAR,2) }} + 1);
        }
    @endif

    function payfororder(){
        Stripe.card.createToken($('#orderinfo'), stripeResponseHandler);
    }

    function stripeResponseHandler(status, response){
        var errormessage = "";
        switch(status){
            case 400: errormessage = "Bad Request<BR>The request was unacceptable, often due to missing a required parameter."; break;
            case 401: errormessage = "Unauthorized<BR>No valid API key provided."; break;
            case 402: errormessage = "Request Failed<BR>The parameters were valid but the request failed."; break;
            case 404: errormessage = "Not Found<BR>The requested resource doesn't exist."; break;
            case 409: errormessage = "Conflict<BR>The request conflicts with another request (perhaps due to using the same idempotent key)."; break;
            case 429: errormessage = "Too Many Requests<BR>Too many requests hit the API too quickly. We recommend an exponential backoff of your requests."; break;
            case 500: case 502: case 503:case 504: errormessage = "Server Errors<BR>Something went wrong on Stripe's end. (These are rare.)"; break;
            case 200:// - OK	Everything worked as expected.
                if (response.error) {
                    $('.payment-errors').text(response.error.message);
                } else {
                    placeorder(response.id);
                }
                break;
        }
        if(errormessage){$(".payment-errors").text(errormessage);}
    }

    function addresshaschanged() {
        skiploadingscreen = true;
        $.post(webroot + "placeorder", {
            _token: token,
            info: getform("#orderinfo"),
            action: "closestrestaurant"
        }, function (result) {
            if (handleresult(result)) {
                var closest = JSON.parse(result)["closest"];
                var restaurant = "No restaurant is within range";
                canplaceorder = false;
                if (closest.hasOwnProperty("id")) {
                    canplaceorder = true;
                    restaurant = "[number] [street], [city]";
                    var keys = Object.keys(closest);
                    for (var i = 0; i < keys.length; i++) {
                        var keyname = keys[i];
                        var keyvalue = closest[keyname];
                        restaurant = restaurant.replace("[" + keyname + "]", keyvalue);
                    }
                }
                $("#restaurant").val(restaurant);
            }
        });
    }

    function showcheckout() {
        var HTML = $("#checkoutaddress").html();
        HTML = HTML.replace('class="', 'class="corner-top ');
        $("#checkoutaddress").html(HTML);
        //HTML = HTML.replace('id="saveaddresses"', 'name="cc_addressid" ID="cc_addressid" ').replace("onchange", 'offchange').replace('Select a saved address', 'Billing Address');
        //$("#billingaddress").html(HTML);
        $("#checkoutmodal").modal("show");
        $(function () {
            $("#orderinfo").validate({
                submitHandler: function (form) {
                    //handled by placeorder
                }
            });
        });
    }

    <?php if (!islive()) {
        echo "Stripe.setPublishableKey('pk_rlgl8pX7nDG2JA8O3jwrtqKpaDIVf'); //test";
    } else {
        echo "Stripe.setPublishableKey('pk_vnR0dLVmyF34VAqSegbpBvhfhaLNi'); //live";
    }?>
</SCRIPT>