<?php

namespace App\Services;

use App\Data;

use App\Models\Ticket;
use App\Models\Seanse;
use App\Models\Sale;

class TicketService 
{
    public static function generateTicketCode($sale_id,$place_id,$user_id=0)
    {
        if(is_null($user_id)) $user_id='0000';
        $cur_date = date('dmy'); 
        $ticket_code =$user_id.$sale_id.$place_id.$cur_date;
        return $ticket_code;

    }

    public static function returnTicket($id){
        $ticket=new Ticket($id);
        $res=Ticket::delete("id=".$id);
        $sale=Sale::find($ticket->sale_id);
        $newsum=$sale['sum']-$ticket->price;
        Sale::update("id=".$sale['id'],[
            "sum"=>$newsum
        ]);
        if(count(Ticket::where("sale_id=".$ticket->sale_id))==0){
            Sale::delete("id=".$ticket->sale_id);
        }
        return $res;
    }

    public static function basket(int $user_id): array
    {
        // Беремо всі продажі користувача з сьогоднішньої дати
        $sales = Sale::where("user_id=".$user_id." AND '".Data::today()."'<=(SELECT date FROM seanses WHERE id=sales.seanse_id LIMIT 1)");
        $tickets = [];

        foreach ($sales as $saleData) {
            $sale = new Sale($saleData["id"]);

            foreach($sale->tickets() as $ticket){
                $tickets[]=new Ticket($ticket['id']);
            }
        }

        return $tickets;
    }
    
    public static function checkticketkod($ticket_kod){
        $ticket=Ticket::where("ticket_kod='$ticket_kod'");
        return !empty($ticket);
    }
}

?>