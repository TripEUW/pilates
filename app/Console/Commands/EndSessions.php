<?php

namespace App\Console\Commands;

use App\Mail\NoticeSessions;
use App\Models\Client;
use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
class EndSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'end:sessions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete sessions that have already ended and discount the corresponding balance to the client';

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
    
        $date_now=date('Y-m-d H:i:s');
        $sessions= Session::
         where('date_end','<',$date_now)
        ->get();
        $nowDate=Carbon::now();
        foreach ($sessions as $key => $session) {
           
            $dateSession=Carbon::createFromFormat('Y-m-d H:i:s',$session->date_end);
            $diff = $dateSession->diffInHours($nowDate);
           
            if($session->status=="enable"){
            $client=Client::where('id',$session->id_client);
            $client->decrement('sessions_machine', $session->sessions_machine);
            $client->decrement('sessions_floor', $session->sessions_floor);
            $client->decrement('sessions_individual', $session->sessions_individual);
            }
            
            if($diff>=48){
            Session::where('id',$session->id)->delete();
            }else{
            if($session->status=="enable"){
             Session::where('id',$session->id)->update(['status'=>'disable']);
            }
            }
         
        }
        Log::info('Delete sessions - Success');
        //Mail::to('frank.olv.dev@gmail.com')->send(new NoticeSessions);
    }
}
