@extends("$theme/layout")
@section('title') Productos @endsection
@section('styles_page_vendors')
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css">
<link href="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />
@endsection
@section('styles_optional_vendors')

@endsection

@section('content_breadcrumbs') 
{!! PilatesHelper::getBreadCrumbs([
["route"=>"#","name"=>"Gestión de Tablas"],
["route"=>"management_product","name"=>"Productos"]
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
Gestión de Productos
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
<a href="#" class="kt-nav__link" data-toggle="modal" data-target="#modal_add_product">
<i class="kt-nav__link-icon flaticon2-add-circular-button"></i>
<span class="kt-nav__link-text">Agregar nuevo producto</span>
</a>
</li>
<li class="kt-nav__item">
<a href="#" onclick="deleteSelectedProducts()" id="btn-delete-rooms" class="kt-nav__link">
<i class="kt-nav__link-icon flaticon2-close-cross"></i>
<span class="kt-nav__link-text">Eliminar seleccionados</span>
</a>
</li>
</ul>
</div>
</div>
&nbsp;
<a href="#" class="btn btn-brand btn-elevate btn-icon-sm" data-toggle="modal" data-target="#modal_add_product">
<i class="la la-plus"></i>
Agregar Producto
</a>
</div>
</div>
</div>
</div>
<div class="kt-portlet__body">

<!--begin: Datatable -->
<div class="container">
<table class="table-bordered table-hover table-data-custom"  id="kt_table_products">
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

<!--end: Datatable -->
</div>
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

        
<!--start: Modal add product --> 
<form action="{{route('management_product_insert')}}"  method="POST" autocomplete="off" role="presentation">
@csrf
@method('post')
<input style="display:none">

<div class="modal fade" id="modal_add_product" tabindex="-1" role="dialog"   aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel">Agregar Producto</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
</button>
</div>


<div class="modal-body text-center" >

<div class="form-group  d-flex justify-content-start align-items-center ">
<label class="form-label mx-2">Tipo Suscripción</label>
<span class="kt-switch kt-switch--icon">
<label>
<input type="checkbox"  value="enable" name="suscription_status">
<span></span>
</label>
</span>
</div>

<div class="form-group">
<label for="name" class="form-control-label">Nombre *</label>
<input type="text" name="name" class="form-control text-center" id="name"  value="{{old('name')}}"  autocomplete="new-password" required>
</div>


<div class="row text-center">
<div class="col-xs-12 col-4">
<div class="form-group">
<label for="sessions_machine" class="form-control-label">S.de Máquina *</label>
<input type="number"  step="1" name="sessions_machine" class="form-control text-center" id="sessions_machine"  value="{{old('sessions_machine',0)}}"  autocomplete="new-password" >
</div>
</div>
<div class="col-xs-12 col-4">
<div class="form-group">
<label for="sessions_floor" class="form-control-label">S.de Suelo *</label>
<input type="number" step="1" name="sessions_floor" class="form-control text-center" id="sessions_floor"  value="{{old('sessions_floor',0)}}"  autocomplete="new-password" >
</div>
</div>
<div class="col-xs-12 col-4">
<div class="form-group">
<label for="sessions_individual" class="form-control-label">S.de Individuales *</label>
<input type="number" step="1" name="sessions_individual" class="form-control text-center" id="sessions_individual"  value="{{old('sessions_individual',0)}}"  autocomplete="new-password" >
</div>
</div>
</div>

<div class="row text-center">
<div class="col-xs-12 col-4">
<div class="form-group">
<label for="tax" class="form-control-label">% IGIC *</label>
<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text">%</span>
</div>
<input type="number" step="any" name="tax" class="form-control text-center" id="tax" oninput="updateFullPrice()" value="{{old('tax',0)}}" >
</div>
</div>
</div>
<div class="col-xs-12 col-4">               
<div class="form-group">
<label for="price" class="form-control-label">Precio sin IGIC</label>
<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text">€</span>
</div>
<input type="number" step="any" name="price" class="form-control text-center" id="price" oninput="updateFullPrice()"  value="{{old('price')}}"  readonly>
</div>
</div>
</div>



<div class="col-xs-12 col-4">
<div class="form-group">
<label for="price_all" class="form-control-label">Precio Final * </label>
<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text">€</span>
</div>
<input type="number" step="any" name="price_all" class="form-control text-center" id="price_all" oninput="updateFullPrice()" value="{{old('price_all')}}" required >
</div>
</div>
</div>
</div>
                     
                        
                      
<div class="form-group form-group-last">
<label for="observation">Observaciones</label>
<textarea class="form-control" name="observation" id="observation" rows="3" style="margin-top: 0px; margin-bottom: 0px; height: 137px;">{{old('observation')}}</textarea>
</div>              

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Agregar</button>
                </div>
           
            </div>
        </div>
        </div>
<!--end: Modal add product -->
</form>



<!--start: Modal edit product --> 
<form action="{{route('management_product_update')}}"  method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
@csrf
@method('put')
<input style="display:none">
<input type="hidden" id="id_edit" name="id">
<div class="modal fade" id="modal_edit_product" tabindex="-1" role="dialog"   aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel">Agregar Producto</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
</button>
</div>


<div class="modal-body text-center" >
<div class="form-group  d-flex justify-content-start align-items-center ">
<label class="form-label mx-2">Tipo Suscripción</label>
<span class="kt-switch kt-switch--icon">
<label>
<input type="checkbox" id="suscription_status_edit" value="enable" name="suscription_status">
<span></span>
</label>
</span>
</div>

<div class="form-group">
<label for="name" class="form-control-label">Nombre *</label>
<input type="text" name="name" class="form-control text-center" id="name_edit"  value=""  autocomplete="new-password" required>
</div>


<div class="row text-center">
<div class="col-xs-12 col-4">
<div class="form-group">
<label for="sessions_machine" class="form-control-label">S.de Máquina *</label>
<input type="number"  step="1" name="sessions_machine" class="form-control text-center" id="sessions_machine_edit"  value="0"  autocomplete="new-password">
</div>
</div>
<div class="col-xs-12 col-4">
<div class="form-group">
<label for="sessions_floor" class="form-control-label">S.de Suelo *</label>
<input type="number" step="1" name="sessions_floor" class="form-control text-center" id="sessions_floor_edit"  value="0"  autocomplete="new-password">
</div>
</div>
<div class="col-xs-12 col-4">
<div class="form-group">
<label for="sessions_individual" class="form-control-label">S.de Individuales *</label>
<input type="number" step="1" name="sessions_individual" class="form-control text-center" id="sessions_individual_edit"  value="0"  autocomplete="new-password">
</div>
</div>
</div>

<div class="row text-center">
<div class="col-xs-12 col-4">
<div class="form-group">
<label for="tax" class="form-control-label">% IGIC *</label>
<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text">%</span>
</div>
<input type="number" step="any" name="tax" class="form-control text-center" oninput="updateFullPriceEdit()" id="tax_edit"  value="0">
<input type="hidden" name="id_tax" id="id_tax_edit" >
</div>
</div>
</div>
<div class="col-xs-12 col-4">               
<div class="form-group">
<label for="price" class="form-control-label">Precio sin IGIC</label>
<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text">€</span>
</div>
<input type="number" step="any" name="price" class="form-control text-center" oninput="updateFullPriceEdit()" id="price_edit"  value=""   readonly>
</div>
</div>
</div>



<div class="col-xs-12 col-4">
<div class="form-group">
<label for="price_all" class="form-control-label">Precio Final *</label>
<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text">€</span>
</div>
<input type="number" step="any" name="price_all" class="form-control text-center" oninput="updateFullPriceEdit()" id="price_all_edit"  value="" required >
</div>
</div>
</div>
</div>


<div class="form-group form-group-last">
<label for="observation">Observaciones</label>
<textarea class="form-control" name="observation" id="observation_edit" rows="3" style="margin-top: 0px; margin-bottom: 0px; height: 137px;"></textarea>
</div>              

</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
<button type="submit" class="btn btn-primary">Actualizar</button>
</div>

</div>
</div>
</div>
    <!--end: Modal edit product -->

    </form>







<!--start: Modal Delete products -->
<div class="modal fade" id="modal_delete_products" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
<div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Eliminar Productos</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            </button>
        </div>
        <form action="{{route('management_product_delete')}}" id="form_delete_products" method="POST" autocomplete="off">
            @csrf
            @method('delete')
            <div id="container-ids-products-delete">

            </div>

        <div class="modal-body">
            <h1 class="text-uppercase text-center" style="font-size: 20px;">  <i class="flaticon-danger text-danger display-1"></i> <br> Realmente desea eliminar los productos seleccionados.</h1>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Eliminar</button>
        </div>
    </form>
    </div>
</div>
</div>
<!--end: Modal Delete products -->

<!--start: Modal Delete product -->
<div class="modal fade" id="modal_delete_product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
<div class="modal-dialog modal-sm" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel">Eliminar Producto</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
</button>
</div>
<form action="{{route('management_product_delete')}}"  method="POST" autocomplete="off">
@csrf
@method('delete')

<input type="hidden" name="id[]" value="" id="id_delete_product">

<div class="modal-body">
        <h1 class="text-uppercase text-center" style="font-size: 20px;">  <i class="flaticon-danger text-danger display-1"></i> <br> Realmente desea eliminar el producto seleccionado. </h1>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
<button type="submit" class="btn btn-primary">Eliminar</button>
</div>
</form>
</div>
</div>
</div>
<!--end: Modal Delete product -->


    

        
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

<script>
var routePublicImages=@json(asset("assets")."/images/");
var routePublicStorage=@json(Storage::url("images/profiles/"));
</script>
<script src="{{asset("assets")}}/js/page-management-product.js" type="text/javascript"></script>
@endsection

