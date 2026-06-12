<?php

namespace App\Controllers;

use App\Data;
use App\Models\Seanse;
use App\Models\Ticket;
use App\Models\Hole;
use App\Models\Sale;
use App\Router;
use App\Services\SaleService;
use App\Services\TicketService;
use App\Services\UserService;
use App\Models\User;

class TicketController extends Controller
{
    public function sellbyuser(?array $param = null)
    {
        $user = new User();
        $seanse_id = $param['seanse_id'] ?? null;
        if (is_null($seanse_id)) {
            Router::redirect('/cashier');
        } else {
            $seanse = new Seanse($seanse_id);
            $film = $seanse->film ?? null;
            $tickets = $seanse->tickets();
            self::render('Продажа квитків', '/profile/ticket_buy', 'profile', [
            'user' => $user,
                'seanse' => $seanse,
                'film' => $film,
                'tickets' => $tickets,
                'hole' => !empty($seanse->hole) ? $seanse->hole : null,
                'date' => Data::date($seanse->date),
                'time' => $seanse->time,
                'user_id'=> (new User())->id,
                'ticket_price'=> Data::$ticket_price, 
            ]);
        }
    }

    public function sellbycashier(?array $param = null)
    {
        $seanse_id = $param['seanse_id'] ?? null;
        if (is_null($seanse_id)) {
            Router::redirect('/cashier');
        } else {
            $seanse = new Seanse($seanse_id);
            $film = $seanse->film ?? null;
            $tickets = $seanse->tickets();
            self::render('Продажа квитків', '/cashier/tickets_sell', 'cashier', [
                'seanse' => $seanse,
                'film' => $film,
                'tickets' => $tickets,
                'hole' => !empty($seanse->hole) ? $seanse->hole : null,
                'date' => Data::date($seanse->date),
                'time' => $seanse->time,
                'ticket_price'=> Data::$ticket_price, 
            ]);
        }
    }

    public function ticketprintbycashier(){
        $row= $_GET['row'] ?? null;
        $place= $_GET['place'] ?? null;
        $seanse_id= $_GET['seanse_id'] ?? null;


        $ticket = Ticket::getTicket($row,$place,$seanse_id);
        $sale= $ticket->sale;
        $tickets=$sale->tickets();
        $tickets=array_map(function($t){
            return new Ticket($t);
        }, $tickets);   
        self::render('Друк квитків', '/cashier/ticket_print', 'cashier', [
            'tickets'=>$tickets
        ]);
    }

    public function basket(){
        if (!isset($_SESSION['user'])) {
            Router::redirect('/login');
        }
        $user = new User();
        Router::redirect('/profile'); // Redirect directly to profile since basket is embedded there
        //$tickets = \App\Services\TicketService::basket($user->id);
        self::render('Кошик', '/profile/ticket_basket', 'profile', [
            'tickets' => $tickets,
            'ticket_price'=> Data::$ticket_price,
            'user' => $user
        ]);
    }

    public function historybycashier($params)
    {
        $filter = isset($params['filter']) ? $params['filter'] : '';
        $sort= isset($params['sort']) ? $params['sort'] : '';
        $sort_type= $sort=='date_asc'?'ASC':'DESC';
        $filter = str_replace('hole_', '', $filter);
        $hole_choosen=null;
        if($filter!=''){
            $hole_choosen=(int)$filter;
        }

        $today= date('Y-m-d');
        $holes=array_map(function($h){
            return new Hole($h);
        }, Hole::all());
        $sales=Sale::where("date='$today'","date $sort_type");
        $ticket_as_objects=[];
        foreach($sales as $sale){
            $sale=new Sale($sale);
            if($sale->seanse->hole->id==$hole_choosen||$hole_choosen==null){
                $tickets=Ticket::where('sale_id='.$sale->id);
                foreach($tickets as $ticket){
                    $ticket_as_objects[]=new Ticket($ticket);
                }
            }
        }
        self::render('Історія продажу квитків', '/cashier/tickets_history', 'cashier', [
            'tickets' => $ticket_as_objects,
            'hole_choosen'=>$hole_choosen,
            'sort'=>$sort,
            'holes'=>$holes
        ]);
    }

