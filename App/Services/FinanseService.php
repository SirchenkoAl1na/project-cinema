<?php

namespace App\Services;

use App\DB;
use App\Models\Film;
use App\Models\Sale;

class FinanseService
{
    public static function profitCurrentMonth()
    {
        $current_month_first_date = date('Y-m-01');
        $current_month_last_date = date('Y-m-t');

        $data=DB::selectByQuery("SELECT SUM(sum) FROM sales WHERE date BETWEEN '$current_month_first_date' AND '$current_month_last_date'");
        return $data[0]['SUM(sum)'] ?? 0;
    }
    
    public static function soldTicketsCurrentMonth()
    {
        $current_month_first_date = date('Y-m-01');
        $current_month_last_date = date('Y-m-t');

        $data=DB::selectByQuery("SELECT COUNT(tickets.id) as number FROM sales JOIN tickets ON tickets.sale_id=sales.id WHERE date BETWEEN '$current_month_first_date' AND '$current_month_last_date'");
        return $data[0]['number'] ?? 0;
    }
    
    public static function averageTicketPrice()
    {
        $current_month_first_date = date('Y-m-01');
        $current_month_last_date = date('Y-m-t');

        $data=DB::selectByQuery("SELECT AVG(sale_avg) as res
        FROM (
            SELECT sales.id, sales.sum / COUNT(tickets.id) AS sale_avg
            FROM sales
            JOIN tickets ON tickets.sale_id = sales.id
            WHERE date BETWEEN '$current_month_first_date' AND '$current_month_last_date'
            GROUP BY sales.id
        ) AS t;
        ");
        return isset($data[0]['res']) ? round($data[0]['res']) : 0;

    }
    
    public static function topFilms($limit=3)
    {
        $current_month_first_date = date('Y-m-01');
        $current_month_last_date = date('Y-m-t');
        $query="SELECT seanses.film_id as film_id, SUM(sales.sum) as ssum
        FROM seanses
        JOIN sales ON sales.seanse_id=seanses.id 
            WHERE seanses.date BETWEEN '$current_month_first_date' AND '$current_month_last_date'
        GROUP BY seanses.film_id 
        ORDER BY ssum DESC LIMIT $limit;";
        $data=DB::selectByQuery($query);

        $res=[];
        foreach($data as $item){
            $res[]=[
                'title'=>(new Film($item['film_id']))->title,
                'profit'=>$item['ssum'],
            ];
        }
        return $res;
    }

    public static function lastHalfYear(){
        
        $res=[];
        $months=[];
        
        $current_month_first_date = date('Y-m-01');
        $current_month_last_date = date('Y-m-t');
        for ($i=0; $i < 6; $i++) { 

            $data=DB::selectByQuery("SELECT SUM(sum) FROM sales WHERE date BETWEEN '$current_month_first_date' AND '$current_month_last_date'");
            
            if(!empty($data)){
                array_unshift($res,$data[0]['SUM(sum)']??0);
            }else{
                array_unshift($res,0);
            }
            array_unshift($months,date('F', strtotime($current_month_first_date)));

            $current_month_first_date = date('Y-m-01', strtotime('-1 month', strtotime($current_month_first_date)));
            $current_month_last_date = date('Y-m-t', strtotime('-1 month', strtotime($current_month_last_date)));
        }

        return [
            "months"=>$months,
            "data"=>$res,
        ];
    }
    
    public static function profitByDay(){
        $today=date('Y-m-d');
        $query="SELECT COUNT(s.id) as sales_count,COUNT(t.id) as tickets_count,SUM(s.sum) as sales_sum FROM sales as s JOIN tickets as t ON s.id=t.sale_id WHERE date='$today'";
        $online=DB::selectByQuery($query." AND s.employer_id IS NULL");//for online
        $by_cashier=DB::selectByQuery($query." AND s.employer_id IS NOT NULL");//for cashier

        $online_profit=$online[0]['sales_sum']??0;
        $cashier_profit=$by_cashier[0]['sales_sum']??0;
        
        $online=$online[0]['tickets_count']??0;
        $by_cashier=$by_cashier[0]['tickets_count']??0;

        return [
            'total_profit'=>$online_profit+$cashier_profit,
            'online_profit'=>$online_profit,
            'cashier_profit'=>$cashier_profit,

            'total_tickets'=>$online+$by_cashier,
            'online_tickets'=>$online,
            'cashier_tickets'=>$by_cashier,
        ];
    }


    public static function theMostPopularSeanse(){
        $today=date('Y-m-d');
        $query="SELECT s.id,COUNT(t.id) as tickets_count FROM sales as s JOIN tickets as t ON s.id=t.sale_id WHERE date='$today' GROUP BY s.seanse_id ORDER BY tickets_count DESC LIMIT 1";
        
        $data=DB::selectByQuery($query);
        $sale=new Sale($data[0]['id']??0);
        
        
        return [
            'film'=>!is_null($sale)?$sale->seanse->film->title:'',
            'time'=>!is_null($sale)?$sale->seanse->time:'',
            'tickets_sold'=>$data[0]['tickets_count']??0,
        ];
    }
    public static function currentMonthDetail(){
        return [];//TODO
    }
}
