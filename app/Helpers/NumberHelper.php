<?php

if (! function_exists('short_number')) {
    function short_number($num)
    {
        $num = (float) $num;

        if ($num >= 1_000_000_000_000) return round($num / 1_000_000_000_000, 1) . 'T';
        if ($num >= 1_000_000_000)     return round($num / 1_000_000_000, 1) . 'B';
        if ($num >= 1_000_000)         return round($num / 1_000_000, 1) . 'M';
        if ($num >= 1_000)             return round($num / 1_000, 1) . 'K';

        return number_format($num, 0, ',', '.');
    }
}

if (! function_exists('short_number_idr')) {
    function short_number_idr($num)
    {
        return 'Rp ' . short_number($num);
    }
}
