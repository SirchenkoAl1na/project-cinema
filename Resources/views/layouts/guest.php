<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Resources/css/index.css">
    <link rel="stylesheet" href="/Resources/css/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title><?php echo $title; ?></title>
</head>

<body>
    <nav>
        <!-- <div class="label" onclick="location.href='/profile/seanses'">FoxCinema</div> -->
        <img class="logo" src="/Resources/img/logo_by_gemini.png" alt="logo_by_gemini.png" onclick="location.href='/profile/seanses'">
        <!-- <img class="logo" src="/Resources/img/logo_by_chatGPT.png" alt="logo_by_chatGPT.png" onclick="location.href='/profile/seanses'"> -->
        
            <div class="auth">
                <a href="/register">Реєстрація</a>
                <a href="/login">Вхід</a>
            </div>
    </nav>
    <main class="j-c-start">
        <?php include($childView); ?>
    </main>
    <script src="/Resources/js/API.js"></script>
    <script src="/Resources/js/SFS.js"></script>
</body>

</html>