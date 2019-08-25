<?php
    $sql = "SELECT * FROM category WHERE parent = 0";
    $pquery = $db->query($sql);
?>    





         <!-- Navigation menu -->

        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <a href="website.php" class="navbar-brand" >BuYiT</a>
               
                <ul class="nav navbar-nav">

                 <?php 
                while($parent = mysqli_fetch_assoc($pquery)) : ?>
                <?php 
                $parent_id = $parent['id']; 
                $sql2 = "SELECT * FROM category WHERE parent = '$parent_id'";
                $cquery = $db->query($sql2);
                
                ?>
                
                <!-- mens -->
                
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $parent['category'];  ?><span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                    <?php while($child = mysqli_fetch_assoc($cquery)) :?>
                    <li><a href="category.php?cat=<?=$child['id']?>"><?php echo $child['category']; ?> </a></li>
                    <?php endwhile; ?>
                    
                    </ul>
                
                </li>
                <?php endwhile;?>
                 </ul>

            </div>
        </nav>