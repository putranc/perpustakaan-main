<?php
include "inc/koneksi.php";
$title_web = "Laporan Perpustakaan - Data Anggota";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="assets_style/assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets_style/assets/bower_components/font-awesome/css/font-awesome.min.css">
    <title><?php echo $title_web; ?></title>
</head>
<body onload="window.print()" style="font-family: Quicksand, sans-serif;">
    <h3 class='text-center' style='margin-top:30px;'>.:: Laporan Perpustakaan ::.</h3>
    <h4 class='text-center'>Data Anggota</h4>
    <?php
    $query = "SELECT * FROM tb_anggota";
    $sql = mysqli_query($koneksi, $query);
    ?>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th style="text-align:center">No</th>
                <th style="text-align:center">ID Anggota</th>
                <th style="text-align:center">Nama</th>
                <th style="text-align:center">Jenis Kelamin</th>
                <th style="text-align:center">Kelas</th>
                <th style="text-align:center">No Telepon</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $no = 1;
        if ($sql && mysqli_num_rows($sql) > 0) {
            while ($data = mysqli_fetch_assoc($sql)) {
                echo "<tr>";
                echo "<td style='text-align:center'>".$no++."</td>";
                echo "<td style='text-align:center'>".$data['id_anggota']."</td>";
                echo "<td>".$data['nama']."</td>";
                echo "<td style='text-align:center'>".$data['jekel']."</td>";
                echo "<td style='text-align:center'>".$data['kelas']."</td>";
                echo "<td style='text-align:center'>".$data['no_hp']."</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6' style='text-align:center;'>Data tidak ada</td></tr>";
        }
        ?>
        </tbody>
    </table>
</body>
</html>
