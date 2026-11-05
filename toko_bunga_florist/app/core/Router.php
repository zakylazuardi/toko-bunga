<?php
class Router {
    private string $base;
    public function __construct() {
        $script = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
        $this->base = rtrim($script, '/');
    }
    private function path(string $requestPath): string {
        if ($this->base && str_starts_with($requestPath, $this->base)) {
            $requestPath = substr($requestPath, strlen($this->base));
        }
        return '/' . trim($requestPath, '/');
    }
    public function dispatch(string $method, string $requestPath): void {
        $path = $this->path($requestPath);
        if ($path === '/') {
            (new FloristController())->home();
            return;
        }
        if ($path === '/admin/login') { (new AuthController())->login($method); return; }
        if ($path === '/admin/logout') { (new AuthController())->logout(); return; }
        if (str_starts_with($path, '/admin')) { $this->admin($path, $method); return; }
        if (preg_match('#^/umkm/([^/]+)/?$#', $path, $m)) { (new FloristController())->home($m[1]); return; }
        if (preg_match('#^/umkm/([^/]+)/products/?$#', $path, $m)) { (new FloristController())->products($m[1]); return; }
        if (preg_match('#^/umkm/([^/]+)/product/(\d+)/?$#', $path, $m)) { (new FloristController())->product($m[1], (int)$m[2]); return; }
        if (preg_match('#^/umkm/([^/]+)/testimonials/?$#', $path, $m)) { (new FloristController())->testimonials($m[1]); return; }
        if (preg_match('#^/umkm/([^/]+)/gallery/?$#', $path, $m)) { (new FloristController())->gallery($m[1]); return; }
        if (preg_match('#^/umkm/([^/]+)/blog/?$#', $path, $m)) { (new FloristController())->blog($m[1]); return; }
        http_response_code(404); echo '<h1>404 - Halaman tidak ditemukan</h1>';
    }
    private function admin(string $path, string $method): void {
        Auth::requireLogin();
        if ($path === '/admin' || $path === '/admin/dashboard') { (new DashboardController())->index(); return; }
        if ($path === '/admin/activity-logs') { (new ActivityLogController())->index(); return; }
        if ($path === '/admin/umkm/create') { (new UmkmController())->create($method); return; }
        if (preg_match('#^/admin/umkm/(\d+)/edit$#', $path, $m)) { (new UmkmController())->edit((int)$m[1], $method); return; }
        if (preg_match('#^/admin/umkm/(\d+)/delete$#', $path, $m)) { (new UmkmController())->delete((int)$m[1]); return; }
        if (preg_match('#^/admin/umkm/(\d+)/manage$#', $path, $m)) { (new UmkmController())->manage((int)$m[1]); return; }
        if (preg_match('#^/admin/umkm/(\d+)/preview$#', $path, $m)) { (new UmkmController())->preview((int)$m[1]); return; }
        if (preg_match('#^/admin/umkm/(\d+)/products/create$#', $path, $m)) { (new ProductController())->create((int)$m[1], $method); return; }
        if (preg_match('#^/admin/product/(\d+)/edit$#', $path, $m)) { (new ProductController())->edit((int)$m[1], $method); return; }
        if (preg_match('#^/admin/product/(\d+)/delete$#', $path, $m)) { (new ProductController())->delete((int)$m[1]); return; }
        if (preg_match('#^/admin/umkm/(\d+)/testimonials/create$#', $path, $m)) { (new TestimonialController())->create((int)$m[1], $method); return; }
        if (preg_match('#^/admin/testimonial/(\d+)/delete$#', $path, $m)) { (new TestimonialController())->delete((int)$m[1]); return; }
        if (preg_match('#^/admin/umkm/(\d+)/gallery/create$#', $path, $m)) { (new GalleryController())->create((int)$m[1], $method); return; }
        if (preg_match('#^/admin/gallery/(\d+)/delete$#', $path, $m)) { (new GalleryController())->delete((int)$m[1]); return; }
        http_response_code(404); echo '<h1>404 - Admin route tidak ditemukan</h1>';
    }
}

function base_url(string $path = ''): string {
    $base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
    return $base . '/' . ltrim($path, '/');
}
function e(?string $value): string { return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8'); }
function money($value): string { return 'Rp ' . number_format((float)$value, 0, ',', '.'); }

function log_badge(string $activity): string {
    $a = strtolower($activity);
    if (str_starts_with($a, 'tambah')) return 'green';
    if (str_starts_with($a, 'edit'))   return 'amber';
    if (str_starts_with($a, 'hapus'))  return 'red';
    return 'gray';
}
function log_time(?string $time): string {
    return $time ? date('d M Y, H:i', strtotime($time)) : '-';
}

function upload_image(string $field, string $folder): ?string {
    if (empty($_FILES[$field]['name']) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) return null;
    $allowed = ['jpg','jpeg','png','webp'];
    $ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed, true)) return null;
    $dir = __DIR__ . '/../../public/uploads/' . trim($folder, '/');
    if (!is_dir($dir)) mkdir($dir, 0777, true);
    $name = uniqid('', true) . '.' . $ext;
    move_uploaded_file($_FILES[$field]['tmp_name'], $dir . '/' . $name);
    return 'uploads/' . trim($folder, '/') . '/' . $name;
}
