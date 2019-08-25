<?php 
require_once  $_SERVER['DOCUMENT_ROOT'].'/ecom/core/init.php';
if(!is_logged_in()){
    login_error_redirect();
}
include 'includes/header.php';
include 'includes/navbar.php';

$dbpath = '';

//delete image
if(isset($_GET['delete'])){

    $id = sanitize($_GET['delete']);
    $db->query("UPDATE products SET deleted = 1 WHERE id = '$id'");
    header("Location: products.php");

}







if(isset($_GET['add']) || isset($_GET['edit'])){
    $brandquery = $db->query("SELECT * FROM brand ORDER BY brand");
    $parentquery =$db->query("SELECT * FROM category WHERE parent = 0 ORDER BY category");
    $title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):'');
    $brand = ((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):'');
    $parent = ((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):'');
    $category = ((isset($_POST['child']) && !empty($_POST['child']))?sanitize($_POST['child']):'');
    $price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):'');
    $list_price = ((isset($_POST['list_price']) && $_POST['list_price'] != '')?sanitize($_POST['list_price']):'');
    $description = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):'');
    $sizes = ((isset($_POST['sizes']) && $_POST['sizes'] != '')?sanitize($_POST['sizes']):'');
    $sizes = rtrim($sizes,',');
    $saved_image = '';

    if(isset($_GET['edit'])){
        $edit_id = (int)$_GET['edit'];
        $productresults = $db->query("SELECT * FROM products WHERE id = '$edit_id'");
        $product = mysqli_fetch_assoc($productresults);
        if(isset($_GET['deleteimage'])){
            $image_url =  $product['image'];
            unlink($image_url);
            $db->query("UPDATE products SET image = '' WHERE id = '$edit_id'");
            header('Location: product.php?edit='. $edit_id);
        }
        
        
        
        $title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):$product['title']);
        $category= ((isset($_POST['child']) && $_POST['child'] != '')?sanitize($_POST['child']):$product['category']);
        $brand = ((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):$product['brand']);
        $parentq = $db->query("SELECT * FROM category WHERE id = '$category'");
        $parentresult = mysqli_fetch_assoc($parentq);
        $parent = ((isset($_POST['parent']) && $_POST['parent'] != '')?sanitize($_POST['parent']):$parentresult['parent']);
        $price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):$product['price']);
        $list_price = ((isset($_POST['list_price']) && $_POST['list_price'] != '')?sanitize($_POST['list_price']):$product['list_price']);
        $description = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):$product['description']);
        $sizes = ((isset($_POST['sizes']) && $_POST['sizes'] != '')?sanitize($_POST['sizes']):$product['Sizes']);
        $sizes = rtrim($sizes,',');
        $saved_image = (($product['image'] != '')?($product['image']):'');
        $dbpath = $saved_image;
         }
         if(!empty($sizes)){
            $SizeString = sanitize($sizes);
           
            $SizeString = rtrim($SizeString,',');
           
            $sizesarray = explode(',',$SizeString);
            $sarray = array();
            $qarray = array();
            foreach($sizesarray as $ss){
                $s = explode(':',$ss);
                $sarray[] = $s[0];
                $qarray[] = $s[1]; 
            }

        }else{
            $sizesarray = array();}
     if($_POST){
        // $dbpath = '';
        $errors = array();
   
            $required = array('title','brand','parent','child','price','sizes');
            foreach($required as $field){
                if($_POST[$field] == ''){
                    $errors[] = 'All fields are required.';
                    break;
                }
            }
            if(!empty($_FILES)){
                var_dump($_FILES);
                $photo = $_FILES['photo'];
                $name = $photo['name'];
                $namearray = explode('.',$name);
                $filename = $namearray[0];
                $fileext = $namearray[1];
                $mime = explode('/',$photo['type']);
                $mimetype = $mime[0];
                $mimeext = $mime[1];
                $tmploc = $photo['tmp_name'];
                $filesize = $photo['size'];
                $allowed = array('png','jpg','jpeg'.'gif');
             
                $uploadname = md5(microtime()).'.'.$fileext;
                $uploadpath = $_SERVER['DOCUMENT_ROOT'].'/ecom/'.$uploadname;
                $dbpath = '/ecom/'.$uploadname;
                if($mimetype != 'image'){
                    $errors[] = 'The file must be an image.';
                }
                if(!in_array($fileext,$allowed)){
                    $errors[] = 'The file extension must be a png ,jpg, jpeg, gif.';
                }
                if($filesize > 150000000){
                    $errors[] = 'The file size must be under 15MB';
                }
                if($fileext != $mimeext && ($mimeext == 'jpeg' && $fileext != 'jpg'))
                {
                    $errors[] = 'File extension does not match the file.';
                }
            }
            if(!empty($errors)){
                echo display_errors($errors);
            }
        else{
            // upload file and insert into database
            move_uploaded_file($tmploc,$uploadpath);
        
            if(isset($_GET['edit'])){
                $insertsql = "UPDATE products SET title = '$title', price = '$price', list_price = '$list_price', brand = '$brand',category = '$category',Sizes = '$sizes',image = '$dbpath', description = '$description' WHERE id ='$edit_id'";
            }
            else{
                $insertsql = "INSERT INTO products (`title`,`price`,`list_price`,`brand`,`category`,`description`,`Sizes`,`image`) 
                VALUES('$title','$price','$list_price','$brand','$category','$description','$sizes','$dbpath')";
            }
          
            $db->query($insertsql);
            header('Location: products.php');  
    }
    }
