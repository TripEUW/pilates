@extends("$theme/layout")
@section('title') Plantillas de Sesiones @endsection
@section('styles_page_vendors')
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css">
<link href="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />
@endsection
@section('styles_optional_vendors')
<link href="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/custom/jquery-ui-1.12.1/jquery-ui.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/custom/jquery-context-menu/jquery.contextMenu.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/custom/jquery-context-menu/jquery.contextMenu.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-timepicker/css/bootstrap-timepicker.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets")}}/css/session-calendar.css" rel="stylesheet" type="text/css" />
@endsection


@section('content_breadcrumbs')
{!! PilatesHelper::getBreadCrumbs([
  ["route"=>"#","name"=>"Panel de Control"],
  ["route"=>route('template'),"name"=>"Plantillas"]
  ]) !!}
  @endsection

  @section('content_page')
  <div class="row" id="templates-view" style="display: none;">
    <div class="col-lg-12">

      <!--begin::Portlet-->
      <div class="kt-portlet" id="kt_portlet">
        <div class="kt-portlet__head">
          <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
              <i class="kt-font-brand flaticon-calendar-with-a-clock-time-tools"></i>
            </span>
            <h3 class="kt-portlet__head-title">
              Plantillas de Sesiones
            </h3>
          </div>
          <div class="kt-portlet__head-toolbar">
            <a href="#" id="btn-sw-all" class="btn btn-brand btn-elevate mx-2 active-type-calendar">
              Todos
            </a>
            <a href="#" id="btn-sw-pilates" class="btn btn-brand btn-elevate mx-2 ">
              Pilates
            </a>
            <a href="#" id="btn-sw-physiotherapy" class="btn btn-brand btn-elevate mx-2">
              Fisioterapia
            </a>
          </div>
        </div>
        <div class="kt-portlet__body">

          <div class="btn-toolbar row mb-2" role="toolbar" aria-label="Toolbar with button groups">

            <div class="col-12 col-lg-3">
              <div class="form-group" id="container-select-template">
              </div>
            </div>

            <div class="col-12 col-lg-5 d-flex align-items-end">
              <div class=" w-100 text-center p-2 " id="text-title-range">
                <div id="name-template-selected" class="name-template-selected d-inline-block"></div>

                <div id="status-template-selected" class="status-template-selected d-inline-block"></div>

              </div>
            </div>

            <div class="col-12 col-lg-4 d-flex align-items-end">
              <div class="mr-2 float-right" role="group" aria-label="First group">
                <button type="button" class="btn btn-sm btn-primary btn-brand--icon" data-toggle="modal" data-target="#modal_add_template">Nueva</button>
                <button type="button" class="btn btn-sm btn-primary btn-brand--icon" id="text-btn-enable-disable" onclick="enableDisableTemplate()">Activar</button>
                <button type="button" class="btn btn-sm btn-primary btn-brand--icon"  onclick="deleteActualTemplate()" >Borrar</button>
                <button type="button" class="btn btn-sm btn-primary btn-brand--icon"  data-toggle="modal" data-target="#modal_rename_template">Renombrar</button>
              </div>
            </div>

          </div>

          <div class="kt-separator kt-separator--md kt-separator--dashed m-0"></div>
        </div>
        <!--begin::calendar-->
        <div id="session-calendar" class="pt-0 pl-4 pr-4 pb-4">

          <table class="session-calendar-table-weekly" >
            <thead>
              <tr><th class="time-cell-session">Hossra</th>
                <th class="cell-session-th">Lunes</th>
                <th class="cell-session-th">Martes</th>
                <th class="cell-session-th">Miércoles</th>
                <th class="cell-session-th">Jueves</th>
                <th class="cell-session-th">Viernes</th>
                <th class="cell-session-th">Sábado</th>
                <th class="cell-session-th">Domingo</th>

              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="time-cell-session">
                  <span class="time"></span>
                  <span class="to-time"></span>
                  <span class="time"></span>
                </td>
                <td class="droppable-cell-session"><div class="event-session"></div></td>
                <td class="droppable-cell-session"><div class="event-session"></div></td>
                <td class="droppable-cell-session"><div class="event-session"></div></td>
                <td class="droppable-cell-session"><div class="event-session"></div></td>
                <td class="droppable-cell-session"><div class="event-session"></div></td>
                <td class="droppable-cell-session"><div class="event-session"></div></td>
                <td class="droppable-cell-session"><div class="event-session"></div></td>
              </tr>
              {{-- item --}}
              <tr>
                <td class="time-cell-session">
                  <span class="time"></span>
                  <span class="to-time"></span>
                  <span class="time"></span>
                </td>
                <td class="droppable-cell-session"><div class="event-session"></div></td>
                <td class="droppable-cell-session"><div class="event-session"></div></td>
                <td class="droppable-cell-session"><div class="event-session"></div></td>
                <td class="droppable-cell-session"><div class="event-session"></div></td>
                <td class="droppable-cell-session"><div class="event-session"></div></td>
                <td class="droppable-cell-session"><div class="event-session"></div></td>
                <td class="droppable-cell-session"><div class="event-session"></div></td>
              </tr>
              {{-- item --}}
              <tr>
                <td class="time-cell-session">
                  <span class="time"></span>
                  <span class="to-time"></span>
                  <span class="time"></span>
                </td>
                <td class="droppable-cell-session"><div class="event-session"></div></td>
                <td class="droppable-cell-session"><div class="event-session"></div></td>
                <td class="droppable-cell-session"><div class="event-session"></div></td>
                <td class="droppable-cell-session"><div class="event-session"></div></td>
                <td class="droppable-cell-session"><div class="event-session"></div></td>
                <td class="droppable-cell-session"><div class="event-session"></div></td>
                <td class="droppable-cell-session"><div class="event-session"></div></td>
              </tr>
              {{-- item --}}

            </tbody>
          </table>


        </div>
        <!--end::calendar-->
      </div>
    </div>
    <!--end::Portlet-->
  </div>


  <div class="row" id="default-view" style="display: none;">
    <div class="col-lg-12">
      <!--begin::Portlet-->
      <div class="kt-portlet" id="kt_portlet">
        <div class="kt-portlet__head">
          <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
              <i class="kt-font-brand flaticon-calendar-with-a-clock-time-tools"></i>
            </span>
            <h3 class="kt-portlet__head-title">
              Plantillas de Sesiones
            </h3>
          </div>

        </div>
        <div class=" d-flex justify-content-center align-items-center" style="min-height: 50vh;">
          <button type="button" class="btn btn-primary btn-brand--icon" data-toggle="modal" data-target="#modal_add_template">Crear Primer Plantilla <br> <i class="flaticon-add-circular-button"></i></button>
        </div>
      </div>
    </div>
    <!--end::Portlet-->
  </div>




  <!--start: Modal add group session -->
  <div class="modal fade" id="modal_add_group_session" tabindex="-1" role="dialog"   aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Agregar Grupo de Sesiones</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          </button>
        </div>

        <div class="modal-body" >

          <div class="form-group mt-4" id="part-one">
            <label>Días</label>
            <div class="form-group mb-2 py-1">
              <div class="kt-checkbox-inline text-center">
                <label class="kt-checkbox unselect-text">
                  <input name="monday"  id="check-monday" type="checkbox">Lunes
                  <span></span>
                </label>
                <label class="kt-checkbox unselect-text">
                  <input name="tuesday"  id="check-tuesday" type="checkbox">Martes
                  <span></span>
                </label>
                <label class="kt-checkbox unselect-text">
                  <input name="wednesday"  id="check-wednesday" type="checkbox">Miércoles
                  <span></span>
                </label>
                <label class="kt-checkbox unselect-text">
                  <input name="thursday"  id="check-thursday" type="checkbox">Jueves
                  <span></span>
                </label>
                <label class="kt-checkbox unselect-text">
                  <input name="friday"  id="check-friday" type="checkbox">Viernes
                  <span></span>
                </label>
              </div>


            </div>
          </div>


          <div class="form-group" id="form-group-time-session">
            <div class="row">
              <div class="col-6">
                <label>Hora Inicios *</label>
                <div class="input-group timepicker">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="picker-icon la la-clock-o"></i>
                    </span>
                  </div>
                  <input class="form-control text-center" id="timepicker_start1" value=""  type="text" />
                  <div id="container-timepicker-fix">
                  </div>
                </div>
              </div>
              <div class="col-6">
                <label>Hora Terminación *</label>
                <div class="input-group timepicker">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="picker-icon la la-clock-o"></i>
                    </span>
                  </div>
                  <input class="form-control text-center" id="timepicker_end1" value="" type="text" />
                </div>
              </div>
            </div>
          </div>

          <input type="text" name="data-hour" hidden class="data-hour" id="data-hour" value="">

          {{-- group --}}
          <div class="form-group" id="part-two">
            <label>Grupo *</label>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <button class="btn btn-outline-secondary btn-input" type="button" onclick="showModalSelectGroup()">Elegir Grupo</button>
              </div>
              <input  class="form-control text-center" id="group-name-1" name="group_name" value="{{old('group_name')}}" readonly required>
              <div class="input-group-prepend">
                <button class="btn btn-outline-secondary btn-input text-center" type="button"  onclick="showModalAddGroup()"><i class="flaticon2-add-circular-button"></i>Nuevo</button>
              </div>
            </div>
            <input type="hidden" name="id_group" value="{{old('id_group')}}" id="group-id-1">
          </div>
          {{-- end group --}}
          {{-- employee
            <div class="form-group" id="part-three">
              <label>Empleado Grupo (opcional)</label>
              <div class="input-group mb-3">

                <div class="input-group-prepend">

                  <button class="btn btn-outline-secondary btn-input" type="button" onclick="showModalSelectEmployee()">Elegir Empleado</button>
                  <a href="{{route('schedule')}}" target="_blank" style="max-width:35px;" class="btn btn-secondary text-center p-1" ><i style="color:#000 !important;" class="flaticon-calendar-with-a-clock-time-tools pr-1"></i></a>
                </div>
                <input  class="form-control text-center" id="employee-name-1" name="employee_name" value="{{old('employee_name')}}" readonly required>
                <div class="input-group-prepend">
                  <button class="btn btn-outline-secondary btn-input text-center" type="button" onclick="showModalAddEmployee()"><i class="flaticon2-add-circular-button"></i>Nuevo</button>
                </div>
              </div>
              <input type="hidden" name="id_group" value="{{old('id_group')}}" id="group-id-1">
            </div>
            end employee --}}
            <div class="form-group" id="part-four">
              <label>Clientes (opcional)</label>
              <div class="input-group mb-3">
                <button class="btn btn-outline-secondary btn-input" type="button" onclick="showModalSelectClient()">Elegir Clientes</button>
              </div>
              <div id="container-table-clients-add">
                <div class="container">
                  <table class="table-bordered table-hover table-data-custom w-100">
                    <thead>
                      <tr>
                        <th>Cliente</th>
                        <th style="width: 55px;">Quitar</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr><td colspan="2" class="text-center"> Ningún cliente agregado. </td></tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <input type="hidden" name="id_client" id="client-id-1" value="{{old('id_client')}}" readonly>
            </div>
            <div class="form-group form-group-last" id="part-five">
              <label for="group-observation">Observaciones</label>
              <textarea class="form-control" name="observation" id="group-observation" rows="3" style="margin-top: 0px; margin-bottom: 0px; height: 50px;">{{old('observation')}}</textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <div id="part-six">
              <button type="submit" class="btn btn-primary" onclick="addGroupSession()">Agregar</button>
            </div>
            <div id="part-seven">
              <button type="submit" class="btn btn-primary" onclick="editHoursSession()">Editar</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--end: Modal add group session -->

    <!--start: Modal new session -->
    <div class="modal fade" id="modal_add_new_session" tabindex="-1" role="dialog"   aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Agregar  Sesión</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            </button>
          </div>

          <div class="modal-body" >

            <div class="form-group">
              <label>Clientes *</label>
              <div class="input-group mb-3">
                <button class="btn btn-outline-secondary btn-input" type="button" onclick="showModalSelectClient()">Elegir Clientes</button>
              </div>
              <div id="container-table-clients-add-2">
                <div class="container">
                  <table class="table-bordered table-hover table-data-custom w-100">
                    <thead>
                      <tr>
                        <th>Cliente</th>
                        <th style="width: 55px;">Quitar</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr><td colspan="2" class="text-center"> Ningún cliente agregado. </td></tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <input type="hidden" name="id_client" id="client-id-1" value="{{old('id_client')}}" readonly>
            </div>
            <div class="form-group form-group-last">
              <label for="group-observation-2">Observaciones</label>
              <textarea class="form-control" name="observation" id="group-observation-2" rows="3" style="margin-top: 0px; margin-bottom: 0px; height: 50px;">{{old('observation')}}</textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary" id="btn-add-session" onclick="addNewSessionGroup()">Agregar</button>
          </div>
        </div>
      </div>
    </div>
    <!--end: Modal new  session -->



    <!--start: Modal edit group session -->
    <div class="modal fade ml-0" id="modal_edit_group_session"    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" >Editar Grupo de Sesiones</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            </button>
          </div>

          <div class="modal-body" >


            <button type="button" onclick="editGroupSession()" class="btn btn-primary float-right" ><i class="fa fa-save"></i>Actualizar</button>
            {{-- <button type="button" onclick="showModalAddEmployee()" class="btn btn-primary float-right mx-2" ><i class="flaticon2-add-circular-button"></i>Nuevo Empleado</button> --}}
            <button type="button" onclick="showModalAddGroup(false)" class="btn btn-primary float-right mx-2" ><i class="flaticon2-add-circular-button"></i>Nuevo Grupo</button>

            <div class="row p-0 m-2">
              <div class="col-6  col-lg-4">
                <div class="float-rigth"  id="status-edit">
                  <div class="status-blue p-1 text-center">Vacío</div>
                </div>
              </div>
              <div class="col-6 col-lg-4">
                <label  id="level-edit">Nivel: 1</label>
              </div>
            </div>
            <div class="row mt-3">

              <div class="col-12 col-lg-12 d-flex justify-content-center align-items-center">

                <input type="hidden" id="day-edit-previous">


                <div class="form-group mt-4">
                  <div class="form-group mb-2 py-1">

                    <div class="kt-radio-inline">
                      <label class="kt-radio">
                        <input type="radio" name="edit-day" id="radio-monday"> Lunes
                        <span></span>
                      </label>
                      <label class="kt-radio">
                        <input type="radio"  name="edit-day" id="radio-tuesday"> Martes
                        <span></span>
                      </label>
                      <label class="kt-radio">
                        <input type="radio" name="edit-day" id="radio-wednesday"> Miércoles
                        <span></span>
                      </label>
                      <label class="kt-radio">
                        <input type="radio" name="edit-day" id="radio-thursday"> Jueves
                        <span></span>
                      </label>
                      <label class="kt-radio">
                        <input type="radio" name="edit-day" id="radio-friday"> Viernes
                        <span></span>
                      </label>
                    </div>

                  </div>
                </div>

              </div>
              <div class="col-12">
                <button type="button"  class="btn btn-primary btn-sm d-inline-block"  data-toggle="modal" data-target="#modal-schedule-default">Cambiar Horario</button>
              </div>
              <div class="col-12 col-lg-6">
                {{-- <div class="form-group" >
                  <label>Fecha *</label>
                  <div class="input-group date">
                    <div class="input-group-append">
                      <span class="input-group-text">
                        <i class="la la-calendar-check-o"></i>
                      </span>
                    </div>
                    <input type="date" class="form-control text-center" id="date_start_edit"  required  >
                    <input type="date" class="form-control text-center d-none" id="date_start_edit_previous"  required >
                  </div>
                </div> --}}

                <div class="form-group" >
                  <label>Hora Inicio </label>
                  <div class="input-group timepicker">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="picker-icon la la-clock-o"></i>
                      </span>
                    </div>
                    <input class="form-control text-center" id="timepicker_start_edit"  value="" disabled type="text" />
                    <input class="form-control text-center d-none" id="timepicker_start_edit_previous" readonly  value=""  type="text" />
                    <div id="container-timepicker-start-edit-fix">
                    </div>
                  </div>
                </div>

                <div class="form-group" >
                  <label>Hora Terminación </label>
                  <div class="input-group timepicker">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="picker-icon la la-clock-o"></i>
                      </span>
                    </div>
                    <input class="form-control text-center" id="timepicker_end_edit"   value="" disabled type="text" />
                    <input class="form-control text-center d-none" id="timepicker_end_edit_previous" readonly   value="" type="text" />
                    <div id="container-timepicker-end-edit-fix">
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-12 col-lg-6">


                <div class="form-group">
                  <label>Grupo </label>
                  <div class="input-group mb-1">
                    <div class="input-group-prepend">
                      <button class="btn btn-outline-secondary btn-input" type="button" onclick="showModalSelectGroup()">Elegir Grupo</button>
                    </div>
                    <input  class="form-control text-center" id="group-name-edit"  readonly required>
                    <!-- <div class="input-group-prepend">
                    <button class="btn btn-outline-secondary btn-input text-center" type="button"  onclick="showModalAddGroup()"><i class="flaticon2-add-circular-button"></i>Nuevo</button>
                  </div> -->
                </div>
                <input type="hidden"  value="" id="group-id-1-edit">

              </div>

              {{-- employee
                <div class="form-group">
                  <label>Empleado </label>
                  <div class="input-group mb-1">

                    <div class="input-group-prepend">

                      <button class="btn btn-outline-secondary btn-input" type="button" onclick="showModalSelectEmployee()">Elegir Empleado</button>
                      <a href="{{route('schedule')}}" target="_blank" style="max-width:35px;" class="btn btn-secondary text-center p-1" ><i style="color:#000 !important;" class="flaticon-calendar-with-a-clock-time-tools pr-1"></i></a>
                    </div>
                    <input  class="form-control text-center" id="employee-name-edit" readonly required>
                    <div class="input-group-prepend">
                      <button class="btn btn-outline-secondary btn-input text-center" type="button" onclick="showModalAddEmployee()"><i class="flaticon2-add-circular-button"></i>Nuevo</button>
                    </div>
                  </div>
                  <input type="hidden" name="id_group" value="{{old('id_group')}}" id="group-id-1">
                </div>
                end employee --}}

                <div class="row">
                  <div class="form-group col-6">
                    <label>Sala </label>
                    <div class="input-group">
                      <input  class="form-control" id="room-name-edit" readonly>
                    </div>
                  </div>
                  <div class="form-group col-6">
                    <label>Tipo </label>
                    <div class="input-group">
                      <input  class="form-control" id="room-type-edit"  readonly>
                    </div>
                  </div>
                </div>


              </div>


              <h4 class="text-center w-100 mt-4">Sesiones</h4>

              <!--begin: Datatable -->
              <div class="container">
                <table class="table-bordered table-hover table-data-custom" style="display:none"   id="kt_table_sessions_group">
                  <thead>
                    <tr>
                      <th class="clean-icon-table">
                        <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
                          <input type="checkbox" name="select_all" value="1" id="select-all-sessions">
                          <span></span>
                        </label>
                      </th>
                      <th>Apellidos Cliente</th>
                      <th>Nombre Cliente</th>
                      <th>Nivel Cliente</th>
                      <th>Observaciones</th>
                      <th>Elimnar Sesión</th>
                    </tr>
                  </thead>
                </table>
              </div>
              <!--end: Datatable -->



            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>

            </div>
          </div>
        </div>
      </div>
    </div>
    <!--end: Modal edit group session -->



    <!--start: Modal select group -->
    <div class="modal fade" id="kt_table_groups_modal" tabindex="-1" role="dialog"   aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Seleccionar Grupo</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            </button>
          </div>
          <div class="modal-body" >
            <div class="container">
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
                    <th>Tipo de sala</th>
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
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          </div>
        </div>
      </div>
    </div>
    <!--end: Modal select group -->

    <!--start: Modal select group -->
    <div class="modal fade" id="modal_edit_session_hour" tabindex="-1" role="dialog"   aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Seleccionar Grupo</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            </button>
          </div>
          <div class="modal-body" >
            <div class="container">
              <div class="form-group" >
                <label>Hora Inicio </label>
                <div class="input-group timepicker">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="picker-icon la la-clock-o"></i>
                    </span>
                  </div>
                  <input class="form-control text-center" id="timepicker_start_edit"  value="" type="text" />
                  <input class="form-control text-center d-none" id="timepicker_start_edit_previous"  value=""  type="text" />
                  <div id="container-timepicker-start-edit-fix">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          </div>
        </div>
      </div>
    </div>
    <!--end: Modal select group -->


    <!--start: Modal select schedule -->
    <div class="modal fade" id="modal-schedule-default" tabindex="-1" role="dialog"   aria-hidden="true">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Seleccionar un horario</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            </button>
          </div>
          <div class="modal-body d-flex justify-content-center align-items-center" >



            <div>
              <?php foreach ($defaultTimes as $key => $defaultTime) { ?>
                <div class="d-flex justify-content-center mb-3">
                  <div class="form-group"><div class="input-group timepicker">
                    <input class="form-control text-center" id="timepicker_schedule_start_{{$key}}" value="" disabled="" type="text">
                  </div>
                </div>

                <div class="form-group"><div class="input-group timepicker"><div class="input-group-prepend"><span class="input-group-text">a</span></div>
                <input class="form-control text-center" id="timepicker_schedule_end_{{$key}}" value="" disabled="" type="text">
              </div>
            </div>
            <button class="btn btn-outline-secondary btn-input text-center" type="button"  onclick="changeEditSchedule('{{$key}}')"><i class="flaticon2-add-circular-button"></i>Elegir</button>
          </div>

        <?php }?>
      </div>





    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
    </div>
  </div>
