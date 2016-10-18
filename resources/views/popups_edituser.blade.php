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
            if ($field["type"] != "hidden")     {echo '<div class="form-group"><DIV CLASS="row"><DIV CLASS="col-md-12">';}
            echo '<INPUT  TYPE="' . $field["type"] . '" NAME="' . $field["name"] . '" ID="' . $Prepend . '_' . $field["name"] . '"';
            if (isset($field["class"]))         {echo ' CLASS="' . $field["class"] . '" ';}
            if (isset($field["value"]))         {echo ' value="' . $field["value"] . '" ';}
            if (isset($field["min"]))           {echo ' min="' . $field["min"] . '" ';}
            if (isset($field["maxlen"]))        {echo ' min="' . $field["maxlen"] . '" ';}
            if (isset($field["max"]))           {echo ' max="' . $field["max"] . '" ';}
            if (isset($field["readonly"]))      {echo ' readonly';}
            if (isset($field["placeholder"]))   {echo ' placeholder="' . $field["placeholder"] . '" ';}
            if (isset($field["corner"]))        {echo ' STYLE="border-' . $field["corner"] . '-radius: 5px;"';}
            echo '>';
            if ($field["type"] != "hidden")     {echo '</DIV></DIV></DIV>';}
        }
    }

    if(!isset($password)){$password = true;}
    if(!isset($email)){$email = true;}
?>


<div class="input-group-vertical">
    <?php
        printarow("Name", $name, array("name" => "name", "value" => $user["name"], "type" => "text", "class" => "form-control session_name_val"));
        if(!isset($phone) || $phone){
            printarow("Phone", $name, array("name" => "phone", "value" => $user["phone"], "type" => "tel", "class" => "form-control session_phone_val"));
        }
        if($email){
            printarow("Email", $name, array("name" => "email", "value" => $user["email"], "type" => "email", "class" => "form-control session_email_val"));
        }
        if(isset($user_id) || isset($showpass)){
            printarow("Old Password", $name, array("name" => "oldpassword", "type" => "password", "class" => "form-control", "placeholder"=>"Old Password"));
            printarow("New Password", $name, array("name" => "newpassword", "type" => "password", "class" => "form-control", "placeholder"=>"New Password"));
        } else if($password) {
            printarow("Password", $name, array("name" => "password", "type" => "password", "class" => "form-control"));
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
