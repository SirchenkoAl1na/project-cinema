<div class="block not-visible row j-c-end">
    <button class="button" onclick="location.href='/admin/employee'">Назад</button>
</div>
<div class="block employer-info" style="color:var(--text)">

<p><b>Ім'я та прізвище: </b><?=$employer->user->full_name?></p>
               <p><b>Логін: </b><?=$employer->user->login?></p>
               <p><b>Номер телефону: </b><?=$employer->user->phone?>
               <button class="button button-not-visible" style="margin:0px;" onclick="Copy('<?= $employer->user->phone ?>',this)" title="Натисність, щоб скопіювати">
                                <i class="fa-solid fa-copy"></i>
                            </button>
                        </p>
               <p><b>Ел.адреса: </b><?=$employer->user->email?></p>
               <p><b>Посада: </b><?=$employer->posada?></p>
            </div>