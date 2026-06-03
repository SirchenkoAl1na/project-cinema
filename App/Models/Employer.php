<?php

namespace App\Models;

class Employer extends Model
{
     static $table = 'employee';
     public int $id;
     public string $shedule;
     public string $posada;
     public int $zarplata;
     public int $user_id;
     public User $user;
     public function __construct(int|array $data)
     {
          if (is_int($data)) {
               $data = self::find($data);
          }

          $this->id = $data['id'] ?? null;
          $this->shedule = $data['shedule'] ?? '';
          $this->posada = $data['posada'] ?? '';
          $this->zarplata = $data['zarplata'] ?? 0;
          $this->user_id = $data['user_id'] ?? '';
          
          $this->user = new User($data['user_id'] ?? 0);
     }
}
