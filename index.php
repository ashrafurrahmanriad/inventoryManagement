<?php
session_start();
include('config/db.php');

$error = '';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if ($password === $user['password']) {
            $_SESSION['user'] = $user['username'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "❌ Invalid Password";
        }
    } else {
        $error = "❌ Invalid Username";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | Inventory Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #0f0f0f;
      color: #00ffcc;
      font-family: 'Courier New', monospace;
    }
    .login-container {
      max-width: 400px;
      margin: 100px auto;
      padding: 30px;
      background-color: #1a1a1a;
      border: 1px solid #00ffcc;
      border-radius: 8px;
      box-shadow: 0 0 20px #00ffcc33;
      animation: glow 3s infinite alternate;
    }

    @keyframes glow {
      0% { box-shadow: 0 0 10px #00ffcc33; }
      100% { box-shadow: 0 0 20px #00ffcc; }
    }

    .form-control {
      background-color: #0f0f0f;
      border: 1px solid #00ffcc;
      color: #00ffcc;
    }

    .form-control:focus {
      background-color: #000;
      color: #00ffcc;
      box-shadow: 0 0 5px #00ffcc;
      border-color: #00ffcc;
    }

    .btn-hacker {
      background-color: #00ffcc;
      color: #000;
      border: none;
      font-weight: bold;
      transition: 0.3s;
    }

    .btn-hacker:hover {
      background-color: #00ccaa;
      box-shadow: 0 0 10px #00ffcc;
    }

    .terminal-title {
      text-align: center;
      margin-bottom: 20px;
      font-size: 24px;
      border-bottom: 1px dashed #00ffcc;
      padding-bottom: 10px;
    }

    .error {
      color: #ff3366;
      background-color: #330000;
      border-left: 4px solid #ff3366;
      padding: 10px;
      margin-bottom: 15px;
    }

    footer {
      text-align: center;
      font-size: 12px;
      margin-top: 30px;
      color: #555;
    }
  </style>
</head>
<body>

  <div class="login-container">
    <div class="terminal-title">🔐 ACCESS PORTAL</div>

    <?php if (!empty($error)) : ?>
      <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" autocomplete="off">
      <div class="mb-3">
        <label class="form-label">👤 Username</label>
        <input type="text" name="username" class="form-control" required placeholder="root@user">
      </div>

      <div class="mb-3">
        <label class="form-label">🔑 Password</label>
        <input type="password" name="password" class="form-control" required placeholder="••••••••">
      </div>

      <button type="submit" class="btn btn-hacker w-100">ENTER SYSTEM</button>
    </form>
  </div>

  <footer>
    &copy; <?= date("Y") ?> SYS-ADMIN PANEL | Built for Inventory Ops
  </footer>

</body>
</html>
