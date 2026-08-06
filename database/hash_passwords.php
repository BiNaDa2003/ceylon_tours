<?php
/**
 * One-time security migration: Hash plain-text passwords for admins & customers.
 * Run this file ONCE via browser, then DELETE it for security.
 * URL: http://localhost/Tour_pkg_booking_system/database/hash_passwords.php
 */
require_once dirname(__DIR__) . '/config/Database.php';

$database = new Database();
$db = $database->getConnection();
if (!$db) { exit('Database connection failed.'); }

$messages = [];

function hashPlainPasswords($db, $table, $messages) {
    $stmt = $db->query("SELECT id, password FROM `$table`");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row) {
        $info = password_get_info($row['password']);
        // If the stored value is NOT a valid password hash, hash it.
        if (empty($info['algo'])) {
            $hash = password_hash($row['password'], PASSWORD_DEFAULT);
            $upd  = $db->prepare("UPDATE `$table` SET password = ? WHERE id = ?");
            $upd->execute([$hash, $row['id']]);
            $messages[] = "✅ $table (id={$row['id']}): plain-text password hashed.";
        }
    }
    return $messages;
}

$messages = hashPlainPasswords($db, 'admins', $messages);
$messages = hashPlainPasswords($db, 'customers', $messages);

if (count($messages) === 0) {
    $messages[] = "ℹ️ No plain-text passwords found — everything is already hashed.";
}

// Sanity check: verify all stored passwords are hashes now.
$bad = 0;
foreach (['admins', 'customers'] as $table) {
    $rows = $db->query("SELECT password FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $row) {
        if (empty(password_get_info($row['password'])['algo'])) $bad++;
    }
}
if ($bad > 0) {
    $messages[] = "⚠️ $bad password(s) still stored in plain text.";
} else {
    $messages[] = "✅ All admin and customer passwords are now stored as hashes.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Password Hashing Migration</title>
<style>
  body { font-family: Arial, sans-serif; max-width: 700px; margin: 40px auto; padding: 20px; }
  .msg { padding: 10px 14px; margin: 8px 0; border-radius: 6px; background: #f0f9f6; border-left: 4px solid #0f7b6c; }
  .warn { background: #fff3cd; border-color: #f59e0b; color: #856404; padding: 12px; border-radius: 6px; margin-top: 20px; }
</style>
</head>
<body>
<h2>🔐 Password Hashing Migration</h2>
<?php foreach ($messages as $m): ?>
  <div class="msg"><?= htmlspecialchars($m) ?></div>
<?php endforeach; ?>

<div class="warn">
  ⚠️ <strong>Security Note:</strong> Please delete <code>database/hash_passwords.php</code> from your server after running this migration.
</div>
</body>
</html>
