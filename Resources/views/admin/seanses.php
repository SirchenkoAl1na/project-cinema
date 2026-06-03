<?php 
use App\Data;
?>
<div class="block not-visible row j-c-end">
    <button class="button button-add" onclick="location.href='/admin/seanses/create'">Додати новий</button>
</div>
<div class="block options">
    <div class="search">
        <h4>За датою:</h4>
        <input type="date" id="choose_date" value="<?php echo $current_date; ?>"  min="<?= Data::today() ?>" onchange="SFS.setFilterParam(document.getElementById('choose_date').value)">
        <!-- <button class="button button-search" onclick="SFS.setFilterParam(document.getElementById('choose_date').value)">Знайти</button> -->
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
                <div class="column">
                    <div class="info">
                        <h4><?php echo htmlspecialchars($film->title); ?></h4>
                        <p><b>Жанр: </b><?php echo !empty($film->genres) ? $film->genres : ' - '; ?></p>
                    </div>
                    <div class="seanses-grid">
                        <?php
                            foreach ($seanses as $seanse) {
                                ?>
                        <!-- event on rigth mouse click -->
                            <div class="seanse-item <?= !$seanse->canBeRemoved()?'seanse-item-not-removed':'' ?>" title="<?php echo $seanse->canBeRemoved() ? 'Якщо хочете відмінити сеанс, натисніть лівою кнопкою миші' : 'Сеанс має продані квитки, тому його не можна відмінити'; ?>" onclick="<?php echo $seanse->canBeRemoved() ? 'RemoveSeanse('.$seanse->id.',\''.$seanse->time.'\',\''.$film->title.'\')' : 'CannotRemoveSeanse()'; ?>">
                                <a><?php echo $seanse->time; ?> | Зал №<?php echo $seanse->hole->nomer; ?></a>    
                            </div>
                        <?php
                            }
                ?>
                        <div class="seanse-item">
                            <a href="/admin/seanses/create?film_id=<?php echo $film->id; ?>">+</a>    
                        </div>
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
        <div class="block not-visible" id="seanses_grid_by_time">
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
            <p>Жанр: <?php echo $seanse->film->genres; ?></p>
            <i>Зал №<?php echo $seanse->hole->nomer; ?></i>
            <p>Вільні квитки: <?php echo $seanse->freeTickets(); ?></p>
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

<script>
    function RemoveSeanse(seanse_id,time="-",film_title="-"){
        if(confirm("Ви хочете відмінити сеанс на "+time+" фільму \""+film_title+"\"? (Натисніть \'ОК\')")){
            // alert("Ця функція в розробці");
            const res=API.post('/api/seanses/remove',{
                seanse_id:seanse_id,
            },'При відміні сеансу, виникла помилка. Спробуйте пізніше');
            if(res!=false) {
                window.location.reload();
            }else{
                alert('При відміні сеансу, виникла помилка. Спробуйте пізніше');
            }

        }
    }
    function CannotRemoveSeanse(){
        alert('Сеанс має продані квитки, тому його не можна відмінити');
    }
</script>