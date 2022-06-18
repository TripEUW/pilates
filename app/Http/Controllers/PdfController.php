<?php

namespace App\Http\Controllers;

use App\Helpers\Pilates;
use App\Models\Backup;
use App\Models\Client;
use App\Models\Configuration;
use App\Models\Employee;
use App\Models\Group;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Session;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use DatePeriod;
use Facade\FlareClient\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use SebastianBergmann\Environment\Console;
use ZipArchive;

class PdfController extends Controller
{
    public function invoice(Request $req,$id_group, $id_client)
    {
        dd($req->getClientIp());
          
        // $client = Client::where("id", $id_client)->first();
        // $groupRoom = Group::join('room', 'group.id_room', '=', 'room.id')->where("group.id", $id_group)->get();
        // $sessionsClient = Session::where("id_client", $id_client)->get(["*"]);
    }

    public function getData()
    {
        $data =  [
            'quantity'      => '1',
            'description'   => 'some ramdom text',
            'price'   => '500',
            'total'     => '500'
        ];
        return $data;
    }

public function printPhp()
{
           /*  $nowDate=Carbon::now();
            $dateSession=Carbon::createFromFormat('Y-m-d H:i:s','2020-02-06 09:00:00');
            $diff = $dateSession->diffInHours($nowDate);
          

            echo "diference days: ".$diff;
              */
           
            //  Pilates::setAudit('login');

            //return view('itinerary1');


            $dateF="2020-03-02";

               

            $itineraries=[];
            $groupsItineraries=[];
          
            $employees=Employee::get();


            foreach ($employees as $key => $employee) {
         $sessionsEmployee = Session::
             Join('group', 'session.id_group', '=', 'group.id')
          ->Join('employee', 'group.id_employee', '=', 'employee.id')
          ->where('date_start', '>=', "$dateF 00:00:00")
          ->where('date_end', '<=', "$dateF 23:59:59")
          ->where('employee.id',  $employee->id)
          ->groupBy('date_start','date_end','id_group')
          ->get(['employee.name as employee_name','employee.last_name as employee_last_name','group.name as group_name','date_start','date_end','group.id_room','group.id as group_id']);


            $itineraryEmployee=[];
            $itemItinerary=[];
            $itemsEmployee=[];

                 foreach ($sessionsEmployee as $key => $sessionEmployee) {
                     $itineraryEmployee['employee_name']="$sessionEmployee->employee_name $sessionEmployee->employee_last_name";

                     $itemItinerary['start']=Carbon::createFromFormat('Y-m-d H:i:s',$sessionEmployee->date_start)->format('H:i');
                     $itemItinerary['end']=Carbon::createFromFormat('Y-m-d H:i:s',$sessionEmployee->date_end)->format('H:i');
                     $itemItinerary['group_name']=$sessionEmployee->group_name;
                     $itemItinerary['room_name']=Room::where('id',$sessionEmployee->id_room)->get(['name'])->first()->name;
                     $itemItinerary['clients']=Session::
                     Join('client', 'session.id_client', '=', 'client.id')
                   ->where('id_group', $sessionEmployee->group_id)
                   ->where('date_start', '>=', $sessionEmployee->date_start)
                   ->where('date_end', '<=', $sessionEmployee->date_end)
                   ->whereNotNull('id_client')
                   ->get(['client.name','client.last_name'])->toArray();
                  array_push($itemsEmployee, $itemItinerary);
                 }

                 if(count($sessionsEmployee)>0){
                     $itineraryEmployee['date']= $dateF;
                     $itineraryEmployee['items_employee']=$itemsEmployee;
                     array_push($itineraries, $itineraryEmployee);
                 }


            }


          
        $count=0;

         do {

             $groupItineraries=[];
             if(isset($itineraries[$count])){array_push($groupItineraries,$itineraries[$count]);unset($itineraries[$count]);$count++;}
             if(isset($itineraries[$count])){array_push($groupItineraries,$itineraries[$count]);unset($itineraries[$count]);$count++;}
             if(isset($itineraries[$count])){array_push($groupItineraries,$itineraries[$count]);unset($itineraries[$count]);$count++;}

             if(count($groupItineraries)>0)
             array_push($groupsItineraries,$groupItineraries);
             
         } while(count($itineraries)>0);

            print_r($itineraries);
            print_r("/////////////groups <br>");
            print_r($groupsItineraries);

}


}
