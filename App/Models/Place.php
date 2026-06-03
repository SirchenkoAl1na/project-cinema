<?php

namespace App\Models;

class Place extends Model
{
    public static $table = 'places';

    public int $id;
    public string $row;
    public string $place;
    public int $hole_id;
    public string $type;
    public ?int $markup;

    public Hole $hole;

    public function __construct(int|array $data)
    {
        if (is_int($data)) {
            $data = self::find($data);
        }

        $this->id = $data['id'] ?? null;
        $this->row = $data['row'] ?? null;
        $this->place = $data['place'] ?? null;
        $this->hole_id = $data['hole_id'] ?? null;
        $this->type = $data['type'] ?? '';
        $this->markup = $data['markup'] ?? null;

        $this->hole = new Hole($data['hole_id'] ?? 0);
    }
}
