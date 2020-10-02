<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>

<!-- Navigation -->
<?php include "includes/navigation.php" ?>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Blog Entries Column -->
        <div class="col-md-8">

            <!-- Post data from db -->
            <?php

            //set $search variable upon button-click
            if (isset($_POST['submit'])) {

                $search = escape($_POST['search']);
                //'%search%' is SQL's way of writing variables
                $query = "SELECT * FROM posts WHERE post_tags LIKE '%$search%' ";
                $search_query = mysqli_query($connection, $query);

                if (!$search_query) {
                    die("QUERY FAILED" . mysqli_error($connection));
                }

                $count = mysqli_num_rows($search_query);

                if ($count == 0) {
                    echo "<h1>No result</h1>";
                } else {
                    
                    while ($row = mysqli_fetch_assoc($search_query)) {
                        $post_id = $row['post_id'];
                        $post_title = $row['post_title'];
                        $post_user = $row['post_user'];
                        $post_date = $row['post_date'];
                        $post_image = $row['post_image'];
                        $post_content = $row['post_content'];

            ?>

                        <h1 class="page-header">
                            Page Heading
                            <small>Secondary Text</small>
                        </h1>

                        <!--Blog Post-->
                    <h2>
                        <a href="post/<?php echo $post_id; ?>"><?php echo $post_title; ?></a>
                    </h2>
                    <p class="lead">
                        by <a href="author_posts.php?author=<?php echo $post_user; ?>&p_id=<?php echo $post_id; ?>"><?php echo $post_user; ?></a>
                    </p>
                    <p><span class="glyphicon glyphicon-time"></span><?php echo $post_date; ?></p>
                    <hr>
                    <a href="post.php?p_id=<?php echo $post_id; ?>"><img class="img-responsive" src="images/<?php echo $post_image; ?>" alt=""></a>
                    <hr>
                    <p><?php echo $post_content; ?></p>
                    <a class="btn btn-primary" href="post.php?p_id=<?php echo $post_id; ?>">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>

                    <hr>

            <?php
                    }
                }
            }
            ?>
        </div>





        <!-- Blog Sidebar Widgets Column -->
        <?php include "includes/sidebar.php"; ?>

    </div>
    <!-- /.row -->

    <hr>


    <?php include "includes/footer.php"; ?>