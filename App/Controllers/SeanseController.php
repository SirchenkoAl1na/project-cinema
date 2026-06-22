<?php

namespace App\Controllers;

use App\Data;
use App\Models\Film;
use App\Models\Hole;
use App\Models\Seanse;
use App\DB;
use App\Router;
use App\Services\SeanseService;
use App\Services\FilmService;
use App\Models\User;

class SeanseController extends Controller
{
    public function indexbyadmin($params=null)
    {   
        $date = Data::today();
        if (!is_null($params) || !empty($params)) $date = $params['filter'] ?? $date;
        $filter= $params['filter2'] ?? 'by_film';

        $groups = [];
        if($filter=='by_film'){
            $film_ids=array_column(DB::selectByQuery("SELECT f.id FROM seanses as s JOIN films as f ON f.id=s.film_id WHERE s.date='$date' GROUP BY f.id"),'id');
     
            foreach($film_ids as $film_id){
                $seanse_on_time= Seanse::where("date='$date' AND film_id=$film_id");
                if(!empty($seanse_on_time)){
                    $seanse_on_time= array_map(function ($item) {
                        return new Seanse($item);
                    }, $seanse_on_time);
                    $groups[]=[
                        'film'=>new Film($film_id),
                        'seanses'=>$seanse_on_time,
                    ];
                }
            }
        }
        else if($filter=='by_time'){
            $times=array_column(DB::selectByQuery("SELECT DISTINCT s.time as time FROM seanses as s WHERE s.date='$date' ORDER BY STR_TO_DATE(s.time, '%H:%i')"),'time');
            // var_dump($times);
            
            foreach($times as $time){
                $seanse_on_time= Seanse::where("date='$date' AND time='$time'");
                if(!empty($seanse_on_time)){
                    $seanse_on_time= array_map(function ($item) {
                        return new Seanse($item);
                    }, $seanse_on_time);
                    $groups[]=[
                        'time'=>$time,
                        'seanses'=>$seanse_on_time,
                    ];
                }
            }
        }
        
        self::render('Сеанси', '/admin/seanses', 'admin',[
            'groups'=>$groups,
            'view'=>$filter,
            'current_date'=>$date,
        ]);
    }

    public function indexbyuser($params=[])
    {
        $user = new User();
        $filter_genre=$params['filter'] ?? '';
        $view = $params['filter2'] ?? 'by_film';
        $date = $params['date'] ?? Data::today();

        $groups = [];

        if($view=='by_film'){
            $film_ids=array_column(DB::selectByQuery("SELECT f.id FROM seanses as s JOIN films as f ON f.id=s.film_id WHERE s.date='$date' GROUP BY f.id"),'id');
     
            foreach($film_ids as $film_id){
                $film=new Film($film_id);
                $seanse_on_time= Seanse::where("date='$date' AND film_id=$film_id");
                $can_be_added=false;
                if(!empty($filter_genre)){
                    $film_genres = !empty($film->genres) ? explode(', ', $film->genres) : [];
                    if(in_array($filter_genre,$film_genres)) $can_be_added=true;
                }else{
                    $can_be_added=true;
                }
                if(!empty($seanse_on_time)&&$can_be_added){
                    $seanse_on_time= array_map(function ($item) {
                        return new Seanse($item);
                    }, $seanse_on_time);
                    $groups[]=[
                        'film'=>$film,
                        'seanses'=>$seanse_on_time,
                    ];
                }
            }
        }
        else if($view=='by_time'){
            // $times=array_column(DB::selectByQuery("SELECT s.time as time FROM seanses as s WHERE s.date='$date' ORDER BY STR_TO_DATE(s.time, '%H:%i')"),'time');
            $times=array_column(DB::selectByQuery("SELECT s.time as time FROM seanses as s WHERE s.date='$date' GROUP BY s.time ORDER BY STR_TO_DATE(s.time, '%H:%i')"),'time');
            
            foreach($times as $time){
                $seanse_on_time= Seanse::where("date='$date' AND time='$time'");
                if(!empty($seanse_on_time)){
                    
                    $seanse_on_time= array_map(function ($item) {
                        return new Seanse($item);
                    }, $seanse_on_time);

                    if(!empty($filter_genre)){
                        $seanse_on_time2=[];
                        foreach($seanse_on_time as $seanse){
                            $film_genres= !empty($seanse->film->genres) ? explode(', ', $seanse->film->genres) : [];
                            
                            if(in_array($filter_genre,$film_genres)) {
                                $seanse_on_time2[]=$seanse;
                            }
                        }
                        $seanse_on_time=$seanse_on_time2;
                    }
                    if(!empty($seanse_on_time)){
                        $groups[]=[
                            'time'=>$time,
                            'seanses'=>$seanse_on_time,
                        ];
                    }
                }
            }
        }

        $primiere_films=FilmService::primiereFilms();
        
        self::render('Сеанси', '/profile/seanses', 'profile',[
            'user' => $user,
            'genres'=>Film::genresList(),
            'filter_genre'=>$filter_genre,
            'primiere_films'=>$primiere_films,
            'view'=>$view,
            'current_date'=>$date,
            'date_today' => Data::today(),

            'groups'=>$groups,
        ]);
    }

