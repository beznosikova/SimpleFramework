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

        /*  ---- menu ---   */
        $menu_controller = new MenuController();
        $view_path = $menu_controller->template();
        $view_menu = new View($menu_controller->getData(), $view_path);
        $menu = $view_menu->render();
        /* ---------------------*/

        /* --------template -----*/
        $theme_model = new Template();
        $header_color = $theme_model->getStyleByAlias('header_color');
        $body_color = $theme_model->getStyleByAlias('body_color');
        /*-----------------------*/

        /* ------ advertising ----------- */
        $advert_controller = new AdvertisingController();
        $view_path = $advert_controller->template();
        $advert_data = array_chunk($advert_controller->getData()['items'], 4);
        $view_advert_left = new View($advert_data[0], $view_path);
        $view_advert_right = new View($advert_data[1], $view_path);
        $advert_left = $view_advert_left->render();
        $advert_right = $view_advert_right->render();
        /* ------ end --- advertising --- */

        /*  ---- menu ---   */
        if ( App::getRouter()->getUri() == "" ){
            $slider_controller = new NewsController();
            $view_path = $slider_controller->template();
            $view_slider = new View($slider_controller->getData(), $view_path);
            $slider = $view_slider->render();
        }
        /* ---------------------*/

        $layout_path = VIEWS_PATH.DS. $layout.'.html';
        $layout_view_object = new View(
            compact(
                'content', 
                'menu', 
                'header_color', 
                'body_color',
                'advert_left',
                'advert_right',
                'slider'
            ), 
            $layout_path
        );
        echo $layout_view_object->render();
    }
}