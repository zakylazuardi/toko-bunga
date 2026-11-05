<?php
session_start();
require_once __DIR__ . '/app/config/database.php';
require_once __DIR__ . '/app/core/Database.php';
require_once __DIR__ . '/app/core/Controller.php';
require_once __DIR__ . '/app/core/Router.php';
require_once __DIR__ . '/app/core/Auth.php';

require_once __DIR__ . '/app/admin/models/Admin.php';
require_once __DIR__ . '/app/admin/models/ActivityLog.php';
require_once __DIR__ . '/app/admin/models/Umkm.php';
require_once __DIR__ . '/app/admin/models/Product.php';
require_once __DIR__ . '/app/admin/models/Testimonial.php';
require_once __DIR__ . '/app/admin/models/Gallery.php';

require_once __DIR__ . '/app/admin/controllers/AuthController.php';
require_once __DIR__ . '/app/admin/controllers/ActivityLogController.php';
require_once __DIR__ . '/app/admin/controllers/DashboardController.php';
require_once __DIR__ . '/app/admin/controllers/UmkmController.php';
require_once __DIR__ . '/app/admin/controllers/ProductController.php';
require_once __DIR__ . '/app/admin/controllers/TestimonialController.php';
require_once __DIR__ . '/app/admin/controllers/GalleryController.php';
require_once __DIR__ . '/app/controllers/FloristController.php';

$router = new Router();
$router->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
