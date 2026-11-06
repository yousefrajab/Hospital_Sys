<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

if (! function_exists('error_detail')) {
    /**
     * Text to append to a user-facing error message.
     *
     * In development (APP_DEBUG=true) this is the exception message. In production it is only a
     * short reference code, so SQL, file paths and other internals never reach the browser;
     * the full exception is written to the log under the same code for support to look up.
     */
    function error_detail(Throwable $e): string
    {
        if (config('app.debug')) {
            return $e->getMessage();
        }

        $reference = strtoupper(Str::random(8));
        Log::error("[ref {$reference}] " . $e->getMessage(), ['exception' => $e]);

        return trans('AdminSb.error_reference', ['code' => $reference]);
    }
}
