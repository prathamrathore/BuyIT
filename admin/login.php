<?php 
require_once  $_SERVER['DOCUMENT_ROOT'].'/ecom/core/init.php';
include 'includes/header.php';
$email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
$email = trim($email);
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$password = trim($password);
?>
<style>
body{
    padding:20px;
    background-image:url("/ecom/black.jpg");
    background-size:100vw 100vh;
    opacity:0.8;
}
</style>

<img src="/ecom/avtar.png" alt="avtar" class="avtar">
<div id="login-form">
    <div>
        <?php
            if($_POST){
            $errors = array();
            if(empty($_POST['email']) || empty($_POST['password'])){
                $errors[] = "You must provide email and password.";
            }
            $query = $db->query("SELECT * FROM users WHERE email = '$email'");
            $user = mysqli_fetch_assoc($query);
            $user_count = mysqli_num_rows($query); 
            if($user_count<1)
            {
                $errors[] = "The email does'nt exist in our database.";
            }
            if(!password_verify($password,$user['password'])){
                $errors[] = "The Password Does Not Match Our Records. Please Try Again.";
            }


            if(!filter_var($email,FILTER_VALIDATE_EMAIL))
            {
                $errors[] = "You must enter a validate email.";
            }
            if(strlen($password) < 6){
                $errors[] = "Password must be atleast of 6 characters.";
            }




            if(!empty($errors)){
                echo display_errors($errors);
            }
            else{
                $user_id = $user['id'];
                login($user_id);
            }
        }
        ?>


    </div>
    <h2 class="text-center">Login</h2><hr>
    <form action="login.php" method="post">
    <div class="form-group">
        <label for="email">EMAIL:</label>
        <input type="email" name="email" style="color:white;" class="form-control" id="email" value="<?=$email;?>" placeholder="Email">
    </div>
    
    <div class="form-group">
        <label for="password">PASSWORD:</label>
        <input type="password" name="password" style="color:white;" class="form-control" id="password" value="<?=$password;?>" placeholder="Password">
    </div>
    <div class="form-group">
        <input type="submit" value="Login" class="btn btn-primary">
    </div>
    </form>
    <p class="text-right"><a href="/ecom/website.php" alt="home">VISIT SITE</a></p>   
</div>






<?php  include 'includes/footer.php';?>