<?php
// Tentukan detail koneksi sebagai konstanta
// Hanya definisikan jika belum ada (Opsional, tapi aman)

if (!defined('DB_SERVER')) {
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_DATABASE', 'data_perpus');
}

// Coba koneksi
// Pastikan tidak mencoba koneksi jika sudah ada (misalnya, jika $koneksi adalah objek koneksi di global scope)

if (!isset($koneksi) || $koneksi === false) {
    $koneksi = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
}

// Pengecekan koneksi
if(!$koneksi){
    die("Koneksi gagal: ".mysqli_connect_error());
}
?>