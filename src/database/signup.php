<?php
// signup.php

session_start();

// Include the database connection
include 'db_connection.php';

// Check if form data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data and sanitize
  $username = trim($_POST['username']);
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  // Basic validation
  if (empty($username) || empty($email) || empty($password)) {
    echo "All fields are required.";
    exit();
  }

  // Hash the password for security
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Prepare and bind
  $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $username, $email, $hashedPassword);

  // Execute and check
  if ($stmt->execute()) {
    // Registration successful
    header("Location: ../pages/sign-in.html");
    exit();
  } else {
    // Handle errors (e.g., duplicate username/email)
    if ($conn->errno == 1062) { // Duplicate entry
      echo "Username or email already exists.";
    } else {
      echo "Error: " . $stmt->error;
    }
  }

  $stmt->close();
  $conn->close();
} else {
  echo "Invalid request method.";
}
?>