</div>
</div>
<!--end: Modal select schedule -->



<!--start: Modal select client -->
<div class="modal fade" id="kt_table_clients_modal" tabindex="-1" role="dialog"   aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Seleccionar Cliente</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <div class="modal-body" >
        <div class="container">
          <button type="button" class="btn btn-sm btn-primary btn-brand--icon" onclick="showHiddenFields('kt_table_clients',this)">Ver campos protegidos</button>
          <!--begin: Datatable -->
          <div class="container">
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
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>
<!--end: Modal select client -->

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


<!--start: Modal add template -->
<div class="modal fade" id="modal_add_template" tabindex="-1" role="dialog"   aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Agregar Plantilla</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <div class="modal-body" >

        <div class="form-group">
          <label for="template-name-add" class="form-control-label text-center">Nombre o titulo de plantilla*</label>
          <input type="text"  class="form-control text-center" id="template-name-add" required>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" onclick="createTemplate()" class="btn btn-primary">Crear</button>
      </div>

    </div>
  </div>
</div>
<!--end: Modal add template -->

<!--start: Modal rename template -->
<div class="modal fade" id="modal_rename_template" tabindex="-1" role="dialog"   aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Renombrar Plantilla</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <div class="modal-body" >

        <div class="form-group">
          <label for="template-name-add" class="form-control-label text-center">Nombre o titulo de plantilla*</label>
          <input type="text"  class="form-control text-center" id="template-name-edit" required>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" onclick="renameTemplate()" class="btn btn-primary">Actualizar</button>
      </div>

    </div>
  </div>
