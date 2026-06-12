<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Resources/css/admin.css">
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.debug.js"></script>

    <title><?php echo $title; ?></title>
</head>

<body>
    <button class="sidebar-toggle" id="sidebarToggle" aria-label="Меню">&#9776;</button>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <div class="sidebar" id="sidebar">
        <div class="header">
            <h2>Кабінет касира</h2>
        </div>
        <nav>
            <ul>
                <li onclick="location.href='/cashier'"><i class="fa-solid fa-house"></i><a href="/cashier">Головна</a></li>
                <li onclick="location.href='/cashier/holes'"><i class="fa-solid fa-person-shelter"></i><a href="/cashier/holes">Зали</a></li>
                <!-- <li><i class="fa-solid fa-ticket"></i><a href="/cashier/tickets/sell">Квитки</a></li> -->
                <li onclick="location.href='/cashier/tickets'"><i class="fa-solid fa-clock-rotate-left"></i><a href="/cashier/tickets">Історія</a></li>
                <li onclick="location.href='/cashier/statistics'"><i class="fa-solid fa-chart-pie"></i><a href="/cashier/statistics">Фінанси</a></li>
                <!-- <li onclick="location.href='/admin/scanner'"><i class="fa-solid fa-qrcode"></i><a href="/admin/scanner">Сканер QR</a></li> -->
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
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    
    <script src="/Resources/js/Roboto.js"></script>
    <script src="/Resources/js/Ticket.js"></script>
    <script src="/Resources/js/Model.js"></script>
    <script src="/Resources/js/Modal.js"></script>
    <script src="/Resources/js/API.js"></script>
    <script src="/Resources/js/Other.js"></script>
    <script src="/Resources/js/SFS.js"></script>
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
    </script>
</body>

</html>