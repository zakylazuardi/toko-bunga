<?php
class FloristController extends Controller {
    public function home(?string $slug=null): void {
        if(!$slug){
            $umkm=Umkm::all(); $this->view('views/home/index',compact('umkm')); return;
        }
        $umkm=Umkm::findBySlug($slug); if(!$umkm){http_response_code(404);exit('Website UMKM tidak ditemukan.');}
        $this->render($umkm,false);
    }
    public function render(array $umkm,bool $preview=false): void {
        $products=Product::byUmkm((int)$umkm['id']); $testimonials=Testimonial::byUmkm((int)$umkm['id']); $gallery=Gallery::byUmkm((int)$umkm['id']);
        $template=$umkm['template'];
        $file=__DIR__.'/../templates/'.$template.'.php';
        if(!is_file($file)) $file=__DIR__.'/../templates/template2.php';
        require $file;
    }
    public function products(string $slug): void { $umkm=Umkm::findBySlug($slug); if(!$umkm)exit('404'); $products=Product::byUmkm($umkm['id']); $this->view('views/public/products',compact('umkm','products')); }
    public function product(string $slug,int $id): void { $umkm=Umkm::findBySlug($slug); $product=Product::find($id); if(!$umkm||!$product||$product['umkm_id']!=$umkm['id'])exit('404'); $this->view('views/public/product',compact('umkm','product')); }
    public function testimonials(string $slug): void { $umkm=Umkm::findBySlug($slug); if(!$umkm)exit('404'); $testimonials=Testimonial::byUmkm($umkm['id']); $this->view('views/public/testimonials',compact('umkm','testimonials')); }
    public function gallery(string $slug): void { $umkm=Umkm::findBySlug($slug); if(!$umkm)exit('404'); $gallery=Gallery::byUmkm($umkm['id']); $this->view('views/public/gallery',compact('umkm','gallery')); }
    public function blog(string $slug): void { $umkm=Umkm::findBySlug($slug); if(!$umkm)exit('404'); $this->view('views/public/blog',compact('umkm')); }
}
