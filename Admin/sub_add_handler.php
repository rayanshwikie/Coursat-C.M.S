<?php
include '../db_connection.php';
session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $subname = trim($_POST['subname']);
    $subprice = trim($_POST['price']);
    $maxc = trim($_POST['maxc']);
    $level = trim($_POST['level']);

    if ($subname === '' || $subprice === '' || $maxc === '' || $level === '') {
        $errors[] = "All fields are required.";
    }

    if ($subname !== '') {
        $stmt = $conn->prepare("SELECT id FROM subscription WHERE sub_name = ?");
        $stmt->bind_param("s", $subname);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "Name already used.";
        }

        $stmt->close();
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO subscription (sub_name, sub_price, sub_max, level) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdii", $subname, $subprice, $maxc, $level);
        if ($stmt->execute()) {
            $_SESSION['message'] = "Subscription added successfully!";
        } else {
            $errors[] = "Something went wrong while inserting.";
        }
        $stmt->close();
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
    }
}

header("Location: sub_add.php");
exit();
