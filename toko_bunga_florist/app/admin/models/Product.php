<?php
class Product {
    public static function byUmkm(int $umkmId): array { $st=db()->prepare('SELECT * FROM products WHERE umkm_id=? ORDER BY id DESC'); $st->execute([$umkmId]); return $st->fetchAll(); }
    public static function find(int $id): ?array { $st=db()->prepare('SELECT * FROM products WHERE id=?'); $st->execute([$id]); return $st->fetch() ?: null; }
    public static function create(array $d): void { $st=db()->prepare('INSERT INTO products (umkm_id,name,category,description,price,variation,stock_status,image,created_at) VALUES (?,?,?,?,?,?,?,?,NOW())'); $st->execute([$d['umkm_id'],$d['name'],$d['category'],$d['description'],$d['price'],$d['variation'],$d['stock_status'],$d['image']]); }
    public static function update(int $id,array $d): void { $st=db()->prepare('UPDATE products SET name=?,category=?,description=?,price=?,variation=?,stock_status=?,image=? WHERE id=?'); $st->execute([$d['name'],$d['category'],$d['description'],$d['price'],$d['variation'],$d['stock_status'],$d['image'],$id]); }
    public static function delete(int $id): void { $st=db()->prepare('DELETE FROM products WHERE id=?'); $st->execute([$id]); }
}
