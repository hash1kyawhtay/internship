<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$host = "mysql-service";  // Kubernetes MySQL service
$user = "root";  
$pass = "root";  
$dbname = "internship";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $website = $_POST['website'];

    $uploadDir = __DIR__ . '/uploads/';   // absolute path
    $dbPathDir = 'uploads/';              // relative path for DB

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (in_array($_FILES['logo']['type'], $allowedTypes)) {
            $fileName = uniqid() . "_" . basename($_FILES['logo']['name']);
            $targetFilePath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetFilePath)) {
                $dbFilePath = $dbPathDir . $fileName;

                $sql = "INSERT INTO companies (name, location, logo, description, website) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssss", $name, $location, $dbFilePath, $description, $website);
                $stmt->execute();
                $stmt->close();

                header("Location: index.php");
                exit;
            } else {
                $message = "Error moving uploaded file.";
            }
        } else {
            $message = "Invalid file type. Only JPG, PNG, GIF, WEBP allowed.";
        }
    } else {
        $message = "No file uploaded or upload error.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Company</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
</head>
<body class="grey lighten-4">

<nav class="blue">
    <div class="nav-wrapper">
        <a href="index.php" class="brand-logo center">Add Company</a>
        <ul class="right">
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
</nav>

<div class="container" style="margin-top:30px;">
    <?php if($message) echo "<p class='red-text'>$message</p>"; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="input-field">
            <input type="text" name="name" required>
            <label>Company Name</label>
        </div>
        <div class="input-field">
            <input type="text" name="location" required>
            <label>Location</label>
        </div>
        <div class="input-field">
            <textarea name="description" class="materialize-textarea" required></textarea>
            <label>Description</label>
        </div>
        <div class="input-field">
            <input type="url" name="website" required>
            <label>Website</label>
        </div>
        <div class="file-field input-field">
            <div class="btn">
                <span>Logo</span>
                <input type="file" name="logo" accept="image/*" required>
            </div>
            <div class="file-path-wrapper">
                <input class="file-path validate" type="text">
            </div>
        </div>
        <button type="submit" class="btn green">Add Company</button>
    </form>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
