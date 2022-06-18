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
["route"=>route('management_sale'),"name"=>"Ventas"],
["route"=>route('management_sale_generate_invoice', ['id' => $sale->id]),"name"=>"Generar Factura"],
]) !!}

@endsection
@section('content_page')
<!-- start:: Content -->
<div class="kt-portlet kt-portlet--mobile">
<div class="kt-portlet__head kt-portlet__head--lg">
<div class="kt-portlet__head-label">

<a href="{{route('management_sale')}}">
<span class="kt-portlet__head-icon">
<i class="kt-font-brand flaticon2-back"></i>
</span>
</a>


<h3 class="kt-portlet__head-title">
Generar Factura
</h3>
</div>

</div>
<div class="kt-portlet__body p-2">
<form action="{{route('management_sale_generate_invoice_function')}}" target="_blank" method="POST" autocomplete="off" role="presentation">
@csrf
@method('post')
<!--begin: form sales -->
<!--begin: part info -->
<div class="row">
<div class="col-xs-12 col-lg-4">
<input type="hidden" id="id_client" name="id_client" value="{{$sale->id_client}}">
<input type="hidden" id="id_sale" name="id_sale" value="{{$sale->id}}">

<div class="form-group">
<label for="cif_nif" class="form-control-label">CIF o NIF</label>
<input type="text" name="cif_nif" class="form-control" id="cif_nif"  value="{{($sale->cif_nif)??old('dni','')}}" autocomplete="off" >
</div>
</div>
</div>
<div class="row">
<div class="col-xs-12 col-lg-4">
<div class="form-group">
<label for="last_name" class="form-control-label">Apellidos</label>
<input type="text" name="last_name" class="form-control" id="last_name"  value="{{($sale->last_name)??old('last_name','')}}" autocomplete="off" readonly>
</div>
</div>
<div class="col-xs-12 col-lg-4">
<div class="form-group">
<label for="name" class="form-control-label">Nombre</label>
<input type="text" name="name" class="form-control" id="name"  value="{{($sale->name)??old('name','')}}" autocomplete="off" readonly>
</div>
</div>
<div class="col-xs-12 col-lg-4">
<div class="form-group">
<label for="email" class="form-control-label">Email</label>
<input type="text" name="email" class="form-control" id="email"  value="{{($sale->email)??old('email','')}}" autocomplete="off" >
</div>
</div>
<div class="col-xs-12 col-lg-8">
<div class="form-group">
<label for="email" class="form-control-label">Dirección</label>
<input type="text" name="address" class="form-control" id="address"  value="{{($sale->address)??old('address','')}}" autocomplete="off" >
</div>
</div>
<div class="col-xs-12 col-lg-4">
<div class="form-group">
<label for="tel" class="form-control-label">Teléfono</label>
<input type="tel" name="tel" class="form-control" id="tel"  value="{{($sale->tel)??old('tel','')}}" autocomplete="off" >
</div>
</div>
</div>


<div class="row pb-0 px-3">
<!--begin: Datatable -->
<div class="table-responsive">
<table class="table table-bordered fix-table">
<thead>
<tr>
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
                @foreach ($productsSale as $product)
                <tr>
                    <td>{{$product->id}}</td>
                      <td>{{$product->name}}</td>
                      <td>{{$product->cant}}</td>
                      <td>{!! $product->price !!}</td>
                      <td>{{$product->discount}}</td>
                      <td>{{$product->tax[0]->tax??'0.00 %'}}</td>
                      <td style="text-align:right">{!! $product->total_product !!}</td>
                  </tr>
                @endforeach
</tbody>
</table>
</div>

<!--end: Datatable -->
</div>

<div class="row d-flex justify-content-end px-2">
<div class="col-xs-12 col-lg-2">
<div class="form-group">
<label for="total_import" class="form-control-label">Total importe:</label>
<input type="text"  class="form-control text-center"  value="{{$totalAmount}}" autocomplete="off" readonly>
</div>
</div>
</div>

<div class="kt-portlet kt-portlet--mobile m-1 p-2 container-distinct2">
    <div class="row px-2 pt-4">
    
<div class="col-xs-12 col-lg-12 text-center">
<div class="form-group w-100">
<button type="button"  onclick="generateInvoice('{{route('management_sale_generate_invoice_function')}}')" class="btn btn-brand btn-elevate btn-icon-sm p-2 w-50">
<i class="fab fa-wpforms"></i>
Generar Factura
</button>
</div>
</div>


    </div>
        
        </div>

<!--end: form sales -->
</form>
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
<script src="{{asset("assets")}}/js/page-management_sale_generate_invoice.js" type="text/javascript"></script>
@endsection

