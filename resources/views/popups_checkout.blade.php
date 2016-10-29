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
                        ?>

                        <input type="text" class="form-control corner-top" ID="restaurant" readonly
                               placeholder="Restaurant Select" TITLE="Closest restaurant"/>


                        <?php
                        echo '<SELECT id="deliverytime" TITLE="Delivery Time" class="form-control"/>';
                        function rounduptoseconds($time, $seconds)
                        {
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
                        <input type="text" id="cookingnotes" class="form-control"
                               placeholder="Notes for the Cook" maxlength="255"/>

                    <button class="m-b-1 btn btn-warning btn-block" onclick="placeorder();">PLACE ORDER</button>
                    <DIV ID="form_integrity" style="color:red;"></DIV>
                </FORM>
                <div class="clearfix"></div>

            </div>
        </div>
    </div>
</div>


<SCRIPT>
    var canplaceorder = false;

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

</SCRIPT>