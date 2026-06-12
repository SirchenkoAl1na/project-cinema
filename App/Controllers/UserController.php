<?php

namespace App\Controllers;

use App\Models\User;
use App\Router;
use App\Services\AchievementService;
use App\Services\AuthService;
use App\Services\ReviewService;
use App\Services\SeanseService;
use App\Services\TicketService;
use App\Services\ImageService;
use App\Models\Sale;
use App\UserType;
use App\Services\FinanseService;
use App\Data;

class UserController extends Controller
{
    public function login()
    {
        self::render('Вхід', 'auth/signin', 'guest');
    }

    public function register()
    {
        self::render('Реєстрація', 'auth/register', 'guest');
    }

    public function signin($request)
    {
        $user = AuthService::findUser($request['login'], $request['password']);
        $type = $user['role'] == 'employer' ? UserType::ADMIN : UserType::LOGINED;
        $user = new User($user);
        $_SESSION['user'] = [
            'id' => $user->id,
            'full_name' => $user->full_name,
            'login' => $user->login,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role,
            'posada' => $user->Employer()->posada,
            'photo' => $user->photo ?? null,
            'created_at' => $user->created_at,
        ];
        if ($type == UserType::ADMIN) {
            Router::redirect('/admin');
        } else {
            Router::redirect('/profile/seanses');
        }
    }

    public function signup($request)
    {
        AuthService::register(
            $request['full_name'],
            $request['login'],
            $request['password'],
            $request['email'],
            $request['phone']
        );
        $_SESSION['message']['registered'] = 'Ви успішно зареєструвалися';

        $user = new User(AuthService::findUser($request['login'], $request['password']));
        $_SESSION['user'] = [
            'id' => $user->id,
            'full_name' => $user->full_name,
            'login' => $user->login,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role,
            'posada' => null,
            'photo' => null,
            'created_at' => $user->created_at,
        ];

        self::render('Успішна реєстарція', 'profile/registered', 'profile',[
            'user'=>$user,
        ]);
    }


    public function logout()
    {
        unset($_SESSION['user']);
        Router::redirect('/');
    }

    public function clientpage()
    {
        self::render('Головна', 'client/index', 'main');
    }

    public function adminpanel()
    {
        self::render('Головна', 'admin/index', 'admin');
    }

    public function profileforadmin()
    {
        self::render('Профіль користувача', 'admin/profile', 'admin', [
            'role' => (new User())->employer()->posada,
        ]);
    }

    public function loadphoto()
    {
        $user=new User();
        self::render('Завантаження фото', 'profile/photo', 'profile',[
            'user'=>$user,
        ]);
    }

    public function APIfindclient($params)
    {
        try {
            $res = null;

            $phone=$_GET['phone'] ?? null;
            $email=$_GET['email'] ?? null;

            if(is_null($phone)){
                $res=User::where("email='$email'");
            }else{
                $res=User::where("phone='$phone'");
            }

            $res=!empty($res)?new User($res[0]):null;

            echo json_encode([
                'status' => 1,
                'data' => $res,
            ]);
        } catch (\Exception $e) {
            echo json_encode([
                'status' => 0,
                'message' => $e->getMessage(),
            ]);
        } 
    }

    public function storephoto($params)
    {
        try{
        $image=ImageService::saveImage("image","resources/img/users/");
        $user=new User();
        if(is_null($image)){
            throw new \Exception("Помилка при завантаженні фото");
        }
        User::update("id=".$user->id,[
            'photo'=>$image,
        ]);
        Router::redirect('/profile');   
        }
        catch(\Exception $e){
        $_SESSION['message']['photo']=$e->getMessage();
        Router::redirect('/profile/photo'); }

    }

    public function statistics()
    {
        $profit=FinanseService::profitCurrentMonth();      
        $sold_tickets=FinanseService::soldTicketsCurrentMonth();
        $average_ticket_price=FinanseService::averageTicketPrice();
        $top_films=FinanseService::topFilms();

        $data_half_year=FinanseService::lastHalfYear();

        self::render('Фінансова звітність', '/admin/statistic', 'admin', [
            'profit' => $profit,
            'sold_tickets' => $sold_tickets,
            'average_ticket_price' => $average_ticket_price,
            'top_films' => $top_films,
            'data_half_year'=>$data_half_year,
        ]);
    }

    public function statisticsbycashier()
    {
        
        $today= date('Y-m-d');
        $sales=Sale::where("date='$today'");
        if(empty($sales)){
            self::render('Фінансова звітність', '/cashier/statistics', 'cashier', [
                'profit' => null,
                'the_most_popular_seanse'=>null,
            ]);
        }else{

            $profit=FinanseService::profitByDay();      
            $the_most_popular_seanse=FinanseService::theMostPopularSeanse();

            self::render('Фінансова звітність', '/cashier/statistics', 'cashier', [
                'profit' => $profit,
                'the_most_popular_seanse'=>$the_most_popular_seanse,
            ]);
        }
    }

    public function edit()
    {
        $user = new User();
        self::render('Редагування профілю', '/profile/profile_edit', 'profile', [
            'user' => $user,
        ]);
    }

    public function storeprofile($params)
    {
        $user = new User();
        User::update("id=".$user->id,[
            'full_name'=>$params['full_name'],
            'login'=>$params['login'],
            'email'=>$params['email'],
            'phone'=>$params['phone'],
        ]);
        Router::redirect('/profile');
    }

    public function profile()
    {
        $user = new User();
        $reviews_last = ReviewService::lastByUser($user->id);
        $seanses_last = SeanseService::lastbyuser($user->id);
        $achievements = AchievementService::collection($user->id);
        $basket = TicketService::basket($user->id);

        self::render('Перегляд профілю', '/profile/profile', 'profile', [
            'user' => $user,
            'reviews_last' => $reviews_last,
            'seanses_last' => $seanses_last,
            'achievements' => $achievements,
            'basket' => $basket,
        ]);
    }

    
}
