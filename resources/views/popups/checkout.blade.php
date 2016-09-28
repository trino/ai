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
                            <SELECT class="form-control" TITLE="Delivery Time"/>
                            <?php
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
                            ?>
                            </SELECT>
                        </span>
                    </div>

                    <input type="text" class="form-control" placeholder="Restaurant"/>
                    <input type="text" class="form-control" placeholder="Credit Card"/>
                    <input type="text" class="form-control" placeholder="Notes"/>

                    <div class="clear_loggedout addressdropdown"></div>
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