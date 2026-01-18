<?php
include '../db_connection.php';
session_start();
include 'links.php';

if (!isset($_SESSION['loggedInStatus'])) {
    header('Location: ../register.php');
    exit();
}

if (!isset($_SESSION['toggle_subs'])) {
    $_SESSION['toggle_subs'] = false;
}
if (isset($_GET['toggle_subs'])) {
    $_SESSION['toggle_subs'] = !$_SESSION['toggle_subs'];
}
$toggle = $_SESSION['toggle_subs'];

$subscriptions = [];
$result = mysqli_query($conn, "SELECT * FROM subscription");
while ($row = mysqli_fetch_assoc($result)) {
    $subscriptions[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manage Subscriptions</title>

</head>
<body class="hold-transition sidebar-mini layout-fixed">

<?php include 'nav.php'; ?>

<div class="wrapper" style="">

  <?php include 'navs/sidebar.php'; ?>

  <div class="content-wrapper">

    <section class="content-header">
      <div class="container-fluid">
        <h4 class="mb-2">Manage Subscriptions</h4>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="card bg-light text-dark">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>Subscriptions Count: <?= count($subscriptions) ?></span>
            <form method="get">
              <button type="submit" name="toggle_subs" value="1" class="btn btn-outline-dark btn-sm">
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

            <?php if ($subscriptions): ?>
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <thead class="table-secondary">
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Price</th>
                      <th>Max Courses</th>
                      <th>Level</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($subscriptions as $sub): ?>
                      <tr>
                        <form method="POST" action="subscription_actions.php">
                          <input type="hidden" name="edit_id" value="<?= $sub['id']; ?>">
                          <td><?= $sub['id'] ?></td>
                          <td>
                            <input type="text" name="subname" class="form-control"
                                   value="<?= htmlspecialchars($sub['sub_name']) ?>"
                                   <?= !$toggle ? 'readonly' : '' ?> required>
                          </td>
                          <td>
                            <div class="input-group">
                              <input type="number" name="price" class="form-control"
                                     value="<?= $sub['sub_price'] ?>"
                                     <?= !$toggle ? 'readonly' : '' ?> required>
                              <span class="input-group-text">$</span>
                            </div>
                          </td>
                          <td>
                            <input type="number" name="max" class="form-control"
                                   value="<?= $sub['sub_max'] ?>"
                                   <?= !$toggle ? 'readonly' : '' ?> required>
                          </td>
                          <td>
                            <input type="number" name="level" class="form-control"
                                   value="<?= $sub['level'] ?>"
                                   <?= !$toggle ? 'readonly' : '' ?> required>
                          </td>
                          <td class="text-center">
                            <button type="submit" name="update" class="btn btn-primary btn-sm"
                                    <?= !$toggle ? 'disabled' : '' ?> title="Save Changes">
                              Update
                            </button>
                          </td>
                        </form>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php else: ?>
              <p class="text-center">No subscriptions found.</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </section>

  </div>
</div>


</body>
</html>
