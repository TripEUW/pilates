@extends("$theme/layout")
@section('title') Ventas @endsection
@section('styles_page_vendors')
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css">
<link href="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />
@endsection
@section('styles_optional_vendors')

@endsection

@section('content_breadcrumbs') 
{!! PilatesHelper::getBreadCrumbs([
["route"=>"#","name"=>"Administración"],
["route"=>"administration_sale","name"=>"Ventas"],
]) !!}
@endsection
@section('content_page')
<!-- start:: Content -->
<div class="kt-portlet kt-portlet--mobile">
<div class="kt-portlet__head kt-portlet__head--lg">
<div class="kt-portlet__head-label">
<span class="kt-portlet__head-icon">
<i class="kt-font-brand  fa fa-ticket-alt"></i>
</span>
<h3 class="kt-portlet__head-title">
Ventas
</h3>
</div>

</div>
<div class="kt-portlet__body p-2">
<!--begin: form sales -->
<!--begin: part info -->
<div class="row">
<div class="col-xs-12 col-lg-2 d-flex align-items-end">
<div class="form-group w-100">
<a href="#" class="btn btn-brand btn-elevate btn-icon-sm mr-3 p-2 w-100" onclick="showModalSelectClient()">
Cliente
<i class="flaticon2-down-arrow"></i>
</a>
</div>
</div>
<div class="col-xs-12 col-lg-2">
<div class="form-group">
<label for="dni" class="form-control-label">CIF o NIF</label>
<input type="text" name="dni" class="form-control" id="dni"  value="" autocomplete="off" >
</div>
</div>
</div>
<div class="row">
<div class="col-xs-12 col-lg-4">
<div class="form-group">
<label for="last_name" class="form-control-label">Apellidos</label>
<input type="text" name="last_name" class="form-control" id="last_name"  value="" autocomplete="off" readonly>
</div>
</div>
<div class="col-xs-12 col-lg-4">
<div class="form-group">
<label for="name" class="form-control-label">Nombre</label>
<input type="text" name="name" class="form-control" id="name"  value="" autocomplete="off" readonly>
</div>
</div>
<div class="col-xs-12 col-lg-4">
<div class="form-group">
<label for="email" class="form-control-label">Email</label>
<input type="text" name="email" class="form-control" id="email"  value="" autocomplete="off" >
</div>
</div>
<div class="col-xs-12 col-lg-8">
<div class="form-group">
<label for="email" class="form-control-label">Dirección</label>
<input type="text" name="address" class="form-control" id="address"  value="" autocomplete="off" >
</div>
</div>
<div class="col-xs-12 col-lg-4">
<div class="form-group">
<label for="tel" class="form-control-label">Teléfono</label>
<input type="tel" name="tel" class="form-control" id="tel"  value="" autocomplete="off" >
</div>
</div>
</div>
<!--end: part info -->
<div class="row container-distinct1 p-2 m-1">
<div class="col-xs-12 col-lg-2 d-flex align-items-end">
<div class="form-group w-100">
<a href="#" class="btn btn-brand btn-elevate btn-icon-sm p-2 w-100" onclick="showModalSelectProduct()">
Productos
<i class="flaticon2-down-arrow"></i>
</a>
</div>
</div>
<div class="col-xs-12 col-lg-4">
<div class="form-group">
<label for="name_p" class="form-control-label">Nombre</label>
<input type="text" name="name_p" class="form-control" id="name_p"  value="" autocomplete="off" readonly>
</div>
</div>
<div class="col-xs-12 col-lg-1">
<div class="form-group">
<label for="unds" class="form-control-label">Unds</label>
<input type="number" step="any" name="unds" class="form-control" id="unds"  value="" autocomplete="off" >
</div>
</div>
<div class="col-xs-12 col-lg-1">
<div class="form-group">
<label for="discount" class="form-control-label">Dto %</label>
<input type="number" step="any" name="discount" class="form-control" id="discount"  value="" autocomplete="off" >
</div>
</div>
<div class="col-xs-12 col-lg-2">
<div class="form-group">
<label for="price" class="form-control-label">Precio</label>
<input type="text"  name="price" class="form-control" id="price"  value="" autocomplete="off" readonly >
</div>
</div>
<div class="col-xs-12 col-lg-2 d-flex align-items-end">
<div class="form-group w-100">
<a href="#" class="btn btn-brand btn-elevate btn-icon-sm p-2 w-100" onclick="addSelectedProduct()">
<i class="la la-plus"></i>
Añadir
</a>
</div>
</div>

</div>

<div class="row pb-0 px-3">
<!--begin: Datatable -->
<div class="table-responsive">
<table class="table table-bordered fix-table">
<thead>
<tr>
<th></th>
<th>#</th>
<th>Nombre</th>
<th>Precio</th>
<th style="max-width:40px;">Cántidad</th>
<th style="max-width:40px;">Descuento %</th>
<th>IGIC %</th>
<th>Importe</th>
</tr>
</thead>
<tbody id="tbody_append_products">
<tr id="default_cell">
<td colspan="8" scope="row" class="text-center">    
<a href="#" class="btn btn-brand btn-elevate btn-icon-sm p-2" onclick="showModalSelectProduct()">
Agrege un producto a la tabla
<i class="la la-plus"></i>
</a>
</td>
</tr>
</tbody>
</table>
</div>

