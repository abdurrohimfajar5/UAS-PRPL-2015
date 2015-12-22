-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 12, 2015 at 02:26 AM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `website  isc`
--
CREATE DATABASE IF NOT EXISTS `website  isc` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `website  isc`;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
`ID` int(10) NOT NULL,
  `category` varchar(100) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

DROP TABLE IF EXISTS `post`;
CREATE TABLE IF NOT EXISTS `post` (
`ID` bigint(20) unsigned NOT NULL,
  `title` text NOT NULL,
  `category` varchar(500) NOT NULL,
  `content` text NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`ID`, `title`, `category`, `content`, `updated`) VALUES
(9, 'Indonesia Maju', 'Gadget', 'Bangsa ini telah berkembang', '2012-01-02 23:05:55'),
(10, 'Persalinan Caesar (SC)', 'Internet', 'Persentase persalinan sectio caesaria (SC) meningkat secara drastis dalam beberapa dekade terakhir. Faktor demografi dan faktor klinis tidak cukup mampu menjelaskan peningkatan tersebut. \r\n\r\nTujuan penelitian ini adalah mengukur peran dokter ahli kebidanan dalam pengambilan keputusan untuk melakukan SC dibandingkan persalinan pervaginam dengan mengontrol variabel sosiodemografi dan faktor risiko ibu dengan menggunakan data Survei Demografi Kesehatan Indonesia Tahun 2007 dan 2002. \r\n\r\nPeranan dokter ahli kebidanan dianalisis dengan menggunakan pemodelan regresi logistik. Hasil penelitian menunjukkan hubungan yang signifikan antara responden yang memilih dokter ahli kebidanan dan kandungan sebagai petugas pelayanan antenatal dengan persalinan SC yang juga dipengaruhi oleh status sosial ekonomi rumah tangga responden. Saran yang harus dilakukan adalah ', '2012-01-02 23:09:26'),
(5, 'Coba lagi aaah', 'Internet', 'Bersama surat ini kami dari Java Web Media (Your web solution) memberikan penawaran upgrade fungsi website Rumah Sakit Bumi Waras (RSBW). \r\nSebagaimana kita ketahui sebelumnya website RSBW yang sekarang online adalah jenis static website, dimana proses update website harus dilakukan oleh orang yang benar-benar memiliki pengetahuan tentang Web Design dan Web Programming.\r\nOleh karena itu, Java Web Media (Your web solution) menawarkan upgrade website menjadi dynamic website.\r\nDengan penawaran upgrade website menjadi dynamic website ini, nantinya website akan lebih mudah untuk diupdate oleh orang awam atau oleh karyawan RSBW sendiri setelah melalui pelatihan yang dilakukan atau dengan mempelajari Manual Book yang nantinya disediakan setelah proses upgrade selesai sebagai salah satu layanan purna jual kami.\r\nAdapaun detail modul-modul dan anggaran biaya yang ditawarkan dapat dilihat pada lampiran.\r\n', '2011-10-29 05:53:32'),
(7, 'UHui', 'Computer', 'ada', '2011-10-29 06:31:31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
`ID` int(10) NOT NULL,
  `name` varchar(64) NOT NULL,
  `username` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `name`, `username`, `password`, `updated`) VALUES
(2, 'fajar', 'fajar', '1995', '2015-12-04 18:50:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
MODIFY `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
