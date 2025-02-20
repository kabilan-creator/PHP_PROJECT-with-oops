<?php
require '../config/helper.php';
require  '../config/database.php';
session_start();

// Check if the user is logged in

if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === false) {
  header("Location:" . url("/views/auth/login.php"));

  exit();
}

if (!isset($_SESSION['AMAIL'])) {
  die("Unauthorized access. Please log in.");
}

$user_email = $_SESSION['AMAIL'];

// Fetch admin and user details in a single query
$sql = "SELECT a.*, u.phone, u.address, u.dob, u.gender_id, g.gender_name 
        FROM admin a
        LEFT JOIN user_details u ON a.AMAIL = u.user_email
        LEFT JOIN gender g ON u.gender_id = g.id
        WHERE a.AMAIL = ? LIMIT 1";
if ($stmt = $db->prepare($sql)) {
  $stmt->bind_param('s', $user_email);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();
  $stmt->close();
} else {
  die("Database error: " . $db->error);
}

if (!$user) {
    die("User not found.");
}

$gender_display = $user['gender_name'] ?? 'N/A';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? "Unknown";
    $phone = $_POST['phone'] ?? "N/A";
    $address = $_POST['address'] ?? "N/A";
    $dob = $_POST['dob'] ?? "N/A";
    $gender = $_POST['gender'] ?? null;
