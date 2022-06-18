@extends("$theme/layout")
@section('title') Auditorias @endsection
@section('styles_page_vendors')
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css">
<link href="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />
@endsection
@section('styles_optional_vendors')

@endsection

@section('content_breadcrumbs') 
{!! PilatesHelper::getBreadCrumbs([
["route"=>"#","name"=>"Panel de Control"],
["route"=>"#","name"=>"Auditorias (logs)"]
]) !!}
@endsection

@section('content_page')
<!-- begin:: Content -->


<div class="kt-portlet kt-portlet--mobile">
<div class="kt-portlet__head kt-portlet__head--lg">
<div class="kt-portlet__head-label">
<span class="kt-portlet__head-icon">
<i class="kt-font-brand flaticon-list"></i>

</span>
<h3 class="kt-portlet__head-title">
Auditorias
</h3>
</div>
<div class="kt-portlet__head-toolbar">
<div class="kt-portlet__head-wrapper">
<div class="kt-portlet__head-actions">


{{-- <a href="#" class="btn btn-brand btn-elevate btn-icon-sm" data-toggle="modal" data-target="#kt_modal_5">
<i class="la la-plus"></i>
Nuevo Rol
</a> --}}

</div>
</div>
</div>
</div>
<div class="kt-portlet__body">
    <form action="{{route('audit_download')}}" id="form_download" method="POST" autocomplete="off" role="presentation">
        @csrf
        @method('post')
    <div class="text-center">
        <div class="form-group text-center">
        <label for="date-download" style="display:block;">Fecha  de auditoria</label>
        <input class="form-control text-center w-50 m-auto"  style="display:block;" type="date" name="date" id="date-download"  value="{{old('date')}}">
        </div>
        <div class="form-group">
        <button type="submit" class="btn btn-primary">Descargar</button>
        </div>
    </div>
    </form>

<input type="hidden" name="_token" id="token_ajax" value="{{ Session::token() }}">
</div>
</div>
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

<script src="{{asset("assets")}}/js/page-audit.js" type="text/javascript"></script>
@endsection

