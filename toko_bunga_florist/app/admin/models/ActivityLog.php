<?php
class ActivityLog {
    public static function record(string $activity, ?string $description = null): void {
        try {
            $stmt = db()->prepare(
                'INSERT INTO activity_logs (admin_id, activity, description, ip_address)
                 VALUES (:admin_id, :activity, :description, :ip_address)'
            );
            $stmt->execute([
                ':admin_id' => $_SESSION['admin_id'] ?? null,
                ':activity' => $activity,
                ':description' => $description,
                ':ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            ]);
        } catch (Throwable $e) {
            // Jangan sampai kegagalan pencatatan log menghentikan fitur utama.
            error_log('ActivityLog error: ' . $e->getMessage());
        }
    }

    public static function latest(int $limit = 100, ?string $type = null): array {
        $limit = max(1, min(500, $limit));
        $where = '';
        switch ($type) {
            case 'tambah': $where = "WHERE al.activity LIKE 'Tambah%'"; break;
            case 'edit':   $where = "WHERE al.activity LIKE 'Edit%'"; break;
            case 'hapus':  $where = "WHERE al.activity LIKE 'Hapus%'"; break;
            case 'akses':  $where = "WHERE al.activity IN ('Login','Logout')"; break;
        }
        $stmt = db()->query(
            'SELECT al.id, al.activity, al.description, al.ip_address, al.created_at,
                    a.name AS admin_name
             FROM activity_logs al
             LEFT JOIN admins a ON a.id = al.admin_id
             ' . $where . '
             ORDER BY al.created_at DESC, al.id DESC
             LIMIT ' . $limit
        );
        return $stmt->fetchAll();
    }
}
