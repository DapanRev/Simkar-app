<?php
require "config.php"; 

// Pengaturan keamanan session
ini_set('session.cookie_lifetime', 0);
ini_set('session.cookie_httponly', 1); 
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
ini_set('session.use_strict_mode', 1); 
session_start();

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
    $password = trim($_POST['password']);

    // Query ke database untuk memeriksa login
    try {
        $query = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $query->execute(['username' => $username]);
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Login berhasil
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['login_time'] = time(); 

            // Redirect berdasarkan role
            switch ($user['role']) {
                case 'admin':
                    header("Location: admin_dashboard.php");
                    break;
                case 'approver':
                    header("Location: admin_dashboard.php");
                    break;
                case 'driver':
                    header("Location: admin_dashboard.php");
                    break;
                default:
                    header("Location: unauthorized.php");
            }
            exit();
        } else {
            $error_message = "Username atau password salah!";
        }
    } catch (Exception $e) {
        $error_message = "Terjadi kesalahan. Silakan coba lagi.";
    }
}
?>