@extends("$theme/layout")
@section('title') Salas y Grupos @endsection
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
["route"=>"management_room_group","name"=>"Salas y grupos"]
]) !!}
@endsection

@section('content_page')
<div class="row p-0 m-0">
    {{-- table rooms --}}
<div class="col-xs-12 col-lg-6">
<div class="kt-portlet kt-portlet--mobile">
<div class="kt-portlet__head kt-portlet__head--lg">
<div class="kt-portlet__head-label">
<span class="kt-portlet__head-icon">
<i class="kt-font-brand  fa fa-inbox"></i>
</span>
<h3 class="kt-portlet__head-title">
Gestión de Salas
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
<a href="#" class="kt-nav__link" data-toggle="modal" data-target="#modal_add_room">
<i class="kt-nav__link-icon flaticon2-add-circular-button"></i>
<span class="kt-nav__link-text">Agergar nueva sala</span>
</a>
</li>
<li class="kt-nav__item">
<a href="#" onclick="deleteSelectedRooms()" id="btn-delete-rooms" class="kt-nav__link">
<i class="kt-nav__link-icon flaticon2-close-cross"></i>
<span class="kt-nav__link-text">Eliminar seleccionadas</span>
</a>
</li>
</ul>
</div>
</div>
&nbsp;
<a href="#" class="btn btn-brand btn-elevate btn-icon-sm" data-toggle="modal" data-target="#modal_add_room">
<i class="la la-plus"></i>
Agregar Sala
</a>
</div>
</div>
</div>
</div>
<div class="kt-portlet__body">
<div class="continer">
<!--begin: Datatable -->
<table class="table-bordered table-hover table-data-custom"  id="kt_table_rooms">
<thead>
<tr>
<th class="clean-icon-table">
<label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
<input type="checkbox" name="select_all" value="1" id="select-all-rooms">
<span></span>
</label>
</th>
<th>#</th>
<th>Nombre</th>
<th>Tipo</th>
<th>Capacidad</th>
<th>Observaciónes</th>
<th>Acciones</th>
</tr>
</thead>
</table>
<!--end: Datatable -->
</div>
</div>
</div>
</div>
{{-- end table rooms --}}

{{-- start table groups --}}
<div class="col-xs-12 col-lg-6">

<div class="kt-portlet kt-portlet--mobile">
<div class="kt-portlet__head kt-portlet__head--lg">
<div class="kt-portlet__head-label">
<span class="kt-portlet__head-icon">
<i class="kt-font-brand fa fa-users"></i>
</span>
<h3 class="kt-portlet__head-title">
Gestión de Grupos
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
<a href="#" class="kt-nav__link" onclick="showModalAddGroup()">
<i class="kt-nav__link-icon flaticon2-add-circular-button"></i>
<span class="kt-nav__link-text">Agregar nuevo grupo</span>
</a>
</li>
<li class="kt-nav__item">
<a href="#" onclick="deleteSelectedGroups()" id="btn-delete-employees" class="kt-nav__link">
<i class="kt-nav__link-icon flaticon2-close-cross"></i>
<span class="kt-nav__link-text">Eliminar seleccionados</span>
</a>
</li>
</ul>
</div>
</div>
&nbsp;
<a href="#" class="btn btn-brand btn-elevate btn-icon-sm" onclick="showModalAddGroup()">
<i class="la la-plus"></i>
Agregar Grupo
</a>
</div>
</div>
</div>
</div>
<div class="kt-portlet__body">
<div class="continer">
<!--begin: Datatable -->
<table class="table-bordered table-hover table-data-custom"  id="kt_table_groups">
        <thead>
        <tr>
        <th class="clean-icon-table">
        <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
            <input type="checkbox" name="select_all" value="1" id="select-all-group">
            <span></span>
        </label>
        </th>
        <th>Nombre</th>
        <th>Empleado</th>
        <th>Sala</th>
        <th>Status</th>
        <th>Nivel</th>
        <th>Observaciones</th>
        <th>Acciones</th>
        </tr>
        </thead>
        </table>
        <!--end: Datatable -->
</div>

</div>
</div>
</div>
{{-- end table groups --}}

</div>
    		<!-- begin:: Content -->
         
       

