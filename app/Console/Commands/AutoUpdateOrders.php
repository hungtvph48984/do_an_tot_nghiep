<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use App\Models\Order;

class AutoUpdateOrders extends Command
{
    // Tên lệnh artisan
    protected $signature = 'orders:auto-update';

    protected $description = 'Tự động: sau 3 ngày từ khi đang giao -> chuyển ĐÃ GIAO; và sau 7 ngày giao -> tắt quyền trả hàng.';

    public function handle(): int
    {
        $hasDeliveredAt = Schema::hasColumn('orders', 'delivered_at');
        $hasCanReturn   = Schema::hasColumn('orders', 'can_return');

        // 1) Sau 3 ngày: ĐANG GIAO -> ĐÃ GIAO 
        $shippingCutoff = now()->subDays(3);
        $this->info("Đang check đơn hàng status=3 cutoff=" . now()->subDays(3));


        $countDelivered = 0;
        Order::where('status', Order::STATUS_SHIPPING)
            ->where('updated_at', '<=', $shippingCutoff)
            ->chunkById(200, function ($orders) use ($hasDeliveredAt, &$countDelivered) {
                foreach ($orders as $order) {
                    $order->status = Order::STATUS_DELIVERED;
                    if ($hasDeliveredAt && is_null($order->delivered_at)) {
                        $order->delivered_at = now();
                    }
                    $order->save();
                    $countDelivered++;
                }
            });

        $this->info("Auto-delivered {$countDelivered} order(s) that stayed SHIPPING >= 3 days.");

        // 2) Sau 7 ngày kể từ thời điểm giao: tắt quyền trả hàng (nếu có cột can_return)
        if ($hasCanReturn) {
            $returnCutoff = now()->subDays(7);

            $eligible = Order::query()
                ->where('status', Order::STATUS_DELIVERED)
                ->when($hasDeliveredAt, function ($q) use ($returnCutoff) {
                    // Có delivered_at thì ưu tiên mốc này
                    $q->where(function ($qq) use ($returnCutoff) {
                        $qq->whereNotNull('delivered_at')
                           ->where('delivered_at', '<=', $returnCutoff);
                    })->orWhere(function ($qq) use ($returnCutoff) {
                        // fallback: không có delivered_at thì dùng updated_at
                        $qq->whereNull('delivered_at')
                           ->where('updated_at', '<=', $returnCutoff);
                    });
                }, function ($q) use ($returnCutoff) {
                    // Không có cột delivered_at thì dùng updated_at
                    $q->where('updated_at', '<=', $returnCutoff);
                })
                ->where('can_return', true);

            $countClosed = 0;
            $eligible->chunkById(200, function ($orders) use (&$countClosed) {
                foreach ($orders as $order) {
                    $order->can_return = false;
                    $order->save();
                    $countClosed++;
                }
            });

            $this->info("Closed return window for {$countClosed} delivered order(s) >= 7 days.");
        } else {
            $this->info("Skip return-window closing (column 'can_return' not found).");
        }

        return self::SUCCESS;
    }
}
