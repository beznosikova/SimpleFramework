<?php
class App
{
    protected static $router;

    public static $db;

    /**
     * @return mixed
     */
    public static function getRouter()
    {
        return self::$router;
    }

    public static function run($uri)
    {
        self::$router = new Router($uri);

        self::$db = new DB(Config::get('db.host'), Config::get('db.user'), Config::get('db.psw'), Config::get('db.db_name'));

        Lang::load(self::$router->getLanguage());

        $controller_class = ucfirst(self::$router->getController()).'Controller';
        $controller_method = strtolower(self::$router->getMethodPrefix().self::$router->getAction());

        $layout = self::$router->getRoute();
// pr($layout);
        if ($layout == 'admin' && Session::get('role') != 'admin'){
            if ($controller_method != 'admin_login'){
                Router::redirect('/admin/users/login');
            }
        }

        // Calling controller's method
        $controller_object = new $controller_class();
        if (method_exists($controller_object, $controller_method)){
            $view_path = $controller_object->$controller_method();
            $view_object = new View($controller_object->getData(), $view_path);
            $content = $view_object->render();
        } else {
            throw new Exception("Method ".$controller_method." of class ".$controller_class. " does not exist!");
        }

        // //menu 
        // $pages_controller = new PagesController();
        // $pages_controller->index();
        // pr($pages_controller->getData());
        // $view_pages = new View($pages_controller->getData());
        // $pages = $view_pages->render();
        // pr($pages);

        $layout_path = VIEWS_PATH.DS. $layout.'.html';
        $layout_view_object = new View(compact('content'), $layout_path);
        echo $layout_view_object->render();
    }
}