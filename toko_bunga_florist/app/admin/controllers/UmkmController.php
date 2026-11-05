<?php
class UmkmController extends Controller {
    private array $templates = [
        'template1' => 'Template Vina',
        'template2' => 'Template Tanisa',
        'template3' => 'Template Soni',
        'template4' => 'Template Zaki',
    ];
    public function create(string $method): void {
        if ($method === 'POST') {
            $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i','-',$_POST['slug'] ?: $_POST['name'])));
            $slug = trim($slug,'-');
            $image = upload_image('hero_image','umkm');
            Umkm::create([
                'name'=>trim($_POST['name']??''),'slug'=>$slug,'template'=>$_POST['template']??'template1','description'=>trim($_POST['description']??''),'address'=>trim($_POST['address']??''),'phone'=>trim($_POST['phone']??''),'email'=>trim($_POST['email']??''),'hours'=>trim($_POST['hours']??''),'hero_title'=>trim($_POST['hero_title']??''),'hero_text'=>trim($_POST['hero_text']??''),'hero_image'=>$image,'status'=>$_POST['status']??'active'
            ]);

            $this->redirect('admin/dashboard');
        }
        $templates=$this->templates;
        $this->view('admin/views/umkm/form', ['umkm'=>null,'templates'=>$templates,'title'=>'Tambah UMKM']);
    }
    public function edit(int $id,string $method): void {
        $umkm=Umkm::find($id); if(!$umkm){http_response_code(404);exit('UMKM tidak ditemukan');}
        if($method==='POST'){
            $image=$umkm['hero_image']; $new=upload_image('hero_image','umkm'); if($new)$image=$new;
            $slug=strtolower(trim(preg_replace('/[^a-z0-9]+/i','-',$_POST['slug'] ?: $_POST['name']))); $slug=trim($slug,'-');
            Umkm::update($id,['name'=>trim($_POST['name']??''),'slug'=>$slug,'template'=>$_POST['template']??'template1','description'=>trim($_POST['description']??''),'address'=>trim($_POST['address']??''),'phone'=>trim($_POST['phone']??''),'email'=>trim($_POST['email']??''),'hours'=>trim($_POST['hours']??''),'hero_title'=>trim($_POST['hero_title']??''),'hero_text'=>trim($_POST['hero_text']??''),'hero_image'=>$image,'status'=>$_POST['status']??'active']);

            $this->redirect('admin/umkm/'.$id.'/manage');
        }
        $templates=$this->templates; $this->view('admin/views/umkm/form',compact('umkm','templates'));
    }
    public function delete(int $id): void { $umkm = Umkm::find($id); Umkm::delete($id);$this->redirect('admin/dashboard'); }
    public function manage(int $id): void {
        $umkm=Umkm::find($id); if(!$umkm){http_response_code(404);exit('UMKM tidak ditemukan');}
        $products=Product::byUmkm($id); $testimonials=Testimonial::byUmkm($id); $gallery=Gallery::byUmkm($id);
        $this->view('admin/views/umkm/manage',compact('umkm','products','testimonials','gallery'));
    }
    public function preview(int $id): void {
        $umkm=Umkm::find($id); if(!$umkm){http_response_code(404);exit('UMKM tidak ditemukan');}
        (new FloristController())->render($umkm,true);
    }
}
