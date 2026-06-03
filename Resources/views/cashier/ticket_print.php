
        <div class="tickets-seanse-info">
            
        <h3><?=$tickets[0]->sale->seanse->film->title?></h3>
            <p>Дата: <?=$tickets[0]->sale->seanse->date?></p>
            <p>Час: <?=$tickets[0]->sale->seanse->time?></p>
            <p>Зала: <?=$tickets[0]->place->hole->nomer?></p>
            <p>Сума: <?=$tickets[0]->sale->sum?> грн</p>
        
        </div>
        <?php
        $all=[];
        foreach($tickets as $ticket){
            $all[]=$ticket->sale->seanse->film->title .','. \App\Data::dateFormat($ticket->sale->seanse->date) .','. $ticket->sale->seanse->time.','.$ticket->sale->seanse->hole->nomer.','. $ticket->place->row.','. $ticket->place->place .','. $ticket->price .','. $ticket->ticket_kod.','.$ticket->qr_token;
        }
        
        ?>
        <button class="button button-show" onclick="generatePDFAll('<?=implode('][',$all) ?>')">Роздрукувати усі</button>
        <div class="row j-c-start">
    <?php 
    foreach($tickets as $ticket){
    ?>
    <div class="ticket-item">
        <p>Ряд: <?=$ticket->place->row?></p>
        <p>Місце: <?=$ticket->place->place?></p>
        <p>Ціна: <?=$ticket->price?> грн</p>
        <button class="button button-icon button-show" onclick="generatePDF('<?= $ticket->sale->seanse->film->title ?>','<?= \App\Data::dateFormat($ticket->sale->seanse->date) ?>','<?= $ticket->sale->seanse->time ?>','<?= $ticket->sale->seanse->hole->nomer ?>','<?= $ticket->place->row ?>','<?= $ticket->place->place ?>','<?= $ticket->price ?>','<?= $ticket->ticket_kod ?>','<?= $ticket->qr_token ?>')" title="Друк"><i class="fa-solid fa-print"></i></button>
    </div>
    <?php 
    }
    ?>
</div>
<div id="qr_temp" style="display:none;"></div>

<script>
function generatePDFAll(data){
    data=data.split('][');
    data.forEach(ticket => {
        generatePDF(ticket.split(',')[0],ticket.split(',')[1],ticket.split(',')[2],ticket.split(',')[3],ticket.split(',')[4],ticket.split(',')[5],ticket.split(',')[6],ticket.split(',')[7],ticket.split(',')[8]);
    });
}
</script>
