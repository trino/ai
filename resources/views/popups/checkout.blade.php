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

                <FORM ID="orderinfo" name="orderinfo">
                    <div class="input-group">
                        <span class="input-group-btn" style="width: 50% !important;">
                            <input type="text" name="cell" class="form-control" placeholder="Cell"/>
                        </span>
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

                    <DIV ID="cc_integrity" style="color:red;"></DIV>
                    <input type="text" class="form-control" name="cc_number" id="cc_number" placeholder="Credit Card" onclick="$('#cc_collapse').collapse('show');"/>
                    <div class="collapse list-group out" id="cc_collapse" title="For security purposes, to change ANY part of the credit info you must re-enter all of it again">
                        <?php
                            if(!function_exists("printarow")){
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
                            }

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
                    </div>

                    <input type="text" class="form-control" placeholder="Notes"/>

                    <div class="clear_loggedout addressdropdown" id="checkoutaddress"></div>
                    <?= view("popups.address", array("dontincludeAPI" => true, "style" => 1)); ?>

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
    // data-toggle="modal" data-target="#checkoutmodal"  href="#collapseCheckout"
    function showcheckout(){
        var ccmessage = "";
        if(userdetails["cc_integrity"].length > 0){
            switch(userdetails["cc_integrity"]) {
                case "missing": ccmessage = "Missing data"; break;
                case "expired": ccmessage = "Card is expired"; break;
                case "invalid": ccmessage = "Card is invalid"; break;
            }
        }
        $("#cc_number").val(userdetails["cc_number"]);
        $("#cc_integrity").text(ccmessage);
        var HTML = $("#checkoutaddress").html().replace('id="saveaddresses"', 'name="cc_addressid"').replace("onchange", 'offchange').replace('Select a saved address', 'Billing Address');
        $("#billingaddress").html(HTML);
        $("#checkoutmodal").modal("show");
    }

</SCRIPT>