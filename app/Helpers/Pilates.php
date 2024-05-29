<?php

namespace App\Helpers;

use App\Models\Attendances;
use App\Models\Client;
use App\Models\Configuration;
use App\Models\Employee;
use App\Models\Group;
use App\Models\Holidays;
use App\Models\InOut;
use App\Models\Module;
use App\Models\Notification;
use App\Models\NoWorkDay;
use App\Models\Rol;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Session;
use App\Models\SessionTemplate;
use App\Models\TaxSale;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Pilates
{

    public static function getMenuEnable($route)
    {
        if (request()->is($route) || request()->is($route . '/*') || request()->route()->getName() == $route) {
            return true;
        } else {
            return false;
        }
    }



    public static function getBreadCrumbs($breadcrumbsList)
    {
        $html = "";
        $htmlInner = "";
        for ($i = 0; $i < count($breadcrumbsList); $i++) {
            if ($i == 0)
                $html .= " <h3 class='kt-subheader__title'>" . $breadcrumbsList[$i]['name'] . "</h3>";
            if ($i == 1)
                $html .= "<span class='kt-subheader__separator kt-hidden'></span>";
            if ($i >= 1)
                $htmlInner .= ' <span class="kt-subheader__breadcrumbs-separator"></span><a href="' . $breadcrumbsList[$i]['route'] . '" class="kt-subheader__breadcrumbs-link"> ' . $breadcrumbsList[$i]['name'] . ' </a>';
        }

        if ($htmlInner != "") {
            $html .= '<div class="kt-subheader__breadcrumbs"><a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>';
            $html .= $htmlInner;
            $html .= "</div>";
        }
        return $html;
    }


    public static function getRolPermissionStatus(Request $request = null, $route = false, $moduleTitle = false)
    {
        //! Need to understand more about the get Role Permission Status allow view all menu
        // return true;
        $flagRoute = false;
        $flagTitle = false;
        $employeeModules = Employee::join('rol', 'employee.id_rol', '=', 'rol.id')
            ->join('rol_module', 'rol.id', '=', 'rol_module.id_rol')
            ->join('module', 'module.id', '=', 'rol_module.id_module')
            ->where('employee.id', auth()->user()->id)->orderBy('employee.id')
            ->orderBy('employee.id')
            ->get(['module.id', 'module.title', 'module.url']);

        $modules = Module::get();

        /*
    foreach($employeeModules as $employeeModule){

     echo $employeeModule->url." <br>";
   
    }
    */

        foreach ($employeeModules as $employeeModule) {
            if ($route && $route != "") {
                if ($route == $employeeModule->url)
                    $flagRoute = true;
            } else {
                //if(Route::has($employeeModule->url)){
                if ($request->route()->getName() == $employeeModule->url)
                    $flagRoute = true;

                $flagExistInListModule = false;
                foreach ($modules as $module) {
                    if ($module->url == $request->route()->getName())
                        $flagExistInListModule = true;
                }

                if (!$flagExistInListModule && !$flagRoute) {
                    $flagRoute = true;
                }
                //}
            }


            //dd("end");

            if ($moduleTitle && $employeeModule->title == $moduleTitle)
                $flagTitle = true;
        }


        if (request()->is('restricted_permission') && !$route)
            return true;

        if ($flagRoute || $flagTitle){
            return true;
        }else {

            return false;
        }

    }


    public static function getFormatMoney($cant)
    {
        return number_format((float) $cant, 2, '.', ',') . ' €';
    }
    public static function getFormatMoneyForDomPDF($cant)
    {
        return number_format((float) $cant, 2, '.', ',') . ' &#8364';
    }


    public static function getFormatPercent($percent)
    {
        return number_format((float) $percent, 2, '.', ',') . ' %';
    }

    public static function getPriceWithTaxes($price, $taxes)
    {
        $priceEnd = (float) $price;
        $sumTaxes = 0;
        foreach ($taxes as $tax) {
            if ($tax->type == 'percent') {
                $calTax = floatval($tax->tax) / 100;
                $sumTaxes += floatval($price) * $calTax;
            } else if ($tax->type == 'money') {
                $sumTaxes += $tax->tax;
            }
        }
        return round($priceEnd + $sumTaxes, 1);
    }

    public static function getTotalAmount($products)
    {
        $totalAmount = 0;
        foreach ($products as $product) {
            $taxes = TaxSale::where('id_product_sale', $product->id)->get();
            $sumTaxes = 0;
            foreach ($taxes as $tax) {
                if ($tax->type == 'percent') {
                    $calTax = floatval($tax->tax) / 100;
                    $sumTaxes += floatval($product->price) * $calTax;
                } else if ($tax->type == 'money') {
                    $sumTaxes += $tax->tax;
                }
            }
            $calDiscount = $product->discount ?? 0;
            $calDiscount = floatval($calDiscount);
            $calDiscount = $calDiscount / 100;
            $calDiscount = floatval($product->price) * $calDiscount;

            $totalAmount += floatval($product->cant) * ((floatval($product->price) + $sumTaxes) - $calDiscount);
        }
        return $totalAmount;
    }

    public static function getTotalAmountSimple($products)
    {
        $totalAmount = 0;
        foreach ($products as $product) {
            $totalAmount += floatval($product->cant) * (floatval($product->price));
        }
        return $totalAmount;
    }

    public static function getTotalByProductAmount($product)
    {

        $taxes = TaxSale::where('id_product_sale', $product->id)->get();
        $sumTaxes = 0;
        foreach ($taxes as $tax) {
            if ($tax->type == 'percent') {
                $calTax = floatval($tax->tax) / 100;
                $sumTaxes += floatval($product->price) * $calTax;
            } else if ($tax->type == 'money') {
                $sumTaxes += $tax->tax;
            }
        }
        $calDiscount = $product->discount ?? 0;
        $calDiscount = floatval($calDiscount);
        $calDiscount = $calDiscount / 100;
        $calDiscount = floatval($product->price) * $calDiscount;

        return floatval($product->cant) * ((floatval($product->price) + $sumTaxes) - $calDiscount);
    }

    public static function getTotalDiscount($products)
    {
        $totalAmount = 0;
        foreach ($products as $product) {
            $calDiscount = $product->discount ?? 0;
            $calDiscount = floatval($calDiscount);
            $calDiscount = $calDiscount / 100;
            $calDiscount = floatval($product->price) * $calDiscount;

            $totalAmount += floatval($product->cant) * ($calDiscount);
        }
        return $totalAmount;
    }

    public static function getTotalTaxes($products)
    {
        $totalAmount = 0;
        foreach ($products as $product) {
            $taxes = TaxSale::where('id_product_sale', $product->id)->get();
            $sumTaxes = 0;
            foreach ($taxes as $tax) {
                if ($tax->type == 'percent') {
                    $calTax = floatval($tax->tax) / 100;
                    $sumTaxes += floatval($product->price) * $calTax;
                } else if ($tax->type == 'money') {
                    $sumTaxes += $tax->tax;
                }
            }

            $totalAmount += floatval($product->cant) * ($sumTaxes);
        }
        return $totalAmount;
    }

    public static function checkBalance($id_group, $id_client)
    {


        $client = Client::where("id", $id_client)->first();
        $groupRoom = Group::where("group.id", $id_group)->join('room', 'group.id_room', '=', 'room.id')->first();
        $sessionsClient = Session::where("id_client", $id_client)->where('status', 'enable')->get();

        $sumMachine = 0;
        $sumFloor = 0;
        $sumIndividual = 0;


        foreach ($sessionsClient as $session) {
            $sumMachine += floatval($session->sessions_machine);
            $sumFloor += floatval($session->sessions_floor);
            $sumIndividual += floatval($session->sessions_individual);
        }

        $balanceMachine = (floatval($client->sessions_machine)) - $sumMachine;
        $balanceFloor = (floatval($client->sessions_floor)) - $sumFloor;
        $balanceIndividual = (floatval($client->sessions_individual)) - $sumIndividual;

        $flagMachine = true;
        $flagFloor = true;
        $flagIndividual = true;



        if ($groupRoom->type_room == 'Máquina') {
            if ($balanceMachine <= 0)
                $flagMachine = false;
        } else if ($groupRoom->type_room == 'Suelo') {
            if ($balanceFloor <= 0)
                $flagFloor = false;
        } else if ($groupRoom->type_room == 'Camilla') {
            if ($balanceIndividual <= 0)
                $flagIndividual = false;
        }



        $response = true;
        $message = false;
        if ($groupRoom->type_room == 'Máquina') {

            if (!$flagMachine) {
                $response = false;
                $message = 'No cuenta con saldo suficiente para una sesión de máquina.';

                if ($balanceFloor >= 2) {
                    $response = true;
                    $message = '¿El cliente desea canjear 2 sesiones de suelo por 1 de máquina ?';
                    $message .= "<br><br>Saldo suelo: $balanceFloor";
                    $message .= "<br>Saldo máquina: $balanceMachine";
                    $message .= "<br>Saldo fisioterapia: $balanceIndividual";
                } else {
                    $message .= "<br><br>Saldo suelo: $balanceFloor";
                    $message .= "<br>Saldo máquina: $balanceMachine";
                    $message .= "<br>Saldo fisioterapia: $balanceIndividual";
                    $message .= "<br><br> ¿Desea asignar la sesión al cliente y poner su saldo como negativo?";
                }
            }
        } else if ($groupRoom->type_room == 'Suelo') {

            if (!$flagFloor) {
                $response = false;
                $message = 'No cuenta con saldo suficiente para una sesión de suelo.';
                $message .= "<br><br>Saldo suelo: $balanceFloor";
                $message .= "<br>Saldo máquina: $balanceMachine";
                $message .= "<br>Saldo fisioterapia: $balanceIndividual";
                $message .= "<br><br> ¿Desea asignar la sesión al cliente y poner su saldo como negativo?";
            }
        } else if ($groupRoom->type_room == 'Camilla') {
            if (!$flagIndividual) {
                $response = false;
                $message = 'No cuenta con saldo suficiente para una sesión de fisioterapia.';
                $message .= "<br><br>Saldo suelo: $balanceFloor";
                $message .= "<br>Saldo máquina: $balanceMachine";
                $message .= "<br>Saldo fisioterapia: $balanceIndividual";
                $message .= "<br><br> ¿Desea asignar la sesión al cliente y poner su saldo como negativo?";
            }
        }






        if ($response) {
            return ['success' => true, 'error' => $message];
        } else {
            return ['success' => false, 'error' => $message];
        }
    }

    public static function getRealBalance($id_client)
    {

        $client = Client::where("id", $id_client)->first();
        $sessionsClient = Session::where("id_client", $id_client)->where('status', 'enable')->get();

        $sumMachine = 0;
        $sumFloor = 0;
        $sumIndividual = 0;


        foreach ($sessionsClient as $session) {
            $sumMachine += floatval($session->sessions_machine);//1
            $sumFloor += floatval($session->sessions_floor);//2
            $sumIndividual += floatval($session->sessions_individual);//0
        }

        $balanceMachine = (floatval($client->sessions_machine)) - $sumMachine;
        $balanceFloor = (floatval($client->sessions_floor)) - $sumFloor;
        $balanceIndividual = (floatval($client->sessions_individual)) - $sumIndividual;

        return ['sessions_machine' => $balanceMachine, 'sessions_floor' => $balanceFloor, 'sessions_individual' => $balanceIndividual];
    }

    public static function getRealStatusGroup($id_group, $date_start, $date_end, $formated = false, $fromFormate = 'Y-m-d g:i A')
    {

        $date_start = DateTime::createFromFormat($fromFormate, $date_start)->format('Y-m-d H:i:s');
        $date_end = DateTime::createFromFormat($fromFormate, $date_end)->format('Y-m-d H:i:s');

        $group = Group::join('room', 'room.id', '=', 'group.id_room')
            ->where('group.id', $id_group)
            ->get([
                'room.capacity as capacity_room',
                'room.type_room'
            ])->first();

        $sessions = Session::where('date_start', $date_start)
            ->where('date_end', $date_end)
            ->where('id_group', $id_group)
            ->whereNotNull('session.id_client')
            ->get(['id'])->count();



        $feeSpaces = (floatval($group->capacity_room)) - floatval($sessions);
        if ($feeSpaces <= 0)
            $feeSpaces = 0;



        $status = 'Completo';
        if ($formated) {
            if ($feeSpaces <= 0) {
                $status = 'Completo';
            } else if ($sessions > 0 && $sessions < $group->capacity_room) {
                $status = 'Espacios libres: ' . $feeSpaces;
            } else if ($sessions <= 0) {
                $status = 'Vacío';
            }
        } else {
            if ($feeSpaces <= 0) {
                $status = false;
            } else if ($sessions > 0 && $sessions < $group->capacity_room) {
                $status = true;
            } else if ($sessions <= 0) {
                $status = true;
            }
        }
        return $status;
    }


    public static function getRealStatusGroupTemplate($id_group, $start, $end, $formated = false, $id_template, $day, $fromFormate = 'H:i')
    {

        $date_start = DateTime::createFromFormat($fromFormate, $start)->format('H:i:s');
        $date_end = DateTime::createFromFormat($fromFormate, $end)->format('H:i:s');

        $group = Group::join('room', 'room.id', '=', 'group.id_room')
            ->where('group.id', $id_group)
            ->get([
                'room.capacity as capacity_room',
                'room.type_room'
            ])->first();

        $sessions = SessionTemplate::
            where('start', $date_start)
            ->where('end', $date_end)
            ->where('id_group', $id_group)
            ->where('id_template', $id_group)
            ->where('day', $day)
            ->whereNotNull('id_client')
            ->get(['id'])->count();


        $feeSpaces = (floatval($group->capacity_room)) - floatval($sessions);
        if ($feeSpaces <= 0)
            $feeSpaces = 0;



        $status = 'Completo';
        if ($formated) {
            if ($feeSpaces <= 0) {
                $status = 'Completo';
            } else if ($sessions > 0 && $sessions < $group->capacity_room) {
                $status = 'Espacios libres: ' . $feeSpaces;
            } else if ($sessions <= 0) {
                $status = 'Vacío';
            }
        } else {
            if ($feeSpaces <= 0) {
                $status = false;
            } else if ($sessions > 0 && $sessions < $group->capacity_room) {
                $status = true;
            } else if ($sessions <= 0) {
                $status = true;
            }
        }
        return $status;
    }

    public static function getRealStatusGroupTemplateCapacity($id_group, $start, $end, $formated = false, $id_template, $day, $sessionsToAdd = 0, $fromFormate = 'H:i')
    {

        $date_start = DateTime::createFromFormat($fromFormate, $start)->format('H:i:s');
        $date_end = DateTime::createFromFormat($fromFormate, $end)->format('H:i:s');

        $group = Group::join('room', 'room.id', '=', 'group.id_room')
            ->where('group.id', $id_group)
            ->get([
                'room.capacity as capacity_room',
                'room.type_room'
            ])->first();

        $sessions = SessionTemplate::
            where('start', $date_start)
            ->where('end', $date_end)
            ->where('id_group', $id_group)
            ->where('id_template', $id_group)
            ->where('day', $day)
            ->whereNotNull('id_client')
            ->get(['id'])->count();
        $sessions = $sessions + $sessionsToAdd;

        $feeSpaces = (floatval($group->capacity_room)) - floatval($sessions);


        if ($feeSpaces < 0)
            return false;
        return true;
    }

    public static function getRealStatusGroupCapacity($id_group, $date_start, $date_end, $formated = false, $fromFormate = 'Y-m-d g:i A', $sessionsToAdd = 0)
    {

        $date_start = DateTime::createFromFormat($fromFormate, $date_start)->format('Y-m-d H:i:s');
        $date_end = DateTime::createFromFormat($fromFormate, $date_end)->format('Y-m-d H:i:s');

        $group = Group::join('room', 'room.id', '=', 'group.id_room')
            ->where('group.id', $id_group)
            ->get([
                'room.capacity as capacity_room',
                'room.type_room'
            ])->first();

        $sessions = Session::where('date_start', $date_start)
            ->where('date_end', $date_end)
            ->where('id_group', $id_group)
            ->whereNotNull('session.id_client')
            ->get(['id'])->count();
        $sessions = $sessions + $sessionsToAdd;



        $feeSpaces = (floatval($group->capacity_room)) - floatval($sessions);


        if ($feeSpaces < 0)
            return false;
        return true;

    }

    public static function getRealStatusGroupByNumFormat($id_group, $date_start, $date_end, $fromFormate = 'Y-m-d g:i A')
    {

        $date_start = DateTime::createFromFormat($fromFormate, $date_start)->format('Y-m-d H:i:s');
        $date_end = DateTime::createFromFormat($fromFormate, $date_end)->format('Y-m-d H:i:s');


        $group = Group::join('room', 'room.id', '=', 'group.id_room')
            ->where('group.id', $id_group)
            ->get([
                'room.capacity as capacity_room',
                'room.type_room'
            ])->first();

        $sessions = Session::where('date_start', $date_start)
            ->where('date_end', $date_end)
            ->where('id_group', $id_group)
            ->whereNotNull('session.id_client')
            ->get(['id'])
            ->count();


        $sessions = intval($sessions);

        $feeSpaces = (floatval($group->capacity_room)) - floatval($sessions);
        if ($feeSpaces <= 0)
            $feeSpaces = 0;

        $status = 1;
        $statusFormat = 'Completo';

        if ($feeSpaces <= 0) {
            $status = 1;
            $statusFormat = 'Completo';
        } else if ($sessions > 0 && $sessions < $group->capacity_room) {
            $status = 2;
            $statusFormat = 'Espacios libres: ' . $feeSpaces;
        } else if ($sessions <= 0) {
            $status = 3;
            $statusFormat = 'Vacío';
        }

        return ['num' => $status, 'format' => $statusFormat];
    }

    public static function getRealStatusGroupByNumFormatCalendar($id_group, $room, $date_start, $date_end)
    {



        $sessions = Session::where('date_start', $date_start)
            ->where('date_end', $date_end)
            ->where('id_group', $id_group)
            ->whereNotNull('session.id_client')
            ->get(['id'])
            ->count();


        $sessions = intval($sessions);

        $feeSpaces = (floatval($room->capacity)) - floatval($sessions);
        if ($feeSpaces <= 0)
            $feeSpaces = 0;

        $status = 1;
        $statusFormat = 'Completo';

        if ($feeSpaces <= 0) {
            $status = 1;
            $statusFormat = 'Completo';
        } else if ($sessions > 0 && $sessions < $room->capacity) {
            $status = 2;
            $statusFormat = 'Espacios libres: ' . $feeSpaces;
        } else if ($sessions <= 0) {
            $status = 3;
            $statusFormat = 'Vacío';
        }

        return ['num' => $status, 'format' => $statusFormat];
    }

    public static function getRealStatusGroupByNumFormatTemplate($id_group, $room, $start, $end, $day, $template)
    {



        $sessions = SessionTemplate::
            where('start', $start)
            ->where('end', $end)
            ->where('id_group', $id_group)
            ->where('day', $day)
            ->where('id_template', $template)
            ->whereNotNull('session_template.id_client')
            ->get(['id'])
            ->count();


        $sessions = intval($sessions);

        $feeSpaces = (floatval($room->capacity)) - floatval($sessions);
        if ($feeSpaces <= 0)
            $feeSpaces = 0;

        $status = 1;
        $statusFormat = 'Completo';

        if ($feeSpaces <= 0) {
            $status = 1;
            $statusFormat = 'Completo';
        } else if ($sessions > 0 && $sessions < $room->capacity) {
            $status = 2;
            $statusFormat = 'Espacios libres: ' . $feeSpaces;
        } else if ($sessions <= 0) {
            $status = 3;
            $statusFormat = 'Vacío';
        }

        return ['num' => $status, 'format' => $statusFormat];
    }




    public static function getRealStatusGroupGlobal($id_group, $date_now, $fromFormate = 'Y-m-d g:i A')
    {

        $date_now = DateTime::createFromFormat($fromFormate, $date_now)->format('Y-m-d H:i:s');
        $date_now_date = DateTime::createFromFormat($fromFormate, $date_now)->format('Y-m-d');


        $group = Group::join('room', 'room.id', '=', 'group.id_room')
            ->where('group.id', $id_group)
            ->get([
                'room.capacity as capacity_room',
                'room.type_room'
            ])->first();

        $sessions = Session::where('date_start', '<=', $date_now)
            ->where('date_end', '>=', $date_now)
            ->where(DB::raw("(DATE_FORMAT(date_end,'%Y-%m-%d'))"), "=", $date_now_date)
            ->where(DB::raw("(DATE_FORMAT(date_start,'%Y-%m-%d'))"), "=", $date_now_date)
            ->where('id_group', $id_group)
            ->whereNotNull('session.id_client')
            ->get(['id'])->count();



        $feeSpaces = (floatval($group->capacity_room)) - floatval($sessions);
        if ($feeSpaces <= 0)
            $feeSpaces = 0;



        $status = 'Completo';
        if ($feeSpaces <= 0) {
            $status = 'Completo';
        } else if ($sessions > 0 && $sessions < $group->capacity_room) {
            $status = 'Espacios libres: ' . $feeSpaces;
        } else if ($sessions <= 0) {
            $status = 'Vacío';
        }

        return $status;
    }

    public static function getNowDateFormatType1($useUnderscore = false)
    {

        $dayName = '';
        switch (date('w')) {
            case '1':
                $dayName = 'Lunes';
                break;
            case '2':
                $dayName = 'Martes';
                break;
            case '3':
                $dayName = 'Miércoles';
                break;
            case '4':
                $dayName = 'Jueves';
                break;
            case '5':
                $dayName = 'Viernes';
                break;
            case '6':
                $dayName = 'Sábado';
                break;
            case '0':
                $dayName = 'Domingo';
                break;
        }

        $dayName = $dayName . ", " . date('d') . " de " . date('F') . ' del ' . date('Y');

        return $dayName;
    }

    public static function getNowDayName()
    {

        $dayName = '';
        switch (date('w')) {
            case '1':
                $dayName = 'Lunes';
                break;
            case '2':
                $dayName = 'Martes';
                break;
            case '3':
                $dayName = 'Miércoles';
                break;
            case '4':
                $dayName = 'Jueves';
                break;
            case '5':
                $dayName = 'Viernes';
                break;
            case '6':
                $dayName = 'Sábado';
                break;
            case '0':
                $dayName = 'Domingo';
                break;
        }

        return $dayName;
    }


    public static function getStatusBackup($statusCode)
    {

        $status = '';
        switch ($statusCode) {
            case '0':
                $status = 'Creando...';
                $status = "<span class='status-gray p-1'>$status</span>";
                break;
            case '1':
                $status = 'Procesando...';
                $status = "<span class='status-blue p-1'>$status</span>";
                break;
            case '2':
                $status = 'Excepción: copia local-si, dropbox-no';
                $status = "<span class='status-yellow p-1'>$status</span>";
                break;
            case '3':
                $status = 'Creada con éxito';
                $status = "<span class='status-green p-1'>$status</span>";
                break;
            case '4':
                $status = 'Error';
                $status = "<span class='status-red p-1'>$status</span>";
                break;
        }



        return $status;
    }

    public static function getNameRolById($id_rol)
    {
        $role = Rol::where('id', $id_rol)->get('name')->first();
        return $role->name;
    }
    public static function getNotificationsEmployee($id_employee, $id_rol)
    {

        return Notification::
            where('cod_receiver', $id_employee)
            ->where('type_receiver', $id_rol)
            ->orderBy('id', 'desc')
            ->offset(0)
            ->limit(25)
            ->get(['*']);

    }

    public static function getNotificationsNoRead($id_employee, $id_rol)
    {

        return Notification::
            where('cod_receiver', $id_employee)
            ->where('type_receiver', $id_rol)
            ->where('status', 'no_read')->count();

    }

    public static function sendNotification(
        $title_n = '',
        $msg_n = '',
        $path_n = '',
        $cod_sender = '',
        $cod_receiver = '',
        $type_sender = '',
        $type_receiver = '',
        $type_notification = '',
        $use_lang_title = 'false',
        $use_lang_msg = 'false',
        $paramsTitleNotifi = array(),
        $paramsMsgNotifi = array(),
        $sendMail = false,
        $icon = '', //name-image.jpg or html code
        $type_icon = '' //html-class, image-public
    ) {
        $dateCreate = date('Y-m-d H:i:s');
        $urlApiNotifications = config('app.api_notifications_socket') . "/api" . "/" . config('app.mode_notifications_socket');
        $notification = Notification::create([
            'title' => $title_n,
            'message' => $msg_n,
            'status' => 'no_read',
            'path' => $path_n,
            'params_title' => json_encode($paramsTitleNotifi, JSON_UNESCAPED_SLASHES),
            'params_message' => json_encode($paramsMsgNotifi, JSON_UNESCAPED_SLASHES),
            'cod_sender' => (empty($cod_sender)) ? null : $cod_sender,
            'cod_receiver' => (empty($cod_receiver)) ? null : $cod_receiver,
            'type_sender' => (empty($type_sender)) ? null : $type_sender,
            'type_receiver' => (empty($type_receiver)) ? null : $type_receiver,
            'type_notification' => $type_notification,
            'date' => $dateCreate,
            'icon' => $icon,
            'type_icon' => $type_icon
        ]);
        if ($notification) {
            $notification = $notification->fresh();
            $post = array(
                'title_n' => $title_n,
                'msg_n' => $msg_n,
                'status' => 'no_read',
                'path_n' => $path_n,
                'cod_sender' => $cod_sender,
                'cod_receiver' => $cod_receiver,
                'type_sender' => $type_sender,
                'type_receiver' => $type_receiver,
                'type_notification' => $type_notification,
                'sex_sender' => 'male',
                'date_n_formated' => $dateCreate,
                'cod_n' => $notification->id,
                'icon' => $icon,
                'type_icon' => $type_icon,
            );

            $post = json_encode($post);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_URL, $urlApiNotifications);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($post)
                )
            );
            $data = curl_exec($ch);
            curl_close($ch);
            return true;
        } else {
            return false;
        }
    }

    public static function getStatusDayWorkEmployee($id_employee)
    {
        $nowDate = Carbon::now();

        $scheduleEmployee = Schedule::where('id_employee', $id_employee)->where('date_start', '>=', $nowDate->clone()->startOfWeek()->format('Y-m-d'))->where('date_end', '<=', $nowDate->clone()->endOfWeek()->format('Y-m-d'));
        $noWorkDays = NoWorkDay::get();

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];


        if ($scheduleEmployee->count() <= 0)
            return ['status_formated' => 'Aun no tiene establecido un horario de trabajo.', 'status' => false, 'in_time' => null, 'out_time' => null, 'date' => null, 'in_time_finish' => null, 'out_time_finish' => null];


        if ($scheduleEmployee->count() > 0) {

            if (Attendances::where('date', $nowDate->clone()->format('Y-m-d'))->where('id_employee', $id_employee)->exists())
                return ['status_formated' => 'Su jornada de hoy ya ha terminado.', 'status' => false, 'in_time' => null, 'out_time' => null, 'date' => null, 'in_time_finish' => null, 'out_time_finish' => null];

            if (NoWorkDay::where('date', $nowDate->clone()->format('Y-m-d'))->exists())
                return ['status_formated' => 'Hoy no es un día de trabajo.', 'status' => false, 'in_time' => null, 'out_time' => null, 'date' => null, 'in_time_finish' => null, 'out_time_finish' => null];

            if ($nowDate->clone()->format('N') == 6 || $nowDate->clone()->format('N') == 7)
                return ['status_formated' => 'Hoy no es un día de trabajo.', 'status' => false, 'in_time' => null, 'out_time' => null, 'date' => null, 'in_time_finish' => null, 'out_time_finish' => null];

            if (Holidays::where('start', '>=', $nowDate->clone()->format('Y-m-d'))->where('end', '<=', $nowDate->clone()->format('Y-m-d'))->where('id_employee', $id_employee)->where('status', 'accept')->exists())
                return ['status_formated' => 'Usted se encuentra en vacacíones.', 'status' => false, 'in_time' => null, 'out_time' => null, 'date' => null, 'in_time_finish' => null, 'out_time_finish' => null];

            $scheduleValidate = Schedule::where($days[intval($nowDate->clone()->format('N')) - 1], 'true')->where('id_employee', $id_employee)->where('date_start', '>=', $nowDate->clone()->startOfWeek()->format('Y-m-d'))->where('date_end', '<=', $nowDate->clone()->endOfWeek()->format('Y-m-d'));



            if ($scheduleValidate->count() > 0) {


                if (
                    Carbon::createFromFormat('H:i:s', $nowDate->clone()->format('H:i:s'), config('app.timezone_for_pilates')) > Carbon::createFromFormat('H:i:s', $scheduleValidate->first()->end, config('app.timezone_for_pilates'))
                    &&
                    InOut::where('date', $nowDate->clone()->format('Y-m-d'))->where('id_employee', $id_employee)->whereNotNull('in_time')->doesntExist()
                )
                    return ['status_formated' => 'Su jornada de hoy ya ha terminado.', 'status' => false, 'in_time' => null, 'out_time' => null, 'date' => null, 'in_time_finish' => null, 'out_time_finish' => null];

                $in_time_finish = InOut::where('date', $nowDate->clone()->format('Y-m-d'))->where('id_employee', auth()->user()->id)->whereNotNull('in_time')->get();
                $out_time_finish = InOut::where('date', $nowDate->clone()->format('Y-m-d'))->where('id_employee', auth()->user()->id)->whereNotNull('out_time')->get();

                $in_time_finish = ($in_time_finish->count() > 0) ? Carbon::createFromFormat('H:i:s', $in_time_finish->first()->in_time, config('app.timezone_for_pilates'))->format('g:i A') : null;
                $out_time_finish = ($out_time_finish->count() > 0) ? Carbon::createFromFormat('H:i:s', $out_time_finish->first()->out_time, config('app.timezone_for_pilates'))->format('g:i A') : null;

                if (Carbon::createFromFormat('H:i:s', $nowDate->clone()->format('H:i:s'), config('app.timezone_for_pilates')) < Carbon::createFromFormat('H:i:s', $scheduleValidate->first()->start, config('app.timezone_for_pilates')))
                    return ['status_formated' => 'Su jornada de hoy aún no comienza.', 'status' => false, 'in_time' => Carbon::createFromFormat('H:i:s', $scheduleValidate->first()->start, config('app.timezone_for_pilates'))->format('g:i A'), 'out_time' => Carbon::createFromFormat('H:i:s', $scheduleValidate->first()->end, config('app.timezone_for_pilates'))->format('g:i A'), 'date' => null, 'in_time_finish' => null, 'out_time_finish' => null, 'except' => true];

                return [
                    'status_formated' => 'Día laboral.',
                    'status' => true,
                    'in_time' => Carbon::createFromFormat('H:i:s', $scheduleValidate->first()->start, config('app.timezone_for_pilates'))->format('g:i A'),
                    'out_time' => Carbon::createFromFormat('H:i:s', $scheduleValidate->first()->end, config('app.timezone_for_pilates'))->format('g:i A'),
                    'date' => $nowDate->clone()->format('Y-m-d'),
                    'in_time_finish' => $in_time_finish,
                    'out_time_finish' => $out_time_finish
                ];
            }
            return ['status_formated' => 'El día de hoy no trabaja.', 'status' => false, 'in_time' => null, 'out_time' => null, 'date' => null];
        }
    }

    public static function getStatusEmployeeGroupBySessionGroup($id_employee, $start, $end, $passDoesHaveSchedule)
    {

        $flagScheduleEmployee = Schedule::where('id_employee', $id_employee);

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $dateStart = Carbon::createFromFormat('Y-m-d H:i:s', $start);
        $dateStartTmp = Carbon::createFromFormat('Y-m-d H:i:s', $start);
        $dateStartHoly = Carbon::createFromFormat('Y-m-d H:i:s', $start);
        $dateEnd = Carbon::createFromFormat('Y-m-d H:i:s', $end);

        if (Holidays::where('start', '>=', $dateStartHoly->format('Y-m-d'))->where('end', '<=', $dateStartHoly->format('Y-m-d'))->where('id_employee', $id_employee)->where('status', 'accept')->exists())
            return false;


        if ($flagScheduleEmployee->exists()) {
            $scheduleValidate = Schedule::
                where($days[intval($dateStart->format('N')) - 1], 'true')
                ->where('id_employee', $id_employee)
                ->where('date_start', '>=', $dateStart->startOfWeek()->format('Y-m-d'))
                ->where('date_end', '<=', $dateStart->endOfWeek()->format('Y-m-d'))
                ->where('start', '<=', $dateStartTmp->format('H:i:s'))
                ->where('end', '>=', $dateEnd->format('H:i:s'));
            if ($scheduleValidate->exists()) {
                return true;
            } else {
                return false;
            }
        } else {
            if ($passDoesHaveSchedule) {
                return true;
            } else {
                return false;
            }
        }
    }

    public static function getStatusEmployeeGroupBySessionGroupSetEmployee($id_employee, $start, $end, $passDoesHaveSchedule)
    {

        $flagScheduleEmployee = Schedule::where('id_employee', $id_employee);

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $dateStart = Carbon::createFromFormat('Y-m-d H:i:s', $start);
        $dateStartTmp = Carbon::createFromFormat('Y-m-d H:i:s', $start);
        $dateStartHoly = Carbon::createFromFormat('Y-m-d H:i:s', $start);
        $dateEnd = Carbon::createFromFormat('Y-m-d H:i:s', $end);

        if (Holidays::where('start', '>=', $dateStartHoly->format('Y-m-d'))->where('end', '<=', $dateStartHoly->format('Y-m-d'))->where('id_employee', $id_employee)->where('status', 'accept')->exists())
            return false;


        if ($flagScheduleEmployee->exists()) {
            $scheduleValidate = Schedule::
                where($days[intval($dateStart->format('N')) - 1], 'true')
                ->where('id_employee', $id_employee)
                ->where('date_start', '>=', $dateStart->startOfWeek()->format('Y-m-d'))
                ->where('date_end', '<=', $dateStart->endOfWeek()->format('Y-m-d'))
                ->where('start', '<=', $dateStartTmp->format('H:i:s'))
                ->where('end', '>=', $dateEnd->format('H:i:s'));
            if ($scheduleValidate->exists()) {
                return true;
            } else {
                return false;
            }
        } else {
            if ($passDoesHaveSchedule) {
                return true;
            } else {
                return false;
            }
        }
    }

    public static function getStatusStatusModuleAssitances()
    {

        $configuration = Configuration::first();

        if (empty($configuration)) {

            return false;
        } else {
            if ($configuration->asisstance_module_status == "true") {
                return true;
            } else {
                return false;
            }

        }
    }

    public static function setAudit($action = false, $customText = false)
    {
        // Define the path
        $directory = 'auditorias';
        $filename = 'auditoria_' . Carbon::now()->format('Ymd') . '.log';
        $path = $directory . '/' . $filename;
    
        // Ensure the directory exists
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }
    
        // Get the full path
        $fullPath = Storage::disk('public')->path($path);
    
        // Update the audit log channel's path
        config(['logging.channels.audit.path' => $fullPath]);
    
        // Log the custom text or action
        if ($customText !== false && trim($customText) !== "") {
            Log::channel('audit')->info($customText);
        } elseif ($action !== false && trim($action) !== "") {
            if (auth()->check()) {
                $nameUser = auth()->user()->name . ' ' . auth()->user()->last_name;
                $time = Carbon::now()->format('H:i:s');
                Log::channel('audit')->info("{$time} - usuario: {$nameUser} - {$action}");
            }
        }
    }


}
