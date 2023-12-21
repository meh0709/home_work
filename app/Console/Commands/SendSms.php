<?php

namespace App\Console\Commands;

use App\Services\TurboSms\TurboSmsService;
use Illuminate\Console\Command;

class SendSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sendSms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle(TurboSmsService $turboSmsService)
    {
        $res = $turboSmsService->sendSms('380668318185', 'test');
        dd($res);
        return 0;
    }
}
