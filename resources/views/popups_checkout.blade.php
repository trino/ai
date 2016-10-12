<div class="modal" id="checkoutmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

                <div class="form-group">
                    <h5 class="modal-title" id="myModalLabel">Checkout</h5>
                </div>

                <?= view("popups_edituser")->render(); ?>

                <FORM ID="orderinfo" name="orderinfo">
                    <div class="clear_loggedout addressdropdown" id="checkoutaddress"></div>

                    <DIV CLASS="input-group-vertical">
                        <?= view("popups_address", array("dontincludeAPI" => true, "style" => 1, "saveaddress" => true))->render(); ?>
                    </DIV>

                    <DIV CLASS="col-md-12">

                        <DIV CLASS="input-group-vertical">

                            <input type="text" class="form-control" ID="restaurant" readonly placeholder="Restaurant Select" TITLE="Closest restaurant"/>
                            <?php
                                echo '<SELECT class="form-control" id="deliverytime" TITLE="Delivery Time"/>';
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
                            <input type="text" class="form-control" id="cookingnotes" placeholder="Notes for the Cook" maxlength="255"/>
                        </div>

                        <button class="m-b-1 btn btn-warning btn-block" onclick="placeorder();">PLACE ORDER</button>
                        <DIV ID="form_integrity" style="color:red;"></DIV>
                    </DIV>
                </FORM>

                <div class="clearfix"></div>

            </div>
        </div>
    </div>
</div>


<SCRIPT>
    var canplaceorder = false;

    function addresshaschanged(){
        $.post(webroot + "placeorder", {
            _token: token,
            info: getform("#orderinfo"),
            action: "closestrestaurant"
        }, function (result) {
            if (handleresult(result)){
                var closest = JSON.parse(result)["closest"];
                var restaurant = "No restaurant is within range";
                canplaceorder = false;
                if (closest.hasOwnProperty("id")){
                    canplaceorder=true;
                    restaurant = "[number] [street], [city]";
                    var keys = Object.keys(closest);
                    for(var i=0; i<keys.length; i++){
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
        var HTML = $("#checkoutaddress").html().replace('id="saveaddresses"', 'name="cc_addressid" ID="cc_addressid" ').replace("onchange", 'offchange').replace('Select a saved address', 'Billing Address');
        $("#billingaddress").html(HTML);
        $("#checkoutmodal").modal("show");
        $(function () {
            $("#orderinfo").validate({
                submitHandler: function (form) {
                    //handled by placeorder
                }
            });
        });
    }


    /*
     function showcheckout() {
     var ccmessage = "";
     $("#cc_number").attr("allowblank", true);
     if (userdetails["cc_integrity"].length > 0) {
     ccmessage = 'Your credit card is ' + userdetails["cc_integrity"] + ". Please enter a new credit card.";
     $("#cc_number").removeAttr("allowblank");
     }
     $("#cc_number").val(userdetails["cc_number"]);
     $("#cc_integrity").text(ccmessage);
     var HTML = $("#checkoutaddress").html().replace('id="saveaddresses"', 'name="cc_addressid" ID="cc_addressid" ').replace("onchange", 'offchange').replace('Select a saved address', 'Billing Address');
     $("#billingaddress").html(HTML);
     $("#checkoutmodal").modal("show");

     clearValidation("#orderinfo");

     $(function () {
     $("#orderinfo").validate({
     rules: {
     cell: {required: true, phonenumber: true},
     cc_number: "creditcard",
     cc_xyear: "cc_expirydate",
     cc_xmonth: "cc_validate",
     cc_fname: {
     required: function () {
     return isCCRequired()
     }
     },
     cc_lname: {
     required: function () {
     return isCCRequired()
     }
     },
     cc_cc: {
     required: function () {
     return isCCRequired()
     }
     },
     cc_addressid: {
     required: function () {
     return isCCRequired()
     }
     }
     },
     messages: {},
     submitHandler: function (form) {
     }
     });
     });

     if (ccmessage) {
     $('#cc_collapse').collapse('show');
     $("#orderinfo").validate().element("#cc_number");
     }
     }

     function isCCcomplete() {
     var text = "";
     if ($(".error:visible").length > 0) {
     text = "There are errors in your checkout form. Please recheck the fields in red";
     } else if (!$("#add_number").val().trim() || !$("#add_street").val().trim() || !$("#add_city").val().trim()) {
     text = "Address is incomplete";
     } else if ($("#add_cell").val().replace(/\D/g, "").length != 10) {
     text = "Phone number is missing or invalid";
     } else if (isCCRequired()) {
     if (!$("#user_cc_fname").val().trim() || !$("#user_cc_lname").val().trim() || !$("#user_cc_cc").val().trim() || $("#cc_addressid").val() == 0) {
     text = "Credit card info is incomplete";
     } else if (!isNumeric($("#user_cc_cc").val())) {
     text = "Credit card info is invalid";
     }
     }
     $("#form_integrity").text(text);
     return text.length == 0;
     }

     function isCCRequired() {
     var CCnumber = $("#cc_number").val();
     if (CCnumber.contains("-XXXX-XXXX-")) {
     return !$("#cc_number").hasAttr("allowblank");
     }
     return CCnumber.length > 0;
     }

     $.validator.addMethod('cc_validate', function (Data, element) {
     $("#orderinfo").validate().element("#cc_xyear");
     return true;
     });
     $.validator.addMethod('cc_expirydate', function (Data, element) {
     var currentdate = Number(<?= date("Yn"); ?>);
     var entereddate = Number(Data + "" + $("#cc_xmonth").val());
     return entereddate > currentdate;
     }, "Please enter a valid expiry date");
     */

</SCRIPT>