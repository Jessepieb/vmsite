<?php
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user'])){
        $reviewTxt = "";
        if(!empty($_POST['review'])){
            try{
                $conn = setupDB($dbhost,$dbusername,$dbpassword);
                $prep = $conn->prepare("INSERT INTO review (product_id,user_id,reviewText) VALUES(:id,:userid,:txt)");
                $prep->bindParam(':id',$_GET['id']);
                $prep->bindParam(':userid',$_SESSION['user']);
                $prep->bindParam(':txt',$_POST['review']);
                $prep->execute();
                $prep = null;
                $conn = null;
                header("location:item.php?id=".$id."\"");
            }
            catch(PDOException $e){
                echo e.getMessage();
            }

        }
        else{
            //TODO afhandelen lege form
        }
        
    }
?>
<html>
    <body>
        <form action="review.php" method="POST" id ="reviewForm">
            Submit your review:<br> <textarea name="review" form="reviewForm"></textarea>
            <input type="submit">
        </form>
    </body>
</html>