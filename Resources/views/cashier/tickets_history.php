<?php
use App\Data;

?>
<i style="color:var(--text);">Тут відображаються квитки продані протягом останньої зміни</i>
<div class="block options">
    <div class="filter">
        <h4>Зали:</h4>
        </br>
        <button class="button button-filter <?= $hole_choosen==null?'active':'' ?>" onclick="SFS.setFilterParam('')">Всі</button>
        <?php
        foreach($holes as $hole){
        ?>
        <button class="button button-filter <?= $hole_choosen==$hole->id?'active':'' ?>" onclick="SFS.setFilterParam('hole_<?= $hole->id ?>')">Зал <?=$hole->nomer?></button>
        <?php
        }
        ?>
    </div>
    <div class="sort">
        <h4>Сортувати за датою:</h4>
        <button class="button button-sort <?= $sort=='date_asc'?'active':'' ?>" onclick="SFS.setSortParams('date_asc')">За датою<i class="fa-solid fa-down-long"></i></button>
        <button class="button button-sort <?= $sort=='date_desc'?'active':'' ?>" onclick="SFS.setSortParams('date_desc')">За датою<i class="fa-solid fa-up-long"></i></button>
    </div>
</div>

<table id="ticket_history">
    <!-- додати скрізь сортування по таблиці -->
<thead>
<tr>
    <th>Фільм</th>
    <th>Сеанс</th>
    <th>Статус</th>
    <th>Зал</th>
    <th>Ціна</th>
    <th>Дата і час продажі</th>
    <th>Ряд</th>
    <th>Місце</th>
    <!-- todo check -->
    <th>Клієнт</th>
    <th>Дії</th>
</tr>
</thead>
<tbody>
<?php
foreach ($tickets as $ticket) {
    ?>
<tr>
    <td><?php echo $ticket->sale->seanse->film->title; ?></td>
    <td><?php echo Data::dateFormat($ticket->sale->seanse->date); ?></td>
    <td><?php echo !is_null($ticket->sale->employer_id)?'<p class="sale-status sale-status-cashier">Продано касиром</p>':'<p class="sale-status sale-status-user">Продано онлайн</p>'; ?></td>
    <td><?php echo $ticket->sale->seanse->hole->nomer; ?></td>
    <td><?php echo $ticket->price; ?> грн</td>
    <td><?php echo $ticket->sale->dateAndTime(); ?></td>
    <td><?php echo $ticket->place->row; ?></td>
    <td><?php echo $ticket->place->place; ?></td>
    <td><?php echo $ticket->sale->user_id != null ? $ticket->sale->user->full_name : '-'; ?></td>
    <td>
    <button class="button button-icon button-show" onclick="generatePDF('<?= $ticket->sale->seanse->film->title ?>','<?= \App\Data::dateFormat($ticket->sale->seanse->date) ?>','<?= $ticket->sale->seanse->time ?>','<?= $ticket->sale->seanse->hole->nomer ?>','<?= $ticket->place->row ?>','<?= $ticket->place->place ?>','<?= $ticket->price ?>','<?= $ticket->ticket_kod ?>','<?= $ticket->qr_token ?>')" title="Друк"><i class="fa-solid fa-print"></i></button>
    <?php 
    if(!is_null($ticket->sale->employer_id)){
    ?>
    <button class="button button-icon button-other" onclick="returnTicket(<?= $ticket->id; ?>)" title="Повернення квитка"><i class="fa-solid fa-rotate-left"></i></button>
    <?php 
    }
    ?>
    </td>
</tr>
<?php } ?>
</tbody>
</table>
<div id="qr_temp" style="display:none;"></div>
<script>
function returnTicket(ticket_id){
    if(!window.confirm('Ви впевнені, що хочете повернути цей квиток?')) return;
    location.href='/cashier/tickets/return?id='+ticket_id;
}
</script>