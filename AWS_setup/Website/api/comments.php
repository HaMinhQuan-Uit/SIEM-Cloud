<?php
if (isset($_POST['comment']) && $user) {
    $comment = $_POST['comment'];
    // SQLi vulnerable
    $conn->query("INSERT INTO comments (content) VALUES ('$comment')");
}
?>

<div class="card">
<h3>Comments</h3>

<?php if ($user): ?>
<form method="POST">
<textarea name="comment"></textarea><br>
<button>Post</button>
</form>
<?php endif; ?>

<hr>

<?php
$res = $conn->query("SELECT * FROM comments ORDER BY id DESC");
while ($row = $res->fetch_assoc()) {
    echo "<p>" . htmlspecialchars($row['content'], ENT_QUOTES) . "</p>";
}
?>
</div>
