<div class="block not-visible row j-c-end">
    <button class="button button-add" onclick="location.href='/admin/films/create'">Додати</button>
</div>
<div class="block options">
    <div class="search">
        <h4>Пошук </h4>
        <input type="text" placeholder="Пошук..." id="search_input" value="<?=$search?>"  onchange="SFS.setSearchParams(document.getElementById('search_input').value)">
        <button class="button button-search" onclick="SFS.setSearchParams(document.getElementById('search_input').value)">Шукати</button>
    </div>
    <div class="filter">
        <h4>Фільтрувати </h4>
        <button class="button button-filter <?= $filter==''?'active':'' ?>" onclick="SFS.setFilterParam('')">Усі </button>
        <button class="button button-filter <?= $filter=='in_cinema'?'active':'' ?>" onclick="SFS.setFilterParam('in_cinema')">В прокаті </button>
        <button class="button button-filter <?= $filter=='in_archive'?'active':'' ?>" onclick="SFS.setFilterParam('in_archive')">В архіві</button>
        <button class="button button-filter <?= $filter=='wait_a_primiere'?'active':'' ?>" onclick="SFS.setFilterParam('wait_a_primiere')">Очікування прим'єри</button>
    </div>
    <div class="sort">
        <h4>Сортувати:</h4>
        <button class="button button-sort <?= $sort==''?'active':'' ?>" onclick="SFS.setSortParams('')">За назвою<i class="fa-solid fa-down-long"></i></button>
        <button class="button button-sort <?= $sort=='by_name_desc'?'active':'' ?>" onclick="SFS.setSortParams('by_title_desc')">За назвою<i class="fa-solid fa-up-long"></i></button>
    </div>
</div>

<?php
use App\Data;
if (!isset($films) || empty($films)) {
    echo "<div class='empty-data'><h3>Не знайдено даних</h3></div>";
    return;
} else {
?>

    <div class="block not-visible" id="films_grid">
        <?php
        foreach ($films as $film) {
        ?>
            <div class="film-card" style="background-image:url('/Resources/img/film_posters/<?= $film->poster ?>')">
                <div class="info">
                    <div class="row w-full">
                        <h4><?= htmlspecialchars($film->title) ?></h4></i></button>
                        <div class="row">
                            <button class="button button-icon button-add" onclick="location.href='/admin/seanses/create?film_id=<?php echo $film->id; ?>'" title="Додати сеанс"><i class="fa-solid fa-plus"></i></button>
                            <button class="button button-icon button-edit" onclick="location.href='/admin/films/edit?id=<?= $film->id  ?>'" title="Редагувати"><i class="fa-solid fa-pen-to-square"></i></button>
                        </div>
                    </div>
                    <div class="column w-full">
                        <!-- TODO: підтягнути оцінки по АПІ -->
                        <p><b title='Оцінка'><i class="fa-solid fa-star"></i></b> <span class="imdb-rating-film" id="imdb_<?=$film->imdb_id?>"> - </span> </p>
                        <p><b>Дата прим'єри:</b> <?=Data::date($film->primiere_date)?></p>
                        <p><b>Дата завершення прокату:</b> <?=!isset($film->end_date)&&!is_null($film->end_date)&&!empty($film->end_date)?Data::date($film->end_date):' - '?></p>
                        <p><b>Жанри:</b> <?= !empty($film->genres) ? $film->genres : ' - '  ?> </p>
                        <p><b>Країна:</b> <?= !empty($film->country) ? $film->country : ' - '  ?> </p>
                        <p><b>Режисер:</b> <?= !empty($film->director) ? $film->director : ' - '   ?> </p>
                        <p><b>Актори:</b> <?= !empty($film->actors) ? implode(", ", $film->actors) : ' - '   ?> </p>
                        
                        <p><b>Опис:</b> <?= !empty($film->description) ? '<i>'.$film->description.'</i>' : ' - '   ?> </p>
                    </div>
                </div>

            </div>
        <?php

        }
        ?>
    </div><?php

        }
?>
<script>
    let IMDbApiKey='5a9cada9';
    
    let imdb_ratings = document.getElementsByClassName('imdb-rating-film');
    for (let index = 0; index < imdb_ratings.length; index++) {
        const imdb_rating = imdb_ratings[index];
      
        let id=imdb_rating.id;
        let imdb_id=id.split('_')[1];

        fetch('https://www.omdbapi.com/?i='+imdb_id+'&apikey='+IMDbApiKey)
        .then(res=>res.json())
        .then(data=>{
            if(data.Response=="True"){
                document.getElementById(id).innerText=data.imdbRating;
            }else{
                document.getElementById(id).innerText=" - ";
            }
        });

    
    }    
</script>