<?php
// --- Hitung total data buku ---
$sql = $koneksi->query("SELECT COUNT(id_buku) AS buku FROM tb_buku");
$data = $sql->fetch_assoc();
$buku = $data['buku'];

// --- Hitung total data anggota ---
$sql = $koneksi->query("SELECT COUNT(id_anggota) AS agt FROM tb_anggota");
$data = $sql->fetch_assoc();
$agt = $data['agt'];

// --- Hitung jumlah buku yang sedang dipinjam ---
$sql = $koneksi->query("SELECT COUNT(id_sk) AS pin FROM tb_sirkulasi WHERE status='PIN'");
$data = $sql->fetch_assoc();
$pin = $data['pin'];

// --- Hitung jumlah buku yang sudah dikembalikan ---
$sql = $koneksi->query("SELECT COUNT(id_sk) AS kem FROM tb_sirkulasi WHERE status='KEM'");
$data = $sql->fetch_assoc();
$kem = $data['kem'];
?>

<!-- Content Header -->
<section class="content-header">
    <h1>
        Dashboard
        <small>Petugas</small>
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">

        <!-- BOX 1 - DATA BUKU -->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-blue">
                <div class="inner">
                    <h4><?= $buku; ?></h4>
                    <p>Buku</p>
                </div>
                <div class="icon">
                    <i class="ion ion-ios-book"></i>
                </div>
                <a href="?page=MyApp/data_buku" class="small-box-footer">
                    More info <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- BOX 2 - DATA ANGGOTA -->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h4><?= $agt; ?></h4>
                    <p>Anggota</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="?page=MyApp/data_agt" class="small-box-footer">
                    More info <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- BOX 3 - PEMINJAMAN (STATUS = PIN) -->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
                <div class="inner">
                    <h4><?= $pin; ?></h4>
                    <p>Peminjaman Aktif</p>
                </div>
                <div class="icon">
                    <i class="ion ion-refresh"></i>
                </div>
                <a href="?page=data_sirkul" class="small-box-footer">
                    More info <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- BOX 4 - PENGEMBALIAN (STATUS = KEM) -->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-red">
                <div class="inner">
                    <h4><?= $kem; ?></h4>
                    <p>Pengembalian</p>
                </div>
                <div class="icon">
                    <i class="ion ion-clipboard"></i>
                </div>
                <a href="?page=log_kembali" class="small-box-footer">
                    More info <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

    </div>
</section>
