<?php
// shipping-details-form.php

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: ../database/signin.php"); // Pastikan path ini benar
  exit();
}

// Include the database connection
include '../database/db_connection.php';

$user_id = $_SESSION['user_id'];

// Fetch email from the users table
$stmt = $conn->prepare("SELECT email FROM users WHERE user_id = ?");
if (!$stmt) {
  echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
  exit();
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($email);
if (!$stmt->fetch()) {
  echo "Error: Email not found for the user.";
  exit();
}
$stmt->close();

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Shipping Details - Plantify</title>
  <style>
    /* Reset and Base Styles */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg,
          rgba(120, 255, 42, 0.2) 0%,
          #f8f2eb 100%);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      color: #333;
      padding: 20px;
      font-size: 18px;
      /* Increased base font size */
      line-height: 1.6;
    }

    /* Container Styles */
    .notification,
    .shipping-form-container {
      background-color: white;
      border-radius: 20px;
      padding: 40px 50px;
      /* Increased padding */
      width: 100%;
      max-width: 650px;
      /* Slightly increased max-width */
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    }

    .notification {
      text-align: center;
    }

    .notification h2 {
      color: #28a745;
      margin-bottom: 25px;
      font-size: 32px;
      /* Increased font size */
    }

    .notification p {
      font-size: 20px;
      /* Increased font size */
      line-height: 1.6;
      margin-bottom: 35px;
    }

    .shipping-form-container {
      display: none;
    }

    h2 {
      color: #28a745;
      margin-bottom: 35px;
      font-size: 32px;
      /* Increased font size */
      font-weight: 700;
      text-align: center;
    }

    /* Input Group Styles */
    .input-group {
      margin-bottom: 25px;
      /* Increased margin */
      position: relative;
    }

    label {
      position: absolute;
      top: 16px;
      /* Adjusted for larger input */
      left: 16px;
      color: #888;
      font-size: 18px;
      /* Increased font size */
      transition: all 0.3s ease;
      pointer-events: none;
      background: transparent;
    }

    input[type="text"],
    input[type="email"],
    input[type="tel"] {
      width: 100%;
      padding: 16px 16px;
      /* Increased padding */
      border: 2px solid #ddd;
      border-radius: 10px;
      /* Slightly increased border-radius */
      font-size: 18px;
      /* Increased font size */
      transition: all 0.3s ease;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="tel"]:focus {
      outline: none;
      border-color: #28a745;
    }

    input:focus+label,
    input:not(:placeholder-shown)+label {
      top: -12px;
      left: 10px;
      font-size: 14px;
      background: white;
      padding: 0 5px;
      color: #28a745;
    }

    /* Button Styles */
    .submit-btn,
    .notification-btn {
      background-color: #28a745;
      color: white;
      padding: 16px;
      /* Increased padding */
      border: none;
      border-radius: 10px;
      width: 100%;
      cursor: pointer;
      font-size: 20px;
      /* Increased font size */
      font-weight: 600;
      transition: all 0.3s ease;
      margin-top: 20px;
    }

    .submit-btn:hover,
    .notification-btn:hover {
      background-color: #218838;
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    /* Responsive Styles */
    @media (max-width: 768px) {

      .notification,
      .shipping-form-container {
        padding: 35px 25px;
      }

      h2 {
        font-size: 28px;
      }

      .notification h2 {
        font-size: 28px;
      }

      .notification p {
        font-size: 18px;
      }

      .input-group {
        margin-bottom: 22px;
      }

      input[type="text"],
      input[type="email"],
      input[type="tel"] {
        padding: 14px 14px;
        font-size: 16px;
      }

      label {
        font-size: 16px;
      }

      .submit-btn,
      .notification-btn {
        padding: 14px;
        font-size: 18px;
      }
    }

    @media (max-width: 480px) {

      .notification,
      .shipping-form-container {
        padding: 25px 20px;
        border-radius: 15px;
      }

      h2 {
        font-size: 24px;
      }

      .notification h2 {
        font-size: 24px;
      }

      .notification p {
        font-size: 16px;
      }

      .input-group {
        margin-bottom: 18px;
      }

      input[type="text"],
      input[type="email"],
      input[type="tel"] {
        padding: 12px 12px;
        font-size: 16px;
      }

      label {
        font-size: 14px;
      }

      .submit-btn,
      .notification-btn {
        padding: 12px;
        font-size: 16px;
      }
    }
  </style>
</head>

<body>
  <div class="notification">
    <h2>Welcome to Plantify!</h2>
    <p>
      Hello! To ensure your plants reach you safely, we need some additional
      information. Please fill out the shipping details on the next screen.
      Your green companions are eager to meet you!
    </p>
    <button class="notification-btn">Continue to Shipping Details</button>
  </div>
  <div class="shipping-form-container">
    <h2>Shipping Details</h2>
    <form
      id="shippingForm"
      action="../database/save_shipping_details.php"
      method="POST">
      <div class="input-group">
        <input
          type="text"
          id="fullName"
          name="fullName"
          required
          placeholder=" " />
        <label for="fullName">Full Name</label>
      </div>
      <div class="input-group">
        <input
          type="text"
          id="email"
          name="email"
          value="<?php echo htmlspecialchars($email); ?>"
          readonly />
        <label for="email">Email</label>
      </div>
      <div class="input-group">
        <input type="tel" id="phone" name="phone" required placeholder=" " />
        <label for="phone">Phone</label>
      </div>
      <div class="input-group">
        <input
          type="text"
          id="address"
          name="address"
          required
          placeholder=" " />
        <label for="address">Address</label>
      </div>
      <div class="input-group">
        <input type="text" id="city" name="city" required placeholder=" " />
        <label for="city">City</label>
      </div>
      <div class="input-group">
        <input
          type="text"
          id="postalCode"
          name="postalCode"
          required
          placeholder=" " />
        <label for="postalCode">Postal Code</label>
      </div>
      <button type="submit" class="submit-btn">Confirm Order</button>
    </form>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const notification = document.querySelector(".notification");
      const notificationBtn = document.querySelector(".notification-btn");
      const shippingForm = document.querySelector(".shipping-form-container");

      if (notificationBtn && notification && shippingForm) {
        notificationBtn.addEventListener("click", function() {
          notification.style.display = "none";
          shippingForm.style.display = "block";
        });
      } else {
        console.error("One or more required elements are missing.");
      }
    });
  </script>
</body>

</html>