-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 15 Oca 2026, 23:44:13
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `english_app`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `grammar_questions`
--

CREATE TABLE `grammar_questions` (
  `id` int(11) NOT NULL,
  `set_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) NOT NULL,
  `option_d` varchar(255) NOT NULL,
  `correct_answer` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `grammar_questions`
--

INSERT INTO `grammar_questions` (`id`, `set_id`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_answer`) VALUES
(1, 1, 'She ___ to school.', 'go', 'goes', 'going', 'gone', 'b'),
(2, 1, 'I ___ a student.', 'is', 'are', 'am', 'be', 'c'),
(3, 1, 'Plural of child?', 'childs', 'childes', 'children', 'childrens', 'c'),
(4, 1, 'He ___ TV now.', 'watch', 'watches', 'is watching', 'watched', 'c'),
(5, 1, 'Correct article: ___ apple', 'a', 'an', 'the', 'none', 'b'),
(6, 1, 'I ___ coffee daily.', 'drink', 'drinks', 'drinking', 'drank', 'a'),
(7, 1, 'Opposite of big?', 'small', 'tall', 'fat', 'long', 'a'),
(8, 1, 'He ___ a car.', 'have', 'has', 'having', 'had', 'b'),
(9, 1, 'She ___ happy.', 'are', 'is', 'be', 'been', 'b'),
(10, 1, 'We ___ friends.', 'is', 'are', 'am', 'be', 'b'),
(11, 2, 'She ___ here since 2020.', 'lives', 'lived', 'has lived', 'living', 'c'),
(12, 2, 'I did not ___.', 'went', 'go', 'gone', 'going', 'b'),
(13, 2, 'Faster is ___ form.', 'comparative', 'superlative', 'base', 'noun', 'a'),
(14, 2, 'There ___ many people.', 'is', 'are', 'be', 'was', 'b'),
(15, 2, 'He speaks ___ than me.', 'good', 'better', 'best', 'well', 'b'),
(16, 2, 'If it rains, I ___ stay.', 'will', 'would', 'was', 'am', 'a'),
(17, 2, 'She enjoys ___.', 'read', 'to read', 'reading', 'reads', 'c'),
(18, 2, 'Looking ___ my keys.', 'to', 'for', 'at', 'on', 'b'),
(19, 2, 'He ___ finished.', 'has not', 'did not', 'is not', 'was not', 'a'),
(20, 2, 'She asked me ___ I was.', 'what', 'where', 'why', 'who', 'b'),
(21, 3, 'If I ___ more time, I would travel.', 'have', 'had', 'will have', 'has', 'b'),
(22, 3, 'He suggested that we ___ earlier.', 'leave', 'left', 'leaving', 'will leave', 'a'),
(23, 3, 'She speaks English very ___.', 'fluent', 'fluency', 'fluently', 'more fluent', 'c'),
(24, 3, 'The report ___ by the manager.', 'wrote', 'was written', 'has write', 'is writing', 'b'),
(25, 3, 'I am not used to ___ up early.', 'get', 'getting', 'got', 'gets', 'b'),
(26, 3, 'He denied ___ the documents.', 'steal', 'to steal', 'stealing', 'stolen', 'c'),
(27, 3, 'Rarely ___ such dedication.', 'I see', 'see I', 'do I see', 'I do see', 'c'),
(28, 3, 'She acted as if she ___ everything.', 'knows', 'knew', 'has known', 'know', 'b'),
(29, 3, 'The meeting was postponed ___ the strike.', 'because', 'because of', 'although', 'despite', 'b'),
(30, 3, 'He apologized ___ being late.', 'for', 'to', 'about', 'with', 'a'),
(31, 4, 'Had I known earlier, I ___ differently.', 'will act', 'would act', 'would have acted', 'acted', 'c'),
(32, 4, 'The more you practice, ___ you become.', 'better', 'the better', 'the best', 'more better', 'b'),
(33, 4, 'She objected to ___ treated unfairly.', 'be', 'being', 'been', 'have been', 'b'),
(34, 4, 'Not only ___ late, but he also forgot the files.', 'he was', 'was he', 'he is', 'is he', 'b'),
(35, 4, 'He is said ___ the company.', 'run', 'to run', 'running', 'ran', 'b'),
(36, 4, 'No sooner had we arrived ___ it started raining.', 'when', 'than', 'then', 'while', 'b'),
(37, 4, 'She has her car ___.', 'repair', 'repaired', 'repairing', 'to repair', 'b'),
(38, 4, 'The proposal was rejected ___ its high cost.', 'due', 'because', 'due to', 'although', 'c'),
(39, 4, 'He speaks as though he ___ the expert.', 'is', 'was', 'were', 'be', 'c'),
(40, 4, 'Little ___ about the consequences.', 'he knew', 'did he know', 'he knows', 'knows he', 'b'),
(41, 5, 'Scarcely ___ the announcement when reactions followed.', 'had they made', 'they had made', 'have they made', 'they make', 'a'),
(42, 5, 'The committee recommended that he ___ immediately.', 'resigns', 'resigned', 'resign', 'resigning', 'c'),
(43, 5, 'It is high time we ___ action.', 'take', 'took', 'have taken', 'will take', 'b'),
(44, 5, 'She is widely regarded ___ the leading expert.', 'as', 'to', 'for', 'like', 'a'),
(45, 5, 'Hardly ___ finished speaking when objections arose.', 'he had', 'had he', 'he has', 'has he', 'b'),
(46, 5, 'The findings bear little ___ to the initial hypothesis.', 'relation', 'connection', 'similar', 'comparison', 'a'),
(47, 5, 'Were it not for her help, we ___ failed.', 'will have', 'would have', 'would', 'had', 'b'),
(48, 5, 'He tends to overestimate his abilities, ___?', 'does he not', 'is he not', 'has he not', 'will he not', 'a'),
(49, 5, 'The policy is intended to ___ growth.', 'foster', 'gain', 'rise', 'improve', 'a'),
(50, 5, 'So complex ___ the issue that few understood it.', 'is', 'was', 'were', 'be', 'a');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `grammar_sets`
--

