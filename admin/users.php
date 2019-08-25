<?php 
require_once '../core/init.php';
if(!is_logged_in()){
    login_error_redirect();
}
if(!is_permission('admin')){
    permisson_error_redirect('index.php');
}
include 'includes/header.php';
include 'includes/navbar.php';

$userquery = $db->query("SELECT * FROM users ORDER BY first_name");
if(isset($_GET['delete'])){
    $user_id = sanitize($_GET['delete']);
    $result = $db->query("DELETE FROM users WHERE id = $user_id");
    $_SESSION['sucess_flash'] = "User has been deleted.";
    header('Location:users.php');
}
if(isset($_GET['add'])){
$name = (isset($_POST['name'])?sanitize($_POST['name']):'');
$email = (isset($_POST['email'])?sanitize($_POST['email']):'');
$password = (isset($_POST['password'])?sanitize($_POST['password']):'');
$confirm = (isset($_POST['confirm'])?sanitize($_POST['confirm']):'');
$permissions = (isset($_POST['permission'])?sanitize($_POST['permission']):'');
if($_POST){



$errors = array();
$query = $db->query("SELECT * FROM users WHERE email = '$email'");
$count = mysqli_num_rows($query);
if($count != 0){
    $errors[] = "Email alerady exists in the database.";
}
$required = array('name','email','password','confirm','permission');
foreach($required as $f){
    if(empty($_POST[$f])){
        $errors[] = "You must fill out all the fields.";
        break;
    }
}
if(strlen($password)<6){
    $errors[] = "Password must be of atleast 6 characters.";
}
if($password != $confirm){
    $errors[] = "Your password and confirm password does not match.";
}
if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
    $errors[] = "You must enter a valid email.";
}


if(!empty($errors)){
    echo display_errors($errors);
}
else{
// insert user into the database
   $hashed = password_hash($password,PASSWORD_DEFAULT);
   $db->query("INSERT INTO users (first_name,email,password,permission) Values ('$name','$email','$hashed','$permissions')");
   $_SESSION['sucess_flash'] = "User has been added";
   header('Location: users.php');
}
}


?>
<h2 class="text-center">Add A New User</h2><hr>
<form action="users.php?add=1" method="post" style="padding:20px;">
<div class="form-group col-md-6">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" class="form-control" value="<?=$name;?>">
</div>
<div class="form-group col-md-6">
    <label for="email">Email:</label>
    <input type="email" id="email1" name="email" class="form-control" value="<?=$email;?>">
</div>
<div class="form-group col-md-6">
    <label for="password">Password:</label>
    <input type="password" id="password1" name="password" class="form-control" value="<?=$password;?>">
</div>
<div class="form-group col-md-6">
    <label for="confirm">confirm:</label>
    <input type="password" id="confirm1" name="confirm" class="form-control" value="<?=$confirm;?>">
</div>
<div class="form-group col-md-6">
    <label for="permission">Permission:</label>
    <select class="form-control" name="permission">
        <option value=""<?=(($permissions == '')?' selected':'');?>></option>
        <option value="editor"<?=(($permissions == 'editor')?' selected':'');?>>Editor</option>
        <option value="amin,editor"<?=(($permissions == 'admin,editor')?' selected':'');?>>Admin</option>
    </select>
</div>
<div class="form-group col-md-6 text-right" style="margin-top:25px;">
    <a href="users.php" class="btn btn-default">Cancel</a>
    <input type="submit" class="btn btn-primary" value="Add User">
</div>

</form>


<?php
}
else{
?>
<h2 class="text-center">Users</h2>
    <a href="users.php?add=1" class="btn btn-success pull-right" style="margin-top:-35px; margin-right:10px;">Add New User</a>
<hr>
<table class="table table-bordered table-condensed table-stripped">
    <thead>
        <th></th>
     
        <th>Name</th>
        <th>Email</th>
        <th>Join Date</th>
        <th>Last Login</th>
        <th>Permission</th>
    </thead>
    <tbody>
    <?php while($user = mysqli_fetch_assoc($userquery)): ?>
        <tr>
    
            <td>
            <?php if($user['id'] != $rs['id']):  ?>
            <a href="users.php?delete=<?=$user['id'];?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-remove-sign"></span></a>

            <?php endif;?>
       
            </td>
            <td><?=$user['first_name']?></td>
            <td><?=$user['email']?></td>
            <td><?=pretty_date($user['join_date']);?></td>
            <td><?=(($user['last_login'] == '0000-00-00 00:00:00')?'Never':pretty_date($user['last_login']))?></td>
            <td><?=$user['permission']?></td>
      
        </tr>
    <?php endwhile;?>
    </tbody>

</table>




<?php } include 'includes/footer.php';?>