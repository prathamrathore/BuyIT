<?php 
require_once  $_SERVER['DOCUMENT_ROOT'].'/ecom/core/init.php';
if(!is_logged_in()){
    login_error_redirect();
}
include 'includes/header.php';
include 'includes/navbar.php';?>

<?php 
    if(isset($_GET['deleted'])){
        $proid = (int)$_GET['deleted'];
        $sql1 = "UPDATE products SET deleted = 0 WHERE id = $proid";
        $db->query($sql1);
        header("Location: archive.php");

    }

?>


<?php 
    $sql = "SELECT * FROM products WHERE deleted = 1";
    $result = $db->query($sql);
?>


<h2 class="text-center">Archived</h2>
<table class="table table-bordered table-condensed table-stripped">
    <thead><th></th> <th>Product</th> <th>Price</th> <th>Category</th> <th> Featured </th><th>SOLD</th></thead>
    <tbody>
        <?php
            while($archive = mysqli_fetch_assoc($result)):
                $childid = $archive['category'];
                $cresult = "SELECT * FROM category WHERE id = $childid";
                $cresults = $db->query($cresult);
                $child = mysqli_fetch_assoc($cresults);
                $parentid = $child['parent'];
                $parentresult = "SELECT * FROM category WHERE id = $parentid";
                $result = $db->query($parentresult);
                $parent = mysqli_fetch_assoc($result);
                $category = $parent['category'].'~'.$child['category'];
        ?>
                <tr>
            <td>
             <a href="archive.php?deleted=<?=$archive['id'];?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-refresh"></span></a>
            </td>
            <td><?=$archive['title'];?></td>
            <td><?=money($archive['price']);?></td>
            <td><?=$category;?></td>
            <td><a href="archive.php?Featured=<?=(($archive['Featured'] == 0)?'1':'0');?>&id=<?=$archive['id'];?>">
            <span class="btn btn-xs btn-default glyphicon glyphicon-<?=(($archive['Featured']) == 1)?'minus':'plus';?>"></span>
            </a>&nbsp <?= (($archive['Featured']) == 1)?'Featured Product':''?></td>
            <td>0</td>
        </tr>
    <?php endwhile; ?>
    </tbody>



</table>


















<?php include 'includes/footer.php';?>