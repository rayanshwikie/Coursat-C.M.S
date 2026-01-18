<?php
session_start();
include '../db_connection.php';

$errors = [];

if (isset($_POST['update'])) {
    $editId = $_POST['edit_id'] ?? '';
    $name = $_POST['subname'] ?? '';
    $price = $_POST['price'] ?? '';
    $maxc = $_POST['max'] ?? '';
    $lev = $_POST['level'] ?? '';

    if (empty($editId) || empty($name) || empty($price) || empty($maxc) || empty($lev)) {
        $errors[] = "All fields are required.";
    } else {
        $sql = "UPDATE subscription SET sub_name=?, sub_price=?, sub_max=?, level=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdiii", $name, $price, $maxc, $lev, $editId);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Update Successful!";
        } else {
            $errors[] = "Something went wrong during update.";
        }
        $stmt->close();
    }
}



if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
}

header("Location:subscription.php " );
exit();

?>
