<html>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <head>
        <?php 
        session_start();
        include 'db_connection.php';
        include 'links.php';
        include 'nav.php';


        $search = $_GET['search'] ?? '';
        $category_filter = $_GET['category'] ?? '';

        $query = "SELECT * FROM courses";
        $conditions = [];

        if ($search) {
            $conditions[] = "course_name LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'";
        }
        if ($category_filter) {
            $conditions[] = "categ_id = '" . mysqli_real_escape_string($conn, $category_filter) . "'";
        }

        if ($conditions) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $result = mysqli_query($conn, $query);
        $courses = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $courses[$row['categ_id']][] = $row;
        }


        $categoryQuery = "SELECT * FROM categories";
        $categories = mysqli_query($conn, $categoryQuery);
        ?>
    
    </head>
    <body>
        <div class="container mt-4">
        <section class="hero-b mb-5">
        <div class="container">
            <h1 >Discover Our Courses</h1>
            <p>Enhance your skills with our wide range of courses.</p>
        </div>
    </section>

        
            <form method="GET" class="mb-4 d-flex">
                <div class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search courses" value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn btn-primary">
                    <svg  width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                    </svg>
                </button>
                </div>
                <div class="d-flex flex-wrap align-items-center ms-3 p-1">
                <a href="?category=<?= htmlspecialchars("") ?>" class="btn btn-primary me-2 mb-2">
    All
</a>
<?php while ($category = mysqli_fetch_assoc($categories)) { ?>
<a href="?category=<?= htmlspecialchars($category['id']) ?>" class="btn btn-primary me-2 mb-2 <?= ($category_filter == $category['id']) ? 'active' : '' ?>">
    <?= htmlspecialchars($category['categ_name']) ?>
</a>
<?php } ?>

                </div>
            </form>

        
            <?php foreach ($courses as $category_id => $courseList) { 
                $categoryName = mysqli_fetch_assoc(mysqli_query($conn, "SELECT categ_name FROM categories WHERE id = '$category_id'"))['categ_name'];
            ?>
            <section id=<?=$category_id ?>>
            <h2 class="mt-4"> <?= htmlspecialchars($categoryName) ?> </h2>

                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                    <?php foreach ($courseList as $course) { ?>
                        <div class="col">
                            <div class="card shadow-sm p-2 cb">
                                <img src="uploads/<?= htmlspecialchars($course['img']) ?>" class="card-img-top cb-i" alt="Course Image">
                                <div class="card-body cb-b">
                                    <h5 class="card-title cb-ti"> <?= htmlspecialchars($course['course_name']) ?> <br>
                                        
                                        <?php
                                        $sub_id=$course['subscription_id'];
                                        $sq="SELECT * from subscription WHERE id='$sub_id'";
                                        $ress=mysqli_query($conn,$sq);
                                        $sub=mysqli_fetch_assoc($ress);
                                        echo'<span class="badge bg-primary ">'.$sub['sub_name'].'</span>';?>
                                        &nbsp;
                                        <?php
                                        if ($course['is_private']==1){
                                            echo '<span class="badge bg-primary ">PRIVATE</span>';
                                        }
                                        ?>
                                           </h5>
                                    <p class="card-text cb-t"> <?= htmlspecialchars($course['description']) ?> </p>
                                    <div class="mt-auto">
                                        <?php if (isset($_SESSION['loggedInStatus'])) { ?>
                                         <form action="course_details.php" method="post">
                                            <input name="course_id" type="hidden" value=<?= htmlspecialchars($course['id']) ?>>
                                            <button type="submit" class="btn btn-primary w-100">Learn More</button>
                                        </form>
                                        <?php } else { ?>
                                        <a href="register.php" class="btn btn-secondary w-100">
                                            Login to Access
                                        </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </section>
        </div>

        <?php include 'footer.php'; ?>

    </body>
</html>