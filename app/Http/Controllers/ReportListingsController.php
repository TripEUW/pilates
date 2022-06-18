<?php

namespace App\Http\Controllers;

use App\Helpers\Pilates;
use App\Models\Attendances;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Group;
use App\Models\Holidays;
use App\Models\Product;
use App\Models\ProductSale;
use App\Models\Rol;
use App\Models\Sale;
use App\Models\Schedule;
use App\Models\Session;
use App\Models\Tax;
use Carbon\Carbon;
use DateTime;
use FFI\CData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportListingsController extends Controller
{

    public function index()
    {
       $statusModuleAssitances=Pilates::getStatusStatusModuleAssitances();
      
        return view('report_listings', compact('statusModuleAssitances'));
    }

    /////////////////////////////////////////////////////////////////// start ventas

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
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->skip($start)->take($limit)->values()->all();
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

                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->skip($start)->take($limit)->values()->all();
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
        $sale['type_payment'] = $type_payment;
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


    ////////////////////////////////////////////////////////////////// end ventas
    ////////////////////////////////////////////////////////////////// start products
    public function getProductDataTable(Request $request)
    {
        $columns = array(
            0 => 'id_select',
            1 => 'count_sold',
            2 => 'id',
            3 => 'name',
            4 => 'sessions_individual',
            5 => 'sessions_floor',
            6 => 'sessions_machine',
            7 => 'observation',
            8 => 'tax',
            9 => 'price',
            10 => 'price_end',
            11 => 'created_at',
        );

        $totalData = Product::get(['*'])
            ->count();

        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;

        $products = [];
        if (
            empty($request->input('search.value'))
            && empty($request->input('start_date'))
            && empty($request->input('end_date'))
        ) {
            if ($limit == -1) {
                $products = Product::get(['*'])->map(function ($product) {
                    return $this->mapProductDataTable($product);
                })->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {
                $products = Product::get(['*'])->map(function ($product) {
                    return  $this->mapProductDataTable($product);
                })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->skip($start)->take($limit)->values()->all();
            }
        } else {
            $search = $request->input('search.value');
            if ($limit == -1) {
                $products =  Product::get(['*'])->map(function ($product) {
                    return   $this->mapProductDataTable($product);
                })
                    ->filter(function ($product) use ($search, $columns, $request) {
                        return $this->filterSearchProductDataTable($product, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {

                $products =  Product::get(['*'])->map(function ($product) {
                    return $this->mapProductDataTable($product);
                })
                    ->filter(function ($product) use ($search, $columns, $request) {
                        return $this->filterSearchProductDataTable($product, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->skip($start)->take($limit)->values()->all();
            }

            $totalFiltered = Product::get(['*'])
                ->map(function ($product) {
                    return $this->mapProductDataTable($product);
                })
                ->filter(function ($product) use ($search, $columns, $request) {
                    return $this->filterSearchProductDataTable($product, $search, $columns, $request);
                })
                ->count();
        }



        $result = [
            'iTotalRecords'        =>  $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData'               =>  $products
        ];

        return $result;
    }

    function mapProductDataTable($product)
    {
        $productTmp = $product;

        $product['id_select'] = $product->id;
        $taxTmp = Tax::where('id_product', $product->id)->get();

        $productTmp['price_end'] = Pilates::getPriceWithTaxes($productTmp->price, $taxTmp);
        $productTmp['tax'] = $taxTmp;
        $product['actions'] = json_decode($productTmp);

        $product['tax'] = $taxTmp->map(function ($tax) {
            if ($tax->type == 'percent') {
                $tax->tax = Pilates::getFormatPercent($tax->tax);
            }
            if ($tax->type == 'money') {
                $tax->tax = Pilates::getFormatMoney($tax->tax);
            }
            return $tax;
        });
        $product['tax'] = $product['tax'][0]->tax;
        $product->price = Pilates::getFormatMoney($product->price);
        $product['price_end'] = Pilates::getFormatMoney(Pilates::getPriceWithTaxes($product->price, $taxTmp));
        $productsSold = ProductSale::where('id_product', $product['id_select'])->get(['cant']);
        $product['created_at2'] = Carbon::createFromFormat('Y-m-d H:i:s', strval($product->created_at))->format('d/m/Y');
        $productsCantSolds = 0;
        foreach ($productsSold as  $productSold) $productsCantSolds += intval($productSold->cant);
        $product['count_sold'] = $productsCantSolds;

        return $product;
    }

    function filterSearchProductDataTable($product, $search, $columns, $request)
    {
        $item = false;

        if (!empty($search)) {
            //general
            foreach ($columns as $colum)
                if (stristr($product[$colum], $search))
                    $item = $product;
            return $item;
        } else {

            $flagAmount = true;
            $flagDate = true;
            $flagClient = true;

            //by date
            if (!empty($request->start_date) &&  !empty($request->end_date)) {
                $start_date = DateTime::createFromFormat('d/m/Y', $request->start_date);
                $end_date = DateTime::createFromFormat('d/m/Y', $request->end_date);
                $date = DateTime::createFromFormat('Y-m-d', DateTime::createFromFormat('Y-m-d H:i:s', $product['created_at'])->format('Y-m-d'));
                if ($date >= $start_date && $date <= $end_date) {
                    $flagDate = true;
                } else {
                    $flagDate = false;
                }
            }

            $item = ($flagAmount && $flagDate && $flagClient) ? $product : false;
            return $item;
        }
    }
    ////////////////////////////////////////////////////////////////// end products
    ////////////////////////////////////////////////////////////////// start employees
    public function getEmployeeDataTable(Request $request)
    {

        $columns = array(
            0 => 'id',
            1 => 'rol',
            2 => 'name',
            3 => 'email',
            4 => 'sex',
            5 => 'date_of_birth',
            6 => 'tel',
            7 => 'address',
            8 => 'observation',
            9 => 'status'
        );

        $totalData = Employee::get(["*"])->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;

        $employees = [];
        if (
            empty($request->input('search.value'))
            && empty($request->input('start_date'))
            && empty($request->input('end_date'))
        ) {

            if ($limit == -1) {
                $employees = Employee::get(['*'])->map(function ($employee) {
                    return $this->analizeMapEmployeeDataTable($employee);
                })->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {
                $employees = Employee::get(['*'])->map(function ($employee) {
                    return  $this->analizeMapEmployeeDataTable($employee);
                })

                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($start)->take($limit)
                    ->values()->all();
            }
        } else {
            $search = $request->input('search.value');
            if ($limit == -1) {
                $employees =  Employee::get(['*'])->map(function ($employee) {
                    return   $this->analizeMapEmployeeDataTable($employee);
                })
                    ->filter(function ($employee) use ($search, $columns, $request) {
                        return $this->filterSearchEmployeeDataTable($employee, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {

                $employees =  Employee::get(['*'])->map(function ($employee) {
                    return $this->analizeMapEmployeeDataTable($employee);
                })
                    ->filter(function ($employee) use ($search, $columns, $request) {
                        return $this->filterSearchEmployeeDataTable($employee, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($start)->take($limit)
                    ->values()->all();
            }

            $totalFiltered = Employee::get(['*'])->map(function ($employee) {
                return $this->analizeMapEmployeeDataTable($employee);
            })
                ->filter(function ($employee) use ($search, $columns, $request) {
                    return $this->filterSearchEmployeeDataTable($employee, $search, $columns, $request);
                })
                ->count();
        }



        $result = [
            'iTotalRecords'        =>  $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData'               =>  $employees
        ];

        return $result;
    }


    function analizeMapEmployeeDataTable($employee)
    {
        $employee->name = "$employee->name $employee->last_name";
        $employee["rol"] = Rol::where('id', $employee->id_rol)->get(['name'])->first()->name;
        $employee->sex = $employee->sex == 'male' ? 'Masculino' : 'Femenino';
        $employee->status = $employee->status == 'enable' ? 'Activo' : 'Inactivo';
        $employee["actions"] = json_decode($employee);

        return $employee;
    }

    function filterSearchEmployeeDataTable($employee, $search, $columns, $request)
    {
        $item = false;

        if (!empty($search)) {
            //general
            foreach ($columns as $colum)
                if (stristr($employee[$colum], $search))
                    $item = $employee;
            return $item;
        } else {

            $flagAmount = true;
            $flagDate = true;
            $flagClient = true;

            //by date
            if (!empty($request->start_date) &&  !empty($request->end_date) && $employee['created_at'] != null) {
                $start_date = DateTime::createFromFormat('d/m/Y', $request->start_date);
                $end_date = DateTime::createFromFormat('d/m/Y', $request->end_date);
                $date = DateTime::createFromFormat('Y-m-d', DateTime::createFromFormat('Y-m-d H:i:s', $employee['created_at'])->format('Y-m-d'));
                if ($date >= $start_date && $date <= $end_date) {
                    $flagDate = true;
                } else {
                    $flagDate = false;
                }
            }

            $item = ($flagAmount && $flagDate && $flagClient) ? $employee : false;
            return $item;
        }
    }


    ///////////////////////////////////////////////////////////////// end employees
    //////////////////////////////////////////////////////////////// start client
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
        if (
            empty($request->input('search.value'))
            && empty($request->input('start_date'))
            && empty($request->input('end_date'))
        ) {

            if ($limit == -1) {
                $clients = Client::get([
                    '*'
                ])->map(function ($client) {
                    return $this->analizeMapClientDataTable($client);
                })->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {
                $clients = Client::get([
                    '*'
                ])->map(function ($client) {
                    return  $this->analizeMapClientDataTable($client);
                })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($start)->take($limit)->values()->all();
            }
        } else {
            $search = $request->input('search.value');
            if ($limit == -1) {
                $clients =  Client::get([
                    '*'
                ])->map(function ($client) {
                    return   $this->analizeMapClientDataTable($client);
                })
                    ->filter(function ($client) use ($search, $columns, $request) {
                        return $this->functionFilterClientDataTable($client, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {

                $clients =  Client::get([
                    '*'
                ])->map(function ($client) {
                    return $this->analizeMapClientDataTable($client);
                })
                    ->filter(function ($client) use ($search, $columns, $request) {
                        return $this->functionFilterClientDataTable($client, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($start)->take($limit)->values()->all();
            }

            $totalFiltered = Client::get(['*'])
                ->filter(function ($client) use ($search, $columns, $request) {
                    return $this->functionFilterClientDataTable($client, $search, $columns, $request);
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

    function functionFilterClientDataTable($client, $search, $columns, $request)
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

            //by date
            if (!empty($request->start_date) &&  !empty($request->end_date) && $client['created_at'] != null) {
                $start_date = DateTime::createFromFormat('d/m/Y', $request->start_date);
                $end_date = DateTime::createFromFormat('d/m/Y', $request->end_date);
                $date = DateTime::createFromFormat('Y-m-d', DateTime::createFromFormat('d/m/Y', $client['created_at'])->format('Y-m-d'));
                if ($date >= $start_date && $date <= $end_date) {
                    $flagDate = true;
                } else {
                    $flagDate = false;
                }
            }

            $item = ($flagAmount && $flagDate && $flagClient) ? $client : false;
            return $item;
        }
    }

    function analizeMapClientDataTable($client)
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
    /////////////////////////////////////////////////////////////// end client
    //////////////////////////////////////////////////////////////// start holidays
    public function getHolidaysDataTable(Request $request)
    {


        $columns = array(
            0 => 'id',
            1 => 'date_add',
            2 => 'name',
            3 => 'start',
            4 => 'end',
            5 => 'status',
            6 => 'total_days',
            7 => 'days_take',
            8 => 'days_pending'
        );

        $totalData = Holidays::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;

        $holidays = [];
        if (
            empty($request->input('search.value'))
            && empty($request->input('start_date'))
            && empty($request->input('end_date'))
        ) {

            if ($limit == -1) {
                $holidays = Holidays::get([
                    '*'
                ])->map(function ($client) {
                    return $this->analizeMapHolidaysDataTable($client);
                })->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {
                $holidays = Holidays::get([
                    '*'
                ])->map(function ($client) {
                    return  $this->analizeMapHolidaysDataTable($client);
                })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($start)->take($limit)->values()->all();
            }
        } else {
            $search = $request->input('search.value');
            if ($limit == -1) {
                $holidays =  Holidays::get([
                    '*'
                ])->map(function ($client) {
                    return   $this->analizeMapHolidaysDataTable($client);
                })
                    ->filter(function ($client) use ($search, $columns, $request) {
                        return $this->FilterHolidaysDataTable($client, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {

                $holidays =  Holidays::get([
                    '*'
                ])->map(function ($client) {
                    return $this->analizeMapHolidaysDataTable($client);
                })
                    ->filter(function ($client) use ($search, $columns, $request) {
                        return $this->FilterHolidaysDataTable($client, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($start)->take($limit)->values()->all();
            }

            $totalFiltered = Holidays::get(['*'])
                ->filter(function ($client) use ($search, $columns, $request) {
                    return $this->FilterHolidaysDataTable($client, $search, $columns, $request);
                })
                ->count();
        }



        $result = [
            'iTotalRecords'        =>  $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData'               =>  $holidays
        ];

        return $result;
    }

    function FilterHolidaysDataTable($holiday, $search, $columns, $request)
    {

        $item = false;

        if (!empty($search)) {
            //general
            foreach ($columns as $colum)
                if (stristr($holiday[$colum], $search))
                    $item = $holiday;
            return $item;
        } else {

            $flagAmount = true;
            $flagDate = true;
            $flagClient = true;

            //by date
            if (!empty($request->start_date) &&  !empty($request->end_date) && $holiday['created_at'] != null) {
                $start_date = DateTime::createFromFormat('d/m/Y', $request->start_date);
                $end_date = DateTime::createFromFormat('d/m/Y', $request->end_date);
                $date = DateTime::createFromFormat('Y-m-d', DateTime::createFromFormat('Y-m-d H:i:s', $holiday['created_at'])->format('Y-m-d'));
                if ($date >= $start_date && $date <= $end_date) {
                    $flagDate = true;
                } else {
                    $flagDate = false;
                }
            }

            $item = ($flagAmount && $flagDate && $flagClient) ? $holiday : false;
            return $item;
        }
    }

    function analizeMapHolidaysDataTable($holiday)
    {

        $holiday->status = $holiday->status == 'accept' ? "<span class='status-green p-1'>Aceptada</span>" : "<span class='status-yellow p-1'>Pendientes de aceptar</span>";
        $employee = Employee::where('id', $holiday->id_employee)->get(['name', 'last_name'])->first();
        $holiday['name'] =  "$employee->name $employee->last_name";

        $total_days = 0;
        $dateStart = Carbon::createFromFormat('Y-m-d', $holiday->start, config('app.timezone_for_pilates'));
        $dateEnd = Carbon::createFromFormat('Y-m-d', $holiday->end, config('app.timezone_for_pilates'));
        $total_days = $total_days + ($dateStart->diffInDays($dateEnd) + 1);

        $days_take = 0;
        $days_pending = 0;

        if ($holiday->status == 'accept') {
            $dateNow = Carbon::createFromFormat('Y-m-d', Carbon::now()->format('Y-m-d'), config('app.timezone_for_pilates'));
            if ($dateNow > $dateEnd) {
                $days_take = $total_days;
                $days_pending = 0;
            } else if ($dateNow >  $dateStart && $dateNow < $dateStart) {
                $days_take = $dateStart->diffInDays($dateNow) + 1;
                $days_pending = $total_days - $days_take;
            }
        }
        $holiday['start2'] = Carbon::createFromFormat('Y-m-d',  $holiday->start)->format('d/m/Y');
        $holiday['end2'] = Carbon::createFromFormat('Y-m-d',$holiday->end)->format('d/m/Y');
        $holiday['date_add2'] = Carbon::createFromFormat('Y-m-d H:i:s',$holiday->date_add)->format('d/m/Y');

        $holiday['total_days'] = $total_days;
        $holiday['days_take'] = $days_take;
        $holiday['days_pending'] = $days_pending;
        $holiday['actions'] = json_decode($holiday);


        return $holiday;
    }
    /////////////////////////////////////////////////////////////// end holidays
    //////////////////////////////////////////////////////////////// start attendances
    public function getAttendancesDataTable(Request $request)
    {


        $columns = array(
            0 => 'id',
            1 => 'date',
            2 => 'name',
            3 => 'status',
            4 => 'o_in_time',
            5 => 'o_out_time',
            6 => 'in_time',
            7 => 'out_time',
            8 => 'hours_to_work',
            9 => 'hours_worked_pending',
            10 => 'hours_worked'
        );

        $totalData = Attendances::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;

        $holidays = [];
        if (
            empty($request->input('search.value'))
            && empty($request->input('start_date'))
            && empty($request->input('end_date'))
        ) {

            if ($limit == -1) {
                $attendances = Attendances::get([
                    '*'
                ])->map(function ($attendance) {
                    return $this->analizeMapAttendancesDataTable($attendance);
                })->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {
                $attendances = Attendances::get([
                    '*'
                ])->map(function ($attendance) {
                    return  $this->analizeMapAttendancesDataTable($attendance);
                })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($start)->take($limit)->values()->all();
            }
        } else {
            $search = $request->input('search.value');
            if ($limit == -1) {
                $attendances =  Attendances::get([
                    '*'
                ])->map(function ($attendance) {
                    return   $this->analizeMapAttendancesDataTable($attendance);
                })
                    ->filter(function ($attendance) use ($search, $columns, $request) {
                        return $this->FilterAttendancesDataTable($attendance, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {

                $attendances =  Attendances::get([
                    '*'
                ])->map(function ($attendance) {
                    return $this->analizeMapAttendancesDataTable($attendance);
                })
                    ->filter(function ($attendance) use ($search, $columns, $request) {
                        return $this->FilterAttendancesDataTable($attendance, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($start)->take($limit)->values()->all();
            }

            $totalFiltered = Attendances::get(['*'])
                ->filter(function ($attendance) use ($search, $columns, $request) {
                    return $this->FilterAttendancesDataTable($attendance, $search, $columns, $request);
                })
                ->count();
        }



        $result = [
            'iTotalRecords'        =>  $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData'               =>  $attendances
        ];

        return $result;
    }

    function FilterAttendancesDataTable($attendance, $search, $columns, $request)
    {

        $item = false;

        if (!empty($search)) {
            //general
            foreach ($columns as $colum)
                if (stristr($attendance[$colum], $search))
                    $item = $attendance;
            return $item;
        } else {

            $flagAmount = true;
            $flagDate = true;
            $flagClient = true;

            //by date
            if (!empty($request->start_date) &&  !empty($request->end_date) && $attendance['date'] != null) {
                $start_date = DateTime::createFromFormat('d/m/Y', $request->start_date);
                $end_date = DateTime::createFromFormat('d/m/Y', $request->end_date);
                $date = DateTime::createFromFormat('Y-m-d',$attendance['date']);
                if ($date >= $start_date && $date <= $end_date) {
                    $flagDate = true;
                } else {
                    $flagDate = false;
                }
            }

            $item = ($flagAmount && $flagDate && $flagClient) ? $attendance : false;
            return $item;
        }
    }

    function analizeMapAttendancesDataTable($attendance)
    {

        $time_to_work = '00:00:00';
        $time_worked_pending = '00:00:00';
        $time_worked = '00:00:00';

        $time_to_work = date('H:i:s',(strtotime($attendance->o_out_time) - strtotime($attendance->o_in_time)));
        
        if ($attendance->in_time != null && $attendance->out_time != null) {
           $time_worked = date('H:i:s',(strtotime($attendance->out_time) - strtotime($attendance->in_time)));
           $time_worked_pending =date('H:i:s',(strtotime($time_to_work ) - strtotime($time_worked)));
        } else {
            $time_worked_pending = "Indefinido";
            $time_worked = "Indefinido";
        }

        if ($attendance->status == 'absent') {
            $attendance->status =  "<span class='status-red p-1 d-inline-block text-center'>Ausente</span>";
        } else if ($attendance->status == 'attended') {
            if($attendance->out_time == null){
                $attendance->status =  "<span class='status-yellow p-1 d-inline-block text-center'>Asistio</span>";
            }else{
                $attendance->status =  "<span class='status-green p-1 d-inline-block text-center'>Asistio</span>";
            }
        }

        $attendance->hours_to_work=$time_to_work;
        if($attendance->in_time == null && $attendance->out_time == null){
            $attendance->hours_worked_pending=$time_to_work;
        }else{
            $attendance->hours_worked_pending=$time_worked_pending;
        }
       
        $attendance->hours_worked=$time_worked;

        $attendance->o_in_time = Carbon::createFromFormat('H:i:s', $attendance->o_in_time, config('app.timezone_for_pilates'))->format('g:i A');
        $attendance->o_out_time = Carbon::createFromFormat('H:i:s', $attendance->o_out_time, config('app.timezone_for_pilates'))->format('g:i A');
      
        if($attendance->in_time == null){
        $attendance->in_time = "<span class='status-red p-1 d-inline-block text-center'>No marco entrada</span>"; 
        }else{
        $attendance->in_time = Carbon::createFromFormat('H:i:s', $attendance->in_time, config('app.timezone_for_pilates'))->format('g:i A');
        }
        if($attendance->out_time == null){
        $attendance->out_time = "<span class='status-red p-1 d-inline-block text-center'>No marco salida</span>"; 
        }else{
        $attendance->out_time = Carbon::createFromFormat('H:i:s', $attendance->out_time, config('app.timezone_for_pilates'))->format('g:i A');
        }
       
        


        $employee = Employee::where('id', $attendance->id_employee)->get(['name', 'last_name'])->first();
        $attendance['name'] =  "$employee->name $employee->last_name";

     
        $attendance['actions'] = json_decode($attendance);


        return $attendance;
    }
    /////////////////////////////////////////////////////////////// end attendances

        //////////////////////////////////////////////////////////////// start free hours
        public function getFreeHoursDataTable(Request $request)
        {
    
    
            $columns = array(
                0 => 'id_employee',
                1 => 'employee_name',
                2 => 'monday',
                3 => 'tuesday',
                4 => 'wednesday',
                5 => 'thursday',
                6 => 'friday'
            );
    
            $totalData = Employee::count();
            $totalFiltered = $totalData;

            $employees=[];
    
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');
            $dir = ($dir == 'desc') ? true : false;
    
            $holidays = [];
            if(!empty($request->input('start_date')) && !empty($request->input('end_date'))){
            if(
            empty($request->input('search.value'))
            ){
    
                if ($limit == -1) {
                    $employees =  Employee::get([
                        '*'
                    ])->map(function ($employees) use($request)  {
                        return $this->analizeMapFreeHoursDataTable($employees,$request);
                    })->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
                } else {
                    $employees = Employee::get([
                        '*'
                    ])->map(function ($employees) use($request)  {
                        return  $this->analizeMapFreeHoursDataTable($employees,$request);
                    })
                        ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                        ->skip($start)->take($limit)->values()->all();
                }
            } else {
                $search = $request->input('search.value');
                if ($limit == -1) {
                    $employees =  Employee::get([
                        '*'
                    ])->map(function ($employees) use($request) {
                        return   $this->analizeMapFreeHoursDataTable($employees,$request);
                    })
                        ->filter(function ($employees) use ($search, $columns, $request) {
                            return $this->FilteFreeHoursDataTable($employees, $search, $columns, $request);
                        })
                        ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
                } else {
    
                    $employees =  Employee::get([
                        '*'
                    ])->map(function ($employees) use($request)  {
                        return $this->analizeMapFreeHoursDataTable($employees,$request);
                    })
                        ->filter(function ($employees) use ($search, $columns, $request) {
                            return $this->FilteFreeHoursDataTable($employees, $search, $columns, $request);
                        })
                        ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                        ->skip($start)->take($limit)->values()->all();
                }
    
                $totalFiltered = Employee::get(['*'])
                    ->filter(function ($employees) use ($search, $columns, $request) {
                        return $this->FilteFreeHoursDataTable($employees, $search, $columns, $request);
                    })
                    ->count();
            }
    
        }
    
    
            $result = [
                'iTotalRecords'        =>  $totalData,
                'iTotalDisplayRecords' => $totalFiltered,
                'aaData'               =>  $employees
            ];
    
            return $result;
        }
    
        function FilteFreeHoursDataTable($employee, $search, $columns, $request)
        {
    
            $item = false;
    
            if (!empty($search)) {
                //general
                foreach ($columns as $colum)
                    if (stristr($employee[$colum], $search))
                        $item = $employee;
                return $item;
            } else {
    
                $flagAmount = true;
                $flagDate = true;
                $flag = true;
    
                //by date
                if (!empty($request->start_date) &&  !empty($request->end_date) && $employee['date'] != null) {
                    $start_date = DateTime::createFromFormat('d/m/Y', $request->start_date);
                    $end_date = DateTime::createFromFormat('d/m/Y', $request->end_date);
                    $date = DateTime::createFromFormat('Y-m-d',$employee['date']);
                    if ($date >= $start_date && $date <= $end_date) {
                        $flagDate = true;
                    } else {
                        $flagDate = false;
                    }
                }
    
                $item = ($flagAmount && $flagDate && $flag) ? $employee : false;
                return $item;
            }
        }
    
        function analizeMapFreeHoursDataTable($employee,$request)
        {

            $start_date = DateTime::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d');
            $end_date = DateTime::createFromFormat('d/m/Y', $request->end_date)->format('Y-m-d');

            $groupsEmployee=Group::where('id_employee',$employee->id)->get(['id']);
            $idsGroups=[];
                  
            foreach ($groupsEmployee as $key => $groupEmployee)array_push($idsGroups,$groupEmployee->id);


            $dateEndTmp=Carbon::createFromFormat('Y-m-d', $end_date)->endOfWeek()->format('Y-m-d');

            $sessions = Session::
              where('date_start', '>=', "$start_date 00:00:00")
            ->where('date_end', '<=',"$dateEndTmp 23:59:59")
            ->whereIn('id_group', $idsGroups)
            ->groupBy('date_start','date_end','id_group')
            ->get();

            $timeMonday='00:00:00';
            $timeTuesday='00:00:00';
            $timeWednesday='00:00:00';
            $timeThursday='00:00:00';
            $timeFriday='00:00:00';

            foreach ($sessions as $key => $session) {
               
               $employeeStatus= Pilates::getStatusEmployeeGroupBySessionGroup($employee->id,$session->date_start,$session->date_end,false);

               if($employeeStatus){
                $time_start = Carbon::createFromFormat('Y-m-d H:i:s', $session->date_start)->format('H:i:s');
                $time_end = Carbon::createFromFormat('Y-m-d H:i:s', $session->date_end)->format('H:i:s');
                $dateTmp=Carbon::createFromFormat('Y-m-d H:i:s', $session->date_start);
                $time_worked = date('H:i:s',(strtotime($time_end) - strtotime($time_start)));

                if($dateTmp->clone()->isMonday() === true){
                    $timeMonday=  date('H:i:s',(strtotime($time_worked) + strtotime($timeMonday)));
                }else if($dateTmp->clone()->isTuesday() === true){
                    $timeTuesday=  date('H:i:s',(strtotime($time_worked) + strtotime($timeTuesday)));
                }else if($dateTmp->clone()->isWednesday() === true){
                    $timeWednesday=  date('H:i:s',(strtotime($time_worked) + strtotime($timeWednesday)));
                }else if($dateTmp->clone()->isThursday() === true){
                    $timeThursday=  date('H:i:s',(strtotime($time_worked) + strtotime($timeThursday)));
                }else if($dateTmp->clone()->isFriday() === true){
                    $timeFriday=  date('H:i:s',(strtotime($time_worked) + strtotime($timeFriday)));
                }
               }
              
            }

          
            $schedules = Schedule::
            where("date_start",">=", $start_date)
          ->where("date_end", "<=", $dateEndTmp)
          ->where('id_employee', $employee->id)
          ->get();

          $timeMondayS='00:00:00';
          $timeTuesdayS='00:00:00';
          $timeWednesdayS='00:00:00';
          $timeThursdayS='00:00:00';
          $timeFridayS='00:00:00';

          foreach ($schedules as $key => $schedule) {
   
            $time_worked = date('H:i:s',(strtotime($schedule->end) - strtotime( $schedule->start)));

            if($schedule->monday == 'true'){
                $timeMondayS=  date('H:i:s',(strtotime($time_worked) + strtotime($timeMondayS)));
            }
            if($schedule->tuesday == 'true'){
                $timeTuesdayS=  date('H:i:s',(strtotime($time_worked) + strtotime($timeTuesdayS)));
            }
            if($schedule->wednesday == 'true'){
                $timeWednesdayS=  date('H:i:s',(strtotime($time_worked) + strtotime($timeWednesdayS)));
            }
            if($schedule->thursday == 'true'){
                $timeThursdayS=  date('H:i:s',(strtotime($time_worked) + strtotime($timeThursdayS)));
            }
            if($schedule->friday == 'true'){
                $timeFridayS=  date('H:i:s',(strtotime($time_worked) + strtotime($timeFridayS)));
            }
          }

          $monday= (strtotime($timeMonday) > strtotime($timeMondayS))?date('H:i:s',(strtotime($timeMonday) - strtotime($timeMondayS))):date('H:i:s',(strtotime($timeMondayS) - strtotime($timeMonday)));
          $tuesday=(strtotime($timeTuesday) > strtotime($timeTuesdayS))?date('H:i:s',(strtotime($timeTuesday) - strtotime($timeTuesdayS))):date('H:i:s',(strtotime($timeTuesdayS) - strtotime($timeTuesday)));
          $wednesday=(strtotime($timeWednesday) > strtotime($timeWednesdayS))?date('H:i:s',(strtotime($timeWednesday) - strtotime($timeWednesdayS))):date('H:i:s',(strtotime($timeWednesdayS) - strtotime($timeWednesday)));
          $thursday=(strtotime($timeThursday) > strtotime($timeThursdayS))?date('H:i:s',(strtotime($timeThursday) - strtotime($timeThursdayS))):date('H:i:s',(strtotime($timeThursdayS) - strtotime($timeThursday)));
          $friday=(strtotime($timeFriday) > strtotime($timeFridayS))?date('H:i:s',(strtotime($timeFriday) - strtotime($timeFridayS))):date('H:i:s',(strtotime($timeFridayS) - strtotime($timeFriday)));

          $employee['monday']=$monday;
          $employee['tuesday']=$tuesday;
          $employee['wednesday']=$wednesday;
          $employee['thursday']=$thursday;
          $employee['friday']= $friday;
          $employee['employee_name'] = "$employee->name $employee->last_name";
          $employee['id_employee'] = $employee->id;
          
    
        return $employee;
        }
        /////////////////////////////////////////////////////////////// end free hours
}
