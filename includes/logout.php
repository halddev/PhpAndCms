<?php ob_start(); ?>
<?php include "db.php"; ?>
<?php session_start(); ?>

<?php
//clear sessions upon logout, and then redirect to index
$_SESSION['username'] = null;
$_SESSION['firstname'] = null;
$_SESSION['lastname'] = null;
$_SESSION['user_role'] = null;

header("Location: ../index.php");

?>