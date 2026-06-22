<?php

namespace App\Models;

use App\DB;
use App\Data;
use App\Models\Place;
use App\Models\Sale;

class Ticket extends Model
{
    public static $table = 'tickets';

    public int $id;
    public int $price;
    public ?int $place_id;
    public ?int $sale_id;
    public string $ticket_kod;
    public ?string $qr_token;
    public string $qr_status;
    public ?string $scanned_at;
    public ?string $scanned_by_name;

    public Place $place;
    public Sale $sale;

    public function __construct(int|array $data)
    {
        if (is_int($data)) {
            $data = self::find($data);
        }

        $this->id = $data['id'];
        $this->price = $data['price'] ?? 0;
        $this->ticket_kod = $data['ticket_kod'] ?? 0;
        $this->place_id = $data['place_id'] ?? null;
        $this->sale_id = $data['sale_id'] ?? null;
        $this->qr_token = $data['qr_token'] ?? null;
        $this->qr_status = $data['qr_status'] ?? 'pending';
        $this->scanned_at = $data['scanned_at'] ?? null;
        $this->scanned_by_name = $data['scanned_by_name'] ?? null;

        $this->place = new Place($data['place_id'] ?? 0);
        $this->sale = new Sale($data['sale_id'] ?? 0);
    }

    public static function isBougth(int $row, int $place, int $seanse_id): bool
    {
        $place_id = Place::where("`row`='$row' AND place='$place' AND hole_id=(SELECT hole_id FROM seanses WHERE id=$seanse_id)")[0]['id'] ?? null;
        $res=DB::selectByQuery("SELECT t.* FROM tickets AS t JOIN sales as s ON s.id=t.sale_id WHERE s.seanse_id=$seanse_id AND place_id=$place_id;");
        return $res!=null?count($res)!=0:false;
    }

    public static function getTicket(int $row, int $place, int $seanse_id)
    {
        $seanse=new Seanse($seanse_id);
        $place_id = Place::where("`row`='$row' AND place='$place' AND hole_id=(SELECT hole_id FROM seanses WHERE id=$seanse_id)")[0]['id'] ?? null;
        if ($place_id==null) return null;
        $res=DB::selectByQuery("SELECT s.*,t.id as ticket_id FROM sales as s JOIN tickets AS t ON t.sale_id=s.id WHERE s.seanse_id=$seanse_id AND t.place_id=$place_id LIMIT 1;");
        if(empty($res)) return null;
        return new Ticket($res[0]['ticket_id'] ?? 0);
        
    }
    
    public function placeInfo():string
    {
        $place=$this->place;
        $row=$place->row;
        $place_number=$place->place;
        return "Ряд $row місце $place_number";
    }

    public function realPrice()
    {
        if ($this->sale->discount == 0) {
            return Data::$ticket_price;
        }
    
        $tickets_count = count($this->sale->tickets());
    
        if ($tickets_count == 0) {
            return 0;
        }
    
        $sales_sum = $tickets_count * Data::$ticket_price;
        $sales_sum -= ($sales_sum * $this->sale->discount) / 100;
    
        return round($sales_sum / $tickets_count, 2);
    }
}
