<?php

namespace App\Http\Controllers;

use App\Helpers\Pilates;
use App\Http\Requests\ValidationInAttendances;
use App\Http\Requests\ValidationOutAttendances;
use App\Models\InOut;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AttendancesController extends Controller
{
    
    public function index()
    {
    
    }

    public function setTimeIn(ValidationInAttendances $request){
        
    $days=['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
    $nowDate=Carbon::now();
    $scheduleToday=Schedule::where($days[intval($nowDate->clone()->format('N'))-1],'true')->where('id_employee',auth()->user()->id)->first();
    InOut::create([
        'date'=>$nowDate->clone()->format('Y-m-d'),
        'in_time'=>$nowDate->clone()->format('H:i:s'),
        'out_time'=>null,
        'id_employee'=>auth()->user()->id,
        'o_in_time'=>$scheduleToday->start,
        'o_out_time'=>$scheduleToday->end
        ]);


    return  Redirect::back()->with('success','La hora de entrada ha sido establecida.');

    }


    public function setTimeOut(ValidationOutAttendances $request){
        $nowDate=Carbon::now();

        InOut::where('date',$nowDate->clone()->format('Y-m-d'))
        ->where('id_employee',auth()->user()->id)
        ->whereNotNull('in_time')
        ->update(['out_time'=>$nowDate->clone()->format('H:i:s')]);
       

        return  Redirect::back()->with('success','La hora de salida ha sido establecida.');
    }

 
}
