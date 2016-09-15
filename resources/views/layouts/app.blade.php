<?php $start_loading_time = microtime(true); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.3/css/bootstrap.min.css">
        <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Roboto+Slab' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="https://select2.github.io/select2/select2-3.5.2/select2.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.3/js/bootstrap.min.js"></script>
        <script src="//select2.github.io/select2/select2-3.4.2/select2.js"></script>
    </head>
    <STYLE>
        .header-cont {
            width:100%;
            position:fixed;
            top:0px;
            z-index: 100;
        }
        .footer-cont {
            width:100%;
            position:fixed;
            bottom:0px;
        }
        .header .footer {
            height:50px;
            background:#F0F0F0;
            border:1px solid #CCC;
            padding-top: 16px;
            margin:0px auto;
        }
        #loadingmodal {
            display: none;
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: rgba(255, 255, 255, .8) url('<?= webroot("resources/assets/images/slice.gif"); ?>') 50% 50% no-repeat;
        }

        body{
            margin: 60px 0px;
        }

        /* When the body has the loading class, we turn the scrollbar off with overflow:hidden */
        body.loading {
            overflow: hidden;
        }

        /* Anytime the body has the loading class, our modal element will be visible */
        body.loading #loadingmodal {
            display: block;
        }
    </STYLE>
    <DIV CLASS="header-cont">
        <DIV CLASS="header card-block bg-danger">
            London Pizza
            <SPAN STYLE="float:right;">
                @if(!read("user_id"))
                    <A data-toggle="modal" data-target="#loginmodal">Login</A>
                @endif
            </SPAN>
        </DIV>
    </DIV>
    <body>
        <div class="container p-a-0 m-t-1 bodycontainer">
            @yield('content')
        </div>
        <?= view("popups.login"); ?>
        <div class="modal" id="alertmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="form-group">
                            <h4 class="modal-title" id="alertmodallabel">Title</h4>
                        </div>
                        <DIV ID="alertmodalbody"></DIV>
                        <button class="btn btn-block btn-warning" data-dismiss="modal" STYLE="margin-top: 15px;">
                            Ok
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal loading" ID="loadingmodal"></div>
    </body>
    <SCRIPT>
        (function() {
            var proxied = window.alert;
            window.alert = function() {
                var title = "Alert";
                if(arguments.length > 1){title = arguments[1];}
                $("#alertmodalbody").text(arguments[0]);
                $("#alertmodallabel").text(title);
                $("#alertmodal").modal('show');
            };
        })();

        $(document).ready(function () {
            $body = $("body");
            $(document).on({
                ajaxStart: function () {$body.addClass("loading");},
                ajaxStop: function () {$body.removeClass("loading");}
            });
        });
    </SCRIPT>
    <DIV CLASS="footer-cont">
        <DIV CLASS="footer card-block bg-danger">
            <?php
                $end_loading_time = microtime(true);
                printf("Page generated in %f seconds. ", $end_loading_time - $start_loading_time);
            ?>
        </DIV>
    </DIV>
</html>