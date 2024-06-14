<?php

namespace App\Console\Commands;

use App\Models\ParcelOrder;
use Illuminate\Console\Command;

class AutoRejectOrderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-reject-order-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $ParcelOrders = ParcelOrder::where('status', 'new')->get();
        foreach ($ParcelOrders as $order) {
            $parcelDate = date_format(date_create($order->parcel_date), "d-m-Y");
            $today = date('d-m-Y');
            if (strtotime($parcelDate) < strtotime($today)) {
                $reason = 'out of date order';
                ParcelOrder::where('id', $order->id)->update([
                    'status' => 'canceled',
                    'reason' => $reason,
                ]);
            }
        }
        $this->info('Successfully update status.');
    }
}
