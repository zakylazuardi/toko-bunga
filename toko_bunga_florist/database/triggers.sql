-- =====================================================================
-- TRIGGER LOG AKTIVITAS (TAMBAH / EDIT / HAPUS)
-- Database : toko_bunga_florist
--
-- Cara pakai:
--   phpMyAdmin : pilih database toko_bunga_florist -> tab SQL -> tempel -> Go
--   MySQL CLI  : mysql -u root toko_bunga_florist < trigger_toko_bunga_florist.sql
--
-- Setiap data di tabel umkm, products, gallery, testimonials,
-- dan admins yang DITAMBAH / DIUBAH / DIHAPUS otomatis dicatat ke
-- tabel activity_logs, baik lewat website maupun lewat phpMyAdmin.
--
-- Pelaku & IP diambil dari variabel sesi MySQL yang diisi website:
--   SET @admin_id = <id admin login>, @ip_address = '<ip>';
-- (sudah dipasang di app/config/database.php). Kalau tidak diisi
-- (misalnya edit langsung di phpMyAdmin), kolom admin_id/ip = NULL.
-- =====================================================================

DROP PROCEDURE IF EXISTS `sp_catat_aktivitas`;

DELIMITER $$

-- Prosedur pembantu: satu pintu untuk menulis ke activity_logs.
-- Kalau admin_id tidak ada di tabel admins (mis. admin baru saja dihapus),
-- diganti NULL supaya foreign key tidak menggagalkan operasi utama.
CREATE PROCEDURE `sp_catat_aktivitas` (IN p_admin INT, IN p_activity VARCHAR(100), IN p_description TEXT)
BEGIN
    IF p_admin IS NOT NULL AND NOT EXISTS (SELECT 1 FROM admins WHERE id = p_admin) THEN
        SET p_admin = NULL;
    END IF;
    INSERT INTO activity_logs (admin_id, activity, description, ip_address)
    VALUES (p_admin, p_activity, p_description, @ip_address);
END$$

DELIMITER ;

-- Hapus trigger lama (kalau ada) supaya script aman dijalankan ulang
DROP TRIGGER IF EXISTS `trg_umkm_ai`;
DROP TRIGGER IF EXISTS `trg_umkm_au`;
DROP TRIGGER IF EXISTS `trg_umkm_ad`;
DROP TRIGGER IF EXISTS `trg_products_ai`;
DROP TRIGGER IF EXISTS `trg_products_au`;
DROP TRIGGER IF EXISTS `trg_products_ad`;
DROP TRIGGER IF EXISTS `trg_gallery_ai`;
DROP TRIGGER IF EXISTS `trg_gallery_au`;
DROP TRIGGER IF EXISTS `trg_gallery_ad`;
DROP TRIGGER IF EXISTS `trg_testimonials_ai`;
DROP TRIGGER IF EXISTS `trg_testimonials_au`;
DROP TRIGGER IF EXISTS `trg_testimonials_ad`;
DROP TRIGGER IF EXISTS `trg_admins_ai`;
DROP TRIGGER IF EXISTS `trg_admins_au`;
DROP TRIGGER IF EXISTS `trg_admins_ad`;
DELIMITER $$

CREATE TRIGGER `trg_umkm_ai` AFTER INSERT ON `umkm` FOR EACH ROW
BEGIN
    CALL sp_catat_aktivitas(@admin_id, 'Tambah UMKM',
        CONCAT('Menambahkan UMKM: ', NEW.name));
END$$

