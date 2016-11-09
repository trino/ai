<div class="modal" id="checkoutmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
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
                            echo view("popups_address", array("dontincludeAPI" => true, "style" => 1, "saveaddress" => true, "form" => false))->render();
                        }
                        echo '<input type="text" class="form-control corner-top" ID="restaurant" readonly placeholder="Restaurant Select" TITLE="Closest restaurant"/>';
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

                    <DIV STYLE="margin-top: 15px;">
                        <DIV class="col-md-12 payment-errors" style="color:red;"></DIV>
                        <?php
                            $cols=12;
                            if(!islive()){
                                $cols-=1;
                                echo '<DIV CLASS="col-md-1"><BUTTON ONCLICK="testcard();" CLASS="form-control btn btn-primary" STYLE="padding-left: 8px;">Test</BUTTON></DIV>';
                            }
                        ?>
                        <DIV CLASS="col-md-{{ $cols }}">
                            <input type="text" size="20" class="form-control" data-stripe="number" placeholder="Card Number">
                        </DIV>
                        <!--DIV CLASS="col-md-4">
                            <input type="text" size="6" data-stripe="address_zip" CLASS="form-control" placeholder="Billing Postal Code">
                        </DIV-->
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
    //https://stripe.com/docs/custom-form
    var canplaceorder = false;
    $( document ).ready(function() {
        getcloseststore = true;
    });

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
        if(!canplaceorder){log("SELECT AN ADDRESS"); return false;}
        var $form = $('#orderinfo');
        $(".payment-errors").html("");
        Stripe.card.createToken($form, stripeResponseHandler);
    }

    function stripeResponseHandler(status, response){
        var errormessage = "";
        switch(status){
            case 400: errormessage = "Bad Request:<BR>The request was unacceptable, often due to missing a required parameter."; break;
            case 401: errormessage = "Unauthorized:<BR>No valid API key provided."; break;
            case 402: errormessage = "Request Failed:<BR>The parameters were valid but the request failed."; break;
            case 404: errormessage = "Not Found:<BR>The requested resource doesn't exist."; break;
            case 409: errormessage = "Conflict:<BR>The request conflicts with another request (perhaps due to using the same idempotent key)."; break;
            case 429: errormessage = "Too Many Requests:<BR>Too many requests hit the API too quickly. We recommend an exponential backoff of your requests."; break;
            case 500: case 502: case 503:case 504: errormessage = "Server Errors:<BR>Something went wrong on Stripe's end."; break;
            case 200:// - OK	Everything worked as expected.
                if (response.error) {
                    $('.payment-errors').html(response.error.message);
                } else {
                    placeorder(response.id);
                }
                break;
        }
        if(errormessage){$(".payment-errors").html(errormessage + "<BR><BR>" + response["error"]["type"] + ":<BR>" + response["error"]["message"]);}
    }

    function addresshaschanged() {
        if(!getcloseststore){return;}
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
                    GenerateHours(closest["hours"]);
                }
                $("#restaurant").val(restaurant);
            }
        });
    }

    function showcheckout() {
        var HTML = $("#checkoutaddress").html();
        HTML = HTML.replace('class="', 'class="corner-top ');
        $("#checkoutaddress").html(HTML);
        $("#checkoutmodal").modal("show");
        $(function () {
            $("#orderinfo").validate({
                submitHandler: function (form) {
                    //handled by placeorder
                }
            });
        });
    }

    var daysofweek = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    var monthnames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    function now(){
        var now = new Date();
        return now.getHours() * 100 + now.getMinutes();
    }
    function getToday(){
        var now = new Date();//doesn't take into account <= because it takes more than 1 minute to place an order
        return now.getDay();
    }
    function GenerateTime(time){
        var minutes = time % 100;
        var thehours = Math.floor(time / 100);
        var hoursAMPM = thehours % 12;
        if (hoursAMPM == 0) {hoursAMPM = 12;}
        var tempstr = hoursAMPM + ":";
        if (minutes < 10) {
            tempstr += "0" + minutes;
        } else {
            tempstr += minutes;
        }
        var extra = "";
        if (time == 0) {
            extra = " (Midnight)";
        } else if (time == 1200) {
            extra = " (Noon)";
        }
        if (time < 1200) {
            return tempstr + " AM" + extra;
        } else {
            return tempstr + " PM" + extra;
        }
    }
    function GenerateHours(hours, increments){
        var now = new Date();//doesn't take into account <= because it takes more than 1 minute to place an order
        if(isUndefined(increments)){increments = 15;}
        var dayofweek = now.getDay();
        var minutesinaday = 1440;
        var totaldays = 2;
        var dayselapsed = 0;
        var today = dayofweek;
        var tomorrow = (today + 1) % 7;
        var time = now.getHours() * 100 + now.getMinutes();
        time = time + (increments - (time % increments));
        var oldValue = $("#deliverytime").val();
        var HTML = '<option>Deliver ASAP</option>';
        var totalInc = (minutesinaday*totaldays) / increments;
        for(var i=0; i<totalInc; i++){
            if(isopen(hours, dayofweek, time) > -1) {
                var minutes = time % 100;
                if(minutes<60) {
                    var thetime = GenerateTime(time);
                    var thedayname = daysofweek[dayofweek];
                    var thedate = monthnames[now.getMonth()] + " " + now.getDate();
                    if (dayofweek == today) {
                        thedayname = "Today";
                    } else if (dayofweek == tomorrow) {
                        thedayname = "Tomorrow";
                    } else {
                        thedayname += " " + thedate;
                    }
                    var tempstr = '<OPTION VALUE="' + thedate + " at " + time + '">' + thedayname + " at " + thetime ;
                    HTML += tempstr + '</OPTION>';
                }
            }

            time = time + increments;
            if(time % 100 >= 60){
                time = (Math.floor(time / 100) + 1) * 100;
            }
            if(time >= 2400){
                time = time % 2400;
                dayselapsed +=1;
                dayofweek = (dayofweek + 1) % 7;
                now = new Date(now.getTime() + 24 * 60 * 60 * 1000);
                if(dayofweek == today || dayselapsed == totaldays){i = totalInc;}
            }
        }

        $("#deliverytimealias").html(HTML);
        $("#deliverytime").html(HTML);
        $("#deliverytime").val(oldValue);
    }

    function isopen(hours, dayofweek, time){
        var now = new Date();//doesn't take into account <= because it takes more than 1 minute to place an order
        if(isUndefined(dayofweek)){dayofweek = now.getDay();}
        if(isUndefined(time)){time = now.getHours() * 100 + now.getMinutes();}
        var today = hours[dayofweek];
        var yesterday = dayofweek - 1;
        if(yesterday<0){yesterday = 6;}
        var yesterdaysdate = yesterday;
        yesterday = hours[yesterday];
        today.open = Number(today.open);
        today.close = Number(today.close);
        yesterday.open = Number(yesterday.open);
        yesterday.close = Number(yesterday.close);
        if(yesterday.open > -1 && yesterday.close > -1 && yesterday.close < yesterday.open){
            if(yesterday.close > time){return yesterdaysdate;}
        }
        if(today.open > -1 && today.close > -1){
            if(today.close < today.open){
                //log("Day: " + dayofweek + " time: " + time + " open: " + today.open + " close: " + today.close );
                if(time >= today.open || time < today.close){return dayofweek;}
            } else {
                if(time >= today.open && time < today.close){return dayofweek;}
            }
        }
        return -1;//closed
    }

    <?php if (!islive()) {
        echo "Stripe.setPublishableKey('pk_rlgl8pX7nDG2JA8O3jwrtqKpaDIVf'); //test";
    } else {
        echo "Stripe.setPublishableKey('pk_vnR0dLVmyF34VAqSegbpBvhfhaLNi'); //live";
    }?>
</SCRIPT>