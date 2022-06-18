<?php

namespace App\Console\Commands;

use App\Helpers\Pilates;
use App\Mail\NoticeSessions;
use App\Models\Attendances as ModelsAttendances;
use App\Models\Employee;
use App\Models\Holidays;
use App\Models\InOut;
use App\Models\NoWorkDay;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
class Attendances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:attendances';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'set faults and assists and times';

    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {

        if (Pilates::getStatusStatusModuleAssitances()) {

            $admins = Employee::where('id_rol', 1)->get();
            $nowDate = Carbon::now();
            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

            $schedules = Schedule::where('date_start', '>=', $nowDate->clone()->startOfWeek()->format('Y-m-d'))->where('date_end', '<=', $nowDate->clone()->endOfWeek()->format('Y-m-d'))->get();


            foreach ($schedules as $key => $schedule) {
                $scheduleValidate = Schedule::where($days[intval($nowDate->clone()->format('N')) - 1], 'true')->where('id_employee', $schedule->id_employee)->where('date_start', '>=', $nowDate->clone()->startOfWeek()->format('Y-m-d'))->where('date_end', '<=', $nowDate->clone()->endOfWeek()->format('Y-m-d'));
                if (
                    !(NoWorkDay::where('date', $nowDate->clone()->format('Y-m-d'))->exists()) &&
                    ($nowDate->clone()->format('N') != 6 && $nowDate->clone()->format('N') != 7) &&
                    !(Holidays::where('start', '>=', $nowDate->clone()->format('Y-m-d'))->where('end', '<=', $nowDate->clone()->format('Y-m-d'))->where('id_employee', $schedule->id_employee)->where('status', 'accept')->exists()) &&
                    $scheduleValidate->count() > 0
                ) {


                    $scheduleValidateTmp = $scheduleValidate->first();
                    $timeOutToday = Carbon::createFromFormat('H:i:s', $scheduleValidateTmp->end, config('app.timezone_for_pilates'));
                    $nowTime = Carbon::createFromFormat('H:i:s', $nowDate->clone()->format('H:i:s'), config('app.timezone_for_pilates'));
                    $scheduleToday = InOut::where('date', $nowDate->clone()->format('Y-m-d'))->where('id_employee', $schedule->id_employee)->whereNotNull('in_time');
                    $existAttendance = ModelsAttendances::where('date', $nowDate->clone()->format('Y-m-d'))->where('id_employee', $schedule->id_employee);
                    if (
                        $nowTime > $timeOutToday
                        &&
                        $scheduleToday->doesntExist()
                        &&
                        $existAttendance->doesntExist()
                    ) {
                        ModelsAttendances::create([
                            'date' => $nowDate->clone()->format('Y-m-d'),
                            'o_in_time' => $scheduleValidateTmp->start,
                            'o_out_time' => $scheduleValidateTmp->end,
                            'in_time' => null,
                            'out_time' => null,
                            'status' => 'absent',
                            'id_employee' => $schedule->id_employee
                        ]);
                        foreach ($admins as  $admin) {
                            $employeeItem = Employee::where('id', $schedule->id_employee)->get(['name', 'last_name'])->first();
                            //start notification
                            $title_n = 'Falta injustificada.';
                            $msg_n =  "Empleado: $employeeItem->name $employeeItem->last_name";
                            $path_n = 'management_employee';
                            $cod_sender = '';
                            $cod_receiver = $admin->id;
                            $type_sender = 0;
                            $type_receiver = $admin->id_rol;
                            $type_notification = 'redirect'; //redirect,message,modal_redirect,none
                            $use_lang_title = 'false';
                            $use_lang_msg = 'false';
                            $paramsTitleNotifi = [];
                            $paramsMsgNotifi = [];
                            $sendMail = false;
                            $icon = 'flaticon2-time icon-font-red'; //name-image.jpg or html code
                            $type_icon = 'html-class'; //html-class, image-public
                            Pilates::sendNotification($title_n, $msg_n, $path_n, $cod_sender, $cod_receiver, $type_sender, $type_receiver, $type_notification, $use_lang_title, $use_lang_msg, $paramsTitleNotifi, $paramsMsgNotifi, $sendMail, $icon, $type_icon);
                            //end notification
                        }
                    }
                }

                $insOutsEmployee = InOut::where('id_employee', $schedule->id_employee)->get();


                foreach ($insOutsEmployee as $key => $inOutEmployee) {
                    $dateInOutEmployee = Carbon::createFromFormat('Y-m-d', $inOutEmployee->date, config('app.timezone_for_pilates'));
                    $nowDateInOut = Carbon::createFromFormat('Y-m-d', $nowDate->clone()->format('Y-m-d'), config('app.timezone_for_pilates'));

                    if ($inOutEmployee->in_time != null && $inOutEmployee->out_time != null) {
                        ModelsAttendances::create([
                            'date' => $dateInOutEmployee->format('Y-m-d'),
                            'o_in_time' => $inOutEmployee->o_in_time,
                            'o_out_time' => $inOutEmployee->o_out_time,
                            'in_time' => $inOutEmployee->in_time,
                            'out_time' => $inOutEmployee->out_time,
                            'status' => 'attended',
                            'id_employee' => $schedule->id_employee
                        ]);

                        InOut::where('id', $inOutEmployee->id)->delete();
                    } else {

                        if ($nowDateInOut > $dateInOutEmployee) {
                            ModelsAttendances::create([
                                'date' => $dateInOutEmployee->format('Y-m-d'),
                                'o_in_time' => $inOutEmployee->o_in_time,
                                'o_out_time' => $inOutEmployee->o_out_time,
                                'in_time' => $inOutEmployee->in_time,
                                'out_time' => ($inOutEmployee->out_time != null) ? $inOutEmployee->out_time : null,
                                'status' => 'attended',
                                'id_employee' => $schedule->id_employee
                            ]);
                            InOut::where('id', $inOutEmployee->id)->delete();
                        }
                    }
                }
            }
        }
    }
}
