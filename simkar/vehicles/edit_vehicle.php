<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

require '../config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM vehicles WHERE id = ?");
    $stmt->execute([$id]);
    $vehicle = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $name = $_POST['name'];
    $plate_number = $_POST['plate_number'];
    $type = $_POST['type'];
    $fuel_type = $_POST['fuel_type'];

    // Query untuk memperbarui data kendaraan
    $stmt = $pdo->prepare("UPDATE vehicles SET name = ?, plate_number = ?, type = ?, fuel_type = ? WHERE id = ?");
    $stmt->execute([$name, $plate_number, $type, $fuel_type, $id]);

    // Redirect setelah update
    header("Location: vehicles.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kendaraan - SIMKAR</title>
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
                <a href="vehicles.php" class="my-2">Vehicles</a>
                <a href="../services/services.php" class="my-2">Services</a>
                <a href="../users/users.php" class="my-2">Users</a>
                <a href="../reports/reports.php" class="my-2"><i class="fas fa-file-excel"></i>Report</a>
            </div>

            <!-- Main Content -->
            <div class="col-md-10">
                <h2 class="my-4">Edit Kendaraan</h2>
                <form action="edit_vehicle.php?id=<?= $vehicle['id'] ?>" method="POST">
                    <div class="form-group">
                        <label for="name">Nama Kendaraan</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= $vehicle['name'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="plate_number">Nomor Plat</label>
                        <input type="text" class="form-control" id="plate_number" name="plate_number" value="<?= $vehicle['plate_number'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="type">Jenis Kendaraan</label>
                        <select class="form-control" id="type" name="type" required>
                            <option value="angkutan_orang" <?= $vehicle['type'] == 'angkutan_orang' ? 'selected' : '' ?>>Angkutan Orang</option>
                            <option value="angkutan_barang" <?= $vehicle['type'] == 'angkutan_barang' ? 'selected' : '' ?>>Angkutan Barang</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fuel_type">Tipe BBM</label>
                        <select class="form-control" id="fuel_type" name="fuel_type" required>
                            <option value="diesel" <?= $vehicle['fuel_type'] == 'diesel' ? 'selected' : '' ?>>Diesel</option>
                            <option value="bensin" <?= $vehicle['fuel_type'] == 'bensin' ? 'selected' : '' ?>>Bensin</option>
                            <option value="electric" <?= $vehicle['fuel_type'] == 'electric' ? 'selected' : '' ?>>Electric</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
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
