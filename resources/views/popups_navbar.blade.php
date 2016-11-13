<nav class="navbar navbar-light bg-danger dont-print " style="z-index: 1;">
    <div class="container">

        <a class="navbar-brand text-white" href="#">
            <div class="pull-left sprite sprite-pizza sprite-medium"></div>
            London Pizza</a>
        <ul class="nav navbar-nav pull-right">
            <li class="nav-item dropdown">
                <a class=" dropdown-toggle text-white" href="http://example.com" id="responsiveNavbarDropdown"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i> </a>
                <div class="dropdown-menu" aria-labelledby="responsiveNavbarDropdown">


<SPAN class="loggedin profiletype profiletype1">
<?php
    //administration lists
    foreach (array("users", "restaurants", "useraddresses", "orders", "additional_toppings") as $table) {
        echo '<A HREF="' . webroot("public/list/" . $table) . '" CLASS="dropdown-item"><i class="fa fa-user-plus"></i> ' . ucfirst($table) . ' list</A>';
    }
    ?>

    <A HREF="<?= webroot("public/editmenu"); ?>" CLASS="dropdown-item"><i
                class="fa fa-user-plus"></i> Edit Menu</A>
<A HREF="<?= webroot("public/list/debug"); ?>" CLASS="dropdown-item"><i
            class="fa fa-user-plus"></i> Debug log</A>
</SPAN>

                    <SPAN class="loggedin">
<A data-toggle="modal" data-target="#profilemodal" href="#" class="dropdown-item">
<i class="fa fa-home"></i> <SPAN CLASS="session_name"></SPAN>
</A>
<A ONCLICK="orders();" class="dropdown-item" href="#"> <i class="fa fa-home"></i> Past Orders</A>
</SPAN>

                    <A ONCLICK="handlelogin('logout');" CLASS="dropdown-item" href="#">
                        <i class="fa fa-home"></i> Log out</A>


                </div>
            </li>

        </ul>

    </div>
</nav>