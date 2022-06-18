@extends("$theme/layout")
@section('title') Panel de Control @endsection
@section('styles_page_vendors')
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css">
<link href="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />
@endsection
@section('styles_optional_vendors')

@endsection

@section('content_breadcrumbs') 
{!! PilatesHelper::getBreadCrumbs([
["route"=>"employee_profile","name"=>"Mi perfil"],
]) !!}
@endsection
@section('content_page')
    		<!-- begin:: Content -->
         
            <form action="{{route('employee_profile_update')}}"  method="POST" autocomplete="off"  enctype="multipart/form-data" >
               

                @csrf
                @method('put')
                <input type="hidden" name="id" value="{{$employee->id}}">
                    <div class="kt-portlet kt-portlet--mobile">
                        <div class="kt-portlet__head kt-portlet__head--lg">
                            <div class="kt-portlet__head-label">
                             
                                <h3 class="kt-portlet__head-title">
                                    Mi perfil
                                </h3>
                            </div>
                        
                                
                            <div class="kt-portlet__head-toolbar">
                                
                                <div class="kt-portlet__head-wrapper">
                                    
                                    <div class="kt-portlet__head-actions">
                     
                                            <button type="button" class="btn btn-brand btn-elevate btn-icon-sm" data-toggle="modal" data-target="#modal_change_password">
                                                    <i class="fa fa-key"></i>
                                                  
                                                    Cambiar contraseña
                                                </button>
                                                &nbsp;
                                        <button class="btn btn-brand btn-elevate btn-icon-sm" type="submit">
                                            <i class="fa fa-save"></i>
                                            Actualizar información
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="kt-portlet__body">
                       
                            <div class="row">
                                    <div class="col-12 d-flex justify-content-center align-items-center">

                                            <label for="img-change"  data-toggle="kt-tooltip" data-placement="top" title="" data-original-title="Clic para cambiar">
                                            <img id="img-change-profile" class="picture-profile" src="{{ (empty($employee->picture))? asset("assets/images/user_default.png") : Storage::url("images/profiles/".$employee->picture) }}"  />                 
                                            </label>
                                                            
                                            <input type='file' id="img-change" style="display:none" name="picture_upload" accept="image/*"/>
                                            <br>
                                            {{-- <small>Clic sobre la imagen para cambiar</small> --}}
                                    </div>
                                <div class="col-6">
                                     <div class="form-group">
                                           
                                            @foreach ($roles as $key => $rol)
                                            @if ($key==old('id_rol',$employee->id_rol))
                                         
                                            <label for="id-rol" class="form-control-label">Rol *</label>
                                            <input type="text" name="id_rol" class="form-control" id="id-rol"  value="{{$rol}}"  disabled>
                                            @endif
                                        
                                            @endforeach
                                     </div>
                                     <div class="form-group">
                                         <label for="recipient-name" class="form-control-label">Nombre *</label>
                                         <input type="text" name="name" class="form-control" id="recipient-name"  value="{{ old('name',$employee->name ?? '')}}"  required>
                                     </div>
                                     <div class="form-group">
                                             <label for="recipient-last-name" class="form-control-label">Apellidos *</label>
                                             <input type="text" name="last_name" class="form-control" id="recipient-last-name"  value="{{old('last_name',$employee->last_name ??'')}}"  required>
                                     </div>
                                     <div class="form-group">
                                             <label for="recipient-email" class="form-control-label">Email *</label>
                                             <input type="email" name="email" class="form-control" id="recipient-email"  value="{{old('email',$employee->email ??'')}}"  required>
                                     </div>
                                 
                                     <div class="form-group">
                                             <label for="recipient-password" class="form-control-label">Contraseña *</label>
                                             <input type="password" name="password" class="form-control" id="recipient-password"  value="{{old('password')}}"  >
                                     </div>
                                     <div class="form-group">
                                             <label for="recipient-re-password" class="form-control-label">Confirmar Contraseña *</label>
                                             <input type="password" name="password_confirmation" class="form-control" id="recipient-re-password"  value="{{old('password_confirmation')}}"  >
                                     </div>
                                
                                </div>
                                <div class="col-6">
                                   
                                     <div class="form-group">
                                             <label for="recipient-date-of-birth" class="form-control-label">Fecha de Nacimiento *</label>
                                             <input type="date" name="date_of_birth" class="form-control" id="recipient-date-of-birth"  value="{{old('date_of_birth',$employee->date_of_birth ??'')}}" required >
                                     </div>
                                     <div class="form-group">
                                             <label for="recipient-dni" class="form-control-label">Dni</label>
                                             <input type="text" name="dni" class="form-control" id="recipient-dni"  value="{{old('dni',$employee->dni ??'')}}" >
                                     </div>
                                     <div class="form-group">
                                             <label for="recipient-address" class="form-control-label">Dirección</label>
                                             <input type="text" name="address" class="form-control" id="recipient-address"  value="{{old('address',$employee->address ??'')}}" >
                                     </div>
                                     <div class="form-group">
                                             <label for="recipient-tel" class="form-control-label">Teléfono</label>
                                             <input type="tel" name="tel" class="form-control" id="recipient-tel"  value="{{old('tel',$employee->tel ??'')}}" >
                                     </div>
                                     <div class="form-group">
                                            <label class="">Sexo</label>
                                            <div class="kt-radio-inline d-flex justify-content-center p-2">
                                                <label class="kt-radio kt-radio--solid">
                                                    <input type="radio" name="sex" value="male" {{old('sex',$employee->sex ??'male')? (old('sex',$employee->sex ??'male')=='male')? 'checked' :'' : 'checked'   }}> Masculino
                                                    <span></span>
                                                </label>
                                                <label class="kt-radio kt-radio--solid">
                                                    <input type="radio" name="sex" value="fmale" {{old('sex',$employee->sex ??'male')? (old('sex',$employee->sex ??'male')=='fmale')? 'checked' :'' : ''   }}> Femenino
                                                    <span></span>
                                                </label>
                                            </div>
                                    
                                    </div>
                         
                               
                                </div>
                 
                                <div class="col-12">
                                     <div class="form-group form-group-last">
                                             <label for="observation">Observaciones</label>
                                             <textarea class="form-control" name="observation" id="observation" rows="3" style="margin-top: 0px; margin-bottom: 0px; height: 137px;" disabled>{{old('observation',$employee->observation ?? '')}}</textarea>
                                         </div>
                                 </div>
                 
                             </div>

                        </div>
                    </div>

                </form>


        
                <!-- end:: Content -->

                <!--start: Modal reset password -->
<div class="modal fade" id="modal_change_password" tabindex="-1" role="dialog"   aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cambiar contraseña</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form action="{{route('employee_profile_change_password')}}"  method="POST" autocomplete="off">
                    @csrf
                    @method('put')
                 
                    <input style="display:none">
                    <input type="hidden" name="id" value="{{$employee->id}}">
                <div class="modal-body" >
                        <div class="form-group">
                                <label for="recipient-now-password" class="form-control-label">Contraseña actual*</label>
                                <input type="password" name="now_password" class="form-control" id="recipient-now-password"  value="" autocomplete="off" >
                        </div>
                        <div class="form-group">
                                <label for="recipient-password" class="form-control-label">Nueva contraseña *</label>
                                <input type="password" name="password" class="form-control" id="recipient-password"  value="{{old('password')}}"  >
                        </div>
                        <div class="form-group">
                                <label for="recipient-re-password" class="form-control-label">Confirmar nueva contraseña *</label>
                                <input type="password" name="password_confirmation" class="form-control" id="recipient-re-password"  value=""  >
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
        <!--end: Modal reset password -->

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

