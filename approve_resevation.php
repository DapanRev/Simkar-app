<?php
session_start();
require '../config.php';

if ($_SESSION['role'] !== 'approver') {
    die("Unauthorized access");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $action = $_POST['action'];

    if (!in_array($action, ['approve', 'reject'])) {
        die("Aksi tidak valid.");
    }

    $status = $action === 'approve' ? 'approved' : 'rejected';

    $sql = "UPDATE reservations SET status = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$status, $id]);

    header("Location: reservation.php?success=Reservasi berhasil $status.");
}
?>
