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
                <?= view("popups.edituser"); ?>

                <FORM ID="orderinfo" name="orderinfo">
                    <div class="input-group">

                        <span class="input-group-btn" style="width: 50% !important;">
                            <?php
                                echo '<SELECT class="form-control" TITLE="Delivery Time"/>';
                                    //<div class="collapse" id="collapseCheckout"></div>
                                    function rounduptoseconds($time, $seconds){
                                        $r = $time % $seconds;
                                        return $time + ($seconds-$r);
                                    }
                                    $mindeliverytime=30*60;//30 minutes
                                    $now = rounduptoseconds(time()+$mindeliverytime, 900);
                                    echo '<OPTION>ASAP</OPTION>';
                                    for($i = 0; $i < 10; $i++){//what is the end time?
                                        echo '<OPTION VALUE="' . $now . '">' . date('g:ia', $now) . '</OPTION>';
                                        $now+=15*60;
                                    }
                                echo '</SELECT>';
                            ?>
                        </span>
                    </div>


                    <input type="text" class="form-control" placeholder="Restaurant"/>

                    <!--DIV ID="cc_integrity" style="color:red;"></DIV>
                    <input type="text" class="form-control" name="cc_number" id="cc_number" placeholder="Credit Card" onclick="$('#cc_collapse').collapse('show');"/>
                    <div class="collapse list-group out" id="cc_collapse" title="For security purposes, to change ANY part of the credit info you must re-enter all of it again">
                        <?php
                           // if(!function_exists("printarow")){
                                function printarow2($Name, $Prepend, $field){
                                    echo '<INPUT TYPE="' . $field["type"] . '" NAME="' . $field["name"] . '" ID="' . $Prepend . '_' . $field["name"] . '" PLACEHOLDER="' . $Name . '"';
                                    if(isset($field["class"])){echo ' CLASS="' . $field["class"] . '" ';}
                                    if(isset($field["value"])){echo ' value="' . $field["value"] . '" ';}
                                    if(isset($field["min"])){echo ' min="' . $field["min"] . '" ';}
                                    if(isset($field["maxlen"])){echo ' min="' . $field["maxlen"] . '" ';}
                                    if(isset($field["max"])){echo ' max="' . $field["max"] . '" ';}
                                    if(isset($field["readonly"])){echo ' readonly';}
                                    echo '>';
                                }
                          //  }

                            echo '<DIV CLASS="col-md-6">';
                            printarow2("First Name", "user", array("name" => "cc_fname", "value" => "", "type" => "text", "class" => "form-control"));
                            echo '</DIV><DIV CLASS="col-md-6">';
                            printarow2("Last Name", "user", array("name" => "cc_lname", "value" => "", "type" => "text", "class" => "form-control"));
                            echo '</DIV><DIV CLASS="col-md-4">';
                            echo printoptions("cc_xmonth", array("Expiry Month", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"), "0", array(0,1,2,3,4,5,6,7,8,9,10,11,12));

                            $currentyear=date("Y");
                            $years = array("Expiry Year");
                            $values = array("");
                            for($year=$currentyear; $year<$currentyear+5; $year++){
                                $years[] = $year;
                                $values[] = $year;
                            }
                            echo '</DIV><DIV CLASS="col-md-4">';
                            echo printoptions("cc_xyear", $years, "", $values);
                            echo '</DIV><DIV CLASS="col-md-4">';
                            printarow2("Security Number", "user", array("name" => "cc_cc", "value" => "", "type" => "number", "class" => "form-control", "min" => 0, "max" => 99999));
                            echo '</DIV><DIV CLASS="col-md-12" ID="billingaddress"></DIV>';
                        ?>
                    </div-->

                    <input type="text" class="form-control" placeholder="Deliver Notes" title="ie: Side door (These will not be seen by the chef)"/>

                    <div class="clear_loggedout addressdropdown" id="checkoutaddress"></div>
                    <?= view("popups.address", array("dontincludeAPI" => true, "style" => 1, "saveaddress" => true)); ?>

                    <DIV CLASS="col-md-12">
                        <DIV ID="form_integrity" style="color:red;"></DIV>
                    </DIV>
                    <DIV align="center">
                        <button class="btn btn-warning btn-block m-t-1" onclick="placeorder();" style="width: 50% !important;">PLACE ORDER</button>
                    </DIV>
                    <P>
                </FORM>
            </div>
        </div>
    </div>
</div>
<SCRIPT>
    function showcheckout(){
        var ccmessage = "";
        $("#cc_number").attr("allowblank", true);
        if(userdetails["cc_integrity"].length > 0){
            ccmessage = 'Your credit card is ' + userdetails["cc_integrity"] + ". Please enter a new credit card.";
            $("#cc_number").removeAttr("allowblank");
        }
        $("#cc_number").val(userdetails["cc_number"]);
        $("#cc_integrity").text(ccmessage);
        var HTML = $("#checkoutaddress").html().replace('id="saveaddresses"', 'name="cc_addressid" ID="cc_addressid" ').replace("onchange", 'offchange').replace('Select a saved address', 'Billing Address');
        $("#billingaddress").html(HTML);
        $("#checkoutmodal").modal("show");

        clearValidation("#orderinfo");
        $(function() {
            $("#orderinfo").validate({
                rules: {
                    cell: {required: true, phonenumber: true},
                    cc_number: "creditcard",
                    cc_xyear: "cc_expirydate",
                    cc_xmonth: "cc_validate",
                    cc_fname: {required: function(){return isCCRequired()}},
                    cc_lname: {required: function(){return isCCRequired()}},
                    cc_cc: {required: function(){return isCCRequired()}},
                    cc_addressid: {required: function(){return isCCRequired()}}
                },
                messages: {

                },
                submitHandler: function(form) {
                    //handled by placeorder
                }
            });
        });

        if(ccmessage){
            $('#cc_collapse').collapse('show');
            $("#orderinfo").validate().element("#cc_number");
        }
    }

    function isCCcomplete(){
        var text = "";
        if($(".error:visible").length>0){
            text = "There are errors in your checkout form. Please recheck the fields in red";
        } else if(!$("#add_number").val().trim() || !$("#add_street").val().trim() || !$("#add_city").val().trim()) {
            text = "Address is incomplete";
        } else if($("#add_cell").val().replace(/\D/g, "").length != 10){
            text = "Phone number is missing or invalid";
        } else if(isCCRequired()){
            if(!$("#user_cc_fname").val().trim() || !$("#user_cc_lname").val().trim() || !$("#user_cc_cc").val().trim() || $("#cc_addressid").val() == 0){
                text = "Credit card info is incomplete";
            } else if(!isNumeric($("#user_cc_cc").val())){
                text = "Credit card info is invalid";
            }
        }
        $("#form_integrity").text(text);
        return text.length==0;
    }

    function isCCRequired(){
        var CCnumber = $("#cc_number").val();
        if(CCnumber.contains("-XXXX-XXXX-")){return !$("#cc_number").hasAttr("allowblank");}
        return CCnumber.length > 0;
    }

    //validate month and year together
    $.validator.addMethod('cc_validate', function (Data, element) {
        $("#orderinfo").validate().element("#cc_xyear");
        return true;
    });
    $.validator.addMethod('cc_expirydate', function (Data, element) {
        var currentdate = Number(<?= date("Yn"); ?>);
        var entereddate = Number(Data + "" + $("#cc_xmonth").val());
        return entereddate > currentdate;
    }, "Please enter a valid expiry date");
</SCRIPT>