// Update admin and user_details using a single transaction
$db->begin_transaction();
try {
    $update_sql = "UPDATE admin SET NAME = ?, gender_id = ? WHERE AMAIL = ?";
    $update_stmt = $db->prepare($update_sql);
    $update_stmt->bind_param('sis', $name, $gender, $user_email);
    $update_stmt->execute();
    $update_stmt->close();

    $details_update_sql = "INSERT INTO user_details (user_email, phone, address, dob, gender_id)
        VALUES (?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE phone = VALUES(phone), address = VALUES(address), dob = VALUES(dob), gender_id = VALUES(gender_id)";
    $details_update_stmt = $db->prepare($details_update_sql);
    $details_update_stmt->bind_param('sssss', $user_email, $phone, $address, $dob, $gender);
    $details_update_stmt->execute();
    $details_update_stmt->close();

    $db->commit();
    $success = "Profile updated successfully.";

    // Re-fetch updated data
    if ($stmt = $db->prepare($sql)) {
        $stmt->bind_param('s', $user_email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $gender_display = $user['gender_name'] ?? 'N/A';
    }
} catch (Exception $e) {
    $db->rollback();
    $error = "Error updating profile: " . $e->getMessage();
}
}

?>
<!DOCTYPE html>
<html lang="en"> <!--begin::Head-->

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>AdminLTE v4 | Dashboard</title><!--begin::Primary Meta Tags-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="title" content="AdminLTE v4 | Dashboard">
  <meta name="author" content="ColorlibHQ">
  <meta name="description" content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS.">
  <meta name="keywords" content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard"><!--end::Primary Meta Tags--><!--begin::Fonts-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous"><!--end::Fonts--><!--begin::Third Party Plugin(OverlayScrollbars)-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/styles/overlayscrollbars.min.css" integrity="sha256-dSokZseQNT08wYEWiz5iLI8QPlKxG+TswNRD8k35cpg=" crossorigin="anonymous"><!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Third Party Plugin(Bootstrap Icons)-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" integrity="sha256-Qsx5lrStHZyR9REqhUF8iQt73X06c8LGIUPzpOhwRrI=" crossorigin="anonymous"><!--end::Third Party Plugin(Bootstrap Icons)--><!--begin::Required Plugin(AdminLTE)-->
  <link rel="stylesheet" href='<?php echo url('/assets/css/adminlte.css') ?>'><!--end::Required Plugin(AdminLTE)--><!-- apexcharts -->
  <link rel="stylesheet" href='<?php echo url('/assets/css/adminlte.min.css') ?>'>
  <style>
    .float-sm-right {
      float: right !important;
    }
  </style>

</head> <!--end::Head--> <!--begin::Body-->


<body class="layout-fixed sidebar-expand-lg bg-body-tertiary"> <!--begin::App Wrapper-->
  <div class="app-wrapper"> <!--begin::Header-->
    <nav class="app-header navbar navbar-expand bg-body"> <!--begin::Container-->
      <div class="container-fluid"> <!--begin::Start Navbar Links-->
        <ul class="navbar-nav">
          <li class="nav-item"> <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"> <i class="bi bi-list"></i> </a> </li>
        </ul> <!--end::Start Navbar Links--> <!--begin::End Navbar Links-->
        <ul class="navbar-nav ms-auto"> <!--begin::Navbar Search-->
          <li class="nav-item">
            <a href='<?php echo url('/views/auth/logout.php') ?>' class="btn btn-danger nav-link text-white px-3">Logout</a>
          </li> <!--end::Notifications Dropdown Menu--> <!--begin::Fullscreen Toggle-->
        </ul> <!--end::End Navbar Links-->
      </div> <!--end::Container-->
    </nav> <!--end::Header--> <!--begin::Sidebar-->
    <?php require '../views/partials/sidebar.php' ?>
    <?php if (isset($success)) : ?>
      <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>

    <?php if (isset($error)) : ?>
      <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>MY PROFILE</h1>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-3">

              <!-- Profile Image -->
              <div class="card card-primary card-outline">
                <div class="card-body box-profile">

                  <h2 class="profile-username text-center">
                    <?php echo htmlspecialchars($user['NAME']); ?></p>
                  </h2>
                  <strong><i class="fas fa-book mr-1 text-center"></i></strong>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->

              <!-- About Me Box -->
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">About Me</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <strong><i class="fas fa-book mr-1"></i>EMAIL</strong>

                  <p class="text-muted">
                    <?php echo htmlspecialchars($user['AMAIL']); ?>
                  </p>

                  <hr>

                  <strong><i class="fas fa-map-marker-alt mr-1"></i>PHONE NUMBER</strong>

                  <p class="text-muted"><?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></p>

                  <hr>

                  <strong><i class="fas fa-pencil-alt mr-1"></i> DATE OF BIRTH</strong>

                  <p class="text-muted">
                    <?php echo htmlspecialchars($user['dob'] ?? 'N/A'); ?>
                  </p>

                  <hr>

                  <strong><i class="far fa-file-alt mr-1"></i>ADDRESS</strong>

                  <p class="text-muted">
                    <?php echo htmlspecialchars($user['address'] ?? 'N/A'); ?>
                  </p>
                  <hr>
                  <strong><i class="fas fa-user mr-1"></i> GENDER</strong>
                  <p class="text-muted"><?php echo htmlspecialchars($gender_display); ?></p>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
            <!-- /.col -->
            <div class="col-md-9">
              <div class="card">
                <div class="card-header p-2">
                  <ul class="nav nav-pills">
                    <li class="col-sm-2 col-form-label"><strong>EDIT PAGE</strong></li>
                  </ul>
                </div><!-- /.card-header -->
                <div class="card-body">
                  <div class="tab-content">
                    <!-- /.tab-pane -->

                    <!-- /.tab-pane -->

                    <div class="tab-pane active" id="settings">
                      <form class="form-horizontal" method="post">
                        <div class="form-group row">
                          <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                          <div class="col-sm-10">
                          <input type="text" class="form-control" id="name" name="name" 
                          value="<?php echo htmlspecialchars($user['NAME'] ?? '', ENT_QUOTES); ?>">
                          </div>
                        </div>
                         <br>
                          <div class="form-group row">
                          <label for="inputExperience" class="col-sm-2 col-form-label">Phone Number</label>
                          <div class="col-sm-10">
                            <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                          </div>
                          </div>
                          <br>

                      <!-- Address -->
                        <div class="form-group row">
                          <label for="inputName2" class="col-sm-2 col-form-label">Address</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>">
                          </div>
                        </div>
                        <br>

                      <!-- Date of Birth -->
                        <div class="form-group row">
                          <label for="inputEmail" class="col-sm-2 col-form-label">Date of Birth</label>
                          <div class="col-sm-10">
                            <input type="date" class="form-control" id="dob" name="dob" value="<?php echo htmlspecialchars($user['dob']); ?>">
                          </div>
                        </div>
                        <br>

                        <?php
                        $gender_query = "SELECT * FROM gender";
                        $gender_result = $db->query($gender_query);
                        ?>
                        <div class="form-group row">
                          <label for="gender" class="col-sm-2 col-form-label">Gender</label>
                          <div class="col-sm-10">
                            <select class="form-control" id="gender" name="gender">
                              <?php while ($gender = $gender_result->fetch_assoc()): ?>
                                <option value="<?php echo $gender['id']; ?>"
                                <?php echo (!empty($user['gender_id']) && $user['gender_id'] == $gender['id']) ? 'selected' : ''; ?>>
                                <?php echo $gender['gender_name']; ?>
                                </option>
                              <?php endwhile; ?>
                            </select>
                          </div>
                        </div>
                        <br>
                        <div class="form-group row">
                          <div class="offset-sm-2 col-sm-10">
                            <button type="submit" class="btn btn-danger">Submit</button>
                          </div>
                        </div>
                      </form>
                    </div>
                    <!-- /.tab-pane -->
                  </div>
                  <!-- /.tab-content -->
                </div><!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div><!-- /.container-fluid -->
      </section>
      <!-- /.content -->
    </div>
  </div>
  </div>
  <footer class="app-footer"> <!--begin::To the end-->
    <div class="float-end d-none d-sm-inline">Anything you want</div> <!--end::To the end--> <!--begin::Copyright--> <strong>
      Copyright &copy; 2014-2024&nbsp;
      <a href=# class="text-decoration-none">Comapny name</a>.
    </strong>
    All rights reserved.
    <!--end::Copyright-->
  </footer>

  <!-- /.login-box -->

  <!-- jQuery -->
  <script src="jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/browser/overlayscrollbars.browser.es6.min.js" integrity="sha256-H2VM7BKda+v2Z4+DRy69uknwxjyDRhszjXFhsL4gD3w=" crossorigin="anonymous"></script> <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha256-whL0tQWoY1Ku1iskqPFvmZ+CHsvmRWx/PIoEvIeWh4I=" crossorigin="anonymous"></script> <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha256-YMa+wAM6QkVyz999odX7lPRxkoYAan8suedu4k2Zur8=" crossorigin="anonymous"></script> <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
  <script src="js/jquery.js"></script> <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
  <script src="js/jquery.validate.min.js"> </script>
  <script src="assets/js/adminlte.min.js"></script>
  <script>
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
  <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js" integrity="sha256-ipiJrswvAR4VAx/th+6zWsdeYmVae0iJuiR+6OqHJHQ=" crossorigin="anonymous"></script> <!-- sortablejs --> <!-- apexcharts -->
  <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js" integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8=" crossorigin="anonymous"></script> <!-- ChartJS -->
  <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js" integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js" integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY=" crossorigin="anonymous"></script> <!-- jsvectormap -->
</body><grammarly-desktop-integration data-grammarly-shadow-root="true"></grammarly-desktop-integration>

</html>