</div>
<!--end: Modal rename template -->


<!--start: Modal delete template -->
<div class="modal fade" id="modal_delete_template" tabindex="-1" role="dialog"   aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Eliminar Plantilla</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <div class="modal-body">
        <h1 class="text-uppercase text-center" style="font-size: 20px;">  <i class="flaticon-danger text-danger display-1"></i> <br> Realmente desea eliminar la plantilla. <div style="font-size:15px; color:red;"></div></h1>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" onclick="deleteTemplates()" class="btn btn-primary">Eliminar</button>
      </div>

    </div>
  </div>
</div>
<!--end: Modal delete template -->

<!--start: Modal add group -->
<div class="modal fade" id="modal_add_group" tabindex="-1" role="dialog"   aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Agregar Grupo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        </button>
      </div>


      <div class="modal-body" >

        <div class="form-group">
          <label for="group-name" class="form-control-label">Nombre *</label>
          <input type="text"  class="form-control" id="group-name-add"  autocomplete="new-password" required>
        </div>
        <div class="form-group">
          <label for="group-level" class="form-control-label">Nivel *</label>
          <input type="number"  class="form-control" id="group-level-add"  autocomplete="new-password" required>
        </div>


        <div class="form-group">
          <label>Sala *</label>
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <button class="btn btn-outline-secondary btn-input" type="button" onclick="showModalSelectRoom()">Elegir sala</button>
            </div>
            <input  class="form-control" id="group-name-room-add"  readonly required>
          </div>
          <input type="hidden"  id="group-id-room-add"  readonly>
        </div>



        <div class="form-group form-group-last">
          <label for="group-observation">Observaciones</label>
          <textarea class="form-control"  id="group-observation-add" rows="3" style="margin-top: 0px; margin-bottom: 0px; height: 50px;"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" onclick="createGroup()" class="btn btn-primary">Agregar</button>
      </div>

    </div>
  </div>
