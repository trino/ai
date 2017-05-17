<HTML>
    <?php
        if(isset($layout)){
            switch($layout){
                case "verify":
                    echo 'Hi ' . $name . '. Thank you for registering at ' . sitename;
                    echo '<BR>Your password is ' . $password;
                    if($requiresauth){
                        echo '<A HREF="' . webroot('public/auth/login') . '?action=verify&code=' . $authcode . '">Click here to verify your email</A>';
                    }
                    break;
                case "forgotpassword":
                    echo 'Your new password is ' . $password;
                    break;
                case "receipt":
                    //hack to put CSS inline for emails
                    echo 'Thank you for your order.<br>';
                    $HTML = view("popups_receipt", array("orderid" => $orderid, "inline" => true, "place" => "email", "style" => 2, "includeextradata" => true, "party" => $party))->render();

                    $Styles = array(
                            "TD" => "border: 1px solid #eceeef; padding: .3rem; display: table-cell;",
                            "TH" => "border-color: #55595c; border-bottom: 2px solid #eceeef; padding: .3rem; display: table-cell; border-right: 2px solid #eceeef;",
                            "th" => "border-color: #55595c; border-bottom: 2px solid #eceeef; padding: .3rem; display: table-cell;"//hack for last TH in a TR
                    );
                    foreach($Styles as $Tag => $Style){
                        $HTML = str_replace('<' . $Tag, '<' . $Tag . ' STYLE="' . $Style . '"', $HTML);
                    }
                    echo $HTML;
                    break;
            }
        }
        if(isset($body)){
            echo $body;
        }
    ?>
    <HR>
    Thank you,
    The <?= sitename; ?> Team
</HTML>