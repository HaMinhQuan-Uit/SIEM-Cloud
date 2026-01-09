<div class="card">
<h3>Admin Dashboard</h3>

<table border="1" width="100%">
<tr><th>User</th><th>Role</th></tr>

<?php
$res = $conn->query("SELECT username, role FROM users");
while ($row = $res->fetch_assoc()) {
    echo "<tr>
        <td>{$row['username']}</td>
        <td>{$row['role']}</td>
    </tr>";
}
?>
</table>
</div>
