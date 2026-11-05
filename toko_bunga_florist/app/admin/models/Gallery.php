<?php
class Gallery {
    public static function byUmkm(int $umkmId): array { $st=db()->prepare('SELECT * FROM gallery WHERE umkm_id=? ORDER BY id DESC'); $st->execute([$umkmId]); return $st->fetchAll(); }
    public static function create(array $d): void { $st=db()->prepare('INSERT INTO gallery (umkm_id,title,category,image,created_at) VALUES (?,?,?,?,NOW())'); $st->execute([$d['umkm_id'],$d['title'],$d['category'],$d['image']]); }
    public static function delete(int $id): void { $st=db()->prepare('DELETE FROM gallery WHERE id=?'); $st->execute([$id]); }
}
