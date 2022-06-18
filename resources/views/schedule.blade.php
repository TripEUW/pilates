@extends("$theme/layout")
@section('title') Horarios @endsection
@section('styles_page_vendors')
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css">
<link href="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />



<link href="{{asset("assets/$theme")}}/vendors/custom/jscolor/pygment_trac.css" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/$theme")}}/vendors/custom/jscolor/iehacks.css" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/$theme")}}/vendors/custom/jscolor/colorjoe.css" rel="stylesheet" type="text/css"/>

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
<link href="{{asset("assets")}}/css/shedule-calendar.css" rel="stylesheet" type="text/css" />
@endsection


@section('content_breadcrumbs')
{!! PilatesHelper::getBreadCrumbs([
  ["route"=>"#","name"=>"Panel de Control"],
  ["route"=>route('schedule'),"name"=>"Horarios"]
  ]) !!}
  @endsection

  @section('content_page')
  <div class="row">
    <div class="col-lg-12">

      <!--begin::Portlet-->
      <div class="kt-portlet" id="kt_portlet">
        <div class="kt-portlet__head">
          <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
              <i class="kt-font-brand flaticon-calendar-with-a-clock-time-tools"></i>
            </span>
            <h3 class="kt-portlet__head-title">
              Horarios
            </h3>
          </div>

        </div>
        <div class="kt-portlet__body">


          <div class="btn-toolbar row" role="toolbar" aria-label="Toolbar with button groups">

            <div class="col-12 col-md-3 col-lg-3">
              <div class="btn-group mr-2 my-1 float-left" role="group" aria-label="First group">
                <button type="button" class="btn btn-primary btn-brand--icon" id="btn-back-time"><i class="flaticon2-back"></i></button>
                <button type="button" class="btn btn-primary btn-brand--icon" id="btn-next-time"><i class="flaticon2-next"></i></button>
              </div>
              <div class="btn-group mr-2 my-1" role="group" aria-label="Second group">
                <button type="button" class="btn btn-primary btn-brand--icon" id="btn-now-time">Hoy</button>
              </div>
            </div>

            <div class="col-12 col-md-3 col-lg-3">
              <div class="kt-margin-b-10-tablet-and-mobile w-100 text-center p-2" id="text-title-range">
                ---------------------------
              </div>
            </div>

            <div class="col-12 col-md-3 col-lg-3 d-flex justify-content-end">
              <div class="btn-group mr-2 my-1 ">
                <button type="button" class="btn btn-primary btn-brand--icon"  onclick="setEmployeeFilter(null)">Ver Todo</button>
              </div>
            </div>

            <div class="col-12 col-md-3 col-lg-3">

            </div>


          </div>

          <div class="kt-separator kt-separator--md kt-separator--dashed m-0"></div>
        </div>
        <div class="row w-100">
          <!--begin::calendar-->
          <div id="session-calendar" class="pt-0 pl-4  pb-4 col-9">

            <table class="session-calendar-table-weekly" >
              <thead>
                <tr>
                  <th class="time-cell-session">Hora</th>
                  <th class="cell-session-th">Lunes</th>
                  <th class="cell-session-th">Martes</th>
                  <th class="cell-session-th">Miércoles</th>
                  <th class="cell-session-th">Jueves</th>
                  <th class="cell-session-th">Visernes</th>
                  <th class="cell-session-th">Sábado</th>
                  <?php if($enableWeekend){ ?>
                    <th class="cell-session-th">Domingo</th>
                  <?php } ?>
                </tr>
              </thead>
              <tbody onclick="emptyTableSchedule()">
                <tr>
                  <td class="time-cell-session">
                    <span class="time"></span>
                    <span class="to-time"></span>
                    <span class="time"></span>
                  </td>
                  <td class="droppable-cell-shedule" data-date="0"><div class="event-shedule"></div></td>
                  <td class="droppable-cell-shedule" data-date="1"><div class="event-shedule"></div></td>
                  <td class="droppable-cell-shedule" data-date="2"><div class="event-shedule"></div></td>
                  <td class="droppable-cell-shedule" data-date="3"><div class="event-shedule"></div></td>
                  <td class="droppable-cell-shedule" data-date="4"><div class="event-shedule"></div></td>
                    <td class="droppable-cell-shedule" data-date="5"><div class="event-shedule"></div></td>
                    <?php if($enableWeekend){ ?>
                    <td class="droppable-cell-shedule" data-date="6"><div class="event-shedule"></div></td>
                  <?php } ?>
                </tr>
                {{-- item --}}
                <tr>
                  <td class="time-cell-session">
                    <span class="time"></span>
                    <span class="to-time"></span>
                    <span class="time"></span>
                  </td>
                  <td class="droppable-cell-shedule" data-date="0"><div class="event-shedule"></div></td>
                  <td class="droppable-cell-shedule" data-date="1"><div class="event-shedule"></div></td>
                  <td class="droppable-cell-shedule" data-date="2"><div class="event-shedule"></div></td>
                  <td class="droppable-cell-shedule" data-date="3"><div class="event-shedule"></div></td>
                  <td class="droppable-cell-shedule" data-date="4"><div class="event-shedule"></div></td>
                    <td class="droppable-cell-shedule" data-date="5"><div class="event-shedule"></div></td>
                    <?php if($enableWeekend){ ?>
                    <td class="droppable-cell-shedule" data-date="6"><div class="event-shedule"></div></td>
                  <?php } ?>
                </tr>
                {{-- item --}}
                <tr>
                  <td class="time-cell-session">
                    <span class="time"></span>
                    <span class="to-time"></span>
                    <span class="time"></span>
                  </td>
                  <td class="droppable-cell-shedule" data-date="0"><div class="event-shedule"></div></td>
                  <td class="droppable-cell-shedule" data-date="1"><div class="event-shedule"></div></td>
                  <td class="droppable-cell-shedule" data-date="2"><div class="event-shedule"></div></td>
                  <td class="droppable-cell-shedule" data-date="3"><div class="event-shedule"></div></td>
                  <td class="droppable-cell-shedule" data-date="4"><div class="event-shedule"></div></td>
                    <td class="droppable-cell-shedule" data-date="5"><div class="event-shedule"></div></td>
                    <?php if($enableWeekend){ ?>
                    <td class="droppable-cell-shedule" data-date="6"><div class="event-shedule"></div></td>
                  <?php } ?>
                </tr>
                {{-- item --}}

              </tbody>
            </table>
          </div>
          <!--end::calendar-->
          <div class="container-selected-employees p-0 col-3">

            <!--begin: Datatable -->
            <table class="table-bordered table-hover table-data-custom w-100 pt-0 mt-0"  id="kt_table_employees_shedule">
              <thead>
                <tr>
                  <th>Empleados</th>
                </tr>
              </thead>
            </table>
            <!--end: Datatable -->

          </div>

        </div>
      </div>
    </div>
    <!--end::Portlet-->
  </div>

  {{-- container holidays start --}}
  <div class="container w-100 container-holidays-schedule">
    <div class="row p-2 container-holidays-schedule-header">
      <div class="col-9 text-center">VACACIONES</div>
      <div class="col-3 text-center">TOTAL DÍAS</div>
    </div>
    <div class="row">
      <div class="col-9 text-center">
        <div class="row" id="container-holidays-dates">
          <div class="w-100 h-100 d-flex justify-content-center align-items-center p-5"><div class="w-100 text-center"><p class="mt-4">Filtre o seleccione un empleado dando clic derecho revisar vacaciones o doble clic izquierdo en algun elemento del empleado.</p></div></div>
        </div>
      </div>
      <div class="days-in-holidays col-3 text-center d-flex justify-content-center align-items-center" id="count-days-holidays">0</div>
    </div>

  </div>
  {{-- container holidays end --}}

  <!--start: Modal add shedule -->
  <div class="modal fade" id="modal_add_shedule" tabindex="-1" role="dialog"   aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Horario de Empleado</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          </button>
        </div>

        <div class="modal-body" >
          <div class="form-group">
            <label for="recipient-name" class="form-control-label">Empleado</label>
            <input type="text" style="text-transform: capitalize;" name="name" class="form-control text-center" id="employee-name-selected" readonly >
          </div>
          <div id="btn-add-more-employee" class="form-group">
            <label>Agregar Más empleados a este mismo Horario</label>
            <div class="input-group mb-3">
              <button class="btn btn-outline-secondary btn-input" type="button" onclick="showModalSelectEmployees()">Elegir Empleados</button>
            </div>
          </div>
          <div id="container-table-employees-add">

          </div>


          <div class="form-group">
            <label>Días que aplica *</label>
            <div class="form-group mb-2 py-4">
              <div class="kt-checkbox-inline text-center">
                <label class="kt-checkbox unselect-text">
                  <input name="monday" onchange="hideShowRowsTime()" id="check-monday" type="checkbox">Lunes
                  <span></span>
                </label>
                <label class="kt-checkbox unselect-text">
                  <input name="tuesday" onchange="hideShowRowsTime()" id="check-tuesday" type="checkbox">Martes
                  <span></span>
                </label>
                <label class="kt-checkbox unselect-text">
                  <input name="wednesday" onchange="hideShowRowsTime()" id="check-wednesday" type="checkbox">Miércoles
                  <span></span>
                </label>
                <label class="kt-checkbox unselect-text">
                  <input name="thursday" onchange="hideShowRowsTime()" id="check-thursday" type="checkbox">Jueves
                  <span></span>
                </label>
                <label class="kt-checkbox unselect-text">
                  <input name="friday" onchange="hideShowRowsTime()" id="check-friday" type="checkbox">Viernes
                  <span></span>
                </label>

                <label class="kt-checkbox unselect-text" >
                  <input name="saturday" onchange="hideShowRowsTime()" id="check-saturday" type="checkbox">Sábado
                  <span></span>
                </label>
                <label class="kt-checkbox unselect-text"  <?= (!$enableWeekend)?"style='display:none;'":""; ?>>
                  <input name="sunday" onchange="hideShowRowsTime()" id="check-sunday" type="checkbox">Domingo
                  <span></span>
                </label>
              </div>


            </div>
            <div class="form-group" id="form-group-time-session">

              <?php
              $days=['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
              $daysEs=['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'];
              if(!$enableWeekend){
                $days=['monday','tuesday','wednesday','thursday','friday','saturday'];
                $daysEs=['Lunes','Martes','Miércoles','Jueves','Viernes','Sabado'];
              }
              ?>
              <button type="button" onclick="setModeTime('advanced')" id="btn-mode-advanced" class="btn btn-primary">Cambiar a modo avanzado</button>
              <button type="button" onclick="setModeTime('simple')" id="btn-mode-simple" class="btn btn-primary">Cambiar a modo básico</button>
              {{--  start sample time--}}
              <div class="row my-2" id="simple-row-time">
                <div class="col-5">
                  <label>Hora Entrada</label>
                  <div class="input-group timepicker">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="picker-icon la la-clock-o"></i>
                      </span>
                    </div>
                    <input class="form-control text-center" id="timepicker_start1_simple" readonly value=""  type="text" />
                    <div class="fix-timepicker-schedule" id="container-timepicker-fix-left-simple">
                    </div>
                  </div>
                </div>
                <div class="col-5">
                  <label>Hora Salida</label>
                  <div class="input-group timepicker">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="picker-icon la la-clock-o"></i>
                      </span>
                    </div>
                    <input class="form-control text-center" id="timepicker_end1_simple" readonly  value="" type="text" />
                    <div class="fix-timepicker-schedule" id="container-timepicker-fix-right-simple">
                    </div>
                  </div>
                </div>
                  <div class="col-5" id="one-date-in-row">
                    <label>Hora Entrada</label>
                    <div class="input-group timepicker">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          <i class="picker-icon la la-clock-o"></i>
                        </span>
                      </div>
                      <input class="form-control text-center" id="timepicker_start1_one_simple" readonly value=""  type="text" />
                      <div class="fix-timepicker-schedule" id="container-timepicker-fix-left-one-simple">
                      </div>
                    </div>
                  </div>
                  <div class="col-5" id="one-date-out-row">
                    <label>Hora Salida</label>
                    <div class="input-group timepicker">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          <i class="picker-icon la la-clock-o"></i>
                        </span>
                      </div>
                      <input class="form-control text-center" id="timepicker_end1_one_simple" readonly  value="" type="text" />
                      <div class="fix-timepicker-schedule" id="container-timepicker-fix-right-one-simple">
                      </div>
                    </div>
                  </div>
                  <div class="col-2" id="one-date-delete-row">
                    <label>&nbsp;</label><br>
                    <button onclick="deleteNewTime(1)" class="btn btn-sm btn-clean btn-icon btn-icon-md" > <i class="flaticon-delete"></i></button>
                  </div>
                  <div class="col-5" id="two-date-in-row">
                    <label>Hora Entrada</label>
                    <div class="input-group timepicker">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          <i class="picker-icon la la-clock-o"></i>
                        </span>
                      </div>
                      <input class="form-control text-center" id="timepicker_start1_two_simple" readonly value=""  type="text" />
                      <div class="fix-timepicker-schedule" id="container-timepicker-fix-left-two-simple">
                      </div>
                    </div>
                  </div>
                  <div class="col-5" id="two-date-out-row">
                    <label>Hora Salida</label>
                    <div class="input-group timepicker">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          <i class="picker-icon la la-clock-o"></i>
                        </span>
                      </div>
                      <input class="form-control text-center" id="timepicker_end1_two_simple" readonly  value="" type="text" />
                      <div class="fix-timepicker-schedule" id="container-timepicker-fix-right-two-simple">
                      </div>
                    </div>
                  </div>
                  <div class="col-2" id="two-date-delete-row">
                    <label>&nbsp;</label><br>
                    <button onclick="deleteNewTime(2)" class="btn btn-sm btn-clean btn-icon btn-icon-md" > <i class="flaticon-delete"></i></button>
                  </div>
                <div class="col-12">
                  <br>
                  <button type="button" id="btn-one-new-date" onclick="addNewDate(1)" class="btn btn-outline-secondary btn-lg btn-block">Agregar horario 1</button>
                  <button type="button" id="btn-two-new-date" onclick="addNewDate(2)" class="btn btn-outline-secondary btn-lg btn-block">Agregar horario 2</button>
                </div>
              </div>

              {{--  end sample time--}}

              <?php
              foreach ($days as $key => $day){
                ?>
                {{--  start --}}
                <div class="row my-2" id="{{$day}}-row-time">
                  <div class="col-5">
                    <label>Hora Entrada {{$daysEs[$key]}}</label>
                    <div class="input-group timepicker">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          <i class="picker-icon la la-clock-o"></i>
                        </span>
                      </div>
                      <input class="form-control text-center" id="timepicker_start1_{{$day}}" readonly value=""  type="text" />
                      <div class="fix-timepicker-schedule" id="container-timepicker-fix-left-{{$day}}">
                      </div>
                    </div>
                  </div>
                  <div class="col-5">
                    <label>Hora Salida {{$daysEs[$key]}}</label>
                    <div class="input-group timepicker">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          <i class="picker-icon la la-clock-o"></i>
                        </span>
                      </div>
                      <input class="form-control text-center" id="timepicker_end1_{{$day}}" readonly  value="" type="text" />
                      <div class="fix-timepicker-schedule" id="container-timepicker-fix-right-{{$day}}">
                      </div>
                    </div>
                  </div>

                  <div class="col-5" id="{{$day}}-one-date-in-row">
                    <label>Hora Entrada {{$daysEs[$key]}}</label>
                    <div class="input-group timepicker">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          <i class="picker-icon la la-clock-o"></i>
                        </span>
                      </div>
                      <input class="form-control text-center" id="timepicker_start1_one_{{$day}}" readonly value=""  type="text" />
                      <div class="fix-timepicker-schedule" id="container-timepicker-fix-left-one-{{$day}}">
                      </div>
                    </div>
                  </div>
                  <div class="col-5" id="{{$day}}-one-date-out-row">
                    <label>Hora Salida {{$daysEs[$key]}}</label>
                    <div class="input-group timepicker">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          <i class="picker-icon la la-clock-o"></i>
                        </span>
                      </div>
                      <input class="form-control text-center" id="timepicker_end1_one_{{$day}}" readonly  value="" type="text" />
                      <div class="fix-timepicker-schedule" id="container-timepicker-fix-right-one-{{$day}}">
                      </div>
                    </div>
                  </div>
                  <div class="col-2" id="{{$day}}-one-date-delete-row">
                    <label>&nbsp;</label><br>
                    <button onclick="deleteNewTimeAdvance(1, '{{$day}}')" class="btn btn-sm btn-clean btn-icon btn-icon-md" > <i class="flaticon-delete"></i></button>
                  </div>

                  <div class="col-5" id="{{$day}}-two-date-in-row">
                    <label>Hora Entrada {{$daysEs[$key]}}</label>
                    <div class="input-group timepicker">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          <i class="picker-icon la la-clock-o"></i>
                        </span>
                      </div>
                      <input class="form-control text-center" id="timepicker_start1_two_{{$day}}" readonly value=""  type="text" />
                      <div class="fix-timepicker-schedule" id="container-timepicker-fix-left-two-{{$day}}">
                      </div>
                    </div>
                  </div>
                  <div class="col-5" id="{{$day}}-two-date-out-row">
                    <label>Hora Salida {{$daysEs[$key]}}</label>
                    <div class="input-group timepicker">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          <i class="picker-icon la la-clock-o"></i>
                        </span>
                      </div>
                      <input class="form-control text-center" id="timepicker_end1_two_{{$day}}" readonly  value="" type="text" />
                      <div class="fix-timepicker-schedule" id="container-timepicker-fix-right-two-{{$day}}">
                      </div>
                    </div>
                  </div>
                  <div class="col-2" id="{{$day}}-two-date-delete-row">
                    <label>&nbsp;</label><br>
                    <button onclick="deleteNewTimeAdvance(2, '{{$day}}')" class="btn btn-sm btn-clean btn-icon btn-icon-md" > <i class="flaticon-delete"></i></button>
                  </div>

                  <div class="col-12">
                    <br>
                    <button type="button" id="{{$day}}-btn-one-new-date" onclick="addNewDateAdvance(1, '{{$day}}')" class="btn btn-outline-secondary btn-lg btn-block">Agregar horario 1</button>
                    <button type="button" id="{{$day}}-btn-two-new-date" onclick="addNewDateAdvance(2, '{{$day}}')" class="btn btn-outline-secondary btn-lg btn-block">Agregar horario 2</button>
                  </div>

                </div>
                {{--  end --}}
                <?php
              }
              ?>
            </div>
          </div>
          <input type="hidden" name="id" id="id-employee-schedule-add">
          <input type="hidden" name="date" id="date-schedule">
          <input type="hidden" name="id" id="id-schedule-register">


          <div class="modal-footer mt-5">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" onclick="addSheduleEmployee(true)">Guardar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--end: Modal add shedule -->


  <!--start: Modal select employee -->
  <div class="modal fade" id="modal_select_employees" tabindex="-1" role="dialog"   aria-hidden="true">
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
                  <th>Empleado</th>
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


  <!--start: Modal change color  -->
  <div class="modal fade" id="modal_change_color" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Cambiar Color</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          </button>
        </div>
        <div class="modal-body text-center">
          <div id="extraPicker"></div>
          <input type="hidden" id="color-change-employee">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="button" onclick="changeColor()" class="btn btn-primary">Cambiar</button>
        </div>

      </div>
    </div>
  </div>
  <!--end: Modal change color  -->



  <!--start: Modal reset schedule -->
  <div class="modal fade" id="modal_reset_schedule" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Reiniciar Horario de Empleado</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          </button>
        </div>
        <div class="modal-body">
          <h1 class="text-uppercase text-center" style="font-size: 20px;">  <i class="flaticon-danger text-danger display-1"></i> <br> Realmente desea reiniciar el horario de este empleado. <div style="font-size:15px; color:red;"> ¡Si realizá esta acción será eliminado todo el horario de este empleado!</div></h1>
          <input type="hidden" id="id-employee-reset-schedule">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary" onclick="resetSchedule()">Reiniciar</button>
        </div>

      </div>
    </div>
  </div>
  <!--end: Modal reset schedule -->

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
  @endsection

  @section('js_page_vendors')
  <script>
  var routePublicImages=@json(asset("assets")."/images/");
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

<script src="{{asset("assets/$theme")}}/vendors/custom/jscolor/scale.fix.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/custom/jscolor/colorjoe.js" type="text/javascript"></script>


<script src="{{asset("assets")}}/js/page-schedule-datatables.js" type="text/javascript"></script>
<script src="{{asset("assets")}}/js/page-shedule.js" type="text/javascript"></script>

@endsection
