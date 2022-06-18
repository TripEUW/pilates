<?php

namespace App\Http\Controllers;

use App\Helpers\Pilates;
use App\Http\Requests\ValidationDeleteRol;
use App\Http\Requests\ValidationRol;
use App\Models\Module;
use App\Models\Rol;
use Illuminate\Http\Request;

class RolController extends Controller
{

    public function index()
    {
        $roles = Rol::orderBy('id')->pluck('name', 'id')->toArray();
        $modules = Module::orderBy('id')->get();
        $rolModule = Rol::with('modules')->get()->pluck('modules', 'id')->toArray();
        //dd($rolMenu);


        return view('rol_and_permission', compact('roles', 'modules', 'rolModule'));
    }



    public function store(ValidationRol $request)
    {

      $rol=  Rol::create(['name' => mb_strtolower($request['name'])]);

          /*auditoria: start*/Pilates::setAudit("Alta rol id: $rol->id"); /*auditoria: end*/
        return redirect('rol_and_permission')->with('success', 'Rol creado con éxito.');
    }



    public function update(ValidationRol $request)
    {

        Rol::findOrFail($request['id'])->update($request->all());
         /*auditoria: start*/Pilates::setAudit("Actualización rol id: $request->id"); /*auditoria: end*/
        return redirect('rol_and_permission')->with('success', 'Rol actualizado con éxito.');
    }


    public function destroy(ValidationDeleteRol $request, $id)
    {

        if (Rol::destroy($id)) {
             /*auditoria: start*/Pilates::setAudit("Baja rol id: $id"); /*auditoria: end*/
            return redirect('rol_and_permission')->with('success', 'Rol eliminado con éxito.');
        } else {
            return redirect('rol_and_permission')->with('success', 'Rol no eliminado con éxito.');
        }
    }
}
