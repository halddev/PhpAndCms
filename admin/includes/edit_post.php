<?php

if (isset($_GET['p_id'])) {
    $the_post_id = escape($_GET['p_id']);
}


$query = "SELECT * FROM posts WHERE post_id = $the_post_id ";
$select_posts_by_id = mysqli_query($connection, $query);
while ($row = mysqli_fetch_assoc($select_posts_by_id)) {
    $post_id = $row['post_id'];
    $post_user = $row['post_user'];
    $post_title = $row['post_title'];
    $post_category_id = $row['post_category_id'];
    $post_status = $row['post_status'];
    $post_image = $row['post_image'];
    $post_content = $row['post_content'];
    $post_tags = $row['post_tags'];
    $post_comment_count = $row['post_comment_count'];
    $post_date = $row['post_date'];
}

//Updating post when clicking "Update":
if (isset($_POST['update_post'])) {

    $post_user = escape($_POST['post_user']);
    $post_title = escape($_POST['post_title']);
    $post_category_id = escape($_POST['post_category']);
    $post_status = escape($_POST['post_status']);
    $post_image = escape($_FILES['image']['name']);
    $post_image_temp = escape($_FILES['image']['tmp_name']);
    $post_content = escape($_POST['post_content']);
    $post_tags = escape($_POST['post_tags']);

    //If no image is selected on "update", find pre-selected image in db:
    if (empty($post_image)) {

        $query = "SELECT * FROM posts WHERE post_id = $the_post_id ";
        $select_image = mysqli_query($connection, $query);

        while ($row = mysqli_fetch_array($select_image)) {

            $post_image = $row['post_image'];
        }
    }
    move_uploaded_file($post_image_temp, "../images/$post_image");

    $update_query = "UPDATE posts SET ";
    $update_query .= "post_title        =   '{$post_title}', ";
    $update_query .= "post_category_id  =   '{$post_category_id}', ";
    $update_query .= "post_date         =   now(), ";
    $update_query .= "post_user         =   '{$post_user}', ";
    $update_query .= "post_status       =   '{$post_status}', ";
    $update_query .= "post_tags         =   '{$post_tags}', ";
    $update_query .= "post_content      =   '{$post_content}', ";
    $update_query .= "post_image        =   '{$post_image}' ";
    $update_query .= "WHERE post_id     =    {$the_post_id} ";



    $update_post = mysqli_query($connection, $update_query);

    confirmQuery($update_post);

    Echo "<h4 class='bg-success'>Post Updated. <a href='../post.php?p_id={$the_post_id}'>View Post</a> or <a href='posts.php'>Edit More Posts</a></h4>";
    // header("Location: posts.php"); //refresh page
}

?>

<form action="" method="post" enctype="multipart/form-data">

    <div class="form-group">
        <label for="title">Post Title</label>
        <input value="<?php echo $post_title; ?>" type="text" class="form-control" name="post_title">
    </div>

    <div class="form-group">
        <label for="category">Category</label><br>
        <select name="post_category" id="post_category">

            <?php
         
            $query = "SELECT * FROM categories";
            $select_categories = mysqli_query($connection, $query);

            confirmQuery($select_categories);
            

            while ($row = mysqli_fetch_assoc($select_categories)) {
                $cat_id = $row['cat_id'];
                $cat_title = $row['cat_title'];

                
                if($cat_id == $post_category_id) {
                    echo "<option selected value='{$cat_id}'>{$cat_title}</option>";
                } else {
                    echo "<option value='{$cat_id}'>{$cat_title}</option>";
                }
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="user">User</label>
        <br>
        <select name="post_user" id="post_user">
            <option value='<?php echo $post_user; ?>'><?php echo $post_user; ?></option>

            <?php
            $query = "SELECT * FROM users";
            $select_users = mysqli_query($connection, $query);

            confirmQuery($select_users);

            while ($row = mysqli_fetch_assoc($select_users)) {
                // $user_id = $row['user_id'];
                $username = $row['username'];

                echo "<option value='{$username}'>{$username}</option>";
            }
            ?>

        </select>
    </div>

    <div class="form-group">
        <label for="status">Status</label><br>
        <select name="post_status">

            <option value='<?php echo $post_status; ?>'><?php echo $post_status; ?></option>
            <?php

            if ($post_status == 'published') {
                echo "<option value='draft'>Draft</option>";
            } else {
                echo "<option value='published'>Published</option>";
            }

            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="image">Image</label><br>
        <img width="100" src="../images/<?php echo $post_image; ?>">
        <input type="file" name="image">
    </div>

    <div class="form-group">
        <label for="post_tags">Post Tags</label>
        <input value="<?php echo $post_tags; ?>" type="text" class="form-control" name="post_tags">
    </div>

    <div class="form-group">
        <label for="post_content">Post Content</label>
        <textarea class="form-control" name="post_content" id="body" cols="30" rows="10"><?php echo $post_content; ?>         
    </textarea>
    </div>

    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="update_post" value="Update Post">
    </div>

</form>