CREATE TRIGGER `trg_umkm_au` AFTER UPDATE ON `umkm` FOR EACH ROW
BEGIN
    DECLARE v_ubah TEXT;
    SET v_ubah = CONCAT_WS('; ',
        IF(NOT (OLD.name <=> NEW.name), CONCAT('nama: ', COALESCE(CAST(OLD.name AS CHAR),'-'), ' -> ', COALESCE(CAST(NEW.name AS CHAR),'-')), NULL),
        IF(NOT (OLD.slug <=> NEW.slug), CONCAT('slug: ', COALESCE(CAST(OLD.slug AS CHAR),'-'), ' -> ', COALESCE(CAST(NEW.slug AS CHAR),'-')), NULL),
        IF(NOT (OLD.template <=> NEW.template), CONCAT('template: ', COALESCE(CAST(OLD.template AS CHAR),'-'), ' -> ', COALESCE(CAST(NEW.template AS CHAR),'-')), NULL),
        IF(NOT (OLD.description <=> NEW.description), 'deskripsi diubah', NULL),
        IF(NOT (OLD.address <=> NEW.address), 'alamat diubah', NULL),
        IF(NOT (OLD.phone <=> NEW.phone), CONCAT('telepon: ', COALESCE(CAST(OLD.phone AS CHAR),'-'), ' -> ', COALESCE(CAST(NEW.phone AS CHAR),'-')), NULL),
        IF(NOT (OLD.email <=> NEW.email), CONCAT('email: ', COALESCE(CAST(OLD.email AS CHAR),'-'), ' -> ', COALESCE(CAST(NEW.email AS CHAR),'-')), NULL),
        IF(NOT (OLD.hours <=> NEW.hours), CONCAT('jam buka: ', COALESCE(CAST(OLD.hours AS CHAR),'-'), ' -> ', COALESCE(CAST(NEW.hours AS CHAR),'-')), NULL),
        IF(NOT (OLD.hero_title <=> NEW.hero_title), CONCAT('judul hero: ', COALESCE(CAST(OLD.hero_title AS CHAR),'-'), ' -> ', COALESCE(CAST(NEW.hero_title AS CHAR),'-')), NULL),
        IF(NOT (OLD.hero_text <=> NEW.hero_text), 'teks hero diubah', NULL),
        IF(NOT (OLD.hero_image <=> NEW.hero_image), 'gambar hero diganti', NULL),
        IF(NOT (OLD.status <=> NEW.status), CONCAT('status: ', COALESCE(CAST(OLD.status AS CHAR),'-'), ' -> ', COALESCE(CAST(NEW.status AS CHAR),'-')), NULL)
    );
    IF v_ubah IS NOT NULL AND v_ubah <> '' THEN
        CALL sp_catat_aktivitas(@admin_id, 'Edit UMKM',
            CONCAT(CONCAT('Mengubah data UMKM: ', NEW.name), ' | ', v_ubah));
    END IF;
END$$

CREATE TRIGGER `trg_umkm_ad` AFTER DELETE ON `umkm` FOR EACH ROW
BEGIN
    CALL sp_catat_aktivitas(@admin_id, 'Hapus UMKM',
        CONCAT('Menghapus UMKM: ', OLD.name));
END$$

CREATE TRIGGER `trg_products_ai` AFTER INSERT ON `products` FOR EACH ROW
BEGIN
    DECLARE v_umkm VARCHAR(150);
    SET v_umkm = (SELECT name FROM umkm WHERE id = NEW.umkm_id);
    CALL sp_catat_aktivitas(@admin_id, 'Tambah Produk',
        CONCAT('Menambahkan produk: ', NEW.name, ' pada UMKM ', COALESCE(v_umkm, '-')));
END$$

CREATE TRIGGER `trg_products_au` AFTER UPDATE ON `products` FOR EACH ROW
BEGIN
    DECLARE v_ubah TEXT;
    DECLARE v_umkm VARCHAR(150);
    SET v_ubah = CONCAT_WS('; ',
        IF(NOT (OLD.name <=> NEW.name), CONCAT('nama: ', COALESCE(CAST(OLD.name AS CHAR),'-'), ' -> ', COALESCE(CAST(NEW.name AS CHAR),'-')), NULL),
        IF(NOT (OLD.category <=> NEW.category), CONCAT('kategori: ', COALESCE(CAST(OLD.category AS CHAR),'-'), ' -> ', COALESCE(CAST(NEW.category AS CHAR),'-')), NULL),
        IF(NOT (OLD.description <=> NEW.description), 'deskripsi diubah', NULL),
        IF(NOT (OLD.price <=> NEW.price), CONCAT('harga: ', COALESCE(CAST(OLD.price AS CHAR),'-'), ' -> ', COALESCE(CAST(NEW.price AS CHAR),'-')), NULL),
        IF(NOT (OLD.variation <=> NEW.variation), CONCAT('variasi: ', COALESCE(CAST(OLD.variation AS CHAR),'-'), ' -> ', COALESCE(CAST(NEW.variation AS CHAR),'-')), NULL),
        IF(NOT (OLD.stock_status <=> NEW.stock_status), CONCAT('stok: ', COALESCE(CAST(OLD.stock_status AS CHAR),'-'), ' -> ', COALESCE(CAST(NEW.stock_status AS CHAR),'-')), NULL),
        IF(NOT (OLD.image <=> NEW.image), 'gambar diganti', NULL)
    );
    IF v_ubah IS NOT NULL AND v_ubah <> '' THEN
        SET v_umkm = (SELECT name FROM umkm WHERE id = NEW.umkm_id);
        CALL sp_catat_aktivitas(@admin_id, 'Edit Produk',
            CONCAT(CONCAT('Mengubah produk: ', NEW.name, ' pada UMKM ', COALESCE(v_umkm, '-')), ' | ', v_ubah));
    END IF;
