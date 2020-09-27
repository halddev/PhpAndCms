<?php include "includes/admin_header.php"; ?>

<?php 

if(!is_admin($_SESSION['username'])){
    header("Location: index.php");
}

?>

<div id="wrapper">

    <!--Navigation-->
    <?php include "includes/admin_navigation.php"; ?>

    <div id="page-wrapper">

        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">

                    <h1 class="page-header">
                        Welcome to Admin
                        <small>Author Name</small>
                    </h1>

                    <?php
                    
                    //Using switch and $_GET to determine content to show:
                    if(isset($_GET['source'])){
                        $source = escape($_GET['source']);
                    } else {

                        $source = ''; //avoid errors when $_GET is mull

                    }
                    
                    switch($source) {

                        case 'add_post';
                            include "includes/add_post.php";
                        break;
                    
                        case 'edit_post';
                            include "includes/edit_post.php";
                        break;
                    
                        default;
                            include "includes/view_all_comments.php";
                        break;
                    }




                    ?>




                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

        <?php include "includes/admin_footer.php"; ?>