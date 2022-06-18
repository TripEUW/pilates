<?php

namespace App\Console\Commands;

use App\Models\Holidays;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
class DeleteHolidaysTimedOut extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:holidays';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove vacations that have not been accepted by the administrator';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $holidaysPending= Holidays::
         where('status','pending')
        ->get();

        foreach ($holidaysPending as $key => $holiday) {
            $dateStart = Carbon::createFromFormat('Y-m-d',$holiday->start,config('app.timezone_for_pilates'));
            $now =Carbon::createFromFormat('d/m/Y', Carbon::now()->format('d/m/Y'),config('app.timezone_for_pilates'));
            $dateStart=$dateStart->subDays(1);
            if($now==$dateStart){
            Holidays::where('id',$holiday->id)->delete();
            }
        }
        Log::info('Delete holidays time out - Success');
    }
}
