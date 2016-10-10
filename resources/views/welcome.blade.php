<?php $GLOBALS["currentview"] = "welcome"; ?>
@extends('layouts.app')
@section('content')
    <div class="row">
        <DIV CLASS="col-md-6">
            <h1>London Pizza Delivery</h1>
            <p>
                Login
            </p>
            <div class="input-group-vertical">
                <div class="form-group">
                    <INPUT TYPE="text" id="login_email" placeholder="Email" class="form-control">
                </div>
                <div class="form-group">
                    <INPUT TYPE="password" id="login_password" placeholder="Password" class="form-control">
                </div>
            </div>
            <BUTTON CLASS="btn  btn-primary" onclick="handlelogin('login');">Log In</BUTTON>
            <BUTTON CLASS="btn btn-link" onclick="handlelogin('forgotpassword');">Forgot Password</BUTTON>
            <div class="p-y-1"></div>
            <p>Signup</p>
            <FORM Name="regform" id="regform">
                <?= view("popups.edituser"); ?>
                <button class="btn btn-primary">
                    Register
                </button>
            </FORM>
        </DIV>
    </div>
    <SCRIPT>
        redirectonlogin = true;
    </SCRIPT>
@endsection