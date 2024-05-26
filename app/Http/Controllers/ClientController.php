<?php

namespace App\Http\Controllers;

use App\Helpers\Pilates;
use App\Http\Requests\ValidationClient;
use App\Http\Requests\ValidationDeleteClient;
use App\Http\Requests\ValidationDocument;
use App\Models\Client;
use App\Models\Configuration;
use App\Models\Document;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::all();
        return response()->view('management_client', ['clients' => $clients]);
    }

    public function store(ValidationClient $request)
    {
        $request->merge(['date_register' => now()]);

        $client = Client::create(array_filter($request->except('user_name', 'picture_upload', 'name_document', 'front', 'back', 'observation_document')));
        if ($request->hasFile('picture_upload')) {
            $ext = $request->file('picture_upload')->extension();
            $pictureObj = $request->picture_upload;

            $pictureName = $client->id . time() . ".$ext";
            $pictureObj = Image::make($pictureObj)->encode($ext, 75);
            $pictureObj->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            });
            Storage::disk('public')->put("images/profiles/clients/$pictureName", $pictureObj->stream());
            Client::findOrFail($client->id)->update(['picture' => $pictureName]);
        }

        if ($request->input('name_document')) {
            $config = Configuration::first();
            $pathForSaveBackupFiles = isset($config->path_gestor) ? $config->path_gestor : config('backups.default_path_gestor');

            $document = Document::create(['name' => $request['name_document'], 'date_update' => date('Ymd'), 'id_client' => $client->id, 'observation' => $request->observation_document]);

            if ($request->hasFile('front')) {
                $ext = $request->file('front')->extension();
                $frontDocument = $request->file('front');
                $documentName = $client->id . $document->id . '_front' . ".$ext";
                Storage::disk('public')->put("$pathForSaveBackupFiles/$documentName", file_get_contents($frontDocument->getRealPath()));
                Document::findOrFail($document->id)->update(['front' => $documentName, 'type_front' => $ext]);
            }
            if ($request->hasFile('back')) {
                $ext = $request->file('back')->extension();
                $backDocument = $request->file('back');
                $documentName = $client->id . $document->id . '_back' . ".$ext";
                Storage::disk('public')->put("$pathForSaveBackupFiles/$documentName", file_get_contents($backDocument->getRealPath()));
                Document::findOrFail($document->id)->update(['back' => $documentName, 'type_back' => $ext]);
            }
        }
        /*auditoria: start*/
        Pilates::setAudit("Alta cliente id: $client->id"); /*auditoria: end*/
        return redirect('management_client')->with('success', 'Cliente creado con éxito.');
    }

    public function addDocument(ValidationDocument $request)
    {
        $config = Configuration::first();
        $pathForSaveBackupFiles = isset($config->path_gestor) ? $config->path_gestor : config('backups.default_path_gestor');

        $document = Document::create(['name' => $request['name_document'], 'date_update' => date('Ymd'), 'id_client' => $request->id_client, 'observation' => $request->observation_document]);

        if ($request->hasFile('front')) {
            $ext = $request->file('front')->extension();
            $frontDocument = $request->file('front');
            $documentName = $request->id_client . $document->id . '_front' . ".$ext";
            Storage::disk('public')->put("$pathForSaveBackupFiles/$documentName", file_get_contents($frontDocument->getRealPath()));
            Document::findOrFail($document->id)->update(['front' => $documentName, 'type_front' => $ext]);
        }
        if ($request->hasFile('back')) {
            $ext = $request->file('back')->extension();
            $backDocument = $request->file('back');
            $documentName = $request->id_client . $document->id . '_back' . ".$ext";
            Storage::disk('public')->put("$pathForSaveBackupFiles/$documentName", file_get_contents($backDocument->getRealPath()));
            Document::findOrFail($document->id)->update(['back' => $documentName, 'type_back' => $ext]);
        }

        /*auditoria: start*/
        Pilates::setAudit("Alta documento id: $document->id"); /*auditoria: end*/
        return redirect('management_client')->with('success', 'Documento agregado al historial del cliente con éxito.');
    }

    public function update(ValidationClient $request)
    {
        $id = $request->input('id');

        if ($request->hasFile('picture_upload')) {
            $ext = $request->file('picture_upload')->extension();
            $pictureObj = $request->picture_upload;
            $actualPictureName = Client::where('id', $id)->get(['picture']);
            $actualPictureName = $actualPictureName[0]->picture ?? null;
            if ($actualPictureName != null && $actualPictureName != '') {
                Storage::disk('public')->delete("images/profiles/clients/$actualPictureName");
            }

            $pictureName = $id . time() . ".$ext";
            $pictureObj = Image::make($pictureObj)->encode($ext, 75);
            $pictureObj->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            });
            Storage::disk('public')->put("images/profiles/clients/$pictureName", $pictureObj->stream());
            $request->request->add(['picture' => $pictureName]);
        }

        Client::findOrFail($id)->update(array_filter($request->except('user_name', 'picture_upload')));
        if ($request->sessions_machine == 0) {
            Client::findOrFail($id)->update(['sessions_machine' => 0]);
        }

        if ($request->sessions_floor == 0) {
            Client::findOrFail($id)->update(['sessions_floor' => 0]);
        }

        if ($request->sessions_individual == 0) {
            Client::findOrFail($id)->update(['sessions_individual' => 0]);
        }

        /*auditoria: start*/
        Pilates::setAudit("Actualización cliente id: $id"); /*auditoria: end*/
        return redirect()->route('management_client')->with('success', 'Cliente actualizado con exitó');
    }
    public function dataTable(Request $request)
    {
        $client = new Client();
        $clients = $client->getClientDataTable($request);
        return response()->json($clients);
    }
    public function destroy(ValidationDeleteClient $request)
    {
        $config = Configuration::first();
        $pathForSaveBackupFiles = isset($config->path_gestor) ? $config->path_gestor : config('backups.default_path_gestor');

        $errors = 0;
        $cantSuccsess = 0;
        $idsClients = $request['id'];
        foreach ($idsClients as $key => $id) {
            $actualPictureName = Client::where('id', $id)->get(['picture']);
            $actualPictureName = $actualPictureName[0]->picture ?? null;

            if ($actualPictureName != null && $actualPictureName != '') {
                Storage::disk('public')->delete("images/profiles/clients/$actualPictureName");
            }

            $actualDocuments = Document::where('id_client', $id)->get();
            foreach ($actualDocuments as $document) {
                if ($document->front != null && $document->front != '') {
                    Storage::disk('public')->delete("$pathForSaveBackupFiles/$document->front");
                }
                if ($document->back != null && $document->back != '') {
                    Storage::disk('public')->delete("$pathForSaveBackupFiles/$document->back");
                }
            }

            if (Client::where('id', $id)->delete()) {
                $cantSuccsess++;
            } else {
                $errors++;
            }
        }

        /*auditoria: start*/
        Pilates::setAudit('Baja cliente ids: ' . implode(', ', $idsClients)); /*auditoria: end*/
        return $cantSuccsess <= 1 ? redirect('management_client')->with('success', $cantSuccsess . ' cliente eliminado con éxito') : redirect('management_client')->with('success', $cantSuccsess . ' clientes eliminados con éxito');
    }
}
