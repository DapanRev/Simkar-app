<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

require "../config.php";

// Ambil data kendaraan dan pengguna untuk dropdown
$vehicles = $pdo->query("SELECT * FROM vehicles")->fetchAll();

// Ambil data pengguna berdasarkan role
$admins = $pdo->query("SELECT * FROM users WHERE role = 'admin'")->fetchAll();
$drivers = $pdo->query("SELECT * FROM users WHERE role = 'driver'")->fetchAll();
$approvers = $pdo->query("SELECT * FROM users WHERE role = 'approver'")->fetchAll();

// Proses form jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $vehicle_id = $_POST['vehicle_id'];
    $driver_id = $_POST['driver_id'];
    $approver_level1_id = $_POST['approver_level1_id'];
    $approver_level2_id = $_POST['approver_level2_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $purpose = $_POST['purpose'];

    // Query untuk insert data reservasi
    $sql = "INSERT INTO reservations (user_id, vehicle_id, driver_id, approver_level1_id, approver_level2_id, start_date, end_date, purpose, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $vehicle_id, $driver_id, $approver_level1_id, $approver_level2_id, $start_date, $end_date, $purpose]);

    header("Location: reservation.php"); // Redirect ke halaman reservasi setelah berhasil
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMKAR - Add Reservation</title>
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
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="../admin_dashboard.php">
            <img src="../assets/gambar/logo-sidebar.png" alt="SIMKAR Logo">
        </a>
        <div class="navbar-nav ml-auto">
            <a href="../logout.php" class="btn btn-outline-danger">Logout</a>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar">
                <a href="../admin_dashboard.php">Dashboard</a>
                <a href="reservation.php" class="active">Reservations</a>
                <a href="../vehicles/vehicles.php">Vehicles</a>
                <a href="../services/services.php">Services</a>
                <a href="../users/users.php">Users</a>
                <a href="../reports/reports.php"><i class="fas fa-file-excel"></i> Report</a>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <h2 class="my-4">Tambah Reservasi</h2>
                <form action="add_reservation.php" method="POST">
                    <div class="form-group">
                        <label for="user_id">Nama Pengguna</label>
                        <select class="form-control" name="user_id" id="user_id" required>
                            <option value="">Pilih Pengguna</option>
                            <?php foreach ($admins as $admin): ?>
                                <option value="<?= $admin['id'] ?>"><?= $admin['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="vehicle_id">Nama Kendaraan</label>
                        <select class="form-control" name="vehicle_id" id="vehicle_id" required>
                            <option value="">Pilih Kendaraan</option>
                            <?php foreach ($vehicles as $vehicle): ?>
                                <option value="<?= $vehicle['id'] ?>"><?= $vehicle['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="driver_id">Nama Supir</label>
                        <select class="form-control" name="driver_id" id="driver_id">
                            <option value="">Pilih Supir (Opsional)</option>
                            <?php foreach ($drivers as $driver): ?>
                                <option value="<?= $driver['id'] ?>"><?= $driver['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="approver_level1_id">Pengesah Level 1</label>
                        <select class="form-control" name="approver_level1_id" id="approver_level1_id" required>
                            <option value="">Pilih Pengesah Level 1</option>
                            <?php foreach ($approvers as $approver): ?>
                                <option value="<?= $approver['id'] ?>"><?= $approver['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="approver_level2_id">Pengesah Level 2</label>
                        <select class="form-control" name="approver_level2_id" id="approver_level2_id" required>
                            <option value="">Pilih Pengesah Level 2</option>
                            <?php foreach ($approvers as $approver): ?>
                                <option value="<?= $approver['id'] ?>"><?= $approver['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="start_date">Tanggal Mulai</label>
                        <input type="datetime-local" class="form-control" name="start_date" id="start_date" required>
                    </div>
                    <div class="form-group">
                        <label for="end_date">Tanggal Selesai</label>
                        <input type="datetime-local" class="form-control" name="end_date" id="end_date" required>
                    </div>
                    <div class="form-group">
                        <label for="purpose">Tujuan</label>
                        <textarea class="form-control" name="purpose" id="purpose" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Reservasi</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
