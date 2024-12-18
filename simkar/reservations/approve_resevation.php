<?php
session_start();
require '../config.php';

if ($_SESSION['role'] !== 'approver') {
    die("Unauthorized access");
}

$reservation_id = $_POST['id'];
$action = $_POST['action'];
$level = $_POST['level'];

if (!$reservation_id || !ctype_digit($reservation_id) || !in_array($action, ['approve', 'reject'])) {
    die("Error: Data tidak valid.");
}

// Tentukan status berdasarkan action
$status = ($action === 'approve') ? 'approved' : 'rejected';

// Proses persetujuan
if ($level == 1) {
    // Update Level 1 status
    $sql = "UPDATE reservations SET approver_level1_status = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$status, $reservation_id]);
} elseif ($level == 2) {
    // Update Level 2 status
    $sql = "UPDATE reservations SET approver_level2_status = ?, status = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$status, $status, $reservation_id]);
}

header("Location: reservation.php");
exit;
?>
