<nav class="navbar dont-print text-white container" style="padding:1rem 0;z-index: 1;border-radius: 0 !important;">
    <a HREF="<?= webroot("public/index"); ?>" onclick="history.go(0);" class="pull-left text-white" style="font-weight:600;letter-spacing: .03rem !important; width: 100px;" href="#">
        <i class="fa fa-car icon-width" aria-hidden="true"></i> Menu
    </a>
    <DIV class="pull-right" style="position: absolute; top: 9px; right: 12px;">
        <ul class="nav navbar-nav pull-lg-right pull-right">
            <li class="nav-item dropdown">
                <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-bars no-padding-margin text-white"></i> </a>
                <ul class="dropdown-menu dropdown-menu-right">
                    <SPAN class="loggedin profiletype profiletype1">
                        <?php
                            foreach (array("users", "restaurants", "useraddresses", "orders", "additional_toppings") as $table) {
                                echo '  <li><A HREF="' . webroot("public/list/" . $table) . '" CLASS="dropdown-item"><i class="fa fa-user-plus"></i> ' . ucfirst($table) . ' list</A></li>';
                            }
                        ?>
                        <li>
                            <A HREF="<?= webroot("public/editmenu"); ?>" CLASS="dropdown-item"><i class="fa fa-user-plus"></i> Edit Menu</A>
                        </li>
                        <li>
                            <A HREF="<?= webroot("public/list/debug"); ?>" CLASS="dropdown-item"><i class="fa fa-user-plus"></i> Debug log</A>
                        </li>
                    </SPAN>
                    <SPAN class="loggedin">
                        <li>
                            <A data-toggle="modal" data-target="#profilemodal" href="#" class="dropdown-item">
                                <i class="fa fa-user"></i> <SPAN CLASS="session_name"></SPAN>
                            </A>
                        </li>
                        <li class="profiletype_not profiletype_not2">
                            <A ONCLICK="orders();" class="dropdown-item" href="#"><i class="fa fa-clock-o"></i> Recent Orders</A>
                        </li>
                        <LI>
                            <A ONCLICK="handlelogin('logout');" CLASS="dropdown-item" href="#"><i class="fa fa-home"></i> Log Out</A>
                        </LI>
                    </SPAN>
                </ul>
            </li>
        </ul>
    </DIV>
</nav>