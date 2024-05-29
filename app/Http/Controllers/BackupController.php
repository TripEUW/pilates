<?php

namespace App\Http\Controllers;

use App\Console\Commands\BackupDataBaseDaily;
use App\Helpers\Pilates;
use App\Http\Requests\ValidationDeleteBackup;
use App\Http\Requests\ValidationRenameBackup;
use App\Http\Requests\ValidationRestoreBackupFile;
use App\Models\Backup;
use App\Models\Configuration;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

class BackupController extends Controller
{
    
    public function index()
    {
        return view('administration_backup');
    }


    public function create()
    {
       
    }

  
    public function store(Request $request)
    {
       
    }

  
    public function show($id)
    {
       
    }

   
    public function edit($id)
    {
        
    }

 
    public function update(Request $request, $id)
    {
       
    }

 
  
    public function destroy(ValidationDeleteBackup $request)
    {
        $config = Configuration::first();
        $pathForSaveBackup = (isset($config->path_backups_day)) ? $config->path_backups_day : config('backups.default_path_backups_day');
        $pathForSaveBackupFiles = (isset($config->path_gestor)) ? $config->path_gestor : config('backups.default_path_gestor');

        $errors = 0;
        $cantSuccsess = 0;
        $idsBackups = $request['id'];
        foreach ($idsBackups as $key => $id) {

            $backup=Backup::where('id', $id)->first();

            if(Storage::disk('dropbox')->has("$pathForSaveBackup/$backup->file_name"))
            Storage::disk('dropbox')->delete("$pathForSaveBackup/$backup->file_name");

            if(Storage::disk('public')->has("$pathForSaveBackup/$backup->file_name"))
            Storage::disk('public')->delete("$pathForSaveBackup/$backup->file_name");

            if ($backup->delete()) {
                $cantSuccsess++;
            } else {
                $errors++;
            }
        }

         /*auditoria: start*/Pilates::setAudit("Baja copia de seguridad ids:".implode(', ', $idsBackups)); /*auditoria: end*/
        return $cantSuccsess <= 1 ?
            redirect('administration_backup')->with('success', $cantSuccsess . ' copia de seguridad eliminada con éxito.')
            :
            redirect('administration_backup')->with('success', $cantSuccsess . ' copias de seguridad eliminadas con éxito.');
    }

    public function dataTable(Request $request)
    {
         
        $backup = new Backup();
        $backups = $backup->getBackupsDataTable($request);
        return response()->json($backups);
    }

    public function downloadBackup(Request $request,$id=false){

    $backup=Backup::where('id',$id)->firstOrFail();
   

    if(Storage::disk('dropbox')->has("$backup->path_dropbox/$backup->file_name") || Storage::disk('public')->has("$backup->path_public/$backup->file_name")){
  

     /*auditoria: start*/Pilates::setAudit("Descarga copia de seguridad id: $id"); /*auditoria: end*/
    if(Storage::disk('dropbox')->has("$backup->path_dropbox/$backup->file_name"))
    return Storage::disk('dropbox')->download("$backup->path_dropbox/$backup->file_name");
    if(Storage::disk('public')->has("$backup->path_public/$backup->file_name"))
    return Storage::disk('public')->download("$backup->path_public/$backup->file_name");

    }else{
    redirect('administration_backup')->with('La copia de seguridad no existe.');
    }

    }

    public function restoreBackupById(Request $request){

        $backup=Backup::where('id',$request->id)->firstOrFail();
        $config = Configuration::first();
      
        if(Storage::disk('dropbox')->has("$backup->path_dropbox/$backup->file_name") || Storage::disk('public')->has("$backup->path_public/$backup->file_name")){

            $fileBackup='';

            if(Storage::disk('dropbox')->has("$backup->path_dropbox/$backup->file_name"))
           $fileBackup = Storage::disk('dropbox')->url("$backup->path_dropbox/$backup->file_name");
           if(Storage::disk('public')->has("$backup->path_public/$backup->file_name"))
           $fileBackup = Storage::disk('public')->url("$backup->path_public/$backup->file_name");


        try { 
        if(DB::unprepared(file_get_contents($fileBackup))){
            // return redirect('administration_backup')->with('success','La copia se restauro con éxito.');
        }else{
            return redirect('administration_backup')->with('warning',"La copia de seguridad no pertenece a esta plataforma o está dañada.");
        }
        }catch(\Illuminate\Database\QueryException $ex){ 
        return redirect('administration_backup')->with('warning',$ex->getMessage());
        }
          
        $documents=Document::get()->all();
        $config = Configuration::first();
        $pathForSaveBackupFiles = (isset($config->path_gestor)) ? $config->path_gestor : config('backups.default_path_gestor');


        foreach ($documents as $key => $document) {
           
        if (!Storage::disk('public')->has($pathForSaveBackupFiles))
        Storage::disk('public')->makeDirectory($pathForSaveBackupFiles);

        if(Storage::disk('dropbox')->has("$pathForSaveBackupFiles/$document->front") && !empty($document->front)){
        $fileDoc = Storage::disk('dropbox')->get("$pathForSaveBackupFiles/$document->front");
        Storage::disk('public')->put("$pathForSaveBackupFiles/$document->front", $fileDoc);
        }
        if(Storage::disk('dropbox')->has("$pathForSaveBackupFiles/$document->back") && !empty($document->back)){
        $fileDoc = Storage::disk('dropbox')->get("$pathForSaveBackupFiles/$document->back");
        Storage::disk('public')->put("$pathForSaveBackupFiles/$document->back", $fileDoc);
        }

        }
        /*auditoria: start*/Pilates::setAudit("Restauración copia de seguridad id: $request->id"); /*auditoria: end*/
        return redirect('administration_backup')->with('success','La copia se restauro con éxito.');

        }else{
        return redirect('administration_backup')->with('danger','La copia de seguridad no existe.');
        }

    }

