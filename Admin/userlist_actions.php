<?php
include '../db_connection.php';
session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['update'])) {
        $name = trim($_POST['firstname'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $roles = $_POST['role'] ?? '';
        $editId = $_POST['edit_id'] ?? '';

        if (empty($editId) || empty($name) || empty($email) || empty($roles)) {
            $errors[] = "All fields are required.";
        } else {
            $sql = "UPDATE users SET username=?, email=?, role=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $name, $email, $roles, $editId);

            if ($stmt->execute()) {
                $_SESSION['message'] = 'Update successful!';
            } else {
                $errors[] = "Something went wrong during update.";
            }
            $stmt->close();
        }
    }

    if (isset($_POST['toggle_status'])) {
        $userId = $_POST['toggle_status_id'];

        if ($userId == $_SESSION['user_id']) {
            $errors[] = "You cannot disable the current active account.";
        } else {
            $query = "SELECT status FROM users WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();

            if ($row) {
                $currentStatus = $row['status'];
                $newStatus = ($currentStatus == 1) ? 0 : 1;

                $updateQuery = "UPDATE users SET status = ? WHERE id = ?";
                $stmt = $conn->prepare($updateQuery);
                $stmt->bind_param("ii", $newStatus, $userId);

                if ($stmt->execute()) {
                    $_SESSION['message'] = "User status updated.";
                } else {
                    $errors[] = "Failed to update user status.";
                }
                $stmt->close();
            } else {
                $errors[] = "User not found.";
            }
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
        }
        header('Location: userlist.php');
        exit();
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
    }

    header("Location: userlist.php");
    exit();
}
