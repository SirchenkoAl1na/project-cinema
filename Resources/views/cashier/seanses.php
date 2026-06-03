<?php 
use App\Data;
?>
<div class="block options">
    <div class="search">
        <h4>За датою:</h4>
        <input type="date" id="choose_date" value="<?= $current_date ?>"  min="<?= Data::today() ?>"  onchange="SFS.setFilterParam(document.getElementById('choose_date').value)">
        <!-- <button class="button button-search" onclick="SFS.setFilterParam(document.getElementById('choose_date').value)">Знайти</button> -->
    </div>
    <div class="grouping">
        <h4>Групувати за:</h4>
        <button class="button button-filter <?= $view=="by_film"?'active':'' ?>" onclick="SFS.setFilter2Param('')">Фільмами</button>
        <button class="button button-filter <?= $view=="by_time"?'active':'' ?>" onclick="SFS.setFilter2Param('by_time')">Часом</button>
    </div>
</div>

<?php
if (!isset($groups) || empty($groups)) {
    ?>
    <div class='empty-data'><h3>Не знайдено даних</h3></div>
    <?php
} else {
    if($view=="by_film"){  
    ?>
    <div class="block not-visible" id="seanses_grid">
    <?php  
        foreach ($groups as $group) {
            $film=$group['film'];
            $seanses=$group['seanses'];
            ?>
            <div class="film-card">
                <img src="/Resources/img/film_posters/<?= $film->poster ?>" alt="<?= $film->poster ?>">
                
                <span class="button-detail" onclick="location.href='/cashier/film?id=<?php echo $film->id; ?>'" title="Переглянути інформацію про фільм">
                    <i class="fa-solid fa-film"></i>
                    </span>
                <div class="column">
                    <div class="info">
                        <h4><?= htmlspecialchars($film->title) ?></h4>
                        <p><b>Жанр: </b><?= !empty($film->genres) ? $film->genres : ' - '  ?></p>
                    </div>
                    <div class="seanses-grid">
                        <?php
                        foreach ($seanses as $seanse) {
                        ?>
                            <div class="seanse-item" title="Продати квиток(-ки)" onclick="location.href='/cashier/tickets/sell?seanse_id=<?= $seanse->id ?>'">
                                <a><?=$seanse->time?></a>    
                            </div>
                        <?php
                        }
                            ?>
                    </div>
                </div>
            </div>
        <?php
        }
    ?>
    </div>
    <?php
    }
    else{
        ?>
        <div class="block not-visible" id="seanses_grid_by_time">
        <?php  
        foreach ($groups as $group) {
            ?>
            <h4>На <?= $group['time'] ?></h4>
    <div class="seanses-grid">
    <?php  
        foreach ($group['seanses'] as $seanse) {
            ?>
            <div class="seanse-card">
                
            <p class="text-bold"><?= $seanse->film->title ?></p>
            <p>Жанр: <?= $seanse->film->genres ?></p>
            <i>Зал №<?= $seanse->hole->nomer ?></i>
            <p>Вільні квитки: <?= $seanse->freeTickets()?></p>
            <i class="fa-solid fa-ticket icon" title="Продати квиток(-ки)" onclick="location.href='/cashier/tickets/sell?seanse_id=<?= $seanse->id ?>'"></i>
            </div>
            <?php
        }
        ?>

    </div>
            <?php
        }
        ?>
        </div>
        <?php
    }
}
?>


