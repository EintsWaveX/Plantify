<?php
// admin-login.php

session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta
    name="author"
    content="Muhammad Zaenal Abidin Abdurrahman, Immanuel Eben Haezer Joseph Aletheia, and Darma Al Gani." />
  <meta name="description" content="Website for Admin to Login." />
  <title>Plantify Admin Login Website</title>
  <link rel="stylesheet" href="../css/admin-login.css" />
</head>

<body>
  <div class="login-container">
    <div class="kiri">
      <h2>Admin Login</h2>
      <?php if (isset($_GET['error'])): ?>
        <div class="error-message">
          <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
      <?php endif; ?>
      <form action="../database/admin_login.php" method="POST">
        <div class="input-group">
          <label for="username">Username</label>
          <input
            type="text"
            id="username"
            name="username"
            placeholder="Admin123"
            required />
        </div>
        <div class="input-group">
          <label for="password">Password</label>
          <input
            type="password"
            id="password"
            name="password"
            placeholder="Admin12345"
            required />
        </div>
        <button type="submit">Login</button>
      </form>
    </div>
    <div class="kanan">
      <img src="./../assets/admin-login/logo.svg" alt="Plantify Logo" width="200px" height="200px" />
      <h2>Welcome to Plantify Admin Login</h2>
      <p>
        Log in with your admin account to access the admin panel and use all of
        site's features.
      </p>
    </div>
  </div>
</body>

</html>