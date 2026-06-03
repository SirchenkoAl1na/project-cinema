<?php

namespace App\Services;

use App\Data;
use App\DB;
use App\Models\Employer;
use App\Models\User;

class EmployerService
{
    public static function storeEmployer($data)
    {
        // get data
        $full_name = $data['full_name'];
        $login = $data['login'];
        $email = $data['email'];
        $phone = $data['phone'];
        $posada = $data['posada'];
        $password = $data['password'];
        // register user
        AuthService::register($full_name, $login, $password, $email,$phone,  'employer');
        // add employer
        $user = DB::selectOne('users', '*', '', 'id desc');
        $res = Employer::create([
            'posada' => $posada,
            'user_id' => $user['id'],
        ]);

        return $res;
    }

    public static function updateEmployer($id, $data)
    {
        $full_name = $data['full_name'];
        $login = $data['login'];
        $email = $data['email'];
        $phone = $data['phone'];
        $posada = $data['posada'];
        $zarplata = $data['zarplata'];

        User::update('id='.$id, [
            'full_name' => $full_name,
            'login' => $login,
            'phone' => $phone,
            'email' => $email,
        ]);
        $res = Employer::update('user_id='.$id, [
            'posada' => $posada,
            'zarplata' => $zarplata,
        ]);

        return $res;
    }
}
