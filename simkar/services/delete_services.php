<?php
require "../config.php";

// Ambil ID layanan yang akan dihapus
$id = $_GET['id'] ?? null;

if ($id) {
    $sql = "DELETE FROM services WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
}

header("Location: services.php");
exit;
?>
