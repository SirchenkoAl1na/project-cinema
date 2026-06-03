<?php

namespace App;

use App\DB;
use App\Services\TicketService;
use App\Services\SaleService;


enum FieldType: string
{
    case INTEGER = 'INT';              // ціле число
    case TEXT = 'VARCHAR(255)';        // текст обмеженої довжини
    case REAL = 'DOUBLE';              // дійсне число
    case BLOB = 'BLOB';
    case BOOL = 'BOOL';            // булеве значення
    case BOOLDEFAULTFALSE = 'BOOL default false';
    case NULL = 'NULL';                // NULL тип — рідко використовується явно
    case DATE = 'DATE';
    case TIME = 'TIME';              // час
    case JSON = 'JSON';
}

class Migration
{
    private static $tables = [
        "users" => [
            ["full_name", FieldType::TEXT, true],
            ["login", FieldType::TEXT, true],
            ["email", FieldType::TEXT, true],
            ["phone", FieldType::TEXT, true],
            ["password", FieldType::TEXT, true],
            ["role", FieldType::TEXT, true],
            ["discount", FieldType::INTEGER],
            ["photo", FieldType::TEXT],
            ["created_at", FieldType::DATE, true],
        ],
        "films" => [
            ["title", FieldType::TEXT, true],
            ["imdb_id", FieldType::TEXT],
            ["original_title", FieldType::TEXT, true],
            ["primiere_date", FieldType::DATE, true],
            ["duration", FieldType::INTEGER, true],
            ["end_date", FieldType::DATE, true],
            ["description", FieldType::JSON, true],
            ["poster", FieldType::TEXT, true],
        ],
        "employee" => [
            ["shedule", FieldType::JSON],
            ["posada", FieldType::TEXT],
            ["zarplata", FieldType::INTEGER],
            ["user_id", FieldType::INTEGER, true, "users"],
        ],
        "seanses" => [
            ["date", FieldType::DATE, true],
            ["time", FieldType::TEXT, true],
            ["status", FieldType::TEXT, true],
            ["is_buing_blocked", FieldType::BOOLDEFAULTFALSE],
            ["film_id", FieldType::INTEGER],
            ["hole_id", FieldType::INTEGER],
        ],
        "achievements" => [
            ["title", FieldType::TEXT, true],
            ["triger", FieldType::TEXT],
            ["description", FieldType::JSON],
            ["level_description", FieldType::TEXT, true],
            ["number_for_goal", FieldType::INTEGER],
            ["discount", FieldType::INTEGER, true],
            ["image_title", FieldType::TEXT],
        ],
        "users_achievements" => [
            ["user_id", FieldType::INTEGER, true, "users"],
            ["achievement_id", FieldType::INTEGER, true, "achievements"],
            ["current_level", FieldType::INTEGER],
            ["achieved", FieldType::BOOLDEFAULTFALSE],
            ["date", FieldType::DATE],
            ["sale_id", FieldType::INTEGER],
        ],
        "holes" => [
            ["nomer", FieldType::TEXT, true],
            ["number_of_places", FieldType::TEXT, true],
            ["status", FieldType::TEXT, true],
        ],
        "places" => [
            ["row", FieldType::TEXT, true],
            ["place", FieldType::TEXT, true],
            ["hole_id", FieldType::INTEGER, true, 'holes'],
            ["type", FieldType::TEXT],
            ["markup", FieldType::INTEGER],
        ],
        "sales" => [
            ["sum", FieldType::INTEGER],
            ["date", FieldType::DATE],
            ["time", FieldType::TIME],
            ["user_id", FieldType::INTEGER, false, 'users'],
            ["discount", FieldType::INTEGER],
            ["employer_id", FieldType::INTEGER, false, 'employee'],
            ["seanse_id", FieldType::INTEGER, true, 'seanses'],
        ],
        "tickets" => [
            ["place_id", FieldType::INTEGER, true, 'places'],
            ["sale_id", FieldType::INTEGER, true, 'sales'],
            ["price", FieldType::INTEGER],
            ["ticket_kod", FieldType::TEXT, true],
            ["qr_token", FieldType::TEXT],
            ["qr_status", FieldType::TEXT],
            ["scanned_at", FieldType::TEXT],
            ["scanned_by_name", FieldType::TEXT]
        ],
        "reviews" => [
            ["user_id", FieldType::TEXT],
            ["film_id", FieldType::INTEGER, true, 'films'],
            ["rating", FieldType::REAL, false],
            ["comment", FieldType::TEXT, true],
            ["date", FieldType::DATE, true],
            ["time", FieldType::TIME, true],
            ["visible", FieldType::BOOL],
            ["email", FieldType::TEXT],
            ["is_blocked", FieldType::BOOLDEFAULTFALSE],
            ["parent_comment_id", FieldType::INTEGER],
        ],
    ];

