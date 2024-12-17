<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

require '../config.php';

// Proses pengambilan data jika form dikirim
$data_reservations = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_date = $_POST['start_date'] ?? null;
    $end_date = $_POST['end_date'] ?? null;

    if ($start_date && $end_date) {
        $query = $pdo->prepare("
        SELECT reservations.*, vehicles.name AS vehicle_name, users.name AS user_name 
        FROM reservations 
        JOIN vehicles ON reservations.vehicle_id = vehicles.id 
        JOIN users ON reservations.user_id = users.id 
        WHERE reservations.start_date >= :start_date AND reservations.end_date <= :end_date
    ");
    
        $query->execute([
            ':start_date' => $start_date,
            ':end_date' => $end_date
        ]);
        $data_reservations = $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pemesanan - SIMKAR</title>
    <link rel="icon" type="image/png" href="../assets/gambar/logo-app.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #f8f9fa; /* Biru soft */
        }
        .navbar-brand img {
            height: 50px; /* Membuat logo lebih besar */
        }
        .sidebar {
            height: 100vh;
            background-color: #f8f9fa;
            padding: 15px;
            border-right: 1px solid #ddd;
            animation: slideIn 0.3s ease-in-out;
        }
        .sidebar a {
            color: #343a40;
            text-decoration: none;
            display: block;
            padding: 8px 10px;
            margin: 8px 0;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .sidebar a:hover {
            background-color: #e2e6ea;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .card.blue {
            background-color: #1e88e5;
            color: white;
        }
        .card.white {
            background-color: #ffffff;
            color: #343a40;
        }
        .card h5 {
            font-size: 1.2rem;
        }
        .card h2 {
            font-size: 2.5rem;
            font-weight: bold;
        }
        .container-fluid {
            padding: 20px;
        }
        h2, h4 {
            font-size: 1.5rem;
        }
        /* Animation for Sidebar */
        @keyframes slideIn {
            from {
                transform: translateX(-100%);
            }
            to {
                transform: translateX(0);
            }
        }
        /* Card Animation */
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .card {
            animation: fadeIn 0.5s ease-out;
        }
        .navbar-brand {
            animation: fadeIn 0.8s ease-out;
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
                <a href="../admin_dashboard.php" class="my-2">Dashboard</a>
                <a href="../reservations/reservation.php" class="my-2">Reservations</a>
                <a href="../vehicles/vehicles.php" class="my-2">Vehicles</a>
                <a href="../services/services.php" class="my-2">Services</a>
                <a href="../users/users.php" class="my-2">Users</a>
                <a href="reports.php" class="my-2"><i class="fas fa-file-excel"></i>Report</a>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <h2 class="my-4">Laporan Pemesanan Kendaraan</h2>

                <!-- Form untuk memilih rentang tanggal -->
                <form method="POST" class="mb-4">
                    <div class="form-row">
                        <div class="col-md-4">
                            <label for="start_date">Tanggal Mulai</label>
                            <input type="date" id="start_date" name="start_date" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label for="end_date">Tanggal Akhir</label>
                            <input type="date" id="end_date" name="end_date" class="form-control" required>
                        </div>
                        <div class="col-md-4 align-self-end">
                            <button type="submit" class="btn btn-primary btn-block">Tampilkan</button>
                        </div>
                    </div>
                </form>

                <!-- Tabel data pemesanan -->
                <?php if (!empty($data_reservations)): ?>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Nama Kendaraan</th>
                                <th>Nama Pengguna</th>
                                <th>Tujuan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data_reservations as $index => $reservation): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($reservation['start_date']) ?></td>
                                    <td><?= htmlspecialchars($reservation['vehicle_name']) ?></td>
                                    <td><?= htmlspecialchars($reservation['user_name']) ?></td>
                                    <td><?= htmlspecialchars($reservation['purpose']) ?></td>
                                    <td><?= htmlspecialchars($reservation['status']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <!-- Tombol Export ke Excel -->
                    <form method="POST" action="export_excel.php">
                        <input type="hidden" name="start_date" value="<?= htmlspecialchars($start_date) ?>">
                        <input type="hidden" name="end_date" value="<?= htmlspecialchars($end_date) ?>">
                        <button type="submit" class="btn btn-primary">Export ke Excel</button>
                    </form>
                <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                    <div class="alert alert-warning">Tidak ada data pada rentang tanggal yang dipilih.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer mt-auto py-3">
        <div class="container text-center">
            <span class="text-muted">© 2024 SIMKAR. All Rights Reserved.</span>
        </div>
    </footer>
</body>
</html>
