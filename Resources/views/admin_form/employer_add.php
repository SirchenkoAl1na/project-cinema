<?php

use App\Data;
use App\Form;

Form::Build([
    [
        '<i style="color:var(--text);">Зірочкою * відмічені обов`язкові поля, які треба додати </i>'
    ],
    [
        [
            'field_type' => 'input',
            'name' => 'full_name',
            'label' => '*Прізвище ім`я по батькові',
            'type' => 'text',
            "attr"=>"required",
        ],
        [
            'field_type' => 'input',
            'name' => 'login',
            'label' => '*Логін',
            'type' => 'text',
            "attr"=>"required",
        ]
    ],
    [
        [
            'field_type' => 'input',
            'name' => 'email',
            'label' => '*Ел.адреса',
            'type' => 'email',
            'attr'=>"autocomplete='off' required"
        ],
        [
            'field_type' => 'input',
            'name' => 'phone',
            'label' => '*Номер телефону',
            'type' => 'phone',
            "attr"=>"required",
        ]
    ],
    [
        [
            'field_type' => 'select',
            'name' => 'posada',
            'label' => '*Посада',
            'options' => Data::$positions,
            "attr"=>"required",
        ],
        [
            'field_type' => 'input',
            'name' => 'zarplata',
            'label' => 'Заробітна плата',
            'type' => 'number',
            'attr' => "required min='0'",
        ],
        [
            'field_type' => 'input',
            'name' => 'password',
            'label' => '*Пароль',
            'type' => 'password',
            'attr'=>"autocomplete='off' required"
        ]
    ],
    // [
    //     [
    //         'field_type' => 'textarea',
    //         'name' => 'shedule',
    //         'label' => 'Розклад',
    //     ],
    // ],
], "/admin/employee/store",
    'Зареєструвати');
    
    
