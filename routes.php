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

//--------------------------------------------------guest--------------------------------------------------
$this->get('/', [GuestController::class, "seanses"], UserType::NOTLOGINED);
$this->get('/seanses', [GuestController::class, "seanses"], UserType::NOTLOGINED);
$this->get('/film', [GuestController::class, "film"], UserType::NOTLOGINED);
$this->get('/seanse', [GuestController::class, "seanse"], UserType::NOTLOGINED);
$this->post('/review', [GuestController::class, "writereview"], UserType::NOTLOGINED);

//auth
$this->get('/login', [UserController::class, "login"], UserType::NOTLOGINED);
$this->get('/register', [UserController::class, "register"], UserType::NOTLOGINED);
$this->post('/signin', [UserController::class, "signin"], UserType::NOTLOGINED);
$this->post('/signup', [UserController::class, "signup"], UserType::NOTLOGINED);
$this->get('/logout', [UserController::class, "logout"]);

//--------------------------------------------------user--------------------------------------------------
//get
$this->get('/profile', [UserController::class, "profile"], UserType::LOGINED);
$this->get('/profile/seanses', [SeanseController::class, "indexbyuser"], UserType::LOGINED);
$this->get('/profile/achievements', [AchievementController::class, "indexbyuser"], UserType::LOGINED);
$this->get('/profile/films/history', [FilmController::class, "historybyuser"], UserType::LOGINED);
$this->get('/profile/reviews', [ReviewController::class, "indexbyuser"], UserType::LOGINED);
$this->get('/profile/basket', [TicketController::class, "basket"], UserType::LOGINED);
$this->get('/profile/ticket/qr', [TicketController::class, 'showQr'], UserType::LOGINED); // QR-квиток
$this->get('/profile/ticket/qr-image', [TicketController::class, 'renderQrImage']);       // SVG-картинка QR (без авт.)
$this->get('/profile/photo',[UserController::class,"loadphoto"], UserType::LOGINED);
$this->get('/profile/edit', [UserController::class, "edit"], UserType::LOGINED);
//show
$this->get('/profile/film', [FilmController::class, "show"], UserType::LOGINED);
//create
$this->get('/profile/tickets/sell', [TicketController::class, "sellbyuser"], UserType::LOGINED);
//store
$this->post('/profile/reviews/store', [ReviewController::class, "storebyuser"], UserType::LOGINED);
$this->post('/profile/ticket/buy', [TicketController::class, "ticketbuy"], UserType::LOGINED);
$this->post('/profile/ticket/return', [TicketController::class, "ticketreturn"], UserType::LOGINED);
$this->post('/profile/photo',[UserController::class,"storephoto"], UserType::LOGINED);
$this->post('/profile/store', [UserController::class, "storeprofile"], UserType::LOGINED);
//edit
$this->post('/profile/edit', [ReviewController::class, "editbyuser"], UserType::LOGINED);
//update
$this->post('/profile/update', [ReviewController::class, "updatebyuser"], UserType::LOGINED);
//delete


