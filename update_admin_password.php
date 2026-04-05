<?php
$db = new SQLite3(__DIR__ . '/database/database.sqlite');
$newEmail = 'admin@demo.lomotanzaniasafari.com';
$oldEmail = 'admin@lomotanzania.com';
$newPassword = 'Arusha@#$20245##';
$hash = password_hash($newPassword, PASSWORD_BCRYPT);

// Try to update both possible emails
$db->exec("UPDATE users SET password = '$hash' WHERE email = '$newEmail' OR email = '$oldEmail'");
echo "Password updated for $newEmail and $oldEmail\n";
$db->close();
