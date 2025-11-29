-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 29-11-2025 a las 05:00:50
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
(236, 'Squash', 19),
(241, 'maiz', 43),
(242, 'barro', 43),
(243, 'aire', 43),
(244, 'Hipopotamo', 43),
(245, 'GUAAAUUU ', 44),
(246, 'WOOOOWWW', 44),
(247, 'WOFFFFF!!! ', 44),
(248, 'brrr brrrr ', 44),
(249, 'Café', 45),
(250, 'Mate', 45),
(251, 'Te', 45),
(252, 'Chocolatada', 45),
(253, '1989', 46),
(254, '1991', 46),
(255, '1985', 46),
(256, '1975', 46),
(257, 'Augusto', 47),
(258, 'Julio César', 47),
(259, 'Nerón', 47),
(260, 'Trajano', 47),
(261, 'Francia', 48),
(262, 'Italia', 48),
(263, 'España', 48),
(264, 'Alemania', 48),
(265, 'Inca', 49),
(266, 'Maya', 49),
(267, 'Azteca', 49),
(268, 'Olmeca', 49),
(269, '1969', 50),
(270, '1959', 50),
(271, '1979', 50),
(272, '1965', 50),
(273, 'Heroína francesa', 51),
(274, 'Reina de España', 51),
(275, 'Científica italiana', 51),
(276, 'Escritora inglesa', 51),
(277, 'Guerra de los Cien Años', 52),
(278, 'Primera Guerra Mundial', 52),
(279, 'Guerra de Troya', 52),
(280, 'Guerra Civil Española', 52),
(281, 'Fidel Castro', 53),
(282, 'Che Guevara', 53),
(283, 'Hugo Chávez', 53),
(284, 'Simón Bolívar', 53),
(285, '1492', 54),
(286, '1500', 54),
(287, '1485', 54),
(288, '1510', 54),
(289, 'Imperio Chino', 55),
(290, 'Imperio Mongol', 55),
(291, 'Imperio Japonés', 55),
(292, 'Imperio Persa', 55),
(293, '8', 56),
(294, '9', 56),
(295, '7', 56),
(296, '10', 56),
(297, 'Corazón', 57),
(298, 'Pulmón', 57),
(299, 'Hígado', 57),
(300, 'Riñón', 57),
(301, 'Ballena azul', 58),
(302, 'Elefante', 58),
(303, 'Jirafa', 58),
(304, 'Tiburón', 58),
(305, '100°C', 59),
(306, '90°C', 59),
(307, '110°C', 59),
(308, '80°C', 59),
(309, '206', 60),
(310, '205', 60),
(311, '210', 60),
(312, '200', 60),
(313, 'Avestruz', 61),
(314, 'Gallina', 61),
(315, 'Cocodrilo', 61),
(316, 'Pingüino', 61),
(317, 'Aluminio', 62),
(318, 'Hierro', 62),
(319, 'Cobre', 62),
(320, 'Oro', 62),
(321, 'Marte', 63),
(322, 'Venus', 63),
(323, 'Júpiter', 63),
(324, 'Saturno', 63),
(325, '32', 64),
(326, '28', 64),
(327, '30', 64),
(328, '36', 64),
(329, 'Oxígeno', 65),
(330, 'Dióxido de carbono', 65),
(331, 'Nitrógeno', 65),
(332, 'Hidrógeno', 65),
(333, 'Roma', 66),
(334, 'Milán', 66),
(335, 'Venecia', 66),
(336, 'Florencia', 66),
(337, 'América del Sur', 67),
(338, 'América del Norte', 67),
(339, 'África', 67),
(340, 'Europa', 67),
(341, 'Sahara', 68),
(342, 'Gobi', 68),
(343, 'Kalahari', 68),
(344, 'Atacama', 68),
(345, 'Océano Pacífico', 69),
(346, 'Océano Atlántico', 69),
(347, 'Océano Índico', 69),
(348, 'Océano Ártico', 69),
(349, 'Tokio', 70),
(350, 'Kioto', 70),
(351, 'Osaka', 70),
(352, 'Hiroshima', 70),
(353, 'Francia', 71),
(354, 'Italia', 71),
(355, 'España', 71),
(356, 'Inglaterra', 71),
(357, 'Vaticano', 72),
(358, 'Mónaco', 72),
(359, 'San Marino', 72),
(360, 'Liechtenstein', 72),
(361, 'Urales', 73),
(362, 'Himalaya', 73),
(363, 'Andes', 73),
(364, 'Alpes', 73),
(365, 'Canberra', 74),
(366, 'Sídney', 74),
(367, 'Melbourne', 74),
(368, 'Brisbane', 74),
(369, 'India', 75),
(370, 'Pakistán', 75),
(371, 'Bangladesh', 75),
(372, 'Nepal', 75),
(373, '5', 76),
(374, '6', 76),
(375, '7', 76),
(376, '11', 76),
(377, 'Béisbol', 77),
(378, 'Fútbol', 77),
(379, 'Tenis', 77),
(380, 'Golf', 77),
(381, '4 años', 78),
(382, '2 años', 78),
(383, '5 años', 78),
(384, '3 años', 78),
(385, 'Francia', 79),
(386, 'Croacia', 79),
(387, 'Brasil', 79),
(388, 'Alemania', 79),
(389, 'El mejor de 3 o 5', 80),
(390, 'Siempre 3', 80),
(391, 'Siempre 5', 80),
(392, 'Solo 1', 80),
(393, 'Básquetbol', 81),
(394, 'Fútbol', 81),
(395, 'Béisbol', 81),
(396, 'Tenis', 81),
(397, '5', 82),
(398, '4', 82),
(399, '6', 82),
(400, '7', 82),
(401, 'Voleibol', 83),
(402, 'Fútbol', 83),
(403, 'Básquetbol', 83),
(404, 'Tenis', 83),
(405, 'Brasil', 84),
(406, 'Alemania', 84),
(407, 'Argentina', 84),
(408, 'Italia', 84),
(409, '1', 85),
(410, '2', 85),
(411, '3', 85),
(412, '6', 85),
(413, 'Thor', 86),
(414, 'Capitán América', 86),
(415, 'Iron Man', 86),
(416, 'Hulk', 86),
(417, 'Michael Jackson', 87),
(418, 'Prince', 87),
(419, 'Elvis Presley', 87),
(420, 'Madonna', 87),
(421, 'El Rey León', 88),
(422, 'Madagascar', 88),
(423, 'Tarzán', 88),
(424, 'Bambi', 88),
(425, 'Woody', 89),
(426, 'Buzz', 89),
(427, 'Rex', 89),
(428, 'Slinky', 89),
(429, 'Queen', 90),
(430, 'The Beatles', 90),
(431, 'Led Zeppelin', 90),
(432, 'Pink Floyd', 90),
(433, 'Titanic', 91),
(434, 'Pearl Harbor', 91),
(435, 'Piratas del Caribe', 91),
(436, 'Avatar', 91),
(437, 'Joker', 92),
(438, 'Lex Luthor', 92),
(439, 'Thanos', 92),
(440, 'Loki', 92),
(441, 'Friends', 93),
(442, 'Seinfeld', 93),
(443, 'How I Met Your Mother', 93),
(444, 'The Big Bang Theory', 93),
(445, 'Robert Downey Jr.', 94),
(446, 'Chris Evans', 94),
(447, 'Chris Hemsworth', 94),
(448, 'Mark Ruffalo', 94),
(449, 'Enredados (Tangled)', 95),
(450, 'Frozen', 95),
(451, 'La Sirenita', 95),
(452, 'Valiente', 95);

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
(5, 'Entretenimiento'),
(7, 'aviacion');

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
  `category_id` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `view_count` int(11) DEFAULT NULL,
  `correct_answer_count` int(11) DEFAULT NULL,
  `difficulty_level` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `question`