CREATE TABLE `grammar_sets` (
  `id` int(11) NOT NULL,
  `level` varchar(5) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `grammar_sets`
--

INSERT INTO `grammar_sets` (`id`, `level`, `title`, `description`) VALUES
(1, 'A1', 'A1 - Beginner Grammar', 'Basic sentence structures and verbs.'),
(2, 'A2', 'A2 - Elementary Grammar', 'Past tense, comparisons, and prepositions.'),
(3, 'B1', 'B1 - Intermediate Grammar', 'Conditionals, passive voice, and gerunds.'),
(4, 'B2', 'B2 - Upper Intermediate', 'Advanced conditionals and inversions.'),
(5, 'C1', 'C1 - Advanced Grammar', 'Complex structures and academic styles.');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `question_options`
--

CREATE TABLE `question_options` (
  `id` int(11) NOT NULL,
  `question_id` int(11) DEFAULT NULL,
  `option_text` varchar(255) DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `question_options`
--

INSERT INTO `question_options` (`id`, `question_id`, `option_text`, `is_correct`) VALUES
(1, 1, 'Ten', 0),
(2, 1, 'Twenty', 1),
(3, 1, 'Fourteen', 0),
(4, 1, 'Thirty', 0),
(5, 2, 'Paris', 0),
(6, 2, 'New York', 0),
(7, 2, 'London', 1),
(8, 2, 'Berlin', 0),
(9, 3, 'Teacher', 0),
(10, 3, 'Doctor', 1),
(11, 3, 'Driver', 0),
(12, 3, 'Pilot', 0),
(13, 4, 'She is a nurse', 0),
(14, 4, 'She is a doctor', 0),
(15, 4, 'She is a teacher', 1),
(16, 4, 'She is a chef', 0),
(17, 5, 'One', 0),
(18, 5, 'Two', 1),
(19, 5, 'Three', 0),
(20, 5, 'Four', 0),
(21, 6, 'John and Max', 0),
(22, 6, 'Peter and Mark', 1),
(23, 6, 'Tom and Jerry', 0),
(24, 6, 'Luna and Mary', 0),
(25, 7, 'A bird and a fish', 0),
(26, 7, 'Two cats', 0),
(27, 7, 'A dog and a cat', 1),
(28, 7, 'Only a dog', 0),
(29, 8, 'Luna', 0),
(30, 8, 'Max', 1),
(31, 8, 'Peter', 0),
(32, 8, 'Rocky', 0),
(33, 9, 'Go to the cinema', 0),
(34, 9, 'Go to the park', 1),
(35, 9, 'Sleep all day', 0),
(36, 9, 'Go to school', 0),
(37, 10, 'They are rich', 0),
(38, 10, 'They live in London', 0),
(39, 10, 'They are supportive', 1),
(40, 10, 'They have pets', 0),
(41, 11, 'Istanbul', 0),
(42, 11, 'Antalya', 1),
(43, 11, 'London', 0),
(44, 11, 'Paris', 0),
(45, 12, 'By car', 0),
(46, 12, 'By bus', 0),
(47, 12, 'By plane', 1),
(48, 12, 'By train', 0),
(49, 13, 'Cold and rainy', 0),
(50, 13, 'Hot and sunny', 1),
(51, 13, 'Windy', 0),
(52, 13, 'Snowy', 0),
(53, 14, 'Pizza', 0),
(54, 14, 'Eggs, cheese, olives', 1),
(55, 14, 'Soup', 0),
(56, 14, 'Burger', 0),
(57, 15, 'Slept', 0),
(58, 15, 'Went to the beach', 1),
(59, 15, 'Watched TV', 0),
(60, 15, 'Went skiing', 0),
(61, 16, 'Sailing', 0),
(62, 16, 'Scuba diving', 1),
(63, 16, 'Surfing', 0),
(64, 16, 'Driving', 0),
(65, 17, 'Sharks', 0),
(66, 17, 'Colorful fish', 1),
(67, 17, 'Boats', 0),
(68, 17, 'Nothing', 0),
(69, 18, 'A museum', 0),
(70, 18, 'An ancient city', 1),
(71, 18, 'A zoo', 0),
(72, 18, 'A mall', 0),
(73, 19, 'A magnet', 1),
(74, 19, 'A shirt', 0),
(75, 19, 'A hat', 0),
(76, 19, 'A bag', 0),
(77, 20, 'Because it was short', 0),
(78, 20, 'Because they swam', 0),
(79, 20, 'Because it was fantastic', 1),
(80, 20, 'It was bad', 0),
(81, 21, 'Books', 0),
(82, 21, 'Technology', 1),
(83, 21, 'Sports', 0),
(84, 21, 'Food', 0),
(85, 22, 'Computers', 0),
(86, 22, 'Blackboards and chalk', 1),
(87, 22, 'Tablets', 0),
(88, 22, 'Robots', 0),
(89, 23, 'Only phones', 0),
(90, 23, 'Whiteboards, tablets, computers', 1),
(91, 23, 'Radios', 0),
(92, 23, 'Nothing', 0),
(93, 24, 'They are heavy', 0),
(94, 24, 'They hold many books', 1),
(95, 24, 'They are expensive', 0),
(96, 24, 'They break easily', 0),
(97, 25, 'Makes it boring', 0),
(98, 25, 'Makes it engaging and fun', 1),
(99, 25, 'Makes it hard', 0),
(100, 25, 'Makes it slow', 0),
(101, 26, 'Too much homework', 0),
(102, 26, 'Distraction', 1),
(103, 26, 'High costs', 0),
(104, 26, 'Bad eyesight', 0),
(105, 27, 'Intelligence', 0),
(106, 27, 'Unequal access to technology', 1),
(107, 27, 'Living location', 0),
(108, 27, 'Age', 0),
(109, 28, 'Ban technology', 0),
(110, 28, 'Find a balance', 1),
(111, 28, 'Use only technology', 0),
(112, 28, 'Ignore it', 0),
(113, 29, 'Yes', 0),
(114, 29, 'No', 1),
(115, 29, 'Maybe', 0),
(116, 29, 'Not mentioned', 0),
(117, 30, 'Technology is bad', 0),
(118, 30, 'Technology in education has pros and cons', 1),
(119, 30, 'Schools are old', 0),
(120, 30, 'Tablets are cheap', 0),
(121, 31, 'For planet', 1),
(122, 32, 'No', 1),
(123, 33, 'Mental health', 1),
(124, 34, 'Mars', 1),
(125, 35, 'Revolution', 1),
(126, 36, 'Power', 1),
(127, 37, 'Quantum', 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `reading_passages`
--

CREATE TABLE `reading_passages` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `cefr_level` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `reading_passages`
--

INSERT INTO `reading_passages` (`id`, `title`, `content`, `cefr_level`) VALUES
(1, 'Level 1: My Family and Home (A1)', 'My name is Sarah and I am twenty years old. I have a large family and we live in a big, white house in London. My father is named John. He is a doctor and works at the city hospital. He is very kind and helps sick people every day. My mother is named Mary. She is a teacher at a primary school. She loves reading books to her students. I have two brothers, Peter and Mark. Peter is ten years old and Mark is fourteen. They love playing football in the garden. We also have a dog named Max and a cat named Luna. Max is very friendly but Luna is shy. On weekends, we usually go to the park together. We have a picnic and play games. In the evening, we watch movies in the living room. I love my family very much because they are always supportive.', 'A1'),
(2, 'Level 2: The Summer Holiday (A2)', 'Last summer, my family and I went on a fantastic holiday to Antalya, Turkey. We traveled by plane from Istanbul, and the flight took about one hour. We stayed at a large hotel near the beach. The weather was very hot and sunny every day, which was perfect for swimming. In the mornings, we usually had a big breakfast with eggs, cheese, and olives. After breakfast, we went to the beach to swim in the sea and build sandcastles. My father tried scuba diving for the first time and he saw many colorful fish. One day, we visited an ancient city nearby. It was very interesting to see the old ruins and learn about history. In the evenings, we walked around the town center and bought some souvenirs. I bought a beautiful magnet for my collection. It was the best holiday of my life.', 'A2'),
(3, 'Level 3: Technology in Education (B1)', 'Technology has become an essential part of modern education, transforming how students learn and teachers teach. In the past, classrooms were equipped with only blackboards and chalk. Today, however, many schools use interactive whiteboards, tablets, and computers. This digital revolution allows students to access a vast amount of information instantly. For example, instead of carrying heavy textbooks, a student can have thousands of books on a single tablet. Furthermore, educational software and games make learning more engaging and fun. Despite these benefits, there are some concerns. Some teachers worry that students might get distracted by social media or games during class. Additionally, not all students have equal access to these technologies at home, which can create a gap between rich and poor students. Therefore, it is important to find a balance and ensure that technology is used effectively to support learning, not replace traditional methods entirely.', 'B1'),
(4, 'Level 4: Environment (B1)', 'Recycling is vital for our planet. We produce too much waste...', 'B1'),
(5, 'Level 5: Careers (B2)', 'Choosing a career is a difficult decision for many young people...', 'B2'),
(6, 'Level 6: Health (B2)', 'Mental health is just as important as physical health...', 'B2'),
(7, 'Level 7: Space (C1)', 'The colonization of Mars presents significant engineering challenges...', 'C1'),
(8, 'Level 8: History (C1)', 'The Industrial Revolution changed the landscape of society forever...', 'C1'),
(9, 'Level 9: Economics (C1)', 'Inflation affects the purchasing power of consumers globally...', 'C1'),
(10, 'Level 10: Future Tech (C1)', 'Quantum computing will revolutionize data processing speeds...', 'C1');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `reading_questions`
--

CREATE TABLE `reading_questions` (
  `id` int(11) NOT NULL,
  `passage_id` int(11) DEFAULT NULL,
  `question_text` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `reading_questions`
--

INSERT INTO `reading_questions` (`id`, `passage_id`, `question_text`) VALUES
(1, 1, 'How old is Sarah?'),
(2, 1, 'Where does Sarah live?'),
(3, 1, 'What is her fathers job?'),
(4, 1, 'What does her mother do?'),
(5, 1, 'How many brothers does Sarah have?'),
(6, 1, 'What are the names of her brothers?'),
(7, 1, 'What kind of pets do they have?'),
(8, 1, 'What is the dogs name?'),
(9, 1, 'What do they usually do on weekends?'),
(10, 1, 'Why does Sarah love her family?'),
(11, 2, 'Where did the family go for holiday?'),
(12, 2, 'How did they travel?'),
(13, 2, 'How was the weather?'),
(14, 2, 'What did they eat for breakfast?'),
(15, 2, 'What did they do after breakfast?'),
(16, 2, 'What activity did the father try?'),
(17, 2, 'What did the father see while diving?'),
(18, 2, 'What did they visit one day?'),
(19, 2, 'What did the writer buy?'),
(20, 2, 'Why was it the best holiday?'),
(21, 3, 'What has transformed modern education?'),
(22, 3, 'What did classrooms have in the past?'),
(23, 3, 'What devices are used in schools today?'),
(24, 3, 'What is one benefit of tablets mentioned?'),
(25, 3, 'How does software affect learning?'),
(26, 3, 'What is a concern teachers have?'),
(27, 3, 'What can cause a gap between students?'),
(28, 3, 'What does the text suggest about balance?'),
(29, 3, 'Does the text say technology should replace teachers?'),
(30, 3, 'What is the main idea of the text?'),
(31, 4, 'Why is recycling vital?'),
(32, 5, 'Is choosing a career easy?'),
(33, 6, 'What is important?'),
(34, 7, 'What is the topic?'),
(35, 8, 'What changed society?'),
(36, 9, 'What does inflation affect?'),
(37, 10, 'What will revolutionize data?');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `vocab_level` varchar(5) DEFAULT 'A1',
  `grammar_level` varchar(5) DEFAULT 'A1',
  `reading_level` varchar(5) DEFAULT 'A1',
  `has_taken_placement` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `vocab_level`, `grammar_level`, `reading_level`, `has_taken_placement`) VALUES
(1, 'kagan', 'kaganbugra@hotmail.com', '$2y$10$YjauD2HSp/mcFFLeujYGiedYZdrV8FbWj9GLOjDZaTaVkuLUPHyV6', '2026-01-15 14:08:39', 'A1', 'A1', 'A1', 0),
(2, 'kagankorkmaz', 'kagan@hotmail.com', '$2y$10$RQV2qnk.fTnX94UGQQbL4ORtV/ZZsQ7C5YP/UgDKcgVWNc9gw6tam', '2026-01-15 14:09:20', 'A1', 'A1', 'A1', 0),
(3, 'ahmet', 'ahmeterkci@hotmail.com', '$2y$10$bPLbkgKhhMGEKWtQrvNMbOZtYDgMna8f0NyuQig9x1YlVuBTv5M0.', '2026-01-15 16:37:05', 'A1', 'A1', 'A1', 0),
(5, 'jaximus', 'kagan@hotmail.com', '$2y$10$fOSNDncJZu011wbE2GwdP.hVgT9AyYOxuzehAriFeJzogP4zcA63m', '2026-01-15 21:09:17', 'A1', 'A1', 'A1', 1),
(6, 'Maximus', 'maximus@hotmail.com', '$2y$10$k5beqgnyf9qFk7tSS10nBOQKMscV86U8tKmgXodhTbByOxE8Yqtu2', '2026-01-15 21:13:12', 'A2', 'A2', 'A2', 1),
(7, 'paganates', 'ates@hotmail.com', '$2y$10$ropMgVoyGVoFgMV7/1YWvuLN6kJ4.r0FQb7BTssba4LEprEvgta3y', '2026-01-15 21:16:11', 'B2', 'C1', 'A2', 1),
(9, 'ARİF ABİ', 'arifgayirnal@hotmail.com', '$2y$10$MoodwZx409oHrhEgHWT.y.0GFNpmlP9CENCpBnxDgDtEZTPfOcxQ.', '2026-01-15 21:49:11', 'A1', 'A1', 'A1', 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `user_progress`
--

CREATE TABLE `user_progress` (
  `user_id` int(11) NOT NULL,
  `xp` int(11) DEFAULT 0,
  `streak` int(11) DEFAULT 0,
  `last_active` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `user_progress`
--

INSERT INTO `user_progress` (`user_id`, `xp`, `streak`, `last_active`) VALUES
(2, 400, 1, '2026-01-15'),
(4, 400, 1, '2026-01-15'),
(6, 300, 1, '2026-01-15');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `user_results`
--

CREATE TABLE `user_results` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category` varchar(20) NOT NULL,
  `score` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `user_results`
--

INSERT INTO `user_results` (`id`, `user_id`, `category`, `score`, `created_at`) VALUES
(1, 2, 'vocab', 100, '2026-01-15 17:07:17'),
(2, 2, 'vocab', 100, '2026-01-15 17:07:28'),
(3, 3, 'vocab', 0, '2026-01-15 17:08:43'),
(4, 3, 'vocab', 100, '2026-01-15 17:09:22'),
(5, 3, 'vocab', 100, '2026-01-15 17:28:37'),
(6, 2, 'vocab', 100, '2026-01-15 17:56:43'),
(8, 3, 'vocab-3', 100, '2026-01-15 18:20:16'),
(10, 2, 'vocab-2', 100, '2026-01-15 20:10:52'),
(11, 2, 'vocab-3', 100, '2026-01-15 20:14:17'),
(13, 2, 'vocab-1', 100, '2026-01-15 20:16:00'),
(14, 2, 'reading-1', 100, '2026-01-15 20:36:10'),
(15, 4, 'vocab-1', 100, '2026-01-15 20:56:35'),
(16, 4, 'reading-1', 100, '2026-01-15 20:57:10'),
(17, 4, 'vocab-2', 100, '2026-01-15 20:59:04'),
(18, 4, 'grammar-1', 100, '2026-01-15 21:00:01'),
(19, 6, 'reading-1', 100, '2026-01-15 21:15:18'),
(20, 6, 'grammar-1', 100, '2026-01-15 21:44:34'),
(21, 6, 'vocab-1', 100, '2026-01-15 21:45:35');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `vocab_sets`
--

CREATE TABLE `vocab_sets` (
  `id` int(11) NOT NULL,
  `level` varchar(5) NOT NULL,
  `title` varchar(100) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `vocab_sets`
--

INSERT INTO `vocab_sets` (`id`, `level`, `title`, `image_url`) VALUES
(1, 'A1', 'A1 - Başlangıç Seviyesi', NULL),
(2, 'A2', 'A2 - Temel İletişim', NULL),
(3, 'B1', 'B1 - Orta Seviye', NULL),
(4, 'B2', 'B2 - İleri Orta Seviye', NULL),
(5, 'C1', 'C1 - Profesyonel İngilizce', NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `vocab_words`
--

CREATE TABLE `vocab_words` (
  `id` int(11) NOT NULL,
  `set_id` int(11) NOT NULL,
  `word` varchar(100) NOT NULL,
  `meaning` varchar(255) NOT NULL,
  `example` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `vocab_words`
--

INSERT INTO `vocab_words` (`id`, `set_id`, `word`, `meaning`, `example`) VALUES
(11, 1, 'apple', 'elma', 'I eat an apple every day.'),
(12, 1, 'house', 'ev', 'My house is big.'),
(13, 1, 'teacher', 'öğretmen', 'She is my English teacher.'),
(14, 1, 'happy', 'mutlu', 'I feel happy today.'),
(15, 1, 'book', 'kitap', 'This book is interesting.'),
(16, 1, 'water', 'su', 'Drink water every day.'),
(17, 1, 'friend', 'arkadaş', 'He is my best friend.'),
(18, 1, 'city', 'şehir', 'I live in a big city.'),
(19, 1, 'car', 'araba', 'My car is red.'),
(20, 1, 'food', 'yemek', 'This food is delicious.'),
(21, 2, 'improve', 'geliştirmek', 'I want to improve my English.'),
(22, 2, 'decide', 'karar vermek', 'She decided to study.'),
(23, 2, 'travel', 'seyahat etmek', 'I like to travel abroad.'),
(24, 2, 'comfortable', 'rahat', 'This chair is comfortable.'),
(25, 2, 'important', 'önemli', 'This exam is important.'),
(26, 2, 'experience', 'deneyim', 'I have work experience.'),
(27, 2, 'difference', 'fark', 'There is a difference.'),
(28, 2, 'success', 'başarı', 'Success needs effort.'),
(29, 2, 'problem', 'problem', 'We solved the problem.'),
(30, 2, 'opinion', 'fikir', 'In my opinion, it is good.'),
(31, 3, 'challenge', 'zorluk', 'Learning English is a challenge.'),
(32, 3, 'opportunity', 'fırsat', 'This job is an opportunity.'),
(33, 3, 'manage', 'yönetmek', 'She manages the team.'),
(34, 3, 'develop', 'geliştirmek', 'Skills develop with practice.'),
(35, 3, 'environment', 'çevre', 'Protect the environment.'),
(36, 3, 'solution', 'çözüm', 'We found a solution.'),
(37, 3, 'effective', 'etkili', 'This method is effective.'),
(38, 3, 'increase', 'artmak', 'Sales increased.'),
(39, 3, 'reduce', 'azaltmak', 'Reduce stress.'),
(40, 3, 'consider', 'düşünmek', 'Consider the options.'),
(41, 4, 'significant', 'önemli', 'There is a significant change.'),
(42, 4, 'maintain', 'sürdürmek', 'Maintain quality.'),
(43, 4, 'assume', 'varsaymak', 'I assume he knows.'),
(44, 4, 'efficient', 'verimli', 'An efficient system.'),
(45, 4, 'consequence', 'sonuç', 'Actions have consequences.'),
(46, 4, 'priority', 'öncelik', 'Safety is a priority.'),
(47, 4, 'commitment', 'bağlılık', 'Show commitment.'),
(48, 4, 'achieve', 'başarmak', 'Achieve success.'),
(49, 4, 'impact', 'etki', 'Social impact matters.'),
(50, 4, 'strategy', 'strateji', 'Use a strategy.'),
(51, 5, 'inevitable', 'kaçınılmaz', 'Change is inevitable.'),
(52, 5, 'ambiguous', 'belirsiz', 'An ambiguous answer.'),
(53, 5, 'comprehensive', 'kapsamlı', 'A comprehensive report.'),
(54, 5, 'sophisticated', 'karmaşık', 'A sophisticated system.'),
(55, 5, 'phenomenon', 'olgu', 'A social phenomenon.'),
(56, 5, 'hypothesis', 'hipotez', 'Test the hypothesis.'),
(57, 5, 'substantial', 'büyük/önemli', 'Substantial improvement.'),
(58, 5, 'implication', 'ima/çıkarım', 'Political implications.'),
(59, 5, 'notion', 'kavram', 'Reject the notion.'),
(60, 5, 'articulate', 'ifade etmek', 'She articulated her ideas.');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `grammar_questions`
--
ALTER TABLE `grammar_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `set_id` (`set_id`);

--
-- Tablo için indeksler `grammar_sets`
--
ALTER TABLE `grammar_sets`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `question_options`
--
ALTER TABLE `question_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Tablo için indeksler `reading_passages`
--
ALTER TABLE `reading_passages`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `reading_questions`
--
ALTER TABLE `reading_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `passage_id` (`passage_id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Tablo için indeksler `user_progress`
--
ALTER TABLE `user_progress`
  ADD PRIMARY KEY (`user_id`);

--
-- Tablo için indeksler `user_results`
--
ALTER TABLE `user_results`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `vocab_sets`
--
ALTER TABLE `vocab_sets`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `vocab_words`
--
ALTER TABLE `vocab_words`
  ADD PRIMARY KEY (`id`),
  ADD KEY `set_id` (`set_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `grammar_questions`
--
ALTER TABLE `grammar_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- Tablo için AUTO_INCREMENT değeri `grammar_sets`
--
ALTER TABLE `grammar_sets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `question_options`
--
ALTER TABLE `question_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- Tablo için AUTO_INCREMENT değeri `reading_passages`
--
ALTER TABLE `reading_passages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Tablo için AUTO_INCREMENT değeri `reading_questions`
--
ALTER TABLE `reading_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Tablo için AUTO_INCREMENT değeri `user_results`
--
ALTER TABLE `user_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Tablo için AUTO_INCREMENT değeri `vocab_sets`
--
ALTER TABLE `vocab_sets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `vocab_words`
--
ALTER TABLE `vocab_words`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `grammar_questions`
--
ALTER TABLE `grammar_questions`
  ADD CONSTRAINT `grammar_questions_ibfk_1` FOREIGN KEY (`set_id`) REFERENCES `grammar_sets` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `question_options`
--
ALTER TABLE `question_options`
  ADD CONSTRAINT `question_options_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `reading_questions` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `reading_questions`
--
ALTER TABLE `reading_questions`
  ADD CONSTRAINT `reading_questions_ibfk_1` FOREIGN KEY (`passage_id`) REFERENCES `reading_passages` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `vocab_words`
--
ALTER TABLE `vocab_words`
  ADD CONSTRAINT `vocab_words_ibfk_1` FOREIGN KEY (`set_id`) REFERENCES `vocab_sets` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
