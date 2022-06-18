<?php

namespace App\Http\Controllers;

use App\Helpers\Pilates;
use App\Http\Requests\ValidationDeleteGroup;
use App\Http\Requests\ValidationDeleteRoom;
use App\Http\Requests\ValidationGroup;
use App\Http\Requests\ValidationRoom;
use App\Models\Employee;
use App\Models\Group;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomAndGroupController extends Controller
{

    public function index()
    {
        return view("room_and_group");
    }


    public function storeRoom(ValidationRoom $request)
    {
       $room= Room::create($request->all());
         /*auditoria: start*/Pilates::setAudit("Alta sala id: $room->id"); /*auditoria: end*/
        return redirect('management_room_group')->with('success', 'Sala creada con éxito.');
    }

    public function storeGroup(ValidationGroup $request)
    {
        $group=Group::create(array_filter($request->except('group_name_employee', 'group_name_room')));
        /*auditoria: start*/Pilates::setAudit("Alta grupo id: $group->id"); /*auditoria: end*/
        return redirect('management_room_group')->with('success', 'Grupo creado con éxito.');
    }


    public function updateRoom(ValidationRoom $request)
    {

        $id = $request->id;
        Room::findOrFail($id)->update($request->all());
        /*auditoria: start*/Pilates::setAudit("Actualización sala id: $id"); /*auditoria: end*/
        return redirect()->route('management_room_group')->with('success', 'Sala actualizada con exitó.');
    }

    public function updateGroup(ValidationGroup $request)
    {

        $id = $request->id;
        Group::findOrFail($id)->update(array_filter($request->except('group_name_employee', 'group_name_room')));
        /*auditoria: start*/Pilates::setAudit("Actualización grupo id: $id"); /*auditoria: end*/
        return redirect()->route('management_room_group')->with('success', 'Grupo actualizado con exitó.');
    }


    public function destroyRoom(ValidationDeleteRoom $request)
    {

        $errors = 0;
        $cantSuccsess = 0;
        $idsRoom = $request['id'];
        foreach ($idsRoom as $key => $id) {

            if (Room::where('id', $id)->delete()) {
                $cantSuccsess++;
            } else {
                $errors++;
            }
        }

          /*auditoria: start*/Pilates::setAudit("Baja sala ids: ".implode(', ', $idsRoom)); /*auditoria: end*/
        return $cantSuccsess <= 1 ?
            redirect('management_room_group')->with('success', $cantSuccsess . ' sala eliminada con éxito.')
            :
            redirect('management_room_group')->with('success', $cantSuccsess . ' salas eliminadas con éxito.');
    }

    public function destroyGroup(ValidationDeleteGroup $request)
    {

        $errors = 0;
        $cantSuccsess = 0;
        $idsGroup = $request['id'];
        foreach ($idsGroup as $key => $id) {
            if (Group::where('id', $id)->delete()) {
                $cantSuccsess++;
            } else {
                $errors++;
            }
        }

        
          /*auditoria: start*/Pilates::setAudit("Baja grupo ids: ".implode(', ', $idsGroup)); /*auditoria: end*/
        return $cantSuccsess <= 1 ?
            redirect('management_room_group')->with('success', $cantSuccsess . ' grupo eliminado con éxito.')
            :
            redirect('management_room_group')->with('success', $cantSuccsess . ' grupos eliminados con éxito.');
    }




    public function dataTableRoom(Request $request)
    {
        $room = new Room();
        $rooms = $room->getRoomDataTable($request);
        return response()->json($rooms);
    }

    public function dataTableEmployeeSelected(Request $request)
    {
        $employee = new Employee();
        $employees = $employee->getEmployeeSelectedDataTable($request);
        return response()->json($employees);
    }

    public function dataTableRoomSelected(Request $request)
    {
        $room = new Room();
        $rooms = $room->getRoomSelectedDataTable($request);
        return response()->json($rooms);
    }

    public function dataTableGroup(Request $request)
    {
        $group = new Group();
        $groups = $group->getGroupDataTable($request);
        return response()->json($groups);
    }
}
