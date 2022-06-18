@extends("$theme/layout")
@section('title') Facturación @endsection
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
["route"=>"#","name"=>"Administración"],
["route"=>"administration_billing","name"=>"Facturación"]
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
Listador Facturas
</h3>
</div>
<div class="kt-portlet__head-toolbar">
<div class="kt-portlet__head-wrapper">
<div class="kt-portlet__head-actions">
<div class="dropdown dropdown-inline">
<button type="button" class="btn btn-brand btn-icon-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
<i class="la la-download"></i> Acciones
</button>
<div class="dropdown-menu dropdown-menu-right">
<ul class="kt-nav">
<li class="kt-nav__section kt-nav__section--first">
<span class="kt-nav__section-text">Elige una opción</span>
</li>
<li class="kt-nav__item">
<a href="#" onclick="selectedInvoicesDelete()" id="btn-delete-rooms" class="kt-nav__link">
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

<!--begin: Search Form -->
<form class="kt-form kt-form--fit kt-margin-b-0 mb-0">
<div class="row kt-margin-b-20">
<div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
<label class="font-weight-bold">Facturas por cliente:</label>
<input type="text" class="form-control kt-input" name="client_search" id="client_search" placeholder="Nombre o apellidos" data-col-index="0">
</div>

<div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
<label class="font-weight-bold">Rango de fechas:</label>
<div class="input-daterange input-group" id="kt_datepicker">
<input type="text" class="form-control kt-input" name="start_date" id="start_date" placeholder="Desde"  />
<div class="input-group-append">
<span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
</div>
<input type="text" class="form-control kt-input" name="end_date" id="end_date" placeholder="Hasta"  />
</div>
</div>

<div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
<label class="font-weight-bold">Rango de importe:</label>
<div class="input-group ">
<input type="number" step="any" min="0" class="form-control kt-input" name="start_amount" id="start_amount" placeholder="Desde" />
<div class="input-group-append">
<span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
</div>
<input type="number" step="any" min="0" class="form-control kt-input" name="end_amount" id="end_amount" placeholder="Hasta" />
</div>
</div>
        
<div class="col-lg-3 d-flex align-items-end justify-content-center">
<button type="button" class="btn btn-primary btn-brand--icon" id="btn_search">
<span>
<i class="la la-search"></i>
<span>Buscar</span>
</span>
</button>
&nbsp;&nbsp;
<button type="button" class="btn btn-secondary btn-secondary--icon" id="btn_reset">
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
<div class="container">
    <button type="button" class="btn btn-sm btn-primary btn-brand--icon my-2" onclick="showHiddenFields('kt_table_billing',this)">Ver campos protegidos</button>
        <table class="table-bordered table-hover table-data-custom" id="kt_table_billing">
                <thead>
                <tr>
                <th class="clean-icon-table">
                <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid mt-0">
                <input type="checkbox" name="select_all" value="1" id="select-all-invoices">
                <span></span>
                </label>
                </th>
                <th>Apellidos</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Nivel</th>
                <th>Sexo</th>
                <th>Email</th>
                <th>Estado Cliente</th>
                {{-- <th>Dirección</th> --}}
                {{-- <th>Dni</th> --}}
                {{-- <th>Fecha de Nacimiento</th> --}}
                {{-- <th>Fecha de Registro</th> --}}
                {{-- <th>Observaciones</th> --}}
                {{-- <th>Saldo Pilates Máquina</th>
                <th>Saldo Pilates Suelo</th>
                <th>Saldo Fisioterapia</th> --}}
                <th>Importe</th>
                <th>Fecha Factura</th>
                <th># Factura</th>
                <th>Actions</th>
                </tr>
                </thead>
                </table>
</div>


<!--end: Datatable -->
</div>
</div>

{{-- end table clients --}}

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

        
<!--start: Modal Delete invoices -->
<div class="modal fade" id="modal_delete_invoices" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
<div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Eliminar Facturas</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            </button>
        </div>
        <form action="{{route('administration_billing_delete')}}" id="form_delete_clients" method="POST" autocomplete="off">
            @csrf
            @method('delete')
            <div id="container-ids-invoices-delete">

            </div>

        <div class="modal-body">
            <h1 class="text-uppercase text-center" style="font-size: 20px;">  <i class="flaticon-danger text-danger display-1"></i> <br> Realmente desea eliminar las facturas seleccionadas. </h1>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Eliminar</button>
        </div>
    </form>
    </div>
</div>
</div>
<!--end: Modal Delete invoices -->

<!--start: Modal Delete invoice -->
<div class="modal fade" id="modal_delete_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
<div class="modal-dialog modal-sm" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel">Eliminar Factura</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
</button>
</div>
<form action="{{route('administration_billing_delete')}}"  method="POST" autocomplete="off">
@csrf
@method('delete')

<input type="hidden" name="id[]" value="" id="id_delete_invoice">

<div class="modal-body">
<h1 class="text-uppercase text-center" style="font-size: 20px;">  <i class="flaticon-danger text-danger display-1"></i> <br> Realmente desea eliminar esta factura. </h1>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
<button type="submit" class="btn btn-primary">Eliminar</button>
</div>
</form>
</div>
</div>
</div>
<!--end: Modal Delete invoice -->


    

        
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
var routePublicImages=@json(asset("assets")."/images/");
var routePublicStorage=@json(Storage::url("images/profiles/"));
</script>
<script src="{{asset("assets")}}/js/page-administration-billing.js" type="text/javascript"></script>
@endsection

