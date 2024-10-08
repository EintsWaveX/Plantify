<?php
// signin.php

session_start();

// Include the database connection
include 'db_connection.php';

// Check if form data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data and sanitize
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);

  // Basic validation
  if (empty($username) || empty($password)) {
    echo "Both username and password are required.";
    exit();
  }

  // Prepare and bind
  $stmt = $conn->prepare("SELECT user_id, password FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $stmt->store_result();

  // Check if user exists
  if ($stmt->num_rows > 0) {
    $stmt->bind_result($user_id, $hashedPassword);
    $stmt->fetch();

    // Verify password
    if (password_verify($password, $hashedPassword)) {
      // Password is correct
      $_SESSION['user_id'] = $user_id;

      // **Periksa apakah pengguna sudah mengisi shipping details**
      $check_stmt = $conn->prepare("SELECT COUNT(*) FROM shipping_details WHERE user_id = ?");
      $check_stmt->bind_param("i", $user_id);
      $check_stmt->execute();
      $check_stmt->bind_result($count);
      $check_stmt->fetch();
      $check_stmt->close();

      if ($count > 0) {
        // **Pengguna sudah mengisi shipping details**
        header("Location: ../pages/main-menu.html");
        exit();
      } else {
        // **Pengguna belum mengisi shipping details**
        header("Location: ../pages/shipping-details-form.php");
        exit();
      }
    } else {
      // Incorrect password
      echo "Invalid username or password.";
    }
  } else {
    // Username not found
    echo "Invalid username or password.";
  }

  $stmt->close();
  $conn->close();
} else {
  echo "Invalid request method.";
}
?>
