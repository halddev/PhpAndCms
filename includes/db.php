<?php ob_start();

//Save connection params as array:
$db['db_host'] = "localhost";
$db['db_user'] = "root";
$db['db_pass'] = "";
$db['db_name'] = "cms";

//Convert array into constants with foreach loop for added security:
    //Equal to manually writing define("DB_HOST", 'localhost');
    //and so on for each individual parameter.
    foreach($db as $key => $value){
        define(strtoupper($key), $value);
    }
//use new constants as parameters for mysqli_connect()
$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$query = "SET NAMES utf8";
mysqli_query($connection, $query);
// if($connection){
//     echo "Connection established.";
// }

?>