<?php

if (! function_exists('format_pkr')) {
    function format_pkr(int $amount): string
    {
        return 'Rs. ' . number_format($amount);
    }
}
