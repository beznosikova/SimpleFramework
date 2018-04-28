<?php
class MenuController extends Controller
{
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->model = new Nav();
    }

    public function admin_index()
    {
        if ($_POST){
            $id = isset($_POST["id"]) ? $_POST["id"] : null;
            $result = $this->model->save($_POST, $id);
            if ($result){
                Session::setFlash("Page was saved.");
            } else {
                Session::setFlash("Error.");
            }
            Router::redirect('/admin/menu/');
        }
        $this->data['items'] = $this->model->getList();
        $this->data['sections'] = array_column($this->data['items'], 'title', 'id');
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
            Router::redirect('/admin/menu/');
        }
        
        if (isset($this->params[0])){
            $this->data['item'] = $this->model->getById($this->params[0]);
            $this->data['sections'] = array_column($this->model->getList(), 'title', 'id');
        } else {
            Session::setFlash('Wrong page id.');
            Router::redirect('/admin/pages/');
        }
    }    

    public function admin_delete()
    {
        if ( isset($this->params[0]) ){
            $result = $this->model->delete($this->params[0]);
            if ($result){
                Session::setFlash("Page was saved.");
            } else {
                Session::setFlash("Error.");
            }
        }
        Router::redirect('/admin/menu/');
    }
}