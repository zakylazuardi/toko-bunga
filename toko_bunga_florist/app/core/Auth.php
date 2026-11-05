<?php
class Auth {
    public static function check(): bool { return !empty($_SESSION['admin_id']); }
    public static function requireLogin(): void {
        if (!self::check()) {
            header('Location: ' . base_url('admin/login'));
            exit;
        }
    }
    public static function login(int $id, string $name): void {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = $id;
        $_SESSION['admin_name'] = $name;
    }
    public static function logout(): void {
        $_SESSION = [];
        session_destroy();
    }
}
