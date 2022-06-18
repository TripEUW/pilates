<?php

namespace App\Http\Controllers;

use App\Helpers\Pilates;
use App\Models\Group;
use App\Models\Rol;
use App\Models\Room;
use App\Models\SessionTemplate;
use App\Models\Template;
use App\Rules\RuleDuplicateGroupSessionsTemplate;
use App\Rules\RuleDuplicateSessionClientGroupTemplate;
use App\Rules\RuleDuplicateSessionSameGroupTemplate;
use App\Rules\RuleEmptyGroupTemplate;
use App\Rules\RuleRoomCrashTemplate;
use App\Rules\RuleWorkingDays;
use App\Rules\RuleDuplicatesSessionClientTemplate;
use App\Rules\RuleEmployeeDuplicateSessionTime2;
use App\Rules\RuleSessionSameTypeTemplate;
use App\Rules\RuleSufficientCapacityRoomGroup;
use App\Rules\RuleSufficientCapacityRoomGroupTemplate;
use App\Rules\sameTypeRoomGroup;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TemplateController extends Controller
{

    public function index()
    {
        $roles = Rol::where('id',2)->orderBy('id')->pluck('name', 'id')->toArray();

        $idDefault=null;
        $templateActive= Template::where('status',"true");
        if($templateActive->exists()){
        $idDefault=$templateActive->first()->id;
        }else{
        $templateFresh=Template::orderBy('id', 'desc');
        if($templateFresh->count()>0){
        $idDefault=$templateFresh->first()->id;
        }else{
        $idDefault=null;
        }
        }

        $templates=Template::orderBy('id', 'desc')->get();

        $defaultTimes=[
            ['start'=>'08:05:00','end'=>'09:00:00'],
            ['start'=>'09:05:00','end'=>'10:00:00'],
            ['start'=>'10:05:00','end'=>'11:00:00'],
            ['start'=>'11:05:00','end'=>'12:00:00'],
            ['start'=>'12:05:00','end'=>'13:00:00'],
            ['start'=>'13:05:00','end'=>'14:00:00'],
            ['start'=>'14:05:00','end'=>'15:00:00'],
            ['start'=>'15:05:00','end'=>'16:00:00'],
            ['start'=>'16:05:00','end'=>'17:00:00'],
            ['start'=>'17:05:00','end'=>'18:00:00'],
            ['start'=>'18:05:00','end'=>'19:00:00'],
            ['start'=>'19:05:00','end'=>'20:00:00'],
            ['start'=>'20:05:00','end'=>'21:00:00'],
            ['start'=>'21:05:00','end'=>'22:00:00']
        ];

        return view('template', compact('roles','idDefault','templates','defaultTimes'));

    }

    public function getTemplateList(){
        $templates=Template::orderBy('id', 'desc')->get();
        return response()->json(['response' => '', 'status' => true, 'data' => $templates]);
    }


    public function getData(Request $request)
    {

      $json = Template::where('id',$request->template_selected)->first();
      if ($json->default_time == '' || $json->default_time == null) {
        $defaultTimes=[
            ['start'=>'08:05:00','end'=>'09:00:00','start_formated'=>'8:05 AM','end_formated'=>'9:00 AM'],
            ['start'=>'09:05:00','end'=>'10:00:00','start_formated'=>'9:05 AM','end_formated'=>'10:00 AM'],
            ['start'=>'10:05:00','end'=>'11:00:00','start_formated'=>'10:05 AM','end_formated'=>'11:00 AM'],
            ['start'=>'11:05:00','end'=>'12:00:00','start_formated'=>'11:05 AM','end_formated'=>'12:00 PM'],
            ['start'=>'12:05:00','end'=>'13:00:00','start_formated'=>'12:05 PM','end_formated'=>'1:00 PM'],
            ['start'=>'13:05:00','end'=>'14:00:00','start_formated'=>'1:05 PM','end_formated'=>'2:00 PM'],
            ['start'=>'14:05:00','end'=>'15:00:00','start_formated'=>'2:05 PM','end_formated'=>'3:00 PM'],
            ['start'=>'15:00:00','end'=>'15:55:00','start_formated'=>'3:00 PM','end_formated'=>'3:55 PM'],
            ['start'=>'15:55:00','end'=>'16:50:00','start_formated'=>'3:55 PM','end_formated'=>'4:50 PM'],
            ['start'=>'16:50:00','end'=>'17:45:00','start_formated'=>'4:50 PM','end_formated'=>'5:45 PM'],
            ['start'=>'17:45:00','end'=>'18:40:00','start_formated'=>'5:45 PM','end_formated'=>'6:40 PM'],
            ['start'=>'18:40:00','end'=>'19:35:00','start_formated'=>'6:40 PM','end_formated'=>'7:35 PM'],
            ['start'=>'20:05:00','end'=>'21:00:00','start_formated'=>'8:05 PM','end_formated'=>'9:00 PM'],
            ['start'=>'21:05:00','end'=>'22:00:00','start_formated'=>'9:05 PM','end_formated'=>'10:00 PM']
        ];
        $json->default_time  = json_encode($defaultTimes);
        $json->save();
      }
      $defaultTimes= json_decode($json->default_time);
    // return response()->json($defaultTimes);

        $tabTypeData = $request->tab_type_data;
        $groupsExists=[];
        $roomsExists=[];

        $sessions = [];


            $sessions = SessionTemplate::
              where('id_template', $request->template_selected)
            ->groupBy('start','end','id_group','day')
            ->orderBy('id_group', 'asc')
            ->get();



        $groupsSession = [];

        foreach ($sessions as $key =>   $session) {

                $groupTmp1=[];

                // EMPLOYEE GROUP
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
                $groupTmp1['time_start']=  $session->start;
                $groupTmp1['time_end']=  $session->end;
                $groupTmp1['day']=  $session->day;

                $status = Pilates::getRealStatusGroupByNumFormatTemplate($employeeGroupData['id_group'],$roomTmp,$session->start, $session->end,$session->day,$request->template_selected);
                $groupTmp1['status'] = $status['num'];
                $groupTmp1['status_format'] = $status['format'];

                array_push($groupsSession, [
                    'group' => $groupTmp1,
                    'room' => $roomTmp,
                    'sessions' => $session,
                    'time_start' => $session->start,
                    'time_end' => $session->end,
                    'time_end_for_short' => $session->end,
                    'day' => $session->day,
                    'is_default' =>false,
                    'status_employee'=> false//($employeeGroupData['id_employee']!=null)? Pilates::getStatusEmployeeGroupBySessionGroup($employeeGroupData['id_employee'],$session->start,$session->end,false):false
                ]);
                }

        }


        foreach ($defaultTimes as $key => $defaultTime) {
            $flagExist=false;

            $startDefault=$defaultTime->start;
            $endDefault=$defaultTime->end;

            foreach ($groupsSession as $key => $groupSession) {
            if($startDefault==$groupSession['time_start'] && $endDefault==$groupSession['time_end'])$flagExist=true;
            }
            if(!$flagExist)array_push($groupsSession, [
                'time_start' => $startDefault,
                'time_end' => $endDefault,
                'time_end_for_short' => $endDefault,
                'day' => null,
                'is_default' =>true
            ]);
        }

        $groupsSession = collect($groupsSession)->sortBy('time_end_for_short', SORT_NATURAL | SORT_FLAG_CASE, false)->values()->all();
        $resData=['groups'=>$groupsSession,'template'=> Template::where('id',$request->template_selected)->first() ];

        return response()->json($resData);
    }




    public function analizeMap($group)
    {

        $status = Pilates::getRealStatusGroupByNumFormatTemplate($group->id_group, $group->time_start, $group->time_end, $group->day);
        $group['status'] = $status['num'];
        $group['status_format'] = $status['format'];

        return $group;
    }



    public function editGroupSessionsDrag(Request $request)
    {


        $rules = [
            'timepicker_start' => 'required|date_format:"H:i"',
            'timepicker_end' => 'required|date_format:"H:i"|after:timepicker_start',

            'timepicker_start_previous' => 'required|date_format:"H:i"',
            'timepicker_end_previous' => 'required|date_format:"H:i"|after:timepicker_start_previous'
        ];


        $isSame = true;
        if ($request->timepicker_start == $request->timepicker_start_previous && $request->timepicker_end == $request->timepicker_end_previous &&  $request->day==$request->day_previous ) {
            $rules['id_group'] = 'required';
        } else {
            $isSame = false;
            $rules['id_group'] = [
                'required',
                //new RuleEmployeeDuplicateSessionTime2( $request->date_start . ' ' . $request->timepicker_start,$request->date_start . ' ' . $request->timepicker_end,$request->id_group),
                new RuleDuplicateGroupSessionsTemplate($request->id_group,  $request->timepicker_start, $request->timepicker_end,$request->id_template,$request->day),
                new RuleRoomCrashTemplate($request->id_group, $request->timepicker_start, $request->timepicker_end,$request->id_template,$request->day),
                new RuleDuplicateSessionClientGroupTemplate($request->id_template,$request->id_group,$request->timepicker_start_previous,$request->timepicker_end_previous,$request->day_previous,$request->timepicker_start,$request->timepicker_end,$request->day)
            ];
        }




        $messages = [
            'id_group.required' => 'Necesita seleccionar un grupo.',
            'timepicker_end.after' => 'La hora final debe ser mayor que la hora inicial.'
        ];

        $customAttr = [
            'day' => 'día',
            'timepicker_start' => 'hora de inicio',
            'timepicker_end' => 'hora de término'
        ];


        $validator = Validator::make($request->all(), $rules, $messages, $customAttr);

        if ($validator->passes()) {

            if (!$isSame) {
                $sessions = SessionTemplate::
                      where('start',  $request->timepicker_start_previous)
                    ->where('end',  $request->timepicker_end_previous)
                    ->where('id_group', $request->id_group)
                    ->where('day', $request->day_previous)
                    ->where('id_template', $request->id_template)
                    ->get();

                foreach ($sessions as $key => $session) {
                SessionTemplate::where('id', $session->id)->update(['start' => $request->timepicker_start, 'end' => $request->timepicker_end, 'day'=>$request->day]);
                }
            }

            return response()->json(['success' => 'Actualización realizada correctamente.', 'error' => false]);
        } else {
            return response()->json(['success' => false, 'error' => $validator->errors()->all()]);
        }
    }

    public function editGroupSessions(Request $request)
    {

        $rules = [
            'timepicker_start' => 'required|date_format:"H:i"',
            'timepicker_end' => 'required|date_format:"H:i"|after:timepicker_start',

            'timepicker_start_previous' => 'required|date_format:"H:i"',
            'timepicker_end_previous' => 'required|date_format:"H:i"|after:timepicker_start_previous'
        ];



        $isSame = true;
        if ($request->timepicker_start == $request->timepicker_start_previous && $request->timepicker_end == $request->timepicker_end_previous &&  $request->day==$request->day_previous && $request->id_group_previous==$request->id_group) {
            $rules['id_group'] = 'required';
        } else {
            $isSame = false;
            $rules['id_group'] = [
                'required',
                //new RuleEmployeeDuplicateSessionTime2( $request->date_start . ' ' . $request->timepicker_start,$request->date_start . ' ' . $request->timepicker_end,$request->id_group),
                new RuleDuplicateGroupSessionsTemplate($request->id_group,  $request->timepicker_start, $request->timepicker_end,$request->id_template,$request->day),
                new RuleRoomCrashTemplate($request->id_group_previous, $request->timepicker_start, $request->timepicker_end,$request->id_template,$request->day),
                new sameTypeRoomGroup($request->id_group_previous,$request->id_group),
                new RuleSufficientCapacityRoomGroupTemplate( $request->timepicker_start_previous, $request->timepicker_end_previous,$request->id_group_previous,$request->id_group,$request->id_template,$request->day_previous),
                new RuleDuplicateSessionClientGroupTemplate($request->id_template,$request->id_group_previous,$request->timepicker_start_previous,$request->timepicker_end_previous,$request->day_previous,$request->timepicker_start,$request->timepicker_end,$request->day)
            ];
        }


        $messages = [
            'id_group.required' => 'Necesita seleccionar un grupo.',
            'timepicker_end.after' => 'La hora final debe ser mayor que la hora inicial.'
        ];

        $customAttr = [
            'day' => 'día',
            'timepicker_start' => 'hora de inicio',
            'timepicker_end' => 'hora de término'
        ];


        $validator = Validator::make($request->all(), $rules, $messages, $customAttr);

        if ($validator->passes()) {




                if (!$isSame) {
                    $sessions = SessionTemplate::
                          where('start',  $request->timepicker_start_previous)
                        ->where('end',  $request->timepicker_end_previous)
                        ->where('id_group', $request->id_group_previous)
                        ->where('day', $request->day_previous)
                        ->where('id_template', $request->id_template)
                        ->get();

                    foreach ($sessions as $key => $session) {
                    SessionTemplate::where('id', $session->id)->update(['start' => $request->timepicker_start, 'end' => $request->timepicker_end, 'day'=>$request->day,'id_group'=>$request->id_group]);
                    }
                }


            return response()->json(['success' => 'Actualización realizada correctamente.', 'error' => false]);
        } else {
            return response()->json(['success' => false, 'error' => $validator->errors()->all()]);
        }
    }

    public function moveSessions(Request $request){
        $cantSessions=(!empty($request->input('sessions_selected')))?count($request->input('sessions_selected')):0;
        $dateStart = DateTime::createFromFormat('H:i:s', $request->timepicker_start)->format('H:i');
        $dateEnd = DateTime::createFromFormat('H:i:s', $request->timepicker_end)->format('H:i');
        $rules = [

            'id_group'  => [
                'required',
                'required_with:sessions_selected',
                new RuleEmptyGroupTemplate($request->id_group, $dateStart, $dateEnd, $cantSessions,$request->day,$request->id_template),

            ],
            'sessions_selected' => [
                'required',
                new RuleSessionSameTypeTemplate($request->id_group,$request->input('sessions_selected')[0])
            ]
        ];

        if (!empty($request->input('sessions_selected'))) {
            $sessionsSelected =  $request->input('sessions_selected');

            if(count($sessionsSelected)>0){
             $tmpSession=  SessionTemplate::where('id',$sessionsSelected[0])->first();
             SessionTemplate::create([
                'id_group' =>$tmpSession->id_group,
                'id_client' => null,
                'id_template' => $tmpSession->id_template,
                'day' =>$tmpSession->day,
                'start' => $tmpSession->start,
                'end' => $tmpSession->end,
                'observation' => $tmpSession->observation
            ]);
            }
            foreach ($sessionsSelected as $key => $sessionSelected) {
                $rules["sessions_selected.$key"] = [
                    new RuleDuplicateSessionSameGroupTemplate($request->id_group,$request->timepicker_start,$request->timepicker_end,$sessionSelected,$request->day,$request->id_template)
                ];
            }
        }
        $messages = [
            'sessions_selected.required' => 'Ninguna sesión para mover.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($request->ajax()) {

            if ($validator->passes()) {


            $sessions =  $request->input('sessions_selected');

            foreach ($sessions as $key => $session) {
            SessionTemplate::where('id',$session)->update(['start'=>$request->timepicker_start,'end'=>$request->timepicker_end,'id_group'=>$request->id_group,'day'=>$request->day]);
            }
            $countSession=count($sessions);
                return response()->json(['response' => ($countSession>1)?"$countSession sesiones movidas con éxito.":"$countSession sesión movida con éxito.", 'status' => true]);
            } else {
                return response()->json(['response' => $validator->errors()->all(), 'status' => false]);
            }
        } else {
            abort(404);
        }
    }

    public function storeTemplate(Request $request)
    {

        $rules = [
            'name' => 'required|max:100|unique:template,name'
        ];

        $messages = [
            'name.required' => 'El nombre o titulo de plantilla es obligatorio.'
        ];

        $customAttr = [
            'name' => 'nombre o titulo de plantilla',
        ];



        $validator = Validator::make($request->all(), $rules, $messages, $customAttr);

        if ($request->ajax()) {

            if ($validator->passes()) {

                $template = Template::create(['name' => $request->name, 'status' => 'false']);

                  /*auditoria: start*/Pilates::setAudit("Alta plantilla id: $template->id"); /*auditoria: end*/
                return response()->json(['response' => 'Plantilla creada con éxito.', 'status' => true, 'data' => $template]);
            } else {
                return response()->json(['response' => $validator->errors()->all(), 'status' => false, 'data' => []]);
            }
        } else {
            abort(404);
        }


    }



    public function storeGroupSession(Request $request)
    {

        $cantClients=(!empty($request->input('clients_selected')))?count($request->input('clients_selected')):0;
        $rules = [
            'serie_days_selected' => 'required',
            'id_template' => 'required',
            'group_selected'  => [
                'required',
                'required_with:clients_selected'
            ],
            'timepicker_start' => 'required|date_format:"H:i"',
            'timepicker_end' => 'required|date_format:"H:i"|after:timepicker_start',
            'observation' => 'nullable|max:5000'
        ];



        if (!empty($request->input('group_selected'))) {


            if(!empty($request->input('serie_days_selected'))){
                $daysSelected = $request->input('serie_days_selected');
                foreach ($daysSelected as $key => $serieDay) {
                    $rules["serie_days_selected.$key"] = [
                        new RuleEmptyGroupTemplate($request->group_selected['id_group'], $request->timepicker_start,$request->timepicker_end, $cantClients,$serieDay,$request->id_template),
                        new RuleDuplicateGroupSessionsTemplate($request->group_selected['id_group'], $request->timepicker_start,$request->timepicker_end,$request->id_template,$serieDay),
                        new RuleRoomCrashTemplate($request->group_selected['id_group'], $request->timepicker_start, $request->timepicker_end,$request->id_template,$serieDay)
                    ];
                }
            }

            if (!empty($request->input('clients_selected')) && !empty($request->input('serie_days_selected'))) {
                $clients =  $request->input('clients_selected');
                foreach ($daysSelected as $key => $serieDay) {
                foreach ($clients as $key => $client) {
                    $rules["clients_selected.$key.id"] = [
                        new RuleDuplicatesSessionClientTemplate($request->group_selected['id_group'], $request->timepicker_start,$request->timepicker_end,  $client['id'], "$client[name] $client[last_name]",$request->id_template,$serieDay)
                    ];
                }
            }
            }




        }



        $messages = [
            'group_selected.required' => 'Necesita seleccionar un grupo.',
            'serie_days_selected.required' => 'Necesita seleccionar al menos un día.',
            'id_template.required' => 'Necesita seleccionar una plantilla.',
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


                $groupRoom = Group::where("group.id", $request->group_selected['id_group'])->join('room', 'group.id_room', '=', 'room.id')->first();

                $dateStart = $request->timepicker_start;
                $dateEnd = $request->timepicker_end;

                // if () {
                //   // code...
                // }
                $dateStart = DateTime::createFromFormat('H:i', $dateStart)->format('H:i:s');
                $dateEnd = DateTime::createFromFormat('H:i', $dateEnd)->format('H:i:s');
                $template = Template::where('id',$request->id_template)->first();
                $template_json = json_decode($template->default_time);
                $count_exists = 0;
                $array_json = [];
                foreach ($template_json as $key_json => $value_json) {
                  if ($value_json->start == $dateStart && $value_json->end == $dateEnd ) {
                    $count_exists += 1;
                  }
                  $array_json [] = $value_json;
                }
                $new_data_json = [];
                if ($count_exists == 0) {
                  $new_data_json [] = [
                    "start" => $dateStart,
                    "end" => $dateEnd,
                    "start_formated" => date('h:i A', strtotime($dateStart)),
                    "end_formated" => date('h:i A', strtotime($dateEnd)),
                    ];
                $json_final = array_merge($array_json,$new_data_json);
                $template->default_time = json_encode($json_final);
                }

                // return response()->json([$dateStart, $dateEnd, $count_exists,$new_data_json,gettype($json_final),$json_final]);

                $daysSelected = $request->input('serie_days_selected');

                if (!empty($request->input('clients_selected'))) {
                    $clients =  $request->input('clients_selected');
                    foreach ($daysSelected as $key => $serieDay) {
                        foreach ($clients as $key => $client) {

                            if ($client['negative_balance']) {
                                if ($groupRoom->type_room == 'Máquina') {
                                    SessionTemplate::create([
                                        'id_group' => $request->group_selected['id_group'],
                                        'id_client' => $client['id'] ?? null,
                                        'id_template' => $request->id_template,
                                        'day' => $serieDay,
                                        'start' => $dateStart,
                                        'end' => $dateEnd,
                                        'observation' => $request->observation,
                                        'sessions_machine' => 1,
                                    ]);
                                } else if ($groupRoom->type_room == 'Suelo') {
                                    SessionTemplate::create([
                                        'id_group' => $request->group_selected['id_group'],
                                        'id_client' => $client['id'] ?? null,
                                        'id_template' => $request->id_template,
                                        'day' => $serieDay,
                                        'start' => $dateStart,
                                        'end' => $dateEnd,
                                        'observation' => $request->observation,
                                        'sessions_floor' => 1
                                    ]);
                                } else if ($groupRoom->type_room == 'Camilla') {
                                    SessionTemplate::create([
                                        'id_group' => $request->group_selected['id_group'],
                                        'id_client' => $client['id'] ?? null,
                                        'id_template' => $request->id_template,
                                        'day' => $serieDay,
                                        'start' => $dateStart,
                                        'end' => $dateEnd,
                                        'observation' => $request->observation,
                                        'sessions_individual' => 1
                                    ]);
                                }
                            }
                        }

                        //nulled session
                        SessionTemplate::create([
                            'id_group' => $request->group_selected['id_group'],
                            'id_client' => null,
                            'id_template' => $request->id_template,
                            'day' => $serieDay,
                            'start' => $dateStart,
                            'end' => $dateEnd,
                            'observation' => $request->observation
                        ]);

                        //
                    }

                } else {

                    foreach ($daysSelected as $key => $serieDay) {

                                if ($groupRoom->type_room == 'Máquina') {
                                    SessionTemplate::create([
                                        'id_group' => $request->group_selected['id_group'],
                                        'id_client' => null,
                                        'id_template' => $request->id_template,
                                        'day' => $serieDay,
                                        'start' => $dateStart,
                                        'end' => $dateEnd,
                                        'observation' => $request->observation,
                                        'sessions_machine' => 0,
                                    ]);
                                } else if ($groupRoom->type_room == 'Suelo') {
                                    SessionTemplate::create([
                                        'id_group' => $request->group_selected['id_group'],
                                        'id_client' => null,
                                        'id_template' => $request->id_template,
                                        'day' => $serieDay,
                                        'start' => $dateStart,
                                        'end' => $dateEnd,
                                        'observation' => $request->observation,
                                        'sessions_floor' => 0
                                    ]);
                                } else if ($groupRoom->type_room == 'Camilla') {
                                    SessionTemplate::create([
                                        'id_group' => $request->group_selected['id_group'],
                                        'id_client' =>  null,
                                        'id_template' => $request->id_template,
                                        'day' => $serieDay,
                                        'start' => $dateStart,
                                        'end' => $dateEnd,
                                        'observation' => $request->observation,
                                        'sessions_individual' => 0
                                    ]);
                      }
                    }
                }
                return response()->json(['success' => 'El grupo de sesiones se agrego con éxito.', 'error' => false]);
            } else {
                return response()->json(['success' => false, 'error' => $validator->errors()->all()]);
            }
        } else {
            abort(404);
        }
    }

    public function editHourGroupSession(Request $request)
    {

        $rules = [
            'id_template' => 'required',
            'timepicker_start' => 'required|date_format:"H:i"',
            'timepicker_end' => 'required|date_format:"H:i"|after:timepicker_start',
            'observation' => 'nullable|max:5000'
        ];

        $messages = [
            'group_selected.required' => 'Necesita seleccionar un grupo.',
            'serie_days_selected.required' => 'Necesita seleccionar al menos un día.',
            'id_template.required' => 'Necesita seleccionar una plantilla.',
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

                $dateStart = $request->timepicker_start;
                $dateEnd = $request->timepicker_end;

                $dateStartOriginal = substr($request->observation, 0, 5);
                $dateEndOriginal = substr($request->observation, -5);

                $dateStart = DateTime::createFromFormat('H:i', $dateStart)->format('H:i:s');
                $dateEnd = DateTime::createFromFormat('H:i', $dateEnd)->format('H:i:s');

                $dateStartOriginal = DateTime::createFromFormat('H:i', $dateStartOriginal)->format('H:i:s');
                $dateEndOriginal = DateTime::createFromFormat('H:i', $dateEndOriginal)->format('H:i:s');

                $template = Template::where('id',$request->id_template)->first();

                $template_json = json_decode($template->default_time);

                // $count_exists = 0;
                $array_json = [];
                foreach ($template_json as $key_json => $value_json) {
                  if ($value_json->start != $dateStartOriginal && $value_json->end != $dateEndOriginal ) {
                    $array_json [] = $value_json;
                  }
                }
                // $new_data_json = [];
                // if ($count_exists == 0) {
                  $new_data_json [] = [
                    "start" => $dateStart,
                    "end" => $dateEnd,
                    "start_formated" => date('h:i A', strtotime($dateStart)),
                    "end_formated" => date('h:i A', strtotime($dateEnd)),
                    ];
                $json_final = array_merge($array_json,$new_data_json);
                // return response()->json(['success' => 'El horario se cambio con éxito.',
                // 'json' => $json_final,
                // 'data' => [$dateStart,$dateEnd,$dateStartOriginal,$dateEndOriginal ], 'error' => false]);
                $template->default_time = json_encode($json_final);
                $template->save();
                // }

                return response()->json(['success' => 'El horario se cambio con éxito.', 'error' => false]);
            } else {
                return response()->json(['success' => false, 'error' => $validator->errors()->all()]);
            }
        } else {
            abort(404);
        }
    }


    public function storeNewSession(Request $request)
    {
        $cantClients=(!empty($request->input('clients_selected')))?count($request->input('clients_selected')):0;

        $date_start = DateTime::createFromFormat('H:i:s', $request->timepicker_start)->format('H:i');
        $date_end = DateTime::createFromFormat('H:i:s', $request->timepicker_end)->format('H:i');
        $rules = [

            'group_selected'  => [
                'required',
                'required_with:clients_selected',
                new RuleEmptyGroupTemplate($request->group_selected['id_group'],  $date_start ,$date_end, $cantClients,$request->day,$request->id_template),
            ],
            'day'   => 'required',
            'timepicker_start' => 'required|date_format:"H:i:s"',
            'timepicker_end' => 'required|date_format:"H:i:s"|after:timepicker_start',
            'observation' => 'nullable|max:250',
            'clients_selected' => 'required'
        ];

        if (!empty($request->input('group_selected'))) {
            if (!empty($request->input('clients_selected'))) {
                $clients =  $request->input('clients_selected');
                foreach ($clients as $key => $client) {
                    $rules["clients_selected.$key.id"] = [
                        new RuleDuplicatesSessionClientTemplate($request->group_selected['id_group'], $date_start,$date_end,  $client['id'], "$client[name] $client[last_name]",$request->id_template,$request->day)
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
            'day' => 'día',
            'timepicker_start' => 'hora de inicio',
            'timepicker_end' => 'hora de término',
            'observation' => 'observaciones'
        ];



        $validator = Validator::make($request->all(), $rules, $messages, $customAttr);

        if ($request->ajax()) {

            if ($validator->passes()) {

                $groupRoom = Group::where("group.id", $request->group_selected['id_group'])->join('room', 'group.id_room', '=', 'room.id')->first();

                $clients =  $request->input('clients_selected');

                foreach ($clients as $key => $client) {


                if ($groupRoom->type_room == 'Máquina') {

                    SessionTemplate::create([
                        'id_group' => $request->group_selected['id_group'],
                        'id_client' =>  $client['id'] ?? null,
                        'id_template' => $request->id_template,
                        'day' => $request->day,
                        'start' => $request->timepicker_start,
                        'end' => $request->timepicker_end,
                        'observation' => $request->observation,
                        'sessions_machine' => 1
                    ]);
                } else if ($groupRoom->type_room == 'Suelo') {
                    SessionTemplate::create([
                        'id_group' => $request->group_selected['id_group'],
                        'id_client' =>  $client['id'] ?? null,
                        'id_template' => $request->id_template,
                        'day' => $request->day,
                        'start' => $request->timepicker_start,
                        'end' => $request->timepicker_end,
                        'observation' => $request->observation,
                        'sessions_floor' => 1
                    ]);
                } else if ($groupRoom->type_room == 'Camilla') {

                    SessionTemplate::create([
                        'id_group' => $request->group_selected['id_group'],
                        'id_client' =>  $client['id'] ?? null,
                        'id_template' => $request->id_template,
                        'day' => $request->day,
                        'start' => $request->timepicker_start,
                        'end' => $request->timepicker_end,
                        'observation' => $request->observation,
                        'sessions_individual' => 1
                    ]);
                }
            }
            $countClients=count($clients);
                return response()->json(['success' => ($countClients>1)?"$countClients sesiones de cliente agregadas con éxito.":"$countClients sesión de cliente agregada con éxito.", 'error' => true]);
            } else {
                return response()->json(['success' => false, 'error' => $validator->errors()->all()]);
            }
        } else {
            abort(404);
        }
    }


    public function dataTableGroupCalendar(Request $request)
    {
        $group = new Group();
        $groups = $group->getGroupDataTableForCalendarTemplate($request);
        return response()->json($groups);
    }

    public function dataTableSessionsGroup(Request $request)
    {
        $session = new SessionTemplate();
        $sessions = $session->getSessionsGroupForCalendar($request);
        return response()->json($sessions);
    }


    function deleteSession(Request $request)
    {
        $errors = 0;
        $cantSuccsess = 0;
        $idsSession = $request['id'];
        foreach ($idsSession as $key => $id) {
            if (SessionTemplate::where('id', $id)->delete()) {
                $cantSuccsess++;
            } else {
                $errors++;
            }
        }

        $response = ['status' => true, 'response' => ($cantSuccsess <= 1) ?
            $cantSuccsess . ' sesión eliminada con éxito' :
            $cantSuccsess . ' sesiones eliminadas con éxito'];

        return response()->json($response);
    }

    function deleteGroupSessions(Request $request)
    {


        $status = false;
        if (SessionTemplate::
              where('id_group', $request->id_group)
            ->where('start', '=', $request->timepicker_start)
            ->where('end', '=', $request->timepicker_end)
            ->where('id_template', '=', $request->id_template)
            ->where('day', '=', $request->day)
            ->delete()
        ) {
            $status = true;
        }

        $response = ['status' => $status, 'response' => ($status) ? 'Grupo de sesiones eliminado con éxito' : 'El grupo no pudo ser eliminado con éxito, intente de nuevo.'];

        return response()->json($response);
    }

    function deleteTemplate(Request $request){
        $rules = [
            'templates' => 'required',
        ];


        $messages = [
            'templates.required' => 'Necesita seleccionar una plantilla.',
        ];



        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->passes()) {
            $errors = 0;
            $cantSuccsess = 0;
            $idsSession = $request['templates'];
            foreach ($idsSession as $key => $id) {
                if (Template::where('id', $id)->delete()) {
                    $cantSuccsess++;
                } else {
                    $errors++;
                }
            }

            $response = ($cantSuccsess <= 1) ?
                $cantSuccsess . ' plantilla eliminada con éxito' :
                $cantSuccsess . ' plantillas eliminadas con éxito';

                   /*auditoria: start*/Pilates::setAudit("Baja plantilla ids: ".implode(', ', $idsSession)); /*auditoria: end*/

            return response()->json(['response' => $response, 'status' => true]);
        } else {
            return response()->json([ 'response' => $validator->errors()->all(),'status' => false]);
        }
    }

    function enableDisableTemplate(Request $request){
        $rules = [
            'template_selected' => 'required',
        ];


        $messages = [
            'template_selected.required' => 'Necesita seleccionar una plantilla.',
        ];



        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->passes()) {

            $templates=Template::where('id','!=',$request->template_selected)->get();

            $updateToFalseTemplate=[];
            foreach ($templates as $key => $template)
            $updateToFalseTemplate[]=$template->id;

            if(count($updateToFalseTemplate)>0)
            Template::whereIn('id',$updateToFalseTemplate)->update(['status'=>'false']);


            $templateTmp=Template::where('id',$request->template_selected)->first();
            $message="";
            if($templateTmp->status=='false'){
            $templateTmp->update(['status'=>'true']);
              /*auditoria: start*/Pilates::setAudit("Activación plantilla id: $request->template_selected"); /*auditoria: end*/
            $message="La plantilla ahora está activa.";
            }else{
             $templateTmp->update(['status'=>'false']);
               /*auditoria: start*/Pilates::setAudit("Desactivación plantilla id: $request->template_selected"); /*auditoria: end*/
             $message="La plantilla ahora está desactivada.";
            }


            return response()->json(['response' =>  $message, 'status' => true]);
        } else {
            return response()->json([ 'response' => $validator->errors()->all(),'status' => false]);
        }
    }

    function renameTemplate(Request $request){
        $rules = [
            'template_selected' => 'required',
            'name' => 'required|max:100|unique:template,name,'.$request->template_selected
        ];


        $messages = [
            'name.required' => 'El nombre o titulo de plantilla es obligatorio.',
            'template_selected.required' => 'Necesita seleccionar una plantilla.'
        ];

        $customAttr = [
            'name' => 'nombre o titulo de plantilla',
        ];


        $validator = Validator::make($request->all(), $rules, $messages,$customAttr);

        if ($validator->passes()) {
            Template::where('id',$request->template_selected)->update(['name'=>$request->name]);
            /*auditoria: start*/Pilates::setAudit("Renombrar plantilla id: $request->template_selected"); /*auditoria: end*/
            return response()->json(['response' =>  "El nombre ha sido cambiado.", 'status' => true]);
        } else {
            return response()->json([ 'response' => $validator->errors()->all(),'status' => false]);
        }
    }


}
