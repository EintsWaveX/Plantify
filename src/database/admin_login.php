<?php
// admin_login.php

session_start();

// Sertakan koneksi database
include 'db_connection.php';

// Periksa apakah data form diterima
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Dapatkan data form dan sanitasi
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validasi dasar
    if (empty($username) || empty($password)) {
        $error = "Both username and password are required.";
        header("Location: ../pages/admin-login.php?error=" . urlencode($error));
        exit();
    }

    // Persiapkan dan ikat parameter
    $stmt = $conn->prepare("SELECT user_id, password, is_admin FROM users WHERE username = ?");
    if (!$stmt) {
        // Jika terjadi kesalahan dalam persiapan statement
        $error = "Database error: Failed to prepare statement.";
        header("Location: ../pages/admin-login.php?error=" . urlencode($error));
        exit();
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Periksa apakah pengguna ada
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashedPassword, $is_admin);
        $stmt->fetch();

        // Verifikasi password
        if (password_verify($password, $hashedPassword)) {
            // Periksa apakah pengguna adalah admin
            if ($is_admin) {
                // Login admin berhasil
                $_SESSION['admin_user_id'] = $user_id; // Gunakan variabel sesi yang berbeda untuk admin
                header("Location: ../pages/admin-dashboard.php");
                exit();
            } else {
                // Bukan admin
                $error = "Access denied. You are not an admin.";
                header("Location: ../pages/admin-login.php?error=" . urlencode($error));
                exit();
            }
        } else {
            // Password salah
            $error = "Invalid username or password.";
            header("Location: ../pages/admin-login.php?error=" . urlencode($error));
            exit();
        }
    } else {
        // Username tidak ditemukan
        $error = "Invalid username or password.";
        header("Location: ../pages/admin-login.php?error=" . urlencode($error));
        exit();
    }

    // Tutup statement dan koneksi
    $stmt->close();
    $conn->close();
} else {
    // Metode request tidak valid
    $error = "Invalid request method.";
    header("Location: ../pages/admin-login.php?error=" . urlencode($error));
    exit();
}
?>
