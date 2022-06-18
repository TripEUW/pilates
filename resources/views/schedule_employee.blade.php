@extends("$theme/layout")
@section('title') Mi Horario @endsection
@section('styles_page_vendors')
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css">
<link href="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />



<link href="{{asset("assets/$theme")}}/vendors/custom/jscolor/pygment_trac.css" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/$theme")}}/vendors/custom/jscolor/iehacks.css" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/$theme")}}/vendors/custom/jscolor/colorjoe.css" rel="stylesheet" type="text/css"/>

@endsection
@section('styles_optional_vendors')
<link href="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/custom/jquery-ui-1.12.1/jquery-ui.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/custom/jquery-context-menu/jquery.contextMenu.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/custom/jquery-context-menu/jquery.contextMenu.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css">
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-timepicker/css/bootstrap-timepicker.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets")}}/css/session-calendar.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets")}}/css/shedule-calendar.css" rel="stylesheet" type="text/css" />
@endsection


@section('content_breadcrumbs')
{!! PilatesHelper::getBreadCrumbs([
["route"=>route('schedule_employee'),"name"=>"Mi Horario"]
]) !!}
@endsection

@section('content_page')
<div class="row">
        <div class="col-lg-12">

<!--begin::Portlet-->
<div class="kt-portlet" id="kt_portlet">
<div class="kt-portlet__head">
<div class="kt-portlet__head-label">
<span class="kt-portlet__head-icon">
<i class="kt-font-brand flaticon-calendar-with-a-clock-time-tools"></i>
</span>
<h3 class="kt-portlet__head-title">
Mi Horario
</h3>
</div>

</div>

<div class="btn-toolbar row p-3" role="toolbar" aria-label="Toolbar with button groups">

<div class="col-12 col-md-4 col-lg-4">
<div class="btn-group mr-2 my-1 float-left" role="group" aria-label="First group">
<button type="button" class="btn btn-primary btn-brand--icon" id="btn-back-time"><i class="flaticon2-back"></i></button>
<button type="button" class="btn btn-primary btn-brand--icon" id="btn-next-time"><i class="flaticon2-next"></i></button>
</div>
<div class="btn-group mr-2 my-1" role="group" aria-label="Second group">
<button type="button" class="btn btn-primary btn-brand--icon" id="btn-now-time">Hoy</button>
</div>
</div>

<div class="col-12 col-md-4 col-lg-4">
<div class="kt-margin-b-10-tablet-and-mobile w-100 text-center p-2" id="text-title-range">
---------------------------
</div>
</div>

<div class="col-12 col-md-4 col-lg-4 d-flex justify-content-end">

</div>

</div>

<div class="row w-100">



<!--begin::calendar-->
<div id="session-calendar" class="pt-0 pl-4  pb-4 col-12">

  <table class="session-calendar-table-weekly" >
    <thead>
        <tr>
          <th class="time-cell-session">Hora</th>
            <th class="cell-session-th">Lunes</th>
            <th class="cell-session-th">Martes</th>
            <th class="cell-session-th">Miércoles</th>
            <th class="cell-session-th">Juevdes</th>
            <th class="cell-session-th">Viernes</th>
            <?php if($enableWeekend){ ?>
            <th class="cell-session-th">Sábado</th>
            <th class="cell-session-th">Domingo</th>
            <?php }?>
        </tr>
    </thead>
  <tbody onclick="emptyTableSchedule()">
<tr>
<td class="time-cell-session">
<span class="time"></span>
<span class="to-time"></span>
<span class="time"></span>
</td>
<td class="droppable-cell-shedule" data-date="0"><div class="event-shedule"></div></td>
<td class="droppable-cell-shedule" data-date="1"><div class="event-shedule"></div></td>
<td class="droppable-cell-shedule" data-date="2"><div class="event-shedule"></div></td>
<td class="droppable-cell-shedule" data-date="3"><div class="event-shedule"></div></td>
<td class="droppable-cell-shedule" data-date="4"><div class="event-shedule"></div></td>
<?php if($enableWeekend){ ?>
  <td class="droppable-cell-shedule" data-date="5"><div class="event-shedule"></div></td>
  <td class="droppable-cell-shedule" data-date="6"><div class="event-shedule"></div></td>
  <?php }?>

