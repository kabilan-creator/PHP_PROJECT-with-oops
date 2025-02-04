<?php
require '../../config/database.php';
require '../../config/mailer.php';
require '../../config/helper.php';
$error = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['name'])) {
        $error[] = "Name is required.";
    }
    if (empty($_POST['email'])) {
        $error[] = "Email is required.";
    }
    if (empty($_POST['password'])) {
        $error[] = "Password is required.";
    }

    if (empty($error)) {
        $hashedPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $activation_token = bin2hex(random_bytes(16));

        $sql = "INSERT INTO admin (NAME, AMAIL, APASS, account_activation) VALUES(?, ?, ?, ?)";
        $stmt = mysqli_prepare($db, $sql);

        if ($stmt === false) {
            echo mysqli_error($db);
        } else {
            mysqli_stmt_bind_param($stmt, "ssss", $_POST['name'], $_POST['email'], $hashedPassword, $activation_token);
            if (mysqli_stmt_execute($stmt)) {
                $mail = new MailHandler();
                $mail = $mail->getMailerInstance();
                $mail->setFrom("noreply@example.com", "Your App");
                $mail->addAddress($_POST["email"]);
                $mail->Subject = "Account Activation";
                $mail->Body = <<<END
                Click <a href="localhost/new project/views/activation.php?token=$activation_token">here</a> to activate your account.
                END;

                try {
                    $mail->send();
                    echo "Message sent, please check your inbox.";
                    header('Location: ../../views/email_verfication.php');
                    exit();
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
                    exit;
                }
            } else {
                echo mysqli_stmt_error($stmt);
            }
        }
    }
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
    <link rel="stylesheet" href="adminlte.css"><!--end::Required Plugin(AdminLTE)--><!-- apexcharts -->
    <link rel="stylesheet" href='<?php echo url('/assets/css/adminlte.css') ?>'><!--end::Required Plugin(AdminLTE)--><!-- apexcharts -->
    <link rel="stylesheet" href='<?php echo url('/assets/css/adminlte.min.css') ?>'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous"><!-- jsvectormap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css" integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous">
    <title>Document</title>
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary"> <!--begin::App Wrapper-->
    <div class="register-page bg-body-secondary app-loaded">
        
        <div class="register-box">
            <div class="register-logo"> <a href="index.html"><b>Register</b> Page</a> </div> <!-- /.register-logo -->
            <div class="card">
                <div class="card-body register-card-body">
                    <p class="register-box-msg">Register a new membership</p>
                    <?php if(!empty($error)) :?>
                     <?php foreach($error as $msg):?>
                        <?= $msg ?>
                        <li><?php endforeach; ?></li>
                    <?php endif; ?>
                    <form action="" method="post" class="formpage"  id="frm">
                        <div class="input-group mb-3"> <input type="text" id="fname" name="name" class="form-control " placeholder="Full Name" >
                        <div class="input-group-append2">
                        <div class="input-group-text">
                            <span >
                            <i class="bi bi-person"></i>
                            </span>
                        </div>
                        </div>
                        </div>
                        <div class="input-group mb-3"> <input type="email" id="mail" name="email" class="form-control " placeholder="Email">
                        <div class="input-group-append2">
                        <div class="input-group-text">
                            <span >
                            <i class="bi bi-envelope"></i>  
                            </span>
                        </div>
                        </div>
                        </div>
                        <div class="input-group mb-3">
                        <input type="password" id="pass" name="password" class="form-control" placeholder="Password">
                        <div class="input-group-append2">
                        <div class="input-group-text">
                            <span class="toggle-password">
                            <i class="bi bi-eye-fill" id="toggleIcon"></i>
                            </span>
                        </div>
                        </div>
                        <!-- <span class="error invalid-feedback">Password must be at least 6 characters long</span> -->
                        </div>
                        <div class="input-group mb-3">
                        <input type="password" id="confirmPass" name="confirm_password" class="form-control" placeholder="Confirm Password">
                        <div class="input-group-append2">
                        <div class="input-group-text">
                          <span class="toggle-password">
                             <i class="bi bi-eye-fill" id="toggleConfirmIcon"></i>
                         </span>
                          </div>
                          </div>
                          </div>

                        <!--begin::Row-->
                        <div class="row ">
                               <!-- /.col -->
                            <div class="col-4">
                                <div class="d-grid gap-2"> <button type="submit" class="btn btn-primary">Sign UP</button> </div>
                            </div> <!-- /.col -->
                        </div> <!--end::Row-->
                     </form>
                     <p class="mt-3 mb-1">
                    <a href='<?php echo url('/views/auth/login.php') ?>'>Login</a>
                      </p>
                      <p class="mt-3 mb-1">
                    <a href='<?php echo url('/views/auth/forgot.php') ?>'>I Forgot my password</a>
                      </p>
                </div> <!-- /.register-card-body -->
            </div>
        </div> <!-- /.register-box --> <!--begin::Third Party Plugin(OverlayScrollbars)-->
        

        <script>
            // $("#frm").validate({
            //     rules:{
            //         name:{
            //             minlength: 2
            //         }
            //     },
            //     Messages:{
            //         required:"plese enter your name",
            //         minlength: "Name atlest 2 char"
            //     },
            //     submitHandler: function(form){
            //         form.submit();

            //     }

            // })

        </script> <!--end::OverlayScrollbars Configure--> <!--end::Script-->
    
    </div> <!--end::App Wrapper--> <!--begin::Script--> <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/browser/overlayscrollbars.browser.es6.min.js" integrity="sha256-H2VM7BKda+v2Z4+DRy69uknwxjyDRhszjXFhsL4gD3w=" crossorigin="anonymous"></script> <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha256-whL0tQWoY1Ku1iskqPFvmZ+CHsvmRWx/PIoEvIeWh4I=" crossorigin="anonymous"></script> <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha256-YMa+wAM6QkVyz999odX7lPRxkoYAan8suedu4k2Zur8=" crossorigin="anonymous"></script> <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
            <script src="js/jquery.js"></script> <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
            <script src="js/jquery.validate.min.js"> </script>
    <script>
    document.querySelector('.toggle-password').addEventListener('click', function () {
    const passwordInput = document.getElementById('pass');
    const toggleIcon = document.getElementById('toggleIcon');

    // Toggle password visibility
    if (passwordInput.type === 'password') {
      passwordInput.type = 'text'; // Show the password
      toggleIcon.classList.add('bi-eye-fill');
      toggleIcon.classList.remove('bi-eye-slash-fill'); // Change to slash icon
    } else if (passwordInput.type = 'text'){
      passwordInput.type = 'password'; // Hide the password
      toggleIcon.classList.add('bi-eye-slash-fill');
      toggleIcon.classList.remove('bi-eye-fill'); // Change back to eye icon
    }
  });
    document.querySelector('.toggle-password').addEventListener('click', function () {
    const confirmPasswordInput = document.getElementById('confirmPass');
    const toggleConfirmIcon = document.getElementById('toggleConfirmIcon');
    if (confirmPasswordInput.type === 'password') {
        confirmPasswordInput.type = 'text';
        toggleConfirmIcon.classList.add('bi-eye-fill');
        toggleConfirmIcon.classList.remove('bi-eye-slash-fill');
    } else if(confirmPasswordInput.type === 'text'){
        confirmPasswordInput.type = 'password';
        toggleConfirmIcon.classList.add('bi-eye-fill');
        toggleConfirmIcon.classList.remove('bi-eye-slash-fill');
    }
});

