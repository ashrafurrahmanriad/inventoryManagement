<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
include('config/db.php');

$result = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>All Products | Inventory System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    /* Hacking terminal vibes */
    body {
      background-color: #000;
      color: #00ff00;
      font-family: 'Courier New', Courier, monospace;
      letter-spacing: 0.05em;
      user-select: none;
    }

    .container {
      margin-top: 30px;
      border: 2px solid #00ff00;
      padding: 15px;
      border-radius: 5px;
      box-shadow:
        0 0 10px #00ff00,
        inset 0 0 10px #00ff00;
    }

    h2 {
      text-align: center;
      font-weight: 700;
      font-size: 2rem;
      text-shadow:
        0 0 8px #00ff00,
        0 0 15px #00ff00,
        0 0 20px #00ff00;
      margin-bottom: 25px;
    }

    table {
      background-color: #000;
      border: 1px solid #00ff00;
      box-shadow: 0 0 15px #00ff00 inset;
    }

    thead th {
      background-color: #003300;
      border-bottom: 2px solid #00ff00;
      color: #00ff00;
      text-transform: uppercase;
      font-size: 0.9rem;
    }

    tbody tr:hover {
      background-color: #003300;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    tbody td {
      border-top: 1px solid #004400;
      vertical-align: middle;
      font-weight: 600;
    }

    a.btn {
      font-weight: 700;
      font-size: 0.8rem;
      padding: 3px 8px;
      border-radius: 3px;
      border: 1px solid #00ff00;
      color: #00ff00 !important;
      background-color: transparent;
      box-shadow:
        0 0 5px #00ff00;
      transition: all 0.2s ease-in-out;
    }

    a.btn:hover {
      background-color: #00ff00;
      color: #000 !important;
      text-shadow: none;
      box-shadow: 0 0 15px #00ff00, 0 0 20px #00ff00;
    }

    .btn-warning {
      border-color: #ffaa00;
      color: #ffaa00 !important;
      box-shadow: 0 0 5px #ffaa00;
    }

    .btn-warning:hover {
      background-color: #ffaa00;
      color: #000 !important;
      box-shadow: 0 0 15px #ffaa00, 0 0 20px #ffaa00;
    }

    .btn-danger {
      border-color: #ff2200;
      color: #ff2200 !important;
      box-shadow: 0 0 5px #ff2200;
    }

    .btn-danger:hover {
      background-color: #ff2200;
      color: #000 !important;
      box-shadow: 0 0 15px #ff2200, 0 0 20px #ff2200;
    }

    .btn-info {
      border-color: #00aaff;
      color: #00aaff !important;
      box-shadow: 0 0 5px #00aaff;
    }

    .btn-info:hover {
      background-color: #00aaff;
      color: #000 !important;
      box-shadow: 0 0 15px #00aaff, 0 0 20px #00aaff;
    }

    .btn-secondary {
      border-color: #005500;
      color: #005500 !important;
      box-shadow: 0 0 5px #005500;
    }

    .btn-secondary:hover {
      background-color: #005500;
      color: #fff !important;
      box-shadow: 0 0 15px #00ff00, 0 0 20px #00ff00;
    }

    footer {
      margin-top: 40px;
      color: #008800;
      text-align: center;
      font-size: 0.9rem;
      font-weight: 700;
      text-shadow: 0 0 5px #00ff00;
      user-select: none;
    }

    /* Flicker animation to enhance hacking feel */
    @keyframes flicker {
      0%, 19%, 21%, 23%, 25%, 54%, 56%, 100% {
        opacity: 1;
      }
      20%, 22%, 24%, 55% {
        opacity: 0.5;
      }
    }

    h2 {
      animation: flicker 3s infinite;
    }

    /* Cursor effect on table header */
    thead th::after {
      content: "_";
      animation: blink 1s steps(2) infinite;
      color: #00ff00;
      margin-left: 2px;
    }

    @keyframes blink {
      0%, 50% {
        opacity: 1;
      }
      51%, 100% {
        opacity: 0;
      }
    }
  </style>
</head>
<body>

<div class="container">
  <h2>📦 Product Inventory List</h2>
  <table class="table table-striped table-bordered">
    <thead>
      <tr>
        <th>ID</th>
        <th>Product Name</th>
        <th>Quantity</th>
        <th>Per Product Price (৳)</th>
        <th>Total Product Price (৳)</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id']; ?></td>
        <td><?= htmlspecialchars($row['name']); ?></td>
        <td><?= $row['quantity']; ?></td>
        <td>৳ <?= number_format($row['price'], 2); ?></td>
        <td>৳ <?= number_format($row['quantity'] * $row['price'], 2); ?></td>
        <td>
          <a href="edit_product.php?id=<?= $row['id']; ?>" class="btn btn-warning">✏️ Edit</a>
          <a href="delete_product.php?id=<?= $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">🗑️ Delete</a>
          <a href="stock_update.php?id=<?= $row['id']; ?>" class="btn btn-info">📦 Update Stock</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <a href="dashboard.php" class="btn btn-secondary mt-3">← Back to Dashboard</a>
</div>

<footer>
  &copy; <?= date("Y") ?> Inventory Management System | Developed by You
</footer>

</body>
</html>
