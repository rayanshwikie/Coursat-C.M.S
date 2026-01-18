<?php
session_start();
include 'links.php';
include 'db_connection.php';
include 'nav.php';

if (!isset($_SESSION['loggedInStatus'])) {
    header('location: register.php');
    exit();
}

$currentUserId = $_SESSION['user_id'];

$userQuery = "SELECT * FROM users WHERE id='$currentUserId'";
$userResult = mysqli_query($conn, $userQuery);
$user = mysqli_fetch_assoc($userResult);

$activeSubQuery = "SELECT * FROM sub_details WHERE user_id = '$currentUserId' AND status=1";
$activeSubResult = mysqli_query($conn, $activeSubQuery);

if (mysqli_num_rows($activeSubResult) > 0) {
    $activeSubscription = mysqli_fetch_assoc($activeSubResult);
    $subscriptionId = $activeSubscription['sub_ID'];

    $subscriptionQuery = "SELECT * FROM subscription WHERE id='$subscriptionId'";
    $subscriptionQueryResult = mysqli_query($conn, $subscriptionQuery);
    $subscriptionInfo = mysqli_fetch_assoc($subscriptionQueryResult);
} else {
    $subscriptionInfo = ["sub_name" => "No Subscription", "level" => 0, "none" => true];
}

if (isset($_POST['finish'])) {
    $courseIdToFinish = $_POST['c_id'];

    $courseQuery = mysqli_query($conn, "SELECT * FROM courses WHERE id='$courseIdToFinish'");
    $course = mysqli_fetch_assoc($courseQuery);
    $requiredSubId = $course['subscription_id'];

    $requiredSubQuery = mysqli_query($conn, "SELECT * FROM subscription WHERE id='$requiredSubId'");
    $requiredSub = mysqli_fetch_assoc($requiredSubQuery);
    $requiredLevel = $requiredSub['level'];

    if ($subscriptionInfo['level'] >= $requiredLevel) {
        $updateStatusQuery = "UPDATE enrollments SET status='0' WHERE user_id='$currentUserId' AND course_id='$courseIdToFinish'";
        mysqli_query($conn, $updateStatusQuery);
    } else {
        echo "<script>alert('You need a higher subscription to finish this course.');</script>";
    }
}
?>

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <style>
       
    </style>
</head>
<body>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/alertify.min.js"></script>

<script>
function alertAndPrevent(msg) {
    alert(msg);
    return false;
}
</script>

