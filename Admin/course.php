<?php
include '../db_connection.php';
session_start();
include 'links.php';

if (!isset($_SESSION['loggedInStatus'])) {
    header('Location: ../register.php');
    exit();
}

if (!isset($_SESSION['toggle_courses'])) {
    $_SESSION['toggle_courses'] = false;
}
if (isset($_GET['toggle_courses'])) {
    $_SESSION['toggle_courses'] = !$_SESSION['toggle_courses'];
}
$toggle = $_SESSION['toggle_courses'];

$query = "SELECT c.*, s.sub_name FROM courses c LEFT JOIN subscription s ON c.subscription_id = s.id";
$result = mysqli_query($conn, $query);
$courses = [];
while ($row = mysqli_fetch_assoc($result)) {
    $courses[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Courses</title>

</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <?php include 'nav.php'; ?>

    <div class="wrapper">
        <?php include 'navs/sidebar.php'; ?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <h4 class="mb-2">Manage Courses</h4>
                </div>
            </div>

            <div class="content">
                <div class="container-fluid">
                    <div class="card bg-light">
                        <div class="card-header d-flex justify-content-between">
                            <p class="mb-0">Courses Count: <?= count($courses); ?></p>
                            <form method="get">
                                <button type="submit" name="toggle_courses" class="btn btn-outline-dark btn-sm">Toggle Edit</button>
                            </form>
                        </div>

                        <div class="card-body">
                            <?php
                            if (isset($_SESSION['errors'])) {
                                foreach ($_SESSION['errors'] as $error) {
                                    echo "<div class='alert alert-danger'>" . htmlspecialchars($error) . "</div>";
                                }
                                unset($_SESSION['errors']);
                            }
                            if (isset($_SESSION['message'])) {
                                echo "<div class='alert alert-success'>" . htmlspecialchars($_SESSION['message']) . "</div>";
                                unset($_SESSION['message']);
                            }
                            ?>

                            <?php if (!empty($courses)): ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Image</th>
                                            <th>Attachment</th>
                                            <th>Category</th>
                                            <th>Private</th>
                                            <th>Subscription</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($courses as $row): ?>
                                            <tr>
                                                <form method="POST" enctype="multipart/form-data" action="course_actions.php">
                                                    <td><?= $row['id'] ?></td>
                                                    <td>
                                                        <input type="hidden" name="update_id" value="<?= $row['id'] ?>">
                                                        <input type="text" name="cname" class="form-control" value="<?= htmlspecialchars($row['course_name']) ?>" <?= !$toggle ? 'readonly' : '' ?> required>
                                                    </td>
                                                    <td>
                                                        <textarea name="cdesc" class="form-control" <?= !$toggle ? 'readonly' : '' ?>><?= htmlspecialchars($row['description']) ?></textarea>
                                                    </td>
                                                    <td>
                                                        <?php if ($row['img']): ?>
                                                            <a href="../uploads/<?= $row['img'] ?>" target="_blank"><?= $row['img'] ?></a><br>
                                                        <?php endif; ?>
                                                        <input type="file" name="course_image" class="form-control" <?= !$toggle ? 'disabled' : '' ?>>
                                                        <input type="hidden" name="old_image" value="<?= $row['img'] ?>">
                                                    </td>
                                                    <td>
                                                        <?php if ($row['attachment']): ?>
                                                            <a href="../uploads/<?= $row['attachment'] ?>" target="_blank"><?= $row['attachment'] ?></a><br>
                                                        <?php endif; ?>
                                                        <input type="file" name="course_attachment" class="form-control" <?= !$toggle ? 'disabled' : '' ?>>
                                                        <input type="hidden" name="old_attachment" value="<?= $row['attachment'] ?>">
                                                    </td>
                                                    <td>
                                                        <select name="ccateg" class="form-select" <?= !$toggle ? 'disabled' : '' ?> required>
                                                            <?php
                                                            $categs = mysqli_query($conn, "SELECT * FROM categories");
                                                            while ($c = mysqli_fetch_assoc($categs)): ?>
                                                                <option value="<?= $c['id'] ?>" <?= $row['categ_id'] == $c['id'] ? 'selected' : '' ?>>
                                                                    <?= htmlspecialchars($c['categ_name']) ?>
                                                                </option>
                                                            <?php endwhile; ?>
                                                        </select>
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="checkbox" name="is_private" <?= $row['is_private'] ? 'checked' : '' ?> <?= !$toggle ? 'disabled' : '' ?>>
                                                    </td>
                                                    <td>
                                                        <select name="subscription_id" class="form-select" <?= !$toggle ? 'disabled' : '' ?>>
                                                            <option value="">None</option>
                                                            <?php
                                                            $subs = mysqli_query($conn, "SELECT * FROM subscription");
                                                            while ($s = mysqli_fetch_assoc($subs)): ?>
                                                                <option value="<?= $s['id'] ?>" <?= $row['subscription_id'] == $s['id'] ? 'selected' : '' ?>>
                                                                    <?= htmlspecialchars($s['sub_name']) ?>
                                                                </option>
                                                            <?php endwhile; ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <button type="submit" name="update" class="btn btn-primary btn-sm" <?= !$toggle ? 'disabled' : '' ?>>Update</button>
                                                    </td>
                                                </form>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php else: ?>
                                <p>No courses found.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  
</body>
</html>
