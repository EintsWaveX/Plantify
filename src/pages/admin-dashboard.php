<?php
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['admin_user_id'])) {
  // Redirect to admin login page
  header("Location: admin-login.php"); // Adjust path as necessary
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta
    name="author"
    content="Muhammad Zaenal Abidin Abdurrahman, Immanuel Eben Haezer Joseph Aletheia, and Darma Al Gani." />
  <meta name="description" content="Admin dashboard for Plantify." />
  <title>Plantify Admin Dashboard</title>
  <link rel="stylesheet" href="../css/admin-dashboard.css" />
  <!-- Jika Anda belum memiliki admin-dashboard.css, Anda dapat menghapus atau menyesuaikannya -->
  <style>
    /* Modal Styles */
    .modal {
      display: none;
      /* Hidden by default */
      position: fixed;
      /* Stay in place */
      z-index: 1001;
      /* Sit on top */
      left: 0;
      top: 0;
      width: 100%;
      /* Full width */
      height: 100%;
      /* Full height */
      overflow: auto;
      /* Enable scroll if needed */
      background-color: rgba(0, 0, 0, 0.5);
      /* Black with opacity */
      padding: 20px;
      /* Add some padding for smaller screens */
      box-sizing: border-box;
    }

    .modal-content {
      background-color: #fefefe;
      margin: auto;
      /* Center the modal */
      padding: 20px;
      border: 1px solid #888;
      width: 100%;
      /* Full width by default */
      max-width: 500px;
      /* Maximum width for larger screens */
      border-radius: 8px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    /* Close button */
    .close-order-modal {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
    }

    .close-order-modal:hover,
    .close-order-modal:focus {
      color: black;
      text-decoration: none;
    }

    /* Form Styles */
    #edit-order-form label {
      display: block;
      margin-top: 10px;
      font-size: 14px;
    }

    #edit-order-form input,
    #edit-order-form textarea,
    #edit-order-form select {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      box-sizing: border-box;
      font-size: 14px;
    }

    #edit-order-form button {
      margin-top: 15px;
      padding: 10px 20px;
      background-color: #4caf50;
      /* Green */
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 14px;
    }

    #edit-order-form button:hover {
      background-color: #45a049;
    }

    /* Status Labels */
    .status {
      padding: 5px 10px;
      border-radius: 4px;
      color: white;
      font-weight: bold;
      display: inline-block;
    }

    .status.shipped {
      background-color: #2196f3;
      /* Blue */
    }

    .status.pending {
      background-color: #ff9800;
      /* Orange */
    }

    .status.delivered {
      background-color: #4caf50;
      /* Green */
    }

    .status.cancelled {
      background-color: #f44336;
      /* Red */
    }

    /* Optional: Enhance table styling */
    .table-container table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    .table-container th,
    .table-container td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    .table-container tr:hover {
      background-color: #f1f1f1;
    }

    .table-container img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      margin-right: 10px;
      vertical-align: middle;
    }

    .edit-order {
      color: #2196f3;
      text-decoration: none;
      cursor: pointer;
    }

    .edit-order:hover {
      text-decoration: underline;
    }

    /* Responsive Modal Styles */

    /* For tablets and larger devices */
    @media (min-width: 600px) {
      .modal-content {
        width: 80%;
        /* Slightly smaller width on medium screens */
        max-width: 600px;
      }
    }

    /* For desktops and larger screens */
    @media (min-width: 992px) {
      .modal-content {
        width: 60%;
        /* Even smaller width on large screens */
        max-width: 800px;
      }
    }

    /* For small mobile devices */
    @media (max-width: 599px) {
      .modal-content {
        width: 100%;
        /* Full width on small screens */
        max-width: none;
        border-radius: 0;
        /* Remove border-radius for full-width modal */
        height: 100%;
        /* Full height */
        overflow-y: auto;
        /* Enable vertical scroll if content is too long */
        padding: 15px;
        /* Adjust padding */
      }

      .close-order-modal {
        font-size: 24px;
        /* Larger close button for better accessibility */
      }

      #edit-order-form button {
        width: 100%;
        /* Full-width button for easier tapping */
      }

      /* Ensure modal content is scrollable on small screens */
      .modal-content {
        max-height: 90vh;
        /* Limit height to 90% of viewport */
        overflow-y: auto;
      }

      /* Adjust font sizes for better readability */
      #edit-order-form label,
      #edit-order-form input,
      #edit-order-form textarea,
      #edit-order-form select,
      #edit-order-form button {
        font-size: 16px;
      }
    }
  </style>
</head>

<body>
  <!-- Sidebar -->