<div class="container">
    <section class="hero-d">
        <div class="container text-center">
            <h1>Dashboard</h1>
            <div class="row info-group justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <h2>Account Name: <br><span class="badge bg-primary rounded-2"><?= htmlspecialchars($user['username']); ?></span></h2>
                </div>
                <div class="col-md-6 col-lg-4">
                    <h2>Subscription Type:<br> <span class="badge bg-primary rounded-2"><?= htmlspecialchars($subscriptionInfo['sub_name']); ?></span>
                        <a href="subscription.php" class="an">✎</a>
                    </h2>
                </div>
            </div>
        </div>
    </section>
    <hr>

    <div class="container">
        <?php include 'message.php';?>
        <h2>Your Active Courses</h2>
        <div class="scroll-container">
            <div class="d-flex flex-row flex-nowrap">
                <?php 
                $activeEnrollmentsQuery = "SELECT course_id FROM enrollments WHERE user_id = '$currentUserId' AND status='1'";
                $activeEnrollmentsResult = mysqli_query($conn, $activeEnrollmentsQuery);
                $activeCourseIds = [];
                while ($row = mysqli_fetch_assoc($activeEnrollmentsResult)) {
                    $activeCourseIds[] = $row['course_id'];
                }

                if (!empty($activeCourseIds)) {
                    $courseIdsList = implode(',', $activeCourseIds);
                    $activeCoursesQuery = "SELECT * FROM courses WHERE id IN ($courseIdsList)";
                    $activeCoursesResult = mysqli_query($conn, $activeCoursesQuery);
                    while ($course = mysqli_fetch_assoc($activeCoursesResult)) {
                        $subQuery = mysqli_query($conn, "SELECT * FROM subscription WHERE id='{$course['subscription_id']}'");
                        $courseSub = mysqli_fetch_assoc($subQuery);
                        $courseLevel = $courseSub['level'];
                        $userLevel = $subscriptionInfo['level'];
                        $hasSub = !isset($subscriptionInfo['none']);
                ?>
                <div class="card me-3" style="min-width: 300px;">
                    <img src="uploads/<?= htmlspecialchars($course['img']); ?>" class="card-img-top fixed-img">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($course['course_name']); ?></h5>
                        <span class="badge bg-primary"><?= $courseSub['sub_name']; ?></span>
                        <?php if ($course['is_private']): ?><span class="badge bg-primary">PRIVATE</span><?php endif; ?>
                        <p class="card-text"><?= nl2br(htmlspecialchars($course['description'])); ?></p>
                        <div class="d-flex">
                            <form method="post">
                                <input type="hidden" name="c_id" value="<?= $course['id'] ?>">
                                <?php if ($hasSub && $userLevel >= $courseLevel): ?>
                                    <button class="btn btn-primary rounded-0" type="submit" name="finish">Finish course</button>
                                <?php else: ?>
                                    <button class="btn btn-secondary rounded-0 disabled-btn" type="button" onclick="return alertAndPrevent('You need a valid or higher subscription to finish this course.')">Finish course</button>
                                <?php endif; ?>

                                <?php if (!empty($course['attachment'])): ?>
                                    <?php if ($hasSub && $userLevel >= $courseLevel): ?>
                                        <a href="uploads/<?= htmlspecialchars($course['attachment']); ?>" class="btn btn-primary rounded-0" target="_blank">View</a>
                                    <?php else: ?>
                                        <a href="#" class="btn btn-secondary rounded-0 disabled-btn" onclick="return alertAndPrevent('You need a valid or higher subscription to view this material.')">View</a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <button class="btn btn-secondary rounded-0" disabled>No Attachment</button>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
                <?php }} else { echo '<p>You are not enrolled in any courses.</p>'; } ?>
            </div>
        </div>
    </div>

    <div class="container">
        <h2>Your Finished Courses</h2>
        <div class="scroll-container">
            <div class="d-flex flex-row flex-nowrap">
                <?php 
                $finishedCoursesQuery = "SELECT course_id FROM enrollments WHERE user_id = '$currentUserId' AND status='0'";
                $finishedCoursesResult = mysqli_query($conn, $finishedCoursesQuery);
                $finishedCourseIds = [];
                while ($row = mysqli_fetch_assoc($finishedCoursesResult)) {
                    $finishedCourseIds[] = $row['course_id'];
                }

                if (!empty($finishedCourseIds)) {
                    $courseIdsList = implode(',', $finishedCourseIds);
                    $finishedCoursesQuery = "SELECT * FROM courses WHERE id IN ($courseIdsList)";
                    $finishedCoursesResult = mysqli_query($conn, $finishedCoursesQuery);
                    while ($course = mysqli_fetch_assoc($finishedCoursesResult)) {
                        $subQuery = mysqli_query($conn, "SELECT * FROM subscription WHERE id='{$course['subscription_id']}'");
                        $courseSub = mysqli_fetch_assoc($subQuery);
                ?>
                <div class="card me-3" style="min-width: 300px;">
                    <img src="uploads/<?= htmlspecialchars($course['img']); ?>" class="card-img-top fixed-img">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($course['course_name']); ?></h5>
                        <span class="badge bg-primary"><?= $courseSub['sub_name']; ?></span>
                        <?php if ($course['is_private']): ?><span class="badge bg-primary">PRIVATE</span><?php endif; ?>
                        <p class="card-text"><?= nl2br(htmlspecialchars($course['description'])); ?></p>
                        <em class="float-end">Completed</em>
                    </div>
                </div>
                <?php }} else { echo '<p>You have not finished any courses.</p>'; } ?>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
