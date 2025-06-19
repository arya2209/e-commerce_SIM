<?php
session_start();
require "koneksi.php";
require "midtrans_config.php";

use Midtrans\Snap;

$nama_penerima = $_POST['nama_penerima'];
$alamat = $_POST['alamat'];
$total_harga = $_POST['total_harga'];
$email = $_POST['email'];
$telepon = $_POST['telepon'];

$order_id = "ORDER-" . time();

$params = [
    'transaction_details' => [
        'order_id' => $order_id,
        'gross_amount' => (int)$total_harga,
    ],
    'customer_details' => [
        'first_name' => $nama_penerima,
        'email' => $email,
        'phone' => $telepon,
    ],
    'enabled_payments' => ['bank_transfer'],
    'bank_transfer' => [
        'bank' => 'bni'
    ]
];

$snapToken = Snap::getSnapToken($params);
echo json_encode(['token' => $snapToken]);