<!--start: Modal add room -->
<div class="modal fade" id="modal_add_room" tabindex="-1" role="dialog"   aria-hidden="true">
<div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Agregar Sala</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            </button>
        </div>
        <form action="{{route('management_room_group_room_insert')}}"  method="POST" autocomplete="off" >
            @csrf
            @method('post')
          

        <div class="modal-body" >
                <div class="form-group">
                        <label for="id_rol">Tipo de sala *</label>
                        <select name="type_room" class="form-control text-capitalize" id="id_rol" required>
                        <option class="text-capitalize" value="" {{old('type_room')?'':'selected'}}  disabled>Seleccionar tipo de sala</option>
                     
                        <option class="text-capitalize" value="Máquina" {{old('type_room')? ('machine'==old('type_room'))? 'selected':'' :''}}>Máquina</option>
                        <option class="text-capitalize" value="Suelo" {{old('type_room')? ('floor'==old('type_room'))? 'selected':'' :''}}>Suelo</option>
                        <option class="text-capitalize" value="Camilla" {{old('type_room')? ('stretcher'==old('type_room'))? 'selected':'' :''}}>Camilla</option>
                        </select>
                </div>
                <div class="form-group">
                        <label for="room-name" class="form-control-label">Nombre *</label>
                        <input type="text" name="name" class="form-control" id="room-name"  value="{{old('name')}}"  autocomplete="new-password" required>
                </div>
                <div class="form-group">
                        <label for="capacity" class="form-control-label">Capacidad *</label>
                        <input type="number" name="capacity" class="form-control" id="capacity"  value="{{old('capacity')}}"  autocomplete="new-password" required>
                </div>
                <div class="form-group form-group-last">
                        <label for="observation">Observaciones</label>
                        <textarea class="form-control" name="observation" id="observation" rows="3" style="margin-top: 0px; margin-bottom: 0px; height: 50px;">{{old('observation')}}</textarea>
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
<!--end: Modal add room -->


<!--start: Modal edit room -->
<div class="modal fade" id="modal_edit_room" tabindex="-1" role="dialog"   aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Sala</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form action="{{route('management_room_group_room_update')}}"  method="POST" autocomplete="off" >
                    @csrf
                    @method('put')
                  
        
                <div class="modal-body" >
                        <div class="form-group">
                                <label for="type-room-edit">Tipo de sala *</label>
                                <select name="type_room" class="form-control text-capitalize" id="type-room-edit" required>
                                <option class="text-capitalize" value=""  disabled>Seleccionar tipo de sala</option>
                             
                                <option class="text-capitalize" value="Máquina" >Máquina</option>
                                <option class="text-capitalize" value="Suelo" >Suelo</option>
                                <option class="text-capitalize" value="Camilla">Camilla</option>
                                </select>
                        </div>
                        <div class="form-group">
                                <label for="room-name-edit" class="form-control-label">Nombre *</label>
                                <input type="text" name="name" class="form-control" id="room-name-edit"  value=""  autocomplete="new-password" required>
                        </div>
                        <div class="form-group">
                                <label for="room-capacity-edit" class="form-control-label">Capacidad *</label>
                                <input type="number" name="capacity" class="form-control" id="room-capacity-edit" required>
                        </div>
                        <div class="form-group form-group-last">
                                <label for="room-observation-edit">Observaciones</label>
                                <textarea class="form-control" name="observation" id="room-observation-edit" rows="3" style="margin-top: 0px; margin-bottom: 0px; height: 50px;"></textarea>
                       </div>
                       <input type="hidden" name="id" id="room-id-edit">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
            </div>
        </div>
        </div>
        <!--end: Modal edit room -->





<!--start: Modal Delete Rooms -->
<div class="modal fade" id="modal_delete_rooms" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
<div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Eliminar Salas</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            </button>
        </div>
        <form action="{{route('management_room_group_room_delete')}}" id="form_delete_rooms" method="POST" autocomplete="off">
            @csrf
            @method('delete')
            <div id="container-ids-rooms-delete">

            </div>

        <div class="modal-body">
            <h1 class="text-uppercase text-center" style="font-size: 20px;">  <i class="flaticon-danger text-danger display-1"></i> <br> Realmente desea eliminar las salas seleccionadas. <div style="font-size:15px; color:red;"> ¡Si realizá esta acción tambien seran eliminados los grupos que tiene asiganados la sala a eliminar ademas de las sesiones que pertenecen a la mismo!</div></h1>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Eliminar</button>
        </div>
    </form>
    </div>
