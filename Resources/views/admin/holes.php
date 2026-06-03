<div class="block not-visible row j-c-end">
    <button class="button button-add" onclick="location.href='/admin/holes/create'">Додати</button>
</div>
<div class="block options">
    <div class="filter">
        <h4>Фільтрувати:</h4>
        <button class="button button-filter <?= $filter==""?'active':'' ?>" onclick="SFS.setFilterParam('')">Всі</button>
        <button class="button button-filter <?= $filter=="open"?'active':'' ?>" onclick="SFS.setFilterParam('open')">Відкриті</button>
        <button class="button button-filter <?= $filter=="under_renovation"?'active':'' ?>" onclick="SFS.setFilterParam('under_renovation')">На ремонті</button>
    </div>
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
                <h4><?=$hole['nomer']?></h4>
                <div class="buttons">
                    <button class="button button-icon button-edit" onclick="location.href='/admin/holes/edit?id=<?= $hole['id']  ?>'" title="Редагувати"><i class="fa-solid fa-pen-to-square"></i></button>
                </div>
            </div>
            <div class="body">
                <p><b>Поточний статус: </b> <?=$hole['status']?></p>
                <p><b>Поточний сеанс: </b> - </p>
                <p><b>Кількість місць: </b> <?=$hole['number_of_places']?> </p>
                <p><b>Заплановані сеанси на день: </b> <ul>

                </ul></p>
            </div>
            </div>
            <?php

        }
            ?>
    </div><?php

    }
        ?>