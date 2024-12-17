<?php
session_start();
require '../config.php';

// Pastikan hanya approver yang bisa mengakses
if ($_SESSION['role'] !== 'approver') {
    die("Unauthorized access");
}

// Ambil ID reservasi dari parameter
if (!isset($_GET['id']) || empty($_GET['id']) || !ctype_digit($_GET['id'])) {
    die("Error: ID tidak valid.");
}

$reservation_id = $_GET['id'];

// Query data reservasi
$sql = "SELECT reservations.*, vehicles.name AS vehicle_name, users.name AS user_name 
        FROM reservations
        JOIN vehicles ON reservations.vehicle_id = vehicles.id
        JOIN users ON reservations.user_id = users.id
        WHERE reservations.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$reservation_id]);
$reservation = $stmt->fetch();

if (!$reservation) {
    die("Error: Reservasi tidak ditemukan.");
}

// Tampilkan data dalam format HTML
?>
<h5>Informasi Reservasi</h5>
<p><strong>Kendaraan:</strong> <?= htmlspecialchars($reservation['vehicle_name']) ?></p>
<p><strong>Pengguna:</strong> <?= htmlspecialchars($reservation['user_name']) ?></p>
<p><strong>Tujuan:</strong> <?= htmlspecialchars($reservation['purpose']) ?></p>
<p><strong>Tanggal Mulai:</strong> <?= date('d-m-Y H:i', strtotime($reservation['start_date'])) ?></p>
<p><strong>Tanggal Selesai:</strong> <?= date('d-m-Y H:i', strtotime($reservation['end_date'])) ?></p>
<p><strong>Status:</strong> <?= ucfirst($reservation['status']) ?></p>

<!-- Tombol Approve/Reject -->
<form action="approve_resevation.php" method="POST">
    <input type="hidden" name="id" value="<?= $reservation['id'] ?>">
    <button type="submit" name="action" value="approve" class="btn btn-primary">Approve</button>
    <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
</form>
