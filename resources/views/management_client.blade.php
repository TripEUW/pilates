@extends("$theme/layout")
@section('title') Clientes @endsection
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
["route"=>"management_client","name"=>"Clientes"]
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
Gestión de Clientes
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
<a href="#" class="kt-nav__link" data-toggle="modal" data-target="#modal_add_client">
<i class="kt-nav__link-icon flaticon2-add-circular-button"></i>
<span class="kt-nav__link-text">Agregar nuevo cliente</span>
</a>
</li>
<li class="kt-nav__item">
<a href="#" onclick="deleteSelectedClients()" id="btn-delete-rooms" class="kt-nav__link">
<i class="kt-nav__link-icon flaticon2-close-cross"></i>
<span class="kt-nav__link-text">Eliminar seleccionados</span>
</a>
</li>

</ul>
</div>
</div>
&nbsp;
<a href="#" class="btn btn-brand btn-elevate btn-icon-sm" data-toggle="modal" data-target="#modal_add_client">
<i class="la la-plus"></i>
Agregar Cliente
</a>
</div>
</div>
</div>
</div>
<div class="kt-portlet__body w-100 ">
<div class="container">
<!--begin: Datatable -->
<button type="button" class="btn btn-sm btn-primary btn-brand--icon my-2" onclick="showHiddenFields('kt_table_clients',this)">Ver campos protegidos</button>

<table class="table-bordered table-hover table-data-custom" style="display:none"   id="kt_table_clients">
<thead>
<tr>
<th class="clean-icon-table">
<label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
<input type="checkbox" name="select_all" value="1" id="select-all-clients">
<span></span>
</label>
</th>
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

        
<!--start: Modal add client --> 
<form action="{{route('management_client_insert')}}"  method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
@csrf
@method('post')
<input style="display:none">

<div class="modal fade" id="modal_add_client" tabindex="-1" role="dialog"   aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Cliente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
             
        
                <div class="modal-body" >
        
                    <div class="row">
                        <div class="col-12 d-flex justify-content-center align-items-center">
        
                            <label for="img-change"  data-toggle="kt-tooltip" data-placement="top" title="" data-original-title="Clic para cambiar">
                            <img id="img-change-profile" class="" src="{{ asset("assets/images/user_default.png") }}"  />                 
                            </label>
                                            
                            <input type='file' id="img-change" style="display:none" name="picture_upload" accept="image/*"/>
                            <br>
                            {{-- <small>Clic sobre la imagen para cambiar</small> --}}
                    </div>
                       <div class="col-6">
                            <div class="form-group">
                                <label for="recipient-name" class="form-control-label">Nombre *</label>
                                <input type="text" name="name" class="form-control" id="recipient-name"  value="{{old('name')}}"  autocomplete="new-password" required>
                            </div>
                            <div class="form-group">
                                    <label for="recipient-last-name" class="form-control-label">Apellidos *</label>
                                    <input type="text" name="last_name" class="form-control" id="recipient-last-name"  value="{{old('last_name')}}"  required>
                            </div>
                            <div class="form-group">
                                    <label for="group-level" class="form-control-label">Nivel *</label>
                                    <input type="number" name="level" class="form-control" id="group-level"  value="{{old('level')}}"  autocomplete="new-password" required>
                            </div>
                                    
                            <div class="form-group">
                                    <label for="recipient-email" class="form-control-label">Email *</label>
                                    <input type="email" name="email" class="form-control" id="recipient-email"  value="{{old('email')}}"  autocomplete="new-password"  required>
                            </div>
                            <div class="form-group">
                                    <label class="">Sexo *</label>
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
                        
                      
                       
                       </div>
                       <div class="col-6">
                        
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
                            <div class="form-group text-center">
                                 
                                    <button type="button" class="btn btn-primary"  data-toggle="modal" data-target="#modal_add_document_register_client">Añadir documento</button>
                            </div>
                       </div>

                       <div class="col-12">
                           <div class="row">
                                <div class="col-4">
                                        <div class="form-group">
                                                <label for="group-sessions-machine" class="form-control-label">Sesiones Pilates Suelo</label>
                                                <input type="number" name="sessions_floor" class="form-control text-center" id="group-sessions-floor"  value="{{old('sessions_floor')}}"  autocomplete="new-password" >
                                        </div>
                                </div>
                                <div class="col-4">
                                        <div class="form-group">
                                                <label for="group-sessions-floor" class="form-control-label">Sesiones Pilates Máquina</label>
                                               
                                                <input type="number" name="sessions_machine" class="form-control text-center" id="group-sessions-machine"  value="{{old('sessions_machine')}}"  autocomplete="new-password" >
                                        </div>
                                </div>
                                <div class="col-4">
                                        <div class="form-group">
                                                <label for="group-sessions-individual" class="form-control-label">Sesiones Fisioterapia</label>
                                                <input type="number" name="sessions_individual" class="form-control text-center" id="group-sessions-individual"  value="{{old('sessions_individual')}}"  autocomplete="new-password" >
                                        </div>
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
           
            </div>
        </div>
        </div>
