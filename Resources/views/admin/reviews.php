<div class="block options">
    <div class="search">
        <h4>Пошук: </h4>
        <i class="hint fa-solid fa-circle-question" title="Можна шукати за: назвою фільму, ім'я та прізвищем користувача, датою публікації (у форматі: 01.01.2025) та ключовами словами відгуку"></i>
        <input type="text" placeholder="Пошук по тексту, фільму, користувачу" id="search_input" value="<?=$search?>" onchange="SFS.setSearchParams(document.getElementById('search_input').value)">
        <button class="button button-search" onclick="SFS.setSearchParams(document.getElementById('search_input').value)">Шукати</button>
    </div>
    <div class="filter">
        <h4>Фільтрувати за оцінкою: </h4>
        <button class="button button-filter <?= $filter==''?'active':'' ?>" onclick="SFS.setFilterParam('')">Будь яка</button>
        <button class="button button-filter <?= $filter=='high'?'active':'' ?>" onclick="SFS.setFilterParam('high')">> 4.5</button>
        <button class="button button-filter <?= $filter=='middle'?'active':'' ?>" onclick="SFS.setFilterParam('middle')">3 - 4.5</button>
        <button class="button button-filter <?= $filter=='low'?'active':'' ?>" onclick="SFS.setFilterParam('low')">1.5 - 3</button>
        <button class="button button-filter <?= $filter=='lowest'?'active':'' ?>" onclick="SFS.setFilterParam('lowest')">< 1.5</button>
    </div>
    <div class="filter">
        <h4>Фільтрувати за статусом: </h4>
        <button class="button button-filter <?= $filter2==''?'active':'' ?>" onclick="SFS.setFilter2Param('')">Публічні</button>
        <button class="button button-filter <?= $filter2=='is_blocked'?'active':'' ?>" onclick="SFS.setFilter2Param('is_blocked')">Заблоковані</button>
    </div>
    <div class="sort">
        <h4>Сортувати за:</h4>
        <button class="button button-sort <?= $sort==''?'active':'' ?>" onclick="SFS.setSortParams('')">За назвою фільму<i class="fa-solid fa-down-long"></i></button>
        <button class="button button-sort <?= $sort=='by_date_asc'?'active':'' ?>" onclick="SFS.setSortParams('by_date_asc')">За датою<i class="fa-solid fa-down-long"></i></button>
        <button class="button button-sort <?= $sort=='by_date_desc'?'active':'' ?>" onclick="SFS.setSortParams('by_date_desc')">За датою<i class="fa-solid fa-up-long"></i></button>
        <button class="button button-sort <?= $sort=='by_rating_desc'?'active':'' ?>" onclick="SFS.setSortParams('by_rating_desc')">За оцінкою<i class="fa-solid fa-down-long"></i></button>
        <button class="button button-sort <?= $sort=='by_rating_asc'?'active':'' ?>" onclick="SFS.setSortParams('by_rating_asc')">За оцінкою<i class="fa-solid fa-up-long"></i></button>
    </div>
</div>

<?php

use App\Data;

if (!isset($reviews) || empty($reviews)) {
    echo "<div class='empty-data'><h3>Не знайдено даних</h3></div>";
    return;
} else {
?>

    <div class="block not-visible" id="reviews_list">
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
                                       <h4><?= $review->film->title ?></h4>
                                       <i><?= " by ".$review->user->full_name ?></i>
                                     </div>
                                    <small> <?= Data::dateFormat($review->date) ?></small>
                                </div>
                                <div class="row">
                                    <div class="buttons row a-c-center h-full">
                                        <button class="button button-icon button-hide" onclick="location.href='/admin/reviews/block?id=<?= $review->id ?>'" title="Приховати"><i class="fa-solid fa-square-minus"></i></button>
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
    </div><?php
        }
            ?>