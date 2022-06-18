
<input type="hidden" name="_token" id="token_ajax_by_reload" value="{{ Session::token() }}">
<audio id="notification-audio"><source src="{{asset("assets/audio/")}}/insight.mp3" type="audio/mp3"></audio>
<input type="hidden" name="_token" id="token_ajax_notification" value="{{ Session::token() }}">
<!--start: Modal modal_redirect -->
<div class="modal fade" id="modal_redirect_notification" tabindex="-1" role="dialog"   aria-hidden="true">
<div class="modal-dialog modal-md" role="document">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body text-center" >


<h1 class="title-notification-modal" id="title-notification-modal"></h1>
<p class="message-notification-modal" id="message-notification-modal"></p>

</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
<button type="button" onclick="" id="btn-notification-link" class="btn btn-primary">Abrir enlace</button>
</div>

</div>
</div>
</div>
<!--end: Modal modal_redirect -->


<!--start: Modal modal_message -->
<div class="modal fade" id="modal_message_notification" tabindex="-1" role="dialog"   aria-hidden="true">
<div class="modal-dialog modal-md" role="document">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body text-center" >


<h1 class="title-notification-modal" id="title-notification-modal-message"></h1>
<p class="message-notification-modal" id="message-notification-modal-message"></p>

</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">ok</button>
</div>

</div>
</div>
</div>
<!--end: Modal modal_message -->


 <!-- end:: Footer -->
</div>
</div>
</div>

<!-- end:: Page -->



<!-- begin::Scrolltop -->
<div id="kt_scrolltop" class="kt-scrolltop">
<i class="fa fa-arrow-up"></i>
</div>

<!-- end::Scrolltop -->

<!-- begin::Global Config(global config for global JS sciprts) -->
<script>
var KTAppOptions = {
"colors": {
    "state": {
        "brand": "#BCCD00",
        "dark": "#282a3c",
        "light": "#ffffff",
        "primary": "#BCCD00",
        "success": "#34bfa3",
        "info": "#36a3f7",
        "warning": "#ffb822",
        "danger": "#fd3995"
    },
    "base": {
        "label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
        "shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
    }
}
};
</script>

<!-- end::Global Config -->

<!--begin:: Global Mandatory Vendors -->
<script src="{{asset("assets/$theme")}}/vendors/general/jquery/dist/jquery.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/popper.js/dist/umd/popper.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/js-cookie/src/js.cookie.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/moment/min/moment.min.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/moment/locale/es-us.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/moment/min/moment-timezone-with-data.min.js" type="text/javascript"></script>
<script>
var configTimeZone=@json(config('app.timezone_for_pilates'));
moment.tz.setDefault(configTimeZone);
</script>

<script src="{{asset("assets/$theme")}}/vendors/general/tooltip.js/dist/umd/tooltip.min.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/perfect-scrollbar/dist/perfect-scrollbar.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/sticky-js/dist/sticky.min.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/wnumb/wNumb.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/pace/pace.min.js" type="text/javascript"></script>

<!--end:: Global Mandatory Vendors -->

<!--begin:: Global Optional Vendors -->
@yield('js_optional_vendors')
<!--end:: Global Optional Vendors -->

<!--begin::Global Theme Bundle(used by all pages) -->
<script src="{{asset("assets/$theme")}}/demo/default/base/scripts.bundle.js" type="text/javascript"></script>
<script src="{{asset("assets")}}/js/sidebar.js" type="text/javascript"></script>
<script src="{{asset("assets")}}/js/overlay.js" type="text/javascript"></script>
<script src="{{asset("assets")}}/js/socket.io.js" type='text/javascript'></script>
<script>
var apiNotifications=@json(config('app.api_notifications_socket'));
var modeNotifications=@json(config('app.mode_notifications_socket'));
var routePublicImagesConfig=@json(asset("assets")."/images/");
var routePublicStorageProfilesConfig=@json(Storage::url("images/profiles/"));
var cod_employee_session=@json(auth()->user()->id);
var cod_role_employee_session=@json(auth()->user()->id_rol);
var baseUrl=@json(config('app.url')."/");
</script>
<script src="{{asset("assets")}}/js/notifications.js" type='text/javascript'></script>
<!--end::Global Theme Bundle -->

<!--begin::Page Vendors(used by this page) -->
 @yield('js_page_vendors')
<!--end::Page Vendors -->

<!--begin::Page Scripts(used by this page) -->
@yield('js_page_scripts')
<!--end::Page Scripts -->

<!--begin::Global App Bundle(used by all pages) -->
<script src="{{asset("assets/$theme")}}/app/bundle/app.bundle.js" type="text/javascript"></script>
<!--end::Global App Bundle -->

<script>
//fix blur in transform3d for popper
Popper.Defaults.modifiers.computeStyle.gpuAcceleration = false;


var portlet = new KTPortlet('main_portlet', {
    bodyToggleSpeed: 400,
    tooltips: true,
    tools: {
        toggle: {
            collapse: 'collapse',
            expand: 'Expand'
        },
        reload: 'Reload',
        remove: 'Remove',
        fullscreen: {
            on: 'Fullscreen',
            off: 'Exit Fullscreen'
        }
    },
    sticky: {
        offset: 300,
        zIndex: 101
    }
});

</script>

<script>
    /*
$(document).ready(function() {
setInterval(()=>{
$.ajax({
url: "refresh_csrf",
type: 'POST',
data: {
_token: $('#token_ajax_by_reload').val()
},
success: function (data) {

$('#token_ajax_by_reload').val(data);
$('[name="_token"]').val(data); // the new token
$('#token_ajax').val(data)
$('#token_ajax1').val(data)
$('#token_ajax2').val(data)


},
error: function (xhr, status, error) {
console.log(JSON.stringify(xhr));
},
});

}, 600000); // 1 hour 
});
*/
</script>
</body>

<!-- end::Body -->
</html>


