<?php
session_start();
include("navs/sidebar.php");
include("../db_connection.php");
include("links.php");

if (!isset($_SESSION['toggle_enr'])) {
            $_SESSION['toggle_enr'] = False;
        }
        if (isset($_GET['toggle_enr'])) {
            $_SESSION['toggle_enr'] = !$_SESSION['toggle_enr'];
        }
        $toggle = $_SESSION['toggle_enr'];
?>
<html>
  <head>

  </head>
  <body class="hold-transition sidebar-mini layout-fixed">
<div class="content-wrapper">
  <?php include "nav.php" ?>
  <section class="content-header">
    <h1>Enrollments</h1>
  </section>

  <section class="content">
    <?php if (isset($_SESSION['success'])): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
    <?php endif; ?>
 <?php
        // Fetch enrollment data
        $query = "SELECT e.id, u.username AS user_name, c.course_name AS course_title, s.sub_name AS subscription_name , e.status AS stat
                  FROM enrollments e
                  JOIN users u ON e.user_id = u.id
                  JOIN courses c ON e.course_id = c.id
                  JOIN subscription s ON c.subscription_id = s.id";
        $result = mysqli_query($conn, $query);
        ?>
    <div class="card bg-light">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>User Enrollments: <?php echo mysqli_num_rows($result);?> </span>
            <form method="get">
              <button class="btn btn-outline-dark btn-sm" name="toggle_enr" value="1">
                <?= $toggle ? 'Disable Editing' : 'Toggle Editing' ?>
              </button>
            </form>
          </div>
          <div class="card-body">
            <?php if (isset($_SESSION['errors'])): ?>
              <?php foreach ($_SESSION['errors'] as $error): ?>
                <div class="alert alert-warning"><?= $error ?></div>
              <?php endforeach; unset($_SESSION['errors']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['message'])): ?>
              <div class="alert alert-success"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
            <?php endif; ?>
      <div class=" table-responsive ">
       

        <?php if ($result && mysqli_num_rows($result) > 0): ?>
          <table class="table table-bordered table-striped table-hove">
            <thead class="table-secondary">
              <tr>
                <th>ID</th>
                <th>User</th>
                <th>Course</th>
                <th>Course Subscription</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = mysqli_fetch_assoc($result)): ?>
              <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['user_name']) ?></td>
                <td><?= htmlspecialchars($row['course_title']) ?></td>
                <td><?= htmlspecialchars($row['subscription_name']) ?></td>
                <td>
                  <form method="POST" action="enrollment_actions.php" style="display:inline;">
                    <input type="hidden" name="enrollment_id" value="<?= $row['id'] ?>">
                     <button type="submit"
                                  class="btn btn-sm <?= $row['stat'] == 1 ? 'btn-danger' : 'btn-success' ?>"
                                  <?= !$toggle ? 'disabled' : '' ?>>
                            <?= $row['stat'] == 1 ? 'Finish' : 'Enable' ?>
                          </button>
                  </form>
                </td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p>No enrollments found.</p>
        <?php endif; ?>
      </div>
    </div>
    </div>
  </section>
</div></body>
</html>
