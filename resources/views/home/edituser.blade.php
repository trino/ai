<?php
    if(!isset($user_id) || !$user_id){$user_id = read("id");}
    if(isset($_POST["action"])){
        switch($_POST["action"]){
            case "testemail":
                $found = first("SELECT * FROM users WHERE id != " . $_POST["user_id"] . " AND email = '" . $_POST["email"] . "'");
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

                insertdb("users", $user);//save
                echo "Data saved";
                break;
        }
        die();
    }
?>
@extends('layouts.app')
@section('content')
    <STYLE>
        .row{
            margin-bottom: 2px;
        }
    </STYLE>
    <div class="row m-t-1">
        <div class="col-md-12">
            <div class="card">
                <div class="card-block bg-danger" style="padding-top:.75rem !important;padding-bottom:.75rem !important;">
                    <h4 class="pull-left">
                        <i class="fa fa-home" aria-hidden="true"></i> Edit user
                    </h4>
                    <A HREF="{{ webroot("public/list/useraddresses?user_id=" . $user_id ) }}" STYLE="float:right;" class="btn btn-sm btn-secondary">Edit Addresses</A>
                </div>
                <div class="card-block">
                    <div class="row">
                        <div class="col-md-12">
                            <FORM NAME="user" id="userform">
                                <?php
                                    $user = first("SELECT * FROM users WHERE id=" . $user_id);
                                    function printarow($Name, $field){
                                        if($field["type"] != "hidden"){echo '<DIV CLASS="row"><DIV CLASS="col-md-2">' . $Name . ':</DIV><DIV CLASS="col-md-10">';}
                                        echo '<INPUT TYPE="' . $field["type"] . '" NAME="' . $field["name"] . '" ID="user_' . $field["name"] . '"';
                                        if(isset($field["class"])){echo ' CLASS="' . $field["class"] . '" ';}
                                        if(isset($field["value"])){echo ' value="' . $field["value"] . '" ';}
                                        if(isset($field["readonly"])){echo ' readonly';}
                                        echo '>';
                                        if($field["type"] != "hidden"){echo '</DIV></DIV>';}
                                    }

                                    echo '<INPUT TYPE="HIDDEN" NAME="id" VALUE="' . $user_id  . '">';
                                    printarow("Your Name", array("name" => "name", "value" => $user["name"], "type" => "text", "class" => "form-control"));
                                    printarow("Cell Phone", array("name" => "phone", "value" => $user["phone"], "type" => "tel", "class" => "form-control"));
                                    printarow("Email Address", array("name" => "email", "value" => $user["email"], "type" => "email", "class" => "form-control"));
                                    printarow("Old Password", array("name" => "oldpassword", "type" => "password", "class" => "form-control"));
                                    printarow("New Password", array("name" => "newpassword", "type" => "password", "class" => "form-control"));
                                ?>
                                <DIV CLASS="row">
                                    <DIV CLASS="col-md-12" align="center">
                                        <BUTTON CLASS="btn btn-primary">Save</BUTTON>
                                    </DIV>
                                </DIV>
                            </FORM>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <SCRIPT SRC="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></SCRIPT>
    <SCRIPT>
        var currentURL = "<?= Request::url(); ?>";
        var minlength = 5;
        var token = "<?= csrf_token(); ?>";
        redirectonlogout = true;

        $.validator.addMethod('phonenumber', function (Data, element) {
            Data = Data.replace(/\D/g, "");
            if(Data.substr(0,1)=="0"){return false;}
            return Data.length == 10;
        }, "Please enter a valid phone number");

        // Wait for the DOM to be ready
        $(function() {
            // Initialize form validation on the registration form.
            // It has the name attribute "registration"
            $("form[name='user']").validate({
                // Specify validation rules
                rules: {
                    // The key name on the left side is the name attribute
                    // of an input field. Validation rules are defined
                    // on the right side
                    name: "required",
                    phone: "phonenumber",
                    email: {
                        required: true,
                        email: true,
                        remote: {
                            url: currentURL,
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
                // Specify validation error messages
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
                },
                submitHandler: function(form) {
                    $.post(currentURL, {
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
            });
        });
    </SCRIPT>
@endsection