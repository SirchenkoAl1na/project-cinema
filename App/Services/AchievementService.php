<?php

namespace App\Services;

use App\Data;
use App\DB;
use App\Models\Place;
use App\Models\Seanse;
use App\Models\Film;
use App\Models\Sale;
use App\Models\User;
use App\Models\Achievement;
use App\Models\AchievementUser;

class AchievementService
{
    public static function storeAchievement($data)
    {
        $title=$data["title"];
        $discount=$data["discount"];
        $triger=$data["triger"];
        $level_description=$data["level_description"];
        $number_for_goal=$data["number_for_goal"];
        $description=$data["triger_detail"];
        if($triger=="few_tickets"){
            $description=json_encode([
                'tickets'=>$description,
            ]);
        }else if($triger=="film_genre"){
            $description=json_encode([
                'genre'=>$description,
            ]);

        }else if($triger=="review"){
            $description=json_encode([
                'reviews'=>$description,
            ]);
        }else if($triger=="film"){
            $film=Film::where('title='.$description)[0];
            $description=json_encode([
                'film_id'=> $film['id']??null,
            ]);
        }

        $image_title=ImageService::saveImage("image_title",'resources/img/achievements/');
        
        $res = Achievement::create([
            'title' => $title,
            'triger' => $triger,
            'level_description' => $level_description,
            'description' => $description,
            'number_for_goal' => $number_for_goal,
            'discount' => $discount,
            'image_title' => $image_title,
        ]);
        return $res;
    }
    public static function updateAchievement($id,$data)
    {
        $title=$data["title"];
        $discount=$data["discount"];
        $triger=$data["triger"];
        $level_description=$data["level_description"];
        $number_for_goal=$data["number_for_goal"];
        
        $description=$data["description"];
        if($triger=="few_tickets"){
            $description=json_decode([
                'tickets'=>$decription,
            ]);
        }else if($triger=="film_genre"){
            $description=json_decode([
                'genre'=>$decription,
            ]);

        }else if($triger=="review"){
            $description=json_decode([
                'reviews'=>$decription,
            ]);
        }else if($triger=="film"){
            $film=Film::where('title='.$description)[0];
            $description=json_decode([
                'film_id'=> $film['id']??null,
            ]);
        }


        $image_title=ImageService::saveImage("image_title",'resources/img/achievements/');
        if($image_title!=null){
            $res = Achievement::update('id='.$id,[
                'image_title' => $image_title,
            ]);
            if(!$res){
                return false; 
            }
        }
        $res = Achievement::update("id=".$id,[
            'title' => $title,
            'triger' => $triger,
            'level_description' => $level_description,
            'number_for_goal' => $number_for_goal,
            'discount' => $discount,
        ]);
        return $res;
    }
    
    public static function checkAchievements(int $sale_id,$user_id=null):void
    {
        $user=new User($user_id);
        $sale=new Sale($sale_id);
        $seanse=$sale->seanse;
        $film=$seanse->film;

        //1. check genre
        $genres=explode(',',$film->genres);
        $achievements=array_map(function($a){
            return new Achievement($a);
        },Achievement::where("triger='film_genre'"));

        foreach($genres as $genre){
            foreach($achievements as $achievement){
                if(isset($achievement->full_description['genre']) && $achievement->full_description['genre']==trim($genre)){
                    self::addAchievementToUser($user_id,$achievement,$sale_id);
                }
            }
        }
            
        //2. check number of tickets
        $tickets_number=count($sale->tickets());
        $achievements=array_map(function($a){
            return new Achievement($a);
        },Achievement::where("triger='few_tickets'"));
        foreach($achievements as $achievement){
            if($achievement->full_description['tickets']==$tickets_number){
                self::addAchievementToUser($user_id,$achievement,$sale_id);
            }
            
        }
        foreach($achievements as $achievement){
            if($achievement->full_description['tickets']==1){
                self::addAchievementToUser($user_id,$achievement,$sale_id);
            }
            
        }
        //3. check time of seanse
        $seanse_time=$seanse->time;
        $seanse_hour=(int)(explode(':',$seanse_time)[0]);
        $achievements=array_map(function($a){
            return new Achievement($a);
        },Achievement::where("triger='time'"));
        foreach($achievements as $achievement){
            $times=explode('-',$achievement->full_description['time']);
            if($seanse_hour>= (int)$times[0] && $seanse_hour<=(int)$times[1]){
                self::addAchievementToUser($user_id,$achievement,$sale_id);
            }
        }
        //todo: add bonuses?
    }
    