<!--end: Modal add client -->
        
<!--start: Modal add document in add client -->
<div class="modal fade" id="modal_add_document_register_client">
<div class="modal-dialog modal-md" >
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel">Selecciona el documento</h5>
<button type="button" class="close" data-dismiss="modal" onclick="openAddClientModal();" >
</button>
</div>
<div class="modal-body text-center" >
<div class="form-group text-center">
<label for="name-document" class="form-control-label">Nombre o título del documento *</label>
<input type="text" name="name_document" class="form-control text-center" id="name-document"  value="{{old('name_document')}}"  autocomplete="new-password"  >
</div>
<small>Si el documento no tiene un reverso puede omitir la imagen o documento reverso</small> 
<div class="row pt-4">
    <div class="col-6 text-center">
            <label  class="form-control-label">Anverso</label>
        <div class="d-flex justify-content-center align-items-center">
              
                <label for="img-change-front"  data-toggle="kt-tooltip" data-placement="top" title="" data-original-title="Clic para cambiar">
                <img id="preview-front-document" class="preview-document" src="{{asset("assets")}}/images/doc-front-default.png"  />                 
                </label>
                                
                <input type='file' id="img-change-front" style="display:none" name="front" accept=""/>
                <br>
                {{-- <small>Clic sobre la imagen para cambiar</small> --}}
        </div>
          
    </div>

    <div class="col-6 text-center">
            <label  class="form-control-label">Reverso</label>
            <div class="d-flex justify-content-center align-items-center">
                    <label for="img-change-back"  data-toggle="kt-tooltip" data-placement="top" title="" data-original-title="Clic para cambiar">
                            <img id="preview-back-document" class="preview-document" src="{{asset("assets")}}/images/doc-back-default.png"  />                 
                            </label>
                                            
                            <input type='file' id="img-change-back" style="display:none" name="back" accept=""/>
                            <br>
                            {{-- <small>Clic sobre la imagen para cambiar</small> --}}
            </div>

          
    </div>
    <div class="col-12">
        <div class="form-group form-group-last">
        <label for="observation_document">Observaciones</label>
        <textarea class="form-control" name="observation_document" id="observation_document" rows="3" style="margin-top: 0px; margin-bottom: 0px; height: 137px;">{{old('observation')}}</textarea>
        </div>
</div>
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-primary" class="close" data-dismiss="modal" onclick="openAddClientModal();">Aceptar</button>
</div>

</div>
</div>
</div>
<!--end: Modal add document in add client -->
</form>

