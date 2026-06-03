<?php

namespace App\Controllers;

use App\Models\Review;
use App\Data;
use App\Router;
use App\Services\ReviewService;
use App\Models\User;


class ReviewController extends Controller
{
    public function indexbyadmin($params)
    {
        $search = isset($params['search']) ? trim($params['search']) : '';
        $filter = isset($params['filter']) ? $params['filter'] : '';
        $filter2 = isset($params['filter2']) ? $params['filter2'] : '';
        $sort = isset($params['sort']) ? $params['sort'] : '';
        $whereClauses = [];
        // search
        if (!empty($search)) {
            $search = addslashes($search);
            if(Data::isDate($search)) $search_date=date("Y-m-d",strtotime($search));
            $whereClauses[] = "reviews.id IN (SELECT r.id FROM reviews as r JOIN films as f ON f.id=r.film_id JOIN users as u ON u.id=r.user_id WHERE r.comment LIKE '%{$search}%' OR f.title LIKE '{$search}%' OR r.date LIKE '{$search}%' OR u.full_name LIKE '{$search}%')";
        }
        // filter
        if($filter=="high") $whereClauses[] = "reviews.rating>=4.5";
        else if($filter=="middle") $whereClauses[] = "reviews.rating BETWEEN 3 AND 4.5";
        else if($filter=="low") $whereClauses[] = "reviews.rating BETWEEN 1.5 AND 3";
        else if($filter=="lowest") $whereClauses[] = "reviews.rating<1.5";
        //filter : block and not block
        if($filter2=="") $whereClauses[] = "reviews.is_blocked=false OR reviews.is_blocked IS NULL";
        else if($filter2=="is_blocked") $whereClauses[] = "reviews.is_blocked=true";
        //sorting
        if($sort=='') $sort='by_film_asc';
        $sortParams=explode("_",$sort);
        if($sortParams[1]=='film') $sort="f.title";
        else if($sortParams[1]=='date') $sort="date";
        else if($sortParams[1]=='rating') $sort="rating";

        if($sortParams[2]=="asc") $sort.=" ASC";
        else if($sortParams[2]=="desc") $sort.=" DESC";
        
        // join all params
        $queryParams=[];
        if (!empty($whereClauses)) $queryParams['filter'] = implode(' AND ', $whereClauses);
        if (!empty($sort)) $queryParams['sort'] = $sort;
    
        $reviews=Review::all($queryParams,'reviews.*','JOIN films as f ON f.id=reviews.film_id');

        $reviews = array_map(function ($item) {
            if ($item['visible']) return new Review($item['id']);
        }, $reviews);
        //TODO: add pagination
        self::render('Оцінки та відгуки', '/admin/reviews', 'admin', [
            'reviews' => $reviews,
            'search'=>$search,
            'filter'=>$filter,
            'filter2'=>$filter2,
            'sort'=>isset($params['sort']) ? $params['sort'] : '',
        ]);
    }

    public function indexbyuser()
    {
        //TODO:
        $user = new User();
        $user_id=Data::getId();
        $reviews=ReviewService::listByUser($user_id);
        $reviews_answers_new=ReviewService::listAnswersNewByUser($user_id);
        $reviews_answers=ReviewService::listAnswersByUser($user_id);

        self::render('Історія відгуків', '/profile/reviews', 'profile', [
            'user' => $user,
            'reviews' => $reviews,
            'reviews_answers_new' => $reviews_answers_new,
            'reviews_answers' => $reviews_answers,
        ]);
    }

    public function APIaddreview($params){
        try {
            $res = ReviewService::createbyregistereduser($params);
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

    public function APIaddreviewbyguest($params){
        try {
            $res = ReviewService::createbyguest($params);
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
    public function blockbyadmin($params)
    {
        $id=$params['id'];
        Review::update("id=".$id,[
            'is_blocked'=>true,
        ]);
        Router::redirect('/admin/reviews');
    }

    public function unblockbyadmin($params)
    {
        Review::update("id=".$id,[
            'is_blocked'=>false,
        ]);
        Router::redirect('/admin/reviews');
    }
}