<!--end: Datatable -->
</div>

<div class="row d-flex justify-content-end px-2">
<div class="col-xs-12 col-lg-2">
<div class="form-group">
<label for="total_import" class="form-control-label">Total importe:</label>
<input type="text" name="total_import" class="form-control text-center" id="total_import"  value="" autocomplete="off" readonly>
</div>
</div>
</div>

<div class="kt-portlet kt-portlet--mobile m-1 p-2 container-distinct2">
        <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
        <span class="kt-portlet__head-icon">
        <i class="kt-font-brand  fa fa-ticket-alt"></i>
        </span>
        <h3 class="kt-portlet__head-title">
        Método de pago
        </h3>
        </div>
        
        </div>
    <div class="row px-2 pt-4">
     <div class="col-xs-12 col-lg-4">
            <div class="row">
                <div class="col-lg-12" id="cant-cash-col">
                    <div class="form-group">
                        <label for="discount" class="form-control-label">Cántidad en Efectivo</label>
                        <input type="number" step="any" name="cant_cash" class="form-control" id="cant-cash" value="" autocomplete="off">
                    </div>
                </div> 
                <div class="col-lg-12" id="cant-tj-col">
                    <div class="form-group">
                        <label for="discount" class="form-control-label">Cántidad con Tarjeta</label>
                        <input type="number" step="any" name="cant_tj" class="form-control" id="cant-tj" value="" autocomplete="off">
                    </div>
                </div> 
                    <div class="col-lg-4">
                            <label class="kt-option kt-option kt-option--plain">
                                <span class="kt-option__control">
                                    <span class="kt-radio kt-radio--check-bold">
                                        <input type="radio" name="method_payment" value="cash" id="radio-cash">
                                        <span></span>
                                    </span>
                                </span>
                                <span class="kt-option__label">
                                    <span class="kt-option__head">
                                        <span class="kt-option__title">
                                           <strong> Efectivo</strong>
                                        </span>
                                    </span>
                                    <span class="kt-option__body">
                                            <i class="fa fa-money-bill" style="font-size:30px;"></i>
                                    </span>
                                </span>
                            </label>
                        </div>
                    <div class="col-lg-4">
                        <label class="kt-option kt-option kt-option--plain">
                            <span class="kt-option__control">
                                <span class="kt-radio kt-radio--check-bold">
                                    <input type="radio" name="method_payment" value="card" id="id-card">
                                    <span></span>
                                </span>
                            </span>
                            <span class="kt-option__label">
                                <span class="kt-option__head">
                                    <span class="kt-option__title">
                                      <strong> Tarjeta</strong>
                                    </span>
                                </span>
                                <span class="kt-option__body">
                                        <i class="la la-credit-card" style="font-size:35px;"></i>
                                </span>
                            </span>
                        </label>
                    </div>

                    <div class="col-lg-4">
                        <label class="kt-option kt-option kt-option--plain">
                            <span class="kt-option__control">
                                <span class="kt-radio kt-radio--check-bold">
                                    <input type="radio" name="method_payment" value="mix" id="id-mix">
                                    <span></span>
                                </span>
                            </span>
                            <span class="kt-option__label">
                                <span class="kt-option__head">
                                    <span class="kt-option__title">
                                      <strong> Mixto</strong>
                                    </span>
                                </span>
                                <span class="kt-option__body">
                                        <i class="fa fa-money-check-alt" style="font-size:35px;"></i>
                                </span>
                            </span>
                        </label>
                    </div>

                  
                
                </div>
    </div>
<div class="col-xs-12 col-lg-4 d-flex justify-content-center align-items-center">
<div class="form-group w-100">
<a href="#" onclick="modalValidSendInvoice()"  class="btn btn-brand btn-elevate btn-icon-sm p-2 w-100">
        <i class="fab fa-wpforms"></i>
Emitir Factura
</a>
</div>
</div>
<div class="col-xs-12 col-lg-4 d-flex justify-content-center align-items-center">
<div class="form-group w-100">
<a href="#" onclick="modalValidSendTicket()" class="btn btn-brand btn-elevate btn-icon-sm p-2 w-100">
        <i class="fa fa-receipt"></i>
Emitir Ticket
</a>
</div>
</div>
    </div>
        
        </div>

<!--end: form sales -->
</div>
</div>