    private static $insert_data = [
        [
            'table' => 'achievements',
            'query' => "INSERT INTO achievements (title, level_description,description, number_for_goal, discount, image_title,triger) VALUES 
                ('Я обовязково виживу', 'Переглянути фільм жанру апокаліпсис','{\"genre\":\"Апокаліпсис\"}', 1, 5, 'I_Will_Survive.png','film_genre'),
                -- ('Yupi kai eye', 'Бойовик','{\"genre\":\"Бойовик\"}', 1, 5, 'Yupi_kai_eye.png','film_genre'),
                -- ('Kamehameha', 'Аніме','{\"genre\":\"Аніме\"}', 1, 5, 'Kamehameha.png','film_genre'),
                ('Детектив', 'Детектив ','{\"genre\":\"Детектив\"}', 1, 5, 'Detective.png','film_genre'),
                ('Містик', 'Містичний фільм','{\"genre\":\"Містика\"}', 1, 5, 'Mystic.png','film_genre'),
                -- ('Служитель Ктулху', 'Лавкрафтіанський жах','{\"genre\":\"Лавкрафтіанський жах\"}', 1, 5, 'Servant_of_Cthulhu.png','film_genre'),
                ('Жахи', 'Жахи','{\"genre\":\"Жахи\"}', 1, 5, 'Horror.png','film_genre'),
                ('Лицар', 'Фентазі','{\"genre\":\"Фентезі\"}', 1, 5, 'Knight.png','film_genre'),
                -- ('Бляшанка', 'Наукова фантастика 1','{\"genre\":\"Наукова фантастика\"}', 1, 5, 'Tin_Can.png','film_genre'),
                -- ('Аугмент', 'Наукова фантастика 5','{\"genre\":\"Наукова фантастика\"}', 1, 5, 'Augment.png','film_genre'),
                -- ('Кіборг', 'Наукова фантастика 10','{\"genre\":\"Наукова фантастика\"}', 1, 5, 'Cyborg.png','film_genre'),
                -- ('Прем’єрний мисливець', 'побував на 5 прем’єрах','{\"primier\":\"5\"}', 5, 5, 'Premier_Hunter.png','film'),
                ('Нічна істота', 'Був на вечірньому сеансі (20-23)','{\"time\":\"21-23\"}', 3, 5, 'Night_Creature.png','time'),
                ('Батарейка', 'Був на ранковому сеансі (10-12)','{\"time\":\"10-12\"}', 3, 5, 'Battery.png','time'),
                ('Романтик', '(Амур) За купівлю квитків на двох','{\"tickets\":\"2\"}',3, 5, 'Romantic.png','few_tickets'),
                ('Душа компанії', 'За купілю 3 квитків','{\"tickets\":\"3\"}', 3, 5, 'Soul_of_the_Party.png','few_tickets'),
                ('Початківець', '1 покупка(різних)','{\"tickets\":\"1\"}', 1, 5, 'Beginner.png','few_tickets'),
                ('Досвідчений', '5 покупок(різних)','{\"tickets\":\"1\"}', 5, 5, 'Experienced.png','few_tickets'),
                ('Герой', '10 покупок(різних)','{\"tickets\":\"1\"}', 10, 5, 'Hero.png','few_tickets'),
                ('Кіноман', 'Переглянуто 50 фільмів','{\"tickets\":\"1\"}', 50, 5, 'Movie_Fan.png','few_tickets');"
        ],
        [
            'table' => 'films',
            'query' => "INSERT INTO films (title,imdb_id, original_title, primiere_date, duration, end_date, description, poster) VALUES
            ('Оппенгеймер','tt15398776', 'Oppenheimer', '2026-01-01', 180, '2026-12-31', '{\"genres\": [\"Драма\", \"Біографія\"], \"director\": \"Крістофер Нолан\", \"cast\": [\"Кілліан Мерфі\", \"Роберт Дауні-молодший\"]}', 'film1.png'),
            ('Тенет','tt6723592', 'Tenet', '2026-02-01', 150, '2026-11-30', '{\"genres\": [\"Фантастика\", \"Бойовик\"], \"director\": \"Крістофер Нолан\", \"cast\": [\"Роберт Паттінсон\", \"Джон Девід Вашингтон\"]}', 'film2.png'),
            ('Дюна','tt1160419', 'Dune', '2026-03-15', 155, '2026-09-30', '{\"genres\": [\"Фантастика\", \"Драма\"], \"director\": \"Дені Вільньов\", \"cast\": [\"Тімоті Шаламе\", \"Зендея\"]}', 'film3.png'),
            ('Аватар: Шлях води','tt1630029', 'Avatar: The Way of Water', '2026-04-20', 192, '2026-10-15', '{\"genres\": [\"Фантастика\", \"Пригоди\"], \"director\": \"Джеймс Кемерон\", \"cast\": [\"Сем Вортінгтон\", \"Зої Салдана\"]}', 'film4.png'),
            ('Місія нездійсненна 7','tt9603208', 'Mission: Impossible - Dead Reckoning Part One', '2026-05-10', 163, '2026-11-20', '{\"genres\": [\"Бойовик\", \"Трилер\"], \"director\": \"Крістофер МакКворі\", \"cast\": [\"Том Круз\", \"Гейлі Етвелл\"]}', 'film5.png'),
            ('Вбивці квіткового місяця','tt5537002', 'Killers of the Flower Moon', '2026-06-05', 206, '2026-12-10', '{\"genres\": [\"Драма\", \"Кримінал\"], \"director\": \"Мартін Скорсезе\", \"cast\": [\"Леонардо Ді Капріо\", \"Роберт Де Ніро\"]}', 'film6.png'),
            ('Барбі','tt1517268', 'Barbie', '2026-07-21', 114, '2026-01-05', '{\"genres\": [\"Комедія\", \"Фентезі\"], \"director\": \"Грета Гервіг\", \"cast\": [\"Марго Роббі\", \"Раян Гослінг\"]}', 'film7.png'),
            ('Матриця: Воскресіння','tt10838180', 'The Matrix Resurrections', '2026-08-01', 148, '2026-02-28', '{\"genres\": [\"Наукова фантастика\", \"Бойовик\"], \"director\": \"Лана Вачовскі\", \"cast\": [\"Кіану Рівз\", \"Керрі-Енн Мосс\"]}', 'film8.png'),
            ('Початок','tt1375666', 'Inception', '2026-09-10', 148, '2026-03-15', '{\"genres\": [\"Наукова фантастика\", \"Трилер\"], \"director\": \"Крістофер Нолан\", \"cast\": [\"Леонардо Ді Капріо\", \"Джозеф Гордон-Левітт\"]}', 'film9.png'),
            ('Інтерстеллар','tt0816692', 'Interstellar', '2026-10-25', 169, '2026-04-30', '{\"genres\": [\"Наукова фантастика\", \"Пригоди\"], \"director\": \"Крістофер Нолан\", \"cast\": [\"Меттью Мак-Конахі\", \"Енн Гетевей\"]}', 'film10.png'),
            ('Форсаж 10','tt5433140', 'Fast X', '2026-05-19', 141, '2026-12-01', '{\"genres\": [\"Бойовик\", \"Пригоди\"], \"director\": \"Луїс Летер’є\", \"cast\": [\"Він Дізель\", \"Мішель Родрігес\"]}', 'film11.png'),
            ('Джон Уік 4','tt10366206', 'John Wick: Chapter 4', '2026-03-24', 169, '2026-10-24', '{\"genres\": [\"Бойовик\", \"Трилер\"], \"director\": \"Чад Стахельські\", \"cast\": [\"Кіану Рівз\", \"Донні Єн\"]}', 'film12.png'),
            ('Вартові галактики 3','tt6791350', 'Guardians of the Galaxy Vol. 3', '2026-05-05', 150, '2026-11-05', '{\"genres\": [\"Наукова фантастика\", \"Бойовик\", \"Комедія\"], \"director\": \"Джеймс Ґанн\", \"cast\": [\"Кріс Пратт\", \"Зої Салдана\"]}', 'film13.png'),
            ('Людина-павук: Навколо Всесвіту','tt9362722','Spider-Man: Across the Spider-Verse', '2026-06-02', 140, '2026-12-02', '{\"genres\": [\"Анімація\", \"Пригоди\", \"Бойовик\"], \"director\": \"Хоакім Дос Сантос\", \"cast\": [\"Шамеїк Мур\", \"Гейлі Стайнфелд\"]}', 'film14.png'),
            ('Трансформери: Час звірів', 'tt5090568','Transformers: Rise of the Beasts', '2026-06-09', 127, '2026-12-09', '{\"genres\": [\"Наукова фантастика\", \"Бойовик\"], \"director\": \"Стівен Кейпл-молодший\", \"cast\": [\"Ентоні Рамос\", \"Домінік Фішбек\"]}', 'film15.png'),
            ('Флеш',  'tt0439572','The Flash','2026-06-15', 144, '2026-12-15', '{\"genres\": [\"Фантастика\", \"Пригоди\", \"Бойовик\"], \"director\": \"Андрес Мускетті\", \"cast\": [\"Езра Міллер\", \"Бен Аффлек\"]}', 'film16.png'),
            ('Русалонька', 'tt5971474','The Little Mermaid', '2026-05-24', 135, '2026-11-24', '{\"genres\": [\"Мюзикл\", \"Фентезі\", \"Сімейний\"], \"director\": \"Роб Маршалл\", \"cast\": [\"Холі Бейлі\", \"Джона Гавер-Кінг\"]}', 'film17.png'),
            ('Індіана Джонс і реліквія долі','tt1462764', 'Indiana Jones and the Dial of Destiny', '2026-06-29', 154, '2026-12-29', '{\"genres\": [\"Пригоди\", \"Бойовик\"], \"director\": \"Джеймс Менголд\", \"cast\": [\"Гаррісон Форд\", \"Фібі Воллер-Брідж\"]}', 'film18.png'),
            ('Елементарно','tt15789038', 'Elemental', '2026-06-16', 102, '2026-12-16', '{\"genres\": [\"Анімація\", \"Комедія\", \"Сімейний\"], \"director\": \"Пітер Сон\", \"cast\": [\"Леа Льюїс\", \"Мамуду Аті\"]}', 'film19.png'),
            ('Місто астероїдів','tt14230388', 'Asteroid City', '2026-06-16', 105, '2026-12-16', '{\"genres\": [\"Комедія\", \"Драма\", \"Фантастика\"], \"director\": \"Вес Андерсон\", \"cast\": [\"Джейсон Шварцман\", \"Скарлетт Йоганссон\"]}', 'film20.png')"
        ],
        [
            'table' => 'employee',
            'query' => "INSERT INTO employee (shedule, posada, user_id, zarplata) VALUES 
                ('{\"Monday\": \"9:00-17:00\", \"Tuesday\": \"9:00-17:00\", \"Wednesday\": \"9:00-17:00\", \"Thursday\": \"9:00-17:00\", \"Friday\": \"9:00-17:00\"}', 'адміністратор', 1, 21000),
                ('{\"Monday\": \"10:00-18:00\", \"Tuesday\": \"10:00-18:00\", \"Wednesday\": \"10:00-18:00\", \"Thursday\": \"10:00-18:00\", \"Friday\": \"10:00-18:00\"}', 'касир', 2, 15000),
                ('{\"Monday\": \"11:00-19:00\", \"Tuesday\": \"11:00-19:00\", \"Wednesday\": \"11:00-19:00\", \"Thursday\": \"11:00-19:00\", \"Friday\": \"11:00-19:00\"}', 'перевіряючий', 3, 12000),
                ('{\"Monday\": \"11:00-19:00\", \"Tuesday\": \"11:00-19:00\", \"Wednesday\": \"11:00-19:00\", \"Thursday\": \"11:00-19:00\", \"Friday\": \"11:00-19:00\"}', 'перевіряючий', 4, 12000),
                ('{\"Monday\": \"12:00-20:00\", \"Tuesday\": \"12:00-20:00\", \"Wednesday\": \"12:00-20:00\", \"Thursday\": \"12:00-20:00\", \"Friday\": \"12:00-20:00\"}', 'касир', 5, 15500),
                ('{\"Monday\": \"13:00-21:00\", \"Tuesday\": \"13:00-21:00\", \"Wednesday\": \"13:00-21:00\", \"Thursday\": \"13:00-21:00\", \"Friday\": \"13:00-21:00\"}', 'перевіряючий', 6, 12500);"
        ],
        [
            'table' => 'holes',
            'query' => "INSERT INTO holes (nomer, number_of_places, status) VALUES 
                ('1', '9', 'відкритий'),
                ('2', '12', 'відкритий'),
                ('3', '15', 'відкритий'),
                ('4', '16', 'відкритий'),
                ('5', '20', 'відкритий'),
                ('6', '8', 'відкритий');"
        ],
        [
            'table' => 'places',
            'query' => "INSERT INTO places (row, place, hole_id) VALUES 
                (1,1,1), (1,2,1), (1,3,1), (2,1,1), (2,2,1), (2,3,1), (3,1,1), (3,2,1), (3,3,1), 
                (1,1,2), (1,2,2), (1,3,2), (1,4,2), (2,1,2), (2,2,2), (2,3,2), (2,4,2), (3,1,2), (3,2,2), (3,3,2), (3,4,2),
                (1,1,3), (1,2,3), (1,3,3), (1,4,3), (1,5,3), (2,1,3), (2,2,3), (2,3,3), (2,4,3), (2,5,3), (3,1,3), (3,2,3), (3,3,3), (3,4,3), (3,5,3),
                (1,1,4), (1,2,4), (1,3,4), (1,4,4), (2,1,4), (2,2,4), (2,3,4), (2,4,4), (3,1,4), (3,2,4), (3,3,4), (3,4,4), (4,1,4), (4,2,4), (4,3,4), (4,4,4),
                (1,1,5), (1,2,5), (1,3,5), (1,4,5), (1,5,5), (2,1,5), (2,2,5), (2,3,5), (2,4,5), (2,5,5), (3,1,5), (3,2,5), (3,3,5), (3,4,5), (3,5,5), (4,1,5), (4,2,5), (4,3,5), (4,4,5), (4,5,5),
                (1,1,6), (1,2,6), (2,1,6), (2,2,6), (3,1,6), (3,2,6), (4,1,6), (4,2,6);"
        ],
        [
            'table' => 'reviews',
            'query' => "INSERT INTO reviews (user_id, film_id, rating, comment, date, parent_comment_id, visible) VALUES 
                (1, 1, 5.0, 'Чудовий фільм!', '2026-06-01', NULL, true),
                (2, 1, 4.5, 'Дуже сподобався!', '2026-06-02', NULL, true),
                (3, 2, 3.0, 'Непоганий фільм.', '2026-06-03', NULL, true),
                (4, 2, 2.0, 'Не сподобався.', '2026-06-04', NULL, true),
                (5, 1, 5.0, 'Неймовірна гра акторів!', '2026-06-05', NULL, true),
                (6, 1, 4.0, 'Трохи заплутаний сюжет, але загалом круто.', '2026-06-06', NULL, true),
                (7, 2, 5.0, 'Вражає!', '2026-06-07', NULL, true),
                (8, 3, 4.5, 'Дені Вільньов – майстер! Однозначно рекомендую.', '2026-06-08', NULL, true),
                (9, 4, 3.5, 'Візуально вражаюче, але сюжет трохи слабкий.', '2026-06-09', NULL, true),
                (10, 5, 5.0, 'Том Круз, як завжди, на висоті!', '2026-06-10', NULL, true),
                (11, 6, 4.8, 'Глибокий і потужний фільм. Скорсезе не підвів.', '2026-06-11', NULL, true),
                (12, 7, 4.2, 'Дуже веселий і яскравий фільм для всієї родини.', '2026-06-12', NULL, true),
                (5, 8, 5.0, 'Чудовий фільм', '2026-06-12', NULL, true),
                (6, 8, 4.0, 'Цікаво, але не Нолан', '2026-06-12', NULL, true);"
        ]
    ];

