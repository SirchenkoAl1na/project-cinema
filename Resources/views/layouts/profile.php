<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Resources/css/index.css">
    <link rel="stylesheet" href="/Resources/css/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.debug.js"></script>
    <title><?php echo $title; ?></title>
</head>

<body>
    <div id="anchor"></div>
    <nav>
        <!-- <div class="label" onclick="location.href='/profile/seanses'">FoxCinema</div> -->
        <img class="logo" src="/Resources/img/logo_by_gemini.png" alt="logo_by_gemini.png" onclick="location.href='/profile/seanses'">
        
        <ul class="nav-items">
            <li><a href="/profile/seanses">Головна</a></li>
            <li><a href="/profile">Профіль</a></li>
            <!-- <li><a href="/profile/basket">Кошик</a></li> -->
            <li><a href="/profile/films/history">Історія</a></li>
            <li><a href="/profile/achievements">Досягнення</a></li>
            <li><a href="/profile/reviews">Відгуки</a></li>
        </ul>
            <div class="userinfo">
                <!-- <a href="/profile/basket" title="Кошик"><i class="fa-solid fa-basket-shopping"></i></a> -->
                <p onclick='location.href="/profile"'><?= $user->login ?></p>
                <a href="/logout">Вийти</a>
                <!-- зміна теми -->
            </div>
    </nav>
    <main class="j-c-start">
        <?php include $childView; ?>
    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="/Resources/js/API.js"></script>
    <script src="/Resources/js/SFS.js"></script>
    
    <script src="/Resources/js/Roboto.js"></script>
    <script src="/Resources/js/Ticket.js"></script>
    
</body>

</html>