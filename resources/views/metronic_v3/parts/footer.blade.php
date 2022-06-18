<!-- BEGIN FOOTER -->
<div class="page-footer">
        <div class="page-footer-inner">
             2019 &copy; Studio Pilates Sandra Galán.
        </div>
        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>
    </div>
    <!-- END FOOTER -->
    <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
    <!-- BEGIN CORE PLUGINS -->
    <script src="{{asset("assets/$theme")}}/global/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="{{asset("assets/$theme")}}/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
    <!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
    <script src="{{asset("assets/$theme")}}/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
    <script src="{{asset("assets/$theme")}}/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="{{asset("assets/$theme")}}/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
    <script src="{{asset("assets/$theme")}}/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="{{asset("assets/$theme")}}/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
    <script src="{{asset("assets/$theme")}}/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
    <script src="{{asset("assets/$theme")}}/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
    <script src="{{asset("assets/$theme")}}/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->
    @yield('js_optional_vendors')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    @yield('js_page_vendors')
    <!-- END PAGE LEVEL PLUGINS -->

    
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="{{asset("assets/$theme")}}/global/scripts/metronic.js" type="text/javascript"></script>
    <script src="{{asset("assets/$theme")}}/admin/layout/scripts/layout.js" type="text/javascript"></script>
    <script src="{{asset("assets/$theme")}}/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
    <script src="{{asset("assets/$theme")}}/admin/layout/scripts/demo.js" type="text/javascript"></script>
    <script src="{{asset("assets/$theme")}}/admin/pages/scripts/index.js" type="text/javascript"></script>
    <script src="{{asset("assets/$theme")}}/admin/pages/scripts/tasks.js" type="text/javascript"></script>
    @yield('js_page_scripts')
    <!-- END PAGE LEVEL SCRIPTS -->

    <script>
       
    jQuery(document).ready(function() {    
       Metronic.init(); // init metronic core componets
       Layout.init(); // init layout
    });
    </script>
    <!-- END JAVASCRIPTS -->
    </body>
    <!-- END BODY -->
    </html>