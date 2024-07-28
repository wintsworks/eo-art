<?php
$dsn="mysql:host=localhost;dbname=ink_eo_db";
$dbuser="root";
$dbpass="Weather4689!";



try{
    $db=new PDO($dsn, $dbuser, $dbpass);
}catch(Exception $e){
    $error = $e->getMessage();
    echo $error;
    exit();
}
?>
