<?php
include '../db_connection.php';
session_start();

$errors = [];

if (isset($_POST['update'])) {
    $name = $_POST['categname'];
    $editId = $_POST['edit_id'];

    $fetchQuery = "SELECT categ_icon FROM categories WHERE id='$editId'";
    $fetchResult = mysqli_query($conn, $fetchQuery);
    $existingIcon = mysqli_fetch_assoc($fetchResult)['categ_icon'];

    if (!empty($_FILES['categic']['tmp_name']) && is_uploaded_file($_FILES['categic']['tmp_name'])) {
        $fileType = mime_content_type($_FILES['categic']['tmp_name']);
        if ($fileType === 'image/svg+xml') {
            $fileData = mysqli_real_escape_string($conn, file_get_contents($_FILES['categic']['tmp_name']));
        } else {
            array_push($errors, "Only SVG files are allowed.");
            $fileData = $existingIcon;
        }
    } else {
        $fileData = $existingIcon;
    }

    if (empty($editId) || empty($name)) {
        array_push($errors, "All fields are required.");
    }

    if (count($errors) > 0) {
        $_SESSION['errors'] = $errors;
    } else {
        $sql = "UPDATE categories SET categ_name='$name', categ_icon='$fileData' WHERE id=$editId";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = "Update Successful!";
        } else {
            $_SESSION['errors'] = ["Something went wrong during update."];
        }
    }

    header("Location: categories.php");
    exit();
}


