<div class="clearfix">
    </div>
    <!-- BEGIN CONTAINER -->
    <div class="page-container">
        <!-- BEGIN SIDEBAR -->
        <div class="page-sidebar-wrapper">
            <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
            <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
            <div class="page-sidebar navbar-collapse collapse">
                <!-- BEGIN SIDEBAR MENU -->
                <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
                <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
                <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
                <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
                <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                <ul class="page-sidebar-menu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
                    <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
                    <li class="sidebar-toggler-wrapper text-center">
                      <img  class="logo-dashboard d-inline-block" alt="Logo" src="{{asset("assets")}}/images/logo-dashboard.png" />
                        <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                        <div class="sidebar-toggler">
                        </div>
                        <!-- END SIDEBAR TOGGLER BUTTON -->
                    </li>
                    <!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->
                    <li class="sidebar-search-wrapper">
                        <!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
                        <!-- DOC: Apply "sidebar-search-bordered" class the below search form to have bordered search box -->
                        <!-- DOC: Apply "sidebar-search-bordered sidebar-search-solid" class the below search form to have bordered & solid search box -->
                        <form class="sidebar-search " action="extra_search.html" method="POST">
                            <a href="javascript:;" class="remove">
                            <i class="icon-close"></i>
                            </a>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                <a href="javascript:;" class="btn submit"><i class="icon-magnifier"></i></a>
                                </span>
                            </div>
                        </form>
                        <!-- END RESPONSIVE QUICK SEARCH FORM -->
                    </li>
<li class="start active open">
<a href="javascript:;">
<i class="icon-home"></i>
<span class="title">Dashboard</span>
</a>

</li>


<li class="heading">
<h3 class="uppercase">Modulos</h3>
</li>
<li class="">
<a href="javascript:;">
<i class="icon-home"></i>
<span class="title">Roles y Permisos</span>
</a>
</li>
<li>
<a href="javascript:;">
<i class="icon-folder"></i>
<span class="title">Gestión de Tablas</span>
<span class="arrow "></span>
</a>
<ul class="sub-menu">

<li>
<a href="#">
<i class="icon-bar-chart"></i>
Empleados </a>
</li>
</ul>
</li>
            
     <!--START MULTI LEVEL EXAMPLE
                    <li>
                        <a href="javascript:;">
                        <i class="icon-folder"></i>
                        <span class="title">Multi Level Menu</span>
                        <span class="arrow "></span>
                        </a>
                        <ul class="sub-menu">
                            <li>
                                <a href="javascript:;">
                                <i class="icon-settings"></i> Item 1 <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="javascript:;">
                                        <i class="icon-user"></i>
                                        Sample Link 1 <span class="arrow"></span>
                                        </a>
                                        <ul class="sub-menu">
                                            <li>
                                                <a href="#"><i class="icon-power"></i> Sample Link 1</a>
                                            </li>
                                            <li>
                                                <a href="#"><i class="icon-paper-plane"></i> Sample Link 1</a>
                                            </li>
                                            <li>
                                                <a href="#"><i class="icon-star"></i> Sample Link 1</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="#"><i class="icon-camera"></i> Sample Link 1</a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="icon-link"></i> Sample Link 2</a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="icon-pointer"></i> Sample Link 3</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="javascript:;">
                                <i class="icon-globe"></i> Item 2 <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="#"><i class="icon-tag"></i> Sample Link 1</a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="icon-pencil"></i> Sample Link 1</a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="icon-graph"></i> Sample Link 1</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">
                                <i class="icon-bar-chart"></i>
                                Item 3 </a>
                            </li>
                        </ul>
                    </li>
        
                END MULTI LEVEL EXAMPLE -->
     
                </ul>
                <!-- END SIDEBAR MENU -->
            </div>
        </div>
        <!-- END SIDEBAR -->