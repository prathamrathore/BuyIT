<?php
  require_once 'C:\xampp\htdocs\ecom\core\init.php';  
  include 'includes/head.php';
  include 'includes/navbar.php';
  include 'includes/headerfull.php';
  include 'includes/leftsidebar.php';

  $sql = "SELECT * FROM products WHERE Featured = 1";
  $featured = $db->query($sql);

?>      

<link rel="stylesheet" href="/ecom/style.css">


       
            
            <div class="col-md-8">
                <div class="row">
                    <h2 class="text-center">Featured Products</h2>
                    <?php while($product = mysqli_fetch_assoc($featured)):
                         
                    ?>
                        <div class="col-md-3">
                            <h4 class="text-center"><?= $product['title'];?></h4>
                            <div style="height:308px;width:220px;"><img style="width:220px;height:auto;" src="<?= $product['image'];?>" alt="<?= $product['title'];?>"></div>
                            <p class="list-price text-danger text-center">List Price: &#x20b9;<s><?= $product['list_price'];?> </s></p>
                            <p class="text-center">Our Price: &#x20b9;<?= $product['price'];?></p>
                            <button type="button" class="btn btn-md btn-success" style="margin-left:80px;" onclick="detailsmodal(<?= $product['id'];?>)">Details!</button>

                        </div>
                    <?php endwhile;?>



                </div>
            </div>
          

<?php
  
  include 'includes/rightsidebar.php'; 
  include 'includes/footer.php';
 
?>         