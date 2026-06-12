<?php

namespace App\Controllers;

use App\Data;
use App\Models\Film;
use App\Models\User;
use App\Models\Review;
use App\Router;
use App\Services\FilmService;
use App\Services\SeanseService;

class FilmController extends Controller
{
    public function indexbyadmin($params)
    {
        $search = isset($params['search']) ? trim($params['search']) : '';
        $filter = isset($params['filter']) ? $params['filter'] : '';
        $sort = isset($params['sort']) ? $params['sort'] : '';

        $whereClauses = [];
        $orderBy = $sort!='by_title_desc'?'title ASC':'title DESC';
        

        if (!empty($search)) {
            $search = addslashes($search);
            $whereClauses[] = "title LIKE '{$search}%'";
        }

        $today = date('Y-m-d');

        switch ($filter) {
            case 'in_archive':
                // прокат завершився
                $whereClauses[] = 'end_date IS NOT NULL AND end_date < "' . $today . '"';
                break;
        
            case 'in_cinema':
                // зараз у кінотеатрі
                $whereClauses[] = 'primiere_date <= "' . $today . '" AND end_date >= "' . $today . '"';
                break;
        
            case 'wait_a_primiere':
                // прем'єра ще не відбулася
                $whereClauses[] = 'primiere_date > "' . $today . '"';
                break;
        }
        

        $queryParams = [];
        if (!empty($whereClauses)) {
            $queryParams['filter'] = implode(' AND ', $whereClauses);
        }
        if (!empty($orderBy)) {
            $queryParams['sort'] = $orderBy;
        }
        $films = Film::all($queryParams);
        
        $films = array_map(function ($item) {
            return new Film($item);
        }, $films);

        self::render('Фільми', '/admin/films', 'admin', [
            'films' => $films,
            'search' => $search,
            'filter' => $filter,
            'sort' => isset($params['sort']) ? $params['sort'] : '',
        ]);
    }

    public function historybyuser()
    {
        $user=new User();
        $seanses = SeanseService::historyByUser(Data::getId());
        self::render('Історія сеансів', 'profile/films_history', 'profile', [
            'user' => $user,
            'seanses' => $seanses,
        ]);
    }

    public function show($params)
    {
        $user = new User();
        $film_id = $params['id'];
        $film = new Film($film_id);
        $review_id=$params['review'] ?? null;
        $seanses = $film->futureSeansesByFilm();
        $reviews = [];
        foreach ($film->reviews() as $review) {
            $reviews[] = new Review($review);
        }
        self::render($film->title, 'profile/film_show', 'profile', [
            'user' => $user,
            'film' => $film,
            'reviews' => $reviews,
            'seanses' => $seanses,
            'review_id'=>$review_id
        ]);
    }

    public function showbycashier($params)
    {
        $user = new User();
        $film_id = $params['id'];
        $film = new Film($film_id);
        $review_id=$params['review'] ?? null;
        $seanses = $film->futureSeansesByFilm();
        $reviews = [];
        foreach ($film->reviews() as $review) {
            $reviews[] = new Review($review);
        }
        self::render("$film->title", 'cashier/film', 'cashier', [
            'user' => $user,
            'film' => $film,
            'reviews' => $reviews,
            'seanses' => $seanses,
            'review_id'=>$review_id
        ]);

    }

    public function createbyadmin()
    {
        $genres=[];
        foreach(Film::genresList() as $g){
            $genres[$g]=$g;
        }
        self::render('Додавання фільму', '/admin_form/film_add', 'admin',[
            'genres'=>$genres
        ]);
    }

    public function storefilmbyadmin($data)
    {
        FilmService::storeFilm($data);
        Router::redirect('/admin/films');
    }

    public function editbyadmin($params)
    {
        $genres=[];
        foreach(Film::genresList() as $g){
            $genres[$g]=$g;
        }
        self::render('Редагування фільму', '/admin_form/film_edit', 'admin', [
            'film' => new Film($params['id']),
            'genres'=>$genres,
        ]);
    }

    public function updatefilmbyadmin($params, $data)
    {
        $id = $params['id'];
        FilmService::updateFilm($id, $data);
        Router::redirect('/admin/films');
    }

    public function filmbyuser($params)
    {
        $id = $params['id'];
        $film = new Film($id);
        $user = new User();
        self::render($film->title, '/profile/film_show', 'profile', [
            'user' => $user,
            'film' => $film,
        ]);
    }
}
