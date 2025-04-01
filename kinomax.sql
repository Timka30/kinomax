-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 01 2025 г., 18:14
-- Версия сервера: 8.0.19
-- Версия PHP: 7.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `kinomax`
--

-- --------------------------------------------------------

--
-- Структура таблицы `category_films`
--

CREATE TABLE `category_films` (
  `id` int NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `category_films`
--

INSERT INTO `category_films` (`id`, `type`) VALUES
(1, 'Аниме'),
(2, 'Боевики'),
(3, 'Детективы'),
(4, 'Драмы'),
(5, 'Комедии'),
(6, 'Фантастика');

-- --------------------------------------------------------

--
-- Структура таблицы `category_serials`
--

CREATE TABLE `category_serials` (
  `id` int NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `category_serials`
--

INSERT INTO `category_serials` (`id`, `type`) VALUES
(1, 'Аниме'),
(2, 'Драмы'),
(3, 'Комедии'),
(4, 'Мелодрамы'),
(5, 'Мультфильмы'),
(6, 'Ужасы'),
(7, 'Фантастика');

-- --------------------------------------------------------

--
-- Структура таблицы `films`
--

CREATE TABLE `films` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `films`
--

INSERT INTO `films` (`id`, `name`, `image`, `category`) VALUES
(1, '5 сантиметров в секунду', '5min-v-sek.webp', 1),
(2, 'Дитя погоды', 'ditya-pogodi.webp', 1),
(3, 'Рыбка поньо', 'fish-ponio.webp', 1),
(4, 'Форма голоса', 'forma-golosa.webp', 1),
(5, 'Ходячий замок', 'hodyachi-zamok.webp', 1),
(6, 'Красавица и дракон', 'krasavica-i-dragon.webp', 1),
(7, 'Клинок рассекающий демонов', 'krd-na-trenerovky-stolpov.webp', 1),
(8, 'Мальчик и птица', 'man-and-bird.webp', 1),
(9, 'Сад изящных слов', 'sad-izyashnih-slov.webp', 1),
(10, 'Семья шпионов', 'spy-family.webp', 1),
(11, 'Судзумэ, закрывающая двери', 'sydzyme-open-doors.webp', 1),
(12, 'Твоё имя', 'tvoe-imya.webp', 1),
(13, 'Ученик чудовища', 'uchenik-chydovisha.webp', 1),
(14, 'Унесенные призраками', 'unes-prizrakami.webp', 1),
(15, 'Ветер крепчает', 'veter-crepchaet.webp', 1),
(16, 'Малыш на драйве', 'baby-na-drive.webp', 2),
(17, 'Форсаж', 'fast.webp', 2),
(18, 'Гнев человеческий', 'gnev-chelovecheskiy.webp', 2),
(19, 'Годзилла против Конга', 'godzila-protiv-konga.webp', 2),
(20, 'Форсаж 10', 'fast-x.webp', 2),
(21, 'Безумный Макс', 'mad-max.webp', 2),
(22, 'Майор Гром: чумной доктор', 'mayour-grom-chumnoy-doctor.webp', 2),
(23, 'Мизантроп', 'mizantrop.webp', 2),
(24, 'Майор Гром: игра', 'mayour-grom-game.webp', 2),
(25, 'Пацан против всех', 'pacan-protiv-vsex.webp', 2),
(26, 'Переводчик', 'perevodchik.webp', 2),
(27, 'Револьвер', 'revolver.webp', 2),
(28, 'Шерлок Холмс', 'sherlok-holms.webp', 2),
(29, 'Джон Уик 4', 'sjon-uyk-4.webp', 2),
(30, 'Сорвать банк', 'sorvat-bank.webp', 2),
(31, 'Бэтмен', 'batman.webp', 3),
(32, 'Черный ящик', 'black-yashik.webp', 3),
(33, 'Казнь', 'cazn.webp', 3),
(34, 'Дело колонии', 'delo-kolonii.webp', 3),
(35, 'Девочка с татуировкой дракона', 'devushka-s-tatu-drakona.webp', 3),
(36, 'Достать ножи', 'dostat-noshi.webp', 3),
(37, 'Убийство в Париже', 'kill-in-paris.webp', 3),
(38, 'Поиск', 'poisk.webp', 3),
(39, 'Призрак в Венеции', 'prizrak-v-venecii.webp', 3),
(40, 'Шестое чувство', 'shestoe-chuvstovo.webp', 3),
(41, 'Убийство на Востоке', 'kill-in-vostok.webp', 3),
(42, 'Спящие псы', 'spyashie-pci.webp', 3),
(43, 'Ветренная река', 'vetr-reka.webp', 3),
(44, 'Воспоминания', 'vospominaniya.webp', 3),
(45, 'Всевидящее око', 'vsevidyashee-oko.webp', 3),
(46, '1+1', '1+1.webp', 4),
(47, 'Артур - ты король', 'artur-ti-korol.webp', 4),
(48, 'А зори здесь тихие...', 'a-zory-zdec-tixie.webp', 4),
(49, 'Крестный отец', 'crestniy-otec.webp', 4),
(50, 'Доктор', 'doctor.webp', 4),
(51, 'Огонь', 'fire.webp', 4),
(52, 'Кунг-фу жеребец', 'kung-fu-zhirebec.webp', 4),
(53, 'Любовь и другие лекарства', 'love-and-drugie.webp', 4),
(54, 'Мастер и Маргарита', 'master-and-maragrita.webp', 4),
(55, 'Материнский инстинкт', 'mother-instinkt.webp', 4),
(56, 'Одна жизнь', 'one-live.webp', 4),
(57, 'Только бог простит', 'only-god.webp', 4),
(58, 'Побег из Шоушенка', 'pobeg-iz-shoyshenka.webp', 4),
(59, 'Вызов', 'vizov.webp', 4),
(60, 'Волк с Уолл-Стрит', 'volk-s-yoll-street.webp', 4),
(61, '7 дней - 7 ночей', '7-dney-7-nochey.webp', 5),
(62, 'Адам и Ева', 'adam-and-eva.webp', 5),
(63, 'Барби', 'barbie.webp', 5),
(64, 'Батя', 'batya.webp', 5),
(65, 'Диктатор', 'diktator.webp', 5),
(66, 'Холоп 2', 'holop-2.webp', 5),
(67, 'Конец славы', 'konec-slavi.webp', 5),
(68, 'Кто угодно, кроме тебя', 'kto-ugodno-krome-tebya.webp', 5),
(69, 'Мальчишник в Вегасе', 'malchishnik-v-vegase.webp', 5),
(70, 'Неожиданные связи', 'neoshidanie-svyazi.webp', 5),
(71, 'Непослушники', 'neposlushniki.webp', 5),
(72, 'Неприличные гости', 'neprelichnie-gosti.webp', 5),
(73, 'Пара из будущего', 'para-iz-budushego.webp', 5),
(74, 'Поехавшая', 'poehavshaya.webp', 5),
(75, 'Родные', 'rodnie.webp', 5),
(76, 'Три тысячи лет желаний', '3-tisyachi-zhelaniy.webp', 6),
(77, 'Алиса в стране чудес', 'alisa-v-strane-chudes.webp', 6),
(78, 'День сурка', 'day-surka.webp', 6),
(79, 'Доктор Стрэндж', 'doctor-strange.webp', 6),
(80, 'Первый день моей жизни', 'first-day-moey-zhizni.webp', 6),
(81, 'Гарри Поттер', 'harry-poter.webp', 6),
(82, 'Хоббит', 'hobbit.webp', 6),
(83, 'Последний богатырь', 'last-bogatiry.webp', 6),
(84, 'Летучий корабль', 'letuckiy-korabl.webp', 6),
(85, 'Меч короля Артура', 'mech-korolya-artura.webp', 6),
(86, 'Пираты карибского моря', 'pirati-carib-morya.webp', 6),
(87, 'По щучьему велению', 'po-chuzheumy-vileniy.webp', 6),
(88, 'Сумерки', 'sumerki.webp', 6),
(89, 'Властелин колец', 'vlastelin-kolec.webp', 6),
(90, 'Ворон', 'voron.webp', 6);

-- --------------------------------------------------------

--
-- Структура таблицы `serials`
--

CREATE TABLE `serials` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `serials`
--

INSERT INTO `serials` (`id`, `name`, `image`, `category`) VALUES
(1, 'Аля иногда кокетничает со мной по-русски', 'alya-inogda-koketnichaet-so-mnoy.webp', 1),
(2, 'Атака титанов', 'ataka-titanov.webp', 1),
(3, 'Блич: Кровавая война', 'blich-krovavaya-voina.webp', 1),
(4, 'Синяя тюрьма: Блю Лок', 'blue-lock.webp', 1),
(5, 'Человек-бензопила', 'chainsaw-man.webp', 1),
(6, 'Киберпанк: Бегущие по краю', 'cyberpunk.webp', 1),
(7, 'Хвост феи', 'hvost-fei.webp', 1),
(8, 'Истребитель демонов', 'istrebitel-demonov.webp', 1),
(9, 'Магическая битва', 'mag-bitva.webp', 1),
(10, 'Монолог фармацевта', 'monolog-farmacevta.webp', 1),
(11, 'О моем перерождении в слизь', 'o-moem-pererozhdenii-v-sliz.webp', 1),
(12, 'Поднятие уровня в одиночку', 'podnyatie-urovnya-v-odinochku.webp', 1),
(13, 'Сага о Винланде', 'saga-o-vinlande.webp', 1),
(14, 'Тетрадь смерти', 'tetrad-smerti.webp', 1),
(15, 'Токийский гуль', 'tokyo-ghoul.webp', 1),
(16, 'Балет', 'balet.webp', 2),
(17, 'Чернобыль', 'chernobl.webp', 2),
(18, 'Король и Шут', 'korol-i-shoot.webp', 2),
(19, 'Легенды о Круге', 'legendi-o-kruge.webp', 2),
(20, 'Мажор', 'major.webp', 2),
(21, 'Молодежка', 'molodezhka.webp', 2),
(22, 'Острые козырьки', 'ostrie-kozirki.webp', 2),
(23, 'Псих', 'psih.webp', 2),
(24, 'Ранетки', 'ranetki.webp', 2),
(25, 'Слово пацана', 'slovo-pacana.webp', 2),
(26, 'Собор', 'sobor.webp', 2),
(27, 'Тригер', 'triger.webp', 2),
(28, 'Трудные подростки', 'trydnie-podrostki.webp', 2),
(29, 'Жиза', 'zhiza.webp', 2),
(30, 'Знахарь', 'znahar.webp', 2),
(31, 'Безпринципные', 'bezprincipnie.webp', 3),
(32, 'Два холма', 'dva-holma.webp', 3),
(33, 'Физрук', 'fizruk.webp', 3),
(34, 'Интерны', 'interni.webp', 3),
(35, 'Ивановы-Ивановы', 'ivanovi-ivanovi.webp', 3),
(36, 'Конец света', 'konec-sveta.webp', 3),
(37, 'Кухня', 'kyhnya.webp', 3),
(38, 'Отель Элеон', 'otel-elion.webp', 3),
(39, 'Отмороженные', 'otmorozhenie.webp', 3),
(40, 'Папины дочки', 'papini-dochki.webp', 3),
(41, 'Реальные пацаны', 'realnie-pacani.webp', 3),
(42, 'Сестры', 'sestri.webp', 3),
(43, 'Сваты', 'svati.webp', 3),
(44, 'Телохранитель', 'telohraniteli.webp', 3),
(45, 'Воронины', 'voronini.webp', 3),
(46, 'Черная любовь', 'chernaya-lybov.webp', 4),
(47, 'Истинная красота', 'istinnaya-krasota.webp', 4),
(48, 'Киберкраш', 'kyberkrash.webp', 4),
(49, 'Любовь на прокат', 'lybov-na-prokat.webp', 4),
(50, 'Мой сосед Кумихо', 'moi-sosed-kymiho.webp', 4),
(51, 'Надвое', 'nadvoe.webp', 4),
(52, 'Невеста речного бога', 'nevesta-rechnogo-boga.webp', 4),
(53, 'Параллельные миры', 'paralelnie-miri.webp', 4),
(54, 'Постучись в мою дверь', 'postuchis-v-moy-dver.webp', 4),
(55, 'Скорая помощь', 'skoraya-pomosh.webp', 4),
(56, 'Тест на беременность', 'test-na-beremennost.webp', 4),
(57, 'Великолепный век', 'velekolepniy-vek.webp', 4),
(58, 'Влюбленные женщины', 'vlyblennie-zhenshini.webp', 4),
(59, 'Я не робот', 'ya-ne-robot.webp', 4),
(60, 'За первого встречного', 'za-pervogo-vstrechnogo.webp', 4),
(61, 'Барбоскины', 'barboskini.webp', 5),
(62, 'Чип и Дейл', 'chip-i-deil.webp', 5),
(63, 'Фиксики', 'fiksiki.webp', 5),
(64, 'Гравити Фолз', 'grafity-folz.webp', 5),
(65, 'Губка боб', 'gubka-bob.webp', 5),
(66, 'Леди баг и Супер кот', 'ledi-bag-i-super-cot.webp', 5),
(67, 'Лунтик и его друзья', 'luntik-i-ego-druzia.webp', 5),
(68, 'Маша и медведь', 'masha-i-medved.webp', 5),
(69, 'Ну погоди', 'ny-pogodi.webp', 5),
(70, 'Простоквашино', 'prostokvashino.webp', 5),
(71, 'Рик и Морти', 'rik-i-morty.webp', 5),
(72, 'Симпсоны', 'simpsoni.webp', 5),
(73, 'Смешарики', 'smeshariki.webp', 5),
(74, 'Три кота', 'tri-kota.webp', 5),
(75, 'Утиные истории', 'ytinie-istorii.webp', 5),
(76, 'Американская история', 'amerikanskaya-istoria.webp', 6),
(77, 'Чаки', 'chaki.webp', 6),
(78, 'Ходячие мертвецы', 'hodyachie-mertvici.webp', 6),
(79, 'Извне', 'izvne.webp', 6),
(80, 'Калейдоскоп ужасов', 'kaleidoskop-uzhasov.webp', 6),
(81, 'Константин', 'konstantin.webp', 6),
(82, 'Королевы крика', 'korolevi-krika.webp', 6),
(83, 'Очень странные дела', 'ochen-strannie-dela.webp', 6),
(84, 'Оно', 'ono.webp', 6),
(85, 'Проповедник', 'propovednik.webp', 6),
(86, 'Противостояние', 'protivostoyanie.webp', 6),
(87, 'Шершни', 'shershni.webp', 6),
(88, 'Сирена', 'sirena.webp', 6),
(89, 'Стэн против сил зла', 'stan-protiv-sil-zla.webp', 6),
(90, 'Страшные сказки', 'strashnie-skazki.webp', 6),
(91, 'Черное зеркало', 'chernoe-zerkalo.webp', 7),
(92, 'Доктор Кто', 'doctor-cto.webp', 7),
(93, 'Fallout', 'fallout.webp', 7),
(94, 'Флэш', 'flash.webp', 7),
(95, 'Halo', 'halo.webp', 7),
(96, 'Хедшот', 'headshot.webp', 7),
(97, 'Кибердеревня', 'kiberderevnya.webp', 7),
(98, 'Локи', 'loki.webp', 7),
(99, 'Мандалорец', 'mandalorec.webp', 7),
(100, 'Мир дикого Запада', 'mir-dikogo-zapada.webp', 7),
(101, 'Остаться в живых', 'ostatsia-v-zhivih.webp', 7),
(102, 'Пацаны', 'pacani.webp', 7),
(103, 'Вечность', 'vechnost.webp', 7),
(104, 'Засланец из космоса', 'zaslanec-iz-kosmosa.webp', 7),
(105, 'Миротворец', 'mirotvorec.webp', 7),
(106, 'Трудные подростки', 'Снимок экрана 2024-10-14 010348.png', 2),
(107, 'Новенький', 'Снимок экрана 2024-10-13 153616.png', 2),
(108, 'Киберслав', 'chaki.webp', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `login` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `isAdmin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `phone`, `email`, `isAdmin`) VALUES
(1, '123', '123', '+7 (999) 999-99-99', 'test@test.test', 0),
(2, 'Timka', 'Timka', '+7 (999) 999-99-99', 'test@test.test', 1),
(14, '13123', '123131', '+7 (321) 321-33-23', 'fds@mail.ru', 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `category_films`
--
ALTER TABLE `category_films`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `category_serials`
--
ALTER TABLE `category_serials`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `films`
--
ALTER TABLE `films`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category` (`category`);

--
-- Индексы таблицы `serials`
--
ALTER TABLE `serials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category` (`category`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `category_films`
--
ALTER TABLE `category_films`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `category_serials`
--
ALTER TABLE `category_serials`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `films`
--
ALTER TABLE `films`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT для таблицы `serials`
--
ALTER TABLE `serials`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `films`
--
ALTER TABLE `films`
  ADD CONSTRAINT `films_ibfk_1` FOREIGN KEY (`category`) REFERENCES `category_films` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `serials`
--
ALTER TABLE `serials`
  ADD CONSTRAINT `serials_ibfk_1` FOREIGN KEY (`category`) REFERENCES `category_serials` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
