<!-- Start user form -->
<?php
    $currentURL = webroot("public/user/info");
    $includesection = Request::url() == $currentURL;
    $encryptedfields = array("fname" => "First Name", "lname" => "Last Name", "number" => "Card Number", "xyear" => "Year", "xmonth" => "Month", "cc" => "Security Number");

    if(!isset($user_id) || !$user_id){$user_id = read("id");}

    if(isset($_POST["action"])){
        switch($_POST["action"]){
            case "testemail":
                if(!isset($_POST["user_id"])){$_POST["user_id"]=0;}
                $found = false;
                if(isset($_POST["email"]) && $_POST["email"]){
                    $found = first("SELECT * FROM users WHERE id != " . $_POST["user_id"] . " AND email = '" . $_POST["email"] . "'");
                }
                if($found){echo "false";} else { echo "true"; }
                break;
            case "saveitem":
                $user = first("SELECT * FROM users WHERE id=" . $user_id);
                $_POST = $_POST["value"];

                //password check
                if($_POST["newpassword"]){
                    if ( ($_POST["oldpassword"] == $user["password"]) || Hash::check($_POST["oldpassword"], $user["password"])) {
                        $user["password"] = Hash::make($_POST["newpassword"]);
                    } else {
                        die("Password mismatch");
                    }
                }
                //email check
                $found = first("SELECT * FROM users WHERE id != " . $user_id . " AND email = '" . $_POST["email"] . "'");
                if(!$found){$user["email"] = $_POST["email"];}

                $user["name"] = $_POST["name"];
                $user["phone"] = $_POST["phone"];
                $user["updated_at"] = now();

                foreach($encryptedfields as $field => $name){
                    if(isset($_POST["cc_" . $field])){
                        $user["cc_" . $field] = encrypt($_POST["cc_" . $field]);
                    }
                }

                insertdb("users", $user);//save
                echo "Data saved";
                break;
        }
        die();
    }

    if(!function_exists("obfuscate")){
        //replaces the middle of a valid credit card number with $maskingCharacter (invalid cards show as such)
        function obfuscate($CardNumber, $maskingCharacter = "*") {
            if (!isvalid_creditcard($CardNumber)) {
                return "[INVALID CARD NUMBER]";
            }
            return substr($CardNumber, 0, 4) . str_repeat($maskingCharacter, strlen($CardNumber) - 8) . substr($CardNumber, -4);
        }

        //checks if a credit card is valid, returns what kind of card it is if it's valid, or $Invalid if it's not
        function isvalid_creditcard($CardNumber, $Invalid = "") {
            $CardNumber = preg_replace('/\D/', '', $CardNumber);
            // http://stackoverflow.com/questions/174730/what-is-the-best-way-to-validate-a-credit-card-in-php
            // https://en.wikipedia.org/wiki/Bank_card_number#Issuer_identification_number_.28IIN.29
            if ($CardNumber) {
                $length = 0;
                $mod10 = true;
                $Prefix = left($CardNumber, 2);
                if ($Prefix >= 51 && $Prefix <= 55) {
                    $length = 16; //mastercard
                    $type = "mastercard";
                } else if ($Prefix == 34 || $Prefix == 37) {
                    $length = 15; //amex
                    $type = "americanExpress";
                } else if (left($CardNumber, 1) == 4) {
                    $length = array(13, 16); //visa
                    $type = "visa";
                } else if ($Prefix == 65) {
                    $length = 16; //discover
                    $type = "discover";
                } else {
                    $Prefix = left($CardNumber, 6);
                    if ($Prefix >= 622126 || $Prefix <= 622925) {
                        $length = 16; //discover
                        $type = "discover";
                    } else {
                        $Prefix = left($CardNumber, 3);
                        if ($Prefix >= 644 || $Prefix <= 649 || left($CardNumber, 4) == 6011) {
                            $length = 16; //discover
                            $type = "discover";
                        }
                    }
                }
                if ($length) {
                    if (!is_array($length)) {
                        $length = array($length);
                    }
                    $Prefix = false;
                    foreach ($length as $digits) {
                        if (strlen($CardNumber) == $digits) {
                            $Prefix = true;
                        }
                    }
                    if ($Prefix) {
                        if ($mod10) {
                            if (luhn_check($CardNumber)) {
                                return $type;
                            }
                        }
                        return $type;
                    }
                }
            }
            return $Invalid;
        }

        //checks if a card is valid
        function luhn_check($number) {
            // Strip any non-digits (useful for credit card numbers with spaces and hyphens)
            $number = preg_replace('/\D/', '', $number);

            // Set the string length and parity
            $number_length = strlen($number);
            $parity = $number_length % 2;

            // Loop through each digit and do the maths
            $total = 0;
            for ($i = 0; $i < $number_length; $i++) {
                $digit = $number[$i];
                // Multiply alternate digits by two
                if ($i % 2 == $parity) {
                    $digit *= 2;
                    // If the sum is two digits, add them together (in effect)
                    if ($digit > 9) {
                        $digit -= 9;
                    }
                }
                // Total up the digits
                $total += $digit;
            }

            // If the total mod 10 equals 0, the number is valid
            return ($total % 10 == 0) ? TRUE : FALSE;
        }

        function startfield($Name = false){
            if($Name){
                echo '<DIV CLASS="row"><DIV CLASS="col-md-2">' . $Name . ':</DIV><DIV CLASS="col-md-10">';
            } else {
                echo '</DIV></DIV>';
            }
        }
    }
    $user = getuser(false, false);
