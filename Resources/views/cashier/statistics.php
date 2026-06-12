
<?php  
use App\Data;
$month= Data::$months_full_ua[date('m')];
$year= date('Y');
$current_month= "$month $year";
?>
<div class="row text-grey">
    <div class="column block">
            <h4>Придбано квитків за день:</h4>
        <div id="chart1" style="min-height:400px;width:98%"></div>
    </div>
    <div class="column">
        <div class="row">
            <div class="block w-full column j-c-around">
                <h4>Прибуток за день</h4>
                <i><?= isset($profit['total_profit'])?$profit['total_profit']." грн":'Не визначено' ?></i>
            </div>
            <div class="block w-full column j-c-around">
                <h4>Найприбутковіший сеанс</h4>
                <i><?= isset($the_most_popular_seanse)?$the_most_popular_seanse['film'].' ('.$the_most_popular_seanse['tickets_sold'].' квитків)'.' '.$the_most_popular_seanse['time']:'Не визначено' ?></i>
            </div>
        </div>
        <div class="row">
            <div class="block w-full column j-c-around">
                <h4>Продано квитків всього</h4>
                <i><?= $profit['total_tickets']??'Не визначено' ?></i>
            </div>
            <div class="block w-full column j-c-around">
                <h4>Продано квитків у кінотеатрі</h4>
                <i><?= $profit['cashier_tickets']??'Не визначено' ?></i>
            </div>
        </div>

    </div>
</div>

<script>
var options = {
          series: [<?=$profit['online_tickets']??0?>, <?=$profit['cashier_tickets']??0?>],
          chart: {
          width: 380,
          type: 'pie',
        },
        labels: ['Онлайн', 'В кінотеатрі'],
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            },
            legend: {
              position: 'bottom',
            }
          }
        }]
        };

        var chart = new ApexCharts(document.querySelector("#chart1"), options);
        chart.render();
      

</script>