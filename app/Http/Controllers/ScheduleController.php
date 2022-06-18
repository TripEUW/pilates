<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidationDestroySchedule;
use App\Http\Requests\ValidationResetSchedule;
use App\Models\Employee;
use App\Models\Holidays;
use App\Models\Schedule;
use App\Rules\RuleCheckInWorkingEmployee;
use App\Rules\RuleInAdvanceHolidays;
use App\Rules\RuleInterferenceHolidays;
use App\Rules\RuleScheduleDays;
use App\Rules\RuleWorkingDaysSchedule;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{

    public function index()
    {
    $enableWeekend=false;
    return view("schedule",compact('enableWeekend'));
    }
    public function indexEmployee(){
    $enableWeekend=false;
    return view("schedule_employee",compact('enableWeekend'));
    }



    public function dataTableEmployee(Request $request)
    {

        $employee = new Employee();
        $employees = $employee->getEmployeeScheduleDataTable($request);
        return response()->json($employees);
    }

    public function dataTableEmployeeSelected(Request $request)
    {

        $employee = new Employee();
        $employees = $employee->getEmployeeScheduleSelectedDataTable($request);
        return response()->json($employees);
    }


    public function storeSchedule(Request $request)
    {

      // $find_data = Schedule::where('id_employee',$request->ids_employee[0]["id"])
      // ->where('date_start','>=',$request->date_start)
      // ->where('date_end','<=',$request->date_end);
      // if () {
      //   // code...
      // }

        $rules = [
            'days'  =>['required', new RuleWorkingDaysSchedule($request->input('days'))]
        ];

        $messages = [
            'days.required' => 'Necesita seleccionar al menos un día de la semana para aplicar el horario.'
        ];
        $days = $request->input('days');
        if($request->mode_time=='simple'){
        $rules["start_simple"] = 'required|date_format:"g:i A"';
        $rules["end_simple"] = 'required|dat    e_format:"g:i A"|after:'."start_simple";
        $messages["end_simple.after"] = "La hora de salida debe ser mayor que la hora de entrada.";

        }else{

        if (isset($days)) {
            foreach ($days as $key => $day) {
                $dayName='';
                if($day['day']==0)$dayName='lunes';
                if($day['day']==1)$dayName='martes';
                if($day['day']==2)$dayName='miércoles';
                if($day['day']==3)$dayName='jueves';
                if($day['day']==4)$dayName='viernes';
                if($day['day']==5)$dayName='sábado';
                if($day['day']==6)$dayName='domingo';

                $rules["days.$key.start"] = 'required|date_format:"g:i A"';
                $rules["days.$key.end"] = 'required|date_format:"g:i A"|after:'."days.$key.start";
                $messages["days.$key.end.after"] = "La hora de salida en $dayName debe ser mayor que la hora de entrada.";
            }
        }

        }

        $customAttr = [
            'days' => 'días'
        ];

        $validator = Validator::make($request->all(), $rules, $messages, $customAttr);

        if ($request->ajax()) {

            if ($validator->passes()) {

            $days2 = $request->input('days');
            $schedulesGroups = [];
            foreach ($days as $key => $day) {
                $scheduleGroup = [];
                foreach ($days2 as $key2 => $day2) {
                    if ($day['start'] == $day2['start'] && $day['end'] == $day2['end']) {
                        array_push($scheduleGroup, $day2);
                        unset($days2[$key2]);
                    }
                }
                unset($days[$key]);

                if (count($scheduleGroup) > 0) {
                    array_push($schedulesGroups, [$scheduleGroup]);
                }
            }

            // return response()->json(['status' => true, 'response' => $request,'dats' => $schedulesGroups]);


             $ids_employee=$request->input("ids_employee");
            foreach ($schedulesGroups as $item) {

                $item=$item[0];

                foreach ($ids_employee as $key => $employee) {

                $attrs = [
                    'id_employee'=>$employee["id"],
                    'date' =>$request->date,
                    'date_start'=>$request->date_start,
                    'date_end'=>$request->date_end,
                    'start' => DateTime::createFromFormat('g:i A', $item[0]["start"])->format('H:i:s'),
                    'end' => DateTime::createFromFormat('g:i A', $item[0]["end"])->format('H:i:s'),
                    'mode' => (count($schedulesGroups)==1)?$request->mode_time:'advanced'
                ];

                foreach ($item as $day) {
                    if($day['day']==0)$attrs['monday']='true';
                    if($day['day']==1)$attrs['tuesday']='true';
                    if($day['day']==2)$attrs['wednesday']='true';
                    if($day['day']==3)$attrs['thursday']='true';
                    if($day['day']==4)$attrs['friday']='true';
                    if($day['day']==5)$attrs['saturday']='true';
                    if($day['day']==6)$attrs['sunday']='true';
                }
                $finder = Schedule::where('id_employee',$employee["id"])
                ->where('date_start',$request->date_start)->where('date_end',$request->date_end)
                ->where('start',DateTime::createFromFormat('g:i A', $item[0]["start"])->format('H:i:s'))
                ->where('end',DateTime::createFromFormat('g:i A', $item[0]["end"])->format('H:i:s'))
                ->where('monday', isset($attrs['monday']) ? $attrs['monday'] : false)
                ->where('tuesday', isset($attrs['tuesday']) ? $attrs['tuesday'] : false)
                ->where('wednesday', isset($attrs['wednesday']) ? $attrs['wednesday'] : false)
                ->where('thursday', isset($attrs['thursday']) ? $attrs['thursday'] : false)
                ->where('friday', isset($attrs['friday']) ? $attrs['friday'] : false)
                ->where('saturday', isset($attrs['saturday']) ? $attrs['saturday'] : false)
                ->where('sunday', isset($attrs['sunday']) ? $attrs['sunday'] : false)
                ->first();
                if (isset($finder)) {
                  $messages = "Existen registros en los días y horas especificados";
                  return response()->json(['status' => false, 'response' => $messages, 'type' => 'simple']);
                }else {
                  Schedule::create($attrs);
                }
            }

            }

            return response()->json(['status' => true, 'response' => "Guardado correctamente"]);
            }else {
            return response()->json(['status' => false, 'response' => $validator->errors()->all()]);
            }

        } else {
            abort(404);
        }
    }

    public function editSchedule(Request $request)
    {

        $rules = [
            'days'  => ['required', new RuleWorkingDaysSchedule($request->input('days'))],
            'ids_employee' => [new RuleCheckInWorkingEmployee($request->ids_employee[0]["id"])]
        ];


        $messages = [
            'days.required' => 'Necesita seleccionar al menos un día de la semana para aplicar el horario.'
        ];
        $days = $request->input('days');
        if ($request->mode_time == 'simple') {
            $rules["start_simple"] = 'required|date_format:"g:i A"';
            $rules["end_simple"] = 'required|date_format:"g:i A"|after:' . "start_simple";
            $messages["end_simple.after"] = "La hora de salida debe ser mayor que la hora de entrada.";
        } else {


            if (isset($days)) {
                foreach ($days as $key => $day) {
                    $dayName = '';
                    if ($day['day'] == 0) $dayName = 'lunes';
                    if ($day['day'] == 1) $dayName = 'martes';
                    if ($day['day'] == 2) $dayName = 'miércoles';
                    if ($day['day'] == 3) $dayName = 'jueves';
                    if ($day['day'] == 4) $dayName = 'viernes';
                    if ($day['day'] == 5) $dayName = 'sábado';
                    if ($day['day'] == 6) $dayName = 'domingo';

                    $rules["days.$key.start"] = 'required|date_format:"g:i A"';
                    $rules["days.$key.end"] = 'required|date_format:"g:i A"|after:' . "days.$key.start";
                    $messages["days.$key.end.after"] = "La hora de salida en $dayName debe ser mayor que la hora de entrada.";
                }
            }
        }




        $customAttr = [
            'days' => 'días'
        ];

        $validator = Validator::make($request->all(), $rules, $messages, $customAttr);

        if ($request->ajax()) {

            if ($validator->passes()) {
            $tmpScheduleForDelete=Schedule::where('id_employee',$request->ids_employee[0]["id"])
            ->where('date_start','>=',$request->date_start)
            ->where('date_end','<=',$request->date_end);

            $idTmpScheduleForDelete=$tmpScheduleForDelete->get(['id'])->first()->id;
            $countTmpScheduleForDelete=$tmpScheduleForDelete->count();

           Schedule::where('id',$request->id)->delete();
           // return response()->json(['status' => false, 'response' => ["Ocurrió un error, intente de nuevo."]]);

            $days2 = $request->input('days');
            $schedulesGroups = [];
            foreach ($days as $key => $day) {
                $scheduleGroup = [];
                foreach ($days2 as $key2 => $day2) {
                    if ($day['start'] == $day2['start'] && $day['end'] == $day2['end']) {
                        array_push($scheduleGroup, $day2);
                        unset($days2[$key2]);
                    }
                }
                unset($days[$key]);

                if (count($scheduleGroup) > 0) {
                    array_push($schedulesGroups, [$scheduleGroup]);
                }
            }


            foreach ($schedulesGroups as $item) {

                $item=$item[0];

                $attrs = [
                    'id_employee'=>$request->ids_employee[0]["id"],
                    'date' =>$request->date,
                    'date_start'=>$request->date_start,
                    'date_end'=>$request->date_end,
                    'start' => DateTime::createFromFormat('g:i A', $item[0]["start"])->format('H:i:s'),
                    'end' => DateTime::createFromFormat('g:i A', $item[0]["end"])->format('H:i:s'),
                    'mode' => (count($schedulesGroups)==1)?$request->mode_time:'advanced'
                ];

                if($countTmpScheduleForDelete==1 && Schedule::where('id',$idTmpScheduleForDelete)->count()<=0){
                $attrs['id']=$idTmpScheduleForDelete;
                }


                foreach ($item as $day) {
                    if($day['day']==0)$attrs['monday']='true';
                    if($day['day']==1)$attrs['tuesday']='true';
                    if($day['day']==2)$attrs['wednesday']='true';
                    if($day['day']==3)$attrs['thursday']='true';
                    if($day['day']==4)$attrs['friday']='true';
                    if($day['day']==5)$attrs['saturday']='true';
                    if($day['day']==6)$attrs['sunday']='true';
                }
                Schedule::create($attrs);
            }



            return response()->json(['status' => true, 'response' => "Editado correctamente"]);
            }else {
            return response()->json(['status' => false, 'response' => $validator->errors()->all()]);
            }

        } else {
            abort(404);
        }
    }

    public function editColorEmployee(Request $request){

        if(Employee::where('id',$request->id)->update(["color"=>$request->color]))
        return response()->json(['status' => true, 'response' => "Color cambiado correctamente"]);
        return response()->json(['status' => false, 'response' => ["Ocurrió un problema al eliminar, intente de nuevo."]]);
    }
    public function destroySchedule(ValidationDestroySchedule $request){


    $daysToDisable=$request->input('days_to_disable');
    $attrsToUpdate=[];
    foreach ($daysToDisable as $day) {
    $attrsToUpdate[$day]='false';
    }

    Schedule::where('id',$request->id)
    ->where('date_start','>=',$request->date_start)
    ->where('date_end','<=',$request->date_end)
    ->update($attrsToUpdate);

    $flagDelete=false;
    $dayNames=['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
    $scheduleAnalize=Schedule::where('id',$request->id)
    ->where('date_start','>=',$request->date_start)
    ->where('date_end','<=',$request->date_end)->get()->first();

    foreach ($dayNames as  $dayName)
    if($scheduleAnalize[$dayName]=='true')
    $flagDelete=true;

    if($flagDelete==false){
    if(Schedule::where('id',$request->id)->where('date_start','>=',$request->date_start)->where('date_end','<=',$request->date_end)->delete()){
    return response()->json(['status' => true, 'response' => "Eliminado correctamente"]);
    }else{
    return response()->json(['status' => false, 'response' => ["Ocurrió un problema al eliminar, intente de nuevo."]]);
    }
    }
    return response()->json(['status' => true, 'response' => "Eliminado correctamente"]);



    }

    public function destroyHolidays(Request $request){
        $rules = [
            'id'  => 'required'
        ];

        $messages = [
            'id.required' => 'Debe seleccionar al menos una solicitud de vacaciones para eliminar.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($request->ajax()) {
            if ($validator->passes()) {

                $errors = 0;
                $cantSuccsess = 0;
                $idsHolidays = $request['id'];
                foreach ($idsHolidays as $id) {
                    if (Holidays::where('id', $id)->delete()) {
                        $cantSuccsess++;
                    } else {
                        $errors++;
                    }
                }

                return $cantSuccsess <= 1 ?
                    response()->json(['status' => true, 'response' => $cantSuccsess . ' elemento eliminado con éxito.'])
                    :
                    response()->json(['status' => true, 'response' => $cantSuccsess . ' elementos eliminados con éxito.']);
            }else {
            return response()->json(['status' => false, 'response' => $validator->errors()->all()]);
            }
        }else {
            abort(404);
        }

    }

    public function resetSchedule(ValidationResetSchedule $request){
    if(Schedule::where('id_employee',$request->id)->delete())
    return response()->json(['status' => true, 'response' => "Reinicio correcto"]);
    return response()->json(['status' => false, 'response' => ["Ocurrió un problema al eliminar, intente de nuevo."]]);
    }

    public function getDataSchedule(Request $request)
    {
    $employeeSelected=$request->employee_selected??null;

    $schedules = [];
    $schedules2 =[];

        if ($employeeSelected != null) {
            $schedules = Schedule::join('employee', 'employee.id', '=', 'schedule.id_employee')
                ->where('schedule.id_employee',$employeeSelected)
                ->where('date_start','>=',$request->date_start)
                ->where('date_end','<=',$request->date_end)
                ->get(['*', 'employee.id as id_employee', 'schedule.id as id_schedule']);
            $schedules2 = Schedule::join('employee', 'employee.id', '=', 'schedule.id_employee')
                ->where('schedule.id_employee',$employeeSelected)
                ->where('date_start','>=',$request->date_start)
                ->where('date_end','<=',$request->date_end)
                ->get(['*', 'employee.id as id_employee', 'schedule.id as id_schedule']);
        } else {
            $schedules = Schedule::join('employee', 'employee.id', '=', 'schedule.id_employee')
                ->where('date_start', '>=', $request->date_start)
                ->where('date_end', '<=', $request->date_end)
                ->get(['*', 'employee.id as id_employee', 'schedule.id as id_schedule']);

            $schedules2 = Schedule::join('employee', 'employee.id', '=', 'schedule.id_employee')
                ->where('date_start', '>=', $request->date_start)
                ->where('date_end', '<=', $request->date_end)
                ->get(['*', 'employee.id as id_employee', 'schedule.id as id_schedule']);
        }


    $schedulesEmployees = [];
    foreach ($schedules as $key => $schedule) {
        $scheduleGroup = [];
        foreach ($schedules2 as $key2 => $schedule2) {
            if ($schedule->start == $schedule2->start && $schedule->end == $schedule2->end) {
                array_push($scheduleGroup, clone $schedule2);
                unset($schedules2[$key2]);
            }
        }
        unset($schedules[$key]);

        if (count($scheduleGroup) > 0) {
            array_push($schedulesEmployees, [
                'groups' => $scheduleGroup,
                'start' => $scheduleGroup[0]->start,
                'end' => $scheduleGroup[0]->end
            ]);
        }

    }

    $schedulesEmployees = collect($schedulesEmployees)->sortBy('end', SORT_NATURAL | SORT_FLAG_CASE, false)->values()->all();


    return response()->json($schedulesEmployees);

    }

    public function getScheduleEmployee(Request $request){

    return response()->json(
        Schedule::
          where('id_employee',$request->id)
        ->where('date_start', '>=', $request->date_start)
        ->where('date_end', '<=', $request->date_end)
        ->where('start', $request->start)
        ->where('end', $request->end)
        ->where('monday', $request->monday)
        ->where('tuesday', $request->tuesday)
        ->where('wednesday', $request->wednesday)
        ->where('thursday', $request->thursday)
        ->where('friday', $request->friday)
        ->where('saturday', $request->saturday)
        ->where('sunday', $request->sunday)
        ->get());

    }

    public function getDataHolidays(Request $request){
        $data=Holidays::where('id_employee',$request->id)->get()
        ->map(function ($holiday) {
            //pending //accept
            if($holiday->status=='pending')$holiday['status_formated']="<span class='status-yellow p-1'>Pendiente</span>";
            if($holiday->status=='accept')$holiday['status_formated']="<span class='status-green p-1'>Aceptada</span>";

            $dateStart = Carbon::createFromFormat('Y-m-d',$holiday->start,config('app.timezone_for_pilates'));
            $now =Carbon::createFromFormat('d/m/Y', Carbon::now()->format('d/m/Y'),config('app.timezone_for_pilates'));

            $holiday['status_entrance']=($now>=$dateStart)?'in':'out';
            return $holiday;
        });
        $days=0;
        foreach ($data as $day) {
            $dateStart = Carbon::createFromFormat('Y-m-d',$day->start,config('app.timezone_for_pilates'));
            $dateEnd = Carbon::createFromFormat('Y-m-d', $day->end,config('app.timezone_for_pilates'));
            $days=$days+($dateStart->diffInDays($dateEnd)+1);
        }
        return response()->json(['data'=>$data,'days'=>$days]);
    }

    public function addHolidays(Request $request){


        $rules = [
            'id'  => 'required',
            'start_date'  => ['required','date_format:"d/m/Y"'],
            'end_date'  =>  ['required','date_format:"d/m/Y"','after_or_equal:start_date',
            new RuleInAdvanceHolidays($request->start_date,$request->end_date),
            new RuleInterferenceHolidays($request->start_date,$request->end_date,$request->id)
        ],

        ];

        $messages = [
            'start_date.required' => 'Necesita seleccionar el dia en que comienzan las vacaciones.',
            'end_date.required' => 'Necesita seleccionar el ultimo dia de vacaciones.',
            'end_date.after_or_equal' => 'El ultimo dia de vacaciones debe ser posterior a el dia en que comienzan.',
        ];
        $customAttr = [
            'start_date' => 'dia inicial',
            'end_date' => 'dia final'
        ];

        $validator = Validator::make($request->all(), $rules, $messages, $customAttr);

        if ($request->ajax()) {
            if ($validator->passes()) {

            $dateStart = Carbon::createFromFormat('d/m/Y',$request->start_date,config('app.timezone_for_pilates'))->format('Y-m-d');
            $dateEnd = Carbon::createFromFormat('d/m/Y', $request->end_date,config('app.timezone_for_pilates'))->format('Y-m-d');
            Holidays::create([
                'date_add'=>now(),
                'start'=>$dateStart,
                'end'=>$dateEnd,
                'id_employee'=>$request->id,
            ]);

            return response()->json(['status' => true, 'response' => "las vacaciones se han solicitado correctamente, un administrador las aceptará o rechazara puedes revisar el estatus en todo momento en la parte de abajo de esta ventana"]);
            }else {
            return response()->json(['status' => false, 'response' => $validator->errors()->all()]);
            }
        }else {
            abort(404);
        }

    }

    public function updateStatusHolidays(Request $request){
        $holiday=Holidays::where('id',$request->id);  //pending //accept
        if($holiday->get()->count()>0){
        if($holiday->get()->first()->status=="pending"){
        $holiday->get()->first()->update(['status'=>'accept']);
        }else{
        $holiday->get()->first()->update(['status'=>'pending']);
        }

        return response()->json(['status' => true, 'response' => ["El registro se ha actualizado."]]);
        }else{
            return response()->json(['status' => false, 'response' => ["El registro fue eliminado por el empleado."]]);
        }
    }
}
