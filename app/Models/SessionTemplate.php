<?php

namespace App\Models;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class SessionTemplate extends Model
{
    protected $table = 'session_template';
    protected $guarded= ['id'];

    
    public function getSessionsGroupForCalendar(Request $request)
    {


        $columns = array(
            0 => 'id_session',
            1 => 'last_name',
            2 => 'name',
            3 => 'level',
            4 => 'observation'
        );



        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;

        $sessions = [];

        $totalData = 0;
        $totalFiltered = $totalData;

        //hire

        if (
            !empty($request->input('day'))
            &&
            !empty($request->input('id_template'))
            &&
            !empty($request->input('timepicker_start'))
            &&
            !empty($request->input('timepicker_end'))
            &&
            !empty($request->input('group_selected'))
        ) {

            $dateStart = $request->timepicker_start;
            $dateEnd = $request->timepicker_end;
            $group_selected = $request->group_selected;
            $day = $request->day;
            $id_template= $request->id_template;

            $start_date = DateTime::createFromFormat('H:i', $dateStart)->format('H:i:s');
            $end_date = DateTime::createFromFormat('H:i', $dateEnd)->format('H:i:s');

            $totalData = SessionTemplate::leftJoin('client', 'session_template.id_client', '=', 'client.id')
                ->where('session_template.start', '=', $start_date)
                ->where('session_template.end', '=', $end_date)
                ->where('session_template.id_group', $group_selected)
                ->where('session_template.day', $day)
                ->where('session_template.id_template', $id_template)
                ->whereNotNull('session_template.id_client')
                ->get([
                    '*', 'session_template.id as id_session', 'session_template.observation as observation'
                ])->map(function ($session) {
                    return $this->analizeFilterSessionsGroup($session);
                })->count();

            $totalFiltered = $totalData;

            if (empty($request->input('search.value'))) {

                if ($limit == -1) {
                    $sessions = SessionTemplate::leftJoin('client', 'session_template.id_client', '=', 'client.id')
                    ->where('session_template.start', '=', $start_date)
                    ->where('session_template.end', '=', $end_date)
                    ->where('session_template.id_group', $group_selected)
                    ->where('session_template.day', $day)
                    ->where('session_template.id_template', $id_template)
                    ->whereNotNull('session_template.id_client')
                        ->get([
                            '*', 'session_template.id as id_session', 'session_template.observation as observation'
                        ])->map(function ($session) {
                            return $this->analizeFilterSessionsGroup($session);
                        })->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
                } else {
                    $sessions = SessionTemplate::leftJoin('client', 'session_template.id_client', '=', 'client.id')
                    ->where('session_template.start', '=', $start_date)
                    ->where('session_template.end', '=', $end_date)
                    ->where('session_template.id_group', $group_selected)
                    ->where('session_template.day', $day)
                    ->where('session_template.id_template', $id_template)
                    ->whereNotNull('session_template.id_client')
                        ->get([
                            '*', 'session_template.id as id_session', 'session_template.observation as observation'
                        ])->map(function ($session) {
                            return $this->analizeFilterSessionsGroup($session);
                        })
                        ->skip($start)->take($limit)
                        ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
                }
            } else {
                $search = $request->input('search.value');
                if ($limit == -1) {
                    $sessions = SessionTemplate::leftJoin('client', 'session_template.id_client', '=', 'client.id')
                    ->where('session_template.start', '=', $start_date)
                    ->where('session_template.end', '=', $end_date)
                    ->where('session_template.id_group', $group_selected)
                    ->where('session_template.day', $day)
                    ->where('session_template.id_template', $id_template)
                    ->whereNotNull('session_template.id_client')
                        ->get([
                            '*', 'session_template.id as id_session', 'session_template.observation as observation'
                        ])->map(function ($session) {
                            return $this->analizeFilterSessionsGroup($session);
                        })
                        ->filter(function ($session) use ($search, $columns, $request) {
                            return $this->filterSearchSessionsDataTable($session, $search, $columns, $request);
                        })
                        ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
                } else {

                    $sessions = SessionTemplate::leftJoin('client', 'session_template.id_client', '=', 'client.id')
                    ->where('session_template.start', '=', $start_date)
                    ->where('session_template.end', '=', $end_date)
                    ->where('session_template.id_group', $group_selected)
                    ->where('session_template.day', $day)
                    ->where('session_template.id_template', $id_template)
                    ->whereNotNull('session_template.id_client')
                        ->get([
                            '*', 'session_template.id as id_session', 'session_template.observation as observation'
                        ])->map(function ($session) {
                            return $this->analizeFilterSessionsGroup($session);
                        })
                        ->filter(function ($session) use ($search, $columns, $request) {
                            return $this->filterSearchSessionsDataTable($session, $search, $columns, $request);
                        })
                        ->skip($start)->take($limit)
                        ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
                }

                $totalFiltered = SessionTemplate::leftJoin('client', 'session_template.id_client', '=', 'client.id')
                ->where('session_template.start', '=', $start_date)
                ->where('session_template.end', '=', $end_date)
                ->where('session_template.id_group', $group_selected)
                ->where('session_template.day', $day)
                ->where('session_template.id_template', $id_template)
                ->whereNotNull('session_template.id_client')
                    ->get([
                        '*', 'session_template.id as id_session', 'session_template.observation as observation'
                    ])
                    ->filter(function ($sale) use ($search, $columns, $request) {
                        return $this->filterSearchSessionsDataTable($sale, $search, $columns, $request);
                    })
                    ->count();
            }
        }

        $result = [
            'iTotalRecords'        =>  $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData'               =>  $sessions
        ];

        return $result;
    }

    function analizeFilterSessionsGroup($session)
    {

        $session['actions'] = json_decode($session);

        return $session;
    }

    function filterSearchSessionsDataTable($session, $search, $columns, $request)
    {
        $item = false;
        //general
        foreach ($columns as $colum)
            if (stristr($session[$colum], $search))
                $item = $session;
        return $item;
    }
}
