<?php
class NewsController extends Controller
{
    private $pagination;

    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->model = new Article();
    }

    public function index()
    {
        $this->data['sections'] = $this->model->getSections();
    }

    public function section()
    {
        $params = App::getRouter()->getParams();
        if (isset($params[0])){
            $alias = strtolower($params[0]);
            $pageUrl = ($params[1])? $params[1]: "";
            $this->pagination = new Pagination($this->model->getSectionListCount($alias), $pageUrl);
            $this->data['items'] = $this->model->getSectionList($alias, $this->pagination->getSqlLimit());
            $this->data['pagination'] = (array)$this->pagination;
        } else {
            Router::redirect('/news/');
        }
    }    

    public function detail()
    {
        $params = App::getRouter()->getParams();

        if (isset($params[0])){
            $alias = strtolower($params[0]);
            $news = $this->model->getByAlias($alias);
            if ($news['tags']){
                $news['tags'] = explode(',', $news['tags']);
            }
            $this->data['item'] = $news;
            // save to readed
            $this->model->updateReaded($this->data['item']['id'], $this->data['item']['readed']);
        } else {
            Router::redirect('/news/');
        }
    }

    public function search()
    {
        $params = App::getRouter()->getParams();
pr($params);
        // if (isset($params[0])){
        //     $alias = strtolower($params[0]);
        //     $news = $this->model->getByAlias($alias);
        //     if ($news['tags']){
        //         $news['tags'] = explode(',', $news['tags']);
        //     }
        //     $this->data['item'] = $news;
        //     // save to readed
        //     $this->model->updateReaded($this->data['item']['id'], $this->data['item']['readed']);
        // } else {
        //     Router::redirect('/news/');
        // }
    }    

/*
    public function admin_index()
    {
        $this->data['pages'] = $this->model->getList();
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
            Router::redirect('/admin/pages/');
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
            Router::redirect('/admin/pages/');
        }

        if (isset($this->params[0])){
            $this->data['page'] = $this->model->getById($this->params[0]);
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
        Router::redirect('/admin/pages/');
    }*/
}