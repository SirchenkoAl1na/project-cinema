<div class="row" id="ticket_sell">
<div class="column">
    <div id="ticket_sell_film_info" class="block row">
        <img src="/Resources/img/film_posters/<?= $film->poster ?>" alt="<?= $film->poster ?>">
        <div class="column j-c-start">
            <h3 onclick="location.href='/film?id=<?=$film->id?>'" class="link"><?= $film->title ?></h3>
            <p><b>Жанри: </b> <?= !empty($film->genres) ? $film->genres : ' - ' ?> </p>
            <p><b>Опис: </b> <?= !empty($film->description) ? '<i>'.$film->description.'</i>' : ' - ' ?> </p>   
            <p><b>Зал: </b><?= !is_null($hole) ? $hole->nomer : '' ?></p>    
            <p><b>Дата та час: </b><?=$time.' '.$date ?></p>
        </div>
    </div>
    <div id="hole_places" class="block">    
        <!-- hidden inputs -->
        <input type="hidden" id="seanse_id" value="<?= $seanse->id ?>">
        <!-- <input type="hidden" id="discount" value="<?= $film->id ?>"> -->
        <?php
        foreach ($tickets as $row) {
            ?>
            <div class="row j-c-center">
                <?php
                foreach ($row as $place) {
                    $id = ($place['row']).'_'.($place['place']).'_'.$ticket_price;
                    ?>
                    <input type="checkbox" <?=$place['is_bougth'] ? 'disabled' : ''?> id="<?=$id?>" name="<?=$id ?>" onclick="CheckTicket(this.id)">
                    <label for="<?=$id?>" title="Ряд <?=$place['row']?> місце <?=$place['place']?>"></label>
                    <?php
                }
            ?>
            </div>
            <?php
        }
        ?>
    </div>
</div>
<div id="choosed_tickets" class="block">
        <h3>Кошик</h3>
        
    <div class="column" id="choosed_tickets_list"></div>
    <div class="row a-c-center" id="sum_and_button">
        <h4>Сума: <span id="sum_counter">0</span> грн</h4>
        <button class="button button-save" onclick="BuyTickets()" style="width:50%">Купити</button>
    </div>
</div>
</div>
<script>

    let tickets=[];
    let user_id=<?= isset($user_id) ? $user_id : 'null' ?>; // user_id from session
    let ticketsList=document.getElementById('choosed_tickets_list');
    let sumCounter=document.getElementById('sum_counter');
    let current_sum=0;
    let seanseId=document.getElementById('seanse_id').value;
    let discount=0;
    UpdateTicketList();

    function updateDiscount(newDiscount){
        discount=newDiscount;
        updateSumCounter();
    }
    function updateSumCounter() {
        let sum=current_sum;
        if(sum!=0){
            sum=sum-(sum*discount/100);
        }
        sumCounter.innerHTML=sum;
    }

    function CheckTicket(id){
        let data=id.split("_");
        let row = data[0];
        let place = data[1];
        let price = data[2];
        let exists = tickets.find(x => x.row === row && x.place === place && x.price === price);
        let ticket_id="ticket_"+row+"_"+ place+"_"+price;
        if(!exists){
            tickets.push({
                row:row,
                place:place,
                price:price,
            });
            UpdateTicketList();
        }else{
            let ticket=document.getElementById(ticket_id);
            RemoveTicketFromList(ticket,id[2]);
        }
    }

    function UpdateTicketList(){
        let html="";
        let sum=0;
        tickets.forEach(ticket => {
            let id="ticket_"+ticket.row+"_"+ticket.place+"_"+ticket.price;
            html+='<div class="ticket" id="'+id+'"><button class="remove-btn" onclick="RemoveTicketFromList(this.parentElement,'+ticket.price+')"><i class="fa-solid fa-x"></i></button>';
            html+='<div class="info"><span><b>Ряд:</b>'+ticket.row+'</span>';
            html+='<span><b>Місце:</b>'+ticket.place+'</span></div>';
            html+='<div class="price">'+ticket.price+' грн</div></div>';
            sum+=parseFloat(ticket.price)
        });
        ticketsList.innerHTML=html;
        current_sum=sum.toFixed(2);
        sumCounter.innerHTML = current_sum-(current_sum*discount/100);
    }

    function RemoveTicketFromList(elem,price){
        if (!elem) return;
        elem.remove();
        let id = elem.id; // ticket_ряд_місце_ціна
        let data = id.split("_");
        tickets = tickets.filter(x =>
            !(x.row === data[1] && x.place === data[2] && x.price === data[3])
        );

        UpdateTicketList();

        // uncheck input
        let input=document.getElementById(data[1]+"_"+data[2]+"_"+data[3]);
        input.checked=false;
        
    }

    function BuyTickets(){
        //todo:check
        // TODO: try check client, else store without client_id
        // console.log('tickets',tickets.length);
        if(tickets.length==0){
            alert("Ви не вибрали жодного квитка!");
            return;
        }
        
        let answer=confirm("Щоб замовити квитки, зареєстурйтесь в застосунку або увійдіть в свій акаунт!");
        if(answer) location.href='/register';
        else location.href='/';
        

    }

</script>