<?php
class ActivityLogController extends Controller {
    public function index(): void {
        $allowed = ['tambah', 'edit', 'hapus', 'akses'];
        $type = $_GET['jenis'] ?? '';
        $type = in_array($type, $allowed, true) ? $type : '';
        $logs = ActivityLog::latest(200, $type ?: null);
        $this->view('admin/views/activity_logs/index', compact('logs', 'type'));
    }
}
