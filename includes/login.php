<?php session_start(); ?>

<?php include "../admin/functions.php";?>
<?php include "db.php"; ?>


<?php

    if(isset($_POST['login'])){
        
        login_user(escape($_POST['username']),escape($_POST['password']));

    

        

    }


?>