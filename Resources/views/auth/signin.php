
<div class="page-auth" style="width:50%;">
<form action="/signin" method="POST">
    <legend>Вхід</legend>
<?= isset($_SESSION['message']['auth']) ? '<p class="msg"> ' . $_SESSION['message']['auth'] . ' </p>' : ''; ?>
    <label>Логін</label>
    <input type="text" name="login" placeholder="Введіть свій логін" class="<?= isset($_SESSION['not_valid']['login'])?"not-valid":"" ?>">
    <?= isset($_SESSION['message']['login']) ? '<p class="msg"> ' . $_SESSION['message']['login'] . ' </p>' : ''; ?>
    <label>Пароль</label>
    <input type="password" name="password" placeholder="Введіть пароль" class="<?= isset($_SESSION['not_valid']['password'])?"not-valid":"" ?>">
    <?php
    echo isset($_SESSION['message']['password']) ? '<p class="msg"> ' . $_SESSION['message']['password'] . ' </p>' : '';
    echo isset($_SESSION['message']['all_form']) ? '<p class="msg"> ' . $_SESSION['message']['all_form'] . ' </p>' : '';
    unset($_SESSION['message']);
    unset($_SESSION['not_valid']);
    ?>
    <button class="btn" type="submit">Увійти</button>
    <!-- <button type='button' class="secondary" onclick='href("/register")'>Реєстрація</button> -->

</form>
</div>
