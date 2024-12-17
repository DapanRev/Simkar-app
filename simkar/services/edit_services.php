<?php
require "../config.php";

// Ambil ID layanan yang akan diedit
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: services.php");
    exit;
}

// Ambil data layanan berdasarkan ID
$sql = "SELECT * FROM services WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$service = $stmt->fetch();

if (!$service) {
    echo "Layanan tidak ditemukan!";
    exit;
}

// Ambil data kendaraan untuk dropdown
$sql = "SELECT id, name FROM vehicles";
$stmt = $pdo->query($sql);
$vehicles = $stmt->fetchAll();

// Proses update layanan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vehicle_id = $_POST['vehicle_id'];
    $service_date = $_POST['service_date'];
    $description = $_POST['description'];
    $cost = $_POST['cost'];

    $sql = "UPDATE services SET vehicle_id = ?, service_date = ?, description = ?, cost = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$vehicle_id, $service_date, $description, $cost, $id]);

    header("Location: services.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMKAR - Edit Service</title>
    <link rel="icon" type="image/png" href="../assets/gambar/logo-app.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <!-- Header -->
    <?php include 'partials/header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php include 'partials/sidebar.php'; ?>

            <!-- Main Content -->
            <div class="col-md-10">
                <h2 class="my-4">Edit Layanan Kendaraan</h2>

                <form action="edit_service.php?id=<?= $service['id'] ?>" method="POST">
                    <div class="form-group">
                        <label for="vehicle_id">Kendaraan</label>
                        <select class="form-control" id="vehicle_id" name="vehicle_id" required>
                            <?php foreach ($vehicles as $vehicle): ?>
                                <option value="<?= $vehicle['id'] ?>" <?= $vehicle['id'] == $service['vehicle_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($vehicle['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="service_date">Tanggal Layanan</label>
                        <input type="date" class="form-control" id="service_date" name="service_date" value="<?= $service['service_date'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required><?= htmlspecialchars($service['description']) ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="cost">Biaya</label>
                        <input type="number" step="0.01" class="form-control" id="cost" name="cost" value="<?= $service['cost'] ?>" required>
                    </div>
                    <button type="submit" class="btn btn-success">Update</button>
                    <a href="services.php" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'partials/footer.php'; ?>
</body>
</html>
