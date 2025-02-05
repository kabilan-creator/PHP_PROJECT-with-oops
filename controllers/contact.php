<?php
require '../config/helper.php';
require '../config/database.php';
session_start();



if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === false) {
  header("Location: ../views/auth/login.php");

  exit();
}
?>

<!--end::Header--> <!--begin::Sidebar-->
<?php require '../views/partials/header.php' ?>
<?php require '../views/partials/sidebar.php' ?>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>CONTACT US</h1>
        </div>

      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">

    <!-- Default box -->
    <div class="card">
      <div class="card-body row">
        <div class="col-5 text-center d-flex align-items-center justify-content-center">
          <div class="">
            <h2>company name</h2>
            <p class="lead mb-5">123 Testing Ave, Testtown, 9876 NA<br>
              Phone: +1 234 56789012
            </p>
          </div>
        </div>
        <div class="col-7">
          <div class="form-group">
            <label for="inputName">Name</label>
            <input type="text" id="inputName" class="form-control">
          </div>
          <div class="form-group">
            <label for="inputEmail">E-Mail</label>
            <input type="email" id="inputEmail" class="form-control">
          </div>
          <div class="form-group">
            <label for="inputSubject">Subject</label>
            <input type="text" id="inputSubject" class="form-control">
          </div>
          <div class="form-group">
            <label for="inputMessage">Message</label>
            <textarea id="inputMessage" class="form-control" rows="4"></textarea>
          </div>
          <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Send message">
          </div>
        </div>
      </div>
    </div>

  </section>
  <!-- /.content -->
</div>

<footer class="app-footer"> <!--begin::To the end-->
  <div class="float-end d-none d-sm-inline">Anything you want</div> <!--end::To the end--> <!--begin::Copyright-->
  <strong>
    Copyright &copy; 2014-2024&nbsp;
    <a href=# class="text-decoration-none">Comapny name</a>.
  </strong>
  All rights reserved.
  <!--end::Copyright-->
</footer> <!--end::Footer-->
</div> <!--end::App Wrapper--> <!--begin::Script--> <!--begin::Third Party Plugin(OverlayScrollbars)-->
<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/browser/overlayscrollbars.browser.es6.min.js"
  integrity="sha256-H2VM7BKda+v2Z4+DRy69uknwxjyDRhszjXFhsL4gD3w=" crossorigin="anonymous"></script>
<!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
  integrity="sha256-whL0tQWoY1Ku1iskqPFvmZ+CHsvmRWx/PIoEvIeWh4I=" crossorigin="anonymous"></script>
<!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
  integrity="sha256-YMa+wAM6QkVyz999odX7lPRxkoYAan8suedu4k2Zur8=" crossorigin="anonymous"></script>
<!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
<script src="js/jquery.js"></script> <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
<script src="js/jquery.validate.min.js"> </script>
<script src="adminlte.min.js"></script>
<script>
  const SELECTOR_SIDEBAR_WRAPPER = ".sidebar-wrapper";
  const Default = {
    scrollbarTheme: "os-theme-light",
    scrollbarAutoHide: "leave",
    scrollbarClickScroll: true,
  };
  document.addEventListener("DOMContentLoaded", function () {
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
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"
  integrity="sha256-ipiJrswvAR4VAx/th+6zWsdeYmVae0iJuiR+6OqHJHQ=" crossorigin="anonymous"></script> <!-- sortablejs -->
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
</script> <!-- apexcharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
  integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8=" crossorigin="anonymous"></script> <!-- ChartJS -->
<script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js"
  integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js"
  integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY=" crossorigin="anonymous"></script> <!-- jsvectormap -->
</body><!--end::Body-->

</html>