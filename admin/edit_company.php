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

$id = intval($_GET['id']);
$message = "";

// Get existing data
$result = $conn->query("SELECT * FROM companies WHERE id=$id");
if ($result->num_rows == 0) {
    die("Company not found");
}
$company = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $website = $_POST['website'];

    $logoPath = $company['logo'];
    if (!empty($_FILES['logo']['name'])) {
        $targetDir = "../assets/logos/";
        if(!is_dir($targetDir)) mkdir($targetDir,0777,true);
        $logoPath = "assets/logos/" . basename($_FILES["logo"]["name"]);
        move_uploaded_file($_FILES["logo"]["tmp_name"], "../" . $logoPath);
    }

    $stmt = $conn->prepare("UPDATE companies SET name=?, logo=?, location=?, description=?, website=? WHERE id=?");
    $stmt->bind_param("sssssi", $name, $logoPath, $location, $description, $website, $id);

    if ($stmt->execute()) {
        $message = "Company updated successfully!";
        $company = ['name'=>$name,'logo'=>$logoPath,'location'=>$location,'description'=>$description,'website'=>$website];
    } else {
        $message = "Error updating company: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Company</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
</head>
<body class="grey lighten-4">

<nav class="blue">
    <div class="nav-wrapper">
        <a href="index.php" class="brand-logo center">Edit Company</a>
        <ul class="right">
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
</nav>

<div class="container" style="margin-top:30px;">
    <?php if($message) echo "<p class='green-text'>$message</p>"; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="input-field">
            <input type="text" name="name" value="<?php echo $company['name']; ?>" required>
            <label class="active">Company Name</label>
        </div>
        <div class="input-field">
            <input type="text" name="location" value="<?php echo $company['location']; ?>" required>
            <label class="active">Location</label>
        </div>
        <div class="input-field">
            <textarea name="description" class="materialize-textarea" required><?php echo $company['description']; ?></textarea>
            <label class="active">Description</label>
        </div>
        <div class="input-field">
            <input type="url" name="website" value="<?php echo $company['website']; ?>" required>
            <label class="active">Website</label>
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
        <button type="submit" class="btn blue">Update Company</button>
    </form>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
