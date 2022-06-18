<?php

namespace App\Http\Controllers;

use App\Helpers\Pilates;
use App\Http\Requests\ValidationCompressAll;
use App\Http\Requests\ValidationCompressByClient;
use App\Http\Requests\ValidationCompressByClients;
use App\Http\Requests\ValidationDeleteDocument;
use App\Http\Requests\ValidationDocument;
use App\Http\Requests\ValidationDocumentEdit;
use App\Models\Client;
use App\Models\Configuration;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class MedicalHistoryController extends Controller
{

    public function index()
    {
        return view("medical_history");
    }


   

    public function updateDocument(ValidationDocumentEdit $request)
    {
        $document = Document::where('id', $request['id'])->firstOrFail();

        Document::findOrFail($request['id'])->update(['name' => $request->name_document, 'observation' => $request->observation, 'date_update' => date("Ymd")]);

        $config = Configuration::first();
        $pathForSaveBackupFiles = (isset($config->path_gestor)) ? $config->path_gestor : config('backups.default_path_gestor');


        if ($request->hasFile('front')) {
            Storage::disk('public')->delete("$pathForSaveBackupFiles/$document->front");

            $ext = $request->file('front')->extension();
            $frontDocument =  $request->file('front');
            $documentName = $document->id_client . $document->id . "_front" . ".$ext";
            Storage::disk('public')->put("$pathForSaveBackupFiles/$documentName", file_get_contents($frontDocument->getRealPath()));
            Document::findOrFail($document->id)->update(['front' => $documentName, 'type_front' => $ext]);
        }

        if (!empty($request->delete_back)) {
            if ($request->delete_back == 'true') {
                Storage::disk('public')->delete("$pathForSaveBackupFiles/$document->back");
                Document::findOrFail($document->id)->update(['back' => null, 'type_back' => null]);
            }
        }

        if ($request->hasFile('back')) {
            Storage::disk('public')->delete("$pathForSaveBackupFiles/$document->back");
            $ext = $request->file('back')->extension();
            $backDocument =  $request->file("back");
            $documentName = $document->id_client . $document->id . "_back" . ".$ext";
            Storage::disk('public')->put("$pathForSaveBackupFiles/$documentName", file_get_contents($backDocument->getRealPath()));
            Document::findOrFail($document->id)->update(['back' => $documentName, 'type_back' => $ext]);
        }

         /*auditoria: start*/Pilates::setAudit("Actualización documento id: $document->id"); /*auditoria: end*/
        return redirect('medical_history')->with('success', 'Documento actualizado con éxito.');
    }



    public function addDocument(ValidationDocument $request)
    {

        $config = Configuration::first();
        $pathForSaveBackupFiles = (isset($config->path_gestor)) ? $config->path_gestor : config('backups.default_path_gestor');

        $document = Document::create(['name' => $request['name_document'], 'date_update' => date("Ymd"), 'id_client' => $request->id_client, 'observation' => $request->observation]);

        if ($request->hasFile('front')) {
            $ext = $request->file('front')->extension();
            $frontDocument =  $request->file('front');
            $documentName = $request->id_client . $document->id . "_front" . ".$ext";
            Storage::disk('public')->put("$pathForSaveBackupFiles/$documentName", file_get_contents($frontDocument->getRealPath()));
            Document::findOrFail($document->id)->update(['front' => $documentName, 'type_front' => $ext]);
        }
        if ($request->hasFile('back')) {
            $ext = $request->file('back')->extension();
            $backDocument =  $request->file("back");
            $documentName = $request->id_client . $document->id . "_back" . ".$ext";
            Storage::disk('public')->put("$pathForSaveBackupFiles/$documentName", file_get_contents($backDocument->getRealPath()));
            Document::findOrFail($document->id)->update(['back' => $documentName, 'type_back' => $ext]);
        }



          /*auditoria: start*/Pilates::setAudit("Alta documento id:$document->id"); /*auditoria: end*/
        return redirect('medical_history')->with('success', 'Documento agregado al historial del cliente con éxito.');
    }


    public function destroyDocument(ValidationDeleteDocument $request)
    {
        $config = Configuration::first();
        $pathForSaveBackupFiles = (isset($config->path_gestor)) ? $config->path_gestor : config('backups.default_path_gestor');

        $errors = 0;
        $cantSuccsess = 0;
        $idsDocument = $request['id'];
        foreach ($idsDocument as $key => $id) {

            $actualDocumentFront = Document::where('id', $id)->get(['front']);
            $actualDocumentFront = $actualDocumentFront[0]->front ?? null;
            if ($actualDocumentFront != null && $actualDocumentFront != "")
                Storage::disk('public')->delete("$pathForSaveBackupFiles/$actualDocumentFront");

            $actualDocumentBack = Document::where('id', $id)->get(['back']);
            $actualDocumentBack = $actualDocumentBack[0]->back ?? null;
            if ($actualDocumentBack != null && $actualDocumentBack != "")
                Storage::disk('public')->delete("$pathForSaveBackupFiles/$actualDocumentBack");

            if (Document::where('id', $id)->delete()) {
                $cantSuccsess++;
            } else {
                $errors++;
            }
        }

        /*auditoria: start*/Pilates::setAudit("Baja documento ids: ".implode(', ', $idsDocument)); /*auditoria: end*/
        return $cantSuccsess <= 1 ?
            redirect('medical_history')->with('success', $cantSuccsess . ' documento eliminado con éxito.')
            :
            redirect('medical_history')->with('success', $cantSuccsess . ' documentos eliminados con éxito.');
    }


    public function getDocuments(Request $request)
    {
        $config = Configuration::first();
        $pathForSaveBackupFiles = (isset($config->path_gestor)) ? $config->path_gestor : config('backups.default_path_gestor');

        if ($request->ajax()) {
            $routePublicImages = asset("assets/images/");
            $routeDocuments = asset("storage/$pathForSaveBackupFiles");

            $documents = Document::where("id_client", $request->id)->get()->all();
            return response()->json([
                'success' =>
                'Documentos cargados con éxito',
                'error' => false,
                'documents' => $documents,
                'routePublicImages' => $routePublicImages,
                'routeDocuments' => $routeDocuments
            ]);
        } else {
            abort(404);
        }
    }

    public function dataTableDocuments(Request $request)
    {
        $document = new Document();
        $documents = $document->getDocumentsDataTable($request);
        return response()->json($documents);
    }

    public function compressAll(ValidationCompressAll $request)
    {

        $clients = Client::get();

        if ($clients->count() <= 0)
            return redirect('medical_history')->with('warning', 'Por el momento no hay ningún documento para descargar.');

        $config = Configuration::first();
        $pathForSaveBackupFiles = (isset($config->path_gestor)) ? $config->path_gestor : config('backups.default_path_gestor');

        $storagePath = "$pathForSaveBackupFiles";
        $public_dir =  Storage::disk('public')->getDriver()->getAdapter()->getPathPrefix()."$storagePath/";
        $zipFileName = ($request->title ?? date("Y-m-d")) . ".zip";

        $zip = new ZipArchive;
        if ($zip->open($public_dir . $zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {

            foreach ($clients as $client) {

                $folder = str_replace(' ', '_', ($client->name . " " . $client->last_name)) . '/';
                $documents = Document::where('id_client', $client->id)->get();
                foreach ($documents as $document) {
                    if ($document->back) {
                        $zip->addFile($public_dir . $document->back, $folder . $document->back);
                        if ($request->pass && $request->pass_repeat)
                            $zip->setEncryptionName($folder . $document->back, ZipArchive::EM_AES_256, $request->pass); //Add file name and password dynamically
                    }
                    if ($document->front) {
                        $zip->addFile($public_dir . $document->front, $folder . $document->front);
                        if ($request->pass && $request->pass_repeat)
                            $zip->setEncryptionName($folder . $document->front, ZipArchive::EM_AES_256, $request->pass); //Add file name and password dynamically
                    }
                }
            }
            $zip->close();
        }
        $headers = array(
            'Content-Type' => 'application/octet-stream',
        );
        $filetopath = $public_dir . $zipFileName;

        if (file_exists($filetopath)) {
              /*auditoria: start*/Pilates::setAudit("Descarga historial comprimido"); /*auditoria: end*/
            return response()->download($filetopath, $zipFileName, $headers)->deleteFileAfterSend(true);
        }
        
      
        return redirect('medical_history')->with('warning', 'Ningún documento que comprimir.');
    }

    public function compressByClients(ValidationCompressByClients $request)
    {

        $clientsIds = $request['id'];

        $config = Configuration::first();
        $pathForSaveBackupFiles = (isset($config->path_gestor)) ? $config->path_gestor : config('backups.default_path_gestor');

        $storagePath = "$pathForSaveBackupFiles";
        $public_dir =  Storage::disk('public')->getDriver()->getAdapter()->getPathPrefix()."$storagePath/";
        $zipFileName = ($request->title ?? date("Y-m-d")) . ".zip";

        $zip = new ZipArchive;
        if ($zip->open($public_dir . $zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {

            foreach ($clientsIds as $id) {
                $client = Client::where("id", $id)->get()->first();
                $folder = str_replace(' ', '_', ($client->name . " " . $client->last_name)) . '/';
                $documents = Document::where('id_client', $client->id)->get();
                foreach ($documents as $document) {
                    if ($document->back) {
                        $zip->addFile($public_dir . $document->back, $folder . $document->back);
                        if ($request->pass && $request->pass_repeat)
                            $zip->setEncryptionName($folder . $document->back, ZipArchive::EM_AES_256, $request->pass); //Add file name and password dynamically
                    }
                    if ($document->front) {
                        $zip->addFile($public_dir . $document->front, $folder . $document->front);
                        if ($request->pass && $request->pass_repeat)
                            $zip->setEncryptionName($folder . $document->front, ZipArchive::EM_AES_256, $request->pass); //Add file name and password dynamically
                    }
                }
            }
            $zip->close();
        }
        $headers = array(
            'Content-Type' => 'application/octet-stream',
        );
        $filetopath = $public_dir . $zipFileName;

        if (file_exists($filetopath)) {
             /*auditoria: start*/Pilates::setAudit("Descarga historial comprimido"); /*auditoria: end*/
            return response()->download($filetopath, $zipFileName, $headers)->deleteFileAfterSend(true);
        }
        
        return redirect('medical_history')->with('warning', 'Ningún documento para comprimir.');
    }

    public function compressByClient(ValidationCompressByClient $request)
    {

        $documentsIds = $request['id'];

        $config = Configuration::first();
        $pathForSaveBackupFiles = (isset($config->path_gestor)) ? $config->path_gestor : config('backups.default_path_gestor');

        $storagePath = "$pathForSaveBackupFiles";
        $public_dir =  Storage::disk('public')->getDriver()->getAdapter()->getPathPrefix()."$storagePath/";
        $zipFileName = ($request->title ?? date("Y-m-d")) . ".zip";

        $zip = new ZipArchive;
        if ($zip->open($public_dir . $zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {


            foreach ($documentsIds as $id) {
                $document = Document::where('id', $id)->get()->first();
                if ($document->back) {
                    $zip->addFile($public_dir . $document->back, $document->back);
                    if ($request->pass && $request->pass_repeat)
                        $zip->setEncryptionName($document->back, ZipArchive::EM_AES_256, $request->pass); //Add file name and password dynamically
                }
                if ($document->front) {
                    $zip->addFile($public_dir . $document->front, $document->front);
                    if ($request->pass && $request->pass_repeat)
                        $zip->setEncryptionName($document->front, ZipArchive::EM_AES_256, $request->pass); //Add file name and password dynamically
                }
            }


            $zip->close();
        }
        $headers = array(
            'Content-Type' => 'application/octet-stream',
        );
        $filetopath = $public_dir . $zipFileName;

        if (file_exists($filetopath)) {
             /*auditoria: start*/Pilates::setAudit("Descarga historial comprimido"); /*auditoria: end*/
            return response()->download($filetopath, $zipFileName, $headers)->deleteFileAfterSend(true);
        }
        return redirect('medical_history')->with('warning', 'Ningún documento que comprimir.');
    }
}
