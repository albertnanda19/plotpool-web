-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 13, 2023 at 01:26 AM
-- Server version: 10.5.20-MariaDB
-- PHP Version: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id20900898_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `novel`
--

CREATE TABLE `novel` (
  `id` int(11) NOT NULL,
  `judul` varchar(100) NOT NULL,
  `penulis` varchar(255) NOT NULL,
  `sampul` varchar(100) NOT NULL,
  `genre` varchar(50) NOT NULL,
  `rating_usia` varchar(20) NOT NULL,
  `tahun_terbit` int(11) NOT NULL,
  `sinopsis` text NOT NULL,
  `file_pdf` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `novel`
--

INSERT INTO `novel` (`id`, `judul`, `penulis`, `sampul`, `genre`, `rating_usia`, `tahun_terbit`, `sinopsis`, `file_pdf`) VALUES
(1, 'Lumière Blanche', 'Cecillia Wang', 'novel-1.jpg', 'Romance', 'NC-17', 2019, 'Empat tahun setelah kematian istrinya, Nina, Alex masih berduka. Hatinya getir, ia merindukan wanita itu setiap harinya. Alex juga merasa kesal ketika melihat Dani—adik Nina yang dahulu juga adalah sahabatnya—telah kembali setelah lama tidak terlihat. Alex tidak tahu kenapa ia menyalahkan Dani atas kepergian Nina.\r\n\r\nMaka, ketika suatu kesempatan mempertemukan mereka menjadi lebih dekat, Alex menggunakan segenap cara untuk membuat Dani tahu bahwa dia marah. Tentang Nina yang sudah tiada dan tentang satu kejadian empat tahun lalu yang merenggangkan hubungan mereka. Apakah Alex akan selamanya marah atau dia harus mengakui bahwa Dani membuat ia merasakan sesuatu lagi?', 'https://www.1024tera.com/sharing/link?surl=GcIFt6wMaj1v9SsGmRPQMg'),
(3, 'Rain Sound', 'Vachaa', 'novel-2.jpg', 'Romance', 'PG-13', 2018, 'Tahukah kamu, hujan turun karena awan tak sanggup lagi menahan air yang membebaninya? Begitu juga alasan orang menangis karena tak sanggup lagi membendung emosi dan luka yang menyesaki hatinya. Aku tak mungkin bisa membenci hujan karena hanya dia yang paling mengerti kesedihanku. Hujan menemaniku saat menangisi kepergianmu.', 'https://www.1024tera.com/sharing/link?surl=zaWa9cBhinrpZJguew6dmw'),
(4, 'Rain', 'Niha', 'novel-3.jpg', 'Romance, Fiksi', 'NC-17', 2018, 'Yooan, berulang kali ia menahan rasa sakitnya, sakit saat sahabat atau cinta pertamanya lebih memilih gadis lain ketimbang dirinya, sakit saat adiknya sendiri membencinya, sakit saat ia harus pergi meninggalkan orang tersayangnya. Semua itu ia lakukan demi mereka, mereka yang telah ia sakiti dan mereka yang ia sayangi. Rasanya dunia ini tidak adil, kenapa orang lain mendapatkan kebahagiaan, sedangkan dirinya tidak? Baginya hidup ini tidak ada artinya, ia terus tersakiti dan tak pernah mencium kebahagiaan. Rasa sakit itu kian hari kian bertambah, ia tidak tahu harus menangis pada siapa, ia tidak tahu harus bersandar pada siapa, ia merasa sendiri, kesepian, dan tak bisa lagi merasakan pelukan. Sampai akhirnya Tuhan benar-benar membuatnya berada di ambang kematian, dalam tidurnya ia terus menangis, menangisi kehidupannya, kehidupan yang sebentar lagi berakhir dengan sendirinya. Haruskah Yooan menyerah? Menyerah dengan cinta dan hidupnya?', 'https://www.1024tera.com/sharing/link?surl=kkOfRtWQhezl9jX0M2nXUA');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `profile_photo` longblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `status`, `profile_photo`) VALUES
(1, 'adminplotpool', 'admin', 'test@test.com', 'admin acc', 0x2e2e2f70726f66696c655f70686f746f732f36343837633162656332383539382e32343031343135302e6a7067),
(2, 'albert', 'tidaktau', 'albertnanda1902@gmail.com', 'sedang turu', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `novel`
--
ALTER TABLE `novel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `novel`
--
ALTER TABLE `novel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
