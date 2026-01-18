<?php
session_start();
include '../db_connection.php';

if (!isset($_SESSION['loggedInStatus'])) {
    header('Location: ../register.php');
    exit();
}

$errors = [];

if (isset($_POST['add'])) {
    $subscription_id = $_POST['subscription_id'] ?? null;
    $coursename = trim($_POST['coursename'] ?? '');
    $coursedesc = trim($_POST['cdesc'] ?? ''); 
    $categid = $_POST['categid'] ?? '';
    $is_private = isset($_POST['is_private']) ? 1 : 0;

    $attachment_path = "";
    if (!empty($_FILES['course_attachment']['name'])) {
        $attachment_dir = "../uploads/";
        $attachment_name = basename($_FILES["course_attachment"]["name"]);
        $attachment_file = $attachment_dir . $attachment_name;
        $ext = strtolower(pathinfo($attachment_file, PATHINFO_EXTENSION));
        $allowed = ["pdf"];

        if (in_array($ext, $allowed)) {
            if (!is_dir($attachment_dir)) mkdir($attachment_dir, 0777, true);
            if (move_uploaded_file($_FILES["course_attachment"]["tmp_name"], $attachment_file)) {
                $attachment_path = $attachment_name;
            } else {
                $errors[] = "Failed to upload the attachment file.";
            }
        } else {
            $errors[] = "Only PDF attachments are allowed.";
        }
    }

    $img_path = "";
    if (!empty($_FILES['course_img']['name'])) {
        $target_dir = "../uploads/";
        $img_name = basename($_FILES["course_img"]["name"]);
        $target_file = $target_dir . $img_name;
        $ext = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed = ["jpg", "jpeg", "png", "gif"];

        if (in_array($ext, $allowed)) {
            if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
            if (move_uploaded_file($_FILES["course_img"]["tmp_name"], $target_file)) {
                $img_path = $img_name;
            } else {
                $errors[] = "Failed to upload the image.";
            }
        } else {
            $errors[] = "Only JPG, JPEG, PNG, and GIF image formats are allowed.";
        }
    } else {
        $errors[] = "Course image is required.";
    }

    if (!empty($coursename)) {
        $stmt = $conn->prepare("SELECT course_name FROM courses WHERE  course_name = ?");
        $stmt->bind_param("s", $coursename);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows > 0) {
            $stmt->close();
            $errors[] = "Course name already exists.";
        }
    } else {
        $errors[] = "Course name cannot be empty.";
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location:course_add.php");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO courses (course_name, description, categ_id, img, attachment, is_private, subscription_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssissii", $coursename, $coursedesc, $categid, $img_path, $attachment_path, $is_private, $subscription_id);
    $stmt->execute();
    $_SESSION['message'] = "Course Added successfully";
    $stmt->close();
    $conn->close();

    header("Location:course_add.php");
    exit();
}
