<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
include('config/db.php');

// Fetch all products for dropdown
$products = $conn->query("SELECT id, name FROM products");

// Handle filter input
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
$product_id = $_GET['product'] ?? '';

// Fetch history data
$where = "WHERE 1=1";
if ($from && $to) {
    $where .= " AND DATE(updated_at) BETWEEN '$from' AND '$to'";
}
if ($product_id) {
    $where .= " AND product_id = " . intval($product_id);
}

$sql = "SELECT h.*, p.name AS product_name FROM stock_history h 
        JOIN products p ON h.product_id = p.id 
        $where 
        ORDER BY h.updated_at DESC";
$history = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Stock Update History | Hacker Mode</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap');

    body {
      background-color: #0f0f0f;
      color: #00ff00;
      font-family: 'Share Tech Mono', monospace;
      min-height: 100vh;
      padding: 20px 0;
      user-select: none;
    }
    h2 {
      text-align: center;
      text-shadow:
        0 0 5px #00ff00,
        0 0 10px #00ff00,
        0 0 20px #0f0;
      margin-bottom: 2rem;
      letter-spacing: 2px;
    }
    form {
      background-color: #111;
      border: 1px solid #00ff00;
      padding: 20px;
      border-radius: 8px;
      box-shadow:
        0 0 10px #00ff00;
      margin-bottom: 2rem;
    }
    label {
      color: #0f0;
      font-weight: bold;
      font-size: 0.9rem;
    }
    input[type="date"],
    select.form-select {
      background-color: #000;
      color: #00ff00;
      border: 1px solid #0f0;
      border-radius: 4px;
      font-family: 'Share Tech Mono', monospace;
    }
    input[type="date"]::placeholder,
    select.form-select option {
      color: #0f0;
    }
    input[type="date"]:focus,
    select.form-select:focus {
      outline: none;
      box-shadow:
        0 0 5px #00ff00,
        0 0 10px #0f0;
    }
    button.btn-primary {
      background-color: transparent;
      border: 1px solid #00ff00;
      color: #00ff00;
      font-family: 'Share Tech Mono', monospace;
      letter-spacing: 1px;
      transition: all 0.3s ease;
    }
    button.btn-primary:hover {
      background-color: #00ff00;
      color: #000;
      box-shadow:
        0 0 15px #0f0,
        0 0 30px #0f0;
    }
    .table-responsive {
      background-color: #111;
      border: 1px solid #0f0;
      box-shadow:
        0 0 15px #0f0;
      border-radius: 8px;
      overflow-x: auto;
    }
    table {
      border-collapse: collapse;
      width: 100%;
      font-size: 0.9rem;
      letter-spacing: 0.5px;
    }
    thead {
      background-color: #000;
      border-bottom: 2px solid #0f0;
      color: #0f0;
      text-shadow: 0 0 3px #0f0;
    }
    tbody tr {
      border-bottom: 1px solid #004400;
    }
    tbody tr:hover {
      background-color: #003300;
      cursor: default;
    }
    td, th {
      padding: 10px 12px;
      text-align: center;
    }
    .text-success {
      color: #0f0 !important;
      font-weight: bold;
      text-shadow: 0 0 5px #0f0;
    }
    .text-danger {
      color: #f00 !important;
      font-weight: bold;
      text-shadow: 0 0 5px #f00;
    }
    a.btn-secondary {
      display: inline-block;
      margin-top: 20px;
      color: #0f0;
      border: 1px solid #0f0;
      background: transparent;
      font-family: 'Share Tech Mono', monospace;
      padding: 8px 15px;
      border-radius: 5px;
      text-decoration: none;
      transition: all 0.3s ease;
    }
    a.btn-secondary:hover {
      background-color: #0f0;
      color: #000;
      box-shadow:
        0 0 10px #0f0;
    }

    /* Glitch effect on header */
    h2.glitch {
      position: relative;
      color: #0f0;
    }
    h2.glitch::before,
    h2.glitch::after {
      content: attr(data-text);
      position: absolute;
      left: 0;
      width: 100%;
      height: 100%;
      top: 0;
      opacity: 0.8;
      clip: rect(0, 900px, 0, 0);
    }
    h2.glitch::before {
      left: 2px;
      text-shadow: -2px 0 red;
      animation: glitch-anim 2s infinite linear alternate-reverse;
    }
    h2.glitch::after {
      left: -2px;
      text-shadow: -2px 0 blue;
      animation: glitch-anim2 3s infinite linear alternate-reverse;
    }
    @keyframes glitch-anim {
      0% {
        clip: rect(0, 900px, 0, 0);
      }
      20% {
        clip: rect(10px, 900px, 85px, 0);
      }
      40% {
        clip: rect(45px, 900px, 60px, 0);
      }
      60% {
        clip: rect(30px, 900px, 80px, 0);
      }
      80% {
        clip: rect(15px, 900px, 50px, 0);
      }
      100% {
        clip: rect(0, 900px, 0, 0);
      }
    }
    @keyframes glitch-anim2 {
      0% {
        clip: rect(0, 900px, 0, 0);
      }
      25% {
        clip: rect(20px, 900px, 70px, 0);
      }
      50% {
        clip: rect(10px, 900px, 90px, 0);
      }
      75% {
        clip: rect(30px, 900px, 60px, 0);
      }
      100% {
        clip: rect(0, 900px, 0, 0);
      }
    }
  </style>
</head>
<body>

<div class="container">
  <h2 class="glitch" data-text="📜 Stock Update History">📜 Stock Update History</h2>

  <form class="row g-3 mb-4" method="get" autocomplete="off" spellcheck="false" autocorrect="off" autocapitalize="none">
    <div class="col-md-3">
      <label class="form-label" for="fromDate">From Date</label>
      <input type="date" id="fromDate" class="form-control" name="from" value="<?= htmlspecialchars($from) ?>" />
    </div>
    <div class="col-md-3">
      <label class="form-label" for="toDate">To Date</label>
      <input type="date" id="toDate" class="form-control" name="to" value="<?= htmlspecialchars($to) ?>" />
    </div>
    <div class="col-md-4">
      <label class="form-label" for="productSelect">Select Product</label>
      <select id="productSelect" name="product" class="form-select">
        <option value="">-- All Products --</option>
        <?php while ($row = $products->fetch_assoc()): ?>
          <option value="<?= $row['id'] ?>" <?= $row['id'] == $product_id ? 'selected' : '' ?>>
            <?= htmlspecialchars($row['name']) ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="col-md-2 align-self-end">
      <button type="submit" class="btn btn-primary w-100">🔍 Filter</button>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
      <thead>
        <tr>
          <th>#</th>
          <th>Product</th>
          <th>Old Qty</th>
          <th>Change</th>
          <th>New Qty</th>
          <th>Updated By</th>
          <th>Updated At</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($history->num_rows > 0): $i = 1; ?>
          <?php while ($row = $history->fetch_assoc()): ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($row['product_name']) ?></td>
            <td><?= $row['old_quantity'] ?></td>
            <td class="<?= $row['change_amount'] >= 0 ? 'text-success' : 'text-danger' ?>">
              <?= $row['change_amount'] >= 0 ? '+' : '' ?><?= $row['change_amount'] ?>
            </td>
            <td><?= $row['new_quantity'] ?></td>
            <td><?= htmlspecialchars($row['updated_by']) ?></td>
            <td><?= $row['updated_at'] ?></td>
          </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="7" class="text-center text-muted" style="color:#006400;">No history found for selected criteria.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <a href="products.php" class="btn btn-secondary">← Back to Products</a>
</div>

</body>
</html>
