<?php

namespace App\Concerns;

use Carbon\Carbon;

trait LogsWithTimestamps
{
    protected function displayLogs(string $message = '', string $type = 'info'): void
    {
        $this->{$type}("[" . Carbon::now()->format('d/m/Y H:i:s') . "] $message");
    }
}
