<?php
const DB_HOST = 'localhost';
const DB_NAME = 'toko_bunga_florist';
const DB_USER = 'root';
const DB_PASS = '';

function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
        // Kirim identitas admin & IP ke MySQL supaya trigger activity_logs
        // tahu siapa yang menambah/mengubah/menghapus data.
        $adminId = isset($_SESSION['admin_id']) ? (int) $_SESSION['admin_id'] : 'NULL';
        $ip = isset($_SERVER['REMOTE_ADDR']) ? $pdo->quote($_SERVER['REMOTE_ADDR']) : 'NULL';
        $pdo->exec("SET @admin_id = {$adminId}, @ip_address = {$ip}");
    }
    return $pdo;
}
