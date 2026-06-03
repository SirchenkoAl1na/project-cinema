
<div class="block options">
    <!-- <div class="search">
        <input type="text" placeholder="Пошук..." id="search_input">
        <button class="button button-search" onclick="SFS.setSearchParams(document.getElementById('search_input').value)">Шукати</button>
    </div> -->
    <div class="filter">
        <h4>Фільтрувати:</h4>
        <button class="button button-filter <?= $filter==""?'active':'' ?>" onclick="SFS.setFilterParam('')">Всі</button>
        <button class="button button-filter <?= $filter=="open"?'active':'' ?>" onclick="SFS.setFilterParam('open')">Відкриті</button>
        <button class="button button-filter <?= $filter=="under_renovation"?'active':'' ?>" onclick="SFS.setFilterParam('under_renovation')">На ремонті</button>
    </div>
    <!-- <div class="sort">
        <button class="button button-sort" onclick="SFS.setSortParams('a')">By a</button>
        <button class="button button-sort" onclick="SFS.setSortParams('b')">By b</button>
    </div> -->
</div>


<?php
if (!isset($holes) || empty($holes)) {
    echo "<div class='empty-data'><h3>Не знайдено даних</h3></div>";
    return;
} else {
?>

    <div class="block not-visible" id="holes_grid">
        <?php
        foreach ($holes as $hole) {
        ?>
            <div class="hole-card">
            <div class="head">
                <h4>Зала №<?=$hole['nomer']?></h4>
            </div>
            <div class="body">
                <p><b>Поточний статус: </b> <?=$hole['status'] ?></p>
                <p><b>Поточний сеанс: </b> <?= $hole['current_seanse']?$hole['current_seanse']['time']." (".$hole['current_seanse']['film_title'].")" :'' ?> </p>
                <p><b>Кількість місць: </b> <?=$hole['number_of_places']??'' ?> </p>
                <p><b>Заплановані сеанси на день: </b> <ul>
                    <?php 
                    foreach($hole['seanses'] as $seanse){
                        ?>
                            <li><?= $seanse['time']." (".$seanse['film_title'].")" ?></li>
                        <?php
                    }
                    ?>
                </ul></p>
            </div>
            </div>
            <?php

        }
            ?>
    </div><?php

    }
        ?>