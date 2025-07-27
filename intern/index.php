<?php
$host = "localhost";
$user = "root";  // default for MAMP
$pass = "root";  // default for MAMP
$dbname = "internship_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$search = "";
if (isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $sql = "SELECT * FROM companies 
            WHERE name LIKE '%$search%' 
            OR location LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM companies";
}
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Internship Companies</title>
    <!-- Materialize CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        .company-card { transition: transform 0.2s ease-in-out; }
        .company-card:hover { transform: translateY(-5px); }
        .card-image img {
            height: 180px;
            object-fit: contain;
            background: #f9f9f9;
        }
        .search-bar {
            margin-top: 20px;
            margin-bottom: 40px;
        }
    </style>
</head>
<body class="grey lighten-4">

<nav class="blue">
    <div class="nav-wrapper">
        <a href="#" class="brand-logo center">Internship Companies</a>
    </div>
</nav>

<div class="container">
    <!-- Search Bar -->
    <div class="row search-bar">
        <form method="GET" action="">
            <div class="input-field col s10">
                <input id="search" type="text" name="search" value="<?php echo htmlspecialchars($search); ?>">
                <label for="search">Search Company or Location</label>
            </div>
            <div class="input-field col s2">
                <button class="btn waves-effect waves-light blue" type="submit">
                    <i class="material-icons">search</i>
                </button>
            </div>
        </form>
    </div>

    <!-- Cards -->
    <div class="row">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
        ?>
            <div class="col s12 m6 l4">
                <div class="card company-card">
                    <div class="card-image">
                        <img src="<?php echo $row['logo']; ?>" alt="<?php echo $row['name']; ?>">
                    </div>
                    <div class="card-content">
                        <span class="card-title"><?php echo $row['name']; ?></span>
                        <p><i class="material-icons tiny">place</i> <?php echo $row['location']; ?></p>
                        <p><?php echo substr($row['description'], 0, 80) . '...'; ?></p>
                    </div>
                    <div class="card-action">
                        <a href="<?php echo $row['website']; ?>" target="_blank">Visit Website</a>
                    </div>
                </div>
            </div>
        <?php
            }
        } else {
            echo "<p class='center-align'>No companies found</p>";
        }
        ?>
    </div>
</div>

<!-- Materialize JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
