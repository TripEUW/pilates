@extends("$theme/layout")
@section('title') Arqueo Diario @endsection
@section('styles_page_vendors')
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css">
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />
@endsection
@section('styles_optional_vendors')

@endsection

@section('content_breadcrumbs') 
{!! PilatesHelper::getBreadCrumbs([
["route"=>"#","name"=>"Panel de Control"],
["route"=>"report_cash_register","name"=>"Arqueo diario"]
]) !!}
@endsection

@section('content_page')

{{-- table clients --}}
<div class="kt-portlet kt-portlet--mobile">
<div class="kt-portlet__head kt-portlet__head--lg">
<div class="kt-portlet__head-label">
<span class="kt-portlet__head-icon">
<i class="kt-font-brand fa fa-money-bill-alt"></i>
</span>
<h3 class="kt-portlet__head-title">
Arqueo Diario
</h3>
</div>

</div>
<div class="kt-portlet__body w-100 ">
<div class="container">

<div class="row mb-2">


<div class="col-12">
<div class="form-group float-right">
<label for="dni" class="form-control-label">Fecha</label>
<input type="text"  class="form-control text-center" id="kt_datepicker" readonly>
</div>
</div>

</div>
        <div class="row">
            
                <div class="col-xs-12 col-lg-4">
                        <div class="form-group">
                        <label for="price" class="form-control-label">Ingresos Tarjeta</label>
                        <div class="input-group">
                        <div class="input-group-prepend">
                        <span class="input-group-text">€</span>
                        </div>
                        <input type="text" id="earnings-tj" class="form-control text-center"   readonly>
                        </div>
                        </div>
                </div>
                <div class="col-xs-12 col-lg-4">
                        <div class="form-group">
                        <label for="price" class="form-control-label">Ingresos Metalico</label>
                        <div class="input-group">
                        <div class="input-group-prepend">
                        <span class="input-group-text">€</span>
                        </div>
                        <input type="text" id="earnings-metalic"  class="form-control text-center"   readonly>
                        </div>
                        </div>
                </div>
                <div class="col-xs-12 col-lg-4">
                <div class="form-group">
                        <div class="form-group">
                        <label for="price" class="form-control-label">Total Ingresos</label>
                        <div class="input-group">
                        <div class="input-group-prepend">
                        <span class="input-group-text">€</span>
                        </div>
                        <input type="text" id="total-earnings" class="form-control text-center"   readonly>
                        </div>
                        </div>
                </div>
                </div>
            
        </div>
</div>
</div>
<div class="w-100 divi-form p-2 text-center">Conteo</div>
<div class="kt-portlet__body w-100 ">

<div class="row">
 <div class="col-4">
@php
$fieldsCount = [
        ['text'=>'Billetes de 500 €','id'=>'0','value_start'=>0],
        ['text'=>'Billetes de 200 €','id'=>'1','value_start'=>0],
        ['text'=>'Billetes de 100 €','id'=>'2','value_start'=>0],
        ['text'=>'Billetes de 50 €','id'=>'3','value_start'=>0],
        ['text'=>'Billetes de 20 €','id'=>'4','value_start'=>0],
        ['text'=>'Billetes de 10 €','id'=>'5','value_start'=>0],
        ['text'=>'Billetes de 5 €','id'=>'6','value_start'=>0],
        ['text'=>'Monedas de 2 €','id'=>'7','value_start'=>0],
        ['text'=>'Monedas de 1 €','id'=>'8','value_start'=>0],
        ['text'=>'Monedas de 50 ¢','id'=>'9','value_start'=>0],
        ['text'=>'Monedas de 20 ¢','id'=>'10','value_start'=>0],
        ['text'=>'Monedas de 10 ¢','id'=>'11','value_start'=>0],
        ['text'=>'Monedas de 5 ¢','id'=>'12','value_start'=>0],
        ['text'=>'Monedas de 2 ¢','id'=>'13','value_start'=>0],
        ['text'=>'Monedas de 1 ¢','id'=>'14','value_start'=>0],
        ];
@endphp
@foreach($fieldsCount as $field)
{{-- start item count --}}
<div class="input-count-money">

<label for="{{'field-count-'.$field['id']}}" class="form-control-label m-2">{{$field['text']}}:</label>
<input onkeypress="checkEditableNumber(event,this)" oninput="setResult()" min="0" type="number" step="1" id="{{'field-count-'.$field['id']}}" value="{{$field['value_start']}}" name="price" class="form-control text-center"  required>

</div>
{{-- end item count --}}
@endforeach
{{-- start item count --}}
<div class="input-count-money">
 <hr>
<label for="" class="form-control-label m-2"><strong>Total en caja:</strong></label>
<input type="text"  id="total-box" value="0"  class="form-control text-center"  readonly>

</div>
{{-- end item count --}}
 </div>
 <div class="col-8">

<div class="form-group">
<label for="price" class="form-control-label">Saldo Inicial *</label>
<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text">€</span>
</div>
<input type="number" min="0" step="any" onkeypress="checkPositiveNumber(event,this)" oninput="setResult()" class="form-control text-center" id="initial-balance"  value="0" required>
</div>
</div>
      
<div class="form-group">
<label for="price" class="form-control-label">Recibos de gastos *</label>
<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text">€</span>
</div>
<input type="number" min="0" step="any" onkeypress="checkPositiveNumber(event,this)" oninput="setResult()"  class="form-control text-center" id="receipts-expenses" value="0"  required>
</div>
</div>

<div class="container text-center">
<label  class="form-control-label d-inline-block w-100 m-2"><strong>Resultado</strong></label>
<div class="container-result-cash-register m-auto d-flex align-items-center justify-content-center" id="status-result">
 <h4 class="">¡Complete los campos necesarios para conocer el resultado del arqueo!</h4>
</div> 
</div>

<div class="container text-center">
<form id="form-report-print-or-save" action="{{route('report_print_cash_register')}}"  target="_blank" method="POST" autocomplete="off" role="presentation">
@csrf
@method('post')
<div id="continaer-ids-counts">

</div>
<input type="hidden" name="initial_balance" id="initial-balance-p">
<input type="hidden" name="receipts_expenses" id="receipts-expenses-p">
<input type="hidden" name="earnings_tj" id="earnings-tj-p">
<input type="hidden" name="earnings_metalic" id="earnings-metalic-p">
<input type="hidden" name="date" id="date-p">
<input type="hidden" name="status" id="status-p">

<button onclick="submitFormReport()"  type="button" class="btn btn-primary m-5"><i class="fa fa-print"></i> Imprimir</button>
</form>

</div>
</div>
</div>
</div>



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
<!-- end:: Content -->
@endsection
@section('js_page_vendors')
	<script src="{{asset("assets/$theme")}}/vendors/general/block-ui/jquery.blockUI.js" type="text/javascript"></script>
        <script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/js/bootstrap-select.js" type="text/javascript"></script>
        <script src="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
        <script src="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.min.js" type="text/javascript"></script>
        <script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
@endsection

@section('js_optional_vendors')
   
@endsection
@section('js_page_scripts')

<script>
var routePublicImages=@json(asset("assets")."/images/");
var routePublicStorage=@json(Storage::url("images/profiles/"));
</script>
<script src="{{asset("assets")}}/js/page-report-cash-register.js" type="text/javascript"></script>
@endsection

