<?php

namespace App\Models;

use App\Helpers\Pilates;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Client extends Model
{
    protected $table = 'client';
    protected $guarded = ['id'];

    public function getNameAttribute($name)
    {
        return ucwords($name);
    }

    public function getLastNameAttribute($lastName)
    {
        return ucwords($lastName);
    }

    public function setEmailAttribute($email)
    {
        $this->attributes['email'] = mb_strtolower($email);
    }

    public function setNameAttribute($name)
    {
        $this->attributes['name'] = mb_convert_case(mb_strtolower($name), MB_CASE_TITLE, 'UTF-8');
    }

    public function setLastNameAttribute($lastName)
    {
        $this->attributes['last_name'] = mb_convert_case(mb_strtolower($lastName), MB_CASE_TITLE, 'UTF-8');
    }

    public function getCreatedAtAttribute($date)
    {
        try {
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d/m/Y');
        } catch (\Carbon\Exceptions\InvalidFormatException $e) {
            error_log("Error al crear la fecha en el modelo Client: " . $e->getMessage());
        }
        return $date;
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
        );

        $query = Client::query();

        $totalData = $query->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir') === 'desc' ? 'desc' : 'asc';

        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');
            $query->where(function ($query) use ($columns, $search) {
                foreach ($columns as $column) {
                    $query->orWhere($column, 'LIKE', "%{$search}%");
                }
            });
            $totalFiltered = $query->count();
        }

        if ($limit != -1) {
            $query->offset($start)->limit($limit);
        }

        $clients = $query->orderBy($order, $dir)->get();

        $clients = $clients->map(function ($client) {
            return $this->analizeFilterClientDataTable($client);
        });

        $result = [
            'iTotalRecords' => $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData' => $clients,
        ];

        return $result;
    }

    function analizeFilterClientDataTable($client)
    {

        $client['id_select'] = $client->id;
        $client['type'] = ($client->suscription == "false" || $client->suscription == "" || $client->suscription == null) ? "Básico" : "Suscripción";
        $client['actions'] = json_decode($client);

        $client['created_at_2'] = $this->formatDate($client->created_at, 'd/m/Y');
   
        return $client;
    }

    private function formatDate($date, $format)
    {
        $formats = ['Y-m-d H:i:s', 'Y-m-d', 'd/m/Y'];
        
        // Log::info("Date format error: Could not parse '{$date}' using the formats: " . implode(', ', $formats));
        // foreach ($formats as $inputFormat) {
        //     try {
        //         return Carbon::createFromFormat($inputFormat, $date)->format($format);
        //     } catch (\Exception $e) {
        //         continue;
        //     }
        // }

        return $date;
    }
}