    public function ticketreturn(?array $params = null){
        $id= $param['id'] ?? null;

        if (is_null($id)) {
            Router::redirect('/profile');
        } else {
            $ticket = new Ticket($ticket_id);
            if ($ticket->sale->user_id != null) {
                $user = $ticket->sale->user;
                UserService::removeAchievement('buy_ticket');
            }
            TicketService::returnTicket($ticket_id);
            Router::redirect('/profile');
        }
    }

    public function APIcheckticketkod(?array $params){
        try {
            $res = TicketService::checkticketkod($params);
            echo json_encode([
                'status' => 1,
                'message' => $res,
            ]);
        } catch (\Exception $e) {
            echo json_encode([
                'status' => 0,
                'message' => $e->getMessage(),
            ]);
        } 
    }

    public function APIbuytickets(array|string $data)
    {
        try {
            $res = SaleService::buyTickets($data,$data['user_id']??null);

            echo json_encode([
                'status' => 1,
                'message' => $res,
            ]);
        } catch (\Exception $e) {
            echo json_encode([
                'status' => 0,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function showQr(?array $param = null): void {
        $ticket_id = $param['id'] ?? null;
        if (!$ticket_id) Router::redirect('/profile');

        $ticket = new Ticket((int)$ticket_id);
        // Перевіряємо що квиток належить поточному користувачу
        if ($ticket->sale->user_id != $_SESSION['user']['id']) {
            Router::redirect('/profile');
        }
        $user = new User();
        self::render('QR-квиток', '/profile/ticket_qr', 'profile', [
            'ticket' => $ticket,
            'user' => $user
        ]);
    }

    public function renderQrImage(?array $param = null): void
    {
        $token = $param['token'] ?? '';
        if (empty($token)) {
            http_response_code(400);
            echo 'Token missing';
            return;
        }
        header('Content-Type: image/svg+xml; charset=utf-8');
        try {
            $options = new \chillerlan\QRCode\QROptions([
                'outputInterface' => \chillerlan\QRCode\Output\QRMarkupSVG::class,
                'eccLevel'        => \chillerlan\QRCode\Common\EccLevel::H,
                'svgAddXmlHeader' => false,
                'outputBase64'    => false,
            ]);
            echo (new \chillerlan\QRCode\QRCode($options))->render($token);
        } catch (\Exception $e) {
            http_response_code(500);
            echo 'QR error: ' . htmlspecialchars($e->getMessage());
        }
    }

    public function APIreturnbyuser($params)
    {
        try {
            $ticket_id = $params['id'] ?? null;
            if (is_null($ticket_id)) {
                echo json_encode([
                    'status' => 0,
                    'message' => "Не вказано id квитка",
                ]);
                return;
            }
            $ticket = new Ticket($ticket_id);
            if ($ticket->sale->user_id != null) {
                $user = $ticket->sale->user;
                UserService::removeAchievement('buy_ticket');
            }
            $res = TicketService::returnTicket($ticket_id);
            echo json_encode([
                'status' => 1,
                'message' => $res,
            ]);
        } catch (\Exception $e) {
            echo json_encode([
                'status' => 0,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function returnbycashier(array $params)
    {
        $ticket_id = $params['id'] ?? null;
        if (is_null($ticket_id)) {
            Router::redirect('/cashier');
        } else {
            $ticket = new Ticket($ticket_id);
            if ($ticket->sale->user_id != null) {
                $user = $ticket->sale->user;
                UserService::removeAchievement('buy_ticket');
            }
            TicketService::returnTicket($ticket_id);
            Router::redirect('/cashier/tickets');
        }
    }
}
