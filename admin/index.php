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
$result = $conn->query("SELECT * FROM companies");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <style>
        .top-bar { margin: 20px 0; }
    </style>
</head>
<body class="grey lighten-4">

<nav class="blue">
    <div class="nav-wrapper">
        <a href="#" class="brand-logo center">Admin Dashboard</a>
        <ul class="right">
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
</nav>

<div class="container">
    <div class="row top-bar">
        <div class="col s12 right-align">
            <a href="add_company.php" class="btn green waves-effect waves-light">
                <i class="material-icons left"></i> Add Company
            </a>
        </div>
    </div>

    <table class="striped highlight responsive-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Logo</th>
                <th>Name</th>
                <th>Location</th>
                <th>Website</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><img src="../<?php echo $row['logo']; ?>" width="60"></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['location']; ?></td>
                <td><a href="<?php echo $row['website']; ?>" target="_blank">Visit</a></td>
                <td>
                    <a href="edit_company.php?id=<?php echo $row['id']; ?>" class="btn-small blue">Edit</a>
                    <a href="delete_company.php?id=<?php echo $row['id']; ?>" class="btn-small red"
                       onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