<aside class="sidebar">
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
      <a href="admin-dashboard.php" class="active">
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
      <a href="customer-list.php">
        <img id="customers" src="../assets/admin-dashboard/customer.svg" alt="Customers" />
        Customers
      </a>
    </li>
    <li>
      <a href="#reports">
        <img id="reports" src="../assets/admin-dashboard/reports.svg" alt="Reports" />
        Reports
      </a>
    </li>
    <li>
      <a href="#settings">
        <img id="settings" src="../assets/admin-dashboard/setting.svg" alt="Settings" />
        Settings
      </a>
    </li>
  </ul>
</aside>


  <!-- Main Content -->
  <div class="dashboard">
    <div class="top-nav">
      <button class="hamburger" id="hamburger" aria-label="Toggle Navigation">
        &#9776;
      </button>
      <h2>Dashboard</h2>
    </div>
    <h2>Dashboard</h2>
    <div class="cards">
      <div class="card">
        <div class="icon">
          <img
            src="../assets/admin-dashboard/users.svg"
            alt="Users" />
        </div>
        <div class="info">
          <h3>13</h3>
          <p>Total Customers</p>
        </div>
      </div>
      <div class="card">
        <div class="icon">
          <img
            src="../assets/admin-dashboard/money.svg"
            alt="Orders" />
        </div>
        <div class="info">
          <h3>20</h3>
          <p>Total Orders</p>
        </div>
      </div>
      <div class="card">
        <div class="icon">
          <img
            src="../assets/admin-dashboard/product.svg"
            alt="Products" />
        </div>
        <div class="info">
          <h3>21</h3>
          <p>Flowers Sold</p>
        </div>
      </div>
      <div class="card">
        <div class="icon">
          <img
            src="../assets/admin-dashboard/money.svg"
            alt="Revenue" />
        </div>
        <div class="info">
          <h3>$6969.69</h3>
          <p>This Month's Revenue</p>
        </div>
      </div>
    </div>
    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>Customer Name</th>
            <th>Order</th>
            <th>Status</th>
            <th>Delivery Date</th>
            <th>Edit</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <img
                src="../assets/admin-dashboard/image.png"
                alt="John Doe" />
              John Doe
            </td>
            <td>Roses Bouquet<br /><span>5 items</span></td>
            <td>
              <span class="status shipped">Shipped</span>
            </td>
            <td>2024-05-01</td>
            <td>
              <a href="#" class="edit-order" data-index="0">Edit</a>
            </td>
          </tr>
          <tr>
            <td>
              <img
                src="../assets/admin-dashboard/avatar-portrait-of-a-kid-child-caucasian-boy-in-round-blue-frame-illustration-in-cartoon-flat-style-vector-removebg-preview.png"
                alt="Oscar Rhys" />
              Oscar Rhys
            </td>
            <td>Tulip Bouquet<br /><span>3 items</span></td>
            <td>
              <span class="status pending">Pending</span>
            </td>
            <td>2024-05-02</td>
            <td>
              <a href="#" class="edit-order" data-index="1">Edit</a>
            </td>
          </tr>
          <tr>
            <td>
              <img
                src="../assets/admin-dashboard/image.png"
                alt="George Reece" />
              George Reece
            </td>
            <td>Orchid Bouquet<br /><span>2 items</span></td>
            <td>
              <span class="status delivered">Delivered</span>
            </td>
            <td>2024-05-03</td>
            <td>
              <a href="#" class="edit-order" data-index="2">Edit</a>
            </td>
          </tr>
          <tr>
            <td>
              <img
                src="../assets/admin-dashboard/image.png"
                alt="Thomas Joe" />
              Thomas Joe<br /><span>thomas@example.com</span>
            </td>
            <td>Lily Bouquet<br /><span>4 items</span></td>
            <td>
              <span class="status shipped">Shipped</span>
            </td>
            <td>2024-05-04</td>
            <td>
              <a href="#" class="edit-order" data-index="3">Edit</a>
            </td>
          </tr>
          <tr>
            <td>
              <img
                src="../assets/admin-dashboard/avatar-portrait-of-a-kid-child-caucasian-boy-in-round-blue-frame-illustration-in-cartoon-flat-style-vector-removebg-preview.png"
                alt="Charlie Kyle" />
              Charlie Kyle<br /><span>charlie@example.com</span>
            </td>
            <td>Sunflower Bouquet<br /><span>6 items</span></td>
            <td>
              <span class="status pending">Pending</span>
            </td>
            <td>2024-05-05</td>
            <td>
              <a href="#" class="edit-order" data-index="4">Edit</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <!-- Contoh link logout -->
  <a href="../database/admin_logout.php">Logout</a>


  <!-- Edit Order Modal -->
  <div id="edit-order-modal" class="modal">
    <div class="modal-content">
      <span class="close-order-modal">&times;</span>
      <h2>Edit Order Status</h2>
      <form id="edit-order-form">
        <!-- Hidden input to store the order index -->
        <input type="hidden" id="order-index" />

        <!-- Customer Name (Read-only) -->
        <label for="customer-name">Customer Name:</label>
        <input type="text" id="customer-name" name="customer-name" readonly />

        <!-- Order Details (Read-only) -->
        <label for="order-details">Order Details:</label>
        <textarea
          id="order-details"
          name="order-details"
          rows="3"
          readonly></textarea>

        <!-- Current Status (Read-only) -->
        <label for="current-status">Current Status:</label>
        <input
          type="text"
          id="current-status"
          name="current-status"
          readonly />

        <!-- New Status -->
        <label for="new-status">New Status:</label>
        <select id="new-status" name="new-status" required>
          <option value="">--Select Status--</option>
          <option value="Pending">Pending</option>
          <option value="Shipped">Shipped</option>
          <option value="Delivered">Delivered</option>
          <option value="Cancelled">Cancelled</option>
        </select>

        <button type="submit">Update Status</button>
      </form>
    </div>
  </div>

  <!-- Scripts -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const sidebarLinks = document.querySelectorAll(".sidebar-menu a");

      sidebarLinks.forEach((link) => {
        link.addEventListener("click", function(e) {
          // If link is not pointing to an external page or add-product.html, prevent default navigation
          const href = this.getAttribute("href");
          if (
            !href.startsWith("http") &&
            !href.endsWith(".html") &&
            href !== "#dashboard" &&
            href !== "#orders" &&
            href !== "#customers" &&
            href !== "#reports" &&
            href !== "#settings"
          ) {
            
          }

          // Remove 'active' class from all links
          sidebarLinks.forEach((l) => l.classList.remove("active"));

          // Add 'active' class to the clicked link
          this.classList.add("active");
        });
      });

      // Initialize order data
      let orders = [];

      // Load initial orders from the table
      const tableRows = document.querySelectorAll(
        ".table-container tbody tr"
      );
      tableRows.forEach((row, index) => {
        // Extract customer name (without the email if present)
        const customerNameRaw = row.cells[0].innerText.trim();
        const customerName = customerNameRaw.split("\n")[0];

        // Extract order details (e.g., "Roses Bouquet, 5 items")
        const orderDetailsText = row.cells[1].innerText;
        const orderDetails = orderDetailsText.replace(/\n/g, ", ");

        // Extract status without HTML tags
        const statusHTML = row.cells[2].innerHTML;
        const status = statusHTML.replace(/<[^>]*>?/gm, "").trim();

        // Extract delivery date
        const deliveryDate = row.cells[3].innerText.trim();

        // Add to orders array
        orders.push({
          customerName: customerName,
          orderDetails: orderDetails,
          status: status,
          deliveryDate: deliveryDate,
        });

        // Assign data-index to each Edit link
        const editLink = row.cells[4].querySelector(".edit-order");
        if (editLink) {
          editLink.setAttribute("data-index", index);
        }
      });

      // Modal Elements
      const editOrderModal = document.getElementById("edit-order-modal");
      const closeOrderModalBtn = document.querySelector(".close-order-modal");
      const editOrderForm = document.getElementById("edit-order-form");

      // Function to open the Edit Order Modal
      function openEditOrderModal(index) {
        const order = orders[index];
        document.getElementById("order-index").value = index;
        document.getElementById("customer-name").value = order.customerName;
        document.getElementById("order-details").value = order.orderDetails;
        document.getElementById("current-status").value = order.status;
        document.getElementById("new-status").value = ""; // Reset new status

        // Display the modal
        editOrderModal.style.display = "block";
      }

      // Function to close the Edit Order Modal
      function closeEditOrderModal() {
        editOrderModal.style.display = "none";
      }

      // Event listener for closing the modal
      closeOrderModalBtn.addEventListener("click", closeEditOrderModal);

      // Event listener for clicks outside the modal content
      window.addEventListener("click", function(event) {
        if (event.target == editOrderModal) {
          closeEditOrderModal();
        }
      });

      // Event listener for Edit Order links
      document.querySelectorAll(".edit-order").forEach((link) => {
        link.addEventListener("click", function(e) {
          
          const index = this.getAttribute("data-index");
          openEditOrderModal(index);
        });
      });

      // Event listener for form submission
      editOrderForm.addEventListener("submit", function(e) {
        

        const index = document.getElementById("order-index").value;
        const newStatus = document.getElementById("new-status").value;

        if (!newStatus) {
          alert("Silakan pilih status baru.");
          return;
        }

        // Update the order data
        orders[index].status = newStatus;

        // Update the table row
        const table = document.querySelector(".table-container tbody");
        const row = table.rows[index];
        const statusCell = row.cells[2];
        statusCell.innerHTML = `<span class="status ${newStatus.toLowerCase()}">${newStatus}</span>`;

        // Close the modal
        closeEditOrderModal();

        // Reset the form
        editOrderForm.reset();

        // Optional: Notify the user
        alert("Status order berhasil diperbarui.");
      });
    });

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