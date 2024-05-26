<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Helpers\Pilates;
use Carbon\Carbon;
use DateTime;

class Document extends Model
{
    protected $table = 'document';
    protected $guarded = ['id'];
    protected $routeDocs='';

    public function getCreatedAtAttribute($date)
    {
        try {
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d/m/Y');
        } catch (\Carbon\Exceptions\InvalidFormatException $e) {
            error_log("Error al crear la fecha en el Modelo Document getCreatedAt: " . $e->getMessage());
        }
        return $date;
    }
    public function getUpdatedAtAttribute($date)
    {
        try {
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d/m/Y');
        } catch (\Carbon\Exceptions\InvalidFormatException $e) {
            error_log("Error al crear la fecha en el modelo Document getUpdatedAtAtrribute: " . $e->getMessage());
        }
        return $date;
    }
    public function getDocumentsDataTable(Request $request)
    {

        $config = Configuration::first();
        $this->routeDocs= $pathForSaveBackupFiles = (isset($config->path_gestor)) ? $config->path_gestor : config('backups.default_path_gestor');

        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'created_at',
            3 => 'updated_at',
            4 => 'observation',
        );
        $idClient = $request->input('id_client');
        $totalData = Document::where("id_client", $idClient)->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;



        $documents = [];
        if (empty($request->input('search.value'))) {

            if ($limit == -1) {
                $documents = Document::where("id_client", $idClient)
                    ->get(['*'])->map(function ($doc) {
                        return $this->analizeFilterDocumentDataTable($doc);
                    })->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {
                $documents = Document::where("id_client", $idClient)
                    ->get(['*'])->map(function ($doc) {
                        return $this->analizeFilterDocumentDataTable($doc);
                    })
                    ->skip($start)->take($limit)
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            }
        } else {
            $search = $request->input('search.value');
            if ($limit == -1) {
                $documents =  Document::where("id_client", $idClient)
                    ->get(['*'])->map(function ($doc) {
                        return $this->analizeFilterDocumentDataTable($doc);
                    })
                    ->filter(function ($doc) use ($search, $columns, $request) {
                        return $this->filterSearchDocumentDataTable($doc, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {

                $documents =  Document::where("id_client", $idClient)
                    ->get(['*'])->map(function ($doc) {
                        return $this->analizeFilterDocumentDataTable($doc);
                    })
                    ->filter(function ($doc) use ($search, $columns, $request) {
                        return $this->filterSearchDocumentDataTable($doc, $search, $columns, $request);
                    })
                    ->skip($start)->take($limit)
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            }

            $totalFiltered = Document::where("id_client", $idClient)
                ->get(['*'])
                ->filter(function ($doc) use ($search, $columns, $request) {
                    return $this->filterSearchDocumentDataTable($doc, $search, $columns, $request);
                })
                ->count();
        }

        $result = [
            'iTotalRecords'        =>  $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData'               =>  $documents
        ];

        return $result;
    }

    function analizeFilterDocumentDataTable($document)
    {

        $routePublicImages = asset("assets/images/");
        $routeDocuments = asset("storage/{$this->routeDocs}");

        $document["routeDocuments"] = $routeDocuments;
        $document["routePublicImages"] = $routePublicImages;

        $document["document"] = json_decode($document);
        $document["actions"] = json_decode($document);
        return $document;
    }

    function filterSearchDocumentDataTable($doc, $search, $columns, $request)
    {
        $item = false;
        //general
        foreach ($columns as $colum)
            if (stristr($doc[$colum], $search))
                $item = $doc;
        return $item;
    }
}
