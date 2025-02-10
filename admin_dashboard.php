<?php
require './config/helper.php';
require  './config/database.php';
session_start();


if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}
 // Handle Create (Add) User/Admin
if (isset($_POST['add_user'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $dob = $_POST['dob'];
    $gender_id = $_POST['gender'];

    // Default password (hashed)
    $password = password_hash("123456", PASSWORD_BCRYPT);

     // Check if the email already exists
     $check_sql = "SELECT AMAIL FROM admin WHERE AMAIL = ?";
     $check_stmt = $db->prepare($check_sql);
     $check_stmt->bind_param('s', $email);
     $check_stmt->execute();
     $check_stmt->store_result();
 
     if ($check_stmt->num_rows > 0) {
         echo "<script>alert('User already exists!');</script>";
     } else {
         //  Insert into admin table
         $sql = "INSERT INTO admin (NAME, AMAIL, APASS, role, gender_id) VALUES (?, ?, ?, ?, ?)";
         $stmt = $db->prepare($sql);
         $stmt->bind_param('ssssi', $name, $email, $password, $role, $gender_id);
 
         if ($stmt->execute()) {
             // Insert into user_details table
             $sql2 = "INSERT INTO user_details (user_email, phone, address, dob, gender_id) VALUES (?, ?, ?, ?, ?)";
             $stmt2 = $db->prepare($sql2);
             $stmt2->bind_param('ssssi', $email, $phone, $address, $dob, $gender_id);
             $stmt2->execute();
             
             echo "<script>alert('User added successfully!');</script>";
         } else {
             echo "<script>alert('Error adding user. Try again!');</script>";
         }
     }
 }

// Handle Update User/Admin
if (isset($_POST['update_user'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $dob = $_POST['dob'];
    $gender_id = $_POST['gender'];

    $sql = "UPDATE admin SET NAME=?, AMAIL=?, role=?, gender_id=? WHERE AID=?";
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        die("SQL Prepare Error: " . $db->error);
    }
    $stmt->bind_param('sssii', $name, $email, $role, $gender_id, $id);
    $stmt->execute();

    $sql2 = "UPDATE user_details SET user_email=?, phone=?, address=?, dob=?, gender_id=? WHERE user_email=?";
    $stmt2 = $db->prepare($sql2);
    $stmt2->bind_param('ssssis', $email, $phone, $address, $dob, $gender_id, $email);
    $stmt2->execute();
}

// Handle Delete User/Admin
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Delete from admin table
    $sql = "DELETE FROM admin WHERE AID=?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();

    // Delete from user_details (if linked)
    $sql2 = "DELETE FROM user_details WHERE user_email=(SELECT AMAIL FROM admin WHERE AID=?)";
    $stmt2 = $db->prepare($sql2);
    $stmt2->bind_param('i', $id);
    $stmt2->execute();
}

// Fetch all users and admins
$sql = "SELECT 
            a.AID AS admin_id, a.NAME, a.AMAIL, a.role, a.gender_id AS admin_gender_id,
            u.user_email, u.phone, u.address, u.dob, u.gender_id AS user_gender_id,
            g.gender_name
        FROM admin a
        LEFT JOIN user_details u ON a.AMAIL = u.user_email
        LEFT JOIN gender g ON a.gender_id = g.id";

$result = $db->query($sql);

if (!$result) {
    die("SQL Error: " . $db->error);
}

?>
<?php require './views/partials/header.php'; ?>
<?php require  './views/partials/sidebar.php';
if (!file_exists('./views/partials/header.php')) {
    die("Error: header.php file not found!");
}
if (!file_exists('./views/partials/sidebar.php')) {
    die("Error: sidebar.php file not found!");
}
?> 
   <div class="container mt-5">
        <h2>Manage Users & Admins</h2>

        <!-- Add User Form -->
        <form method="POST">
            <h4>Add User</h4>
            <input type="text" name="name" placeholder="Name" required class="form-control">
            <input type="email" name="email" placeholder="Email" required class="form-control mt-2">
            <input type="text" name="phone" placeholder="Phone" class="form-control mt-2">
            <input type="text" name="address" placeholder="Address" class="form-control mt-2">
            <input type="date" name="dob" placeholder="DOB" class="form-control mt-2">
            <select name="gender" class="form-select mt-2">
                <option value="1">Male</option>
                <option value="2">Female</option>
            </select>
            <select name="role" class="form-select mt-2">
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
            <button type="submit" name="add_user" class="btn btn-success mt-2">Add</button>
        </form>

        <hr>

        <!--  Display Users & Admins -->
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>DOB</th>
                    <th>Gender</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <form method="POST">
                    <td><input type="text" name="name" value="<?php echo htmlspecialchars($row['NAME']); ?>" class="form-control"></td>
                    <td><input type="email" name="email" value="<?php echo htmlspecialchars($row['AMAIL']); ?>" class="form-control"></td>
                    <td><input type="text" name="phone" value="<?php echo htmlspecialchars($row['phone'] ?: ''); ?>" class="form-control"></td>
                    <td><input type="text" name="address" value="<?php echo htmlspecialchars($row['address'] ?: ''); ?>" class="form-control"></td>
                    <td><input type="date" name="dob" value="<?php echo htmlspecialchars($row['dob'] ?: ''); ?>" class="form-control"></td>
                    <td>
                        <select name="gender" class="form-select">
                            <option value="1" <?php echo ($row['admin_gender_id'] == 1) ? 'selected' : ''; ?>>Male</option>
                            <option value="2" <?php echo ($row['admin_gender_id'] == 2) ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </td>
                    <td>
                        <select name="role" class="form-select">
                            <option value="admin" <?php echo ($row['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                            <option value="user" <?php echo ($row['role'] == 'user') ? 'selected' : ''; ?>>User</option>
                        </select>
                    </td>
                    <td>
                        <input type="hidden" name="id" value="<?php echo $row['admin_id']; ?>">
                        <button type="submit" name="update_user" class="btn btn-primary btn-sm">Update</button>
                        <a href="javascript:void(0);" 
                            onclick="confirmDelete(<?php echo $row['admin_id']; ?>)" 
                            class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </form>
            </tr>
            <?php } ?>


            </tbody>
        </table>
    </div>
        <footer class="app-footer"> <!--begin::To the end-->
            <div class="float-end d-none d-sm-inline">Anything you want</div> <!--end::To the end--> <!--begin::Copyright--> <strong>
                Copyright &copy; 2014-2024&nbsp;
                <a href="#" class="text-decoration-none">Company name</a>.
            </strong>
            All rights reserved.
            <!--end::Copyright-->
        </footer> <!--end::Footer-->
    </div> <!--end::App Wrapper--> <!--begin::Script--> <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/browser/overlayscrollbars.browser.es6.min.js" integrity="sha256-H2VM7BKda+v2Z4+DRy69uknwxjyDRhszjXFhsL4gD3w=" crossorigin="anonymous"></script> <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha256-whL0tQWoY1Ku1iskqPFvmZ+CHsvmRWx/PIoEvIeWh4I=" crossorigin="anonymous"></script> <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha256-YMa+wAM6QkVyz999odX7lPRxkoYAan8suedu4k2Zur8=" crossorigin="anonymous"></script> <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="./js/adminlte.js"></script> <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
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
    </script> 
    <script>
    function confirmDelete(id) {
        const confirmation = confirm("Are you sure you want to delete this user?");
        if (confirmation) {
            // Redirect to the delete link with the admin ID
            window.location.href = "?delete=" + id;
        }
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js" integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8=" crossorigin="anonymous"></script> <!-- ChartJS -->
    <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js" integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js" integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY=" crossorigin="anonymous"></script> <!-- jsvectormap -->
</body><!--end::Body-->

</html>