<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
include('config/db.php');

$id = $_GET['id'];
$conn->query("DELETE FROM products WHERE id=$id");
header("Location: products.php");