    function renameBackup(ValidationRenameBackup $request){
        $backup=Backup::where('id',$request->id)->firstOrFail();

        if(Storage::disk('dropbox')->has("$backup->path_dropbox/$backup->file_name")){
        Storage::disk('dropbox')->move("$backup->path_dropbox/$backup->file_name", "$backup->path_dropbox/$request->file_name.sql"); 
        }
        if(Storage::disk('public')->has("$backup->path_public/$backup->file_name")){
        Storage::disk('public')->move("$backup->path_public/$backup->file_name", "$backup->path_public/$request->file_name.sql"); 
        }
        /*auditoria: start*/Pilates::setAudit("Renombrar copia de seguridad id: $request->id"); /*auditoria: end*/
        $backup->update(['file_name'=>$request->file_name.".sql"]);

        return redirect('administration_backup')->with('success','La copia fue renombrada con éxito.');
    }

    function createBackupFull(){
        //Artisan::queue('store:backup_daily',['force_backup'=>true]);
        $response = Bus::dispatchNow(new BackupDataBaseDaily(true));


        if($response['status']=='2'){
            Log::info('2');
            return redirect('administration_backup')->with('warning',$response['response']);
        }else if($response['status']=='3'){
            Log::info('3');
            return redirect('administration_backup')->with('success',$response['response']);
        }else if($response['status']=='4'){
            Log::info('4');
            return redirect('administration_backup')->with('danger',$response['response']);
        }
     
    }

    

    function restoreBackupByFile(ValidationRestoreBackupFile $request){

        $name = $request->file('backup')->getClientOriginalName();
        $file =  $request->file("backup");

        try { 
        if(DB::unprepared(file_get_contents($file->getRealPath()))){
            //return redirect('administration_backup')->with('success','La copia se restauro con éxito.');
        }else{
            return redirect('administration_backup')->with('danger',"La copia de seguridad no pertenece a esta plataforma o está dañada.");
        }
        }catch(\Illuminate\Database\QueryException $ex){ 
        return redirect('administration_backup')->with('danger',$ex->getMessage());
        }

        $documents=Document::get()->all();
        $config = Configuration::first();
        $pathForSaveBackupFiles = (isset($config->path_gestor)) ? $config->path_gestor : config('backups.default_path_gestor');


        foreach ($documents as $key => $document) {
           
        if (!Storage::disk('public')->has($pathForSaveBackupFiles))
        Storage::disk('public')->makeDirectory($pathForSaveBackupFiles);

        if(Storage::disk('dropbox')->has("$pathForSaveBackupFiles/$document->front") && !empty($document->front)){
        $fileDoc = Storage::disk('dropbox')->get("$pathForSaveBackupFiles/$document->front");
        Storage::disk('public')->put("$pathForSaveBackupFiles/$document->front", $fileDoc);
        }
        if(Storage::disk('dropbox')->has("$pathForSaveBackupFiles/$document->back") && !empty($document->back)){
        $fileDoc = Storage::disk('dropbox')->get("$pathForSaveBackupFiles/$document->back");
        Storage::disk('public')->put("$pathForSaveBackupFiles/$document->back", $fileDoc);
        }

        }
       /*auditoria: start*/Pilates::setAudit("Restauración copia de seguridad desde un archivo"); /*auditoria: end*/
        return redirect('administration_backup')->with('success','La copia se restauro con éxito.');
    }
}
