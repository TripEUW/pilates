<?php

namespace App\Http\Controllers;

use App\Helpers\Pilates;
use App\Http\Requests\ValidationChangePassword;
use App\Http\Requests\ValidationUpdateProfileEmployee;
use App\Models\Employee;
use App\Models\Rol;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use PhpParser\Node\Stmt\TryCatch;

class EmployeeProfileController extends Controller
{

    public function index()
    {
        $employee = Employee::findOrFail(auth()->user()->id);
        $roles = Rol::orderBy('id')->pluck('name', 'id')->toArray();
        return view('profile_employee', compact('employee', 'roles'));
    }

    public function changePassword(ValidationChangePassword $request)
    {
        $id = $request->id;
        Employee::findOrFail($id)->update(
            [
                'password' => bcrypt($request->password)
            ]
        );

         /*auditoria: start*/Pilates::setAudit("Cambió su contraseña"); /*auditoria: end*/
        return redirect()->route('employee_profile')->with('success', 'La contraseña se actualizó con exitó y tendrá efecto la próxima vez que inicie sesión.');
    }


    public function update(ValidationUpdateProfileEmployee $request)
    {
        $id = $request->id;
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
            Employee::findOrFail($id)->update(
                [
                    'name' => $request->name,
                    'last_name' => $request->last_name,
                    'user_name' => $request->user_name,
                    'dni' => $request->dni,
                    'tel' => $request->tel,
                    'email' => $request->email,
                    'address' => $request->address,
                    'sex' => $request->sex,
                    'date_of_birth' => $request->date_of_birth,
                    'observation' => $request->observation,
                    'picture' => $request->picture
                ]
            );
        } else {
            Employee::findOrFail($id)->update(
                [
                    'name' => $request->name,
                    'last_name' => $request->last_name,
                    'user_name' => $request->user_name,
                    'dni' => $request->dni,
                    'tel' => $request->tel,
                    'email' => $request->email,
                    'address' => $request->address,
                    'sex' => $request->sex,
                    'date_of_birth' => $request->date_of_birth,
                    'observation' => $request->observation
                ]
            );
        }

    /*auditoria: start*/Pilates::setAudit("Actualizó la información de su perfil"); /*auditoria: end*/
        return redirect()->route('employee_profile')->with('success', 'Información actualizada con exitó.');
    }
}
