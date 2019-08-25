<?php 
require_once  $_SERVER['DOCUMENT_ROOT'].'/ecom/core/init.php';
if(!is_logged_in()){
    login_error_redirect();
}
include 'includes/header.php';
include 'includes/navbar.php';


// get brand from database
$sql = "SELECT * FROM brand ORDER BY brand";
$results = $db->query($sql);
$errors = array();

// edit the brand
if(isset($_GET['edit']) && !empty($_GET['edit'])){
    $edit_id = (int)$_GET['edit'];
    $edit_id = sanitize($edit_id);
    $sql2 = "SELECT * FROM brand WHERE id = '$edit_id'";
    $edit_result = $db->query($sql2);
    $eBrand = mysqli_fetch_assoc($edit_result);
    
}




//delete the brand 
if(isset($_GET['delete']) && !empty($_GET['delete'])){
    $delete_id = (int)$_GET['delete'];
    $delete_id = sanitize($delete_id);
    $sql = "DELETE FROM brand WHERE id = '$delete_id'";
    $db->query($sql);
    header('Location: brand.php');
    
    }


// if add form is submited


if(isset($_POST['add_submit'])){
    $brand = sanitize($_POST['brand']); 
    

        if($_POST['brand']  ==''){
            $errors[] .='You must enter a Brand!!!';
        }
        $sql = "SELECT * FROM brand WHERE brand = '$brand'";
                if(isset($_GET['edit'])){
                    $sql = "SELECT * FROM brand WHERE brand = '$brand' AND id != '$edit_id'";

                }


                $result = $db->query($sql);
                $count = mysqli_num_rows($result);
        if($count > 0){
            $errors[] .= $brand.' Brand Alerady Exists...Please Enter Another Brand!!!';

        }
        if(!empty($errors)){
            echo display_errors($errors);
        }
                else{
                    if(isset($_GET['edit'])){
                        $sql3 = "UPDATE brand SET brand = '$brand' WHERE id = '$edit_id'";
                    }
                    else{
                        $sql3 = "INSERT INTO brand (brand) VALUES('$brand')";
                        }

                        $db->query($sql3);
                        header('Location: brand.php');
                    }
       

}

?>

<h2 class="text-center">Brands</h2><hr>

<div class="text-center"> 
    <form action="brand.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:'');?>" method="post" class="form-inline">
        <div class="form-group">
            <?php 
            $brand_value = '';
            if(isset($_GET['edit'])){
                $brand_value = $eBrand['brand'];
                }
                else{
                    if(isset($_POST['brand'])){
                    $brand_value = sanitize($_POST['brand']);
                    }
                }
            ?>


            <label for="brand" id="brand"><?=((isset($_GET['edit']))?'Edit':'Add');?> A Brand:</label>
            <input type="text" name="brand" id="brand" class="form-control" value="<?=$brand_value;?>">
            <?php if(isset($_GET['edit'])):?>
                <a href="brand.php" class="btn btn-default">Cancel</a>
             <?php endif; ?>


            <input type="submit" name="add_submit" value="<?=((isset($_GET['edit']))?'Edit':'Add');?> Brand" class="btn btn-success">
        </div>
    </form>
</div>
<hr>
<table class="table table-bordered table-striped table-auto ">
    <thead>
        <th></th><th>Brands</th><th></th>
    </thead>
    <tbody>
        <?php 
        while($brand = mysqli_fetch_assoc($results)): ?>
        <tr>
            <td><a href="brand.php?edit=<?=$brand['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a></td>
            <td><?= $brand['brand'];?></td>
            <td><a href="brand.php?delete=<?=$brand['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include 'includes/footer.php';?>