<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Room extends Model
{
    //
    protected $table = 'room';
    protected $guarded = ['id'];



    public function getRoomDataTable(Request $request)
    {



        $columns = array(
            0 => 'id',
            1 => 'id',
            2 => 'name',
            3 => 'type_room',
            4 => 'capacity',
        );

        $totalData = Room::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $rooms = [];
        if (empty($request->input('search.value'))) {

            if ($limit == -1) {
                $rooms = Room::orderBy($order, $dir)
                    ->get([
                        'id',
                        'id as id_num',
                        'name',
                        'type_room',
                        'capacity',
                        'observation',
                        'id as actions'
                    ])->map(function ($room) {
                        $room->actions = [
                            'id' => $room->id,
                            'type_room' => $room->type_room,
                            'name' => $room->name,
                            'observation' => $room->observation,
                            'capacity' => $room->capacity
                        ];
                        return $room;
                    });
            } else {

                $rooms = Room::orderBy($order, $dir)
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get([
                        'id',
                        'id as id_num',
                        'name',
                        'type_room',
                        'capacity',
                        'observation',
                        'id as actions'
                    ])->map(function ($room) {
                        $room->actions = [
                            'id' => $room->id,
                            'type_room' => $room->type_room,
                            'name' => $room->name,
                            'observation' => $room->observation,
                            'capacity' => $room->capacity
                        ];
                        return $room;
                    });
            }
        } else {
            $search = $request->input('search.value');
            if ($limit == -1) {


                $rooms = Room::orderBy($order, $dir)
                    //->where('id','LIKE',"%{$search}%")
                    ->Where('name', 'LIKE', "%{$search}%")
                    ->orWhere('type_room', 'LIKE', "%{$search}%")
                    ->orWhere('capacity', 'LIKE', "%{$search}%")
                    ->orWhere('observation', 'LIKE', "%{$search}%")
                    ->get([
                        'id',
                        'id as id_num',
                        'name',
                        'type_room',
                        'capacity',
                        'observation',
                        'id as actions'
                    ])->map(function ($room) {
                        $room->actions = [
                            'id' => $room->id,
                            'type_room' => $room->type_room,
                            'name' => $room->name,
                            'observation' => $room->observation,
                            'capacity' => $room->capacity
                        ];
                        return $room;
                    });
            } else {



                $rooms = Room::orderBy($order, $dir)
                    // ->where('id','LIKE',"%{$search}%")
                    ->Where('name', 'LIKE', "%{$search}%")
                    ->orWhere('type_room', 'LIKE', "%{$search}%")
                    ->orWhere('capacity', 'LIKE', "%{$search}%")
                    ->orWhere('observation', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get([
                        'id',
                        'id as id_num',
                        'name',
                        'type_room',
                        'capacity',
                        'observation',
                        'id as actions'
                    ])->map(function ($room) {
                        $room->actions = [
                            'id' => $room->id,
                            'type_room' => $room->type_room,
                            'name' => $room->name,
                            'observation' => $room->observation,
                            'capacity' => $room->capacity
                        ];
                        return $room;
                    });
            }

            $totalFiltered = Room::orderBy($order, $dir)
                //->where('id','LIKE',"%{$search}%")
                ->Where('name', 'LIKE', "%{$search}%")
                ->orWhere('type_room', 'LIKE', "%{$search}%")
                ->orWhere('capacity', 'LIKE', "%{$search}%")
                ->orWhere('observation', 'LIKE', "%{$search}%")
                ->count();
        }




        $result = [
            'iTotalRecords'        =>  $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData'               =>  $rooms
        ];

        return $result;
    }




    public function getRoomSelectedDataTable(Request $request)
    {



        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'type_room',
            3 => 'capacity',
            4 => 'observation',
        );

        $totalData = Room::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $rooms = [];
        if (empty($request->input('search.value'))) {

            if ($limit == -1) {
                $rooms = Room::orderBy($order, $dir)
                    ->get([
                        'id',
                        'name',
                        'type_room',
                        'capacity',
                        'observation',
                        'id as actions'
                    ])->map(function ($room) {
                        $room->actions = [
                            'id' => $room->id,
                            'type_room' => $room->type_room,
                            'name' => $room->name,
                            'capacity' => $room->capacity
                        ];
                        return $room;
                    });
            } else {

                $rooms = Room::orderBy($order, $dir)
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get([
                        'id',
                        'name',
                        'type_room',
                        'capacity',
                        'observation',
                        'id as actions'
                    ])->map(function ($room) {
                        $room->actions = [
                            'id' => $room->id,
                            'type_room' => $room->type_room,
                            'name' => $room->name,
                            'capacity' => $room->capacity
                        ];
                        return $room;
                    });
            }
        } else {
            $search = $request->input('search.value');
            if ($limit == -1) {


                $rooms = Room::orderBy($order, $dir)
                    //->where('id','LIKE',"%{$search}%")
                    ->Where('name', 'LIKE', "%{$search}%")
                    ->orWhere('type_room', 'LIKE', "%{$search}%")
                    ->orWhere('capacity', 'LIKE', "%{$search}%")
                    ->orWhere('observation', 'LIKE', "%{$search}%")
                    ->get([
                        'id',
                        'name',
                        'type_room',
                        'capacity',
                        'observation',
                        'id as actions'
                    ])->map(function ($room) {
                        $room->actions = [
                            'id' => $room->id,
                            'type_room' => $room->type_room,
                            'name' => $room->name,
                            'capacity' => $room->capacity
                        ];
                        return $room;
                    });
            } else {



                $rooms = Room::orderBy($order, $dir)
                    // ->where('id','LIKE',"%{$search}%")
                    ->Where('name', 'LIKE', "%{$search}%")
                    ->orWhere('type_room', 'LIKE', "%{$search}%")
                    ->orWhere('capacity', 'LIKE', "%{$search}%")
                    ->orWhere('observation', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get([
                        'id',
                        'name',
                        'type_room',
                        'capacity',
                        'observation',
                        'id as actions'
                    ])->map(function ($room) {
                        $room->actions = [
                            'id' => $room->id,
                            'type_room' => $room->type_room,
                            'name' => $room->name,
                            'capacity' => $room->capacity
                        ];
                        return $room;
                    });
            }

            $totalFiltered = Room::orderBy($order, $dir)
                //->where('id','LIKE',"%{$search}%")
                ->Where('name', 'LIKE', "%{$search}%")
                ->orWhere('type_room', 'LIKE', "%{$search}%")
                ->orWhere('capacity', 'LIKE', "%{$search}%")
                ->orWhere('observation', 'LIKE', "%{$search}%")
                ->count();
        }




        $result = [
            'iTotalRecords'        =>  $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData'               =>  $rooms
        ];

        return $result;
    }

    public function getNameAttribute($name)
    { //Accessors and mutators
        return ucwords($name);
    }
    public function getTypeRoomAttribute($typeRoom)
    { //Accessors and mutators
        return ucwords($typeRoom);
    }
}
