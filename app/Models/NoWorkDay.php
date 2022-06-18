<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Helpers\Pilates;
use Carbon\Carbon;

class NoWorkDay extends Model
{
    protected $table = 'no_work_day';
    protected $guarded = ['id'];

    
    public function getNoWorkDayDataTable(Request $request)
    {


        $columns = array(
            0 => 'id',
            1 => 'date',
            2 => 'description'
        );

        $totalData = NoWorkDay::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;


        $days = [];
        if (empty($request->input('search.value'))) {

            if ($limit == -1) {
                $days = NoWorkDay::get(['*'])->map(function ($day) {
                        return $this->mapDataTableNoWorkDays($day);
                    })->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {
                $days = NoWorkDay::get(['*'])->map(function ($day) {
                        return $this->mapDataTableNoWorkDays($day);
                    })
                    ->skip($start)->take($limit)
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            }
        } else {
            $search = $request->input('search.value');
            if ($limit == -1) {
                $days =  NoWorkDay::get(['*'])->map(function ($day) {
                        return $this->mapDataTableNoWorkDays($day);
                    })
                    ->filter(function ($day) use ($search, $columns, $request) {
                        return $this->filterSearchNoWorkDays($day, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {

                $days =  NoWorkDay::get(['*'])->map(function ($day) {
                        return $this->mapDataTableNoWorkDays($day);
                    })
                    ->filter(function ($day) use ($search, $columns, $request) {
                        return $this->filterSearchNoWorkDays($day, $search, $columns, $request);
                    })
                    ->skip($start)->take($limit)
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            }

            $totalFiltered = NoWorkDay::get(['*'])
                ->filter(function ($day) use ($search, $columns, $request) {
                    return $this->filterSearchNoWorkDays($day, $search, $columns, $request);
                })
                ->count();
        }

        $result = [
            'iTotalRecords'        =>  $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData'               =>  $days
        ];

        return $result;
    }

    function mapDataTableNoWorkDays($day)
    {
        $day->date=Carbon::createFromFormat('Y-m-d', $day->date)->format('d/m/Y');
        $day['actions']=json_decode($day);
        return $day;
    }

    function filterSearchNoWorkDays($day, $search, $columns, $request)
    {
        $item = false;
            //general
            foreach ($columns as $colum)
                if (stristr($day[$colum], $search))
                    $item = $day;
            return $item;
    }
}