    static function run()
    {
        if (!isset($_SESSION['migration'])) {
            //create tables
            foreach (self::$tables as $key => $data) {
                self::createTable($key, $data);
            }
            $admin_password = md5('secret123');
            $employer_basic_password = md5('123456employer');
            $test_client_password = md5('123456');
            $date = date('Y-m-d H:i:s');

            self::fillTable(
                'users',
                "INSERT INTO users (full_name, login, phone, email, password, role, created_at) VALUES 
                ('Дмитро Коваленко', 'admin', '380692445192', 'dmitro.k@test.com', '$admin_password', 'employer', '$date'),
                ('Олена Шевченко', 'cashier1', '380642514679', 'olena.s@test.com', '$employer_basic_password', 'employer', '$date'),
                ('Андрій Мельник', 'manager1', '380522550653', 'andriy.m@test.com', '$employer_basic_password', 'employer', '$date'),
                ('Наталія Поліщук', 'manager2', '380322758818', 'nataliia.p@test.com', '$employer_basic_password', 'employer', '$date'),
                ('Олексій Петренко', 'user1', '380522569347', 'alex.p@test.com', '$test_client_password', 'client', '$date'),
                ('Марина Бондаренко', 'user2', '380564369780', 'maryna.b@test.com', '$test_client_password', 'client', '$date'),
                ('Павло Сидоренко', 'user3', '380442344355', 'pavlo.s@test.com', '$test_client_password', 'client', '$date'),
                ('Юлія Кузьменко', 'user4', '380951234567', 'yulia.k@test.com', '$test_client_password', 'client', '$date'),
                ('Віктор Захарчук', 'user5', '380967654321', 'viktor.z@test.com', '$test_client_password', 'client', '$date'),
                ('Ірина Кравчук', 'user6', '380671122334', 'iryna.k@test.com', '$test_client_password', 'client', '$date'),
                ('Антон Ткаченко', 'user7', '380639876543', 'anton.t@test.com', '$test_client_password', 'client', '$date'),
                ('Ольга Олійник', 'user8', '380501231231', 'olga.o@test.com', '$test_client_password', 'client', '$date'),
                ('Віталій Коваль', 'user9', '380981122334', 'vitaliy.k@test.com', '$test_client_password', 'client', '$date'),
                ('Світлана Мороз', 'user10', '380978765432', 'svitlana.m@test.com', '$test_client_password', 'client', '$date'),
                ('Тарас Литвин', 'user11', '380995544332', 'taras.l@test.com', '$test_client_password', 'client', '$date'),
                ('Людмила Іванова', 'user12', '380687766554', 'lyudmyla.i@test.com', '$test_client_password', 'client', '$date'),
                ('Микола Савчук', 'user13', '380663322110', 'mykola.s@test.com', '$test_client_password', 'client', '$date'),
                ('Галина Клименко', 'user14', '380931122556', 'halyna.k@test.com', '$test_client_password', 'client', '$date'),
                ('Ігор Петров', 'user15', '380945566778', 'igor.p@test.com', '$test_client_password', 'client', '$date'),
                ('Катерина Соловйова', 'user16', '380509988776', 'kateryna.s@test.com', '$test_client_password', 'client', '$date');"
            );

            foreach (self::$insert_data as $item) {
                self::fillTable($item['table'], $item['query']);
            }
            
            $check_seanses = DB::selectByQuery("SELECT id FROM seanses LIMIT 1;");
            if (empty($check_seanses)) {
                self::generateSeanses();
                // self::generateSalesAndTicketsUsingServices();
            }

            $_SESSION['migration'] = true;
        }
    }

