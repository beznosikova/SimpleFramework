<?php
class UsersController extends Controller
{
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->model = new User();
    }

    public function admin_login()
    {
        if ($_POST && isset($_POST['login']) && isset($_POST['password'])){
            $user = $this->model->getByLogin($_POST['login']);
            $hash = md5(Config::get('salt').$_POST['password']);
            if ($user && $user['is_active'] && $hash == $user['password']){
                Session::set('login', $user['login']);
                Session::set('role', $user['role']);
            }
            Router::redirect('/admin/');
        }
    }

    public function admin_logout()
    {
        Session::destroy();
        Router::redirect('/admin/');
    }

    public function register()
    {
        if ($_POST && isset($_POST['register'])&& $_POST['register'] != ""){

            $errorStr = "";

            $login = htmlspecialchars($_POST['login']);
            $email = htmlspecialchars($_POST['email']);
            $password = htmlspecialchars($_POST['password']);
            $password_second = htmlspecialchars($_POST['password_second']);

            $errorStr .= $this->model->loginValidation($login);
            $errorStr .= $this->model->emailValidation($email);
            $errorStr .= $this->model->pswValidation($password, $password_second);

            $data = compact("login", "email", "password");

            if (empty($errorStr)){
                $result = $this->model->register($data);
                if ($result){
                    Session::setFlash("Registration success!");
                    Session::set('login', $login);
                    Session::set('role', 'user');
                    $this->data = "success";
                }
            } else {
                $this->data = $data;
                Session::setFlash($errorStr);
            }
        }
    }
}