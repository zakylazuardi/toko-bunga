<?php
require_once __DIR__ . '/app/config/database.php';

$username = 'admin';
$passwordHash = password_hash('admin123', PASSWORD_DEFAULT);

$st = db()->prepare('SELECT id FROM admins WHERE username = ? LIMIT 1');
$st->execute([$username]);

if ($st->fetch()) {
    $update = db()->prepare('UPDATE admins SET password = ?, name = ? WHERE username = ?');
    $update->execute([$passwordHash, 'Super Admin', $username]);
    echo 'Password akun admin berhasil diatur ulang.';
} else {
    $insert = db()->prepare('INSERT INTO admins (name, username, password) VALUES (?, ?, ?)');
    $insert->execute(['Super Admin', $username, $passwordHash]);
    echo 'Akun admin berhasil dibuat.';
}
