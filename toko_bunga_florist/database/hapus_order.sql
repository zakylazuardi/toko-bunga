-- =====================================================================
-- MIGRASI: hapus fitur Order Management dari database yang SUDAH ADA
-- Jalankan sekali di phpMyAdmin (pilih database toko_bunga_florist -> SQL).
-- Database baru tidak perlu file ini (database.sql sudah tanpa tabel orders).
-- PERHATIAN: semua data pesanan di tabel orders akan ikut terhapus.
-- =====================================================================
DROP TRIGGER IF EXISTS `trg_orders_ai`;
DROP TRIGGER IF EXISTS `trg_orders_au`;
DROP TRIGGER IF EXISTS `trg_orders_ad`;
DROP TABLE IF EXISTS `orders`;