</tr>
{{-- item --}}
<tr>
  <td class="time-cell-shedule">
  <span class="time"></span>
  <span class="to-time"></span>
  <span class="time"></span>
  </td>
  <td class="droppable-cell-shedule" data-date="0"><div class="event-shedule"></div></td>
  <td class="droppable-cell-shedule" data-date="1"><div class="event-shedule"></div></td>
  <td class="droppable-cell-shedule" data-date="2"><div class="event-shedule"></div></td>
  <td class="droppable-cell-shedule" data-date="3"><div class="event-shedule"></div></td>
  <td class="droppable-cell-shedule" data-date="4"><div class="event-shedule"></div></td>
  <?php if($enableWeekend){ ?>
  <td class="droppable-cell-shedule" data-date="5"><div class="event-shedule"></div></td>
  <td class="droppable-cell-shedule" data-date="6"><div class="event-shedule"></div></td>
  <?php }?>
  </tr>
  {{-- item --}}
  <tr>
    <td class="time-cell-shedule">
    <span class="time"></span>
    <span class="to-time"></span>
    <span class="time"></span>
    </td>
    <td class="droppable-cell-shedule" data-date="0"><div class="event-shedule"></div></td>
    <td class="droppable-cell-shedule" data-date="1"><div class="event-shedule"></div></td>
    <td class="droppable-cell-shedule" data-date="2"><div class="event-shedule"></div></td>
    <td class="droppable-cell-shedule" data-date="3"><div class="event-shedule"></div></td>
    <td class="droppable-cell-shedule" data-date="4"><div class="event-shedule"></div></td>
    <?php if($enableWeekend){ ?>
      <td class="droppable-cell-shedule" data-date="5"><div class="event-shedule"></div></td>
      <td class="droppable-cell-shedule" data-date="6"><div class="event-shedule"></div></td>
      <?php }?>
    </tr>
    {{-- item --}}

</tbody>
</table>
</div>
<!--end::calendar-->


</div>
</div>
</div>
<!--end::Portlet-->
</div>

<div class="kt-portlet kt-portlet--mobile mb-0">
<div class="kt-portlet__head kt-portlet__head--lg">
<div class="kt-portlet__head-label">
<span class="kt-portlet__head-icon">
<i class="kt-font-brand  fa fa-inbox"></i>
</span>
<h3 class="kt-portlet__head-title">
Mis Vacaciones
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
<a href="#" class="kt-nav__link" data-toggle="modal" data-target="#modal_add_holidays">
<i class="kt-nav__link-icon flaticon2-add-circular-button"></i>
<span class="kt-nav__link-text">Solicitar Vacaciones</span>
</a>
</li>
<li class="kt-nav__item">
<a href="#" data-toggle="modal" data-target="#modal_delete_holidays" class="kt-nav__link">
<i class="kt-nav__link-icon flaticon2-close-cross"></i>
<span class="kt-nav__link-text">Eliminar seleccionados</span>
</a>
</li>
</ul>
</div>
</div>
&nbsp;
<a href="#" class="btn btn-brand btn-elevate btn-icon-sm" data-toggle="modal" data-target="#modal_add_holidays">
<i class="la la-plus"></i>
Solicitar Vacaciones
</a>
</div>
</div>
</div>
</div>
</div>

{{-- container holidays start --}}
<div class="container w-100 container-holidays-schedule">
<div class="row p-2 container-holidays-schedule-header">
<div class="col-9 text-center">VACACIONES</div>
<div class="col-3 text-center">TOTAL DÍAS</div>
</div>
<div class="row">
<div class="col-9 text-center">
  <div class="row" id="container-holidays-dates">
    <div class="w-100 h-100 d-flex justify-content-center align-items-center p-5"><div class="kt-spinner kt-spinner--sm kt-spinner--success"></div></div>
  </div>
