<?php
function check_sub(){

    include 'db_connection.php';

    $sql = "SELECT * FROM sub_details WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        
        $created_at = new DateTime($row['sub_date']);
        $now = new DateTime();
        $interval = $now->diff($created_at);

        if ($interval->days > 30) {
            
            $upd_sql = "UPDATE sub_details SET status = 0 WHERE user_id = ?";
            $delete_stmt = $conn->prepare($upd_sql);
            $delete_stmt->bind_param("i", $_SESSION['user_id']);
            $delete_stmt->execute();

            $_SESSION['errors']= ["Your Subscription has expired.",];
        } }
}
?>
