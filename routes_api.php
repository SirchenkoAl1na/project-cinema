<?php


use App\Controllers\AdminController;
use App\Controllers\UserController;
use App\Controllers\HoleController;
use App\Controllers\FilmController;
use App\Controllers\TicketController;
use App\Controllers\AchievementController;
use App\Controllers\ReviewController;

use App\Controllers\EmployerController;
use App\Controllers\SeanseController;
use App\Controllers\GuestController;
use App\Controllers\ScannerController;
use App\UserType;

// $this->get('/api', [UserController::class, "login"], UserType::NOTLOGINED);//example
$this->post('/api/tickets/buy', [TicketController::class, "APIbuytickets"]);
$this->get('/api/clients/find', [UserController::class, "APIfindclient"]);
$this->get('/api/check_ticketkod', [TicketController::class, "APIcheckticketkod"]);
$this->post('/api/add_review',[ReviewController::class, "APIaddreviewbyguest"]);
$this->post('/api/tickets/returnbyuser',[TicketController::class, "APIreturnbyuser"], UserType::LOGINED);
$this->post('/api/reviews/add', [ReviewController::class, "APIaddreview"]);
$this->post('/admin/scanner/validate', [ScannerController::class, "validate"], UserType::ADMIN);
$this->post('/api/seanses/remove',[SeanseController::class,"APIremoveseanse"],UserType::ADMIN);
$this->get('/api/seanses/tickets', [SeanseController::class, "APIseansetickets"]);
