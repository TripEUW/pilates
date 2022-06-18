<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\RolModule;
use Illuminate\Http\Request;

class RolModuleController extends Controller
{


    public function store(Request $request)
    {
        if ($request->ajax()) {
            if ($request->input('state') == 1) {
                RolModule::create(['id_rol' => $request->input('id_rol'), 'id_module' => $request->input('id_module')]);
                 
                return response()->json(['response' => 'El rol se asignó correctamente.']);
            } else {
                RolModule::where('id_rol', $request->input('id_rol'))->where('id_module', $request->input('id_module'))->delete();
                
                return response()->json(['response' => 'El rol se elimino correctamente.']);
            }
        } else {
            abort(404);
        }
    }
}
