<?php
include '../db_connection.php';
include 'links.php';

session_start();
if (!isset($_SESSION['loggedInStatus'])) {
    header('Location: ../register.php');
    exit();
}
?>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/alertify.min.js"></script>
<?php include 'nav.php'?>
<div class="wrapper">
<?php include 'navs/sidebar.php'?>
<div class="content-wrapper">
<div class="container-fluid ">
    <div class="content">
    <div class="card bg-light">
        <div class="card-header">
            <h2 class="mb-0 text-dark">Add Course</h2>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data" action="course_add_handler.php">
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


                <div class="mb-3">
                    <label class="form-label">Course Name</label>
                    <input type="text" name="coursename" class="form-control"required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Course Description</label>
                    <textarea class="form-control" name="cdesc" rows="3"></textarea>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_private" id="is_private">
                    <label class="form-check-label fw-bold form-label" for="is_private">
                        Make this Course Private
                    </label>
                </div>

                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select class="form-select" name="categid"required>
                        <option value="">Select category</option >
                        <?php
                        $cquery = "SELECT * FROM categories";
                        $cres = mysqli_query($conn, $cquery);
                        while ($row = mysqli_fetch_assoc($cres)) {
                            echo '<option value="' . $row['id'] . '">' . $row['categ_name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Subscription</label>
                    <select class="form-select" name="subscription_id"required>
                        <option value="">Select subscription</option>
                        <?php
                        $squery = "SELECT * FROM subscription";
                        $sres = mysqli_query($conn, $squery);
                        while ($row = mysqli_fetch_assoc($sres)) {
                            echo '<option value="' . $row['id'] . '">' . $row['sub_name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Course Image(JPG,PNG)</label>
                    <input type="file" name="course_img" class="form-control"required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Attachment (PDF)</label>
                    <input type="file" name="course_attachment" class="form-control"required>
                </div>

                <button type="submit" name="add" class="btn btn-primary">Add</button>
            </form>
        </div>
    </div>
</div></div></div></div>

</body>
</html>
