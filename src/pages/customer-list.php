<?php
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['admin_user_id'])) {
    // Redirect to admin login page
    header("Location: admin-login.php"); // Adjust path as necessary
    exit();
}

// Include the database connection
include '../database/db_connection.php';

// Handle search query
$search = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';

// Handle status filter
$status_filter = isset($_GET['status_filter']) && in_array($_GET['status_filter'], ['active', 'inactive', 'suspended']) ? $_GET['status_filter'] : '';

// Build SQL query with search and status filter
$sql = "SELECT user_id, username, email, status, created_at FROM users WHERE is_admin = 0 AND (username LIKE ? OR email LIKE ?)";

if ($status_filter) {
    $sql .= " AND status = ?";
}

$stmt = $conn->prepare($sql);

if ($status_filter) {
    $stmt->bind_param("sss", $search, $search, $status_filter);
} else {
    $stmt->bind_param("ss", $search, $search);
}

$stmt->execute();
$result = $stmt->get_result();

// Simpan data pelanggan dalam array
$customers = [];
while ($row = $result->fetch_assoc()) {
    $customers[] = $row;
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta tags and title -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta
        name="author"
        content="Muhammad Zaenal Abidin Abdurrahman, Immanuel Eben Haezer Joseph Aletheia, and Darma Al Gani." />
    <meta name="description" content="Customer List - Plantify Admin Dashboard." />
    <title>Customer List - Plantify Admin Dashboard</title>
    <!-- Stylesheets -->
    <link rel="stylesheet" href="../css/admin-dashboard.css" />
    <style>
        /* Tambahkan CSS khusus untuk halaman customer list di sini */
        .search-container {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-container form {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .search-container input[type="text"] {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .search-container select {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .search-container button {
            padding: 8px 16px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-container button:hover {
            background-color: #2980b9;
        }

        .table-container {
            overflow-x: auto;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead {
            background-color: #f8f9fa;
        }

        table th,
        table td {
            padding: 15px;
            text-align: left;
            color: #333;
        }

        table th {
            font-weight: 600;
        }

        table tbody tr {
            border-bottom: 1px solid #dee2e6;
        }

        table tbody tr:last-child {
            border-bottom: none;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .action-buttons a {
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            color: #fff;
            font-size: 14px;
        }

        .action-buttons .edit-button {
            background-color: #f1c40f;
        }

        .action-buttons .edit-button:hover {
            background-color: #d4ac0d;
        }

        .action-buttons .delete-button {
            background-color: #e74c3c;
        }

        .action-buttons .delete-button:hover {
            background-color: #c0392b;
        }

        /* Status Labels */
        .status-label {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            text-align: center;
            display: inline-block;
            font-weight: 500;
            color: #fff;
        }

        .status-label.active {
            background-color: #2ecc71;
            /* Green */
        }

        .status-label.inactive {
            background-color: #95a5a6;
            /* Gray */
        }

        .status-label.suspended {
            background-color: #e74c3c;
            /* Red */
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <!-- Sidebar content -->
        <!-- ... (sama seperti sebelumnya) ... -->
        <div class="sidebar-header">
            <img src="../assets/admin-dashboard/logoBeneran.png" alt="Plantify Logo" />
        </div>
        <div class="sidebar-profile">
            <img src="../assets/admin-dashboard/Anzu Hanashiro.jpg" alt="Profile Picture" />
            <h3>Nama Admin</h3>
            <p>plantify@gmail.com</p>
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="admin-dashboard.php">
                    <img id="dashboard" src="../assets/admin-dashboard/dashboard.svg" alt="Dashboard" />
                    Dashboard
                </a>
            </li>
            <li>
                <a href="add-product.html">
                    <img id="products" src="../assets/admin-dashboard/product.svg" alt="Products" />
                    Products
                </a>
            </li>
            <li>
                <a href="#orders">
                    <img id="orders" src="../assets/admin-dashboard/order.svg" alt="Orders" />
                    Orders
                </a>
            </li>
            <li>
                <a href="customer-list.php" class="active">
                    <img id="customers" src="../assets/admin-dashboard/customer.svg" alt="Customers" />
                    Customers
                </a>
            </li>
            <li>
                <a href="#reports">
                    <img
                        id="reports"
                        src="../assets/admin-dashboard/reports.svg"
                        alt="Reports" />
                    Reports
                </a>
            </li>
            <li>
                <a href="#settings">
                    <img
                        id="settings"
                        src="../assets/admin-dashboard/setting.svg"
                        alt="Settings" />
                    Settings
                </a>
            </li>
            <!-- Tambahkan menu lainnya jika diperlukan -->
        </ul>
    </aside>

    <!-- Main Content -->
    <div class="dashboard">
        <div class="top-nav">
            <button class="hamburger" id="hamburger" aria-label="Toggle Navigation">
                &#9776;
            </button>
            <h2>Customers</h2>
        </div>
        <h2>Customer List</h2>

        <!-- Search and Filter -->
        <div class="search-container">
            <form method="GET" action="customer-list.php">
                <input type="text" name="search" placeholder="Search customers..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
                <select name="status_filter">
                    <option value="">All Status</option>
                    <option value="active" <?php if (isset($_GET['status_filter']) && $_GET['status_filter'] == 'active') echo 'selected'; ?>>Active</option>
                    <option value="inactive" <?php if (isset($_GET['status_filter']) && $_GET['status_filter'] == 'inactive') echo 'selected'; ?>>Inactive</option>
                    <option value="suspended" <?php if (isset($_GET['status_filter']) && $_GET['status_filter'] == 'suspended') echo 'selected'; ?>>Suspended</option>
                </select>
                <button type="submit">Search</button>
            </form>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Registered At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($customers) > 0): ?>
                        <?php foreach ($customers as $customer): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($customer['user_id']); ?></td>
                                <td><?php echo htmlspecialchars($customer['username']); ?></td>
                                <td><?php echo htmlspecialchars($customer['email']); ?></td>
                                <td>
                                    <span class="status-label <?php echo htmlspecialchars($customer['status']); ?>">
                                        <?php echo ucfirst(htmlspecialchars($customer['status'])); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($customer['created_at']); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="edit-customer.php?id=<?php echo $customer['user_id']; ?>" class="edit-button">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                        <a href="delete-customer.php?id=<?php echo $customer['user_id']; ?>" class="delete-button" onclick="return confirm('Are you sure you want to delete this customer?');">
                                            <i class="fa fa-trash"></i> Delete
                                        </a>

                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No customers found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Toggle Sidebar
        const hamburger = document.getElementById("hamburger");
        const sidebar = document.querySelector(".sidebar");

        hamburger.addEventListener("click", (e) => {
            e.stopPropagation(); // Prevent event bubbling
            sidebar.classList.toggle("active");
            hamburger.classList.toggle("active");
            document.body.classList.toggle("sidebar-active");
        });

        // Close sidebar when clicking outside
        document.addEventListener("click", (e) => {
            if (!sidebar.contains(e.target) && !hamburger.contains(e.target)) {
                sidebar.classList.remove("active");
                hamburger.classList.remove("active");
                document.body.classList.remove("sidebar-active");
            }
        });
    </script>
</body>

</html>