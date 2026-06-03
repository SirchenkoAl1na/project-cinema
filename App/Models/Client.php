<?php

namespace App\Models;

class Client extends Model
{
     static $table='clients';
     
    public int $id;
    public string $full_name;

     public function __construct(int|array $data)
      {
           if (is_int($data)) {
                $data = self::find($data);
           }
         //todo
         }
}
