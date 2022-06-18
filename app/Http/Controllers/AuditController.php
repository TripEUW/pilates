<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidationDownloadAudit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return view('audit');
    }

    public function download(ValidationDownloadAudit $request){

        $name="auditoria_".Carbon::createFromFormat('Y-m-d',$request->date)->format('Ymd').".log";
        $tmpPath="auditorias";
     return  Storage::disk('public')->download("$tmpPath/$name");

        //return redirect('audit')->with('success', 'Descarga con éxito.');
    }
    
}
