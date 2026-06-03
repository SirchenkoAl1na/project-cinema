<h1 class="page-title"><?php echo $title; ?></h1>

<div class="block column" style="width:70%">
<?php
use App\Data;
use App\Models\Ticket;
use App\Models\Sale;
if(!empty($seanses)){
foreach ($seanses as $sale) {
    ?>

<div class="seanse-history-item">
    <div class="shi-tickets">
        <?php
        foreach($sale->tickets() as $ticket){
        $ticket=new Ticket($ticket);
        ?>
        <div class="ticket" id="ticket_1_2_150">
            <div class="info">
                <span><b>Ряд: </b><?= $ticket->place->row ?></span>
                <span><b>Місце: </b><?= $ticket->place->place ?></span>
            </div>
            <div class="price"><?= Data::$ticket_price ?> грн</div>
        </div>
        <?php
        }
        ?>
    </div>
    <div class="shi-info">
        <h4 onclick="location.href='/profile/film?id=<?=$sale->seanse->film_id?>'"><?= $sale->seanse->film->title ?></h4>
        <?php
        $sum=$sale->sum-($sale->sum*$sale->discount/100);
        
        ?>
        <p><b>Сума: </b><?= $sum ?> грн <?= $sale->discount!=0?"(Знижка ".$sale->discount."%)":"" ?></p>
        <p><b>Дата та час: </b><?= Data::datetimeFormat($sale->seanse->date,$sale->seanse->time) ?></p>
    </div>
    <div class="shi-buttons">
        <button class="button button-icon" onclick="location.href='/profile/film?id=<?=$sale->seanse->film->id?>'" title="Залишити відгук">
            <i class="fa-solid fa-film"></i>
        </button>
    </div>


</div>


<?php
}
}else{
    ?>
    <div class="empty-list"><p>Поки ви не переглядали фільмів</p></div>
    <?php
}
?>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>

<script>
async function generatePDF(filmTitle, date, time, tickets_info) {
    const {jsPDF} = window.jspdf;

    const doc= new jsPDF();

    const robotoFontBase64 = ... // load the *.ttf font file as binary string
    // *** Font Embedding ***
    doc.addFileToVFS('Roboto-Regular.ttf', robotoFontBase64);
    doc.addFont('Roboto-Regular.ttf', 'Roboto', 'normal'); 
    doc.setFont('Roboto');

    doc.text(100,20,filmTitle);
    doc.text(20,30,'Дата: ' + date);
    doc.text(20,40,'Час: ' + time);
    console.log(tickets_info);
    doc.save("Квитки.pdf")
}
</script>