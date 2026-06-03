<?php

namespace App\Models;

use App\DB;

class Hole extends Model
{
    public static $table = 'holes';

    public int $id;
    public string $nomer;
    public int $number_of_places;
    public string $status;

    public function __construct(int|array $data)
    {
        if (is_int($data)) {
            $data = self::find($data);
        }
        // todo
        $this->id = $data['id'] ?? null;
        $this->nomer = $data['nomer'] ?? null;
        $this->number_of_places = $data['number_of_places'] ?? null;
        $this->status = $data['status'] ?? null;
    }

    public function places()
    {
        $hole_id = $this->id;
        $places = DB::selectByQuery('SELECT COUNT(place) as count FROM places WHERE hole_id='.$hole_id.' GROUP BY `row` ORDER BY `row`;');
        $places_res = [];
        for ($i = 0; $i < count($places); $i++) {
            $places_on_row = [];
            for ($j = 0; $j < $places[$i]['count']; $j++) {
                $r=$i+1;
                $p=$j+1;
                $place_id=DB::selectOne("places","id","hole_id=$hole_id AND `row`=$r AND place=$p")['id'] ?? null;
                $places_on_row[] =[
                    'id' => $place_id,
                    'row'=> $r,
                    'place' => $p,
                ];
            }
            $places_res[] = $places_on_row;
        }

        return $places_res;
    }

    public function placesSimple(){
        $hole_id = $this->id;
        $places = DB::selectByQuery('SELECT COUNT(place) as count FROM places WHERE hole_id='.$hole_id.' GROUP BY `row` ORDER BY `row`;');
        $places_res = [];
        for ($i = 0; $i < count($places); $i++) {
            $places_on_row = [];
            for ($j = 0; $j < $places[$i]['count']; $j++) {
                $r=$i+1;
                $p=$j+1;
                $places_on_row[] = $p;
            }
            $places_res[] = $places_on_row;
        }

        return $places_res;
    }

    public function rows()
    {
        return array_column(DB::selectByQuery('SELECT count(place) as row_count FROM places WHERE hole_id='.$this->id.' GROUP BY `row` ORDER BY `row`;'), 'row_count');
    }

    public function seansesOnDay(): array
    {
        $today = date('Y-m-d');
        $hole_id = $this->id;

        $seanses = DB::selectByQuery(
            "SELECT seanses.*, f.title as film_title,f.duration as duration
            FROM seanses
            JOIN films as f ON f.id = seanses.film_id
            WHERE seanses.hole_id=$hole_id
            AND seanses.date = '$today'
            ORDER BY STR_TO_DATE(seanses.time, '%H:%i')"
        );

        $seanses_res = [];
        foreach ($seanses as $seanse) {
            $seanses_res[] = [
                'id' => $seanse['id'],
                'time' => $seanse['time'],
                'date' => $seanse['date'],
                'film_title' => $seanse['film_title'],
                'hole_id' => $seanse['hole_id'],
                'duration' => $seanse['duration'],
            ];
        }

        return $seanses_res;
    }

    public function currentSeanse()
    {
        $today = date('Y-m-d');
        $currentTime = date('H:i:s');
        $hole_id = (int)$this->id;

        $seanses = self::seansesOnDay();

        foreach ($seanses as $seanse) {

            $startTime = strtotime($seanse['date'].' '.$seanse['time']);
            $endTime = $startTime + ($seanse['duration'] * 60);
            $now = time();
            if ($now >= $startTime && $now <= $endTime) {
                return [
                    'id' => $seanse['id'],
                    'time' => $seanse['time'],
                    'film_title' => $seanse['film_title'],
                    'hole_id' => $seanse['hole_id'],
                ];
            }
        }

        return null;
    }
}
