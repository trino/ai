<?php $GLOBALS["currentview"] = "welcome"; ?>
@extends('layouts.app')
@section('content')

    <STYLE>
        .bodycontainer{
            margin-top: 0px !important;
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

        .dropdown-toggle{
            display: none;
        }

        #page {
            display:block;
            width:100%;
            height:95vh !important;
            overflow:hidden;
        }
        #content {
            float:left;
            width:100%;
            height:100%;
            display:block;
            overflow:hidden;

            background-color: red;
        }
        #content div {
            height:100%;
        }

        .v-wrap{
            height: 100%;
            white-space: nowrap;
            text-align: center;
        }
        .v-wrap:before{
            content: "";
            display: inline-block;
            vertical-align: middle;
            height: 100%;
        }
        .v-box{
            display: inline-block;
            vertical-align: middle;
            white-space: normal;
            width:95%;
        }
    </STYLE>
    <DIV class="header">
        <SPAN CLASS="pull-left">London Pizza</SPAN>
        <INPUT TYPE="text" id="login_email" placeholder="Email address">
        <INPUT TYPE="password" id="login_password" placeholder="password">
        <BUTTON CLASS="btn btn-sm btn-primary" onclick="handlelogin('login');">Log In</BUTTON>
        <BUTTON CLASS="btn btn-sm btn-secondary" onclick="handlelogin('forgotpassword');">Forgot Password</BUTTON>
    </DIV>

    <div id="page">
        <div id="content">
            <DIV CLASS="col-md-6">
                <div class="v-wrap">
                    <article class="v-box">
                        Place holder
                    </article>
                </div>
            </DIV>
            <DIV CLASS="col-md-6">
                <div class="v-wrap">
                    <article class="v-box">
                        <FORM Name="regform" id="regform">234234324234
                            <?= view("popups.edituser"); ?>
                            <DIV STYLE="margin-top: 15px;">
                                <DIV CLASS="col-md-4">
                                    <button class="btn btn-block btn-primary">
                                        Register
                                    </button>
                                </DIV>
                            </DIV>
                        </FORM>
                    </article>
                </div>
            </DIV>
        </div>
    </div>

    <SCRIPT>
        redirectonlogin = true;
    </SCRIPT>
@endsection