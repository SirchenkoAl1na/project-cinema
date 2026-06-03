<?php

use App\Form;

Form::Build([
    [
        [
            'field_type' => 'select',
            'name' => 'film_id',
            'label' => 'Фільм',
            'options' => $films,
            'value'=>$film_id,
        ],
        [
            'field_type' => 'select',
            'name' => 'hole_id',
            'label' => 'Зал',
            'options' => $holes,
        ]
    ],
    [
        [
            'field_type' => 'input',
            'name' => 'date',
            'label' => 'Дата',
            'type' => 'date',
            'attr' => "value='$tomorrow' min='$tomorrow'",
        ],
        [
            'field_type' => 'input',
            'name' => 'time',
            'label' => 'Час',
            'type' => 'time',
            'attr' => ' min="'.$cinemaOpen.'" max="'.$cinemaClose.'"',
        ]
    ],
], "/admin/seanses/store");
