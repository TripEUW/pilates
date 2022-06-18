<?php

namespace App\Models;
use Illuminate\Http\Request;
use App\Helpers\Pilates;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Model;

class Backup extends Model
{
    protected $table = 'backup';
    protected $guarded = ['id'];

    
    public function getBackupsDataTable(Request $request)
    {

        $columns = array(
            0 => 'id',
            1 => 'description',
            2 => 'file_name',
            3 => 'date_create',
            4 => 'file_size',
            5 => 'status',
        );

        $totalData = Backup::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;


        $backups = [];
        if (empty($request->input('search.value'))) {

            if ($limit == -1) {
                $backups = Backup::get(['*'])->map(function ($backup) {
                        return $this->analizeFilterBackupDataTable($backup);
                    })->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {
                $backups = Backup::get(['*'])->map(function ($backup) {
                        return $this->analizeFilterBackupDataTable($backup);
                    })
                    ->skip($start)->take($limit)
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            }
        } else {
            $search = $request->input('search.value');
            if ($limit == -1) {
                $backups =  Backup::get(['*'])->map(function ($backup) {
                        return $this->analizeFilterBackupDataTable($backup);
                    })
                    ->filter(function ($backup) use ($search, $columns, $request) {
                        return $this->filterSearchBackupDataTable($backup, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {

                $backups =  Backup::get(['*'])->map(function ($backup) {
                        return $this->analizeFilterBackupDataTable($backup);
                    })
                    ->filter(function ($backup) use ($search, $columns, $request) {
                        return $this->filterSearchBackupDataTable($backup, $search, $columns, $request);
                    })
                    ->skip($start)->take($limit)
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            }

            $totalFiltered = Backup::get(['*'])
                ->filter(function ($backup) use ($search, $columns, $request) {
                    return $this->filterSearchBackupDataTable($backup, $search, $columns, $request);
                })
                ->count();
        }



        $result = [
            'iTotalRecords'        =>  $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData'               =>  $backups
        ];

        return $result;
    }

    function analizeFilterBackupDataTable($backup)
    {

        $backup->description="$backup->description";
        $backup->file_name=basename($backup->file_name, '.sql');
        $backup->file_size="$backup->file_size bytes";
        $backup['status_num']=$backup->status;
        $backup->status=Pilates::getStatusBackup($backup->status);
    
       $backup->date_create= Carbon::createFromFormat('Y-m-d H:i:s',  $backup->date_create)->format('d/m/Y');
        $backup->actions = json_decode($backup);
        return $backup;
    }

    function filterSearchBackupDataTable($backup, $search, $columns, $request)
    {
        $item = false;
            //general
            foreach ($columns as $colum)
                if (stristr($backup[$colum], $search))
                    $item = $backup;
            return $item;
    
    }
}
