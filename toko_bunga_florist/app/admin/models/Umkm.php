<?php

class Umkm
{
    public static function all(): array
    {
        return db()->query('SELECT * FROM umkm ORDER BY id DESC')->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $st = db()->prepare('SELECT * FROM umkm WHERE id = ?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }

    public static function findBySlug(string $slug): ?array
    {
        $st = db()->prepare('SELECT * FROM umkm WHERE slug = ?');
        $st->execute([$slug]);
        return $st->fetch() ?: null;
    }

    public static function create(array $d): int
    {
        $st = db()->prepare(
            'INSERT INTO umkm
            (name, slug, template, description, address, phone, email, hours,
             hero_title, hero_text, hero_image, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())'
        );
        $st->execute([
            $d['name'], $d['slug'], $d['template'] ?? 'template1',
            $d['description'] ?? '', $d['address'] ?? '', $d['phone'] ?? '',
            $d['email'] ?? '', $d['hours'] ?? '', $d['hero_title'] ?? '',
            $d['hero_text'] ?? '', $d['hero_image'] ?? null, $d['status'] ?? 'active'
        ]);
        return (int) db()->lastInsertId();
    }

    public static function update(int $id, array $d): void
    {
        $st = db()->prepare(
            'UPDATE umkm SET name=?, slug=?, template=?, description=?, address=?,
             phone=?, email=?, hours=?, hero_title=?, hero_text=?, hero_image=?, status=?
             WHERE id=?'
        );
        $st->execute([
            $d['name'], $d['slug'], $d['template'] ?? 'template1',
            $d['description'] ?? '', $d['address'] ?? '', $d['phone'] ?? '',
            $d['email'] ?? '', $d['hours'] ?? '', $d['hero_title'] ?? '',
            $d['hero_text'] ?? '', $d['hero_image'] ?? null, $d['status'] ?? 'active', $id
        ]);
    }

    public static function delete(int $id): void
    {
        $st = db()->prepare('DELETE FROM umkm WHERE id = ?');
        $st->execute([$id]);
    }
}
