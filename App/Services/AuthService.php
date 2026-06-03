<?php

namespace App\Services;

use App\DB;
use App\Data;

class AuthService
{
    public static function userExists($login, $password)
    {
        $password = md5($password);
        $res = DB::selectOne('users', '*', "login='$login' AND password='$password'");
        return !is_null($res);
    }
    public static function findUser($login, $password)
    {
        $password = md5($password);
        return DB::selectOne('users', '*', "login='$login' AND password='$password'");
    }
    public static function checkLogin($login)
    {
        $res = DB::selectOne('users', '*', "login='$login';");
        return !is_null($res);
    }

    public static function register($full_name, $login, $password, $email,$phone,  $role = 'client')
    {
        $password = md5($password);
        $res=DB::insert('users', [
            'full_name' => $full_name,
            'login' => $login,
            'phone' => $phone,
            'password' => $password,
            'email' => $email,
            'role' => $role,
            'created_at'=>Data::today()
        ]);
        return $res;
    }
}
