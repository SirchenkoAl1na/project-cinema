<?php

namespace App\Controllers;

use App\Data;
use App\Models\Film;
use App\Models\Hole;
use App\Models\Seanse;
use App\Models\Review;
use App\DB;
use App\Router;
use App\Services\SeanseService;
use App\Services\FilmService;
use App\Models\User;

class GuestController extends Controller
{
    public function seanses($params=[])
    {
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
            // $times=array_column(DB::selectByQuery("SELECT DISTINCT s.time as time FROM seanses as s WHERE s.date='$date' ORDER BY STR_TO_DATE(s.time, '%H:%i')"),'time');
            $times=array_column(DB::selectByQuery("SELECT s.time as time FROM seanses as s WHERE s.date='$date' ORDER BY STR_TO_DATE(s.time, '%H:%i')"),'time');
            
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
        // var_dump($groups);
        // exit();
        self::render('Головна', 'guest/seanses', 'guest', [
            'genres' => Film::genresList(),
            'filter_genre' => $filter_genre,
            'view'=>$view,
            'current_date' => $date,
            'date_today' => Data::today(),
            'groups' => $groups,
            'primiere_films'=>$primiere_films,
        ]);
    }

    public function seanse($params)
    {
        $seanse_id = $params['seanse_id'] ?? null;
        if (is_null($seanse_id)) {
            Router::redirect('/seanses');
        } else {
            $seanse = new Seanse($seanse_id);
            $film = $seanse->film ?? null;
            $tickets = $seanse->tickets();
            self::render('Продажа квитків', '/guest/seanse', 'guest', [
                'seanse' => $seanse,
                'film' => $film,
                'tickets' => $tickets,
                'hole' => !empty($seanse->hole) ? $seanse->hole : null,
                'date' => Data::date($seanse->date),
                'time' => $seanse->time,
                'ticket_price'=> Data::$ticket_price, 
            ]);
        }
        
    }
    public function film($params)
    {
        $film_id = $params['id'];
        $film = new Film($film_id);
        $seanses=$film->futureSeansesByFilm();
        $reviews = [];
        foreach ($film->reviews() as $review) {
            $reviews[] = new Review($review);
        }
        self::render($film->title, 'guest/film', 'guest', [
            'film' => $film,
            'reviews' => $reviews,
            'seanses'=>$seanses
        ]);
    }
}
