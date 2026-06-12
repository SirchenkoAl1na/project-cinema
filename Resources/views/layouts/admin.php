<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Resources/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">   
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
        .select2{
            width: 100%;
        }
        .select2-results__option{
            color:black;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
    <title><?php echo $title; ?></title>
</head>

<body>
    <!-- Гамбургер-кнопка (тільки мобільні) -->
    <button class="sidebar-toggle" id="sidebarToggle" aria-label="Меню">&#9776;</button>
    <!-- Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <div class="sidebar" id="sidebar">
        <div class="header">
            <h2>Кабінет адміністратора</h2>
        </div>
        <nav>
            <ul>
                <li onclick="location.href='/admin'"><i class="fa-solid fa-house"></i><a href="/admin">Головна</a></li>
                <li onclick="location.href='/admin/statistics'"><i class="fa-solid fa-chart-pie"></i><a href="/admin/statistics">Фінанси</a></li>
                <li onclick="location.href='/admin/holes'"><i class="fa-solid fa-person-shelter"></i><a href="/admin/holes">Зали</a></li>
                <li onclick="location.href='/admin/films'"><i class="fa-solid fa-film"></i><a href="/admin/films">Фільми</a></li>
                <li onclick="location.href='/admin/achievements'"><i class="fa-solid fa-trophy"></i><a href="/admin/achievements">Досягнення</a></li>
                <!-- <li><i class="fa-solid fa-glasses"></i><a href="/admin/clients">Клієнти</a></li> -->
                <li onclick="location.href='/admin/reviews'"><i class="fa-solid fa-comment"></i><a href="/admin/reviews">Відгуки</a></li>
                <li onclick="location.href='/admin/employee'"><i class="fa-solid fa-id-badge"></i><a href="/admin/employee">Працівники</a></li>
                <li onclick="location.href='/admin/scanner'"><i class="fa-solid fa-qrcode"></i><a href="/admin/scanner">Сканер QR</a></li>
            </ul>
        </nav>
        <div class="footer">
            <a href="/admin/profile"><?= $_SESSION['user']['full_name'] ?></a>
            <!-- TEST CHANGING THEME -->
            <!-- <button class="button button-icon button-chenge-theme" onclick="toggleTheme()"><i class="fa-solid fa-sun"></i></button> -->
            <a href="/logout" class="btn"><ion-icon name="log-out-outline"></ion-icon></a>
            <!-- change ion icons -->
        </div>
    </div>
    <main>
        <h1><?= $title ?></h1>
        <?php include($childView); ?>
    </main>
    <script src="/Resources/js/Model.js"></script>
    <script src="/Resources/js/Modal.js"></script>
    <script src="/Resources/js/API.js"></script>
    <script src="/Resources/js/Other.js"></script>
    <script src="/Resources/js/SFS.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <script>
    (function(){
        var btn = document.getElementById('sidebarToggle');
        var sidebar = document.getElementById('sidebar');
        var overlay = document.getElementById('sidebarOverlay');
        function close(){ sidebar.classList.remove('open'); overlay.classList.remove('active'); }
        btn.addEventListener('click', function(){
            var isOpen = sidebar.classList.toggle('open');
            overlay.classList.toggle('active', isOpen);
        });
        overlay.addEventListener('click', close);
    })();
    
    $(document).ready(function() {
        $('.select2').select2();
    });
    </script>
</body>

</html>