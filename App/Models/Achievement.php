<?php

namespace App\Models;

class Achievement extends Model
{
    static $table = 'achievements';
    
    public int $id;
    public string $title;
    public string $level_description;
    public string $description;
    public array $full_description;
    public int $number_for_goal;
    public int $discount;
    public string $triger;
    public string $image_title;
    public bool $is_blocked;

    public function __construct(int|array $data)
    {
        if (is_int($data)) {
            $data = self::find($data);
        }
        
        $this->id = $data['id'] ?? null;
        $this->title = $data['title'] ?? '';
        $this->level_description = $data['level_description'] ?? '';
        $this->description = $data['description']??"[]";
        $this->full_description = \json_decode($this->description,true);
        $this->number_for_goal = $data['number_for_goal'] ?? 0;
        $this->discount = $data['discount'] ?? '';
        $this->triger = $data['triger'] ?? '';
        $this->image_title = $data['image_title'] ?? '';
        $this->is_blocked = (bool)($data['is_blocked'] ?? 0);
    }
}
