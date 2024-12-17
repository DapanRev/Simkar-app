<?php
require "../config.php";

// Ambil ID pengguna dari URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk menghapus pengguna
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    // Redirect ke halaman users.php setelah berhasil menghapus
    header("Location: users.php");
    exit;
}
?>
