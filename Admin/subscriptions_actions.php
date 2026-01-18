<?php
include '../db_connection.php';
session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_subscription'], $_POST['sub_id'])) {
    $id = intval($_POST['sub_id']);

    $check = mysqli_query($conn, "SELECT status FROM sub_details WHERE id = $id");

    if ($check && mysqli_num_rows($check) > 0) {
        $row = mysqli_fetch_assoc($check);
        $newStatus = $row['status'] == 1 ? 0 : 1;

        $updateQuery = "UPDATE sub_details SET status = $newStatus WHERE id = $id";

        $update = mysqli_query($conn, $updateQuery);

        if ($update) {
            $_SESSION['message'] = "Subscription status updated.";
        } else {
            $_SESSION['message'] = "❌ Update failed.";
        }
    } else {
        $_SESSION['message'] = "❗ Subscription not found.";
    }
} else {
    $_SESSION['message'] = "🚫 Invalid request.";
}

header("Location: subscriptions.php");
exit();
