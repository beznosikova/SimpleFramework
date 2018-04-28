<?php
class AdvertisingController extends Controller
{
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->model = new Advert();
    }

    public function admin_index()
    {
        $this->data['items'] = $this->model->getList();
    }

    public function admin_add()
    {
        if ($_POST){
            $result = $this->model->save($_POST);
            if ($result){
                Session::setFlash("Page was saved.");
            } else {
                Session::setFlash("Error.");
            }
            Router::redirect('/admin/advertising/');
        }
    }

    public function admin_edit()
    {
        if ($_POST){
            $id = isset($_POST["id"]) ? $_POST["id"] : null;
            $result = $this->model->save($_POST, $id);
            if ($result){
                Session::setFlash("Page was saved.");
            } else {
                Session::setFlash("Error.");
            }
            Router::redirect('/admin/advertising/');
        }

        if (isset($this->params[0])){
            $this->data['page'] = $this->model->getById($this->params[0]);
        } else {
            Session::setFlash('Wrong page id.');
            Router::redirect('/admin/advertising/');
        }
    }
    
    public function admin_delete()
    {
        if ( isset($this->params[0]) ){
            $result = $this->model->delete($this->params[0]);
            if ($result){
                Session::setFlash("Page was deleted.");
            } else {
                Session::setFlash("Error.");
            }
        }
        Router::redirect('/admin/advertising/');
    }
}