@extends("$theme/layout")
@section('title') Mis Notificaciones @endsection
@section('styles_page_vendors')
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css">
<link href="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />
@endsection
@section('styles_optional_vendors')

@endsection

@section('content_breadcrumbs') 
{!! PilatesHelper::getBreadCrumbs([
["route"=>route('notification'),"name"=>"Mis Notificaciones"]
]) !!}
@endsection

@section('content_page')

    {{-- table clients --}}

<div class="kt-portlet kt-portlet--mobile">
<div class="kt-portlet__head kt-portlet__head--lg">
<div class="kt-portlet__head-label">
<span class="kt-portlet__head-icon">
<i class="kt-font-brand  fa fa-inbox"></i>
</span>
<h3 class="kt-portlet__head-title">
Mis Notificaciones
</h3>
</div>
<div class="kt-portlet__head-toolbar">
<div class="kt-portlet__head-wrapper">
<div class="kt-portlet__head-actions">
<div class="dropdown dropdown-inline">
<button type="button" class="btn btn-default btn-icon-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
<i class="la la-download"></i> Acciones
</button>
<div class="dropdown-menu dropdown-menu-right">
<ul class="kt-nav">
<li class="kt-nav__section kt-nav__section--first">
<span class="kt-nav__section-text">Elige una opción</span>
</li>
<li class="kt-nav__item">
<a href="#" onclick="deleteSelectedNotifications()" class="kt-nav__link">
<i class="kt-nav__link-icon flaticon2-close-cross"></i>
<span class="kt-nav__link-text">Eliminar seleccionadas</span>
</a>
</li>
</ul>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="kt-portlet__body w-100 ">
<div class="container">
<!--begin: Datatable -->

<table class="table-data-custom-notifications" style="display:none"   id="kt_table_notifications">
<thead>
<tr>
<th class="clean-icon-table text-left">
<label class="kt-checkbox kt-checkbox--single kt-checkbox--solid  ml-1">
<input type="checkbox" name="select_all" value="1" id="select-all-notifications">
<span></span>
</label>
</th>
<th class="d-none">Notificación</th>
<th class="d-none">Eliminar</th>
</tr>
</thead>
</table>
</div>
<!--end: Datatable -->
</div>
</div>

{{-- end table clients --}}

<!--start: Modal Delete Notifications -->
<div class="modal fade" id="modal_delete_notifications" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
<div class="modal-dialog modal-sm" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel">Eliminar Notificaciones</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
</button>
</div>
<form action="{{route('notification_delete_notification')}}"  method="POST" autocomplete="off">
@csrf
@method('delete')
<div id="container-ids-notifications">

</div>

<div class="modal-body">
<h1 class="text-uppercase text-center" style="font-size: 20px;">  <i class="flaticon-danger text-danger display-1"></i> <br> Realmente desea eliminar las notificaciones seleccionadas. <div style="font-size:15px; color:red;"></div></h1>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
<button type="submit" class="btn btn-primary">Eliminar</button>
</div>
</form>
</div>
</div>
</div>
<!--end: Modal Delete Notifications -->

<!--start: Modal Delete Notification -->
<div class="modal fade" id="modal_delete_notification" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
<div class="modal-dialog modal-sm" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel">Eliminar Notificación</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
</button>
</div>
<form action="{{route('notification_delete_notification')}}"  method="POST" autocomplete="off">
@csrf
@method('delete')

<input type="hidden" name="id[]" value="" id="id_delete_notification">

<div class="modal-body">
<h1 class="text-uppercase text-center" style="font-size: 20px;">  <i class="flaticon-danger text-danger display-1"></i> <br> Realmente desea eliminar la notificación seleccionada. <div style="font-size:15px; color:red;"></div></h1></div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
<button type="submit" class="btn btn-primary">Eliminar</button>
</div>
</form>
</div>
</div>
</div>
<!--end: Modal Delete Notification -->

        
<input type="hidden" name="_token" id="token_ajax" value="{{ Session::token() }}">
                <!-- end:: Content -->

@endsection


@section('js_page_vendors')
<script src="{{asset("assets/$theme")}}/vendors/general/block-ui/jquery.blockUI.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/js/bootstrap-select.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.min.js" type="text/javascript"></script>
@endsection

@section('js_optional_vendors')
   
@endsection
@section('js_page_scripts')
<script src="{{asset("assets")}}/js/page-notifications.js" type="text/javascript"></script>
@endsection