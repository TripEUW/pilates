@extends("$theme/layout")
@section('title') Listados @endsection
@section('styles_page_vendors')
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css">
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />
@endsection
@section('styles_optional_vendors')

@endsection

@section('content_breadcrumbs') 
{!! PilatesHelper::getBreadCrumbs([
["route"=>"#","name"=>"Informes"],
["route"=>"report_listings","name"=>"Listados"]
]) !!}
@endsection

@section('content_page')



<div class="kt-portlet kt-portlet--mobile">
<div class="kt-portlet__head kt-portlet__head--lg">
<div class="kt-portlet__head-label">
<span class="kt-portlet__head-icon">
<i class="kt-font-brand  flaticon-list-2"></i>
</span>
<h3 class="kt-portlet__head-title">
Listado de Informes
</h3>
</div>
<div class="kt-portlet__head-toolbar">
<div class="kt-portlet__head-wrapper">
<div class="kt-portlet__head-actions">

</div>
</div>
</div>
</div>
<div class="kt-portlet__body w-100 ">
        <!--begin: Search Form -->
<form class="kt-form kt-form--fit kt-margin-b-0 mb-0">
<div class="row kt-margin-b-20">

<div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
<div class="form-group">
<label for="table-select" class="font-weight-bold">Informe por tabla:</label>
<select class="form-control" id="table-select" onchange="showByTable(this)">
    
</select>
</div>
</div>

<div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
<div class="form-group">
<label for="template-select" class="font-weight-bold">Informe por plantilla:</label>
<select class="form-control" id="template-select" onchange="showByTemplate(this)">

</select>
</div>
</div>

<div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
<label class="font-weight-bold" id="title-range-filter-date">Rango de fechas:</label>
<div class="input-daterange input-group" id="kt_datepicker">
<input type="text" class="form-control kt-input" name="start_date" id="start_date" placeholder="Desde"  />
<div class="input-group-append">
<span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
</div>
<input type="text" class="form-control kt-input" name="end_date" id="end_date" placeholder="Hasta"  />
</div>
</div>

<div class="col-lg-3 d-flex align-items-end justify-content-center">
    <button type="button" class="btn btn-primary btn-brand--icon" onclick="showBySearch(false)">
    <span>
    <i class="la la-search"></i>
    <span>Buscar</span>
    </span>
    </button>
    &nbsp;&nbsp;
    <button type="button" class="btn btn-secondary btn-secondary--icon" onclick="showBySearch(true)">
    <span>
    <i class="la la-close"></i>
    <span>Limpiar</span>
    </span>
    </button>
    </div>

</div>
<div class="kt-separator kt-separator--md kt-separator--dashed"></div>
</form>

<div class="container text-center" id="kt-container-default">
<h4 class="w-100 text-center p-4 my-5">Seleccione una tabla o plantilla para mostrar el informe.</h4>
</div>
{{-----------------------------------------------------------------------------------start tabla ventas  --}}
<div class="container" id="kt_table_sales_container" style="display:none;">

<form class="kt-form kt-form--fit kt-margin-b-0 mb-0">
<div class="row">
<div class="col-lg-4 kt-margin-b-10-tablet-and-mobile">
<label class="font-weight-bold">Ventas por cliente o empleado:</label>
<input type="text" class="form-control kt-input" name="client_search" id="client_search" placeholder="Nombre o apellidos" data-col-index="0">
</div>

<div class="col-lg-4 kt-margin-b-10-tablet-and-mobile">
<label class="font-weight-bold">Rango de importe:</label>
<div class="input-group ">
<input type="number" step="any" min="0" class="form-control kt-input" name="start_amount" id="start_amount" placeholder="Desde" />
<div class="input-group-append">
<span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
</div>
<input type="number" step="any" min="0" class="form-control kt-input" name="end_amount" id="end_amount" placeholder="Hasta" />
</div>
</div>

