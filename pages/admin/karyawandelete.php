<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $database = new Database();
    $db = $database->getConnection();

    $deleteSqlPengguna = "DELETE FROM pengguna WHERE id = ?";
    $stmtpengguna = $db->prepare($deleteSqlPengguna);
    $stmtpengguna->bindParam(1, $_GET['id']);
    if ($stmtpengguna->execute()) {
        $deleteSqlKaryawan = "DELETE FROM karyawan WHERE id = ?";
        $stmtkaryawan = $db->prepare($deleteSqlKaryawan);
        $stmtkaryawan->bindParam(1, $_GET['id']);
        if ($stmtkaryawan->execute()) {
            $_SESSION['hasil'] = true; 
            $_SESSION['pesan'] = "Berhasil hapus data";
        } else {
            $_SESSION['hasil'] = false; 
            $_SESSION['pesan'] = "Gagal hapus data";
        }
    }
}
echo "<meta http-equiv='refresh' content='0;url=?page=karyawanread'>";