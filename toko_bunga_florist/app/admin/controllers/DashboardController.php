<?php
class DashboardController extends Controller {
    public function index(): void {
        $stats = [
            'umkm' => (int)db()->query('SELECT COUNT(*) FROM umkm')->fetchColumn(),
            'active' => (int)db()->query("SELECT COUNT(*) FROM umkm WHERE status='active'")->fetchColumn(),
            'products' => (int)db()->query('SELECT COUNT(*) FROM products')->fetchColumn(),
        ];
        $umkm = Umkm::all();
        $recent = ActivityLog::latest(6);
        $this->view('admin/views/dashboard/index', compact('stats','umkm','recent'));
    }
}
