<html class="ht">
  
  <head>
    
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>
      corsat
    </title>
    <?php 
    include 'links.php'; include 'db_connection.php'; 
   include 'get_user.php'; 
    session_start(); 
    if(isset($_SESSION['loggedInStatus'])){
         header( 'Location: index.php'); exit(); 
         } 
         ?>
      <link rel="stylesheet" href="css/style.css" />

      <?php 
      if(isset($_POST['registerBtn'])){
    $name = mysqli_real_escape_string($conn,$_POST['name']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $password = mysqli_real_escape_string($conn,$_POST['password']);
    

    $errors = [];

    if($name == '' OR $email == '' OR $password == '' ){
        array_push($errors, "All fields are required");
    }

    if($email != '' && !filter_var($email, FILTER_VALIDATE_EMAIL)){
        array_push($errors, "Enter valid email address");
        
    }
  

    if($email != ''){
      $userCheck = mysqli_query($conn, "SELECT email FROM users WHERE email='$email'");
      if($userCheck){
        if(mysqli_num_rows($userCheck) > 0){
          array_push($errors, "Email already registered");
            }
        }
        else{
          array_push($errors, "Something Went Wrong!");
        }
    }

    if(count($errors) > 0){
        $_SESSION['errors'] = $errors;
        header('Location: register.php');
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    $query = "INSERT INTO users ( username , password , email ) VALUES ('$name','$hashed_password','$email')";
    $userResult = mysqli_query($conn, $query);

    if($userResult){
        $_SESSION['message'] = "Registered Successfully";
        $_SESSION['loggedInStatus'] = true;
        getuser($email);
        header('Location: index.php');
        exit();
    }
    else{
        $_SESSION['message'] = "Something Went Wrong3";
        header('Location: register.php');
        exit();
    }

}

?>
  </head>
  
  <body class="bdy">
  <?php include 'nav.php';?> 
    <div class="main-content">
      <div class="left-section animate__animated animate__fadeInLeft">
        <h1 class="display-1" style="font-family: 'Red Hat Display', serif; font-weight: bolder;">
          Hello, Welcome!
        </h1>
        <p class="lead" style="font-family: 'Red Hat Display', serif;">
          Already have an account?
        </p>
        <a href="login.php">
          <button type="button" class="btn btn-outline-light btn-lg">
            Login
          </button>
        </a>
      </div>
      <div class="right-section">
        <h1 class="display-1" style="font-family: 'Red Hat Display', serif; font-weight: bolder; color: black;">
          Register
        </h1>
        <form method="POST">
        <?php include 'message.php'?>
              <div class="input-group mb-3">
                <span class="input-group-text">
                  <svg width="24" height="24" viewBox="0 0 16 16" fill="currentColor" style="color: black;">
                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"
                    />
                  </svg>
                </span>
                <input type="text" name="name" class="form-control" placeholder="Username">
              </div>
              <div class="input-group mb-3">
                <span class="input-group-text">
                  <svg width="24" height="24" viewBox="0 0 16 16" fill="currentColor" style="color: black;">
                    <path d="M13.106 7.222c0-2.967-2.249-5.032-5.482-5.032-3.35 0-5.646 2.318-5.646 5.702 0 3.493 2.235 5.708 5.762 5.708.862 0 1.689-.123 2.304-.335v-.862c-.43.199-1.354.328-2.29.328-2.926 0-4.813-1.88-4.813-4.798 0-2.844 1.921-4.881 4.594-4.881 2.735 0 4.608 1.688 4.608 4.156 0 1.682-.554 2.769-1.416 2.769-.492 0-.772-.28-.772-.76V5.206H8.923v.834h-.11c-.266-.595-.881-.964-1.6-.964-1.4 0-2.378 1.162-2.378 2.823 0 1.737.957 2.906 2.379 2.906.8 0 1.415-.39 1.709-1.087h.11c.081.67.703 1.148 1.503 1.148 1.572 0 2.57-1.415 2.57-3.643zm-7.177.704c0-1.197.54-1.907 1.456-1.907.93 0 1.524.738 1.524 1.907S8.308 9.84 7.371 9.84c-.895 0-1.442-.725-1.442-1.914">
                    </path>
                  </svg>
                </span>
                <input type="text" name="email" class="form-control" placeholder="Email">
              </div>
              <div class="input-group mb-3">
                <span class="input-group-text">
                  <svg width="24" height="24" viewBox="0 0 16 16" fill="currentColor" style="color: black;">
                    <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2M5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1"
                    />
                  </svg>
                </span>
                <input type="password" name="password" class="form-control" placeholder="Password">
              </div>
              
              <button type="submit" name="registerBtn" class="btn btn-primary">
                Register
              </button>
        </form>
      </div>
    </div>
  </body>

</html>