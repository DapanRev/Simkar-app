<?php
require "config.php";
?>

<!doctype html>
<html lang="en" data-bs-theme="auto">
  <head>
    <script src="../assets/js/color-modes.js"></script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Your Name">
    <meta name="generator" content="Hugo 0.122.0">
    <title>Login - Simkar</title>

    <link rel="icon" type="image/x-icon" href="<?= $main_url ?>/assets/gambar/logo-app.png">
    <link href="<?= $main_url ?>/assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
    body {
    background: url('<?= $main_url ?>/assets/gambar/klinik.jpg') no-repeat center center fixed;
    background-size: cover;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
    margin: 0;
    font-family: 'Arial', sans-serif;
    }

      .login-container {
        display: flex;
        background-color: rgba(255, 255, 255, 0.85);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border-radius: 15px;
        overflow: hidden;
      }

      .login-image {
        width: 350px;
        height: auto;
        background-size: cover;
        background-position: center;
        background-image: url('<?= $main_url ?>/assets/gambar/background-img.jpeg');
      }

      .login-form {
        padding: 2rem;
        width: 360px;
        background-color: white;
        border: 2px solid 
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      }

      .form-floating input {
        border-radius: 0.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      }

      .btn-primary {
        background-color: #2470dc;
        border: none;
        transition: background-color 0.3s ease;
      }

      .btn-primary:hover {
        background-color: #2685fc;
      }

      .logo {
        width: 300px;
        margin-bottom: 1.5rem;
        animation: fadeIn 1s ease-in-out;
      }

      .form-signin .btn {
        border-radius: 0.5rem;
        font-size: 1rem;
        font-weight: bold;
      }

      @keyframes fadeIn {
        0% { opacity: 0; transform: translateY(-20px); }
        100% { opacity: 1; transform: translateY(0); }
      }

      .text-body-secondary {
        color: #6c757d;
        font-size: 0.875rem;
      }

    </style>
  </head>
  <body>
    <main class="d-flex align-items-center justify-content-center w-100">
      <div class="login-container">
        <div class="login-form">
          <form action="proses-login.php" method="post">
            <img src="<?= $main_url ?>/assets/gambar/logo-app.png" alt="logo" class="logo mx-auto d-block">
            
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="floatingInput" placeholder="Username" name="username" required>
              <label for="floatingInput">Username</label>
            </div>

            <div class="form-floating mb-3">
              <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password" required>
              <label for="floatingPassword">Password</label>
            </div>

            <div class="mb-3">
              <input type="checkbox" id="showPassword" onclick="togglePassword()">
              <label for="showPassword">Show Password</label>
            </div>

            <button class="btn btn-primary w-100 py-2" type="submit" name="login">Login</button>
            <p class="mt-4 mb-3 text-body-secondary">&copy; Christian 2024</p>
          </form>
        </div>

        <div class="login-image">
        </div>
      </div>
    </main>

    <script>
      // Fungsi untuk menunjukkan atau menyembunyikan password
      function togglePassword() {
          var passwordField = document.getElementById("floatingPassword");
          if (passwordField.type === "password") {
              passwordField.type = "text";
          } else {
              passwordField.type = "password";
          }
      }
    </script>

    <script src="<?= $main_url ?>/assets/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
