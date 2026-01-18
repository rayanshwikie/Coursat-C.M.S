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
  <title>User Subscriptions</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
</head>
<body class="hold-transition sidebar-mini layout-fixed">
     <?php include 'nav.php'; ?>
<div class="wrapper">

 
  <?php include 'navs/sidebar.php'; ?>

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <h4 class="mb-2">User Subscriptions</h4>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">

        <?php
        if (!isset($_SESSION['toggle_subscriptions'])) {
            $_SESSION['toggle_subscriptions'] = False;
        }
        if (isset($_GET['toggle_subscriptions'])) {
            $_SESSION['toggle_subscriptions'] = !$_SESSION['toggle_subscriptions'];
        }
        $toggle = $_SESSION['toggle_subscriptions'];

        $limit = 10;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        $countQuery = "SELECT COUNT(*) AS total FROM sub_details";
        $countResult = mysqli_query($conn, $countQuery);
        $total = mysqli_fetch_assoc($countResult)['total'];
        $totalPages = ceil($total / $limit);

        $query = "
            SELECT sd.id, sd.user_id, sd.sub_ID, sd.status, sd.sub_date,
                   u.username, s.sub_name
            FROM sub_details sd
            LEFT JOIN users u ON sd.user_id = u.id
            LEFT JOIN subscription s ON sd.sub_ID = s.id
            ORDER BY sd.id DESC
            LIMIT $limit OFFSET $offset
        ";
        $result = mysqli_query($conn, $query);
        ?>

        <div class="card bg-light">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>User Subscriptions: <?= $total ?></span>
            <form method="get">
              <button class="btn btn-outline-dark btn-sm" name="toggle_subscriptions" value="1">
                <?= $toggle ? 'Disable Editing' : 'Toggle Editing' ?>
              </button>
            </form>
          </div>

          <div class="card-body">
            <?php if (isset($_SESSION['message'])): ?>
              <div class="alert alert-success"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
            <?php endif; ?>

            <div class="table-responsive">
              <table class="table table-bordered table-hover">
                <thead class="table-secondary">
                  <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Subscription</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                      <td><?= $row['id'] ?></td>
                      <td><?= htmlspecialchars($row['username']) ?></td>
                      <td><?= htmlspecialchars($row['sub_name']) ?></td>
                      <td><?= $row['sub_date'] ?></td>
                      <td><?= $row['status'] == 1 ? 'Active' : 'Disabled' ?></td>
                      <td class="text-center">
                        <form method="POST" action="subscriptions_actions.php">
                          <input type="hidden" name="sub_id" value="<?= $row['id'] ?>">
                          <button type="submit"
                                  name="toggle_subscription"
                                  class="btn btn-sm <?= $row['status'] == 1 ? 'btn-danger' : 'btn-success' ?>"
                                  <?= !$toggle ? 'disabled' : '' ?>>
                            <?= $row['status'] == 1 ? 'Disable' : 'Enable' ?>
                          </button>
                        </form>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>

            <?php if ($totalPages > 1): ?>
              <nav>
                <ul class="pagination justify-content-center">
                  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                      <a class="page-link" href="?page=<?= $i ?>&toggle_subscriptions=<?= $toggle ? 1 : 0 ?>"><?= $i ?></a>
                    </li>
                  <?php endfor; ?>
                </ul>
              </nav>
            <?php endif; ?>

          </div>
        </div>

      </div>
    </div>
  </div>
</div>


</body>
</html>
