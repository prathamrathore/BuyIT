<?php 
require_once  $_SERVER['DOCUMENT_ROOT'].'/ecom/core/init.php';
unset($_SESSION['pruser']);
header('Location: login.php');

?>