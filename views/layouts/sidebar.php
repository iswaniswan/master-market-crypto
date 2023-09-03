<?php

use app\components\Session;
use app\widgets\UplonMenu;

$itemsDistributor = [
    ['label' => 'Distributor','header' => true],
    ['label' => 'Dashboard', 'icon' => 'ti-dashboard', 'url' => ['/dashboard/index-distributor']],
    ['label' => 'ROI', 'icon' => 'ti-reload', 'url' => ['/roi/index-distributor']],
    ['label' => 'Tickets', 'icon' => 'ti-ticket', 'url' => ['/fund-ticket/index-distributor']],
    ['label' => 'Downline', 'icon' => 'ti-link', 'items' => [
        ['label' => 'My Downlines', 'url' =>['/member/index-member-downline-binary']],
        ['label' => 'Binary Tree', 'url' =>['/member/index-member-binary-tree']],
        ['label' => 'Register Downline', 'url' =>['/member/create-member-downline']],
    ]],   
    ['label' => 'Deposit Member', 'icon' =>'ti-bag', 'url' =>['/deposit/index-distributor']],
    ['label' => 'Withdraw Member', 'icon' =>'ti-wallet', 'url' =>['/withdraw/index-distributor']],
    ['label' => 'Reward', 'icon' => 'ti-cup', 'items' => [
        ['label' => 'Daftar Reward', 'url' =>['/reward/index-member']],
        ['label' => 'Reward Member', 'url' =>['/reward-claimed']],
    ]],
//    ['label' => 'Reward Member', 'icon' =>'ti-crown', 'url' =>['/reward-claimed']],
//    ['label' => 'Reward', 'icon' => 'ti-cup', 'url' => ['/reward/index-member']],
    ['label' => 'Fund Statement', 'icon' => 'ti-book', 'url' => ['/member/index-fund-statement']],
    ['label' => 'Logout', 'icon'=>'ti-shift-right', 'url' => ['/site/logout'], 'template'=>'<a class="nav-link {active}" data-method="post" href="{url}" {target}>{icon} {label}</a>'],
];

$itemsAdmin = [
    ['label' => 'Administrator','header' => true],
    ['label' => 'Dashboard', 'icon' => 'ti-dashboard', 'url' => ['/dashboard/index']],
//    ['label' => 'ROI', 'icon' => 'ti-reload', 'items' => [
//        ['label' => 'Setting', 'url' => ['/roi/index-admin']],
//        ['label' => 'Riwayat', 'url' => ['/roi/index-history-daily']],
//    ]],
    ['label' => 'Transactions', 'icon' => 'icon-shuffle', 'url' => ['/wallet/index']],
//    ['label' => 'Distributors', 'icon' => 'ti-home', 'items' => [
//        ['label' => 'My Distributors', 'url' =>['/member/index-admin-distributor']],
//        ['label' => 'Register Distributor', 'url' =>['/member/create-admin-distributor']],
//    ]],
//    ['label' => 'Downlines', 'icon' => 'ti-link', 'items' => [
//        ['label' => 'My Downlines', 'url' =>['/member/index-admin-downline']],
//        ['label' => 'Binary Tree', 'url' =>['/member/index-member-binary-tree']],
//        ['label' => 'Register Downline', 'url' =>['/member/create-member-downline']],
//    ]],
//    ['label' => 'Deposit Member', 'icon' =>'ti-bag', 'url' =>['/deposit/index-admin']],
//    ['label' => 'Withdraw Member', 'icon' =>'ti-wallet', 'url' =>['/withdraw/index-admin']],
//    ['label' => 'Reward', 'icon' => 'ti-cup', 'items' => [
//        ['label' => 'Daftar Reward', 'url' =>['/reward/index-member']],
//        ['label' => 'Reward Member', 'url' =>['/reward-claimed']],
//    ]],
    ['label' => 'Master', 'icon' => 'ti-settings', 'items' => [
        ['label' => 'Crypto', 'icon' => 'icon-drop', 'url' => ['/crypto/index']],
    ]],
    ['label' => 'Logout', 'icon'=>'ti-shift-right', 'url' => ['/site/logout'], 'template'=>'<a class="nav-link {active}" data-method="post" href="{url}" {target}>{icon} {label}</a>'],
];

if (Session::isAdmin() === false) {
    $itemsAdmin = [];
}

if ((Session::isDistributor() === false) or (Session::isDistributor() and Session::isMemberActive() == false)) {
    $itemsDistributor = [];
}

