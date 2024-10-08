<?php
// forgot_password.php

// Include the database connection
include 'db_connection.php';

// Check if form data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data and sanitize
  $username = trim($_POST['username']);
  $new_password = trim($_POST['password']);

  // Basic validation
  if (empty($username) || empty($new_password)) {
    echo "Both username and new password are required.";
    exit();
  }

  // Hash the new password
  $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);

  // Prepare and bind
  $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
  $stmt->bind_param("ss", $hashedPassword, $username);

  // Execute and check
  if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
      // Password updated successfully
      header("Location: ../pages/sign-in.html");
      exit();
    } else {
      // Username not found
      echo "Username not found.";
    }
  } else {
    // Handle errors
    echo "Error: " . $stmt->error;
  }

  $stmt->close();
  $conn->close();
} else {
  echo "Invalid request method.";
}
?>
