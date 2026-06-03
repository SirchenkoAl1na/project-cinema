<?php

namespace App\Models;

use App\Models\User;
use App\Models\Achievement;
use App\Models\Sale;


class AchievementUser extends Model
{
     static $table = 'users_achievements';
     
     public int $id;
     public int $user_id;
     public int $achievement_id;
     public int $current_level;
     public bool $achieved;
     public string $date;
     public int $sale_id;

     public User $user;
     public Achievement $achievement;
     public Sale $sale;

     public function __construct(int|array $data)
     {
          if (is_int($data)) {
               $data = self::find($data);
          }

          $this->id = $data['id'] ?? null;
          $this->user_id = $data['user_id'] ?? null;
          $this->achievement_id = $data['achievement_id'] ?? null;
          $this->current_level = $data['current_level'] ?? null;
          $this->achieved = $data['achieved'] ?? null;
          $this->date = $data['date'] ?? null;
          $this->sale_id = $data['sale_id'] ?? null;
          
          
          $this->user = new User($data['user_id'] ?? 0);
          $this->achievement = new Achievement($data['achievement_id'] ?? 0);
          $this->sale = new Sale($data['sale_id'] ?? 0);
     }
}
