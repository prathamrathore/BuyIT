<?php 
require_once  $_SERVER['DOCUMENT_ROOT'].'/ecom/core/init.php';
if(!is_logged_in()){
    login_error_redirect();
}
include 'includes/header.php';
include 'includes/navbar.php';
?>
<style>
body{
    padding: 73px 10px;
}

</style>

<?php $sql = "SELECT * FROM category WHERE parent = 0";
$result = $db->query($sql);
$errors = array();
$category1 = '';
$post_parent = '';

// edit the category


if(isset($_GET['edit']) && !empty($_GET['edit'])){
     $edit_id = (int)$_GET['edit'];
     $edit_id = sanitize($edit_id);
     $sql = "SELECT * FROM category WHERE id = '$edit_id'";
     $edit_result = $db->query($sql);
     $edit_category = mysqli_fetch_assoc($edit_result);

}


//delete the category

if(isset($_GET['delete']) && !empty($_GET['delete'])){

    $delete_id = (int)$_GET['delete'];
    $delete_id = sanitize($delete_id);
    $sql = "SELECT * FROM category WHERE id = '$delete_id'";
    $result = $db->query($sql);
    $category1 = mysqli_fetch_assoc($result);
    if($category1['parent'] == 0){

        $sql = "DELETE FROM category WHERE parent = '$delete_id'";
        $db->query($sql);
        $dsql = "DELETE FROM category WHERE id = '$delete_id'";
        $db->query($dsql);
     
        header('LOCATION: category.php');
       
    }else{

    $dsql = "DELETE FROM category WHERE id = '$delete_id'";
    $db->query($dsql);

    header('LOCATION: category.php');
   
    }

}


if(isset($_POST) && !empty($_POST)){
             
                $category = sanitize($_POST['category']);
                $post_parent = (int)sanitize($_POST['parent']);
                $sqlform = "SELECT * FROM category WHERE category = '$category' AND parent = '$post_parent'";
               
                
                if($category == ''){
                    $errors[] .= 'The category cannot be left blank.';
                }
               
    
                if(isset($_GET['edit'])){
                    // $id = $edit_category['id'];
                    $sqlform = "SELECT * FROM category WHERE category = '$category' AND parent = '$post_parent' AND id != '$edit_id'";
                }
                
                
                
                $fresult = $db->query($sqlform);
                $count = mysqli_num_rows($fresult);   
         
            if($count>0){
               $errors[] .= $category. ' alerady exists. Please choose a new category. ';
            }
            if(!empty($errors)){
                $display = display_errors($errors); } 
           else{       
            if(isset($_GET['edit'])){
                        $updatesql = "UPDATE category SET category = '$category', parent = '$post_parent' WHERE id = '$edit_id'";
                       
                        $db->query($updatesql);
                    
                        header('LOCATION: category.php');
                        
                            
                       
                        }
             else{
                           
                $updatesql = "INSERT INTO category (category,parent) VALUES ('$category','$post_parent')";
                           
                $db->query($updatesql); 
            
                header('LOCATION: category.php');      
              
                        }
                           
                    }        
                        
        }
        $category_value = '';
        $parent_value = 0;
        if(isset($_GET['edit'])){
            $category_value = $edit_category['category'];
            $parent_value = $edit_category['parent'];
        }
        else{
            if(isset($_POST)){
            $category_value = $category1;
            $parent_value = $post_parent;
        }
    }
        

        ?>





<h2 class="text-center">Categories</h2><hr>
<div class="row">
    <div class="col-md-6">
    <legend><?=((isset($_GET['edit']))?'Edit ':'Add ');?>Category</legend>
    <div id="errors"></div>
        <form action="category.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:'');?>" class="form" method="post">
            <div class="form_group">
                <label for="parent" >Parent</label>
                <select name="parent" id="parent" class="form-control">
                    <option value="0"<?= (($parent_value == 0)?' selected="selected"':'');?>>Parent</option>
                    <?php while($parent = mysqli_fetch_assoc($result)):?>
                        <option value="<?= $parent['id'];?>"<?= (($parent_value == $parent['id'])?' selected="selected"':'');?>><?= $parent['category'];?></option>
                    <?php endwhile;?>
                </select>
            </div>
            <div class="form-group">
            <label for="category">Category</label>
            <input type="text" name="category" class="form-control" value="<?=$category_value;?>">
            
            </div>
            <div class="form-group">
                <input type="submit" name="add_submit" value="<?= ((isset($_GET['edit']))?'Edit':'Add')?> Category" class="btn btn-success">
            </div>
        </form>
    
    </div>
    <div class="col-md-6">
        <table class="table table-bordered">
        <thead>
            <th>Category</th>
            <th>Parent</th>
            <th></th>
        </thead>
        <tbody>
        <?php 
         $sql = "SELECT * FROM category WHERE parent = 0";
         $result = $db->query($sql);
         while($parent = mysqli_fetch_assoc($result)):
            $parent_id = (int)$parent['id'];
            $sql2 = "SELECT * FROM category WHERE parent = '$parent_id'";
            $cresult = $db->query($sql2);    
            
        ?>
        <tr class="bg-primary">
            <td><?= $parent['category'];?></td>
            <td>Parent</td>
            <td>
            <a href="category.php?edit=<?= $parent['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
            <a href="category.php?delete=<?= $parent['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
            </td>
        </tr>
        <?php while($child = mysqli_fetch_assoc($cresult)):?>
        <tr class="bg-info">
            <td><?= $child['category'];?></td>
            <td><?= $parent['category'];?></td>
            <td>
            <a href="category.php?edit=<?= $child['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
            <a href="category.php?delete=<?= $child['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
            </td>
        </tr>

        <?php endwhile; ?>
        <?php endwhile;?>
        </tbody>
        </table>
    </div>
</div>

  <script>
            jQuery('document').ready(function(){
                jQuery('#errors').html('<?=$display;?>');

            });
            
            </script>

<?php include 'includes/footer.php';?>