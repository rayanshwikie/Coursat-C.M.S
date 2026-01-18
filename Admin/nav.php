<nav class="navbar navbar-expand-lg bg-dark p-3">
  <div class="container-fluid">

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse w-100" id="navbarSupportedContent">

    <ul class="navbar-nav mx-auto mb-2 mb-lg-0 d-flex flex-row gap-3 text-center">
        <li class="nav-item">
          <a class="nav-link navtex " href="../index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link navtex " href="../browse.php">Browse</a>
        </li>
        <li class="nav-item">
          <a class="nav-link navtex " href="../subscription.php">Subscriptions</a>
        </li>
        <li class="nav-item">
          <a class="nav-link navtex " href="../dashboard.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <?php 
            include '../db_connection.php';
            if (isset($_SESSION['user_id'])) {
              $id = $_SESSION['user_id'];
              $result = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'"); 
              $row = mysqli_fetch_assoc($result);
              if ($row['role'] == 'admin') {
                echo '<a class="nav-link navtex " href="index.php">Admin</a>';
              }
            }
          ?>
        </li>
      </ul>

      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <?php 
            if (isset($_SESSION['loggedInStatus'])) {
              echo '<form method="post" action="../logout.php">
                      <button type="submit" class="btn btn-outline-primary rounded-0">Logout</button>
                    </form>';
            } else {
              echo '<a href="../register.php">
                      <button type="button" class="btn btn-outline-primary rounded-0">Register</button>
                    </a>';
            }
          ?>
        </li>
      </ul>
    </div>
  </div>
</nav>