<form action="{{route('management_client_add_document')}}"  method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
@csrf
@method('post')
<input style="display:none">
<input type="hidden" name="id_client" id="id-client-add-doc">
<!--start: Modal add document in add client -->
<div class="modal fade" id="modal_add_document_client">
<div class="modal-dialog modal-md" >
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel">Selecciona el documento</h5>
<button type="button" class="close" data-dismiss="modal"  >
</button>
</div>
<div class="modal-body text-center" >
<div class="form-group text-center">
<label for="name-document" class="form-control-label">Nombre o título del documento *</label>
<input type="text" name="name_document" class="form-control text-center" id="name-document"  value="{{old('name_document')}}"  autocomplete="new-password"  >
</div>
<small>Si el documento no tiene un reverso puede omitir la imagen o documento reverso</small> 
<div class="row pt-4">
    <div class="col-6 text-center">
            <label  class="form-control-label">Anverso</label>
        <div class="d-flex justify-content-center align-items-center">
              
                <label for="img-change-front-edit"  data-toggle="kt-tooltip" data-placement="top" title="" data-original-title="Clic para cambiar">
                <img id="preview-front-document-edit" class="preview-document" src="{{asset("assets")}}/images/doc-front-default.png"  />                 
                </label>
                                
                <input type='file' id="img-change-front-edit" style="display:none" name="front" accept=""/>
                <br>
                {{-- <small>Clic sobre la imagen para cambiar</small> --}}
        </div>
          
    </div>

    <div class="col-6 text-center">
            <label  class="form-control-label">Reverso</label>
            <div class="d-flex justify-content-center align-items-center">
                    <label for="img-change-back-edit"  data-toggle="kt-tooltip" data-placement="top" title="" data-original-title="Clic para cambiar">
                            <img id="preview-back-document-edit" class="preview-document" src="{{asset("assets")}}/images/doc-back-default.png"  />                 
                            </label>
                                            
                            <input type='file' id="img-change-back-edit" style="display:none" name="back" accept=""/>
                            <br>
                            {{-- <small>Clic sobre la imagen para cambiar</small> --}}
            </div>

          
    </div>
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" class="close" data-dismiss="modal">Cancelar</button>
<button type="submit" class="btn btn-primary"  >Agregar</button>
</div>

</div>
</div>
</div>
<!--end: Modal add document in add client -->
</form>

<!--start: Modal edit client --> 
<form action="{{route('management_client_update')}}"  method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
    @csrf
    @method('put')
    <input style="display:none">

    <input type="hidden" id="id-client-edit" name="id">
    
    <div class="modal fade" id="modal_edit_client" tabindex="-1" role="dialog"   aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Editar Cliente</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                 
            
                    <div class="modal-body" >
                        <button type="button" class="btn btn-sm btn-primary btn-brand--icon my-2" onclick="showHiddenFieldsEdit(this)">Editar campos protegidos</button>
                        <div class="row">
                            <div class="col-12 d-flex justify-content-center align-items-center">
            
                                <label for="img-change2"  data-toggle="kt-tooltip" data-placement="top" title="" data-original-title="Clic para cambiar">
                                <img id="img-change-profile2" class="" src=""  />                 
                                </label>
                                                
                                <input type='file' id="img-change2" style="display:none" name="picture_upload" accept="image/*"/>
                                <br>
                                {{-- <small>Clic sobre la imagen para cambiar</small> --}}
                        </div>
                           <div class="col-6">
                                <div class="form-group">
                                    <label for="recipient-name-edit" class="form-control-label">Nombre *</label>
                                    <input type="text" name="name" class="form-control" id="recipient-name-edit"  value=""  autocomplete="new-password" required>
                                </div>
                                <div class="form-group">
                                        <label for="recipient-last-name-edit" class="form-control-label">Apellidos *</label>
                                        <input type="text" name="last_name" class="form-control" id="recipient-last-name-edit"  value=""  required>
                                </div>
                                <div class="form-group">
                                        <label for="group-level-edit" class="form-control-label">Nivel *</label>
                                        <input type="number" name="level" class="form-control" id="group-level-edit"  value=""  autocomplete="new-password" required>
                                </div>
                                        
                                <div class="form-group">
                                        <label for="recipient-email-edit" class="form-control-label">Email *</label>
                                        <input type="email" name="email" class="form-control" id="recipient-email-edit"  value=""  autocomplete="new-password"  required>
                                </div>
                                <div class="form-group">
                                        <label class="">Sexo *</label>
                                        <div class="kt-radio-inline d-flex justify-content-center p-2">
                                            <label class="kt-radio kt-radio--solid">
                                                <input type="radio" id="sex-male-edit" name="sex" value="male"> Masculino
                                                <span></span>
                                            </label>
                                            <label class="kt-radio kt-radio--solid">
                                                <input type="radio" id="sex-fmale-edit" name="sex" value="fmale"> Femenino
                                                <span></span>
                                            </label>
                                        </div>
                                </div>
                            
                          
                           
                           </div>
                           <div class="col-6">
                            
                                <div class="form-group">
                                        <label for="recipient-date-of-birth-edit" class="form-control-label">Fecha de Nacimiento *</label>
                                        <input type="date" name="date_of_birth" class="form-control" id="recipient-date-of-birth-edit"  value="" required >
                                </div>
                                <div class="form-group" id="recipient-dni-edit-container">
                                        <label for="recipient-dni-edit" class="form-control-label">Dni</label>
                                        <input type="text" name="dni" class="form-control" id="recipient-dni-edit"  value="" >
                                </div>
                                <div class="form-group" id="recipient-address-edit-container">
                                        <label for="recipient-address-edit" class="form-control-label">Dirección</label>
                                        <input type="text" name="address" class="form-control" id="recipient-address-edit"  value="" autocomplete="new-password">
                                </div>
                                <div class="form-group" id="recipient-tel-edit-container">
                                        <label for="recipient-tel-edit" class="form-control-label">Teléfono</label>
                                        <input type="tel" name="tel" class="form-control" id="recipient-tel-edit"  value=""  autocomplete="new-password">
                                </div>
                                
                           </div>
    
                           <div class="col-12" id="inputs_balance">
                               <div class="row">
                                    <div class="col-4">
                                            <div class="form-group">
                                                    <label for="group-sessions-floor-edit" class="form-control-label">Sesiones Pilates Suelo</label>
                                                    <input type="number" name="sessions_floor" class="form-control text-center" id="group-sessions-floor-edit"  value=""  autocomplete="new-password" >
                                            </div>
                                    </div> 
                                    <div class="col-4">
                                            <div class="form-group">
                                                    <label for="group-sessions-machine-edit" class="form-control-label">Sesiones Pilates Máquina</label>
                                                    <input type="number" name="sessions_machine" class="form-control text-center" id="group-sessions-machine-edit"  value=""  autocomplete="new-password" >
                                            </div>
                                    </div>
                                    <div class="col-4">
                                            <div class="form-group">
                                                    <label for="group-sessions-individual-edit" class="form-control-label">Sesiones Fisioterapia</label>
                                                    <input type="number" name="sessions_individual" class="form-control text-center" id="group-sessions-individual-edit"  value=""  autocomplete="new-password" >
                                            </div>
                                    </div>
                               </div>
    
                           </div>
            
                           <div class="col-12">
                                <div class="form-group form-group-last">
                                        <label for="observation-edit">Observaciones</label>
                                        <textarea class="form-control" name="observation" id="observation-edit" rows="3" style="margin-top: 0px; margin-bottom: 0px; height: 137px;"></textarea>
                                    </div>
                            </div>
            
                        </div>
                           
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
               
                </div>
            </div>
            </div>
    <!--end: Modal edit client -->

    </form>







