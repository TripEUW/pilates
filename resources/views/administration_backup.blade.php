@extends("$theme/layout")
@section('title') Copias de Seguridad @endsection
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
["route"=>"administration_backup","name"=>"Copias de Seguridad"]
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
Copias de Seguridad
</h3>
</div>
<div class="kt-portlet__head-toolbar">
<div class="kt-portlet__head-wrapper">
<div class="kt-portlet__head-actions text-center">

<a href="#" class="btn btn-brand btn-elevate btn-icon-sm m-1" data-toggle="modal" data-target="#modal_full_backup">
<i class="fa fa-database"></i>
Lanzar Backup Full
</a>
&nbsp;
<a href="#" class="btn btn-brand btn-elevate btn-icon-sm m-1" data-toggle="modal" data-target="#modal_restore_from_file_backup">
<i class="flaticon2-reload"></i>
Restaurar Backup
</a>
&nbsp;

<div class="dropdown dropdown-inline m-1">
<button type="button" class="btn btn-brand btn-icon-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
<i class="la la-download"></i> Acciones
</button>
<div class="dropdown-menu dropdown-menu-right">
<ul class="kt-nav">
<li class="kt-nav__section kt-nav__section--first">
<span class="kt-nav__section-text">Elige una opción</span>
</li>
<li class="kt-nav__item">
<a href="#" onclick="selectedBackupsDelete()" id="btn-delete-rooms" class="kt-nav__link">
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

<!--begin: Datatable -->
<div class="container">
        <table class="table-bordered table-hover table-data-custom" id="kt_table_backup">
                <thead>
                <tr>
                <th class="clean-icon-table">
                <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid mt-0">
                <input type="checkbox" name="select_all" value="1" id="select-all-backups">
                <span></span>
                </label>
                </th>
                <th>Descripción</th>
                <th>Nombre</th>
                <th>Fecha</th>
                <th>Tamaño</th>
                <th>Estado</th>
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

        
<!--start: Modal Delete backups -->
<div class="modal fade" id="modal_delete_backups" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
<div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Eliminar Copias de Seguridad</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            </button>
        </div>
        <form action="{{route('administration_backup_delete')}}"  method="POST" autocomplete="off">
            @csrf
            @method('delete')
            <div id="container-ids-backups-delete">

            </div>

        <div class="modal-body">
            <h1 class="text-uppercase text-center" style="font-size: 20px;">  <i class="flaticon-danger text-danger display-1"></i> <br> Realmente desea eliminar las copias de seguridad seleccionadas. </h1>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Eliminar</button>
        </div>
    </form>
    </div>
</div>
</div>
<!--end: Modal Delete backups -->

<!--start: Modal Delete backup -->
<div class="modal fade" id="modal_delete_backup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
<div class="modal-dialog modal-sm" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel">Eliminar Copia de Seguridad</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
</button>
</div>
<form action="{{route('administration_backup_delete')}}"  method="POST" autocomplete="off">
@csrf
@method('delete')

<input type="hidden" name="id[]" value="" id="id_delete_backup">

<div class="modal-body">
<h1 class="text-uppercase text-center" style="font-size: 20px;">  <i class="flaticon-danger text-danger display-1"></i> <br> Realmente desea eliminar esta copia de seguridad. </h1>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
<button type="submit" class="btn btn-primary">Eliminar</button>
</div>
</form>
</div>
</div>
</div>
<!--end: Modal Delete backup -->

<!--start: Modal restore backup -->
<div class="modal fade" id="modal_restore_backup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
<div class="modal-dialog modal-md" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel">Restaurar Copia de Seguridad</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
</button>
</div>

<form action="{{route('administration_backup_restore_by_id')}}" id="form-for-restore"  method="POST" autocomplete="off">
@csrf
@method('post')
<input type="hidden" name="id" id="id-for-restore-backup">
<div class="modal-body">
<h1 class="text-uppercase text-center" style="font-size: 20px;">  <i class="flaticon-danger text-danger display-1"></i>
<br> Realmente desea restaurar esta copia de seguridad. 
<div style="font-size:15px; color:red;"> ¡Antes de realizar esta acción asegúrese de que ningún usuario este en un proceso importante o haciendo uso de la plataforma!</div>
</h1>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
<button type="button" onclick="restoreAccept()" class="btn btn-primary">Restaurar</button>
</div>
</form>

</div>
</div>
</div>
<!--end: Modal restore backup -->

<!--start: Modal rename backup -->
<div class="modal fade" id="modal_rename_backup" tabindex="-1" role="dialog"   aria-hidden="true">
<div class="modal-dialog modal-md" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel">Cambiar nombre</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
</button>
</div>
<form action="{{route('administration_backup_backup_rename')}}"  method="POST" autocomplete="off">
@csrf
@method('put')

<input style="display:none">
<input type="hidden" name="id" id="id-backup-for-rename">
<div class="modal-body" >
<div class="form-group">
<label for="name-backup-change" class="form-control-label">Nombre*</label>
<input type="text" name="file_name" class="form-control" id="name-backup-change"  value="" autocomplete="off" required>
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
<button type="submit" class="btn btn-primary">Cambiar</button>
</div>
</form>
</div>
</div>
</div>
<!--end: Modal rename backup-->

<!--start: Modal backup full-->
<div class="modal fade" id="modal_full_backup" tabindex="-1" role="dialog"  aria-hidden="true">
<div class="modal-dialog modal-md" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel">Lanzar Copia de Seguridad</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
</button>
</div>

<form action="{{route('administration_backup_create_backup_full')}}" method="POST" autocomplete="off">
@csrf
@method('post')
<div class="modal-body">
<h1 class="text-uppercase text-center" style="font-size: 20px;">  <i class="flaticon-danger text-danger display-1"></i>
<br> Realmente desea crear una copia de seguridad en este día. 
</h1>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
<button type="submit"  class="btn btn-primary">Lanzar</button>
</div>
</form>

</div>
</div>
</div>
<!--start: Modal backup full-->

<!--start: Modal restore backup-->
<div class="modal fade" id="modal_restore_from_file_backup" tabindex="-1" role="dialog"  aria-hidden="true">
<div class="modal-dialog modal-md" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel">Restaurar Copia de Seguridad</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
</button>
</div>

<form action="{{route('administration_backup_restore_by_file')}}"  method="POST" autocomplete="off" enctype="multipart/form-data">
@csrf
@method('post')

<input style="display:none">
<input type="hidden" name="id" id="id-backup-for-rename">
<div class="modal-body" >
<div class="form-group">
<label for="name-backup-change" class="form-control-label">Selecciona el backup*</label>
<input type="file" name="backup" class="form-control" style="padding:1px;" accept=".sql" required>
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
<button type="submit" class="btn btn-primary">Restaurar</button>
</div>
</form>

</div>
</div>
</div>
<!--start: Modal restore backup-->

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
<script src="{{asset("assets")}}/js/page-administration-backup.js" type="text/javascript"></script>
@endsection

