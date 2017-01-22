<nav class="shadow dont-print bg-danger text-white " style="padding:1rem 0;z-index: 1;border-radius: 0 !important;">
    <div class="container-fluid">
        <a HREF="<?= webroot("public/index"); ?>" onclick="history.go(0);" class="text-white text-center" style="font-family: 'Pacifico', cursive;letter-spacing: .2rem !important;font-size: 1.25rem "
           href="/">
            LondonPizza.ca
        </a>
        <a style="font-size: 1.25rem " class="pull-right" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-bars text-white"></i>
        </a>

        <ul class="dropdown-menu dropdown-menu-right">
<SPAN class="loggedin profiletype profiletype1">
<?php
    foreach (array("users", "restaurants", "useraddresses", "orders", "additional_toppings") as $table) {
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
<li>
<A data-toggle="modal" data-target="#profilemodal" href="#" class="dropdown-item">
<i class="fa fa-user icon-width"></i> <SPAN CLASS="session_name"></SPAN>
</A>
</li>
<li class="profiletype_not profiletype_not2">
<A ONCLICK="orders();" class="dropdown-item" href="#"><i class="fa fa-clock-o icon-width"></i> Past Orders</A>
</li>
<LI>
<A ONCLICK="handlelogin('logout');" CLASS="dropdown-item" href="#"><i class="fa fa-home icon-width"></i> Log Out</A>
</LI>
</SPAN>
        </ul>
    </div>
</nav>