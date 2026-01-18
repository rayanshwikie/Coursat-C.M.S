<?php
session_start();
include '../db_connection.php';

if (!isset($_SESSION['loggedInStatus'])) {
    header('Location: ../register.php');
    exit();
}

if (isset($_POST['update'])) {
    $cname = mysqli_real_escape_string($conn, $_POST['cname']);
    $cdesc = mysqli_real_escape_string($conn, $_POST['cdesc'] ?? '');
    $ccateg = mysqli_real_escape_string($conn, $_POST['ccateg']);
    $editId = mysqli_real_escape_string($conn, $_POST['update_id']);

    $is_private = isset($_POST['is_private']) ? 1 : 0;
    $subscription_id = !empty($_POST['subscription_id']) ? intval($_POST['subscription_id']) : 'NULL';

    $old_image = mysqli_real_escape_string($conn, $_POST['old_image']);
    $old_attachment = mysqli_real_escape_string($conn, $_POST['old_attachment']);

    $course_image = $_FILES['course_image']['name'] ?? '';
    $course_attachment = $_FILES['course_attachment']['name'] ?? '';

    $upload_dir = "../uploads/";
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    $new_image = $old_image;
    if (!empty($course_image)) {
        $new_image = basename($course_image);
        move_uploaded_file($_FILES['course_image']['tmp_name'], $upload_dir . $new_image);
    }

    $new_attachment = $old_attachment;
    if (!empty($course_attachment)) {
        $new_attachment = basename($course_attachment);
        move_uploaded_file($_FILES['course_attachment']['tmp_name'], $upload_dir . $new_attachment);
    }

    if (empty($editId) || empty($cname) || empty($ccateg)) {
        $_SESSION['errors'][] = "Course name and category are required.";
    } else {
        $sql = "UPDATE courses SET 
                    course_name='$cname', 
                    description='$cdesc', 
                    categ_id='$ccateg', 
                    img='$new_image',
                    attachment='$new_attachment',
                    is_private='$is_private',
                    subscription_id=" . ($subscription_id !== 'NULL' ? "'$subscription_id'" : "NULL") . "
                WHERE id='$editId'";

        if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = "update successful!";
        } else {
            $_SESSION['errors'][] = "Error: " . mysqli_error($conn);
        }
    }
}

header("Location: course.php");
exit();
