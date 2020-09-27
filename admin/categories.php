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

                    <!--Forms on left-->
                    <div class="col-xs-6">

                        <?php insert_categories(); ?>

                        <!--ADD FORM-->
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="cat-title">Add Category:</label>
                                <input type="text" class="form-control" name="cat_title">
                            </div>
                            <div class="form-group">
                                <input class="btn btn-primary" type="submit" name="submit" value="Add category">
                            </div>
                        </form>


                        <?php //INCLUDE UPDATE-SECTION
                        renderUpdateSection();
                        ?>

                    </div>

                    <!--Table-->
                    <div class="col-xs-6">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Category Title</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php findAllCategories(); ?>

                                <?php
                                deleteCategories();
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

        <?php include "includes/admin_footer.php"; ?>