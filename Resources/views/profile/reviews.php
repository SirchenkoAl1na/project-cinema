<h1 class="page-title"><?=$title?></h1>
<div class="row" style="width:80%">
        
    <div class="block column j-c-start" id="reviews_my">
    <h2>Ваші відгуки:</h2>
            <?php 
            use App\Data;
            if(!empty($reviews)){
            foreach($reviews as $review){
            ?>
               
        <div class="review-item">
            <div class="info">
                <div class="row">
                    <i class="fa-solid fa-eye icon" title="Перейти до фільму" onclick="location.href='/profile/film?id=<?=$review->film_id?>&review=<?=$review->id?>'"></i>
                    <div class="column">
                        <div class="row">
                            <div class="column j-c-start">
                                 <div class="row j-c-start h-auto">
                                   <h4><?= !is_null($review->parent_comment_id)?$review->film->title."<div class='row'><i class='text-light' style='margin-left:0;margin-right:5px'>коментар у відповідь </i> ".$review->getParentComment()->user->full_name."</div>":$review->film->title ?></h4>
                                 </div>
                                <small> <?php echo Data::dateFormat($review->date); ?></small>
                            </div>
                            <div class="row">
                                <div class="buttons row a-c-center h-full">
                                    </div>
                            </div>
                        </div>
                        <div class="column">
                            <small>Оцінка: <?php echo $review->rating; ?> / 5</small>
                            <p><?php echo $review->comment; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <?php  
            }
            }else{
            ?>
            <div class="empty-list"><p>Поки ви не залишали відгуків</p></div>
            <?php 
            }
            ?>
    </div>
        <div class="block column" id="review_answers">
            <h2>Відгуки інших користувачів:</h2>
            <?php 
            if(!empty($reviews_answers)&&!empty($reviews_answers_new)){
            
                foreach($reviews_answers_new as $review){
                    ?>
                    <div class="review-item">
                        <div class="info">
                            <div class="row">
                                <i class="fa-solid fa-eye icon" title="Перейти до фільму" onclick="location.href='/profile/film?id=<?=$review->film_id?>&review=<?=$review->id?>'"></i>
                                <div class="column">
                                    <div class="row">
                                        <div class="column j-c-start">
                                             <div class="row j-c-start h-auto">
                                               <h4><?= !is_null($review->parent_comment_id)?$review->film->title."<div class='row'><i class='text-light' style='margin-left:0;margin-right:5px'>коментар у відповідь </i> ".$review->getParentComment()->user->full_name."</div>":$review->film->title ?></h4>
                                             </div>
                                            <small> <?php echo Data::dateFormat($review->date); ?></small>
                                        </div>
                                        <div class="row">
                                            <div class="buttons row a-c-center h-full">
                                                </div>
                                        </div>
                                    </div>
                                    <div class="column">
                                        <small>Оцінка: <?php echo $review->rating; ?> / 5</small>
                                        <p><?php echo $review->comment; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php  
                    }
                    
            
            }else{
            ?>
            <div class="empty-list"><p>Поки відповідей на ваші відгуки немає</p></div>
            <?php 
            }
            ?>
        </div>
</div>