<?php

namespace App\Models;

use App\DB;

class User extends Model
{
    static $table = 'users';

    public int|null $id;
    public string $full_name;
    public string $login;
    public string $email;
    public string $phone;
    private string $password;
    public string $role;
    public int $discount;
    public string $photo;
    public string $created_at;

    public Employer $employer;

    public function __construct($data=null)
    {
        if(is_null($data)&& isset($_SESSION['user']['id'])){
            $data=$_SESSION['user']['id'];
        }
        if (is_int($data)) {
            $data = self::find($data);
        }

        $this->id = $data['id'] ?? null;
        $this->full_name = $data['full_name'] ?? '';
        $this->login = $data['login'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->phone = $data['phone'] ?? '';
        $this->discount = $data['discount'] ?? 0;
        $this->password = $data['password'] ?? '';
        $this->role = $data['role'] ?? '';
        $this->photo = $data['photo'] ?? 'default.png';
        $this->created_at = $data['created_at'] ?? '';

    
        
    }

    public function Employer($id_=null)
    {
        $id=$id_==null?$this->id:$id_;
        $employer = Employer::where("user_id=".$id)[0];
        if(is_null($employer)) return null;
        else return new Employer($employer['id']);
    }

    
}
