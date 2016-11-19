@extends('layouts_app')
@section('content')

<?php
if(!read("id")){
echo view("popups_login")->render();
} else {
?>

<div class="row">
<div class="col-md-8">
<?php
//phpinfo();
if (islive()) {
$allowedIPs = array("24.36.153.107", "45.58.85.42", "38.121.83.92", "216.165.195.31", "24.36.134.113", "184.151.178.135");
if (!in_array($_SERVER["REMOTE_ADDR"], $allowedIPs)) {
die("IP " . $_SERVER["REMOTE_ADDR"] . " not recognized");
}
}

//menu caching
$doCache = false;//disabled for development
$menucache_filename = resource_path() . "/menucache.html";
$menucache_uptodate = isFileUpToDate("menucache", $menucache_filename);
if ($menucache_uptodate && $doCache) {
echo file_get_contents($menucache_filename);
} else {
$menu = view("popups_menu");
if ($doCache) {
file_put_contents($menucache_filename, $menu);
setsetting("menucache", filemtime($menucache_filename));
}
echo '<!-- menu cache generated at: ' . now() . ' --> ' . $menu;
}
?>
</div>
<div class="col-md-4">


<div class="card text-white bg-danger " style="margin: 0 !important;border-radius: 0 !important;">
<div class="card-block ">
<div class="row">
<div class="col-md-12">
<h5 class="pull-left text-white">
My Order
</h5>
<div class="clearfix"></div>

<div id="myorder"></div>

</div>

</div>
<div ID="checkoutbutton">


</div>


<div class="row  my-2">
<div class="col-md-12">

<div class="clearfix"></div>

<button class="btn btn-warning pull-left " id="checkout-btn" onclick="showcheckout();"
style="display: none;">
CHECKOUT
</button>

<button class="btn pull-left btn-link text-white "
ONCLICK="confirm2('Are you sure you want to clear your order?', 'Clear Order', function(){clearorder();});">
CLEAR
</button>


</div>
</div>
</div>
</div>


@include("popups_checkout")

</div>
</div>
@include("popups_editprofile_modal")

<?php } ?>
@endsection