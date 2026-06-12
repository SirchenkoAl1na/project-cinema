<?php

namespace App\Models;

use Dom\Comment;

class Review extends Model
{

     static $table='reviews';

     public int $id;
     public int $user_id;
     public int $film_id;
     public string $rating;
     public string $comment;
     public string $date;
     public bool $visible;
     public bool $is_blocked;
     public ?int $parent_comment_id = null;
     public User $user;
     public Film $film;

     public function __construct(int|array $data)
     {
          if (is_int($data)) {
               $data = self::find($data);
          }

          $this->id = $data['id'];
          $this->user_id = $data['user_id'] ?? null;
          $this->film_id = $data['film_id'] ?? null;
          $this->rating = $data['rating'] ?? null;
          $this->comment = $data['comment'] ?? null;
          $this->date = $data['date'] ?? null;
          $this->visible = $data['visible'] ?? false;
          $this->is_blocked = $data['is_blocked'] ?? false;
          $this->parent_comment_id = $data['parent_comment_id'] ?? null;

          $this->user = new User($this->user_id);
          $this->film = new Film($this->film_id);
     }
     
     public function getParentComment(): Review|null
     {
          return new Review($this->parent_comment_id);
     }
     
     public function getChildComments(): array|null
     {
          //TODO
          return [];
     }
}
