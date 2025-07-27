<?php
session_start();



$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // For demo: use static username/password
    if ($username == "admin" && $password == "12345") {
        $_SESSION['admin_logged_in'] = true;
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
</head>
<body class="grey lighten-4">
<div class="container" style="margin-top:100px; max-width:400px;">
    <div class="card">
        <div class="card-content">
            <span class="card-title">Admin Login</span>
            <?php if($error) echo "<p class='red-text'>$error</p>"; ?>
            <form method="POST">
                <div class="input-field">
                    <input type="text" name="username" id="username" placeholder="Username" required>
                    
                </div>
                <div class="input-field">
                    <input type="password" name="password" id="password" placeholder="Password"required>
                    
                </div>
                <button class="btn blue waves-effect waves-light" type="submit">Login</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