END$$

CREATE TRIGGER `trg_products_ad` AFTER DELETE ON `products` FOR EACH ROW
BEGIN
    DECLARE v_umkm VARCHAR(150);
    SET v_umkm = (SELECT name FROM umkm WHERE id = OLD.umkm_id);
    CALL sp_catat_aktivitas(@admin_id, 'Hapus Produk',
        CONCAT('Menghapus produk: ', OLD.name, ' pada UMKM ', COALESCE(v_umkm, '-')));
END$$

CREATE TRIGGER `trg_gallery_ai` AFTER INSERT ON `gallery` FOR EACH ROW
BEGIN
    DECLARE v_umkm VARCHAR(150);
    SET v_umkm = (SELECT name FROM umkm WHERE id = NEW.umkm_id);
    CALL sp_catat_aktivitas(@admin_id, 'Tambah Galeri',
        CONCAT('Menambahkan foto galeri ', COALESCE(NEW.title, CONCAT('ID ', NEW.id)), ' untuk UMKM ', COALESCE(v_umkm, '-')));
END$$

CREATE TRIGGER `trg_gallery_au` AFTER UPDATE ON `gallery` FOR EACH ROW
BEGIN
    DECLARE v_ubah TEXT;
    DECLARE v_umkm VARCHAR(150);
    SET v_ubah = CONCAT_WS('; ',
        IF(NOT (OLD.title <=> NEW.title), CONCAT('judul: ', COALESCE(CAST(OLD.title AS CHAR),'-'), ' -> ', COALESCE(CAST(NEW.title AS CHAR),'-')), NULL),
        IF(NOT (OLD.category <=> NEW.category), CONCAT('kategori: ', COALESCE(CAST(OLD.category AS CHAR),'-'), ' -> ', COALESCE(CAST(NEW.category AS CHAR),'-')), NULL),
        IF(NOT (OLD.image <=> NEW.image), 'gambar diganti', NULL)
    );
    IF v_ubah IS NOT NULL AND v_ubah <> '' THEN
        SET v_umkm = (SELECT name FROM umkm WHERE id = NEW.umkm_id);
        CALL sp_catat_aktivitas(@admin_id, 'Edit Galeri',
            CONCAT(CONCAT('Mengubah foto galeri ', COALESCE(NEW.title, CONCAT('ID ', NEW.id)), ' pada UMKM ', COALESCE(v_umkm, '-')), ' | ', v_ubah));
    END IF;
END$$

CREATE TRIGGER `trg_gallery_ad` AFTER DELETE ON `gallery` FOR EACH ROW
BEGIN
    DECLARE v_umkm VARCHAR(150);
    SET v_umkm = (SELECT name FROM umkm WHERE id = OLD.umkm_id);
    CALL sp_catat_aktivitas(@admin_id, 'Hapus Galeri',
        CONCAT('Menghapus foto galeri ', COALESCE(OLD.title, CONCAT('ID ', OLD.id)), ' dari UMKM ', COALESCE(v_umkm, '-')));
END$$

CREATE TRIGGER `trg_testimonials_ai` AFTER INSERT ON `testimonials` FOR EACH ROW
BEGIN
    DECLARE v_umkm VARCHAR(150);
    SET v_umkm = (SELECT name FROM umkm WHERE id = NEW.umkm_id);
    CALL sp_catat_aktivitas(@admin_id, 'Tambah Testimoni',
        CONCAT('Menambahkan testimoni dari: ', NEW.name, ' pada UMKM ', COALESCE(v_umkm, '-')));
END$$

