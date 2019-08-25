<?php


 $db = mysqli_connect('localhost','root','','shinigami');

 if(mysqli_connect_errno()){
     echo 'Database connection failed with following errors '. mysqli_connect_error();
     die();
 }
 session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/ecom/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/ecom/helpers/helper.php';

$cart_id='';
if(isset($_COOKIE[CART_COOKIE])){
    $cart_id = sanitize($_COOKIE[CART_COOKIE]);

}

if(isset($_SESSION['pruser'])){

    $user_id =  $_SESSION['pruser'];
    $query = $db->query("SELECT * FROM users WHERE id = $user_id");
    $rs = mysqli_fetch_assoc($query);
    $result = explode(' ',$rs['first_name']);
    $frist = $result[0];
    $second = $result[1];

}




if(isset($_SESSION['sucess_flash'])){
    echo'<div class="bg-success"><p class="text-center text-success">'.$_SESSION['sucess_flash'].'</p></div>';
    unset($_SESSION['sucess_flash']); 
}

  

if(isset($_SESSION['error'])){
    echo'<div class="bg-danger"><p class="text-center text-danger">'.$_SESSION['error'].'</p></div>';
    unset($_SESSION['error']);
}










  