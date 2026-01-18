<?php
session_start();
include '../db_connection.php';
include 'check_admin.php';
check_admin();
include 'links.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Subscription</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
 
</head>
<body class="hold-transition sidebar-mini layout-fixed">

<?php include 'nav.php'; ?>

<div class="wrapper" >

  <?php include 'navs/sidebar.php'; ?>

  <div class="content-wrapper">

    <section class="content-header">
      <div class="container-fluid">
        <h4 class="mb-2">Add Subscription</h4>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">

        <div class="card bg-light text-dark p-3">

          <?php
          if (isset($_SESSION['errors'])) {
              foreach ($_SESSION['errors'] as $error) {
                  echo '<div class="alert alert-warning">' . $error . '</div>';
              }
              unset($_SESSION['errors']);
          }

          if (isset($_SESSION['message'])) {
              echo '<div class="alert alert-success">' . $_SESSION['message'] . '</div>';
              unset($_SESSION['message']);
          }
          ?>

          <form method="post" action="sub_add_handler.php">
              <div class="mb-3">
                  <label for="subname" class="form-label">Subscription Name</label>
                  <input type="text" name="subname" class="form-control" id="subname" required>
              </div>

              <div class="mb-3">
                  <label for="price" class="form-label">Subscription Price</label>
                  <input type="number" name="price" class="form-control" id="price" required>
              </div>

              <div class="mb-3">
                  <label for="maxc" class="form-label">Maximum Active Courses</label>
                  <input type="number" name="maxc" class="form-control" id="maxc" required>
              </div>

              <div class="mb-3">
                  <label for="level" class="form-label">Subscription Level</label>
                  <input type="number" name="level" class="form-control" id="level" required>
              </div>

              <button type="submit" name="add" class="btn btn-primary">Add Subscription</button>
          </form>

        </div>

      </div>
    </section>

  </div>
</div>

</body>
</html>
