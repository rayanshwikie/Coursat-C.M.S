<?php
include '../db_connection.php';
session_start();
if (!isset($_SESSION['loggedInStatus'])) {
    header('Location: ../register.php');
    exit();
}
include 'links.php';

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <?php include 'nav.php'; ?>

    <div class="wrapper">
        <!-- Sidebar -->
        <?php include 'navs/sidebar.php'; ?>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <h4 class="mb-2">Add Category</h4>
                </div>
            </div>

            <!-- Content Body -->
            <div class="content">
                <div class="container-fluid">
                    <div class="card bg-light">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <p class="mb-0 text-dark">Add New Category</p>
                        </div>

                        <div class="card-body">
                        <?php
if (isset($_SESSION['message'])) {
    echo '<div class="alert alert-success">' . $_SESSION['message'] . '</div>';
    unset($_SESSION['message']);
}

if (isset($_SESSION['errors']) && is_array($_SESSION['errors'])) {
    echo '<div class="alert alert-warning"><ul class="mb-0">';
    foreach ($_SESSION['errors'] as $error) {
        echo '<li>' . htmlspecialchars($error) . '</li>';
    }
    echo '</ul></div>';
    unset($_SESSION['errors']);
}
?>

                            <form action="categ_add_handler.php" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="category_name" class="form-label">Category Name</label>
                                    <input type="text" name="category_name" id="category_name" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="category_icon" class="form-label">Upload SVG Icon</label>
                                    <input type="file" name="category_icon" id="category_icon" class="form-control" accept=".svg">
                                </div>

                                <button type="submit" name="add_category" class="btn btn-primary">Add Category</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/alertify.min.js"></script>
</body>
</html>