CREATE TRIGGER `trg_testimonials_au` AFTER UPDATE ON `testimonials` FOR EACH ROW
BEGIN
    DECLARE v_ubah TEXT;
    DECLARE v_umkm VARCHAR(150);
    SET v_ubah = CONCAT_WS('; ',
        IF(NOT (OLD.name <=> NEW.name), CONCAT('nama: ', COALESCE(CAST(OLD.name AS CHAR),'-'), ' -> ', COALESCE(CAST(NEW.name AS CHAR),'-')), NULL),
        IF(NOT (OLD.rating <=> NEW.rating), CONCAT('rating: ', COALESCE(CAST(OLD.rating AS CHAR),'-'), ' -> ', COALESCE(CAST(NEW.rating AS CHAR),'-')), NULL),
        IF(NOT (OLD.message <=> NEW.message), 'pesan diubah', NULL),
        IF(NOT (OLD.status <=> NEW.status), CONCAT('status: ', COALESCE(CAST(OLD.status AS CHAR),'-'), ' -> ', COALESCE(CAST(NEW.status AS CHAR),'-')), NULL)
    );
    IF v_ubah IS NOT NULL AND v_ubah <> '' THEN
        SET v_umkm = (SELECT name FROM umkm WHERE id = NEW.umkm_id);
        CALL sp_catat_aktivitas(@admin_id, 'Edit Testimoni',
            CONCAT(CONCAT('Mengubah testimoni dari: ', NEW.name, ' pada UMKM ', COALESCE(v_umkm, '-')), ' | ', v_ubah));
    END IF;
END$$

CREATE TRIGGER `trg_testimonials_ad` AFTER DELETE ON `testimonials` FOR EACH ROW
BEGIN
    DECLARE v_umkm VARCHAR(150);
    SET v_umkm = (SELECT name FROM umkm WHERE id = OLD.umkm_id);
    CALL sp_catat_aktivitas(@admin_id, 'Hapus Testimoni',
        CONCAT('Menghapus testimoni dari: ', OLD.name, ' pada UMKM ', COALESCE(v_umkm, '-')));
END$$

CREATE TRIGGER `trg_admins_ai` AFTER INSERT ON `admins` FOR EACH ROW
BEGIN
    CALL sp_catat_aktivitas(COALESCE(@admin_id, NEW.id), 'Tambah Admin',
        CONCAT('Menambahkan admin: ', CONCAT(NEW.name, ' (', NEW.username, ')')));
END$$

CREATE TRIGGER `trg_admins_au` AFTER UPDATE ON `admins` FOR EACH ROW
BEGIN
    DECLARE v_ubah TEXT;
    SET v_ubah = CONCAT_WS('; ',
        IF(NOT (OLD.name <=> NEW.name), CONCAT('nama: ', COALESCE(CAST(OLD.name AS CHAR),'-'), ' -> ', COALESCE(CAST(NEW.name AS CHAR),'-')), NULL),
        IF(NOT (OLD.username <=> NEW.username), CONCAT('username: ', COALESCE(CAST(OLD.username AS CHAR),'-'), ' -> ', COALESCE(CAST(NEW.username AS CHAR),'-')), NULL),
        IF(NOT (OLD.password <=> NEW.password), 'password diubah', NULL)
    );
    IF v_ubah IS NOT NULL AND v_ubah <> '' THEN
        CALL sp_catat_aktivitas(COALESCE(@admin_id, NEW.id), 'Edit Admin',
            CONCAT(CONCAT('Mengubah admin: ', CONCAT(NEW.name, ' (', NEW.username, ')')), ' | ', v_ubah));
    END IF;
END$$

CREATE TRIGGER `trg_admins_ad` AFTER DELETE ON `admins` FOR EACH ROW
BEGIN
    CALL sp_catat_aktivitas(@admin_id, 'Hapus Admin',
        CONCAT('Menghapus admin: ', CONCAT(OLD.name, ' (', OLD.username, ')')));
END$$
DELIMITER ;

-- =====================================================================
-- CATATAN
-- * Penghapusan otomatis lewat foreign key (hapus UMKM -> produk, galeri,
--   testimoni ikut terhapus) TIDAK memicu trigger MySQL, jadi
--   yang tercatat hanya "Hapus UMKM".
-- * Edit hanya dicatat kalau ada kolom yang benar-benar berubah.
-- * Password tidak pernah ditulis ke log, hanya "password diubah".
-- =====================================================================
