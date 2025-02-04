<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark"> <!--begin::Sidebar Brand-->
    <div class="sidebar-brand"> <!--begin::Brand Link--> <a href="./index.html" class="brand-link"> <!--begin::Brand Image--> <img src="<?php echo url('/img/AdminLTELogo.png'); ?>" alt="AdminLTE Logo" class="brand-image opacity-75 shadow"> <!--end::Brand Image--> <!--begin::Brand Text--> <span class="brand-text fw-light">PHP PROJECT</span> <!--end::Brand Text--> </a> <!--end::Brand Link--> </div> <!--end::Sidebar Brand--> <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2"> <!--begin::Sidebar Menu-->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                <li class="nav-item"> <a href='<?php echo url('/index.php') ?>' class="nav-link"> <i class="far fa-circle nav-icon"></i>
                        <p>HOME</p>
                    </a> </li>
                <li class="nav-item"> <a href='<?php echo url('/controllers/Services.php') ?>'class="nav-link"> <i class="far fa-circle nav-icon"></i>
                        <p>SERVICES</p>
                    </a> 
                </li>
                <li class="nav-item"> <a href='<?php echo url('/controllers/profile_page.php') ?> 'class="nav-link"> <i class="far fa-circle nav-icon"></i>
                    <p>PROFILE PAGE</p>
                </a> 
                </li>
                <li class="nav-item"> <a href='<?php echo url('/controllers/contact.php') ?>'class="nav-link"> <i class="far fa-circle nav-icon"></i>
                    <p>CONTACT US</p>
                </a> 
                </li>
            </ul> <!--end::Sidebar Menu-->
        </nav>
    </div> <!--end::Sidebar Wrapper-->
</aside> 