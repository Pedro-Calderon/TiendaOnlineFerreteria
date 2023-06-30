<?php
session_start();
define("CLIENT_ID","AaxEzkBqy5E7wx182P1_y0qAkTHa2RYUZf2sB6chB5i7nFfGWTFOpP0x-2ADW6H6NbGQcphi1JlNWisD");
define("CURRENCY","MXN");
define("SITE_URL","https://tiendaferreteria2.000webhostapp.com/Ferreteria");
define("KEY_TOKEN","P.C07-20M*");
define("MONEDA","$");
$num_cart=0;
if(isset($_SESSION['carrito']['productos'])){
    $num_cart=count( $_SESSION['carrito']['productos']);
}
?>