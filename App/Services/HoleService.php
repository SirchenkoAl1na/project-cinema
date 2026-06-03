<?php

namespace App\Services;

use App\Data;
use App\DB;
use App\Models\Place;
use App\Models\Hole;
use App\Models\User;

class HoleService
{
    public static function storeHole($data)
    {
        $nomer=$data['nomer'];
        $status=$data['status']!=""?$data['status']:'відкритий';
        $places=explode(',',$data['hole_places']);

        $number_of_places=0;
        foreach ($places as $number_of_places_in_row) {
            $number_of_places+=$number_of_places_in_row;
        }
        $res=Hole::create([
            'nomer' => $nomer,
            'number_of_places' => $number_of_places,
            'status' => $status,
        ]);
        $hole_id=Hole::count();

        foreach ($places as $row_index => $number_of_places_in_row) {
            for ($i=0; $i < $number_of_places_in_row; $i++) { 
                Place::create([
                    'row'=>$row_index,
                    'place'=>$i,
                    'hole_id'=>$hole_id,
                    'type'=>null,
                    'markup'=>null,
                ]);
            }
        }

        return $res;
    }
    
    public static function updateHole($hole_id,$data)
    {
        $nomer=$data['nomer'];
        $status=$data['status']!=""?$data['status']:'відкритий';
        $places=explode(',',$data['hole_places']);

        $number_of_places=0;
        foreach ($places as $number_of_places_in_row) {
            $number_of_places+=$number_of_places_in_row;
        }
        $res=Hole::update("id=".$hole_id,[
            'nomer' => $nomer,
            'number_of_places' => $number_of_places,
            'status' => $status,
        ]);

        Place::delete('hole_id='.$hole_id);
        foreach ($places as $row_index => $number_of_places_in_row) {
            for ($i=0; $i < $number_of_places_in_row; $i++) { 
                Place::create([
                    'row'=>$row_index,
                    'place'=>$i,
                    'hole_id'=>$hole_id,
                    'type'=>null,
                    'markup'=>null,
                ]);
            }
        }

        return $res;
    }
}