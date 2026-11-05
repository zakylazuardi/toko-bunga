<?php
class TestimonialController extends Controller {
    public function create(int $umkmId,string $method): void {
        $umkm=Umkm::find($umkmId); if(!$umkm){http_response_code(404);exit('UMKM tidak ditemukan');}
        if($method==='POST'){
            Testimonial::create(['umkm_id'=>$umkmId,'name'=>trim($_POST['name']??''),'rating'=>(int)($_POST['rating']??5),'message'=>trim($_POST['message']??''),'status'=>'Published']);

            $this->redirect('admin/umkm/'.$umkmId.'/manage');
        }
        $this->view('admin/views/testimonials/form',compact('umkm'));
    }
    public function delete(int $id): void { $rows=db()->prepare('SELECT umkm_id FROM testimonials WHERE id=?'); $rows->execute([$id]); $row=$rows->fetch(); if(!$row){$this->redirect('admin/dashboard');} Testimonial::delete($id);$this->redirect('admin/umkm/'.$row['umkm_id'].'/manage'); }
}
