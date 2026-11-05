<?php
$escape = static fn($value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$previewMode = $previewMode ?? false;
$storeName = $escape($umkm['name'] ?? 'Florista');
$description = $escape($umkm['description'] ?? 'Bunga segar, indah, dan berkualitas untuk orang tersayang.');
$phone = preg_replace('/\D+/', '', (string) ($umkm['phone'] ?? ''));
$slug = (string) ($umkm['slug'] ?? '');
$whatsappUrl = $phone !== ''
    ? 'https://wa.me/' . $phone . '?text=' . rawurlencode('Halo ' . ($umkm['name'] ?? 'Florista') . ', saya ingin memesan bunga.')
    : '#kontak';
$publicBase = rtrim(base_url('public'), '/');
$storeImageUrl = static function ($path) use ($publicBase, $escape): string {
    $path = trim((string) $path);
    if ($path === '') {
        return 'https://images.unsplash.com/photo-1525310072745-f49212b5ac6d?auto=format&fit=crop&w=900&q=80';
    }
    if (filter_var($path, FILTER_VALIDATE_URL) && in_array(parse_url($path, PHP_URL_SCHEME), ['http', 'https'], true)) {
        return $escape($path);
    }
    return $escape($publicBase . '/' . ltrim($path, '/'));
};
$heroImage = $storeImageUrl($umkm['hero_image'] ?? '');
$aboutImage = 'https://images.unsplash.com/photo-1487530811176-3780de880c2d?auto=format&fit=crop&w=900&q=80';
$stylePath = __DIR__ . '/../../public/assets/css/template3.css';
$styleVersion = is_file($stylePath) ? (string) filemtime($stylePath) : '1';
$catalog = [
    ['Buket Bunga', 'Rangkaian cantik untuk mengungkapkan rasa.', 'https://images.unsplash.com/photo-1525310072745-f49212b5ac6d?auto=format&fit=crop&w=700&q=80'],
    ['Bunga Meja', 'Sentuhan segar untuk ruang penuh cerita.', 'https://images.unsplash.com/photo-1490750967868-88aa4486c946?auto=format&fit=crop&w=700&q=80'],
    ['Hampers', 'Hadiah istimewa yang dirangkai dengan hati.', 'https://images.unsplash.com/photo-1526045478516-99145907023c?auto=format&fit=crop&w=700&q=80'],
    ['Dekorasi', 'Hadirkan suasana indah di hari spesial Anda.', 'https://images.unsplash.com/photo-1507504031003-b417219a0fde?auto=format&fit=crop&w=700&q=80'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= $description ?>">
    <title><?= $storeName ?> · Bunga untuk Setiap Momen</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $escape(base_url('public/assets/css/template3.css')) ?>?v=<?= $styleVersion ?>">
</head>
<body class="flower-store theme-pastel">
    <?php if ($previewMode): ?>
        <aside class="demo-banner"><span><strong>Pratinjau Template Soni</strong> · Ini tampilan pratinjau website UMKM.</span><a href="<?= $escape(base_url('admin/dashboard')) ?>">Kembali ke dashboard ↗</a></aside>
    <?php endif; ?>
    <header class="store-header">
        <a class="store-brand" href="#home"><span class="brand-flower">✿</span><span><?= $storeName ?><small>FLOWER STUDIO</small></span></a>
        <nav class="store-nav"><a href="#home">Beranda</a><a href="#produk">Produk</a><a href="#tentang">Tentang Kami</a><a href="#galeri">Galeri</a><a href="#testimoni">Testimoni</a><a href="#kontak">Kontak</a></nav>
        <?php if ($previewMode): ?>
            <a class="button button-primary store-nav-cta" href="#kontak">Pratinjau ↗</a>
        <?php elseif (!empty($products[0]['id'])): ?>
            <a class="button button-primary store-nav-cta" href="<?= $escape(base_url('umkm/' . rawurlencode($slug) . '/product/' . (int)$products[0]['id'])) ?>">Pesan Produk ↗</a>
        <?php elseif ($phone !== ''): ?>
            <a class="button button-primary store-nav-cta" href="<?= $escape($whatsappUrl) ?>" target="_blank" rel="noopener">Hubungi Kami ↗</a>
        <?php else: ?>
            <a class="button button-primary store-nav-cta" href="#kontak">Lihat Kontak</a>
        <?php endif; ?>
    </header>
    <main>
        <section class="store-hero" id="home">
            <div class="store-hero-copy"><span class="eyebrow">RANGKAIAN BUNGA DENGAN CERITA</span><h1><?= $escape($umkm['hero_title'] ?? 'Merangkai Bunga untuk Setiap Momen Spesial') ?></h1><p><?= $description ?></p><div class="landing-actions"><a class="button button-primary" href="#produk">Lihat Katalog <span>→</span></a><?php if ($phone !== ''): ?><a class="button button-light" href="<?= $escape($whatsappUrl) ?>" target="_blank" rel="noopener">Hubungi Kami</a><?php else: ?><a class="button button-light" href="#kontak">Lihat Kontak</a><?php endif; ?></div></div>
            <div class="store-hero-image"><img src="<?= $escape($heroImage) ?>" alt="Rangkaian bunga dari <?= $storeName ?>"><span class="store-image-caption">HANDCRAFTED WITH LOVE</span></div>
        </section>
        <section class="store-values"><article><span>✿</span><strong>Bunga Segar Berkualitas</strong></article><article><span>♡</span><strong>Dirangkai dengan Hati</strong></article><article><span>◷</span><strong>Untuk Momen Spesial</strong></article><article><span>✧</span><strong>Pelayanan Ramah</strong></article></section>
        <section class="store-products" id="produk"><div class="section-title"><div><span class="eyebrow">PILIHAN DARI KAMI</span><h2>Rangkaian untuk setiap cerita.</h2></div><p>Temukan bunga yang tepat untuk disampaikan dengan sepenuh hati.</p></div><div class="store-product-grid">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <article class="store-product-card"><div class="store-product-image"><img src="<?= $storeImageUrl($product['image'] ?? '') ?>" alt="<?= $escape($product['name'] ?? 'Produk bunga') ?>"></div><div class="store-product-copy"><span><?= $escape($product['category'] ?? 'RANGKAIAN BUNGA') ?></span><h3><?= $escape($product['name'] ?? '') ?></h3><?php if (!empty($product['description'])): ?><p><?= $escape($product['description']) ?></p><?php endif; ?><strong>Rp <?= number_format((float)($product['price'] ?? 0), 0, ',', '.') ?></strong>
                    <?php if ($previewMode): ?><span class="demo-order-note">Pratinjau produk</span><?php elseif (!empty($product['id'])): ?><a href="<?= $escape(base_url('umkm/' . rawurlencode($slug) . '/product/' . (int)$product['id'])) ?>">Lihat detail & pesan →</a><?php elseif ($phone !== ''): ?><a href="<?= $escape($whatsappUrl) ?>" target="_blank" rel="noopener">Tanya ketersediaan →</a><?php endif; ?></div></article>
                <?php endforeach; ?>
            <?php else: ?>
                <?php foreach ($catalog as [$title, $copy, $image]): ?><article class="store-product-card"><div class="store-product-image"><img src="<?= $escape($image) ?>" alt="<?= $escape($title) ?>"></div><div class="store-product-copy"><span>KOLEKSI BUNGA</span><h3><?= $escape($title) ?></h3><p><?= $escape($copy) ?></p><span class="demo-order-note">Tambahkan produk melalui dashboard admin</span></div></article><?php endforeach; ?>
            <?php endif; ?>
        </div></section>
        <section class="store-about" id="tentang"><div class="store-about-image"><img src="<?= $escape($aboutImage) ?>" alt="Florist sedang merangkai bunga"></div><div class="store-about-copy"><span class="eyebrow">CERITA KAMI</span><h2>Keindahan kecil, dirangkai dengan sepenuh hati.</h2><p><?= $description ?></p><p><?= $escape($umkm['address'] ?? 'Kami siap membantu Anda memilih rangkaian terbaik.') ?></p><?php if ($phone !== ''): ?><a class="button button-secondary" href="<?= $escape($whatsappUrl) ?>" target="_blank" rel="noopener">Kenali Kami</a><?php endif; ?></div></section>
        <section class="store-gallery" id="galeri"><div class="section-title"><div><span class="eyebrow">INSPIRASI RANGKAIAN</span><h2>Momen indah, dalam kelopak bunga.</h2></div></div><div class="gallery-strip"><?php if (!empty($gallery)): ?><?php foreach ($gallery as $item): ?><img src="<?= $storeImageUrl($item['image'] ?? '') ?>" alt="<?= $escape($item['title'] ?? 'Galeri bunga') ?>"><?php endforeach; ?><?php else: ?><img src="https://images.unsplash.com/photo-1490750967868-88aa4486c946?auto=format&fit=crop&w=700&q=80" alt="Rangkaian mawar"><img src="https://images.unsplash.com/photo-1508610048659-a06b669e3321?auto=format&fit=crop&w=700&q=80" alt="Bouquet cantik"><img src="https://images.unsplash.com/photo-1526045478516-99145907023c?auto=format&fit=crop&w=700&q=80" alt="Bunga pastel"><img src="https://images.unsplash.com/photo-1507504031003-b417219a0fde?auto=format&fit=crop&w=700&q=80" alt="Bunga dekorasi"><?php endif; ?></div></section>
        <?php $publishedTestimonials = array_values(array_filter($testimonials ?? [], static fn($t) => ($t['status'] ?? 'Published') === 'Published')); ?>
        <?php if (!empty($publishedTestimonials)): ?><section class="store-testimonials" id="testimoni"><div class="section-title"><div><span class="eyebrow">KATA PELANGGAN</span><h2>Terima kasih sudah mempercayakan momen Anda.</h2></div></div><div class="testimonial-grid"><?php foreach ($publishedTestimonials as $testimonial): ?><article class="testimonial-card"><span class="testimonial-stars"><?= str_repeat('★', max(1, min(5, (int)($testimonial['rating'] ?? 5)))) ?></span><p>“<?= $escape($testimonial['message'] ?? '') ?>”</p><strong><?= $escape($testimonial['name'] ?? 'Pelanggan') ?></strong></article><?php endforeach; ?></div></section><?php endif; ?>
        <section class="store-contact" id="kontak"><div><span class="eyebrow">ADA YANG INGIN DITANYAKAN?</span><h2>Mari rangkai momen indah Anda.</h2><p><?= $escape($umkm['address'] ?? '') ?><?php if ($phone !== ''): ?> · <?= $escape($umkm['phone']) ?><?php endif; ?></p><?php if (!empty($umkm['hours'])): ?><p>Jam operasional: <?= $escape($umkm['hours']) ?></p><?php endif; ?></div><?php if ($phone !== ''): ?><a class="button button-white" href="<?= $escape($whatsappUrl) ?>" target="_blank" rel="noopener">Hubungi via WhatsApp ↗</a><?php else: ?><span class="button button-white">Informasi kontak belum tersedia</span><?php endif; ?></section>
    </main>
    <footer class="store-footer"><a class="store-brand" href="#home"><span class="brand-flower">✿</span><?= $storeName ?></a><span>© <?= date('Y') ?> <?= $storeName ?>. Dirangkai dengan sepenuh hati.</span><a href="<?= $escape(base_url()) ?>">Website Builder Florist ✿</a></footer>
    <?php if (!$previewMode && $phone !== ''): ?><a class="whatsapp-float" href="<?= $escape($whatsappUrl) ?>" target="_blank" rel="noopener" aria-label="Hubungi melalui WhatsApp">◉</a><?php endif; ?>
    <script src="<?= $escape(base_url('public/assets/js/template3.js')) ?>"></script>
</body>
</html>
