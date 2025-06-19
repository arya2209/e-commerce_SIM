<?php
require_once dirname(__FILE__) . '/vendor/autoload.php'; // Pastikan sudah install midtrans via Composer

\Midtrans\Config::$serverKey = 'SB-Mid-server-QGrQSTI3B52m5EMvqpYeI3Ap';
\Midtrans\Config::$isProduction = false; // Ganti jadi true jika sudah live
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;
