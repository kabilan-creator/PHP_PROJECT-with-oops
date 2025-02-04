<?php
require '../../config/helper.php';
require  '../../config/database.php';
session_start();


if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
  header("Location:". url("/index.php"));

  exit();
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard"><!--end::Primary Meta Tags--><!--begin::Fonts-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous"><!--end::Fonts--><!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/styles/overlayscrollbars.min.css" integrity="sha256-dSokZseQNT08wYEWiz5iLI8QPlKxG+TswNRD8k35cpg=" crossorigin="anonymous"><!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" integrity="sha256-Qsx5lrStHZyR9REqhUF8iQt73X06c8LGIUPzpOhwRrI=" crossorigin="anonymous"><!--end::Third Party Plugin(Bootstrap Icons)--><!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href='<?php echo url('/assets/css/adminlte.css') ?>'><!--end::Required Plugin(AdminLTE)--><!-- apexcharts -->
    <link rel="stylesheet" href='<?php echo url('/assets/css/adminlte.min.css') ?>'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous"><!-- jsvectormap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css" integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous">

    <title>login page</title>
</head>
<body>
    <body class="login-page" data-new-gr-c-s-check-loaded="14.1211.0" data-gr-ext-installed="" style="min-height: 495.6px;"> 
        <div class="login-box">
          <div class="login-logo">
            <a><strong>LOGIN</strong></a>
          </div>
          <!-- /.login-logo -->
          <div class="card">
            <div class="card-body login-card-body">
              <p class="login-box-msg">Sign in to start your session</p>
              <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
              $username = $_POST['user'];
              $password = $_POST['pass'];
          
              error_log("Submitted username: $username");
              error_log("Submitted password: $password");

              // SQL query to check user credentials
              $sql = "SELECT * FROM admin WHERE AMAIL = ?";
              $stmt = $db->prepare($sql);
          
              if (!$stmt) {
                  die("SQL prepare error: " . $db->error);
              }
          
              if (!$stmt->bind_param('s', $username)) {
                  die("Bind param error: " . $stmt->error);
              }
          
              $stmt->execute();
              $result = $stmt->get_result();
          
              if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                
                // Ensure $user["account_activation"] exists and is not null
                if ($user["account_activation"] === NULL) {
                    // Verify the hashed password
                    if (password_verify($password, $user['APASS'])) {
                        $_SESSION['AMAIL'] = $username;
                        $_SESSION['is_logged_in'] = true;
                        header('Location:'.url('/index.php')); // Redirect to dashboard
                        exit();
                    } else {
                        $error = "Invalid username or password.";
                    }
                } else {
                    $error = "Account activation is required.";
                }
            } else {
                $error = "Invalid username or password.";
            }
          
              if (isset($error)) {
                  echo "<div class='alert alert-danger'>$error</div>";
              }
          }
              ?>

        
              <form action="" method="post"  id="frm">
                <div class="input-group mb-3">
                  <input type="email" name="user" id="login" class="form-control" placeholder="Email">
                  <div class="input-group-append2">
                   <div class="input-group-text">
                   <span >
                   <i class="bi bi-envelope"></i>  
                   </span>
                  </div>
              </div>
                </div>
                <div class="input-group mb-3">
              <input type="password" name="pass" id="pass" class="form-control" placeholder="Password">
              <div class="input-group-append2">
                <div class="input-group-text">
                  <span class="toggle-password">
                    <i class="bi-eye-slash-fill" id="toggleIcon"></i>
                  </span>
                </div>
              </div>
            </div>
                <div class="row">

                  <!-- /.col -->
                  <div class="col-4">
                    <button type="submit" name="login" class="btn btn-primary btn-block">Sign In</button>
                  </div>
                  <!-- /.col -->
                </div>
              </form>
    
              <!-- /.social-auth-links -->
        
             
             
              <p class="mt-3 mb-1">
                <a href='<?php echo url('/views/auth/forgot.php') ?>'>I Forgot my password</a>
              </p>
              <P class="mb-0">
               <a href='<?php echo url('/views/auth/register.php') ?>'>Register a new membership</a>
              </p>
            </div>
            <!-- /.login-card-body -->
          </div>
        </div>
        <!-- /.login-box -->
        
        <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/browser/overlayscrollbars.browser.es6.min.js" integrity="sha256-H2VM7BKda+v2Z4+DRy69uknwxjyDRhszjXFhsL4gD3w=" crossorigin="anonymous"></script> <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha256-whL0tQWoY1Ku1iskqPFvmZ+CHsvmRWx/PIoEvIeWh4I=" crossorigin="anonymous"></script> <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha256-YMa+wAM6QkVyz999odX7lPRxkoYAan8suedu4k2Zur8=" crossorigin="anonymous"></script> <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
        <script src="js/jquery.js"></script> <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
        <script src="js/jquery.validate.min.js"> </script>

        <script>
          document.querySelector('.toggle-password').addEventListener('click', function () {
    const passwordInput = document.getElementById('pass');
    const toggleIcon = document.getElementById('toggleIcon');
    
    // Toggle password input type
    if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      toggleIcon.classList.add('bi-eye-fill');
      toggleIcon.classList.remove('bi-eye-slash-fill');
    } else if(passwordInput.type === 'text') {
      passwordInput.type = 'password';
      toggleIcon.classList.add('bi-eye-slash-fill');
      toggleIcon.classList.remove('bi-eye-fill');
    }
  });
             $(document).ready(function () {
                $("#frm").on("submit", function (e) {

                    // Clear previous error messages
                    $(".error").remove();

                    let isValid = true;

                    const email = $("#login").val().trim();
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(email)){
                        $(".input-group-append1").after(' <span class="error invalid-feedback">invalid mail</span>');
                        $("#login").addClass('is-invalid')
                        isValid = false;
                    }
                    const password = $("#pass").val().trim();
                    if (password.length < 6) {
                        $(".input-group-append2").after('<span class="error invalid-feedback">Password must be at least 6 characters long</span>');
                        $("#pass").addClass('is-invalid')
                        isValid = false;
                    }

                    if (!isValid) {
                      e.preventDefault();
                    }

                });
             });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js" integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js" integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY=" crossorigin="anonymous"></script> <!-- jsvectormap -->
        
        
        </body>
</body>
</html>