//--------------------------------------------------admin(-panel)--------------------------------------------------
$this->get('/admin', [SeanseController::class, "indexbyadmin"], UserType::ADMIN);
$this->get('/admin/seanses', [SeanseController::class, "showbyadmin"], UserType::ADMIN);
$this->get('/admin/statistics', [UserController::class, "statistics"], UserType::ADMIN);
$this->get('/admin/holes', [HoleController::class, "indexbyadmin"], UserType::ADMIN);
$this->get('/admin/films', [FilmController::class, "indexbyadmin"], UserType::ADMIN);
$this->get('/admin/achievements', [AchievementController::class, "indexbyadmin"], UserType::ADMIN);
$this->get('/admin/scanner', [ScannerController::class, "index"], UserType::ADMIN);
// ..reviews
$this->get('/admin/reviews', [ReviewController::class, "indexbyadmin"], UserType::ADMIN);
$this->get('/admin/employee', [EmployerController::class, "indexbyadmin"], UserType::ADMIN);
$this->get('/admin/profile', [UserController::class, "profileforadmin"], UserType::ADMIN);
//show 
$this->get('/admin/employee/show',[EmployerController::class,"showbyadmin"],UserType::ADMIN);
$this->get('/admin/films/show',[FilmController::class,"showbyadmin"],UserType::ADMIN);
//create
$this->get('/admin/seanses/create', [SeanseController::class, "createbyadmin"], UserType::ADMIN);
$this->get('/admin/holes/create', [HoleController::class, "createbyadmin"], UserType::ADMIN);
$this->get('/admin/films/create', [FilmController::class, "createbyadmin"], UserType::ADMIN);
$this->get('/admin/achievements/create', [AchievementController::class, "createbyadmin"], UserType::ADMIN);
$this->get('/admin/employee/create', [EmployerController::class, "createbyadmin"], UserType::ADMIN);
$this->get('/admin/employee/new-password', [EmployerController::class, "newpasswordbyadmin"], UserType::ADMIN);
//store
$this->post('/admin/seanses/store', [SeanseController::class, "storeseansebyadmin"], UserType::ADMIN);
$this->post('/admin/holes/store', [HoleController::class, "storeholebyadmin"], UserType::ADMIN);
$this->post('/admin/films/store', [FilmController::class, "storefilmbyadmin"], UserType::ADMIN);
$this->post('/admin/achievements/store', [AchievementController::class, "storeachievementbyadmin"], UserType::ADMIN);
$this->post('/admin/employee/store', [EmployerController::class, "storeemployerbyadmin"], UserType::ADMIN);
//edit
$this->get('/admin/holes/edit', [HoleController::class, "editbyadmin"], UserType::ADMIN);
$this->get('/admin/films/edit', [FilmController::class, "editbyadmin"], UserType::ADMIN);
$this->get('/admin/achievements/edit', [AchievementController::class, "editbyadmin"], UserType::ADMIN);
$this->get('/admin/employee/edit', [EmployerController::class, "editbyadmin"], UserType::ADMIN);
$this->get('/admin/employee/new-password', [EmployerController::class, "newpasswordbyadmin"], UserType::ADMIN);
//update
$this->put('/admin/holes/update', [HoleController::class, "updateholebyadmin"], UserType::ADMIN);
$this->put('/admin/films/update', [FilmController::class, "updatefilmbyadmin"], UserType::ADMIN);
$this->put('/admin/achievements/update', [AchievementController::class, "updateachievementbyadmin"], UserType::ADMIN);
$this->put('/admin/employee/update', [EmployerController::class, "updateemployerbyadmin"], UserType::ADMIN);
$this->put('/admin/employee/new-password/save', [EmployerController::class, "savenewpasswordbyadmin"], UserType::ADMIN);
//delete&hide
$this->get('/admin/seanses/delete', [SeanseController::class, "deletebyadmin"], UserType::ADMIN);
$this->get('/admin/holes/delete', [HoleController::class, "deletebyadmin"], UserType::ADMIN);
$this->get('/admin/films/delete', [FilmController::class, "deletebyadmin"], UserType::ADMIN);
$this->get('/admin/employee/delete', [EmployerController::class, "deletebyadmin"], UserType::ADMIN);//???
$this->get('/admin/achievements/delete', [AchievementController::class, "deletebyadmin"], UserType::ADMIN);
$this->get('/admin/reviews/block', [ReviewController::class, "blockbyadmin"], UserType::ADMIN);
$this->get('/admin/reviews/unblock', [ReviewController::class, "unblockbyadmin"], UserType::ADMIN);

//--------------------------------------------------cashier--------------------------------------------------

$this->get('/cashier', [SeanseController::class, "indexbycashier"],  UserType::CASHIER);
$this->get('/cashier/seanses',  [SeanseController::class, "indexbycashier"], UserType::CASHIER);
$this->get('/cashier/film',  [FilmController::class, "showbycashier"], UserType::CASHIER);
$this->get('/cashier/tickets/sell', [TicketController::class, "sellbycashier"], UserType::CASHIER);
$this->get('/cashier/statistics', [UserController::class, "statisticsbycashier"], UserType::CASHIER);
$this->get('/cashier/tickets',  [TicketController::class, "historybycashier"], UserType::CASHIER);
$this->get('/cashier/holes',    [HoleController::class,   "holesbycashier"],   UserType::CASHIER);

$this->get('/cashier/tickets/print', [TicketController::class, "ticketprintbycashier"], UserType::CASHIER);
$this->post('/cashier/tickets/sell', [TicketController::class, "sellbycashier"], UserType::CASHIER);
$this->post('/cashier/tickets/sell', [TicketController::class, "sellbycashier"], UserType::CASHIER);
$this->get('/cashier/tickets/return', [TicketController::class, "returnbycashier"], UserType::CASHIER);

