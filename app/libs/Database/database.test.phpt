<?php
require "Database.class.php";

$database = new Database("localhost", "admin", "admin", "thunder");
$database->set_debug(true);

$orders = $database->get(
    "o.id, o.customer_id, c.name, c.surname, SUM(cart.quantity * cart.price) AS total_price",
    "order o",
    array(
        "join" => array("customer c" => "o.customer_id=c.id", "cart" => "o.id=cart.order_id"),
        "where" => ["&" => ["o.customer_id" => "IS NOT NULL", ["|" => ["o.id" => "!=6", "o.customer_id" => 1]], "cart.order_id" => "IS NOT NULL"]],   // PHP 5.4+
        //"where" => ["&" => ["o.customer_id" => "IS NOT NULL", "o.id" => "!=6' OR 1='1"]],   // PHP 5.4+
        //"where" => "o.customer_id IS NOT NULL AND o.id!='6' OR 1='1'",   // old-fashioned definition
        "order" => array("total_price DESC"),
        "group" => "cart.order_id",
        "limit" => array(0, 15)
    ));

echo "<pre>";
print_r($orders);
echo "</pre>";
?>