<?php

date_default_timezone_set('Asia/Jakarta');

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'simkar';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

$main_url = "http://localhost/simkar/";

function in_date($tgl){
    $dd = substr($tgl, 8, 2);
    $mm = substr($tgl, 5, 2);
    $yy = substr($tgl, 0, 4);

    return $dd . "-" . $mm . "-" . $yy;
}

function htgUmur($tgl_lahir){
    $tglLahir = new DateTime($tgl_lahir);
    $hariini  = new DateTime("today");

    $umur     = $hariini->diff($tglLahir)->y;

    return $umur . " Tahun";
}
?>
