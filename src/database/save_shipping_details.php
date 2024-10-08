<?php
// save_shipping_details.php

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: ../pages/sign-in.php");
  exit();
}

// Include the database connection
include 'db_connection.php';

// Check if form data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get user ID from session
  $user_id = $_SESSION['user_id'];

  // Get form data and sanitize
  $full_name = trim($_POST['fullName']);
  $phone = trim($_POST['phone']);
  $address = trim($_POST['address']);
  $city = trim($_POST['city']);
  $postal_code = trim($_POST['postalCode']);

  // Basic validation
  if (empty($full_name) || empty($phone) || empty($address) || empty($city) || empty($postal_code)) {
    echo "All fields are required.";
    exit();
  }

  // Fetch email from the users table based on user_id
  $stmt = $conn->prepare("SELECT email FROM users WHERE user_id = ?");
  if (!$stmt) {
    echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
    exit();
  }
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $stmt->bind_result($email);
  if (!$stmt->fetch()) {
    // Email not found for this user_id
    echo "Error: Email not found for the user.";
    exit();
  }
  $stmt->close();

  // Check if email was successfully fetched
  if (empty($email)) {
    echo "Error: Email is empty.";
    exit();
  }

  // Prepare and bind the INSERT statement
  $stmt = $conn->prepare("INSERT INTO shipping_details (user_id, full_name, email, phone, address, city, postal_code) VALUES (?, ?, ?, ?, ?, ?, ?)");
  if (!$stmt) {
    echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
    exit();
  }
  $stmt->bind_param("issssss", $user_id, $full_name, $email, $phone, $address, $city, $postal_code);

  // Execute and check
  if ($stmt->execute()) {
    // Shipping details saved successfully
    header("Location: ../pages/main-menu.html");
    exit();
  } else {
    // Handle errors (e.g., duplicate entries)
    echo "Error: " . $stmt->error;
  }

  $stmt->close();
  $conn->close();
} else {
  echo "Invalid request method.";
}
?>
