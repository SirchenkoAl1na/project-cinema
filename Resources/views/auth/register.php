
<div class="page-auth" style="width:45%;max-width:50%;"><form action="/signup" method="POST" enctype="multipart/form-data">
    
    <p>Зірочкою * відмічені НЕобов'язкові поля</p>

    <label for="full_name">Ім'я та прізвище</label>
    <input type="text" name="full_name" id="full_name" placeholder="" value="<?=$_SESSION['message']['old_values']['full_name']??'' ?>">
    <?= isset($_SESSION['message']['full_name']) ? '<p class="msg"> ' . $_SESSION['message']['full_name'] . ' </p>' : ''; ?>
    
    <label for="login">Логін</label>
    <input type="text" name="login" id="login" placeholder="" value="<?=$_SESSION['message']['old_values']['login']??'' ?>">
    <?= isset($_SESSION['message']['login']) ? '<p class="msg"> ' . $_SESSION['message']['login'] . ' </p>' : ''; ?>
    
    <label for="email">Ел.пошта</label>
    <input type="email" name="email" id="email" placeholder=""  value="<?=$_SESSION['message']['old_values']['email']??'' ?>">
    <?= isset($_SESSION['message']['email']) ? '<p class="msg"> ' . $_SESSION['message']['email'] . ' </p>' : ''; ?>

    
    <label for="phone">*Номер телефону</label>
    <input type="phone" name="phone" id="phone" placeholder="" value="<?=$_SESSION['message']['old_values']['phone']??'' ?>">
    <?= isset($_SESSION['message']['phone']) ? '<p class="msg"> ' . $_SESSION['message']['phone'] . ' </p>' : ''; ?>
    
    <label for="password">Пароль</label>
    <input type="password" name="password" id="password" placeholder="" value="<?=$_SESSION['message']['old_values']['password']??'' ?>">
    
    <label for="password_confirm">Пітвердження пароля</label>
    <input type="password" name="password_confirm" id="password_confirm" placeholder="" value="<?=$_SESSION['message']['old_values']['password_confirm']??'' ?>">
    <?= isset($_SESSION['message']['password']) ? '<p class="msg"> ' . $_SESSION['message']['password'] . ' </p>' : ''; ?>
    <?= isset($_SESSION['message']['password_confirm']) ? '<p class="msg"> ' . $_SESSION['message']['password_confirm'] . ' </p>' : ''; ?>
    <?php
    echo isset($_SESSION['message']['all_form']) ? '<p class="msg"> ' . $_SESSION['message']['all_form'] . ' </p>' : '';
    unset($_SESSION['message']);
    unset($_SESSION['not_valid']);
    ?>
    <button class="btn" type="submit">Зареєструватися</button>
    <!-- <button type="button" class="secondary" onclick='href("/")'>Авторизація</button> -->
   
</form></div>
