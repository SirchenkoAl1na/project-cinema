<?php

namespace App\Models;

use App\Data;
use App\DB;
use App\Models\User;
use App\Models\Seanse;
use App\Models\Employer;
use App\Services\SeanseService;



class Sale extends Model
{
    public static $table = 'sales';

    public int $id;
    public string $date;
    public string $time;
    public int $sum;
    public int $discount;

    public int|null $user_id;
    public int $seanse_id;
    public int|null $employer_id;

    public User $user;
    public Seanse $seanse;
    public Employer $employer;

    public bool $canBeReturned;

    public function __construct(int|array $data)
    {
        if (is_int($data)) {
            $data = self::find($data);
        }
        

        $this->id = $data['id'] ?? null;
        $this->date = $data['date'] ?? '';
        $this->time = $data['time'] ?? '';
        $this->sum = $data['sum'] ?? 0;
        $this->discount = $data['discount'] ?? 0;

        $this->user_id = $data['user_id'];
        $this->seanse_id = $data['seanse_id'];
        $this->employer_id = $data['employer_id'] ?? null;
        $this->canBeReturned=$this->canBeReturned();

        $this->user = new User($this->user_id);
        $this->seanse = new Seanse($this->seanse_id);
        if(!is_null($this->employer_id)) $this->employer = new Employer($data['employer_id'] ?? 0);
    }

    public function tickets(){
        return Ticket::where("sale_id=".$this->id);
    }

    public function dateAndTime(){
        return Data::datetimeFormat($this->date, $this->time);
    }

    private function canBeReturned(){
        $current_date=Data::today();
        if(strtotime($this->date)>=strtotime($current_date)) return false;
        return true;
    }
    public function getAchievements(){
        //TODO
        return [];
    }

}