</div>
<!--end: Modal add group -->

<!--start: Modal add employee -->
<div class="modal fade" id="modal_add_employee" tabindex="-1" role="dialog"   aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Agregar Empleado</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <form action="{{route('dashboard_create_employee')}}" id="form-add-employee"  method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
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
                <input type="date" name="date_of_birth" class="form-control text-center" id="recipient-date-of-birth"  value="{{old('date_of_birth')}}" required >
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

<!--start: Modal check level  -->
<div class="modal fade" id="modal_check_level" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Nivel incompatible</h5>
        <button type="button" class="close" data-dismiss="modal"  onclick="acceptLevelDiff(false)" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" value="" id="type-level-check">
        <h1 class="text-uppercase text-center" style="font-size: 20px;">  <i class="flaticon-danger text-danger display-1"></i> <br> ¡El cliente y grupo seleccionado no pertenecen al mismo nivel! <br><br><br> <div style="font-size:15px; color:red;"> ¿Realmente desea elegir este grupo para el cliente?</div></h1>
      </div>
      <div class="modal-footer">
        <button type="button"  data-dismiss="modal" onclick="acceptAndCloseLevelDiff()"  class="btn btn-primary">Aceptar</button>
        <button type="button" class="btn btn-secondary" onclick="acceptLevelDiff(false)" data-dismiss="modal">Cancelar</button>
      </div>

    </div>
  </div>
