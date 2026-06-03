<?php

use App\Form;

Form::Build([
    [
        [
            'field_type' => 'input',
            'name' => 'password',
            'label' => 'Пароль',
            'type' => 'password',
            'attr'=>"autocomplete='off' required"
        ],
        [
            'field_type' => 'input',
            'name' => 'password_confirm',
            'label' => 'Повторіть пароль',
            'type' => 'password',
            'attr'=>"autocomplete='off' required placeholder=''"
        ]
    ],
    [
        isset($_SESSION['message']['password']) ? '<p class="msg"> ' . $_SESSION['message']['password'] . ' </p>' : ''
    ]
], "/admin/employee/new-password/save?id=".$employer_id,"Оновити пароль");

        unset($_SESSION['message']);
        unset($_SESSION['not_valid']);
?>

<script>
    
</script>

