<?php

use App\Data;
use App\Form;

Form::Build([
    [
        '<i style="color:var(--text);">Зірочкою * відмічені поля, які обов`язково треба заповнити </i>',
    ],
    [
        [
            'field_type' => 'input',
            'name' => 'full_name',
            'label' => '*Прізвище ім`я по батькові',
            'type' => 'text',
            'attr' => "required value='".$employer->user->full_name."'",
        ],
        [
            'field_type' => 'input',
            'name' => 'login',
            'label' => '*Логін',
            'type' => 'text',
            'attr' => "required value='".$employer->user->login."'",
        ],
    ],
    [
        [
            'field_type' => 'input',
            'name' => 'zarplata',
            'label' => 'Заробітна плата',
            'type' => 'number',
            'attr' => "required min='0' value='".$employer->zarplata."'",
        ],
        [
            'field_type' => 'input',
            'name' => 'email',
            'label' => '*Ел.адреса',
            'type' => 'email',
            'attr' => "autocomplete='off' required value='".$employer->user->email."'",
        ],
    ],
    [
        [
            'field_type' => 'input',
            'name' => 'phone',
            'label' => '*Номер телефону',
            'type' => 'phone',
            'attr' => "required value='".$employer->user->phone."'",
        ],
        [
            'field_type' => 'select',
            'name' => 'posada',
            'label' => '*Посада',
            'options' => Data::$positions,
            'attr' => 'required',
            'value' => $employer->posada,
        ],
    ],
    // [
    //     [
    //         'field_type' => 'textarea',
    //         'name' => 'shedule',
    //         'label' => 'Розклад',
    //     ],
    // ],
], '/admin/employee/update?id='.$employer->user_id,
    'Оновити');
