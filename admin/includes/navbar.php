<nav class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <a href="/ecom/admin/index.php" class="navbar-brand" >BuYiT Admin</a>
               
                <ul class="nav navbar-nav">

                
                <!-- mens -->


                <li><a href="brand.php">Brands</a></li>
                <li><a href="category.php">Categories</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="archive.php">Archived</a></li>
                <?php if(is_permission('admin')):?>
                <li><a href="users.php">Users</a></li>

                <?php endif;?>
                <?php
                 $first = explode(' ',$rs['first_name']);
                ?>
                <li class="dropdown">
                    
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">Hello <?= $first[0];?>!
                    <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="change_password.php">Change Password</a></li>
                        <li><a href="logout.php">Log Out</a></li>
                    </ul>
                </li>
                
                <!-- <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $parent['category'];  ?><span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                 
                    <li><a href=""> </a></li>
                
                    
                    </ul>
                
                </li>
                 </ul> -->

            </div>
        </nav>