
<?php

use App\Form;
Form::Build([
    [
        '<i style="color:var(--text);">Зірочкою * відмічені обов`язкові поля, які треба додати в першу чергу</i>'
    ],
    [
        [
            'field_type' => 'input',
            'name' => 'title',
            'label' => '*Назва',
            'type' => 'text',
            'attr' => "placeholder='Введіть назву фільму' value='".$film->title."'",
        ],
        [
            'field_type' => 'input',
            'name' => 'original',
            'label' => 'Назва в оригіналі',
            'type' => 'text',
            'attr'=>"onchange='updateIMDbId(this.value)' required value='".($film->original_title??'')."'",
        ],
        [
            'field_type' => 'input',
            'name' => 'imdb_id',
            'label' => 'IMDb id',
            'type' => 'text',
            'attr'=> " id='imdb_rating' value='".($film->imdb_rating??'')."'"
        ],
        [
            'field_type' => 'input',
            'name' => 'poster',
            'label' => 'Зображення (постер)',
            'type' => 'file',
            'attr' => "accept='image/*' onchange='previewImage(event)'",
        ],
    ],
    [
        [
            'field_type' => 'input',
            'name' => 'primiere_date',
            'label' => '*Дата прим`єри',
            'type' => 'date',
            'attr' => "value='".($film->primiere_date??'')."'",
        ],
        [
            'field_type' => 'input',
            'name' => 'end_date',
            'label' => 'Дата завершення прокату (планова)',
            'type' => 'date',
            'attr' =>" value='".($film->end_date??'')."'",
        ]
    ],
    [
        [
            'field_type' => 'textarea',
            'name' => 'description',
            'label' => 'Опис',
            'type' => 'text',
            'attr' => "placeholder='Введіть опис фільму' value='".$film->description."'",
        ],
        [
            'field_type' => 'selectmultiple',
            'name' => 'genre',
            'label' => 'Жанри',
            'attr' => "class='select2' multiple='multiple'",
            'options' => $genres,
            'values'=>explode(', ',$film->genres),
        ]
    ],
    [
        [
            'field_type' => 'input',
            'name' => 'duration',
            'label' => 'Тривалість (хвилини)',
            'type' => 'number',
            'attr' => "value='".$film->duration."' min='0' required max='360'",
        ],
        [
            'field_type' => 'input',
            'name' => 'country',
            'label' => 'Країни',
            'type' => 'text',
            'attr' => "placeholder='Введіть через кому' value='".$film->country."'",
        ]
    ],
    [
        [
            'field_type' => 'input',
            'name' => 'director',
            'label' => 'Режисери',
            'type' => 'text',
            'attr' => "placeholder='Введіть через кому' value='".$film->director."'",
        ],
        [
            'field_type' => 'input',
            'name' => 'actors',
            'label' => 'Актори',
            'type' => 'text',
            'attr' => "placeholder='Введіть через кому' value='".implode(', ',$film->actors)."'",
        ]
    ],
], "/admin/films/update?id=".$film->id);
?>
<script>
    let IMDbApiKey='5a9cada9';

    function updateIMDbId(title){
        let imdb_rating=document.getElementById('imdb_rating');
        imdb_rating.value='Шукаємо...';

        // далі переписати
        fetch('https://www.omdbapi.com/?t='+encodeURIComponent(title)+'&apikey='+IMDbApiKey)
        .then(res=>res.json())
        .then(data=>{
            if(data.Response=="True"){
                imdb_rating.value=data.imdbID;
            }
        })

    }
</script>