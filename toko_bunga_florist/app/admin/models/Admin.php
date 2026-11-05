<?php
class Admin
{
    public static function findByUsername(string $username): ?array
    {
        // Sesuai dengan database/database.sql: tabel admins (id, name, username, password)
        $st = db()->prepare(
            'SELECT id AS admin_id, name AS nama_admin, username, password
             FROM admins WHERE username = ? LIMIT 1'
        );
        $st->execute([$username]);

        return $st->fetch() ?: null;
    }
}
