<HTML>
    <?php
        //hack to put CSS inline for emails
        echo 'Thank you for your order!<br>';
        $HTML = view("popups_receipt", array("orderid" => $orderid, "inline" => true))->render();
        $Styles = array(
            "TD" => "border: 1px solid #eceeef; padding: .3rem; display: table-cell;",
            "TH" => "border-color: #55595c; border-bottom: 2px solid #eceeef; padding: .3rem; display: table-cell; text-align:left; border-right: 2px solid #eceeef;",
            "th" => "border-color: #55595c; border-bottom: 2px solid #eceeef; padding: .3rem; display: table-cell; text-align:left;"//hack for last TH in a TR
        );
        foreach($Styles as $Tag => $Style){
            $HTML = str_replace('<' . $Tag, '<' . $Tag . ' STYLE="' . $Style . '"', $HTML);
        }
        echo $HTML;
    ?>
    <div class="text-xs-center bg-danger text-white">
        <br><br> CHECK US OUT ON SOCIAL MEDIA
        <br><br> FOOD DRIVE PROGRAM
        <br><br> EMAIL US
        <br><br> SEARCH GOOGLE
        <br><br>
    </div>
</HTML>
