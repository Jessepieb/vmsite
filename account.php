<!DOCTYPE html>
<html lang="en">
<?php 
    include("login.php");
    include("includes/head.php");
    include("includes/basket.php");
    if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['pass'])){
        try{
            $conn = setupDb($dbhost,$dbUpdateUsername,$dbUpdatePassword);
            $prep = $conn->prepare("UPDATE user SET pass=:p WHERE username=:un;");
            $prep -> bindParam(':p',hash("sha256",$_POST['pass']));
            $prep -> bindParam(':un',$_POST['user']);
            $prep->execute();
            $prep = null;
            $conn = null;
        }
        catch(PDOException $e){
            echo $e->getMessage(); //debug
        }
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
                <div class="box">
                    <h4>Account Information:</h4>
                    <p><?php echo (isset($_SESSION['user'])? "Name: ".$_SESSION['user']: "Not logged in");?></p>
                    <hr>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                        <p>Change your password:</p>
                        <input type="password" name="pass" class="form">
                        <input type="hidden" name="user" value="<?php echo (isset($_SESSION['user'])? $_SESSION['user']: '');?>">
                        <span class="input-group-btn">
                            <button class="btn btn-primary" type="submit">Change</button>
                        </span>
                    </form>
                </div>
                <div class="box">
                    <h4>Products in cart:</h4>
                        <table class="table">
                        <thead>
                            <tr>
                                <th colspan="2">Product</th>
                                <th style="width: 100px; text-align: center;">Amount</th>
                                <th style="width: 100px; text-align: center;">Price</th>
                                <th style="width: 100px; text-align: center;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php ReturnProductsInBasket();?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4">Total</th>
                                <th colspan="2" style="text-align: right;"><?php TotalPriceOfCart(); ?></th>
                            </tr>
                        </tfoot>
                    </table>
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