</div>
</div>
<!--end: Modal Delete Rooms -->

<!--start: Modal Delete room -->
<div class="modal fade" id="modal_delete_room" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
<div class="modal-dialog modal-sm" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel">Eliminar Sala</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
</button>
</div>
<form action="{{route('management_room_group_room_delete')}}"  method="POST" autocomplete="off">
@csrf
@method('delete')

<input type="hidden" name="id[]" value="" id="id_delete_room">

<div class="modal-body">
        <h1 class="text-uppercase text-center" style="font-size: 20px;">  <i class="flaticon-danger text-danger display-1"></i> <br> Realmente desea eliminar la sala seleccionada. <div style="font-size:15px; color:red;"> ¡Si realizá esta acción tambien sera eliminado el grupo que tiene asignado la sala y las sesiones que pertenecen al mismo!</div></h1>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
<button type="submit" class="btn btn-primary">Eliminar</button>
</div>
</form>
</div>
</div>
</div>
<!--end: Modal Delete rom -->


<!--start: Modal Delete group -->
<div class="modal fade" id="modal_delete_group" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
    <div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Eliminar Grupo</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    </button>
    </div>
    <form action="{{route('management_room_group_group_delete')}}"  method="POST" autocomplete="off">
    @csrf
    @method('delete')
    
    <input type="hidden" name="id[]" value="" id="id_delete_group">
    
    <div class="modal-body">
            <h1 class="text-uppercase text-center" style="font-size: 20px;">  <i class="flaticon-danger text-danger display-1"></i> <br> Realmente desea eliminar el grupo seleccionado. <div style="font-size:15px; color:red;"> ¡Si realizá esta acción tambien seran eliminadas las sesiones que el grupo tiene asiganadas!</div></h1>
    </div>
    <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
    <button type="submit" class="btn btn-primary">Eliminar</button>
    </div>
    </form>
    </div>
    </div>
    </div>
    <!--end: Modal Delete group -->
<!--start: Modal Delete groups -->
<div class="modal fade" id="modal_delete_groups" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Eliminar Grupos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="{{route('management_room_group_group_delete')}}" id="form_delete_groups" method="POST" autocomplete="off">
                @csrf
                @method('delete')
                <div id="container-ids-groups-delete">
                </div>
            <div class="modal-body">
                <h1 class="text-uppercase text-center" style="font-size: 20px;">  <i class="flaticon-danger text-danger display-1"></i> <br> Realmente desea eliminar los grupos seleccionado. <div style="font-size:15px; color:red;"> ¡Si realizá esta acción tambien seran eliminadas las sesiones que los grupos seleccionados tienen asiganadas!</div></h1>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Eliminar</button>
            </div>
        </form>
        </div>
    </div>
    </div>
    <!--end: Modal Delete groups -->
    
<!--start: Modal add group -->
<div class="modal fade" id="modal_add_group" tabindex="-1" role="dialog"   aria-hidden="true">
<div class="modal-dialog modal-md" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel">Agregar Grupo</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
</button>
</div>
<form action="{{route('management_room_group_group_insert')}}"  method="POST" autocomplete="off" >
@csrf
@method('post')

<div class="modal-body" >

<div class="form-group">
<label for="group-name" class="form-control-label">Nombre *</label>
<input type="text" name="name" class="form-control" id="group-name"  value="{{old('name')}}"  autocomplete="new-password" required>
</div>
<div class="form-group">
<label for="group-level" class="form-control-label">Nivel *</label>
<input type="number" name="level" class="form-control" id="group-level"  value="{{old('level')}}"  autocomplete="new-password" required>
</div>



<div class="form-group">
<label>Empleado</label>
<div class="input-group mb-3">
<div class="input-group-prepend">
<button class="btn btn-outline-secondary btn-input" type="button" onclick="showModalSelectEmployee()">Elegir empleado</button>
</div>
<input  class="form-control" id="group-name-employee" name="group_name_employee" value="{{old('group_name_employee')}}" readonly required>
</div>
<input type="hidden" name="id_employee" value="{{old('id_employee')}}" id="group-id-employee">
</div>