--

INSERT INTO `question` (`question_id`, `question_text`, `question_date`, `correct_answer_id`, `category_id`, `status`, `view_count`, `correct_answer_count`, `difficulty_level`) VALUES
(1, '¿Quién descubrió América?', '2025-11-02 00:00:00', 1, 1, 'activa', 17, 13, 'Principiante'),
(2, '¿En qué año comenzó la Primera Guerra Mundial?', '2025-11-02 00:00:00', 5, 1, 'activa', 24, 2, 'Avanzado'),
(3, '¿Cuál es el planeta más grande del sistema solar?', '2025-11-02 00:00:00', 9, 2, 'activa', 48, 8, 'Avanzado'),
(4, '¿Qué gas necesitan las plantas para realizar la fotosíntesis?', '2025-11-02 00:00:00', 13, 2, 'activa', 18, 5, 'Avanzado'),
(5, '¿Cuántos jugadores tiene un equipo de fútbol en el campo?', '2025-11-02 00:00:00', 17, 3, 'activa', 14, 10, 'Principiante'),
(6, '¿En qué deporte se utiliza una raqueta y una pelota amarilla?', '2025-11-02 00:00:00', 21, 3, 'activa', 15, 11, 'Principiante'),
(7, '¿Cuál es el río más largo del mundo?', '2025-11-02 00:00:00', 25, 4, 'activa', 10, 7, 'Principiante'),
(8, '¿En qué continente se encuentra Egipto?', '2025-11-02 00:00:00', 29, 4, 'activa', 15, 7, 'Principiante'),
(9, '¿Quién es el creador de Mickey Mouse?', '2025-11-02 00:00:00', 33, 5, 'activa', 22, 16, 'Principiante'),
(10, '¿En qué año se estrenó la película “Titanic”?', '2025-11-02 00:00:00', 37, 5, 'activa', 30, 4, 'Avanzado'),
(11, '¿Cuál es el océano más grande del mundo?', '2025-11-02 21:42:05', 201, 3, 'active', 4, 1, 'Principiante'),
(12, '¿Quién descubrió la penicilina?', '2025-11-02 21:42:05', 205, 1, 'active', 10, 0, 'Principiante'),
(13, '¿En qué país se encuentra la Torre de Pisa?', '2025-11-02 21:42:05', 209, 3, 'active', 7, 1, 'Principiante'),
(14, '¿Qué deporte se juega en Wimbledon?', '2025-11-02 21:42:05', 213, 4, 'active', 2, 0, 'Principiante'),
(15, '¿Quién escribió \"Cien años de soledad\"?', '2025-11-02 21:42:05', 217, 5, 'active', NULL, NULL, 'Principiante'),
(16, '¿Cuál es el metal más ligero?', '2025-11-02 21:42:05', 221, 1, 'active', 2, 0, 'Principiante'),
(17, '¿Qué imperio construyó el Coliseo?', '2025-11-02 21:42:05', 225, 2, 'active', 3, 3, 'Principiante'),
(18, '¿Cuál es el continente más poblado?', '2025-11-02 21:42:05', 229, 3, 'active', 4, 0, 'Principiante'),
(19, '¿En qué deporte se usa una raqueta y una pelota amarilla pequeña?', '2025-11-02 21:42:05', 233, 4, 'active', 3, 1, 'Principiante'),
(21, '¿Cuál es el planeta más cercano al sol?', '2025-11-02 00:00:00', 121, 1, 'active', 10, 3, 'Avanzado'),
(22, '¿En qué año llegó Cristóbal Colón a América?', '2025-11-02 00:00:00', 125, 2, 'active', NULL, NULL, 'Principiante'),
(23, '¿Cuál es la capital de Francia?', '2025-11-02 00:00:00', 129, 3, 'active', 2, 2, 'Principiante'),
(24, '¿Cuántos jugadores tiene un equipo de fútbol en el campo?', '2025-11-02 00:00:00', 17, 4, 'active', 2, 0, 'Principiante'),
(25, '¿Quién escribió Hamlet?', '2025-11-02 00:00:00', 137, 5, 'active', 3, 1, 'Principiante'),
(26, '¿Cuál es el elemento químico con símbolo O?', '2025-11-02 00:00:00', 141, 1, 'active', 5, 1, 'Principiante'),
(27, '¿Quién fue el primer presidente de Estados Unidos?', '2025-11-02 00:00:00', 145, 2, 'active', 9, 6, 'Principiante'),
(28, '¿Qué río pasa por Londres?', '2025-11-02 00:00:00', 149, 3, 'active', 7, 2, 'Principiante'),
(29, '¿En qué deporte se utiliza un aro y un balón grande?', '2025-11-02 00:00:00', 153, 4, 'active', 1, 0, 'Principiante'),
(30, '¿Cuál es la saga de películas sobre un mago llamado Harry?', '2025-11-02 00:00:00', 157, 5, 'active', 7, 1, 'Principiante'),
(31, '¿Cuál es la velocidad de la luz?', '2025-11-02 00:00:00', 161, 1, 'active', 1, 0, 'Principiante'),
(32, '¿Qué civilización construyó las pirámides de Egipto?', '2025-11-02 00:00:00', 165, 2, 'active', 3, 0, 'Principiante'),
(33, '¿Cuál es la montaña más alta del mundo?', '2025-11-02 00:00:00', 169, 3, 'active', 8, 3, 'Principiante'),
(34, '¿Cuántos sets se juegan normalmente en un partido de tenis masculino?', '2025-11-02 00:00:00', 174, 4, 'active', 1, 0, 'Principiante'),
(35, '¿Quién pintó la Mona Lisa?', '2025-11-02 00:00:00', 177, 5, 'active', 7, 0, 'Principiante'),
(36, '¿Qué gas respiramos principalmente?', '2025-11-02 00:00:00', 181, 2, 'active', 1, 1, 'Principiante'),
(37, '¿En qué año terminó la Segunda Guerra Mundial?', '2025-11-02 00:00:00', 185, 2, 'active', 11, 2, 'Principiante'),
(38, '¿Cuál es el país más grande del mundo?', '2025-11-02 00:00:00', 189, 3, 'active', 3, 2, 'Principiante'),
(39, '¿Qué instrumento se toca con baquetas y tiene parches?', '2025-11-02 00:00:00', 39, 4, 'active', 1, 0, 'Principiante'),
(40, '¿Qué película animada tiene a un ratón llamado Mickey?', '2025-11-02 00:00:00', 197, 5, 'active', 4, 2, 'Principiante'),
(43, 'que come la gallina?', '2025-11-28 07:12:35', 241, 2, 'active', 0, 0, 'Principiante'),
(44, 'como ladra el perro? ', '2025-11-28 08:56:28', 245, 2, 'active', 1, 1, 'Principiante'),
(45, 'Cual es la bebida mas deliciosa', '2025-11-28 08:45:49', 249, 1, 'active', 0, 0, 'Principiante'),
(46, '¿En qué año cayó el Muro de Berlín?', '2025-11-29 04:59:48', 253, 1, 'active', 0, 0, 'Principiante'),
(47, '¿Quién fue el primer emperador romano?', '2025-11-29 04:59:48', 257, 1, 'active', 0, 0, 'Principiante'),
(48, '¿En qué país nació Napoleón Bonaparte?', '2025-11-29 04:59:48', 261, 1, 'active', 0, 0, 'Principiante'),
(49, '¿Qué civilización construyó Machu Picchu?', '2025-11-29 04:59:48', 265, 1, 'active', 0, 0, 'Principiante'),
(50, '¿En qué año llegó el hombre a la Luna?', '2025-11-29 04:59:48', 269, 1, 'active', 0, 0, 'Principiante'),
(51, '¿Quién fue Juana de Arco?', '2025-11-29 04:59:48', 273, 1, 'active', 0, 0, 'Principiante'),
(52, '¿Qué guerra duró 100 años?', '2025-11-29 04:59:48', 277, 1, 'active', 0, 0, 'Principiante'),
(53, '¿Quién fue el líder de la Revolución Cubana?', '2025-11-29 04:59:48', 281, 1, 'active', 0, 0, 'Principiante'),
(54, '¿En qué año se descubrió América?', '2025-11-29 04:59:48', 285, 1, 'active', 0, 0, 'Principiante'),
(55, '¿Qué imperio construyó la Gran Muralla China?', '2025-11-29 04:59:48', 289, 1, 'active', 0, 0, 'Principiante'),
(56, '¿Cuántos planetas tiene el sistema solar?', '2025-11-29 04:59:48', 293, 2, 'active', 0, 0, 'Principiante'),
(57, '¿Qué órgano bombea la sangre en el cuerpo humano?', '2025-11-29 04:59:48', 297, 2, 'active', 0, 0, 'Principiante'),
(58, '¿Cuál es el animal más grande del mundo?', '2025-11-29 04:59:48', 301, 2, 'active', 0, 0, 'Principiante'),
(59, '¿A qué temperatura hierve el agua?', '2025-11-29 04:59:48', 305, 2, 'active', 0, 0, 'Principiante'),
(60, '¿Cuántos huesos tiene el cuerpo humano adulto?', '2025-11-29 04:59:48', 309, 2, 'active', 0, 0, 'Principiante'),
(61, '¿Qué animal pone los huevos más grandes?', '2025-11-29 04:59:48', 313, 2, 'active', 0, 0, 'Principiante'),
(62, '¿Cuál es el metal más abundante en la corteza terrestre?', '2025-11-29 04:59:48', 317, 2, 'active', 0, 0, 'Principiante'),
(63, '¿Qué planeta es conocido como el planeta rojo?', '2025-11-29 04:59:48', 321, 2, 'active', 0, 0, 'Principiante'),
(64, '¿Cuántos dientes tiene un adulto?', '2025-11-29 04:59:48', 325, 2, 'active', 0, 0, 'Principiante'),
(65, '¿Qué gas produce las plantas durante la fotosíntesis?', '2025-11-29 04:59:48', 329, 2, 'active', 0, 0, 'Principiante'),
(66, '¿Cuál es la capital de Italia?', '2025-11-29 04:59:48', 333, 4, 'active', 0, 0, 'Principiante'),
(67, '¿En qué continente está Brasil?', '2025-11-29 04:59:48', 337, 4, 'active', 0, 0, 'Principiante'),
(68, '¿Cuál es el desierto más grande del mundo?', '2025-11-29 04:59:48', 341, 4, 'active', 0, 0, 'Principiante'),
(69, '¿Qué océano está al oeste de América?', '2025-11-29 04:59:48', 345, 4, 'active', 0, 0, 'Principiante'),
(70, '¿Cuál es la capital de Japón?', '2025-11-29 04:59:48', 349, 4, 'active', 0, 0, 'Principiante'),
(71, '¿En qué país está la Torre Eiffel?', '2025-11-29 04:59:48', 353, 4, 'active', 0, 0, 'Principiante'),
(72, '¿Cuál es el país más pequeño del mundo?', '2025-11-29 04:59:48', 357, 4, 'active', 0, 0, 'Principiante'),
(73, '¿Qué cordillera separa Europa de Asia?', '2025-11-29 04:59:48', 361, 4, 'active', 0, 0, 'Principiante'),
(74, '¿Cuál es la capital de Australia?', '2025-11-29 04:59:48', 365, 4, 'active', 0, 0, 'Principiante'),
(75, '¿En qué país está el Taj Mahal?', '2025-11-29 04:59:48', 369, 4, 'active', 0, 0, 'Principiante'),
(76, '¿Cuántos jugadores hay en un equipo de básquetbol?', '2025-11-29 04:59:48', 373, 3, 'active', 0, 0, 'Principiante'),
(77, '¿En qué deporte se usa un guante y una pelota pequeña blanca?', '2025-11-29 04:59:48', 377, 3, 'active', 0, 0, 'Principiante'),
(78, '¿Cada cuántos años se celebran los Juegos Olímpicos?', '2025-11-29 04:59:48', 381, 3, 'active', 0, 0, 'Principiante'),
(79, '¿Qué país ganó el Mundial de Fútbol 2018?', '2025-11-29 04:59:48', 385, 3, 'active', 0, 0, 'Principiante'),
(80, '¿Cuántos sets gana quien vence en tenis?', '2025-11-29 04:59:48', 389, 3, 'active', 0, 0, 'Principiante'),
(81, '¿En qué deporte destaca Michael Jordan?', '2025-11-29 04:59:48', 393, 3, 'active', 0, 0, 'Principiante'),
(82, '¿Cuántos anillos tiene el logo olímpico?', '2025-11-29 04:59:48', 397, 3, 'active', 0, 0, 'Principiante'),
(83, '¿En qué deporte se usa una red alta y un balón?', '2025-11-29 04:59:48', 401, 3, 'active', 0, 0, 'Principiante'),
(84, '¿Qué selección tiene más Copas del Mundo?', '2025-11-29 04:59:48', 405, 3, 'active', 0, 0, 'Principiante'),
(85, '¿Cuántos puntos vale un gol en fútbol?', '2025-11-29 04:59:48', 409, 3, 'active', 0, 0, 'Principiante'),
(86, '¿Qué superhéroe tiene un martillo mágico?', '2025-11-29 04:59:48', 413, 5, 'active', 0, 0, 'Principiante'),
(87, '¿Quién canta \"Thriller\"?', '2025-11-29 04:59:48', 417, 5, 'active', 0, 0, 'Principiante'),
(88, '¿Qué película tiene a un león llamado Simba?', '2025-11-29 04:59:48', 421, 5, 'active', 0, 0, 'Principiante'),
(89, '¿Cómo se llama el cowboy de Toy Story?', '2025-11-29 04:59:48', 425, 5, 'active', 0, 0, 'Principiante'),
(90, '¿Qué banda cantaba \"Bohemian Rhapsody\"?', '2025-11-29 04:59:48', 429, 5, 'active', 0, 0, 'Principiante'),
(91, '¿Qué película tiene a Jack y Rose en un barco?', '2025-11-29 04:59:48', 433, 5, 'active', 0, 0, 'Principiante'),
(92, '¿Quién es el archienemigo de Batman?', '2025-11-29 04:59:48', 437, 5, 'active', 0, 0, 'Principiante'),
(93, '¿Qué serie tiene a Ross, Rachel y Monica?', '2025-11-29 04:59:48', 441, 5, 'active', 0, 0, 'Principiante'),
(94, '¿Quién interpretó a Iron Man en las películas?', '2025-11-29 04:59:48', 445, 5, 'active', 0, 0, 'Principiante'),
(95, '¿Qué película animada tiene a una princesa con cabello mágico?', '2025-11-29 04:59:48', 449, 5, 'active', 0, 0, 'Principiante');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `report`
--

