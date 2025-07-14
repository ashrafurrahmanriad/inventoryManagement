<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
include('config/db.php');

$id = $_GET['id'];
$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $qty = $_POST['quantity'];
    $price = $_POST['price'];

    $sql = "UPDATE products SET name=?, description=?, quantity=?, price=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssidi", $name, $desc, $qty, $price, $id);
    $stmt->execute();
    header("Location: products.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit Product | Inventory System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap');

    body {
      background-color: #0f1217;
      font-family: 'Share Tech Mono', monospace;
      color: #00ff99;
      user-select: none;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }
    .container {
      max-width: 600px;
      background: #111820;
      border-radius: 12px;
      box-shadow: 0 0 15px #00ff99aa;
      padding: 30px;
      border: 1px solid #00ff99;
    }
    .card-header {
      background: transparent;
      color: #00ff99;
      font-size: 1.8rem;
      margin-bottom: 25px;
      border-bottom: 1px solid #00ff99;
      padding-bottom: 10px;
      text-shadow: 0 0 8px #00ff99;
    }
    label {
      color: #00ff99;
      font-weight: 600;
      text-shadow: 0 0 5px #00ff99;
    }
    input.form-control, textarea.form-control {
      background: #0f1217;
      border: 1.5px solid #00ff99;
      color: #00ff99;
      font-family: 'Share Tech Mono', monospace;
      box-shadow: inset 0 0 6px #00ff99;
      transition: 0.3s ease;
    }
    input.form-control:focus, textarea.form-control:focus {
      border-color: #00ffaa;
      box-shadow: 0 0 12px #00ffaa;
      outline: none;
      background: #101820;
    }
    textarea.form-control {
      resize: vertical;
      min-height: 100px;
    }
    .btn-success {
      background-color: #00ff99;
      border: none;
      font-weight: 700;
      color: #0f1217;
      text-shadow: none;
      box-shadow: 0 0 12px #00ff99;
      transition: 0.3s ease;
    }
    .btn-success:hover {
      background-color: #00cc7a;
      box-shadow: 0 0 18px #00cc7a;
      color: #e0ffe0;
    }
    .btn-secondary {
      background-color: transparent;
      border: 1.5px solid #00ff99;
      color: #00ff99;
      text-shadow: 0 0 5px #00ff99;
      transition: 0.3s ease;
    }
    .btn-secondary:hover {
      background-color: #00ff99;
      color: #0f1217;
      border-color: #00ff99;
      box-shadow: 0 0 10px #00ff99;
    }
    footer {
      margin-top: 40px;
      text-align: center;
      color: #007f4f;
      font-family: 'Share Tech Mono', monospace;
      text-shadow: 0 0 6px #007f4f;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="card-header">
    <h3>⌨️ Edit Product Information</h3>
  </div>
  <form method="POST" autocomplete="off">
    <div class="mb-3">
      <label for="name">Product Name</label>
      <input id="name" type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required autocomplete="off" />
    </div>
    <div class="mb-3">
      <label for="description">Description</label>
      <textarea id="description" name="description" class="form-control"><?= htmlspecialchars($product['description']) ?></textarea>
    </div>
    <div class="mb-3">
      <label for="quantity">Quantity</label>
      <input id="quantity" type="number" name="quantity" class="form-control" value="<?= $product['quantity'] ?>" required />
    </div>
    <div class="mb-3">
      <label for="price">Price (৳)</label>
      <input id="price" type="number" step="0.01" name="price" class="form-control" value="<?= $product['price'] ?>" required />
    </div>
    <button type="submit" class="btn btn-success">💾 Update Product</button>
    <a href="products.php" class="btn btn-secondary ms-2">← Back to Products</a>
  </form>
</div>

<footer>
  &copy; <?= date("Y") ?> Inventory Management System
</footer>

</body>
</html>
