<?php
require '../config.php';

// Ambil data dari form
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];

// Query untuk mendapatkan data pemesanan sesuai dengan rentang tanggal
$query = $pdo->prepare("
    SELECT reservations.*, 
           vehicles.name AS vehicle_name, 
           users.name AS user_name, 
           driver.name AS driver_name, 
           approver1.name AS approver_level1, 
           approver2.name AS approver_level2
    FROM reservations 
    JOIN vehicles ON reservations.vehicle_id = vehicles.id 
    JOIN users ON reservations.user_id = users.id 
    LEFT JOIN users AS driver ON reservations.driver_id = driver.id
    LEFT JOIN users AS approver1 ON reservations.approver_level1_id = approver1.id
    LEFT JOIN users AS approver2 ON reservations.approver_level2_id = approver2.id
    WHERE reservations.start_date >= :start_date AND reservations.end_date <= :end_date
");
$query->execute([
    ':start_date' => $start_date,
    ':end_date' => $end_date
]);

$data_reservations = $query->fetchAll(PDO::FETCH_ASSOC);

// Menyiapkan file Excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="laporan_pemesanan.xls"');

// Membuat struktur HTML untuk tabel
echo "<html>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            text-align: center;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        td {
            background-color: #ffffff;
        }
      </style>";
echo "</head>";
echo "<body>";

// Membuat judul tabel
echo "<h2 style='text-align:center;'>Laporan Pemesanan Kendaraan</h2>";

// Membuat header kolom
echo "<table>";
echo "<tr>
        <th>No</th>
        <th>Tanggal Mulai</th>
        <th>Tanggal Akhir</th>
        <th>Nama Kendaraan</th>
        <th>Nama Pengguna</th>
        <th>Nama Supir</th>
        <th>Pengesah Level 1</th>
        <th>Pengesah Level 2</th>
        <th>Tujuan</th>
        <th>Status</th>
      </tr>";

// Looping untuk output data ke dalam file Excel
foreach ($data_reservations as $index => $reservation) {
    echo "<tr>";
    echo "<td>" . ($index + 1) . "</td>";
    echo "<td>" . date('Y-m-d H:i', strtotime($reservation['start_date'])) . "</td>";
    echo "<td>" . date('Y-m-d H:i', strtotime($reservation['end_date'])) . "</td>";
    echo "<td>" . htmlspecialchars($reservation['vehicle_name']) . "</td>";
    echo "<td>" . htmlspecialchars($reservation['user_name']) . "</td>";
    echo "<td>" . (!empty($reservation['driver_name']) ? htmlspecialchars($reservation['driver_name']) : 'Tidak Ada Supir') . "</td>";
    echo "<td>" . (!empty($reservation['approver_level1']) ? htmlspecialchars($reservation['approver_level1']) : '-') . "</td>";
    echo "<td>" . (!empty($reservation['approver_level2']) ? htmlspecialchars($reservation['approver_level2']) : '-') . "</td>";
    echo "<td>" . htmlspecialchars($reservation['purpose']) . "</td>";
    echo "<td>" . htmlspecialchars($reservation['status']) . "</td>";
    echo "</tr>";
}

echo "</table>";
echo "</body>";
echo "</html>";
?>
