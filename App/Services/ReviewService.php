<?php

namespace App\Services;

use App\Data;
use App\Models\Review;
use App\Models\User;
use App\Models\Film;


class ReviewService 
{

    public static function createbyregistereduser($params)
    {
        $film_id=$params['film_id'];
        $rating=$params['rating'];
        $review=$params['review'];
        $user_id=Data::getId();
        $parent_comment_id=$params['parent_comment_id'] ?? null;
        
        $res=Review::create([
            "film_id"=>$film_id,
            "rating"=>$rating,
            "comment"=>$review,
            "user_id"=>$user_id,
            "date"=>Data::today(),
            "time"=>Data::timeNow(),
            "visible"=>1,
            "parent_comment_id"=>$parent_comment_id,
        ]);
        return $res;

    }
    public static function createbyguest($params)
    {
        $film_id=$params['film_id'];
        $rating=$params['rating'];
        $review=$params['review'];
        $ticket_kod=$params['ticket_kod'];
        $user_id=Data::getId();
        $parent_comment_id=$params['parent_comment_id'] ?? null;
        
        $exists=Ticket::where("ticket_kod='".$ticket_kod."'");
            
        if(!empty($exists)){
            $res=Review::create([
                "film_id"=>$film_id,
                "rating"=>$rating,
                "comment"=>$review,
                "user_id"=>$user_id,
                "date"=>Data::today(),
                "time"=>Data::timeNow(),
                "visible"=>1,
                "parent_comment_id"=>$parent_comment_id,
            ]);
        }
        return $res;
    }

    public static function lastByUser(int $user_id){
        // CHECK: return 4 last reviews
        $data=Review::where("user_id=".$user_id." ORDER BY date DESC,time DESC LIMIT 4");
        return array_map(function($item){
            return new Review($item);
        },$data);
    }
    
    public static function listByUser(int $user_id){
        // CHECK
        return array_map(function($item){
            return new Review($item);
        },Review::where("user_id=".$user_id." ","date DESC,time DESC"));
    }
    
    public static function listAnswersNewByUser(int $user_id){
        // CHECK
        return array_map(function($item){
            return new Review($item);
        },Review::where("parent_comment_id IN (SELECT id FROM reviews WHERE user_id=".$user_id.") ","date DESC,time DESC"));//лише нові: AND date>='".Data::today()."' AND time>'".(date('H:i:s', strtotime('-24 hours')))."'
        
    }
    
    public static function listAnswersByUser(int $user_id){
        // CHECK
        return array_map(function($item){
            return new Review($item);
        }, Review::where("parent_comment_id IN (SELECT id FROM reviews WHERE user_id=".$user_id.")","date DESC,time DESC"));//AND (date<'".Data::today()."' OR time<='".(date('H:i:s', strtotime('-24 hours')))."')
    }
}

?>