<?php
/**
 * One-time migration: Add email column to admins table.
 * Run this file ONCE via browser, then DELETE it for security.
 * URL: http://localhost/Tour_pkg_booking_system/add_admin_email.php
 */
require_once 'config/Database.php';

$database = new Database();
$db = $database->getConnection();

$messages = [];

// 1. Add email column if it doesn't exist
try {
    $db->exec("ALTER TABLE admins ADD COLUMN email VARCHAR(100) UNIQUE AFTER username");
    $messages[] = "✅ Column 'email' added to admins table.";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column') !== false) {
        $messages[] = "ℹ️ Column 'email' already exists — skipping.";
    } else {
        $messages[] = "❌ Error adding column: " . $e->getMessage();
    }
}

// 2. Set a default email for the existing admin (id=1) if not already set
try {
    $stmt = $db->prepare("UPDATE admins SET email = ? WHERE id = 1 AND (email IS NULL OR email = '')");
    $stmt->execute(['admin@ceylontours.com']);
    $affected = $stmt->rowCount();
    if ($affected > 0) {
        $messages[] = "✅ Default email 'admin@ceylontours.com' set for admin (id=1).";
    } else {
        $messages[] = "ℹ️ Admin (id=1) already has an email — not changed.";
    }
} catch (PDOException $e) {
    $messages[] = "❌ Error updating email: " . $e->getMessage();
}

// 3. Show current admin records
$stmt = $db->query("SELECT id, username, email, created_at FROM admins");
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Email Migration</title>
<style>
  body { font-family: Arial, sans-serif; max-width: 700px; margin: 40px auto; padding: 20px; }
  .msg { padding: 10px 14px; margin: 8px 0; border-radius: 6px; background: #f0f9f6; border-left: 4px solid #0f7b6c; }
  table { width: 100%; border-collapse: collapse; margin-top: 20px; }
  th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
  th { background: #0f7b6c; color: white; }
  .warn { background: #fff3cd; border-color: #f59e0b; color: #856404; padding: 12px; border-radius: 6px; margin-top: 20px; }
</style>
</head>
<body>
<h2>🛠 Admin Email Migration</h2>
<?php foreach ($messages as $m): ?>
  <div class="msg"><?= htmlspecialchars($m) ?></div>
<?php endforeach; ?>

<h3>Current admin records:</h3>
<table>
  <tr><th>ID</th><th>Username</th><th>Email</th><th>Created At</th></tr>
  <?php foreach ($admins as $a): ?>
  <tr>
    <td><?= $a['id'] ?></td>
    <td><?= htmlspecialchars($a['username']) ?></td>
    <td><?= htmlspecialchars($a['email'] ?? '(none)') ?></td>
    <td><?= $a['created_at'] ?></td>
  </tr>
  <?php endforeach; ?>
</table>

<div class="warn">
  ⚠️ <strong>Security Note:</strong> Please delete <code>add_admin_email.php</code> from your server after running this migration.
  <br>You can now log in at <a href="index.php?route=login">Login</a> using the email shown above.
</div>
</body>
</html>
