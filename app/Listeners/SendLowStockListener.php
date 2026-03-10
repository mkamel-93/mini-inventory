<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\LowStockDetectedEvent;

class SendLowStockListener extends BaseListener
{
    /**
     * Handle the event.
     */
    public function handle(LowStockDetectedEvent $event): void
    {
        logger("Low stock detected for {$event->inventoryItem->name} in warehouse {$event->warehouse->name}");
    }
}
