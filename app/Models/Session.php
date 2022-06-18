<?php

namespace App\Models;

use Illuminate\Http\Request;
use App\Helpers\Pilates;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $table = 'session';
    protected $guarded = ['id'];

    public $groupsExists=[];
    public  $roomsExists=[];


    public function getSessionsGroupForCalendar(Request $request)
    {


        $columns = array(
            0 => 'id_session',
            1 => 'last_name',
            2 => 'name',
            3 => 'level',
            4 => 'observation'
        );



        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;

        $sessions = [];

        $totalData = 0;
        $totalFiltered = $totalData;

        //hire

        if (
            !empty($request->input('date_start'))
            &&
            !empty($request->input('timepicker_start'))
            &&
            !empty($request->input('timepicker_end'))
            &&
            !empty($request->input('group_selected'))
        ) {

            $dateStart = $request->date_start . ' ' . $request->timepicker_start;
            $dateEnd = $request->date_start . ' ' . $request->timepicker_end;
            $group_selected = $request->group_selected;

            $start_date = DateTime::createFromFormat('Y-m-d g:i A', $dateStart)->format('Y-m-d H:i:s');
            $end_date = DateTime::createFromFormat('Y-m-d g:i A', $dateEnd)->format('Y-m-d H:i:s');

            $totalData = Session::leftJoin('client', 'session.id_client', '=', 'client.id')
                ->where('session.date_start', '=', $start_date)
                ->where('session.date_end', '=', $end_date)
                ->where('session.id_group', $group_selected)
                ->whereNotNull('session.id_client')
                ->get([
                    '*', 'session.id as id_session', 'session.observation as observation'
                ])->map(function ($session) {
                    return $this->analizeFilterSessionsGroup($session);
                })->count();

            $totalFiltered = $totalData;

            if (empty($request->input('search.value'))) {

                if ($limit == -1) {
                    $sessions = Session::leftJoin('client', 'session.id_client', '=', 'client.id')
                        ->where('session.date_start', '=', $start_date)
                        ->where('session.date_end', '=', $end_date)
                        ->where('session.id_group', $group_selected)
                        ->whereNotNull('session.id_client')
                        ->get([
                            '*', 'session.id as id_session', 'session.observation as observation'
                        ])->map(function ($session) {
                            return $this->analizeFilterSessionsGroup($session);
                        })->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
                } else {
                    $sessions = Session::leftJoin('client', 'session.id_client', '=', 'client.id')
                        ->where('session.date_start', '=', $start_date)
                        ->where('session.date_end', '=', $end_date)
                        ->where('session.id_group', $group_selected)
                        ->whereNotNull('session.id_client')
                        ->get([
                            '*', 'session.id as id_session', 'session.observation as observation'
                        ])->map(function ($session) {
                            return $this->analizeFilterSessionsGroup($session);
                        })
                        ->skip($start)->take($limit)
                        ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
                }
            } else {
                $search = $request->input('search.value');
                if ($limit == -1) {
                    $sessions = Session::leftJoin('client', 'session.id_client', '=', 'client.id')
                        ->where('session.date_start', '=', $start_date)
                        ->where('session.date_end', '=', $end_date)
                        ->where('session.id_group', $group_selected)
                        ->whereNotNull('session.id_client')
                        ->get([
                            '*', 'session.id as id_session', 'session.observation as observation'
                        ])->map(function ($session) {
                            return $this->analizeFilterSessionsGroup($session);
                        })
                        ->filter(function ($session) use ($search, $columns, $request) {
                            return $this->filterSearchSessionsDataTable($session, $search, $columns, $request);
                        })
                        ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
                } else {

                    $sessions = Session::leftJoin('client', 'session.id_client', '=', 'client.id')
                        ->where('session.date_start', '=', $start_date)
                        ->where('session.date_end', '=', $end_date)
                        ->where('session.id_group', $group_selected)
                        ->whereNotNull('session.id_client')
                        ->get([
                            '*', 'session.id as id_session', 'session.observation as observation'
                        ])->map(function ($session) {
                            return $this->analizeFilterSessionsGroup($session);
                        })
                        ->filter(function ($session) use ($search, $columns, $request) {
                            return $this->filterSearchSessionsDataTable($session, $search, $columns, $request);
                        })
                        ->skip($start)->take($limit)
                        ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
                }

                $totalFiltered = Session::leftJoin('client', 'session.id_client', '=', 'client.id')
                    ->where('session.date_start', '=', $start_date)
                    ->where('session.date_end', '=', $end_date)
                    ->where('session.id_group', $group_selected)
                    ->whereNotNull('session.id_client')
                    ->get([
                        '*', 'session.id as id_session', 'session.observation as observation'
                    ])
                    ->filter(function ($sale) use ($search, $columns, $request) {
                        return $this->filterSearchSessionsDataTable($sale, $search, $columns, $request);
                    })
                    ->count();
            }
        }

        $result = [
            'iTotalRecords'        =>  $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData'               =>  $sessions
        ];

        return $result;
    }

    function analizeFilterSessionsGroup($session)
    {

        $session['actions'] = json_decode($session);

        return $session;
    }

    function filterSearchSessionsDataTable($session, $search, $columns, $request)
    {
        $item = false;
        //general
        foreach ($columns as $colum)
            if (stristr($session[$colum], $search))
                $item = $session;
        return $item;
    }

    //////////////////////////////////////////////////////////////////////
    public function getGroupsSessions(Request $request)
    {
        $columns = array(
            1 => 'date',
            2 => 'start_date',
            3 => 'end_date',
            4 => 'type',
            5 => 'employee_name',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;

        $sessions = [];

        $totalData = 0;
        $totalFiltered = $totalData;

        //hire

        if (
            !empty($request->input('start_date'))
            &&
            !empty($request->input('end_date'))
        ) {

          

            $start_date = $request->start_date;
            $end_date = $request->end_date;

            $totalData = Session::
            where('date_start', '>=', $start_date)
           ->where('date_end', '<=', $end_date)
           ->where('status',  'enable')
           ->groupBy('date_start','date_end','id_group')
           ->get()
           ->filter(function ($session) {
                    return $this->mapGroupsSessions($session);
            })->count();

            $totalFiltered = $totalData;

            if (empty($request->input('search.value'))) {

                if ($limit == -1) {
                    $sessions = Session::
                    where('date_start', '>=', $start_date)
                   ->where('date_end', '<=', $end_date)
                   ->where('status',  'enable')
                   ->groupBy('date_start','date_end','id_group')
                   ->get()
                   ->filter(function ($session) {
                    return $this->mapGroupsSessions($session);
                    })->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
                } else {
                    $sessions = Session::
                    where('date_start', '>=', $start_date)
                   ->where('date_end', '<=', $end_date)
                   ->where('status',  'enable')
                   ->groupBy('date_start','date_end','id_group')
                   ->get()
                   ->filter(function ($session) {
                            return $this->mapGroupsSessions($session);
                        })
                        ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                        ->skip($start)->take($limit)
                        ->values()->all();
                }
            } else {
                $search = $request->input('search.value');
                if ($limit == -1) {
                    $sessions = Session::
                    where('date_start', '>=', $start_date)
                   ->where('date_end', '<=', $end_date)
                   ->where('status',  'enable')
                   ->groupBy('date_start','date_end','id_group')
                   ->get()
                   ->filter(function ($session) {
                            return $this->mapGroupsSessions($session);
                        })
                        ->filter(function ($session) use ($search, $columns, $request) {
                            return $this->filterSearchGroupsSessions($session, $search, $columns, $request);
                        })
                        ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
                } else {

                    $sessions = Session::
                    where('date_start', '>=', $start_date)
                   ->where('date_end', '<=', $end_date)
                   ->where('status',  'enable')
                   ->groupBy('date_start','date_end','id_group')
                   ->get()
                   ->filter(function ($session) {
                            return $this->mapGroupsSessions($session);
                        })
                        ->filter(function ($session) use ($search, $columns, $request) {
                            return $this->filterSearchGroupsSessions($session, $search, $columns, $request);
                        })
                        ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                        ->skip($start)->take($limit)
                        ->values()->all();
                }

                $totalFiltered = Session::
                where('date_start', '>=', $start_date)
               ->where('date_end', '<=', $end_date)
               ->where('status',  'enable')
               ->groupBy('date_start','date_end','id_group')
               ->get()
                ->filter(function ($sale) use ($search, $columns, $request) {
                        return $this->filterSearchGroupsSessions($sale, $search, $columns, $request);
                 })
                    ->count();
            }
        }

        $result = [
            'iTotalRecords'        =>  $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData'               =>  $sessions
        ];

        return $result;
    }


    public function getGroupsSessions2(Request $request)
    {
        $columns = array(
            1 => 'date',
            2 => 'start_date',
            3 => 'end_date',
            4 => 'type'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;

        $sessions = [];

        $totalData = 0;
        $totalFiltered = $totalData;
     

        //hire

        if (
            !empty($request->input('start_date'))
            &&
            !empty($request->input('end_date'))
            &&
            !empty($request->input('group_selected'))
        ) {
            $group_selected = $request->group_selected;
            $group_selected=Group::where('id',$request->group_selected)->first();
            $type_room= Room::where('id',$group_selected->id_room)->first()->type_room;

          

            $start_date = $request->start_date;
            $end_date = $request->end_date;

            $totalData = Session::
            where('date_start', '>=', $start_date)
           ->where('date_end', '<=', $end_date)
           ->where('status',  'enable')
           ->groupBy('date_start','date_end','id_group')
           ->get()
           ->filter(function ($session) use($type_room){
                    return $this->mapGroupsSessions2($session,$type_room);
            })->count();

            $totalFiltered = $totalData;

            if (empty($request->input('search.value'))) {

                if ($limit == -1) {
                    $sessions = Session::
                    where('date_start', '>=', $start_date)
                   ->where('date_end', '<=', $end_date)
                   ->where('status',  'enable')
                   ->groupBy('date_start','date_end','id_group')
                   ->get()
                   ->filter(function ($session) use($type_room) {
                    return $this->mapGroupsSessions2($session,$type_room);
                    })->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
                } else {
                    $sessions = Session::
                    where('date_start', '>=', $start_date)
                   ->where('date_end', '<=', $end_date)
                   ->where('status',  'enable')
                   ->groupBy('date_start','date_end','id_group')
                   ->get()
                   ->filter(function ($session) use($type_room) {
                            return $this->mapGroupsSessions2($session,$type_room);
                        })
                        ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                        ->skip($start)->take($limit)
                        ->values()->all();
                }
            } else {
                $search = $request->input('search.value');
                if ($limit == -1) {
                    $sessions = Session::
                    where('date_start', '>=', $start_date)
                   ->where('date_end', '<=', $end_date)
                   ->where('status',  'enable')
                   ->groupBy('date_start','date_end','id_group')
                   ->get()
                   ->filter(function ($session) use($type_room) {
                            return $this->mapGroupsSessions2($session, $type_room);
                        })
                        ->filter(function ($session) use ($search, $columns, $request) {
                            return $this->filterSearchGroupsSessions($session, $search, $columns, $request);
                        })
                        ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
                } else {

                    $sessions = Session::
                    where('date_start', '>=', $start_date)
                   ->where('date_end', '<=', $end_date)
                   ->where('status',  'enable')
                   ->groupBy('date_start','date_end','id_group')
                   ->get()
                   ->filter(function ($session) use($type_room) {
                            return $this->mapGroupsSessions2($session,$type_room);
                        })
                        ->filter(function ($session) use ($search, $columns, $request) {
                            return $this->filterSearchGroupsSessions($session, $search, $columns, $request);
                        })
                        ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                        ->skip($start)->take($limit)
                        ->values()->all();
                }

                $totalFiltered = Session::
                where('date_start', '>=', $start_date)
               ->where('date_end', '<=', $end_date)
               ->where('status',  'enable')
               ->groupBy('date_start','date_end','id_group')
               ->get()
                ->filter(function ($sale) use ($search, $columns, $request) {
                        return $this->filterSearchGroupsSessions($sale, $search, $columns, $request);
                 })
                    ->count();
            }
        }

        $result = [
            'iTotalRecords'        =>  $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData'               =>  $sessions
        ];

        return $result;
    }

    function mapGroupsSessions($session)
    {
        $group=[];
 
      
        $group=Group::where('id',$session->id_group)->first();
        $room =Room::where('id', $group->id_room)->first();
        $group['room_group']=$room;
 

        $status = Pilates::getRealStatusGroupByNumFormatCalendar($session->id_group, $group['room_group'],$session->date_start, $session->date_end);

        $group['status'] = $status['num'];
        $group['status_format'] = $status['format'];

  
        $group['date_start']=$session->date_start;
        $group['date_end']=$session->date_end;
        $group['id_group']=$session->id_group;
        $group['room']=$group['room_group'];
        $group['room']=$group['room_group'];

        if($group['id_employee']!=null){
            $status= Pilates::getStatusEmployeeGroupBySessionGroupSetEmployee($group['id_employee'],$session->date_start,$session->date_end,false);
            $group['status_employee']=($group['id_employee']!=null)?$status:false;
        }else{
            $group['status_employee']=false;
        }



        if($group['status_employee']==true){
        return false;
        }else{
        $session['group'] = json_decode($group);
        $session['date'] = Carbon::createFromFormat('Y-m-d H:i:s', $session->date_start)->format('d/m/Y');
        $session['start_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $session->date_start)->format('H:i');
        $session['end_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $session->date_end)->format('H:i');
        $session['type'] = $group['room_group']['type_room'];
        $employeeTmp=null;
        $session['employee_name'] = 'Sin empleado';
        
        if($group['id_employee']!=null){
        $employeeTmp=Employee::where('id',$group['id_employee'])->first();
        $session['employee_name'] = $employeeTmp['name']." ".$employeeTmp['last_name'].", sin horario o su horario ha cambiado.";
        }
        $session['employee'] = $employeeTmp;
    

        return $session;
        }
     
    }


    function mapGroupsSessions2($session,$type_room)
    {
        $group=[];
 
      
        $group=Group::where('id',$session->id_group)->first();
        $room =Room::where('id', $group->id_room)->first();
        $group['room_group']=$room;
 

        $status = Pilates::getRealStatusGroupByNumFormatCalendar($session->id_group, $group['room_group'],$session->date_start, $session->date_end);

        $group['status'] = $status['num'];
        $group['status_format'] = $status['format'];

  
        $group['date_start']=$session->date_start;
        $group['date_end']=$session->date_end;
        $group['id_group']=$session->id_group;
        $group['room']=$group['room_group'];

        if($status['num']===1 || $type_room!=$group['room_group']['type_room']){

        return false;
        }else{
        $session['group'] = json_decode($group);
        $session['date'] = Carbon::createFromFormat('Y-m-d H:i:s', $session->date_start)->format('d/m/Y');
        $session['start_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $session->date_start)->format('H:i');
        $session['end_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $session->date_end)->format('H:i');
        $session['type'] = $group['room_group']['type_room'];
        return $session;
        }
     
    }




    function filterSearchGroupsSessions($session, $search, $columns, $request)
    {
        $item = false;
        //general
        foreach ($columns as $colum)
            if (stristr($session[$colum], $search))
                $item = $session;
        return $item;
    }
}
