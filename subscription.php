<html>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<head>
    <?php 
    session_start();
    include 'links.php';
    include 'db_connection.php';

    if (!isset($_SESSION['loggedInStatus'])) {
        header("location: register.php");
    }

    if (isset($_POST['sub'])) {
        $user_id = $_SESSION['user_id'];
        $sub_id = $_POST['sub_id'];

        $check_active = "SELECT * FROM sub_details WHERE user_id = '$user_id' AND status = 1";
        $check_result = mysqli_query($conn, $check_active);

        if (mysqli_num_rows($check_result) > 0) {
            $_SESSION['errors'][] = "You are already subscribed to a plan.";
        } else {
            $check_inactive = "SELECT * FROM sub_details WHERE user_id = '$user_id' AND status = 0";
            $inactive_result = mysqli_query($conn, $check_inactive);

            if (mysqli_num_rows($inactive_result) > 0) {
                $query = "UPDATE sub_details SET sub_id = '$sub_id', status = 1 WHERE user_id = '$user_id' AND status = 0";
            } else {
                $query = "INSERT INTO sub_details (user_id, sub_id, status) VALUES ('$user_id', '$sub_id', 1)";
            }

            $result = mysqli_query($conn, $query);

            if ($result) {
                $sub_select = mysqli_query($conn,"SELECT * FROM sub_details WHERE user_id = '$user_id' AND status = 1");
                $res=mysqli_fetch_assoc($sub_select);

                $_SESSION['message'] = "Subscribed successfully!";
                $_SESSION['sub_id'] = $res['id'];
            } else {
                $_SESSION['errors'][] = "Subscription failed. Please try again.";
            }
        }

        header('location: subscription.php');
        exit();
    }

    if (isset($_POST['cancel_sub'])) {
        $user_id = $_SESSION['user_id'];
        $query = "UPDATE sub_details SET status = 0 WHERE user_id = '$user_id'";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $_SESSION['message'] = "Subscription canceled successfully!";
            $_SESSION['sub_id'] ='';
        } else {
            $_SESSION['errors'][] = "Failed to cancel subscription.";
        }
    }
    ?>    

    <style>
      
    </style>
</head>

<body>
    <?php include 'nav.php'; ?>
    <div class="container py-5 ">
        <section class="hero mb-5">
            <div class="container">
                <h1 style=" font-family:Red Hat Display, serif;">Choose your Subscription</h1>
                <p>Choose the Subscription that aligns with your needs.</p>
            </div>
        </section>

        <br>

        <?php include 'message.php'; ?>

        <div class="row text-center align-items-center">
            <?php
            $query = "SELECT * FROM subscription ORDER BY level ASC";
            $result = mysqli_query($conn, $query);

            $user_id = $_SESSION['user_id'];
            $subscribed_query = "SELECT sub_id FROM sub_details WHERE user_id = '$user_id' AND status = 1";
            $subscribed_result = mysqli_query($conn, $subscribed_query);
            $subscribed_data = mysqli_fetch_assoc($subscribed_result);
            $user_sub_id = $subscribed_data['sub_id'] ?? null;

            while ($row = mysqli_fetch_assoc($result)) {
                $is_subscribed = ($user_sub_id == $row['id']);
                $sub_level = $row['level'];

                $included_subs_query = "SELECT sub_name FROM subscription WHERE level <= '$sub_level' ORDER BY level ASC";
                $subs_result = mysqli_query($conn, $included_subs_query);

                $included_subs = [];
                while ($s = mysqli_fetch_assoc($subs_result)) {
                    $included_subs[] = $s['sub_name'];
                }
                $included_subs_text = implode(', ', $included_subs);

                echo '
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card cb-s text-center border-0 shadow rounded-0 p-4 mx-auto">
                        <div class="card-body">
                            <h3 class="card-text cb-s-text fw-bold">' . htmlspecialchars($row['sub_name']) . '</h3>
                            <div class="card-text1">
                                <p style="font-size: xx-large; font-family: red hat display,serif; font-weight: bolder;">' . htmlspecialchars($row['sub_price']) . '$ /month</p>
                                <p><b>' . htmlspecialchars($row['sub_name']) . '</b> courses</p>
                                <p><b>' . htmlspecialchars($row['sub_name']) . ' Private</b> courses</p>
                                <p><b>Max Active courses:</b> ' . htmlspecialchars($row['sub_max']) . '</p>     
                                <p><strong>Includes Access To:</strong> ' . $included_subs_text . ' <strong>courses</strong></p>
                            </div>
                        </div>
                        <form method="POST">';

                if ($is_subscribed) {
                    echo '
                        <input type="hidden" name="sub_id" value="' . $row['id'] . '">
                        <button type="button" class="btn btn-primary btn-lg rounded-0" disabled>Subscribed</button>
                        <button type="submit" name="cancel_sub" class="btn btn-danger btn-lg rounded-0">Cancel</button>';
                } else {
                    echo '
                        <input type="hidden" name="sub_id" value="' . $row['id'] . '">
                        <button type="submit" name="sub" class="btn btn-primary btn-lg rounded-0">SUBSCRIBE</button>';
                }

                echo '</form>
                    </div>
                </div>';
            }
            ?>
            <p class="lead">Our Team will contact you later with the payment info.</p>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