</div>
<div class="days-in-holidays col-3 text-center d-flex justify-content-center align-items-center" id="count-days-holidays"></div>
</div>

</div>
{{-- container holidays end --}}



<!--start: Modal add holidays  -->
<div class="modal fade" id="modal_add_holidays" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
<div class="modal-dialog modal-md" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel">Solicitar Vacaciones</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
</button>
</div>
<div class="modal-body text-center">

<label class="font-weight-bold">Rango de fechas:</label>
<div class="input-daterange input-group" id="kt_datepicker">
<input type="text" class="form-control kt-input text-center" name="start_date" id="start_date" placeholder="Desde"  />
<div class="input-group-append">
<span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
</div>
<input type="text" class="form-control kt-input text-center" name="end_date" id="end_date" placeholder="Hasta"  />
</div>

</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
<button type="button" onclick="applyForHolidays()" class="btn btn-primary">Solicitar</button>
</div>
</div>
</div>
</div>
<!--end: Modal add holidays  -->

<!--start: Modal delete holidays-->
<div class="modal fade" id="modal_delete_holidays" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
<div class="modal-dialog modal-sm" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel">Eliminar Vacaciones</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
</button>
</div>
<div class="modal-body">
<h1 class="text-uppercase text-center" style="font-size: 20px;">  <i class="flaticon-danger text-danger display-1"></i> <br> Realmente desea eliminar las peticiones de vacaciones seleccionadas.</h1>
<input type="hidden" id="id-employee-reset-schedule">
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
<button type="submit" class="btn btn-primary"  data-dismiss="modal" onclick="deleteHolidays()" >Eliminar</button>
</div>

</div>
</div>
</div>
<!--end: Modal delete holidays-->


<!--start: Modal errors  -->
<div class="modal fade" id="modal_errors" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
  <div class="modal-content">
  <div class="modal-header">
  <h5 class="modal-title" id="exampleModalLabel">Soluciona los Siguientes Errores</h5>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  </button>
  </div>
  <div class="modal-body">
  <h1 class="text-uppercase text-center" style="font-size: 25px;">  <i class="flaticon-danger text-danger display-1"></i> <br>  <div style="font-size:12px; color:red;" id="content-errors"> </div></h1>
  </div>
  <div class="modal-footer">
  <button type="button"  data-dismiss="modal" class="btn btn-primary">Ok</button>
  </div>

  </div>
  </div>
  </div>
  <!--end: Modal errors  -->



<input type="hidden" name="_token" id="token_ajax" value="{{ Session::token() }}">
@endsection

@section('js_page_vendors')
<script>
var routePublicImages=@json(asset("assets")."/images/");
</script>
<script src="{{asset("assets/$theme")}}/vendors/general/block-ui/jquery.blockUI.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/js/bootstrap-select.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.min.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-datetime-picker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/custom/components/vendors/bootstrap-timepicker/init.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>





@endsection


@section('js_optional_vendors')

@endsection

@section('js_page_scripts')
<script src="{{asset("assets/$theme")}}/vendors/custom/jquery-ui-1.12.1/jquery-ui.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/custom/jquery-context-menu/jquery.ui.position.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/custom/jquery-context-menu/jquery.contextMenu.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/js/bootstrap-select.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/custom/jscolor/scale.fix.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/custom/jscolor/colorjoe.js" type="text/javascript"></script>

<script>
  var employeeSelected=@json(auth()->user()->id);
</script>
<script src="{{asset("assets")}}/js/page-schedule-datatables-employee.js" type="text/javascript"></script>
<script src="{{asset("assets")}}/js/page-shedule.js" type="text/javascript"></script>

@endsection
