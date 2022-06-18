<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Helpers\Pilates;


class Notification extends Model
{
    protected $table='notification';
    protected $guarded= ['id'];

    public function getNotificationsDataTable(Request $request,$id,$id_rol)
    {


        $columns = array(
            0 => 'id',
            1 => 'title',
            2 => 'message',
            3 => 'date'
        );

        $totalData = Notification::where('cod_receiver',$id)->where('type_receiver',$id_rol)->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;


        $notifications = [];
        if (empty($request->input('search.value'))) {

            if ($limit == -1) {
                $notifications = Notification::get(['*'])->where('cod_receiver',$id)->where('type_receiver',$id_rol)->map(function ($notification) {
                        return $this->mapDataTableNotifications($notification);
                    })->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {
                $notifications = Notification::get(['*'])->where('cod_receiver',$id)->where('type_receiver',$id_rol)->map(function ($notification) {
                        return $this->mapDataTableNotifications($notification);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($start)->take($limit)
                    ->values()->all();
            }
        } else {
            $search = $request->input('search.value');
            if ($limit == -1) {
                $notifications =  Notification::get(['*'])->where('cod_receiver',$id)->where('type_receiver',$id_rol)->map(function ($notification) {
                        return $this->mapDataTableNotifications($notification);
                    })
                    ->filter(function ($notification) use ($search, $columns, $request) {
                        return $this->filterSearchNotifications($notification, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {

                $notifications =  Notification::get(['*'])->where('cod_receiver',$id)->where('type_receiver',$id_rol)->map(function ($notification) {
                        return $this->mapDataTableNotifications($notification);
                    })
                    ->filter(function ($notification) use ($search, $columns, $request) {
                        return $this->filterSearchNotifications($notification, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($start)->take($limit)
                    ->values()->all();
            }

            $totalFiltered = Notification::get(['*'])
                ->where('cod_receiver',$id)->where('type_receiver',$id_rol)
                ->filter(function ($notification) use ($search, $columns, $request) {
                    return $this->filterSearchNotifications($notification, $search, $columns, $request);
                })
                ->count();
        }

        $result = [
            'iTotalRecords'        =>  $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData'               =>  $notifications
        ];

        return $result;
    }

    function mapDataTableNotifications($notification)
    {
        $notification->path=route($notification->path);
        $notification['notification']=json_decode($notification);
        $notification['actions']=$notification->id;
        return   $notification;
    }

    function filterSearchNotifications($notification, $search, $columns, $request)
    {
        $item = false;
            //general
            foreach ($columns as $colum)
                if (stristr($notification[$colum], $search))
                    $item = $notification;
            return $item;
    }
}
