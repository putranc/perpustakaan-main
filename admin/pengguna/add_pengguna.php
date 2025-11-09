<section class="content-header">
    <h1>
        Pengguna Sistem
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="index.php">
                <i class="fa fa-home"></i>
                <b>Si Tabsis</b>
            </a>
        </li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Tambah Pengguna</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove">
                            <i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="nama_pengguna">Nama Pengguna</label>
                            <input type="text" name="nama_pengguna" id="nama_pengguna" class="form-control" placeholder="Nama pengguna" required maxlength="50">
                        </div>

                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="Username" required maxlength="30">
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                        </div>

                        <div class="form-group">
                            <label>Level</label>
                            <select name="level" id="level" class="form-control" required>
                                <option value="">-- Pilih Level --</option>
                                <option value="Administrator">Administrator</option>
                                <option value="Petugas">Petugas</option>
                            </select>
                        </div>

                    </div>
                    <div class="box-footer">
                        <input type="submit" name="Simpan" value="Simpan" class="btn btn-info">
                        <a href="?page=MyApp/data_pengguna" title="Kembali" class="btn btn-warning">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php
// ====================================================================
// PROSES SIMPAN DATA (Diperbaiki untuk Keamanan dan Syntax)
// ====================================================================

if (isset($_POST['Simpan'])){
    
    // 1. Ambil data POST (tanpa mysqli_real_escape_string karena akan pakai Prepared Statement)
    $nama_pengguna = $_POST['nama_pengguna'];
    $username      = $_POST['username'];
    $password      = $_POST['password'];
    $level         = $_POST['level'];

    // 2. Validasi Kelengkapan Data (Defect 02/03 terkait Username Kosong)
    if (empty($nama_pengguna) || empty($username) || empty($password) || empty($level)) {
        // Jika ada yang kosong, tampilkan error (Meski ada 'required', ini adalah fallback server-side)
        echo "<script>
        Swal.fire({title: 'Data Tidak Lengkap',text: 'Semua field wajib diisi!',icon: 'warning',confirmButtonText: 'OK'
        }).then((result) => {if (result.value){
            window.location = 'index.php?page=MyApp/add_pengguna';
            }
        })</script>";
        exit;
    }

    // 3. Cek Duplikasi Username menggunakan Prepared Statement (Perbaikan Defect 01)
    $stmt_check = $koneksi->prepare("SELECT username FROM tb_pengguna WHERE username = ?");
    if ($stmt_check === FALSE) {
        error_log("Error prepare check username: " . $koneksi->error);
        // Lanjutkan ke proses error Simpan Gagal jika Anda tidak ingin menampilkan error database ke user
    } else {
        $stmt_check->bind_param("s", $username);
        $stmt_check->execute();
        $stmt_check->store_result();
        
        if ($stmt_check->num_rows > 0) {
            // Username sudah ada
            $stmt_check->close();
            echo "<script>
            Swal.fire({title: 'Tambah Data Gagal',text: 'Username sudah digunakan!',icon: 'error',confirmButtonText: 'OK'
            }).then((result) => {if (result.value){
                window.location = 'index.php?page=MyApp/add_pengguna';
                }
            })</script>";
            exit;
        }
        $stmt_check->close();
    }
    
    // 4. Proses Simpan Data (INSERT) menggunakan Prepared Statement
    
    // Enkripsi Password (md5 sudah sangat tua, disarankan pakai password_hash)
    $hashed_password = md5($password); 
    
    $stmt_insert = $koneksi->prepare("INSERT INTO tb_pengguna (nama_pengguna, username, password, level) VALUES (?, ?, ?, ?)");
    
    if ($stmt_insert === FALSE) {
        $query_simpan = false; // Gagal Prepare
        error_log("Error prepare insert: " . $koneksi->error);
    } else {
        // Bind parameters: 4 string (s)
        $stmt_insert->bind_param("ssss", $nama_pengguna, $username, $hashed_password, $level);
        
        $query_simpan = $stmt_insert->execute();
        $stmt_insert->close();
    }
    
    // 5. Feedback ke User
    if ($query_simpan) {
        echo "<script>
        Swal.fire({title: 'Tambah Data Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'
        }).then((result) => {if (result.value){
            window.location = 'index.php?page=MyApp/data_pengguna';
            }
        })</script>";
    } else {
        echo "<script>
        Swal.fire({title: 'Tambah Data Gagal',text: 'Terjadi kesalahan saat menyimpan data.',icon: 'error',confirmButtonText: 'OK'
        }).then((result) => {if (result.value){
            window.location = 'index.php?page=MyApp/add_pengguna';
            }
        })</script>";
    }
}
?>