$items = [
    ['label' => 'Member','header' => true],
    ['label' => 'Dashboard', 'icon' => 'ti-dashboard', 'url' => ['/dashboard/index-member']],
    ['label' => 'Market', 'icon' => 'icon-drop', 'url' => ['/crypto/index']],
    ['label' => 'Wallet', 'icon' => 'icon-wallet', 'url' => ['/wallet/index']],
//    ['label' => 'Tickets', 'icon' => 'ti-ticket', 'url' => ['/fund-ticket/index-member']],
//    ['label' => 'Deposit', 'icon' => 'ti-bag', 'items' => [
//        ['label' => 'Tambah', 'url' =>['/deposit/create-member']],
//        ['label' => 'Riwayat', 'url' =>['/deposit/history-member']],
//    ]],
//    ['label' => 'Withdraw', 'icon' => 'ti-wallet', 'items' => [
//        ['label' => 'Active', 'url' =>['/withdraw/create-active']],
//        ['label' => 'Passive', 'url' =>['/withdraw/create-passive']],
//        ['label' => 'Riwayat', 'url' =>['/withdraw/history-member']],
//    ]],
//    ['label' => 'Downline', 'icon' => 'ti-link', 'items' => [
//        ['label' => 'My Downlines', 'url' =>['/member/index-member-downline-binary']],
//        ['label' => 'Binary Tree', 'url' =>['/member/index-member-binary-tree']],
//        ['label' => 'Register Downline', 'url' =>['/member/create-member-downline']],
//    ]],
//    ['label' => 'Reward', 'icon' => 'ti-cup', 'url' => ['/reward/index-member']],
//    ['label' => 'Fund Statement', 'icon' => 'ti-book', 'url' => ['/member/index-fund-statement']],
    ['label' => 'Logout', 'icon'=>'ti-shift-right', 'url' => ['/site/logout'], 'template'=>'<a class="nav-link {active}" data-method="post" href="{url}" {target}>{icon} {label}</a>'],
];

/** sementara hilangkan item member di sidebar admin */
if (Session::isAdmin() or Session::isDistributor()) {
    $items = [];
}

foreach ($itemsAdmin as $item) {
    array_push($items, $item);
}

foreach ($itemsDistributor as $item) {
    array_push($items, $item);
}

?>


<div class="left-side-menu">
    <div class="slimscroll-menu">
        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <?= UplonMenu::widget([
                'items' => $items
            ]) ?>
        </div>
        <!-- End Sidebar -->
        <div class="clearfix"></div>
    </div>
    <!-- Sidebar -left -->
</div>


