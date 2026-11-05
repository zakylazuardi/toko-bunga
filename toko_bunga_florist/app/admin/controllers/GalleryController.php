<?php
class GalleryController extends Controller {
    public function create(int $umkmId,string $method): void {
        $umkm=Umkm::find($umkmId); if(!$umkm){http_response_code(404);exit('UMKM tidak ditemukan');}
        if($method==='POST'){
            $image=upload_image('image','gallery');
            if($image) Gallery::create(['umkm_id'=>$umkmId,'title'=>trim($_POST['title']??''),'category'=>trim($_POST['category']??''),'image'=>$image]);
            $this->redirect('admin/umkm/'.$umkmId.'/manage');
        }
        $this->view('admin/views/gallery/form',compact('umkm'));
    }
    public function delete(int $id): void { $rows=db()->prepare('SELECT umkm_id FROM gallery WHERE id=?'); $rows->execute([$id]); $row=$rows->fetch(); if(!$row){$this->redirect('admin/dashboard');} Gallery::delete($id);$this->redirect('admin/umkm/'.$row['umkm_id'].'/manage'); }
}
