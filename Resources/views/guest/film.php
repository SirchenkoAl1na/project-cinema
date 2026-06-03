
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
        <!-- <h4>Середня оцінка: ?</h4> -->
        <!-- <h4>Оцінка IMDB: <span id="imdb_rating"> - </span></h4>
        <div class="block column a-c-center" style="height:fit-content;">
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
        let rating=0;
        let parent_comment_id=null;
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
    function AddReview(){

        let ticketkod=document.getElementById("review_ticketkod");
        let name=document.getElementById("review_name");
        let text=document.getElementById("review_text");

        if(ticketkod.value.length==0 || name.value.length==0 || text.value.length==0){
            document.getElementById("review_alert").innerText = "Заповніть всі поля.";
            return;
        }

        let correct_ticketkod=false;
        await fetch("/api/check_ticketkod?kod="+ticketkod.value).then(res=>res.json()).then(data=>correct_ticketkod=data.message);
        if(correct_ticketkod){
            ticketkod.style.borderColor="green";
            document.getElementById("review_alert").innerText = "";
            fetch("/api/add_review",{
                method:"POST",
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    film_id:<?= $film->id ?>,
                    name:name.value,
                    comment:text.value,
                    ticketkod:ticketkod.value,
                    rating:rating,
                    parent_comment_id:answerId,
                })
            }).then(res=>res.json()).then(data=>{
                if(data.status=="success"){
                    alert("Відгук успішно додано!");
                    location.reload();
                }else{
                    alert("Сталася помилка при додаванні відгуку. Спробуйте ще раз.");
                }
            });

        }else{
            ticketkod.style.borderColor="red";
            document.getElementById("review_alert").innerText = "Введений код не коректний.";
        }
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