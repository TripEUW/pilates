@extends("$theme/layout")
@section('title') Roles y Permisos @endsection
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
["route"=>"#","name"=>"Roles y Permisos"]
]) !!}
@endsection

@section('content_page')
						<!-- begin:: Content -->


                                <div class="kt-portlet kt-portlet--mobile">
                                    <div class="kt-portlet__head kt-portlet__head--lg">
                                        <div class="kt-portlet__head-label">
                                            <span class="kt-portlet__head-icon">
                                                <i class="kt-font-brand flaticon2-link"></i>


                                            </span>
                                            <h3 class="kt-portlet__head-title">
                                                Asignar Permisos
                                            </h3>
                                        </div>
                                        <div class="kt-portlet__head-toolbar">
                                            <div class="kt-portlet__head-wrapper">
                                                <div class="kt-portlet__head-actions">
                                                    <div class="dropdown dropdown-inline">
                                                        {{-- <button type="button" class="btn btn-default btn-icon-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="la la-download"></i> Export
                                                        </button> --}}
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <ul class="kt-nav">
                                                                <li class="kt-nav__section kt-nav__section--first">
                                                                    <span class="kt-nav__section-text">Choose an option</span>
                                                                </li>
                                                                <li class="kt-nav__item">
                                                                    <a href="#" class="kt-nav__link">
                                                                        <i class="kt-nav__link-icon la la-print"></i>
                                                                        <span class="kt-nav__link-text">Print</span>
                                                                    </a>
                                                                </li>
                                                                <li class="kt-nav__item">
                                                                    <a href="#" class="kt-nav__link">
                                                                        <i class="kt-nav__link-icon la la-copy"></i>
                                                                        <span class="kt-nav__link-text">Copy</span>
                                                                    </a>
                                                                </li>
                                                                <li class="kt-nav__item">
                                                                    <a href="#" class="kt-nav__link">
                                                                        <i class="kt-nav__link-icon la la-file-excel-o"></i>
                                                                        <span class="kt-nav__link-text">Excel</span>
                                                                    </a>
                                                                </li>
                                                                <li class="kt-nav__item">
                                                                    <a href="#" class="kt-nav__link">
                                                                        <i class="kt-nav__link-icon la la-file-text-o"></i>
                                                                        <span class="kt-nav__link-text">CSV</span>
                                                                    </a>
                                                                </li>
                                                                <li class="kt-nav__item">
                                                                    <a href="#" class="kt-nav__link">
                                                                        <i class="kt-nav__link-icon la la-file-pdf-o"></i>
                                                                        <span class="kt-nav__link-text">PDF</span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    &nbsp;

                                                    <a href="#" class="btn btn-brand btn-elevate btn-icon-sm" data-toggle="modal" data-target="#kt_modal_5">
                                                        <i class="la la-plus"></i>
                                                         Nuevo Rol
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="kt-portlet__body">
                 <input type="hidden" name="_token" id="token_ajax" value="{{ Session::token() }}">

                <table class="table-bordered table-hover table-data-custom" role="grid" id="kt_table_1">
                    <thead>
                        <tr>
                            <th>Id </th>
                            <th>Modulo </th>
                            <?php foreach ($roles as $keyRol => $rol) { ?>

                            <th class="text-center"><span class="text-capitalize"> <?=$rol ?></span>
                                    <span class="d-inline-flex">
                                            <a href="#" onclick="updateRol(<?= $keyRol ?>,'<?=$rol?>')" class="btn btn-sm btn-clean btn-icon btn-icon-md" > <i class="flaticon-edit"></i> </a>
                                            @if($keyRol!=1)
                                            <a href="#" onclick="deleteRol(<?= $keyRol ?>)" class="btn btn-sm btn-clean btn-icon btn-icon-md" > <i class="flaticon-delete"></i> </a>
                                            @endif
                                    </span>
                            </th>

                        <?php } ?>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($modules as $keyModule => $module) { ?>
                        <tr>

                            <td nowrap>
<?= $module->id ?>
                            </td>
                            <td nowrap>


                            <span class="text-capitalize"> <?= $module->title ?> </span>
                            </td>
                            <?php foreach ($roles as $keyRol=>  $rol) { ?>
                                <?php
                                $isCheck=false;
                                    if(!empty($rolModule[$keyRol])){
                                        foreach ($rolModule[$keyRol] as $rolModuleItem) {
                                            if($rolModuleItem['id']==$module->id){
                                                $isCheck=true;
                                            }
                                        }
                                    }
                                ?>
                                <?php if ($isCheck) { ?>
                                <td class="text-center"><label class="kt-checkbox kt-checkbox--single kt-checkbox--solid "><input type="checkbox"  onchange="updatePermission(<?=$keyRol?>,<?= $module->id?>,this,'{{route('rol_module_insert')}}')" class="m-checkable" checked="checked" <?=($keyRol==1)?'disabled':''?>><span></span></label></td>
                                <?php }else{ ?>
                                <td class="text-center"><label class="kt-checkbox kt-checkbox--single kt-checkbox--solid "><input type="checkbox"  onchange="updatePermission(<?=$keyRol?>,<?=$module->id?>,this,'{{route('rol_module_insert')}}')" class="m-checkable" <?=($keyRol==1)?'disabled':''?>><span></span></label></td>
                                    <?php } ?>
                           <?php } ?>

                        </tr>
                    <?php } ?>


                    </tbody>
                </table>

                <!--end: Datatable -->
            </div>
        </div>
         <!--start: Modal Add Rol -->
        <div class="modal fade" id="kt_modal_5" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Agregar Nuevo Rol</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <form action="{{route('rol_and_permission_insert')}}" method="POST" autocomplete="off">

                            @csrf
                            @method('post')

                        <div class="modal-body">

                                <div class="form-group">
                                    <label for="recipient-name" class="form-control-label">Nombre de Rol:</label>
                                <input type="text" name="name" class="form-control" id="recipient-name" value="{{old('name')}}">
                                </div>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
              <!--end: Modal Add Rol -->
                   <!--start: Modal Delete Rol -->
        <div class="modal fade" id="modal_delete_rol" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Eliminar Rol</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <form action="{{url('rol_and_permission/delete/')}}" id="form_delete_rol" method="POST" autocomplete="off">
                            @csrf
                            @method('delete')

                            <input type="hidden" name="id" value="" id="id_delete_rol">

                        <div class="modal-body">
                            <h1 class="text-uppercase text-center">  <i class="flaticon-danger text-danger display-3"></i> <br> Realmente desea eliminar este rol</h1>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Eliminar</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
              <!--end: Modal Delete Rol -->
                               <!--start: Modal update Rol -->
        <div class="modal fade" id="modal_update_rol" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Actualizar Rol</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <form action="{{route('rol_and_permission_update')}}"  method="POST" autocomplete="off">
                            @csrf
                            @method('put')

                        <input type="hidden" name="id" value="{{old('id')}}" id="id_update_rol">

                        <div class="modal-body">
                                <div class="form-group">
                                        <label for="recipient-name" class="form-control-label">Nombre de Rol:</label>
                                    <input type="text" name="name" class="form-control" id="name_update_rol" value="{{old('name')}}">
                                    </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
              <!--end: Modal update Rol -->

    <!-- end:: Content -->
<script>
var cantRoles=@json($roles);
cantRoles=Object.keys(cantRoles).length;
</script>

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

<script src="{{asset("assets")}}/js/page-rol-and-permission.js" type="text/javascript"></script>
@endsection
