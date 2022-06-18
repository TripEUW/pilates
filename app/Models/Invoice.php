<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Helpers\Pilates;
use DateTime;

class Invoice extends Model
{
    protected $table = 'invoice';
    protected $guarded = ['id'];



    public function getClientInvoiceDataTable(Request $request)
    {


        $columns = array(
            0 => 'id',
            1 => 'last_name',
            2 => 'name',
            3 => 'tel',
            4 => 'level',
            5 => 'sex',
            6 => 'email',
            7 => 'status',
            // 8=> 'address',
            // 9=> 'dni',
            // 10=> 'date_of_birth',
            // 11=> 'date_register',
            // 12=> 'observation',
            // 13=> 'sessions_machine',
            // 14=> 'sessions_floor',
            // 15=> 'sessions_individual',

            8 => 'amount_invoice',
            9 => 'date_invoice',
            10 => 'code_invoice'
        );

        $totalData = Client::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;


        $clients = [];
        if (
            empty($request->input('search.value'))
            && empty($request->input('client_search'))
            && empty($request->input('start_date'))
            && empty($request->input('end_date'))
            && empty($request->input('start_amount'))
            && empty($request->input('end_amount'))
        ) {

            if ($limit == -1) {
                $clients = Client::join('sale', 'client.id', '=', 'sale.id_client')
                    ->join('invoice', 'sale.id', '=', 'invoice.id_sale')
                    ->get([
                        '*', 'client.id as id', 'client.date_register as date_register', 'sale.id as id_sale', 'invoice.id as id_invoice', 'invoice.id as code_invoice', 'invoice.date_create as date_invoice'
                    ])->map(function ($client) {
                        return $this->analizeFilterClientInvoiceDataTable($client);
                    })->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {
                $clients = Client::join('sale', 'client.id', '=', 'sale.id_client')
                    ->join('invoice', 'sale.id', '=', 'invoice.id_sale')
                    ->get([
                        '*', 'client.id as id', 'client.date_register as date_register', 'sale.id as id_sale', 'invoice.id as id_invoice', 'invoice.id as code_invoice', 'invoice.date_create as date_invoice'
                    ])->map(function ($client) {
                        return $this->analizeFilterClientInvoiceDataTable($client);
                    })
                    ->skip($start)->take($limit)
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            }
        } else {
            $search = $request->input('search.value');
            if ($limit == -1) {
                $clients =  Client::join('sale', 'client.id', '=', 'sale.id_client')
                    ->join('invoice', 'sale.id', '=', 'invoice.id_sale')
                    ->get([
                        '*', 'client.id as id', 'client.date_register as date_register', 'sale.id as id_sale', 'invoice.id as id_invoice', 'invoice.id as code_invoice', 'invoice.date_create as date_invoice'
                    ])->map(function ($client) {
                        return $this->analizeFilterClientInvoiceDataTable($client);
                    })
                    ->filter(function ($client) use ($search, $columns, $request) {
                        return $this->filterSearchClientInvoiceDataTable($client, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {

                $clients =  Client::join('sale', 'client.id', '=', 'sale.id_client')
                    ->join('invoice', 'sale.id', '=', 'invoice.id_sale')
                    ->get([
                        '*', 'client.id as id', 'client.date_register as date_register', 'sale.id as id_sale', 'invoice.id as id_invoice', 'invoice.id as code_invoice', 'invoice.date_create as date_invoice'
                    ])->map(function ($client) {
                        return $this->analizeFilterClientInvoiceDataTable($client);
                    })
                    ->filter(function ($client) use ($search, $columns, $request) {
                        return $this->filterSearchClientInvoiceDataTable($client, $search, $columns, $request);
                    })
                    ->skip($start)->take($limit)
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            }

            $totalFiltered = Client::join('sale', 'client.id', '=', 'sale.id_client')
                ->join('invoice', 'sale.id', '=', 'invoice.id_sale')
                ->get(['*', 'client.id as id', 'client.date_register as date_register', 'sale.id as id_sale', 'invoice.id as id_invoice', 'invoice.id as code_invoice', 'invoice.date_create as date_invoice'])
                ->filter(function ($client) use ($search, $columns, $request) {
                    return $this->filterSearchClientInvoiceDataTable($client, $search, $columns, $request);
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

    function analizeFilterClientInvoiceDataTable($client)
    {
        $products = ProductSale::where('id_sale', $client->id_sale)->get();
        $client['amount_invoice'] = Pilates::getFormatMoney(Pilates::getTotalAmount($products));
        $client['amount_invoice_simple'] = Pilates::getTotalAmount($products);
        $client->date_invoice = DateTime::createFromFormat('Y-m-d H:i:s', $client->date_invoice)->format('d/m/Y');


        $client->actions = json_decode($client);
        $client->sex = ($client->sex == "male") ? "Masculino" : "Femenino";
        return $client;
    }

    function filterSearchClientInvoiceDataTable($client, $search, $columns, $request)
    {
        $item = false;

        if (!empty($search)) {
            //general
            foreach ($columns as $colum)
                if (stristr($client[$colum], $search))
                    $item = $client;
            return $item;
        } else {

            $flagAmount = true;
            $flagDate = true;
            $flagClient = true;
            //total amount
            if ($request->start_amount != "" && $request->end_amount != "") {
                $start_amount = floatval($request->start_amount);
                $end_amount = floatval($request->end_amount);
                $amount = floatval($client['amount_invoice_simple']);
                if ($amount >= $start_amount && $amount <= $end_amount) {
                    $flagAmount = true;
                } else {
                    $flagAmount = false;
                }
            }
            //by date
            if (!empty($request->start_date) &&  !empty($request->end_date)) {
                $start_date = DateTime::createFromFormat('d/m/Y', $request->start_date);
                $end_date = DateTime::createFromFormat('d/m/Y', $request->end_date);
                $dateInvoice = DateTime::createFromFormat('d/m/Y', $client['date_invoice']);
                if ($dateInvoice >= $start_date && $dateInvoice <= $end_date) {
                    $flagDate = true;
                } else {
                    $flagDate = false;
                }
            }
            //client
            $columnsSearch = array('id', 'last_name', 'name');
            $flagEntry = false;
            if (!empty($request->client_search)) {
                foreach ($columnsSearch as $colum) {
                    if (!$flagEntry) {
                        if (stristr($client[$colum], $request->client_search)) {
                            $flagClient = true;
                            $flagEntry = true;
                        } else {
                            $flagClient = false;
                        }
                    }
                }
            }

            $item = ($flagAmount && $flagDate && $flagClient) ? $client : false;
            return $item;
        }
    }
}
