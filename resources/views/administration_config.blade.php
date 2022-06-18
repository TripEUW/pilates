@extends("$theme/layout")
@section('title') Configuración @endsection
@section('styles_page_vendors')
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css">
<link href="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" rel="stylesheet" type="text/css" />
@endsection
@section('styles_optional_vendors')

@endsection

@section('content_breadcrumbs')
{!! PilatesHelper::getBreadCrumbs([
["route"=>"#","name"=>"Panel de Control"],
["route"=>"administration_config","name"=>"Configuración"]
]) !!}
@endsection

@section('content_page')


<!--begin::Portlet-->
<div class="kt-portlet kt-portlet--head-sm" >
        <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
        <span class="kt-portlet__head-icon">
        <i class="kt-font-brand flaticon-settings"></i>
        </span>
        <h3 class="kt-portlet__head-title">
        Días No Laborales
        </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
        <div class="kt-portlet__head-group">
        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapse4">
        <i class="flaticon-eye p-1"></i>
        </button>

        </div>
        </div>
        </div>

        <!--begin::Form-->
        <div class="container-fluid collapse show" class="collapse" id="collapse4">

        <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
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
        <a href="#" class="kt-nav__link" data-toggle="modal" data-target="#modal_add_no_work_day">
        <i class="kt-nav__link-icon flaticon2-add-circular-button"></i>
        <span class="kt-nav__link-text">Agregar día</span>
        </a>
        </li>
        <li class="kt-nav__item">
        <a href="#" onclick="deleteSelectedNoWorkDays()" id="btn-delete-rooms" class="kt-nav__link">
        <i class="kt-nav__link-icon flaticon2-close-cross"></i>
        <span class="kt-nav__link-text">Eliminar seleccionados</span>
        </a>
        </li>
        </ul>
        </div>
        </div>
        &nbsp;
        <a href="#" class="btn btn-brand btn-elevate btn-icon-sm" data-toggle="modal" data-target="#modal_add_no_work_day">
        <i class="la la-plus"></i>
        Agregar Día
        </a>
        </div>
        </div>
        </div>
        </div>

        <div class="container">
        <!--begin: Datatable -->
        <table class="table-bordered table-hover table-data-custom text-center" style="display:none"   id="kt_table_no_work_days">
        <thead>
        <tr>
        <th class="clean-icon-table">
        <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
        <input type="checkbox" name="select_all" value="1" id="select-all-days">
        <span></span>
        </label>
        </th>
        <th>Fecha</th>
        <th>Descripción</th>
        <th>Actions</th>
        </tr>
        </thead>
        </table>
        </div>
        <!--end: Datatable -->
        </div>
        <!--end::Form-->
        </div>


        <!--begin::Portlet-->