    public function indexbycashier($params=null)
    {
        $date = Data::today();
        if (!is_null($params) || !empty($params)) $date = $params['filter'] ?? $date;
        $filter= $params['filter2'] ?? 'by_film';

        $groups = [];
        if($filter=='by_film'){
            $film_ids=array_column(DB::selectByQuery("SELECT f.id FROM seanses as s JOIN films as f ON f.id=s.film_id WHERE s.date='$date' GROUP BY f.id"),'id');
     
            foreach($film_ids as $film_id){
                $seanse_on_time= Seanse::where("date='$date' AND film_id=$film_id");
                if(!empty($seanse_on_time)){
                    $seanse_on_time= array_map(function ($item) {
                        return new Seanse($item);
                    }, $seanse_on_time);
                    $groups[]=[
                        'film'=>new Film($film_id),
                        'seanses'=>$seanse_on_time,
                    ];
                }
            }
        }
        else if($filter=='by_time'){
            $times=array_column(DB::selectByQuery("SELECT DISTINCT s.time as time FROM seanses as s WHERE s.date='$date' ORDER BY STR_TO_DATE(s.time, '%H:%i')"),'time');
             
            foreach($times as $time){
                $seanse_on_time= Seanse::where("date='$date' AND time='$time'");
                if(!empty($seanse_on_time)){
                    $seanse_on_time= array_map(function ($item) {
                        return new Seanse($item);
                    }, $seanse_on_time);
                    $groups[]=[
                        'time'=>$time,
                        'seanses'=>$seanse_on_time,
                    ];
                }
            }
        }
        
    
        self::render('Сеанси', '/cashier/seanses', 'cashier',[
            'groups'=>$groups,
            'view'=>$filter,
            'current_date'=>$date
        ]);
    }

    public function createbyadmin($params)
    {
        $film_id=$params['film_id'] ?? null;
        $today= date('Y-m-d');
        $films=[];
        foreach (Film::where('primiere_date <= "' . $today . '" AND end_date >= "' . $today . '"') as $item) {
            $films[$item['id']]=$item['title'];
        }
        foreach (Film::where('primiere_date >= "' . $today . '"') as $item) {
            $films[$item['id']]=$item['title'] . " (Прим'єра: " . $item['primiere_date'] . ")";
        }
        $holes=[];
        foreach (Hole::where("status='відкритий'") as $item) {
            $holes[$item['id']]=$item['nomer'];
        }
        self::render('Додавання сеансу', '/admin_form/seanse_add', 'admin', [
            'films' => $films,
            'holes' => $holes,
            'cinemaOpen'=>Data::$cinemaOpen,
            'cinemaClose'=>Data::$cinemaClose,
            'minDate'=>Data::prevdate(Data::today()),
            'tomorrow'=>Data::nextdate(Data::today()),
            'film_id'=>$film_id,
        ]);
    }

    public function APIseansetickets($params){
        try {
            $seanse_id=$params['seanse_id'];
            $seanse = new Seanse($seanse_id);
            $tickets = $seanse->tickets();
            echo json_encode([
                'status' => 1,
                'message' => [
                    'seanse' => $seanse,
                    'tickets' => $tickets,
                ],
            ]);
        } catch (\Exception $e) {
            echo json_encode([
                'status' => 0,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function storeseansebyadmin($request)
    {
        SeanseService::storeSeanse($request);
        Router::redirect('/admin');
    }

    public function APIremoveseanse($params){
        try {
            echo "seanse id".$params['seanse_id'];
            $res = SeanseService::removeSeanse($params['seanse_id']);
            echo json_encode([
                'status' => 1,
                'message' => $res,
            ]);
        } catch (\Exception $e) {
            echo json_encode([
                'status' => 0,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
