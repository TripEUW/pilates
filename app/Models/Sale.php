<?php

namespace App\Models;

use Illuminate\Http\Request;
use App\Helpers\Pilates;
use DateTime;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $table = 'sale';
    protected $guarded = ['id'];

    public function getSalesDataTable(Request $request)
    {


        $columns = array(
            0 => 'id_sale',
            1 => 'sale_date',
            2 => 'amount_invoice_simple',
            3 => 'client_name_c',
            4 => 'employee_name_c',
            5 => 'type_payment',
            6 => 'type_emission'
        );

        $totalData = Sale::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;


        $sales = [];
        if (
            empty($request->input('search.value'))
            && empty($request->input('client_search'))
            && empty($request->input('start_date'))
            && empty($request->input('end_date'))
            && empty($request->input('start_amount'))
            && empty($request->input('end_amount'))
        ) {

            if ($limit == -1) {
                $sales = Sale::leftJoin('employee', 'sale.id_employee', '=', 'employee.id')
                    ->leftJoin('client', 'sale.id_client', '=', 'client.id')
                    ->get([
                        '*', 'sale.id as id_sale', 'employee.name as employee_name', 'employee.last_name as employee_last_name', 'client.name as client_name', 'client.last_name as client_last_name', 'sale.created_at as sale_date'
                    ])->map(function ($sale) {
                        return $this->analizeFilterSaleDataTable($sale);
                    })->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {
                $sales = Sale::leftJoin('employee', 'sale.id_employee', '=', 'employee.id')
                    ->leftJoin('client', 'sale.id_client', '=', 'client.id')
                    ->get([
                        '*', 'sale.id as id_sale', 'employee.name as employee_name', 'employee.last_name as employee_last_name', 'client.name as client_name', 'client.last_name as client_last_name', 'sale.created_at as sale_date'
                    ])->map(function ($sale) {
                        return $this->analizeFilterSaleDataTable($sale);
                    })
                    ->skip($start)->take($limit)
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            }
        } else {
            $search = $request->input('search.value');
            if ($limit == -1) {
                $sales =  Sale::leftJoin('employee', 'sale.id_employee', '=', 'employee.id')
                    ->leftJoin('client', 'sale.id_client', '=', 'client.id')
                    ->get([
                        '*', 'sale.id as id_sale', 'employee.name as employee_name', 'employee.last_name as employee_last_name', 'client.name as client_name', 'client.last_name as client_last_name', 'sale.created_at as sale_date'
                    ])->map(function ($sale) {
                        return $this->analizeFilterSaleDataTable($sale);
                    })
                    ->filter(function ($sale) use ($search, $columns, $request) {
                        return $this->filterSearchSaleDataTable($sale, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {

                $sales =  Sale::leftJoin('employee', 'sale.id_employee', '=', 'employee.id')
                    ->leftJoin('client', 'sale.id_client', '=', 'client.id')
                    ->get([
                        '*', 'sale.id as id_sale', 'employee.name as employee_name', 'employee.last_name as employee_last_name', 'client.name as client_name', 'client.last_name as client_last_name', 'sale.created_at as sale_date'
                    ])->map(function ($sale) {
                        return $this->analizeFilterSaleDataTable($sale);
                    })
                    ->filter(function ($sale) use ($search, $columns, $request) {
                        return $this->filterSearchSaleDataTable($sale, $search, $columns, $request);
                    })
                    ->skip($start)->take($limit)
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            }

            $totalFiltered = Sale::leftJoin('employee', 'sale.id_employee', '=', 'employee.id')
                ->leftJoin('client', 'sale.id_client', '=', 'client.id')
                ->get([
                    '*', 'sale.id as id_sale', 'employee.name as employee_name', 'employee.last_name as employee_last_name', 'client.name as client_name', 'client.last_name as client_last_name', 'sale.created_at as sale_date'
                ])
                ->filter(function ($sale) use ($search, $columns, $request) {
                    return $this->filterSearchSaleDataTable($sale, $search, $columns, $request);
                })
                ->count();
        }



        $result = [
            'iTotalRecords'        =>  $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData'               =>  $sales
        ];

        return $result;
    }

    function analizeFilterSaleDataTable($sale)
    {
        $type_payment="";
        if($sale->type_payment == 'cash'){
            $type_payment="Efectivo";
        }else if($sale->type_payment == 'card'){
            $type_payment="Tarjeta";
        }else{
            $type_payment="Mixto";
        }
        $products = ProductSale::where('id_sale', $sale->id_sale)->get();
        $sale['amount_invoice'] = Pilates::getFormatMoney(Pilates::getTotalAmount($products));
        $sale['amount_invoice_simple'] = Pilates::getTotalAmount($products);
        $sale['employee_name_c'] = $sale->employee_name . ' ' . $sale->employee_last_name;
        $sale['client_name_c'] = $sale->client_name . ' ' . $sale->client_last_name;
        $sale['type_payment'] =  $type_payment;
        $sale['type_emission'] = ($sale->type_emission == 'invoice') ? 'Factura' : 'Ticket';
        $sale['invoice'] = ($sale->invoice_count > 0) ? true : false;
        $sale['ticket'] = ($sale->ticket_count > 0) ? true : false;

        $sale['date_formated'] = DateTime::createFromFormat('Y-m-d H:i:s', $sale->sale_date)->format('d-m-Y');

        $sale->sale_date = DateTime::createFromFormat('Y-m-d H:i:s', $sale->sale_date)->format('d/m/Y');


        $sale->actions = json_decode($sale);
        return $sale;
    }

    function filterSearchSaleDataTable($sale, $search, $columns, $request)
    {
        $item = false;

        if (!empty($search)) {
            //general
            foreach ($columns as $colum)
                if (stristr($sale[$colum], $search))
                    $item = $sale;
            return $item;
        } else {

            $flagAmount = true;
            $flagDate = true;
            $flagClient = true;
            //total amount
            if ($request->start_amount != "" && $request->end_amount != "") {
                $start_amount = floatval($request->start_amount);
                $end_amount = floatval($request->end_amount);
                $amount = floatval($sale['amount_invoice_simple']);
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
                $dateInvoice = DateTime::createFromFormat('d-m-Y', $sale['date_formated']);
                if ($dateInvoice >= $start_date && $dateInvoice <= $end_date) {
                    $flagDate = true;
                } else {
                    $flagDate = false;
                }
            }
            //client
            $columnsSearch = array('employee_name_c', 'client_name_c');
            $flagEntry = false;
            if (!empty($request->client_search)) {
                foreach ($columnsSearch as $colum) {
                    if (!$flagEntry) {
                        if (stristr($sale[$colum], $request->client_search)) {
                            $flagClient = true;
                            $flagEntry = true;
                        } else {
                            $flagClient = false;
                        }
                    }
                }
            }

            $item = ($flagAmount && $flagDate && $flagClient) ? $sale : false;
            return $item;
        }
    }
}