?>

<h2 class="text-center"><?=((isset($_GET['edit']))?'Edit':'Add A New');?> Product</h2><hr>
<form action="products.php?<?=((isset($_GET['edit'])?'edit='.$edit_id:'add=1'));?>" method="post" enctype="multipart/form-data">
    <div class="form-group col-md-3">
        <label for="title">Title</label>
        <input type="text" name="title" id="title" class="form-control" value="<?=$title;?>">
    </div> 
    <div class="form-group col-md-3">
        <label for="brand">Brand</label>
        <select name="brand" id="brand" class="form-control">
            <option value=""<?=(($brand == '')?' selected':'');?>></option>
            <?php  while($b = mysqli_fetch_assoc($brandquery)):?>
            <option value="<?=$b['id'];?>"<?=(($brand == $b['id'])?' selected':'');?>><?=$b['brand'];?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="form-group col-md-3">
        <label for="parent">Parent</label>
            <select name="parent" id="parent" class="form-control">
            
            <option value=""<?=((isset($_POST['parent']) && $_POST['parent'] == '')?' selected':'');?>></option> 
                <?php while($p = mysqli_fetch_assoc($parentquery)):?>
                    <option value="<?=$p['id'];?>"<?=(($parent== $p['id'])?' selected':'')?>><?=$p['category'];?></option>
                <?php endwhile;?> 
            </select>
    </div>
    <div class="form-group col-md-3">
        <label for="child">Child</label>
            <select name="child" id="child" class="form-control"></select>
        </label>
    </div>
    <div class="form-group col-md-3">
        <label for="price">Price</label>
        <input type="text" id="price" name="price" class="form-control" value="<?=$price;?>">
        </label>
    </div>
    <div class="form-group col-md-3">
        <label for="list_price">List Price</label>
        <input type="text" id="list_price" name="list_price" class="form-control" value="<?=$list_price;?>">
        </label>
    </div>
    <div class="form-group col-md-3">
        <label >Quantity & Sizes</label>
        <button type="button" class="btn btn-default form-control" onclick="jQuery('#sizesModal').modal('toggle');return false;">Quantity & Sizes</button>
    </div>
    <div class="form-group col-md-3">
        <label for="sizes">Sizes & QTY Preview</label>
        <input type="text" id="sizes" name="sizes" class="form-control" value="<?=$sizes;?>" readonly> 
    </div>
    <div class="form-group col-md-6">
    <?php if($saved_image != ''):?>
        <div class="saved-image"  style="height:308px;width:220px;"><img src="<?=$saved_image;?>" alt="saved image" style="width:220px;height:auto;">
        <br>
        <a href="products.php?deleteimage=1&edit=<?=$edit_id;?>" class="text-danger" >Delete Image</a>
        </div>
    <?php else: ?>
        <label for="photo">Product Photo</label>
        <input type="file" name="photo" id="photo" class="form-control">
    <?php endif;?>
    </div>
    <div class="form-group col-md-6">
    <label for="description">Description</label>
    <textarea name="description" id="description" rows="6" class="form-control"><?=$description;?></textarea>
    </div>
    <div class="form-group pull-right">
        <a href="products.php" class="btn btn-default">Cancel</a>
        <input type="submit" value="<?=((isset($_GET['edit'])?'Edit':'Add'));?> Product" class="btn-success btn"><div class="clearfix"></div>
    </div>
