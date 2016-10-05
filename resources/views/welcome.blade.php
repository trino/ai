<?php $GLOBALS["currentview"] = "welcome"; ?>
@extends('layouts.app')
@section('content')

    <STYLE>
        .bodycontainer{
            margin-top: 0px !important
        }
        .header{
            vertical-align: middle;
            line-height: 60px;
            height: 60px;
            background-color: #d9534f;
            text-align: right;
        }
        .header input, .header .btn{
            height: 30px;
            margin-right: 15px;
        }
        .header .pull-left{
            margin-left: 15px;
        }
    </STYLE>
    <DIV class="header">
        <SPAN CLASS="pull-left">London Pizza</SPAN>
        <INPUT TYPE="text" id="login_email" placeholder="Email address">
        <INPUT TYPE="password" id="login_password" placeholder="password">
        <BUTTON CLASS="btn btn-sm btn-primary" onclick="handlelogin('login');">Log In</BUTTON>
        <BUTTON CLASS="btn btn-sm btn-secondary" onclick="handlelogin('forgotpassword');">Forgot Password</BUTTON>
    </DIV>
    <DIV CLASS="col-md-6">
        Place holder
    </DIV>
    <DIV CLASS="col-md-6">
        <FORM Name="regform" id="regform">
            <?= view("popups.edituser"); ?>
            <DIV STYLE="margin-top: 15px;">
                <DIV CLASS="col-md-4">
                    <button class="btn btn-block btn-primary">
                        Register
                    </button>
                </DIV>
            </DIV>
        </FORM>
    </DIV>
    <SCRIPT>
        redirectonlogin = true;
    </SCRIPT>
@endsection