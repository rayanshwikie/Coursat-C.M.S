<?php 
    function getuser($email){
        
        include 'db_connection.php';

        $result = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");

        global $conn; 

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            if ($row) {
                $_SESSION['user_id'] = $row['id']; 
                } 
            else {
                $_SESSION['user_id'] = null; 
                }
            } 
        else {
        die("Query Failed: " . mysqli_error($conn));
        }
        }
?>