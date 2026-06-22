
<?php
           use App\Data;
                ?>
                
                <div id="film_info">
    <div id="poster" >
        <img src="/Resources/img/film_posters/<?php echo $film->poster; ?>" alt="<?php echo $film->poster; ?>">
    </div>
    <div id="info">
        
    <h4><?php echo htmlspecialchars($film->title); ?></h4>
                        <p><b>Жанри:</b> <?php echo !empty($film->genres) ? $film->genres : ' - '; ?> </p>
                        <p><b>Країна:</b> <?php echo !empty($film->country) ? $film->country : ' - '; ?> </p>
                        <p><b>Режисер:</b> <?php echo !empty($film->director) ? $film->director : ' - '; ?> </p>
                        <p><b>Актори:</b> <?php echo !empty($film->actors) ? implode(', ', $film->actors) : ' - '; ?> </p>
                        
                        <p><b>Опис:</b> <?php echo !empty($film->description) ? '<i>'.$film->description.'</i>' : ' - '; ?> </p>
    </div>
    <div id="seanses">
        <h2>Сеанси</h2>
        <div id="seanses_on_film">


            <?php
            if(!empty($seanses)){
            foreach ($seanses as $date => $group) {
                ?>
            <div class="seanses-on-day">
                <h4><?= Data::dateFormat($date) ?></h4>
                <div class="seanses-grid">
                <?php
                foreach ($group as $seanse) {
                ?>
                    <div class="seanse-item" onclick="location.href='/profile/tickets/sell?seanse_id=<?= $seanse['id'] ?>'">
                        <a><?= $seanse['time'] ?></a>
                    </div>
                <?php
                }
                ?>
                </div>
            </div>

            <?php
            }}else{
                ?>
                <div class="empty-list"><p>На цей фільм немає сеансів/p></div>
                <?php
            }
        ?>
        </div>
    </div>
    <div id="reviews">
        <h2>Оцінка і відгуки:</h2>
        <?php
        $avg_r=$film->getAverageRating();
        ?>
        <h4 title="<?= $avg_r!=0?'':'Відсутня' ?>">Середня оцінка: <?= $avg_r==0?' - ':$avg_r ?></h4>
        <h4>Середня оцінка IMDB: <span id="imdb_rating"> - </span></h4>
        <div class="block column" style="height:fit-content;">
            <div class="row">
                <div id="answer">
                    <p></p>
                </div>
            </div>
            <div class="row">
                <textarea name="review" style="width:100%" id="" cols="100" rows="10" placeholder="Опишіть враження від перегляду..." onchange="toggleReview(this.value)"></textarea>
            </div>
            <div class="row j-c-b rating a-c-center">
                <!-- <h3>Оцінка: </h3> -->
                <!-- TODO -->
                <!-- left and rigth mouse btn -->
                <p id="rating_text">Ваша оцінка:</p>
                <div class="row j-c-start" style="padding:5px;" id="rating">
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                </div>


                <div class="row">
                    <button class="button" style="width:fit-content;margin:0;" onclick="AddReview()">Залишити відгук</button>
                </div>
            </div>
        </div>
<?php

        foreach ($reviews as $review) {
            ?>
            <div class="review-item <?= $review->id==$review_id?'review-active':'' ?>" id="review_<?=$review->id?>">
                <div class="info">
                    <div class="row">
                    <?php 
                    if($review->user_id!=$user->id){
                    ?>
                    <i class="fa-solid fa-comments icon" title="Відповісти" onclick="AnswerBlock(<?php echo $review->id; ?>,'<?php echo $review->comment; ?>','<?php echo $review->user->full_name; ?>')"></i>
                    <?php 
                    }
                    ?>
                    <div class="img">
                    <img class="profile-photo" src="/Resources/img/users/<?php echo $review->user->photo; ?>" alt="<?php echo $review->user->photo; ?>">


                    </div>    
                        <div class="column">
                            <div class="row">
                                <div class="column j-c-start">
                                     <div class="row j-c-start h-auto">
                                       <h4 class="row"><?= !is_null($review->parent_comment_id)?$review->user->full_name." <p class='text-light'> у відповідь </p> <span onclick='findParentComment(".$review->parent_comment_id.")'>".$review->getParentComment()->user->full_name:$review->user->full_name ?></span></h4>
                                     </div>
                                    <small> <?php echo Data::dateFormat($review->date); ?></small>
                                </div>
                                <div class="row">
                                    <div class="buttons row a-c-center h-full">
                                        </div>
                                </div>
                            </div>
                            <div class="column">
                                <?php
                                if($review->rating!=0&&$review->rating!=null){
                                ?>
                                <small>Оцінка: <?php echo $review->rating; ?> / 5</small>
                                <?php
                                }else{
                                    ?>
                                    <small>Оцінка відсутня</small>
                                    <?php
                                }
                                ?>
                                <p><?php echo $review->comment; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php

        }
        ?>
    </div>
    
