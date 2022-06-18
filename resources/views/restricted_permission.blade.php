@extends("$theme/layout")
@section('title') Acceso no Autorizado @endsection
@section('styles_page_vendors')

@endsection
@section('styles_optional_vendors')

@endsection


@section('content_breadcrumbs') 
{!! PilatesHelper::getBreadCrumbs([
["route"=>'restricted_permission',"name"=>"Acceso no Autorizado"]
]) !!}
@endsection

@section('content_page')
  <div class="w-100 h-100 d-flex align-items-center justify-content-center">
     
        <div class="row text-center">
            <div class="col-12">   <i class="flaticon-danger text-danger display-3"></i> </div>
            <div class="col-12">  <h1>ACCESO NO AUTORIZADO</h1></div>
        </div>
      

  </div>
@endsection

@section('js_page_vendors')
 
@endsection

@section('js_optional_vendors')
   
@endsection
@section('js_page_scripts')
   
@endsection