CREATE TABLE `report` (
  `report_id` int(11) NOT NULL,
  `question_id` int(11) DEFAULT NULL,
  `invalid_question` char(1) DEFAULT NULL,
  `report_date` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `report`
--

INSERT INTO `report` (`report_id`, `question_id`, `invalid_question`, `report_date`, `user_id`, `reason`) VALUES
(1, 12, NULL, '2025-11-28', 5, 'no me gusta'),
(2, 30, NULL, '2025-11-28', 5, 'no gusta'),
(3, 30, NULL, '2025-11-28', 5, 'no gusta'),
(4, 30, NULL, '2025-11-28', 5, 'no gusta'),
(9, 13, NULL, '2025-11-28', 8, 'no me gusta !'),
(10, 13, NULL, '2025-11-28', 8, '- **Usuario**: `editor`\r\n- **Contraseña**: `12345`'),
(11, 13, NULL, '2025-11-28', 8, '- **Usuario**: `editor`\r\n- **Contraseña**: `12345`'),
(12, 37, NULL, '2025-11-28', 8, 'no lo se !'),
(13, 37, NULL, '2025-11-28', 8, 'no lo se !'),
(14, 37, NULL, '2025-11-28', 8, 'no lo se !'),
(15, 37, NULL, '2025-11-28', 8, 'no lo se !'),
(16, 32, NULL, '2025-11-28', 8, 'Me ofende'),
(17, 21, NULL, '2025-11-28', 8, 'Me ofende el sol'),
(18, 26, NULL, '2025-11-28', 8, 'Me molesta elnoxigeno'),
(19, 26, NULL, '2025-11-28', 8, 'El oxigeno es malo'),
(20, 37, NULL, '2025-11-28', 8, 'Ninguno siguieron'),
(21, 16, NULL, '2025-11-29', 12, 'no creo que haya metal ligero'),
(22, 16, NULL, '2025-11-29', 12, 'si es ligero el metal entonces yo no soy ligero'),
(23, 16, NULL, '2025-11-29', 12, 'si el metal quiere ser ligero que lo sea'),
(24, 16, NULL, '2025-11-29', 12, 'que es ligero ?');

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
(5, 'panchufleto', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', 'JUGADOR', '', 'panchufleto', 'lopez', '1990-01-01 00:00:00', '2025-11-24 01:54:28', 'Otro', 'panchufle@example.com', 'Argentina', 'images/profile_panchufleto_1764312387.png', 22, 4, 0, 0, 'Medio', 0, '2025-11-24 01:54:28'),
(6, 'pancho', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', 'JUGADOR', '', 'panchito', 'Robert', '1900-02-02 00:00:00', '2025-11-24 19:57:53', 'Femenino', 'panchito@gmail.com', 'Peru', 'images/usuario.png', 16, 3, 0, 0, 'Medio', 0, '2025-11-24 19:57:53'),
(7, 'Sebas', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', 'JUGADOR', '', 'Sebastian', 'Marcheschi', '1997-02-11 00:00:00', '2025-11-25 01:21:52', 'Masculino', 'marcheschi97@hotmail.com', 'Argentina', 'images/usuario.png', 5, 1, 0, 0, 'Medio', 0, '2025-11-25 01:21:52'),
(8, 'barto', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', 'JUGADOR', '', 'bart', 'simpson', '2000-02-02 00:00:00', '2025-11-25 19:32:31', 'Masculino', 'bart@simpson.com', 'USA', 'images/profile_barto_1764312631.png', 28, 6, 0, 0, 'Medio', 0, '2025-11-25 19:32:31'),
(9, 'homero', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', 'JUGADOR', '', 'homero', 'simpson', '1980-02-02 00:00:00', '2025-11-25 19:35:27', 'Masculino', 'homero@gmail.com', 'USA', 'images/profile_homero_1764312476.png', 34, 9, 0, 0, 'Principiante', 0, '2025-11-25 19:35:27'),
(10, 'editor', '12345', 'editor', '', 'Editor', 'User', '1995-01-01 00:00:00', '2025-11-28 05:54:03', 'otro', 'editor@example.com', 'Argentina', 'images/usuario.png', 0, 0, 0, 0, NULL, 0, '2025-11-28 05:54:03'),
(11, 'pirulo', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', 'JUGADOR', '', 'pirulino', 'piru', '2000-12-12 00:00:00', '2025-11-28 08:19:00', 'Femenino', 'piru@gmail.com', 'Argentina', 'images/profile_pirulo_1764317940.jpg', 0, 0, 0, 0, 'Principiante', 0, '2025-11-28 08:19:00'),
(12, 'lisa', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', 'JUGADOR', '', 'lisa', 'simpson', '1990-02-02 00:00:00', '2025-11-29 05:23:20', 'Femenino', 'lisa@gmail.com', 'USA', 'images/profile_lisa_1764390200.png', 6, 1, 0, 0, 'Medio', 0, '2025-11-29 04:23:20');

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
  MODIFY `answer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=453;

--
-- AUTO_INCREMENT de la tabla `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT de la tabla `report`
--
ALTER TABLE `report`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
  ADD CONSTRAINT `question_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`);

--
-- Filtros para la tabla `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `fk_report_question` FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_report_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
