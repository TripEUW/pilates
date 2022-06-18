<?php

namespace App\Http\Controllers;

use App\Helpers\Pilates;
use App\Http\Requests\ValidationDeleteNoWorkDay;
use App\Http\Requests\ValidationFiscalData;
use App\Http\Requests\ValidationNoWorkDay;
use App\Http\Requests\ValidationPathBackupsdb;
use App\Http\Requests\ValidationPathDocumentaryManager;
use App\Models\Configuration;
use App\Models\NoWorkDay;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConfigController extends Controller
{

    public function index()
    {
        $config = Configuration::first();
        return view("administration_config", compact('config'));
    }


    public function updateStatusModuleAssitances(Request $request){
        $configuration = Configuration::first();

        if (empty($configuration)) {
            Configuration::create($request->all());
            /*auditoria: start*/Pilates::setAudit("Actualización configuración modulo de asistencias"); /*auditoria: end*/
            return response()->json(['response' => "Configuración guardada con éxito", 'status' => true]);
        } else {
            Configuration::findOrFail($configuration->id)->update($request->all());
            /*auditoria: start*/Pilates::setAudit("Actualización configuración modulo de asistencias"); /*auditoria: end*/
            return response()->json(['response' => "Configuración guardada con éxito", 'status' => true]);
        }
        return response()->json(['response' => "Ocurrió un error, intente de nuevo.absolute-container", 'status' => false]);

    }
    public function updateFiscalData(ValidationFiscalData $request)
    {

        $configuration = Configuration::first();

        if (empty($configuration)) {
            Configuration::create($request->all());
             /*auditoria: start*/Pilates::setAudit("Actualización configuración de datos fiscales"); /*auditoria: end*/
            return redirect('administration_config')->with('success', 'Configuración guardada con éxito');
        } else {
            Configuration::findOrFail($configuration->id)->update($request->all());
            /*auditoria: start*/Pilates::setAudit("Actualización configuración de datos fiscales"); /*auditoria: end*/
            return redirect('administration_config')->with('success', 'Configuración guardada con éxito');
        }
        return redirect('administration_config');
    }
    public function updateDocumentaryManagerData(ValidationPathDocumentaryManager $request)
    {

        $configuration = Configuration::first();

        $pathForSaveBackupFiles = (isset($configuration->path_gestor)) ? $configuration->path_gestor : config('backups.default_path_gestor');

        if (empty($configuration)) {
            Configuration::create($request->all());
            
            $files = Storage::disk('public')->files($pathForSaveBackupFiles);

            if (!Storage::disk('public')->has($request->path_gestor))
            Storage::disk('public')->makeDirectory($request->path_gestor);

            foreach ($files as $file) {
            $nameFile = basename($file);
            if(!Storage::exists("$request->path_gestor/$nameFile"))
            Storage::disk('public')->move($file,"$request->path_gestor/$nameFile");
            }
            /*auditoria: start*/Pilates::setAudit("Actualización configuración de gestor documental"); /*auditoria: end*/
            return redirect('administration_config')->with('success', 'Configuración guardada con éxito');
        } else {
            $files = Storage::disk('public')->files($pathForSaveBackupFiles);

            if (!Storage::disk('public')->has($request->path_gestor))
            Storage::disk('public')->makeDirectory($request->path_gestor);

            foreach ($files as $file) {
            $nameFile = basename($file);
            if(!Storage::exists("$request->path_gestor/$nameFile"))
            Storage::disk('public')->move($file,"$request->path_gestor/$nameFile");
            }

            Configuration::findOrFail($configuration->id)->update($request->all());
            /*auditoria: start*/Pilates::setAudit("Actualización configuración de gestor documental"); /*auditoria: end*/
            return redirect('administration_config')->with('success', 'Configuración guardada con éxito');
        }

        return redirect('administration_config');
    }
    public function updateBackupsPathData(ValidationPathBackupsdb $request)
    {

        $configuration = Configuration::first();

        if (empty($configuration)) {
            Configuration::create($request->all());
                /*auditoria: start*/Pilates::setAudit("Actualización configuración copias de seguridad"); /*auditoria: end*/
            return redirect('administration_config')->with('success', 'Configuración guardada con éxito');
        } else {
            Configuration::findOrFail($configuration->id)->update($request->all());
              /*auditoria: start*/Pilates::setAudit("Actualización configuración copias de seguridad"); /*auditoria: end*/
            return redirect('administration_config')->with('success', 'Configuración guardada con éxito');
        }
        return redirect('administration_config');
    }

    public function dataTableNoWorkDays(Request $request)
    {
        $day = new NoWorkDay();
        $days = $day->getNoWorkDayDataTable($request);
        return response()->json($days);
    }

    public function storeNoWorkDay(ValidationNoWorkDay $request){
        $request->merge(['date' => Carbon::createFromFormat('d/m/Y',$request->date)->format('Y-m-d')]);
        NoWorkDay::create($request->all());
        /*auditoria: start*/Pilates::setAudit("Alta día festivo"); /*auditoria: end*/
        return redirect('administration_config')->with('success', 'Día festivo agregado con éxito');

    }

    public function destroyNoWorkDay(ValidationDeleteNoWorkDay $request){
        $errors = 0;
        $cantSuccsess = 0;
        $idsDays = $request['id'];
        foreach ($idsDays as $key => $id) {

            if (NoWorkDay::where('id', $id)->delete()) {
                $cantSuccsess++;
            } else {
                $errors++;
            }
        }
         /*auditoria: start*/Pilates::setAudit("Baja día festivo ids: ".implode(', ', $idsDays)); /*auditoria: end*/
        return $cantSuccsess <= 1 ?
            redirect('administration_config')->with('success', $cantSuccsess . ' día eliminado con éxito.')
            :
            redirect('administration_config')->with('success', $cantSuccsess . ' días eliminados con éxito.');
    }

    public function editNoWorkDay(ValidationNoWorkDay $request)
    {
        
        $request->merge(['date' => Carbon::createFromFormat('d/m/Y',$request->date)->format('Y-m-d')]);

        NoWorkDay::findOrFail($request['id'])->update($request->all());
        /*auditoria: start*/Pilates::setAudit("Actualización día festivo id: ".$request['id']); /*auditoria: end*/
        return redirect('administration_config')->with('success', 'Actualizado con éxito.');
    }

    public function checkStatusHideAttr(Request $request){

        return response()->json(['response' => "Ahora pude ver los campos protegidos", 'status' => true]);

    }

    
}
