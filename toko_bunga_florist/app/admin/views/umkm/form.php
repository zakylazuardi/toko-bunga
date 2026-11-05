<?php $title=$title ?? ($umkm?'Edit UMKM':'Tambah UMKM'); require __DIR__.'/../layouts/header.php'; require __DIR__.'/../layouts/sidebar.php'; $isEdit=!empty($umkm); ?>
<main class="main"><div class="topbar"><div><h1><?=$title?></h1><p>Isi data toko lalu pilih salah satu dari 4 template website.</p></div></div>
<form class="panel form" method="post" enctype="multipart/form-data"><div class="form-grid">
<label>Nama UMKM *<input name="name" required value="<?=e($umkm['name']??'')?>"></label>
<label>Slug Website *<input name="slug" required value="<?=e($umkm['slug']??'')?>" placeholder="rosa-garden"></label>
<label>Nomor WhatsApp<input name="phone" value="<?=e($umkm['phone']??'')?>"></label>
<label>Email<input type="email" name="email" value="<?=e($umkm['email']??'')?>"></label>
<label class="full">Alamat<input name="address" value="<?=e($umkm['address']??'')?>"></label>
<label class="full">Jam Operasional<input name="hours" value="<?=e($umkm['hours']??'08.00 - 20.00')?>"></label>
<label class="full">Deskripsi Toko<textarea name="description"><?=e($umkm['description']??'')?></textarea></label>
<label>Judul Hero<input name="hero_title" value="<?=e($umkm['hero_title']??'Bunga Segar untuk Setiap Momen')?>"></label>
<label>Teks Hero<input name="hero_text" value="<?=e($umkm['hero_text']??'Dirangkai dengan penuh perhatian untuk orang-orang tersayang.')?>"></label>
<label>Foto Hero<input type="file" name="hero_image" accept="image/*"></label>
<label>Status<select name="status"><option value="active" <?=($umkm['status']??'active')==='active'?'selected':''?>>Active</option><option value="inactive" <?=($umkm['status']??'')==='inactive'?'selected':''?>>Inactive</option></select></label>
</div><h3>Pilih Template</h3><div class="templates">
<?php foreach($templates as $key=>$name): ?><label class="template-card"><input type="radio" name="template" value="<?=e($key)?>" <?=($umkm['template']??'template1')===$key?'checked':''?>><div><strong><?=e($name)?></strong><span><?=e($key)?></span></div></label><?php endforeach; ?></div>
<div class="form-actions"><a class="btn" href="<?=base_url('admin/dashboard')?>">Batal</a><button class="btn primary">Simpan UMKM</button></div></form></main>
<?php require __DIR__.'/../layouts/footer.php'; ?>
