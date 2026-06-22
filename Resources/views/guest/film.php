
<?php
    use App\Data;
?>
    <button onclick="location.href='/'" style="width:100px;left:0;">На головну</button></div>
<div id="film_info">
    <div id="poster" >
        <img src="/Resources/img/film_posters/<?= $film->poster ?>" alt="<?= $film->poster ?>">
    </div>
    <div id="info">
        
    <h4><?= htmlspecialchars($film->title) ?></h4>
                        <p><b>Жанри:</b> <?= !empty($film->genres) ? $film->genres : ' - '  ?> </p>
                        <p><b>Країна:</b> <?= !empty($film->country) ? $film->country : ' - '  ?> </p>
                        <p><b>Режисер:</b> <?= !empty($film->director) ? $film->director : ' - '   ?> </p>
                        <p><b>Актори:</b> <?= !empty($film->actors) ? implode(", ", $film->actors) : ' - '   ?> </p>
                        
                        <p><b>Опис:</b> <?= !empty($film->description) ? '<i>'.$film->description.'</i>' : ' - '   ?> </p>
    </div>
    <div id="seanses">
        <h2>Сеанси</h2>
        <!-- seanses -->
        <div id="seanses_on_film">
        <?php
        foreach ($seanses as $date => $group) {
            ?>
        <div class="seanses-on-day">
            <h4><?= Data::dateFormat($date) ?></h4>
            <div class="seanses-grid">
            <?php
            foreach ($group as $seanse) {
            ?>
                <div class="seanse-item" onclick="location.href='/seanse?seanse_id=<?= $seanse['id'] ?>'">
                    <a><?= $seanse['time'] ?></a>
                </div>
            <?php
            }
            ?>
            </div>
        </div>

        <?php
        }
        ?>
        </div>
    </div>
    <div id="reviews">
        <h2>Оцінки і відгуки:</h2>
        <?php
        $avg_r=$film->getAverageRating();
        ?>
        <h4 title="<?= $avg_r!=0?'':'Відсутня' ?>">Середня оцінка: <?= $avg_r==0?' - ':$avg_r ?></h4>
        <h4>Середня оцінка IMDB: <span id="imdb_rating"> - </span></h4>
        <!-- <div class="block column a-c-center" style="height:fit-content;">
        <div class="row">
            <textarea name="" style="width:100%" id="review_text" cols="100" rows="10" placeholder="Опишіть враження від перегляду..."></textarea>
        </div>
        <p id="review_alert" style="color:coral;width:100%;"></p>
        <div class="row">

            <input type="text" id="review_name" placeholder="Ім'я">
            <p id="rating_text">Ваша оцінка:</p>
                <div class="row j-c-start" style="padding:5px;" id="rating">
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                </div>

            <input type="text" id="review_ticketkod" placeholder="Код квитка"><div class="row" style="padding:6px"><i class="hint fa-solid fa-circle-question" title="Ви можете знайти його на вашому квитку"></i></div>
            
            <button class="button" style="width:fit-content;margin:0;" onclick="AddReview()">Залишити відгук</button>
        </div>

        </div> -->
<?php
    if(!empty($reviews)){
        
        foreach ($reviews as $review) {
        ?>
            <div class="review-item">
                <div class="info">
                    <div class="row">
                        <img src="/Resources/img/users/<?= $review->user->photo ?>" alt="<?= $review->user->photo ?>">
                        <div class="column">
                            <div class="row">
                                <div class="column j-c-start">
                                    
                                     <div class="row j-c-start h-auto">
                                       <h4><?= $review->user->full_name ?></h4>
                                     </div>
                                    <small> <?= Data::dateFormat($review->date) ?></small>
                                </div>
                                <div class="row">
                                    <div class="buttons row a-c-center h-full">
                                        </div>
                                </div>
                            </div>
                            <div class="column">
                                <small>Оцінка: <?= $review->rating ?> / 5</small>
                                <p><?= $review->comment ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php

        }   
}else{
    ?>
<div class="empty-list"><p>Поки що немає оцінок чи відгуків на фільм</p></div>     
    <?php
}
        ?>
    </div>
</div>

<script>
       let IMDbApiKey='5a9cada9';
    let film_imdb_id='<?php echo $film->imdb_id; ?>';
let span_imdb=document.getElementById('imdb_rating');

fetch('https://www.omdbapi.com/?i='+film_imdb_id+'&apikey='+IMDbApiKey)
.then(res=>res.json())
.then(data=>{
    if(data.Response=="True"){
        span_imdb.innerText=data.imdbRating;
    }else{
        span_imdb.innerText="Немає даних";
    }
})


</script>