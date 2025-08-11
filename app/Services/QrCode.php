<?php

namespace App\Services;

class QrCode
{
    private static $size = 100;

    public static function size($size)
    {
        $instance = new static();
        static::$size = $size;
        return $instance;
    }

    public function generate($data)
    {
        // Using Google Charts API for QR code generation
        $size = static::$size;
        $url = "https://chart.googleapis.com/chart?chs={$size}x{$size}&cht=qr&chl=" . urlencode($data);
        
        return '<img src="' . $url . '" alt="QR Code" width="' . $size . '" height="' . $size . '" />';
    }
}