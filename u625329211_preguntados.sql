-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 24-11-2025 a las 06:45:23
-- Versión del servidor: 11.8.3-MariaDB-log
-- Versión de PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `u625329211_preguntados`
--
CREATE DATABASE IF NOT EXISTS `u625329211_preguntados` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `u625329211_preguntados`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `answer`
--

CREATE TABLE `answer` (
  `answer_id` int(11) NOT NULL,
  `answer_text` varchar(255) NOT NULL,
  `question_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `answer`
--

INSERT INTO `answer` (`answer_id`, `answer_text`, `question_id`) VALUES
(1, 'Cristóbal Colón', 1),
(2, 'Américo Vespucio', 1),
(3, 'Fernando de Magallanes', 1),
(4, 'Hernán Cortés', 1),
(5, '1914', 2),
(6, '1939', 2),
(7, '1905', 2),
(8, '1920', 2),
(9, 'Júpiter', 3),
(10, 'Saturno', 3),
(11, 'Marte', 3),
(12, 'Neptuno', 3),
(13, 'Dióxido de carbono', 4),
(14, 'Oxígeno', 4),
(15, 'Nitrógeno', 4),
(16, 'Hidrógeno', 4),
(17, '11', 5),
(18, '10', 5),
(19, '12', 5),
(20, '9', 5),
(21, 'Tenis', 6),
(22, 'Bádminton', 6),
(23, 'Squash', 6),
(24, 'Ping pong', 6),
(25, 'Nilo', 7),
(26, 'Amazonas', 7),
(27, 'Yangtsé', 7),
(28, 'Misisipi', 7),
(29, 'África', 8),
(30, 'Asia', 8),
(31, 'Europa', 8),
(32, 'América', 8),
(33, 'Walt Disney', 9),
(34, 'Stan Lee', 9),
(35, 'Steven Spielberg', 9),
(36, 'George Lucas', 9),
(37, '1997', 10),
(38, '2000', 10),
(39, '1995', 10),
(40, '2001', 10),
(121, 'Mercurio', 21),
(122, 'Venus', 21),
(123, 'Tierra', 21),
(124, 'Marte', 21),
(125, '1492', 22),
(126, '1500', 22),
(127, '1485', 22),
(128, '1498', 22),
(129, 'París', 23),
(130, 'Londres', 23),
(131, 'Berlín', 23),
(132, 'Madrid', 23),
(133, '11', 24),
(134, '9', 24),
(135, '7', 24),
(136, '5', 24),
(137, 'Shakespeare', 25),
(138, 'Cervantes', 25),
(139, 'Tolstoi', 25),
(140, 'Hemingway', 25),
(141, 'Oxígeno', 26),
(142, 'Hidrógeno', 26),
(143, 'Carbono', 26),
(144, 'Nitrógeno', 26),
(145, 'George Washington', 27),
(146, 'Abraham Lincoln', 27),
(147, 'Thomas Jefferson', 27),
(148, 'John Adams', 27),
(149, 'Tamise', 28),
(150, 'Danubio', 28),
(151, 'Rin', 28),
(152, 'Nilo', 28),
(153, 'Baloncesto', 29),
(154, 'Fútbol', 29),
(155, 'Hockey', 29),
(156, 'Voleibol', 29),
(157, 'Harry Potter', 30),
(158, 'El Señor de los Anillos', 30),
(159, 'Percy Jackson', 30),
(160, 'Crónicas de Narnia', 30),
(161, '299792 km/s', 31),
(162, '150000 km/s', 31),
(163, '100000 km/s', 31),
(164, '1 millón km/s', 31),
(165, 'Egipcia', 32),
(166, 'Mesopotámica', 32),
(167, 'Romana', 32),
(168, 'Griega', 32),
(169, 'Everest', 33),
(170, 'K2', 33),
(171, 'Mont Blanc', 33),
(172, 'Annapurna', 33),
(173, '3', 34),
(174, '5', 34),
(175, '1', 34),
(176, '7', 34),
(177, 'Leonardo da Vinci', 35),
(178, 'Miguel Ángel', 35),
(179, 'Pablo Picasso', 35),
(180, 'Rembrandt', 35),
(181, 'Oxígeno', 36),
(182, 'Nitrógeno', 36),
(183, 'Dióxido de carbono', 36),
(184, 'Helio', 36),
(185, '1945', 37),
(186, '1939', 37),
(187, '1918', 37),
(188, '1960', 37),
(189, 'Rusia', 38),
(190, 'Canadá', 38),
(191, 'China', 38),
(192, 'Estados Unidos', 38),
(193, 'Batería', 39),
(194, 'Guitarra', 39),
(195, 'Piano', 39),
(196, 'Violín', 39),
(197, 'Mickey Mouse', 40),
(198, 'Donald Duck', 40),
(199, 'Goofy', 40),
(200, 'Pluto', 40),
(201, 'Océano Pacífico', 11),
(202, 'Océano Atlántico', 11),
(203, 'Océano Índico', 11),
(204, 'Océano Ártico', 11),
(205, 'Alexander Fleming', 12),
(206, 'Louis Pasteur', 12),
(207, 'Marie Curie', 12),
(208, 'Isaac Newton', 12),
(209, 'Italia', 13),
(210, 'Francia', 13),
(211, 'España', 13),
(212, 'Alemania', 13),
(213, 'Tenis', 14),
(214, 'Fútbol', 14),
(215, 'Cricket', 14),
(216, 'Rugby', 14),
(217, 'Gabriel García Márquez', 15),
(218, 'Jorge Luis Borges', 15),
(219, 'Mario Vargas Llosa', 15),
(220, 'Isabel Allende', 15),
(221, 'Litio', 16),
(222, 'Oro', 16),
(223, 'Plata', 16),
(224, 'Aluminio', 16),
(225, 'Imperio Romano', 17),
(226, 'Imperio Griego', 17),
(227, 'Imperio Egipcio', 17),
(228, 'Imperio Persa', 17),
(229, 'Asia', 18),
(230, 'Europa', 18),
(231, 'África', 18),
(232, 'América', 18),
(233, 'Tenis', 19),
(234, 'Ping Pong', 19),
(235, 'Bádminton', 19),
(236, 'Squash', 19);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `category`
--

INSERT INTO `category` (`category_id`, `category_name`) VALUES
(1, 'Historia'),
(2, 'Ciencia'),
(3, 'Deportes'),
(4, 'Geografía'),
(5, 'Entretenimiento');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `difficulty`
--

CREATE TABLE `difficulty` (
  `difficulty_id` int(11) NOT NULL,
  `question_id` int(11) DEFAULT NULL,
  `times_used` int(11) DEFAULT 0,
  `correct_answers` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `match`
--

CREATE TABLE `match` (
  `match_id` int(11) NOT NULL,
  `player1` varchar(255) DEFAULT NULL,
  `player2` varchar(255) DEFAULT NULL,
  `round` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `player1_score` double DEFAULT NULL,
  `player2_score` double DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `question`
--

CREATE TABLE `question` (
  `question_id` int(11) NOT NULL,
  `question_text` varchar(255) NOT NULL,
  `question_date` datetime DEFAULT NULL,
  `correct_answer_id` int(11) DEFAULT NULL,
  `difficulty_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `question`
--

INSERT INTO `question` (`question_id`, `question_text`, `question_date`, `correct_answer_id`, `difficulty_id`, `category_id`, `status`) VALUES
(1, '¿Quién descubrió América?', '2025-11-02 00:00:00', 1, NULL, 1, 'activa'),
(2, '¿En qué año comenzó la Primera Guerra Mundial?', '2025-11-02 00:00:00', 5, NULL, 1, 'activa'),
(3, '¿Cuál es el planeta más grande del sistema solar?', '2025-11-02 00:00:00', 9, NULL, 2, 'activa'),
(4, '¿Qué gas necesitan las plantas para realizar la fotosíntesis?', '2025-11-02 00:00:00', 13, NULL, 2, 'activa'),
(5, '¿Cuántos jugadores tiene un equipo de fútbol en el campo?', '2025-11-02 00:00:00', 17, NULL, 3, 'activa'),
(6, '¿En qué deporte se utiliza una raqueta y una pelota amarilla?', '2025-11-02 00:00:00', 21, NULL, 3, 'activa'),
(7, '¿Cuál es el río más largo del mundo?', '2025-11-02 00:00:00', 25, NULL, 4, 'activa'),
(8, '¿En qué continente se encuentra Egipto?', '2025-11-02 00:00:00', 29, NULL, 4, 'activa'),
(9, '¿Quién es el creador de Mickey Mouse?', '2025-11-02 00:00:00', 33, NULL, 5, 'activa'),
(10, '¿En qué año se estrenó la película “Titanic”?', '2025-11-02 00:00:00', 37, NULL, 5, 'activa'),
(11, '¿Cuál es el océano más grande del mundo?', '2025-11-02 21:42:05', 201, NULL, 3, 'active'),
(12, '¿Quién descubrió la penicilina?', '2025-11-02 21:42:05', 205, NULL, 1, 'active'),
(13, '¿En qué país se encuentra la Torre de Pisa?', '2025-11-02 21:42:05', 209, NULL, 3, 'active'),
(14, '¿Qué deporte se juega en Wimbledon?', '2025-11-02 21:42:05', 213, NULL, 4, 'active'),
(15, '¿Quién escribió \"Cien años de soledad\"?', '2025-11-02 21:42:05', 217, NULL, 5, 'active'),
(16, '¿Cuál es el metal más ligero?', '2025-11-02 21:42:05', 221, NULL, 1, 'active'),
(17, '¿Qué imperio construyó el Coliseo?', '2025-11-02 21:42:05', 225, NULL, 2, 'active'),
(18, '¿Cuál es el continente más poblado?', '2025-11-02 21:42:05', 229, NULL, 3, 'active'),
(19, '¿En qué deporte se usa una raqueta y una pelota amarilla pequeña?', '2025-11-02 21:42:05', 233, NULL, 4, 'active'),
(21, '¿Cuál es el planeta más cercano al sol?', '2025-11-02 00:00:00', 121, NULL, 1, 'active'),
(22, '¿En qué año llegó Cristóbal Colón a América?', '2025-11-02 00:00:00', 125, NULL, 2, 'active'),
(23, '¿Cuál es la capital de Francia?', '2025-11-02 00:00:00', 129, NULL, 3, 'active'),
(24, '¿Cuántos jugadores tiene un equipo de fútbol en el campo?', '2025-11-02 00:00:00', 17, NULL, 4, 'active'),
(25, '¿Quién escribió Hamlet?', '2025-11-02 00:00:00', 137, NULL, 5, 'active'),
(26, '¿Cuál es el elemento químico con símbolo O?', '2025-11-02 00:00:00', 141, NULL, 1, 'active'),
(27, '¿Quién fue el primer presidente de Estados Unidos?', '2025-11-02 00:00:00', 145, NULL, 2, 'active'),
(28, '¿Qué río pasa por Londres?', '2025-11-02 00:00:00', 149, NULL, 3, 'active'),
(29, '¿En qué deporte se utiliza un aro y un balón grande?', '2025-11-02 00:00:00', 153, NULL, 4, 'active'),
(30, '¿Cuál es la saga de películas sobre un mago llamado Harry?', '2025-11-02 00:00:00', 157, NULL, 5, 'active'),
(31, '¿Cuál es la velocidad de la luz?', '2025-11-02 00:00:00', 161, NULL, 1, 'active'),
(32, '¿Qué civilización construyó las pirámides de Egipto?', '2025-11-02 00:00:00', 165, NULL, 2, 'active'),
(33, '¿Cuál es la montaña más alta del mundo?', '2025-11-02 00:00:00', 169, NULL, 3, 'active'),
(34, '¿Cuántos sets se juegan normalmente en un partido de tenis masculino?', '2025-11-02 00:00:00', 174, NULL, 4, 'active'),
(35, '¿Quién pintó la Mona Lisa?', '2025-11-02 00:00:00', 177, NULL, 5, 'active'),
(36, '¿Qué gas respiramos principalmente?', '2025-11-02 00:00:00', 181, NULL, 1, 'active'),
(37, '¿En qué año terminó la Segunda Guerra Mundial?', '2025-11-02 00:00:00', 185, NULL, 2, 'active'),
(38, '¿Cuál es el país más grande del mundo?', '2025-11-02 00:00:00', 189, NULL, 3, 'active'),
(39, '¿Qué instrumento se toca con baquetas y tiene parches?', '2025-11-02 00:00:00', 39, NULL, 4, 'active'),
(40, '¿Qué película animada tiene a un ratón llamado Mickey?', '2025-11-02 00:00:00', 197, NULL, 5, 'active');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `report`
--

CREATE TABLE `report` (
  `report_id` int(11) NOT NULL,
  `question_id` int(11) DEFAULT NULL,
  `invalid_question` char(1) DEFAULT NULL,
  `report_date` date DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` varchar(15) NOT NULL,
  `authToken` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `birth_year` datetime NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `gender` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `country` varchar(100) DEFAULT NULL,
  `profile_picture` varchar(255) NOT NULL,
  `total_score` int(20) DEFAULT NULL,
  `games_played` int(20) DEFAULT NULL,
  `games_won` int(20) DEFAULT NULL,
  `match_lost` int(20) DEFAULT NULL,
  `difficulty_level` varchar(100) DEFAULT NULL,
  `answered_questions` int(11) NOT NULL,
  `last_login` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `rol`, `authToken`, `name`, `lastname`, `birth_year`, `created_at`, `gender`, `email`, `country`, `profile_picture`, `total_score`, `games_played`, `games_won`, `match_lost`, `difficulty_level`, `answered_questions`, `last_login`) VALUES
(1, 'ADMIN', '12345', 'ADMIN', '', 'Admin', 'istrador', '1990-01-01 00:00:00', '2025-11-02 21:29:20', 'Masculino', 'eladmin@gmail.com', 'Argentina', 'images/usuario.png', 0, 0, 0, 0, NULL, 0, '2025-11-02 21:29:20'),
(2, 'alexia', 'ead5631214f67ed38456e82b16ee22576a7860282268f629316e9e1bd3bc5640', 'JUGADOR', '535631c6-b9f6-11f0-8306-6db02ed39983', 'Neymar', 'Putellas', '1994-02-04 00:00:00', '2025-11-05 03:20:05', 'Femenino', 'alexia.putellas@example.com', 'España', 'images/Neymar.jpg', 8700, 230, 180, 50, 'Avanzado', 1100, '2025-11-05 03:20:05'),
(3, 'leomessi', 'db160e9f98553ab525af6b17a719c6b010241d4d2932132d5c4b4867ab560611', 'JUGADOR', '53562985-b9f6-11f0-8306-6db02ed39983', 'Lionel', 'Messi', '1987-06-24 00:00:00', '2025-11-05 03:20:05', 'Masculino', 'leo.messi@example.com', 'Argentina', 'images/Messi.jpg', 9800, 250, 200, 50, 'Medio', 1200, '2025-11-05 03:20:05'),
(4, 'mbappe', '04d96a896a4cd7dbd16fcef1a9ebe912f789f205ce51d750fea6beb614dbbdcd', 'JUGADOR', '535630b0-b9f6-11f0-8306-6db02ed39983', 'Ronaldinho', 'Mbappé', '1998-12-20 00:00:00', '2025-11-05 03:20:05', 'Masculino', 'k.mbappe@example.com', 'Francia', 'images/Ronaldinho.jpg', 7500, 190, 140, 50, 'Principiante', 900, '2025-11-05 03:20:05'),
(5, 'panchufleto', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', 'JUGADOR', '', 'panchufleto', NULL, '1990-01-01 00:00:00', '2025-11-24 01:54:28', 'Masculino', 'panchufleto@example.com', NULL, 'images/usuario.png', 0, 0, 0, 0, NULL, 0, '2025-11-24 01:54:28');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `answer`
--
ALTER TABLE `answer`
  ADD PRIMARY KEY (`answer_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indices de la tabla `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indices de la tabla `difficulty`
--
ALTER TABLE `difficulty`
  ADD PRIMARY KEY (`difficulty_id`);

--
-- Indices de la tabla `match`
--
ALTER TABLE `match`
  ADD PRIMARY KEY (`match_id`),
  ADD KEY `player1` (`player1`),
  ADD KEY `player2` (`player2`);

--
-- Indices de la tabla `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `difficulty_id` (`difficulty_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indices de la tabla `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_report_question` (`question_id`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `answer`
--
ALTER TABLE `answer`
  MODIFY `answer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=237;

--
-- AUTO_INCREMENT de la tabla `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `difficulty`
--
ALTER TABLE `difficulty`
  MODIFY `difficulty_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `match`
--
ALTER TABLE `match`
  MODIFY `match_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `question`
--
ALTER TABLE `question`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `report`
--
ALTER TABLE `report`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `answer`
--
ALTER TABLE `answer`
  ADD CONSTRAINT `answer_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`);

--
-- Filtros para la tabla `match`
--
ALTER TABLE `match`
  ADD CONSTRAINT `match_ibfk_1` FOREIGN KEY (`player1`) REFERENCES `user` (`username`),
  ADD CONSTRAINT `match_ibfk_2` FOREIGN KEY (`player2`) REFERENCES `user` (`username`);

--
-- Filtros para la tabla `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `question_ibfk_1` FOREIGN KEY (`difficulty_id`) REFERENCES `difficulty` (`difficulty_id`),
  ADD CONSTRAINT `question_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`);

--
-- Filtros para la tabla `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `fk_report_question` FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `report_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
