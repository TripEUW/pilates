@extends("$theme/layout")
@section('title') Empleados @endsection
@section('styles_page_vendors')
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css">
<link href="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />
@endsection
@section('styles_optional_vendors')

@endsection

@section('content_breadcrumbs') 
{!! PilatesHelper::getBreadCrumbs([
["route"=>"#","name"=>"Gestion de Tablas"],
["route"=>"management_employee","name"=>"Empleados"]
]) !!}
@endsection

@section('content_page')
    		<!-- begin:: Content -->
         
                
                    <div class="kt-portlet kt-portlet--mobile">
                        <div class="kt-portlet__head kt-portlet__head--lg">
                            <div class="kt-portlet__head-label">
                                <span class="kt-portlet__head-icon">
                                    <i class="kt-font-brand flaticon-map"></i>
                                </span>
                                <h3 class="kt-portlet__head-title">
                                    Gestión de Empleados
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
                                                        <a href="#" class="kt-nav__link" data-toggle="modal" data-target="#modal_add_employee">
                                                            <i class="kt-nav__link-icon fa fa-user-plus"></i>
                                                            <span class="kt-nav__link-text">Agergar nuevo empleado</span>
                                                        </a>
                                                    </li>
                                                    <li class="kt-nav__item">
                                                        <a href="#" onclick="deleteSelectedEmployees()" id="btn-delete-employees" class="kt-nav__link">
                                                            <i class="kt-nav__link-icon fa fa-user-minus"></i>
                                                            <span class="kt-nav__link-text">Eliminar seleccionados</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        &nbsp;
                                        <a href="#" class="btn btn-brand btn-elevate btn-icon-sm" data-toggle="modal" data-target="#modal_add_employee">
                                            <i class="la la-plus"></i>
                                            Agregar Empleado
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="kt-portlet__body">
                                <div class="container">
                                    <button type="button" class="btn btn-sm btn-primary btn-brand--icon my-2" onclick="showHiddenFields('kt_table_1',this)">Ver campos protegidos</button>
                            <!--begin: Datatable -->
                            <table class="table-bordered table-hover table-data-custom"  id="kt_table_1">
                                <thead>
                                    <tr>
                                        <th class="clean-icon-table">
                                            <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
                                                <input type="checkbox" name="select_all" value="1" id="select-all">
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

                            <!--end: Datatable -->
                        </div>
                    </div>
                    </div>
