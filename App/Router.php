<?php

namespace App;

use App\Controllers;
use App\Controllers\MainController;

enum UserType: string
{
    case LOGINED = "LOGINED";
    case ADMIN = "ADMIN";
    case CASHIER = "CASHIER";
    case NOTLOGINED = "NOTLOGINED";
}
class Router
{
    private array $routes = [
        "GET" => [],
        "POST" => [],
        "PUT" => [],
        "DELETE" => []
    ];

    public function __construct()
    {
        include_once './routes.php';
        include_once './routes_api.php';
        $this->checkAuth();
    }

    public UserType $user_type;

    public function route(string $url, string $method)
    {
        if (!empty($_GET) && !empty($_POST)) $method = "PUT";
        $this->middleware($url, $method);
        $url = explode("?", $url)[0];
        $event = $this->routes[$method][$url]['action'] ?? null;
        if ($event === null) {
            Data::error404($method,$url);
            return;
        }
        if(str_contains($url,'/api')){
            $_POST=(array)json_decode(file_get_contents('php://input'));
        }
        switch ($method) {
            case "GET":
                (new $event[0])->{$event[1]}($_GET ?? "");
                break;
            case "POST":
                Validate::validate("$event[1]", $_POST);
                (new $event[0])->{$event[1]}($_POST ?? "");
                break;
            case "PUT":
                Validate::validate("$event[1]", $_POST);
                (new $event[0])->{$event[1]}($_GET, $_POST);
                break;
            case "DELETE":
                $this->delete($event, $method);
                (new $event[0])->{$event[1]}($_GET ?? "");
                break;
        }
    }
    public static function redirect($url, $permanent = false)
    {
        header('Location: ' . $url, true, $permanent ? 301 : 302);
        exit();
    }

    public function middleware(&$url, &$method)
    {
        $pageUserType = $this->routes[$method][$url]['userType'] ?? null;
        if ($pageUserType == null) return;
        if ($this->user_type == UserType::NOTLOGINED && $pageUserType != UserType::NOTLOGINED) self::redirect("/");
        elseif ($this->user_type == UserType::LOGINED && $pageUserType != UserType::LOGINED) self::redirect("/profile/seanses");
        elseif ($this->user_type == UserType::ADMIN && $pageUserType != UserType::ADMIN) self::redirect("/admin");
        elseif ($this->user_type == UserType::CASHIER && $pageUserType != UserType::CASHIER) self::redirect("/cashier");
    }

    public function checkAuth(): void
    {
        if (!isset($_SESSION['user'])){
            $this->user_type = UserType::NOTLOGINED;
        }
        else {
            if ($_SESSION['user']['role'] == 'employer')
            {
                if($_SESSION['user']['posada'] == 'адміністратор'){
                    $this->user_type = UserType::ADMIN;
                }
                else if($_SESSION['user']['posada'] == 'касир'){
                    $this->user_type = UserType::CASHIER;
                }else{
                    $this->user_type = UserType::ADMIN;
                }
            }
            else{
                $this->user_type = UserType::LOGINED;
            }
        }
    }

    public function post(string $path, $action, UserType $userType = null): void
    {
        $this->routes["POST"][$path] = [
            "action" => $action,
            "userType" => $userType
        ];
    }
    public function get(string $path, $action, UserType $userType = null): void
    {
        $this->routes["GET"][$path] = [
            "action" => $action,
            "userType" => $userType
        ];
    }
    public function put(string $path, $action, UserType $userType = null): void
    {
        $this->routes["PUT"][$path] = [
            "action" => $action,
            "userType" => $userType
        ];
    }
    public function delete(string $path, $action, UserType $userType = null): void
    {
        $this->routes["DELETE"][$path] = [
            "action" => $action,
            "userType" => $userType
        ];
    }
}
