<?php

namespace App\Models;

use App\Helpers\Pilates;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Group extends Model
{
    protected $table = 'group';
    protected $guarded = ['id'];

    public function getGroupDataTable(Request $request)
    {

        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'employee_name',
            3 => 'room_name',
            4 => 'status',
            5 => 'level',
            6 => 'observation'
        );


        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;
        $groups = [];

        //hire

        $totalData = Group::count();
        $totalFiltered = $totalData;

        if (empty($request->input('search.value'))) {

            if ($limit == -1) {
                $groups = Group::leftJoin('employee', 'employee.id', '=', 'group.id_employee')
                    ->join('room', 'room.id', '=', 'group.id_room')
                    ->leftJoin('session', 'session.id_group', '=', 'group.id')
                    ->distinct()
                    ->get([
                        'group.id',
                        'group.id as id_num',
                        'group.name',
                        DB::raw('(SELECT COUNT(id_group) FROM `session` WHERE `group`.`id`=session.id_group AND session.id_client IS NOT NULL) as n_sessions'),
                        'room.capacity as capacity_room',
                        'group.level',
                        'group.observation',
                        'group.id as actions',
                        'employee.id as employee_id',
                        DB::raw('CONCAT(employee.name," ",employee.last_name) as employee_name'),
                        'room.id as room_id',
                        'room.name as room_name',
                        'room.type_room'
                    ])->map(function ($group) use ($request) {
                        return $this->analizeMap0($group, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {

                $groups = Group::leftJoin('employee', 'employee.id', '=', 'group.id_employee')
                    ->join('room', 'room.id', '=', 'group.id_room')
                    ->leftJoin('session', 'session.id_group', '=', 'group.id')
                    ->distinct()
                    ->get([
                        'group.id',
                        'group.id as id_num',
                        'group.name',
                        DB::raw('(SELECT COUNT(id_group) FROM `session` WHERE `group`.`id`=session.id_group AND session.id_client IS NOT NULL) as n_sessions'),
                        'room.capacity as capacity_room',
                        'group.level',
                        'group.observation',
                        'group.id as actions',
                        'employee.id as employee_id',
                        DB::raw('CONCAT(employee.name," ",employee.last_name) as employee_name'),
                        'room.id as room_id',
                        'room.name as room_name',
                        'room.type_room'
                    ])->map(function ($group) use ($request) {
                        return $this->analizeMap0($group, $request);
                    })
                    ->skip($start)->take($limit)
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            }
        } else {
            $search = $request->input('search.value');
            if ($limit == -1) {


                $groups = Group::leftJoin('employee', 'employee.id', '=', 'group.id_employee')
                    ->join('room', 'room.id', '=', 'group.id_room')
                    ->leftJoin('session', 'session.id_group', '=', 'group.id')
                    ->distinct()
                    ->get([
                        'group.id',
                        'group.id as id_num',
                        'group.name',
                        DB::raw('(SELECT COUNT(id_group) FROM `session` WHERE `group`.`id`=session.id_group AND session.id_client IS NOT NULL) as n_sessions'),
                        'room.capacity as capacity_room',
                        'group.level',
                        'group.observation',
                        'group.id as actions',
                        'employee.id as employee_id',
                        DB::raw('CONCAT(employee.name," ",employee.last_name) as employee_name'),
                        'room.id as room_id',
                        'room.name as room_name',
                        'room.type_room'
                    ])->map(function ($group) use ($request) {
                        return $this->analizeMap0($group, $request);
                    })
                    ->filter(function ($group) use ($search, $columns, $request) {
                        return $this->filterSearchGroup($group, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {

                $groups = Group::leftJoin('employee', 'employee.id', '=', 'group.id_employee')
                    ->join('room', 'room.id', '=', 'group.id_room')
                    ->leftJoin('session', 'session.id_group', '=', 'group.id')
                    ->distinct()
                    ->get([
                        'group.id',
                        'group.id as id_num',
                        'group.name',
                        DB::raw('(SELECT COUNT(id_group) FROM `session` WHERE `group`.`id`=session.id_group AND session.id_client IS NOT NULL) as n_sessions'),
                        'room.capacity as capacity_room',
                        'group.level',
                        'group.observation',
                        'group.id as actions',
                        'employee.id as employee_id',
                        DB::raw('CONCAT(employee.name," ",employee.last_name) as employee_name'),
                        'room.id as room_id',
                        'room.name as room_name',
                        'room.type_room'
                    ])->map(function ($group) use ($request) {
                        return $this->analizeMap0($group, $request);
                    })
                    ->filter(function ($group) use ($search, $columns, $request) {
                        return $this->filterSearchGroup($group, $search, $columns, $request);
                    })
                    ->skip($start)->take($limit)
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            }

            $totalFiltered = Group::leftJoin('employee', 'employee.id', '=', 'group.id_employee')
                ->join('room', 'room.id', '=', 'group.id_room')
                ->leftJoin('session', 'session.id_group', '=', 'group.id')
                ->distinct()
                ->get()
                ->filter(function ($group) use ($search, $columns, $request) {
                    return $this->filterSearchGroup($group, $search, $columns, $request);
                })
                ->count();
        }


        $result = [
            'iTotalRecords'        =>  $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData'               =>  $groups
        ];

        return $result;
    }

    public function analizeMap0($group, $request)
    {

        $group->actions = [
            'name' => $group->name,
            'level' => $group->level,
            'id_group' => $group->id_num,
            'employee_id' => $group->employee_id,
            'employee_name' => $group->employee_name,
            'room_id' => $group->room_id,
            'room_name' => $group->room_name,
            'type_room' => $group->type_room,
            'capacity_room' => $group->capacity_room,
            'observation' => $group->observation
        ];
        $group['date_now'] = date('Y-m-d H:i:s');
        $group['status'] = Pilates::getRealStatusGroupGlobal($group->id_num, date('Y-m-d H:i:s'), 'Y-m-d H:i:s');
        return $group;
    }
    public function analizeMap($group, $request)
    {

        $group->actions = [
            'name' => $group->name,
            'level' => $group->level,
            'id_group' => $group->id_num,
            'employee_id' => $group->employee_id,
            'employee_name' => $group->employee_name,
            'room_id' => $group->room_id,
            'room_name' => $group->room_name,
            'type_room' => $group->type_room,
            'capacity_room' => $group->capacity_room,
            'observation' => $group->observation
        ];

        $dateStart = $request->date_start . ' ' . $request->timepicker_start;
        $dateEnd = $request->date_start . ' ' . $request->timepicker_end;

        $status = Pilates::getRealStatusGroup($group->id_num, $dateStart, $dateEnd, true,'Y-m-d H:i');
        $group['status'] = $status;
        return $group;
    }

    public function analizeMapTemplate($group, $request)
    {

        $group->actions = [
            'name' => $group->name,
            'level' => $group->level,
            'id_group' => $group->id_num,
            'employee_id' => $group->employee_id,
            'employee_name' => $group->employee_name,
            'room_id' => $group->room_id,
            'room_name' => $group->room_name,
            'type_room' => $group->type_room,
            'capacity_room' => $group->capacity_room,
            'observation' => $group->observation
        ];

    

        //$status = Pilates::getRealStatusGroup($group->id_num, $dateStart, $dateEnd, true,'Y-m-d H:i');
        $group['status'] = null;
        return $group;
    }
    ///

    public function getGroupDataTableForCalendar(Request $request)
    {

        $columns = array(
            0 => 'id_num',
            1 => 'name',
            2 => 'employee_name',
            3 => 'room_name',
            4 => 'type_room',
            5 => 'status',
            6 => 'level',
            7 => 'observation'
        );

        $totalData = 0;
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;
        $groups = [];

        //hire

        if (
            !empty($request->input('date_start'))
            &&
            !empty($request->input('timepicker_start'))
            &&
            !empty($request->input('timepicker_end'))
        ) {

            $totalData = Group::count();
            $totalFiltered = $totalData;

            if (empty($request->input('search.value'))) {

                if ($limit == -1) {
                    $groups = Group::leftJoin('employee', 'employee.id', '=', 'group.id_employee')
                        ->join('room', 'room.id', '=', 'group.id_room')
                        ->leftJoin('session', 'session.id_group', '=', 'group.id')
                        ->distinct()
                        ->get([
                            'group.id',
                            'group.id as id_num',
                            'group.name',
                            DB::raw('(SELECT COUNT(id_group) FROM `session` WHERE `group`.`id`=session.id_group AND session.id_client IS NOT NULL) as n_sessions'),
                            'room.capacity as capacity_room',
                            'group.level',
                            'group.observation',
                            'group.id as actions',
                            'employee.id as employee_id',
                            DB::raw('CONCAT(employee.name," ",employee.last_name) as employee_name'),
                            'room.id as room_id',
                            'room.name as room_name',
                            'room.type_room'
                        ])->map(function ($group) use ($request) {
                            return $this->analizeMap($group, $request);
                        })
                        ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
                } else {

                    $groups = Group::leftJoin('employee', 'employee.id', '=', 'group.id_employee')
                        ->join('room', 'room.id', '=', 'group.id_room')
                        ->leftJoin('session', 'session.id_group', '=', 'group.id')
                        ->distinct()
                        ->get([
                            'group.id',
                            'group.id as id_num',
                            'group.name',
                            DB::raw('(SELECT COUNT(id_group) FROM `session` WHERE `group`.`id`=session.id_group AND session.id_client IS NOT NULL) as n_sessions'),
                            'room.capacity as capacity_room',
                            'group.level',
                            'group.observation',
                            'group.id as actions',
                            'employee.id as employee_id',
                            DB::raw('CONCAT(employee.name," ",employee.last_name) as employee_name'),
                            'room.id as room_id',
                            'room.name as room_name',
                            'room.type_room'
                        ])->map(function ($group) use ($request) {
                            return $this->analizeMap($group, $request);
                        })
                        ->skip($start)->take($limit)
                        ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
                }
            } else {
                $search = $request->input('search.value');
                if ($limit == -1) {


                    $groups = Group::leftJoin('employee', 'employee.id', '=', 'group.id_employee')
                        ->join('room', 'room.id', '=', 'group.id_room')
                        ->leftJoin('session', 'session.id_group', '=', 'group.id')
                        ->distinct()
                        ->get([
                            'group.id',
                            'group.id as id_num',
                            'group.name',
                            DB::raw('(SELECT COUNT(id_group) FROM `session` WHERE `group`.`id`=session.id_group AND session.id_client IS NOT NULL) as n_sessions'),
                            'room.capacity as capacity_room',
                            'group.level',
                            'group.observation',
                            'group.id as actions',
                            'employee.id as employee_id',
                            DB::raw('CONCAT(employee.name," ",employee.last_name) as employee_name'),
                            'room.id as room_id',
                            'room.name as room_name',
                            'room.type_room'
                        ])->map(function ($group) use ($request) {
                            return $this->analizeMap($group, $request);
                        })
                        ->filter(function ($group) use ($search, $columns, $request) {
                            return $this->filterSearchGroup($group, $search, $columns, $request);
                        })
                        ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
                } else {

                    $groups = Group::leftJoin('employee', 'employee.id', '=', 'group.id_employee')
                        ->join('room', 'room.id', '=', 'group.id_room')
                        ->leftJoin('session', 'session.id_group', '=', 'group.id')
                        ->distinct()
                        ->get([
                            'group.id',
                            'group.id as id_num',
                            'group.name',
                            DB::raw('(SELECT COUNT(id_group) FROM `session` WHERE `group`.`id`=session.id_group AND session.id_client IS NOT NULL) as n_sessions'),
                            'room.capacity as capacity_room',
                            'group.level',
                            'group.observation',
                            'group.id as actions',
                            'employee.id as employee_id',
                            DB::raw('CONCAT(employee.name," ",employee.last_name) as employee_name'),
                            'room.id as room_id',
                            'room.name as room_name',
                            'room.type_room'
                        ])->map(function ($group) use ($request) {
                            return $this->analizeMap($group, $request);
                        })
                        ->filter(function ($group) use ($search, $columns, $request) {
                            return $this->filterSearchGroup($group, $search, $columns, $request);
                        })
                        ->skip($start)->take($limit)
                        ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
                }

                $totalFiltered = Group::leftJoin('employee', 'employee.id', '=', 'group.id_employee')
                    ->join('room', 'room.id', '=', 'group.id_room')
                    ->leftJoin('session', 'session.id_group', '=', 'group.id')
                    ->distinct()
                    ->get()
                    ->filter(function ($group) use ($search, $columns, $request) {
                        return $this->filterSearchGroup($group, $search, $columns, $request);
                    })
                    ->count();
            }
        }

        $result = [
            'iTotalRecords'        =>  $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData'               =>  $groups
        ];

        return $result;
    }

    function getGroupDataTableForCalendarTemplate(Request $request)
    {

        $columns = array(
            0 => 'id_num',
            1 => 'name',
            2 => 'employee_name',
            3 => 'room_name',
            4 => 'type_room',
            5 => 'status',
            6 => 'level',
            7 => 'observation'
        );

        $totalData = 0;
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;
        $groups = [];

        //hire

            $totalData = Group::count();
            $totalFiltered = $totalData;

            if (empty($request->input('search.value'))) {

                if ($limit == -1) {
                    $groups = Group::leftJoin('employee', 'employee.id', '=', 'group.id_employee')
                        ->join('room', 'room.id', '=', 'group.id_room')
                        ->leftJoin('session', 'session.id_group', '=', 'group.id')
                        ->distinct()
                        ->get([
                            'group.id',
                            'group.id as id_num',
                            'group.name',
                            DB::raw('(SELECT COUNT(id_group) FROM `session` WHERE `group`.`id`=session.id_group AND session.id_client IS NOT NULL) as n_sessions'),
                            'room.capacity as capacity_room',
                            'group.level',
                            'group.observation',
                            'group.id as actions',
                            'employee.id as employee_id',
                            DB::raw('CONCAT(employee.name," ",employee.last_name) as employee_name'),
                            'room.id as room_id',
                            'room.name as room_name',
                            'room.type_room'
                        ])->map(function ($group) use ($request) {
                            return $this->analizeMapTemplate($group, $request);
                        })
                        ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
                } else {

                    $groups = Group::leftJoin('employee', 'employee.id', '=', 'group.id_employee')
                        ->join('room', 'room.id', '=', 'group.id_room')
                        ->leftJoin('session', 'session.id_group', '=', 'group.id')
                        ->distinct()
                        ->get([
                            'group.id',
                            'group.id as id_num',
                            'group.name',
                            DB::raw('(SELECT COUNT(id_group) FROM `session` WHERE `group`.`id`=session.id_group AND session.id_client IS NOT NULL) as n_sessions'),
                            'room.capacity as capacity_room',
                            'group.level',
                            'group.observation',
                            'group.id as actions',
                            'employee.id as employee_id',
                            DB::raw('CONCAT(employee.name," ",employee.last_name) as employee_name'),
                            'room.id as room_id',
                            'room.name as room_name',
                            'room.type_room'
                        ])->map(function ($group) use ($request) {
                            return $this->analizeMapTemplate($group, $request);
                        })
                        ->skip($start)->take($limit)
                        ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
                }
            } else {
                $search = $request->input('search.value');
                if ($limit == -1) {


                    $groups = Group::leftJoin('employee', 'employee.id', '=', 'group.id_employee')
                        ->join('room', 'room.id', '=', 'group.id_room')
                        ->leftJoin('session', 'session.id_group', '=', 'group.id')
                        ->distinct()
                        ->get([
                            'group.id',
                            'group.id as id_num',
                            'group.name',
                            DB::raw('(SELECT COUNT(id_group) FROM `session` WHERE `group`.`id`=session.id_group AND session.id_client IS NOT NULL) as n_sessions'),
                            'room.capacity as capacity_room',
                            'group.level',
                            'group.observation',
                            'group.id as actions',
                            'employee.id as employee_id',
                            DB::raw('CONCAT(employee.name," ",employee.last_name) as employee_name'),
                            'room.id as room_id',
                            'room.name as room_name',
                            'room.type_room'
                        ])->map(function ($group) use ($request) {
                            return $this->analizeMapTemplate($group, $request);
                        })
                        ->filter(function ($group) use ($search, $columns, $request) {
                            return $this->filterSearchGroup($group, $search, $columns, $request);
                        })
                        ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
                } else {

                    $groups = Group::leftJoin('employee', 'employee.id', '=', 'group.id_employee')
                        ->join('room', 'room.id', '=', 'group.id_room')
                        ->leftJoin('session', 'session.id_group', '=', 'group.id')
                        ->distinct()
                        ->get([
                            'group.id',
                            'group.id as id_num',
                            'group.name',
                            DB::raw('(SELECT COUNT(id_group) FROM `session` WHERE `group`.`id`=session.id_group AND session.id_client IS NOT NULL) as n_sessions'),
                            'room.capacity as capacity_room',
                            'group.level',
                            'group.observation',
                            'group.id as actions',
                            'employee.id as employee_id',
                            DB::raw('CONCAT(employee.name," ",employee.last_name) as employee_name'),
                            'room.id as room_id',
                            'room.name as room_name',
                            'room.type_room'
                        ])->map(function ($group) use ($request) {
                            return $this->analizeMapTemplate($group, $request);
                        })
                        ->filter(function ($group) use ($search, $columns, $request) {
                            return $this->filterSearchGroup($group, $search, $columns, $request);
                        })
                        ->skip($start)->take($limit)
                        ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
                }

                $totalFiltered = Group::leftJoin('employee', 'employee.id', '=', 'group.id_employee')
                    ->join('room', 'room.id', '=', 'group.id_room')
                    ->leftJoin('session', 'session.id_group', '=', 'group.id')
                    ->distinct()
                    ->get()
                    ->filter(function ($group) use ($search, $columns, $request) {
                        return $this->filterSearchGroup($group, $search, $columns, $request);
                    })
                    ->count();
            }
        

        $result = [
            'iTotalRecords'        =>  $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData'               =>  $groups
        ];

        return $result;
    }


    function filterSearchGroup($group, $search, $columns, $request)
    {
        $item = false;
        //general
        foreach ($columns as $colum)
            if (stristr($group[$colum], $search))
                $item = $group;
        return $item;
    }
}
