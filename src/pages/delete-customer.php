<?php
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['admin_user_id'])) {
  header("Location: admin-login.php");
  exit();
}

// Include the database connection
include '../database/db_connection.php';

// Check if customer ID is provided
if (!isset($_GET['id'])) {
  echo "Customer ID is missing.";
  exit();
}

$user_id = intval($_GET['id']);

// Delete customer from database
$stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
  // Redirect back to customer list
  header("Location: customer-list.php");
  exit();
} else {
  echo "Error deleting customer: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
