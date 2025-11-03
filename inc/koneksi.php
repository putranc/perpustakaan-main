<?php
$koneksi = mysqli_connect("localhost","root","","data_perpus");
if(!$koneksi){
    die("Koneksi gagal: ".mysqli_connect_error());
}
?>