<!--start: Modal Delete clients -->
<div class="modal fade" id="modal_delete_clients" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
<div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Eliminar Clientes</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            </button>
        </div>
        <form action="{{route('management_client_delete')}}" id="form_delete_clients" method="POST" autocomplete="off">
            @csrf
            @method('delete')
            <div id="container-ids-clients-delete">

            </div>

        <div class="modal-body">
            <h1 class="text-uppercase text-center" style="font-size: 20px;">  <i class="flaticon-danger text-danger display-1"></i> <br> Realmente desea eliminar los clientes seleccionados. <div style="font-size:15px; color:red;"> ¡Si realizá esta acción tambien será eliminada toda la información de cada cliente eliminado!</div></h1>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Eliminar</button>
        </div>
    </form>
    </div>
</div>
</div>
<!--end: Modal Delete clients -->

<!--start: Modal Delete client -->
<div class="modal fade" id="modal_delete_client" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
<div class="modal-dialog modal-sm" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel">Eliminar Cliente</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
</button>
</div>
<form action="{{route('management_client_delete')}}"  method="POST" autocomplete="off">
@csrf
@method('delete')

<input type="hidden" name="id[]" value="" id="id_delete_client">

<div class="modal-body">
        <h1 class="text-uppercase text-center" style="font-size: 20px;">  <i class="flaticon-danger text-danger display-1"></i> <br> Realmente desea eliminar el cliente seleccionado. <div style="font-size:15px; color:red;"> ¡Si realizá esta acción tambien será eliminada toda la información del cliente eliminado!</div></h1>
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
<script src="{{asset("assets")}}/js/page-management-client.js" type="text/javascript"></script>
@endsection