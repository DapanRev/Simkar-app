<?php

require '../config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM vehicles WHERE id = ?");
    $stmt->execute([$id]);

    // Redirect ke halaman vehicles setelah berhasil
    header("Location: vehicles.php");
    exit();
}
?>