<div class="kt-portlet kt-portlet--head-sm" >
        <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
        <span class="kt-portlet__head-icon">
        <i class="kt-font-brand flaticon-settings"></i>
        </span>
        <h3 class="kt-portlet__head-title">
        Faltas y Asistencias
        </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
        <div class="kt-portlet__head-group">
        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapse5">
        <i class="flaticon-eye p-1"></i>
        </button>

        </div>
        </div>
        </div>

        <!--begin::Form-->
        <div class="container-fluid collapse show" class="collapse" id="collapse5">

        <p class="w-100 text-center p-3 font-weight-bold">Activar/Desactivar modulo de asistencias de empelados</p>

        <div class="d-flex justify-content-center align-items-center p-4">
                <span class="kt-switch kt-switch--lg kt-switch--icon">
                        <label>
                                <input type="checkbox" {{ (isset($config->asisstance_module_status))?(($config->asisstance_module_status=="true")?'checked="checked"':""):"" }}  onchange="updateStatusModuleAssitances(this)" id="asisstance_module_status">
                                <span></span>
                        </label>
                </span>

        </div>

        </div>
        <!--end::Form-->
        </div>

        <!--end::Portlet-->

		<!--begin::Portlet-->
                <div class="kt-portlet kt-portlet--head-sm" >
                        <div class="kt-portlet__head">
                                <div class="kt-portlet__head-label">

                                        <span class="kt-portlet__head-icon">
                                        <i class="kt-font-brand flaticon-settings"></i>
                                        </span>
                                        <h3 class="kt-portlet__head-title">
                                                Datos Fiscales
                                        </h3>
                                </div>
                                <div class="kt-portlet__head-toolbar">
                                        <div class="kt-portlet__head-group">
                                                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapse1">
                                                        <i class="flaticon-eye p-1"></i>
                                                      </button>

                                        </div>
                                </div>
                        </div>

                        <!--begin::Form-->
                        <div class="container-fluid collapse show" class="collapse" id="collapse1">
                                <form  action="{{route('administration_config_update_fiscal_data')}}" method="POST" autocomplete="off">
                                <div class="kt-portlet__body">

                                                @csrf
                                                @method('put')
                                       <div class="row">
                                        <div class="col-xs-12 col-lg-9">
                                                <div class="form-group">
                                                        <label for="name_entity">Nombre o Entidad *</label>
                                                        <input type="text" class="form-control" id="name_entity" name="name_entity" value="{{ ($config->name_entity)??old('name_entity','') }}"  required>

                                                </div>
                                        </div>
                                        <div class="col-xs-12 col-lg-3">
                                                <div class="form-group">
                                                        <label for="cif">CIF *</label>
                                                        <input type="text" class="form-control" id="cif"  name="cif" value="{{ ($config->cif)??old('cif','') }}" required>

                                                </div>
                                        </div>
                                        <div class="col-xs-12 col-lg-12">
                                                <div class="form-group">
                                                        <label for="address">Dirección *</label>
                                                        <input type="text" class="form-control" id="address" name="address" value="{{ ($config->address)??old('address','') }}" required>

                                                </div>
                                        </div>

                                        <div class="col-xs-2 col-lg-3">
                                                <div class="form-group">
                                                        <label for="tel">Teléfono *</label>
                                                        <input type="tel" class="form-control" id="tel"  name="tel" value="{{ ($config->tel)??old('tel','') }}" required>

                                                </div>
                                        </div>

                                        <div class="col-xs-2 col-lg-3">
                                                <div class="form-group">
                                                        <label for="mobile">Móvil</label>
                                                        <input type="tel" class="form-control" id="mobile" name="mobile" value="{{ ($config->mobile)??old('mobile','') }}">

                                                </div>
                                        </div>
                                        <div class="col-xs-2 col-lg-3">
                                                <div class="form-group">
                                                        <label for="tomo">Tomo *</label>
                                                        <input type="text" class="form-control" id="tomo" name="tomo" value="{{ ($config->tomo)??old('tomo','') }}" required>

                                                </div>
                                        </div>
                                        <div class="col-xs-2 col-lg-3">
                                                <div class="form-group">
                                                        <label for="folio">Folio *</label>
                                                        <input type="text" class="form-control" id="folio" name="folio" value="{{ ($config->folio)??old('folio','') }}" required>

                                                </div>
                                        </div>
                                        <div class="col-xs-2 col-lg-3">
                                                <div class="form-group">
                                                        <label for="num_factura"># Factura *</label>
                                                        <input type="text" class="form-control" id="num_factura" name="num_factura" value="{{ ($config->num_factura)??old('num_factura','') }}" required>
                                                </div>
                                        </div>
                                       </div>

                                </div>
                                <div class="kt-portlet__foot">
                                        <div class="">
                                                <button type="submit" class="btn btn-brand">Guardar</button>

                                        </div>
                                </div>
                        </form>
                        </div>

                        <!--end::Form-->
                </div>

                <!--end::Portlet-->

                		<!--begin::Portlet-->
                                <div class="kt-portlet kt-portlet--head-sm" >
                                        <div class="kt-portlet__head">
                                                <div class="kt-portlet__head-label">
                                                                <span class="kt-portlet__head-icon">
                                                                                <i class="kt-font-brand flaticon-settings"></i>
                                                                                </span>
                                                        <h3 class="kt-portlet__head-title">
                                                               Gestor Documental
                                                        </h3>
                                                </div>
                                                <div class="kt-portlet__head-toolbar">
                                                        <div class="kt-portlet__head-group">
                                                                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapse2">
                                                                        <i class="flaticon-eye p-1"></i>
                                                                      </button>

                                                        </div>
                                                </div>
                                        </div>

                                        <!--begin::Form-->
                                        <div class="container-fluid collapse show" class="collapse" id="collapse2">
                                                <form  action="{{route('administration_config_documentary_manager_data')}}" method="POST" autocomplete="off">
                                                @csrf
                                                @method('put')
                                                <div class="kt-portlet__body">
                                                       <div class="row">
                                                        <div class="col-xs-12 col-lg-12">
                                                                <div class="form-group">
                                                                        <label for="path_gestor">Ruta donde se almacenan los ficheros del gestor documental:</label>
                                                                        <input type="text" class="form-control" id="path_gestor" name="path_gestor" value="{{ (isset($config->path_gestor))?$config->path_gestor:config('backups.default_path_gestor')}}">
                                                                </div>
                                                        </div>
                                                       </div>
                                                </div>
                                                <div class="kt-portlet__foot">
                                                        <div class="">
                                                                <button type="submit" class="btn btn-brand">Guardar</button>

                                                        </div>
                                                </div>

                                                </form>
                                        </div>

                                        <!--end::Form-->
                                </div>

                                <!--end::Portlet-->


                		<!--begin::Portlet-->
                                <div class="kt-portlet kt-portlet--head-sm" >
                                        <div class="kt-portlet__head">
                                                <div class="kt-portlet__head-label">
                                                                <span class="kt-portlet__head-icon">
                                                                                <i class="kt-font-brand flaticon-settings"></i>
                                                                                </span>
                                                        <h3 class="kt-portlet__head-title">
                                                               Gestión de Copias de Seguridad
                                                        </h3>
                                                </div>
                                                <div class="kt-portlet__head-toolbar">
                                                        <div class="kt-portlet__head-group">
                                                                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapse3">
                                                                        <i class="flaticon-eye p-1"></i>
                                                                      </button>

                                                        </div>
                                                </div>
                                        </div>

                                        <!--begin::Form-->
                                        <div class="container-fluid collapse show" class="collapse" id="collapse3">
                                                <form  action="{{route('administration_config_update_paths_backups_data')}}" method="POST" autocomplete="off">
                                                        @csrf
                                                        @method('put')
                                                <div class="kt-portlet__body">
                                                       <div class="row">
                                                        <div class="col-xs-12 col-lg-12">
                                                                <div class="form-group">
                                                                        <label for="rute_backups_day">Ruta donde se almacenan los backups de la BBDD diarios:</label>
                                                                        <input type="text" class="form-control" name="path_backups_day" id="path_backups_day"  value="{{ (isset($config->path_backups_day))?$config->path_backups_day:config('backups.default_path_backups_day')}}">
                                                                </div>
                                                        </div>
                                                        <div class="col-xs-12 col-lg-12">
                                                                <div class="form-group">
                                                                        <label for="rute_backups_week">Ruta donde se almacenan los backups de la BBDD semanales:</label>
                                                                        <input type="text" class="form-control" name="path_backups_week" id="path_backups_week"  value="{{ (isset($config->path_backups_day))?$config->path_backups_week:config('backups.path_backups_week')}}">
                                                                </div>
                                                        </div>
                                                       </div>
                                                </div>
                                                <div class="kt-portlet__foot">
                                                        <div class="">
                                                                <button type="submit" class="btn btn-brand">Guardar</button>

                                                        </div>
                                                </div>

                                                </form>
                                        </div>

                                        <!--end::Form-->
                                </div>

                                <!--end::Portlet-->