<?php /*
 <div class="left-side-menu">
    <div class="slimscroll-menu">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul class="metismenu" id="side-menu">
                <li class="menu-title">Navigation</li>
                <li>
                    <a href="index.html">
                        <i class="mdi mdi-view-dashboard"></i>
                        <span> Dashboard </span>
                    </a>
                </li>
                <li>
                    <a href="calendar.html">
                        <i class="mdi mdi-calendar-month"></i>
                        <span> Calendar </span>
                    </a>
                </li>
                <li>
                    <a href="javascript: void(0);">
                        <i class="mdi mdi-flip-horizontal"></i>
                        <span> Layouts </span>
                        <span class="badge badge-danger badge-pill float-right">New</span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="layouts-dark-sidebar.html">Dark Sidebar</a></li>
                        <li><a href="layouts-small-sidebar.html">Small Sidebar</a></li>
                        <li><a href="layouts-sidebar-collapsed.html">Sidebar Collapsed</a></li>
                        <li><a href="layouts-unsticky.html">Unsticky Layout</a></li>
                        <li><a href="layouts-boxed.html">Boxed Layout</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);">
                        <i class="mdi mdi-google-pages"></i>
                        <span> Pages </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="pages-starter.html">Starter Page</a></li>
                        <li><a href="pages-login.html">Login</a></li>
                        <li><a href="pages-register.html">Register</a></li>
                        <li><a href="pages-recoverpw.html">Recover Password</a></li>
                        <li><a href="pages-lock-screen.html">Lock Screen</a></li>
                        <li><a href="pages-404.html">Error 404</a></li>
                        <li><a href="pages-500.html">Error 500</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);">
                        <i class="mdi mdi-content-copy"></i>
                        <span> Extra Pages </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="pages-timeline.html">Timeline</a></li>
                        <li><a href="pages-invoice.html">Invoice</a></li>
                        <li><a href="pages-pricing.html">Pricing</a></li>
                        <li><a href="pages-gallery.html">Gallery</a></li>
                        <li><a href="pages-maintenance.html">Maintenance</a></li>
                        <li><a href="pages-comingsoon.html">Coming Soon</a></li>
                    </ul>
                </li>
                <li class="menu-title mt-2">Components</li>
                <li>
                    <a href="javascript: void(0);">
                        <i class="mdi mdi-format-underline"></i>
                        <span> User Interface </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="ui-buttons.html">Buttons</a></li>
                        <li><a href="ui-cards.html">Cards</a></li>
                        <li><a href="ui-dropdowns.html">Dropdowns</a></li>
                        <li><a href="ui-checkbox-radio.html">Checkboxs-Radios</a></li>
                        <li><a href="ui-navs.html">Navs</a></li>
                        <li><a href="ui-progress.html">Progress</a></li>
                        <li><a href="ui-modals.html">Modals</a></li>
                        <li><a href="ui-notification.html">Notification</a></li>
                        <li><a href="ui-alerts.html">Alerts</a></li>
                        <li><a href="ui-carousel.html">Carousel</a></li>
                        <li><a href="ui-bootstrap.html">Bootstrap UI</a></li>
                        <li><a href="ui-typography.html">Typography</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);">
                        <i class="mdi mdi-package-variant-closed"></i>
                        <span> Components </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="components-grid.html">Grid</a></li>
                        <li><a href="components-range-sliders.html">Range sliders</a></li>
                        <li><a href="components-sweet-alert.html">Sweet Alerts</a></li>
                        <li><a href="components-ratings.html">Ratings</a></li>
                        <li><a href="components-treeview.html">Treeview</a></li>
                        <li><a href="components-tour.html">Tour</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);">
                        <i class="mdi mdi-puzzle-outline"></i>
                        <span> Widgets </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="widgets-tiles.html">Tile Box</a></li>
                        <li><a href="widgets-charts.html">Chart Widgets</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);">
                        <i class="mdi mdi-black-mesa"></i>
                        <span> Icons </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="icons-materialdesign.html">Material Design</a></li>
                        <li><a href="icons-ionicons.html">Ion Icons</a></li>
                        <li><a href="icons-fontawesome.html">Font awesome</a></li>
                        <li><a href="icons-themify.html">Themify Icons</a></li>
                        <li><a href="icons-simple-line.html">Simple line Icons</a></li>
                        <li><a href="icons-weather.html">Weather Icons</a></li>
                        <li><a href="icons-pe7.html">PE7 Icons</a></li>
                        <li><a href="icons-typicons.html">Typicons</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);">
                        <i class="mdi mdi-file-document-box-check-outline"></i>
                        <span class="badge badge-warning badge-pill float-right">8</span>
                        <span> Forms </span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="form-elements.html">General Elements</a></li>
                        <li><a href="form-advanced.html">Advanced Form</a></li>
                        <li><a href="form-validation.html">Form Validation</a></li>
                        <li><a href="form-pickers.html">Form Pickers</a></li>
                        <li><a href="form-wizard.html">Form Wizard</a></li>
                        <li><a href="form-mask.html">Form Masks</a></li>
                        <li><a href="form-uploads.html">Multiple File Upload</a></li>
                        <li><a href="form-xeditable.html">X-editable</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);">
                        <i class="mdi mdi-table-settings"></i>
                        <span> Tables </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="tables-basic.html">Basic Tables</a></li>
                        <li><a href="tables-datatable.html">Data Tables</a></li>
                        <li><a href="tables-responsive.html">Responsive Table</a></li>
                        <li><a href="tables-tablesaw.html">Tablesaw</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);">
                        <i class="mdi mdi-poll"></i>
                        <span> Charts </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="charts-flot.html">Flot Charts</a></li>
                        <li><a href="charts-morris.html">Morris Charts</a></li>
                        <li><a href="charts-chartjs.html">Chartjs</a></li>
                        <li><a href="charts-peity.html">Peity Charts</a></li>
                        <li><a href="charts-chartist.html">Chartist Charts</a></li>
                        <li><a href="charts-c3.html">C3 Charts</a></li>
                        <li><a href="charts-sparkline.html">Sparkline Charts</a></li>
                        <li><a href="charts-knob.html">Jquery Knob</a></li>
                    </ul>
                </li>
                <li class="menu-title mt-2">More</li>
                <li>
                    <a href="javascript: void(0);">
                        <i class="mdi mdi-share-variant"></i>
                        <span> Multi Level </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level nav" aria-expanded="false">
                        <li>
                            <a href="javascript: void(0);">Level 1.1</a>
                        </li>
                        <li>
                            <a href="javascript: void(0);" aria-expanded="false">Level 1.2
                                <span class="menu-arrow"></span>
                            </a>
                            <ul class="nav-third-level nav" aria-expanded="false">
                                <li>
                                    <a href="javascript: void(0);">Level 2.1</a>
                                </li>
                                <li>
                                    <a href="javascript: void(0);">Level 2.2</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- End Sidebar -->
        <div class="clearfix"></div>
    </div>
    <!-- Sidebar -left -->
</div>
*/ ?>