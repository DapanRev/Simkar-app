<?php
session_start();
require '../config.php';

// Pastikan user adalah approver
if ($_SESSION['role'] !== 'approver') {
    die("Unauthorized access");
}

// Ambil ID reservasi
$reservation_id = $_GET['id'] ?? null;

if (!$reservation_id || !ctype_digit($reservation_id)) {
    die("Error: ID tidak valid.");
}

// Query data reservasi
$sql = "SELECT reservations.*, 
               vehicles.name AS vehicle_name, 
               users.name AS user_name, 
               approver1.name AS approver1_name, 
               approver2.name AS approver2_name
        FROM reservations
        JOIN vehicles ON reservations.vehicle_id = vehicles.id
        JOIN users ON reservations.user_id = users.id
        LEFT JOIN users AS approver1 ON reservations.approver_level1_id = approver1.id
        LEFT JOIN users AS approver2 ON reservations.approver_level2_id = approver2.id
        WHERE reservations.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$reservation_id]);
$reservation = $stmt->fetch();

if (!$reservation) {
    die("Error: Reservasi tidak ditemukan.");
}

// Ambil sesi user login
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['username'];

// Logika untuk menampilkan tombol Approve/Reject
$show_approve_level1 = $user_id == $reservation['approver_level1_id'] && $reservation['approver_level1_status'] === 'pending';
$show_approve_level2 = $user_id == $reservation['approver_level2_id'] && $reservation['approver_level1_status'] === 'approved' && $reservation['approver_level2_status'] === 'pending';
?>

<h5>Informasi Reservasi</h5>
<p><strong>Kendaraan:</strong> <?= htmlspecialchars($reservation['vehicle_name']) ?></p>
<p><strong>Pengguna:</strong> <?= htmlspecialchars($reservation['user_name']) ?></p>
<p><strong>Tujuan:</strong> <?= htmlspecialchars($reservation['purpose']) ?></p>
<p><strong>Tanggal Mulai:</strong> <?= date('d-m-Y H:i', strtotime($reservation['start_date'])) ?></p>
<p><strong>Tanggal Selesai:</strong> <?= date('d-m-Y H:i', strtotime($reservation['end_date'])) ?></p>
<p><strong>Status Approver Level 1:</strong> <?= ucfirst($reservation['approver_level1_status']) ?> oleh <?= htmlspecialchars($reservation['approver1_name']) ?></p>
<p><strong>Status Approver Level 2:</strong> <?= ucfirst($reservation['approver_level2_status']) ?> oleh <?= htmlspecialchars($reservation['approver2_name']) ?></p>

<?php if ($show_approve_level1): ?>
    <!-- Tombol Approve/Reject untuk Level 1 -->
    <form action="approve_resevation.php" method="POST">
        <input type="hidden" name="id" value="<?= $reservation['id'] ?>">
        <input type="hidden" name="level" value="1">
        <button type="submit" name="action" value="approve" class="btn btn-primary">Approve</button>
        <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
    </form>
<?php endif; ?>

<?php if ($show_approve_level2): ?>
    <!-- Tombol Approve/Reject untuk Level 2 -->
    <form action="approve_resevation.php" method="POST">
        <input type="hidden" name="id" value="<?= $reservation['id'] ?>">
        <input type="hidden" name="level" value="2">
        <button type="submit" name="action" value="approve" class="btn btn-primary">Approve</button>
        <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
    </form>
<?php endif; ?>
