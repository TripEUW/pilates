<?php

namespace App\Console\Commands;

use App\Mail\NoticeSessions;
use App\Models\Client;
use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
class EndSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'end:subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'change status for subscriptions';

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
    
        Client::where('status','enable')->update(['suscription' => "false"]);
       
    }
}
