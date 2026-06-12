<?php

namespace App;

use App\Services\AuthService;

class Validate
{
    public static $not_need_validate=['storeseansebyadmin','storefilmbyadmin',
    'storeachievementbyadmin','storeemployerbyadmin','updateemployerbyadmin',
    'updatefilmbyadmin','updateachievementbyadmin','APIaddreview','APIreturnbyuser','APIremoveseanse',
    'storeprofile', 'validate'];
    public static function validate($page, $array)
    {
        try {
            $is_valid=true;
            if(!in_array($page,static::$not_need_validate)){
                $is_valid = static::{$page}($array);
            }
            if ($is_valid == false) {
                $_SESSION['message']['old_values'] = $array;
                header('Location: ' . str_replace("http://localhost:8080", "", $_SERVER['HTTP_REFERER']));
                exit(); 
            }
        } catch (\Error $e) {
            echo $e->getMessage();
            echo "<br>";
            echo $e->getTraceAsString();
            exit();
        }
    }
    public static function signin($array)
    {
        $is_valid = true;

        $login = $array['login'];
        $password = $array['password'];

        if (empty($login) && empty($password)) {
            $_SESSION['message']['all_form'] = '* Заповніть всі поля';
            $_SESSION['not_valid']['login'] = true;
            $_SESSION['not_valid']['password'] = true;
            $is_valid = false;
        } else if (empty($login) && !empty($password)) {
            $_SESSION['message']['login'] = 'Введіть логін';
            $_SESSION['not_valid']['login'] = true;
            $is_valid = false;
        } else if (!empty($login) && empty($password)) {
            $_SESSION['message']['password'] = 'Введіть пароль';
            $_SESSION['not_valid']['password'] = true;
            $is_valid = false;
        } 
            if(AuthService::checkLogin( $login)){
                if(!AuthService::userExists( $login,  $password)){
                    $_SESSION['message']['auth'] = 'Пароль не вірний';
                    $_SESSION['not_valid']['password'] = true;
                    $is_valid = false;
                }
            }
            else{
                $_SESSION['message']['auth'] = 'Користувача з таким логіном не знайдено';
                $_SESSION['not_valid']['login'] = true;
                $is_valid = false;
            }
        return $is_valid;
    }
    public static function signup($array)
    {
        $is_valid = true;

        $login = $array['login'];
        $password = $array['password'];
        $password_confirm = $array['password_confirm'];
        $phone = $array['phone'];
        $email = $array['email'];

        if (empty($login) && empty($email) && empty($password) && empty($password_confirm)) {
            $_SESSION['message']['all_form'] = '* Заповніть всі поля';

            $is_valid = false;
            return $is_valid;
        }
        if (empty($login)) {
            $_SESSION['message']['login'] = 'Введіть логін';
            $_SESSION['not_valid']['login'] = true;
            $is_valid = false;
        }
        if (empty($email)) {
            $_SESSION['message']['email'] = 'Введіть ел.пошту';
            $_SESSION['not_valid']['email'] = true;
            $is_valid = false;
        }
        if (empty($password)) {
            $_SESSION['message']['password'] = 'Введіть пароль';
            $_SESSION['not_valid']['password'] = true;
            $is_valid = false;
        }
        if (empty($password)) {
            $_SESSION['message']['password'] = 'Введіть пароль';
            $_SESSION['not_valid']['password'] = true;
            $is_valid = false;
        }
        if (empty($password_confirm)) {
            $_SESSION['message']['password_confirm'] = 'Введіть підвтердження пароля';
            $_SESSION['not_valid']['password_confirm'] = true;
            $is_valid = false;
        }

        if(strlen($phone) != 10||!is_numeric($phone)){
            $_SESSION['message']['phone'] = 'Телефон має містити тільки цифри (рівно 10)';
            $_SESSION['not_valid']['phone'] = true;
            $is_valid = false;
        }
        if (strlen($password) < 6) {
            $_SESSION['message']['password'] = 'Пароль повинен містити не менше 6 символів';
            $_SESSION['not_valid']['password'] = true;
            $is_valid = false;

        } else if ($password != $password_confirm) {
            $_SESSION['message']['password'] = 'Паролі не співпадають';
            $_SESSION['not_valid']['password'] = true;
            $is_valid = false;
        }
        $email_data = DB::selectOne('users', '*', "email='$email'");
        if (!is_null($email_data)) {
            $_SESSION['message']['email'] = 'Ел.пошта вже використовується';
            $_SESSION['not_valid']['email'] = true;
            $is_valid = false;
        }
        $login_data = DB::selectOne('users', '*', "login='$login'");
        if (!is_null($login_data)) {
            $_SESSION['message']['login'] = 'Логін вже використовується';
            $_SESSION['not_valid']['login'] = true;
            $is_valid = false;
        }


        return $is_valid;
    }

    public static function savenewpasswordbyadmin($array){
        $is_valid = true;
        
        $password = $array['password'];
        $password_confirm = $array['password_confirm'];
        if ($password != $password_confirm) {
            $_SESSION['message']['password'] = 'Паролі не співпадають';
            $_SESSION['not_valid']['password'] = true;
            $is_valid = false;
        }
        return $is_valid;
    }

    public static function storeholebyadmin($array){
        $is_valid = true;
        
        $nomer = $array['nomer'];
        $status = $array['status'];
        $hole_places = $array['hole_places'];

        $exists=DB::selectByQuery('SELECT * FROM holes WHERE nomer='.$nomer.' LIMIT 1;');
        if (!empty($exists)) {
            $_SESSION['message']['nomer'] = 'Цей номер залу вже існує';
            $_SESSION['message']['old_values']['nomer'] =  $nomer ;
            $_SESSION['message']['old_values']['status'] = $status;
            $_SESSION['message']['old_values']['hole_places'] =$hole_places;
            $_SESSION['not_valid']['nomer'] = true;
            $is_valid = false;
        }
        else if(empty($hole_places)){
            $_SESSION['message']['hole_places'] = 'Не можна створити залу без жодного місця в ньому!';
            $_SESSION['not_valid']['hole_places'] = true;
            $_SESSION['message']['old_values']['nomer'] =  $nomer ;
            $_SESSION['message']['old_values']['status'] = $status;
            $is_valid = false;
        }
        return $is_valid;
    }

    public static function updateholebyadmin($array){
        $is_valid = true;
        
        $id = $array['id'];
        $nomer = $array['nomer'];
        $status = $array['status'];
        $hole_places = $array['hole_places'];


        $exists=DB::selectByQuery('SELECT * FROM holes WHERE nomer='.$nomer.' AND id!='.$id.' LIMIT 1;');
        if (!empty($exists)) {
            $_SESSION['message']['nomer'] = 'Цей номер залу вже існує';
            $_SESSION['message']['old_values']['nomer'] =  $nomer ;
            $_SESSION['message']['old_values']['status'] = $status;
            $_SESSION['message']['old_values']['hole_places'] =$hole_places;
            $_SESSION['not_valid']['nomer'] = true;
            $is_valid = false;
        }
        
        return $is_valid;
    }

    public static function storephoto($array){
        //TODO:
        return true;
    }

    public static function APIbuytickets($array){

        return true;
    }

    public static function APIaddreviewbyguest($params){
        //TODO: CHECK TICKET KOD IS CORRECT
        return true;
    }
}
