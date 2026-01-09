<?php
$user = $_COOKIE['user'] ?? null;
$role = "guest";

if ($user) {
    $res = $conn->query("SELECT role FROM users WHERE username='$user'");
    if ($res && $res->num_rows === 1) {
        $role = $res->fetch_assoc()['role'];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>ShopEase | Vulnerable Demo</title>
<link rel="stylesheet" href="/assets/style.css">
</head>
<body>

<header>
<h2>ShopEase Demo (Intentionally Vulnerable)</h2>
<nav>
<a href="/">Home</a>
<a href="?products=true">Products</a>
<a href="?comments=true">Comments</a>
<a href="?ping=true">Diagnostics</a>
<a href="?page=pages/help.php">Help</a>

<?php if ($role === "admin"): ?>
<a href="?admin=true">Admin</a>
<?php endif; ?>

<?php if ($user): ?>
<a href="?logout=true">Logout</a>
<?php endif; ?>
</nav>
</header>

<div class="container">
