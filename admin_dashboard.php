<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require 'config.php'; 

$stmt = $pdo->query("SELECT COUNT(*) AS total_vehicles FROM vehicles");
$row = $stmt->fetch();
$total_vehicles = $row['total_vehicles'];

$stmt = $pdo->query("SELECT COUNT(*) AS total_reservations FROM reservations");
$row = $stmt->fetch();
$total_reservations = $row['total_reservations'];

$stmt = $pdo->query("SELECT COUNT(*) AS total_services FROM services");
$row = $stmt->fetch();
$total_services = $row['total_services'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMKAR Dashboard</title>
    <link rel="icon" type="image/png" href="assets/gambar/logo-app.png">
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
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="admin_dashboard.php">
            <img src="assets/gambar/logo-sidebar.png" alt="SIMKAR Logo">
        </a>
        <div class="ml-auto">
            <a href="logout.php" class="btn btn-outline-danger">Logout</a>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar">
                <a href="admin_dashboard.php" class="my-2"><i class="fas fa-home"></i> Dashboard</a>
                <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'approver'): ?>
                <a href="reservations/reservation.php" class="my-2">Reservations</a>
                <?php endif; ?>
                <a href="vehicles/vehicles.php" class="my-2"><i class="fas fa-car"></i> Vehicles</a>
                <a href="services/services.php" class="my-2"><i class="fas fa-tools"></i> Services</a>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="users/users.php" class="my-2">Users</a>
                <?php endif; ?>
                <a href="reports/reports.php" class="my-2"><i class="fas fa-file-excel"></i>Report</a>
            </div>

            <!-- Main Content -->
            <div class="col-md-10">
                <h2 class="my-4">Welcome to SIMKAR Dashboard</h2>

                <!-- Cards -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="card blue text-center p-3">
                            <h5><i class="fas fa-car"></i> Total Vehicles</h5>
                            <h2><?= $total_vehicles; ?></h2>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card white text-center p-3">
                            <h5><i class="fas fa-clipboard-list"></i> Total Reservations</h5>
                            <h2><?= $total_reservations; ?></h2>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card blue text-center p-3">
                            <h5><i class="fas fa-tools"></i> Vehicles in Maintenance</h5>
                            <h2><?= $total_services; ?></h2>
                        </div>
                    </div>
                </div>

                <!-- Chart Section -->
                <div class="mt-5">
                    <h4>Vehicle Usage Statistics</h4>
                    <div class="card white p-3">
                        <!-- Placeholder for Chart -->
                        <canvas id="usageChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Example chart data
        var ctx = document.getElementById('usageChart').getContext('2d');
        var usageChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['January', 'February', 'March', 'April'],
                datasets: [{
                    label: 'Vehicle Usage',
                    data: [10, 15, 20, 18],
                    backgroundColor: '#1e88e5'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
