<?php
session_start();
include '../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enrollment_id'])) {
    $id = intval($_POST['enrollment_id']);

    if ($id > 0) {
        $query = "SELECT status FROM enrollments WHERE id = $id";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $currentStatus = $row['status'];
            $newStatus = ($currentStatus == 1) ? 0 : 1;

            $update = mysqli_query($conn, "UPDATE enrollments SET status = $newStatus WHERE id = $id");

            $_SESSION['message'] = $update ? "Enrollment status updated." : "Failed to update status.";
        } else {
            $_SESSION['message'] = "Enrollment not found.";
        }
    } else {
        $_SESSION['message'] = "Invalid enrollment ID.";
    }
} else {
    $_SESSION['message'] = "Invalid request.";
}

header("Location: enrollments.php");
exit();
