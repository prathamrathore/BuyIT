<?php 
require_once  $_SERVER['DOCUMENT_ROOT'].'/ecom/core/init.php';
if(!is_logged_in()){
    login_error_redirect();
}

include 'includes/header.php';
$hashed = $rs['password'];

$old_password = ((isset($_POST['old_password']))?sanitize($_POST['old_password']):'');
$old_password = trim($old_password);

$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$password = trim($password);

$confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
$confirm = trim($confirm);
$new_hashed = password_hash($password,PASSWORD_DEFAULT);

$user_id = $rs['id'];

?>
<style>
body{
    padding:20px;
    background-size:cover;
    background-image:url("/ecom/black1.jpg");
    background-size:100vw 102vh;
    background-repeat:no-repeat;
    opacity:0.8;
}
</style>


<img src="/ecom/avtar.png" alt="avtar" class="avtar">
<div id="login-form">
    <div>
        <?php
            if($_POST){
            $errors = array();
            if(empty($_POST['old_password']) || empty($_POST['password'] || empty($_POST['confirm']))){
                $errors[] = "You must fill out all fields.";
            }
            if($password != $confirm){
                $errors[] = "The New Password and Confirm New Password does not match.";
            }
          
            if(!password_verify($old_password,$hashed)){
                $errors[] = "Your Old Password does not match our records.";
            }
            if(strlen($password) < 6){
                $errors[] = "Password must be atleast of 6 characters.";
            }




            if(!empty($errors)){
                echo display_errors($errors);
            }
            else{
                $db->query("UPDATE users SET password = '$new_hashed' WHERE id = '$user_id'");
                $_SESSION['sucess_flash'] = "Your Password has been updated.";
                header('Location: index.php');
            }
        }
        ?>


    </div>
    <h2 class="text-center">Change Password</h2><hr>
    <form action="change_password.php" method="post">
    <div class="form-group">
        <label for="Old Password">Old Password:</label>
        <input type="password" style="color:white;" name="old_password" class="form-control" id="password" value="<?=$Old_Password;?>">
    </div>
    
    <div class="form-group">
        <label for="password">New Password:</label>
        <input type="password" style="color:white;" name="password" class="form-control" id="password" value="<?=$password;?>">
    </div>
    <div class="form-group">
        <label for="confirm">confirm:</label>
        <input type="password" style="color:white;" name="confirm" class="form-control" id="password" value="<?=$confirm;?>">
    </div>
    <div class="form-group">
        <a href="index.php" class="btn btn-default">Cancel</a>
        <input type="submit" value="Change Password" class="btn btn-primary">
    </div>
    </form>
    <p class="text-right"><a href="/ecom/website.php" alt="home">VISIT SITE</a></p>   
</div>






<?php  include 'includes/footer.php';?>