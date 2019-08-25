     <!-- modal -->
     <?php
     require_once  $_SERVER['DOCUMENT_ROOT'].'/ecom/core/init.php';


     if(isset($_POST["id"])){
        $id = $_POST["id"];
    }else{
        $id = NULL;
    }
     $id = (int)$id;
     $sql = "SELECT * FROM products WHERE id = $id";
     $result = $db->query($sql);
     $product = mysqli_fetch_assoc($result);
     $brand_id =  $product['brand'];
     $sql = "SELECT brand From brand WHERE id = '$brand_id'";
     $brand_query = $db->query($sql);
     $brand = mysqli_fetch_assoc($brand_query);
     $size_string = $product['Sizes'];
     $size_string = rtrim($size_string,',');
     $size_array = explode(',',$size_string);
     ?>



     <?php ob_start();?>


         <div class="modal fade details-1" id="details-modal" tabindex="-1" role="dialog" aria-labelledby="detail-1" aria-hodden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" onclick="modal_close()" aria-label="close" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title text-center"><?= $product['title'];?></h4>
                      
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                            <span id="modal_errors" class="bg-danger"></span>
                                <div class="col-sm-6">
                                    <div class="center-block">
                                        <img src="<?= $product['image'];?>" style="height:auto;width:220px;"    alt="levi" class="details img-responsive">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <h4>Details!</h4>
                                    <p><?= nl2br($product['description']);?></p>
                                    <hr>
                                    <p>Price: <?= $product['price'];?></p>
                                    <p>Brand: <?= $brand['brand'];?></p>
                                    <form action="add_cart.php" mathod="post" id="add_product_form">
                                    <input type="hidden" name="available" id="available" value="">
                                    <input type="hidden" name="product_id" value="<?=$id;?>">
                                    <div class="form-group">
                                        <div class="col-xs-3">
                                            <label for="quantity">Quantity:</label>
                                            <input type="number" class="form-control" id="quantity" name="quantity" min="0">
                                        </div>
                                
                                    </div>
                                    <br>
                                    <br>
                                    <div class="form-group">
                                    <br>
                                    <br>
                                        <label for="size">Size:</label>
                                        <select class="form-control" id="size" name="size">
                                        <option value=""></option>
                                        <?php foreach($size_array as $string){
                                            $string_array = explode(':',$string);
                                            $size = $string_array[0];
                                            $available = $string_array[1];
                                  

                                            echo '<option value="'.$size.'" data-available="'.$available.'">'.$size.' ('.$available.' AVAILABLE)</option>';
                                        }
                                        ?>
                                       
                                        
                                        </select>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" onclick="modal_close()">Close</button>
                        <button class="btn btn-warning" onclick="add_to_cart();return false;"><span class="glyphicon glyphicon-shopping-cart"></span> Add to cart</button>
                    </div>
                </div>
            </div>
         </div>
         <script>
        jQuery('#size').change(function(){
            var available = jQuery('#size option:selected').data("available");
            jQuery('#available').val(available);
        });



 function modal_close(){
    jQuery('#details-modal').modal('hide');

 }




      

         </script>
         <?php echo ob_get_clean();?>
