
<?php 
use App\Data;
if(!empty($primiere_films)){
?>
<div id="primiere_films" class="block block-with-width80">
<h2 class="text-white">Скоро прим'єра:</h2>
<div class="primiere-films-grid">
<?php
foreach($primiere_films as $film){
?>
<div class="primiere-film-card">
    
</div>
<?php
}
?>
</div>

</div>

<?php 
}
?>
<div class="block options">
    <div class="search row j-c-center">
        <input type="date" id="choose_date" value="<?php echo $current_date; ?>" min="<?= Data::today() ?>">
        <button class="button button-search" onclick="SFS.chooseDate(document.getElementById('choose_date').value)">Знайти</button>
    </div>
    <div class="filter row j-c-start" style="width:60%;">
        <h4 style="text-wrap-mode: nowrap;align-content: center;">Фільтрувати за жанром:</h4>
        <button class="button button-filter <?php echo $filter_genre == '' ? 'active' : ''; ?>" onclick="SFS.setFilterParam('')">Усі</button>
        <?php
        foreach ($genres as $genre) {
            ?>
        <button class="button button-filter <?php echo $filter_genre == $genre ? 'active' : ''; ?>" onclick="SFS.setFilterParam('<?php echo $genre; ?>')"><?php echo $genre; ?></button>
        <?php
        }
        ?>
    </div>
    <div class="grouping">
        <h4>Групувати за:</h4>
        <button class="button button-filter <?php echo $view == 'by_film' ? 'active' : ''; ?>" onclick="SFS.setFilter2Param('')">Фільмами</button>
        <button class="button button-filter <?php echo $view == 'by_time' ? 'active' : ''; ?>" onclick="SFS.setFilter2Param('by_time')">Часом</button>
    </div>
</div>

<?php
if (!isset($groups) || empty($groups)) {
    ?>
        <div class='empty-data'><h3>Не знайдено даних</h3></div>
    <?php
} else {
    if ($view == 'by_film') {
        ?>
    <div class="block not-visible" id="seanses_grid">
    <?php
            foreach ($groups as $group) {
                $film = $group['film'];
                $seanses = $group['seanses'];
                ?>
            <div class="film-card">
                <img src="/Resources/img/film_posters/<?php echo $film->poster; ?>" alt="<?php echo $film->poster; ?>">
                <button class="button-detail" onclick="location.href='/profile/film?id=<?php echo $film->id; ?>'" title="Переглянути інформацію про фільм">
                    <i class="fa-solid fa-film"></i>
                    </button>
                <div class="column">
                    <div class="info">
                        <h4><?php echo htmlspecialchars($film->title); ?></h4>
                        <p><b>Жанр: </b><?php echo !empty($film->genres) ? str_replace($filter_genre,"<b>".$filter_genre."</b>",$film->genres) : ' - '; ?></p>
                    </div>
                    <div class="seanses-grid">
                        <?php
                            foreach ($seanses as $seanse) {
                                ?>
                            <div class="seanse-item" onclick="location.href='/profile/tickets/sell?seanse_id=<?php echo $seanse->id; ?>'">
                                <a><?php echo $seanse->time; ?></a>    
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
    } else {
        ?>
        <div class="block not-visible seanses-grid" id="seanses_grid_by_time">
        <?php
        foreach ($groups as $group) {
            ?>
            <h4>На <?php echo $group['time']; ?></h4>
            <div class="seanses-grid">
                <?php
            foreach ($group['seanses'] as $seanse) {
                ?>
                <div class="seanse-card">
                    
                <p class="text-bold"><?php echo $seanse->film->title; ?></p>
                <p>Жанр: <?= str_replace($filter_genre,"<b>".$filter_genre."</b>",$seanse->film->genres)?></p>
                <i>Зал №<?php echo $seanse->hole->nomer; ?></i>
                <!-- <p>Не продано квитків: <?php echo $seanse->freeTickets(); ?></p> -->
                <i class="fa-solid fa-ticket icon" title="Продати квиток(-ки)" onclick="location.href='/profile/tickets/sell?seanse_id=<?php echo $seanse->id; ?>'"></i>
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