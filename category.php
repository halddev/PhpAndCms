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

            <!-- 'Posts'-data from db -->
            <?php

            if (isset($_GET['category'])) {
                $post_category_id = escape($_GET['category']);

                if (is_admin($_SESSION['username'])) {

                    //Fetching posts using prepared statements
                    $stmt1 = mysqli_prepare($connection, "SELECT post_id, post_title, post_user, post_date, post_image, 
                             post_content FROM posts WHERE post_category_id = ?");
                } else {
                    $stmt2 = mysqli_prepare($connection, "SELECT SELECT post_id, post_title, post_user, post_date, 
                             post_image, post_content FROM posts WHERE post_category_id = ? AND post_status = ? ");

                    $published = 'published';
                }


                if (isset($stmt1)) {

                    //bind params in $stmt1, param is of type int (i), and the param is $post_category_id.
                    mysqli_stmt_bind_param($stmt1, "i", $post_category_id);

                    //executing the statement
                    mysqli_stmt_execute($stmt1);

                    //Bind the result of the statement to variables
                    mysqli_stmt_bind_result(
                        $stmt1,
                        $post_id,
                        $post_title,
                        $post_user,
                        $post_date,
                        $post_image,
                        $post_content
                    );

                    $stmt = $stmt1;
                } else {
                    //2 params, one is type "int", other is "string", hence "is"
                    mysqli_stmt_bind_param($stmt2, "is", $post_category_id, $published);

                    mysqli_stmt_execute($stmt2);

                    mysqli_stmt_bind_result(
                        $stmt2,
                        $post_id,
                        $post_title,
                        $post_user,
                        $post_date,
                        $post_image,
                        $post_content
                    );

                    $stmt = $stmt2;
                }

                $count = mysqli_stmt_num_rows($stmt);


                //-------------------Heading-----------------------------

                // $title_query = "SELECT cat_title FROM categories WHERE cat_id = $post_category_id ";
                // $title_query = mysqli_query($connection, $title_query);
                // $cat_title = mysqli_fetch_assoc($title_query)['cat_title'];
                //$cat_title = $row['cat_title'];
            ?>

                <h1 class="page-header">
                    Posts in
                    <small><?php echo $cat_title; ?></small>
                </h1>


                <?php

                if ($count === 0) {
                    echo "<h2 class='text-center'>No posts available</h2>";
                }

                //Number of pages = number of posts/5 rounded up
                $count = ceil($count / $per_page);

                $query .= "ORDER BY post_id DESC LIMIT $page_1, $per_page ";
                $show_posts_query = mysqli_query($connection, $query);

                while (mysqli_stmt_fetch($stmt)) :


                ?>

                    <!--------Blog Posts------------>
                    <h2>
                        <a href="post.php?p_id=<?php echo $post_id; ?>"><?php echo $post_title; ?></a>
                    </h2>
                    <p class="lead">
                        by <a href="author_posts.php?author=<?php echo $post_user; ?>&p_id=<?php echo $post_id; ?>"><?php echo $post_user; ?></a>
                    </p>
                    <p><span class="glyphicon glyphicon-time"></span><?php echo $post_date; ?></p>
                    <hr>
                    <img class="img-responsive" src="/cms/images/<?php echo $post_image; ?>" alt="">
                    <hr>
                    <p><?php echo $post_content; ?></p>
                    <a class="btn btn-primary" href="#">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>

                    <hr>
            <?php
                endwhile;
                mysqli_stmt_close($stmt);//close statement and db connection
            } else {

                header("Location: index.php");
            }

            ?>

        </div>





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
                echo "<li><a class='active_link' href='category.php?category={$post_category_id}&page={$i}'>$i</a></li>";
            } else {
                echo "<li><a href='category.php?category={$post_category_id}&page={$i}'>$i</a></li>";
            }
        }
        ?>
    </ul>

    <?php include "includes/footer.php"; ?>