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
  <title>Users List</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

</head>
<body class="hold-transition sidebar-mini layout-fixed">
<?php include 'nav.php'; ?>
<div class="wrapper">

  

  <?php include 'navs/sidebar.php'; ?>

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <h4 class="mb-2">Users Management</h4>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">

        <?php
        if (!isset($_SESSION['toggle'])) {
            $_SESSION['toggle'] = false;
        }
        if (isset($_GET['toggle'])) {
            $_SESSION['toggle'] = !$_SESSION['toggle'];
        }
        $toggle = $_SESSION['toggle'];

        $limit = 10;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        $countQuery = "SELECT COUNT(*) AS total FROM users";
        $countResult = mysqli_query($conn, $countQuery);
        $totalRows = mysqli_fetch_assoc($countResult)['total'];
        $totalPages = ceil($totalRows / $limit);

        $query = "SELECT * FROM users ORDER BY id ASC LIMIT $limit OFFSET $offset";
        $result = mysqli_query($conn, $query);
        $users = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }
        ?>

        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <p class="mb-0">Users Count: <?= $totalRows; ?></p>
            <form method="get" class="mb-0">
              <button type="submit" name="toggle" value="1" class="btn btn-outline-dark btn-sm">
                <?= $toggle ? 'Disable Editing' : 'Toggle Editing' ?>
              </button>
            </form>
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
            <?php if (!empty($users)): ?>
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <thead class="table-secondary">
                    <tr>
                      <th>Id</th>
                      <th>Username</th>
                      <th>Email</th>
                      <th>Role</th>
                      <th class="text-center">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($users as $row): ?>
                      <tr>
                        <form method="POST" action="userlist_actions.php">
                          <td><?= $row['id']; ?></td>
                          <td>
                            <input type="hidden" name="edit_id" value="<?= $row['id']; ?>">
                            <input class="form-control" type="text" name="firstname" value="<?= htmlspecialchars($row['username']); ?>" <?= !$toggle ? 'readonly' : '' ?> required>
                          </td>
                          <td>
                            <input class="form-control" type="email" name="email" value="<?= htmlspecialchars($row['email']); ?>" <?= !$toggle ? 'readonly' : '' ?> required>
                          </td>
                          <td>
                            <select class="form-select" name="role" <?= !$toggle ? 'disabled' : '' ?> required>
                              <option selected value="<?= $row['role']; ?>"><?= $row['role']; ?></option>
                              <option value="student">student</option>
                              <option value="admin">admin</option>
                            </select>
                          </td>
                          <td class="text-center">
                            <button type="submit" class="btn btn-primary btn-sm" name="update" <?= !$toggle ? 'disabled' : '' ?>>Update</button>
                            <input type="hidden" name="toggle_status_id" value="<?= $row['id']; ?>">
                            <button type="submit" class="btn btn-sm <?= $row['status'] == 1 ? 'btn-danger' : 'btn-success'; ?>" name="toggle_status" <?= !$toggle ? 'disabled' : '' ?>>
                              <?= $row['status'] == 1 ? 'Disable' : 'Enable'; ?>
                            </button>
                          </td>
                        </form>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>

              <?php if ($totalPages > 1): ?>
                <nav>
                  <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                      <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&toggle=<?= $toggle ? '1' : '0' ?>"><?= $i ?></a>
                      </li>
                    <?php endfor; ?>
                  </ul>
                </nav>
              <?php endif; ?>
            <?php else: ?>
              <p class="text-center">No users found.</p>
            <?php endif; ?>
          </div>
        </div>

      </div>
    </div>
  </div>

</div>


</body>
</html>
