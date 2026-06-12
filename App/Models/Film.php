<?php

namespace App\Models;

use App\DB;

class Film extends Model
{

     static $table='films';

     public int $id;
     public string $title;
     public string $original_title;
     public string $imdb_id;
     public string $poster;
     public string $primiere_date;
     public string $end_date;
     public int $duration;
     
     public string $description;
     public string $director;
     public string $country;
     public string $genres;
     public array $actors;

     public function __construct(int|array $data)
     {
          if (is_int($data)) {
               $data = self::find($data);
          }

          $this->id = $data['id'] ?? null;
          $this->title = $data['title'] ?? '';
          $this->imdb_id = $data['imdb_id'] ?? null;
          $this->original_title= $data['original_title'] ?? '';
          $this->primiere_date = $data['primiere_date'] ?? '';
          $this->end_date = $data['end_date'] ?? null;
          $this->poster = $data['poster'] ?? null;
          $this->duration=$data['duration'] ?? null;

          $posterPath = $_SERVER['DOCUMENT_ROOT'] . '/resources/img/film_posters/' . $data['poster'];
          if (!file_exists($posterPath)) {
          $this->poster = 'default.png';
          }
          $descriontion_json=$data['description'] ?? '{}';
          $description_data = json_decode($descriontion_json, true);

          $this->description = $description_data['description'] ?? '';
          $this->director = $description_data['director'] ?? '';
          $this->country = $description_data['country'] ?? '';
          $this->genres = is_array($description_data['genres'])?implode(', ',$description_data['genres']) : ($description_data['genres']??'');
          $cast = $description_data['cast'] ?? '';
          $this->actors=is_array($cast)?$cast:explode(',',$cast);
     } 
     public function reviews(){
          return Review::where('film_id='.$this->id.' AND is_blocked=false');
     }

     public static function genresList(){
          $genres = [];
          $films = self::all();
          foreach ($films as $film) {
               $film=new Film($film);
               $film_genres = !empty($film->genres)?explode(', ', $film->genres): [];
               foreach ($film_genres as $genre) {
                    if (!in_array($genre, $genres)) {
                         $genres[] = $genre;
                    }
               }
          }
          return $genres;
     }

     public function futureSeansesByFilm(){
          //check
          $today = date('Y-m-d');
          $time_now = date('H:i');
          $film_id=$this->id;
          $seanses = DB::selectByQuery("SELECT * FROM seanses WHERE film_id = ".$film_id." AND date >= '".$today."' ORDER BY date, STR_TO_DATE(time, '%H:%i')");
          $senases2=[];
          foreach($seanses as $seanse){
               if($seanse['date']==$today){
                    if($seanse['time']>$time_now){
                         $senases2[]=$seanse;
                    }
               }else{
                    $senases2[]=$seanse;
               }
          }
          $seanses=$senases2;
          if(empty($seanses)){
               return [];
          }
          //todo group by date
          $groupedSeanses=[
               $seanses[0]['date'] => []
          ];
          foreach($seanses as $seanse){
               if(!array_key_exists($seanse['date'],$groupedSeanses)){
                    $groupedSeanses[$seanse['date']]=[];
               }
               $groupedSeanses[$seanse['date']][]=$seanse;
          }
          return $groupedSeanses;
     }

     public function getAverageRating(){
          $reviews= $this->reviews();
          $sum=0;
          $counter=0;
          foreach($reviews as $review){
               $rating=$review['rating'];
               if($rating!=0&&$rating!=null){
                    $sum+=$rating;
                    $counter++;
               }
          }
          if($counter==0) return 0;
          $res=round($sum/$counter,1);
          return $res;

     }

}
