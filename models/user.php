<?php
class User extends Model
{
    public function getByLogin($login)
    {
        $login = $this->db->escape($login);
        $sql = "select * from users where login = '{$login}' limit 1";
        $result = $this->db->query($sql);
        if (isset($result[0])){
            return $result[0];
        }
        return false;
    }

    public function register(array $data)
    {
        extract($data);
        if (!isset($login) || !isset($email) || !isset($password)){
            return false;
        }

        $login = $this->db->escape($login);
        $email = $this->db->escape($email);
        $password = $this->db->escape($password);
        $password_hash = md5(Config::get('salt').$password);

        $sql = "insert into users (login, email, password, role, is_active) 
                  values ('{$login}', '{$email}', '{$password_hash}', 'user', '1')";


        return $this->db->query($sql);
    }

    public function loginValidation(string $login) : string
    {
        $errorStr = "";
        $matches = preg_match(Config::get("login_pattern"), $login);

        if (!$matches){
            $errorStr .= "Login must be 5-10 symbols (a-z A-Z 0-9 _ . -)<br/>";
        } else {
            if ($this->getByLogin($login)) {
                $errorStr .= "Login is used<br/>";
            }
        }

        return $errorStr;
    }

    /**
     * Passwords validation
     */
    public function pswValidation(string $psw, string $psw_second) : string
    {
        $errorStr = "";

        if ($psw == "" || $psw_second == ""){
            $errorStr .= "Passwords are reqiered!<br/>";
        } else {
            if ($psw != $psw_second){
                $errorStr .= "Passwords must be the same!<br/>";
            } else {
                if (!preg_match(Config::get("psw_pattern"), $psw)){
                    $errorStr .= "Password must be 5-10 any symbols<br/>";
                }
            }
        }

        return $errorStr;
    }

    public function emailValidation(string $email) : string
    {
        $errorStr = "";
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errorStr .= "Email is wrong!<br/>";
        }
        return $errorStr;
    }
}