?>
@if($includesection)
    @extends('layouts.app')
    @section('content')
        <div class="row m-t-1">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-block bg-danger" style="padding-top:.75rem !important;padding-bottom:.75rem !important;">
                        <h4 class="pull-left">
                            <A HREF="{{ webroot("public") }}"><i class="fa fa-home" aria-hidden="true"></i></A> Edit user
                        </h4>
                        <A HREF="{{ webroot("public/list/useraddresses?user_id=" . $user_id ) }}" STYLE="float:right;" class="btn btn-sm btn-secondary">Edit Addresses</A>
                    </div>
                    <div class="card-block">
                        <div class="row">
                            <div class="col-md-12">
@endif
    <STYLE>
        .row{
            margin-bottom: 2px;
        }
    </STYLE>
    <FORM NAME="user" id="userform">
        @include("popups.edituser")
        <HR>
        <H2>Credit Card info</H2><BR>
        <?php
            //"fname", "lname", "number", "xyear", "xmonth", "cc"
            foreach($encryptedfields as $field => $name){
                $user["cc_" . $field] = isencrypted($user["cc_" . $field]);
                switch($field){
                    case "xmonth":
                        startfield($name);
                        echo printoptions("cc_xmonth", array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"), $user["cc_xmonth"], array(1,2,3,4,5,6,7,8,9,10,11,12));
                        startfield();
                        break;
                    case "xyear":
                        startfield($name);
                        $currentyear=date("Y");
                        $years = array();
                        for($year=$currentyear; $year<$currentyear+5; $year++){
                            $years[] = $year;
                        }
                        echo printoptions("cc_xyear", $years, $user["cc_xyear"]);
                        startfield();
                        break;
                    case "cc":
                        printarow($name, "user", array("name" => "cc_cc", "value" => $user["cc_cc"], "type" => "number", "class" => "form-control", "min" => 0, "max" => 999));
                        break;
                    default://fname, lname, number
                        printarow($name, "user", array("name" => "cc_" . $field, "value" => $user["cc_" . $field], "type" => "text", "class" => "form-control"));
                }
            }

            startfield("Billing Address");
            echo '<div CLASS="addressdropdown"></div></div></div>';
        ?>
        <DIV CLASS="row">
            <DIV CLASS="col-md-12" align="center">
                <BUTTON CLASS="btn btn-primary" onclick="userform_submit();">Save</BUTTON>
            </DIV>
        </DIV>
    </FORM>

    <SCRIPT>
        var minlength = 5;
        redirectonlogout = true;

        function userform_submit(){
            $.post("<?= $currentURL; ?>", {
                action: "saveitem",
                _token: token,
                value: getform("#userform")
            }, function (result) {
                if(result) {
                    alert(result);
                }
            });
            return false;
        }

        @if($user["cc_addressid"])
            $(document).ready(function () {
                setTimeout( function () {
                    $("#saveaddresses").val(<?= $user["cc_addressid"]; ?>);
                }, 100 );
            });
        @endif

        $.validator.addMethod('creditcard', function (value, element) {
            var nCheck = 0, nDigit = 0, bEven = false, desiredlength = 16;
            value = value.replace(/\D/g, "");
            if (value.left(1) == 3){desiredlength=15;}//amex
            if (value.length != desiredlength){return false;}
            for (var n = value.length - 1; n >= 0; n--) {
                var cDigit = value.charAt(n), nDigit = parseInt(cDigit, 10);
                if (bEven) {if ((nDigit *= 2) > 9) nDigit -= 9;}
                nCheck += nDigit;
                bEven = !bEven;
            }
            return (nCheck % 10) == 0;
        }, "Please enter a valid credit card number");

        $(function() {
            $("form[name='user']").validate({
                rules: {
                    name: "required",
                    phone: "phonenumber",
                    cc_number: "creditcard",
                    email: {
                        required: true,
                        email: true,
                        remote: {
                            url: "<?= $currentURL; ?>",
                            type: "post",
                            data: {
                                action: "testemail",
                                email: function() {
                                    return $('#user_email').val();
                                },
                                user_id: "{{ $user_id }}"
                            }
                        }
                    },
                    oldpassword: {
                        //required: function(element){return $("#user_newpassword").val()!="";},
                        minlength: minlength
                    },
                    newpassword: {
                        required: function(element){
                            return $("#user_oldpassword").val()!="";
                        },
                        minlength: minlength
                    }
                },
                messages: {
                    name: "Please enter your name",
                    oldpassword: {
                        required: "Please provide your old password",
                        minlength: "Your old password is at least " + minlength + " characters long"
                    },
                    newpassword: {
                        required: "Please provide a new password",
                        minlength: "Your new password must be at least " + minlength + " characters long"
                    },
                    email: "Please enter a valid and unique email address"
                }
            });
        });
    </SCRIPT>
@if($includesection)
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
@endif
<!-- End user form -->