</div>
<!--end: Modal check level  -->

<!--start: Modal check balance   -->
<div class="modal fade" id="modal-check-balance" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Saldo insuficiente</h5>
        <button type="button" class="close" data-dismiss="modal"  onclick="acceptBalanceDiff()" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h1 class="text-uppercase text-center" style="font-size: 20px;">  <i class="flaticon-danger text-danger display-1"></i> <br> ¡El cliente no cuenta con saldo suficiente! <br><br><br> <div style="font-size:15px; color:red;" id="modal-check-balance-text"> </div></h1>
      </div>
      <div class="modal-footer">
        <button type="button"  data-dismiss="modal" onclick="acceptAndCloseBalanceDiff(true)"  class="btn btn-primary">Agregar con saldo negativo</button>
        <button type="button"  data-dismiss="modal" onclick="acceptAndCloseBalanceDiff()"  class="btn btn-primary">Canjear sesiones</button>
        <button type="button" class="btn btn-secondary" onclick="acceptBalanceDiff()" data-dismiss="modal">No</button>
      </div>
    </div>
  </div>
</div>
<!--end: Modal check balance  -->

<!--start: Modal check balance negative  -->
<div class="modal fade" id="modal-check-balance-2" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Saldo insuficiente</h5>
        <button type="button" class="close" data-dismiss="modal"  onclick="acceptBalanceDiff()" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h1 class="text-uppercase text-center" style="font-size: 20px;">  <i class="flaticon-danger text-danger display-1"></i> <br> ¡El cliente no cuenta con saldo suficiente! <br><br><br> <div style="font-size:15px; color:red;" id="modal-check-balance-text-2"> </div></h1>
      </div>
      <div class="modal-footer">

        <button type="button"  data-dismiss="modal" onclick="acceptAndCloseBalanceDiff(true)"  class="btn btn-primary">Si</button>
        <button type="button" class="btn btn-secondary" onclick="acceptBalanceDiff()" data-dismiss="modal">No</button>
      </div>
    </div>
  </div>
