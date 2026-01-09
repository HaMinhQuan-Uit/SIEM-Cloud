<?php
require_once "config/db.php";
require_once "api/auth.php";
require_once "pages/header.php";
?>

<!-- REFLECTED XSS -->
<?php if (isset($_GET['msg'])): ?>
<div class="card">
<p><?= $_GET['msg'] ?></p>
</div>
<?php endif; ?>

<!-- LOGIN -->
<div class="card">
<h3>Login</h3>

<?php if ($user): ?>
<p>Logged in as <b><?= htmlspecialchars($user) ?></b> (role: <?= htmlspecialchars($role) ?>)</p>
<?php else: ?>
<form method="POST">
<input name="username">
<input name="password" type="password">
<button>Login</button>
</form>
<?php endif; ?>

<?php if ($login_error): ?>
<p class="badge">Login failed</p>
<?php endif; ?>
</div>

<?php
if (isset($_GET['products'])) include "api/products.php";
if (isset($_GET['comments'])) include "api/comments.php";
if (isset($_GET['admin'])) include "api/admin.php";
?>

<!-- COMMAND INJECTION -->
<?php if (isset($_GET['ping'])): ?>
<div class="card">
<form>
<input name="ping">
<button>Ping</button>
</form>
<pre>
<?php system("ping -c 2 " . $_GET['ping']); ?>
</pre>
</div>
<?php endif; ?>

<!-- LFI -->
<?php
if (isset($_GET['page'])) {
    include($_GET['page']);
}
?>

<?php require_once "pages/footer.php"; ?>
