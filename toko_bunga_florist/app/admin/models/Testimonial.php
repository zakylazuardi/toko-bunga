<?php
class Testimonial {
    public static function byUmkm(int $umkmId): array { $st=db()->prepare('SELECT * FROM testimonials WHERE umkm_id=? ORDER BY id DESC'); $st->execute([$umkmId]); return $st->fetchAll(); }
    public static function create(array $d): void { $st=db()->prepare('INSERT INTO testimonials (umkm_id,name,rating,message,status,created_at) VALUES (?,?,?,?,?,NOW())'); $st->execute([$d['umkm_id'],$d['name'],$d['rating'],$d['message'],$d['status']]); }
    public static function delete(int $id): void { $st=db()->prepare('DELETE FROM testimonials WHERE id=?'); $st->execute([$id]); }
}
