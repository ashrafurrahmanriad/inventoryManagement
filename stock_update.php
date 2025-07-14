<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
include('config/db.php');

// Validate ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: products.php");
    exit();
}
$id = intval($_GET['id']);

// Get Product
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product_result = $stmt->get_result();
if ($product_result->num_rows === 0) {
    header("Location: products.php");
    exit();
}
$product = $product_result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $change = intval($_POST['change']);
    $old_qty = $product['quantity'];
    $new_qty = $old_qty + $change;
    if ($new_qty < 0) $new_qty = 0;

    // Update product quantity
    $update_stmt = $conn->prepare("UPDATE products SET quantity = ? WHERE id = ?");
    $update_stmt->bind_param("ii", $new_qty, $id);
    $update_stmt->execute();

    // Insert into stock_history
    $updated_by = $_SESSION['user'];
    $history_stmt = $conn->prepare("INSERT INTO stock_history (product_id, old_quantity, change_amount, new_quantity, updated_by) VALUES (?, ?, ?, ?, ?)");
    $history_stmt->bind_param("iiiis", $id, $old_qty, $change, $new_qty, $updated_by);
    $history_stmt->execute();

    header("Location: products.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Update Stock | Inventory System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap');

    body {
      background-color: #0f1217;
      font-family: 'Share Tech Mono', monospace;
      color: #00ff99;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      user-select: none;
      margin: 0;
      padding: 20px;
    }
    .container {
      max-width: 480px;
      background: #111820;
      border-radius: 12px;
      padding: 30px 25px;
      box-shadow: 0 0 20px #00ff99aa;
      border: 1px solid #00ff99;
    }
    .card-header {
      background: transparent;
      border-bottom: 1px solid #00ff99;
      color: #00ff99;
      font-size: 1.6rem;
      padding-bottom: 10px;
      margin-bottom: 20px;
      text-shadow: 0 0 8px #00ff99;
    }
    label {
      color: #00ff99;
      font-weight: 600;
      text-shadow: 0 0 5px #00ff99;
    }
    input.form-control {
      background: #0f1217;
      border: 1.5px solid #00ff99;
      color: #00ff99;
      font-family: 'Share Tech Mono', monospace;
      box-shadow: inset 0 0 8px #00ff99;
      transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    input.form-control:focus {
      border-color: #00ffaa;
      box-shadow: 0 0 15px #00ffaa;
      outline: none;
      background: #101820;
      color: #00ffbb;
    }
    input[readonly] {
      background: #0a0e12;
      border-color: #007f4f;
      box-shadow: inset 0 0 6px #007f4f;
      color: #007f4f;
      font-weight: 700;
    }
    button.btn-success {
      background-color: #00ff99;
      border: none;
      font-weight: 700;
      color: #0f1217;
      box-shadow: 0 0 15px #00ff99;
      transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }
    button.btn-success:hover {
      background-color: #00cc7a;
      box-shadow: 0 0 20px #00cc7a;
      color: #e0ffe0;
    }
    a.btn-secondary {
      background-color: transparent;
      border: 1.5px solid #00ff99;
      color: #00ff99;
      text-shadow: 0 0 5px #00ff99;
      transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
      margin-left: 10px;
    }
    a.btn-secondary:hover {
      background-color: #00ff99;
      color: #0f1217;
      box-shadow: 0 0 12px #00ff99;
      border-color: #00ff99;
    }
  </style>
</head>
<body>
  <div class="container shadow-lg">
    <div class="card-header">
      Update Stock for: <span style="color:#00ffaa;"><?= htmlspecialchars($product['name']); ?></span>
    </div>
    <form method="POST" autocomplete="off">
      <div class="mb-3">
        <label for="currentQty">Current Quantity</label>
        <input id="currentQty" type="text" class="form-control" value="<?= $product['quantity']; ?>" readonly />
      </div>
      <div class="mb-3">
        <label for="changeQty">Change Quantity (+/-)</label>
        <input id="changeQty" type="number" name="change" class="form-control" required placeholder="Enter quantity to add or subtract" />
      </div>
      <button type="submit" class="btn btn-success">💾 Update Stock</button>
      <a href="products.php" class="btn btn-secondary">← Back to Products</a>
    </form>
  </div>
</body>
</html>