    private static function generateSeanses()
    {
        $currentDate = new \DateTime();
        $seanseTimes = ['10:00', '14:00', '18:00', '22:00'];
        $filmsCount = 20; // Кількість фільмів у БД
        $holesCount = 6;  // Кількість залів у БД
        $queries = [];

        for ($i = 0; $i < 50; $i++) {
            $randomDays = mt_rand(0, 30);
            $seanseDate = (clone $currentDate)->modify("+$randomDays days")->format('Y-m-d');
            $seanseTime = $seanseTimes[array_rand($seanseTimes)];
            $filmId = mt_rand(1, $filmsCount);
            $holeId = mt_rand(1, $holesCount);

            $queries[] = "('$seanseDate', '$seanseTime', 'available', $filmId, $holeId)";
        }

        if (!empty($queries)) {
            $query = "INSERT INTO seanses (date, time, status, film_id, hole_id) VALUES " . implode(',', $queries);
            self::fillTable('seanses', $query);
        }
    }

    private static function generateSalesAndTicketsUsingServices()
    {
        $seanses = DB::selectByQuery("SELECT id, hole_id FROM seanses");
        
        if (empty($seanses)) {
            echo 'No seanses available to generate sales.';
            return;
        }
        
        $clients =  array_column(DB::selectByQuery("SELECT id FROM users WHERE role='client'"),'id');
        
        $employers = array_column(DB::selectByQuery("SELECT id FROM users WHERE role='employer'"),'id');
        
        $basePrice = Data::$ticket_price; // Базова ціна квитка
        $salesCount = 50; // Кількість продажів
        

        for ($i = 0; $i < $salesCount; $i++) {
            
            $seanse = $seanses[array_rand($seanses)];
            $seanseId = $seanse['id'];
            $holeId = $seanse['hole_id'];

            $clientUserId = $clients[array_rand($clients)];
            $employerUserId = $employers[array_rand($employers)];
            $discount = mt_rand(0, 15);
            $numberOfTickets = mt_rand(1, 5);
            
            $ticketsData = [];
            $totalSum = 0;
            // Збираємо вільні місця для поточного залу
            $allPlacesInHole = DB::selectByQuery("SELECT `row`, place FROM places WHERE hole_id = $holeId");

            // Генеруємо квитки, поки є вільні місця
            for ($j = 0; $j < $numberOfTickets; $j++) {
                if (empty($allPlacesInHole)) {
                    break;
                }
                
                $placeData = array_shift($allPlacesInHole);
                $ticketsData[] = [
                    'row'=>$placeData['row'],
                    'place'=>$placeData['place'],
                ];
                $totalSum += $basePrice;

            }

            if (empty($ticketsData)) {
                continue;
            }

            $finalSum = $totalSum * (1 - $discount / 100);

            // Формування даних для SaleService::buyTickets
            $saleData = [
                'tickets' => $ticketsData,
                'sum' => $finalSum,
                'discount' => $discount,
                'user_id' => $clientUserId, // Клієнт
                'seanse_id' => $seanseId,
            ];

            // Виклик реального сервісу для створення продажу та квитків
            SaleService::buyTickets($saleData, $employerUserId); // Працівник, який здійснює продаж
        }
        
    }

