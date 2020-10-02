<?php

function redirect($location)
{
    header("Location:" . $location);
    exit;
}

function imagePlaceholder($image=''){

    if(!$image){
        return 'aaa.jpg';
    } else {
        return $image;
    }
}

function currentUser(){
    if(isset($_SESSION['username'])){
        return $_SESSION['username'];
    }
    return false;
}

//Check for method - ie. 'post' when using forms
function ifItIsMethod($method = null)
{

    if ($_SERVER['REQUEST_METHOD'] == strtoupper($method)) {
        return true;
    }

    return false;
}

function isLoggedIn()
{

    if (isset($_SESSION['user_role'])) {

        return true;
    }

    return false;
}

function checkIfUserIsLoggedInAndRedirect($redirectLocation = null)
{

    if (isLoggedIn()) {

        redirect($redirectLocation);
    }
}

function escape($string)
{

    global $connection;

    return mysqli_real_escape_string($connection, trim($string));
}

function users_online()
{

    //Check for param from script.js, 'usersonline'
    if (isset($_GET['usersonline'])) {

        global $connection;

        if (!$connection) {

            session_start();

            include "../includes/db.php";


            $session = session_id(); //Save session in variable
            $time = time();

            //time after login until user is nolonger shown as online:
            $time_out_in_seconds = 30;
            $time_out = $time - $time_out_in_seconds;

            $query = "SELECT * FROM users_online WHERE session = '$session'";
            $send_query = mysqli_query($connection, $query);

            //$count for number of online users
            $count = mysqli_num_rows($send_query);

            //If new user logs in (session id doesn't exist in db), create it as new entry
            if ($count == NULL) {
                mysqli_query($connection, "INSERT INTO users_online(session, time) VALUES ('$session', '$time')");
            } else {
                mysqli_query($connection, "UPDATE users_online SET time = '$time' WHERE session ='$session'");
            }

            $users_online_query = mysqli_query($connection, "SELECT * FROM users_online WHERE time > $time_out");
            echo $count_user = mysqli_num_rows($users_online_query);
        }
    } // get request isset() ends

}

users_online();

function confirmQuery($result)
{

    global $connection;

    if (!$result) {
        die("QUERY FAILED " . mysqli_error($connection));
    }
}

function insert_categories()
{

    global $connection; //Setting $connection to global to access it in function

    if (isset($_POST['submit'])) {

        $cat_title = escape($_POST['cat_title']);

        if ($cat_title == "" || empty($cat_title)) {

            echo "This field must be filled.";
        } else {

            $stmt = mysqli_prepare($connection, "INSERT INTO categories(cat_title) VALUES(?) ");

            mysqli_stmt_bind_param($stmt, "s", $cat_title);

            mysqli_stmt_execute($stmt);


            if (!$stmt) {
                die('QUERY FAILED' . mysqli_error($connection));
            }
        }
        mysqli_stmt_close($stmt);
    }
}

function findAllCategories()
{

    global $connection;
    // FIND ALL CATEGORIES and DISPLAY AS TABLE
    $query = "SELECT * FROM categories";
    $select_categories = mysqli_query($connection, $query);
    while ($row = mysqli_fetch_assoc($select_categories)) {
        $cat_id = $row['cat_id'];
        $cat_title = $row['cat_title'];

        echo "<tr>";
        echo "<td>{$cat_id}</td>";
        echo "<td>{$cat_title}</td>";
        echo "<td><a onClick=\"javascript: return confirm('Do you really want to delete this category?'); \" 
        href='categories.php?delete={$cat_id}'>Delete</a></td>"; //Add "delete" as key in $_GET superglobal array
        echo "<td><a href='categories.php?edit={$cat_id}'>Edit</a></td>";
        echo "</tr>";
    }
}

function deleteCategories()
{
    global $connection;
    if (isset($_GET['delete'])) { //Check $_GET for "delete" key - true if "Delete" is pressed
        $the_cat_id = escape($_GET['delete']);
        $query = "DELETE FROM categories WHERE cat_id = {$the_cat_id} ";
        $delete_query = mysqli_query($connection, $query);
        redirect("/cms/admin/categories.php");
    }
}

function renderUpdateSection()
{
    global $connection;
    if (isset($_GET['edit'])) {

        $cat_id = escape($_GET['edit']);

        include "includes/update_categories.php";
    }
}

function recordCount($table)
{

    global $connection;

    $query = "SELECT * FROM " . $table;
    $select_all_posts = mysqli_query($connection, $query);

    $result = mysqli_num_rows($select_all_posts);

    confirmQuery($result);

    return $result;
}

function checkStatus($table, $column, $status)
{

    global $connection;

    $query = "SELECT * FROM $table WHERE $column = '$status' ";
    $result = mysqli_query($connection, $query);

    confirmQuery($result);
    return mysqli_num_rows($result);
}

function checkRole($table, $column, $role) //
{
    global $connection;

    $query = "SELECT * FROM $table WHERE $column = '$role' ";
    $result = mysqli_query($connection, $query);

    confirmQuery($result);
    return mysqli_num_rows($result);
}

function is_admin($username)
{

    global $connection;

    $query = "SELECT user_role FROM users WHERE username = '$username'";
    $result = mysqli_query($connection, $query);
    confirmQuery($result);

    $row = mysqli_fetch_array($result);

    if ($row['user_role'] == 'admin') {
        return true;
    } else {
        return false;
    }
}

function username_exists($username)
{ //Check if entered username is taken already
    global $connection;

    $query = "SELECT username FROM users WHERE username = '$username'";
    $result = mysqli_query($connection, $query);
    confirmQuery($result);

    if (mysqli_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

function email_exists($email)
{ //Check if entered email is registered already
    global $connection;

    $query = "SELECT user_email FROM users WHERE user_email = '$email'";
    $result = mysqli_query($connection, $query);
    confirmQuery($result);

    if (mysqli_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

function register_user($username, $email, $password)
{
    global $connection;

    $username   = escape($_POST['username']);
    $email      = escape($_POST['email']);
    $password   = escape($_POST['password']);

    $password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));

    $query = "INSERT INTO users (username, user_email, user_password, user_role) ";
    $query .= "VALUES('{$username}', '{$email}', '{$password}', 'subscriber' ) ";
    $register_user_query = mysqli_query($connection, $query);

    confirmQuery($register_user_query);
}

function login_user($username, $password)
{
    global $connection;

    $username = trim($username);
    $password = trim($password);

    $username = escape($_POST['username']);
    $password = escape($_POST['password']);

    $query = "SELECT * FROM users WHERE username = '{$username}' ";
    $select_user_query = mysqli_query($connection, $query);

    if (!$select_user_query) {
        die("QUERY FAILED" . mysqli_error($connection));
    }

    while ($row = mysqli_fetch_array($select_user_query)) {
        $db_id = $row['user_id'];
        $db_username = $row['username'];
        $db_user_password = $row['user_password'];
        $db_user_firstname = $row['user_firstname'];
        $db_user_lastname = $row['user_lastname'];
        $db_user_role = $row['user_role'];

        //Successful login:
        if (password_verify($password, $db_user_password)) {

            $_SESSION['username'] = $db_username;
            $_SESSION['firstname'] = $db_user_firstname;
            $_SESSION['lastname'] = $db_user_lastname;
            $_SESSION['user_role'] = $db_user_role;

            redirect("/cms/admin");

            //Whatever other scenarios may occcur:
        } else {

            return false;
        }
    }
    return true;
}
