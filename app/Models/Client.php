<?php

namespace App\Models;

use App\Helpers\Pilates;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'client';
    protected $guarded = ['id'];



    public function getNameAttribute($name)
    { //Accessors and mutators
        return ucwords($name);
    }
    public function getLastNameAttribute($lastName)
    { //Accessors and mutators
        return ucwords($lastName);
    }
    public function setEmailAttribute($email)
    {
        $this->attributes['email'] = mb_strtolower($email);
    }

    public function setNameAttribute($name)
    { //Accessors and mutators
        $this->attributes['name'] = mb_convert_case(mb_strtolower($name), MB_CASE_TITLE, 'UTF-8');
    }
    public function setLastNameAttribute($lastName)
    { //Accessors and mutators
        $this->attributes['last_name'] = mb_convert_case(mb_strtolower($lastName), MB_CASE_TITLE, 'UTF-8');
    }
    public function getCreatedAtAttribute($date)
    {
    return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d/m/Y');
    }
    public function getDateRegisterAttribute($date)
    {
    return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d/m/Y');
    }
    public function getDateOfBirthAttribute($date)
    {
    return Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
    }


    public function getClientDataTable(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'last_name',
            2 => 'name',
            3 => 'suscription',
            4 => 'tel',
            5 => 'level',
            6 => 'sex',
            7 => 'email',
            8 => 'status',
            9 => 'address',
            10 => 'date_of_birth',
            11 => 'date_register',
            12 => 'observation',
            13 => 'sessions_machine',
            14 => 'sessions_floor',
            15 => 'sessions_individual'
        );

        $totalData = Client::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;

        $clients = [];
        if (empty($request->input('search.value'))) {

            if ($limit == -1) {
                $clients = Client::get([
                        '*'
                    ])->map(function ($client) {
                        return $this->analizeFilterClientDataTable($client);
                    })->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {
                $clients = Client::get([
                        '*'
                    ])->map(function ($client) {
                        return  $this->analizeFilterClientDataTable($client);
                    })
                    ->skip($start)->take($limit)
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            }
        } else {
            $search = $request->input('search.value');
            if ($limit == -1) {
                $clients =  Client::get([
                        '*'
                    ])->map(function ($client) {
                        return   $this->analizeFilterClientDataTable($client);
                    })
                    ->filter(function ($client) use ($search, $columns) {
                        $item = false;
                        foreach ($columns as $colum)
                            if (stristr($client[$colum], $search))
                                $item = $client;
                        return $item;
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {

                $clients =  Client::get([
                        '*'
                    ])->map(function ($client) {
                        return $this->analizeFilterClientDataTable($client);
                    })
                    ->filter(function ($client) use ($search, $columns) {
                        $item = false;
                        foreach ($columns as $colum)
                            if (stristr($client[$colum], $search))
                                $item = $client;
                        return $item;
                    })
                    ->skip($start)->take($limit)
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            }

            $totalFiltered = Client::get(['*'])
                ->filter(function ($client) use ($search, $columns) {
                    $item = false;
                    foreach ($columns as $colum)
                        if (stristr($client[$colum], $search))
                            $item = $client;
                    return $item;
                })
                ->count();
        }



        $result = [
            'iTotalRecords'        =>  $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData'               =>  $clients
        ];

        return $result;
    }

    function analizeFilterClientDataTable($client)
    {
        $client->actions = json_decode($client);
        $client->sex = ($client->sex == "male") ? "Masculino" : "Femenino";
        $realBalance = Pilates::getRealBalance($client->id);
        $client->sessions_machine = $realBalance['sessions_machine'];
        $client->sessions_floor = $realBalance['sessions_floor'];
        $client->sessions_individual = $realBalance['sessions_individual'];
        $client->suscription = ($client->suscription=="true")?"Activa":"Inactiva";

        $sessionsClient = Session::where('id_client', $client->id)->get(['id'])->count();
        $balance = Pilates::getRealBalance($client->id);
        $status = "";
        if ($sessionsClient > 0)
            $status = "<span class='status-green p-1'>Asignado</span>";
        if ($sessionsClient <= 0)
            $status = "<span class='status-blue p-1'>Pendiente</span>";
        if ($balance['sessions_machine'] <= 0 && $balance['sessions_floor'] <= 0  && $balance['sessions_individual'] <= 0)
            $status = "<span class='status-gray p-1'>Sin Saldo</span>";

        $client->status = $status;

        return $client;
    }
}
