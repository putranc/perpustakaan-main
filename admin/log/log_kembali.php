<section class="content-header">
    <h1 style="text-align:center;">
        Riwayat Pengembalian Buku
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="index.php">
                <i class="fa fa-home"></i> <b>Si Perpustakaan</b>
            </a>
        </li>
    </ol>
</section>

<section class="content">
<div class="box box-primary">
<div class="box-body">
<div class="table-responsive">
<table id="example1" class="table table-bordered table-striped">
<thead>
<tr>
    <th>No</th>
    <th>Judul Buku</th>
    <th>Peminjam</th>
    <th>Tanggal Dikembalikan</th>
</tr>
</thead>
<tbody>
<?php
$no = 1;
$sql = $koneksi->query("
    SELECT 
        b.judul_buku, 
        a.nama, 
        s.tgl_dikembalikan
    FROM tb_sirkulasi s
    INNER JOIN tb_buku b ON s.id_buku = b.id_buku
    INNER JOIN tb_anggota a ON s.id_anggota = a.id_anggota
    WHERE s.status = 'KEM' 
    AND s.tgl_dikembalikan IS NOT NULL 
    AND s.tgl_dikembalikan <> '0000-00-00'
    ORDER BY s.tgl_dikembalikan DESC
");

while ($data = $sql->fetch_assoc()) {
    $tgl_dikembalikan = !empty($data['tgl_dikembalikan']) 
        ? date("d/M/Y", strtotime($data['tgl_dikembalikan'])) 
        : "-";
?>
<tr>
    <td><?= $no++; ?></td>
    <td><?= htmlspecialchars($data['judul_buku']); ?></td>
    <td><?= htmlspecialchars($data['nama']); ?></td>
    <td><?= $tgl_dikembalikan; ?></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>
</div>
</section>