</div>
<!--end: Modal check balance negative -->

<!--start: Modal Delete session -->
<div class="modal fade" id="modal_delete_session" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Eliminar Sesión</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <div class="modal-body">
        <h1 class="text-uppercase text-center" style="font-size: 20px;">  <i class="flaticon-danger text-danger display-1"></i> <br> Realmente desea eliminar la sesión de este cliente. <div style="font-size:15px; color:red;"></div></h1>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button  data-dismiss="modal" onclick="confirmDeleteSession()" class="btn btn-primary">Eliminar</button>
      </div>
    </div>
  </div>
</div>
<!--end: Modal Delete session -->

<!--start: Modal Delete Group Session -->
<div class="modal fade" id="modal_delete_group_sessions" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Eliminar Grupo de Sesiones</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <div class="modal-body">
        <h1 class="text-uppercase text-center" style="font-size: 20px;">  <i class="flaticon-danger text-danger display-1"></i> <br> Realmente desea eliminar el grupo de sesiones. <div style="font-size:15px; color:red;"> ¡Si realiza esta acción también serán eliminadas las sesiones de este grupo!</div></h1>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button  data-dismiss="modal" onclick="confirmDeleteGroupSessions()" class="btn btn-primary">Eliminar</button>
      </div>
    </div>
  </div>
