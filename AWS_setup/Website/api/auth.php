<?php
require_once __DIR__ . "/../config/db.php";

if (isset($_GET['logout'])) {
    setcookie("user", "", time() - 3600, "/");
    header("Location: /");
    exit;
}

$login_error = false;

if (isset($_POST['username']) && isset($_POST['password'])) {
    $u = $_POST['username'];
    $p = $_POST['password'];

    // INTENTIONALLY VULNERABLE
    $sql = "SELECT * FROM users WHERE username='$u' AND password='$p'";
    $res = $conn->query($sql);

    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        setcookie("user", $row['username'], time()+3600, "/");
        header("Location: /");
        exit;
    } else {
        $login_error = true;
    }
}
?>
