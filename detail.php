<!DOCTYPE html>
<html lang="en">
<?php 
    include("login.php");
    include("review.php");
    include("includes/head.php");
    $logInOut = (isset($_SESSION['user'])? 'Logged in as:'.$_SESSION['user'].'   '.'<a href="logout.php" data-toggle="modal" data-target="#login-modal">Logout</a>' : '<a href="#" data-toggle="modal" data-target="#login-modal">Login</a>');
    if(!empty($_GET['id'])){
        $id = (int)$_GET['id'];
        $conn = setupDb($dbhost,$dbSelectUsername,$dbSelectPassword);
        $stmt = $conn->prepare("SELECT COUNT(*) FROM php.product WHERE product_id=:id;"); //kijken of er uberhaupt items zijn
        $stmt->bindParam(':id',$id);
        $stmt->execute();
        if($stmt->fetchColumn() == 1){ 
            $product_id = $name = $description = $price = $img = $extraDes = $cat = ""; //variabelen voor productinformatie
            $alreadySet = false;
            $stmt = $conn->prepare("SELECT * FROM php.product LEFT OUTER JOIN php.review using(product_id) WHERE product.product_id =:id ;"); //include reviews
            $stmt->bindParam(':id',$id);
            if($stmt->execute()){
                while($row = $stmt->fetch()){
                    if(!$alreadySet){ //hoeft maar 1 keer gedaan te worden
                        $product_id = $row[0];
                        $name = $row[1];
                        $price = $row[2];
                        $description = (!empty($row[3])? $row[3]: 'No description available.'); //description is niet verplicht
                        $img = $row[4];
                        $extraDes = (!empty($row[5])? $row[5]: ""); //als er geen 'extraDes' is, moet het een empty string zijn
                        $cat = $row[6];
                        $alreadySet = true;
                    }
                    if(!empty($row[7])){
                        array_push($reviews,array($row[7],$row[8],$row[9])); //reviews toevoegen aan array
                    }
                }
                if(count($reviews) > 0){
                    $reviewText = $reviewText.showReviews($reviews);
                }
            }
        }
        else{
            echo "<h2>No item found with this id..</h><br>";
        }
        $stmt = null;
        $conn = null;
    }
    else{
        echo "No id set"; //debug
    }
?>
<body>
    <div id="navb">
    <?php 
        include("includes/topbar.php"); 
        include("includes/navbar.php");
    ?>
    </div>
        <div id="all">
            <div id="content">
                <div class="container">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li><a href="index.php">Home</a>
                        </li>
                        <li><a href="search.php?cat=all">All items</a>
                        </li>
                        <li><a href="search.php?cat=<?php echo $cat ?>"><?php echo $cat ?></a>
                        </li>
                        <li><?php echo $name ?></li>
                    </ul>

                </div>
                    <div class="col-md-3">
                    </div>
                    <div class="col-md-10">
                        <div class="row" id="productMain">
                            <div class="col-sm-6">
                                <div id="mainImage">
                                <img src=<?php echo $img?> alt="" class="img-responsive">
                            </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="box">
                                    <h1 class="text-center"><?php echo $name ?></h1>
                                    <p class="price">€<?php echo $price ?></p>
                                    <p class="text-center buttons">
                                        <a href="basket.html" class="btn btn-primary"><i class="fa fa-shopping-cart"></i> Add to cart</a> 
                                        <a href="basket.html" class="btn btn-default"><i class="fa fa-heart"></i> Add to wishlist</a>
                                    </p>
                                </div>
                                <div class="row" id="thumbs">
                                <div class="col-xs-4">
                                    <a href=<?php echo $img ?> class="thumb">
                                        <img src=<?php echo $img ?> alt="" class="img-responsive">
                                    </a>
                                </div>
                                <div class="col-xs-4">
                                    <a href=<?php echo str_replace(".jpg","_2.jpg",$img) ?> class="thumb">
                                        <img src=<?php echo str_replace(".jpg","_2.jpg",$img) ?> alt="" class="img-responsive">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box" id="details">
                        <p>
                        <h4>Product description</h4>
                        <p><?php echo $description;?></p>
                        <?php if(!empty($extraDes)){
                            echo '<hr>
                            <blockquote>
                                <p><em>'.$extraDes.'</em>
                                </p>
                            </blockquote>';
                        } ?>     
                    </div>
                    <?php reviewPrint($reviewText);?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                include("includes/footer.php");  
                include("includes/copyright.php");
            ?>
        </div>
        <?php include("includes/scripts.php"); ?>
</body>
</html>