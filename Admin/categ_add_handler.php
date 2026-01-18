<?php
session_start();
include '../db_connection.php';

if (!isset($_SESSION['loggedInStatus'])) {
    header('Location: ../register.php');
    exit();
}

$errors = [];

if (isset($_POST['add_category'])) {
    $category_name = isset($_POST['category_name']) ? trim($_POST['category_name']) : '';

    if (empty($category_name)) {
        $errors[] = "Category name is required.";
    }

    if (!empty($category_name)) {
        $stmt = $conn->prepare("SELECT id FROM categories WHERE  categ_name = ?");
        $stmt->bind_param("s", $category_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errors[] = "Category name already exists.";
        }
        $stmt->close();
    }

    $category_icon_content = null;
    if (!empty($_FILES['category_icon']['name'])) {
        $icon_file = $_FILES['category_icon']['tmp_name'];
        $icon_file_type = strtolower(pathinfo($_FILES['category_icon']['name'], PATHINFO_EXTENSION));

        if ($icon_file_type !== 'svg') {
            $errors[] = "Only SVG files are allowed for the icon.";
        } else {
            $svg_content = file_get_contents($icon_file);
            if ($svg_content === false) {
                $errors[] = "Failed to read the SVG file content.";
            } else {
                $category_icon_content = $svg_content;
            }
        }
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: categ_add.php');
        exit();
    }

    if ($category_icon_content !== null) {
        $stmt = $conn->prepare("INSERT INTO categories (categ_name, categ_icon) VALUES (?, ?)");
        $stmt->bind_param("ss", $category_name, $category_icon_content);
    } else {
        $stmt = $conn->prepare("INSERT INTO categories (categ_name) VALUES (?)");
        $stmt->bind_param("s", $category_name);
    }

    if ($stmt->execute()) {
        $_SESSION['message'] = "Category added successfully!";
    } else {
        $_SESSION['errors'] = ["Something went wrong! " . $stmt->error];
    }

    $stmt->close();
    $conn->close();

    header('Location: categ_add.php');
    exit();
}
?>
