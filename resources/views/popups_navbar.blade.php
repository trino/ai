<div class="container-fluid bg-danger list-group-item text-white">


    <button  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background: transparent" class="btn btn-sm pull-right ">
        <i class="fa fa-bars text-white"></i>


    </button>

            <ul class="dropdown-menu dropdown-menu-left">
                @if(read("id"))
                    <SPAN class="loggedin profiletype profiletype1">
                        <?php
                            foreach (array("users", "restaurants", "useraddresses", "orders", "additional_toppings", "actions") as $table) {
                                echo '  <li><A HREF="' . webroot("public/list/" . $table) . '" CLASS="dropdown-item"><i class="fa fa-user-plus"></i> ' . ucfirst($table) . ' list</A></li>';
                            }
                        ?>
                        <li>
                            <A HREF="<?= webroot("public/editmenu"); ?>" CLASS="dropdown-item"><i class="fa fa-user-plus icon-width"></i> Edit Menu</A>
                        </li>
                        <li>
                            <A HREF="<?= webroot("public/list/debug"); ?>" CLASS="dropdown-item"><i class="fa fa-user-plus icon-width"></i> Debug log</A>
                        </li>
                    </SPAN>
                    <SPAN class="loggedin">
                        <li id="profileinfo">
                            <A data-toggle="modal" data-target="#profilemodal" href="#" class="dropdown-item">
                                <i class="fa fa-user icon-width"></i> <SPAN CLASS="session_name"></SPAN>
                            </A>
                        </li>
                        <!--li class="profiletype_not profiletype_not2">
                            <A ONCLICK="orders();" class="dropdown-item" href="#"><i class="fa fa-clock-o icon-width"></i> Past Orders</A>
                        </li-->
                        <LI>
                            <A ONCLICK="handlelogin('logout');" CLASS="dropdown-item" href="#"><i class="fa fa-home icon-width"></i> Log Out</A>
                        </LI>
                    </SPAN>
                @endif
                <SPAN class="loggedout">
                    <LI>
                        <A CLASS="dropdown-item" href="<?= webroot(""); ?>"><i class="fa fa-user icon-width"></i> Log In</A>
                    </LI>
                </SPAN>
                <LI>
                    <A CLASS="dropdown-item" href="help"><i class="fa fa-question-circle icon-width"></i> More Info</A>
                </LI>
            </ul>


            <a HREF="<?= webroot("public/index"); ?>" onclick="history.go(0);" class="ml-2 align-middle text-white pull-left londonpizza" href="/">
                londonpizza.ca
            </a>

            <span class="align-middle rounded sprite sprite-wings sprite-medium " style="visibility: hidden"></span>


</div>