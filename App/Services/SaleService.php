<?php

namespace App\Services;

use App\Data;
use App\DB;
use App\Models\Place;
use App\Models\Seanse;
use App\Models\Sale;
use App\Models\Ticket;
use App\Models\User;

class SaleService
{
    public static function buyTickets($data,$user_id=null)
    {
       // stop if seanse buing is blocked
            $seanse = new Seanse($data['seanse_id']);
            if ($seanse->is_buing_blocked) {
                return false;
            }else{
                // блокування продажу квитків для сеансу
                Seanse::update('id='.$data['seanse_id'],[
                    'is_buing_blocked' => 1,
                ]);
            }
        $tickets=$data['tickets']??[];
        if(empty($tickets)){
            return false;
        }
        

        $price=Data::$ticket_price;
        $date=Data::today();//дата
        $time=Data::timeNow();//час

        $sum=$data['sum'];
        $discount=$data['discount'];

        $client_name=$data['client_name']??null;
        $client_phone=$data['client_phone']??null;
        $client_email=$data['client_email']??null;
        $user_have_data=!is_null($client_phone)||!is_null($client_email);
        $user=null;
        
        if(is_null($user_id)&&$user_have_data){
            $users=User::where("email='$client_email' OR phone='$client_phone'");
            if(empty($users)){
                $user=User::create([
                    'full_name'=>$client_name,
                    'email'=>$client_email,
                    'phone'=>$client_phone,
                    'role'=>'client',
                    'created_at'=>Data::today(),
                ]);
                $user=new User($user);
            }else{
                $user=new User($users[0]['id']);
                if(is_null($client_phone)){
                    User::update('id='.$user->id,[
                        'name'=>$client_name,
                        'phone'=>$client_phone,
                    ]);
                }else if(is_null($client_email)){
                    User::update('id='.$user->id,[
                        'name'=>$client_name,
                        'email'=>$client_email,
                    ]);

                }
            }
        }
        else if(!is_null($user_id)){
            $user=new User($user_id);
        }
        $client_id=!is_null($user)?$user->id:null;
        $employer_id=$data['employer_id']??null;
        $seanse=new Seanse($data['seanse_id']);

        $res=Sale::create([
            'date'=>$date,
            'time'=>$time,
            'sum'=>$sum,
            'discount'=>$discount,
            'user_id'=>$client_id,
            'employer_id'=>$employer_id,
            'seanse_id'=>$data['seanse_id'],
        ]);
        $sale_id=Sale::count();

        $ticket_ids = [];
        $qr_tokens  = [];

        foreach($tickets as $ticket){

            $ticket=(array)$ticket;
            $row=$ticket['row'];
            $place_=$ticket['place'];
            $hole_id=$seanse->hole_id;
            $places=Place::where("`row`='$row' AND place='$place_' AND hole_id=$hole_id");

            $place_id=null;
            if(!empty($places)){
               $place_id=$places[0]['id']; 
            }
            else{
                return false;
            }

            //якщо один з квитків вже куплений
            $ticket_exists = Ticket::where("
                place_id=$place_id
                AND sale_id IN (
                    SELECT id FROM sales WHERE seanse_id={$data['seanse_id']}
                )
            ");
            if(count($ticket_exists)!=0) return false;

            $qr_token = hash('sha256', uniqid('qr_', true) . random_bytes(8));

            Ticket::create([
                'price'=>$price,
                'place_id'=>$place_id,
                'sale_id'=>$sale_id,
                'ticket_kod'=>TicketService::generateTicketCode($sale_id, $place_id, $client_id),
                'qr_token' => $qr_token,
                'qr_status' => 'pending',
            ]);
            $new_ticket = Ticket::where("qr_token='$qr_token'");
            if (!empty($new_ticket)) {
                $ticket_ids[] = $new_ticket[0]['id'];
            }
            $qr_tokens[] = $qr_token;
        }
        if($discount!=0){
            User::update('id='.$client_id,[
                'discount'=>$user->discount-$discount,
            ]);
        }
        // AchievementService::checkAchievements($sale_id,$user->id??null);
        Seanse::update('id='.$data['seanse_id'],[
            'is_buing_blocked' => 0,
        ]);
        return [
            'sale_id'    => $sale_id,
            'ticket_ids' => $ticket_ids,
            'qr_tokens'  => $qr_tokens,
        ];
    }
}
