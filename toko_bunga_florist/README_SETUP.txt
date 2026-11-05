SETUP TOKOBUNGA FLORIST - MVC ADMIN + 4 TEMPLATE

1. Extract folder Toko_Bunga_Floristy ke C:\xampp\htdocs\.
2. Jalankan Apache dan MySQL di XAMPP.
3. Buka phpMyAdmin, lalu import database/database.sql.
4. Cek app/config/database.php. Default XAMPP: user root, password kosong, database toko_bunga_florist.
5. Buka http://localhost/Toko_Bunga_Florist/admin/login
6. Login demo: username admin, password admin123.

ALUR UTAMA
Login Admin -> Dashboard -> Tambah UMKM -> isi profil + pilih template -> Simpan.
Dashboard menyediakan Kelola, Edit, Preview, dan Delete untuk tiap UMKM.

EMPAT TEMPLATE TERPUSAT
Semua template berada di app/templates/ dan CSS-nya terpisah di public/assets/css/:
- template1.php + template1.css: Elegan Dark
- template2.php + template2.css: Rosa Garden
- template3.php + template3.css: Minimal Cream
- template4.php + template4.css: Fresh Green

CARA MENYATUKAN KERJA 4 ANGGOTA
Setiap anggota mengerjakan satu pasangan file templateN.php dan templateN.css.
Satukan file PHP ke app/templates/ dan CSS ke public/assets/css/. Jangan menimpa file milik anggota lain; gunakan nomor 1-4 yang unik. Jika template memerlukan gambar/JS khusus, simpan dengan nama/folder khusus di public/assets/ agar tidak bentrok.
Semua template menerima data UMKM yang sama ($umkm, $products, $testimonials, $gallery), sehingga tidak perlu membuat empat project admin/database terpisah. Pemilihan desain dilakukan dari kolom template pada tabel umkm.

CATATAN
- Folder rosa-garden lama yang duplikat di app/ dan public/ dihapus dari paket rapih; versi template Rosa Garden yang dipakai sistem adalah app/templates/template2.php dan public/assets/css/template2.css.
- Blog publik masih placeholder.
- Untuk produksi, ubah password admin dan perketat validasi upload.


INTEGRASI TEMPLATE SONI
- Desain Soni diintegrasikan ke app/templates/template3.php.
- CSS desain Soni: public/assets/css/template3.css
- JavaScript desain Soni: public/assets/js/template3.js
- Pilihan template admin sekarang bernama Template Vina, Template Tanisa, Template Soni, dan Template Zaki.
- Template Soni menggunakan data UMKM, produk, galeri, dan testimoni dari database project utama.
- Setelah ekstrak, jalankan melalui XAMPP dan uji di admin: edit/tambah UMKM, pilih Template Soni, simpan, lalu buka URL /umkm/slug-umkm.


TRIGGER LOG AKTIVITAS (DATABASE)
- Setelah import database/database.sql, jalankan database/triggers.sql
  (phpMyAdmin: pilih database toko_bunga_florist -> tab SQL, atau
   mysql -u root toko_bunga_florist < database/triggers.sql).
- Tambah/edit/hapus data UMKM, produk, galeri, testimoni, dan admin
  otomatis tercatat di tabel activity_logs oleh trigger MySQL.
- app/config/database.php mengirim @admin_id dan @ip_address ke MySQL
  agar log mencatat siapa pelakunya. Login/Logout tetap dicatat oleh PHP.
