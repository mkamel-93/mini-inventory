<?php

declare(strict_types=1);

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BaseListener implements ShouldQueue
{
    use InteractsWithQueue;
}