<div class="col-lg-4 d-flex align-items-end justify-content-center">
<button type="button" class="btn btn-primary btn-brand--icon" id="btn_search_sale">
<span>
<i class="la la-search"></i>
<span>Buscar</span>
</span>
</button>
&nbsp;&nbsp;
<button type="button" class="btn btn-secondary btn-secondary--icon" id="btn_reset_sale">
<span>
<i class="la la-close"></i>
<span>Limpiar</span>
</span>
</button>
</div>
</div>
<div class="kt-separator kt-separator--md kt-separator--dashed"></div>
</form>
<!--begin: Datatable -->
<table class="table-bordered table-hover table-data-custom" id="kt_table_sales">
<thead>
<tr>
<th class="clean-icon-table">
<label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
<input type="checkbox" name="select_all" value="1" id="select-all-sales">
<span></span>
</label>
</th>
<th>Fecha</th>
<th>Importe</th>
<th>Cliente</th>
<th>Empleado</th>
<th>Tipo de pago</th>
<th>Emisión en venta</th>
<th>Actions</th>
</tr>
</thead>
</table>
<!--ended: Datatable -->
</div>
{{-----------------------------------------------------------------------------------end tabla ventas  --}}

{{-----------------------------------------------------------------------------------start tabla productos  --}}
<div class="container" id="kt_table_products_container" style="display:none;">
<!--begin: Datatable -->
<table class="table-bordered table-hover table-data-custom"  id="kt_table_products">
<thead>
<tr>
<th class="clean-icon-table">
<label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
<input type="checkbox" name="select_all" value="1" id="select-all-products">
<span></span>
</label>
</th>
<th>Veces vendido</th>
<th>#</th>
<th>Nombre</th>
<th>S.Individuales</th>
<th>S.de Suelo</th>
<th>S.de Máquina</th>
<th>Observaciones</th>
<th>% IGIC</th>
<th>Precio sin IGIC</th>
<th>Precio</th>
<th>Fecha de Creación</th>
<th>Actions</th>
</tr>
</thead>
</table>
<!--ended: Datatable -->
</div>
{{-----------------------------------------------------------------------------------end tabla productos  --}}

{{-----------------------------------------------------------------------------------start tabla empleados  --}}
<div class="container" id="kt_table_employees_container" style="display:none;">
    <button type="button" class="btn btn-sm btn-primary btn-brand--icon my-2" onclick="showHiddenFields('kt_table_employee',this)">Ver campos protegidos</button>
<!--begin: Datatable -->
<table class="table-bordered table-hover table-data-custom"  id="kt_table_employee">
<thead>
<tr>
<th class="clean-icon-table">
<label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
<input type="checkbox" name="select_all" value="1" id="select-all-employees">
<span></span>
</label>
</th>
<th>Rol</th>
<th>Nombre</th>
<th>Email</th>
<th>Sexo</th>
<th>Fecha de Nacimiento</th>
<th>Teléfono</th>
<th>Dirección</th>
<th>Observaciones</th>
<th>Status</th>
<th>Actions</th>
</tr>
</thead>