</div>
<script>
//onload 
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
    document.getElementsByClassName("review-active")[0].classList.remove('review-active');
        }, 3000);
});


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




let rating = 0; // від 0 до 5, з кроком 0.5
let review='';
let answerComment=document.getElementById('answer');
let answerId=null;
toggleAnswerBlock()
function toggleAnswerBlock(params) {
    if(answerId==null){
        answerComment.style.display = 'none';
    }else{
        answerComment.style.display = 'block';
    }
}
function toggleReview(text) {
    review=text;
}
function findParentComment(parent_id){
    location.href="#review_"+parent_id;
    document.getElementById("review_"+parent_id).classList.add('review-active');
    setTimeout(function() {
    document.getElementById("review_"+parent_id).classList.remove('review-active');
        }, 3000);
}
function AddReview(){
    if(review=='') {
        alert("Будь ласка, введіть текст відгуку!");
        return;
    }
    const res=API.post('/api/reviews/add',{
        rating:rating,
        review:review,
        parent_comment_id:answerId,
        film_id:<?php echo $film->id; ?>,
    },"Помилка при додаванні відгуку!");
    if(res!=false) {
        alert("Відгук успішно додано!");
        window.location.reload();
    }
}

function AnswerBlock(review_id,comment,autor){
        answerId=review_id;
        autor= autor || 'Автор';
        toggleAnswerBlock();
        answerComment.innerHTML='<i class="fa-solid fa-xmark icon" onclick="removeAnswerBlock()"></i>';
        answerComment.innerHTML += `<p>Відповісти на відгук від <b>${autor}</b>:</p>`;
        answerComment.innerHTML += `<small>${comment}</small>`;       
        location.href="#anchor";
        hideStars();
}
function removeAnswerBlock() {
    answerId=null;
    answerComment.innerHTML = '';
    toggleAnswerBlock();
}
const stars = document.querySelectorAll('#rating i');
function hideStars() {
    document.getElementById('rating_text').style.display = 'none';
    document.getElementById('rating').style.display = 'none';
}
function showStars() {
    document.getElementById('rating').style.display = 'flex';
    document.getElementById('rating_text').style.display = 'block';
}
function renderStars() {
    stars.forEach((star, i) => {
        let starValue = i + 1; // позиція (1–5)

        if (rating >= starValue) {
            star.className = "fa-solid fa-star"; // повна
        } else if (rating >= starValue - 0.5) {
            star.className = "fa-solid fa-star-half-stroke"; // половинка
        } else {
            star.className = "fa-regular fa-star"; // пуста
        }
    });
}

document.getElementById('rating').addEventListener('mousedown', (e) => {
    e.preventDefault(); // щоб ПКМ не викликав контекстне меню
    if (e.button === 0) { // ЛКМ
        rating = Math.min(5, rating + 0.5);
    } else if (e.button === 2) { // ПКМ
        rating = Math.max(0, rating - 0.5);
    }
    renderStars();
});

// щоб ПКМ не відкривав меню
document.getElementById('rating').addEventListener('contextmenu', (e) => e.preventDefault());

renderStars(); // початковий стан

</script>