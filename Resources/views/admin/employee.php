<div class="block not-visible row j-c-end">
    <button class="button button-add" onclick="location.href='/admin/employee/create'">Додати</button>
</div>
<div class="block options">
    <div class="search">
        <h4>Пошук:</h4>
    <i class="hint fa-solid fa-circle-question" title="Можна шукати за: ПІБ, логіном, ел.адресі і номером телефону"></i>
        <input type="text" placeholder="Пошук..." id="search_input" value="<?=$search?>"  onchange="SFS.setSearchParams(document.getElementById('search_input').value)">
        <button class="button button-search" onclick="SFS.setSearchParams(document.getElementById('search_input').value)">Шукати</button>
    </div>
    <div class="filter">
        <h4>Фільтрувати за:</h4>
        <button class="button button-filter <?= $filter==''?'active':'' ?>" onclick="SFS.setFilterParam('')">Усі</button>
        <button class="button button-filter <?= $filter=='cashiers'?'active':'' ?>" onclick="SFS.setFilterParam('cashiers')">Касири</button>
        <button class="button button-filter <?= $filter=='ushers'?'active':'' ?>" onclick="SFS.setFilterParam('ushers')">Перевіряючі</button>
    </div>
    <div class="sort">
        <h4>Сортувати:</h4>
        <button class="button button-sort <?= $sort==''?'active':'' ?>" onclick="SFS.setSortParams('')">За ПІБ<i class="fa-solid fa-down-long"></i></button>
        <button class="button button-sort <?= $sort=='name_desc'?'active':'' ?>" onclick="SFS.setSortParams('name_desc')">За ПІБ<i class="fa-solid fa-up-long"></i></button>
    </div>
</div>
<?php
if (!isset($employee) || empty($employee)) {
    echo "<div class='empty-data'><h3>Не знайдено даних</h3></div>";
    return;
} else {
?>
    <div class="block">
        <table id="employee_table">
            <thead>
                <tr>
                    <th>ПІБ</th>
                    <th>Логін</th>
                    <th>Номер телефону</th>
                    <th>Ел.адреса</th>
                    <th>Посада</th>
                    <th>Заробітна плата</th>
                    <th>Дії</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($employee as $employer) {
                ?>
                    <tr>
                        <td><?= $employer->user->full_name ?></td>
                        <td><?= $employer->user->login ?></td>
                        <td><?= $employer->user->phone ?>
                            <button class="button button-not-visible" onclick="Copy('<?= $employer->user->phone ?>',this)" title="Натисність, щоб скопіювати">
                                <i class="fa-solid fa-copy"></i>
                            </button>

                        </td>
                        <td><?= $employer->user->email ?></td>
                        <td><?= $employer->posada ?></td>
                        <td><?= $employer->zarplata!=0?$employer->zarplata.' грн':'Не призначено' ?></td></td>
                        <td>
                            <button class="button button-icon button-show" onclick="location.href='/admin/employee/show?id=<?= $employer->id ?>'" title="Переглянути"><i class="fa-solid fa-info"></i></button>
                            <!-- <button class="button button-icon button-save" onclick="location.href='/admin/employee/shedule?id=<?= $employer->id  ?>'" title="Перегляд та редагування розкладу"><i class="fa-solid fa-calendar"></i></button> -->
                            
                            <?php 
                            if($employer->posada!="адміністратор"){
                            ?>
                            <button class="button button-icon button-other" onclick="location.href='/admin/employee/new-password?id=<?= $employer->user_id  ?>'" title="Змінити пароль"><i class="fa-solid fa-unlock-keyhole"></i></button>
                            <button class="button button-icon button-edit" onclick="location.href='/admin/employee/edit?id=<?= $employer->user_id  ?>'" title="Редагувати"><i class="fa-solid fa-pen-to-square"></i></button>
                            <?php 
                            }
                            ?>
                            </td>
                    </tr>
                <?php

                }
                ?>
            </tbody>
        </table>
    </div><?php

        }
            ?>