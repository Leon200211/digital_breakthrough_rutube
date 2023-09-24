-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3307
-- Время создания: Сен 20 2023 г., 12:55
-- Версия сервера: 8.0.30
-- Версия PHP: 8.0.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `digital-breakthrough-rutube`
--

-- --------------------------------------------------------

--
-- Структура таблицы `upload_video`
--

CREATE TABLE `upload_video` (
  `id` int NOT NULL,
  `video` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `date_create` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `quality` int NOT NULL,
  `commentary` int NOT NULL,
  `is_processed` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `upload_video`
--

INSERT INTO `upload_video` (`id`, `video`, `name`, `description`, `date_create`, `quality`, `commentary`, `is_processed`) VALUES
(1, 'test_671427.mp4', 'test', 'test', '2023-09-19 21:57:37', 1, 1, 1),
(2, 'test_372620.mp4', 'test', 'test', '2023-09-19 22:04:26', 1, 1, 0),
(3, 'test_397403.mp4', 'test', 'test', '2023-09-20 11:09:57', 1, 1, 0),
(4, 'test_38307.mp4', 'test', 'test', '2023-09-20 11:12:38', 1, 1, 0),
(5, 'test_642825.mp4', 'test', 'test', '2023-09-20 11:14:31', 1, 1, 0),
(6, 'test_141574.mp4', 'test', 'test', '2023-09-20 11:17:34', 1, 1, 0),
(7, 'test_409573.mp4', 'test', 'test', '2023-09-20 11:21:22', 1, 1, 0),
(8, 'test_910529.mp4', 'test', 'test', '2023-09-20 11:21:48', 1, 1, 0),
(9, 'test_99247.mp4', 'test', 'test', '2023-09-20 11:23:09', 1, 1, 0),
(10, 'test_809878.mp4', 'test', 'test', '2023-09-20 11:23:48', 1, 1, 0),
(11, 'test_982267.mp4', 'test', 'test', '2023-09-20 11:24:17', 1, 1, 0),
(12, 'test_245171.mp4', 'test', 'test', '2023-09-20 11:24:47', 1, 1, 0),
(13, 'test_599669.mp4', 'test', 'test', '2023-09-20 11:25:30', 1, 1, 0),
(14, 'test_216797.mp4', 'test', 'test', '2023-09-20 11:26:09', 1, 1, 0),
(15, 'test_629019.mp4', 'test', 'test', '2023-09-20 11:27:02', 1, 1, 0),
(16, 'test_906813.mp4', 'test', 'test', '2023-09-20 11:28:56', 1, 1, 0),
(17, 'test_822035.mp4', 'test', 'test', '2023-09-20 11:29:57', 1, 1, 0),
(18, 'test_327448.mp4', 'test', 'test', '2023-09-20 11:30:10', 1, 1, 0),
(19, 'test_870610.mp4', 'test', 'test', '2023-09-20 11:30:31', 1, 1, 0),
(20, 'test_183705.mp4', 'test', 'test', '2023-09-20 11:30:50', 1, 1, 0),
(21, 'test_88816.mp4', 'test', 'test', '2023-09-20 11:52:39', 1, 1, 0),
(22, 'test_682630.mp4', 'test', 'test', '2023-09-20 12:02:08', 1, 1, 0),
(23, 'test_267433.mp4', 'test', 'test', '2023-09-20 12:02:53', 1, 1, 0),
(24, 'test_330062.mp4', 'test', 'test', '2023-09-20 12:03:00', 1, 1, 0),
(25, 'test_983665.mp4', 'test', 'test', '2023-09-20 12:03:20', 1, 1, 0),
(26, 'test_450805.mp4', 'test', 'test', '2023-09-20 12:03:53', 1, 1, 0),
(27, 'test_615771.mp4', 'test', 'test', '2023-09-20 12:04:20', 1, 1, 0),
(28, 'test_576471.mp4', 'test', 'test', '2023-09-20 12:04:44', 1, 1, 0),
(29, 'test_946627.mp4', 'test', 'test', '2023-09-20 12:10:26', 1, 1, 0),
(30, 'test_445888.mp4', 'test', 'test', '2023-09-20 12:11:20', 1, 1, 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `upload_video`
--
ALTER TABLE `upload_video`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `upload_video`
--
ALTER TABLE `upload_video`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
