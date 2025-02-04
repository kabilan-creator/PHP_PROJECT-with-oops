<?php
require '../../config/helper.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="adminlte.min.css"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <link rel="stylesheet" href='<?php echo url('/assets/css/adminlte.min.css') ?>'>
    <title>Document</title>
</head>

    <body class="login-page" data-new-gr-c-s-check-loaded="14.1211.0" data-gr-ext-installed="" style="min-height: 332.4px;">
        <div class="login-box">
          <div class="login-logo">
            <a ><strong>FORGOT PASSWORD</strong></a>
          </div>
          <!-- /.login-logo -->
          <div class="card">
            <div class="card-body login-card-body">
              <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>
        
              <form action="../../views/recover-password.php" method="post">
                <div class="input-group mb-3">
                  <input type="email" name="email" class="form-control" placeholder="Email">
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-envelope"></span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-block">Request new password</button>
                  </div>
                  <!-- /.col -->
                </div>
              </form>
        
              <p class="mt-3 mb-1">
                <a href="login.php">Login</a>
              </p>
              <p class="mb-0">
                <a href="register.php" class="text-center">Register a new membership</a>
              </p>
            </div>
            <!-- /.login-card-body -->
          </div>
        </div>
        <!-- /.login-box -->
        
        
        
        </body>
    

</html>