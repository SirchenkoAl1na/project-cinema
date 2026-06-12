<?php

namespace App\Services;

use App\Data;
use App\DB;
use App\Models\Place;
use App\Models\Seanse;
use App\Models\Ticket;
use App\Models\Sale;
use App\Models\User;

class SeanseService
{
    public static function storeSeanse($data)
    {
        $hole_id = $data['hole_id'];
        $res=Seanse::create([
            'film_id' => $data['film_id'],
            'hole_id' => $hole_id,
            'date' => $data['date'],
            'time' => $data['time'],
        ]);
        $places = Place::where('hole_id=' . $hole_id);
        foreach ($places as $place) {
            Ticket::create([
                'place_id' => $place['id'],
                'seanse_id' => $res['id'],
                'bougth' => false,
                'user_id' => (new User())->id,
                'price' => Data::$ticket_price,
            ]);
        }
        return $res;
    }
    
    public static function lastbyuser(int $user_id){
        $today=Data::today();
        $data=Sale::where("sales.user_id=".$user_id." AND '".$today."'<=(SELECT seanses.date FROM seanses WHERE seanses.id=sales.seanse_id LIMIT 1) ORDER BY sales.date,sales.time DESC LIMIT 4");
        if(is_null($data)||empty($data)) return [];
        return array_map(function($item){
            return new Sale($item);
        },$data);
    }

    public static function historyByUser(int $user_id){
        //CHECK: 
        $today=date("Y-m-d");
        $data=DB::selectByQuery("SELECT sales.* FROM sales JOIN seanses ON seanses.id=sales.seanse_id WHERE sales.user_id=$user_id AND sales.date>='$today' ORDER BY date(seanses.date) DESC;");
        if(is_null($data)||empty($data)) return [];
        return array_map(function($item){
            $sale= new Sale($item);
            return $sale;
        },$data);
    }

    public static function removeSeanse(int $seanse_id){
        // if((new Seanse($seanse_id))->canBeRemoved()){
            return Seanse::delete("id=".$seanse_id);
        // }
        // else return false;
    }
    
}
