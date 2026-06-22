<div class="row" id="ticket_sell">
<div class="column">
    <div id="ticket_sell_film_info" class="block row">
        <img src="/Resources/img/film_posters/<?= $film->poster ?>" alt="<?= $film->poster ?>">
        <div class="column j-c-start">
            <h3 onclick="location.href='/profile/film?id=<?=$film->id?>'" class="link"><?= $film->title ?></h3>
            <p><b>Жанри: </b> <?= !empty($film->genres) ? $film->genres : ' - ' ?> </p>
            <p><b>Опис: </b> <?= !empty($film->description) ? '<i>'.$film->description.'</i>' : ' - ' ?> </p>   
            <p><b>Зал: </b><?= !is_null($hole) ? $hole->nomer : '' ?></p>    
            <p><b>Дата та час: </b><?=$time.' '.$date ?></p>
        </div>
    </div>
    <div id="hole_places" class="block">    
    </div>
</div>
<div id="choosed_tickets" class="block">
        <h3>Кошик</h3>
        <p><b>Бонуси:</b> <?= $user->discount ?> балів <i class="hint fa-solid fa-circle-question" title="За один раз можна використати лише до 20 балів. 1 бал = 1 %"></i></p>
        <?php 
        if($user->discount!=0){
            ?>
            <input type="number" value="0" name="discount" id="discount" style="width:100px;" min="0" max="<?= $user->discount>=20?20:$user->discount ?>" onchange="updateDiscount(this.value)">
            <?php
        }
        ?>
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
    let seanseId=<?= $seanse->id ?>;
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
                alerted:false,
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
let maxDiscount=<?=$user->discount?>;

    function BuyTickets(){
        //todo:check
        // TODO: try check client, else store without client_id
        // console.log('tickets',tickets.length);
        if(discount>maxDiscount){
            alert("Ви не можете використати більше бонусів, ніж маєте!");
            return;
            
        }else if(discount>20){
            alert("Ви не можете використати більше 20 бонусів за одне замовлення!");
            return;
        }
        if(tickets.length==0){
            alert("Ви не вибрали жодного квитка!");
            return;
        }
        
        fetch('/api/tickets/buy', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                tickets:tickets,
                seanse_id:seanseId,
                discount:discount,
                user_id:user_id,
                sum:current_sum,})
            }).then(response => {
                if (!response.ok) {
                    throw new Error(`Error: ${response.status}`);
                }
                return response.json();
            }).then((res) => {
                  console.log(res);
                if(res.message!=false) {
                    alert("Квитки успішно заброньовано");
                    window.location.reload();
                    location.href='/profile';
                }else{
                    console.error(res.message);
                    alert("Сталася помилка при покупці квитків");
                }
            })
            .catch(error => {
                console.error(error);
                alert("Сталася помилка при покупці квитків");
            });
          

    }
    let hole_places=document.getElementById('hole_places');
    let ticket_price=150;
function updateTickets(){

    fetch('/api/seanses/tickets?seanse_id=<?= $seanse->id ?>')
    .then(res=>res.json())
    .then(data=>{
        let alert_will_be=false;
        //draw places
        let html='<input type="hidden" id="seanse_id" value="<?= $seanse->id ?>">';
        data.message.tickets.forEach(row=>{
            html+='<div class="row j-c-center">';
            row.forEach(ticket=>{


                let id = ticket.row+"_"+ticket.place+"_"+ticket_price;
                let is_bougth=ticket.is_bougth ? 'disabled' : '';
                let ticket_class='';
                //if some ticket had bougthed
                tickets.forEach(element => {
                    if(element.alerted==false&&element.row==ticket.row&&element.place==ticket.place){
                        if(is_bougth){
                            ticket_class='ticket-bougthed'
                            element.alerted=true;
                            alert_will_be=true;
                        
                        }else{
                            is_bougth='checked'
                        }
                    }
                });

                tickets=[];
                UpdateTicketList();
                html+='<input type="checkbox" '+is_bougth+' id="'+id+'" name="'+id+'" onclick="CheckTicket(this.id)" class="'+ticket_class+'"><label for="'+id+'" title="Ряд '+ticket.row+' місце '+ticket.place+'"></label>';
            });
            html+='</div>';
        });
        hole_places.innerHTML=html;
        if(alert_will_be) alert("Квитки відмічені зеленим були щойно куплені!");
    })
}
    updateTickets();

setInterval(function(){
    updateTickets()
}, 5000)//кожні 10 секунд

</script>