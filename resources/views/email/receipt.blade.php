Here is a copy of your order
<?php
    $info = first("SELECT * FROM orders WHERE id = " . $orderid);
    var_dump($info);
    echo view("popups.receipt", array("orderid" => $orderid))->render();
?>