<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

require "../config.php";

// Query untuk mengambil data reservasi
$sql = "SELECT reservations.*, vehicles.name AS vehicle_name, users.name AS user_name 
        FROM reservations
        JOIN vehicles ON reservations.vehicle_id = vehicles.id
        JOIN users ON reservations.user_id = users.id";
$stmt = $pdo->query($sql);
$reservations = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMKAR - Reservations</title>
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
                <a href="../admin_dashboard.php">Dashboard</a>
                <a href="reservation.php" class="active">Reservations</a>
                <a href="../vehicles/vehicles.php">Vehicles</a>
                <a href="../services/services.php">Services</a>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="../users/users.php" class="my-2">Users</a>
                <?php endif; ?>
                <a href="../reports/reports.php" class="my-2"><i class="fas fa-file-excel"></i>Report</a>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <h2 class="my-4">Daftar Reservasi</h2>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="add_reservation.php" class="btn btn-primary mb-3">Tambah Reservasi Baru</a>
                <?php endif; ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Pengguna</th>
                            <th>Nama Kendaraan</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <?php if ($_SESSION['role'] === 'approver'): ?>
                                <th>Detail</th>
                            <?php endif; ?>
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <th>Aksi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservations as $reservation): ?>
                            <tr>
                                <td><?= $reservation['id'] ?></td>
                                <td><?= $reservation['user_name'] ?></td>
                                <td><?= $reservation['vehicle_name'] ?></td>
                                <td><?= date('Y-m-d H:i:s', strtotime($reservation['start_date'])) ?></td>
                                <td><?= date('Y-m-d H:i:s', strtotime($reservation['end_date'])) ?></td>
                                <td><?= ucfirst($reservation['status']) ?></td>
                                <?php if ($_SESSION['role'] === 'approver'): ?>
                                <td>
                                    <button 
                                        class="btn btn-outline-primary btn-sm" 
                                        data-toggle="modal" 
                                        data-target="#summaryModal" 
                                        data-id="<?= $reservation['id'] ?>">Summary
                                    </button>
                                </td>
                            <?php endif; ?>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                                <td>
                                    <a href="edit_reservation.php?id=<?= $reservation['id'] ?>" class="btn btn-outline-primary btn-sm">Edit</a>
                                    <a href="delete_reservation.php?id=<?= $reservation['id'] ?>" class="btn btn-outline-danger btn-sm">Hapus</a>
                                </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Modal Summary -->
<div class="modal fade" id="summaryModal" tabindex="-1" role="dialog" aria-labelledby="summaryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="summaryModalLabel">Detail Reservasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Detail reservasi akan ditampilkan di sini -->
                <div id="summaryContent">
                    <!-- Placeholder -->
                    <p>Loading...</p>
                </div>
            </div>
        </div>
    </div>
</div>
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
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        $('#summaryModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget); // Tombol yang diklik
            const reservationId = button.data('id'); // Ambil ID reservasi

            // Tampilkan "Loading..." sementara data diambil
            $('#summaryContent').html('<p>Loading...</p>');

            // Ambil data dari server menggunakan AJAX
            $.ajax({
                url: 'get_reservation_summary.php', // Endpoint untuk mengambil data
                method: 'GET',
                data: { id: reservationId },
                success: function (response) {
                    // Tampilkan data di modal
                    $('#summaryContent').html(response);
                },
                error: function () {
                    $('#summaryContent').html('<p>Terjadi kesalahan saat memuat data.</p>');
                }
            });
        });
    });
</script>
</body>
</html>
