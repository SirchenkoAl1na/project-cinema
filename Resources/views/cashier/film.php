<?php 
use App\Data;
?>
<div id="film_show" class="column" style="margin-top:20px;">
        <div>
    <div class="row j-c-start">
    <div id="poster" >
        <img src="/Resources/img/film_posters/<?php echo $film->poster; ?>" alt="<?php echo $film->poster; ?>">
    </div>
    <div class="column j-c-start">
      
                        <p><b>Жанри:</b> <?php echo !empty($film->genres) ? $film->genres : ' - '; ?> </p>
                        <p><b>Країна:</b> <?php echo !empty($film->country) ? $film->country : ' - '; ?> </p>
                        <p><b>Режисер:</b> <?php echo !empty($film->director) ? $film->director : ' - '; ?> </p>
                        <p><b>Актори:</b> <?php echo !empty($film->actors) ? implode(', ', $film->actors) : ' - '; ?> </p>
                        <?php
                        $avg_r=$film->getAverageRating();
                        ?>
                        <h4 title="<?= $avg_r!=0?'':'Відсутня' ?>">Середня оцінка користувачів: <?= $avg_r==0?' - ':$avg_r ?></h4>
                        <h4>Середня оцінка IMDB: <span id="imdb_rating"> - </span></h4>
                        <p><b>Опис:</b> <?php echo !empty($film->description) ? '<i>'.$film->description.'</i>' : ' - '; ?> </p>
    </div>
</div>
</div>
    <div id="seanses">
        <h2>Сеанси</h2>
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
                    <div class="seanse-item" onclick="location.href='/cashier/tickets/sell?seanse_id=<?= $seanse['id'] ?>'">
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
    </div>
    
    