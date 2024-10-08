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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $status = $_POST['status'];

  // Validate status
  if (!in_array($status, ['active', 'inactive', 'suspended'])) {
    echo "Invalid status value.";
    exit();
  }

  // Update status in the database
  $stmt = $conn->prepare("UPDATE users SET status = ? WHERE user_id = ?");
  $stmt->bind_param("si", $status, $user_id);

  if ($stmt->execute()) {
    // Redirect back to customer list
    header("Location: customer-list.php");
    exit();
  } else {
    echo "Error updating status: " . $stmt->error;
  }

  $stmt->close();
}

// Fetch customer data
$stmt = $conn->prepare("SELECT username, email, status FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $status);
if (!$stmt->fetch()) {
  echo "Customer not found.";
  exit();
}
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Meta tags and title -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Customer - Plantify Admin Dashboard</title>
  <!-- Stylesheets -->
  <link rel="stylesheet" href="../css/admin-dashboard.css">
  <style>
    .edit-form {
      max-width: 500px;
      margin: 40px auto;
      background-color: #fff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .edit-form h2 {
      margin-bottom: 20px;
      text-align: center;
    }

    .edit-form label {
      display: block;
      margin-bottom: 8px;
      font-weight: bold;
    }

    .edit-form input[type="text"],
    .edit-form input[type="email"],
    .edit-form select {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    .edit-form button {
      width: 100%;
      padding: 12px;
      background-color: #3498db;
      color: #fff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .edit-form button:hover {
      background-color: #2980b9;
    }

    .edit-form a {
      display: block;
      margin-top: 15px;
      text-align: center;
      color: #3498db;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <!-- Main Content -->
  <div class="dashboard">
    <h2>Edit Customer</h2>
    <form method="POST" action="" class="edit-form">
      <label for="username">Username:</label>
      <input type="text" id="username" value="<?php echo htmlspecialchars($username); ?>" disabled>

      <label for="email">Email:</label>
      <input type="email" id="email" value="<?php echo htmlspecialchars($email); ?>" disabled>

      <label for="status">Status:</label>
      <select name="status" id="status">
        <option value="active" <?php if ($status == 'active') echo 'selected'; ?>>Active</option>
        <option value="inactive" <?php if ($status == 'inactive') echo 'selected'; ?>>Inactive</option>
        <option value="suspended" <?php if ($status == 'suspended') echo 'selected'; ?>>Suspended</option>
      </select>

      <button type="submit">Update Status</button>
      <a href="customer-list.php">Back to Customer List</a>
    </form>
  </div>
</body>
</html>
