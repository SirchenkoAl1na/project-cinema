<div class="form">
    <button style="width:100px" onclick="location.href='/profile'">Назад</button>
<?php

use App\Form;

Form::Build([
    [
        [
            'field_type' => 'input',
            'name' => 'full_name',
            'label' => 'Прізвище та ім`я',
            'type' => 'text',
            'attr' => "value='" . $user->full_name . "' required",
        ],
        [
            'field_type' => 'input',
            'name' => 'login',
            'label' => 'Логін',
            'type' => 'text',
            'attr' => "value='" . $user->login . "' required",
        ],
    ],
    [
        [
            'field_type' => 'input',
            'name' => 'email',
            'label' => 'Ел.адреса',
            'type' => 'email',
            'attr' => "value='" . $user->email . "' required",
        ],
        [
            'field_type' => 'input',
            'name' => 'phone',
            'label' => 'Номер',
            'type' => 'phone',
            'attr' => "value='" . $user->phone . "' required",
        ],
    ],
], "/profile/store");
?></div>