</form>

    <!-- Modal -->
<div class="modal fade" id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="sizesModal">Quantity & Sizes</h4>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
            <?php for($i=1;$i<=12;$i++): ?>
                <div class="form-group col-md-4">
                    <label for="size<?=$i?>">Size</label>
                    <input type="text" class="form-control" value="<?=((!empty($sarray[$i-1]))?$sarray[$i-1]:'');?>" name="size<?=$i?>" id="size<?=$i?>">
                </div>
                
                <div class="form-group col-md-2">
                    <label for="qty<?=$i?>">Quantity</label>
                    <input type="text" class="form-control" value="<?=((!empty($qarray[$i-1]))?$qarray[$i-1]:'');?>" name="qty<?=$i?>" id="qty<?=$i?>">
                </div>
            <?php endfor;?>  
        
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="updatesize();jQuery('#sizesModal').modal('toggle');return false;">Save changes</button>
      </div>
    </div>
  </div>
</div>



<?php } else{

$sql = "SELECT * FROM products WHERE deleted = 0";
$presults = $db->query($sql);

if(isset($_GET['Featured'])){

    $id = (int)$_GET['id'];
    $featured = (int)$_GET['Featured'];
    $featuredsql = "UPDATE products SET Featured = $featured WHERE id = $id";
    $db->query($featuredsql);
    header('Location: products.php');
}

?>
<style>
body{
    padding: 73px 10px;
}

</style>


<h2 class="text-center">Product</h2>
<a href="products.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Add Product</a><div class="clearfix"></div>
<hr>
<table class="table table-bordered table-condensed table-striped">
    <thead><th></th> <th>Product</th> <th>Price</th> <th>Category</th> <th> Featured </th><th>SOLD</th></thead>
    <tbody>
    <?php while($product = mysqli_fetch_assoc($presults)):
        
        $childid = $product['category'];
        $catsql = "SELECT * FROM category WHERE id = $childid";
        $result = $db->query($catsql);
        $child = mysqli_fetch_assoc($result);
        $parentid = $child['parent'];
        $psql = "SELECT * FROM category WHERE id = $parentid";
        $presult = $db->query($psql);
        $parent = mysqli_fetch_assoc($presult);
        $category = $parent['category'].'~'.$child['category']; 
        ?>
        <tr>
            <td>
             <a href="products.php?edit=<?=$product['id'];?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span></a>
             <a href="products.php?delete=<?=$product['id'];?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-remove"></span></a>

             </td>
            <td><?=$product['title'];?></td>
            <td><?=money($product['price']);?></td>
            <td><?=$category;?></td>
            <td><a href="products.php?Featured=<?=(($product['Featured'] == 0)?'1':'0');?>&id=<?=$product['id'];?>">
            <span class="btn btn-xs btn-default glyphicon glyphicon-<?=(($product['Featured']) == 1)?'minus':'plus';?>"></span>
            </a>&nbsp <?= (($product['Featured']) == 1)?'Featured Product':''?></td>
            <td>0</td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>


<?php } include 'includes/footer.php';?>

<script>
    jQuery('document').ready(function(){
        child_category('<?=$category;?>');
      

    });
</script>