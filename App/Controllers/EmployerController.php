<?php

namespace App\Controllers;

use App\Models\Employer;
use App\Models\User;
use App\Router;
use App\DB;
use App\Data;
use App\Services\EmployerService;

class EmployerController extends Controller
{
    public function indexbyadmin($params)
    {
        
        $search = isset($params['search']) ? trim($params['search']) : '';
        $filter = isset($params['filter']) ? $params['filter'] : '';
        $sort = isset($params['sort']) ? $params['sort'] : '';
        $whereClauses = ["role='employer'"];
        // search
        if (!empty($search)) {
            $search = addslashes($search);
            $whereClauses[] = "id IN (SELECT id FROM users WHERE full_name LIKE '{$search}%' OR phone LIKE '{%$search}%' OR email LIKE '{$search}%' OR login LIKE '{$search}%')";
        }
        // filter
        if($filter=="cashiers") $whereClauses[] = "id IN (SELECT user_id FROM employee WHERE posada='касир')";
        else if($filter=="ushers") $whereClauses[] = "id IN (SELECT user_id FROM employee WHERE posada='перевіряючий')";
        //sorting
        if($sort=='') $sort='by_name_asc';
        $sortParams=explode("_",$sort);
        if($sortParams[1]=='name') $sort="full_name";
        else if($sortParams[1]=='login') $sort="login";

        if($sortParams[2]=="asc") $sort.=" ASC";
        else if($sortParams[2]=="desc") $sort.=" DESC";
        
        // join all params
        $queryParams=[];
        if (!empty($whereClauses)) $queryParams['filter'] = implode(' AND ', $whereClauses);
        if (!empty($sort)) $queryParams['sort'] = $sort;
    
        $users=User::all($queryParams);
        $employee=array_map(function ($item) {
            return new Employer(DB::selectOne("employee",'*','user_id='.$item['id']));
        }, $users);
        //add test data
        self::render('Список працівників', '/admin/employee', 'admin', [
            'employee' => $employee,
            'search'=>$search,
            'filter'=>$filter,
            'sort'=>isset($params['sort']) ? $params['sort'] : '',
        ]);
    }

    public function showbyadmin($params)
    {
        $id=$params['id'];
        $employer=new Employer($id);
        self::render('Профіль працівника','/admin/employer','admin',[
            'employer'=>$employer  
        ]);
    }

    public function createbyadmin()
    {
        self::render('Додавання працівника', '/admin_form/employer_add', 'admin');
    }
    
    public function storeemployerbyadmin($data)
    {
        EmployerService::storeEmployer($data);
        Router::redirect('/admin/employee');
    }

    public function newpasswordbyadmin($param){

        $id=$param['id'];
        $employer=Employer::find($id);
        self::render('Оновлення паролю працівника', '/admin_form/update_password', 'admin',[
            'employer_id'=>$id
        ]);
    }
    
    public function editbyadmin($param)
    {
        $id=$param['id'];
        $user=new User($id);
        $employer=$user->Employer($id);
        self::render('Редагування працівника', '/admin_form/employer_edit', 'admin',[
            'employer'=>$employer
        ]);
    }

    public function updateemployerbyadmin($param,$data)
    {
        $id=$param['id'];
        EmployerService::updateEmployer($id,$data);
        Router::redirect('/admin/employee');

    }
    

    public function savenewpasswordbyadmin($param,$data)
    {
        $id=$param['id'];
        $password=$data['password'];
        User::update('id='.$id,[
            'password'=>md5($password)
        ]);

        Router::redirect('/admin/employee');
    }
    
}
