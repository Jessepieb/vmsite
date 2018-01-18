<?php       //db variabelen
    $dbhost = "127.0.0.1"; 
    $dbInsertUsername = "user2";
    $dbInsertPassword = "phpBDADMIN";
    $dbSelectUsername = "user1";
    $dbSelectPassword = "phpBDADMIN";
    $dbUpdateUsername = "";
    $dbUpdatePassword = "";


    function setupDB($hst,$un,$pw){
        try {
                $conn = new PDO("mysql:host=$hst;dbname=php", $un, $pw);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $conn;
            }
        catch(PDOException $e)
            {
                echo "Connection failed: " . $e->getMessage(); //debug
                return null;
            }       
    }
?>
