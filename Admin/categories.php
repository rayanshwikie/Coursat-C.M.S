<?php
session_start();
include '../db_connection.php';
include 'check_admin.php';
check_admin();
include 'links.php';

if (!isset($_SESSION['toggle_categories'])) {
    $_SESSION['toggle_categories'] = false;
}
if (isset($_GET['toggle_categories'])) {
    $_SESSION['toggle_categories'] = !$_SESSION['toggle_categories'];
}
$toggle = $_SESSION['toggle_categories'];

$query = "SELECT * FROM categories";
$result = mysqli_query($conn, $query);

$categories = [];
while ($row = mysqli_fetch_assoc($result)) {
    $categories[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Categories</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>
<body class="hold-transition sidebar-mini layout-fixed">

<?php include 'nav.php'; ?>

<div class="wrapper" >

    <?php include 'navs/sidebar.php'; ?>

    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <h4>Manage Categories</h4>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">

                <div class="card bg-light">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <p class="mb-0">Categories Count: <?= count($categories); ?></p>
                        <form method="get">
                            <button type="submit" name="toggle_categories" value="1" class="btn btn-outline-dark btn-sm" title="Toggle Editing">
                               
                                <?= $toggle ? 'Disable Editing' : 'Toggle Editing' ?>
                            </button>
                        </form>
                    </div>

                    <div class="card-body">

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

                        <?php if (!empty($categories)): ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-secondary">
                                        <tr>
                                            <th>ID</th>
                                            <th>Category Name</th>
                                            <th>Category Icon</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($categories as $row): ?>
                                            <?php
                                                $editId = $row['id'];
                                                $categName = $row['categ_name'];
                                                $categc = $row['categ_icon'];
                                            ?>
                                            <tr>
                                                <form method="POST" action="categories_actions.php" enctype="multipart/form-data">
                                                    <td><?= $editId; ?></td>
                                                    <td>
                                                        <input type="hidden" name="edit_id" value="<?= $editId; ?>">
                                                        <input class="form-control" type="text" name="categname" value="<?= htmlspecialchars($categName); ?>" <?= !$toggle ? 'readonly' : '' ?> required>
                                                    </td>
                                                    <td>
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-text">
                                                                <?php
                                                                if ($categc) {
                                                                    echo '<img src="data:image/svg+xml;base64,' . base64_encode($categc) . '" width="30" height="30">';
                                                                } else {
                                                                    echo 'No Icon';
                                                                }
                                                                ?>
                                                            </span>
                                                            <input type="file" name="categic" class="form-control" accept=".svg" <?= !$toggle ? 'disabled' : '' ?>>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="submit" class="btn btn-primary btn-sm" name="update" title="Edit" <?= !$toggle ? 'disabled' : '' ?>>
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
                            <p class="text-center">No Categories found.</p>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </section>
    </div>
</div>


</body>
</html>
