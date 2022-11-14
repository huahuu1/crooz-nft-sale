<?php

namespace App\Console\Commands;

use App\Models\ExchangeRate;
use Illuminate\Support\Facades\Http;
use Illuminate\Console\Command;
use App\Services\ExchangeRateService;
use Exception;
use Illuminate\Support\Facades\Log;

class UpdateExchangeRateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:exchange-rate';

    protected $exchangeRate;

    protected $exchangeRateService;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Exchange Rate Command';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->exchangeRate = new ExchangeRate();
        $this->exchangeRateService = new ExchangeRateService();
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $this->updateExchangeRate();
    }

    public function updateExchangeRate()
    {
        $symbols = 'JPY';
        $base = 'USD';

        try {
            $response = Http::withHeaders([
                'apikey' => config('defines.exchange_rate.api_key'),
            ])->get(config('defines.exchange_rate.url'), [
                'symbols' => $symbols,
                'base' => $base,
            ])->json();

            if (isset($response['success'])) {
                $symbol = $base . $symbols;
                $rate = $response['rates'][$symbols];
                $rateTimestamp = date('Y-m-d H:i:s', $response['timestamp']);
                $this->exchangeRateService->createExchangeRate($symbol, $rate, $rateTimestamp);

                Log::info('Get exchange rate was successful');
            }
        } catch (Exception $e) {
            Log::error($e);
        }
    }
}
