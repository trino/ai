<?php
    $currentURL = webroot("public/user/info");
    if (isset($user_id)) {
        $user = first("SELECT * FROM users WHERE id=" . $user_id);
        echo '<INPUT TYPE="HIDDEN" NAME="id" VALUE="' . $user_id . '">';
        if (!isset($name)) {
            $name = "user";
        }
    } else {
        $user = array("name" => "", "phone" => "", "email" => "");
        if (!isset($name)) {
            $name = "reg";
        }
    }
    if (!function_exists("printarow")) {
        function printarow($Name, $Prepend, $field) {
            if ($field["type"] != "hidden")     {echo '';}
            echo '<INPUT  TYPE="' . $field["type"] . '" NAME="' . $field["name"] . '" ID="' . $Prepend . '_' . $field["name"] . '"';
            if (isset($field["class"]))         {echo ' CLASS="' . $field["class"] . '" ';}
            if (isset($field["value"]))         {echo ' value="' . $field["value"] . '" ';}
            if (isset($field["min"]))           {echo ' min="' . $field["min"] . '" ';}
            if (isset($field["maxlen"]))        {echo ' min="' . $field["maxlen"] . '" ';}
            if (isset($field["max"]))           {echo ' max="' . $field["max"] . '" ';}
            if (isset($field["readonly"]))      {echo ' readonly';}
            if (isset($field["autocomplete"]) && $field["autocomplete"]) {echo ' autocomplete="' . $field["autocomplete"] . '"';}
            if (isset($field["placeholder"]))   {echo ' placeholder="' . $field["placeholder"] . '" ';}
            if (isset($field["corner"]))        {echo ' STYLE="border-' . $field["corner"] . '-radius: 5px;"';}
            if (isset($field["required"]) && $field["required"]) { echo ' REQUIRED';}
            echo '>';
            if ($field["type"] != "hidden")     {echo '';}
        }
    }
    if(!isset($password)){$password = true;}
    if(!isset($email)){$email = true;}
    if(!isset($autocomplete)){$autocomplete = "";}
?>
<div>
    <?php
        printarow("Name", $name, array("name" => "name", "value" => $user["name"], "type" => "text",  "placeholder"=>"Name","class" => "form-control session_name_val"));
        if(!isset($phone) || $phone){
            if(!isset($phone)){$phone = false;}
            printarow("Phone", $name, array("name" => "phone", "value" => $user["phone"], "type" => "tel", "placeholder"=>"Cell", "class" => "form-control session_phone_val", "required" => $phone));
        }
        if($email){
            printarow("Email", $name, array("name" => "email", "value" => $user["email"], "type" => "email", "placeholder"=>"Email", "class" => "form-control session_email_val"));
        }
        if(isset($user_id) || isset($showpass)){
            printarow("Old Password", $name, array("name" => "oldpassword", "type" => "password", "class" => "form-control", "placeholder"=>"Old Password", "autocomplete" => $autocomplete));
            printarow("New Password", $name, array("name" => "newpassword", "type" => "password", "class" => "form-control", "placeholder"=>"New Password", "autocomplete" => $autocomplete));
        } else if($password) {
            printarow("Password", $name, array("name" => "password", "type" => "password", "class" => "form-control", "placeholder"=>"Password", "autocomplete" => $autocomplete));
        }
        if(isset($address) && $address){
            echo view("popups_address", array("style" => 1))->render();
        }
    ?>
</div>
<SCRIPT>
    var minlength = 5;
    redirectonlogout = true;

    function userform_submit() {
        var formdata = getform("#userform");
        var keys = ["name", "email", "phone"];
        for (var keyid = 0; keyid < keys.length; keyid++) {
            var key = keys[keyid];
            var val = formdata[key];
            createCookieValue("session_" + key, val);
            $(".session_" + key).text(val);
            $(".session_" + key + "_val").val(val);
        }
        $.post("<?= $currentURL; ?>", {
            action: "saveitem",
            _token: token,
            value: formdata
        }, function (result) {
            if (result) {
                alert(result);
            }
        });
        return false;
    }

    @if(isset($user_id) && $user["cc_addressid"])
        $(document).ready(function () {
            setTimeout(function () {
                $("#saveaddresses").val(<?= $user["cc_addressid"]; ?>);
            }, 100);
        });
    @endif

    $(function () {
        $("form[name='user']").validate({
            rules: {
                name: "required",
                phone: {
                    phonenumber: true,
                    required: <?= $phone == "required" ? "true": "false"; ?>
                },
                cc_number: "creditcard",
                email: {
                    required: true,
                    email: true,
                    remote: {
                        url: "<?= $currentURL; ?>",
                        type: "post",
                        data: {
                            action: "testemail",
                            _token: token,
                            email: function () {
                                return $('#user_email').val();
                            },
                            user_id: userdetails["id"]
                        }
                    }
                },
                oldpassword: {
                    //required: function(element){return $("#user_newpassword").val()!="";},
                    minlength: minlength
                },
                newpassword: {
                    required: function (element) {
                        return $("#user_oldpassword").val() != "";
                    },
                    minlength: minlength
                }
            },
            messages: {
                name: "Please enter your name",
                phone: {
                    required: "Please provide an up-to-date phone number",
                    phonenumber: "Please provide a valid phone number"
                },
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

        $("#orderinfo").validate({
                rules: {
                    name: "required",
                    phone: {
                        phonenumber: true,
                        required: <?= $phone == "required" ? "true": "false"; ?>
                    }
                },
                messages: {
                    name: "Please enter your name",
                    phone: "Please enter a valid phone number"
                }
        });
    });

    $(document).ready(function () {
        setTimeout(function () {
            $("#orderinfo").removeAttr("novalidate");
        }, 100);
    });
</SCRIPT>
