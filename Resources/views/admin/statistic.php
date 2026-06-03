
<?php  
use App\Data;
$month= Data::$months_full_ua[date('m')];
$year= date('Y');
$current_month= "$month $year";
?>
<div class="column text-grey" >
    <div class="row">
        <div class="block w-full column j-c-between" style="min-height:150px;padding:20px 10px;">
            <h4>Прибуток</h4>
            <p class="text-bold"><?= isset($profit)?$profit." грн":'Не визначено' ?></p>
            <p>за <?= $current_month ?></p>
        </div>
        <div class="block w-full column j-c-between" style="min-height:150px;padding:20px 10px;">
            <h4>Продано квитків</h4>
            <p class="text-bold"><?= $sold_tickets??'Не визначено' ?></p>
            <p>за <?= $current_month ?></p>
        </div>
        <div class="block w-full column j-c-between" style="min-height:150px;padding:20px 10px;">
            <h4>Середня ціна квитка (враховуючи знижку)</h4>
            <p class="text-bold"><?= isset($average_ticket_price)?$average_ticket_price." грн":'Не визначено' ?></p>
            <p>за <?= $current_month ?></p>
        </div>
        <div class="block w-full column j-c-between" style="min-height:150px;padding:20px 10px;">
            <h4>Найприбутковіші фільми</h4>
            <?php 
            if(!isset($top_films)||empty($top_films)){
                echo "<div class='empty-data' style='margin:0'><h3>Не знайдено даних</h3></div>";
            }else{
              ?>

            <ol style="margin-left:30px">
            <?php 
            for($i=0;$i<count($top_films);$i++){
            ?>
                <li><?= $top_films[$i]['title'] .' - '. $top_films[$i]['profit'].' грн' ?></li>
            <?php 
            }
            ?>
            </ol>
              <?php
            }
            
            ?>
    </div>
    </div>
    <!-- TODO: later -->
    <div class="row" style="margin-top:10px;">
        <div class="block" id="chart1" style="min-height:300px;width:100%"></div>
        <!-- <div class="block" id="chart2" style="min-height:300px;width:30%;">[пай з відотками прибутку]</div> -->
    </div>
</div>

<script>

  let profits=[];
  let count_sold_tickets=[];
  let count_of_seanses=[];

  let data1={
    months: <?= '[\''.implode('\',\'',$data_half_year['months']).'\']' ?>,
    data: <?= '['.implode(',',$data_half_year['data']).']' ?>,
  };
  let data2={
    data: [],
  };
  var options = {
          series: [
          {
            name: "Прибуток",
            data: data1.data
          }
        ],
          chart: {
          height: 450,
          width: '100%',
          type: 'line',
          dropShadow: {
            enabled: true,
            color: '#000',
            top: 18,
            left: 7,
            blur: 10,
            opacity: 0.5
          },
          zoom: {
            enabled: false
          },
          toolbar: {
            show: false
          }
        },
        colors: ['#77B6EA', '#545454'],
        dataLabels: {
          enabled: true,
        },
        stroke: {
          curve: 'smooth'
        },
        title: {
          text: 'Гістограма прибутку за останні півроку',
          align: 'left'
        },
        grid: {
          borderColor: '#e7e7e7',
          row: {
            colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
            opacity: 0.5
          },
        },
        markers: {
          size: 1
        },
        xaxis: {
          categories: data1.months,
          title: {
            text: 'Місяць'
          }
        },
        yaxis: {
          title: {
            text: 'Прибуток'
          },
          min: 5,
          max: 40
        },
        legend: {
          position: 'top',
          horizontalAlign: 'right',
          floating: true,
          offsetY: -25,
          offsetX: -5
        }
        };

        var chart = new ApexCharts(document.querySelector("#chart1"), options);
        chart.render();


        // var options = {
        //   series: data2.data,
        //   chart: {
        //   type: 'donut',
        // },
        // responsive: [{
        //   breakpoint: 480,
        //   options: {
        //     chart: {
        //       width: 200
        //     },
        //     legend: {
        //       position: 'bottom'
        //     }
        //   }
        // }]
        // };

        // var chart = new ApexCharts(document.querySelector("#chart2"), options);
        // chart.render();
      
      

</script>