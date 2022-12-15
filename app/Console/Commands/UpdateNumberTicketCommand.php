<?php

namespace App\Console\Commands;

use App\Jobs\CreateOrUpdateUserTicketJob;
use App\Services\AuctionInfoService;
use App\Traits\ApiBscScanTransaction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateNumberTicketCommand extends Command
{
    use ApiBscScanTransaction;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:ticket {auction_id=4}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update number ticket of user Command';

    /**
     * The auction Info Service
     *
     * @var App\Services\AuctionInfoService
     */
    protected $auctionInfoService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->auctionInfoService = new AuctionInfoService();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        info("Start UpdateNumberTicket");
        $this->updateNumberTicket();
        info("End UpdateNumberTicket");
    }

    /**
     * Update Miss Transactions
     *
     * @return void
     */
    public function updateNumberTicket()
    {
        // get all transaction in blockchain
        $dataTickets = collect($this->getAllTransactionsBscScan('ticket', $this->argument('auction_id')));

        if (!empty($dataTickets)) {
            $auctionInfo = $this->auctionInfoService->infoNftAuctionById($this->argument('auction_id'));
            $startDate = Carbon::parse($auctionInfo->start_date, 'UTC')->getTimestamp();
            $endDate = Carbon::parse($auctionInfo->end_date, 'UTC')->getTimestamp();

            // filter value > 0
            $dataFiltered = $dataTickets->filter(function ($item) use($startDate, $endDate) {
                return $item['value'] > 0 &&
                       $item['timeStamp'] >= $startDate &&
                       $item['timeStamp'] <= $endDate;
            });
            // group value by wallet address
            $result[] = $dataFiltered->groupBy('from')->map(function ($row) {
                return [
                    'wallet_address' => $row->first()['from'],
                    'value' => $row->sum('value'),
                    'token_decimal' => $row->first()['tokenDecimal'],
                    'time_stamp' => $row->first()['timeStamp']
                ];
            })->values()->all();

            $result = collect(array_merge([], ...$result));

            // call job insert gacha tickets
            if (!empty($result)) {
                foreach ($result->chunk(20) as $k => $dataTicket) {
                    CreateOrUpdateUserTicketJob::dispatch(
                        $dataTicket,
                        $this->argument('auction_id')
                    )
                        ->onQueue(config('defines.queue.general'))
                        ->delay(now()->addSeconds(((int) $k + 1) * 2));
                }
            }
        }
    }
}