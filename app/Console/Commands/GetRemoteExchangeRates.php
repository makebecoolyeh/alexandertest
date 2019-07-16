<?php

namespace App\Console\Commands;

use App\Services\ExchangeRateService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GetRemoteExchangeRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remExcRates:get {--insert : Whether the data should be inserted to database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get exchange rates from REST API Privat24 (remote resource)';

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
        $data = ExchangeRateService::getRemoteExchangeRates();
        if($this->option('insert') && count($data) !== 0) {
            DB::table('exchange_rates')->insert($data);
        }
    }
}
