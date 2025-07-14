<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
include('config/db.php');

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $qty = $_POST['quantity'];
    $price = $_POST['price'];

    $sql = "INSERT INTO products (name, description, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssid", $name, $desc, $qty, $price);

    if ($stmt->execute()) {
        $message = '<div class="alert alert-success text-success">✔️ PRODUCT INSERTED SUCCESSFULLY!</div>';
    } else {
        $message = '<div class="alert alert-danger text-danger">❌ SYSTEM ERROR. COULD NOT ADD PRODUCT.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ADD PRODUCT | SYS-CTRL</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #0d0d0d;
      color: #00ff00;
      font-family: 'Courier New', monospace;
    }
    .terminal-box {
      background-color: #1a1a1a;
      padding: 30px;
      border-radius: 10px;
      border: 1px solid #00ff00;
      box-shadow: 0 0 10px #00ff00;
      margin-top: 50px;
    }
    .terminal-title {
      font-size: 24px;
      text-align: center;
      margin-bottom: 20px;
      text-shadow: 0 0 5px #00ff00;
    }
    label {
      color: #00ffcc;
    }
    .form-control {
      background-color: #000000;
      color: #00ff00;
      border: 1px solid #00ff00;
    }
    .btn-hack {
      background-color: black;
      border: 1px solid #00ff00;
      color: #00ff00;
    }
    .btn-hack:hover {
      background-color: #003300;
      color: #ffffff;
    }
    a {
      color: #00ccff;
      text-decoration: none;
    }
    footer {
      text-align: center;
      margin-top: 40px;
      color: #444;
      font-size: 14px;
    }
    .alert {
      background-color: #111;
      border-left: 4px solid #00ff00;
      padding: 10px;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="terminal-box">
    <div class="terminal-title">[+] SYSTEM: ADD PRODUCT MODULE</div>
    <?php if (!empty($message)) echo $message; ?>
    <form method="POST">
      <div class="mb-3">
        <label>Product Name:</label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Description:</label>
        <textarea name="description" class="form-control" rows="3"></textarea>
      </div>
      <div class="mb-3">
        <label>Quantity:</label>
        <input type="number" name="quantity" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Price (৳):</label>
        <input type="number" step="0.01" name="price" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-hack">>> EXECUTE INSERT</button>
      <a href="dashboard.php" class="btn btn-hack ms-2">← BACK TO SYS-DASH</a>
    </form>
  </div>
</div>

<footer>
  &copy; <?= date("Y") ?> SYS-CTRL INVENTORY | Developed by You 🧠
</footer>

</body>
</html>
