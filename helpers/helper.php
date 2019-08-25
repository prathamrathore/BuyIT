<?php 
function display_errors($errors){
$display = '<ul class="bg-danger">';
foreach($errors as $error){
    $display .= '<li class="text-danger">'.$error.'</li>';
}
$display .='</ul>';
return $display;
}

function sanitize($dirty){
    return htmlentities($dirty,ENT_QUOTES,'UTF-8');
   
   }
   
function money($number){

    return '&#x20b9;'.number_format($number,2);
}
function login($user_id){

    $_SESSION['pruser'] = $user_id;
    global $db;
    $date = date("Y-m-d H:i:s");
    $db->query("UPDATE users SET last_login = '$date' WHERE id = '$user_id'");
    $_SESSION['sucess_flash'] = "you are now logged in.";
    header('Location: index.php');   
}
function is_logged_in(){
if(isset( $_SESSION['pruser']) &&  $_SESSION['pruser'] > 0){
    return true;

}
return false;

}
function login_error_redirect($url = 'login.php'){
    $_SESSION['error'] = "You must be logged in to access that page.";
    header('Location: '.$url);
}

function permisson_error_redirect($url = 'login.php'){
    $_SESSION['error'] = "You are not permitted to access that page.";
    header('Location: '.$url);
}



function is_permission($permission = 'admin'){
    global $rs;
    $permissions = explode(',',$rs['permission']); 
    if(in_array($permission,$permissions,true)){
        return true;
    }
    else{
        return false;
    }
}

function pretty_date($date){
    return date("M d, Y h:i A",strtotime($date));
}
function get_category($child_id){
    global $db;
    $id = sanitize($child_id);
    $sql = ("SELECT p.id AS 'pid', p.category AS 'parent' , c.category AS 'child' , c.id AS 'cid'
    FROM category p
    INNER JOIN category c
    ON c.parent = p.id
    WHERE c.id = '$id'");
    $result = $db->query($sql);
    $category = mysqli_fetch_assoc($result);
    return $category;


}