<div class="form-group">
<label>Sala *</label>
<div class="input-group mb-3">
<div class="input-group-prepend">
<button class="btn btn-outline-secondary btn-input" type="button" onclick="showModalSelectRoom()">Elegir sala</button>
</div>
<input  class="form-control" id="group-name-room" name="group_name_room" value="{{old('group_name_room')}}" readonly required>
</div>
<input type="hidden" name="id_room" id="group-id-room" value="{{old('id_room')}}" readonly>
</div>
        
        

<div class="form-group form-group-last">
<label for="group-observation">Observaciones</label>
<textarea class="form-control" name="observation" id="group-observation" rows="3" style="margin-top: 0px; margin-bottom: 0px; height: 50px;">{{old('observation')}}</textarea>
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
<!--end: Modal add group -->

<!--start: Modal edit group -->
<div class="modal fade" id="modal_edit_group" tabindex="-1" role="dialog"   aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
    <div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Editar Grupo</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    </button>
    </div>
    <form action="{{route('management_room_group_group_update')}}"  method="POST" autocomplete="off" >
    @csrf
    @method('put')
    <input type="hidden" id="group-id-edit" name="id">
    <div class="modal-body" >
    
    <div class="form-group">
    <label for="group-name-edit" class="form-control-label">Nombre *</label>
    <input type="text" name="name" class="form-control" id="group-name-edit"  value=""  autocomplete="new-password" required>
    </div>
    <div class="form-group">
    <label for="group-level-edit" class="form-control-label">Nivel *</label>
    <input type="number" name="level" class="form-control" id="group-level-edit"  value=""  autocomplete="new-password" required>
    </div>
    
    
    
    <div class="form-group">
    <label>Empleado *</label>
    <div class="input-group mb-3">
    <div class="input-group-prepend">
    <button class="btn btn-outline-secondary btn-input" type="button" onclick="showModalSelectEmployee()">Elegir empleado</button>
    </div>
    <input  class="form-control" id="group-name-employee-edit" name="group_name_employee" value="" readonly required>
    </div>
    <input type="hidden" name="id_employee"  id="group-id-employee-edit">
    </div>
    
    
    <div class="form-group">
    <label>Sala *</label>
    <div class="input-group mb-3">
    <div class="input-group-prepend">
    <button class="btn btn-outline-secondary btn-input" type="button" onclick="showModalSelectRoom()">Elegir sala</button>
    </div>
    <input  class="form-control" id="group-name-room-edit" name="group_name_room" value="" readonly required>
    </div>
    <input type="hidden" name="id_room" id="group-id-room-edit" value="" readonly>
    </div>
            
            
    
    <div class="form-group form-group-last">
    <label for="group-observation-edit">Observaciones</label>
    <textarea class="form-control" name="observation" id="group-observation-edit" rows="3" style="margin-top: 0px; margin-bottom: 0px; height: 50px;"></textarea>
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
    <!--end: Modal edit group -->
            
<!--start: Modal select employee -->
<div class="modal fade" id="modal_select_employee" tabindex="-1" role="dialog"   aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel">Seleccionar Empleado</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
</button>
</div>
<div class="modal-body" >
        <div class="container">
    <!--begin: Datatable -->
<table class="table-bordered table-hover table-data-custom"  id="kt_table_employee_selected">
        <thead>
        <tr>
        <th>#</th>
        <th>Nombre</th>
        <th>Rol</th>
        <th>Cuenta</th>
        <th>Status</th>
        <th># Grupos asignados</th>
        <th>Elegir</th>
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
<!--end: Modal select employee -->

            
<!--start: Modal select room -->
<div class="modal fade" id="modal_select_room" tabindex="-1" role="dialog"   aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
    <div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Seleccionar Sala</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    </button>
    </div>
    <div class="modal-body" >
        <div class="container">
        <!--begin: Datatable -->
    <table class="table-bordered table-hover table-data-custom"  id="kt_table_room_selected">
            <thead>
            <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Tipo</th>
            <th>Capacidad</th>
            <th>Observaciones</th>
            <th>Elegir</th>
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
    <!--end: Modal select room -->

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
<!--end: Modal info -->

<input type="hidden" name="_token" id="token_ajax" value="{{ Session::token() }}">
<input type="hidden" name="_token2" id="token_ajax2" value="{{ Session::token() }}">
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
<script src="{{asset("assets")}}/js/page-room-and-group.js" type="text/javascript"></script>
@endsection

