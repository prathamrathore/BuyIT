<?php 
require_once '../core/init.php';
if(!is_logged_in()){
    header('Location: index.php');
}

include 'includes/header.php';
include 'includes/navbar.php';


?>

<h2 class="text-center">Hello <?=$rs['first_name'];?></h2>



<?php include 'includes/footer.php';?>