<!--start: Modal Delete Days -->
<div class="modal fade" id="modal_delete_no_work_days" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
<div class="modal-dialog modal-sm" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel">Eliminar Días</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
</button>
</div>
<form action="{{route('administration_config_destroy_no_work_day')}}"  method="POST" autocomplete="off">
@csrf
@method('delete')
<div id="container-ids-no-work-days-delete">

</div>

<div class="modal-body">
<h1 class="text-uppercase text-center" style="font-size: 20px;">  <i class="flaticon-danger text-danger display-1"></i> <br> Realmente desea eliminar los dias seleccionados. <div style="font-size:15px; color:red;"></div></h1>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
<button type="submit" class="btn btn-primary">Eliminar</button>
</div>
</form>
</div>
</div>
</div>
<!--end: Modal Delete Days -->

<!--start: Modal Delete Day -->
<div class="modal fade" id="modal_delete_no_work_day" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
<div class="modal-dialog modal-sm" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel">Eliminar Día</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
</button>
</div>
<form action="{{route('administration_config_destroy_no_work_day')}}"  method="POST" autocomplete="off">
@csrf
@method('delete')

<input type="hidden" name="id[]" value="" id="id_delete_no_work_day">

<div class="modal-body">
<h1 class="text-uppercase text-center" style="font-size: 20px;">  <i class="flaticon-danger text-danger display-1"></i> <br> Realmente desea eliminar el día seleccionado. <div style="font-size:15px; color:red;"></div></h1>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
<button type="submit" class="btn btn-primary">Eliminar</button>
</div>
</form>
</div>
</div>
</div>
<!--end: Modal Delete Day -->

