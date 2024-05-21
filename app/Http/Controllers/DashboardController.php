<?php

namespace App\Http\Controllers;

use App\Helpers\Pilates;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Group;
use App\Models\LockAdd;
use App\Models\NoWorkDay;
use App\Models\Rol;
use App\Models\Room;
use App\Models\Session;
use App\Models\SessionTemplate;
use App\Models\Template;
use App\Rules\RuleDuplicateGroupSessions;
use App\Rules\RuleDuplicateSessionClientGroup;
use App\Rules\RuleDuplicateSessionSameGroup;
use App\Rules\RuleDuplicatesSessionClient;
use App\Rules\RuleEmployeeDuplicateSessionTime;
use App\Rules\RuleEmployeeDuplicateSessionTime2;
use App\Rules\RuleEmployeeDuplicateSessionTimeDrag;



use App\Rules\RuleEmployeeSetGroup;
use App\Rules\RuleEmptyGroup;
use App\Rules\RuleExistEmployeeInGroup;
use App\Rules\RuleNoWorkDayEvent;
use App\Rules\RuleRoomCrash;
use App\Rules\RuleSessionSameType;
use App\Rules\RuleSufficientBalance;
use App\Rules\RuleSufficientCapacityRoomGroup;
use App\Rules\RuleTemplateActive;
use App\Rules\RuleTemplateHasBeenLoaded;
use App\Rules\RuleWorkingDays;
use App\Rules\sameTypeRoomGroup;
use Carbon\Carbon;
use Carbon\Traits\Date;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use PDF;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Rol::where('id',2)->orderBy('id')->pluck('name', 'id')->toArray();
        return view('dashboard', compact('roles'));
    }


    public function dataTableGroupCalendar(Request $request)
    {
        $group = new Group();
        $groups = $group->getGroupDataTableForCalendar($request);
        return response()->json($groups);
    }

    public function dataTableSessionsGroup(Request $request)
    {
        $session = new Session();
        $sessions = $session->getSessionsGroupForCalendar($request);
        return response()->json($sessions);
    }

    public function checkBalance(Request $request)
    {
        if ($request->ajax()) {
            $response = Pilates::checkBalance($request->id_group, $request->id_client);
            return response()->json($response);
        } else {
            abort(404);
        }
    }

    public function storeEmployee(Request $request){
        $rules = [
            'name' => 'required|max:50',
            'last_name' => 'required|max:100',
            'user_name' => 'nullable|max:100|unique:employee,user_name',
            'password' => 'required|min:5',
            'password_confirmation' => 'required|same:password|min:5',
            'dni' => 'max:10',
            'tel' => 'max:100',
            'email' => 'required|email|max:100|unique:employee,email',
            'address' => 'max:500',
            'sex' => 'required|max:255',
            'date_of_birth' => 'required|date_format:Y-m-d',
            'observation' => 'max:5000',
            'status' => 'required',
            'id_rol' => 'required|integer|exists:rol,id',
            'picture_upload' => 'image|max:10240' //kilobytes 10240 = 10mb
        ];
        $messages=[
            'picture_upload.image' => 'La foto del empleado debe ser en formato de imagen como: jpg, png.',
            'picture_upload.max' => 'La foto del empleado no puede ser mayor a 10 mb.',
        ];
        $customAttr=[
            'name' => 'nombre',
           'last_name' => 'apellidos',
           'user_name' => 'nombre de usuario',
           'tel' => 'teléfono',
           'address' => 'dirección',
           'sex' => 'sexo',
           'date_of_birth' => 'fecha de nacimiento',
           'observation' => 'observaciones',
           'picture_upload' => 'imagen de perfil',
           'password'=>'contraseña',
           'password_confirmation'=>'confirmar contraseña',
           'status'=>'estado de cuenta',
        ];
        $validator = Validator::make($request->all(), $rules, $messages, $customAttr);

        if ($request->ajax()) {

            if ($validator->passes()) {
            $request->merge(['color'=>'#' . substr(md5(mt_rand()), 0, 6)]);

            if ($request->has('password'))
            $request->merge(['password' => bcrypt($request->password)]);

            $employee = Employee::create(array_filter($request->except('password_confirmation', 'user_name', 'picture_upload')));
            if ($request->hasFile('picture_upload')) {

                $ext = $request->file('picture_upload')->extension();
                $pictureObj =  $request->picture_upload;

                $pictureName = $employee->id . time() . ".$ext";
                $pictureObj = Image::make($pictureObj)->encode($ext, 75);
                $pictureObj->resize(150, 150, function ($constraint) {
                    $constraint->aspectRatio();
                });
                Storage::disk('public')->put("images/profiles/$pictureName", $pictureObj->stream());
                Employee::findOrFail($employee->id)->update(['picture' =>  $pictureName]);
            }

            $namespace = 'App\Http\Controllers';
            $controller = app()->make($namespace . '\Auth\EmployeeForgotPasswordController');
            $controller->callAction('sendResetLinkEmail', [$request]);

               /*auditoria: start*/Pilates::setAudit("Alta empleado id: $employee->id"); /*auditoria: end*/
            return response()->json(['response' => true,'employee'=>['employee_id'=>$employee->id,'employee_name'=>"$employee->name $employee->last_name"],'message'=>'Empleado creado con éxito.<br>Puede proporcionar la contraseña creada para este empleado o el empleado puede restablecer la contraseña en el mail que se le ha enviado.','error' =>[]]);
            }else{
                return response()->json(['response' => false,'employee'=>[],'message'=>'','error' => $validator->errors()->all()]);
            }

        }else {
            abort(404);
        }
    }

    public function storeGroup(Request $request){

        $rules = [
            'name' => 'required|max:29|unique:group,name',
            'level' => 'required|integer|min:1|max:100',
            //'id_employee' => 'required',
            'id_room' => 'required',
            'observation' => 'max:5000'
        ];
        $messages=[
            'id_employee.required' => 'Debe seleccionar un empleado para el grupo.',
            'id_room.required' => 'Debe seleccionar una sala para el grupo.',
        ];
        $customAttr=[
            'name' => 'nombre de grupo',
            'level' => 'nivel',
            'observation' => 'observaciones'
        ];
        $validator = Validator::make($request->all(), $rules, $messages, $customAttr);

        if ($request->ajax()) {

            if ($validator->passes()) {
                $group=Group::create($request->all());

               /*auditoria: start*/Pilates::setAudit("Alta grupo id: $group->id"); /*auditoria: end*/
                return response()->json(['response' => true,'group'=>['id'=>$group->id,'name'=>$group->name],'message'=>"Grupo creado y seleccionado con éxito",'error' =>[]]);
            }else{
                return response()->json(['response' => false,'message'=>'','error' => $validator->errors()->all()]);
            }

        }else {
            abort(404);
        }
    }

    public function storeGroupSession(Request $request)
    {
        $daysSelected=(!empty($request->input('serie_days_selected')))?$request->input('serie_days_selected'):[];
        $serieDays = [];

        $cantClients=(!empty($request->input('clients_selected')))?count($request->input('clients_selected')):0;
        $rules = [

            'group_selected'  => [
                'requi',
                'required_with:clients_selected',
                'required_with:employee_selected',
                new RuleEmptyGroup($request->group_selected['id_group'], $request->date_start . ' ' . $request->timepicker_start, $request->date_start . ' ' . $request->timepicker_end, $cantClients),
                new RuleDuplicateGroupSessions($request->group_selected['id_group'], $request->date_start . ' ' . $request->timepicker_start, $request->date_start . ' ' . $request->timepicker_end),
                new RuleRoomCrash($request->group_selected['id_group'], $request->date_start . ' ' . $request->timepicker_start, $request->date_start . ' ' . $request->timepicker_end),
                new RuleTemplateHasBeenLoaded($request->date_start . ' ' . $request->timepicker_start, $request->date_start . ' ' . $request->timepicker_end,$request->mode_static)
            ],
            'date_start'   => ['required', 'date_format:"Y-m-d"', new RuleWorkingDays($request->date_start), new RuleNoWorkDayEvent($request->date_start)],
            'timepicker_start' => 'required|date_format:"g:i A"',
            'timepicker_end' => 'required|date_format:"g:i A"|after:timepicker_start',
            'observation' => 'nullable|max:250'
        ];

        if (!empty($request->input('group_selected'))) {


            if (!empty($request->input('clients_selected'))) {
                $clients =  $request->input('clients_selected');
                foreach ($clients as $key => $client) {
                    $rules["clients_selected.$key.id"] = [
                        /*new RuleSufficientBalance(
                            $request->group_selected['id_group'],
                            $client['id'],
                            "$client[name] $client[last_name]"
                        ),*/
                        new RuleDuplicatesSessionClient($request->group_selected['id_group'], $request->date_start . ' ' . $request->timepicker_start, $request->date_start . ' ' . $request->timepicker_end,  $client['id'], "$client[name] $client[last_name]")
                    ];
                }
            }
        }

        if (!empty($request->input('employee_selected'))) {
            $rules['employee_selected'] = [
                'nullable',
                new RuleEmployeeSetGroup($request->employee_selected, $request->date_start . ' ' . $request->timepicker_start,$request->date_start . ' ' . $request->timepicker_end,true),
                new RuleEmployeeDuplicateSessionTime($request->employee_selected, $request->date_start . ' ' . $request->timepicker_start,$request->date_start . ' ' . $request->timepicker_end,$request->group_selected['id_group'])
            ];
        }

        $messages = [
            'group_selected.required' => 'Necesita seleccionar un grupo.',
            'employee_selected.required' => 'Necesita seleccionar un empleado.',
            // 'client_selected.required' => 'Necesita seleccionar un cliente.',
            'timepicker_end.after' => 'La hora final debe ser mayor que la hora inicial.',
            'group_selected.required_with' => 'Es necesario que elija un grupo.'
        ];

        $customAttr = [
            'group_selected' => 'grupo',
            'date_start' => 'fecha',
            'timepicker_start' => 'hora de inicio',
            'timepicker_end' => 'hora de término',
            'observation' => 'observaciones'
        ];



        $validator = Validator::make($request->all(), $rules, $messages, $customAttr);

        if ($request->ajax()) {

            if ($validator->passes()) {
                $idEmployee = auth()->user()->id;

                $groupRoom = Group::where("group.id", $request->group_selected['id_group'])->join('room', 'group.id_room', '=', 'room.id')->first();

                $dateStart = $request->date_start . ' ' . $request->timepicker_start;
                $dateEnd = $request->date_start . ' ' . $request->timepicker_end;


                $dateStart = DateTime::createFromFormat('Y-m-d g:i A', $dateStart)->format('Y-m-d H:i:s');
                $dateEnd = DateTime::createFromFormat('Y-m-d g:i A', $dateEnd)->format('Y-m-d H:i:s');

                $balanceCheck = null;


                if(!empty($request->input('serie_days_selected'))){
                    $daysSelectedItems=['monday','tuesday','wednesday','thursday','friday'];
                    $dateDay = Carbon::createFromFormat('Y-m-d', $request->date_start);

                    $year = $dateDay->year;
                    $month = $dateDay->month;
                    $days = $dateDay->daysInMonth;


                    foreach (range(1, $days) as $day) {
                        $date = Carbon::createFromFormat('Y-m-d', "$year-$month-$day");
                        $dayNoWork=NoWorkDay::where('date', $date->clone()->format('Y-m-d'))->exists();

                        if (!$dayNoWork  && (in_array($daysSelectedItems[0], $daysSelected)) && $date->clone()->isMonday() === true && ($date > $dateDay)) $serieDays[] = (Carbon::createFromFormat('Y-m-d', "$year-$month-$day")->format('Y-m-d'));
                        if (!$dayNoWork  && (in_array($daysSelectedItems[1], $daysSelected)) && $date->clone()->isTuesday() === true && ($date > $dateDay)) $serieDays[] = (Carbon::createFromFormat('Y-m-d', "$year-$month-$day")->format('Y-m-d'));
                        if (!$dayNoWork  && (in_array($daysSelectedItems[2], $daysSelected)) && $date->clone()->isWednesday() === true && ($date > $dateDay)) $serieDays[] = (Carbon::createFromFormat('Y-m-d', "$year-$month-$day")->format('Y-m-d'));
                        if (!$dayNoWork  && (in_array($daysSelectedItems[3], $daysSelected)) && $date->clone()->isThursday() === true && ($date > $dateDay)) $serieDays[] = (Carbon::createFromFormat('Y-m-d', "$year-$month-$day")->format('Y-m-d'));
                        if (!$dayNoWork  && (in_array($daysSelectedItems[4], $daysSelected)) && $date->clone()->isFriday() === true && ($date > $dateDay)) $serieDays[] = (Carbon::createFromFormat('Y-m-d', "$year-$month-$day")->format('Y-m-d'));
                    }

                }

                if (!empty($request->input('clients_selected'))) {
                    $clients =  $request->input('clients_selected');

                    foreach ($clients as $key => $client) {

                    $balanceCheck = Pilates::checkBalance($request->group_selected['id_group'],  $client['id']);

                    if($balanceCheck['success'] == true || $client['negative_balance']){
                    if ($groupRoom->type_room == 'Máquina') {
                        Session::create([
                            'id_group' => $request->group_selected['id_group'],
                            'id_client' => $client['id'] ?? null,
                            'date_start' => $dateStart,
                            'date_end' => $dateEnd,
                            'observation' => $request->observation,
                            'sessions_machine' => (($balanceCheck['success'] == true && $balanceCheck['error'] == false) || $client['negative_balance']=='true') ? 1 : 0,
                            'sessions_floor' => (($balanceCheck['success'] == true && $balanceCheck['error'] != false) && $client['negative_balance']=='false') ? 2 : 0,
                        ]);
                    } else if ($groupRoom->type_room == 'Suelo') {
                        Session::create([
                            'id_group' => $request->group_selected['id_group'],
                            'id_client' => $client['id'] ?? null,
                            'date_start' => $dateStart,
                            'date_end' => $dateEnd,
                            'observation' => $request->observation,
                            'sessions_floor' => 1
                        ]);
                    } else if ($groupRoom->type_room == 'Camilla') {
                        Session::create([
                            'id_group' => $request->group_selected['id_group'],
                            'id_client' => $client['id'] ?? null,
                            'date_start' => $dateStart,
                            'date_end' => $dateEnd,
                            'observation' => $request->observation,
                            'sessions_individual' => 1
                        ]);
                    }
                }

                }


                foreach ($serieDays as $key => $serieDay) {
                $dateStart = $serieDay . ' ' . $request->timepicker_start;
                $dateEnd = $serieDay . ' ' . $request->timepicker_end;
                $dateStart = DateTime::createFromFormat('Y-m-d g:i A', $dateStart)->format('Y-m-d H:i:s');
                $dateEnd = DateTime::createFromFormat('Y-m-d g:i A', $dateEnd)->format('Y-m-d H:i:s');

                foreach ($clients as $key => $client) {

                $balanceCheck = Pilates::checkBalance($request->group_selected['id_group'],  $client['id']);

                if($balanceCheck['success'] == true || $client['negative_balance']=='true'){
                    if ($groupRoom->type_room == 'Máquina') {
                        Session::create([
                            'id_group' => $request->group_selected['id_group'],
                            'id_client' => $client['id'] ?? null,
                            'date_start' => $dateStart,
                            'date_end' => $dateEnd,
                            'observation' => $request->observation,
                            'sessions_machine' => (($balanceCheck['success'] == true && $balanceCheck['error'] == false) || $client['negative_balance']=='true') ? 1 : 0,
                            'sessions_floor' => (($balanceCheck['success'] == true && $balanceCheck['error'] != false) && $client['negative_balance']=='false') ? 2 : 0,
                        ]);
                    } else if ($groupRoom->type_room == 'Suelo') {
                        Session::create([
                            'id_group' => $request->group_selected['id_group'],
                            'id_client' => $client['id'] ?? null,
                            'date_start' => $dateStart,
                            'date_end' => $dateEnd,
                            'observation' => $request->observation,
                            'sessions_floor' => 1
                        ]);
                    } else if ($groupRoom->type_room == 'Camilla') {
                        Session::create([
                            'id_group' => $request->group_selected['id_group'],
                            'id_client' => $client['id'] ?? null,
                            'date_start' => $dateStart,
                            'date_end' => $dateEnd,
                            'observation' => $request->observation,
                            'sessions_individual' => 1
                        ]);
                    }
                }

                }


                }


                } else {
                    if ($groupRoom->type_room == 'Máquina') {
                        Session::create([
                            'id_group' => $request->group_selected['id_group'],
                            'date_start' => $dateStart,
                            'id_client' => $request->client_selected['id'] ?? null,
                            'date_end' => $dateEnd,
                            'observation' => $request->observation,
                            'sessions_machine' => 0
                        ]);
                    } else if ($groupRoom->type_room == 'Suelo') {
                        Session::create([
                            'id_group' => $request->group_selected['id_group'],
                            'id_client' => $request->client_selected['id'] ?? null,
                            'date_start' => $dateStart,
                            'date_end' => $dateEnd,
                            'observation' => $request->observation,
                            'sessions_floor' => 0
                        ]);
                    } else if ($groupRoom->type_room == 'Camilla') {
                        Session::create([
                            'id_group' => $request->group_selected['id_group'],
                            'id_client' => $request->client_selected['id'] ?? null,
                            'date_start' => $dateStart,
                            'date_end' => $dateEnd,
                            'observation' => $request->observation,
                            'sessions_individual' => 0
                        ]);
                    }

                    foreach ($serieDays as $key => $serieDay) {
                        $dateStart = $serieDay . ' ' . $request->timepicker_start;
                        $dateEnd = $serieDay . ' ' . $request->timepicker_end;
                        $dateStart = DateTime::createFromFormat('Y-m-d g:i A', $dateStart)->format('Y-m-d H:i:s');
                        $dateEnd = DateTime::createFromFormat('Y-m-d g:i A', $dateEnd)->format('Y-m-d H:i:s');


                        if ($groupRoom->type_room == 'Máquina') {
                            Session::create([
                                'id_group' => $request->group_selected['id_group'],
                                'date_start' => $dateStart,
                                'id_client' => $request->client_selected['id'] ?? null,
                                'date_end' => $dateEnd,
                                'observation' => $request->observation,
                                'sessions_machine' => 0
                            ]);
                        } else if ($groupRoom->type_room == 'Suelo') {
                            Session::create([
                                'id_group' => $request->group_selected['id_group'],
                                'id_client' => $request->client_selected['id'] ?? null,
                                'date_start' => $dateStart,
                                'date_end' => $dateEnd,
                                'observation' => $request->observation,
                                'sessions_floor' => 0
                            ]);
                        } else if ($groupRoom->type_room == 'Camilla') {
                            Session::create([
                                'id_group' => $request->group_selected['id_group'],
                                'id_client' => $request->client_selected['id'] ?? null,
                                'date_start' => $dateStart,
                                'date_end' => $dateEnd,
                                'observation' => $request->observation,
                                'sessions_individual' => 0
                            ]);
                        }
                    }
                }

                if (!empty($request->input('employee_selected'))) {
                    Group::where('id',$request->group_selected['id_group'])->update(['id_employee'=>$request->employee_selected]);
                }


                /*auditoria: start*/Pilates::setAudit("Creó un grupo de sessiones que inicia $request->date_start $request->timepicker_start y termina $request->date_start $request->timepicker_end"); /*auditoria: end*/
                return response()->json(['success' => 'El grupo de sesiones se agrego con éxito.', 'error' => false]);
            } else {
                return response()->json(['success' => false, 'error' => $validator->errors()->all()]);
            }
        } else {
            abort(404);
        }
    }

    public function editGroupSessionsDrag(Request $request)
    {

        $dateStart = $request->date_start . ' ' . $request->timepicker_start;
        $dateEnd = $request->date_start . ' ' . $request->timepicker_end;

        $dateStart = DateTime::createFromFormat('Y-m-d g:i A', $dateStart)->format('Y-m-d H:i:s');
        $dateEnd = DateTime::createFromFormat('Y-m-d g:i A', $dateEnd)->format('Y-m-d H:i:s');


        $dateStart_previous = $request->date_start_previous . ' ' . $request->timepicker_start_previous;
        $dateEnd_previous = $request->date_start_previous . ' ' . $request->timepicker_end_previous;
        $dateStart_previous = DateTime::createFromFormat('Y-m-d g:i A', $dateStart_previous)->format('Y-m-d H:i:s');
        $dateEnd_previous = DateTime::createFromFormat('Y-m-d g:i A', $dateEnd_previous)->format('Y-m-d H:i:s');

        $rules = [
            'date_start'   => ['required', 'date_format:"Y-m-d"', new RuleWorkingDays($request->date_start), new RuleNoWorkDayEvent($request->date_start)],
            'timepicker_start' => 'required|date_format:"g:i A"',
            'timepicker_end' => 'required|date_format:"g:i A"|after:timepicker_start',

            'date_start_previous'  => 'required|date_format:"Y-m-d"',
            'timepicker_start_previous' => 'required|date_format:"g:i A"',
            'timepicker_end_previous' => 'required|date_format:"g:i A"|after:timepicker_start_previous'
        ];


        $isSame = true;
        if ($dateStart == $dateStart_previous && $dateEnd == $dateEnd_previous) {
            $rules['id_group'] = 'required';
        } else {
            $isSame = false;
            $rules['id_group'] = [
                'required',
                new RuleEmployeeDuplicateSessionTime2( $request->date_start . ' ' . $request->timepicker_start,$request->date_start . ' ' . $request->timepicker_end,$request->id_group),
                new RuleDuplicateGroupSessions($request->id_group, $request->date_start . ' ' . $request->timepicker_start, $request->date_start . ' ' . $request->timepicker_end),
                new RuleRoomCrash($request->id_group, $request->date_start . ' ' . $request->timepicker_start, $request->date_start . ' ' . $request->timepicker_end),
                new RuleDuplicateSessionClientGroup($request->id_group,$dateStart_previous,$dateEnd_previous,$dateStart,$dateEnd )
            ];
        }




        $messages = [
            'id_group.required' => 'Necesita seleccionar un grupo.',
            'timepicker_end.after' => 'La hora final debe ser mayor que la hora inicial.'
        ];

        $customAttr = [
            'date_start' => 'fecha',
            'timepicker_start' => 'hora de inicio',
            'timepicker_end' => 'hora de término'
        ];


        $validator = Validator::make($request->all(), $rules, $messages, $customAttr);

        if ($validator->passes()) {

            if (!$isSame) {
                $sessions = Session::where('date_start', '>=', $dateStart_previous)
                    ->where('date_end', '<=', $dateEnd_previous)
                    ->where('id_group', $request->id_group)
                    ->get();

                foreach ($sessions as $key => $session) {
                    Session::where('id', $session->id)->update(['date_start' => $dateStart, 'date_end' => $dateEnd]);
                }
            }

            return response()->json(['success' => 'Actualización realizada correctamente.', 'error' => false]);
        } else {
            return response()->json(['success' => false, 'error' => $validator->errors()->all()]);
        }
    }

    public function editGroupSessions(Request $request)
    {

        $dateStart = $request->date_start . ' ' . $request->timepicker_start;
        $dateEnd = $request->date_start . ' ' . $request->timepicker_end;

        $dateStart = DateTime::createFromFormat('Y-m-d g:i A', $dateStart)->format('Y-m-d H:i:s');
        $dateEnd = DateTime::createFromFormat('Y-m-d g:i A', $dateEnd)->format('Y-m-d H:i:s');


        $dateStart_previous = $request->date_start_previous . ' ' . $request->timepicker_start_previous;
        $dateEnd_previous = $request->date_start_previous . ' ' . $request->timepicker_end_previous;
        $dateStart_previous = DateTime::createFromFormat('Y-m-d g:i A', $dateStart_previous)->format('Y-m-d H:i:s');
        $dateEnd_previous = DateTime::createFromFormat('Y-m-d g:i A', $dateEnd_previous)->format('Y-m-d H:i:s');

        $rules = [
            'date_start'   => ['required', 'date_format:"Y-m-d"', new RuleWorkingDays($request->date_start), new RuleNoWorkDayEvent($request->date_start)],
            'timepicker_start' => 'required|date_format:"g:i A"',
            'timepicker_end' => 'required|date_format:"g:i A"|after:timepicker_start',

            'date_start_previous'  => 'required|date_format:"Y-m-d"',
            'timepicker_start_previous' => 'required|date_format:"g:i A"',
            'timepicker_end_previous' => 'required|date_format:"g:i A"|after:timepicker_start_previous'
        ];

        if (!empty($request->input('employee_selected'))) {
            $rules['employee_selected'] = [
                'nullable',
                new RuleEmployeeSetGroup($request->employee_selected, $request->date_start . ' ' . $request->timepicker_start,$request->date_start . ' ' . $request->timepicker_end,true),
                new RuleEmployeeDuplicateSessionTime($request->employee_selected, $request->date_start . ' ' . $request->timepicker_start,$request->date_start . ' ' . $request->timepicker_end,$request->id_group_previous)
            ];
        }


        $isSame = true;
        if ($dateStart == $dateStart_previous && $dateEnd == $dateEnd_previous && $request->id_group == $request->id_group_previous) {
            $rules['id_group'] = 'required';
        } else {
            $isSame = false;
            $rules['id_group'] = [
                'required',
                new RuleDuplicateGroupSessions($request->id_group, $request->date_start . ' ' . $request->timepicker_start, $request->date_start . ' ' . $request->timepicker_end),
                new RuleRoomCrash($request->id_group_previous, $request->date_start . ' ' . $request->timepicker_start, $request->date_start . ' ' . $request->timepicker_end),
                new sameTypeRoomGroup($request->id_group_previous,$request->id_group),
                new RuleSufficientCapacityRoomGroup($request->date_start_previous . ' ' . $request->timepicker_start_previous, $request->date_start_previous . ' ' . $request->timepicker_end_previous,$request->id_group_previous,$request->id_group),
                new RuleDuplicateSessionClientGroup($request->id_group_previous,$dateStart_previous,$dateEnd_previous,$dateStart,$dateEnd )
            ];
        }


        $messages = [
            'id_group.required' => 'Necesita seleccionar un grupo.',
            'timepicker_end.after' => 'La hora final debe ser mayor que la hora inicial.'
        ];

        $customAttr = [
            'date_start' => 'fecha',
            'timepicker_start' => 'hora de inicio',
            'timepicker_end' => 'hora de término'
        ];


        $validator = Validator::make($request->all(), $rules, $messages, $customAttr);

        if ($validator->passes()) {



            if (!$isSame) {
                $sessions = Session::where('date_start', '>=', $dateStart_previous)
                    ->where('date_end', '<=', $dateEnd_previous)
                    ->where('id_group', $request->id_group_previous)
                    ->get();

                foreach ($sessions as $key => $session) {

                    Session::where('id', $session->id)->update(['date_start' => $dateStart, 'date_end' => $dateEnd,'id_group'=>$request->id_group]);
                }
            }


            if (!empty($request->input('employee_selected'))) {
                Group::where('id',$request->id_group)->update(['id_employee'=>$request->employee_selected]);
            }



            /*auditoria: start*/Pilates::setAudit("Actualizó el grupo de sessiones que iniciaba $dateStart_previous y terminaba $dateEnd_previous a $dateStart y $dateEnd"); /*auditoria: end*/
            return response()->json(['success' => 'Actualización realizada correctamente.', 'error' => false]);
        } else {
            return response()->json(['success' => false, 'error' => $validator->errors()->all()]);
        }
    }

    public function setEmployeeGroup(Request $request){

        $rules = [
            'id_employee' => 'required',
            'date_start' => 'required',
            'date_end' => 'required',
            'id_group' => 'required'
        ];

        if (
            !empty($request->input('id_employee'))
         && !empty($request->input('date_start'))
         && !empty($request->input('date_end'))
         && !empty($request->input('id_group'))
         ) {
            $rules['id_employee'] = [
                'nullable',
                new RuleEmployeeSetGroup($request->id_employee,Carbon::createFromFormat('Y-m-d H:i:s',$request->date_start)->format('Y-m-d g:i A'),Carbon::createFromFormat('Y-m-d H:i:s',$request->date_end)->format('Y-m-d g:i A'),true),
                new RuleEmployeeDuplicateSessionTimeDrag($request->id_employee, Carbon::createFromFormat('Y-m-d H:i:s',$request->date_start)->format('Y-m-d g:i A'),Carbon::createFromFormat('Y-m-d H:i:s',$request->date_end)->format('Y-m-d g:i A'))
            ];
        }

        $messages = [

        ];

        $customAttr = [

        ];


        $validator = Validator::make($request->all(), $rules, $messages, $customAttr);

        if ($validator->passes()) {
        Group::where('id',$request->id_group)->update(['id_employee'=>$request->id_employee]);

        /*auditoria: start*/Pilates::setAudit("Agregó el empleado con id $request->id_employee al grupo $request->id_group"); /*auditoria: end*/
        return response()->json(['response' => 'Actualización realizada correctamente.', 'status' => true]);
        } else {
            return response()->json(['status' => false, 'response' => $validator->errors()->all()]);
        }
    }


    public function moveSessions(Request $request){
        $cantSessions=(!empty($request->input('sessions_selected')))?count($request->input('sessions_selected')):0;
        $dateStart = $request->date_start . ' ' . $request->timepicker_start;
        $dateEnd = $request->date_start . ' ' . $request->timepicker_end;
        $rules = [

            'id_group'  => [
                'required',
                'required_with:sessions_selected',
                 new RuleEmptyGroup($request->id_group, $request->date_start . ' ' . $request->timepicker_start, $request->date_start . ' ' . $request->timepicker_end,$cantSessions),

            ],
            'sessions_selected' => [
                'required',
                new RuleSessionSameType($request->id_group,$request->input('sessions_selected')[0])
            ]
        ];

        if (!empty($request->input('sessions_selected'))) {
            $sessionsSelected =  $request->input('sessions_selected');
            foreach ($sessionsSelected as $key => $sessionSelected) {
                $rules["sessions_selected.$key"] = [
                    new RuleDuplicateSessionSameGroup($request->id_group,$dateStart,$dateEnd,$sessionSelected )
                ];
            }
        }
        $messages = [
            'sessions_selected.required' => 'Ninguna sesión para mover.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($request->ajax()) {

            if ($validator->passes()) {



            $dateStart = DateTime::createFromFormat('Y-m-d g:i A', $dateStart)->format('Y-m-d H:i:s');
            $dateEnd = DateTime::createFromFormat('Y-m-d g:i A', $dateEnd)->format('Y-m-d H:i:s');

            $sessions =  $request->input('sessions_selected');

            if(count($sessions)>0){
                $tmpSession=  Session::where('id',$sessions[0])->first();
                Session::create([
                    'id_group' => $tmpSession->id_group,
                    'id_client' => null,
                    'date_start' => $tmpSession->date_start,
                    'date_end' => $tmpSession->date_end,
                    'observation' => $tmpSession->observation
                ]);
               }

            foreach ($sessions as $key => $session) {
            Session::where('id',$session)->update(['date_start'=>$dateStart,'date_end'=>$dateEnd,'id_group'=>$request->id_group]);
            }
            $countSession=count($sessions);
             /*auditoria: start*/Pilates::setAudit("Movió $countSession sesion(s)"); /*auditoria: end*/
                return response()->json(['response' => ($countSession>1)?"$countSession sesiones movidas con éxito.":"$countSession sesión movida con éxito.", 'status' => true]);
            } else {
                return response()->json(['response' => $validator->errors()->all(), 'status' => false]);
            }
        } else {
            abort(404);
        }


    }

    public function moveSessions2(Request $request){
        $cantSessions=(!empty($request->input('sessions_selected')))?count($request->input('sessions_selected')):0;
        $dateStart = DateTime::createFromFormat('Y-m-d H:i:s', $request->date_start)->format('Y-m-d g:i A');
        $dateEnd =DateTime::createFromFormat('Y-m-d H:i:s', $request->date_end)->format('Y-m-d g:i A');
        $rules = [

            'id_group'  => [
                'required',
                'required_with:sessions_selected',
                 new RuleEmptyGroup($request->id_group, $dateStart, $dateEnd,$cantSessions),

            ],
            'sessions_selected' => [
                'required',
                new RuleSessionSameType($request->id_group,$request->input('sessions_selected')[0])
            ]
        ];

        if (!empty($request->input('sessions_selected'))) {
            $sessionsSelected =  $request->input('sessions_selected');
            foreach ($sessionsSelected as $key => $sessionSelected) {
                $rules["sessions_selected.$key"] = [
                    new RuleDuplicateSessionSameGroup($request->id_group,$dateStart,$dateEnd,$sessionSelected )
                ];
            }
        }
        $messages = [
            'sessions_selected.required' => 'Ninguna sesión para mover.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($request->ajax()) {

            if ($validator->passes()) {



            $dateStart = DateTime::createFromFormat('Y-m-d g:i A', $dateStart)->format('Y-m-d H:i:s');
            $dateEnd = DateTime::createFromFormat('Y-m-d g:i A', $dateEnd)->format('Y-m-d H:i:s');

            $sessions =  $request->input('sessions_selected');

            if(count($sessions)>0){
                $tmpSession=  Session::where('id',$sessions[0])->first();
                Session::create([
                    'id_group' => $tmpSession->id_group,
                    'id_client' => null,
                    'date_start' => $tmpSession->date_start,
                    'date_end' => $tmpSession->date_end,
                    'observation' => $tmpSession->observation
                ]);
               }

            foreach ($sessions as $key => $session) {
            Session::where('id',$session)->update(['date_start'=>$dateStart,'date_end'=>$dateEnd,'id_group'=>$request->id_group]);
            }
            $countSession=count($sessions);
             /*auditoria: start*/Pilates::setAudit("Movió sesiones ids: ".implode(', ', $sessions)); /*auditoria: end*/
                return response()->json(['response' => ($countSession>1)?"$countSession sesiones movidas con éxito.":"$countSession sesión movida con éxito.", 'status' => true]);
            } else {
                return response()->json(['response' => $validator->errors()->all(), 'status' => false]);
            }
        } else {
            abort(404);
        }


    }



    public function storeNewSession(Request $request)
    {
        $cantClients=(!empty($request->input('clients_selected')))?count($request->input('clients_selected')):0;

        $rules = [

            'group_selected'  => [
                'required',
                'required_with:clients_selected',
                 new RuleEmptyGroup($request->group_selected['id_group'], $request->date_start . ' ' . $request->timepicker_start, $request->date_start . ' ' . $request->timepicker_end,$cantClients)
            ],
            'date_start'   => ['required', 'date_format:"Y-m-d"', new RuleWorkingDays($request->date_start), new RuleNoWorkDayEvent($request->date_start)],
            'timepicker_start' => 'required|date_format:"g:i A"',
            'timepicker_end' => 'required|date_format:"g:i A"|after:timepicker_start',
            'observation' => 'nullable|max:250',
            'clients_selected' => 'required'
        ];

        if (!empty($request->input('group_selected'))) {
            if (!empty($request->input('clients_selected'))) {
                $clients =  $request->input('clients_selected');
                foreach ($clients as $key => $client) {
                    $rules["clients_selected.$key.id"] = [
                       /* new RuleSufficientBalance(
                            $request->group_selected['id_group'],
                            $client['id'],
                            "$client[name] $client[last_name]"
                        ),*/
                        new RuleDuplicatesSessionClient($request->group_selected['id_group'], $request->date_start . ' ' . $request->timepicker_start, $request->date_start . ' ' . $request->timepicker_end,  $client['id'], "$client[name] $client[last_name]")
                    ];
                }
            }
        }

        $messages = [
            'group_selected.required' => 'Necesita seleccionar un grupo.',
            'clients_selected.required' => 'Necesita agregar al menos a un cliente.',
            'timepicker_end.after' => 'La hora final debe ser mayor que la hora inicial.',
            'group_selected.required_with' => 'Es necesario que elija un grupo.'
        ];

        $customAttr = [
            'group_selected' => 'grupo',
            'date_start' => 'fecha',
            'timepicker_start' => 'hora de inicio',
            'timepicker_end' => 'hora de término',
            'observation' => 'observaciones'
        ];



        $validator = Validator::make($request->all(), $rules, $messages, $customAttr);

        if ($request->ajax()) {

            if ($validator->passes()) {
                $idEmployee = auth()->user()->id;

                $groupRoom = Group::where("group.id", $request->group_selected['id_group'])->join('room', 'group.id_room', '=', 'room.id')->first();

                $dateStart = $request->date_start . ' ' . $request->timepicker_start;
                $dateEnd = $request->date_start . ' ' . $request->timepicker_end;


                $dateStart = DateTime::createFromFormat('Y-m-d g:i A', $dateStart)->format('Y-m-d H:i:s');
                $dateEnd = DateTime::createFromFormat('Y-m-d g:i A', $dateEnd)->format('Y-m-d H:i:s');

                $clients =  $request->input('clients_selected');

                foreach ($clients as $key => $client) {

                $balanceCheck = Pilates::checkBalance($request->group_selected['id_group'], $client['id']);

                if ($groupRoom->type_room == 'Máquina') {
                    Session::create([
                        'id_group' => $request->group_selected['id_group'],
                        'id_client' => $client['id'] ?? null,
                        'date_start' => $dateStart,
                        'date_end' => $dateEnd,
                        'observation' => $request->observation,
                        'sessions_machine' => (($balanceCheck['success'] == true && $balanceCheck['error'] == false) || $client['negative_balance']=='true') ? 1 : 0,
                        'sessions_floor' => (($balanceCheck['success'] == true && $balanceCheck['error'] != false) && $client['negative_balance']=='false') ? 2 : 0,
                    ]);
                } else if ($groupRoom->type_room == 'Suelo') {
                    Session::create([
                        'id_group' => $request->group_selected['id_group'],
                        'id_client' => $client['id'] ?? null,
                        'date_start' => $dateStart,
                        'date_end' => $dateEnd,
                        'observation' => $request->observation,
                        'sessions_floor' => 1
                    ]);
                } else if ($groupRoom->type_room == 'Camilla') {
                    Session::create([
                        'id_group' => $request->group_selected['id_group'],
                        'id_client' => $client['id'] ?? null,
                        'date_start' => $dateStart,
                        'date_end' => $dateEnd,
                        'observation' => $request->observation,
                        'sessions_individual' => 1
                    ]);
                }
            }
            $countClients=count($clients);
            $groupTmpAudit=$request->group_selected['id_group'];
             /*auditoria: start*/Pilates::setAudit("Agregó $countClients sesion(s) de cliente al horario de inicio:$dateStart y termino:$dateEnd en el grupo con id  $groupTmpAudit"); /*auditoria: end*/
                return response()->json(['success' => ($countClients>1)?"$countClients sesiones de cliente agregadas con éxito.":"$countClients sesión de cliente agregada con éxito.", 'error' => false]);
            } else {
                return response()->json(['success' => false, 'error' => $validator->errors()->all()]);
            }
        } else {
            abort(404);
        }
    }

    public function getDataByRange(Request $request)
    {


    $groupsExists=[];
    $roomsExists=[];
    $template_json=Template::where('status','true')->first();
    if (isset($template_json)) {
      $defaultTimes = json_decode($template_json->default_time);
    }else {
      $defaultTimes= json_decode('[
          {"start":"08:05:00","end":"09:00:00","start_formated":"8:05 AM","end_formated":"9:00 AM"},
          {"start":"09:05:00","end":"10:00:00","start_formated":"9:05 AM","end_formated":"10:00 AM"},
          {"start":"10:05:00","end":"11:00:00","start_formated":"10:05 AM","end_formated":"11:00 AM"},
          {"start":"11:05:00","end":"12:00:00","start_formated":"11:05 AM","end_formated":"12:00 PM"},
          {"start":"12:05:00","end":"13:00:00","start_formated":"12:05 PM","end_formated":"1:00 PM"},
          {"start":"13:05:00","end":"14:00:00","start_formated":"1:05 PM","end_formated":"2:00 PM"},
          {"start":"14:05:00","end":"15:00:00","start_formated":"2:05 PM","end_formated":"3:00 PM"},
          {"start":"15:00:00","end":"15:55:00","start_formated":"3:00 PM","end_formated":"3:55 PM"},
          {"start":"15:55:00","end":"16:50:00","start_formated":"3:55 PM","end_formated":"4:50 PM"},
          {"start":"16:50:00","end":"17:45:00","start_formated":"4:50 PM","end_formated":"5:45 PM"},
          {"start":"17:45:00","end":"18:40:00","start_formated":"5:45 PM","end_formated":"6:40 PM"},
          {"start":"18:40:00","end":"19:35:00","start_formated":"6:40 PM","end_formated":"7:35 PM"},
          {"start":"20:05:00","end":"21:00:00","start_formated":"8:05 PM","end_formated":"9:00 PM"},
          {"start":"21:05:00","end":"22:00:00","start_formated":"9:05 PM","end_formated":"10:00 PM"}
      ]');
    }
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $tabTypeData = $request->tab_type_data;

       $locks=LockAdd::where('date_start', '>=', $start_date)->where('date_end', '<=', $end_date)->get();

        $sessions = [];

        $sessions = Session::
             where('date_start', '>=', $start_date)
            ->where('date_end', '<=', $end_date)
            ->groupBy('date_start','date_end','id_group')
            ->orderBy('id_group', 'asc')
            ->get(['id','id_group','id_client','date_start','date_end','sessions_machine','sessions_floor','sessions_individual','status']);




        $groupsSession = [];
        $array_hours_exist = [];

        foreach ($sessions as $key =>   $session) {
                   $groupTmp1=[];

                    $employeeGroupData=[];
                    $flagExist=false;
                    foreach ($groupsExists as $key => $group) {
                        if($session->id_group==$group['id_group']){
                            $flagExist=true;
                            $employeeGroupData=$group;
                        }
                    }

                    if(!$flagExist){
                        $employeeGroupData = Group::where('group.id', $session->id_group)
                        ->leftJoin('employee', 'employee.id', '=', 'group.id_employee')
                        ->get([
                            'group.id as id_group',
                            'group.name as name_group',
                            'group.level as level_group',
                            'group.id_room as group_room',
                            'group.observation as observations_group',
                            'employee.id  as id_employee',
                            'employee.name as name_employee',
                            'employee.last_name as last_name_employee'
                        ])->first();
                        array_push($groupsExists, $employeeGroupData);
                    }

                        // ROOM GROUP
                $flagExistRoom=false;
                $roomTmp=[];
                foreach ($roomsExists as $key => $room) {
                    if($room->id==$employeeGroupData['group_room']){
                        $flagExistRoom=true;
                        $roomTmp=$room;
                    }
                }
                if(!$flagExistRoom){
                    $roomTmp = Room::where('id', $employeeGroupData['group_room'])->first();
                    array_push($roomsExists, $roomTmp);
                }

                $flagAdd=false;
                if(($roomTmp->type_room=='Máquina' || $roomTmp->type_room=='Suelo') && $tabTypeData=='pilates'){
                    $flagAdd=true;
                }else if($roomTmp->type_room == 'Camilla' && $tabTypeData=='physiotherapy'){
                    $flagAdd=true;
                }else if($tabTypeData=='all'){
                    $flagAdd=true;
                }

                if($flagAdd){

                    $groupTmp1['id']= $employeeGroupData['id_group'];
                    $groupTmp1['id_num']= $employeeGroupData['id_group'];
                    $groupTmp1['id_group']= $employeeGroupData['id_group'];
                    $groupTmp1['name']= $employeeGroupData['name_group'];
                    $groupTmp1['level']= $employeeGroupData['level_group'];

                    //employee
                    $groupTmp1['name_employee']= $employeeGroupData['name_employee'];
                    $groupTmp1['last_name_employee']= $employeeGroupData['last_name_employee'];
                    //end employee
                    $groupTmp1['observation']=$employeeGroupData['observations_group'];
                    $groupTmp1['actions']= $employeeGroupData['id_group'];
                    //room
                    $groupTmp1['room_id']= $roomTmp->id;
                    $groupTmp1['room_name']= $roomTmp->name;
                    $groupTmp1['type_room']= $roomTmp->type_room;
                    //sessions
                    $groupTmp1['date_start']=  $session->date_start;
                    $groupTmp1['date_end']=  $session->date_end;

                    $status = Pilates::getRealStatusGroupByNumFormatCalendar($employeeGroupData['id_group'],$roomTmp,$session->date_start, $session->date_end);
                    $groupTmp1['status'] = $status['num'];
                    $groupTmp1['status_format'] = $status['format'];


                array_push($groupsSession, [
                    'group' => $groupTmp1,
                    'room' => $roomTmp,
                    'sessions' => [$session],
                    'status' => $session->status,
                    'time_start' => $session->date_start,
                    'time_end' => $session->date_end,
                    'time_end_for_short' => DateTime::createFromFormat('Y-m-d H:i:s', $session->date_end)->format('H:i:s'),
                    'date_start' => DateTime::createFromFormat('Y-m-d H:i:s', $session->date_start)->format('Y-m-d'),
                    'timepicker_start' => DateTime::createFromFormat('Y-m-d H:i:s', $session->date_start)->format('g:i A'),
                    'timepicker_end' => DateTime::createFromFormat('Y-m-d H:i:s',$session->date_end )->format('g:i A'),
                    'is_default' =>false,
                    'locks'=>$locks,
                    'status_employee'=> ($employeeGroupData['id_employee']!=null)? Pilates::getStatusEmployeeGroupBySessionGroup($employeeGroupData['id_employee'],$session->date_start,$session->date_end,false):false
                ]);
                array_push($array_hours_exist, [
                  'timepicker_start' => DateTime::createFromFormat('Y-m-d H:i:s', $session->date_start)->format('h:i A'),
                  'timepicker_end' => DateTime::createFromFormat('Y-m-d H:i:s',$session->date_end )->format('h:i A')
                ]);
                }
        }

        $a = [];
        foreach ($defaultTimes as $key => $defaultTime) {
            $flagExist=false;
            $flagHourExist = false;

            $startDefault=$defaultTime->start;
            $endDefault=$defaultTime->end;
            foreach ($groupsSession as $key => $groupSession) {
            if($defaultTime->start_formated==$groupSession['timepicker_start'] && $defaultTime->end_formated==$groupSession['timepicker_end'])$flagExist=true;
            }
            foreach ($array_hours_exist as $key_ahe => $value_ahe) {
              // $a[] = [$defaultTime->start_formated,$value_ahe['timepicker_start'],$defaultTime->end_formated,$value_ahe['timepicker_end']];
              if ($defaultTime->start_formated == $value_ahe['timepicker_start']
              && $defaultTime->end_formated == $value_ahe['timepicker_end']){
                  $flagHourExist=true;
              //   $a [] = [$value_ahe];
              }
            }
            if (!$flagHourExist){
              if(!$flagExist)array_push($groupsSession, [
                'time_start' => "2015-01-14 $startDefault",
                'time_end' => "2015-01-14 $endDefault",
                'time_end_for_short' => $endDefault,
                'date_start' => "2015-01-14",
                'timepicker_start' =>$defaultTime->start_formated,
                'timepicker_end' => $defaultTime->end_formated,
                'locks'=>$locks,
                'is_default' =>true
              ]);
            }
        }

        $groupsSession = collect($groupsSession)->sortBy('time_end_for_short', SORT_NATURAL | SORT_FLAG_CASE, false)->values()->all();

        return response()->json($groupsSession);
    }

    public function analizeMap($group)
    {

        $status = Pilates::getRealStatusGroupByNumFormat($group->id_group, $group->date_start, $group->date_end, 'Y-m-d H:i:s');
        $group['status'] = $status['num'];
        $group['status_format'] = $status['format'];

        return $group;
    }

    function deleteSession(Request $request)
    {
        $errors = 0;
        $cantSuccsess = 0;
        $idsSession = $request['id'];
        foreach ($idsSession as $key => $id) {
            if (Session::where('id', $id)->delete()) {
                $cantSuccsess++;
            } else {
                $errors++;
            }
        }

        $response = ['status' => true, 'response' => ($cantSuccsess <= 1) ?
            $cantSuccsess . ' sesión eliminada con éxito' :
            $cantSuccsess . ' sesiones eliminadas con éxito'];

        /*auditoria: start*/Pilates::setAudit("Baja sesión ids: ".implode(', ', $idsSession)); /*auditoria: end*/
        return response()->json($response);
    }

    function deleteGroupSessions(Request $request)
    {

        $dateStart = $request->date_start . ' ' . $request->timepicker_start;
        $dateEnd = $request->date_start . ' ' . $request->timepicker_end;

        $dateStart = DateTime::createFromFormat('Y-m-d g:i A', $dateStart)->format('Y-m-d H:i:s');
        $dateEnd = DateTime::createFromFormat('Y-m-d g:i A', $dateEnd)->format('Y-m-d H:i:s');

        $status = false;
        if (Session::where('id_group', $request->id_group)
            ->where('date_start', '=', $dateStart)
            ->where('date_end', '=', $dateEnd)
            ->delete()
        ) {
            $status = true;
        }

        /*auditoria: start*/Pilates::setAudit("Eliminó un grupo de sesiones con horario de  inicio:$dateStart y termino:$dateEnd y grupo con id $request->id_group"); /*auditoria: end*/
        $response = ['status' => $status, 'response' => ($status) ? 'Grupo de sesiones eliminado con éxito' : 'El grupo no pudo ser eliminado con éxito, intente de nuevo.'];

        return response()->json($response);
    }

    function lockGroupSessionAdd(Request $request){

        $dateStart = $request->date_start . ' ' . $request->timepicker_start;
        $dateEnd = $request->date_start . ' ' . $request->timepicker_end;

        $dateStart = DateTime::createFromFormat('Y-m-d g:i A', $dateStart)->format('Y-m-d H:i:s');
        $dateEnd = DateTime::createFromFormat('Y-m-d g:i A', $dateEnd)->format('Y-m-d H:i:s');

        if($request->status=="true"){
            LockAdd::create(['date_start'=>$dateStart,'date_end'=>$dateEnd]);
             /*auditoria: start*/Pilates::setAudit("Bloqueo de sesiones para el horario de inicio:$dateStart y termino:$dateEnd "); /*auditoria: end*/
            return response()->json(['response' => "Bloqueada correctamente.", 'status' => true]);
        }else {
            LockAdd::where('date_start',$dateStart)->where('date_end',$dateEnd)->delete();
              /*auditoria: start*/Pilates::setAudit("Desbloqueo de sesiones para el horario de inicio:$dateStart y termino:$dateEnd "); /*auditoria: end*/
            return response()->json(['response' => "Desbloqueada correctamente.", 'status' => true]);
        }


    }

    public function dataTableEmployeeSelected(Request $request)
    {
        $employee = new Employee();
        $employees = $employee->getEmployeeSelectedDataTableDashboard($request);
        return response()->json($employees);
    }

    public function dataTableGroupSessions(Request $request)
    {
        $session = new Session();
        $sessions = $session->getGroupsSessions($request);
        return response()->json($sessions);

    }


    public function dataTableGroupSessions2(Request $request)
    {
        $session = new Session();
        $sessions = $session->getGroupsSessions2($request);
        return response()->json($sessions);

    }

    public function refreshCsrf(Request $request)
    {
        return csrf_token();
    }

    public function  loadTemplate(Request $request)
    {
        $enableStartToday=false;


        $rules = [
            "start_date"=>[new RuleTemplateActive()]
        ];
        $messages=[
        ];
        $customAttr=[
        ];
        $validator = Validator::make($request->all(), $rules, $messages, $customAttr);

        if ($request->ajax()) {

            if ($validator->passes()) {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
                $mode_static = $request->mode_static;
                $month_selected=$request->month;

            $template=Template::where('status','true')->first();
            $sessionsTemplate= SessionTemplate::where('id_template',$template->id)->get();


            $dateDay = Carbon::createFromFormat('Y-m-d H:i:s', $start_date);

            $year = $dateDay->year;
            $month = $month_selected;
            $days = $dateDay->daysInMonth;

            $dateDayTmp = Carbon::createFromFormat('Y-m-d', "$year-$month-01");

            $year = $dateDayTmp->year;
            $month = $month_selected;
            $days = $dateDayTmp->daysInMonth;

            $nowDate=Carbon::now();

            $sessionsDelete=Session::get();
            $sessionsToDelete=[];

            if($mode_static=='true' || $mode_static==true ){

             foreach($sessionsDelete as $session){
                $dateSession = Carbon::createFromFormat('Y-m-d H:i:s', $session->date_start);
                if($dateSession->clone()->month == $month && $dateSession->clone()->year == $year)
                $sessionsToDelete[]= $session->id;
             }

            }else{
                foreach($sessionsDelete as $session){
                    $sessionsToDelete[]= $session->id;
                 }
            }

            if(count($sessionsToDelete)>0)
            Session::whereIn('id',$sessionsToDelete)->delete();

            $sessionsToCreate=[];

            foreach (range(1, $days) as $day) {
                $date = Carbon::createFromFormat('Y-m-d', "$year-$month-$day");
                $dayNoWork=NoWorkDay::where('date', $date->clone()->format('Y-m-d'))->exists();

                $extraRule=true;
                if($enableStartToday)
                $extraRule=(($date > $nowDate));


                foreach ($sessionsTemplate as $session) {

                if (!$dayNoWork  && $date->clone()->isMonday() === true && $session->day=="monday" && $extraRule)
                $sessionsToCreate[]=[
                    'id_group' => $session->id_group,
                    'id_client' => $session['id_client'] ?? null,
                    'date_start' => $date->clone()->format('Y-m-d')." ".$session->start,
                    'date_end' => $date->clone()->format('Y-m-d')." ".$session->end,
                    'observation' => $session->observation,
                    'sessions_machine' => $session->sessions_machine,
                    'sessions_floor' =>$session->sessions_floor,
                    'sessions_individual' =>$session->sessions_individual,
                ];

                if (!$dayNoWork  && $date->clone()->isTuesday() === true && $session->day=="tuesday" && $extraRule)
                $sessionsToCreate[]=[
                    'id_group' => $session->id_group,
                    'id_client' => $session['id_client'] ?? null,
                    'date_start' => $date->clone()->format('Y-m-d')." ".$session->start,
                    'date_end' => $date->clone()->format('Y-m-d')." ".$session->end,
                    'observation' => $session->observation,
                    'sessions_machine' => $session->sessions_machine,
                    'sessions_floor' =>$session->sessions_floor,
                    'sessions_individual' =>$session->sessions_individual,
                ];

                if (!$dayNoWork  && $date->clone()->isWednesday() === true && $session->day=="wednesday" && $extraRule)
                $sessionsToCreate[]=[
                    'id_group' => $session->id_group,
                    'id_client' => $session['id_client'] ?? null,
                    'date_start' => $date->clone()->format('Y-m-d')." ".$session->start,
                    'date_end' => $date->clone()->format('Y-m-d')." ".$session->end,
                    'observation' => $session->observation,
                    'sessions_machine' => $session->sessions_machine,
                    'sessions_floor' =>$session->sessions_floor,
                    'sessions_individual' =>$session->sessions_individual,
                ];



                if (!$dayNoWork  && $date->clone()->isThursday() === true && $session->day=="thursday" && $extraRule)
                $sessionsToCreate[]=[
                    'id_group' => $session->id_group,
                    'id_client' => $session['id_client'] ?? null,
                    'date_start' => $date->clone()->format('Y-m-d')." ".$session->start,
                    'date_end' => $date->clone()->format('Y-m-d')." ".$session->end,
                    'observation' => $session->observation,
                    'sessions_machine' => $session->sessions_machine,
                    'sessions_floor' =>$session->sessions_floor,
                    'sessions_individual' =>$session->sessions_individual,
                ];

                if (!$dayNoWork  && $date->clone()->isFriday() === true && $session->day=="friday" && $extraRule)
                $sessionsToCreate[]=[
                    'id_group' => $session->id_group,
                    'id_client' => $session['id_client'] ?? null,
                    'date_start' => $date->clone()->format('Y-m-d')." ".$session->start,
                    'date_end' => $date->clone()->format('Y-m-d')." ".$session->end,
                    'observation' => $session->observation,
                    'sessions_machine' => $session->sessions_machine,
                    'sessions_floor' =>$session->sessions_floor,
                    'sessions_individual' =>$session->sessions_individual,
                ];

                }
            }
            if(count($sessionsToCreate)>0)
            Session::insert($sessionsToCreate);


            /*auditoria: start*/Pilates::setAudit("Cargo plantilla  id: $template->id"); /*auditoria: end*/
            return response()->json(['response' => "Plantilla cargada correctamente.", 'status' => true]);
            }else{
                return response()->json(['response' => $validator->errors()->all(), 'status' => false]);
            }

        }else {
            abort(404);
        }

    }

    public function loadTemplateCheck(Request $request)
    {
        $month_selected=$request->month;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $mode_static = $request->mode_static;

        $dateDay = Carbon::createFromFormat('Y-m-d H:i:s', $start_date);
        $year = $dateDay->year;
        $month =  $month_selected;

        $countSessions=0;
        if($mode_static=='true')
        $countSessions=Session::whereMonth('date_start', $month)->whereYear('date_start',  $year)->get()->count();
        if($mode_static=='false')
        $countSessions=Session::get()->count();

        if($countSessions<=0)
        return response()->json(['status' => true]);
        return response()->json(['status' => false]);

    }



    public function  printItinerary(Request $request)
    {

        $rules = [
            "date"=>['date_format:d/m/Y',new RuleExistEmployeeInGroup($request->date)]
        ];
        $messages=[
        ];
        $customAttr=[
        ];
        $validator = Validator::make($request->all(), $rules, $messages, $customAttr);

        if ($request->ajax()) {

            if ($validator->passes()) {

                $dateF=Carbon::createFromFormat('d/m/Y',$request->date)->format('Y-m-d');



               $itineraries=[];
               $groupsItineraries=[];

               $employees=Employee::get();


               foreach ($employees as $key => $employee) {
            $sessionsEmployee = Session::
                Join('group', 'session.id_group', '=', 'group.id')
             ->Join('employee', 'group.id_employee', '=', 'employee.id')
             ->where('date_start', '>=', "$dateF 00:00:00")
             ->where('date_end', '<=', "$dateF 23:59:59")
             ->where('employee.id',  $employee->id)
             ->groupBy('date_start','date_end','id_group')
             ->get(['employee.name as employee_name','employee.last_name as employee_last_name','group.name as group_name','date_start','date_end','group.id_room','group.id as group_id']);


               $itineraryEmployee=[];
               $itemItinerary=[];
               $itemsEmployee=[];

                    foreach ($sessionsEmployee as $key => $sessionEmployee) {
                        $itineraryEmployee['employee_name']="$sessionEmployee->employee_name $sessionEmployee->employee_last_name";

                        $itemItinerary['start']=Carbon::createFromFormat('Y-m-d H:i:s',$sessionEmployee->date_start)->format('H:i');
                        $itemItinerary['end']=Carbon::createFromFormat('Y-m-d H:i:s',$sessionEmployee->date_end)->format('H:i');
                        $itemItinerary['group_name']=$sessionEmployee->group_name;
                        $itemItinerary['room_name']=Room::where('id',$sessionEmployee->id_room)->get(['name'])->first()->name;
                        $itemItinerary['clients']=Session::
                        Join('client', 'session.id_client', '=', 'client.id')
                      ->where('id_group', $sessionEmployee->group_id)
                      ->where('date_start', '>=', $sessionEmployee->date_start)
                      ->where('date_end', '<=', $sessionEmployee->date_end)
                      ->whereNotNull('id_client')
                      ->get(['client.name','client.last_name'])->toArray();
                     array_push($itemsEmployee, $itemItinerary);
                    }

                    if(count($sessionsEmployee)>0){
                        $itineraryEmployee['date']=$request->date;
                        $itineraryEmployee['items_employee']=$itemsEmployee;
                        array_push($itineraries, $itineraryEmployee);
                    }


               }



           $count=0;

            do {

                $groupItineraries=[];
                if(isset($itineraries[$count])){array_push($groupItineraries,$itineraries[$count]);unset($itineraries[$count]);$count++;}
                if(isset($itineraries[$count])){array_push($groupItineraries,$itineraries[$count]);unset($itineraries[$count]);$count++;}
                if(isset($itineraries[$count])){array_push($groupItineraries,$itineraries[$count]);unset($itineraries[$count]);$count++;}

                if(count($groupItineraries)>0)
                array_push($groupsItineraries,$groupItineraries);

            } while(count($itineraries)>0);



            $pdf = PDF::loadView('itinerary1',compact('groupsItineraries'))->setPaper('A4', 'portrait');

            $content = $pdf->download()->getOriginalContent();
                //return $pdf->stream("itinerario.pdf");
            $namePdf="itinerario.pdf";
            Storage::disk('public')->put($namePdf, $content);


            return response()->json(['response' => asset("storage/$namePdf"), 'status' => true]);
            }else{
            return response()->json(['response' => $validator->errors()->all(), 'status' => false]);
            }

        }else {
            abort(404);
        }
    }



}
