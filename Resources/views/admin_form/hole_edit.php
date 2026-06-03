<?php

use App\Form;

use App\Data;

Form::Build([
    [
        [
            'field_type' => 'number',
            'name' => 'nomer',
            'label' => 'Номер залу',
            'type' => 'text',
            "attr"=>"required value='".(!isset($_SESSION['message']['nomer'])?$hole->nomer:$_SESSION['message']['old_values']['nomer'])."'",
        ],
        [
            'field_type' => 'select',
            'name' => 'status',
            'label' => 'Статус (не обов`язково)',
            'options' => Data::$holeStatuses,
            "attr"=>"",
            "value"=>!isset($_SESSION['message']['nomer'])?$hole->status:$_SESSION['message']['old_values']['status']
        ]
    ],
    [
        isset($_SESSION['message']['nomer']) ? '<p class="msg"> ' . $_SESSION['message']['nomer'] . ' </p>' : ''
    ],
    [
        '<div class="block not-visible column a-c-center"><div id="hole_places" class="column j-c-center a-c-center"></div><button type="button" class="button button-add" onclick="AddHoleRow()">Додати ряд</button></div>'
    ],
    [
        [
            'field_type' => 'input',
            'name' => 'hole_places',
            'label' => '',
            'type' => 'text',
            "attr"=>"id='input_hole_places' value='".(!isset($_SESSION['message']['nomer'])?implode(',',$hole->rows()):$_SESSION['message']['old_values']['hole_places'])."' style='display:none;'",
        ],
        [
            'field_type' => 'input',
            'name' => 'id',
            'label' => '',
            'type' => 'text',
            "attr"=>"id='hole_id' value='".$hole->id."' style='display:none;'",
        ],
    ]
], "/admin/holes/update?id=".$hole->id);

unset($_SESSION['message']);
unset($_SESSION['not_valid']);
?>

<script>
    let hole=document.getElementById('hole_places');
    let inputHole=document.getElementById('input_hole_places');
    let places = [inputHole.value.split(',').map(x=>parseInt(x))].flat().filter(x=>!isNaN(x) && x>0); // convert to array of numbers, remove NaN and 0
 console.log(places);
    DrawHole();
    // todo: maybe add number of places
    function DrawHole(){
        let html='';
        places.forEach((row,row_id) => {
            html+='<div class="row a-c-center hole-row" id="row_'+row_id+'">';
            for (let place_id = 0; place_id < row; place_id++) {
                html+='<div class="hole-place" id="place_'+row_id+'_'+place_id+'" onclick="RemoveHolePlace(this.parentElement)">X</div>'
            }
            html+='<button class="button button-add" title="Додати місце" onclick="AddHolePlace(this.parentElement)">+</button>';
            html+='<button class="button button-hide" title="Видалити ряд" onclick="RemoveHoleRow(this.parentElement)">x</button></div>';
        });
        // html+='<button class="button button-add" onclick="AddHoleRow()">Додати ряд</button>'
        hole.innerHTML=html;
        inputHole.value=places;
    }
    function RemoveHolePlace(row)
    {
        let rowId=row.id.split('_')[1];
        places[rowId]--;
        if(places[rowId]==0){
            places=places.filter((x,index)=>index!=rowId);
        }
        DrawHole();
    }
    function AddHolePlace(row)
    {
        let rowId=row.id.split('_')[1];
        places[rowId]++;
        DrawHole();
    }
    function RemoveHoleRow(row)
    {
        let rowId=row.id.split('_')[1];
        places=places.filter((x,index)=>index!=rowId);
        DrawHole();
    }
    function AddHoleRow()
    {
        if(places.length==9){//29
            alert('Не можна додати більше 10 рядів');//30
        }else{
        places.push(1);
        DrawHole();
        }
    }
</script>

