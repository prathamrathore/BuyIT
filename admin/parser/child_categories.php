<?php
require_once  $_SERVER['DOCUMENT_ROOT'].'/ecom/core/init.php';
$parentid = (int)$_POST['parentid'];
$selected = sanitize($_POST['selected']);
$childcat = $db->query("SELECT * FROM category WHERE parent = '$parentid' ORDER BY category");
ob_start();?>
<option value=""></option>
 <?php while($child = mysqli_fetch_assoc($childcat)): ?>
    <option value="<?=$child['id'];?>"<?=(($selected == $child['id'])?' selected':'');?>><?=$child['category'];?></option>
<?php endwhile;?>
<?php echo ob_get_clean();?>