<!--start: Modal add employee -->
<div class="modal fade" id="modal_add_employee" tabindex="-1" role="dialog"   aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Agregar Empleado</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            </button>
        </div>
        <form action="{{route('management_employee_insert')}}"  method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
            @csrf
            @method('post')
            <input style="display:none">

        <div class="modal-body" >

            <div class="row">
                <div class="col-12 d-flex justify-content-center align-items-center">

                    <label for="img-change"  data-toggle="kt-tooltip" data-placement="top" title="" data-original-title="Clic para cambiar">
                    <img id="img-change-profile" class="picture-profile" src="{{ asset("assets/images/user_default.png") }}"  />                 
                    </label>
                                    
                    <input type='file' id="img-change" style="display:none" name="picture_upload" accept="image/*"/>
                    <br>
                    {{-- <small>Clic sobre la imagen para cambiar</small> --}}
            </div>
               <div class="col-6">
                    <div class="form-group">
                            <label for="id_rol">Rol *</label>
                            <select name="id_rol" class="form-control text-capitalize" id="id_rol" required>
                            <option class="text-capitalize" value="" {{old('id_rol')?'':'selected'}}  disabled>Seleccionar Rol</option>
                                @foreach ($roles as $key => $rol)
                            <option class="text-capitalize" value="{{$key}}" {{old('id_rol')? ($key==old('id_rol'))? 'selected':'' :''}}>{{$rol}}</option>
                                @endforeach
                    
                            </select>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="form-control-label">Nombre *</label>
                        <input type="text" name="name" class="form-control" id="recipient-name"  value="{{old('name')}}"  autocomplete="new-password" required>
                    </div>
                    <div class="form-group">
                            <label for="recipient-last-name" class="form-control-label">Apellidos *</label>
                            <input type="text" name="last_name" class="form-control" id="recipient-last-name"  value="{{old('last_name')}}"  required>
                    </div>
                    <div class="form-group">
                            <label for="recipient-email" class="form-control-label">Email *</label>
                            <input type="email" name="email" class="form-control" id="recipient-email"  value="{{old('email')}}"  autocomplete="new-password"  required>
                    </div>
                
                    <div class="form-group">
                            
                            <label for="recipient-password" class="form-control-label">Contraseña *</label>
                           
                            <input type="password" name="password" class="form-control" id="recipient-password"  value="{{old('password')}}"   autocomplete="new-password" required/>
                    </div>
                    <div class="form-group">
                            <label for="recipient-re-password" class="form-control-label">Confirmar Contraseña *</label>
                            <input type="password" name="password_confirmation" class="form-control" id="recipient-re-password"  value="{{old('password_confirmation')}}"  autocomplete="new-password"  required/>
                    </div>
               
               </div>
               <div class="col-6">
                    <div class="form-group">
                            <label class="">Sexo</label>
                            <div class="kt-radio-inline d-flex justify-content-center p-2">
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="sex" value="male" {{old('sex')? (old('sex')=='male')? 'checked' :'' : 'checked'   }}> Masculino
                                    <span></span>
                                </label>
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="sex" value="fmale" {{old('sex')? (old('sex')=='fmale')? 'checked' :'' : ''   }}> Femenino
                                    <span></span>
                                </label>
                            </div>
                    
                    </div>
                    <div class="form-group">
                            <label for="recipient-date-of-birth" class="form-control-label">Fecha de Nacimiento *</label>
                            <input type="date" name="date_of_birth" class="form-control" id="recipient-date-of-birth"  value="{{old('date_of_birth')}}" required >
                    </div>
                    <div class="form-group">
                            <label for="recipient-dni" class="form-control-label">Dni</label>
                            <input type="text" name="dni" class="form-control" id="recipient-dni"  value="{{old('dni')}}" >
                    </div>
                    <div class="form-group">
                            <label for="recipient-address" class="form-control-label">Dirección</label>
                            <input type="text" name="address" class="form-control" id="recipient-address"  value="{{old('address')}}" autocomplete="new-password">
                    </div>
                    <div class="form-group">
                            <label for="recipient-tel" class="form-control-label">Teléfono</label>
                            <input type="tel" name="tel" class="form-control" id="recipient-tel"  value="{{old('tel')}}"  autocomplete="new-password">
                    </div>

        
                    <div class="form-group">
                            <label class="">Status</label>
                            <div class="kt-radio-inline d-flex justify-content-center p-2">
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="status" value="enable" {{old('status')? (old('status')=='enable')? 'checked' :'' : 'checked'   }}> Activo
                                    <span></span>
                                </label>
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="status" value="disable" {{old('status')? (old('status')=='disable')? 'checked' :'' : ''   }}> Inactivo
                                    <span></span>
                                </label>
                            </div>
                    
                    </div>
              
               </div>

               <div class="col-12">
                    <div class="form-group form-group-last">
                            <label for="observation">Observaciones</label>
                            <textarea class="form-control" name="observation" id="observation" rows="3" style="margin-top: 0px; margin-bottom: 0px; height: 137px;">{{old('observation')}}</textarea>
                        </div>
                </div>

            </div>
               
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Agregar</button>
        </div>
    </form>
    </div>
</div>
</div>
<!--end: Modal add employee -->



<!--start: Modal info employee -->
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
        <!--end: Modal info employee -->

                   <!--start: Modal Delete Employees -->
                   <div class="modal fade" id="modal_delete_employees" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Eliminar Empleados</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                </button>
                            </div>
                            <form action="{{route('management_employee_delete')}}" id="form_delete_employees" method="POST" autocomplete="off">
                                @csrf
                                @method('delete')
                                <div id="container-ids-delete">

                                </div>
    
                            <div class="modal-body">
                                <h1 class="text-uppercase text-center">  <i class="flaticon-danger text-danger display-1"></i> <br> Realmente desea eliminar los empleados seleccionados</h1>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Eliminar</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
                  <!--end: Modal Delete Employees -->

<!--start: Modal Delete Rol -->
<div class="modal fade" id="modal_delete_employee" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
<div class="modal-dialog modal-sm" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel">Eliminar Empleado</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
</button>
</div>
<form action="{{route('management_employee_delete')}}"  method="POST" autocomplete="off">
@csrf
@method('delete')

<input type="hidden" name="id[]" value="" id="id_delete_employee">

<div class="modal-body">
<h1 class="text-uppercase text-center">  <i class="flaticon-danger text-danger display-3"></i> <br> Realmente desea eliminar a este empelado</h1>
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
<script src="{{asset("assets")}}/js/page-management-employee.js" type="text/javascript"></script>
@endsection

