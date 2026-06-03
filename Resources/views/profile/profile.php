<?php
use App\Data;   
use App\Models\User;
?>
<div id="profile">

    <!-- reviews_last
seanses_last
achievements
basket -->
    <div class="block column" id="profile_info">
        <h3>Профіль:</h3>
        <div class="row">
            <div class="img">
                <img class="profile-photo" src="/Resources/img/users/<?php echo $user->photo; ?>" alt="<?php echo $user->photo; ?>">
            </div>
            <div class="column j-c-start" style="margin-top:10px;">
                <p><b>Прізвище та ім'я: </b><?= $user->full_name ?></p>
                <p><b>Логін: </b><?= $user->login ?></p>
                <p><b>Ел.адреса: </b><?= $user->email ?></p>
                <p><b>Номер тел.: </b><?= $user->phone ?></p>
                <p><b>Створено акаунт: </b><?= \App\Data::dateFormat($user->created_at) ?></p>
                <br/>
                <p><b>Бонуси:</b> <?= $user->discount ?> балів</p>
                <div class="row j-c-end"style="height:fit-content">
                    <button class="button button-icon" onclick="location.href='/profile/photo'" title="<?= $user->photo=='default.png'?'Завантажити фото':'Змінити фото' ?>">
                        <i class="fa-solid fa-camera"></i>
                    </button>
                    <button class="button button-icon" onclick="location.href='/profile/edit'" title="Редагувати профіль">
                        <i class="fa-solid fa-pen"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="block column j-c-start" id="profile_basket">
        <h3>Кошик:</h3>
        <?php
        if(!empty($basket)){
        ?>
        <div class="row j-c-start flex-wrap h-full">
        <?php 
            foreach($basket as $ticket){

                ?>
                <div class="basket-item">
                    <b><?= $ticket->sale->seanse->film->title ?></b>
                    <i><?= $ticket->qr_status=="scanned"?"(перевірено)":"" ?></i>
                    <p><?= \App\Data::datetimeFormat($ticket->sale->seanse->date,$ticket->sale->seanse->time) ?></p>
                    <p>Місце: <?= $ticket->place->row ?> ряд, <?=$ticket->place->place ?> місце</p>
                    <?php
                    $sum=\App\Data::$ticket_price;
                    if($ticket->sale->discount!=0){
                        $sum=round($sum*(100-($ticket->sale->discount/count($ticket->sale->tickets())))/100);
                    }
                    ?>
                    <p>Ціна: <?= $sum ?> грн</p>
                    
                    <div class="row">
                        <?php if ($ticket->qr_token): ?>
                        <button class="button button-icon" style="right:35px;" onclick="location.href='/profile/ticket/qr?id=<?=$ticket->id?>'" title="Показати QR код квитка">
                            <i class="fa-solid fa-qrcode"></i>
                        </button>
                        <?php endif; ?>
                        
                        <button class="button button-icon" onclick="generatePDF('<?= $ticket->sale->seanse->film->title ?>','<?= \App\Data::dateFormat($ticket->sale->seanse->date) ?>','<?= $ticket->sale->seanse->time ?>','<?= $ticket->sale->seanse->hole->nomer ?>','<?= $ticket->place->row ?>','<?= $ticket->place->place ?>','<?= $ticket->price ?>','<?= $ticket->ticket_kod ?>','<?= $ticket->qr_token ?>')" title="Друк">
                            <i class="fa-solid fa-print"></i>
                        </button>

                        <button class="button button-icon" onclick="returnTicket(<?=$ticket->id?>)" title="Видалити з кошика">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
                <?php
            }
        ?>
        </div>
        <?php
        } else {
            echo '<div class="empty-list"><p>Поки ви не маєте замовлених квитків</p></div>';
        }
        ?>
    </div><div id="qr_temp" style="display:none;"></div>
   
    <div class="block column" id="profile_achievements">
        <h3>Досягнення</h3>
        <?php
        if(!empty($achievements)){
        ?>
        <div class="row j-c-start flex-wrap" style="overflow-y:scroll">
        <?php 
        foreach($achievements as $achievement){
        ?>
        <div class="achievement-user-item"> 
            <img src="/Resources/img/achievements/<?=$achievement['achievement']->image_title?>" alt="<?=$achievement['achievement']->title?>" class="<?=$achievement['achieved']?'achieved':''?>">
            <i class="fa-solid fa-circle-info info-icon" title="<?=$achievement['achievement']->level_description?>"></i>
            <?php 
            if($achievement['achievement']->number_for_goal!=1){
                $goal=$achievement['achievement']->number_for_goal;
                $current=$achievement['current_level'];
                $percent=0;
                if($goal!=0) $percent=round($current/$goal*100);
            ?>
            <div class="achievement-progress-bar" title="<?=$current.'/'.$goal?>">
                <div class="achievement-progress-bar-current" style="width:<?=$percent?>%;"></div>
            </div>
            <?php 
            }
            ?>
            <p><?=$achievement['achievement']->title?></p>
            <i><?=$achievement['achievement']->discount?> %</i>
        </div>
        <?php 
        }
        ?>
            
        </div>
        <?php
        }else{
            ?>
            <div class="empty-list"><p>Поки ви не отримали досягнень</p></div> 
            <?php
        }
        ?>
    </div>
    <div class="block column" id="profile_history">
        <h3>Переглянуті фільми:</h3>
        <?php
        if(!empty($seanses_last)){
        ?>
        <div class="row j-c-start flex-wrap">
        <?php 
        foreach($seanses_last as $sale){
        ?>
        <div class="watched-film-item">
        <img src="/Resources/img/film_posters/<?= $sale->seanse->film->poster ?>" alt="<?= $sale->seanse->film->poster ?>">
            <h4 onclick="location.href='/profile/film?id=<?=$sale->seanse->film_id?>'"><?=$sale->seanse->film->title?></h4>
            <p>Дата: <?=Data::datetimeFormat($sale->seanse->date,$sale->seanse->time) ?><span></span></p>
            <div class="row achievemnts-list">
                <?php
                $achievements_list=$sale->getAchievements(); 
                if(!empty($achievements_list)){
                foreach($achievements_list as $achievement) { 
                ?>
                <img src="/Resources/img/achievements/<?=$achievement->image_title?>" title="<?=$achievement->title?>" alt="<?=$achievement->image_title?>">
                <?php 
                }
            }
                
                ?>
            </div>
        </div>
        <?php 
        }   ?>
        </div>
        <?php
        }else{
        ?>
        <div class="empty-list"><p>Переглянуті фільми відображаються лише після того як ви відвідаєте сеанс</p></div>     
        <?php
        }
        ?>   
    </div>
</div>

<script>
   
    function returnTicket(id){
        if(confirm("Ви дійсно хочете видалити квиток з кошика?")){
            const res=API.post('/api/tickets/returnbyuser',{
                id:id
            },'При поверненні виникла помилка! Спробуйте пізніше.');
            if(res!=false) {
                location.reload();
            }
        }
    }
    
</script>