<?php

namespace App\Http\Controllers;

use App\Helpers\Pilates;
use App\Http\Requests\ValidationDeleteEmployee;
use App\Http\Requests\ValidationEmployee;
use App\Models\Employee;
use App\Models\Rol;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;


class EmployeeController extends Controller
{

    public function index()
    {
        $roles = Rol::orderBy('id')->pluck('name', 'id')->toArray();
        return view('management_employee', compact('roles'));
    }


    public function store(ValidationEmployee $request)
    {
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
        return redirect('management_employee')->with('success', 'Empleado creado con éxito.<br>Puede proporcionar la contraseña creada para este empleado o el empleado puede restablecer la contraseña en el mail que se le ha enviado.');
    }


    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $employee["date_of_birth2"]=Carbon::createFromFormat('d/m/Y', $employee->date_of_birth)->format('Y-m-d');

        $roles = Rol::orderBy('id')->pluck('name', 'id')->toArray();
        return view('edit_employee', compact('employee', 'roles'));
    }


    public function update(ValidationEmployee $request, $id)
    {

        if ($request->hasFile('picture_upload')) {

            $ext = $request->file('picture_upload')->extension();
            $pictureObj =  $request->picture_upload;
            $actualPictureName = Employee::where('id', $id)->get(['picture']);
            $actualPictureName = $actualPictureName[0]->picture ?? null;
            if ($actualPictureName != null && $actualPictureName != "")
                Storage::disk('public')->delete("images/profiles/$actualPictureName");

            $pictureName = $id . time() . ".$ext";
            $pictureObj = Image::make($pictureObj)->encode($ext, 75);
            $pictureObj->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            });
            Storage::disk('public')->put("images/profiles/$pictureName", $pictureObj->stream());
            $request->request->add(['picture' =>  $pictureName]);
        }
   
           
            if(empty($request->password)){
            $request->request->remove('password');
            }else{
            $request->request->set('password',bcrypt($request->password));
            }
            Employee::findOrFail($id)->update(array_filter($request->except('password_confirmation', 'user_name', 'picture_upload')));

         /*auditoria: start*/Pilates::setAudit("Actualización empleado id: $id"); /*auditoria: end*/
       
        return redirect()->route('management_employee_edit', ['id' => $id])->with('success', 'Empleado actualizado con exitó');
    }



    public function destroy(ValidationDeleteEmployee $request)
    {
        $errors = 0;
        $cantSuccsess = 0;
        $idsEmployee = $request['id'];
        foreach ($idsEmployee as $key => $id) {

            $actualPictureName = Employee::where('id', $id)->get(['picture']);
            $actualPictureName = $actualPictureName[0]->picture ?? null;
            if ($actualPictureName != null && $actualPictureName != "")
                Storage::disk('public')->delete("images/profiles/$actualPictureName");

            if (Employee::where('id', $id)->delete()) {
                $cantSuccsess++;
            } else {
                $errors++;
            }
        }

         /*auditoria: start*/Pilates::setAudit("Baja empleado ids: ".implode(', ', $idsEmployee)); /*auditoria: end*/
        return $cantSuccsess <= 1 ?
            redirect('management_employee')->with('success', $cantSuccsess . ' empleado eliminado con éxito.')
            :
            redirect('management_employee')->with('success', $cantSuccsess . ' empleados eliminados con éxito.');
    }


    public function dataTable(Request $request)
    {

        $employee = new Employee();
        $employees = $employee->getEmployeeDataTable($request);
        return response()->json($employees);
    }
}
