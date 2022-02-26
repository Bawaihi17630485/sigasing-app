<?php
if (isset($_GET['id'])) {

    $database = new Database();
    $db = $database->getConnection();

    $id = $_GET['id'];
    $findSql = "SELECT K.*, P.* FROM karyawan K
                LEFT JOIN pengguna P ON P.id = K.pengguna_id WHERE K.id = ?";
    $stmt = $db->prepare($findSql);
    $stmt->bindParam(1, $_GET['id']);
    $stmt->execute();
    $row = $stmt->fetch();
    if (isset($row['id'])) {
        if (isset($_POST['button_update'])) {

            $database = new Database();
            $db = $database->getConnection();
        
            $validateSql = "SELECT * FROM karyawan WHERE nik = ? AND id != ?";
            $stmt = $db->prepare($validateSql);
            $stmt->bindParam(1, $_POST['nik']);
            $stmt->bindParam(2, $_POST['id']);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {           
?>
                 <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                    <h5><i class="icon fas fa-ban"></i> Gagal</h5>
                    Nik karyawan sama sudah ada
                 </div>
    <?php
        } else {
            $md5Password = md5($_POST['password']);
            $updateSqlpengguna = "UPDATE pengguna SET username = ?, password = ?, peran = ? WHERE id = ?";
            $stmtpengguna = $db->prepare($updateSqlpengguna);
            $stmtpengguna->bindParam(1, $_POST['username']);
            $stmtpengguna->bindParam(2, $md5Password);
            $stmtpengguna->bindParam(3, $_POST['peran']);
            $stmtpengguna->bindParam(4, $_POST['id']);
            if ($stmtpengguna->execute()) {    
                $updateSqlkaryawan = "UPDATE karyawan SET nik = ?, nama_lengkap = ?, handphone = ?, email = ?, tanggal_masuk = ? WHERE id = ?";
                $stmtKaryawan = $db->prepare($updateSqlkaryawan);
                $stmtKaryawan->bindParam(1, $_POST['nik']);
                $stmtKaryawan->bindParam(2, $_POST['nama_lengkap']);
                $stmtKaryawan->bindParam(3, $_POST['handphone']);
                $stmtKaryawan->bindParam(4, $_POST['email']);
                $stmtKaryawan->bindParam(5, $_POST['tanggal_masuk']);
                $stmtKaryawan->bindParam(6, $_POST['id']);
            if ($stmtKaryawan->execute()) {
                $_SESSION['hasil'] = true; 
                $_SESSION['pesan'] = "Berhasil ubah data";
        } else {
                $_SESSION['hasil'] = false; 
                $_SESSION['pesan'] = "Gagal ubah data";
        }
        echo "<meta http-equiv='refresh' content='0;url=?page=karyawanread'>";
    }
}
}
?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb2">
          <div class="col-sm-6">
            <h1>Ubah Data Lokasi</h1>
          </div>
          <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="?page=home">Home</a></li>
                    <li class="breadcrumb-item"><a href="?page=karyawanread">Karyawan</a></li>
                    <li class="breadcrumb-item active">Ubah Data</li>
                </ol>
           </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Ubah Lokasi</h3>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label for="nama_lokasi">Nomor Induk Karyawan</label>
                    <input type="hidden" class="form-control" name="id" value="<?php echo $row['id'] ?>">
                    <input type="text" class="form-control" name="nik" value="<?php echo $row['nik'] ?>">
                </div>
                <div class="form-group">
                    <label for="nama_lokasi">Nama Lengkap</label>
                    <input type="text" class="form-control" name="nama_lengkap" value="<?php echo $row['nama_lengkap'] ?>">
                </div>
                <div class="form-group">
                    <label for="nama_lokasi">No Handphone</label>
                    <input type="text" class="form-control" name="handphone" value="<?php echo $row['handphone'] ?>">
                </div>
                <div class="form-group">
                    <label for="nama_lokasi">Email</label>
                    <input type="text" class="form-control" name="email" value="<?php echo $row['email'] ?>">
                </div>
                <div class="form-group">
                    <label for="nama_lokasi">Tanggal Masuk</label>
                    <input type="date" class="form-control" name="tanggal_masuk" value="<?php echo $row['tanggal_masuk'] ?>">
                </div>
                <div class="form-group">
                    <label for="nama_lokasi">Username</label>
                    <input type="text" class="form-control" name="username" value="<?php echo $row['username'] ?>">
                </div>
                <div class="form-group">
                    <label for="nama_lokasi">Password</label>
                    <input type="password" class="form-control" name="password">
                </div>
                <div class="form-group">
                    <label for="nama_lokasi">Password Ulangi</label>
                    <input type="password" class="form-control" name="password_ulangi">
                </div>
                <div class="form-group">
                    <label for="peran">Peran</label>
                    <select class="form-control" name="peran">
                        <option value="<?php echo $row['peran'] ?>"><?php echo $row['peran'] ?></option>
                        <option value="ADMIN">ADMIN</option>
                        <option value="USER">USER</option>
                    </select>
                </div>
                <a href="?page=karyawanread" class="btn btn-danger btn-sm float-right">
                    <i class="fa fa-times"></i> Batal
                </a>
                <button type="submit" name="button_update" class="btn btn-success btn-sm float-right">
                    <i class="fa fa-save"></i> Simpan
                </button>
            </form>
         </div>
</section>


<?php
    } else {
        echo "<meta http-equiv='refresh' content='0;url=?page=karyawanread'>";
    }
} else {
    echo "<meta http-equiv='refresh' content='0;url=?page=karyawanread'>";
}
?>