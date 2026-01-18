<html>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <head>
        <?php
        session_start();
        include 'db_connection.php';
        include 'links.php';
        include 'nav.php';

        $errors = [];
        $course = null;

        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['course_id'])) {
            $course_id = $_POST['course_id'];

            $stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
            $stmt->bind_param("i", $course_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $course = $result->fetch_assoc();
            } else {
                $errors[] = "Course Not Found";
            }
            $stmt->close();
        }

        if ($course) {
            $c_id = $course['categ_id'];
            $queryc = "SELECT * FROM categories WHERE id = '$c_id'";
            $result = mysqli_query($conn, $queryc);
            $row = mysqli_fetch_assoc($result);
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['enroll'])) {
            if (!isset($_SESSION['user_id'])) {
                $errors[] = "You must be logged in to enroll.";
            } else {
                $user_id = $_SESSION['user_id'];

                $sub_check_query = "SELECT sub_ID FROM sub_details WHERE user_id = '$user_id' AND status=1";
                $sub_check_result = mysqli_query($conn, $sub_check_query);

                if (mysqli_num_rows($sub_check_result) > 0) {
                    $sub_row = mysqli_fetch_assoc($sub_check_result);
                    $sub_ID = $sub_row['sub_ID'];

                    $sub_query = "SELECT * FROM subscription WHERE id = '$sub_ID'";
                    $sub_result = mysqli_query($conn, $sub_query);

                    if (mysqli_num_rows($sub_result) > 0) {
                        $sub_data = mysqli_fetch_assoc($sub_result);
                        $sub_max = $sub_data['sub_max'];

                        $enrollment_count_query = "SELECT COUNT(*) AS total_enrollments FROM enrollments WHERE user_id = '$user_id' AND status='1'";
                        $enrollment_count_result = mysqli_query($conn, $enrollment_count_query);
                        $enrollment_count_data = mysqli_fetch_assoc($enrollment_count_result);
                        $current_enrollments = $enrollment_count_data['total_enrollments'];

                        if ($current_enrollments >= $sub_max) {
                            array_push($errors, "You have reached your subscription limit of $sub_max courses.");
                        } else {
                            $course_required_sub_id = $_POST['req_id'];

                            $req_sub_query = "SELECT level FROM subscription WHERE id = '$course_required_sub_id'";
                            $req_sub_result = mysqli_query($conn, $req_sub_query);
                            $req_sub_data = mysqli_fetch_assoc($req_sub_result);
                            $required_level = $req_sub_data['level'];

                            $user_level = $sub_data['level'];

                            if ($user_level < $required_level) {
                                array_push($errors, "this course is for a higher subscription.");
                            } else {
                                $enroll_query = "INSERT INTO enrollments (user_id, course_id) VALUES ('$user_id', '$course_id')";

                                if (mysqli_query($conn, $enroll_query)) {
                                    $_SESSION['message'] = "Successfully enrolled in the course!";
                                } else {
                                    array_push($errors, "Failed to enroll.");
                                }
                            }
                        }
                    } else {
                        array_push($errors, "You need a subscription to enroll in this course.");
                    }
                } else {
                    array_push($errors, "You need a subscription to enroll in this course.");
                }
            }
        }

        if (count($errors) > 0) {
            $_SESSION['errors'] = $errors;
        }
        ?>

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">
        
    </head>
    <body>
        <div class="container mt-4">
            <?php include 'message.php';?>

            <?php if ($course): ?>
            <div class="row align-items-center">
                <div class="col-lg-6 text-center mb-4">
                    <img src="uploads/<?= htmlspecialchars($course['img']) ?>" class="course-detail-img" alt="<?= htmlspecialchars($course['course_name']) ?>">
                </div>
                <div class="col-lg-6">
                    <h1 class="display-4"><?= htmlspecialchars($course['course_name']) ?></h1>

                    <h4>
                        <strong>Category:</strong>
                        <span class="badge bg-primary"><?= htmlspecialchars($row['categ_name']) ?></span>
                        <?php
                        if ($course['is_private'] == 1) {
                            echo '<span class="badge bg-primary ">PRIVATE</span>';
                        }
                        ?>
                    </h4>

                    <h4>Subscription :
                        <?php
                        $sub_id = $course['subscription_id'];
                        $sq = "SELECT * from subscription WHERE id='$sub_id'";
                        $ress = mysqli_query($conn, $sq);
                        $sub = mysqli_fetch_assoc($ress);
                        echo '<span class="badge bg-primary ">' . $sub['sub_name'] . '</span>';
                        ?>
                    </h4>

                    <h5><strong>Created at:</strong> <?= date("Y-m-d", strtotime($course['created_at'])) ?></h5>

                    <p><?= nl2br(htmlspecialchars($course['description'])) ?></p>

                    <div class="course-buttons mt-4">
                        <form method="POST">
                            <input type="hidden" name="course_id" value="<?= htmlspecialchars($course['id']) ?>">
                            <input type="hidden" name="req_id" value="<?= $course['subscription_id'] ?>">

                            <?php
                            $user_id = $_SESSION['user_id'] ?? null;
                            $stat = 1;
                            $check_enrollment = "SELECT * FROM enrollments WHERE user_id = '$user_id' AND course_id = '$course_id' AND status = '$stat'";
                            $enrollment_result = mysqli_query($conn, $check_enrollment);

                            if ($user_id && mysqli_num_rows($enrollment_result) > 0) {
                                echo '<button type="button" class="btn btn-secondary btn-lg rounded-0" data-bs-toggle="popover" data-bs-content="You are already enrolled.">Enrolled</button>';
                            } else {
                                echo '<button type="submit" name="enroll" class="btn btn-primary btn-lg rounded-0">Enroll Now</button>';
                            }
                            ?>

                            <a href="browse.php" class="btn btn-secondary btn-lg rounded-0">Back to Courses</a>
                        </form>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <script src="js/bootstrap.bundle.min.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
                var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
                    return new bootstrap.Popover(popoverTriggerEl);
                });
            });
        </script>
    </body>
</html>
