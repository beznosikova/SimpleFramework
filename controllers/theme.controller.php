<?php
class ThemeController extends Controller
{
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->model = new Template();
    }

    public function admin_index()
    {
        if ($_POST){
            $result = $this->model->save($_POST);
            if ($result){
                Session::setFlash("Config was changed.");
            } else {
                Session::setFlash("Error.");
            }
        }
        $this->data['items'] = $this->model->getList();
    }
}