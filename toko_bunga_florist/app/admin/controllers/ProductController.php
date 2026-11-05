<?php
class ProductController extends Controller {
    public function create(int $umkmId,string $method): void {
        $umkm=Umkm::find($umkmId); if(!$umkm){http_response_code(404);exit('UMKM tidak ditemukan');}
        if($method==='POST'){
            $image=upload_image('image','products');
            Product::create(['umkm_id'=>$umkmId,'name'=>trim($_POST['name']??''),'category'=>trim($_POST['category']??''),'description'=>trim($_POST['description']??''),'price'=>(float)($_POST['price']??0),'variation'=>trim($_POST['variation']??''),'stock_status'=>$_POST['stock_status']??'Ready','image'=>$image]);

            $this->redirect('admin/umkm/'.$umkmId.'/manage');
        }
        $this->view('admin/views/products/form',['product'=>null,'umkm'=>$umkm,'title'=>'Tambah Produk']);
    }
    public function edit(int $id,string $method): void {
        $product=Product::find($id); if(!$product){http_response_code(404);exit('Produk tidak ditemukan');}
        $umkm=Umkm::find((int)$product['umkm_id']);
        if($method==='POST'){
            $image=$product['image']; $new=upload_image('image','products'); if($new)$image=$new;
            Product::update($id,['name'=>trim($_POST['name']??''),'category'=>trim($_POST['category']??''),'description'=>trim($_POST['description']??''),'price'=>(float)($_POST['price']??0),'variation'=>trim($_POST['variation']??''),'stock_status'=>$_POST['stock_status']??'Ready','image'=>$image]);

            $this->redirect('admin/umkm/'.$product['umkm_id'].'/manage');
        }
        $this->view('admin/views/products/form',compact('product','umkm')+['title'=>'Edit Produk']);
    }
    public function delete(int $id): void { $p=Product::find($id); if(!$p){ $this->redirect('admin/dashboard'); } Product::delete($id);$this->redirect('admin/umkm/'.$p['umkm_id'].'/manage'); }
}