$(document).ready(function () {
    $("#frm").on("submit", function (e) {
        $(".error").remove();
        let isValid = true;

        const password = $("#pass").val().trim();
        const confirmPassword = $("#confirmPass").val().trim();

        if (password !== confirmPassword) {
            $("#confirmPass").after('<span class="error invalid-feedback">Passwords do not match</span>');
            $("#confirmPass").addClass('is-invalid');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
        }
    });
});

        const SELECTOR_SIDEBAR_WRAPPER = ".sidebar-wrapper";
        const Default = {
            scrollbarTheme: "os-theme-light",
            scrollbarAutoHide: "leave",
            scrollbarClickScroll: true,
        };
        document.addEventListener("DOMContentLoaded", function() {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            if (
                sidebarWrapper &&
                typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== "undefined"
            ) {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }
        });
    </script> <!--end::OverlayScrollbars Configure--> <!-- OPTIONAL SCRIPTS --> <!-- sortablejs -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js" integrity="sha256-ipiJrswvAR4VAx/th+6zWsdeYmVae0iJuiR+6OqHJHQ=" crossorigin="anonymous"></script> <!-- sortablejs -->
    <script>
        const connectedSortables =
            document.querySelectorAll(".connectedSortable");
        connectedSortables.forEach((connectedSortable) => {
            let sortable = new Sortable(connectedSortable, {
                group: "shared",
                handle: ".card-header",
            });
        });

        const cardHeaders = document.querySelectorAll(
            ".connectedSortable .card-header",
        );
        cardHeaders.forEach((cardHeader) => {
            cardHeader.style.cursor = "move";
        });


        $(document).ready(function () {
                    $("#frm").on("submit", function (e) {
                        // Prevent form submission
                        // e.preventDefault();
    
                        // Clear previous error messages
                        $(".error").remove();
    
                        let isValid = true;
    
                        // Validate Full Name
                        const name = $("#fname").val().trim();
                        if (name.length < 3) {
                            $(".data1").after(' <span class="error invalid-feedback">Name must be at least 3 characters long</span>');
                            $("#fname").addClass('is-invalid')
                            isValid = false;
                        }
    
                        // Validate Email
                        const email = $("#mail").val().trim();
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(email)) {
                            $(".input-group-text2").after('<span class="error invalid-feedback">Enter a valid email</span>');
                            $("#mail").addClass('is-invalid')
                            isValid = false;
                        }
    
                        // Validate Password
                        const password = $("#pass").val().trim();
                        if (password.length < 6) {
                            $(".input-group-text3").after('<span class="error invalid-feedback">Password must be at least 6 characters long</span>');
                            $("#pass").addClass('is-invalid')
                            isValid = false;
                        }
    
    
                        // Submit the form if all fields are valid
                        if (!isValid) {
                            // alert("Form submitted successfully!");
                            // Uncomment the line below to submit the form
                            // this.submit();
                            e.preventDefault();
                        }
                    });
                });
    </script> <!-- apexcharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js" integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8=" crossorigin="anonymous"></script> <!-- ChartJS -->
    <script>
        // NOTICE!! DO NOT USE ANY OF THIS JAVASCRIPT
        // IT'S ALL JUST JUNK FOR DEMO
        // ++++++++++++++++++++++++++++++++++++++++++

        const sales_chart_options = {
            series: [{
                    name: "Digital Goods",
                    data: [28, 48, 40, 19, 86, 27, 90],
                },
                {
                    name: "Electronics",
                    data: [65, 59, 80, 81, 56, 55, 40],
                },
            ],
            chart: {
                height: 300,
                type: "area",
                toolbar: {
                    show: false,
                },
            },
            legend: {
                show: false,
            },
            colors: ["#0d6efd", "#20c997"],
            dataLabels: {
                enabled: false,
            },
            stroke: {
                curve: "smooth",
            },
            xaxis: {
                type: "datetime",
                categories: [
                    "2023-01-01",
                    "2023-02-01",
                    "2023-03-01",
                    "2023-04-01",
                    "2023-05-01",
                    "2023-06-01",
                    "2023-07-01",
                ],
            },
            tooltip: {
                x: {
                    format: "MMMM yyyy",
                },
            },
        };

        const sales_chart = new ApexCharts(
            document.querySelector("#revenue-chart"),
            sales_chart_options,
        );
        sales_chart.render();
    </script> <!-- jsvectormap -->
    <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js" integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js" integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY=" crossorigin="anonymous"></script> <!-- jsvectormap -->
    <!--end::Script-->
</body><!--end::Body-->
</html>