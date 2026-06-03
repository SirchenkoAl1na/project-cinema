<?php

namespace App\Models;

use App\DB;
class Seanse extends Model
{
    public static $table = 'seanses';

    public int $id;
    public string $date;
    public string $time;
    public string $status;
    public bool $is_buing_blocked;

    public ?int $film_id;
    public ?int $hole_id;

    public Film $film;
    public Hole $hole;

    public function __construct(int|array $data)
    {
        if (is_int($data)) {
            $data = self::find($data);
        }

        $this->id = $data['id'] ?? null;
        $this->date = $data['date'] ?? '';
        $this->time = $data['time'] ?? '';
        $this->status = $data['status'] ?? '';
        $this->is_buing_blocked = !empty($data['is_buing_blocked']) ? (bool)$data['is_buing_blocked'] : false;
        $this->film_id = $data['film_id'] ?? null;
        $this->hole_id = $data['hole_id'] ?? null;

        if (!empty($data['film_id'])) {
            $this->film = new Film($data['film_id']);
        }
        if (!empty($data['hole_id'])) {
            $this->hole = new Hole($data['hole_id']);
        }
    }

    public function freeTickets()
    {
        $places=$this->hole->number_of_places ?? 0;
        $tickets=count($this->tickets());
        return $places-$tickets;
    }
    
    public function tickets(): array
    {
        if (!$this->hole_id || !isset($this->hole)) {
            return [];
        }
        $places = $this->hole->places();
        $places_res = [];
    
        foreach ($places as $row) {
            $new_row = [];
            foreach ($row as $place) {
                $is_bougth = Ticket::isBougth($place['row'], $place['place'], $this->id);
                $new_row[] = [
                    'id'        => $place['id'],
                    'row'       => $place['row'],
                    'place'     => $place['place'],
                    'is_bougth' => $is_bougth,
                    'seanse_id' => $this->id,   // ✅ замість new Seanse()
                ];
            }
            $places_res[] = $new_row;
        }
    
        return $places_res;
    }

    public function canBeRemoved():bool
    {
        $data=DB::selectByQuery("SELECT * FROM sales WHERE seanse_id={$this->id} LIMIT 1;");
        return empty($data);
    }
    
}