</div>
<!--end: Modal Delete Group Session -->



<!-- begin:: Panel -->
<div id="kt_demo_panel" class="kt-demo-panel p-2">
  <div class="kt-demo-panel__head">
    <div class="w-100">
      <button type="button" onclick="setMoveSessions()" class="btn btn-primary">Mover Seleccionados</button>


    </div>
    <a href="#" class="kt-demo-panel__close" onclick="hideShowPanelLeft(false)"><i class="flaticon2-delete"></i></a>



  </div>


  <div class="kt-demo-panel__body">
    <!--begin: Datatable -->

    <table class="table-bordered table-hover table-data-custom" style="display:none"   id="kt_table_sessions_group_move">
      <thead>
        <tr>
          <th class="clean-icon-table">
            <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
              <input type="checkbox" name="select_all" value="1" id="select-all-sessions-move">
              <span></span>
            </label>
          </th>
          <th>Apellidos</th>
          <th>Cliente</th>
          <th>Nivel</th>
          <th>Observaciones</th>
          <th>Elimnar</th>
        </tr>
      </thead>
    </table>

    <!--end: Datatable -->
  </div>
</div>

<!-- end:: Panel -->

<input type="hidden" name="_token" id="token_ajax" value="{{ Session::token() }}">
@endsection

@section('js_page_vendors')
<script>
var routePublicImages=@json(asset("assets")."/images/");
var templateSelected=@json($idDefault);
</script>
<script src="{{asset("assets/$theme")}}/vendors/general/block-ui/jquery.blockUI.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/js/bootstrap-select.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.min.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-datetime-picker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/custom/components/vendors/bootstrap-timepicker/init.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
@endsection


@section('js_optional_vendors')

@endsection
@section('js_page_scripts')
<script src="{{asset("assets/$theme")}}/vendors/custom/jquery-ui-1.12.1/jquery-ui.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/custom/jquery-context-menu/jquery.ui.position.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/custom/jquery-context-menu/jquery.contextMenu.js" type="text/javascript"></script>
<script src="{{asset("assets")}}/js/page-template-datatables.js" type="text/javascript"></script>
<script src="{{asset("assets")}}/js/page-template.js" type="text/javascript"></script>

@endsection
