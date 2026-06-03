<?php

namespace App\Services;

use App\Services\ImageService;
use App\Data;
use App\DB;
use App\Models\Place;
use App\Models\Seanse;
use App\Models\Film;
use App\Models\User;

class FilmService
{
    public static function storeFilm($data)
    {
        $title=$data['title'];
        $imdb_id=$data['imdb_id'];
        $original=$data['original'];
        $primiere_date=$data['primiere_date'];
        $duration=$data['duration'];
        $description=$data['description'];
        $genre=$data['genre'];
        $country=$data['country'];
        $director=$data['director'];
        $actors=$data['actors'];
        
        // thanks chatGPT
        $poster=ImageService::saveImage("poster","resources/img/film_posters/");
    
        $res = Film::create([
            'title' => $title,
            'imdb_id' => $imdb_id,
            'original_title'=>$original,
            'duration'=>$duration,
            'poster' => $poster, // збережеться filename
            'primiere_date' => $primiere_date,
            'description' => json_encode([
                'genres' => $genre,
                'cast' => $actors,
                'director' => $director,
                'description' => $description,
                'country' => $country,
            ], JSON_UNESCAPED_UNICODE),
        ]);

        return $res;
    }

    public static function updateFilm($film_id,$data){
        $title=$data['title'];
        $imdb_id=$data['imdb_id'];
        $original=$data['original'];
        $primiere_date=$data['primiere_date'];
        $end_date=$data['end_date'] ?? null; 
        $description=$data['description'];
        $duration=$data['duration'];
        $genre=$data['genre'];
        $country=$data['country'];
        $director=$data['director'];
        $actors=$data['actors'];
        
        $poster=ImageService::saveImage("poster","resources/img/film_posters/");
        if($poster!=null){
            $res = Film::update('id='.$film_id,[
                'poster' => $poster,
            ]);
            if(!$res){
                return false; 
            }
        }

        $res = Film::update('id='.$film_id,[
            'title' => $title,
            'imdb_id' => $imdb_id,
            'original_title'=>$original,
            'primiere_date' => $primiere_date,
            'end_date'=>$end_date,
            'duration'=>$duration,
            'description' => json_encode([
                'genres' => $genre,
                'cast' => $actors,
                'director' => $director,
                'description' => $description,
                'country' => $country,
            ], JSON_UNESCAPED_UNICODE),
        ]);

        return $res;
    }

    public static function primiereFilms(){
        //todo: return films what are primieres inthe next 2 months
        return [];
    }
}