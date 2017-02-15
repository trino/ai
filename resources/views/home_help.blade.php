@extends('layouts_app')
@section('content')
    <STYLE>
        li>.title{
            font-weight: bold;
        }
        .collapse, .collapsing{
            margin-left: 30px;
        }
    </STYLE>
    <DIV class="card card-block">
        <?php
            function newID(){
                if(isset($GLOBALS["lastid"])){
                    $GLOBALS["lastid"] += 1;
                } else {
                    $GLOBALS["lastid"] = 0;
                }
                return lastID();
            }
            function lastID(){
                return "section_" . $GLOBALS["lastid"];
            }
            function newlist($Title){
                if(isset($GLOBALS["startlist"])){echo '</UL>';}
                $GLOBALS["startlist"] = true;
                echo '<H2>' . $Title . '</H2><UL>';
            }
            function newitem($Title, $Text, $Class = ""){
                echo '<LI data-toggle="collapse" data-target="#' . newID() . '"><SPAN CLASS="title cursor-pointer ' . $Class . '">' . $Title . '</SPAN><div id="' . lastID() . '" class="collapse">' . $Text . '</div></LI>';
            }
            newlist("Users");
            newitem("Signing in", "figffdfgi");
            newitem("Forgot password    ", "figffdfgi");
            newitem("Registering", "figffdfgi");

            if(read("profiletype") > 0){
                newlist("Restaurants");
                newitem("Registering", "figffdfgi");
                newitem("View", "figffdfgi", "btn btn-sm btn-success");
                newitem("Delete", "figffdfgi", "btn btn-sm btn-danger");
                newitem("Confirm", "figffdfgi", "btn btn-sm btn-primary");
                newitem('<i class="fa fa-envelope"></i> Email', "figffdfgi", "btn btn-sm btn-secondary");
                newitem("Decline", "figffdfgi", "btn btn-sm btn-danger");
            }

            echo '</UL>';//end last list
        ?>
    </DIV>
@endsection

