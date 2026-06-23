<div class="row">
<div class="column">
    <div id="film_info" class="block row">
        <img src="/Resources/img/film_posters/<?= $film->poster ?>" alt="<?= $film->poster ?>">
        <div class="column j-c-start">
            <h3><?= $film->title ?></h3>
            <p><b>Жанри: </b> <?= !empty($film->genres) ? $film->genres : ' - ' ?> </p>
            <p><b>Опис: </b> <?= !empty($film->description) ? '<i>'.$film->description.'</i>' : ' - ' ?> </p>   
            <p><b>Зал: </b><?= !is_null($hole) ? $hole->nomer : '' ?></p>    
            <p><b>Дата та час: </b><?=$time.' '.$date ?></p>
        </div>
    </div>
    <div id="hole_places" class="block">    
    </div>
</div>
<div class="column" style="width:400px">
    <div id="client_info" class="block">
        
    <div class="form-control">
            <label for="client_name"><b>Ім'я клієнта:</b></label>
            <input type="name" id="client_name" onchange="updateClientName(this.value)">
        </div>
        <!-- <i style="color:var(--text);">*Введіть хоча б одне з наступних полів</i> -->
        <div class="form-control">
            <label for="client_phone"><b>Телефон клієнта:</b></label>
            <input type="phone" id="client_phone" onchange="updateClientPhone(this.value)">
        </div>

        <div class="form-control">
            <label for="client_email"><b>Електрона адреса:</b></label>
            <input type="email" id="client_email" onchange="updateClientEmail(this.value)">
        </div>
    </div>
    <div id="choosed_tickets" class="block">
            <h3>Кошик</h3>
            <div id="discount_block" style="display:none;">
                <p><b>Бонуси:</b> <span></span> балів <i class="hint fa-solid fa-circle-question" title="За один раз можна використати лише до 20 балів. 1 бал = 1 %"></i></p>
                <input type="number" value="0" name="discount" id="discount" style="width:100px;" min="0" max="" onchange="updateDiscount(this.value)">
            </div>
        <div class="column" id="choosed_tickets_list"></div>
        <div class="row a-c-center">
            <h4>Сума: <span id="sum_counter">0</span> грн</h4>
            <button class="button button-save" onclick="BuyTickets()">Продати</button>
        </div>
    </div>

</div>
</div>
<script>

    let tickets=[];
    let employer_id='<?= $_SESSION['user']['id'] ?>';
    let seanse_id='<?= $seanse->id ?>';
    let ticketsList=document.getElementById('choosed_tickets_list');
    let sumCounter=document.getElementById('sum_counter');
    let current_sum=0;
    let discountBlock=document.getElementById('discount_block');
    let seanseId=<?= $seanse->id ?>;
    let discount=0;
    UpdateTicketList();

    let clientName="";
    let clientPhone="";
    let clientEmail="";

    function updateClientName(value){
        clientName=value;
    }
    function updateClientPhone(value){
        clientPhone=value;
        FindClient();
    }
    function updateClientEmail(value){
        clientEmail=value;
        FindClient();
    }

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
        console.log("sum",sumCounter);
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

    function BuyTickets() {
        if (tickets.length === 0) {
            alert("Ви не вибрали жодного місця!");
            return;
        } else if (clientName == ''&& clientEmail != '') {
            alert("Введіть ім'я клієнта!");
            return;
        }else if (clientName == ''&& clientPhone != '') {
            alert("Введіть ім'я клієнта!");
            return;
        } else if (clientName != '' && clientEmail == ''&&clientPhone == '') {
            alert("Введіть телефон або електронну адресу клієнта!");
            return;
        }

        fetch('/api/tickets/buy', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                employer_id:employer_id,
                tickets: tickets,
                seanse_id: seanseId,
                discount: discount,
                client_name: clientName,
                client_phone: clientPhone,
                client_email: clientEmail,
                sum: parseFloat(sumCounter.innerHTML)
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error: ${response.status}`);
            }
            return response.json();
        })
        .then(result => {
            window.location.reload();
            location.href='/cashier/tickets/print?row='+tickets[0].row+'&place='+tickets[0].place+'&seanse_id='+seanseId;
        })
        .catch(error => {
            console.error(error);
            alert("Сталася помилка при покупці квитків");
        });
    }

    function FindClient(){
        fetch('/api/clients/find?phone='+clientPhone+'&email='+clientEmail, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }})
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error: ${response.status}`);
                }
                return response.json();
            }).
            then(res=>{
                console.log('client',res);
                if(res.data!=null){
                discountBlock.style.display='block';
                discountBlock.querySelector('span').innerHTML=res.data.discount;
                document.getElementById('discount').max=res.data.discount;
                }else{
                discountBlock.style.display='none';
                discountBlock.querySelector('span').innerHTML=0;
                document.getElementById('discount').max=0;

                }
            })
            .catch(error => {
                console.error(error);
                alert("Сталася помилка при пошуку клієнта");
        })
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
                          //  is_bougth='checked'
                        }
                    }
                });

                html+='<input type="checkbox" '+is_bougth+' id="'+id+'" name="'+id+'" onclick="CheckTicket(this.id)" class="'+ticket_class+'"><label for="'+id+'" title="Ряд '+ticket.row+' місце '+ticket.place+'"></label>';
            });
            html+='</div>';
        });
        hole_places.innerHTML=html;if(alert_will_be) {
                tickets=[];
                UpdateTicketList();

          alert("Квитки відмічені зеленим були щойно куплені!");  
        }
    })
}
    updateTickets();

setInterval(function(){
    updateTickets()
}, 5000)//кожні 10 секунд
</script>