<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
include('config/db.php');

$count = $conn->query("SELECT COUNT(*) as total FROM products")->fetch_assoc()['total'];
$out_of_stock = $conn->query("SELECT COUNT(*) as total FROM products WHERE quantity = 0")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>💻 Dashboard | Hackventory System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #000;
      color: #00ff9f;
      font-family: 'Courier New', Courier, monospace;
      padding-bottom: 50px;
    }
    .dashboard-header {
      background-color: #0a0a0a;
      color: #00ff9f;
      padding: 30px;
      text-align: center;
      border-bottom: 2px solid #00ff9f;
      box-shadow: 0 0 20px #00ff9f;
    }
    .card {
      background-color: #111;
      border: 1px solid #00ff9f;
      color: #00ff9f;
      box-shadow: 0 0 15px rgba(0,255,159,0.3);
      transition: 0.3s;
    }
    .card:hover {
      transform: scale(1.02);
      box-shadow: 0 0 25px rgba(0,255,159,0.6);
    }
    .card-title {
      font-size: 1.3rem;
    }
    .btn-nav {
      margin: 5px;
      background-color: transparent;
      border: 1px solid #00ff9f;
      color: #00ff9f;
      font-weight: bold;
      transition: 0.2s ease;
    }
    .btn-nav:hover {
      background-color: #00ff9f;
      color: #000;
      box-shadow: 0 0 10px #00ff9f;
    }
    footer {
      position: fixed;
      bottom: 0;
      width: 100%;
      text-align: center;
      background-color: #0a0a0a;
      color: #888;
      font-size: 13px;
      padding: 10px 0;
      border-top: 1px solid #00ff9f;
    }
    .glitch-text {
      animation: flicker 1.5s infinite alternate;
      text-shadow: 0 0 3px #00ff9f, 0 0 5px #00ff9f;
    }
    @keyframes flicker {
      0% { opacity: 1; }
      50% { opacity: 0.85; }
      100% { opacity: 1; }
    }
  </style>
</head>
<body>

  <div class="dashboard-header">
    <h2 class="glitch-text">👨‍💻 Access Granted: <?= htmlspecialchars($_SESSION['user']) ?></h2>
    <p>System Node: Inventory Control Panel</p>
  </div>

  <div class="container mt-5">
    <div class="row mb-4">
      <div class="col-md-6">
        <div class="card mb-3">
          <div class="card-body">
            <h5 class="card-title">📦 Total Products</h5>
            <p class="card-text fs-4"><?= $count ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card mb-3" style="border-color:#ff004c; color:#ff004c;">
          <div class="card-body">
            <h5 class="card-title">⚠️ Out of Stock</h5>
            <p class="card-text fs-4"><?= $out_of_stock ?></p>
          </div>
        </div>
      </div>
    </div>

    <div class="text-center mb-4">
      <a href="add_product.php" class="btn btn-nav">➕ Add Product</a>
      <a href="products.php" class="btn btn-nav">📄 View Products</a>
      <a href="stock_history.php" class="btn btn-nav">📋 Stock History</a>
      <a href="logout.php" class="btn btn-nav">🔒 Logout</a>
    </div>
  </div>

  <footer>
    ⌛ <?= date("Y") ?> | Hackventory v1.0 | Coded in the shadows 🕶️
  </footer>

</body>
</html>
