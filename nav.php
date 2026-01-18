<style>
    
</style>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db_connection.php';
include 'links.php';


$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid p-2">
        <a class="navbar-brand" style="font-family: 'Red Hat Display', serif; font-weight: bolder; font-size: 22px; color: cornflowerblue;">COURSAT</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link navtex <?php echo ($current_page == 'index.php') ? 'active-link' : ''; ?>" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link navtex <?php echo ($current_page == 'browse.php') ? 'active-link' : ''; ?>" href="browse.php">Browse</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link navtex <?php echo ($current_page == 'subscription.php') ? 'active-link' : ''; ?>" href="subscription.php">Subscriptions</a>
                </li>
                <?php if (isset($_SESSION['user_id'])): ?>
    <li class="nav-item">
        <a class="nav-link navtex <?= ($current_page == 'dashboard.php') ? 'active-link' : ''; ?>" href="dashboard.php">Dashboard</a>
    </li>
<?php endif; ?>

                <?php
                if (isset($_SESSION['user_id'])) {
                    $id = $_SESSION['user_id'];
                    $result = mysqli_query($conn, "SELECT role FROM users WHERE id='$id'");
                    $row = mysqli_fetch_assoc($result);
                    if ($row['role'] == 'admin') {
                        echo '<li class="nav-item"><a class="nav-link navtex ' . ($current_page == "admin/userlist.php" ? 'active-link' : '') . '" href="admin/userlist.php">Admin</a></li>';
                    }
                }
                ?>
            </ul>

            <ul class="navbar-nav">
                <?php
                if (isset($_SESSION['loggedInStatus'])) {
                    echo '<li class="nav-item">
                            <form method="post" action="logout.php">
                                <button type="submit" class="btn btn-outline-primary rounded-0">
                                    <svg class="ic" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                                        <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </li>';
                } else {
                    echo '<li class="nav-item">
                            <a href="register.php">
                                <button type="button" class="btn btn-outline-primary rounded-0">
                                    <svg class="ic" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                                    </svg>
                                    Register
                                </button>
                            </a>
                        </li>';
                }
                ?>
            </ul>
        </div>
    </div>
</nav>

<script src="js/bootstrap.bundle.min.js"></script>
