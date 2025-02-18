<?php
require './config/database.php'; 
require './config/helper.php';
session_start();

// Ensure user is logged in as an employee
if (!isset($_SESSION['AMAIL']) || $_SESSION['role'] != 'employee') {
    die("Access Denied. Please login as an employee.");
}

$email = $_SESSION['AMAIL'];

if (!$db) {
    die("Database connection error: " . mysqli_connect_error());
}

// Fetch user_id from user_details
$sql = "SELECT id FROM user_details WHERE user_email = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("Error: Employee not found.");
}

$user_id = $user['id'];

// Fetch assigned projects
$sql = "SELECT projects.name, projects.description, projects.start_date, projects.end_date, projects.status 
        FROM projects
        INNER JOIN employee_projects ON projects.id = employee_projects.project_id
        WHERE employee_projects.user_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if any projects were found
$projects = [];
$projects = $result->fetch_all(MYSQLI_ASSOC);

// Close statement
$stmt->close();

// Now, $projects is an array containing all project data
?>
<?php require './views/partials/header.php'; ?>
<?php require './views/partials/sidebar.php'; ?>
<div class="container mt-5">
    <h2>Your Assigned Projects</h2>
    <ul class="list-group">
    <?php foreach ($projects as $project): ?>
        <li class="list-group-item">
            <strong><?php echo htmlspecialchars($project['name']); ?></strong><br>
            <p><?php echo htmlspecialchars($project['description']); ?></p>
            <p><strong>Start Date:</strong> <?php echo htmlspecialchars($project['start_date']); ?></p>
            <p><strong>End Date:</strong> <?php echo htmlspecialchars($project['end_date']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($project['status']); ?></p>
        </li>
    <?php endforeach; ?>
</ul>
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
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js" integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8=" crossorigin="anonymous"></script> <!-- ChartJS -->
    <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js" integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js" integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY=" crossorigin="anonymous"></script> <!-- jsvectormap -->
</body><!--end::Body-->

</html>