<!--start: Modal add no work day  -->
<div class="modal fade" id="modal_add_no_work_day" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
<div class="modal-dialog modal-md" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel">Agregar Día</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
</button>
</div>

<form action="{{route('administration_config_add_no_work_day')}}"  method="POST" autocomplete="off" role="presentation">
@csrf
@method('post')
<div class="modal-body text-center">
<div class="form-group">
<label for="kt_datepicker" class="form-control-label">Fecha *</label>
<input type="text"  class="form-control text-center" name="date" id="kt_datepicker" value="{{old('date')}}" readonly>
</div>

<div class="form-group form-group-last">
<label for="observation-edit">Descripción</label>
<textarea class="form-control" name="description"  rows="3" style="margin-top: 0px; margin-bottom: 0px; height: 50px;">{{old('description')}}</textarea>
</div>

</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
<button type="submit" class="btn btn-primary">Agregar</button>
</div>
</form>
</div>
</div>
</div>
<!--end: Modal add no work day  -->


<!--start: Modal edit no work day  -->
<div class="modal fade" id="modal_edit_no_work_day" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
<div class="modal-dialog modal-md" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel">Agregar Día</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
</button>
</div>

<form action="{{route('administration_config_edit_no_work_day')}}"  method="POST" autocomplete="off" role="presentation">
@csrf
@method('put')
<div class="modal-body text-center">
<div class="form-group">
<label for="kt_datepicker" class="form-control-label">Fecha *</label>
<input type="text"  class="form-control text-center" name="date" id="kt_datepicker_edit"  readonly>
</div>

<div class="form-group form-group-last">
<label for="observation-edit">Descripción</label>
<textarea class="form-control" name="description" id="description-edit" rows="3" style="margin-top: 0px; margin-bottom: 0px; height: 50px;"></textarea>
</div>
<input type="hidden" name="id" id="id-edit-no-work-day">
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
<button type="submit" class="btn btn-primary">Actualizar</button>
</div>
</form>
</div>
</div>
</div>
<!--end: Modal edit no work day  -->

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
	<script src="{{asset("assets/$theme")}}/vendors/general/block-ui/jquery.blockUI.js" type="text/javascript"></script>
        <script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/js/bootstrap-select.js" type="text/javascript"></script>
        <script src="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
        <script src="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.min.js" type="text/javascript"></script>
        <script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
        <script src="{{asset("assets/$theme")}}/vendors/custom/components/vendors/bootstrap-datepicker/init.js" type="text/javascript"></script>
@endsection

@section('js_optional_vendors')

@endsection
@section('js_page_scripts')
<script src="{{asset("assets")}}/js/page-administration-config.js" type="text/javascript"></script>
@endsection