    public static function addAchievementToUser( $user_id, $achievement, $sale_id): void
    {
        $user=new User($user_id);
        $achievement_users=AchievementUser::where("user_id=".$user_id." AND achievement_id=".$achievement->id);
        if(empty($achievement_users)){
            AchievementUser::create([
                'user_id'=>$user_id,
                'sale_id'=>$sale_id,
                'achievement_id'=>$achievement->id,
                'current_level'=>1,
                'achieved'=>$achievement->number_for_goal==1?1:0,
                'date'=>Data::today(),
            ]);
            if($achievement->number_for_goal==1){
                User::update("id=".$user_id,[
                    "discount"=>$user->discount+$achievement->discount,
                ]);
            }
        }else{
            $achievement_user=$achievement_users[0];

            if($achievement_user['achieved']==1){
                return;
            }
            else{
                $achieved=$achievement_user['current_level']+1==$achievement->number_for_goal?1:0;
                $current_level=$achieved==1?$achievement->number_for_goal:$achievement_user['current_level']+1;
                AchievementUser::update("id=".$achievement_user['id'],[
                    'current_level'=>$current_level,
                    'achieved'=>$achieved,
                    'date'=>Data::today(),
                ]);
                if($achieved==1){
                    User::update("id=".$user_id,[
                        "discount"=>$user->discount+$achievement->discount,
                    ]);
                }
            }
        }
    }

    public static function returnAchievements(int $sale_id,int $user_id)
    {
        //todo
        //delete all acchienvemtns where saleid=$sale_id
        //return result?
    }

    public static function collectionFull(int $user_id) {
        $rows = DB::selectByQuery("
            SELECT a.*, ua.current_level, ua.achieved, ua.date
            FROM achievements a
            LEFT JOIN users_achievements ua 
                   ON a.id = ua.achievement_id 
                  AND ua.user_id = $user_id
        ");
    
        $res1 = [];
        $res2 = [];
        $res3 = [];
    
        foreach ($rows as $row) {
            $achievement = new Achievement($row);
    
            if (is_null($row['achieved'])) {
                // користувач ще не має запису про досягнення
                $res3[] = [
                    'achievement'    => $achievement,
                    'current_level'  => 0,
                    'achieved'       => 0,
                    'date'           => null,
                ];
            } elseif ($row['achieved']) {
                // досягнення виконане
                $res1[] = [
                    'achievement'    => $achievement,
                    'current_level'  => $row['current_level'],
                    'achieved'       => $row['achieved'],
                    'date'           => $row['date'],
                ];
            } else {
                // в процесі
                $res2[] = [
                    'achievement'    => $achievement,
                    'current_level'  => $row['current_level'],
                    'achieved'       => $row['achieved'],
                    'date'           => $row['date'],
                ];
            }
        }
    

        $res=array_merge($res1, $res2, $res3);
        //sort by title
        // usort($res, function($a, $b) {
        //     return strcmp($a['achievement']->title, $b['achievement']->title);
        // });
        

        return $res;
    }
    
    

    public static function collection(int $user_id){
        // CHECK:
        $achievements_by_user=DB::selectByQuery("SELECT * FROM users_achievements WHERE user_id=".$user_id);
        $res= array_map(function($achievement_by_user){
            return [
                'achievement'=>new Achievement($achievement_by_user['achievement_id']),
                'current_level'=>$achievement_by_user['current_level'],
                'achieved'=>$achievement_by_user['achieved'],
                'date'=>$achievement_by_user['date'],
            ];
        },$achievements_by_user);


        //sort by achieved
        $res2=[];
        foreach($res as $i){
            if($i['achieved']){
                $res2[]=$i;
            }
        }
        foreach($res as $i){
            if(!$i['achieved']){
                $res2[]=$i;
            }
        }
        return $res2;

    }
}