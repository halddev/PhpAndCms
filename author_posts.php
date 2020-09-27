<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>

<!-- Navigation -->
<?php include "includes/navigation.php" ?>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Blog Entries Column -->
        <div class="col-md-8">

            <?php

            if (isset($_GET['p_id'])) {
                $the_post_id = escape($_GET['p_id']);
                $the_post_user = escape($_GET['author']);
            }
            ?>

            <h1 class="page-header">
                All posts by
                <small><?php echo $the_post_user; ?></small>
            </h1>

            <?php
            //pagination

            $per_page = 5;

            if (isset($_GET['page'])) {
                $page = $_GET['page'];
            } else {
                $page = 1;
            }

            if ($page == "" || $page == 1) {
                $page_1 = 0;
            } else {
                $page_1 = ($page * $per_page) - $per_page;
            }

            ?>

            <!-- Post data from db -->
            <?php


            $query = "SELECT * FROM posts WHERE post_user = '{$the_post_user}' AND post_status = 'published' ORDER BY post_id DESC";
            $count_posts_query = mysqli_query($connection, $query);
            $count = mysqli_num_rows($count_posts_query);

            if ($count < 1) {
                echo "<h2 class='text-center'>This user has yet to post anything.</h2>";
            } else {
                //Number of pages = number of posts/5 rounded up
                $count = ceil($count / $per_page);

                $query = "SELECT * FROM posts WHERE post_user = '{$the_post_user}' AND post_status = 'published' ORDER BY post_id DESC LIMIT $page_1, $per_page ";
                $select_all_posts_query = mysqli_query($connection, $query);

                while ($row = mysqli_fetch_assoc($select_all_posts_query)) {
                    $post_id = $row['post_id'];
                    $post_title = $row['post_title'];
                    $post_user = $row['post_user'];
                    $post_date = $row['post_date'];
                    $post_image = $row['post_image'];
                    $post_content = $row['post_content'];

            ?>



                    <!--Blog Posts-->
                    <h2>
                        <?php echo "<a href='post.php?p_id={$post_id}'>{$post_title}</a>"; ?>
                    </h2>
                    <p class="lead">
                        by <?php echo $post_user; ?>
                    </p>
                    <p><span class="glyphicon glyphicon-time"></span><?php echo $post_date; ?></p>
                    <hr>
                    <img class="img-responsive" src="images/<?php echo $post_image; ?>" alt="">
                    <hr>
                    <p><?php echo $post_content; ?></p>

                    <hr>

            <?php }
            } ?>



            <!-- Blog Comments -->
            <?php

            if (isset($_POST['create_comment'])) {

                $the_post_id = escape($_GET['p_id']);

                $comment_author = escape($_POST['comment_author']);
                $comment_email = escape($_POST['comment_email']);
                $comment_content = escape($_POST['comment_content']);

                if (
                    !empty($comment_author) && !empty($comment_email) &&
                    !empty($comment_content)
                ) {
                    $query = "INSERT INTO comments (comment_post_id, comment_author, comment_email, 
                comment_content, comment_status, comment_date) ";
                    $query .= "VALUES ($the_post_id, '{$comment_author}', '{$comment_email}', 
                '{$comment_content}', 'unapproved', now())";

                    $create_comment_query = mysqli_query($connection, $query);
                    if (!$create_comment_query) {
                        die('QUERY FAILED' . mysqli_error($connection));
                    }

                    $query = "UPDATE posts SET post_comment_count = post_comment_count + 1 ";
                    $query .= "WHERE post_id = $the_post_id ";
                    $update_comment_count = mysqli_query($connection, $query);
                } else {
                    echo "<script>alert('All fields are required');</script>";
                }
            }

            ?>


        </div>
        <br>




        <!-- Blog Sidebar Widgets Column -->
        <?php include "includes/sidebar.php"; ?>

    </div>
    <!-- /.row -->

    <hr>
    <!--Pagination-->
    <ul class="pager">
        <?php
        for ($i = 1; $i <= $count; $i++) {
            if ($i == $page) {
                echo "<li><a class='active_link' href='author_posts.php?author={$post_user}&p_id={$the_post_id}&page={$i}'>$i</a></li>";
            } else {
                echo "<li><a href='author_posts.php?author={$post_user}&p_id={$the_post_id}&page={$i}'>$i</a></li>";
            }
        }
        ?>
    </ul>
    
    <?php echo "<h1>{$the_post_user} and {$the_post_id} </h1>"; ?>

    <?php include "includes/footer.php"; ?>