<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$host = "mysql-service";  // Kubernetes service name for MySQL
$user = "root";  // default for MAMP
$pass = "root";  // default for MAMP
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
    
    // Handle logo upload
    $logoPath = "";
    if (!empty($_FILES['logo']['name'])) {
        $targetDir = "../assets/logos/";
        if(!is_dir($targetDir)) mkdir($targetDir,0777,true);
        $logoPath = "assets/logos/" . basename($_FILES["logo"]["name"]);
        move_uploaded_file($_FILES["logo"]["tmp_name"], "../" . $logoPath);
    }

    $stmt = $conn->prepare("INSERT INTO companies (name, logo, location, description, website) VALUES (?,?,?,?,?)");
    $stmt->bind_param("sssss", $name, $logoPath, $location, $description, $website);

    if ($stmt->execute()) {
        $message = "Company added successfully!";
    } else {
        $message = "Error adding company: " . $conn->error;
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
    <?php if($message) echo "<p class='green-text'>$message</p>"; ?>
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
                <input type="file" name="logo" accept="image/*">
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
