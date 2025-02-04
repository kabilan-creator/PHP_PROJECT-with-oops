<?php
require './config/helper.php';
require  './config/database.php';
session_start();


if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === false) {
    header("Location:" .url('/views/auth/login.php'));

    exit();
}
require './views/partials/header.php';  
?>

<?php require  './views/partials/sidebar.php';
if (!file_exists('./views/partials/header.php')) {
    die("Error: header.php file not found!");
}
if (!file_exists('./views/partials/sidebar.php')) {
    die("Error: sidebar.php file not found!");
}
?>      
       <!--end::Sidebar--> <!--begin::App Main-->
        <section class="about-us-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h2>About Us</h2>
                        <p>
                            At <b>YourCompany</b>,Lorem ipsum dolor sit, amet consectetur adipisicing elit. Hic consequatur reiciendis, porro error non nulla blanditiis perferendis architecto adipisci nemo nam labore corrupti eligendi earum a, quisquam voluptatibus quia officia?
                        </p>
                        <p>
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Incidunt fugit eveniet ea fugiat deleniti soluta, sed ratione iusto quo quod enim repellendus ullam atque est quos tempora consequatur harum ut sit magni. Magnam dolor temporibus error aliquid nihil eveniet officia, eaque fugiat, sunt quidem laudantium excepturi consequuntur doloribus repellat! Praesentium numquam sed, consectetur tempore ratione, quam quisquam a enim aspernatur harum blanditiis, reiciendis commodi maxime cumque vitae doloremque? Voluptatem quos tenetur vitae reprehenderit maiores cupiditate, in similique, praesentium voluptate sint perspiciatis rem expedita iusto non illo magni impedit architecto adipisci aperiam quas. Est amet, praesentium ratione velit reiciendis magnam et.
                        </p>
                    </div>
                    <div class="col-md-6 text-center">
                        <img src="<?php echo url('/img/photo1.png'); ?>" alt="About Us" class="about-image">
                    </div>
                </div>
            </div>
        </section>

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
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js" integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8=" crossorigin="anonymous"></script> <!-- ChartJS -->
    <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js" integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js" integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY=" crossorigin="anonymous"></script> <!-- jsvectormap -->
</body><!--end::Body-->

</html>