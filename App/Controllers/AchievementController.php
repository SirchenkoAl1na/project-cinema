<?php

namespace App\Controllers;

use App\Models\Achievement;
use App\Data;
use App\Services\AchievementService;
use App\Router;
use App\Models\User;

class AchievementController extends Controller
{

    public function indexbyadmin()
    {
        $achievements = Achievement::all();
        //todo: get achievements from database
        self::render('Досягнення', '/admin/achievements', 'admin', [
            'achievements' => $achievements
        ]);
    }

    public function indexbyuser(){
        $user = new User();
        $achievements = AchievementService::collectionFull(Data::getId());
        self::render('Досягнення', '/profile/achievements', 'profile', [
            'user' => $user,
            'achievements' => $achievements
        ]);
    }

    public function createbyadmin()
    {
        self::render('Додавання досягнення', '/admin_form/achievement_add', 'admin');
    }
    
    public function storeachievementbyadmin($data)
    {
        AchievementService::storeAchievement($data);
        Router::redirect('/admin/achievements');
    }

    public function editbyadmin($params)
    {
        self::render('Додавання досягнення', '/admin_form/achievement_edit', 'admin',[
            'achievement' => new Achievement($params['id']),
        ]);
    }
    
    public function updateachievementbyadmin($params,$data)
    {
        AchievementService::updateAchievement($params['id'],$data);
        Router::redirect('/admin/achievements');
    }
    
    public function deletebyadmin($params){
        $id=$params['id'];
        Achievement::delete("id=".$id);
        Router::redirect('/admin/achievements');
    }
}