    static function getFieldPK()
    {
        return "id INT AUTO_INCREMENT PRIMARY KEY";
    }

    static function getFieldFK($name, $table)
    {
        return "FOREIGN KEY ($name) REFERENCES $table(id)";
    }

    static function getField($name, $type, $notNull = false): string
    {
        $nullStr = $notNull ? "NOT NULL" : "";
        return "`$name` {$type->value} $nullStr";
    }

    static function createTable($name, $data)
    {
        $rows_str = self::getFieldPK();
        $fks = [];
        foreach ($data as $row) {
            $rows_str .= ", " . self::getField($row[0], $row[1], $row[2] ?? false);
            if (isset($row[3]))
                array_push($fks, [$row[0], $row[3]]);
        }
        foreach ($fks as $fk) {
            $rows_str .= ", " . self::getFieldFK($fk[0], $fk[1]);
        }
        $query = "CREATE TABLE IF NOT EXISTS $name ($rows_str)";
        try {
            $res = DB::query($query);
            return $res;
        } catch (\PDOException $e) {
            Data::Error($e,'query: ' . $query);
            return false;
        }
    }
    static function fillTable($table, $query)
    {
        try {
            $db = DB::connect();
            $check_data = DB::selectByQuery("SELECT * FROM {$table} LIMIT 1;");
            // echo $query . "<br>";
            if (!empty($check_data))
                return;
            else
                $db->exec($query);
        } catch (PDOException $e) {
            Data::Error($e,'query: ' . $query);
        }
    }
    static function reset()
    {
        // TODO change
        $db = DB::connect();
        $db->exec("SET FOREIGN_KEY_CHECKS=0;");
        foreach (self::$tables as $table => $data) {
            $db->exec("DROP TABLE IF EXISTS $table;");
        }
        $db->exec("SET FOREIGN_KEY_CHECKS=1;");
        unset($_SESSION['migration']);
    }
}
