<?php
require "../config.php";

// Ambil ID reservasi dari query string
$reservation_id = $_GET['id'];

// Query untuk menghapus data reservasi
$sql = "DELETE FROM reservations WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$reservation_id]);

header("Location: reservation.php"); // Redirect ke halaman reservasi setelah berhasil
?>