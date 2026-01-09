<?php
$res = $conn->query("SELECT * FROM products");
?>

<div class="card">
<h3>Products</h3>
<table border="1" width="100%">
<tr><th>ID</th><th>Name</th><th>Action</th></tr>

<?php while ($row = $res->fetch_assoc()): ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['name'] ?></td>
<td><a href="?order=<?= $row['id'] ?>">Buy</a></td>
</tr>
<?php endwhile; ?>

</table>
</div>
