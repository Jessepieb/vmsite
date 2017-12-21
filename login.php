<?php
    session_start();
    include("dbConfig.php");
    $user = $pass = $userEr = $passEr = $err ="";

    if ($_SERVER["REQUEST_METHOD"] == "POST" && (!isset($_SESSION['user']))){ //gebruikers die al ingelogd zijn zouden dit stuk code nu overslaan, dat voorkomt de bug
        if($conn == null){
            $conn = setupDB($dbhost,$dbSelectUsername,$dbSelectPassword);
        }
        if(empty($_POST["user"])){
            //TODO error handling
        }
        else{
            $user = $_POST["user"];
        }
        if(empty($_POST["pass"])){
            //TODO error handling
        }
        else{
            $pass = $_POST["pass"];
        }
        if(!empty($user) && !empty($pass)){
            login($conn,$user,$pass); //geen validatie
        }
    }

    function login($conn,$user,$pass){
        $pass = hash("sha256",$pass); //simpele hash functie zonder salt
        //echo "SELECT * FROM user WHERE username = '{$user}' AND pass = '{$pass}';"."<br>";        //voor debug
        try{
            $res = $conn->query("SELECT * FROM user WHERE username = '{$user}' AND pass = '{$pass}';");
            $row = $res->fetch();
            if($row){
                $_SESSION['user'] = $row[1];
                $res = null;
                $conn = null;
            }
            else{
                echo "Username or password invalid!"; //TODO error handling
            }
        }
        catch(PDOException $e){
            echo $e->getMessage(); //debug
        }
    }
?>
