<nav class="navbar-default navbar-top navbar navbar-full navbar-dark bg-danger dont-print" style="z-index: 1;
padding:.75rem !important;">
    <span class="nav-link navbar-brand" style="font-weight:600;line-height: 1.5 !important;color:white !important;">
        <a HREF="<?= webroot("public/index"); ?>" class=" pull-left" style="color:white;cursor:pointer;">
        <i class="fa fa-home" style="width: 25px;" aria-hidden="true"></i> London Pizza</a>
    </span>
    @if(read("id"))
        <div class="pull-right">
            <ul class="nav navbar-nav pull-lg-right">
                <li class="nav-item dropdown">
                    <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="fa fa-user no-padding-margin"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <h6 class="dropdown-header session_name" style="margin-top: .25rem;"></h6>
                        <div class="dropdown-divider"></div>
                        <SPAN class="loggedin profiletype profiletype1">
                            <?php
                                //administration lists
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
                                    <i class="fa fa-home"></i> <SPAN CLASS="session_name"></SPAN>
                                </A>
                            </li>
                            <li>
                                <A ONCLICK="orders();" class="dropdown-item" href="#"> <i class="fa fa-home"></i> Recent Orders</A>
                            </li>
                            <li>
                                <A ONCLICK="handlelogin('logout');" CLASS="dropdown-item" href="#"><i class="fa fa-home"></i> Log out</A>
                            </li>
                        </SPAN>
                    </ul>
                </li>
            </ul>
        </div>
    @endif
</nav>