<?php

return [
    /*
    |--------------------------------------------------------------------------
    | QR Code Backend
    |--------------------------------------------------------------------------
    |
    | This option controls the default backend that is used to generate QR codes.
    | You may set this to "imagick" or "gd".
    |
    */

    'backend' => 'gd',

    /*
    |--------------------------------------------------------------------------
    | QR Code Style
    |--------------------------------------------------------------------------
    |
    | This option controls the style of the QR code. You may set this to "square",
    | "dot", or "round".
    |
    */

    'style' => 'square',

    /*
    |--------------------------------------------------------------------------
    | QR Code Size
    |--------------------------------------------------------------------------
    |
    | This option controls the size of the QR code in pixels.
    |
    */

    'size' => 300,

    /*
    |--------------------------------------------------------------------------
    | QR Code Margin
    |--------------------------------------------------------------------------
    |
    | This option controls the margin around the QR code in pixels.
    |
    */

    'margin' => 0,

    /*
    |--------------------------------------------------------------------------
    | QR Code Format
    |--------------------------------------------------------------------------
    |
    | This option controls the format of the QR code. You may set this to "png",
    | "svg", or "eps".
    |
    */

    'format' => 'png',

    /*
    |--------------------------------------------------------------------------
    | QR Code Error Correction Level
    |--------------------------------------------------------------------------
    |
    | This option controls the error correction level of the QR code. You may set
    | this to "L", "M", "Q", or "H".
    |
    */

    'error_correction' => 'H',
];