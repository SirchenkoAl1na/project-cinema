<?php

namespace App;

class Data
{
    public static $ticket_price=150;
    public static $week_days_ua = [
        "0" => "нед",
        "1" => "пон",
        "2" => "вівт",
        "3" => "сер",
        "4" => "чет",
        "5" => "п'ят",
        "6" => "суб",
    ];
    public static $months_ua = [
        "01" => "січ",
        "02" => "лют",
        "03" => "бер",
        "04" => "квіт",
        "05" => "трав",
        "06" => "черв",
        "07" => "лип",
        "08" => "серп",
        "09" => "вер",
        "10" => "жовт",
        "11" => "лист",
        "12" => "груд",
    ];
    public static $months_full_ua = [
        "01" => "Січень",
        "02" => "Лютий",
        "03" => "Березень",
        "04" => "Квітень",
        "05" => "Травень",
        "06" => "Червень",
        "07" => "Липень",
        "08" => "Серпень",
        "09" => "Вересень",
        "10" => "Жовтень",
        "11" => "Листопад",
        "12" => "Грудень",
    ];
    public static $positions = [
        // "адміністратор" => "Адміністратор",
        "касир" => "Касир",
        "перевіряючий" => "Перевіряючий",
    ];
    public static $holeStatuses = [
        "відкритий" => "Відкритий",
        "на ремонті" => "На ремонті",
    ];
    
    public static $achievementsTrigers=[
        "film" => "Відвідано сеанси з фільмом",
        "review" => "Залишено відгук",
        "film_genre" => "Відвідано сеанси з фільмом певного жанру",
        "few_tickets" => "Куплено два квитки або більше",
        "time" => "Куплено квитки до сеансу на певний час дня",
        "primier"=>"Відвідано прем'єрний сеанс фільму",
        
    ];
    public static string $cinemaOpen = "10:00";
    public static string $cinemaClose = "23:30";
    
    public static function pa(array $array)
    {
        echo "<pre>";
        var_dump($array);
        echo "<pre>";
    }
    public static function pp()
    {
        echo "<pre>";
        var_dump($_POST);
        echo "<pre>";
    }
    public static function pg()
    {
        echo "<pre>";
        var_dump($_GET);
        echo "<pre>";
    }
    public static function ps()
    {
        echo "<pre>";
        var_dump($_SESSION);
        echo "<pre>";
    }
    public static function pt($text)
    {
        echo "<pre>";
        var_dump($text);
        echo "<pre>";
    }

    public static function getId(){
        if(isset($_SESSION['user']['id'])){
            return $_SESSION['user']['id'];
        }else{
            return null;
        }
    }

    public static function timeNow()
    {
        return date("H:m:s");
    }
    public static function today($param='')
    {
        $date= date("Y-m-d");
        if($param === '') {
            return $date;
        }
        return date('Y-m-d', strtotime($param.' day', strtotime($date)));
    }

    public static function date($date):string
    {
        return date("d.m.Y",strtotime($date));
    }

    public static function isDate($date):bool
    {
        if(strtotime($date))  return true;
        else return false;
    }

    public static function prevdate($date, int $days = 1)
    {
        return date('Y-m-d', strtotime('-' . $days . 'day', strtotime($date)));
    }

    public static function nextdate($date, int $days = 1)
    {
        return date('Y-m-d', strtotime('+' . $days . 'day', strtotime($date)));
    }
    
    public static function Error(string|object $e,$second_text="")
    {
        
        echo "<pre class='error'>";
        if(is_string($e)){
            echo "Error: " . $e . "\n";
        }else{
            echo "Error: " . $e->getMessage() . "\n";
            echo "File: " . $e->getFile() . "\n";
            echo "Line: " . $e->getLine() . "\n";
        }
        if(!empty($second_text="")) echo $second_text;
        echo "</pre>";
    }
    public static function error404($method,$url)
    {
        http_response_code(404);
        echo "404 Not Found";
        echo "<br>";
        echo "Method: $method";
        echo "<br>";
        echo "URL: $url";
    }
    public static function dateFormat(string $date): string
    {
        $day = date('d', strtotime($date));
        $month = date('m', strtotime($date));
        $year = date('Y', strtotime($date));
        $month_text = self::$months_ua[$month] ?? $month;
        return "$day $month_text. $year";
    }
    public static function datetimeFormat(string $date,string $time): string
    {
        $day = date('d', strtotime($date));
        $month = date('m', strtotime($date));
        $year = date('Y', strtotime($date));
        $hour = date('H', strtotime($time));
        $minute = date('i', strtotime($time));
        $month_text = self::$months_ua[$month] ?? $month;
        return "$day $month_text. $year $hour:$minute";
    }
}
