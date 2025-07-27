<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$host = "mysql-service";  // Kubernetes service name for MySQL
$user = "root";  // default for MAMP
$pass = "root";  // default for MAMP
$dbname = "internship_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = intval($_GET['id']);
$conn->query("DELETE FROM companies WHERE id=$id");

header("Location: index.php");
exit;