<!--start: Modal select client -->
<div class="modal fade" id="modal_select_client" tabindex="-1" role="dialog"   aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Seleccionar Cliente</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        </button>
        </div>
        <div class="modal-body d-inline-block w-100" >
        
        <div class="container">
        <!--begin: Datatable -->
        <button type="button" class="btn btn-sm btn-primary btn-brand--icon" onclick="showHiddenFields('kt_table_clients',this)">Ver campos protegidos</button>
        <table class="table-bordered table-hover table-data-custom"  width="100" style="display:none" id="kt_table_clients">
                <thead>
                <tr>
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
        <!--end: Datatable -->
        </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        </div>
        </div>
        </div>
        </div>
        <!--end: Modal select client -->
        
        
        
        
        <!--start: Modal select products -->
        <div class="modal fade" id="modal_select_products" tabindex="-1" role="dialog"   aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Seleccionar Producto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        </button>
        </div>
        <div class="modal-body d-inline-block" >
        <!--begin: Datatable -->
        <div class="container">
        <table class="table-bordered table-hover table-data-custom" style="display:none" cellspacing="0" width="100"  id="kt_table_products">
                <thead>
                <tr>
                <th class="clean-icon-table">
                <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
                <input type="checkbox" name="select_all" value="1" id="select-all-products">
                <span></span>
                </label>
                </th>
                <th>#</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Individuales</th>
                <th>Suelo</th>
                <th>Máquina</th>
                <th>Observaciones</th>
                <th>% IGIC</th>
                <th>Precio sin IGIC</th>
                <th>Precio</th>
                <th>Fecha de Creación</th>
                <th>Actions</th>
                </tr>
                </thead>
                </table>
                
                <!--end: Datatable -->
            </div>
        </div>
        <div class="modal-footer">
        <button type="button"  class="btn btn-primary" data-dismiss="modal">Cerrar</button>
        </div>
        </div>
        </div>
        </div>
        <!--end: Modal select products -->
                
        
        
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


        <!--start: Modal confirm ticket -->
      <div class="modal fade" id="modal_confirm_ticket" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirmar venta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <h1 class="text-uppercase text-center" style="font-size: 20px;">  <i class="flaticon-danger text-danger display-1"></i> <br> ¿Realmente desea completar la venta?. <div style="font-size:15px; color:red;"> ¡Recuerde confirmar al cliente el total del importe antes de generar una factura o ticket!</div></h1>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" onclick="issueTicket()" data-dismiss="modal" class="btn btn-primary">Confirmar</button>
                </div>
          
            </div>
        </div>
        </div>
        <!--end: Modal confirm ticket -->

        
        <!--start: Modal confirm invoice -->
      <div class="modal fade" id="modal_confirm_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Confirmar venta</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <h1 class="text-uppercase text-center" style="font-size: 20px;">  <i class="flaticon-danger text-danger display-1"></i> <br> ¿Realmente desea completar la venta?. <div style="font-size:15px; color:red;"> ¡Recuerde confirmar al cliente el total del importe antes de generar una factura o ticket!</div></h1>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" onclick="issueInvoice()"  data-dismiss="modal" class="btn btn-primary">Confirmar</button>
                    </div>
              
                </div>
            </div>
            </div>
            <!--end: Modal confirm invoice -->

             <!--start: Modal errors sale -->
      <div class="modal fade" id="modal_errors_sale" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Errores en venta</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <h1 class="text-uppercase text-center" style="font-size: 25px;">  <i class="flaticon-danger text-danger display-1"></i> <br>  <div style="font-size:12px; color:red;" id="content-errors-sale"> </div></h1>
                    </div>
                    <div class="modal-footer">
                        <button type="button"  data-dismiss="modal" class="btn btn-primary">Ok</button>
                    </div>
              
                </div>
            </div>
            </div>
            <!--end: Modal errors sale -->


            
        <!--start: Modal end ticket -->
      <div class="modal fade" id="modal_end_ticket" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Venta exitosa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <h1 class="text-uppercase text-center" style="font-size: 20px;">  <i class="flaticon2-correct text-success display-1"></i> <div style="font-size:15px; color:#000;"> ¡La venta se realizó con éxito!</div></h1>
                        <br>
 
                        <a href="#" id="download-print-ticket-btn" target="_blank" class="btn btn-brand btn-elevate btn-icon-sm mr-3 p-2 w-100" >Imprimir o descargar ticket</a>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
              
                </div>
            </div>
            </div>
            <!--end: Modal end ticket -->

                <!--start: Modal end invoice -->
      <div class="modal fade" id="modal_end_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Venta exitosa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <h1 class="text-uppercase text-center" style="font-size: 20px;">  <i class="flaticon2-correct text-success display-1"></i> <div style="font-size:15px; color:#000;"> ¡La venta se realizó con éxito!</div></h1>
                        <br>
                        <a href="#" id="download-print-invoice-btn" target="_blank" class="btn btn-brand btn-elevate btn-icon-sm mr-3 p-2 w-100" >Imprimir o descargar factura</a>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
              
                </div>
            </div>
            </div>
            <!--end: Modal end invoice -->
        
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
<script src="{{asset("assets")}}/js/page-administration-sale.js" type="text/javascript"></script>
@endsection

