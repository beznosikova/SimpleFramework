<?php
class NewsController extends Controller
{
    private $pagination;

    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->model = new Article();
    }

    public function template()
    {
        $this->data['items'] = $this->model->getLastNews();
        return VIEWS_PATH.DS."news".DS."template.html";
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
            $this->data['filter'] = $this->model->getFilterItems();
            $this->data['section'] = $this->model->getSectionByAlias($alias);
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
        if ($_GET){
            $pageUrl = ($params[0])? $params[0]: "";
            $this->pagination = new Pagination($this->model->getSearchListCount($_GET), $pageUrl);
            $this->data['items'] = $this->model->getSearchList($_GET, $this->pagination->getSqlLimit());
            $this->data['pagination'] = (array)$this->pagination;
        } else {
            $this->data['message'] = 'Choose data for search!';
        }
    }    

    public function ajax()
    {
        if (!empty($_GET['tags'])){
            $items = $this->model->getSearchList(['tags' => $_GET['tags']]);
            $view_object = new View($items);
            $content = $view_object->render();            
            echo $content;
        }
        die();
    }        


    public function admin_index()
    {
        $this->data['sections'] = $this->model->getSections();
    }

    public function admin_section()
    {
        $method = ($this->params[0]) ? "admin_section_".$this->params[0] : "admin_index";

        if (method_exists("NewsController", $method)){
            $this->$method();
            return VIEWS_PATH.DS."news".DS.$method.".html";

        } else {
            throw new Exception("Method ".$controller_method." of class ".$controller_class. " does not exist!");
        }        
    }    

    public function admin_section_add()
    {
        if ($_POST){
            $result = $this->model->saveSection($_POST);
            if ($result){
                Session::setFlash("Page was saved.");
            } else {
                Session::setFlash("Error.");
            }
            Router::redirect('/admin/news/section/');
        }
    }

    public function admin_section_edit()
    {
        if ($_POST){
            $id = isset($_POST["id"]) ? $_POST["id"] : null;
            $result = $this->model->saveSection($_POST, $id);
            if ($result){
                Session::setFlash("Page was saved.");
            } else {
                Session::setFlash("Error.");
            }
            Router::redirect('/admin/news/section/');
        }

        if (isset($this->params[1])){
            $this->data['section'] = $this->model->getSectionById($this->params[1]);
        } else {
            Session::setFlash('Wrong page id.');
            Router::redirect('/admin/news/section/');
        }
    }

    public function admin_section_delete()
    {
        if ( isset($this->params[1]) ){
            $result = $this->model->deleteSection($this->params[1]);
            if ($result){
                Session::setFlash("Page was saved.");
            } else {
                Session::setFlash("Error.");
            }
        }
        Router::redirect('/admin/news/section/');
    }

    public function admin_list()
    {
        if (isset($this->params[0])){
            $alias = strtolower($this->params[0]);
            $this->data['items'] = $this->model->getSectionList($alias);
            $this->data['section'] = $this->model->getSectionByAlias($alias);
        } else {
            Router::redirect('/admin/news/section/');
        }
    } 

    // methods for detail page of news
    public function admin_detail()
    {
        $method = ($this->params[0]) ? "admin_detail_".$this->params[0] : "admin_index";

        if (method_exists("NewsController", $method)){
            $this->$method();
            return VIEWS_PATH.DS."news".DS.$method.".html";

        } else {
            throw new Exception("Method ".$controller_method." of class ".$controller_class. " does not exist!");
        }   
    }       

    public function admin_detail_add()
    {
        $this->data['sections'] = $this->model->getSections();

        if ($_POST){
            $result = $this->model->saveDetail(array_merge($_POST, $_FILES));
            if ($result){
                Session::setFlash("Page was saved.");
            } else {
                Session::setFlash("Error.");
            }
            Router::redirect('/admin/news/');
        }
    }

    public function admin_detail_edit()
    {
        if ($_POST){
            $result = $this->model->saveDetail(array_merge($_POST, $_FILES));
            if ($result){
                Session::setFlash("Page was saved.");
            } else {
                Session::setFlash("Error.");
            }
            Router::redirect('/admin/news/');
        }

        $this->data['sections'] = $this->model->getSections();
        $this->data['item'] = $this->model->getByAlias($this->params[1]);

        if(empty($this->data['item']))
            Session::setFlash("Error.");        
    }   

    public function admin_detail_delete()
    {
        if ( isset($this->params[1]) ){
            $result = $this->model->deleteDetail($this->params[1]);
            if ($result){
                Session::setFlash("Page was saved.");
            } else {
                Session::setFlash("Error.");
            }
        }
        Router::redirect('/admin/news/section/');
    }          
}