<?php
require './config/database.php'; 
require './config/helper.php';
session_start();

// Ensure user is logged in as an admin
if (!isset($_SESSION['AMAIL']) || $_SESSION['role'] != 'admin') {
    die("Access Denied. Please login as an admin.");
}

$email = $_SESSION['AMAIL'];

if (!$db) {
    die("Database connection error: " . mysqli_connect_error());
}
// Fetch the admin's user ID from the user_details table
$sql = "SELECT id FROM user_details WHERE user_email = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("Error: Admin user not found in user_details.");
}

$user_id = $user['id'];

// Fetch employees list
$employees = $db->query("SELECT id, user_email FROM user_details");

// Fetch projects list
$projects = $db->query("SELECT id, name FROM projects");

// Handle project assignment
if (isset($_POST['assign_project'])) {
    $employee_id = $_POST['employee_id'];
    $project_id = $_POST['project_id'];

    $sql = "INSERT INTO employee_projects (user_id, project_id, assigned_by) VALUES (?, ?, ?)";
    $stmt = $db->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("iii", $employee_id, $project_id, $user_id );
        $stmt->execute();
        $_SESSION['message'] = "Project Assign successfully!";
        $_SESSION['msg_type'] = "success";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $_SESSION['message'] = "Error Assign project. Try again!";
        $_SESSION['msg_type'] = "danger";
        die("SQL Error: " . $db->error);
    }
}

// Handle new project creation
if (isset($_POST['add_project'])) {
    $project_name = $_POST['project_name'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $status = $_POST['status'];

    $sql = "INSERT INTO projects (name, description, start_date, end_date, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sssss", $project_name, $description, $start_date, $end_date, $status);
        $stmt->execute();
        $_SESSION['message'] = "Project added successfully!";
        $_SESSION['msg_type'] = "success";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $_SESSION['message'] = "Error adding project. Try again!";
        $_SESSION['msg_type'] = "danger";
        die("SQL Error: " . $db->error);
    }
}

// Fetch all assigned projects
$sql = "SELECT ep.id, e.user_email, p.name, p.description, p.start_date, p.end_date, p.status 
        FROM employee_projects ep
        INNER JOIN user_details e ON ep.user_id = e.id
        INNER JOIN projects p ON ep.project_id = p.id";
$assignedProjects = $db->query($sql);
if (!$assignedProjects) {
    die("Error fetching assigned projects: " . $db->error);
}
// Handle project removal
if (isset($_POST['remove_project'])) {
    $remove_project_id = $_POST['remove_project_id'];

    $sql = "DELETE FROM employee_projects WHERE id = ?";
    $stmt = $db->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $remove_project_id);
        $stmt->execute();
        $_SESSION['message'] = "User deleted successfully!";
        $_SESSION['msg_type'] = "danger";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        die("SQL Error: " . $db->error);
    }
}
?>

<?php require './views/partials/header.php'; ?>
<?php require './views/partials/sidebar.php'; ?>

<div class="container mt-5">
        <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['msg_type']; ?> alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['message']); unset($_SESSION['msg_type']); ?>
        <?php endif; ?>
    <h2>Project Management</h2>

    <!-- Add Project Form -->
    <h3>Add New Project</h3>
    <form method="post" class="mt-3">
        <label>Project Name:</label>
        <input type="text" name="project_name" class="form-control" required>

        <label class="mt-2">Description:</label>
        <textarea name="description" class="form-control" required></textarea>

        <label class="mt-2">Start Date:</label>
        <input type="date" name="start_date" class="form-control" required>

        <label class="mt-2">End Date:</label>
        <input type="date" name="end_date" class="form-control" required>

        <label class="mt-2">Status:</label>
        <select name="status" class="form-control" required>
            <option value="Active">Active</option>
            <option value="Completed">Completed</option>
            <option value="On Hold">On Hold</option>
        </select>

        <button type="submit" name="add_project" class="btn btn-success mt-3">Add Project</button>
    </form>

    <!-- Assign Project Form -->
    <h3 class="mt-5">Assign Project</h3>
    <form method="post" class="mt-3">
        <label>Select Employee:</label>
        <select name="employee_id" class="form-control" required>
            <?php foreach ($employees as $emp): ?>
                <option value="<?= $emp['id']; ?>"> <?= htmlspecialchars($emp['user_email']); ?> </option>
            <?php endforeach; ?>
        </select>

        <label class="mt-2">Select Project:</label>
        <select name="project_id" class="form-control" required>
            <?php foreach ($projects as $project): ?>
                <option value="<?= $project['id']; ?>"><?php echo htmlspecialchars($project['name']); ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="assign_project" class="btn btn-primary mt-3">Assign</button>


    </form>

    <!-- Display Assigned Projects -->
    <h3 class="mt-5">Assigned Projects</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Project Name</th>
                <th>Description</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
// Check if the query returned a valid result set
if ($assignedProjects && $assignedProjects->num_rows > 0) {
    // Convert the result to an array
    $assignedProjects = $assignedProjects->fetch_all(MYSQLI_ASSOC);
    
    foreach ($assignedProjects as $project): ?>
        <tr>
            
            <td><?php echo htmlspecialchars($project['user_email']); ?></td>
            <td><?php echo htmlspecialchars($project['name']); ?></td>
            <td><?php echo htmlspecialchars($project['description']); ?></td>
            <td><?php echo htmlspecialchars($project['start_date']); ?></td>
            <td><?php echo htmlspecialchars($project['end_date']); ?></td>
            <td><?php echo htmlspecialchars($project['status']); ?></td>
                 <td>
                    <form method="post" class="d-inline" onsubmit="return confirmRemove()">
                        <input type="hidden" name="remove_project_id" value="<?= $project['id']; ?>">
                        <button type="submit" name="remove_project" class="btn btn-danger btn-sm">Remove</button>
                    </form>
                </td>
        </tr>
    <?php endforeach; 
} else {
    echo "<tr><td colspan='6'>No assigned projects found.</td></tr>";
}
?>

        </tbody>
    </table>
</div>
<footer class="app-footer">
    <div class="float-end d-none d-sm-inline">Anything you want</div>
    <strong>
        Copyright &copy; 2014-2024&nbsp;
        <a href="#" class="text-decoration-none">Company name</a>.
    </strong>
    All rights reserved.
</footer> <!--end::Footer--><!--end::App Wrapper--> <!--begin::Script--> <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/browser/overlayscrollbars.browser.es6.min.js" integrity="sha256-H2VM7BKda+v2Z4+DRy69uknwxjyDRhszjXFhsL4gD3w=" crossorigin="anonymous"></script> <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha256-whL0tQWoY1Ku1iskqPFvmZ+CHsvmRWx/PIoEvIeWh4I=" crossorigin="anonymous"></script> <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha256-YMa+wAM6QkVyz999odX7lPRxkoYAan8suedu4k2Zur8=" crossorigin="anonymous"></script> <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="/assets/js/adminlte.js"></script> <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
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
    // JavaScript function to confirm the removal
    function confirmRemove() {
        return confirm("Are you sure you want to remove this project?");
    }
</script>
<script>
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        let alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            alert.style.transition = "opacity 0.5s ease-out";
            alert.style.opacity = "0";
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
    </script>