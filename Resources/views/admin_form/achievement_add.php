<?php

use App\Form;
use App\Data;

Form::Build([
    [
        '<i style="color:var(--text);">Зірочкою * відмічені поля, які треба обов`язково додати</i>'
    ],
    [
        [
            'field_type' => 'input',
            'name' => 'title',
            'label' => '*Назва',
            'type' => 'text',
            'attr'=>'required'
        ],
        [
            'field_type' => 'input',
            'name' => 'image_title',
            'label' => '*Зображення',
            'type' => 'file',
            'attr'=>'required'
        ],
    ],
    [
        [
            'field_type' => 'select',
            'name' => 'triger',
            'label' => '*Тригер',
            'options' => Data::$achievementsTrigers,
            'attr'=>'required onchange="changeTriger(this)"'
        ],
        [
            'field_type' => 'input',
            'name' => 'triger_detail',
            'label' => 'Деталі',
            'type' => 'text',
            'attr'=>'id="triger_detail"'
        ],
        [
            'field_type' => 'textarea',
            'name' => 'level_description',
            'label' => '*Опис ',
            'type' => 'text',
            'attr'=>'required placeholder="Введіть опис досягнення, зрозумілий для користувача, та умови отримання" '
        ],
    ],
    [
        [
            'field_type' => 'input',
            'name' => 'number_for_goal',
            'label' => 'Цільова кількість для отримання <i class="hint fa-solid fa-circle-question" title="Введіть це значення, тільки якщо для отримання створюваного досягнення треба відвідати кілька сеансів"></i>',
            'type' => 'number',
            'attr'=>''
        ],
        [
            'field_type' => 'input',
            'name' => 'discount',
            'label' => '*Знижка',
            'type' => 'number',
            'attr'=>"max='15' placeholder='До 15% максимум'"
        ],
    ],
], "/admin/achievements/store");


?>
<script>
        let detail_input=document.getElementById("triger_detail");
        console.log("detail_input",detail_input);
        let detail_form_control=detail_input.parentElement;
        console.log("detail_form_control",detail_form_control);
        //document load
        document.addEventListener("DOMContentLoaded", function() {
            detail_form_control.style.display="none";
        });
    function changeTriger(triger){
        let value=triger.value;
        console.log("Обрано тригер ",value);
        if(value=="film_genre"){
            detail_form_control.style.display="flex";
            detail_input.setAttribute("placeholder","Введіть жанр фільму");
            detail_input.setAttribute("type","text");
            detail_input.setAttribute("required","");
        }
        else if(value=="few_tickets"){
            detail_form_control.style.display="flex";
            detail_input.setAttribute("placeholder","Введіть кількість квитків");
            detail_input.setAttribute("type","number");
            detail_input.setAttribute("required","");
        }
        else if(value=="review"){
            detail_form_control.style.display="flex";
            detail_input.setAttribute("placeholder","Введіть кількість відгуків");
            detail_input.setAttribute("type","number");
            detail_input.setAttribute("required","");
        }
        else if(value=="film"){
            detail_form_control.style.display="flex";
            detail_input.setAttribute("placeholder","Введіть точну назву фільму");
            detail_input.setAttribute("type","text");
            detail_input.setAttribute("required","");
        }
        else if(value=="time"){
            detail_form_control.style.display="flex";
            detail_input.setAttribute("placeholder","Введіть години у форматі: 8-10 або 20-22");
            detail_input.setAttribute("type","text");
            detail_input.setAttribute("required","");
        }
        else{
            detail_form_control.style.display="none";

        }
        return;
    }
</script>