</table>
<!--ended: Datatable -->
</div>
{{-----------------------------------------------------------------------------------end tabla empleados  --}}
{{-----------------------------------------------------------------------------------start tabla clientes  --}}
<div class="container" id="kt_table_clients_container" style="display:none;">
<button type="button" class="btn btn-sm btn-primary btn-brand--icon my-2" onclick="showHiddenFields('kt_table_clients',this)">Ver campos protegidos</button>
<!--begin: Datatable -->
<table class="table-bordered table-hover table-data-custom" style="display:none"   id="kt_table_clients">
<thead>
<tr>
<th class="clean-icon-table">
<label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
<input type="checkbox" name="select_all" value="1" id="select-all-clients">
<span></span>
</label>
</th>
<th>Apellidos</th>
<th>Nombre</th>
<th>Suscripción</th>
<th>Teléfono</th>
<th>Nivel</th>
<th>Sexo</th>
<th>Email</th>
<th>Estado</th>
<th>Dirección</th>
<th>Dni</th>
<th>Fecha de Nacimiento</th>
<th>Fecha de Registro</th>
<th>Observaciones</th>
<th>Saldo Pilates Máquina</th>
<th>Saldo Pilates Suelo</th>
<th>Saldo Fisioterapia</th>
<th>Actions</th>
</tr>
</thead>
</table>
<!--ended: Datatable -->
</div>
{{-----------------------------------------------------------------------------------end tabla clientes  --}}
{{-----------------------------------------------------------------------------------start tabla vacaciones y ausencias  --}}
<div class="container" id="kt_table_holidays_container" style="display:none;">
<!--begin: Datatable -->
<table class="table-bordered table-hover table-data-custom" style="display:none"   id="kt_table_holidays">
    <thead>
    <tr>
    <th class="clean-icon-table">
    <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
    <input type="checkbox" name="select_all" value="1" id="select-all-holidays">
    <span></span>
    </label>
    </th>
    <th>Fecha de solicitud</th>
    <th>Empleado</th>
    <th>Fecha de Comienzo</th>
    <th>Fecha de Término</th>
    <th>Status</th>
    <th>Total Días</th>
    <th>Días Cogidos</th>
    <th>Días Pendientes de Coger</th>
    <th>Actions</th>
    </tr>
    </thead>
    </table>
    <!--ended: Datatable -->
</div>
{{-----------------------------------------------------------------------------------end tabla vacaciones y ausencias  --}}
{{-----------------------------------------------------------------------------------start tabla asistencias  --}}
<div class="container" id="kt_table_attendances_container" style="display:none;">
<!--begin: Datatable -->
<table class="table-bordered table-hover table-data-custom" style="display:none"   id="kt_table_attendances">
<thead>
<tr>
<th class="clean-icon-table">
<label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
<input type="checkbox" name="select_all" value="1" id="select-all-attendances">
<span></span>
</label>
</th>
<th>Fecha</th>
<th>Empleado</th>
<th>Status</th>
<th>Hora de entrada</th>
<th>Hora de salida</th>
<th>Hora que entro</th>
<th>Hora que salió</th>
<th>Tiempo a trabajar</th>
<th>Tiempo por trabajar</th>
<th>Tiempo que trabajo</th>
<th>Actions</th>
</tr>
</thead>
</table>
<!--ended: Datatable -->
</div>
{{-----------------------------------------------------------------------------------end tabla asistencias  --}}

{{-----------------------------------------------------------------------------------start tabla horas libres  --}}
<div class="container" id="kt_table_free_hours_container" style="display:none;">
<!--begin: Datatable -->
<table class="table-bordered table-hover table-data-custom" style="display:none"   id="kt_table_free_hours">
<thead>
<tr>
<th>Id</th>
<th>Empleado</th>
<th>Lunes</th>
<th>Martes</th>
<th>Miércoles</th>
<th>Jueves</th>
<th>Viernes</th>
</tr>
</thead>
</table>
<!--ended: Datatable -->
</div>
{{-----------------------------------------------------------------------------------end tabla horas libres  --}}
</div>
</div>


<!--start: Modal info  -->
<div class="modal fade" id="modal-info-cell" tabindex="-1" role="dialog"   aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-info-cell-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
             
              
        
                <div class="modal-body text-dark white-space-pre-wrap" id="modal-info-cell-content" >
        
                 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
          
            </div>
        </div>
        </div>
<!--end: Modal info  -->

    

        
<input type="hidden" name="_token" id="token_ajax" value="{{ Session::token() }}">
                <!-- end:: Content -->

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
<script>
    var statusModuleAssitances=@json($statusModuleAssitances);
</script>
<script src="{{asset("assets")}}/js/page-report-listings.js" type="text/javascript"></script>
@endsection

