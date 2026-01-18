<?php 

function check_admin(){
    include '../db_connection.php';
if (isset($_SESSION['user_id'])) {

    $id=$_SESSION['user_id'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'"); 

    $row = mysqli_fetch_assoc($result);
    
    if ($row['role'] == 'admin'){



        }
    else{
        echo'<div class="text-center align-items-center p-5 " style="color:red;"><h1>Error : Access Denied</h1></div>';
        exit();
    }   
    } }?>