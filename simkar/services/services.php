<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

require "../config.php";

// Query untuk mengambil semua data layanan
$sql = "SELECT services.*, vehicles.name AS vehicle_name 
        FROM services 
        JOIN vehicles ON services.vehicle_id = vehicles.id";
$stmt = $pdo->query($sql);
$services = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMKAR - Services</title>
    <link rel="icon" type="image/png" href="../assets/gambar/logo-app.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
        }
        .navbar {
            background-color: #f8f9fa;
        }
        .navbar-brand img {
            height: 50px;
        }
        .sidebar {
            height: 100vh;
            background-color: #f8f9fa;
            padding: 15px;
            border-right: 1px solid #ddd;
        }
        .sidebar a {
            color: #343a40;
            text-decoration: none;
            display: block;
            padding: 10px;
        }
        .sidebar a:hover {
            background-color: #e2e6ea;
            border-radius: 5px;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .table thead {
            background-color: #f8f9fa;
        }
        footer {
            background-color: #f8f9fa;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="../admin_dashboard.php">
            <img src="../assets/gambar/logo-sidebar.png" alt="SIMKAR Logo">
        </a>
        <div class="ml-auto">
            <a href="../logout.php" class="btn btn-outline-danger">Logout</a>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar">
                <a href="../admin_dashboard.php" class="my-2">Dashboard</a>
                <a href="../reservations/reservation.php" class="my-2">Reservations</a>
                <a href="../vehicles/vehicles.php" class="my-2">Vehicles</a>
                <a href="../services/services.php" class="my-2">Services</a>
                <a href="../users/users.php" class="my-2">Users</a>
                <a href="../reports/reports.php" class="my-2"><i class="fas fa-file-excel"></i>Report</a>
            </div>

            <!-- Main Content -->
            <div class="col-md-10">
                <h2 class="my-4">Daftar Layanan Kendaraan</h2>
                <a href="add_service.php" class="btn btn-primary mb-3">Tambah Layanan Baru</a>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Kendaraan</th>
                            <th>Tanggal Layanan</th>
                            <th>Deskripsi</th>
                            <th>Biaya</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($services as $service): ?>
                            <tr>
                                <td><?= $service['id'] ?></td>
                                <td><?= htmlspecialchars($service['vehicle_name']) ?></td>
                                <td><?= $service['service_date'] ?></td>
                                <td><?= htmlspecialchars($service['description']) ?></td>
                                <td>Rp <?= number_format($service['cost'], 2, ',', '.') ?></td>
                                <td>
                                    <a href="edit_service.php?id=<?= $service['id'] ?>" class="btn btn-outline-primary btn-sm">Edit</a>
                                    <a href="delete_service.php?id=<?= $service['id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus layanan ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 SIMKAR - All rights reserved.</p>
    </footer>

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
