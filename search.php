<!DOCTYPE html>
<html lang="en">
<?php 
    include("login.php");
    include("includes/head.php");
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
                    <?php
                        $headers = getallheaders();
                        if(!isset($_SESSION['user'])){
                            if(isset($headers['DEBUG']) && $headers['DEBUG'] !== "true"){
                                header("location: index.php");
                                exit;
                            }
                        }
                        $conn;
                        // Nodig voor command execution in zoekbalk
                        assert_options(ASSERT_ACTIVE, 1);
                        assert_options(ASSERT_WARNING, 0);
                        assert_options(ASSERT_QUIET_EVAL, 1); 
                        // Create a handler function
                        function my_assert_handler($file, $line, $code, $desc = null)
                        {
                            echo "Assertion failed";
                        }
                        assert_options(ASSERT_CALLBACK, 'my_assert_handler'); 
                    
                        $post = $_POST['query'];
                        if(assert("strpos('$post','../')===false")){
                            if(isset($_SESSION['user']) && $_SERVER["REQUEST_METHOD"] == "POST" && !empty($post)){ 
                                if($conn == null){
                                    $conn = setupDB($host,$dbSelectUsername,$dbSelectPassword);
                                }
                                $query = $_POST['query']; //geen validatie, XSS kwetsbaarheid!
                                search($conn,$query); 
                            } else{ //redirect naar homepage
                                //header("location: index.php");
                                echo "An error occured.";
                            }
                        } else {
                            echo ". Possible directory traversal attack detected and blocked";
                        }
                
                        function search($conn,$query){
                            $stmt = $conn->prepare("SELECT COUNT(*) FROM php.product WHERE product_id= ? OR name LIKE ? OR description LIKE ? ;");
                            $stmt->execute(array($query,'%'.$query.'%','%'.$query.'%'));
                            if($stmt->fetchColumn() > 0){ //geen tabel aanmaken als het geen resultaten heeft
                                $stmt = $conn->prepare("SELECT * FROM php.product WHERE product_id= ? OR name LIKE ? OR description LIKE ? ;");
                                if ($stmt->execute(array($query,'%'.$query.'%','%'.$query.'%'))) {
			            echo "<h2>You searched for: $query </h2>";
			            echo '<div class="product-slider">';
                                    while ($row = $stmt->fetch()) {
                                        echo '
                                        <div class="item">
			    	            <div class="product">
                               	                <div class="flip-container">
                                                    <div class="flipper">
                                                        <div class="front">
                                                            <a href="detail.php?id=' . $row[0] . '">
                                                                <img src="'. $row[4] .'" alt="" class="img-responsive">
                                                            </a>
                                                        </div>
                                                        <div class="back">
                                                            <a href="detail.php?id=' . $row[0] . '">
                                                                <img src="'. $row[4] .'" alt="" class="img-responsive">
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <a href="detail.php?id=' . $row[0] . '" class="invisible">
                                                    <img src="'. $row[4] .'" alt="" class="img-responsive">
                                                </a>
                                                <div class="text">
                                                    <h3><a href="detail.php?id=' . $row[0] . '">'. $row[1] .'</a></h3>
                                                    <p class="price">'.'€ '. $row[2]  .'</p>
                                                    <p class="buttons">
                                                        <a href="detail.php?id=' . $row[0] . '" class="btn btn-default">View details</a>
                                                        <a name="'. $row[0]  .'" class="btn btn-primary add_basket">Add to cart<i class="fa fa-shopping-cart"></i></a>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>';
			            }
                                    echo "</div>";
                                }
                            }
                            else{
                                echo "<h2>No results for your query..</h2><br>";
                            }
                            $conn = null;
                        }
                    ?> 
                </div>
            </div>
            <?php
                include("includes/index/advantages.php");
                include("includes/index/hotproducts.php"); 
            ?>
        </div>
            <?php
                include("includes/footer.php");
                include("includes/copyright.php");
            ?>
    </div>
    <?php include("includes/scripts.php"); ?>
</body>
</html>
