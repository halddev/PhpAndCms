<?php
if (isset($_POST['create_user'])) {

    // Saving input on button click:
    $user_firstname = escape($_POST['user_firstname']);
    $user_lastname = escape($_POST['user_lastname']);
    $user_role = escape($_POST['user_role']);
    $username = escape($_POST['username']);
    $user_email = escape($_POST['user_email']);
    $user_password = escape($_POST['user_password']);

    if (!empty($username) && !empty($user_email) && !empty($user_password)) {
      
        //password encryption (less encryption since we're already in admin. 
        //If they already have access to this, we're screwed anyway.)
        $user_password = password_hash($user_password, PASSWORD_BCRYPT, array('cost' => 10));


        $query = "INSERT INTO users(user_firstname, user_lastname, user_role, 
    username, user_email, user_password) ";

        $query .=
            "VALUES('{$user_firstname}','{$user_lastname}','{$user_role}','{$username}',
    '{$user_email}','{$user_password}') ";

        $create_user_query = mysqli_query($connection, $query);

        confirmQuery($create_user_query);
        // header("Location: users.php"); //refresh page

        echo "<h3> User Created: " . "<a href='users.php'>View users</a></h3> ";
    }
}

?>


<form action="" method="post" enctype="multipart/form-data">

    <div class="form-group">
        <label for="firstname">Firstname</label>
        <input type="text" class="form-control" name="user_firstname">
    </div>
    <div class="form-group">
        <label for="lastname">Lastname</label>
        <input type="text" class="form-control" name="user_lastname">
    </div>

    <div class="form-group">
        <select name="user_role" id="user_role">

            <option value="subscriber">Select Role</option>

            <option value="admin">Admin</option>
            <option value="subscriber">Subscriber</option>

        </select>
    </div>

    <!-- <div class="form-group">
        <label for="post_image">Post Image</label>
        <input type="file" name="image">
    </div> -->

    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" name="username">
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" name="user_email">
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" name="user_password">
    </div>

    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="create_user" value="Add User">
    </div>

</form>