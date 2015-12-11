-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le : Mar 02 Octobre 2012 à 11:18
-- Version du serveur: 5.5.24
-- Version de PHP: 5.3.10-1ubuntu3.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `kibsa_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `autrelivr`
--

CREATE TABLE IF NOT EXISTS `autrelivr` (
  `ID_AUTRELIVR` int(11) NOT NULL AUTO_INCREMENT,
  `ID_EXERCICE` int(11) NOT NULL,
  `CODE_MAGASIN` varchar(10) NOT NULL,
  `CODE_AUTRELIVR` varchar(250) DEFAULT NULL,
  `AUL_DATE` date DEFAULT NULL,
  `AUL_SOUCRE` varchar(20) DEFAULT NULL,
  `AUL_DETAIL` text,
  `AUL_VALIDE` tinyint(4) DEFAULT NULL,
  `AUL_DATEVALID` datetime DEFAULT NULL,
  PRIMARY KEY (`ID_AUTRELIVR`),
  KEY `EX_AUTRELIVR_FK` (`ID_EXERCICE`),
  KEY `MAGREC_FK` (`CODE_MAGASIN`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `bareme`
--

CREATE TABLE IF NOT EXISTS `bareme` (
  `ID_BAREME` int(11) NOT NULL AUTO_INCREMENT,
  `BAR_NATURE` varchar(20) NOT NULL,
  `CODE_PRODUIT` varchar(10) NOT NULL,
  `ID_UNITE` varchar(10) NOT NULL,
  `BAR_LIBELLE` varchar(50) DEFAULT NULL,
  `BAR_QTE` float DEFAULT NULL,
  `BAR_PRIX` float DEFAULT NULL,
  `BAR_MIXTE` tinyint(4) DEFAULT NULL,
  `PRD_MIXTE` varchar(10) DEFAULT NULL,
  `BAR_QTEMIX` float DEFAULT NULL,
  `BAR_VALID` tinyint(4) DEFAULT NULL,
  `ORDRE` int(11) DEFAULT NULL,
  `TOTAL` int(11) DEFAULT NULL,
  `BACNBREPLAT` int(11) NOT NULL,
  `RATIONTOTAL` float NOT NULL,
  PRIMARY KEY (`ID_BAREME`),
  KEY `CND_BAR_FK` (`CODE_PRODUIT`),
  KEY `UNT_BAR_FK` (`ID_UNITE`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Contenu de la table `bareme`
--

INSERT INTO `bareme` (`ID_BAREME`, `BAR_NATURE`, `CODE_PRODUIT`, `ID_UNITE`, `BAR_LIBELLE`, `BAR_QTE`, `BAR_PRIX`, `BAR_MIXTE`, `PRD_MIXTE`, `BAR_QTEMIX`, `BAR_VALID`, `ORDRE`, `TOTAL`, `BACNBREPLAT`, `RATIONTOTAL`) VALUES
(1, 'DOT', 'PA-01', 'kg', 'Riz', 0.275, 35, 0, '', 0, NULL, NULL, NULL, 0, 0),
(2, 'DOT', 'PA-02', 'kg', 'Haricot', 0.275, 35, 0, '', 0, NULL, NULL, NULL, 0, 0),
(3, 'DOT', 'PA-04', 'bid', 'Huile', 0, 0, 0, '', 0, NULL, NULL, NULL, 0, 0),
(4, 'DOT', 'PA-03', 'kg', 'PÃ¢te alimentaire', 0.25, 35, 0, '', 0, NULL, NULL, NULL, 0, 0),
(5, 'DOT', 'PA-05', 'cart', 'Sardine', 0, 0, 0, '', 0, NULL, NULL, NULL, 0, 0),
(6, 'DOT', 'PA-07', 'cart', 'Tomate', 0, 0, 0, '', 0, NULL, NULL, NULL, 0, 0),
(7, 'BAC', 'PA-01', 'kg', 'Riz', 0.275, 0, NULL, NULL, NULL, NULL, NULL, NULL, 20, 5.5),
(8, 'BAC', 'PA-011', 'kg', 'Couscous arabe', 0.25, 0, NULL, NULL, NULL, NULL, NULL, NULL, 5, 1.25),
(9, 'BAC', 'PA-02', 'kg', 'Haricot', 0.275, 0, NULL, NULL, NULL, NULL, NULL, NULL, 5, 1.375),
(10, 'BAC', 'PA-03', 'kg', 'PÃ¢te alimentaire', 0.25, 0, NULL, NULL, NULL, NULL, NULL, NULL, 14, 3.5),
(11, 'BAC', 'PA-04', 'l', 'huile', 0.06, 0, NULL, NULL, NULL, NULL, NULL, NULL, 42, 2.52),
(12, 'BAC', 'PA-07', 'kg', 'Tomate', 0.02, 0, NULL, NULL, NULL, NULL, NULL, NULL, 42, 0.84),
(13, 'BAC', 'PA-06', 'kg', 'CafÃ©', 0.002, 0, NULL, NULL, NULL, NULL, NULL, NULL, 21, 0.042),
(14, 'BAC', 'PA-08', 'kg', 'Lait', 0.018, 0, NULL, NULL, NULL, NULL, NULL, NULL, 21, 3.78),
(15, 'BAC', 'PA-09', 'kg', 'Sucre', 0.03, 0, NULL, NULL, NULL, NULL, NULL, NULL, 21, 0.63);

-- --------------------------------------------------------

--
-- Structure de la table `beneficiaire`
--

CREATE TABLE IF NOT EXISTS `beneficiaire` (
  `ID_BENEF` int(11) NOT NULL AUTO_INCREMENT,
  `CODE_NOMBENF` varchar(10) NOT NULL,
  `IDPROVINCE` int(11) NOT NULL,
  `CODE_BENEF` varchar(10) DEFAULT NULL,
  `BENEF_NOM` varchar(150) DEFAULT NULL,
  `BENEF_EBREVIATION` varchar(10) DEFAULT NULL,
  `BENEF_TEL` varchar(30) DEFAULT NULL,
  `BENEF_ADRESSE` varchar(250) DEFAULT NULL,
  `BENEF_VILLE` varchar(50) NOT NULL,
  `BENEF_EMAIL` varchar(100) DEFAULT NULL,
  `BENEF_DATEINT` date DEFAULT NULL,
  `BENEF_DIST` float DEFAULT NULL,
  `BENEF_EFF` int(11) NOT NULL,
  `BENEF_FILLE` int(11) NOT NULL,
  `BENEF_GARC` int(11) NOT NULL,
  `BENEF_DATECREAT` datetime DEFAULT NULL,
  PRIMARY KEY (`ID_BENEF`),
  KEY `BENEF_NBENEF_FK` (`CODE_NOMBENF`),
  KEY `BEN_PROV_FK` (`IDPROVINCE`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=703 ;

--
-- Contenu de la table `beneficiaire`
--

INSERT INTO `beneficiaire` (`ID_BENEF`, `CODE_NOMBENF`, `IDPROVINCE`, `CODE_BENEF`, `BENEF_NOM`, `BENEF_EBREVIATION`, `BENEF_TEL`, `BENEF_ADRESSE`, `BENEF_VILLE`, `BENEF_EMAIL`, `BENEF_DATEINT`, `BENEF_DIST`, `BENEF_EFF`, `BENEF_FILLE`, `BENEF_GARC`, `BENEF_DATECREAT`) VALUES
(3, 'ETB', 1, '', 'LycÃ©e Provincial de Boromo', '', '', '', '', '', '0000-00-00', 0, 1493, 0, 0, NULL),
(4, 'ETB', 1, '', 'LycÃ©e dÃ©partemental de Fara', '', '', '', '', '', '0000-00-00', 0, 815, 0, 0, NULL),
(5, 'ETB', 1, '', 'CEG de Poura Mine', '', '', '', '', '', '0000-00-00', 0, 693, 0, 0, NULL),
(7, 'ETB', 6, '', 'LycÃ©e muni de Tougan', '', '', '', '', '', '0000-00-00', 0, 407, 0, 0, NULL),
(8, 'ETB', 6, '', 'LycÃ©e DÃ©part. de Kiembara', '', '', '', '', '', '0000-00-00', 0, 762, 0, 0, NULL),
(10, 'ETB', 6, '', 'LycÃ©e Provincial de Tougan', '', '', '', '', '', '0000-00-00', 0, 2161, 0, 0, NULL),
(13, 'ETB', 5, '', 'CEG de Gossina', '', '', '', '', '', '0000-00-00', 0, 486, 0, 0, NULL),
(14, 'ETB', 5, '', 'CEG de Yaba', '', '', '', '', '', '0000-00-00', 0, 555, 0, 0, NULL),
(15, 'ETB', 5, '', 'CEG de Kougny', '', '', '', '', '', '0000-00-00', 0, 852, 0, 0, NULL),
(17, 'ETB', 5, '', 'Lyc muni de Toma', '', '', '', '', '', '0000-00-00', 0, 390, 0, 0, NULL),
(18, 'ETB', 5, '', 'CEG DE BIBA', '', '', '', '', '', '0000-00-00', 0, 501, 0, 0, NULL),
(19, 'ETB', 5, '', 'CEG de Bonou', '', '', '', '', '', '0000-00-00', 0, 178, 0, 0, NULL),
(20, 'ETB', 5, '', 'CEG de Kera', '', '', '', '', '', '0000-00-00', 0, 235, 0, 0, NULL),
(21, 'ETB', 9, '', 'LycÃ©e Philippe Zinda K.', '', '', '', '', '', '0000-00-00', 0, 4539, 0, 0, NULL),
(22, 'ETB', 9, '', 'LycÃ©e Nelson Mandela', '', '', '', '', '', '0000-00-00', 0, 2401, 0, 0, NULL),
(23, 'ETB', 9, '', 'LycÃ©e Song-Taaba', '', '', '', '', '', '0000-00-00', 0, 1786, 0, 0, NULL),
(24, 'ETB', 9, '', 'LycÃ©e Mixte DE GOUNGHIN', '', '', '', '', '', '0000-00-00', 0, 1722, 0, 0, NULL),
(25, 'ETB', 9, '', 'LycÃ©e de VÃ©nÃ©grÃ©', '', '', '', '', '', '0000-00-00', 0, 2126, 0, 0, NULL),
(26, 'ETB', 9, '', 'LycÃ©e Bambata', '', '', '', '', '', '0000-00-00', 0, 1807, 0, 0, NULL),
(27, 'ETB', 9, '', 'LycÃ©e Marien N''Gouabi', '', '', '', '', '', '0000-00-00', 0, 3010, 0, 0, NULL),
(28, 'ETB', 9, '', 'LycÃ©e de  Bogodogo', '', '', '', '', '', '0000-00-00', 0, 966, 0, 0, NULL),
(29, 'ETB', 9, '', 'LTO', '', '', '', '', '', '0000-00-00', 0, 2213, 0, 0, NULL),
(30, 'ETB', 9, '', 'LTAC', '', '', '', '', '', '0000-00-00', 0, 1043, 0, 0, NULL),
(31, 'ETB', 9, '', 'LycÃ©e professionnel rÃ©gional du Centre', '', '', '', '', '', '0000-00-00', 0, 889, 0, 0, NULL),
(32, 'ETB', 9, '', 'lYCEE PROFESSIONNEL Yennenga', '', '', '', '', '', '0000-00-00', 0, 232, 0, 0, NULL),
(33, 'ETB', 9, '', 'LP/BB', '', '', '', '', '', '0000-00-00', 0, 1203, 0, 0, NULL),
(34, 'ETB', 9, NULL, 'Coll N D de Kolgh-Naba', NULL, '', NULL, '', NULL, NULL, NULL, 0, 0, 0, NULL),
(35, 'ETB', 9, '', 'LycÃ©e Communal RimvougrÃ©', '', '', '', '', '', '0000-00-00', 0, 1495, 0, 0, NULL),
(36, 'ETB', 9, '', 'LycÃ©e Municipal de Sigh-Noghin', '', '', '', '', '', '0000-00-00', 0, 942, 0, 0, NULL),
(37, 'ETB', 9, '', 'Lycee Municipal de BLMG', '', '', '', '', '', '0000-00-00', 0, 597, 0, 0, NULL),
(38, 'ETB', 9, '', 'ETS Gabriel Taborin', '', '', '', '', '', '0000-00-00', 0, 0, 0, 0, NULL),
(39, 'ETB', 9, '', 'CETF', '', '', '', '', '', '0000-00-00', 0, 231, 0, 0, NULL),
(40, 'ETB', 9, '', 'CEG muni de Signoghin', '', '', '', '', '', '0000-00-00', 0, 596, 0, 0, NULL),
(41, 'ETB', 9, '', 'CEG de Bogodogo', '', '', '', '', '', '0000-00-00', 0, 532, 0, 0, NULL),
(42, 'ETB', 9, '', 'CEG de Paspanga', '', '', '', '', '', '0000-00-00', 0, 693, 0, 0, NULL),
(43, 'ETB', 9, '', 'LycÃ©e wendpuire de Saaba', '', '', '', '', '', '0000-00-00', 0, 1901, 0, 0, NULL),
(44, 'ETB', 9, '', 'LycÃ©e de Tanghin Das', '', '', '', '', '', '0000-00-00', 0, 1374, 0, 0, NULL),
(45, 'ETB', 9, '', 'LycÃ©e de Komsilga', '', '', '', '', '', '0000-00-00', 0, 781, 0, 0, NULL),
(46, 'ETB', 9, '', 'LycÃ©e de Koubri', '', '', '', '', '', '0000-00-00', 0, 805, 0, 0, NULL),
(47, 'ETB', 9, '', 'LycÃ©e de Konki-Ipala', '', '', '', '', '', '0000-00-00', 0, 766, 0, 0, NULL),
(48, 'ETB', 9, '', 'CEG MUNICIPAL DE PABRE', '', '', '', '', '', '0000-00-00', 0, 531, 0, 0, NULL),
(49, 'ETB', 36, '', 'LycÃ©e prov Dimdolbsom de BoussÃ©', '', '', '', '', '', '0000-00-00', 0, 2159, 0, 0, NULL),
(50, 'ETB', 36, '', 'LycÃ©e dÃ©p de ToÃ©ghin', '', '', '', '', '', '0000-00-00', 0, 443, 0, 0, NULL),
(51, 'ETB', 36, '', 'CEG de Sourgoubila', '', '', '', '', '', '0000-00-00', 0, 831, 0, 0, NULL),
(52, 'ETB', 36, '', 'LycÃ©e dÃ©p de Laye', '', '', '', '', '', '0000-00-00', 0, 809, 0, 0, NULL),
(53, 'ETB', 36, '', 'LycÃ©e dÃ©p de Niou', '', '', '', '', '', '0000-00-00', 0, 757, 0, 0, NULL),
(54, 'ETB', 36, '', 'CEG DE Bantodgo', '', '', '', '', '', '0000-00-00', 0, 417, 0, 0, NULL),
(55, 'ETB', 37, '', 'LycÃ©e provincial  Naba Bassy de ZiniarÃ©', '', '', '', '', '', '0000-00-00', 0, 2806, 0, 0, NULL),
(56, 'ETB', 37, '', 'CEG municipal de ZiniarÃ©', '', '', '', '', '', '0000-00-00', 0, 310, 0, 0, NULL),
(57, 'ETB', 37, '', 'CEG Naba Zida de Zitenga', '', '', '', '', '', '0000-00-00', 0, 784, 0, 0, NULL),
(58, 'ETB', 37, '', 'LycÃ©e  MUNICIPAL Naba Oubri  DE ZiniarÃ©', '', '', '', '', '', '0000-00-00', 0, 776, 0, 0, NULL),
(59, 'ETB', 37, '', 'CEG de DapÃ©logo', '', '', '', '', '', '0000-00-00', 0, 554, 0, 0, NULL),
(60, 'ETB', 37, '', 'CEG de Absouya', '', '', '', '', '', '0000-00-00', 0, 490, 0, 0, NULL),
(61, 'ETB', 37, '', 'LycÃ©e dÃ©part  de Loumbila', '', '', '', '', '', '0000-00-00', 0, 1187, 0, 0, NULL),
(62, 'ETB', 37, '', 'LycÃ©e 2nd cycle de ZiniarÃ©', '', '', '', '', '', '0000-00-00', 0, 313, 0, 0, NULL),
(63, 'ETB', 37, '', 'CEG de Ourgou-ManÃ©ga', '', '', '', '', '', '0000-00-00', 0, 572, 0, 0, NULL),
(64, 'ETB', 37, '', 'CEG MULTIL SPC DE LOUM', '', '', '', '', '', '0000-00-00', 0, 549, 0, 0, NULL),
(65, 'ETB', 37, '', 'LycÃ©e dÃ©p DE DONSIN', '', '', '', '', '', '0000-00-00', 0, 660, 0, 0, NULL),
(66, 'ETB', 37, '', 'CEG de NagrÃ©ongo', '', '', '', '', '', '0000-00-00', 0, 521, 0, 0, NULL),
(67, 'ETB', 35, '', 'LycÃ©e Provincial de Zorgho', '', '', '', '', '', '0000-00-00', 0, 1943, 0, 0, NULL),
(68, 'ETB', 35, '', 'LycÃ©e Communal N. Kouliga', '', '', '', '', '', '0000-00-00', 0, 1310, 0, 0, NULL),
(69, 'ETB', 35, '', 'LycÃ©e dÃ©p de Boudry', '', '', '', '', '', '0000-00-00', 0, 705, 0, 0, NULL),
(70, 'ETB', 35, '', 'LycÃ©e de MÃ©guet', '', '', '', '', '', '0000-00-00', 0, 866, 0, 0, NULL),
(71, 'ETB', 35, '', 'CEG de TuirÃ©', '', '', '', '', '', '0000-00-00', 0, 445, 0, 0, NULL),
(72, 'ETB', 35, '', 'LycÃ©e dÃ©p de MogtÃ©do', '', '', '', '', '', '0000-00-00', 0, 1245, 0, 0, NULL),
(73, 'ETB', 35, '', 'LycÃ©e dÃ©p de Zam', '', '', '', '', '', '0000-00-00', 0, 1044, 0, 0, NULL),
(74, 'ETB', 35, '', 'CEG de Zoungou', '', '', '', '', '', '0000-00-00', 0, 634, 0, 0, NULL),
(75, 'ETB', 35, '', 'CEG de Kogho', '', '', '', '', '', '0000-00-00', 0, 359, 0, 0, NULL),
(76, 'ETB', 35, '', 'CEG de Salogho', '', '', '', '', '', '0000-00-00', 0, 529, 0, 0, NULL),
(77, 'ETB', 35, '', 'CEG de Imiga', '', '', '', '', '', '0000-00-00', 0, 400, 0, 0, NULL),
(78, 'ETB', 35, '', 'CEG de NÃ©dogo', '', '', '', '', '', '0000-00-00', 0, 601, 0, 0, NULL),
(79, 'ETB', 16, '', 'LycÃ©e Provincial de Koudougou', '', '', '', '', '', '0000-00-00', 0, 2802, 0, 0, NULL),
(80, 'ETB', 16, '', 'LycÃ©e Municipal de Kdg', '', '', '', '', '', '0000-00-00', 0, 2207, 0, 0, NULL),
(81, 'ETB', 16, '', 'LycÃ©e DÃ©p de Sabou', '', '', '', '', '', '0000-00-00', 0, 957, 0, 0, NULL),
(82, 'ETB', 16, '', 'LycÃ©e DÃ©p de SiglÃ©', '', '', '', '', '', '0000-00-00', 0, 922, 0, 0, NULL),
(83, 'ETB', 16, '', 'LycÃ©e rÃ©gional d''enseignement professionnel de Bingo', '', '', '', '', '', '0000-00-00', 0, 223, 0, 0, NULL),
(84, 'ETB', 16, NULL, 'ESPAK Koudougou', NULL, '', NULL, '', NULL, NULL, NULL, 0, 0, 0, NULL),
(86, 'ETB', 16, NULL, 'Col Priv Techn de KDG', NULL, '', NULL, '', NULL, NULL, NULL, 0, 0, 0, NULL),
(87, 'ETB', 16, '', 'Coll  l''AmitiÃ© de KDG', '', '', '', '', '', '0000-00-00', 0, 737, 0, 0, NULL),
(88, 'ETB', 16, '', 'LycÃ©e prof Agr de Nanoro', '', '', '', '', '', '0000-00-00', 0, 0, 0, 0, NULL),
(89, 'ETB', 16, '', 'CollÃ¨ge Joseph Moukassa', '', '', '', '', '', '0000-00-00', 0, 0, 0, 0, NULL),
(91, 'ETB', 16, '', 'LycÃ©e D. de Kokologo', '', '', '', '', '', '0000-00-00', 0, 953, 0, 0, NULL),
(94, 'ETB', 16, '', 'CEG de Imasgo', '', '', '', '', '', '0000-00-00', 0, 708, 0, 0, NULL),
(95, 'ETB', 16, '', 'CEG de Saria', '', '', '', '', '', '0000-00-00', 0, 265, 0, 0, NULL),
(96, 'ETB', 16, '', 'LycÃ©e dÃ©p  de Soaw', '', '', '', '', '', '0000-00-00', 0, 766, 0, 0, NULL),
(97, 'ETB', 16, '', 'LycÃ©e dÃ©p de Thiou', '', '', '', '', '', '0000-00-00', 0, 683, 0, 0, NULL),
(98, 'ETB', 16, '', 'CEG de Ramongo', '', '', '', '', '', '0000-00-00', 0, 978, 0, 0, NULL),
(100, 'ETB', 16, '', 'LycÃ©e dÃ©p de Poa', '', '', '', '', '', '0000-00-00', 0, 480, 0, 0, NULL),
(101, 'ETB', 16, '', 'CEG de Villy', '', '', '', '', '', '0000-00-00', 0, 692, 0, 0, NULL),
(102, 'ETB', 16, '', 'CEG DE SOURGOU', '', '', '', '', '', '0000-00-00', 0, 411, 0, 0, NULL),
(106, 'ETB', 16, '', 'CEG de Tanghin', '', '', '', '', '', '0000-00-00', 0, 185, 0, 0, NULL),
(107, 'ETB', 16, '', 'CEG de GodÃ©', '', '', '', '', '', '0000-00-00', 0, 444, 0, 0, NULL),
(108, 'ETB', 17, '', 'LycÃ©e Provincial RÃ©o', '', '', '', '', '', '0000-00-00', 0, 1775, 0, 0, NULL),
(109, 'ETB', 17, '', 'LycÃ©e Communal de RÃ©o', '', '', '', '', '', '0000-00-00', 0, 736, 0, 0, NULL),
(110, 'ETB', 17, '', 'LycÃ©e de TÃ©nado', '', '', '', '', '', '0000-00-00', 0, 1164, 0, 0, NULL),
(111, 'ETB', 17, '', 'CEG de KordiÃ©', '', '', '', '', '', '0000-00-00', 0, 392, 0, 0, NULL),
(112, 'ETB', 17, '', 'LycÃ©e dÃ©p de Didyr', '', '', '', '', '', '0000-00-00', 0, 1344, 0, 0, NULL),
(113, 'ETB', 17, '', 'LycÃ© dÃ©p Pakiri Babou GUEL de de Pouni', '', '', '', '', '', '0000-00-00', 0, 800, 0, 0, NULL),
(114, 'ETB', 17, '', 'LycÃ©e dÃ©p Wendkouni de  de Tita', '', '', '', '', '', '0000-00-00', 0, 984, 0, 0, NULL),
(115, 'ETB', 17, '', 'LycÃ©e dÃ©p de Godyr', '', '', '', '', '', '0000-00-00', 0, 557, 0, 0, NULL),
(116, 'ETB', 17, '', 'CEG de Dassa', '', '', '', '', '', '0000-00-00', 0, 493, 0, 0, NULL),
(117, 'ETB', 17, '', 'CEG de Kyon', '', '', '', '', '', '0000-00-00', 0, 414, 0, 0, NULL),
(119, 'ETB', 17, '', 'CEG de Doudou', '', '', '', '', '', '0000-00-00', 0, 584, 0, 0, NULL),
(121, 'ETB', 18, '', 'LycÃ©e Provincial de LÃ©o', '', '', '', '', '', '0000-00-00', 0, 1216, 0, 0, NULL),
(122, 'ETB', 18, '', 'LycÃ©e Municipal', '', '', '', '', '', '0000-00-00', 0, 883, 0, 0, NULL),
(123, 'ETB', 18, '', 'CEG de To', '', '', '', '', '', '0000-00-00', 0, 840, 0, 0, NULL),
(124, 'ETB', 18, '', 'CEG de BiÃ©ha', '', '', '', '', '', '0000-00-00', 0, 513, 0, 0, NULL),
(125, 'ETB', 18, '', 'CEG de Silly', '', '', '', '', '', '0000-00-00', 0, 775, 0, 0, NULL),
(126, 'ETB', 18, '', 'CEG DE BOURA', '', '', '', '', '', '0000-00-00', 0, 406, 0, 0, NULL),
(127, 'ETB', 18, '', 'CEG de Niabouri', '', '', '', '', '', '0000-00-00', 0, 338, 0, 0, NULL),
(128, 'ETB', 18, '', 'CEG de NiebÃ©lanayou', '', '', '', '', '', '0000-00-00', 0, 386, 0, 0, NULL),
(129, 'ETB', 19, '', 'LycÃ©e dÃ©p de Kassou', '', '', '', '', '', '0000-00-00', 0, 1100, 0, 0, NULL),
(130, 'ETB', 19, '', 'CEG de Dalo', '', '', '', '', '', '0000-00-00', 0, 252, 0, 0, NULL),
(131, 'ETB', 19, '', 'CEG de Gao', '', '', '', '', '', '0000-00-00', 0, 374, 0, 0, NULL),
(132, 'ETB', 19, '', 'CEG   de Sapouy', '', '', '', '', '', '0000-00-00', 0, 744, 0, 0, NULL),
(133, 'ETB', 22, '', 'LycÃ©e Provincial Naba Baongo de Manga', '', '', '', '', '', '0000-00-00', 0, 1743, 0, 0, NULL),
(137, 'ETB', 22, '', 'LycÃ©e dÃ©p de Guiba', '', '', '', '', '', '0000-00-00', 0, 683, 0, 0, NULL),
(138, 'ETB', 22, '', 'LycÃ©e dÃ©p de NobÃ©rÃ©', '', '', '', '', '', '0000-00-00', 0, 617, 0, 0, NULL),
(139, 'ETB', 22, '', 'Lyc municipal. de Manga', '', '', '', '', '', '0000-00-00', 0, 865, 0, 0, NULL),
(144, 'ETB', 22, '', 'CEG de Kaibo', '', '', '', '', '', '0000-00-00', 0, 577, 0, 0, NULL),
(145, 'ETB', 21, '', 'LycÃ©e Provincial de po', '', '', '', '', '', '0000-00-00', 0, 1241, 0, 0, NULL),
(146, 'ETB', 21, '', 'LycÃ©e dÃ©p de TiÃ©bÃ©lÃ©', '', '', '', '', '', '0000-00-00', 0, 836, 0, 0, NULL),
(147, 'ETB', 21, '', 'LycÃ©e dÃ©p DE ZECCO', '', '', '', '', '', '0000-00-00', 0, 801, 0, 0, NULL),
(148, 'ETB', 21, '', 'LycÃ©e D. de Ziou', '', '', '', '', '', '0000-00-00', 0, 801, 0, 0, NULL),
(149, 'ETB', 21, '', 'LycÃ©e municipall de Po', '', '', '', '', '', '0000-00-00', 0, 980, 0, 0, NULL),
(150, 'ETB', 21, '', 'CEG DE Guiaro', '', '', '', '', '', '0000-00-00', 0, 321, 0, 0, NULL),
(151, 'ETB', 21, '', 'CEG de Kaya Navio', '', '', '', '', '', '0000-00-00', 0, 381, 0, 0, NULL),
(152, 'ETB', 20, '', 'LycÃ©e Provincial DE kombissiri', '', '', '', '', '', '0000-00-00', 0, 1260, 0, 0, NULL),
(153, 'ETB', 20, '', 'LycÃ©e Municipal de Kombissiri', '', '', '', '', '', '0000-00-00', 0, 830, 0, 0, NULL),
(154, 'ETB', 20, '', 'LycÃ©e de SaponÃ©', '', '', '', '', '', '0000-00-00', 0, 1417, 0, 0, NULL),
(155, 'ETB', 20, '', 'LycÃ©e dÃ©p  de Kayao', '', '', '', '', '', '0000-00-00', 0, 816, 0, 0, NULL),
(156, 'ETB', 20, '', 'LycÃ©e dÃ©p.de Doulougou', '', '', '', '', '', '0000-00-00', 0, 806, 0, 0, NULL),
(157, 'ETB', 20, '', 'LycÃ©e dÃ©p  de ToÃ©cÃ©', '', '', '', '', '', '0000-00-00', 0, 699, 0, 0, NULL),
(158, 'ETB', 20, '', 'CEG de Gaongo', '', '', '', '', '', '0000-00-00', 0, 281, 0, 0, NULL),
(159, 'ETB', 20, '', 'CEG de Tuili', '', '', '', '', '', '0000-00-00', 0, 445, 0, 0, NULL),
(160, 'ETB', 15, '', 'LycÃ©e Provincial de Kaya', '', '', '', '', '', '0000-00-00', 0, 2653, 0, 0, NULL),
(161, 'ETB', 15, '', 'LycÃ©e munic de Kaya', '', '', '', '', '', '0000-00-00', 0, 2282, 0, 0, NULL),
(162, 'ETB', 15, '', 'LycÃ©e de Boussouma', '', '', '', '', '', '0000-00-00', 0, 1112, 0, 0, NULL),
(163, 'ETB', 15, '', 'LycÃ©e de Korsimoro', '', '', '', '', '', '0000-00-00', 0, 2345, 0, 0, NULL),
(164, 'ETB', 15, '', 'LycÃ©e dÃ©p de ManÃ©', '', '', '', '', '', '0000-00-00', 0, 565, 0, 0, NULL),
(165, 'ETB', 15, '', 'LycÃ©e dÃ©p  de Barsalogo', '', '', '', '', '', '0000-00-00', 0, 1233, 0, 0, NULL),
(166, 'ETB', 15, '', 'LycÃ©e dÃ©p  de Pissila', '', '', '', '', '', '0000-00-00', 0, 862, 0, 0, NULL),
(167, 'ETB', 15, '', 'CEG de Dablo', '', '', '', '', '', '0000-00-00', 0, 339, 0, 0, NULL),
(168, 'ETB', 15, '', 'CEG de PENSA', '', '', '', '', '', '0000-00-00', 0, 419, 0, 0, NULL),
(169, 'ETB', 15, '', 'CEG de Ziga', '', '', '', '', '', '0000-00-00', 0, 279, 0, 0, NULL),
(170, 'ETB', 15, '', 'CEG DE PibaorÃ©', '', '', '', '', '', '0000-00-00', 0, 361, 0, 0, NULL),
(172, 'ETB', 15, '', 'CEG de Soubeira', '', '', '', '', '', '0000-00-00', 0, 385, 0, 0, NULL),
(173, 'ETB', 15, '', 'CEG de FoubÃ©', '', '', '', '', '', '0000-00-00', 0, 254, 0, 0, NULL),
(174, 'ETB', 15, '', 'CEG muni de Pissila', '', '', '', '', '', '0000-00-00', 0, 289, 0, 0, NULL),
(175, 'ETB', 15, '', 'CEG de Imiougou', '', '', '', '', '', '0000-00-00', 0, 239, 0, 0, NULL),
(176, 'ETB', 15, '', 'CEG de Kaya', '', '', '', '', '', '0000-00-00', 0, 286, 0, 0, NULL),
(177, 'ETB', 14, '', 'LycÃ©e Provincial  de Boulsa', '', '', '', '', '', '0000-00-00', 0, 1732, 0, 0, NULL),
(178, 'ETB', 14, '', 'LycÃ©e dep Dargo', '', '', '', '', '', '0000-00-00', 0, 759, 0, 0, NULL),
(179, 'ETB', 14, '', 'LycÃ©e dÃ©p de Tougouri', '', '', '', '', '', '0000-00-00', 0, 961, 0, 0, NULL),
(180, 'ETB', 14, '', 'ceg de Boala', '', '', '', '', '', '0000-00-00', 0, 308, 0, 0, NULL),
(182, 'ETB', 14, '', 'CEG de ZÃ©guÃ©dÃ©guin', '', '', '', '', '', '0000-00-00', 0, 585, 0, 0, NULL),
(183, 'ETB', 14, '', 'CEG de Yalgo', '', '', '', '', '', '0000-00-00', 0, 551, 0, 0, NULL),
(184, 'ETB', 14, '', 'CET DE BOULSA', '', '', '', '', '', '0000-00-00', 0, 214, 0, 0, NULL),
(185, 'ETB', 14, '', 'CEG de Nagbingou', '', '', '', '', '', '0000-00-00', 0, 193, 0, 0, NULL),
(186, 'ETB', 13, '', 'LycÃ©e Provincial de kongoussi', '', '', '', '', '', '0000-00-00', 0, 1768, 0, 0, NULL),
(187, 'ETB', 13, '', 'LycÃ©e municipal de Kongoussi', '', '', '', '', '', '0000-00-00', 0, 1354, 0, 0, NULL),
(188, 'ETB', 13, '', 'LycÃ©e dÃ©p de TikarÃ©', '', '', '', '', '', '0000-00-00', 0, 915, 0, 0, NULL),
(189, 'ETB', 13, '', 'CEG de Rollo', '', '', '', '', '', '0000-00-00', 0, 417, 0, 0, NULL),
(190, 'ETB', 13, '', 'CEG de Bourzanga', '', '', '', '', '', '0000-00-00', 0, 373, 0, 0, NULL),
(191, 'ETB', 13, '', 'CEG de SabcÃ©', '', '', '', '', '', '0000-00-00', 0, 799, 0, 0, NULL),
(192, 'ETB', 13, '', 'CEG de Rouko', '', '', '', '', '', '0000-00-00', 0, 424, 0, 0, NULL),
(193, 'ETB', 13, '', 'CEG DE GUIBARE', '', '', '', '', '', '0000-00-00', 0, 349, 0, 0, NULL),
(194, 'ETB', 13, '', 'CEG de Loagha', '', '', '', '', '', '0000-00-00', 0, 340, 0, 0, NULL),
(195, 'ETB', 38, '', 'LycÃ©e Provincial de Gorom-gorom', '', '', '', '', '', '0000-00-00', 0, 866, 0, 0, NULL),
(196, 'ETB', 38, '', 'CEG de Gorom-Gorom', '', '', '', '', '', '0000-00-00', 0, 173, 0, 0, NULL),
(197, 'ETB', 38, '', 'CEG de Markoye', '', '', '', '', '', '0000-00-00', 0, 408, 0, 0, NULL),
(199, 'ETB', 38, '', 'CEG de DÃ©ou', '', '', '', '', '', '0000-00-00', 0, 172, 0, 0, NULL),
(200, 'ETB', 38, '', 'CEG DE OURSY', '', '', '', '', '', '0000-00-00', 0, 167, 0, 0, NULL),
(201, 'ETB', 38, '', 'CEG de Korzina', '', '', '', '', '', '0000-00-00', 0, 201, 0, 0, NULL),
(202, 'ETB', 39, '', 'LycÃ©e Provincial DE dORI', '', '', '', '', '', '0000-00-00', 0, 1492, 0, 0, NULL),
(204, 'ETB', 39, '', 'CEG de Bani', '', '', '', '', '', '0000-00-00', 0, 364, 0, 0, NULL),
(205, 'ETB', 39, '', 'CEG de Seytenga', '', '', '', '', '', '0000-00-00', 0, 173, 0, 0, NULL),
(207, 'ETB', 39, '', 'LycÃ©e Municipal de Dori', '', '', '', '', '', '0000-00-00', 0, 394, 0, 0, NULL),
(208, 'ETB', 39, '', 'CEG DE SampÃ©lga', '', '', '', '', '', '0000-00-00', 0, 195, 0, 0, NULL),
(209, 'ETB', 41, '', 'LycÃ©e Provincial de Sebba', '', '', '', '', '', '0000-00-00', 0, 474, 0, 0, NULL),
(210, 'ETB', 41, '', 'CEG DE SOLHAN', '', '', '', '', '', '0000-00-00', 0, 324, 0, 0, NULL),
(211, 'ETB', 41, '', 'CEG DE BOUNDOURE', '', '', '', '', '', '0000-00-00', 0, 176, 0, 0, NULL),
(212, 'ETB', 41, '', 'CEG DE TankougnadiÃ©', '', '', '', '', '', '0000-00-00', 0, 75, 0, 0, NULL),
(213, 'ETB', 40, '', 'LycÃ©e dÃ©p de Arbinda', '', '', '', '', '', '0000-00-00', 0, 501, 0, 0, NULL),
(214, 'ETB', 40, '', 'CEG DE KOUTOUGOU', '', '', '', '', '', '0000-00-00', 0, 174, 0, 0, NULL),
(215, 'ETB', 33, '', 'LycÃ©e Yamwaya', '', '', '', '', '', '0000-00-00', 0, 2457, 0, 0, NULL),
(216, 'ETB', 33, '', 'LycÃ©e YadÃ©ga', '', '', '', '', '', '0000-00-00', 0, 1955, 0, 0, NULL),
(217, 'ETB', 33, '', 'LycÃ©e Municipal DE OHG', '', '', '', '', '', '0000-00-00', 0, 1593, 0, 0, NULL),
(218, 'ETB', 33, '', 'LycÃ©e dÃ©p de SÃ©guÃ©nÃ©ga', '', '', '', '', '', '0000-00-00', 0, 909, 0, 0, NULL),
(219, 'ETB', 33, '', 'LycÃ©e muni de SÃ©guÃ©nÃ©ga', '', '', '', '', '', '0000-00-00', 0, 393, 0, 0, NULL),
(220, 'ETB', 33, '', 'LycÃ©e Charles Foyer de OHG', '', '', '', '', '', '0000-00-00', 0, 0, 0, 0, NULL),
(221, 'ETB', 33, '', 'CollÃ¨ge Sainte MARIE', '', '', '', '', '', '0000-00-00', 0, 0, 0, 0, NULL),
(222, 'ETB', 33, '', 'LycÃ©e Professionnel de Ouahigouya', '', '', '', '', '', '0000-00-00', 0, 234, 0, 0, NULL),
(223, 'ETB', 33, '', 'CEG de Tangaye', '', '', '', '', '', '0000-00-00', 0, 364, 0, 0, NULL),
(224, 'ETB', 33, '', 'LycÃ©e Muni deTangaye', '', '', '', '', '', '0000-00-00', 0, 364, 0, 0, NULL),
(225, 'ETB', 33, '', 'LycÃ©e dÃ©p   de Kalsaka', '', '', '', '', '', '0000-00-00', 0, 767, 0, 0, NULL),
(226, 'ETB', 33, '', 'CEG  de Rambo', '', '', '', '', '', '0000-00-00', 0, 571, 0, 0, NULL),
(227, 'ETB', 33, '', 'CEG   de Thiou', '', '', '', '', '', '0000-00-00', 0, 503, 0, 0, NULL),
(228, 'ETB', 33, '', 'CEG  de Pourra', '', '', '', '', '', '0000-00-00', 0, 403, 0, 0, NULL),
(229, 'ETB', 33, '', 'LycÃ©e dÃ©p   de Koumbri', '', '', '', '', '', '0000-00-00', 0, 781, 0, 0, NULL),
(230, 'ETB', 33, '', 'CEG   de Namissiguima', '', '', '', '', '', '0000-00-00', 0, 389, 0, 0, NULL),
(231, 'ETB', 33, '', 'CEG   de Kossouka', '', '', '', '', '', '0000-00-00', 0, 608, 0, 0, NULL),
(232, 'ETB', 33, '', 'LycÃ©e dÃ©p   de ZogorÃ©', '', '', '', '', '', '0000-00-00', 0, 492, 0, 0, NULL),
(233, 'ETB', 33, '', 'CEG   de Oula', '', '', '', '', '', '0000-00-00', 0, 700, 0, 0, NULL),
(234, 'ETB', 33, '', 'CEG DE BOGOYA', '', '', '', '', '', '0000-00-00', 0, 291, 0, 0, NULL),
(235, 'ETB', 33, '', 'LYCEE MUN DE THIOU', '', '', '', '', '', '0000-00-00', 0, 503, 0, 0, NULL),
(236, 'ETB', 33, '', 'CEG DE BARGA', '', '', '', '', '', '0000-00-00', 0, 431, 0, 0, NULL),
(238, 'ETB', 33, '', 'CEG de Kain', '', '', '', '', '', '0000-00-00', 0, 161, 0, 0, NULL),
(239, 'ETB', 33, '', 'CEG de Bema-SilmossÃ©', '', '', '', '', '', '0000-00-00', 0, 183, 0, 0, NULL),
(241, 'ETB', 31, '', 'LycÃ©e Provincial du Loroum', '', '', '', '', '', '0000-00-00', 0, 1262, 0, 0, NULL),
(242, 'ETB', 31, '', 'CEG de Ouindigui', '', '', '', '', '', '0000-00-00', 0, 560, 0, 0, NULL),
(243, 'ETB', 31, '', 'CEG de Banh', '', '', '', '', '', '0000-00-00', 0, 614, 0, 0, NULL),
(244, 'ETB', 31, '', 'LycÃ©e  professionnel Municipal de Titao', '', '', '', '', '', '0000-00-00', 0, 505, 0, 0, NULL),
(245, 'ETB', 34, '', 'LycÃ©e Provincial du Zandoma', '', '', '', '', '', '0000-00-00', 0, 1395, 0, 0, NULL),
(246, 'ETB', 34, '', 'CEG de Tougo', '', '', '', '', '', '0000-00-00', 0, 425, 0, 0, NULL),
(247, 'ETB', 34, '', 'LycÃ©e dÃ©p de Boussou', '', '', '', '', '', '0000-00-00', 0, 571, 0, 0, NULL),
(248, 'ETB', 34, '', 'CEG DE BASSI', '', '', '', '', '', '0000-00-00', 0, 314, 0, 0, NULL),
(249, 'ETB', 34, '', 'CEG DE LEBA', '', '', '', '', '', '0000-00-00', 0, 460, 0, 0, NULL),
(250, 'ETB', 34, '', 'CEG de NiessÃ©ga', '', '', '', '', '', '0000-00-00', 0, 248, 0, 0, NULL),
(251, 'ETB', 32, '', 'LycÃ©e Provincial DE Yako', '', '', '', '', '', '0000-00-00', 0, 1434, 0, 0, NULL),
(252, 'ETB', 32, '', 'LycÃ©e Municipal Toussaint Louverture de Yako', '', '', '', '', '', '0000-00-00', 0, 1221, 0, 0, NULL),
(253, 'ETB', 32, '', 'LycÃ©e dÃ©p de Samba', '', '', '', '', '', '0000-00-00', 0, 898, 0, 0, NULL),
(254, 'ETB', 32, '', 'LycÃ©e dÃ©p de ArbollÃ©', '', '', '', '', '', '0000-00-00', 0, 1097, 0, 0, NULL),
(255, 'ETB', 32, '', 'LycÃ©e dÃ©p  de Bokin', '', '', '', '', '', '0000-00-00', 0, 969, 0, 0, NULL),
(256, 'ETB', 32, '', 'CEG de BagarÃ©', '', '', '', '', '', '0000-00-00', 0, 640, 0, 0, NULL),
(257, 'ETB', 32, '', 'CEG DE Pilimpikou', '', '', '', '', '', '0000-00-00', 0, 395, 0, 0, NULL),
(259, 'ETB', 32, '', 'LycÃ©e dÃ©p de La-Todin', '', '', '', '', '', '0000-00-00', 0, 1241, 0, 0, NULL),
(260, 'ETB', 32, '', 'CEG de Kirsi', '', '', '', '', '', '0000-00-00', 0, 564, 0, 0, NULL),
(261, 'ETB', 32, '', 'LycÃ©e dÃ©p DE Gomponsom', '', '', '', '', '', '0000-00-00', 0, 642, 0, 0, NULL),
(262, 'ETB', 32, '', 'CEG de Tindila', '', '', '', '', '', '0000-00-00', 0, 403, 0, 0, NULL),
(263, 'ETB', 40, '', 'LycÃ©e Provincial', '', '', '', '', '', '0000-00-00', 0, 1087, 0, 0, NULL),
(264, 'ETB', 40, '', 'CEG de Djibo', '', '', '', '', '', '0000-00-00', 0, 233, 0, 0, NULL),
(265, 'ETB', 40, '', 'CEG DE TOGOMAYEL', '', '', '', '', '', '0000-00-00', 0, 479, 0, 0, NULL),
(266, 'ETB', 40, '', 'LYCEE MUNICIPAL', '', '', '', '', '', '0000-00-00', 0, 420, 0, 0, NULL),
(267, 'ETB', 40, '', 'LycÃ©e dÃ©p de PobÃ©-Mengao', '', '', '', '', '', '0000-00-00', 0, 831, 0, 0, NULL),
(268, 'ETB', 40, '', 'CEG de BaraboulÃ©', '', '', '', '', '', '0000-00-00', 0, 364, 0, 0, NULL),
(269, 'ETB', 40, '', 'CEG de Kelbo', '', '', '', '', '', '0000-00-00', 0, 403, 0, 0, NULL),
(270, 'ETB', 10, '', 'LycÃ©e RialÃ©', '', '', '', '', '', '0000-00-00', 0, 1677, 0, 0, NULL),
(271, 'ETB', 10, '', 'LycÃ©e Municipal DE TENKODOGO', '', '', '', '', '', '0000-00-00', 0, 1559, 0, 0, NULL),
(272, 'ETB', 10, '', 'LycÃ©e Municipal de Garango', '', '', '', '', '', '0000-00-00', 0, 594, 0, 0, NULL),
(273, 'ETB', 10, '', 'CETFP de Garango', '', '', '', '', '', '0000-00-00', 0, 201, 0, 0, NULL),
(274, 'ETB', 10, '', 'collÃ¨ge Marie Reine', '', '', '', '', '', '0000-00-00', 0, 0, 0, 0, NULL),
(275, 'ETB', 10, '', 'LycÃ©e DÃ©part de Garango', '', '', '', '', '', '0000-00-00', 0, 871, 0, 0, NULL),
(276, 'ETB', 10, '', 'LycÃ©e dÃ©p de Bittou', '', '', '', '', '', '0000-00-00', 0, 1069, 0, 0, NULL),
(277, 'ETB', 10, '', 'LycÃ©e dÃ©p  de BÃ©guÃ©do', '', '', '', '', '', '0000-00-00', 0, 612, 0, 0, NULL),
(278, 'ETB', 10, '', 'CEG de Boussouma', '', '', '', '', '', '0000-00-00', 0, 1020, 0, 0, NULL),
(279, 'ETB', 10, '', 'LycÃ©e DÃ©p de ZabrÃ©', '', '', '', '', '', '0000-00-00', 0, 1156, 0, 0, NULL),
(280, 'ETB', 10, '', 'LycÃ©e Dpt de BagrÃ©', '', '', '', '', '', '0000-00-00', 0, 435, 0, 0, NULL),
(282, 'ETB', 10, '', 'CEG de KomtoÃ©ga', '', '', '', '', '', '0000-00-00', 0, 555, 0, 0, NULL),
(283, 'ETB', 10, '', 'CEG de Niagho', '', '', '', '', '', '0000-00-00', 0, 457, 0, 0, NULL),
(284, 'ETB', 10, '', 'CEG de BagrÃ©', '', '', '', '', '', '0000-00-00', 0, 419, 0, 0, NULL),
(285, 'ETB', 10, '', 'CEG de BanÃ©', '', '', '', '', '', '0000-00-00', 0, 429, 0, 0, NULL),
(286, 'ETB', 12, '', 'LycÃ©e provincial Kourita', '', '', '', '', '', '0000-00-00', 0, 1849, 0, 0, NULL),
(287, 'ETB', 12, '', 'LycÃ©e municipal DE KoupÃ©la', '', '', '', '', '', '0000-00-00', 0, 1227, 0, 0, NULL),
(288, 'ETB', 12, '', 'LycÃ©e dÃ©p de pouytenga', '', '', '', '', '', '0000-00-00', 0, 1327, 0, 0, NULL),
(289, 'ETB', 12, '', 'LycÃ©e dÃ©p de Gounghin', '', '', '', '', '', '0000-00-00', 0, 967, 0, 0, NULL),
(290, 'ETB', 12, '', 'LycÃ©e dÃ©p de Andemtenga', '', '', '', '', '', '0000-00-00', 0, 1404, 0, 0, NULL),
(291, 'ETB', 12, '', 'CEG de Tensobtenga', '', '', '', '', '', '0000-00-00', 0, 609, 0, 0, NULL),
(292, 'ETB', 12, '', 'LycÃ©e munipal de Pouytenga', '', '', '', '', '', '0000-00-00', 0, 1028, 0, 0, NULL),
(293, 'ETB', 12, '', 'CEG DE BaskourÃ©', '', '', '', '', '', '0000-00-00', 0, 546, 0, 0, NULL),
(294, 'ETB', 12, '', 'CEG DE Yargo', '', '', '', '', '', '0000-00-00', 0, 505, 0, 0, NULL),
(295, 'ETB', 12, '', 'CEG DE Dialgaye', '', '', '', '', '', '0000-00-00', 0, 605, 0, 0, NULL),
(296, 'ETB', 11, '', 'LycÃ©e Provincial de Ouargaye', '', '', '', '', '', '0000-00-00', 0, 791, 0, 0, NULL),
(297, 'ETB', 11, '', 'CEG de Ouargaye', '', '', '', '', '', '0000-00-00', 0, 312, 0, 0, NULL),
(298, 'ETB', 11, '', 'LycÃ©e dÃ©p DE Dourtenga', '', '', '', '', '', '0000-00-00', 0, 662, 0, 0, NULL),
(299, 'ETB', 11, '', 'CEG DE Comin-Yanga', '', '', '', '', '', '0000-00-00', 0, 568, 0, 0, NULL),
(300, 'ETB', 11, '', 'CEG de YondÃ©', '', '', '', '', '', '0000-00-00', 0, 518, 0, 0, NULL),
(301, 'ETB', 11, '', 'CEG de Lalgaye', '', '', '', '', '', '0000-00-00', 0, 482, 0, 0, NULL),
(302, 'ETB', 11, '', 'CEG de Yargatenga', '', '', '', '', '', '0000-00-00', 0, 442, 0, 0, NULL),
(306, 'ETB', 23, '', 'LycÃ©e provincial BogandÃ©', '', '', '', '', '', '0000-00-00', 0, 977, 0, 0, NULL),
(308, 'ETB', 23, '', 'LycÃ©e dÃ©p de piÃ©la', '', '', '', '', '', '0000-00-00', 0, 970, 0, 0, NULL),
(310, 'ETB', 23, '', 'LycÃ©e DÃ©part  de Mani', '', '', '', '', '', '0000-00-00', 0, 845, 0, 0, NULL),
(312, 'ETB', 23, '', 'CEG de Thion', '', '', '', '', '', '0000-00-00', 0, 362, 0, 0, NULL),
(314, 'ETB', 24, '', 'LycÃ©e Diaba Lompo', '', '', '', '', '', '0000-00-00', 0, 2415, 0, 0, NULL),
(315, 'ETB', 24, '', 'LycÃ©e professionnel de Fada', '', '', '', '', '', '0000-00-00', 0, 267, 0, 0, NULL),
(316, 'ETB', 24, '', 'LycÃ©e dÃ©p de Diabo', '', '', '', '', '', '0000-00-00', 0, 888, 0, 0, NULL),
(317, 'ETB', 24, '', 'LycÃ©e dÃ©p de Diapangou', '', '', '', '', '', '0000-00-00', 0, 617, 0, 0, NULL),
(318, 'ETB', 24, '', 'CEG de Yamba', '', '', '', '', '', '0000-00-00', 0, 304, 0, 0, NULL),
(319, 'ETB', 24, '', 'LycÃ©e dÃ©p de Tibga', '', '', '', '', '', '0000-00-00', 0, 643, 0, 0, NULL),
(320, 'ETB', 24, '', 'LycÃ©e dÃ©p de Matiacoali', '', '', '', '', '', '0000-00-00', 0, 923, 0, 0, NULL),
(321, 'ETB', 24, '', 'CEG de Namoungou', '', '', '', '', '', '0000-00-00', 0, 390, 0, 0, NULL),
(322, 'ETB', 25, '', 'LycÃ©e provincial GayÃ©ri', '', '', '', '', '', '0000-00-00', 0, 1111, 0, 0, NULL),
(323, 'ETB', 25, '', 'CEG municipal  de GayÃ©ri', '', '', '', '', '', '0000-00-00', 0, 328, 0, 0, NULL),
(324, 'ETB', 25, '', 'CEG de Foutouri', '', '', '', '', '', '0000-00-00', 0, 64, 0, 0, NULL),
(327, 'ETB', 27, '', 'lycÃ©e Prov Untani de Diapaga', '', '', '', '', '', '0000-00-00', 0, 1081, 0, 0, NULL),
(328, 'ETB', 27, '', 'CEG de  Logobou', '', '', '', '', '', '0000-00-00', 0, 493, 0, 0, NULL),
(329, 'ETB', 27, '', 'lycÃ©e dÃ©p de Tambaga', '', '', '', '', '', '0000-00-00', 0, 936, 0, 0, NULL),
(330, 'ETB', 27, '', 'CEG DE Partiaga', '', '', '', '', '', '0000-00-00', 0, 418, 0, 0, NULL),
(331, 'ETB', 27, '', 'LycÃ©e dÃ©p de kantchari', '', '', '', '', '', '0000-00-00', 0, 925, 0, 0, NULL),
(332, 'ETB', 27, '', 'CEG DE Namounou', '', '', '', '', '', '0000-00-00', 0, 467, 0, 0, NULL),
(334, 'ETB', 27, '', 'CEG DE Tansarga', '', '', '', '', '', '0000-00-00', 0, 810, 0, 0, NULL),
(335, 'ETB', 27, '', 'CEG DE Bottou', '', '', '', '', '', '0000-00-00', 0, 352, 0, 0, NULL),
(336, 'ETB', 27, '', 'CEG de Mahadaga', '', '', '', '', '', '0000-00-00', 0, 505, 0, 0, NULL),
(337, 'ETB', 27, '', 'CEG de Pentenga', '', '', '', '', '', '0000-00-00', 0, 364, 0, 0, NULL),
(339, 'ETB', 1, '', 'CEG de Bagassi', '', '', '', '', '', '0000-00-00', 0, 345, 0, 0, NULL),
(340, 'ETB', 1, '', 'CEG    de Pa', '', '', '', '', '', '0000-00-00', 0, 475, 0, 0, NULL),
(341, 'ETB', 1, '', 'LycÃ©e dÃ©p. de Siby', '', '', '', '', '', '0000-00-00', 0, 1135, 0, 0, NULL),
(342, 'ETB', 1, '', 'CEG de Oury', '', '', '', '', '', '0000-00-00', 0, 692, 0, 0, NULL),
(343, 'ETB', 1, '', 'CEG de PompoÃ©', '', '', '', '', '', '0000-00-00', 0, 373, 0, 0, NULL),
(350, 'ETB', 3, '', 'LycÃ©e Provincial DE NOUNA', '', '', '', '', '', '0000-00-00', 0, 1741, 0, 0, NULL),
(351, 'ETB', 3, NULL, 'CFP de Nouna', NULL, '', NULL, '', NULL, NULL, NULL, 0, 0, 0, NULL),
(352, 'ETB', 3, '', 'CEG de Doumbala', '', '', '', '', '', '0000-00-00', 0, 403, 0, 0, NULL),
(353, 'ETB', 3, '', 'CEG    de Bomborokuy', '', '', '', '', '', '0000-00-00', 0, 449, 0, 0, NULL),
(354, 'ETB', 3, '', 'CollÃ¨ge Charles Lwanga', '', '', '', '', '', '0000-00-00', 0, 0, 0, 0, NULL),
(355, 'ETB', 3, '', 'CEG de Bourasso', '', '', '', '', '', '0000-00-00', 0, 384, 0, 0, NULL),
(356, 'ETB', 3, '', 'CEG DE Barani', '', '', '', '', '', '0000-00-00', 0, 467, 0, 0, NULL),
(357, 'ETB', 3, '', 'CEG de Madouba', '', '', '', '', '', '0000-00-00', 0, 208, 0, 0, NULL),
(358, 'ETB', 6, '', 'LycÃ©e dÃ©p. de Kassoum', '', '', '', '', '', '0000-00-00', 0, 819, 0, 0, NULL),
(359, 'ETB', 6, '', 'CEG de Gomboro', '', '', '', '', '', '0000-00-00', 0, 410, 0, 0, NULL),
(360, 'ETB', 6, '', 'CEG DE TOENI', '', '', '', '', '', '0000-00-00', 0, 606, 0, 0, NULL),
(361, 'ETB', 6, '', 'CEG DE LANKOUE', '', '', '', '', '', '0000-00-00', 0, 364, 0, 0, NULL),
(363, 'ETB', 6, '', 'CEG DE LANFIERA', '', '', '', '', '', '0000-00-00', 0, 619, 0, 0, NULL),
(364, 'ETB', 4, '', 'LycÃ©e Provincial DE DDG', '', '', '', '', '', '0000-00-00', 0, 1806, 0, 0, NULL),
(365, 'ETB', 4, '', 'LycÃ©e   Municipal DÃ©dougou', '', '', '', '', '', '0000-00-00', 0, 2029, 0, 0, NULL),
(366, 'ETB', 4, '', 'LycÃ©e dÃ©p de Bondokuy', '', '', '', '', '', '0000-00-00', 0, 644, 0, 0, NULL),
(367, 'ETB', 4, '', 'CEG    de Ouarkoye', '', '', '', '', '', '0000-00-00', 0, 1112, 0, 0, NULL),
(368, 'ETB', 4, '', 'LycÃ©e  dÃ©p de SafanÃ©', '', '', '', '', '', '0000-00-00', 0, 718, 0, 0, NULL),
(373, 'ETB', 4, '', 'CEG de Douroula', '', '', '', '', '', '0000-00-00', 0, 318, 0, 0, NULL),
(374, 'ETB', 4, '', 'CEG de Kona', '', '', '', '', '', '0000-00-00', 0, 328, 0, 0, NULL),
(376, 'ETB', 28, '', 'LycÃ©e Ouezzin Coulibaly', '', '', '', '', '', '0000-00-00', 0, 5131, 0, 0, NULL),
(377, 'ETB', 28, '', 'LycÃ©e Mollo Sanou', '', '', '', '', '', '0000-00-00', 0, 2520, 0, 0, NULL),
(378, 'ETB', 28, '', 'LycÃ©e Municipal Vinama ThiÃ©mounou Djibril  de Bobo', '', '', '', '', '', '0000-00-00', 0, 2905, 0, 0, NULL),
(379, 'ETB', 28, '', 'LycÃ©e Mixte d''Accart Ville', '', '', '', '', '', '0000-00-00', 0, 1533, 0, 0, NULL),
(380, 'ETB', 28, '', 'LycÃ©e Professionnel rÃ©gional Guimbi Ouattarade Bobo', '', '', '', '', '', '0000-00-00', 0, 941, 0, 0, NULL),
(381, 'ETB', 28, '', 'LycÃ©e Communal de Do', '', '', '', '', '', '0000-00-00', 0, 456, 0, 0, NULL),
(382, 'ETB', 28, '', 'CMS de Dafinso', '', '', '', '', '', '0000-00-00', 0, 277, 0, 0, NULL),
(383, 'ETB', 28, '', 'LycÃ©e dÃ©p. de Bama', '', '', '', '', '', '0000-00-00', 0, 1349, 0, 0, NULL),
(384, 'ETB', 28, '', 'CEG de Balla', '', '', '', '', '', '0000-00-00', 0, 385, 0, 0, NULL),
(385, 'ETB', 28, '', 'CEG de Fo', '', '', '', '', '', '0000-00-00', 0, 288, 0, 0, NULL),
(386, 'ETB', 28, '', 'CEG de Faramana', '', '', '', '', '', '0000-00-00', 0, 389, 0, 0, NULL),
(387, 'ETB', 28, '', 'LycÃ© dÃ©p. de LÃ©na', '', '', '', '', '', '0000-00-00', 0, 499, 0, 0, NULL),
(388, 'ETB', 28, '', 'CEG de PadÃ©ma', '', '', '', '', '', '0000-00-00', 0, 497, 0, 0, NULL),
(389, 'ETB', 28, '', 'LycÃ©e de Toussiana', '', '', '', '', '', '0000-00-00', 0, 1294, 0, 0, NULL),
(390, 'ETB', 28, '', 'CEG de DandÃ©', '', '', '', '', '', '0000-00-00', 0, 553, 0, 0, NULL),
(391, 'ETB', 28, '', 'CEG de  Satiri', '', '', '', '', '', '0000-00-00', 0, 569, 0, 0, NULL),
(392, 'ETB', 28, '', 'CEG de Karangasso-Sambla', '', '', '', '', '', '0000-00-00', 0, 312, 0, 0, NULL),
(393, 'ETB', 28, '', 'CEG de YÃ©guÃ©rÃ©sso', '', '', '', '', '', '0000-00-00', 0, 862, 0, 0, NULL),
(394, 'ETB', 28, '', 'LycÃ©e National', '', '', '', '', '', '0000-00-00', 0, 2070, 0, 0, NULL),
(395, 'ETB', 28, '', 'CEG de PÃ©ni', '', '', '', '', '', '0000-00-00', 0, 598, 0, 0, NULL),
(396, 'ETB', 28, '', 'CEG de Karangasso-ViguÃ©', '', '', '', '', '', '0000-00-00', 0, 488, 0, 0, NULL),
(397, 'ETB', 28, '', 'CEG DE KOUNDOUGOU', '', '', '', '', '', '0000-00-00', 0, 482, 0, 0, NULL),
(398, 'ETB', 28, '', 'CEG de Kouentou', '', '', '', '', '', '0000-00-00', 0, 440, 0, 0, NULL),
(399, 'ETB', 28, '', 'Centre Feminin Adelaiode de CissÃ©', '', '', '', '', '', '0000-00-00', 0, 0, 0, 0, NULL),
(400, 'ETB', 28, '', 'CEG de Logofoursso', '', '', '', '', '', '0000-00-00', 0, 410, 0, 0, NULL),
(401, 'ETB', 28, '', 'CEG de Dafra', '', '', '', '', '', '0000-00-00', 0, 480, 0, 0, NULL),
(403, 'ETB', 28, '', 'CEG de Farakoba', '', '', '', '', '', '0000-00-00', 0, 234, 0, 0, NULL),
(404, 'ETB', 28, '', 'CEG DE Soumousso', '', '', '', '', '', '0000-00-00', 0, 403, 0, 0, NULL),
(405, 'ETB', 29, '', 'LycÃ©e Provincial Diongolo TraorÃ© de Orodara', '', '', '', '', '', '0000-00-00', 0, 1367, 0, 0, NULL),
(406, 'ETB', 29, '', 'LycÃ©e   Municipa de orodara', '', '', '', '', '', '0000-00-00', 0, 676, 0, 0, NULL),
(407, 'ETB', 29, '', 'CEG de  Koloko', '', '', '', '', '', '0000-00-00', 0, 563, 0, 0, NULL),
(408, 'ETB', 29, '', 'CEG de Kourouma', '', '', '', '', '', '0000-00-00', 0, 666, 0, 0, NULL),
(409, 'ETB', 29, '', 'LycÃ©e  de N''Dorola', '', '', '', '', '', '0000-00-00', 0, 659, 0, 0, NULL),
(410, 'ETB', 29, '', 'LycÃ©e dÃ©p. de Samorogouan', '', '', '', '', '', '0000-00-00', 0, 569, 0, 0, NULL),
(411, 'ETB', 29, '', 'CEG de Sindo', '', '', '', '', '', '0000-00-00', 0, 239, 0, 0, NULL),
(412, 'ETB', 29, '', 'CEG de Morolaba', '', '', '', '', '', '0000-00-00', 0, 492, 0, 0, NULL),
(413, 'ETB', 29, '', 'CEG de DigouÃ©ra', '', '', '', '', '', '0000-00-00', 0, 386, 0, 0, NULL),
(414, 'ETB', 29, '', 'CEG de Banzon', '', '', '', '', '', '0000-00-00', 0, 433, 0, 0, NULL),
(415, 'ETB', 29, '', 'CEG de Kayan', '', '', '', '', '', '0000-00-00', 0, 390, 0, 0, NULL),
(416, 'ETB', 29, '', 'CEG de Somogohiri', '', '', '', '', '', '0000-00-00', 0, 387, 0, 0, NULL),
(417, 'ETB', 29, '', 'CEG de kangala', '', '', '', '', '', '0000-00-00', 0, 360, 0, 0, NULL),
(420, 'ETB', 29, '', 'CEG DE DiÃ©ri', '', '', '', '', '', '0000-00-00', 0, 146, 0, 0, NULL),
(421, 'ETB', 29, '', 'CEG DE Sifarasso', '', '', '', '', '', '0000-00-00', 0, 168, 0, 0, NULL),
(422, 'ETB', 29, '', 'CEG DE Sokoroni', '', '', '', '', '', '0000-00-00', 0, 200, 0, 0, NULL),
(423, 'ETB', 29, '', 'CEG DE Kourinion', '', '', '', '', '', '0000-00-00', 0, 319, 0, 0, NULL),
(424, 'ETB', 29, '', 'CEG DE Mahon', '', '', '', '', '', '0000-00-00', 0, 272, 0, 0, NULL),
(425, 'ETB', 30, '', 'LycÃ©e Provincial du Tuy', '', '', '', '', '', '0000-00-00', 0, 1611, 0, 0, NULL),
(426, 'ETB', 30, '', 'LycÃ©e municipal de HoundÃ©', '', '', '', '', '', '0000-00-00', 0, 762, 0, 0, NULL),
(427, 'ETB', 30, '', 'LycÃ©e dÃ©p de  Fouzan', '', '', '', '', '', '0000-00-00', 0, 730, 0, 0, NULL),
(428, 'ETB', 30, '', 'LycÃ©e dÃ©p  de BÃ©rÃ©ba', '', '', '', '', '', '0000-00-00', 0, 665, 0, 0, NULL),
(429, 'ETB', 30, '', 'LycÃ©e dÃ©p de Koumbia', '', '', '', '', '', '0000-00-00', 0, 833, 0, 0, NULL),
(430, 'ETB', 30, '', 'CEG de BÃ©kuy', '', '', '', '', '', '0000-00-00', 0, 163, 0, 0, NULL),
(431, 'ETB', 30, '', 'CEG de Koti', '', '', '', '', '', '0000-00-00', 0, 531, 0, 0, NULL),
(432, 'ETB', 30, '', 'CEG de Boni', '', '', '', '', '', '0000-00-00', 0, 352, 0, 0, NULL),
(433, 'ETB', 4, '', 'CEG de Ouakara', '', '', '', '', '', '0000-00-00', 0, 201, 0, 0, NULL),
(442, 'ETB', 4, '', 'CEG de DÃ©dougou', '', '', '', '', '', '0000-00-00', 0, 188, 0, 0, NULL),
(443, 'ETB', 4, '', 'CEG de Karo', '', '', '', '', '', '0000-00-00', 0, 120, 0, 0, NULL),
(444, 'ETB', 4, '', 'CEG de Soukuy', '', '', '', '', '', '0000-00-00', 0, 180, 0, 0, NULL),
(445, 'ETB', 4, '', 'CEG de Fakena', '', '', '', '', '', '0000-00-00', 0, 176, 0, 0, NULL),
(454, 'ETB', 4, '', 'CEG de KÃ©ra Mouhoun', '', '', '', '', '', '0000-00-00', 0, 247, 0, 0, NULL),
(455, 'ETB', 4, '', 'CEG de Tierkou', '', '', '', '', '', '0000-00-00', 0, 201, 0, 0, NULL),
(456, 'ETB', 7, '', 'LycÃ©e Lompolo KonÃ©', '', '', '', '', '', '0000-00-00', 0, 1946, 0, 0, NULL),
(457, 'ETB', 7, '', 'LycÃ©e Municipal HEMA FADOUAH GNIAMBIA', '', '', '', '', '', '0000-00-00', 0, 2143, 0, 0, NULL),
(458, 'ETB', 7, '', 'LycÃ©e dÃ©p de BÃ©rÃ©gadougou', '', '', '', '', '', '0000-00-00', 0, 869, 0, 0, NULL),
(460, 'ETB', 7, '', 'lLycÃ©e Santa de Niangoloko', '', '', '', '', '', '0000-00-00', 0, 1435, 0, 0, NULL),
(461, 'ETB', 7, '', 'LycÃ©e DÃ©p de SidÃ©radougou', '', '', '', '', '', '0000-00-00', 0, 621, 0, 0, NULL),
(462, 'ETB', 7, '', 'CEG de SoubakaniÃ©dougou', '', '', '', '', '', '0000-00-00', 0, 562, 0, 0, NULL),
(463, 'ETB', 7, '', 'CEG de TiÃ©fora', '', '', '', '', '', '0000-00-00', 0, 1214, 0, 0, NULL),
(464, 'ETB', 7, '', 'CEG de Mangodara', '', '', '', '', '', '0000-00-00', 0, 1057, 0, 0, NULL),
(465, 'ETB', 7, '', 'LycÃ©e Municipal Jacques Toulat de Banfora', '', '', '', '', '', '0000-00-00', 0, 1706, 0, 0, NULL),
(466, 'ETB', 7, '', 'LycÃ©e  Municip. de Niangoloko', '', '', '', '', '', '0000-00-00', 0, 803, 0, 0, NULL),
(467, 'ETB', 7, '', 'CEG DE SiniÃ©na', '', '', '', '', '', '0000-00-00', 0, 432, 0, 0, NULL),
(468, 'ETB', 7, '', 'CEG de Moussodougou', '', '', '', '', '', '0000-00-00', 0, 359, 0, 0, NULL),
(469, 'ETB', 7, '', 'CEG DE Tengrela', '', '', '', '', '', '0000-00-00', 0, 415, 0, 0, NULL),
(472, 'ETB', 8, '', 'LycÃ©e Provincial de la LÃ©raba', '', '', '', '', '', '0000-00-00', 0, 595, 0, 0, NULL),
(473, 'ETB', 8, '', 'LycÃ©e dep  Douna', '', '', '', '', '', '0000-00-00', 0, 656, 0, 0, NULL),
(474, 'ETB', 8, '', 'CEG de Dakoro', '', '', '', '', '', '0000-00-00', 0, 588, 0, 0, NULL),
(475, 'ETB', 8, '', 'CEG de Kankalaba', '', '', '', '', '', '0000-00-00', 0, 529, 0, 0, NULL),
(476, 'ETB', 8, '', 'CEG de Niankorodougou', '', '', '', '', '', '0000-00-00', 0, 449, 0, 0, NULL),
(477, 'ETB', 8, '', 'CEG de WÃ©lÃ©ni', '', '', '', '', '', '0000-00-00', 0, 591, 0, 0, NULL),
(478, 'ETB', 8, '', 'CEG de Konadougou', '', '', '', '', '', '0000-00-00', 0, 227, 0, 0, NULL),
(479, 'ETB', 42, '', 'LycÃ©e Provincial de DiÃ©bougou', '', '', '', '', '', '0000-00-00', 0, 1163, 0, 0, NULL),
(480, 'ETB', 42, '', 'LycÃ©e Municipal de DiÃ©bougou', '', '', '', '', '', '0000-00-00', 0, 876, 0, 0, NULL),
(481, 'ETB', 42, '', 'LycÃ©e Gnonan KAM  de  Dolo', '', '', '', '', '', '0000-00-00', 0, 400, 0, 0, NULL),
(482, 'ETB', 42, '', 'CEG de Tiankoura', '', '', '', '', '', '0000-00-00', 0, 400, 0, 0, NULL),
(483, 'ETB', 42, '', 'CEG de Iolonioro', '', '', '', '', '', '0000-00-00', 0, 561, 0, 0, NULL),
(484, 'ETB', 42, '', 'CEG DE Bamako', '', '', '', '', '', '0000-00-00', 0, 161, 0, 0, NULL),
(485, 'ETB', 43, '', 'LycÃ©e Provincial DU iOBA', '', '', '', '', '', '0000-00-00', 0, 1390, 0, 0, NULL),
(486, 'ETB', 43, '', 'CEG Communal de Dano', '', '', '', '', '', '0000-00-00', 0, 304, 0, 0, NULL),
(487, 'ETB', 43, '', 'LycÃ©e dÃ©p de Dissin', '', '', '', '', '', '0000-00-00', 0, 1227, 0, 0, NULL),
(488, 'ETB', 43, '', 'LycÃ©e dÃ©p de Koper', '', '', '', '', '', '0000-00-00', 0, 752, 0, 0, NULL),
(489, 'ETB', 43, '', 'LycÃ©e dÃ©p de OuÃ©ssa', '', '', '', '', '', '0000-00-00', 0, 791, 0, 0, NULL),
(490, 'ETB', 43, '', 'CEG de GuÃ©guÃ©rÃ©', '', '', '', '', '', '0000-00-00', 0, 1009, 0, 0, NULL),
(491, 'ETB', 43, '', 'CEG de Zambo', '', '', '', '', '', '0000-00-00', 0, 362, 0, 0, NULL),
(493, 'ETB', 43, '', 'CEG de Nakar', '', '', '', '', '', '0000-00-00', 0, 164, 0, 0, NULL),
(494, 'ETB', 44, '', 'LycÃ©e Provincial du nombiel', '', '', '', '', '', '0000-00-00', 0, 803, 0, 0, NULL),
(495, 'ETB', 44, '', 'CEG de  Legmoin', '', '', '', '', '', '0000-00-00', 0, 604, 0, 0, NULL),
(496, 'ETB', 44, '', 'CEG COMMUNAL DE BatiÃ©', '', '', '', '', '', '0000-00-00', 0, 455, 0, 0, NULL),
(497, 'ETB', 44, '', 'CEG de Boussoukoula', '', '', '', '', '', '0000-00-00', 0, 243, 0, 0, NULL),
(498, 'ETB', 45, '', 'LycÃ©e provincial Bafuji de Gaoua', '', '', '', '', '', '0000-00-00', 0, 1560, 0, 0, NULL),
(499, 'ETB', 45, '', 'LycÃ©e    Municipal de Gaoua', '', '', '', '', '', '0000-00-00', 0, 1114, 0, 0, NULL),
(500, 'ETB', 45, '', 'CEG COMMUNAL Kampti', '', '', '', '', '', '0000-00-00', 0, 277, 0, 0, NULL),
(501, 'ETB', 45, '', 'LycÃ©e dÃ©p  de  LorÃ©pÃ©ni', '', '', '', '', '', '0000-00-00', 0, 972, 0, 0, NULL),
(502, 'ETB', 45, '', 'CEG de Bouroum-Bouroum', '', '', '', '', '', '0000-00-00', 0, 733, 0, 0, NULL),
(503, 'ETB', 45, '', 'CEG de Nako', '', '', '', '', '', '0000-00-00', 0, 547, 0, 0, NULL),
(505, 'ETB', 45, '', 'CEG DE GOMBLORA', '', '', '', '', '', '0000-00-00', 0, 427, 0, 0, NULL),
(506, 'ETB', 45, '', 'CEG de Malba', '', '', '', '', '', '0000-00-00', 0, 249, 0, 0, NULL),
(508, 'ETB', 45, '', 'CEG de BoussiÃ©ra', '', '', '', '', '', '0000-00-00', 0, 448, 0, 0, NULL),
(509, 'ETB', 45, '', 'CEG de DjiguÃ©', '', '', '', '', '', '0000-00-00', 0, 185, 0, 0, NULL),
(511, 'ETB', 45, '', 'CEG DE DipÃ©o', '', '', '', '', '', '0000-00-00', 0, 179, 0, 0, NULL),
(512, 'ETB', 45, '', 'CEG DE Tobo', '', '', '', '', '', '0000-00-00', 0, 125, 0, 0, NULL),
(513, 'ETB', 45, '', 'CEG DE Balakar', '', '', '', '', '', '0000-00-00', 0, 117, 0, 0, NULL),
(514, 'ETB', 45, '', 'LycÃ©e dÃ©p de Kampti', '', '', '', '', '', '0000-00-00', 0, 894, 0, 0, NULL),
(517, 'ETB', 29, '', 'CEG de SEREKENI', '', '', '', '', '', '0000-00-00', 0, 197, 0, 0, '2012-08-13 16:07:53'),
(518, 'ETB', 28, '', 'CEG de Kokoroba', '', '', '', '', '', '0000-00-00', 0, 214, 0, 0, '2012-09-04 13:01:56'),
(519, 'ETB', 28, '', 'CEG de Dan', '', '', '', '', '', '0000-00-00', 0, 154, 0, 0, '2012-09-04 13:03:07'),
(520, 'ETB', 28, '', 'LycÃ©e municipal Sanny Sanon', '', '', '', '', '', '0000-00-00', 0, 1689, 0, 0, '2012-09-04 13:06:07'),
(521, 'ETB', 28, '', 'CEG de Sogossagasso', '', '', '', '', '', '0000-00-00', 0, 110, 0, 0, '2012-09-04 13:06:51'),
(522, 'ETB', 28, '', 'CEG de Banahorodougou', '', '', '', '', '', '0000-00-00', 0, 132, 0, 0, '2012-09-04 13:08:15'),
(523, 'ETB', 29, '', 'CEG de Kotoura', '', '', '', '', '', '0000-00-00', 0, 171, 0, 0, '2012-09-04 13:16:04'),
(524, 'ETB', 29, '', 'CEG de Kongolikoro', '', '', '', '', '', '0000-00-00', 0, 75, 0, 0, '2012-09-04 13:16:37'),
(525, 'ETB', 29, '', 'CEG de Tin', '', '', '', '', '', '0000-00-00', 0, 99, 0, 0, '2012-09-04 13:17:09'),
(526, 'ETB', 30, '', 'CEG de Kari', '', '', '', '', '', '0000-00-00', 0, 157, 0, 0, '2012-09-04 13:21:22'),
(527, 'ETB', 30, '', 'CEG de PÃª', '', '', '', '', '', '2012-09-04', 0, 0, 0, 0, '2012-09-04 13:22:03'),
(528, 'ETB', 30, '', 'CEG de Kovio', '', '', '', '', '', '0000-00-00', 0, 110, 0, 0, '2012-09-04 13:22:36'),
(529, 'ETB', 30, '', 'CEG DE Ouakuy', '', '', '', '', '', '0000-00-00', 0, 173, 0, 0, '2012-09-04 13:23:11'),
(530, 'ETB', 30, '', 'CEG de Sara', '', '', '', '', '', '0000-00-00', 0, 84, 0, 0, '2012-09-04 13:23:38'),
(531, 'ETB', 30, '', 'CEG de BouÃ©rÃ©', '', '', '', '', '', '0000-00-00', 0, 346, 0, 0, '2012-09-04 13:24:17'),
(532, 'ETB', 1, '', 'CEG de Bana', '', '', '', '', '', '0000-00-00', 0, 562, 0, 0, '2012-09-04 13:31:39'),
(533, 'ETB', 1, '', 'CEG de Nabou', '', '', '', '', '', '0000-00-00', 0, 174, 0, 0, '2012-09-04 13:32:01'),
(534, 'ETB', 1, '', 'CEG de Ouahabou', '', '', '', '', '', '0000-00-00', 0, 680, 0, 0, '2012-09-04 13:32:33'),
(535, 'ETB', 1, '', 'CEG de TonÃ©', '', '', '', '', '', '0000-00-00', 0, 339, 0, 0, '2012-09-04 13:33:04'),
(536, 'ETB', 1, '', 'CEG de Vy', '', '', '', '', '', '0000-00-00', 0, 154, 0, 0, '2012-09-04 13:33:40'),
(537, 'ETB', 1, '', 'CEG de Yaho', '', '', '', '', '', '0000-00-00', 0, 355, 0, 0, '2012-09-04 13:34:11'),
(538, 'ETB', 1, '', 'LycÃ©e dÃ©p de Bagassi', '', '', '', '', '', '0000-00-00', 0, 892, 0, 0, '2012-09-04 13:34:44'),
(539, 'ETB', 3, '', 'CEG communal de Nouna', '', '', '', '', '', '0000-00-00', 0, 198, 0, 0, '2012-09-04 13:37:09'),
(540, 'ETB', 3, '', 'CEG de Dara', '', '', '', '', '', '0000-00-00', 0, 161, 0, 0, '2012-09-04 13:37:32'),
(541, 'ETB', 3, '', 'CEG de Dokuy', '', '', '', '', '', '0000-00-00', 0, 282, 0, 0, '2012-09-04 13:37:59'),
(542, 'ETB', 3, '', 'CEG de Goni', '', '', '', '', '', '0000-00-00', 0, 353, 0, 0, '2012-09-04 13:38:27'),
(543, 'ETB', 3, '', 'CEG de Sono', '', '', '', '', '', '0000-00-00', 0, 224, 0, 0, '2012-09-04 13:38:49'),
(544, 'ETB', 3, '', 'LycÃ©e dÃ©p de Djibasso', '', '', '', '', '', '0000-00-00', 0, 1092, 0, 0, '2012-09-04 13:39:19'),
(546, 'ETB', 4, '', 'CEG de TchÃ©riba', '', '', '', '', '', '0000-00-00', 0, 682, 0, 0, '2012-09-04 14:17:19'),
(547, 'ETB', 5, '', 'LycÃ©e Provincial du Nayala', '', '', '', '', '', '0000-00-00', 0, 1456, 0, 0, '2012-09-04 14:22:40'),
(548, 'ETB', 5, '', 'CEG de Daman', '', '', '0', '', '', '0000-00-00', 0, 180, 0, 0, '2012-09-04 14:24:02'),
(549, 'ETB', 5, '', 'CEG de Saoura', '', '', '', '', '', '0000-00-00', 0, 221, 0, 0, '2012-09-04 14:24:30'),
(550, 'ETB', 5, '', 'CEG de Zouma', '', '', '', '', '', '0000-00-00', 0, 183, 0, 0, '2012-09-04 14:25:03'),
(551, 'ETB', 5, '', 'CEG de SiÃ©llÃ©', '', '', '', '', '', '0000-00-00', 0, 209, 0, 0, '2012-09-04 14:25:56'),
(552, 'ETB', 5, '', 'LycÃ©e dÃ©p.3de Gassan', '', '', '', '', '', '0000-00-00', 0, 907, 0, 0, '2012-09-04 14:26:32'),
(553, 'ETB', 5, '', 'CollÃ¨ge SacrÃ© coeur de Toma', '', '', '', '', '', '0000-00-00', 0, 0, 0, 0, '2012-09-04 14:29:34'),
(554, 'ETB', 6, '', 'CEG de Kassan', '', '', '', '', '', '0000-00-00', 0, 199, 0, 0, '2012-09-04 14:32:57'),
(555, 'ETB', 6, '', 'CEG de Bangassogo', '', '', '', '', '', '0000-00-00', 0, 230, 0, 0, '2012-09-04 14:37:00'),
(556, 'ETB', 6, '', 'CEG de Bonou', '', '', '', '', '', '0000-00-00', 0, 366, 0, 0, '2012-09-04 14:37:22'),
(557, 'ETB', 6, '', 'CEG de Di', '', '', '', '', '', '0000-00-00', 0, 659, 0, 0, '2012-09-04 14:37:49'),
(558, 'ETB', 6, '', 'LycÃ©e communal de Tougan', '', '', '', '', '', '0000-00-00', 0, 862, 0, 0, '2012-09-04 14:38:47'),
(560, 'ETB', 7, '', 'CEG de Ouo', '', '', '', '', '', '0000-00-00', 0, 390, 0, 0, '2012-09-04 14:59:49'),
(561, 'ETB', 7, '', 'CEG de Tarfila', '', '', '', '', '', '0000-00-00', 0, 281, 0, 0, '2012-09-04 15:00:53'),
(562, 'ETB', 8, '', 'CEG de Baguera', '', '', '', '', '', '0000-00-00', 0, 222, 0, 0, '2012-09-04 15:03:17'),
(563, 'ETB', 8, '', 'CEG de Loumana', '', '', '', '', '', '0000-00-00', 0, 462, 0, 0, '2012-09-04 15:03:58'),
(564, 'ETB', 8, '', 'CEG de WOLOKONTO', '', '', '', '', '', '0000-00-00', 0, 353, 0, 0, '2012-09-04 15:04:46'),
(565, 'ETB', 9, '', 'CEG Municipal de GonsÃ©', '', '', '', '', '', '0000-00-00', 0, 231, 0, 0, '2012-09-04 15:17:51'),
(566, 'ETB', 9, '', 'CEG de BazoulÃ©', '', '', '', '', '', '0000-00-00', 0, 153, 0, 0, '2012-09-04 15:18:31'),
(567, 'ETB', 9, '', 'CEG DE Dondoulma', '', '', '', '', '', '0000-00-00', 0, 169, 0, 0, '2012-09-04 15:19:15'),
(568, 'ETB', 9, '', 'CEG DE GUIGUEMTENGA', '', '', '', '', '', '0000-00-00', 0, 107, 0, 0, '2012-09-04 15:20:15'),
(569, 'ETB', 9, '', 'CEG DE KienfangÃ©', '', '', '', '', '', '0000-00-00', 0, 247, 0, 0, '2012-09-04 15:21:11'),
(570, 'ETB', 9, '', 'CEG DE OUANSOUA', '', '', '', '', '', '0000-00-00', 0, 103, 0, 0, '2012-09-04 15:21:41'),
(571, 'ETB', 9, '', 'CEG de Sabtoana', '', '', '', '', '', '0000-00-00', 0, 97, 0, 0, '2012-09-04 15:22:47'),
(572, 'ETB', 13, '', 'CEG de NassÃ©rÃ©', '', '', '', '', '', '0000-00-00', 0, 263, 0, 0, '2012-09-04 15:27:01'),
(573, 'ETB', 13, '', 'CEG de Manegtaba', '', '', '', '', '', '0000-00-00', 0, 209, 0, 0, '2012-09-04 15:27:24'),
(574, 'ETB', 13, '', 'CEG DE Zimtenga', '', '', '', '', '', '0000-00-00', 0, 200, 0, 0, '2012-09-04 15:27:46'),
(575, 'ETB', 14, '', 'CEG de Bouroum', '', '', '', '', '', '0000-00-00', 0, 409, 0, 0, '2012-09-04 15:32:03'),
(576, 'ETB', 2, '', 'CEG de BalavÃ©', '', '', '', '', '', '0000-00-00', 0, 792, 0, 0, '2012-09-05 12:27:42'),
(577, 'ETB', 2, '', 'CEG DE Bena', '', '', '', '', '', '0000-00-00', 0, 290, 0, 0, '2012-09-05 12:28:31'),
(578, 'ETB', 2, '', 'CEG de Daboura', '', '', '', '', '', '0000-00-00', 0, 319, 0, 0, '2012-09-05 12:30:53'),
(579, 'ETB', 2, '', 'CEG de Diontala', '', '', '', '', '', '0000-00-00', 0, 276, 0, 0, '2012-09-05 12:31:33'),
(580, 'ETB', 2, '', 'CEG de Sama', '', '', '', '', '', '0000-00-00', 0, 230, 0, 0, '2012-09-05 12:31:55'),
(581, 'ETB', 2, '', 'CEG de Sanaba', '', '', '', '', '', '0000-00-00', 0, 783, 0, 0, '2012-09-05 12:32:43');
INSERT INTO `beneficiaire` (`ID_BENEF`, `CODE_NOMBENF`, `IDPROVINCE`, `CODE_BENEF`, `BENEF_NOM`, `BENEF_EBREVIATION`, `BENEF_TEL`, `BENEF_ADRESSE`, `BENEF_VILLE`, `BENEF_EMAIL`, `BENEF_DATEINT`, `BENEF_DIST`, `BENEF_EFF`, `BENEF_FILLE`, `BENEF_GARC`, `BENEF_DATECREAT`) VALUES
(582, 'ETB', 2, '', 'CEG de Tansila', '', '', '', '', '', '0000-00-00', 0, 720, 0, 0, '2012-09-05 12:33:04'),
(583, 'ETB', 2, '', 'LycÃ©Ã© dÃ©pa. de Kouka', '', '', '', '', '', '0000-00-00', 0, 1360, 0, 0, '2012-09-05 12:33:45'),
(584, 'ETB', 2, '', 'LycÃ©e provincial des Banwa', '', '', '', '', '', '0000-00-00', 0, 1321, 0, 0, '2012-09-05 12:34:23'),
(585, 'ETB', 20, '', 'CEG de Guirgho', '', '', '', '', '', '0000-00-00', 0, 298, 0, 0, '2012-09-05 12:41:34'),
(586, 'ETB', 20, '', 'CEG de Sambin', '', '', '', '', '', '0000-00-00', 0, 94, 0, 0, '2012-09-05 12:42:22'),
(587, 'ETB', 20, '', 'CEG de Tim Tim', '', '', '', '', '', '0000-00-00', 0, 367, 0, 0, '2012-09-05 12:42:51'),
(588, 'ETB', 20, '', 'CEG de Toudou', '', '', '', '', '', '0000-00-00', 0, 254, 0, 0, '2012-09-05 12:43:13'),
(589, 'ETB', 20, '', 'CEG de Gana', '', '', '', '', '', '0000-00-00', 0, 238, 0, 0, '2012-09-05 12:43:39'),
(590, 'ETB', 20, '', 'CEG de Pissi', '', '', '', '', '', '0000-00-00', 0, 226, 0, 0, '2012-09-05 12:43:58'),
(591, 'ETB', 20, '', 'LycÃ©e dÃ©p de IpÃ©lcÃ©', '', '', '', '', '', '0000-00-00', 0, 1018, 0, 0, '2012-09-05 12:44:25'),
(594, 'ETB', 10, '', 'CEG  DE OuanrÃ©gou', '', '', '', '', '', '0000-00-00', 0, 258, 0, 0, '2012-09-05 12:55:42'),
(595, 'ETB', 10, '', 'ceg DE Gando', '', '', '', '', '', '0000-00-00', 0, 131, 0, 0, '2012-09-05 12:56:21'),
(596, 'ETB', 10, '', 'CEG de OunzÃ©ogo', '', '', '', '', '', '0000-00-00', 0, 253, 0, 0, '2012-09-05 12:56:52'),
(597, 'ETB', 10, '', 'CEG de Sanogo', '', '', '', '', '', '0000-00-00', 0, 242, 0, 0, '2012-09-05 12:57:15'),
(598, 'ETB', 10, '', 'CEG de TengarÃ©', '', '', '', '', '', '0000-00-00', 0, 502, 0, 0, '2012-09-05 12:57:53'),
(599, 'ETB', 16, '', 'CEG de Bingo', '', '', '', '', '', '0000-00-00', 0, 215, 0, 0, '2012-09-05 13:10:28'),
(600, 'ETB', 16, '', 'CEG  DE Boulpon', '', '', '', '', '', '0000-00-00', 0, 400, 0, 0, '2012-09-05 13:11:19'),
(602, 'ETB', 16, '', 'CEG de Nabadogo', '', '', '', '', '', '0000-00-00', 0, 216, 0, 0, '2012-09-05 13:12:10'),
(603, 'ETB', 16, '', 'CEG de Nandiala', '', '', '', '', '', '0000-00-00', 0, 315, 0, 0, '2012-09-05 13:12:35'),
(604, 'ETB', 16, '', 'CEG de Pella', '', '', '', '', '', '0000-00-00', 0, 418, 0, 0, '2012-09-05 13:12:58'),
(605, 'ETB', 16, '', 'CEG de SakoinsÃ©', '', '', '', '', '', '0000-00-00', 0, 264, 0, 0, '2012-09-05 13:13:20'),
(606, 'ETB', 16, '', 'CEG de SogpÃ©lcÃ©', '', '', '', '', '', '0000-00-00', 0, 469, 0, 0, '2012-09-05 13:13:48'),
(607, 'ETB', 16, '', 'CEG de Soula', '', '', '', '', '', '0000-00-00', 0, 202, 0, 0, '2012-09-05 13:14:08'),
(608, 'ETB', 16, '', 'Coll communal de Koudougou', '', '', '', '', '', '0000-00-00', 0, 1288, 0, 0, '2012-09-05 13:14:43'),
(609, 'ETB', 16, '', 'LycÃ©e dÃ©partemental de Imasgo', '', '', '', '', '', '0000-00-00', 0, 708, 0, 0, '2012-09-05 13:15:15'),
(610, 'ETB', 16, '', 'LycÃ©e dÃ©p de Kindi', '', '', '', '', '', '0000-00-00', 0, 970, 0, 0, '2012-09-05 13:15:38'),
(611, 'ETB', 16, '', 'LycÃ©e dÃ©partemental de Nanoro', '', '', '', '', '', '0000-00-00', 0, 787, 0, 0, '2012-09-05 13:16:19'),
(612, 'ETB', 17, '', 'CollÃ¨ge sainte CÃ©cile de RÃ©o', '', '', '', '', '', '0000-00-00', 0, 0, 0, 0, '2012-09-05 13:22:07'),
(613, 'ETB', 17, '', 'CEG de Zamo', '', '', '', '', '', '0000-00-00', 0, 361, 0, 0, '2012-09-05 13:22:33'),
(614, 'ETB', 17, '', 'CEG de Zoula', '', '', '', '', '', '0000-00-00', 0, 383, 0, 0, '2012-09-05 13:23:00'),
(615, 'ETB', 35, '', 'CEG de Sapaga', '', '', '', '', '', '0000-00-00', 0, 162, 0, 0, '2012-09-05 13:26:59'),
(616, 'ETB', 35, '', 'CEG de Boena', '', '', '', '', '', '0000-00-00', 0, 314, 0, 0, '2012-09-05 13:27:23'),
(617, 'ETB', 35, '', 'CEG de Tanghin', '', '', '', '', '', '0000-00-00', 0, 309, 0, 0, '2012-09-05 13:27:55'),
(618, 'ETB', 23, '', 'CEG de Bilanga-Yanga', '', '', '', '', '', '0000-00-00', 0, 328, 0, 0, '2012-09-05 13:31:18'),
(619, 'ETB', 23, '', 'CEG de Coalla', '', '', '', '', '', '0000-00-00', 0, 356, 0, 0, '2012-09-05 13:31:44'),
(620, 'ETB', 23, '', 'CEG de LIPTOUGOU', '', '', '0', '', '', '0000-00-00', 0, 259, 0, 0, '2012-09-05 13:32:13'),
(621, 'ETB', 23, '', 'CEG de Mopienga', '', '', '', '', '', '0000-00-00', 0, 179, 0, 0, '2012-09-05 13:33:05'),
(622, 'ETB', 23, '', 'CEG municipal de BogandÃ©', '', '', '', '', '', '0000-00-00', 0, 759, 0, 0, '2012-09-05 13:33:35'),
(623, 'ETB', 23, '', 'LycÃ©e dÃ©p de Bilanga', '', '', '', '', '', '0000-00-00', 0, 1124, 0, 0, '2012-09-05 13:34:01'),
(624, 'ETB', 24, '', 'LycÃ©e communan de Fada', '', '', '', '', '', '0000-00-00', 0, 1126, 0, 0, '2012-09-05 13:37:19'),
(625, 'ETB', 24, '', 'LycÃ©e professionnel agricole de Tamba', '', '', '', '', '', '2012-09-05', 0, 0, 0, 0, '2012-09-05 13:38:05'),
(626, 'ETB', 28, '', 'CEG de Kouroukan', '', '', '', '', '', '0000-00-00', 0, 272, 0, 0, '2012-09-05 13:40:15'),
(627, 'ETB', 43, '', 'CEG de Oronkua', '', '', '', '', '', '0000-00-00', 0, 423, 0, 0, '2012-09-05 13:47:35'),
(628, 'ETB', 43, '', 'CEG de Tovor', '', '', '', '', '', '0000-00-00', 0, 95, 0, 0, '2012-09-05 13:48:02'),
(629, 'ETB', 9, '', 'CEG Municipal de Nongre-Massom', '', '', '', '', '', '0000-00-00', 0, 459, 0, 0, '2012-09-05 13:50:00'),
(630, 'ETB', 11, '', 'LycÃ©e dÃ©p de Sangha', '', '', '', '', '', '0000-00-00', 0, 1530, 0, 0, '2012-09-05 13:56:09'),
(631, 'ETB', 26, '', 'CEG lumiÃ¨re de Kompienga', '', '', '', '', '', '0000-00-00', 0, 1203, 0, 0, '2012-09-05 13:57:24'),
(632, 'ETB', 26, '', 'LycÃ©e provincial de Pama', '', '', '', '', '', '0000-00-00', 0, 901, 0, 0, '2012-09-05 13:57:59'),
(633, 'ETB', 12, '', 'CEG de Dagamtenga', '', '', '', '', '', '0000-00-00', 0, 128, 0, 0, '2012-09-05 14:01:02'),
(634, 'ETB', 12, '', 'CEG de KabÃ¨ga', '', '', '', '', '', '0000-00-00', 0, 458, 0, 0, '2012-09-05 14:01:34'),
(635, 'ETB', 12, '', 'CEG de Kando', '', '', '', '', '', '0000-00-00', 0, 476, 0, 0, '2012-09-05 14:02:02'),
(636, 'ETB', 12, '', 'LycÃ©e dÃ©partemental de Andemtenga', '', '', '', '', '', '0000-00-00', 0, 1404, 0, 0, '2012-09-05 14:02:39'),
(637, 'ETB', 36, '', 'LycÃ©e municipal de BoussÃ©', '', '', '', '', '', '0000-00-00', 0, 241, 0, 0, '2012-09-05 14:07:54'),
(638, 'ETB', 36, '', 'CEG de Koukin', '', '', '', '', '', '0000-00-00', 0, 156, 0, 0, '2012-09-05 14:08:30'),
(639, 'ETB', 8, '', 'CEG de ZÃ©gnedougou', '', '', '', '', '', '0000-00-00', 0, 178, 0, 0, '2012-09-05 14:10:15'),
(640, 'ETB', 31, '', 'CEG de InganÃ©', '', '', '', '', '', '0000-00-00', 0, 199, 0, 0, '2012-09-05 14:14:28'),
(641, 'ETB', 31, '', 'CEG de SollÃ©', '', '', '', '', '', '0000-00-00', 0, 391, 0, 0, '2012-09-05 14:14:55'),
(642, 'ETB', 21, '', 'CEG de Kampala', '', '', '', '', '', '0000-00-00', 0, 424, 0, 0, '2012-09-05 14:18:39'),
(643, 'ETB', 21, '', 'CEG de Songo', '', '', '', '', '', '0000-00-00', 0, 75, 0, 0, '2012-09-05 14:19:08'),
(644, 'ETB', 21, '', 'CEG de Guenon', '', '', '', '', '', '0000-00-00', 0, 380, 0, 0, '2012-09-05 14:20:09'),
(645, 'ETB', 44, '', 'CEG de KpÃ©rÃ©', '', '', '', '', '', '0000-00-00', 0, 169, 0, 0, '2012-09-05 14:22:28'),
(646, 'ETB', 44, '', 'CEG de Midebdo', '', '', '', '', '', '0000-00-00', 0, 304, 0, 0, '2012-09-05 14:23:15'),
(648, 'ETB', 37, '', 'CEG de Bissiga', '', '', '', '', '', '0000-00-00', 0, 288, 0, 0, '2012-09-05 14:34:11'),
(649, 'ETB', 37, '', 'CEG de Bendogo', '', '', '', '', '', '0000-00-00', 0, 204, 0, 0, '2012-09-05 14:34:37'),
(650, 'ETB', 37, '', 'CEG de GuiÃ©', '', '', '', '', '', '0000-00-00', 0, 324, 0, 0, '2012-09-05 14:35:26'),
(651, 'ETB', 37, '', 'CEG de Pagatenga', '', '', '', '', '', '0000-00-00', 0, 225, 0, 0, '2012-09-05 14:36:05'),
(652, 'ETB', 37, '', 'CEG de Ziga', '', '', '', '', '', '0000-00-00', 0, 171, 0, 0, '2012-09-05 14:36:30'),
(653, 'ETB', 32, '', 'CEG de Guipa', '', '', '', '', '', '0000-00-00', 0, 101, 0, 0, '2012-09-05 14:48:49'),
(654, 'ETB', 32, '', 'CEG  DE imiougou', '', '', '', '', '', '0000-00-00', 0, 82, 0, 0, '2012-09-05 14:49:54'),
(655, 'ETB', 32, '', 'CEG de Batono', '', '', '', '', '', '0000-00-00', 0, 257, 0, 0, '2012-09-05 14:50:40'),
(656, 'ETB', 32, '', 'CEG de BourÃ©', '', '', '', '', '', '0000-00-00', 0, 118, 0, 0, '2012-09-05 14:51:18'),
(657, 'ETB', 32, '', 'CEG de DourÃ©', '', '', '', '', '', '0000-00-00', 0, 250, 0, 0, '2012-09-05 14:52:35'),
(658, 'ETB', 32, '', 'CEG de Minissia', '', '', '', '', '', '0000-00-00', 0, 242, 0, 0, '2012-09-05 14:53:16'),
(659, 'ETB', 32, '', 'CEG Ouissiga', '', '', '', '', '', '0000-00-00', 0, 113, 0, 0, '2012-09-05 14:54:01'),
(660, 'ETB', 32, '', 'CEG de Sarma', '', '', '', '', '', '0000-00-00', 0, 201, 0, 0, '2012-09-05 14:54:34'),
(661, 'ETB', 32, '', 'CEG de Song Naba', '', '', '', '', '', '0000-00-00', 0, 361, 0, 0, '2012-09-05 14:55:56'),
(662, 'ETB', 32, '', 'CEG de TEMA', '', '', '', '', '', '0000-00-00', 0, 305, 0, 0, '2012-09-05 14:57:48'),
(663, 'ETB', 45, '', 'CEG de PÃ©rigban', '', '', '', '', '', '0000-00-00', 0, 279, 0, 0, '2012-09-05 15:09:45'),
(664, 'ETB', 45, '', 'CEG communal de Gaoua', '', '', '', '', '', '0000-00-00', 0, 354, 0, 0, '2012-09-05 15:10:55'),
(665, 'ETB', 15, '', 'CEG communal de Boussouma', '', '', '', '', '', '0000-00-00', 0, 181, 0, 0, '2012-09-05 15:19:03'),
(666, 'ETB', 15, '', 'CEG multilingue de Tanyoko', '', '', '', '', '', '0000-00-00', 0, 266, 0, 0, '2012-09-05 15:19:50'),
(667, 'ETB', 39, '', 'CEG de Dori', '', '', '', '', '', '0000-00-00', 0, 226, 0, 0, '2012-09-05 15:24:12'),
(668, 'ETB', 39, '', 'CEG de Gorgadji', '', '', '', '', '', '0000-00-00', 0, 322, 0, 0, '2012-09-05 15:24:58'),
(669, 'ETB', 40, '', 'CEG de SikirÃ©', '', '', '', '', '', '0000-00-00', 0, 212, 0, 0, '2012-09-05 15:32:45'),
(670, 'ETB', 41, '', 'CEG de Mansila', '', '', '', '', '', '0000-00-00', 0, 317, 0, 0, '2012-09-05 16:14:05'),
(671, 'ETB', 41, '', 'CEG de TitabÃ©', '', '', '', '', '', '0000-00-00', 0, 64, 0, 0, '2012-09-05 16:16:20'),
(672, 'ETB', 33, '', 'LycÃ©e Communal de Tougou', '', '', '', '', '', '0000-00-00', 0, 98, 0, 0, '2012-09-05 17:52:32'),
(673, 'ETB', 33, '', 'CEG de Sabouna', '', '', '', '', '', '0000-00-00', 0, 79, 0, 0, '2012-09-06 09:33:32'),
(674, 'ETB', 33, '', 'CEG de Berenga', '', '', '', '', '', '0000-00-00', 0, 225, 0, 0, '2012-09-06 09:34:21'),
(675, 'ETB', 33, '', 'CEG de Sissamba', '', '', '', '', '', '0000-00-00', 0, 254, 0, 0, '2012-09-06 09:37:00'),
(676, 'ETB', 33, '', 'CEG de Ziga', '', '', '', '', '', '0000-00-00', 0, 254, 0, 0, '2012-09-06 09:43:24'),
(677, 'ETB', 33, '', 'CEG de Zomkalga', '', '', '', '', '', '0000-00-00', 0, 156, 0, 0, '2012-09-06 09:44:43'),
(678, 'ETB', 33, '', 'CEG de NongfairÃ©', '', '', '', '', '', '0000-00-00', 0, 124, 0, 0, '2012-09-06 09:46:52'),
(679, 'ETB', 19, '', 'CEG de Bognonou', '', '', '', '', '', '0000-00-00', 0, 284, 0, 0, '2012-09-06 09:50:47'),
(680, 'ETB', 19, '', 'CEG de Bakata', '', '', '', '', '', '0000-00-00', 0, 465, 0, 0, '2012-09-06 09:51:48'),
(681, 'ETB', 19, '', 'LycÃ©e provincial de Sapouy', '', '', '', '', '', '0000-00-00', 0, 204, 0, 0, '2012-09-06 09:52:38'),
(682, 'ETB', 34, '', 'CEG de Tamounouma', '', '', '', '', '', '0000-00-00', 0, 105, 0, 0, '2012-09-06 09:58:25'),
(684, 'ETB', 34, '', 'CEG de Bougounam', '', '', '', '', '', '0000-00-00', 0, 152, 0, 0, '2012-09-06 09:59:42'),
(685, 'ETB', 34, '', 'CEG de Kera DourÃ©', '', '', '', '', '', '0000-00-00', 0, 217, 0, 0, '2012-09-06 10:01:09'),
(686, 'ETB', 22, '', 'CEG de Kiougou', '', '', '', '', '', '0000-00-00', 0, 361, 0, 0, '2012-09-06 10:06:41'),
(687, 'ETB', 22, '', 'CEG de Nobili', '', '', '', '', '', '0000-00-00', 0, 372, 0, 0, '2012-09-06 10:07:10'),
(688, 'ETB', 22, '', 'CEG de Manga', '', '', '', '', '', '0000-00-00', 0, 466, 0, 0, '2012-09-06 10:07:33'),
(689, 'ETB', 22, '', 'CEG de Sidtenga', '', '', '', '', '', '0000-00-00', 0, 250, 0, 0, '2012-09-06 10:08:05'),
(690, 'ETB', 22, '', 'LycÃ©e dÃ©p DE BÃ©rÃ©', '', '', '', '', '', '0000-00-00', 0, 784, 0, 0, '2012-09-06 10:16:51'),
(691, 'ETB', 22, '', 'LycÃ©e dÃ©p de BindÃ©', '', '', '', '', '', '0000-00-00', 0, 647, 0, 0, '2012-09-06 10:18:17'),
(692, 'ETB', 22, '', 'LycÃ©e dÃ©p de Gogo', '', '', '', '', '', '0000-00-00', 0, 979, 0, 0, '2012-09-06 10:18:55'),
(693, 'ETB', 22, '', 'LycÃ©e dÃ©p Kir Soga de Gonboussougou', '', '', '', '', '', '0000-00-00', 0, 837, 0, 0, '2012-09-06 10:24:08'),
(694, 'ETB', 42, '', 'CEG de Bondigui', '', '', '', '', '', '0000-00-00', 0, 365, 0, 0, '2012-09-06 17:46:09'),
(695, 'ETB', 42, '', 'CEG de Bapla', '', '', '', '', '', '0000-00-00', 0, 350, 0, 0, '2012-09-06 17:46:42'),
(696, 'ETB', 28, '', 'CEG de Nasso', '', '', '', '', '', '0000-00-00', 0, 151, 0, 0, '2012-09-07 10:13:31'),
(697, 'ETB', 14, '', 'CEG de Boulsa', '', '', '', '', '', '2012-09-07', 0, 278, 0, 0, '2012-09-07 10:20:31'),
(698, 'ETB', 5, '', 'Lycee DÃ©p. de YÃ©', '', '', '', '', '', '0000-00-00', 0, 886, 0, 0, '2012-09-07 10:34:23'),
(699, 'ETB', 12, '', 'CEG de Tambogo', '', '', '', '', '', '2012-09-14', 0, 275, 0, 0, '2012-09-14 10:44:59'),
(700, 'ETB', 34, '', 'CEG de BangassÃ©', '', '', '', '', '', '2012-09-14', 0, 174, 0, 0, '2012-09-14 10:47:20'),
(701, 'ETB', 45, '', 'CEG de Niogo', '', '', '', '', '', '0000-00-00', 0, 275, 0, 0, '2012-09-14 10:48:55'),
(702, 'ETB', 28, '', 'CEG de Kofila', '', '', '', '', '', '2012-09-14', 0, 109, 0, 0, '2012-09-14 10:50:02');

-- --------------------------------------------------------

--
-- Structure de la table `benefmag`
--

CREATE TABLE IF NOT EXISTS `benefmag` (
  `ID_BENMAG` int(11) NOT NULL AUTO_INCREMENT,
  `ID_BENEF` int(11) NOT NULL,
  `CODE_MAGASIN` varchar(10) NOT NULL,
  `BM_DATEDEBUT` date DEFAULT NULL,
  `BM_DATEFIN` date DEFAULT NULL,
  PRIMARY KEY (`ID_BENMAG`),
  KEY `BENEF_MAG_FK` (`ID_BENEF`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=804 ;

--
-- Contenu de la table `benefmag`
--

INSERT INTO `benefmag` (`ID_BENMAG`, `ID_BENEF`, `CODE_MAGASIN`, `BM_DATEDEBUT`, `BM_DATEFIN`) VALUES
(15, 3, 'MAG 6', '2012-01-01', '0000-00-00'),
(16, 4, 'MAG 6', '2012-01-01', '0000-00-00'),
(17, 5, 'MAG 6', '2012-01-01', '0000-00-00'),
(19, 7, 'MAG 6', '2012-01-01', '0000-00-00'),
(20, 8, 'MAG 6', '2012-01-01', '0000-00-00'),
(22, 10, 'MAG 6', '2012-01-01', '0000-00-00'),
(25, 13, 'MAG 6', '2012-01-01', '0000-00-00'),
(26, 14, 'MAG 6', '2012-01-01', '0000-00-00'),
(27, 15, 'MAG 6', '2012-01-01', '0000-00-00'),
(29, 17, 'MAG 6', '2012-01-01', '0000-00-00'),
(30, 18, 'MAG 6', '2012-01-01', '0000-00-00'),
(31, 19, 'MAG 6', '2012-01-01', '0000-00-00'),
(32, 20, 'MAG 6', '2012-01-01', '0000-00-00'),
(33, 21, 'MAG0', '2012-01-01', NULL),
(34, 22, 'MAG0', '2012-01-01', NULL),
(35, 23, 'MAG0', '2012-01-01', NULL),
(36, 24, 'MAG0', '2012-01-01', NULL),
(37, 25, 'MAG0', '2012-01-01', NULL),
(38, 26, 'MAG0', '2012-01-01', '0000-00-00'),
(39, 27, 'MAG0', '2012-01-01', NULL),
(40, 28, 'MAG0', '2012-01-01', NULL),
(41, 29, 'MAG0', '2012-01-01', NULL),
(42, 30, 'MAG0', '2012-01-01', NULL),
(43, 31, 'MAG0', '2012-01-01', NULL),
(44, 32, 'MAG0', '2012-01-01', NULL),
(45, 33, 'MAG0', '2012-01-01', NULL),
(46, 34, 'MAG0', '2012-01-01', NULL),
(47, 35, 'MAG0', '2012-01-01', NULL),
(48, 36, 'MAG0', '2012-01-01', NULL),
(49, 37, 'MAG0', '2012-01-01', NULL),
(50, 38, 'MAG0', '2012-01-01', NULL),
(51, 39, 'MAG0', '2012-01-01', NULL),
(52, 40, 'MAG0', '2012-01-01', NULL),
(53, 41, 'MAG0', '2012-01-01', NULL),
(54, 42, 'MAG0', '2012-01-01', NULL),
(55, 43, 'MAG0', '2012-01-01', NULL),
(56, 44, 'MAG0', '2012-01-01', NULL),
(57, 45, 'MAG0', '2012-01-01', NULL),
(58, 46, 'MAG0', '2012-01-01', NULL),
(59, 47, 'MAG0', '2012-01-01', NULL),
(60, 48, 'MAG0', '2012-01-01', NULL),
(61, 49, 'MAG0', '2012-01-01', NULL),
(62, 50, 'MAG0', '2012-01-01', NULL),
(63, 51, 'MAG0', '2012-01-01', NULL),
(64, 52, 'MAG0', '2012-01-01', NULL),
(65, 53, 'MAG0', '2012-01-01', NULL),
(66, 54, 'MAG0', '2012-01-01', NULL),
(67, 55, 'MAG0', '2012-01-01', NULL),
(68, 56, 'MAG0', '2012-01-01', NULL),
(69, 57, 'MAG0', '2012-01-01', NULL),
(70, 58, 'MAG0', '2012-01-01', NULL),
(71, 59, 'MAG0', '2012-01-01', NULL),
(72, 60, 'MAG0', '2012-01-01', NULL),
(73, 61, 'MAG0', '2012-01-01', NULL),
(74, 62, 'MAG0', '2012-01-01', NULL),
(75, 63, 'MAG0', '2012-01-01', NULL),
(76, 64, 'MAG0', '2012-01-01', NULL),
(77, 65, 'MAG0', '2012-01-01', NULL),
(78, 66, 'MAG0', '2012-01-01', NULL),
(79, 67, 'MAG0', '2012-01-01', NULL),
(80, 68, 'MAG0', '2012-01-01', NULL),
(81, 69, 'MAG0', '2012-01-01', NULL),
(82, 70, 'MAG0', '2012-01-01', NULL),
(83, 71, 'MAG0', '2012-01-01', NULL),
(84, 72, 'MAG0', '2012-01-01', NULL),
(85, 73, 'MAG0', '2012-01-01', NULL),
(86, 74, 'MAG0', '2012-01-01', NULL),
(87, 75, 'MAG0', '2012-01-01', NULL),
(88, 76, 'MAG0', '2012-01-01', NULL),
(89, 77, 'MAG0', '2012-01-01', NULL),
(90, 78, 'MAG0', '2012-01-01', NULL),
(91, 79, 'MAG0', '2012-01-01', NULL),
(92, 80, 'MAG0', '2012-01-01', NULL),
(93, 81, 'MAG0', '2012-01-01', NULL),
(94, 82, 'MAG0', '2012-01-01', NULL),
(95, 83, 'MAG0', '2012-01-01', NULL),
(96, 84, 'MAG0', '2012-01-01', NULL),
(98, 86, 'MAG0', '2012-01-01', NULL),
(99, 87, 'MAG0', '2012-01-01', NULL),
(100, 88, 'MAG0', '2012-01-01', NULL),
(101, 89, 'MAG0', '2012-01-01', NULL),
(103, 91, 'MAG0', '2012-01-01', NULL),
(106, 94, 'MAG0', '2012-01-01', NULL),
(107, 95, 'MAG0', '2012-01-01', NULL),
(108, 96, 'MAG0', '2012-01-01', NULL),
(109, 97, 'MAG0', '2012-01-01', NULL),
(110, 98, 'MAG0', '2012-01-01', NULL),
(112, 100, 'MAG0', '2012-01-01', NULL),
(113, 101, 'MAG0', '2012-01-01', NULL),
(114, 102, 'MAG0', '2012-01-01', NULL),
(118, 106, 'MAG0', '2012-01-01', NULL),
(119, 107, 'MAG0', '2012-01-01', NULL),
(120, 108, 'MAG0', '2012-01-01', NULL),
(121, 109, 'MAG0', '2012-01-01', NULL),
(122, 110, 'MAG0', '2012-01-01', NULL),
(123, 111, 'MAG0', '2012-01-01', NULL),
(124, 112, 'MAG0', '2012-01-01', NULL),
(125, 113, 'MAG0', '2012-01-01', NULL),
(126, 114, 'MAG0', '2012-01-01', NULL),
(127, 115, 'MAG0', '2012-01-01', NULL),
(128, 116, 'MAG0', '2012-01-01', NULL),
(129, 117, 'MAG0', '2012-01-01', NULL),
(131, 119, 'MAG0', '2012-01-01', NULL),
(133, 121, 'MAG0', '2012-01-01', NULL),
(134, 122, 'MAG0', '2012-01-01', NULL),
(135, 123, 'MAG0', '2012-01-01', NULL),
(136, 124, 'MAG0', '2012-01-01', NULL),
(137, 125, 'MAG0', '2012-01-01', NULL),
(138, 126, 'MAG0', '2012-01-01', NULL),
(139, 127, 'MAG0', '2012-01-01', NULL),
(140, 128, 'MAG0', '2012-01-01', NULL),
(141, 129, 'MAG0', '2012-01-01', NULL),
(142, 130, 'MAG0', '2012-01-01', NULL),
(143, 131, 'MAG0', '2012-01-01', NULL),
(144, 132, 'MAG0', '2012-01-01', NULL),
(145, 133, 'MAG0', '2012-01-01', NULL),
(149, 137, 'MAG0', '2012-01-01', NULL),
(150, 138, 'MAG0', '2012-01-01', NULL),
(151, 139, 'MAG0', '2012-01-01', NULL),
(156, 144, 'MAG0', '2012-01-01', NULL),
(157, 145, 'MAG0', '2012-01-01', NULL),
(158, 146, 'MAG0', '2012-01-01', NULL),
(159, 147, 'MAG0', '2012-01-01', NULL),
(160, 148, 'MAG0', '2012-01-01', NULL),
(161, 149, 'MAG0', '2012-01-01', NULL),
(162, 150, 'MAG0', '2012-01-01', NULL),
(163, 151, 'MAG0', '2012-01-01', NULL),
(164, 152, 'MAG0', '2012-01-01', NULL),
(165, 153, 'MAG0', '2012-01-01', '0000-00-00'),
(166, 154, 'MAG0', '2012-01-01', NULL),
(167, 155, 'MAG0', '2012-01-01', NULL),
(168, 156, 'MAG0', '2012-01-01', NULL),
(169, 157, 'MAG0', '2012-01-01', NULL),
(170, 158, 'MAG0', '2012-01-01', NULL),
(171, 159, 'MAG0', '2012-01-01', NULL),
(172, 160, 'MAG 5', '2012-01-01', '0000-00-00'),
(173, 161, 'MAG 5', '2012-01-01', '0000-00-00'),
(174, 162, 'MAG 5', '2012-01-01', '0000-00-00'),
(175, 163, 'MAG 5', '2012-01-01', '0000-00-00'),
(176, 164, 'MAG 5', '2012-01-01', '0000-00-00'),
(177, 165, 'MAG 5', '2012-01-01', '0000-00-00'),
(178, 166, 'MAG 5', '2012-01-01', '0000-00-00'),
(179, 167, 'MAG 5', '2012-01-01', '0000-00-00'),
(180, 168, 'MAG 5', '2012-01-01', '0000-00-00'),
(181, 169, 'MAG 5', '2012-01-01', '0000-00-00'),
(182, 170, 'MAG 5', '2012-01-01', '0000-00-00'),
(184, 172, 'MAG 5', '2012-01-01', '0000-00-00'),
(185, 173, 'MAG 5', '2012-01-01', '0000-00-00'),
(186, 174, 'MAG 5', '2012-01-01', '0000-00-00'),
(187, 175, 'MAG 5', '2012-01-01', '0000-00-00'),
(188, 176, 'MAG 5', '2012-01-01', '0000-00-00'),
(189, 177, 'MAG 5', '2012-01-01', '0000-00-00'),
(190, 178, 'MAG 5', '2012-01-01', '0000-00-00'),
(191, 179, 'MAG 5', '2012-01-01', '0000-00-00'),
(192, 180, 'MAG 5', '2012-01-01', '0000-00-00'),
(194, 182, 'MAG 5', '2012-01-01', '0000-00-00'),
(195, 183, 'MAG 5', '2012-01-01', '0000-00-00'),
(196, 184, 'MAG 5', '2012-01-01', '0000-00-00'),
(197, 185, 'MAG 5', '2012-01-01', '0000-00-00'),
(198, 186, 'MAG 5', '2012-01-01', '0000-00-00'),
(199, 187, 'MAG 5', '2012-01-01', '0000-00-00'),
(200, 188, 'MAG 5', '2012-01-01', '0000-00-00'),
(201, 189, 'MAG 5', '2012-01-01', '0000-00-00'),
(202, 190, 'MAG 5', '2012-01-01', '0000-00-00'),
(203, 191, 'MAG 5', '2012-01-01', '0000-00-00'),
(204, 192, 'MAG 5', '2012-01-01', '0000-00-00'),
(205, 193, 'MAG 5', '2012-01-01', '0000-00-00'),
(206, 194, 'MAG 5', '2012-01-01', '0000-00-00'),
(207, 195, 'MAG 5', '2012-01-01', '0000-00-00'),
(208, 196, 'MAG 5', '2012-01-01', '0000-00-00'),
(209, 197, 'MAG 5', '2012-01-01', '0000-00-00'),
(211, 199, 'MAG 5', '2012-01-01', '0000-00-00'),
(212, 200, 'MAG 5', '2012-01-01', '0000-00-00'),
(213, 201, 'MAG 5', '2012-01-01', '0000-00-00'),
(214, 202, 'MAG 5', '2012-01-01', '0000-00-00'),
(216, 204, 'MAG 5', '2012-01-01', '0000-00-00'),
(217, 205, 'MAG 5', '2012-01-01', '0000-00-00'),
(219, 207, 'MAG 5', '2012-01-01', '0000-00-00'),
(220, 208, 'MAG 5', '2012-01-01', '0000-00-00'),
(221, 209, 'MAG 5', '2012-01-01', '0000-00-00'),
(222, 210, 'MAG 5', '2012-01-01', '0000-00-00'),
(223, 211, 'MAG 5', '2012-01-01', '0000-00-00'),
(224, 212, 'MAG 5', '2012-01-01', '0000-00-00'),
(225, 213, 'MAG 5', '2012-01-01', '0000-00-00'),
(226, 214, 'MAG 5', '2012-01-01', '0000-00-00'),
(227, 215, 'MAG3', '2012-01-01', NULL),
(228, 216, 'MAG3', '2012-01-01', NULL),
(229, 217, 'MAG3', '2012-01-01', NULL),
(230, 218, 'MAG3', '2012-01-01', NULL),
(231, 219, 'MAG3', '2012-01-01', NULL),
(232, 220, 'MAG3', '2012-01-01', NULL),
(233, 221, 'MAG3', '2012-01-01', NULL),
(234, 222, 'MAG3', '2012-01-01', NULL),
(235, 223, 'MAG3', '2012-01-01', NULL),
(236, 224, 'MAG3', '2012-01-01', NULL),
(237, 225, 'MAG3', '2012-01-01', NULL),
(238, 226, 'MAG3', '2012-01-01', NULL),
(239, 227, 'MAG3', '2012-01-01', NULL),
(240, 228, 'MAG3', '2012-01-01', NULL),
(241, 229, 'MAG3', '2012-01-01', NULL),
(242, 230, 'MAG3', '2012-01-01', NULL),
(243, 231, 'MAG3', '2012-01-01', NULL),
(244, 232, 'MAG3', '2012-01-01', NULL),
(245, 233, 'MAG3', '2012-01-01', NULL),
(246, 234, 'MAG3', '2012-01-01', NULL),
(247, 235, 'MAG3', '2012-01-01', NULL),
(248, 236, 'MAG3', '2012-01-01', NULL),
(250, 238, 'MAG3', '2012-01-01', NULL),
(251, 239, 'MAG3', '2012-01-01', NULL),
(253, 241, 'MAG3', '2012-01-01', NULL),
(254, 242, 'MAG3', '2012-01-01', NULL),
(255, 243, 'MAG3', '2012-01-01', NULL),
(256, 244, 'MAG3', '2012-01-01', NULL),
(257, 245, 'MAG3', '2012-01-01', NULL),
(258, 246, 'MAG3', '2012-01-01', NULL),
(259, 247, 'MAG3', '2012-01-01', NULL),
(260, 248, 'MAG3', '2012-01-01', NULL),
(261, 249, 'MAG3', '2012-01-01', NULL),
(262, 250, 'MAG3', '2012-01-01', NULL),
(263, 251, 'MAG3', '2012-01-01', NULL),
(264, 252, 'MAG3', '2012-01-01', NULL),
(265, 253, 'MAG3', '2012-01-01', NULL),
(266, 254, 'MAG3', '2012-01-01', NULL),
(267, 255, 'MAG3', '2012-01-01', NULL),
(268, 256, 'MAG3', '2012-01-01', NULL),
(269, 257, 'MAG3', '2012-01-01', NULL),
(271, 259, 'MAG3', '2012-01-01', NULL),
(272, 260, 'MAG3', '2012-01-01', NULL),
(273, 261, 'MAG3', '2012-01-01', NULL),
(274, 262, 'MAG3', '2012-01-01', NULL),
(275, 263, 'MAG3', '2012-01-01', NULL),
(276, 264, 'MAG3', '2012-01-01', NULL),
(277, 265, 'MAG3', '2012-01-01', NULL),
(278, 266, 'MAG3', '2012-01-01', NULL),
(279, 267, 'MAG3', '2012-01-01', NULL),
(280, 268, 'MAG3', '2012-01-01', NULL),
(281, 269, 'MAG3', '2012-01-01', NULL),
(282, 270, 'MAG2', '2012-01-01', NULL),
(283, 271, 'MAG2', '2012-01-01', NULL),
(284, 272, 'MAG2', '2012-01-01', NULL),
(285, 273, 'MAG2', '2012-01-01', NULL),
(286, 274, 'MAG2', '2012-01-01', NULL),
(287, 275, 'MAG2', '2012-01-01', NULL),
(288, 276, 'MAG2', '2012-01-01', NULL),
(289, 277, 'MAG2', '2012-01-01', NULL),
(290, 278, 'MAG2', '2012-01-01', NULL),
(291, 279, 'MAG2', '2012-01-01', NULL),
(292, 280, 'MAG2', '2012-01-01', NULL),
(294, 282, 'MAG2', '2012-01-01', NULL),
(295, 283, 'MAG2', '2012-01-01', NULL),
(296, 284, 'MAG2', '2012-01-01', NULL),
(297, 285, 'MAG2', '2012-01-01', NULL),
(298, 286, 'MAG2', '2012-01-01', NULL),
(299, 287, 'MAG2', '2012-01-01', NULL),
(300, 288, 'MAG2', '2012-01-01', NULL),
(301, 289, 'MAG2', '2012-01-01', NULL),
(302, 290, 'MAG2', '2012-01-01', NULL),
(303, 291, 'MAG2', '2012-01-01', NULL),
(304, 292, 'MAG2', '2012-01-01', NULL),
(305, 293, 'MAG2', '2012-01-01', NULL),
(306, 294, 'MAG2', '2012-01-01', NULL),
(307, 295, 'MAG2', '2012-01-01', NULL),
(308, 296, 'MAG2', '2012-01-01', NULL),
(309, 297, 'MAG2', '2012-01-01', NULL),
(310, 298, 'MAG2', '2012-01-01', NULL),
(311, 299, 'MAG2', '2012-01-01', NULL),
(312, 300, 'MAG2', '2012-01-01', NULL),
(313, 301, 'MAG2', '2012-01-01', NULL),
(314, 302, 'MAG2', '2012-01-01', NULL),
(318, 306, 'MAG2', '2012-01-01', NULL),
(320, 308, 'MAG2', '2012-01-01', NULL),
(322, 310, 'MAG2', '2012-01-01', NULL),
(324, 312, 'MAG2', '2012-01-01', NULL),
(326, 314, 'MAG2', '2012-01-01', NULL),
(327, 315, 'MAG2', '2012-01-01', NULL),
(328, 316, 'MAG2', '2012-01-01', NULL),
(329, 317, 'MAG2', '2012-01-01', NULL),
(330, 318, 'MAG2', '2012-01-01', NULL),
(331, 319, 'MAG2', '2012-01-01', NULL),
(332, 320, 'MAG2', '2012-01-01', NULL),
(333, 321, 'MAG2', '2012-01-01', NULL),
(334, 322, 'MAG2', '2012-01-01', NULL),
(335, 323, 'MAG2', '2012-01-01', NULL),
(336, 324, 'MAG2', '2012-01-01', NULL),
(339, 327, 'MAG2', '2012-01-01', NULL),
(340, 328, 'MAG2', '2012-01-01', NULL),
(341, 329, 'MAG2', '2012-01-01', NULL),
(342, 330, 'MAG2', '2012-01-01', NULL),
(343, 331, 'MAG2', '2012-01-01', NULL),
(344, 332, 'MAG2', '2012-01-01', NULL),
(346, 334, 'MAG2', '2012-01-01', NULL),
(347, 335, 'MAG2', '2012-01-01', NULL),
(348, 336, 'MAG2', '2012-01-01', NULL),
(349, 337, 'MAG2', '2012-01-01', NULL),
(351, 339, 'MAG 6', '2012-01-01', '0000-00-00'),
(352, 340, 'MAG 6', '2012-01-01', '0000-00-00'),
(353, 341, 'MAG 6', '2012-01-01', '0000-00-00'),
(354, 342, 'MAG 6', '2012-01-01', '0000-00-00'),
(355, 343, 'MAG 6', '2012-01-01', '0000-00-00'),
(362, 350, 'MAG 6', '2012-01-01', '0000-00-00'),
(363, 351, 'MAG 6', '2012-01-01', '0000-00-00'),
(364, 352, 'MAG 6', '2012-01-01', '0000-00-00'),
(365, 353, 'MAG 6', '2012-01-01', '0000-00-00'),
(366, 354, 'MAG 6', '2012-01-01', '0000-00-00'),
(367, 355, 'MAG 6', '2012-01-01', '0000-00-00'),
(368, 356, 'MAG 6', '2012-01-01', '0000-00-00'),
(369, 357, 'MAG 6', '2012-01-01', '0000-00-00'),
(370, 358, 'MAG 6', '2012-01-01', '0000-00-00'),
(371, 359, 'MAG 6', '2012-01-01', '0000-00-00'),
(372, 360, 'MAG 6', '2012-01-01', '0000-00-00'),
(373, 361, 'MAG 6', '2012-01-01', '0000-00-00'),
(375, 363, 'MAG 6', '2012-01-01', '0000-00-00'),
(376, 364, 'MAG 6', '2012-01-01', '0000-00-00'),
(377, 365, 'MAG 6', '2012-01-01', '0000-00-00'),
(378, 366, 'MAG 6', '2012-01-01', '0000-00-00'),
(379, 367, 'MAG 6', '2012-01-01', '0000-00-00'),
(380, 368, 'MAG 6', '2012-01-01', '0000-00-00'),
(385, 373, 'MAG 6', '2012-01-01', '0000-00-00'),
(386, 374, 'MAG 6', '2012-01-01', '0000-00-00'),
(388, 376, 'MAG1', '2012-01-01', NULL),
(389, 377, 'MAG1', '2012-01-01', NULL),
(390, 378, 'MAG1', '2012-01-01', NULL),
(391, 379, 'MAG1', '2012-01-01', NULL),
(392, 380, 'MAG1', '2012-01-01', NULL),
(393, 381, 'MAG1', '2012-01-01', NULL),
(394, 382, 'MAG1', '2012-01-01', NULL),
(395, 383, 'MAG1', '2012-01-01', NULL),
(396, 384, 'MAG1', '2012-01-01', NULL),
(397, 385, 'MAG1', '2012-01-01', NULL),
(398, 386, 'MAG1', '2012-01-01', NULL),
(399, 387, 'MAG1', '2012-01-01', NULL),
(400, 388, 'MAG1', '2012-01-01', NULL),
(401, 389, 'MAG1', '2012-01-01', NULL),
(402, 390, 'MAG1', '2012-01-01', NULL),
(403, 391, 'MAG1', '2012-01-01', NULL),
(404, 392, 'MAG1', '2012-01-01', NULL),
(405, 393, 'MAG1', '2012-01-01', NULL),
(406, 394, 'MAG1', '2012-01-01', NULL),
(407, 395, 'MAG1', '2012-01-01', NULL),
(408, 396, 'MAG1', '2012-01-01', NULL),
(409, 397, 'MAG1', '2012-01-01', NULL),
(410, 398, 'MAG1', '2012-01-01', NULL),
(411, 399, 'MAG1', '2012-01-01', NULL),
(412, 400, 'MAG1', '2012-01-01', NULL),
(413, 401, 'MAG1', '2012-01-01', NULL),
(415, 403, 'MAG1', '2012-01-01', NULL),
(416, 404, 'MAG1', '2012-01-01', NULL),
(417, 405, 'MAG1', '2012-01-01', NULL),
(418, 406, 'MAG1', '2012-01-01', NULL),
(419, 407, 'MAG1', '2012-01-01', NULL),
(420, 408, 'MAG1', '2012-01-01', NULL),
(421, 409, 'MAG1', '2012-01-01', NULL),
(422, 410, 'MAG1', '2012-01-01', NULL),
(423, 411, 'MAG1', '2012-01-01', NULL),
(424, 412, 'MAG1', '2012-01-01', NULL),
(425, 413, 'MAG1', '2012-01-01', NULL),
(426, 414, 'MAG1', '2012-01-01', NULL),
(427, 415, 'MAG1', '2012-01-01', NULL),
(428, 416, 'MAG1', '2012-01-01', NULL),
(429, 417, 'MAG1', '2012-01-01', NULL),
(432, 420, 'MAG1', '2012-01-01', NULL),
(433, 421, 'MAG1', '2012-01-01', NULL),
(434, 422, 'MAG1', '2012-01-01', NULL),
(435, 423, 'MAG1', '2012-01-01', NULL),
(436, 424, 'MAG1', '2012-01-01', NULL),
(437, 425, 'MAG1', '2012-01-01', NULL),
(438, 426, 'MAG1', '2012-01-01', NULL),
(439, 427, 'MAG1', '2012-01-01', NULL),
(440, 428, 'MAG1', '2012-01-01', NULL),
(441, 429, 'MAG1', '2012-01-01', NULL),
(442, 430, 'MAG1', '2012-01-01', NULL),
(443, 431, 'MAG1', '2012-01-01', NULL),
(444, 432, 'MAG1', '2012-01-01', NULL),
(445, 433, 'MAG 6', '2012-01-01', '0000-00-00'),
(454, 442, 'MAG 6', '2012-01-01', '0000-00-00'),
(455, 443, 'MAG 6', '2012-01-01', '0000-00-00'),
(456, 444, 'MAG 6', '2012-01-01', '0000-00-00'),
(457, 445, 'MAG 6', '2012-01-01', '0000-00-00'),
(466, 454, 'MAG 6', '2012-01-01', '0000-00-00'),
(467, 455, 'MAG 6', '2012-01-01', '0000-00-00'),
(468, 456, 'MAG1', '2012-01-01', NULL),
(469, 457, 'MAG1', '2012-01-01', NULL),
(470, 458, 'MAG1', '2012-01-01', NULL),
(472, 460, 'MAG1', '2012-01-01', NULL),
(473, 461, 'MAG1', '2012-01-01', NULL),
(474, 462, 'MAG1', '2012-01-01', NULL),
(475, 463, 'MAG1', '2012-01-01', NULL),
(476, 464, 'MAG1', '2012-01-01', NULL),
(477, 465, 'MAG1', '2012-01-01', NULL),
(478, 466, 'MAG1', '2012-01-01', NULL),
(479, 467, 'MAG1', '2012-01-01', NULL),
(480, 468, 'MAG1', '2012-01-01', NULL),
(481, 469, 'MAG1', '2012-01-01', NULL),
(484, 472, 'MAG1', '2012-01-01', NULL),
(485, 473, 'MAG1', '2012-01-01', NULL),
(486, 474, 'MAG1', '2012-01-01', NULL),
(487, 475, 'MAG1', '2012-01-01', NULL),
(488, 476, 'MAG1', '2012-01-01', NULL),
(489, 477, 'MAG1', '2012-01-01', NULL),
(490, 478, 'MAG1', '2012-01-01', NULL),
(491, 479, 'MAG4', '2012-01-01', NULL),
(492, 480, 'MAG4', '2012-01-01', NULL),
(493, 481, 'MAG4', '2012-01-01', NULL),
(494, 482, 'MAG4', '2012-01-01', NULL),
(495, 483, 'MAG4', '2012-01-01', NULL),
(496, 484, 'MAG4', '2012-01-01', NULL),
(497, 485, 'MAG4', '2012-01-01', NULL),
(498, 486, 'MAG4', '2012-01-01', NULL),
(499, 487, 'MAG4', '2012-01-01', NULL),
(500, 488, 'MAG4', '2012-01-01', NULL),
(501, 489, 'MAG4', '2012-01-01', NULL),
(502, 490, 'MAG4', '2012-01-01', NULL),
(503, 491, 'MAG4', '2012-01-01', NULL),
(505, 493, 'MAG4', '2012-01-01', NULL),
(506, 494, 'MAG4', '2012-01-01', NULL),
(507, 495, 'MAG4', '2012-01-01', NULL),
(508, 496, 'MAG4', '2012-01-01', NULL),
(509, 497, 'MAG4', '2012-01-01', NULL),
(510, 498, 'MAG4', '2012-01-01', NULL),
(511, 499, 'MAG4', '2012-01-01', NULL),
(512, 500, 'MAG4', '2012-01-01', NULL),
(513, 501, 'MAG4', '2012-01-01', NULL),
(514, 502, 'MAG4', '2012-01-01', NULL),
(515, 503, 'MAG4', '2012-01-01', NULL),
(517, 505, 'MAG4', '2012-01-01', NULL),
(518, 506, 'MAG4', '2012-01-01', NULL),
(520, 508, 'MAG4', '2012-01-01', NULL),
(521, 509, 'MAG4', '2012-01-01', NULL),
(523, 511, 'MAG4', '2012-01-01', NULL),
(524, 512, 'MAG4', '2012-01-01', NULL),
(525, 513, 'MAG4', '2012-01-01', NULL),
(526, 514, 'MAG4', '2012-01-01', NULL),
(527, 231, 'MAG 3', '2012-09-14', '0000-00-00'),
(529, 367, 'MAG 6', '2012-09-14', '0000-00-00'),
(531, 567, 'MAG0', '2012-09-17', '0000-00-00'),
(532, 629, 'MAG0', '2012-09-17', '0000-00-00'),
(533, 565, 'MAG0', '2012-09-17', '0000-00-00'),
(534, 566, 'MAG0', '2012-09-17', '0000-00-00'),
(535, 568, 'MAG0', '2012-09-17', '0000-00-00'),
(536, 569, 'MAG0', '2012-09-17', '0000-00-00'),
(537, 570, 'MAG0', '2012-09-17', '0000-00-00'),
(538, 571, 'MAG0', '2012-09-17', '0000-00-00'),
(539, 535, 'MAG 6', '2012-09-17', '0000-00-00'),
(540, 547, 'MAG 6', '2012-09-17', '0000-00-00'),
(541, 553, 'MAG 6', '2012-09-17', '0000-00-00'),
(542, 552, 'MAG 6', '2012-09-17', '0000-00-00'),
(543, 698, 'MAG 6', '2012-09-17', '0000-00-00'),
(544, 550, 'MAG 6', '2012-09-17', '0000-00-00'),
(545, 649, 'MAG0', '2012-09-17', '0000-00-00'),
(546, 616, 'MAG0', '2012-09-17', '0000-00-00'),
(547, 617, 'MAG0', '2012-09-17', '0000-00-00'),
(548, 652, 'MAG0', '2012-09-17', '0000-00-00'),
(549, 650, 'MAG0', '2012-09-17', '0000-00-00'),
(550, 615, 'MAG0', '2012-09-17', '0000-00-00'),
(551, 638, 'MAG0', '2012-09-17', '0000-00-00'),
(552, 648, 'MAG0', '2012-09-17', '0000-00-00'),
(553, 610, 'MAG0', '2012-09-17', '0000-00-00'),
(554, 603, 'MAG0', '2012-09-17', '0000-00-00'),
(555, 604, 'MAG0', '2012-09-17', '0000-00-00'),
(556, 613, 'MAG0', '2012-09-17', '0000-00-00'),
(557, 600, 'MAG0', '2012-09-17', '0000-00-00'),
(558, 680, 'MAG0', '2012-09-17', '0000-00-00'),
(559, 599, 'MAG0', '2012-09-17', '0000-00-00'),
(560, 612, 'MAG0', '2012-09-18', '0000-00-00'),
(561, 607, 'MAG0', '2012-09-18', '0000-00-00'),
(562, 681, 'MAG0', '2012-09-18', '0000-00-00'),
(563, 606, 'MAG0', '2012-09-18', '0000-00-00'),
(564, 614, 'MAG0', '2012-09-18', '0000-00-00'),
(565, 602, 'MAG0', '2012-09-18', '0000-00-00'),
(566, 605, 'MAG0', '2012-09-18', '0000-00-00'),
(567, 691, 'MAG0', '2012-09-18', '0000-00-00'),
(568, 693, 'MAG0', '2012-09-18', '0000-00-00'),
(569, 690, 'MAG0', '2012-09-18', '0000-00-00'),
(570, 591, 'MAG0', '2012-09-18', '0000-00-00'),
(571, 692, 'MAG0', '2012-09-18', '0000-00-00'),
(572, 587, 'MAG0', '2012-09-18', '0000-00-00'),
(573, 585, 'MAG0', '2012-09-18', '0000-00-00'),
(574, 590, 'MAG0', '2012-09-18', '0000-00-00'),
(575, 588, 'MAG0', '2012-09-18', '0000-00-00'),
(576, 642, 'MAG0', '2012-09-18', '0000-00-00'),
(577, 688, 'MAG0', '2012-09-18', '0000-00-00'),
(578, 687, 'MAG0', '2012-09-18', '0000-00-00'),
(579, 686, 'MAG0', '2012-09-18', '0000-00-00'),
(580, 689, 'MAG0', '2012-09-18', '0000-00-00'),
(581, 575, 'MAG 5', '2012-09-18', '0000-00-00'),
(582, 666, 'MAG 5', '2012-09-18', '0000-00-00'),
(583, 697, 'MAG 5', '2012-09-18', '0000-00-00'),
(584, 572, 'MAG 5', '2012-09-18', '0000-00-00'),
(585, 668, 'MAG 5', '2012-09-18', '0000-00-00'),
(586, 667, 'MAG 5', '2012-09-18', '0000-00-00'),
(587, 669, 'MAG 5', '2012-09-18', '0000-00-00'),
(589, 571, 'MAG0', '2012-09-18', '0000-00-00'),
(591, 569, 'MAG0', '2012-09-18', '0000-00-00'),
(592, 665, 'MAG 5', '2012-09-18', '0000-00-00'),
(593, 574, 'MAG 5', '2012-09-18', '0000-00-00'),
(594, 573, 'MAG 5', '2012-09-18', '0000-00-00'),
(595, 679, 'MAG0', '2012-09-18', '0000-00-00'),
(596, 644, 'MAG0', '2012-09-18', '0000-00-00'),
(597, 589, 'MAG0', '2012-09-18', '0000-00-00'),
(598, 643, 'MAG0', '2012-09-18', '0000-00-00'),
(599, 586, 'MAG0', '2012-09-18', '0000-00-00'),
(600, 637, 'MAG0', '2012-09-18', '0000-00-00'),
(601, 651, 'MAG0', '2012-09-18', '0000-00-00'),
(602, 671, 'MAG 5', '2012-09-18', '0000-00-00'),
(603, 684, 'MAG 3', '2012-09-18', '0000-00-00'),
(604, 656, 'MAG 3', '2012-09-18', '0000-00-00'),
(605, 700, 'MAG 3', '2012-09-18', '0000-00-00'),
(607, 661, 'MAG 3', '2012-09-18', '0000-00-00'),
(608, 682, 'MAG 3', '2012-09-18', '0000-00-00'),
(609, 678, 'MAG 3', '2012-09-18', '0000-00-00'),
(610, 685, 'MAG 3', '2012-09-18', '0000-00-00'),
(611, 672, 'MAG 3', '2012-09-18', '0000-00-00'),
(612, 175, 'MAG 5', '2012-09-18', '0000-00-00'),
(613, 653, 'MAG 3', '2012-09-18', '0000-00-00'),
(614, 673, 'MAG 3', '2012-09-18', '0000-00-00'),
(615, 215, 'MAG 3', '2012-09-18', '0000-00-00'),
(616, 216, 'MAG 3', '2012-09-18', '0000-00-00'),
(617, 217, 'MAG 3', '2012-09-18', '0000-00-00'),
(618, 218, 'MAG 3', '2012-09-18', '0000-00-00'),
(619, 219, 'MAG 3', '2012-09-18', '0000-00-00'),
(620, 220, 'MAG 3', '2012-09-18', '0000-00-00'),
(621, 221, 'MAG 3', '2012-09-18', '0000-00-00'),
(622, 659, 'MAG 3', '2012-09-18', '0000-00-00'),
(623, 222, 'MAG 3', '2012-09-18', '0000-00-00'),
(624, 223, 'MAG 3', '2012-09-18', '0000-00-00'),
(625, 224, 'MAG 3', '2012-09-18', '0000-00-00'),
(626, 225, 'MAG 3', '2012-09-18', '0000-00-00'),
(627, 226, 'MAG 3', '2012-09-18', '0000-00-00'),
(628, 235, 'MAG 3', '2012-09-18', '0000-00-00'),
(629, 229, 'MAG 3', '2012-09-18', '0000-00-00'),
(630, 230, 'MAG 3', '2012-09-18', '0000-00-00'),
(632, 232, 'MAG 3', '2012-09-18', '0000-00-00'),
(633, 233, 'MAG 3', '2012-09-18', '0000-00-00'),
(634, 234, 'MAG 3', '2012-09-18', '0000-00-00'),
(635, 235, 'MAG 3', '2012-09-18', '0000-00-00'),
(636, 236, 'MAG 3', '2012-09-18', '0000-00-00'),
(637, 238, 'MAG 3', '2012-09-18', '0000-00-00'),
(638, 239, 'MAG 3', '2012-09-18', '0000-00-00'),
(639, 241, 'MAG 3', '2012-09-18', '0000-00-00'),
(640, 242, 'MAG 3', '2012-09-18', '0000-00-00'),
(641, 243, 'MAG 3', '2012-09-18', '0000-00-00'),
(642, 244, 'MAG 3', '2012-09-18', '0000-00-00'),
(643, 640, 'MAG 3', '2012-09-18', '0000-00-00'),
(644, 641, 'MAG 3', '2012-09-18', '0000-00-00'),
(645, 245, 'MAG 3', '2012-09-18', '0000-00-00'),
(646, 246, 'MAG 3', '2012-09-18', '0000-00-00'),
(647, 247, 'MAG 3', '2012-09-18', '0000-00-00'),
(648, 248, 'MAG 3', '2012-09-18', '0000-00-00'),
(649, 249, 'MAG 3', '2012-09-18', '0000-00-00'),
(650, 250, 'MAG 3', '2012-09-18', '0000-00-00'),
(651, 251, 'MAG 3', '2012-09-18', '0000-00-00'),
(652, 252, 'MAG 3', '2012-09-18', '0000-00-00'),
(653, 253, 'MAG 3', '2012-09-18', '0000-00-00'),
(654, 254, 'MAG 3', '2012-09-18', '0000-00-00'),
(655, 255, 'MAG 3', '2012-09-18', '0000-00-00'),
(656, 256, 'MAG 3', '2012-09-18', '0000-00-00'),
(657, 257, 'MAG 3', '2012-09-18', '0000-00-00'),
(658, 675, 'MAG 3', '2012-09-18', '0000-00-00'),
(659, 259, 'MAG 3', '2012-09-18', '0000-00-00'),
(660, 260, 'MAG 3', '2012-09-18', '0000-00-00'),
(661, 261, 'MAG 3', '2012-09-18', '0000-00-00'),
(662, 262, 'MAG 3', '2012-09-18', '0000-00-00'),
(663, 655, 'MAG 3', '2012-09-18', '0000-00-00'),
(664, 674, 'MAG 3', '2012-09-18', '0000-00-00'),
(665, 658, 'MAG 3', '2012-09-18', '0000-00-00'),
(666, 662, 'MAG 3', '2012-09-18', '0000-00-00'),
(667, 676, 'MAG 3', '2012-09-18', '0000-00-00'),
(668, 677, 'MAG 3', '2012-09-18', '0000-00-00'),
(669, 660, 'MAG 3', '2012-09-18', '0000-00-00'),
(670, 657, 'MAG 3', '2012-09-18', '0000-00-00'),
(671, 263, 'MAG 3', '2012-09-18', '0000-00-00'),
(672, 265, 'MAG 3', '2012-09-18', '0000-00-00'),
(673, 266, 'MAG 3', '2012-09-18', '0000-00-00'),
(674, 267, 'MAG 3', '2012-09-18', '0000-00-00'),
(675, 268, 'MAG 3', '2012-09-18', '0000-00-00'),
(676, 269, 'MAG 3', '2012-09-18', '0000-00-00'),
(677, 596, 'MAG2', '2012-09-18', '0000-00-00'),
(678, 630, 'MAG2', '2012-09-18', '0000-00-00'),
(679, 598, 'MAG2', '2012-09-18', '0000-00-00'),
(680, 597, 'MAG2', '2012-09-18', '0000-00-00'),
(681, 699, 'MAG2', '2012-09-18', '0000-00-00'),
(682, 634, 'MAG2', '2012-09-18', '0000-00-00'),
(683, 291, 'MAG2', '2012-09-18', '0000-00-00'),
(684, 619, 'MAG2', '2012-09-18', '0000-00-00'),
(685, 620, 'MAG2', '2012-09-18', '0000-00-00'),
(686, 623, 'MAG2', '2012-09-18', '0000-00-00'),
(687, 618, 'MAG2', '2012-09-18', '0000-00-00'),
(688, 622, 'MAG2', '2012-09-18', '0000-00-00'),
(689, 624, 'MAG2', '2012-09-18', '0000-00-00'),
(690, 632, 'MAG2', '2012-09-18', '0000-00-00'),
(691, 631, 'MAG2', '2012-09-18', '0000-00-00'),
(692, 625, 'MAG2', '2012-09-18', '0000-00-00'),
(693, 594, 'MAG2', '2012-09-18', '0000-00-00'),
(694, 595, 'MAG2', '2012-09-18', '0000-00-00'),
(695, 633, 'MAG2', '2012-09-18', '0000-00-00'),
(696, 635, 'MAG2', '2012-09-18', '0000-00-00'),
(697, 621, 'MAG2', '2012-09-18', '0000-00-00'),
(699, 538, 'MAG 6', '2012-09-19', '0000-00-00'),
(700, 534, 'MAG 6', '2012-09-19', '0000-00-00'),
(701, 533, 'MAG 6', '2012-09-19', '0000-00-00'),
(702, 536, 'MAG 6', '2012-09-19', '0000-00-00'),
(703, 584, 'MAG 6', '2012-09-19', '0000-00-00'),
(704, 583, 'MAG 6', '2012-09-19', '0000-00-00'),
(705, 576, 'MAG 6', '2012-09-19', '0000-00-00'),
(706, 582, 'MAG 6', '2012-09-19', '0000-00-00'),
(707, 581, 'MAG 6', '2012-09-19', '0000-00-00'),
(708, 579, 'MAG 6', '2012-09-19', '0000-00-00'),
(709, 580, 'MAG 6', '2012-09-19', '0000-00-00'),
(710, 577, 'MAG 6', '2012-09-19', '0000-00-00'),
(711, 543, 'MAG 6', '2012-09-19', '0000-00-00'),
(712, 540, 'MAG 6', '2012-09-19', '0000-00-00'),
(713, 541, 'MAG 6', '2012-09-19', '0000-00-00'),
(714, 539, 'MAG 6', '2012-09-19', '0000-00-00'),
(715, 578, 'MAG 6', '2012-09-19', '0000-00-00'),
(716, 556, 'MAG 6', '2012-09-19', '0000-00-00'),
(717, 557, 'MAG 6', '2012-09-19', '0000-00-00'),
(718, 555, 'MAG 6', '2012-09-19', '0000-00-00'),
(719, 554, 'MAG 6', '2012-09-19', '0000-00-00'),
(720, 532, 'MAG 6', '2012-09-19', '0000-00-00'),
(721, 537, 'MAG 6', '2012-09-19', '0000-00-00'),
(722, 542, 'MAG 6', '2012-09-19', '0000-00-00'),
(723, 551, 'MAG 6', '2012-09-19', '0000-00-00'),
(724, 548, 'MAG 6', '2012-09-19', '0000-00-00'),
(725, 549, 'MAG 6', '2012-09-19', '0000-00-00'),
(726, 546, 'MAG 6', '2012-09-19', '0000-00-00'),
(727, 520, 'MAG1', '2012-09-19', '0000-00-00'),
(728, 531, 'MAG1', '2012-09-19', '0000-00-00'),
(729, 696, 'MAG1', '2012-09-19', '0000-00-00'),
(730, 519, 'MAG1', '2012-09-19', '0000-00-00'),
(731, 626, 'MAG1', '2012-09-19', '0000-00-00'),
(732, 702, 'MAG1', '2012-09-19', '0000-00-00'),
(734, 518, 'MAG1', '2012-09-19', '0000-00-00'),
(735, 517, 'MAG1', '2012-09-19', '0000-00-00'),
(736, 523, 'MAG1', '2012-09-19', '0000-00-00'),
(737, 525, 'MAG1', '2012-09-19', '0000-00-00'),
(738, 524, 'MAG1', '2012-09-19', '0000-00-00'),
(739, 530, 'MAG1', '2012-09-19', '0000-00-00'),
(740, 526, 'MAG1', '2012-09-19', '0000-00-00'),
(741, 529, 'MAG1', '2012-09-19', '0000-00-00'),
(742, 528, 'MAG1', '2012-09-19', '0000-00-00'),
(743, 527, 'MAG1', '2012-09-19', '0000-00-00'),
(744, 563, 'MAG1', '2012-09-19', '0000-00-00'),
(745, 462, 'MAG1', '2012-09-19', '0000-00-00'),
(746, 561, 'MAG1', '2012-09-19', '0000-00-00'),
(747, 560, 'MAG1', '2012-09-19', '0000-00-00'),
(748, 564, 'MAG1', '2012-09-19', '0000-00-00'),
(749, 639, 'MAG1', '2012-09-19', '0000-00-00'),
(750, 472, 'MAG1', '2012-09-19', '0000-00-00'),
(751, 562, 'MAG1', '2012-09-19', '0000-00-00'),
(752, 521, 'MAG1', '2012-09-19', '0000-00-00'),
(753, 522, 'MAG1', '2012-09-19', '0000-00-00'),
(754, 628, 'MAG 4', '2012-09-19', '0000-00-00'),
(755, 479, 'MAG 4', '2012-09-19', '0000-00-00'),
(756, 480, 'MAG 4', '2012-09-19', '0000-00-00'),
(757, 481, 'MAG 4', '2012-09-19', '0000-00-00'),
(758, 482, 'MAG 4', '2012-09-19', '0000-00-00'),
(759, 483, 'MAG 4', '2012-09-19', '0000-00-00'),
(760, 484, 'MAG 4', '2012-09-19', '0000-00-00'),
(761, 485, 'MAG 4', '2012-09-19', '0000-00-00'),
(762, 486, 'MAG 4', '2012-09-19', '0000-00-00'),
(763, 487, 'MAG 4', '2012-09-19', '0000-00-00'),
(764, 488, 'MAG 4', '2012-09-19', '0000-00-00'),
(765, 489, 'MAG 4', '2012-09-19', '0000-00-00'),
(766, 490, 'MAG 4', '2012-09-19', '0000-00-00'),
(767, 491, 'MAG 4', '2012-09-19', '0000-00-00'),
(768, 695, 'MAG 4', '2012-09-20', '0000-00-00'),
(769, 493, 'MAG 4', '2012-09-20', '0000-00-00'),
(770, 494, 'MAG 4', '2012-09-20', '0000-00-00'),
(771, 495, 'MAG 4', '2012-09-20', '0000-00-00'),
(772, 496, 'MAG 4', '2012-09-20', '0000-00-00'),
(773, 497, 'MAG 4', '2012-09-20', '0000-00-00'),
(774, 663, 'MAG 4', '2012-09-20', '0000-00-00'),
(775, 498, 'MAG 4', '2012-09-20', '0000-00-00'),
(776, 499, 'MAG 4', '2012-09-20', '0000-00-00'),
(777, 664, 'MAG 4', '2012-09-20', '0000-00-00'),
(778, 514, 'MAG 4', '2012-09-20', '0000-00-00'),
(779, 501, 'MAG 4', '2012-09-20', '0000-00-00'),
(780, 502, 'MAG 4', '2012-09-20', '0000-00-00'),
(781, 503, 'MAG 4', '2012-09-20', '0000-00-00'),
(782, 646, 'MAG 4', '2012-09-20', '0000-00-00'),
(783, 505, 'MAG 4', '2012-09-20', '0000-00-00'),
(784, 506, 'MAG 4', '2012-09-20', '0000-00-00'),
(785, 694, 'MAG 4', '2012-09-20', '0000-00-00'),
(786, 508, 'MAG 4', '2012-09-20', '0000-00-00'),
(787, 509, 'MAG 4', '2012-09-20', '0000-00-00'),
(788, 645, 'MAG 4', '2012-09-20', '0000-00-00'),
(789, 511, 'MAG 4', '2012-09-20', '0000-00-00'),
(790, 512, 'MAG 4', '2012-09-20', '0000-00-00'),
(791, 513, 'MAG 4', '2012-09-20', '0000-00-00'),
(792, 500, 'MAG 4', '2012-09-20', '0000-00-00'),
(793, 701, 'MAG 4', '2012-09-20', '0000-00-00'),
(794, 627, 'MAG 4', '2012-09-20', '0000-00-00'),
(795, 608, 'MAG0', '2012-09-21', '0000-00-00'),
(796, 611, 'MAG0', '2012-09-21', '0000-00-00'),
(797, 264, 'MAG 3', '2012-10-01', '0000-00-00'),
(798, 656, 'MAG 3', '2012-10-01', '0000-00-00'),
(799, 654, 'MAG 3', '2012-10-01', '0000-00-00'),
(800, 661, 'MAG 3', '2012-10-01', '0000-00-00'),
(801, 678, 'MAG 3', '2012-10-01', '0000-00-00'),
(802, 673, 'MAG 3', '2012-10-01', '0000-00-00'),
(803, 653, 'MAG 3', '2012-10-01', '0000-00-00');

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE IF NOT EXISTS `categorie` (
  `CODE_CATEGORIE` varchar(10) NOT NULL,
  `CAT_LIBELLE` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`CODE_CATEGORIE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `categorie`
--

INSERT INTO `categorie` (`CODE_CATEGORIE`, `CAT_LIBELLE`) VALUES
('AU', 'Autres'),
('PA', 'Produits alimentaires'),
('UC', 'Utensiles de cuisine');

-- --------------------------------------------------------

--
-- Structure de la table `cde_prd`
--

CREATE TABLE IF NOT EXISTS `cde_prd` (
  `ID_COMMANDE` int(11) NOT NULL,
  `ID_CONDIT` int(11) NOT NULL,
  `CDEPRD_QUANTITE` float DEFAULT NULL,
  `CDEPRD_PRIX` decimal(8,0) DEFAULT NULL,
  `CDE_UNITE` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`ID_COMMANDE`,`ID_CONDIT`),
  KEY `CDE_PRD_FK` (`ID_COMMANDE`),
  KEY `CDE_PRD2_FK` (`ID_CONDIT`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `cde_prd`
--

INSERT INTO `cde_prd` (`ID_COMMANDE`, `ID_CONDIT`, `CDEPRD_QUANTITE`, `CDEPRD_PRIX`, `CDE_UNITE`) VALUES
(1, 6, 180, 0, 'sac'),
(1, 12, 1400, 0, 'cart'),
(1, 13, 3500, 0, 'sht'),
(1, 15, 280, 0, 'sac'),
(1, 16, 4700, 0, 'bt'),
(1, 17, 140, 0, 'cart'),
(2, 9, 1250, 0, 'bid'),
(2, 10, 7000, 0, 'cart'),
(2, 11, 800, 0, 'cart');

-- --------------------------------------------------------

--
-- Structure de la table `centre`
--

CREATE TABLE IF NOT EXISTS `centre` (
  `IDCENTRE` int(11) NOT NULL AUTO_INCREMENT,
  `ID_EXERCICE` int(11) NOT NULL,
  `LIBCENTRE` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`IDCENTRE`),
  KEY `EXE_CENTRE_FK` (`ID_EXERCICE`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `centre`
--

INSERT INTO `centre` (`IDCENTRE`, `ID_EXERCICE`, `LIBCENTRE`) VALUES
(2, 2011, 'Centres d''examen 2011-2012'),
(3, 2012, 'programmation bac');

-- --------------------------------------------------------

--
-- Structure de la table `centreexam`
--

CREATE TABLE IF NOT EXISTS `centreexam` (
  `ID_BENEF` int(11) NOT NULL,
  `IDCENTRE` int(11) NOT NULL,
  `CTRENOM` varchar(200) DEFAULT NULL,
  `CTREEFFECTIF` int(11) DEFAULT NULL,
  `CTRFILLE` int(11) NOT NULL,
  `CTRGARCON` int(11) NOT NULL,
  PRIMARY KEY (`ID_BENEF`,`IDCENTRE`),
  KEY `CENTREEXAM_FK` (`ID_BENEF`),
  KEY `CENTREEXAM2_FK` (`IDCENTRE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `centreexam`
--

INSERT INTO `centreexam` (`ID_BENEF`, `IDCENTRE`, `CTRENOM`, `CTREEFFECTIF`, `CTRFILLE`, `CTRGARCON`) VALUES
(3, 2, 'BOROMO', 99, 0, 0),
(7, 2, 'TOUGAN', 259, 0, 0),
(33, 2, 'OUAGADOUGOU', 34, 0, 0),
(44, 2, 'TANGHIN DASSOURI', 24, 0, 0),
(55, 2, 'ZINIARE', 50, 0, 0),
(67, 2, 'ZORGHO', 109, 0, 0),
(79, 2, 'KOUDOUGOU', 239, 0, 0),
(108, 2, 'REO', 145, 0, 0),
(121, 2, 'LEO', 31, 0, 0),
(133, 2, 'MANGA', 70, 0, 0),
(145, 2, 'PO', 99, 0, 0),
(152, 2, 'KOMBISSIRI', 57, 0, 0),
(154, 2, 'SAPONE', 98, 0, 0),
(160, 2, 'KAYA', 381, 0, 0),
(186, 2, 'KONGOUSSI', 75, 0, 0),
(202, 2, 'DORI', 77, 0, 0),
(215, 2, 'OUAHIGOUYA', 171, 0, 0),
(251, 2, 'YAKO', 188, 0, 0),
(263, 2, 'DJIBO', 10, 0, 0),
(270, 2, 'TENKODOGO', 243, 0, 0),
(286, 2, 'KOUPELA', 262, 0, 0),
(306, 2, 'BOGANDE', 82, 0, 0),
(314, 2, 'FADA N''GOURMA', 125, 0, 0),
(327, 2, 'DIAPAGA', 96, 0, 0),
(350, 2, 'NOUNA', 210, 0, 0),
(364, 2, 'DEDOUGOU', 53, 0, 0),
(377, 2, 'BOBO-DIOULASSO', 156, 0, 0),
(405, 2, 'ORODARA', 40, 0, 0),
(425, 2, 'HOUNDE', 67, 0, 0),
(456, 2, 'BANFORA', 153, 0, 0),
(479, 2, 'DIEBOUGOU', 188, 0, 0),
(498, 2, 'GAOUA', 127, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `cnd_autreliv`
--

CREATE TABLE IF NOT EXISTS `cnd_autreliv` (
  `ID_CONDIT` int(11) NOT NULL,
  `ID_AUTRELIVR` int(11) NOT NULL,
  `CNDAUL_QTE` float DEFAULT NULL,
  `CNDAUL_UNITE` varchar(10) DEFAULT NULL,
  `CNDAUL_MAG` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`ID_CONDIT`,`ID_AUTRELIVR`),
  KEY `CND_AUTRELIV_FK` (`ID_CONDIT`),
  KEY `CND_AUTRELIV2_FK` (`ID_AUTRELIVR`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `cnd_invt`
--

CREATE TABLE IF NOT EXISTS `cnd_invt` (
  `ID_CONDIT` int(11) NOT NULL,
  `ID_INVENTAIRE` int(11) NOT NULL,
  `STOCK_PHYSIQUE` float DEFAULT NULL,
  `STOCK_THEO` float DEFAULT NULL,
  `ECART` float DEFAULT NULL,
  `RAISON_ECART` text,
  `INV_UNITE` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`ID_CONDIT`,`ID_INVENTAIRE`),
  KEY `CND_INVT_FK` (`ID_CONDIT`),
  KEY `CND_INVT2_FK` (`ID_INVENTAIRE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE IF NOT EXISTS `commande` (
  `ID_COMMANDE` int(11) NOT NULL AUTO_INCREMENT,
  `CODE_MAGASIN` varchar(10) NOT NULL,
  `ID_EXERCICE` int(11) NOT NULL,
  `ID_FORNISSEUR` int(11) NOT NULL,
  `CODE_COMMANDE` varchar(250) DEFAULT NULL,
  `CDE_LIBELLE` varchar(200) DEFAULT NULL,
  `CDE_DATE` date DEFAULT NULL,
  `CDE_STATUT` tinyint(4) DEFAULT NULL,
  `CDE_DATEVALID` datetime DEFAULT NULL,
  PRIMARY KEY (`ID_COMMANDE`),
  KEY `EX_CDE_FK` (`ID_EXERCICE`),
  KEY `FOUR_CDE_FK` (`ID_FORNISSEUR`),
  KEY `MAGCDE_FK` (`CODE_MAGASIN`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `commande`
--

INSERT INTO `commande` (`ID_COMMANDE`, `CODE_MAGASIN`, `ID_EXERCICE`, `ID_FORNISSEUR`, `CODE_COMMANDE`, `CDE_LIBELLE`, `CDE_DATE`, `CDE_STATUT`, `CDE_DATEVALID`) VALUES
(1, 'MAG0', 2012, 2, '24/00/01/02/00/2012/00053/MESS/SG/DAF', 'ACQUISITION DES VIVRES AU PROFIT DES CANTINE SCOLAIRES DU SECONDAIRE', '2012-03-05', 1, NULL),
(2, 'MAG0', 2012, 3, '24/00/01/02/00/2012/00015/MESS/SG/DAF', 'ACQUISITION DES VIVRES AU PROFIT DES CANTINES SCOLAIRES DU SECONDAIRE', '2012-03-05', 1, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `compte`
--

CREATE TABLE IF NOT EXISTS `compte` (
  `LOGIN` varchar(10) NOT NULL,
  `NUM_MLLE` varchar(10) NOT NULL,
  `IDPROFIL` int(11) NOT NULL,
  `PWD` varchar(200) DEFAULT NULL,
  `ACTIVATED` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`LOGIN`),
  KEY `PERS_CPTE_FK` (`NUM_MLLE`),
  KEY `CPT_PROFIL_FK` (`IDPROFIL`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `compte`
--

INSERT INTO `compte` (`LOGIN`, `NUM_MLLE`, `IDPROFIL`, `PWD`, `ACTIVATED`) VALUES
('aline', 'M', 1, '8d3152ebd103cea3509c7dcfad8f8c10', 1),
('kam', '09898T', 2, 'd968a18370429ceee4e7fb0268ec50bf', 1),
('kibsa', '677D', 1, 'ff0754b72205f653f4bab2f2025b16fa', 1),
('maiga', 'MM', 1, 'e8d49aef01ceacf2ca3328f2b20b9dc6', 1),
('moussa', '43479F', 1, '309cd3800aacbd003ac36199fa537295', 1),
('root', '0345Y', 1, '5ea8ca668425c818d8e2aa7b8b6fef7c', 1);

-- --------------------------------------------------------

--
-- Structure de la table `conditionmt`
--

CREATE TABLE IF NOT EXISTS `conditionmt` (
  `ID_CONDIT` int(11) NOT NULL AUTO_INCREMENT,
  `CODE_PRODUIT` varchar(10) NOT NULL,
  `ID_UNITE` varchar(10) NOT NULL,
  `CND_LIBELLE` text NOT NULL,
  `CND_SEUILMIN` bigint(20) DEFAULT NULL,
  `CND_SEUILMAX` bigint(20) DEFAULT NULL,
  `CND_PRIX` float DEFAULT NULL,
  `CND_SOUS_CONT` tinyint(4) DEFAULT NULL,
  `CND_X_ELT` float DEFAULT NULL,
  `X_ID_CONDIT` bigint(20) DEFAULT NULL,
  `CND_QTE` float DEFAULT NULL,
  PRIMARY KEY (`ID_CONDIT`),
  KEY `PRD_CND_FK` (`CODE_PRODUIT`),
  KEY `UT_CND_FK` (`ID_UNITE`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Contenu de la table `conditionmt`
--

INSERT INTO `conditionmt` (`ID_CONDIT`, `CODE_PRODUIT`, `ID_UNITE`, `CND_LIBELLE`, `CND_SEUILMIN`, `CND_SEUILMAX`, `CND_PRIX`, `CND_SOUS_CONT`, `CND_X_ELT`, `X_ID_CONDIT`, `CND_QTE`) VALUES
(1, 'PA-01', 'sac', 'Riz sac de 100kg', 0, NULL, 0, 0, 0, 0, 100),
(2, 'PA-01', 'sac', 'Riz sac de 50kg', 0, NULL, 0, 0, 0, 0, 50),
(3, 'PA-01', 'sac', 'Riz sac de 25kg', 0, NULL, 0, 0, 0, 0, 25),
(4, 'PA-01', 'sac', 'Riz sac de 30kg', 0, NULL, 0, 0, 0, 0, 30),
(5, 'PA-02', 'sac', 'Haricot sac de 100kg', 0, NULL, 0, 0, 0, 0, 100),
(6, 'PA-02', 'sac', 'Haricot sac de 50kg', 0, NULL, 0, 0, 0, 0, 50),
(7, 'PA-03', 'cart', 'PÃ¢te alimentaire sachet de 250g', 0, NULL, 0, 0, 0, 0, 10),
(8, 'PA-03', 'cart', 'PÃ¢te alimentaire carton de 8kg', 0, NULL, 0, 0, 0, 0, 8),
(9, 'PA-04', 'bid', 'Huile bidon de 20l', 0, NULL, 0, 0, 0, 0, 20),
(10, 'PA-05', 'cart', 'Sardine carton de 50 boÃ®tes 125g', 0, NULL, 0, 0, 0, 0, 6.25),
(11, 'PA-07', 'cart', 'Tomate carton de 12 boÃ®tes de 800g', 0, NULL, 0, 0, 0, 0, 10),
(12, 'PA-03', 'cart', 'PÃ¢tes alimentaires carton de 10kg', 0, NULL, 0, 0, 40, 7, 10),
(13, 'PA-06', 'sht', 'CafÃ© sachet de 60g', 0, NULL, 0, 0, 0, 0, 0.06),
(14, 'PA-06', 'cart', 'CafÃ© boÃ®te de 200g', 0, NULL, 0, 0, 0, 0, 0.2),
(15, 'PA-011', 'sac', 'Cousous sac de 25kg', 0, NULL, 0, 0, 0, 0, 25),
(16, 'PA-08', 'bt', 'Lait boÃ®te 400g', 0, NULL, 0, 0, 0, 0, 0.4),
(17, 'PA-09', 'cart', 'Sucre carton de 25 paquets 25kg', 0, NULL, 0, 0, 0, 0, 25);

-- --------------------------------------------------------

--
-- Structure de la table `conversion`
--

CREATE TABLE IF NOT EXISTS `conversion` (
  `ID_UNITE` varchar(10) NOT NULL,
  `UNI_ID_UNITE` varchar(10) NOT NULL,
  `VAUT` float DEFAULT NULL,
  PRIMARY KEY (`ID_UNITE`,`UNI_ID_UNITE`),
  KEY `CONVERSION_FK` (`ID_UNITE`),
  KEY `CONVERSION2_FK` (`UNI_ID_UNITE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `declass`
--

CREATE TABLE IF NOT EXISTS `declass` (
  `ID_DECLASS` int(11) NOT NULL AUTO_INCREMENT,
  `ID_EXERCICE` int(11) NOT NULL,
  `CODE_MAGASIN` varchar(10) NOT NULL,
  `CODE_DECLAS` varchar(250) DEFAULT NULL,
  `DCL_DATE` date DEFAULT NULL,
  `DCL_RAISON` text,
  `DCL_REFRAPPORT` varchar(100) DEFAULT NULL,
  `DCL_CABINET` varchar(200) DEFAULT NULL,
  `DCL_VALIDE` tinyint(4) DEFAULT NULL,
  `DCL_DATEVALID` datetime DEFAULT NULL,
  PRIMARY KEY (`ID_DECLASS`),
  KEY `EX_DECLASS_FK` (`ID_EXERCICE`),
  KEY `MAGDECL_FK` (`CODE_MAGASIN`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `declass_cnd`
--

CREATE TABLE IF NOT EXISTS `declass_cnd` (
  `ID_CONDIT` int(11) NOT NULL,
  `ID_DECLASS` int(11) NOT NULL,
  `DECLASSCND_QUANTITE` float DEFAULT NULL,
  `DECLASSCND_EFF` tinyint(4) DEFAULT NULL,
  `DEC_UNITE` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`ID_CONDIT`,`ID_DECLASS`),
  KEY `DECLASS_CND_FK` (`ID_CONDIT`),
  KEY `DECLASS_CND2_FK` (`ID_DECLASS`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `decoupageadm`
--

CREATE TABLE IF NOT EXISTS `decoupageadm` (
  `ID_DECOUPAGE` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`ID_DECOUPAGE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `donnee_annuelle`
--

CREATE TABLE IF NOT EXISTS `donnee_annuelle` (
  `ID_BENEF` int(11) NOT NULL,
  `CODE_MAGASIN` varchar(10) NOT NULL,
  `ID_EXERCICE` int(11) NOT NULL,
  `EFFECTIF` int(11) DEFAULT NULL,
  `GARCON` int(11) DEFAULT NULL,
  `FILLE` int(11) DEFAULT NULL,
  `INTENDANT` varchar(200) DEFAULT NULL,
  `TEL_INTENDANT` varchar(20) DEFAULT NULL,
  `MOY_RATIONNEL` int(11) DEFAULT NULL,
  `DATECREAT` date DEFAULT NULL,
  PRIMARY KEY (`ID_BENEF`,`CODE_MAGASIN`,`ID_EXERCICE`),
  KEY `DONNEE_ANNUELLE_FK` (`ID_BENEF`),
  KEY `DONNEE_ANNUELLE2_FK` (`CODE_MAGASIN`),
  KEY `DONNEE_ANNUELLE3_FK` (`ID_EXERCICE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `dotation`
--

CREATE TABLE IF NOT EXISTS `dotation` (
  `ID_DOTATION` int(11) NOT NULL AUTO_INCREMENT,
  `ID_PROGR` int(11) DEFAULT NULL,
  `ID_BENEF` int(11) NOT NULL,
  `ID_EXERCICE` int(11) NOT NULL,
  `CODE_NDOTATION` varchar(10) NOT NULL,
  `CODE_MAGASIN` varchar(10) NOT NULL,
  `CODE_DOTATION` varchar(250) DEFAULT NULL,
  `DOT_DATE` date DEFAULT NULL,
  `ASSOCIE` tinyint(4) DEFAULT NULL,
  `PROGRAMME` bigint(20) DEFAULT NULL,
  `DOT_VALIDE` tinyint(4) DEFAULT NULL,
  `DOT_DATEVALID` datetime DEFAULT NULL,
  `DOT_NATURE` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`ID_DOTATION`),
  KEY `BENEF_DOT_FK` (`ID_BENEF`),
  KEY `DOT_NDOT_FK` (`CODE_NDOTATION`),
  KEY `EX_DOT_FK` (`ID_EXERCICE`),
  KEY `DOT_PROG_FK` (`ID_PROGR`),
  KEY `MAGDOT_FK` (`CODE_MAGASIN`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `dotation`
--

INSERT INTO `dotation` (`ID_DOTATION`, `ID_PROGR`, `ID_BENEF`, `ID_EXERCICE`, `CODE_NDOTATION`, `CODE_MAGASIN`, `CODE_DOTATION`, `DOT_DATE`, `ASSOCIE`, `PROGRAMME`, `DOT_VALIDE`, `DOT_DATEVALID`, `DOT_NATURE`) VALUES
(2, 32, 27, 2012, '1DOT', 'MAG0', '', '2012-10-02', 1, 32, 1, NULL, 1),
(3, 26, 21, 2012, '1DOT', 'MAG0', '', '2012-10-02', 1, 26, 1, NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `dot_cnd`
--

CREATE TABLE IF NOT EXISTS `dot_cnd` (
  `ID_CONDIT` int(11) NOT NULL,
  `ID_DOTATION` int(11) NOT NULL,
  `DOTCND_QTE` float DEFAULT NULL,
  `DOTCND_RECU` tinyint(4) DEFAULT NULL,
  `DOT_UNITE` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`ID_CONDIT`,`ID_DOTATION`),
  KEY `DOT_CND_FK` (`ID_CONDIT`),
  KEY `DOT_CND2_FK` (`ID_DOTATION`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `dot_cnd`
--

INSERT INTO `dot_cnd` (`ID_CONDIT`, `ID_DOTATION`, `DOTCND_QTE`, `DOTCND_RECU`, `DOT_UNITE`) VALUES
(2, 2, 46, NULL, 'sac'),
(2, 3, 50, NULL, 'sac'),
(6, 2, 10, NULL, 'sac'),
(6, 3, 10, NULL, 'sac'),
(9, 2, 10, NULL, 'bid'),
(9, 3, 11, NULL, 'bid'),
(11, 2, 6, NULL, 'cart'),
(11, 3, 7, NULL, 'cart'),
(12, 2, 50, NULL, 'cart'),
(12, 3, 50, NULL, 'cart');

-- --------------------------------------------------------

--
-- Structure de la table `exercice`
--

CREATE TABLE IF NOT EXISTS `exercice` (
  `ID_EXERCICE` int(11) NOT NULL,
  `EX_LIBELLE` varchar(50) DEFAULT NULL,
  `EX_DATEDEBUT` date DEFAULT NULL,
  `EX_DATEFIN` date DEFAULT NULL,
  `EX_CLOTURE` tinyint(4) DEFAULT NULL,
  `EX_DATECLOTURE` datetime DEFAULT NULL,
  PRIMARY KEY (`ID_EXERCICE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `exercice`
--

INSERT INTO `exercice` (`ID_EXERCICE`, `EX_LIBELLE`, `EX_DATEDEBUT`, `EX_DATEFIN`, `EX_CLOTURE`, `EX_DATECLOTURE`) VALUES
(2010, 'Exercice budgÃ©taire 2010-2011', '2011-01-01', '2011-12-31', 0, '0000-00-00 00:00:00'),
(2011, 'Exercice budgÃ©taire 2011-2012', '2012-07-01', '2011-07-31', 0, '0000-00-00 00:00:00'),
(2012, 'Exercice budgÃ©taire 2012-2013', '2012-08-01', '2012-08-31', 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `ex_prd`
--

CREATE TABLE IF NOT EXISTS `ex_prd` (
  `CODE_PRODUIT` varchar(10) NOT NULL,
  `ID_EXERCICE` int(11) NOT NULL,
  `EXPRD_DATE` date DEFAULT NULL,
  `EXPRD_STOCK` float DEFAULT NULL,
  `EXPRD_QTECDE` float DEFAULT NULL,
  `EXPRD_QTELVR` float DEFAULT NULL,
  `EXPRD_DOTATION` float DEFAULT NULL,
  PRIMARY KEY (`CODE_PRODUIT`,`ID_EXERCICE`),
  KEY `EX_PRD_FK` (`CODE_PRODUIT`),
  KEY `EX_PRD2_FK` (`ID_EXERCICE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `fournisseur`
--

CREATE TABLE IF NOT EXISTS `fournisseur` (
  `ID_FORNISSEUR` int(11) NOT NULL AUTO_INCREMENT,
  `CODE_FOUR` varchar(10) DEFAULT NULL,
  `FOUR_NOM` varchar(100) DEFAULT NULL,
  `FOUR_TEL` varchar(30) DEFAULT NULL,
  `FOUR_ADRESSE` varchar(250) DEFAULT NULL,
  `FOUR_EMAIL` varchar(100) DEFAULT NULL,
  `FOUR_RESPONSABLE` varchar(100) DEFAULT NULL,
  `FOUR_RESPTEL` varchar(30) DEFAULT NULL,
  `FOUR_RESPEMAIL` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`ID_FORNISSEUR`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `fournisseur`
--

INSERT INTO `fournisseur` (`ID_FORNISSEUR`, `CODE_FOUR`, `FOUR_NOM`, `FOUR_TEL`, `FOUR_ADRESSE`, `FOUR_EMAIL`, `FOUR_RESPONSABLE`, `FOUR_RESPTEL`, `FOUR_RESPEMAIL`) VALUES
(1, 'FOUR-01', 'CORAM', '50345454', '01 BP 4545', 'coram@fasonet.bf', 'COMPAORE Rahim', '70346767', 'FOUR-01'),
(2, 'FOUR-02', 'Alpha & Omega', '20981098', '', '', '', '', 'F002'),
(3, 'FOUR-03', 'A.CO.R', '70206305', '', '', '', '', ''),
(4, 'FOUR-04', 'SONAGESS', '50303030', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Structure de la table `groupe`
--

CREATE TABLE IF NOT EXISTS `groupe` (
  `ID_GROUPE` int(11) NOT NULL AUTO_INCREMENT,
  `GRPE_LIBELLE` varchar(100) DEFAULT NULL,
  `GRP_PARAMETRAGE` varchar(20) DEFAULT NULL,
  `GRP_COMMANDE` varchar(20) DEFAULT NULL,
  `GRP_LIVRAISON` varchar(20) DEFAULT NULL,
  `GRP_AUTRELIVRAISON` varchar(20) DEFAULT NULL,
  `GRP_PROGRAMMATION` varchar(20) DEFAULT NULL,
  `GRP_REVERSEMENT` varchar(20) DEFAULT NULL,
  `GRP_DOTATION` varchar(20) DEFAULT NULL,
  `GRP_AUTREDOTATION` varchar(20) DEFAULT NULL,
  `GRP_DECLASSEMENT` varchar(20) DEFAULT NULL,
  `GRP_TRANSFERT` varchar(20) DEFAULT NULL,
  `GRP_RECONDITIONNEMENT` varchar(20) DEFAULT NULL,
  `GRP_REPORT` varchar(20) DEFAULT NULL,
  `GRP_INVENTAIRE` varchar(20) DEFAULT NULL,
  `GRP_PERSONNEL` varchar(20) DEFAULT NULL,
  `GRP_UTILISATEUR` varchar(20) DEFAULT NULL,
  `GRP_GROUPE` varchar(20) DEFAULT NULL,
  `GRP_BENEFICIAIRE` varchar(20) DEFAULT NULL,
  `GRP_FOURNISSEUR` varchar(20) DEFAULT NULL,
  `GRP_LOG` varchar(20) DEFAULT NULL,
  `GRP_CATEGORIE` varchar(20) DEFAULT NULL,
  `GRP_PRODUIT` varchar(20) DEFAULT NULL,
  `GRP_CONDITIONNEMENT` varchar(20) DEFAULT NULL,
  `GRP_USTENSILE` varchar(20) DEFAULT NULL,
  `GRP_UNITE` varchar(20) DEFAULT NULL,
  `GRP_CONVERSION` varchar(20) DEFAULT NULL,
  `GRP_BAREME` varchar(20) DEFAULT NULL,
  `GRP_TYPELOCALITE` varchar(20) DEFAULT NULL,
  `GRP_LOCALITE` varchar(20) DEFAULT NULL,
  `GRP_TYPESERVICE` varchar(20) DEFAULT NULL,
  `GRP_TYPEBENEFICIAIRE` varchar(20) DEFAULT NULL,
  `GRP_EXERCICE` varchar(20) DEFAULT NULL,
  `GRP_TYPEDOTATION` varchar(20) DEFAULT NULL,
  `GRP_RESPONSABLE` varchar(20) DEFAULT NULL,
  `GRP_PARAMETRE` varchar(20) DEFAULT NULL,
  `GRP_PROVINCE` varchar(20) DEFAULT NULL,
  `GRP_REGION` varchar(20) DEFAULT NULL,
  `GRP_DONNANNUELLE` varchar(20) DEFAULT NULL,
  `GRP_ETAT` varchar(20) DEFAULT NULL,
  `GRP_DB` varchar(20) DEFAULT NULL,
  `GRP_MENU_CPMIEP` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`ID_GROUPE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `groupelocalite`
--

CREATE TABLE IF NOT EXISTS `groupelocalite` (
  `ID_GRPLOC` varchar(10) NOT NULL,
  `GRPLOC_LIBELLE` varchar(50) NOT NULL,
  `GRPLOC_LIEN` varchar(10) NOT NULL,
  PRIMARY KEY (`ID_GRPLOC`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `groupeservice`
--

CREATE TABLE IF NOT EXISTS `groupeservice` (
  `ID_GRPSERVICE` varchar(10) NOT NULL,
  `GRPSER_LIBELLE` varchar(50) NOT NULL,
  `GRPSER_LIEN` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`ID_GRPSERVICE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `groupeservice`
--

INSERT INTO `groupeservice` (`ID_GRPSERVICE`, `GRPSER_LIBELLE`, `GRPSER_LIEN`) VALUES
('CA', 'Cantine', 'GP'),
('GP', 'Gestion du patrimoine', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `infogenerale`
--

CREATE TABLE IF NOT EXISTS `infogenerale` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `INF_CLIENT` text,
  `INF_DATEACQ` date DEFAULT NULL,
  `INF_LICENCE` text,
  `INF_MINISTERE` text,
  `INF_SECRETARIAT` text,
  `INF_DIRECTION` text,
  `INF_SERVICE` text,
  `INF_PAYS` text,
  `INF_DEVISE` text,
  `INF_VILLE` text,
  `INF_SIGNATEUR1` text,
  `INF_NOMSIGNATEUR1` text,
  `INF_SIGNATEUR2` text,
  `INF_NOMSIGNATEUR2` text,
  `INF_SIGNATEUR3` text,
  `INF_NOMSIGNATEUR3` text,
  `INF_SIGNATEUR4` text,
  `INF_NOMSIGNATEUR4` text,
  `INF_VALIDAUTO` tinyint(4) DEFAULT NULL,
  `INF_MAGASIN` text,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `infogenerale`
--

INSERT INTO `infogenerale` (`ID`, `INF_CLIENT`, `INF_DATEACQ`, `INF_LICENCE`, `INF_MINISTERE`, `INF_SECRETARIAT`, `INF_DIRECTION`, `INF_SERVICE`, `INF_PAYS`, `INF_DEVISE`, `INF_VILLE`, `INF_SIGNATEUR1`, `INF_NOMSIGNATEUR1`, `INF_SIGNATEUR2`, `INF_NOMSIGNATEUR2`, `INF_SIGNATEUR3`, `INF_NOMSIGNATEUR3`, `INF_SIGNATEUR4`, `INF_NOMSIGNATEUR4`, `INF_VALIDAUTO`, `INF_MAGASIN`) VALUES
(1, 'MESS', '2011-10-12', 'demo', 'MINISTERE DES ENSIGNEMENTS SECONDAIRE ET SUPERIEUR', 'SECRETARIAT GENERAL', 'DIRECTION DE L''ADMINISTRATION ET DES FINANCES', 'SERVICE DE GESTION DES CANTINES SCOLAIRES', 'BURKINA FASO', 'Unite - ProgrÃ¨s - Justice', 'OUAGADOUGOU', 'Magasinier', '', 'Chef de service', '', 'Gestionnaire', '', 'Directeur de l''Administration des finances', '', 0, '');

-- --------------------------------------------------------

--
-- Structure de la table `inventaire`
--

CREATE TABLE IF NOT EXISTS `inventaire` (
  `ID_INVENTAIRE` int(11) NOT NULL AUTO_INCREMENT,
  `ID_EXERCICE` int(11) NOT NULL,
  `CODE_MAGASIN` varchar(10) NOT NULL,
  `CODE_INVENTAIRE` varchar(20) DEFAULT NULL,
  `INV_LIBELLE` varchar(250) DEFAULT NULL,
  `INV_DATE` date DEFAULT NULL,
  `INV_VALID` tinyint(4) DEFAULT NULL,
  `INV_DATEVALID` datetime DEFAULT NULL,
  PRIMARY KEY (`ID_INVENTAIRE`),
  KEY `EX_INVT_FK` (`ID_EXERCICE`),
  KEY `MAGINV_FK` (`CODE_MAGASIN`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `livraison`
--

CREATE TABLE IF NOT EXISTS `livraison` (
  `ID_LIVRAISON` int(11) NOT NULL AUTO_INCREMENT,
  `ID_EXERCICE` int(11) NOT NULL,
  `ID_COMMANDE` int(11) NOT NULL,
  `CODE_MAGASIN` varchar(10) NOT NULL,
  `LVR_DATE` date DEFAULT NULL,
  `LVR_VALIDE` tinyint(4) DEFAULT NULL,
  `LVR_DATEVALID` datetime DEFAULT NULL,
  `CODE_LIVRAISON` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`ID_LIVRAISON`),
  KEY `CDE_LVR_FK` (`ID_COMMANDE`),
  KEY `EXER_LIVR_FK` (`ID_EXERCICE`),
  KEY `MAGLIVR_FK` (`CODE_MAGASIN`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `livraison`
--

INSERT INTO `livraison` (`ID_LIVRAISON`, `ID_EXERCICE`, `ID_COMMANDE`, `CODE_MAGASIN`, `LVR_DATE`, `LVR_VALIDE`, `LVR_DATEVALID`, `CODE_LIVRAISON`) VALUES
(1, 2012, 1, 'MAG0', '2012-08-16', 1, NULL, '0001234/2012/Alpha & Omega'),
(2, 2012, 2, 'MAG0', '2012-08-16', 1, NULL, '0010/2012');

-- --------------------------------------------------------

--
-- Structure de la table `localite`
--

CREATE TABLE IF NOT EXISTS `localite` (
  `ID_LOCALITE` int(11) NOT NULL AUTO_INCREMENT,
  `ID_DECOUPAGE` int(11) NOT NULL,
  `ID_GRPLOC` varchar(10) NOT NULL,
  `LOC_NOM` varchar(50) NOT NULL,
  `LOC_LIEN` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`ID_LOCALITE`),
  KEY `GRPLOC_LOC_FK` (`ID_GRPLOC`),
  KEY `LOC_DECOUPAGE_FK` (`ID_DECOUPAGE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `logs`
--

CREATE TABLE IF NOT EXISTS `logs` (
  `ID_LOG` int(11) NOT NULL AUTO_INCREMENT,
  `LOGIN` varchar(10) NOT NULL,
  `LOG_DATE` datetime DEFAULT NULL,
  `LOG_DESCRIP` text,
  `MLLE` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`ID_LOG`),
  KEY `CPTE_LOG_FK` (`LOGIN`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1048 ;

--
-- Contenu de la table `logs`
--

INSERT INTO `logs` (`ID_LOG`, `LOGIN`, `LOG_DATE`, `LOG_DESCRIP`, `MLLE`) VALUES
(1, 'root', '2012-09-17 11:07:48', 'Modification d''une affectation (340, MAG1)', '0345Y'),
(2, 'root', '2012-09-17 11:10:41', 'Modification d''une affectation (26, MAG0)', '0345Y'),
(3, 'root', '2012-09-17 12:08:12', 'DÃ©connexion du systÃ¨me Ã©chouÃ©e', '0345Y'),
(4, 'root', '2012-09-17 12:10:59', 'Connexion au systÃ¨me', '0345Y'),
(5, 'root', '2012-09-17 12:13:25', 'DÃ©connexion du systÃ¨me Ã©chouÃ©e', '0345Y'),
(6, 'moussa', '2012-09-17 12:13:48', 'Connexion au systÃ¨me', '43479F'),
(11, 'moussa', '2012-09-17 15:15:55', 'Tentative de connexion au systÃ¨me Ã©chouÃ©e', ''),
(12, 'moussa', '2012-09-17 15:16:15', 'Tentative de connexion au systÃ¨me Ã©chouÃ©e', ''),
(13, 'moussa', '2012-09-17 15:16:37', 'Tentative de connexion au systÃ¨me Ã©chouÃ©e', ''),
(14, 'moussa', '2012-09-17 15:16:51', 'Tentative de connexion au systÃ¨me Ã©chouÃ©e', ''),
(15, 'moussa', '2012-09-17 15:17:08', 'Tentative de connexion au systÃ¨me Ã©chouÃ©e', ''),
(16, 'moussa', '2012-09-17 15:17:10', 'Tentative de connexion au systÃ¨me Ã©chouÃ©e', ''),
(17, 'moussa', '2012-09-17 15:17:12', 'Tentative de connexion au systÃ¨me Ã©chouÃ©e', ''),
(18, 'moussa', '2012-09-17 15:17:15', 'Tentative de connexion au systÃ¨me Ã©chouÃ©e', ''),
(19, 'moussa', '2012-09-17 15:17:17', 'Tentative de connexion au systÃ¨me Ã©chouÃ©e', ''),
(20, 'moussa', '2012-09-17 15:17:29', 'Connexion au systÃ¨me', '43479F'),
(21, 'moussa', '2012-09-17 15:40:37', 'Ajout d''une affectation (MAG0, 567)', '43479F'),
(22, 'moussa', '2012-09-17 15:44:34', 'Ajout d''une affectation (MAG0, 629)', '43479F'),
(23, 'moussa', '2012-09-17 15:45:34', 'Ajout d''une affectation (MAG0, 565)', '43479F'),
(24, 'moussa', '2012-09-17 15:47:13', 'Ajout d''une affectation (MAG0, 566)', '43479F'),
(25, 'moussa', '2012-09-17 15:47:46', 'Ajout d''une affectation (MAG0, 568)', '43479F'),
(26, 'moussa', '2012-09-17 15:48:16', 'Ajout d''une affectation (MAG0, 569)', '43479F'),
(27, 'moussa', '2012-09-17 15:48:58', 'Ajout d''une affectation (MAG0, 570)', '43479F'),
(28, 'moussa', '2012-09-17 15:49:30', 'Ajout d''une affectation (MAG0, 571)', '43479F'),
(29, 'moussa', '2012-09-17 15:57:25', 'Ajout d''une affectation (MAG0, 535)', '43479F'),
(30, 'moussa', '2012-09-17 16:04:14', 'Ajout d''une affectation (MAG0, 547)', '43479F'),
(31, 'moussa', '2012-09-17 16:15:44', 'Ajout d''une affectation (MAG0, 553)', '43479F'),
(32, 'moussa', '2012-09-17 16:17:07', 'Ajout d''une affectation (MAG0, 552)', '43479F'),
(33, 'moussa', '2012-09-17 16:26:53', 'Ajout d''une affectation (MAG0, 698)', '43479F'),
(34, 'moussa', '2012-09-17 16:34:27', 'Ajout d''une affectation (MAG0, 550)', '43479F'),
(35, 'moussa', '2012-09-17 16:49:26', 'Ajout d''une affectation (MAG0, 649)', '43479F'),
(36, 'moussa', '2012-09-17 16:50:19', 'Ajout d''une affectation (MAG0, 616)', '43479F'),
(37, 'moussa', '2012-09-17 16:51:04', 'Ajout d''une affectation (MAG0, 617)', '43479F'),
(38, 'moussa', '2012-09-17 16:51:35', 'Ajout d''une affectation (MAG0, 652)', '43479F'),
(39, 'moussa', '2012-09-17 16:52:00', 'Ajout d''une affectation (MAG0, 650)', '43479F'),
(40, 'moussa', '2012-09-17 16:52:28', 'Ajout d''une affectation (MAG0, 615)', '43479F'),
(41, 'moussa', '2012-09-17 16:55:48', 'Ajout d''une affectation (MAG0, 638)', '43479F'),
(42, 'moussa', '2012-09-17 16:56:11', 'Ajout d''une affectation (MAG0, 648)', '43479F'),
(43, 'moussa', '2012-09-17 17:08:45', 'Ajout d''une affectation (MAG0, 610)', '43479F'),
(44, 'moussa', '2012-09-17 17:09:56', 'Ajout d''une affectation (MAG0, 603)', '43479F'),
(45, 'moussa', '2012-09-17 17:10:20', 'Ajout d''une affectation (MAG0, 604)', '43479F'),
(46, 'moussa', '2012-09-17 17:10:58', 'Ajout d''une affectation (MAG0, 613)', '43479F'),
(47, 'moussa', '2012-09-17 17:13:48', 'Ajout d''une affectation (MAG0, 600)', '43479F'),
(48, 'moussa', '2012-09-17 17:14:33', 'Ajout d''une affectation (MAG0, 680)', '43479F'),
(49, 'moussa', '2012-09-17 17:15:57', 'Ajout d''une affectation (MAG0, 599)', '43479F'),
(50, 'moussa', '2012-09-17 17:22:04', 'Ajout d''une programmation (37, 1DOT, 231)', '43479F'),
(51, 'moussa', '2012-09-17 17:22:05', 'Ajout des lignes de programmation', '43479F'),
(52, 'moussa', '2012-09-17 17:24:10', 'Ajout d''une programmation (38, 1DOT, 42)', '43479F'),
(53, 'moussa', '2012-09-17 17:24:10', 'Ajout des lignes de programmation', '43479F'),
(54, 'moussa', '2012-09-17 17:25:22', 'DÃ©connexion du systÃ¨me Ã©chouÃ©e', '43479F'),
(55, 'moussa', '2012-09-18 08:24:47', 'Connexion au systÃ¨me', '43479F'),
(57, 'maiga', '2012-09-18 08:34:21', 'Connexion au systÃ¨me', 'MM'),
(58, 'moussa', '2012-09-18 08:41:27', 'Ajout d''une affectation (MAG0, 612)', '43479F'),
(59, 'moussa', '2012-09-18 09:02:38', 'Ajout d''une affectation (MAG0, 607)', '43479F'),
(60, 'moussa', '2012-09-18 09:03:21', 'Ajout d''une affectation (MAG0, 681)', '43479F'),
(61, 'moussa', '2012-09-18 09:04:10', 'Ajout d''une affectation (MAG0, 606)', '43479F'),
(62, 'moussa', '2012-09-18 09:05:06', 'Ajout d''une affectation (MAG0, 614)', '43479F'),
(63, 'moussa', '2012-09-18 09:05:38', 'Ajout d''une affectation (MAG0, 602)', '43479F'),
(64, 'moussa', '2012-09-18 09:06:16', 'Ajout d''une affectation (MAG0, 605)', '43479F'),
(65, 'moussa', '2012-09-18 09:13:49', 'Ajout d''une affectation (MAG0, 691)', '43479F'),
(66, 'moussa', '2012-09-18 09:24:16', 'Ajout d''une affectation (MAG0, 693)', '43479F'),
(67, 'moussa', '2012-09-18 09:25:35', 'Ajout d''une affectation (MAG0, 690)', '43479F'),
(68, 'moussa', '2012-09-18 09:27:19', 'Ajout d''une affectation (MAG0, 591)', '43479F'),
(69, 'moussa', '2012-09-18 09:31:29', 'Ajout d''une affectation (MAG0, 692)', '43479F'),
(70, 'moussa', '2012-09-18 09:32:22', 'Ajout d''une affectation (MAG0, 587)', '43479F'),
(71, 'moussa', '2012-09-18 09:32:55', 'Ajout d''une affectation (MAG0, 585)', '43479F'),
(72, 'moussa', '2012-09-18 09:33:58', 'Ajout d''une affectation (MAG0, 590)', '43479F'),
(73, 'moussa', '2012-09-18 09:34:31', 'Ajout d''une affectation (MAG0, 588)', '43479F'),
(74, 'moussa', '2012-09-18 09:35:39', 'Ajout d''une affectation (MAG0, 642)', '43479F'),
(75, 'moussa', '2012-09-18 09:36:11', 'Ajout d''une affectation (MAG0, 688)', '43479F'),
(76, 'moussa', '2012-09-18 09:39:41', 'Ajout d''une affectation (MAG0, 687)', '43479F'),
(77, 'moussa', '2012-09-18 09:40:27', 'Ajout d''une affectation (MAG0, 686)', '43479F'),
(78, 'moussa', '2012-09-18 09:40:55', 'Ajout d''une affectation (MAG0, 689)', '43479F'),
(79, 'moussa', '2012-09-18 09:49:44', 'Ajout d''une affectation (MAG0, 575)', '43479F'),
(80, 'moussa', '2012-09-18 09:51:32', 'Ajout d''une affectation (MAG0, 666)', '43479F'),
(81, 'moussa', '2012-09-18 09:52:02', 'Ajout d''une affectation (MAG0, 697)', '43479F'),
(82, 'moussa', '2012-09-18 09:52:38', 'Ajout d''une affectation (MAG0, 572)', '43479F'),
(83, 'moussa', '2012-09-18 09:56:56', 'Ajout d''une affectation (MAG0, 668)', '43479F'),
(84, 'moussa', '2012-09-18 09:57:37', 'Ajout d''une affectation (MAG0, 667)', '43479F'),
(85, 'moussa', '2012-09-18 09:58:11', 'Ajout d''une affectation (MAG0, 669)', '43479F'),
(86, 'moussa', '2012-09-18 10:24:08', 'Ajout d''une affectation (MAG0, 568)', '43479F'),
(87, 'moussa', '2012-09-18 10:25:57', 'Ajout d''une affectation (MAG0, 571)', '43479F'),
(88, 'moussa', '2012-09-18 10:26:56', 'Ajout d''une affectation (MAG0, 570)', '43479F'),
(89, 'moussa', '2012-09-18 10:27:46', 'Ajout d''une affectation (MAG0, 569)', '43479F'),
(90, 'moussa', '2012-09-18 10:32:31', 'Ajout d''une affectation (MAG0, 665)', '43479F'),
(91, 'moussa', '2012-09-18 10:33:10', 'Ajout d''une affectation (MAG0, 574)', '43479F'),
(92, 'moussa', '2012-09-18 10:33:53', 'Ajout d''une affectation (MAG0, 573)', '43479F'),
(93, 'moussa', '2012-09-18 10:35:16', 'Ajout d''une affectation (MAG0, 679)', '43479F'),
(94, 'moussa', '2012-09-18 10:36:16', 'Ajout d''une affectation (MAG0, 644)', '43479F'),
(95, 'moussa', '2012-09-18 10:37:19', 'Ajout d''une affectation (MAG0, 589)', '43479F'),
(96, 'moussa', '2012-09-18 10:37:59', 'Ajout d''une affectation (MAG0, 643)', '43479F'),
(97, 'moussa', '2012-09-18 10:38:26', 'Ajout d''une affectation (MAG0, 586)', '43479F'),
(98, 'moussa', '2012-09-18 10:40:08', 'Ajout d''une affectation (MAG0, 637)', '43479F'),
(99, 'moussa', '2012-09-18 10:40:42', 'Ajout d''une affectation (MAG0, 651)', '43479F'),
(100, 'moussa', '2012-09-18 10:41:26', 'Ajout d''une affectation (MAG0, 671)', '43479F'),
(101, 'moussa', '2012-09-18 10:42:15', 'Ajout d''une affectation (MAG0, 684)', '43479F'),
(102, 'moussa', '2012-09-18 10:43:43', 'Ajout d''une affectation (MAG0, 656)', '43479F'),
(103, 'moussa', '2012-09-18 10:44:06', 'Ajout d''une affectation (MAG0, 700)', '43479F'),
(104, 'moussa', '2012-09-18 10:46:35', 'Ajout d''une affectation (MAG0, 700)', '43479F'),
(105, 'moussa', '2012-09-18 10:47:10', 'Ajout d''une affectation (MAG0, 661)', '43479F'),
(106, 'moussa', '2012-09-18 10:47:40', 'Ajout d''une affectation (MAG0, 682)', '43479F'),
(107, 'moussa', '2012-09-18 10:48:16', 'Ajout d''une affectation (MAG0, 678)', '43479F'),
(108, 'aline', '2012-09-18 12:05:32', 'Connexion au systÃ¨me', 'M'),
(109, 'moussa', '2012-09-18 12:09:21', 'Ajout d''une affectation (MAG0, 685)', '43479F'),
(110, 'moussa', '2012-09-18 12:09:49', 'Ajout d''une affectation (MAG0, 672)', '43479F'),
(111, 'moussa', '2012-09-18 12:13:03', 'Ajout d''une affectation (MAG0, 175)', '43479F'),
(112, 'moussa', '2012-09-18 12:13:41', 'Ajout d''une affectation (MAG0, 653)', '43479F'),
(113, 'moussa', '2012-09-18 12:14:27', 'Ajout d''une affectation (MAG0, 673)', '43479F'),
(114, 'moussa', '2012-09-18 12:27:03', 'Ajout d''une affectation (MAG0, 215)', '43479F'),
(115, 'moussa', '2012-09-18 12:27:54', 'Ajout d''une affectation (MAG0, 216)', '43479F'),
(116, 'moussa', '2012-09-18 12:28:53', 'Ajout d''une affectation (MAG0, 217)', '43479F'),
(117, 'moussa', '2012-09-18 12:29:30', 'Ajout d''une affectation (MAG0, 218)', '43479F'),
(118, 'moussa', '2012-09-18 12:31:27', 'Ajout d''une affectation (MAG0, 219)', '43479F'),
(119, 'moussa', '2012-09-18 12:31:47', 'Ajout d''une affectation (MAG0, 220)', '43479F'),
(120, 'moussa', '2012-09-18 12:32:30', 'Ajout d''une affectation (MAG0, 221)', '43479F'),
(121, 'moussa', '2012-09-18 12:39:14', 'Ajout d''une affectation (MAG0, 659)', '43479F'),
(122, 'moussa', '2012-09-18 12:44:51', 'Ajout d''une affectation (MAG0, 222)', '43479F'),
(123, 'moussa', '2012-09-18 12:45:27', 'Ajout d''une affectation (MAG0, 223)', '43479F'),
(124, 'moussa', '2012-09-18 12:46:13', 'Ajout d''une affectation (MAG0, 224)', '43479F'),
(125, 'moussa', '2012-09-18 12:46:38', 'Ajout d''une affectation (MAG0, 225)', '43479F'),
(126, 'moussa', '2012-09-18 12:54:30', 'Ajout d''une affectation (MAG0, 226)', '43479F'),
(127, 'moussa', '2012-09-18 12:56:55', 'Ajout d''une affectation (MAG0, 235)', '43479F'),
(128, 'moussa', '2012-09-18 12:57:49', 'Ajout d''une affectation (MAG0, 229)', '43479F'),
(129, 'moussa', '2012-09-18 12:58:08', 'Ajout d''une affectation (MAG0, 230)', '43479F'),
(130, 'moussa', '2012-09-18 12:58:39', 'DÃ©connexion du systÃ¨me Ã©chouÃ©e', '43479F'),
(131, 'moussa', '2012-09-18 15:17:29', 'Tentative de connexion au systÃ¨me Ã©chouÃ©e', ''),
(132, 'moussa', '2012-09-18 15:17:44', 'Connexion au systÃ¨me', '43479F'),
(133, 'moussa', '2012-09-18 15:19:03', 'Ajout d''une affectation (MAG0, 230)', '43479F'),
(134, 'moussa', '2012-09-18 15:22:03', 'Ajout d''une affectation (MAG0, 232)', '43479F'),
(135, 'moussa', '2012-09-18 15:27:22', 'Ajout d''une affectation (MAG0, 233)', '43479F'),
(136, 'moussa', '2012-09-18 15:28:07', 'Ajout d''une affectation (MAG0, 234)', '43479F'),
(137, 'moussa', '2012-09-18 15:30:13', 'Ajout d''une affectation (MAG0, 235)', '43479F'),
(138, 'moussa', '2012-09-18 15:32:37', 'Ajout d''une affectation (MAG0, 236)', '43479F'),
(139, 'moussa', '2012-09-18 15:33:24', 'Ajout d''une affectation (MAG0, 238)', '43479F'),
(140, 'moussa', '2012-09-18 15:34:39', 'Ajout d''une affectation (MAG0, 239)', '43479F'),
(141, 'moussa', '2012-09-18 15:35:55', 'Ajout d''une affectation (MAG0, 241)', '43479F'),
(142, 'moussa', '2012-09-18 15:36:44', 'Ajout d''une affectation (MAG0, 242)', '43479F'),
(143, 'moussa', '2012-09-18 15:37:40', 'Ajout d''une affectation (MAG0, 243)', '43479F'),
(144, 'moussa', '2012-09-18 15:48:58', 'Ajout d''une affectation (MAG0, 244)', '43479F'),
(145, 'moussa', '2012-09-18 15:49:35', 'Ajout d''une affectation (MAG0, 640)', '43479F'),
(146, 'moussa', '2012-09-18 15:50:13', 'Ajout d''une affectation (MAG0, 641)', '43479F'),
(147, 'moussa', '2012-09-18 15:51:16', 'Ajout d''une affectation (MAG0, 245)', '43479F'),
(148, 'moussa', '2012-09-18 15:52:04', 'Ajout d''une affectation (MAG0, 246)', '43479F'),
(149, 'moussa', '2012-09-18 15:54:53', 'Ajout d''une affectation (MAG0, 247)', '43479F'),
(150, 'moussa', '2012-09-18 15:55:21', 'Ajout d''une affectation (MAG0, 248)', '43479F'),
(151, 'moussa', '2012-09-18 15:56:00', 'Ajout d''une affectation (MAG0, 249)', '43479F'),
(152, 'moussa', '2012-09-18 15:57:01', 'Ajout d''une affectation (MAG0, 250)', '43479F'),
(153, 'moussa', '2012-09-18 15:57:44', 'Ajout d''une affectation (MAG0, 251)', '43479F'),
(154, 'moussa', '2012-09-18 15:59:11', 'Ajout d''une affectation (MAG0, 252)', '43479F'),
(155, 'moussa', '2012-09-18 16:03:56', 'Ajout d''une affectation (MAG0, 253)', '43479F'),
(156, 'moussa', '2012-09-18 16:04:24', 'Ajout d''une affectation (MAG0, 254)', '43479F'),
(157, 'moussa', '2012-09-18 16:04:53', 'Ajout d''une affectation (MAG0, 255)', '43479F'),
(158, 'moussa', '2012-09-18 16:08:54', 'Ajout d''une affectation (MAG0, 256)', '43479F'),
(159, 'moussa', '2012-09-18 16:09:33', 'Ajout d''une affectation (MAG0, 257)', '43479F'),
(160, 'moussa', '2012-09-18 16:10:23', 'Ajout d''une affectation (MAG0, 675)', '43479F'),
(161, 'moussa', '2012-09-18 16:11:14', 'Ajout d''une affectation (MAG0, 259)', '43479F'),
(162, 'moussa', '2012-09-18 16:15:05', 'Ajout d''une affectation (MAG0, 260)', '43479F'),
(163, 'moussa', '2012-09-18 16:17:40', 'Ajout d''une affectation (MAG0, 261)', '43479F'),
(164, 'moussa', '2012-09-18 16:18:21', 'Ajout d''une affectation (MAG0, 262)', '43479F'),
(165, 'moussa', '2012-09-18 16:18:58', 'Ajout d''une affectation (MAG0, 655)', '43479F'),
(166, 'moussa', '2012-09-18 16:19:34', 'Ajout d''une affectation (MAG0, 674)', '43479F'),
(167, 'moussa', '2012-09-18 16:20:32', 'Ajout d''une affectation (MAG0, 658)', '43479F'),
(168, 'moussa', '2012-09-18 16:21:13', 'Ajout d''une affectation (MAG0, 662)', '43479F'),
(169, 'moussa', '2012-09-18 16:22:18', 'Ajout d''une affectation (MAG0, 676)', '43479F'),
(170, 'moussa', '2012-09-18 16:22:57', 'Ajout d''une affectation (MAG0, 677)', '43479F'),
(171, 'moussa', '2012-09-18 16:23:25', 'Ajout d''une affectation (MAG0, 660)', '43479F'),
(172, 'moussa', '2012-09-18 16:23:52', 'Ajout d''une affectation (MAG0, 657)', '43479F'),
(173, 'moussa', '2012-09-18 16:26:16', 'Ajout d''une affectation (MAG0, 263)', '43479F'),
(174, 'moussa', '2012-09-18 16:26:56', 'Ajout d''une affectation (MAG0, 265)', '43479F'),
(175, 'moussa', '2012-09-18 16:27:28', 'Ajout d''une affectation (MAG0, 266)', '43479F'),
(176, 'moussa', '2012-09-18 16:28:08', 'Ajout d''une affectation (MAG0, 267)', '43479F'),
(177, 'moussa', '2012-09-18 16:30:25', 'Ajout d''une affectation (MAG0, 268)', '43479F'),
(178, 'moussa', '2012-09-18 16:31:13', 'Ajout d''une affectation (MAG0, 269)', '43479F'),
(179, 'moussa', '2012-09-18 17:00:26', 'Ajout d''une affectation (MAG2, 596)', '43479F'),
(180, 'moussa', '2012-09-18 17:00:58', 'Ajout d''une affectation (MAG2, 630)', '43479F'),
(181, 'moussa', '2012-09-18 17:01:39', 'Ajout d''une affectation (MAG2, 598)', '43479F'),
(182, 'moussa', '2012-09-18 17:03:17', 'Ajout d''une affectation (MAG2, 597)', '43479F'),
(183, 'moussa', '2012-09-18 17:04:06', 'Ajout d''une affectation (MAG2, 699)', '43479F'),
(184, 'moussa', '2012-09-18 17:04:36', 'Ajout d''une affectation (MAG2, 634)', '43479F'),
(185, 'moussa', '2012-09-18 17:05:20', 'Ajout d''une affectation (MAG2, 291)', '43479F'),
(186, 'moussa', '2012-09-18 17:08:26', 'Ajout d''une affectation (MAG2, 619)', '43479F'),
(187, 'moussa', '2012-09-18 17:09:46', 'Ajout d''une affectation (MAG2, 620)', '43479F'),
(188, 'moussa', '2012-09-18 17:10:57', 'Ajout d''une affectation (MAG2, 623)', '43479F'),
(189, 'moussa', '2012-09-18 17:12:11', 'Ajout d''une affectation (MAG2, 618)', '43479F'),
(190, 'moussa', '2012-09-18 17:13:23', 'Ajout d''une affectation (MAG2, 622)', '43479F'),
(191, 'moussa', '2012-09-18 17:13:47', 'Ajout d''une affectation (MAG2, 624)', '43479F'),
(192, 'moussa', '2012-09-18 17:14:25', 'Ajout d''une affectation (MAG2, 632)', '43479F'),
(193, 'moussa', '2012-09-18 17:18:27', 'Ajout d''une affectation (MAG2, 631)', '43479F'),
(194, 'moussa', '2012-09-18 17:18:56', 'Ajout d''une affectation (MAG2, 625)', '43479F'),
(195, 'moussa', '2012-09-18 17:19:40', 'Ajout d''une affectation (MAG2, 594)', '43479F'),
(196, 'moussa', '2012-09-18 17:20:11', 'Ajout d''une affectation (MAG2, 595)', '43479F'),
(197, 'moussa', '2012-09-18 17:21:20', 'Ajout d''une affectation (MAG2, 633)', '43479F'),
(198, 'moussa', '2012-09-18 17:21:51', 'Ajout d''une affectation (MAG2, 635)', '43479F'),
(199, 'moussa', '2012-09-18 17:22:26', 'Ajout d''une affectation (MAG2, 621)', '43479F'),
(200, 'moussa', '2012-09-18 17:24:39', 'DÃ©connexion du systÃ¨me Ã©chouÃ©e', '43479F'),
(201, 'moussa', '2012-09-19 08:19:37', 'Connexion au systÃ¨me', '43479F'),
(203, 'moussa', '2012-09-19 11:01:58', 'Connexion au systÃ¨me', '43479F'),
(204, 'moussa', '2012-09-19 11:07:03', 'Ajout d''une affectation (MAG1, 3)', '43479F'),
(205, 'moussa', '2012-09-19 11:10:15', 'Ajout d''une affectation (MAG1, 538)', '43479F'),
(206, 'moussa', '2012-09-19 11:12:07', 'Ajout d''une affectation (MAG1, 534)', '43479F'),
(207, 'moussa', '2012-09-19 11:12:38', 'Ajout d''une affectation (MAG1, 533)', '43479F'),
(208, 'moussa', '2012-09-19 11:14:02', 'Ajout d''une affectation (MAG1, 536)', '43479F'),
(209, 'moussa', '2012-09-19 11:18:40', 'Ajout d''une affectation (MAG1, 584)', '43479F'),
(210, 'moussa', '2012-09-19 11:24:49', 'Ajout d''une affectation (MAG1, 583)', '43479F'),
(211, 'moussa', '2012-09-19 11:28:30', 'Ajout d''une affectation (MAG1, 576)', '43479F'),
(212, 'moussa', '2012-09-19 11:31:32', 'Ajout d''une affectation (MAG1, 582)', '43479F'),
(213, 'moussa', '2012-09-19 11:34:19', 'Ajout d''une affectation (MAG1, 581)', '43479F'),
(214, 'moussa', '2012-09-19 11:35:35', 'Ajout d''une affectation (MAG1, 579)', '43479F'),
(215, 'moussa', '2012-09-19 11:37:03', 'Ajout d''une affectation (MAG1, 580)', '43479F'),
(216, 'moussa', '2012-09-19 11:38:19', 'Ajout d''une affectation (MAG1, 577)', '43479F'),
(217, 'moussa', '2012-09-19 11:41:15', 'Ajout d''une affectation (MAG1, 543)', '43479F'),
(218, 'moussa', '2012-09-19 11:42:27', 'Ajout d''une affectation (MAG1, 540)', '43479F'),
(219, 'moussa', '2012-09-19 11:43:52', 'Ajout d''une affectation (MAG1, 541)', '43479F'),
(220, 'moussa', '2012-09-19 11:45:31', 'Ajout d''une affectation (MAG1, 539)', '43479F'),
(221, 'moussa', '2012-09-19 12:04:23', 'Ajout d''une affectation (MAG1, 578)', '43479F'),
(222, 'moussa', '2012-09-19 12:06:13', 'Ajout d''une affectation (MAG1, 556)', '43479F'),
(223, 'moussa', '2012-09-19 12:07:42', 'Ajout d''une affectation (MAG1, 557)', '43479F'),
(224, 'moussa', '2012-09-19 12:09:24', 'Ajout d''une affectation (MAG1, 555)', '43479F'),
(225, 'moussa', '2012-09-19 12:10:27', 'Ajout d''une affectation (MAG1, 554)', '43479F'),
(226, 'moussa', '2012-09-19 12:16:18', 'Ajout d''une affectation (MAG1, 532)', '43479F'),
(227, 'moussa', '2012-09-19 12:18:04', 'Ajout d''une affectation (MAG1, 537)', '43479F'),
(228, 'moussa', '2012-09-19 12:18:38', 'Ajout d''une affectation (MAG1, 542)', '43479F'),
(229, 'moussa', '2012-09-19 12:21:23', 'Ajout d''une affectation (MAG1, 551)', '43479F'),
(230, 'moussa', '2012-09-19 12:22:53', 'Ajout d''une affectation (MAG1, 548)', '43479F'),
(231, 'moussa', '2012-09-19 12:23:23', 'Ajout d''une affectation (MAG1, 549)', '43479F'),
(232, 'moussa', '2012-09-19 12:24:03', 'Ajout d''une affectation (MAG1, 546)', '43479F'),
(233, 'moussa', '2012-09-19 12:26:43', 'Ajout d''une affectation (MAG1, 520)', '43479F'),
(234, 'moussa', '2012-09-19 12:27:36', 'Ajout d''une affectation (MAG1, 531)', '43479F'),
(235, 'moussa', '2012-09-19 12:28:53', 'Ajout d''une affectation (MAG1, 696)', '43479F'),
(236, 'moussa', '2012-09-19 12:29:18', 'Ajout d''une affectation (MAG1, 519)', '43479F'),
(237, 'moussa', '2012-09-19 12:30:07', 'Ajout d''une affectation (MAG1, 626)', '43479F'),
(238, 'moussa', '2012-09-19 12:30:50', 'Ajout d''une affectation (MAG1, 702)', '43479F'),
(239, 'moussa', '2012-09-19 12:31:23', 'Ajout d''une affectation (MAG1, 407)', '43479F'),
(240, 'moussa', '2012-09-19 12:33:41', 'Ajout d''une affectation (MAG1, 518)', '43479F'),
(241, 'moussa', '2012-09-19 12:34:18', 'Ajout d''une affectation (MAG1, 517)', '43479F'),
(242, 'moussa', '2012-09-19 12:35:31', 'Ajout d''une affectation (MAG1, 523)', '43479F'),
(243, 'moussa', '2012-09-19 12:36:25', 'Ajout d''une affectation (MAG1, 525)', '43479F'),
(244, 'moussa', '2012-09-19 12:37:47', 'Ajout d''une affectation (MAG1, 524)', '43479F'),
(245, 'moussa', '2012-09-19 12:42:07', 'Ajout d''une affectation (MAG1, 530)', '43479F'),
(246, 'moussa', '2012-09-19 12:42:32', 'Ajout d''une affectation (MAG1, 526)', '43479F'),
(247, 'moussa', '2012-09-19 12:43:02', 'Ajout d''une affectation (MAG1, 529)', '43479F'),
(248, 'moussa', '2012-09-19 12:44:05', 'Ajout d''une affectation (MAG1, 528)', '43479F'),
(249, 'moussa', '2012-09-19 12:44:36', 'Ajout d''une affectation (MAG1, 527)', '43479F'),
(250, 'moussa', '2012-09-19 12:45:32', 'Ajout d''une affectation (MAG1, 563)', '43479F'),
(251, 'moussa', '2012-09-19 12:46:08', 'Ajout d''une affectation (MAG1, 462)', '43479F'),
(252, 'moussa', '2012-09-19 12:46:49', 'Ajout d''une affectation (MAG1, 561)', '43479F'),
(253, 'moussa', '2012-09-19 12:47:17', 'Ajout d''une affectation (MAG1, 560)', '43479F'),
(254, 'moussa', '2012-09-19 12:50:19', 'Ajout d''une affectation (MAG1, 564)', '43479F'),
(259, 'moussa', '2012-09-19 15:46:24', 'Tentative de connexion au systÃ¨me Ã©chouÃ©e', ''),
(260, 'moussa', '2012-09-19 15:46:44', 'Connexion au systÃ¨me', '43479F'),
(261, 'moussa', '2012-09-19 16:08:25', 'Ajout d''une affectation (MAG1, 639)', '43479F'),
(262, 'moussa', '2012-09-19 16:12:12', 'Ajout d''une affectation (MAG1, 472)', '43479F'),
(263, 'moussa', '2012-09-19 16:19:14', 'Ajout d''une affectation (MAG1, 562)', '43479F'),
(264, 'moussa', '2012-09-19 16:23:13', 'Ajout d''une affectation (MAG1, 521)', '43479F'),
(265, 'moussa', '2012-09-19 16:24:34', 'Ajout d''une affectation (MAG1, 522)', '43479F'),
(266, 'moussa', '2012-09-19 16:27:15', 'Ajout d''une affectation (MAG1, 628)', '43479F'),
(267, 'moussa', '2012-09-19 17:06:42', 'Connexion au systÃ¨me', '43479F'),
(268, 'moussa', '2012-09-19 17:17:45', 'Modification d''une affectation (628, MAG 4)', '43479F'),
(269, 'moussa', '2012-09-19 17:19:59', 'Ajout d''une affectation (MAG 4, 479)', '43479F'),
(270, 'moussa', '2012-09-19 17:20:47', 'Ajout d''une affectation (MAG 4, 480)', '43479F'),
(271, 'moussa', '2012-09-19 17:24:51', 'Ajout d''une affectation (MAG 4, 481)', '43479F'),
(272, 'moussa', '2012-09-19 17:25:32', 'Ajout d''une affectation (MAG 4, 482)', '43479F'),
(273, 'moussa', '2012-09-19 17:26:01', 'Ajout d''une affectation (MAG 4, 483)', '43479F'),
(274, 'moussa', '2012-09-19 17:26:19', 'Ajout d''une affectation (MAG 4, 484)', '43479F'),
(275, 'moussa', '2012-09-19 17:27:20', 'Ajout d''une affectation (MAG 4, 485)', '43479F'),
(276, 'moussa', '2012-09-19 17:28:11', 'Ajout d''une affectation (MAG 4, 486)', '43479F'),
(277, 'moussa', '2012-09-19 17:28:53', 'Ajout d''une affectation (MAG 4, 487)', '43479F'),
(278, 'moussa', '2012-09-19 17:29:45', 'Ajout d''une affectation (MAG 4, 488)', '43479F'),
(279, 'moussa', '2012-09-19 17:30:23', 'Ajout d''une affectation (MAG 4, 489)', '43479F'),
(280, 'moussa', '2012-09-19 17:31:03', 'Ajout d''une affectation (MAG 4, 490)', '43479F'),
(281, 'moussa', '2012-09-19 17:31:44', 'Ajout d''une affectation (MAG 4, 491)', '43479F'),
(282, 'moussa', '2012-09-20 08:31:11', 'Tentative de connexion au systÃ¨me Ã©chouÃ©e', ''),
(283, 'moussa', '2012-09-20 08:31:32', 'Connexion au systÃ¨me', '43479F'),
(284, 'moussa', '2012-09-20 08:34:35', 'Ajout d''une affectation (MAG 4, 695)', '43479F'),
(285, 'moussa', '2012-09-20 08:35:37', 'Ajout d''une affectation (MAG 4, 493)', '43479F'),
(286, 'moussa', '2012-09-20 08:38:15', 'Ajout d''une affectation (MAG 4, 494)', '43479F'),
(287, 'moussa', '2012-09-20 08:42:50', 'Ajout d''une affectation (MAG 4, 495)', '43479F'),
(288, 'moussa', '2012-09-20 08:44:27', 'Ajout d''une affectation (MAG 4, 496)', '43479F'),
(289, 'moussa', '2012-09-20 08:45:23', 'Ajout d''une affectation (MAG 4, 497)', '43479F'),
(290, 'moussa', '2012-09-20 08:46:24', 'Ajout d''une affectation (MAG 4, 663)', '43479F'),
(291, 'moussa', '2012-09-20 08:48:30', 'Ajout d''une affectation (MAG 4, 498)', '43479F'),
(292, 'moussa', '2012-09-20 08:48:50', 'Ajout d''une affectation (MAG 4, 499)', '43479F'),
(293, 'moussa', '2012-09-20 08:55:59', 'Ajout d''une affectation (MAG 4, 664)', '43479F'),
(294, 'moussa', '2012-09-20 08:56:42', 'Ajout d''une affectation (MAG 4, 514)', '43479F'),
(295, 'moussa', '2012-09-20 08:57:06', 'Ajout d''une affectation (MAG 4, 501)', '43479F'),
(296, 'moussa', '2012-09-20 08:59:29', 'Ajout d''une affectation (MAG 4, 502)', '43479F'),
(297, 'moussa', '2012-09-20 09:00:08', 'Ajout d''une affectation (MAG 4, 503)', '43479F'),
(298, 'moussa', '2012-09-20 09:00:41', 'Ajout d''une affectation (MAG 4, 646)', '43479F'),
(299, 'moussa', '2012-09-20 09:01:10', 'Ajout d''une affectation (MAG 4, 505)', '43479F'),
(300, 'moussa', '2012-09-20 09:01:48', 'Ajout d''une affectation (MAG 4, 506)', '43479F'),
(301, 'moussa', '2012-09-20 09:03:22', 'Ajout d''une affectation (MAG 4, 694)', '43479F'),
(302, 'moussa', '2012-09-20 09:03:55', 'Ajout d''une affectation (MAG 4, 508)', '43479F'),
(303, 'moussa', '2012-09-20 09:04:30', 'Ajout d''une affectation (MAG 4, 509)', '43479F'),
(304, 'moussa', '2012-09-20 09:05:16', 'Ajout d''une affectation (MAG 4, 645)', '43479F'),
(305, 'moussa', '2012-09-20 09:05:47', 'Ajout d''une affectation (MAG 4, 511)', '43479F'),
(306, 'moussa', '2012-09-20 09:06:25', 'Ajout d''une affectation (MAG 4, 512)', '43479F'),
(307, 'moussa', '2012-09-20 09:07:22', 'Ajout d''une affectation (MAG 4, 513)', '43479F'),
(308, 'moussa', '2012-09-20 09:07:49', 'Ajout d''une affectation (MAG 4, 500)', '43479F'),
(309, 'moussa', '2012-09-20 09:08:28', 'Ajout d''une affectation (MAG 4, 701)', '43479F'),
(310, 'moussa', '2012-09-20 09:09:01', 'Ajout d''une affectation (MAG 4, 627)', '43479F'),
(311, 'moussa', '2012-09-20 09:54:14', 'DÃ©connexion du systÃ¨me Ã©chouÃ©e', '43479F'),
(313, 'maiga', '2012-09-20 10:23:48', 'Connexion au systÃ¨me', 'MM'),
(314, 'moussa', '2012-09-20 10:34:53', 'Connexion au systÃ¨me', '43479F'),
(315, 'moussa', '2012-09-20 10:41:31', 'Ajout d''une programmation (1, 1DOT, 49)', '43479F'),
(316, 'moussa', '2012-09-20 10:41:31', 'Ajout des lignes de programmation', '43479F'),
(317, 'moussa', '2012-09-20 10:45:03', 'Ajout d''une programmation (2, 1DOT, 50)', '43479F'),
(318, 'moussa', '2012-09-20 10:45:03', 'Ajout des lignes de programmation', '43479F'),
(319, 'kibsa', '2012-09-20 10:47:52', 'Connexion au systÃ¨me', '677D'),
(320, 'moussa', '2012-09-20 11:00:42', 'Ajout d''une programmation (3, 1DOT, 51)', '43479F'),
(321, 'moussa', '2012-09-20 11:00:42', 'Ajout des lignes de programmation', '43479F'),
(322, 'moussa', '2012-09-20 11:01:16', 'Ajout d''une programmation (3, 1DOT, 51)', '43479F'),
(323, 'moussa', '2012-09-20 11:01:16', 'Modification des lignes de programmation', '43479F'),
(324, 'moussa', '2012-09-20 11:03:37', 'Ajout d''une programmation (4, 1DOT, 52)', '43479F'),
(325, 'moussa', '2012-09-20 11:03:37', 'Ajout des lignes de programmation', '43479F'),
(326, 'moussa', '2012-09-20 11:04:46', 'Ajout d''une programmation (5, 1DOT, 53)', '43479F'),
(327, 'moussa', '2012-09-20 11:04:46', 'Ajout des lignes de programmation', '43479F'),
(328, 'moussa', '2012-09-20 11:06:02', 'Ajout d''une programmation (6, 1DOT, 54)', '43479F'),
(329, 'moussa', '2012-09-20 11:06:02', 'Ajout des lignes de programmation', '43479F'),
(330, 'moussa', '2012-09-20 11:07:08', 'Ajout d''une programmation (7, 1DOT, 55)', '43479F'),
(331, 'moussa', '2012-09-20 11:07:09', 'Ajout des lignes de programmation', '43479F'),
(332, 'moussa', '2012-09-20 11:07:57', 'Ajout d''une programmation (8, 1DOT, 62)', '43479F'),
(333, 'moussa', '2012-09-20 11:07:57', 'Ajout des lignes de programmation', '43479F'),
(334, 'moussa', '2012-09-20 11:09:06', 'Ajout d''une programmation (9, 1DOT, 574)', '43479F'),
(335, 'moussa', '2012-09-20 11:09:07', 'Ajout des lignes de programmation', '43479F'),
(336, 'moussa', '2012-09-20 11:10:05', 'Ajout d''une programmation (10, 1DOT, 56)', '43479F'),
(337, 'moussa', '2012-09-20 11:10:06', 'Ajout des lignes de programmation', '43479F'),
(338, 'moussa', '2012-09-20 11:10:58', 'Ajout d''une programmation (11, 1DOT, 59)', '43479F'),
(339, 'moussa', '2012-09-20 11:10:58', 'Ajout des lignes de programmation', '43479F'),
(340, 'moussa', '2012-09-20 11:11:48', 'Ajout d''une programmation (12, 1DOT, 60)', '43479F'),
(341, 'moussa', '2012-09-20 11:11:48', 'Ajout des lignes de programmation', '43479F'),
(342, 'moussa', '2012-09-20 11:15:26', 'Ajout d''une programmation (13, 1DOT, 61)', '43479F'),
(343, 'moussa', '2012-09-20 11:15:26', 'Ajout des lignes de programmation', '43479F'),
(344, 'moussa', '2012-09-20 11:20:06', 'Ajout d''une programmation (14, 1DOT, 58)', '43479F'),
(345, 'moussa', '2012-09-20 11:20:06', 'Ajout des lignes de programmation', '43479F'),
(346, 'moussa', '2012-09-20 11:21:10', 'Ajout d''une programmation (15, 1DOT, 63)', '43479F'),
(347, 'moussa', '2012-09-20 11:21:10', 'Ajout des lignes de programmation', '43479F'),
(348, 'moussa', '2012-09-20 11:22:15', 'Ajout d''une programmation (16, 1DOT, 64)', '43479F'),
(349, 'moussa', '2012-09-20 11:22:15', 'Ajout des lignes de programmation', '43479F'),
(350, 'moussa', '2012-09-20 11:23:18', 'Ajout d''une programmation (17, 1DOT, 65)', '43479F'),
(351, 'moussa', '2012-09-20 11:23:19', 'Ajout des lignes de programmation', '43479F'),
(352, 'moussa', '2012-09-20 11:24:15', 'Ajout d''une programmation (18, 1DOT, 66)', '43479F'),
(353, 'moussa', '2012-09-20 11:24:15', 'Ajout des lignes de programmation', '43479F'),
(354, 'moussa', '2012-09-20 11:27:42', 'Ajout d''une programmation (19, 1DOT, 68)', '43479F'),
(355, 'moussa', '2012-09-20 11:27:42', 'Ajout des lignes de programmation', '43479F'),
(356, 'moussa', '2012-09-20 11:29:45', 'Ajout d''une programmation (20, 1DOT, 69)', '43479F'),
(357, 'moussa', '2012-09-20 11:29:45', 'Ajout des lignes de programmation', '43479F'),
(358, 'moussa', '2012-09-20 11:32:31', 'Ajout d''une programmation (21, 1DOT, 70)', '43479F'),
(359, 'moussa', '2012-09-20 11:32:31', 'Ajout des lignes de programmation', '43479F'),
(360, 'moussa', '2012-09-20 11:33:29', 'Ajout d''une programmation (22, 1DOT, 71)', '43479F'),
(361, 'moussa', '2012-09-20 11:33:29', 'Ajout des lignes de programmation', '43479F'),
(362, 'moussa', '2012-09-20 11:34:47', 'Ajout d''une programmation (12, 1DOT, 60)', '43479F'),
(363, 'moussa', '2012-09-20 11:34:47', 'Modification des lignes de programmation', '43479F'),
(364, 'moussa', '2012-09-20 11:38:04', 'Ajout d''une programmation (23, 1DOT, 72)', '43479F'),
(365, 'moussa', '2012-09-20 11:38:04', 'Ajout des lignes de programmation', '43479F'),
(366, 'moussa', '2012-09-20 11:41:18', 'Ajout d''une programmation (24, 1DOT, 73)', '43479F'),
(367, 'moussa', '2012-09-20 11:41:18', 'Ajout des lignes de programmation', '43479F'),
(368, 'moussa', '2012-09-20 11:42:20', 'Ajout d''une programmation (25, 1DOT, 74)', '43479F'),
(369, 'moussa', '2012-09-20 11:42:20', 'Ajout des lignes de programmation', '43479F'),
(374, 'moussa', '2012-09-20 12:54:38', 'Connexion au systÃ¨me', '43479F'),
(375, 'moussa', '2012-09-20 13:00:58', 'Ajout d''une programmation (26, 1DOT, 21)', '43479F'),
(376, 'moussa', '2012-09-20 13:00:58', 'Ajout des lignes de programmation', '43479F'),
(377, 'moussa', '2012-09-20 13:03:58', 'Ajout d''une programmation (27, 1DOT, 22)', '43479F'),
(378, 'moussa', '2012-09-20 13:03:58', 'Ajout des lignes de programmation', '43479F'),
(379, 'moussa', '2012-09-20 13:09:19', 'Ajout d''une programmation (28, 1DOT, 23)', '43479F'),
(380, 'moussa', '2012-09-20 13:09:19', 'Ajout des lignes de programmation', '43479F'),
(381, 'moussa', '2012-09-20 13:12:16', 'Ajout d''une programmation (29, 1DOT, 24)', '43479F'),
(382, 'moussa', '2012-09-20 13:12:16', 'Ajout des lignes de programmation', '43479F'),
(383, 'moussa', '2012-09-20 13:14:20', 'Ajout d''une programmation (30, 1DOT, 25)', '43479F'),
(384, 'moussa', '2012-09-20 13:14:20', 'Ajout des lignes de programmation', '43479F'),
(386, 'moussa', '2012-09-20 14:58:13', 'Connexion au systÃ¨me', '43479F'),
(387, 'moussa', '2012-09-20 15:00:59', 'Ajout d''une programmation (31, 1DOT, 26)', '43479F'),
(388, 'moussa', '2012-09-20 15:00:59', 'Ajout des lignes de programmation', '43479F'),
(389, 'moussa', '2012-09-20 15:02:40', 'Ajout d''une programmation (32, 1DOT, 27)', '43479F'),
(390, 'moussa', '2012-09-20 15:02:40', 'Ajout des lignes de programmation', '43479F'),
(391, 'moussa', '2012-09-20 15:04:46', 'Ajout d''une programmation (33, 1DOT, 28)', '43479F'),
(392, 'moussa', '2012-09-20 15:04:46', 'Ajout des lignes de programmation', '43479F'),
(393, 'moussa', '2012-09-20 15:06:50', 'Ajout d''une programmation (34, 1DOT, 29)', '43479F'),
(394, 'moussa', '2012-09-20 15:06:50', 'Ajout des lignes de programmation', '43479F'),
(395, 'moussa', '2012-09-20 15:08:02', 'Ajout d''une programmation (35, 1DOT, 30)', '43479F'),
(396, 'moussa', '2012-09-20 15:08:02', 'Ajout des lignes de programmation', '43479F'),
(397, 'moussa', '2012-09-20 15:09:51', 'Ajout d''une programmation (36, 1DOT, 31)', '43479F'),
(398, 'moussa', '2012-09-20 15:09:51', 'Ajout des lignes de programmation', '43479F'),
(399, 'moussa', '2012-09-20 15:12:45', 'Ajout d''une programmation (37, 1DOT, 32)', '43479F'),
(400, 'moussa', '2012-09-20 15:12:45', 'Ajout des lignes de programmation', '43479F'),
(401, 'moussa', '2012-09-20 15:13:56', 'Ajout d''une programmation (38, 1DOT, 33)', '43479F'),
(402, 'moussa', '2012-09-20 15:13:56', 'Ajout des lignes de programmation', '43479F'),
(403, 'moussa', '2012-09-20 15:15:55', 'Ajout d''une programmation (39, 1DOT, 34)', '43479F'),
(404, 'moussa', '2012-09-20 15:15:55', 'Ajout des lignes de programmation', '43479F'),
(405, 'moussa', '2012-09-20 15:17:35', 'Ajout d''une programmation (40, 1DOT, 35)', '43479F'),
(406, 'moussa', '2012-09-20 15:17:35', 'Ajout des lignes de programmation', '43479F'),
(407, 'moussa', '2012-09-20 15:19:23', 'Ajout d''une programmation (41, 1DOT, 36)', '43479F'),
(408, 'moussa', '2012-09-20 15:19:23', 'Ajout des lignes de programmation', '43479F'),
(409, 'moussa', '2012-09-20 15:21:01', 'Ajout d''une programmation (42, 1DOT, 37)', '43479F'),
(410, 'moussa', '2012-09-20 15:21:01', 'Ajout des lignes de programmation', '43479F'),
(411, 'moussa', '2012-09-20 15:22:11', 'Ajout d''une programmation (43, 1DOT, 38)', '43479F'),
(412, 'moussa', '2012-09-20 15:22:11', 'Ajout des lignes de programmation', '43479F'),
(413, 'moussa', '2012-09-20 15:23:35', 'Ajout d''une programmation (44, 1DOT, 39)', '43479F'),
(414, 'moussa', '2012-09-20 15:23:35', 'Ajout des lignes de programmation', '43479F'),
(415, 'moussa', '2012-09-20 15:25:24', 'Ajout d''une programmation (45, 1DOT, 40)', '43479F'),
(416, 'moussa', '2012-09-20 15:25:24', 'Ajout des lignes de programmation', '43479F'),
(417, 'moussa', '2012-09-20 15:27:07', 'Ajout d''une programmation (46, 1DOT, 41)', '43479F'),
(418, 'moussa', '2012-09-20 15:27:07', 'Ajout des lignes de programmation', '43479F'),
(419, 'moussa', '2012-09-20 15:39:37', 'Ajout d''une programmation (9, 1DOT, 57)', '43479F'),
(420, 'moussa', '2012-09-20 15:39:37', 'Modification des lignes de programmation', '43479F'),
(425, 'moussa', '2012-09-20 16:35:02', 'Tentative de connexion au systÃ¨me Ã©chouÃ©e', ''),
(426, 'moussa', '2012-09-20 16:35:17', 'Connexion au systÃ¨me', '43479F'),
(427, 'moussa', '2012-09-20 16:36:41', 'Ajout d''une programmation (47, 1DOT, 42)', '43479F'),
(428, 'moussa', '2012-09-20 16:36:41', 'Ajout des lignes de programmation', '43479F'),
(429, 'moussa', '2012-09-20 16:38:08', 'Ajout d''une programmation (48, 1DOT, 43)', '43479F'),
(430, 'moussa', '2012-09-20 16:38:08', 'Ajout des lignes de programmation', '43479F'),
(431, 'moussa', '2012-09-20 16:39:37', 'Ajout d''une programmation (49, 1DOT, 44)', '43479F'),
(432, 'moussa', '2012-09-20 16:39:37', 'Ajout des lignes de programmation', '43479F'),
(433, 'moussa', '2012-09-20 16:40:52', 'Ajout d''une programmation (50, 1DOT, 45)', '43479F'),
(434, 'moussa', '2012-09-20 16:40:53', 'Ajout des lignes de programmation', '43479F'),
(435, 'moussa', '2012-09-20 16:42:01', 'Ajout d''une programmation (51, 1DOT, 46)', '43479F'),
(436, 'moussa', '2012-09-20 16:42:01', 'Ajout des lignes de programmation', '43479F'),
(437, 'moussa', '2012-09-20 16:43:06', 'Ajout d''une programmation (52, 1DOT, 47)', '43479F'),
(438, 'moussa', '2012-09-20 16:43:06', 'Ajout des lignes de programmation', '43479F'),
(439, 'moussa', '2012-09-20 16:45:57', 'Ajout d''une programmation (53, 1DOT, 567)', '43479F'),
(440, 'moussa', '2012-09-20 16:45:57', 'Ajout des lignes de programmation', '43479F'),
(441, 'moussa', '2012-09-20 16:55:09', 'Ajout d''une programmation (54, 1DOT, 48)', '43479F'),
(442, 'moussa', '2012-09-20 16:55:09', 'Ajout des lignes de programmation', '43479F'),
(443, 'moussa', '2012-09-20 16:56:33', 'Ajout d''une programmation (55, 1DOT, 629)', '43479F'),
(444, 'moussa', '2012-09-20 16:56:33', 'Ajout des lignes de programmation', '43479F'),
(445, 'moussa', '2012-09-20 16:57:33', 'Ajout d''une programmation (56, 1DOT, 565)', '43479F'),
(446, 'moussa', '2012-09-20 16:57:33', 'Ajout des lignes de programmation', '43479F'),
(447, 'moussa', '2012-09-20 17:01:03', 'Ajout d''une programmation (57, 1DOT, 566)', '43479F'),
(448, 'moussa', '2012-09-20 17:01:03', 'Ajout des lignes de programmation', '43479F'),
(449, 'moussa', '2012-09-20 17:03:56', 'Ajout d''une programmation (58, 1DOT, 75)', '43479F'),
(450, 'moussa', '2012-09-20 17:03:56', 'Ajout des lignes de programmation', '43479F'),
(451, 'moussa', '2012-09-20 17:05:33', 'Ajout d''une programmation (59, 1DOT, 76)', '43479F'),
(452, 'moussa', '2012-09-20 17:05:33', 'Ajout des lignes de programmation', '43479F'),
(453, 'moussa', '2012-09-20 17:06:38', 'Ajout d''une programmation (60, 1DOT, 77)', '43479F'),
(454, 'moussa', '2012-09-20 17:06:38', 'Ajout des lignes de programmation', '43479F'),
(455, 'moussa', '2012-09-20 17:07:55', 'Ajout d''une programmation (61, 1DOT, 78)', '43479F'),
(456, 'moussa', '2012-09-20 17:07:55', 'Ajout des lignes de programmation', '43479F'),
(457, 'moussa', '2012-09-20 17:09:07', 'Ajout d''une programmation (62, 1DOT, 649)', '43479F'),
(458, 'moussa', '2012-09-20 17:09:07', 'Ajout des lignes de programmation', '43479F'),
(459, 'moussa', '2012-09-20 17:10:07', 'Ajout d''une programmation (63, 1DOT, 616)', '43479F'),
(460, 'moussa', '2012-09-20 17:10:07', 'Ajout des lignes de programmation', '43479F'),
(461, 'moussa', '2012-09-20 17:11:19', 'Ajout d''une programmation (64, 1DOT, 617)', '43479F'),
(462, 'moussa', '2012-09-20 17:11:19', 'Ajout des lignes de programmation', '43479F'),
(463, 'moussa', '2012-09-20 17:12:37', 'Ajout d''une programmation (65, 1DOT, 652)', '43479F'),
(464, 'moussa', '2012-09-20 17:12:37', 'Ajout des lignes de programmation', '43479F'),
(465, 'moussa', '2012-09-20 17:13:41', 'Ajout d''une programmation (66, 1DOT, 650)', '43479F'),
(466, 'moussa', '2012-09-20 17:13:41', 'Ajout des lignes de programmation', '43479F'),
(467, 'moussa', '2012-09-20 17:14:42', 'Ajout d''une programmation (67, 1DOT, 615)', '43479F'),
(468, 'moussa', '2012-09-20 17:14:42', 'Ajout des lignes de programmation', '43479F'),
(469, 'moussa', '2012-09-20 17:16:08', 'Ajout d''une programmation (68, 1DOT, 638)', '43479F'),
(470, 'moussa', '2012-09-20 17:16:08', 'Ajout des lignes de programmation', '43479F'),
(471, 'moussa', '2012-09-20 17:17:18', 'Ajout d''une programmation (69, 1DOT, 648)', '43479F'),
(472, 'moussa', '2012-09-20 17:17:18', 'Ajout des lignes de programmation', '43479F'),
(473, 'moussa', '2012-09-20 17:18:38', 'Ajout d''une programmation (68, 1DOT, 638)', '43479F'),
(474, 'moussa', '2012-09-20 17:18:38', 'Modification des lignes de programmation', '43479F'),
(475, 'moussa', '2012-09-20 17:21:29', 'Ajout d''une programmation (70, 1DOT, 79)', '43479F'),
(476, 'moussa', '2012-09-20 17:21:29', 'Ajout des lignes de programmation', '43479F'),
(477, 'moussa', '2012-09-20 17:22:55', 'Ajout d''une programmation (71, 1DOT, 80)', '43479F'),
(478, 'moussa', '2012-09-20 17:22:55', 'Ajout des lignes de programmation', '43479F'),
(479, 'moussa', '2012-09-20 17:24:04', 'Ajout d''une programmation (72, 1DOT, 81)', '43479F'),
(480, 'moussa', '2012-09-20 17:24:04', 'Ajout des lignes de programmation', '43479F'),
(481, 'moussa', '2012-09-20 17:25:12', 'Ajout d''une programmation (73, 1DOT, 82)', '43479F'),
(482, 'moussa', '2012-09-20 17:25:12', 'Ajout des lignes de programmation', '43479F'),
(483, 'moussa', '2012-09-20 17:26:46', 'Ajout d''une programmation (74, 1DOT, 83)', '43479F'),
(484, 'moussa', '2012-09-20 17:26:46', 'Ajout des lignes de programmation', '43479F'),
(485, 'moussa', '2012-09-20 17:27:49', 'Ajout d''une programmation (75, 1DOT, 599)', '43479F'),
(486, 'moussa', '2012-09-20 17:27:49', 'Ajout des lignes de programmation', '43479F'),
(487, 'moussa', '2012-09-20 17:28:43', 'Ajout d''une programmation (76, 1DOT, 84)', '43479F'),
(488, 'moussa', '2012-09-20 17:28:43', 'Ajout des lignes de programmation', '43479F'),
(489, 'moussa', '2012-09-20 17:37:54', 'DÃ©connexion du systÃ¨me Ã©chouÃ©e', '43479F'),
(492, 'moussa', '2012-09-21 08:14:04', 'Connexion au systÃ¨me', '43479F'),
(493, 'moussa', '2012-09-21 08:18:05', 'Ajout d''une programmation (77, 1DOT, 67)', '43479F'),
(494, 'moussa', '2012-09-21 08:18:05', 'Ajout des lignes de programmation', '43479F'),
(495, 'moussa', '2012-09-21 08:28:44', 'Ajout d''une programmation (78, 1DOT, 86)', '43479F'),
(496, 'moussa', '2012-09-21 08:28:44', 'Ajout des lignes de programmation', '43479F'),
(497, 'moussa', '2012-09-21 08:29:38', 'Ajout d''une programmation (79, 1DOT, 87)', '43479F'),
(498, 'moussa', '2012-09-21 08:29:38', 'Ajout des lignes de programmation', '43479F'),
(499, 'moussa', '2012-09-21 08:30:59', 'Ajout d''une programmation (80, 1DOT, 88)', '43479F'),
(500, 'moussa', '2012-09-21 08:30:59', 'Ajout des lignes de programmation', '43479F'),
(501, 'moussa', '2012-09-21 08:31:56', 'Ajout d''une programmation (81, 1DOT, 89)', '43479F'),
(502, 'moussa', '2012-09-21 08:31:57', 'Ajout des lignes de programmation', '43479F'),
(503, 'moussa', '2012-09-21 08:32:50', 'Ajout d''une programmation (82, 1DOT, 610)', '43479F'),
(504, 'moussa', '2012-09-21 08:32:50', 'Ajout des lignes de programmation', '43479F'),
(505, 'moussa', '2012-09-21 08:35:23', 'Ajout d''une programmation (83, 1DOT, 91)', '43479F'),
(506, 'moussa', '2012-09-21 08:35:23', 'Ajout des lignes de programmation', '43479F'),
(507, 'moussa', '2012-09-21 08:36:25', 'Ajout d''une programmation (84, 1DOT, 603)', '43479F'),
(508, 'moussa', '2012-09-21 08:36:25', 'Ajout des lignes de programmation', '43479F'),
(509, 'moussa', '2012-09-21 08:54:12', 'Ajout d''une programmation (85, 1DOT, 94)', '43479F'),
(510, 'moussa', '2012-09-21 08:54:12', 'Ajout des lignes de programmation', '43479F'),
(511, 'moussa', '2012-09-21 08:55:55', 'Ajout d''une programmation (86, 1DOT, 95)', '43479F'),
(512, 'moussa', '2012-09-21 08:55:55', 'Ajout des lignes de programmation', '43479F'),
(513, 'moussa', '2012-09-21 08:59:25', 'Ajout d''une programmation (87, 1DOT, 96)', '43479F'),
(514, 'moussa', '2012-09-21 08:59:25', 'Ajout des lignes de programmation', '43479F'),
(515, 'moussa', '2012-09-21 09:00:38', 'Ajout d''une programmation (88, 1DOT, 97)', '43479F'),
(516, 'moussa', '2012-09-21 09:00:38', 'Ajout des lignes de programmation', '43479F'),
(517, 'moussa', '2012-09-21 09:01:57', 'Ajout d''une programmation (89, 1DOT, 98)', '43479F'),
(518, 'moussa', '2012-09-21 09:01:57', 'Ajout des lignes de programmation', '43479F'),
(519, 'moussa', '2012-09-21 09:02:58', 'Ajout d''une programmation (90, 1DOT, 604)', '43479F'),
(520, 'moussa', '2012-09-21 09:02:59', 'Ajout des lignes de programmation', '43479F'),
(521, 'moussa', '2012-09-21 09:03:48', 'Ajout d''une programmation (91, 1DOT, 100)', '43479F'),
(522, 'moussa', '2012-09-21 09:03:49', 'Ajout des lignes de programmation', '43479F'),
(523, 'moussa', '2012-09-21 09:07:10', 'Ajout d''une programmation (92, 1DOT, 101)', '43479F'),
(524, 'moussa', '2012-09-21 09:07:10', 'Ajout des lignes de programmation', '43479F'),
(525, 'moussa', '2012-09-21 09:08:32', 'Ajout d''une programmation (93, 1DOT, 102)', '43479F'),
(526, 'moussa', '2012-09-21 09:08:32', 'Ajout des lignes de programmation', '43479F'),
(527, 'moussa', '2012-09-21 09:09:33', 'Ajout d''une programmation (94, 1DOT, 613)', '43479F'),
(528, 'moussa', '2012-09-21 09:09:33', 'Ajout des lignes de programmation', '43479F'),
(529, 'moussa', '2012-09-21 09:15:08', 'Ajout d''une programmation (95, 1DOT, 600)', '43479F'),
(530, 'moussa', '2012-09-21 09:15:08', 'Ajout des lignes de programmation', '43479F'),
(531, 'moussa', '2012-09-21 09:15:56', 'Ajout d''une programmation (96, 1DOT, 680)', '43479F'),
(532, 'moussa', '2012-09-21 09:15:56', 'Ajout des lignes de programmation', '43479F'),
(533, 'moussa', '2012-09-21 09:16:56', 'Ajout d''une programmation (97, 1DOT, 106)', '43479F'),
(534, 'moussa', '2012-09-21 09:16:56', 'Ajout des lignes de programmation', '43479F'),
(535, 'moussa', '2012-09-21 09:17:39', 'Ajout d''une programmation (98, 1DOT, 107)', '43479F'),
(536, 'moussa', '2012-09-21 09:17:39', 'Ajout des lignes de programmation', '43479F'),
(537, 'moussa', '2012-09-21 09:19:13', 'Ajout d''une programmation (99, 1DOT, 108)', '43479F'),
(538, 'moussa', '2012-09-21 09:19:13', 'Ajout des lignes de programmation', '43479F'),
(541, 'moussa', '2012-09-21 09:21:03', 'Ajout d''une programmation (100, 1DOT, 109)', '43479F'),
(542, 'moussa', '2012-09-21 09:21:04', 'Ajout des lignes de programmation', '43479F'),
(543, 'kibsa', '2012-09-21 09:21:10', 'Connexion au systÃ¨me', '677D'),
(544, 'moussa', '2012-09-21 09:25:09', 'Ajout d''une programmation (101, 1DOT, 110)', '43479F'),
(545, 'moussa', '2012-09-21 09:25:09', 'Ajout des lignes de programmation', '43479F'),
(546, 'moussa', '2012-09-21 09:31:34', 'Ajout d''une programmation (102, 1DOT, 111)', '43479F'),
(547, 'moussa', '2012-09-21 09:31:34', 'Ajout des lignes de programmation', '43479F'),
(548, 'moussa', '2012-09-21 09:33:27', 'Ajout d''une programmation (103, 1DOT, 112)', '43479F'),
(549, 'moussa', '2012-09-21 09:33:27', 'Ajout des lignes de programmation', '43479F'),
(550, 'kibsa', '2012-09-21 09:35:26', 'Connexion au systÃ¨me', '677D'),
(551, 'kibsa', '2012-09-21 09:38:09', 'Ajout d''une programmation (26, 1DOT, 21)', '677D'),
(552, 'kibsa', '2012-09-21 09:38:09', 'Modification des lignes de programmation', '677D'),
(553, 'moussa', '2012-09-21 09:41:07', 'Ajout d''une programmation (104, 1DOT, 113)', '43479F'),
(554, 'moussa', '2012-09-21 09:41:07', 'Ajout des lignes de programmation', '43479F'),
(555, 'moussa', '2012-09-21 09:43:14', 'Ajout d''une programmation (105, 1DOT, 114)', '43479F'),
(556, 'moussa', '2012-09-21 09:43:14', 'Ajout des lignes de programmation', '43479F'),
(557, 'moussa', '2012-09-21 09:46:38', 'Ajout d''une programmation (106, 1DOT, 115)', '43479F'),
(558, 'moussa', '2012-09-21 09:46:38', 'Ajout des lignes de programmation', '43479F'),
(559, 'moussa', '2012-09-21 09:47:29', 'Ajout d''une programmation (107, 1DOT, 116)', '43479F'),
(560, 'moussa', '2012-09-21 09:47:29', 'Ajout des lignes de programmation', '43479F'),
(561, 'moussa', '2012-09-21 09:56:51', 'Connexion au systÃ¨me', '43479F'),
(562, 'moussa', '2012-09-21 09:59:03', 'Ajout d''une programmation (26, 1DOT, 21)', '43479F'),
(563, 'moussa', '2012-09-21 09:59:03', 'Modification des lignes de programmation', '43479F'),
(564, 'moussa', '2012-09-21 09:59:40', 'Ajout d''une programmation (27, 1DOT, 22)', '43479F'),
(565, 'moussa', '2012-09-21 09:59:40', 'Modification des lignes de programmation', '43479F'),
(566, 'moussa', '2012-09-21 10:00:21', 'Ajout d''une programmation (34, 1DOT, 29)', '43479F'),
(567, 'moussa', '2012-09-21 10:00:21', 'Modification des lignes de programmation', '43479F'),
(568, 'moussa', '2012-09-21 10:01:44', 'Ajout d''une programmation (35, 1DOT, 30)', '43479F'),
(569, 'moussa', '2012-09-21 10:01:44', 'Modification des lignes de programmation', '43479F'),
(570, 'moussa', '2012-09-21 10:05:05', 'Ajout d''une programmation (36, 1DOT, 31)', '43479F'),
(571, 'moussa', '2012-09-21 10:05:06', 'Modification des lignes de programmation', '43479F'),
(572, 'moussa', '2012-09-21 10:08:48', 'Ajout d''une programmation (33, 1DOT, 28)', '43479F'),
(573, 'moussa', '2012-09-21 10:08:48', 'Modification des lignes de programmation', '43479F'),
(574, 'moussa', '2012-09-21 10:09:02', 'Ajout d''une programmation (30, 1DOT, 25)', '43479F'),
(575, 'moussa', '2012-09-21 10:09:02', 'Modification des lignes de programmation', '43479F'),
(576, 'moussa', '2012-09-21 10:09:17', 'Ajout d''une programmation (31, 1DOT, 26)', '43479F'),
(577, 'moussa', '2012-09-21 10:09:18', 'Modification des lignes de programmation', '43479F'),
(578, 'moussa', '2012-09-21 10:09:41', 'Ajout d''une programmation (29, 1DOT, 24)', '43479F'),
(579, 'moussa', '2012-09-21 10:09:41', 'Modification des lignes de programmation', '43479F'),
(580, 'moussa', '2012-09-21 10:10:01', 'Ajout d''une programmation (32, 1DOT, 27)', '43479F'),
(581, 'moussa', '2012-09-21 10:10:01', 'Modification des lignes de programmation', '43479F'),
(582, 'moussa', '2012-09-21 10:11:22', 'Ajout d''une programmation (28, 1DOT, 23)', '43479F'),
(583, 'moussa', '2012-09-21 10:11:22', 'Modification des lignes de programmation', '43479F'),
(584, 'moussa', '2012-09-21 10:12:08', 'Ajout d''une programmation (37, 1DOT, 32)', '43479F'),
(585, 'moussa', '2012-09-21 10:12:08', 'Modification des lignes de programmation', '43479F'),
(586, 'moussa', '2012-09-21 10:12:27', 'Ajout d''une programmation (38, 1DOT, 33)', '43479F'),
(587, 'moussa', '2012-09-21 10:12:27', 'Modification des lignes de programmation', '43479F'),
(588, 'moussa', '2012-09-21 10:19:09', 'Ajout d''une programmation (41, 1DOT, 36)', '43479F'),
(589, 'moussa', '2012-09-21 10:19:09', 'Modification des lignes de programmation', '43479F');
INSERT INTO `logs` (`ID_LOG`, `LOGIN`, `LOG_DATE`, `LOG_DESCRIP`, `MLLE`) VALUES
(590, 'moussa', '2012-09-21 10:20:54', 'Ajout d''une programmation (108, 1DOT, 117)', '43479F'),
(591, 'moussa', '2012-09-21 10:20:54', 'Ajout des lignes de programmation', '43479F'),
(592, 'moussa', '2012-09-21 10:21:41', 'Ajout d''une programmation (109, 1DOT, 612)', '43479F'),
(593, 'moussa', '2012-09-21 10:21:41', 'Ajout des lignes de programmation', '43479F'),
(594, 'moussa', '2012-09-21 10:22:33', 'Ajout d''une programmation (110, 1DOT, 119)', '43479F'),
(595, 'moussa', '2012-09-21 10:22:33', 'Ajout des lignes de programmation', '43479F'),
(596, 'moussa', '2012-09-21 10:24:01', 'Ajout d''une programmation (111, 1DOT, 121)', '43479F'),
(597, 'moussa', '2012-09-21 10:24:01', 'Ajout des lignes de programmation', '43479F'),
(598, 'moussa', '2012-09-21 10:25:02', 'Ajout d''une programmation (112, 1DOT, 122)', '43479F'),
(599, 'moussa', '2012-09-21 10:25:02', 'Ajout des lignes de programmation', '43479F'),
(600, 'moussa', '2012-09-21 10:25:46', 'Ajout d''une programmation (113, 1DOT, 123)', '43479F'),
(601, 'moussa', '2012-09-21 10:25:46', 'Ajout des lignes de programmation', '43479F'),
(602, 'moussa', '2012-09-21 10:26:25', 'Ajout d''une programmation (114, 1DOT, 124)', '43479F'),
(603, 'moussa', '2012-09-21 10:26:25', 'Ajout des lignes de programmation', '43479F'),
(604, 'moussa', '2012-09-21 10:27:07', 'Ajout d''une programmation (115, 1DOT, 125)', '43479F'),
(605, 'moussa', '2012-09-21 10:27:07', 'Ajout des lignes de programmation', '43479F'),
(606, 'moussa', '2012-09-21 10:27:57', 'Ajout d''une programmation (116, 1DOT, 126)', '43479F'),
(607, 'moussa', '2012-09-21 10:27:57', 'Ajout des lignes de programmation', '43479F'),
(608, 'moussa', '2012-09-21 10:28:34', 'Ajout d''une programmation (117, 1DOT, 127)', '43479F'),
(609, 'moussa', '2012-09-21 10:28:34', 'Ajout des lignes de programmation', '43479F'),
(610, 'moussa', '2012-09-21 10:29:16', 'Ajout d''une programmation (118, 1DOT, 128)', '43479F'),
(611, 'moussa', '2012-09-21 10:29:17', 'Ajout des lignes de programmation', '43479F'),
(612, 'moussa', '2012-09-21 10:30:42', 'Ajout d''une programmation (119, 1DOT, 129)', '43479F'),
(613, 'moussa', '2012-09-21 10:30:42', 'Ajout des lignes de programmation', '43479F'),
(614, 'moussa', '2012-09-21 10:31:28', 'Ajout d''une programmation (120, 1DOT, 130)', '43479F'),
(615, 'moussa', '2012-09-21 10:31:28', 'Ajout des lignes de programmation', '43479F'),
(616, 'moussa', '2012-09-21 10:32:05', 'Ajout d''une programmation (121, 1DOT, 131)', '43479F'),
(617, 'moussa', '2012-09-21 10:32:05', 'Ajout des lignes de programmation', '43479F'),
(618, 'moussa', '2012-09-21 10:32:40', 'Ajout d''une programmation (122, 1DOT, 132)', '43479F'),
(619, 'moussa', '2012-09-21 10:32:41', 'Ajout des lignes de programmation', '43479F'),
(620, 'moussa', '2012-09-21 10:33:29', 'Ajout d''une programmation (123, 1DOT, 607)', '43479F'),
(621, 'moussa', '2012-09-21 10:33:29', 'Ajout des lignes de programmation', '43479F'),
(622, 'moussa', '2012-09-21 10:34:32', 'Ajout d''une programmation (124, 1DOT, 681)', '43479F'),
(623, 'moussa', '2012-09-21 10:34:32', 'Ajout des lignes de programmation', '43479F'),
(624, 'moussa', '2012-09-21 10:35:23', 'Ajout d''une programmation (125, 1DOT, 606)', '43479F'),
(625, 'moussa', '2012-09-21 10:35:23', 'Ajout des lignes de programmation', '43479F'),
(626, 'moussa', '2012-09-21 10:36:08', 'Ajout d''une programmation (126, 1DOT, 614)', '43479F'),
(627, 'moussa', '2012-09-21 10:36:08', 'Ajout des lignes de programmation', '43479F'),
(628, 'moussa', '2012-09-21 10:36:48', 'Ajout d''une programmation (127, 1DOT, 602)', '43479F'),
(629, 'moussa', '2012-09-21 10:36:48', 'Ajout des lignes de programmation', '43479F'),
(630, 'moussa', '2012-09-21 10:37:42', 'Ajout d''une programmation (128, 1DOT, 605)', '43479F'),
(631, 'moussa', '2012-09-21 10:37:42', 'Ajout des lignes de programmation', '43479F'),
(632, 'moussa', '2012-09-21 10:40:33', 'Ajout d''une programmation (129, 1DOT, 133)', '43479F'),
(633, 'moussa', '2012-09-21 10:40:33', 'Ajout des lignes de programmation', '43479F'),
(634, 'moussa', '2012-09-21 10:45:55', 'Ajout d''une programmation (130, 1DOT, 691)', '43479F'),
(635, 'moussa', '2012-09-21 10:45:55', 'Ajout des lignes de programmation', '43479F'),
(636, 'moussa', '2012-09-21 10:48:59', 'Ajout d''une programmation (131, 1DOT, 693)', '43479F'),
(637, 'moussa', '2012-09-21 10:48:59', 'Ajout des lignes de programmation', '43479F'),
(638, 'moussa', '2012-09-21 10:55:41', 'Ajout d''une programmation (132, 1DOT, 690)', '43479F'),
(639, 'moussa', '2012-09-21 10:55:41', 'Ajout des lignes de programmation', '43479F'),
(640, 'moussa', '2012-09-21 10:57:12', 'Ajout d''une programmation (133, 1DOT, 137)', '43479F'),
(641, 'moussa', '2012-09-21 10:57:12', 'Ajout des lignes de programmation', '43479F'),
(642, 'moussa', '2012-09-21 10:58:42', 'Ajout d''une programmation (134, 1DOT, 138)', '43479F'),
(643, 'moussa', '2012-09-21 10:58:42', 'Ajout des lignes de programmation', '43479F'),
(644, 'moussa', '2012-09-21 11:00:47', 'Ajout d''une programmation (135, 1DOT, 139)', '43479F'),
(645, 'moussa', '2012-09-21 11:00:47', 'Ajout des lignes de programmation', '43479F'),
(646, 'moussa', '2012-09-21 11:06:52', 'Ajout d''une programmation (136, 1DOT, 591)', '43479F'),
(647, 'moussa', '2012-09-21 11:06:52', 'Ajout des lignes de programmation', '43479F'),
(648, 'moussa', '2012-09-21 11:11:29', 'Ajout d''une programmation (137, 1DOT, 692)', '43479F'),
(649, 'moussa', '2012-09-21 11:11:29', 'Ajout des lignes de programmation', '43479F'),
(650, 'moussa', '2012-09-21 11:13:39', 'Ajout d''une programmation (138, 1DOT, 587)', '43479F'),
(651, 'moussa', '2012-09-21 11:13:39', 'Ajout des lignes de programmation', '43479F'),
(652, 'moussa', '2012-09-21 11:15:29', 'Ajout d''une programmation (139, 1DOT, 585)', '43479F'),
(653, 'moussa', '2012-09-21 11:15:29', 'Ajout des lignes de programmation', '43479F'),
(654, 'moussa', '2012-09-21 11:16:43', 'Ajout d''une programmation (140, 1DOT, 144)', '43479F'),
(655, 'moussa', '2012-09-21 11:16:44', 'Ajout des lignes de programmation', '43479F'),
(656, 'moussa', '2012-09-21 11:18:46', 'Ajout d''une programmation (141, 1DOT, 145)', '43479F'),
(657, 'moussa', '2012-09-21 11:18:46', 'Ajout des lignes de programmation', '43479F'),
(658, 'moussa', '2012-09-21 11:20:24', 'Ajout d''une programmation (142, 1DOT, 146)', '43479F'),
(659, 'moussa', '2012-09-21 11:20:24', 'Ajout des lignes de programmation', '43479F'),
(660, 'moussa', '2012-09-21 11:21:48', 'Ajout d''une programmation (143, 1DOT, 147)', '43479F'),
(661, 'moussa', '2012-09-21 11:21:48', 'Ajout des lignes de programmation', '43479F'),
(662, 'moussa', '2012-09-21 11:23:10', 'Ajout d''une programmation (144, 1DOT, 148)', '43479F'),
(663, 'moussa', '2012-09-21 11:23:10', 'Ajout des lignes de programmation', '43479F'),
(664, 'moussa', '2012-09-21 11:24:36', 'Ajout d''une programmation (145, 1DOT, 149)', '43479F'),
(665, 'moussa', '2012-09-21 11:24:36', 'Ajout des lignes de programmation', '43479F'),
(666, 'moussa', '2012-09-21 11:26:16', 'Ajout d''une programmation (146, 1DOT, 150)', '43479F'),
(667, 'moussa', '2012-09-21 11:26:16', 'Ajout des lignes de programmation', '43479F'),
(668, 'moussa', '2012-09-21 11:28:09', 'Ajout d''une programmation (147, 1DOT, 151)', '43479F'),
(669, 'moussa', '2012-09-21 11:28:09', 'Ajout des lignes de programmation', '43479F'),
(670, 'moussa', '2012-09-21 11:29:49', 'Ajout d''une programmation (148, 1DOT, 152)', '43479F'),
(671, 'moussa', '2012-09-21 11:29:49', 'Ajout des lignes de programmation', '43479F'),
(672, 'moussa', '2012-09-21 11:31:00', 'Ajout d''une programmation (149, 1DOT, 153)', '43479F'),
(673, 'moussa', '2012-09-21 11:31:00', 'Ajout des lignes de programmation', '43479F'),
(674, 'moussa', '2012-09-21 11:35:49', 'Ajout d''une programmation (150, 1DOT, 154)', '43479F'),
(675, 'moussa', '2012-09-21 11:35:49', 'Ajout des lignes de programmation', '43479F'),
(676, 'moussa', '2012-09-21 11:37:58', 'Ajout d''une programmation (151, 1DOT, 155)', '43479F'),
(677, 'moussa', '2012-09-21 11:37:58', 'Ajout des lignes de programmation', '43479F'),
(678, 'moussa', '2012-09-21 11:39:32', 'Ajout d''une programmation (152, 1DOT, 156)', '43479F'),
(679, 'moussa', '2012-09-21 11:39:32', 'Ajout des lignes de programmation', '43479F'),
(680, 'moussa', '2012-09-21 11:43:27', 'Ajout d''une programmation (153, 1DOT, 157)', '43479F'),
(681, 'moussa', '2012-09-21 11:43:27', 'Ajout des lignes de programmation', '43479F'),
(682, 'moussa', '2012-09-21 11:44:17', 'Ajout d''une programmation (154, 1DOT, 158)', '43479F'),
(683, 'moussa', '2012-09-21 11:44:17', 'Ajout des lignes de programmation', '43479F'),
(684, 'moussa', '2012-09-21 11:46:09', 'Ajout d''une programmation (155, 1DOT, 159)', '43479F'),
(685, 'moussa', '2012-09-21 11:46:09', 'Ajout des lignes de programmation', '43479F'),
(686, 'moussa', '2012-09-21 11:47:37', 'Ajout d''une programmation (156, 1DOT, 590)', '43479F'),
(687, 'moussa', '2012-09-21 11:47:37', 'Ajout des lignes de programmation', '43479F'),
(688, 'moussa', '2012-09-21 11:48:25', 'Ajout d''une programmation (157, 1DOT, 588)', '43479F'),
(689, 'moussa', '2012-09-21 11:48:25', 'Ajout des lignes de programmation', '43479F'),
(690, 'moussa', '2012-09-21 11:49:14', 'Ajout d''une programmation (158, 1DOT, 642)', '43479F'),
(691, 'moussa', '2012-09-21 11:49:14', 'Ajout des lignes de programmation', '43479F'),
(692, 'moussa', '2012-09-21 11:50:21', 'Ajout d''une programmation (159, 1DOT, 688)', '43479F'),
(693, 'moussa', '2012-09-21 11:50:21', 'Ajout des lignes de programmation', '43479F'),
(694, 'moussa', '2012-09-21 11:51:55', 'Ajout d''une programmation (160, 1DOT, 687)', '43479F'),
(695, 'moussa', '2012-09-21 11:51:55', 'Ajout des lignes de programmation', '43479F'),
(696, 'moussa', '2012-09-21 11:52:56', 'Ajout d''une programmation (161, 1DOT, 686)', '43479F'),
(697, 'moussa', '2012-09-21 11:52:57', 'Ajout des lignes de programmation', '43479F'),
(698, 'moussa', '2012-09-21 11:53:55', 'Ajout d''une programmation (162, 1DOT, 689)', '43479F'),
(699, 'moussa', '2012-09-21 11:53:55', 'Ajout des lignes de programmation', '43479F'),
(700, 'moussa', '2012-09-21 11:57:35', 'Ajout d''une programmation (163, 1DOT, 568)', '43479F'),
(701, 'moussa', '2012-09-21 11:57:35', 'Ajout des lignes de programmation', '43479F'),
(702, 'moussa', '2012-09-21 11:59:13', 'Ajout d''une programmation (164, 1DOT, 571)', '43479F'),
(703, 'moussa', '2012-09-21 11:59:13', 'Ajout des lignes de programmation', '43479F'),
(704, 'moussa', '2012-09-21 12:02:44', 'Ajout d''une programmation (165, 1DOT, 570)', '43479F'),
(705, 'moussa', '2012-09-21 12:02:44', 'Ajout des lignes de programmation', '43479F'),
(706, 'moussa', '2012-09-21 12:05:18', 'Ajout d''une programmation (166, 1DOT, 569)', '43479F'),
(707, 'moussa', '2012-09-21 12:05:18', 'Ajout des lignes de programmation', '43479F'),
(708, 'moussa', '2012-09-21 12:07:53', 'Ajout d''une programmation (167, 1DOT, 665)', '43479F'),
(709, 'moussa', '2012-09-21 12:07:53', 'Ajout des lignes de programmation', '43479F'),
(710, 'moussa', '2012-09-21 12:10:01', 'Ajout d''une programmation (168, 1DOT, 574)', '43479F'),
(711, 'moussa', '2012-09-21 12:10:01', 'Ajout des lignes de programmation', '43479F'),
(712, 'moussa', '2012-09-21 12:11:23', 'Ajout d''une programmation (169, 1DOT, 573)', '43479F'),
(713, 'moussa', '2012-09-21 12:11:23', 'Ajout des lignes de programmation', '43479F'),
(714, 'moussa', '2012-09-21 12:12:48', 'Ajout d''une programmation (170, 1DOT, 679)', '43479F'),
(715, 'moussa', '2012-09-21 12:12:48', 'Ajout des lignes de programmation', '43479F'),
(716, 'moussa', '2012-09-21 12:13:54', 'Ajout d''une programmation (171, 1DOT, 644)', '43479F'),
(717, 'moussa', '2012-09-21 12:13:54', 'Ajout des lignes de programmation', '43479F'),
(718, 'moussa', '2012-09-21 12:15:02', 'Ajout d''une programmation (172, 1DOT, 589)', '43479F'),
(719, 'moussa', '2012-09-21 12:15:02', 'Ajout des lignes de programmation', '43479F'),
(720, 'moussa', '2012-09-21 12:16:20', 'Ajout d''une programmation (173, 1DOT, 643)', '43479F'),
(721, 'moussa', '2012-09-21 12:16:20', 'Ajout des lignes de programmation', '43479F'),
(722, 'moussa', '2012-09-21 12:18:06', 'Ajout d''une programmation (174, 1DOT, 637)', '43479F'),
(723, 'moussa', '2012-09-21 12:18:06', 'Ajout des lignes de programmation', '43479F'),
(724, 'moussa', '2012-09-21 12:20:18', 'Ajout d''une programmation (175, 1DOT, 651)', '43479F'),
(725, 'moussa', '2012-09-21 12:20:18', 'Ajout des lignes de programmation', '43479F'),
(726, 'moussa', '2012-09-21 12:21:20', 'DÃ©connexion du systÃ¨me Ã©chouÃ©e', '43479F'),
(728, 'maiga', '2012-09-21 12:31:15', 'Connexion au systÃ¨me', 'MM'),
(729, 'moussa', '2012-09-21 12:33:53', 'Connexion au systÃ¨me', '43479F'),
(730, 'moussa', '2012-09-21 12:40:26', 'DÃ©connexion du systÃ¨me Ã©chouÃ©e', '43479F'),
(731, 'moussa', '2012-09-21 13:18:11', 'Connexion au systÃ¨me', '43479F'),
(732, 'moussa', '2012-09-21 13:25:20', 'Ajout d''une affectation (MAG0, 608)', '43479F'),
(733, 'moussa', '2012-09-21 13:28:45', 'Ajout d''une programmation (176, 1DOT, 608)', '43479F'),
(734, 'moussa', '2012-09-21 13:28:45', 'Ajout des lignes de programmation', '43479F'),
(735, 'moussa', '2012-09-21 13:40:32', 'Ajout d''une affectation (MAG0, 611)', '43479F'),
(736, 'moussa', '2012-09-21 13:42:11', 'Ajout d''une programmation (177, 1DOT, 611)', '43479F'),
(737, 'moussa', '2012-09-21 13:42:11', 'Ajout des lignes de programmation', '43479F'),
(738, 'moussa', '2012-09-21 13:54:49', 'Ajout d''une programmation (178, 1DOT, 586)', '43479F'),
(739, 'moussa', '2012-09-21 13:54:49', 'Ajout des lignes de programmation', '43479F'),
(742, 'moussa', '2012-09-21 15:24:42', 'Connexion au systÃ¨me', '43479F'),
(743, 'moussa', '2012-09-21 15:44:19', 'Modification d''une affectation (4, MAG 6)', '43479F'),
(744, 'moussa', '2012-09-21 15:45:48', 'Modification d''une affectation (5, MAG 6)', '43479F'),
(745, 'moussa', '2012-09-21 15:46:22', 'Modification d''une affectation (535, MAG 6)', '43479F'),
(746, 'moussa', '2012-09-21 15:48:04', 'Modification d''une affectation (3, MAG 6)', '43479F'),
(747, 'moussa', '2012-09-21 15:49:45', 'Modification d''une affectation (14, MAG 6)', '43479F'),
(748, 'moussa', '2012-09-21 15:50:11', 'Modification d''une affectation (15, MAG 6)', '43479F'),
(749, 'moussa', '2012-09-21 15:50:31', 'Modification d''une affectation (17, MAG 6)', '43479F'),
(750, 'moussa', '2012-09-21 15:50:55', 'Modification d''une affectation (18, MAG 6)', '43479F'),
(751, 'moussa', '2012-09-21 15:51:17', 'Modification d''une affectation (547, MAG 6)', '43479F'),
(752, 'moussa', '2012-09-21 15:51:38', 'Modification d''une affectation (550, MAG 6)', '43479F'),
(753, 'moussa', '2012-09-21 15:51:59', 'Modification d''une affectation (552, MAG 6)', '43479F'),
(754, 'moussa', '2012-09-21 15:52:23', 'Modification d''une affectation (19, MAG 6)', '43479F'),
(755, 'moussa', '2012-09-21 15:52:49', 'Modification d''une affectation (20, MAG 6)', '43479F'),
(756, 'moussa', '2012-09-21 15:53:09', 'Modification d''une affectation (553, MAG 6)', '43479F'),
(757, 'moussa', '2012-09-21 15:53:28', 'Modification d''une affectation (698, MAG 6)', '43479F'),
(758, 'moussa', '2012-09-21 15:53:46', 'Modification d''une affectation (13, MAG 6)', '43479F'),
(759, 'moussa', '2012-09-21 15:54:08', 'Modification d''une affectation (10, MAG 6)', '43479F'),
(760, 'moussa', '2012-09-21 15:54:33', 'Modification d''une affectation (8, MAG 6)', '43479F'),
(761, 'moussa', '2012-09-21 15:54:55', 'Modification d''une affectation (7, MAG 6)', '43479F'),
(762, 'moussa', '2012-09-21 16:01:30', 'Modification d''une affectation (343, MAG 6)', '43479F'),
(763, 'moussa', '2012-09-21 16:07:21', 'Modification d''une affectation (532, MAG 6)', '43479F'),
(764, 'moussa', '2012-09-21 16:07:45', 'Modification d''une affectation (343, MAG 6)', '43479F'),
(765, 'moussa', '2012-09-21 16:08:21', 'Modification d''une affectation (339, MAG 6)', '43479F'),
(766, 'moussa', '2012-09-21 16:08:45', 'Modification d''une affectation (340, MAG 6)', '43479F'),
(767, 'moussa', '2012-09-21 16:09:09', 'Modification d''une affectation (536, MAG 6)', '43479F'),
(768, 'moussa', '2012-09-21 16:09:28', 'Modification d''une affectation (535, MAG 6)', '43479F'),
(769, 'moussa', '2012-09-21 16:09:52', 'Modification d''une affectation (534, MAG 6)', '43479F'),
(770, 'moussa', '2012-09-21 16:10:11', 'Modification d''une affectation (342, MAG 6)', '43479F'),
(771, 'moussa', '2012-09-21 16:10:36', 'Modification d''une affectation (341, MAG 6)', '43479F'),
(772, 'moussa', '2012-09-21 16:10:55', 'Modification d''une affectation (538, MAG 6)', '43479F'),
(773, 'moussa', '2012-09-21 16:11:14', 'Modification d''une affectation (536, MAG 6)', '43479F'),
(774, 'moussa', '2012-09-21 16:11:34', 'Modification d''une affectation (533, MAG 6)', '43479F'),
(775, 'moussa', '2012-09-21 16:11:57', 'Modification d''une affectation (537, MAG 6)', '43479F'),
(776, 'moussa', '2012-09-21 16:12:21', 'Modification d''une affectation (577, MAG 6)', '43479F'),
(777, 'moussa', '2012-09-21 16:12:56', 'Modification d''une affectation (340, MAG 6)', '43479F'),
(778, 'moussa', '2012-09-21 16:13:22', 'Modification d''une affectation (578, MAG 6)', '43479F'),
(779, 'moussa', '2012-09-21 16:13:43', 'Modification d''une affectation (581, MAG 6)', '43479F'),
(780, 'moussa', '2012-09-21 16:14:00', 'Modification d''une affectation (582, MAG 6)', '43479F'),
(781, 'moussa', '2012-09-21 16:14:21', 'Modification d''une affectation (576, MAG 6)', '43479F'),
(782, 'moussa', '2012-09-21 16:14:43', 'Modification d''une affectation (580, MAG 6)', '43479F'),
(783, 'moussa', '2012-09-21 16:15:08', 'Modification d''une affectation (584, MAG 6)', '43479F'),
(784, 'moussa', '2012-09-21 16:15:26', 'Modification d''une affectation (579, MAG 6)', '43479F'),
(785, 'moussa', '2012-09-21 16:15:43', 'Modification d''une affectation (583, MAG 6)', '43479F'),
(786, 'moussa', '2012-09-21 16:16:00', 'Modification d''une affectation (352, MAG 6)', '43479F'),
(787, 'moussa', '2012-09-21 16:16:20', 'Modification d''une affectation (542, MAG 6)', '43479F'),
(788, 'moussa', '2012-09-21 16:16:39', 'Modification d''une affectation (351, MAG 6)', '43479F'),
(789, 'moussa', '2012-09-21 16:17:04', 'Modification d''une affectation (350, MAG 6)', '43479F'),
(790, 'moussa', '2012-09-21 16:17:45', 'Modification d''une affectation (541, MAG 6)', '43479F'),
(791, 'moussa', '2012-09-21 16:17:58', 'Modification d''une affectation (540, MAG 6)', '43479F'),
(792, 'moussa', '2012-09-21 16:18:13', 'Modification d''une affectation (355, MAG 6)', '43479F'),
(793, 'moussa', '2012-09-21 16:18:29', 'Modification d''une affectation (354, MAG 6)', '43479F'),
(794, 'moussa', '2012-09-21 16:18:44', 'Modification d''une affectation (356, MAG 6)', '43479F'),
(795, 'moussa', '2012-09-21 16:19:01', 'Modification d''une affectation (539, MAG 6)', '43479F'),
(796, 'moussa', '2012-09-21 16:19:41', 'Modification d''une affectation (353, MAG 6)', '43479F'),
(797, 'moussa', '2012-09-21 16:20:18', 'Modification d''une affectation (543, MAG 6)', '43479F'),
(798, 'moussa', '2012-09-21 16:20:43', 'Modification d''une affectation (357, MAG 6)', '43479F'),
(799, 'moussa', '2012-09-21 16:21:08', 'Modification d''une affectation (366, MAG 6)', '43479F'),
(800, 'moussa', '2012-09-21 16:22:08', 'Modification d''une affectation (445, MAG 6)', '43479F'),
(801, 'moussa', '2012-09-21 16:22:31', 'Modification d''une affectation (444, MAG 6)', '43479F'),
(802, 'moussa', '2012-09-21 16:23:06', 'Modification d''une affectation (373, MAG 6)', '43479F'),
(803, 'moussa', '2012-09-21 16:23:44', 'Modification d''une affectation (374, MAG 6)', '43479F'),
(804, 'moussa', '2012-09-21 16:24:01', 'Modification d''une affectation (546, MAG 6)', '43479F'),
(805, 'moussa', '2012-09-21 16:24:16', 'Modification d''une affectation (365, MAG 6)', '43479F'),
(806, 'moussa', '2012-09-21 16:24:33', 'Modification d''une affectation (443, MAG 6)', '43479F'),
(807, 'moussa', '2012-09-21 16:24:53', 'Modification d''une affectation (368, MAG 6)', '43479F'),
(808, 'moussa', '2012-09-21 16:25:11', 'Modification d''une affectation (455, MAG 6)', '43479F'),
(809, 'moussa', '2012-09-21 16:25:32', 'Modification d''une affectation (364, MAG 6)', '43479F'),
(810, 'moussa', '2012-09-21 16:25:47', 'Modification d''une affectation (442, MAG 6)', '43479F'),
(811, 'moussa', '2012-09-21 16:26:01', 'Modification d''une affectation (454, MAG 6)', '43479F'),
(812, 'moussa', '2012-09-21 16:26:16', 'Modification d''une affectation (433, MAG 6)', '43479F'),
(813, 'moussa', '2012-09-21 16:26:30', 'Modification d''une affectation (367, MAG 6)', '43479F'),
(814, 'moussa', '2012-09-21 16:26:51', 'Modification d''une affectation (551, MAG 6)', '43479F'),
(815, 'moussa', '2012-09-21 16:27:09', 'Modification d''une affectation (549, MAG 6)', '43479F'),
(816, 'moussa', '2012-09-21 16:27:29', 'Modification d''une affectation (548, MAG 6)', '43479F'),
(817, 'moussa', '2012-09-21 16:27:59', 'Modification d''une affectation (554, MAG1)', '43479F'),
(818, 'moussa', '2012-09-21 16:28:18', 'Modification d''une affectation (554, MAG 6)', '43479F'),
(819, 'moussa', '2012-09-21 16:28:34', 'Modification d''une affectation (358, MAG 6)', '43479F'),
(820, 'moussa', '2012-09-21 16:28:57', 'Modification d''une affectation (363, MAG 6)', '43479F'),
(821, 'moussa', '2012-09-21 16:29:20', 'Modification d''une affectation (557, MAG 6)', '43479F'),
(822, 'moussa', '2012-09-21 16:29:39', 'Modification d''une affectation (361, MAG 6)', '43479F'),
(823, 'moussa', '2012-09-21 16:29:56', 'Modification d''une affectation (556, MAG 6)', '43479F'),
(824, 'moussa', '2012-09-21 16:30:15', 'Modification d''une affectation (360, MAG 6)', '43479F'),
(825, 'moussa', '2012-09-21 16:30:33', 'Modification d''une affectation (555, MAG 6)', '43479F'),
(826, 'moussa', '2012-09-21 16:30:54', 'Modification d''une affectation (359, MAG 6)', '43479F'),
(827, 'moussa', '2012-09-21 16:33:01', 'Modification d''une affectation (192, MAG 5)', '43479F'),
(828, 'moussa', '2012-09-21 16:34:05', 'Modification d''une affectation (186, MAG 5)', '43479F'),
(829, 'moussa', '2012-09-21 16:34:35', 'Modification d''une affectation (193, MAG 5)', '43479F'),
(830, 'moussa', '2012-09-21 16:35:15', 'Modification d''une affectation (194, MAG 5)', '43479F'),
(831, 'moussa', '2012-09-21 16:35:40', 'Modification d''une affectation (572, MAG 5)', '43479F'),
(832, 'moussa', '2012-09-21 16:36:04', 'Modification d''une affectation (573, MAG 5)', '43479F'),
(833, 'moussa', '2012-09-21 16:36:30', 'Modification d''une affectation (574, MAG 5)', '43479F'),
(834, 'moussa', '2012-09-21 16:36:55', 'Modification d''une affectation (187, MAG 5)', '43479F'),
(835, 'moussa', '2012-09-21 16:37:34', 'Modification d''une affectation (188, MAG 5)', '43479F'),
(836, 'moussa', '2012-09-21 16:38:04', 'Modification d''une affectation (189, MAG 5)', '43479F'),
(837, 'moussa', '2012-09-21 16:38:34', 'Modification d''une affectation (190, MAG 5)', '43479F'),
(838, 'moussa', '2012-09-21 16:39:02', 'Modification d''une affectation (191, MAG 5)', '43479F'),
(839, 'moussa', '2012-09-21 16:39:34', 'Modification d''une affectation (184, MAG 5)', '43479F'),
(840, 'moussa', '2012-09-21 16:40:26', 'Modification d''une affectation (185, MAG 5)', '43479F'),
(841, 'moussa', '2012-09-21 16:41:01', 'Modification d''une affectation (183, MAG 5)', '43479F'),
(842, 'moussa', '2012-09-21 16:42:37', 'Modification d''une affectation (575, MAG 5)', '43479F'),
(843, 'moussa', '2012-09-21 16:45:12', 'Modification d''une affectation (697, MAG 5)', '43479F'),
(844, 'moussa', '2012-09-21 16:45:40', 'Modification d''une affectation (177, MAG 5)', '43479F'),
(845, 'moussa', '2012-09-21 16:46:04', 'Modification d''une affectation (178, MAG 5)', '43479F'),
(846, 'moussa', '2012-09-21 16:46:32', 'Modification d''une affectation (179, MAG 5)', '43479F'),
(847, 'moussa', '2012-09-21 16:47:46', 'Modification d''une affectation (180, MAG 5)', '43479F'),
(848, 'moussa', '2012-09-21 16:48:13', 'Modification d''une affectation (182, MAG 5)', '43479F'),
(849, 'moussa', '2012-09-21 16:48:37', 'Modification d''une affectation (166, MAG 5)', '43479F'),
(850, 'moussa', '2012-09-21 16:49:03', 'Modification d''une affectation (167, MAG 5)', '43479F'),
(851, 'moussa', '2012-09-21 16:49:29', 'Modification d''une affectation (176, MAG 5)', '43479F'),
(852, 'moussa', '2012-09-21 16:49:50', 'Modification d''une affectation (173, MAG 5)', '43479F'),
(853, 'moussa', '2012-09-21 16:50:13', 'Modification d''une affectation (168, MAG 5)', '43479F'),
(854, 'moussa', '2012-09-21 16:50:39', 'Modification d''une affectation (169, MAG 5)', '43479F'),
(855, 'moussa', '2012-09-21 16:51:03', 'Modification d''une affectation (170, MAG 5)', '43479F'),
(856, 'moussa', '2012-09-21 16:51:28', 'Modification d''une affectation (172, MAG 5)', '43479F'),
(857, 'moussa', '2012-09-21 16:51:53', 'Modification d''une affectation (174, MAG 5)', '43479F'),
(858, 'moussa', '2012-09-21 16:52:21', 'Modification d''une affectation (164, MAG 5)', '43479F'),
(859, 'moussa', '2012-09-21 16:52:50', 'Modification d''une affectation (165, MAG 5)', '43479F'),
(860, 'moussa', '2012-09-21 16:53:26', 'Modification d''une affectation (665, MAG 5)', '43479F'),
(861, 'moussa', '2012-09-21 16:53:51', 'Modification d''une affectation (666, MAG 5)', '43479F'),
(862, 'moussa', '2012-09-21 16:54:16', 'Modification d''une affectation (161, MAG 5)', '43479F'),
(863, 'moussa', '2012-09-21 16:54:39', 'Modification d''une affectation (160, MAG 5)', '43479F'),
(864, 'moussa', '2012-09-21 16:55:01', 'Modification d''une affectation (162, MAG 5)', '43479F'),
(865, 'moussa', '2012-09-21 16:55:28', 'Modification d''une affectation (163, MAG 5)', '43479F'),
(866, 'moussa', '2012-09-21 16:55:50', 'Modification d''une affectation (175, MAG 5)', '43479F'),
(867, 'moussa', '2012-09-21 16:56:12', 'Modification d''une affectation (175, MAG 5)', '43479F'),
(868, 'moussa', '2012-09-21 17:01:10', 'Modification d''une affectation (201, MAG 5)', '43479F'),
(869, 'moussa', '2012-09-21 17:01:36', 'Modification d''une affectation (197, MAG 5)', '43479F'),
(870, 'moussa', '2012-09-21 17:02:09', 'Modification d''une affectation (199, MAG 5)', '43479F'),
(871, 'moussa', '2012-09-21 17:02:37', 'Modification d''une affectation (200, MAG 5)', '43479F'),
(872, 'moussa', '2012-09-21 17:03:02', 'Modification d''une affectation (196, MAG 5)', '43479F'),
(873, 'moussa', '2012-09-21 17:03:24', 'Modification d''une affectation (195, MAG 5)', '43479F'),
(874, 'moussa', '2012-09-21 17:03:46', 'Modification d''une affectation (667, MAG 5)', '43479F'),
(875, 'moussa', '2012-09-21 17:04:12', 'Modification d''une affectation (205, MAG 5)', '43479F'),
(876, 'moussa', '2012-09-21 17:04:45', 'Modification d''une affectation (202, MAG 5)', '43479F'),
(877, 'moussa', '2012-09-21 17:05:10', 'Modification d''une affectation (208, MAG 5)', '43479F'),
(878, 'moussa', '2012-09-21 17:05:34', 'Modification d''une affectation (668, MAG 5)', '43479F'),
(879, 'moussa', '2012-09-21 17:05:58', 'Modification d''une affectation (207, MAG 5)', '43479F'),
(880, 'moussa', '2012-09-21 17:06:22', 'Modification d''une affectation (204, MAG 5)', '43479F'),
(881, 'moussa', '2012-09-21 17:06:50', 'Modification d''une affectation (268, MAG 5)', '43479F'),
(882, 'moussa', '2012-09-21 17:07:13', 'Modification d''une affectation (669, MAG 5)', '43479F'),
(883, 'moussa', '2012-09-21 17:07:38', 'Modification d''une affectation (269, MAG 5)', '43479F'),
(884, 'moussa', '2012-09-21 17:08:05', 'Modification d''une affectation (263, MAG 5)', '43479F'),
(885, 'moussa', '2012-09-21 17:08:34', 'Modification d''une affectation (265, MAG 5)', '43479F'),
(886, 'moussa', '2012-09-21 17:09:02', 'Modification d''une affectation (266, MAG 5)', '43479F'),
(887, 'moussa', '2012-09-21 17:09:27', 'Modification d''une affectation (214, MAG 5)', '43479F'),
(888, 'moussa', '2012-09-21 17:09:51', 'Modification d''une affectation (267, MAG 5)', '43479F'),
(889, 'moussa', '2012-09-21 17:10:17', 'Modification d''une affectation (213, MAG 5)', '43479F'),
(890, 'moussa', '2012-09-21 17:10:48', 'Modification d''une affectation (211, MAG 5)', '43479F'),
(891, 'moussa', '2012-09-21 17:11:10', 'Modification d''une affectation (209, MAG 5)', '43479F'),
(892, 'moussa', '2012-09-21 17:11:36', 'Modification d''une affectation (671, MAG 5)', '43479F'),
(893, 'moussa', '2012-09-21 17:12:01', 'Modification d''une affectation (212, MAG 5)', '43479F'),
(894, 'moussa', '2012-09-21 17:12:26', 'Modification d''une affectation (210, MAG 5)', '43479F'),
(895, 'maiga', '2012-09-24 15:22:02', 'Tentative de connexion au systÃ¨me Ã©chouÃ©e', ''),
(896, 'moussa', '2012-09-24 15:22:25', 'Connexion au systÃ¨me', '43479F'),
(897, 'moussa', '2012-09-24 15:36:21', 'Modification d''une affectation (241, MAG 3)', '43479F'),
(898, 'moussa', '2012-09-24 15:37:36', 'Modification d''une affectation (241, MAG 3)', '43479F'),
(899, 'moussa', '2012-09-24 15:38:59', 'Modification d''une affectation (241, MAG 3)', '43479F'),
(900, 'moussa', '2012-09-24 15:41:11', 'Modification d''une affectation (241, MAG 3)', '43479F'),
(901, 'moussa', '2012-09-24 15:42:17', 'Modification d''une affectation (241, MAG 3)', '43479F'),
(902, 'moussa', '2012-09-24 15:43:18', 'Modification d''une affectation (641, MAG 3)', '43479F'),
(903, 'moussa', '2012-09-24 15:46:21', 'Modification d''une affectation (241, MAG 3)', '43479F'),
(904, 'moussa', '2012-09-24 15:50:00', 'Modification d''une affectation (640, MAG 3)', '43479F'),
(905, 'moussa', '2012-09-24 15:51:06', 'Modification d''une affectation (244, MAG 3)', '43479F'),
(906, 'moussa', '2012-09-24 15:51:37', 'Modification d''une affectation (241, MAG 3)', '43479F'),
(907, 'moussa', '2012-09-24 15:52:18', 'Modification d''une affectation (243, MAG 3)', '43479F'),
(908, 'moussa', '2012-09-24 15:53:14', 'Modification d''une affectation (242, MAG 3)', '43479F'),
(909, 'moussa', '2012-09-24 15:53:48', 'Modification d''une affectation (660, MAG 3)', '43479F'),
(910, 'moussa', '2012-09-24 15:54:19', 'Modification d''une affectation (260, MAG 3)', '43479F'),
(911, 'moussa', '2012-09-24 15:54:50', 'Modification d''une affectation (255, MAG 3)', '43479F'),
(912, 'moussa', '2012-09-24 15:55:30', 'Modification d''une affectation (254, MAG 3)', '43479F'),
(913, 'moussa', '2012-09-24 15:55:59', 'Modification d''une affectation (655, MAG 3)', '43479F'),
(914, 'moussa', '2012-09-24 16:16:41', 'Modification d''une affectation (656, MAG 3)', '43479F'),
(915, 'moussa', '2012-09-24 16:27:32', 'Modification d''une affectation (256, MAG 3)', '43479F'),
(916, 'moussa', '2012-09-24 16:28:08', 'Modification d''une affectation (259, MAG 3)', '43479F'),
(917, 'moussa', '2012-09-24 16:28:39', 'Modification d''une affectation (659, MAG 3)', '43479F'),
(918, 'moussa', '2012-09-24 16:29:17', 'Modification d''une affectation (253, MAG 3)', '43479F'),
(919, 'moussa', '2012-09-24 16:29:50', 'Modification d''une affectation (653, MAG 3)', '43479F'),
(920, 'moussa', '2012-09-24 16:31:16', 'Modification d''une affectation (257, MAG 3)', '43479F'),
(921, 'moussa', '2012-09-24 16:31:38', 'Modification d''une affectation (658, MAG0)', '43479F'),
(922, 'moussa', '2012-09-24 16:32:18', 'Modification d''une affectation (658, MAG 3)', '43479F'),
(923, 'moussa', '2012-09-24 16:32:43', 'Modification d''une affectation (245, MAG 3)', '43479F'),
(924, 'moussa', '2012-09-24 16:33:06', 'Modification d''une affectation (250, MAG 3)', '43479F'),
(925, 'moussa', '2012-09-24 16:33:29', 'Modification d''une affectation (246, MAG 3)', '43479F'),
(926, 'moussa', '2012-09-24 16:33:47', 'Modification d''une affectation (684, MAG 3)', '43479F'),
(927, 'moussa', '2012-09-24 16:34:03', 'Modification d''une affectation (682, MAG 3)', '43479F'),
(928, 'moussa', '2012-09-24 16:34:30', 'Modification d''une affectation (247, MAG 3)', '43479F'),
(929, 'moussa', '2012-09-24 16:34:46', 'Modification d''une affectation (248, MAG 3)', '43479F'),
(930, 'moussa', '2012-09-24 16:35:02', 'Modification d''une affectation (685, MAG 3)', '43479F'),
(931, 'moussa', '2012-09-24 16:35:23', 'Modification d''une affectation (700, MAG 3)', '43479F'),
(932, 'moussa', '2012-09-24 16:35:41', 'Modification d''une affectation (700, MAG 3)', '43479F'),
(933, 'moussa', '2012-09-24 16:36:01', 'Modification d''une affectation (249, MAG 3)', '43479F'),
(934, 'moussa', '2012-09-24 16:36:42', 'Modification d''une affectation (252, MAG 3)', '43479F'),
(935, 'moussa', '2012-09-24 16:37:14', 'Modification d''une affectation (262, MAG 3)', '43479F'),
(936, 'moussa', '2012-09-24 16:38:03', 'Modification d''une affectation (662, MAG 3)', '43479F'),
(937, 'moussa', '2012-09-24 16:38:32', 'Modification d''une affectation (657, MAG 3)', '43479F'),
(938, 'moussa', '2012-09-24 16:39:04', 'Modification d''une affectation (251, MAG 3)', '43479F'),
(939, 'moussa', '2012-09-24 16:39:44', 'Modification d''une affectation (261, MAG 3)', '43479F'),
(940, 'moussa', '2012-09-24 16:41:19', 'Modification d''une affectation (222, MAG 3)', '43479F'),
(941, 'moussa', '2012-09-24 16:43:00', 'Modification d''une affectation (244, MAG 3)', '43479F'),
(942, 'moussa', '2012-09-24 16:43:45', 'Modification d''une affectation (661, MAG 3)', '43479F'),
(943, 'moussa', '2012-09-24 16:44:45', 'Modification d''une affectation (233, MAG 3)', '43479F'),
(944, 'moussa', '2012-09-24 16:45:52', 'Modification d''une affectation (226, MAG 3)', '43479F'),
(945, 'moussa', '2012-09-24 16:46:53', 'Modification d''une affectation (678, MAG 3)', '43479F'),
(946, 'moussa', '2012-09-24 16:47:58', 'Modification d''une affectation (673, MAG 3)', '43479F'),
(947, 'moussa', '2012-09-24 16:48:54', 'Modification d''une affectation (218, MAG 3)', '43479F'),
(948, 'moussa', '2012-09-24 16:49:53', 'Modification d''une affectation (221, MAG 3)', '43479F'),
(949, 'moussa', '2012-09-24 16:50:20', 'Modification d''une affectation (236, MAG 3)', '43479F'),
(950, 'moussa', '2012-09-24 16:50:44', 'Modification d''une affectation (674, MAG 3)', '43479F'),
(951, 'moussa', '2012-09-24 16:51:08', 'Modification d''une affectation (232, MAG 3)', '43479F'),
(952, 'moussa', '2012-09-24 16:51:30', 'Modification d''une affectation (216, MAG 3)', '43479F'),
(953, 'moussa', '2012-09-24 16:51:52', 'Modification d''une affectation (225, MAG 3)', '43479F'),
(954, 'moussa', '2012-09-24 16:52:12', 'Modification d''une affectation (217, MAG 3)', '43479F'),
(955, 'moussa', '2012-09-24 16:52:35', 'Modification d''une affectation (235, MAG 3)', '43479F'),
(956, 'moussa', '2012-09-24 16:52:59', 'Modification d''une affectation (677, MAG 3)', '43479F'),
(957, 'moussa', '2012-09-24 16:53:24', 'Modification d''une affectation (220, MAG 3)', '43479F'),
(958, 'moussa', '2012-09-24 16:53:46', 'Modification d''une affectation (231, MAG 3)', '43479F'),
(959, 'moussa', '2012-09-24 16:54:13', 'Modification d''une affectation (672, MAG 3)', '43479F'),
(960, 'moussa', '2012-09-24 16:54:34', 'Modification d''une affectation (215, MAG 3)', '43479F'),
(961, 'moussa', '2012-09-24 16:54:56', 'Modification d''une affectation (224, MAG 3)', '43479F'),
(962, 'moussa', '2012-09-24 16:55:35', 'Modification d''une affectation (235, MAG 3)', '43479F'),
(963, 'moussa', '2012-09-24 16:55:56', 'Modification d''une affectation (676, MAG 3)', '43479F'),
(964, 'moussa', '2012-09-24 16:56:31', 'Modification d''une affectation (230, MAG 3)', '43479F'),
(965, 'moussa', '2012-09-24 16:56:56', 'Modification d''une affectation (219, MAG 3)', '43479F'),
(966, 'moussa', '2012-09-24 16:57:16', 'Modification d''une affectation (239, MAG 3)', '43479F'),
(967, 'moussa', '2012-09-24 16:57:36', 'Modification d''une affectation (223, MAG 3)', '43479F'),
(968, 'moussa', '2012-09-24 16:58:00', 'Modification d''une affectation (234, MAG 3)', '43479F'),
(969, 'moussa', '2012-09-24 16:58:27', 'Modification d''une affectation (675, MAG 3)', '43479F'),
(970, 'moussa', '2012-09-24 16:58:53', 'Modification d''une affectation (229, MAG 3)', '43479F'),
(971, 'moussa', '2012-09-24 16:59:23', 'Modification d''une affectation (238, MAG 3)', '43479F'),
(972, 'moussa', '2012-09-24 17:08:44', 'Connexion au systÃ¨me', '43479F'),
(975, 'moussa', '2012-09-25 13:41:08', 'Connexion au systÃ¨me', '43479F'),
(977, 'moussa', '2012-09-25 15:13:35', 'Connexion au systÃ¨me', '43479F'),
(978, 'moussa', '2012-09-25 15:23:01', 'Connexion au systÃ¨me', '43479F'),
(979, 'moussa', '2012-09-25 15:50:51', 'Ajout d''un transfert (1, TRS-1)', '43479F'),
(980, 'moussa', '2012-09-25 15:50:51', 'Ajout des lignes de transfert (1, TRS-1)', '43479F'),
(981, 'moussa', '2012-09-25 15:50:51', 'Ajout d''un mouvement(1, TRS-1)', '43479F'),
(982, 'moussa', '2012-09-25 15:59:01', 'Connexion au systÃ¨me', '43479F'),
(983, 'moussa', '2012-09-25 16:04:33', 'Changement de l''exercice budgÃ©taire (2012, 2011 / MAG0, MAG0)', '43479F'),
(984, 'kibsa', '2012-09-25 16:22:38', 'Connexion au systÃ¨me', '677D'),
(985, 'kibsa', '2012-09-25 16:27:21', 'Ajout d''une programmation (26, 1DOT, 21)', '677D'),
(986, 'kibsa', '2012-09-25 16:27:22', 'Modification des lignes de programmation', '677D'),
(987, 'kibsa', '2012-09-25 16:30:34', 'Ajout d''une dotation (1, )', '677D'),
(988, 'kibsa', '2012-09-26 08:43:42', 'Connexion au systÃ¨me', '677D'),
(989, 'kibsa', '2012-09-26 08:43:56', 'Changement de l''exercice budgÃ©taire (2012, 2012 / MAG0, MAG2)', '677D'),
(990, 'kibsa', '2012-09-26 08:45:58', 'Ajout d''une programmation (179, 1DOT, 594)', '677D'),
(991, 'kibsa', '2012-09-26 08:45:58', 'Ajout des lignes de programmation', '677D'),
(992, 'moussa', '2012-09-27 09:39:22', 'Connexion au systÃ¨me', '43479F'),
(993, 'moussa', '2012-09-27 09:41:43', 'Connexion au systÃ¨me', '43479F'),
(994, 'moussa', '2012-09-27 09:42:29', 'Ajout d''une dotation (1, )', '43479F'),
(995, 'moussa', '2012-09-27 09:51:47', 'Changement de l''exercice budgÃ©taire (2012, 2012 / MAG0, MAG2)', '43479F'),
(996, 'moussa', '2012-09-27 09:52:14', 'Changement de l''exercice budgÃ©taire (2012, 2012 / MAG2, MAG1)', '43479F'),
(997, 'moussa', '2012-09-27 09:52:27', 'Changement de l''exercice budgÃ©taire (2012, 2012 / MAG1, MAG0)', '43479F'),
(998, 'moussa', '2012-09-27 09:54:30', 'Ajout d''une programmation (32, 1DOT, 27)', '43479F'),
(999, 'moussa', '2012-09-27 09:54:30', 'Modification des lignes de programmation', '43479F'),
(1000, 'moussa', '2012-09-27 09:56:40', 'Changement de l''exercice budgÃ©taire (2012, 2011 / MAG0, MAG0)', '43479F'),
(1001, 'moussa', '2012-09-27 09:57:50', 'Changement de l''exercice budgÃ©taire (2011, 2012 / MAG0, MAG0)', '43479F'),
(1002, 'moussa', '2012-10-01 09:00:05', 'Tentative de connexion au systÃ¨me Ã©chouÃ©e', ''),
(1003, 'moussa', '2012-10-01 09:00:21', 'Connexion au systÃ¨me', '43479F'),
(1004, 'moussa', '2012-10-01 09:12:12', 'Connexion au systÃ¨me', '43479F'),
(1006, 'moussa', '2012-10-01 10:24:02', 'Connexion au systÃ¨me', '43479F'),
(1007, 'moussa', '2012-10-01 10:27:33', 'Modification d''une affectation (269, MAG0)', '43479F'),
(1008, 'moussa', '2012-10-01 10:30:46', 'Modification d''une affectation (268, MAG 3)', '43479F'),
(1009, 'moussa', '2012-10-01 10:31:38', 'Modification d''une affectation (263, MAG 3)', '43479F'),
(1010, 'moussa', '2012-10-01 10:32:24', 'Modification d''une affectation (267, MAG 3)', '43479F'),
(1011, 'moussa', '2012-10-01 10:33:00', 'Modification d''une affectation (266, MAG 3)', '43479F'),
(1012, 'moussa', '2012-10-01 10:34:26', 'Modification d''une affectation (265, MAG 3)', '43479F'),
(1013, 'moussa', '2012-10-01 10:44:56', 'Modification d''une affectation (269, MAG 3)', '43479F'),
(1014, 'moussa', '2012-10-01 10:49:57', 'Ajout d''une affectation (MAG 3, 264)', '43479F'),
(1015, 'moussa', '2012-10-01 11:03:45', 'Ajout d''une affectation (MAG 3, 656)', '43479F'),
(1016, 'moussa', '2012-10-01 11:05:25', 'Ajout d''une affectation (MAG 3, 654)', '43479F'),
(1017, 'moussa', '2012-10-01 11:06:25', 'Ajout d''une affectation (MAG 3, 661)', '43479F'),
(1018, 'moussa', '2012-10-01 11:07:52', 'Ajout d''une affectation (MAG 3, 678)', '43479F'),
(1019, 'moussa', '2012-10-01 11:08:35', 'Ajout d''une affectation (MAG 3, 673)', '43479F'),
(1020, 'moussa', '2012-10-01 11:09:30', 'Ajout d''une affectation (MAG 3, 653)', '43479F'),
(1022, 'moussa', '2012-10-01 11:42:27', 'Connexion au systÃ¨me', '43479F'),
(1024, 'moussa', '2012-10-01 13:25:21', 'Connexion au systÃ¨me', '43479F'),
(1026, 'moussa', '2012-10-01 17:04:42', 'Tentative de connexion au systÃ¨me Ã©chouÃ©e', ''),
(1027, 'moussa', '2012-10-01 17:04:55', 'Connexion au systÃ¨me', '43479F'),
(1028, 'moussa', '2012-10-01 17:18:10', 'Changement de l''exercice budgÃ©taire (2012, 2011 / MAG0, MAG0)', '43479F'),
(1029, 'moussa', '2012-10-01 17:20:25', 'Changement de l''exercice budgÃ©taire (2011, 2012 / MAG0, MAG0)', '43479F'),
(1030, 'moussa', '2012-10-01 17:20:47', 'Changement de l''exercice budgÃ©taire (2012, 2012 / MAG0, MAG1)', '43479F'),
(1031, 'moussa', '2012-10-02 08:16:48', 'Connexion au systÃ¨me', '43479F'),
(1033, 'root', '2012-10-02 10:46:01', 'Connexion au systÃ¨me', '0345Y'),
(1034, 'root', '2012-10-02 10:46:16', 'Changement de l''exercice budgÃ©taire (2012, 2011 / MAG0, MAG0)', '0345Y'),
(1035, 'root', '2012-10-02 10:46:45', 'Changement de l''exercice budgÃ©taire (2011, 2012 / MAG0, MAG0)', '0345Y'),
(1036, 'root', '2012-10-02 10:53:37', 'Ajout d''une dotation (2, )', '0345Y'),
(1037, 'root', '2012-10-02 10:53:37', 'Ajout des lignes de dotation (2, )', '0345Y'),
(1038, 'root', '2012-10-02 10:53:37', 'Ajout d''un mouvement(2, dotation nÂ°)', '0345Y'),
(1039, 'root', '2012-10-02 11:13:46', 'Ajout d''une dotation (3, )', '0345Y'),
(1040, 'root', '2012-10-02 11:13:46', 'Ajout des lignes de dotation (3, )', '0345Y'),
(1041, 'root', '2012-10-02 11:13:46', 'Ajout d''un mouvement(3, dotation nÂ°)', '0345Y'),
(1042, 'root', '2012-10-02 11:14:03', 'Ajout d''une dotation (2, )', '0345Y'),
(1043, 'root', '2012-10-02 11:14:03', 'Validation des lignes de dotation (2, )', '0345Y'),
(1044, 'root', '2012-10-02 11:14:03', 'Validation d''un mouvement(2, dotation nÂ°)', '0345Y'),
(1045, 'root', '2012-10-02 11:14:07', 'Ajout d''une dotation (3, )', '0345Y'),
(1046, 'root', '2012-10-02 11:14:07', 'Validation des lignes de dotation (3, )', '0345Y'),
(1047, 'root', '2012-10-02 11:14:07', 'Validation d''un mouvement(3, dotation nÂ°)', '0345Y');

-- --------------------------------------------------------

--
-- Structure de la table `lvr_prd`
--

CREATE TABLE IF NOT EXISTS `lvr_prd` (
  `ID_LIVRAISON` int(11) NOT NULL,
  `ID_CONDIT` int(11) NOT NULL,
  `LVRPRD_QUANTITE` float DEFAULT NULL,
  `LVRPRD_RECU` float DEFAULT NULL,
  `LIV_UNITE` varchar(10) DEFAULT NULL,
  `LVR_IDCOMMANDE` bigint(20) DEFAULT NULL,
  `LVRPRD_MAG` varchar(20) NOT NULL,
  PRIMARY KEY (`ID_LIVRAISON`,`ID_CONDIT`),
  KEY `LVR_PRD_FK` (`ID_LIVRAISON`),
  KEY `LVR_PRD2_FK` (`ID_CONDIT`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `lvr_prd`
--

INSERT INTO `lvr_prd` (`ID_LIVRAISON`, `ID_CONDIT`, `LVRPRD_QUANTITE`, `LVRPRD_RECU`, `LIV_UNITE`, `LVR_IDCOMMANDE`, `LVRPRD_MAG`) VALUES
(1, 6, 180, 180, 'sac', 1, 'MAG0'),
(1, 12, 1400, 1400, 'cart', 1, 'MAG0'),
(1, 13, 3500, 3500, 'sht', 1, 'MAG0'),
(1, 15, 280, 280, 'sac', 1, 'MAG0'),
(1, 16, 4700, 4700, 'bt', 1, 'MAG0'),
(1, 17, 140, 140, 'cart', 1, 'MAG0'),
(2, 9, 1250, 1250, 'bid', 2, 'MAG0'),
(2, 10, 7000, 7000, 'cart', 2, 'MAG0'),
(2, 11, 800, 800, 'cart', 2, 'MAG0');

-- --------------------------------------------------------

--
-- Structure de la table `magasin`
--

CREATE TABLE IF NOT EXISTS `magasin` (
  `CODE_MAGASIN` varchar(10) NOT NULL,
  `IDPROVINCE` int(11) NOT NULL,
  `ID_SERVICE` int(11) NOT NULL,
  `MAG_NOM` varchar(50) DEFAULT NULL,
  `MAG_VILLE` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`CODE_MAGASIN`),
  KEY `SER_MAG_FK` (`ID_SERVICE`),
  KEY `MAG_PROVINCE_FK` (`IDPROVINCE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `magasin`
--

INSERT INTO `magasin` (`CODE_MAGASIN`, `IDPROVINCE`, `ID_SERVICE`, `MAG_NOM`, `MAG_VILLE`) VALUES
('MAG 3', 33, 13, 'Magasin de Ouahigouya', ''),
('MAG 4', 45, 11, 'Magasin de Gaoua', 'Gaoua'),
('MAG 5', 15, 14, 'Magasin de Kaya', 'Kaya'),
('MAG 6', 4, 12, 'Magasin de DÃ©dougou', 'DÃ©dougou'),
('MAG0', 9, 1, 'Magasin de Ouagadougou', 'Ouagadougou'),
('MAG1', 28, 2, 'Magasin de Bobo-dioulasso', 'Bobo-dioulasso'),
('MAG2', 24, 10, 'Magasin de Fada N''Gourma', 'Fada N''Gourma');

-- --------------------------------------------------------

--
-- Structure de la table `magrever`
--

CREATE TABLE IF NOT EXISTS `magrever` (
  `ID_REVERSEMENT` int(11) NOT NULL,
  `CODE_MAGASIN` varchar(10) NOT NULL,
  PRIMARY KEY (`ID_REVERSEMENT`,`CODE_MAGASIN`),
  KEY `MAGREVER_FK` (`ID_REVERSEMENT`),
  KEY `MAGREVER2_FK` (`CODE_MAGASIN`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `mag_compte`
--

CREATE TABLE IF NOT EXISTS `mag_compte` (
  `CODE_MAGASIN` varchar(10) NOT NULL,
  `LOGIN` varchar(10) NOT NULL,
  PRIMARY KEY (`CODE_MAGASIN`,`LOGIN`),
  KEY `MAG_COMPTE_FK` (`CODE_MAGASIN`),
  KEY `MAG_COMPTE2_FK` (`LOGIN`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `mag_compte`
--

INSERT INTO `mag_compte` (`CODE_MAGASIN`, `LOGIN`) VALUES
('MAG0', 'aline'),
('MAG0', 'kibsa'),
('MAG0', 'maiga'),
('MAG0', 'moussa'),
('MAG0', 'root'),
('MAG1', 'kibsa'),
('MAG1', 'moussa'),
('MAG1', 'root'),
('MAG2', 'kam'),
('MAG2', 'kibsa'),
('MAG2', 'moussa'),
('MAG2', 'root');

-- --------------------------------------------------------

--
-- Structure de la table `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `IDMENU` varchar(10) NOT NULL,
  `LIBMENU` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`IDMENU`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `menu`
--

INSERT INTO `menu` (`IDMENU`, `LIBMENU`) VALUES
('aid', 'Aide'),
('cde', 'Menu Commandes/Livraisons'),
('cde_ali', 'Autres livraisons'),
('cde_cde', 'Commandes'),
('cde_liv', 'Livraisons'),
('int', 'Inventaire'),
('int_int', 'Inventaire de stock'),
('int_jou', 'Journal des mouvements de stock'),
('int_sto', 'Etat du stock'),
('mvt', 'Menu Mouvement sur stock'),
('mvt_bac', 'Mouvemant des dotations BAC'),
('mvt_dec', 'DÃ©classement'),
('mvt_dot', 'Dotation'),
('mvt_rep', 'Reports'),
('mvt_trf', 'Transferts'),
('par', 'ParamÃ©trage'),
('par_aff', 'Affectation des Ã©tablissements'),
('par_bac', 'BarÃªme BAC'),
('par_bar', 'BarÃªme'),
('par_ben', 'BÃ©nÃ©ficiaire'),
('par_bud', 'Exercice budgÃ©taire'),
('par_cat', 'CatÃ©gorie produit'),
('par_cdt', 'Conditionnement'),
('par_con', 'Conditionnement'),
('par_ctr', 'Centres d''examen'),
('par_dot', 'Type de dotation'),
('par_exe', 'Exercice budgÃ©taire'),
('par_fou', 'Fournisseur'),
('par_gen', 'ParamÃ¨tres gÃ©nÃ©raux'),
('par_grp', 'Groupe d''utilisateur'),
('par_inf', 'Infos annuelles bÃ©nÃ©ficiaire'),
('par_log', 'Logs utilisateurs'),
('par_mag', 'Magasin'),
('par_men', 'Menu d''accÃ¨s'),
('par_per', 'Personnel'),
('par_prd', 'Produits'),
('par_prv', 'Province'),
('par_reg', 'RÃ©gion'),
('par_sau', 'Sauvegarde'),
('par_ser', 'Service'),
('par_tbe', 'Type de bÃ©nÃ©ficiaire'),
('par_tse', 'Type de service'),
('par_uni', 'UnitÃ© de mesure'),
('par_uti', 'Compte utilisateur'),
('prg', 'Menu programmation'),
('prg_bac', 'Programmation BAC'),
('prg_prg', 'Mise Ã  jour des programmations'),
('prg_rvs', 'Reversement');

-- --------------------------------------------------------

--
-- Structure de la table `mouvement`
--

CREATE TABLE IF NOT EXISTS `mouvement` (
  `ID_MOUVEMENT` int(11) NOT NULL AUTO_INCREMENT,
  `ID_CONDIT` int(11) NOT NULL,
  `ID_EXERCICE` int(11) NOT NULL,
  `CODE_MAGASIN` varchar(10) NOT NULL,
  `ID_SOURCE` bigint(20) DEFAULT NULL,
  `ID_MAGASIN` varchar(10) DEFAULT NULL,
  `MVT_DATE` date DEFAULT NULL,
  `MVT_TIME` time DEFAULT NULL,
  `MVT_QUANTITE` float DEFAULT NULL,
  `MVT_UNITE` varchar(10) DEFAULT NULL,
  `MVT_NATURE` varchar(30) DEFAULT NULL,
  `MVT_VALID` tinyint(4) DEFAULT NULL,
  `MVT_DATEVALID` datetime DEFAULT NULL,
  PRIMARY KEY (`ID_MOUVEMENT`),
  KEY `MVT_MAG_FK` (`CODE_MAGASIN`),
  KEY `MVT_CND_FK` (`ID_CONDIT`),
  KEY `EX_MVT_FK` (`ID_EXERCICE`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Contenu de la table `mouvement`
--

INSERT INTO `mouvement` (`ID_MOUVEMENT`, `ID_CONDIT`, `ID_EXERCICE`, `CODE_MAGASIN`, `ID_SOURCE`, `ID_MAGASIN`, `MVT_DATE`, `MVT_TIME`, `MVT_QUANTITE`, `MVT_UNITE`, `MVT_NATURE`, `MVT_VALID`, `MVT_DATEVALID`) VALUES
(1, 6, 2012, 'MAG0', 1, NULL, '2012-08-16', '12:12:22', 180, 'sac', 'LIVRAISON', 1, NULL),
(2, 12, 2012, 'MAG0', 1, NULL, '2012-08-16', '12:12:22', 1400, 'cart', 'LIVRAISON', 1, NULL),
(3, 13, 2012, 'MAG0', 1, NULL, '2012-08-16', '12:12:22', 3500, 'sht', 'LIVRAISON', 1, NULL),
(4, 15, 2012, 'MAG0', 1, NULL, '2012-08-16', '12:12:22', 280, 'sac', 'LIVRAISON', 1, NULL),
(5, 16, 2012, 'MAG0', 1, NULL, '2012-08-16', '12:12:22', 4700, 'bt', 'LIVRAISON', 1, NULL),
(6, 17, 2012, 'MAG0', 1, NULL, '2012-08-16', '12:12:22', 140, 'cart', 'LIVRAISON', 1, NULL),
(7, 9, 2012, 'MAG0', 2, NULL, '2012-08-16', '12:12:18', 1250, 'bid', 'LIVRAISON', 1, NULL),
(8, 10, 2012, 'MAG0', 2, NULL, '2012-08-16', '12:12:18', 7000, 'cart', 'LIVRAISON', 1, NULL),
(9, 11, 2012, 'MAG0', 2, NULL, '2012-08-16', '12:12:18', 800, 'cart', 'LIVRAISON', 1, NULL),
(10, 9, 2012, 'MAG0', 1, NULL, '2012-09-25', '15:50:51', 20, 'bid', 'TRANSFERT SORTANT', 0, NULL),
(11, 2, 2012, 'MAG0', 1, NULL, '2012-09-25', '15:50:51', 50, 'sac', 'TRANSFERT SORTANT', 0, NULL),
(12, 2, 2012, 'MAG0', 2, NULL, '2012-10-02', '11:14:03', 46, 'sac', 'DOTATION', 1, NULL),
(13, 6, 2012, 'MAG0', 2, NULL, '2012-10-02', '11:14:03', 10, 'sac', 'DOTATION', 1, NULL),
(14, 9, 2012, 'MAG0', 2, NULL, '2012-10-02', '11:14:03', 10, 'bid', 'DOTATION', 1, NULL),
(15, 12, 2012, 'MAG0', 2, NULL, '2012-10-02', '11:14:03', 50, 'cart', 'DOTATION', 1, NULL),
(16, 11, 2012, 'MAG0', 2, NULL, '2012-10-02', '11:14:03', 6, 'cart', 'DOTATION', 1, NULL),
(17, 2, 2012, 'MAG0', 3, NULL, '2012-10-02', '11:14:07', 50, 'sac', 'DOTATION', 1, NULL),
(18, 6, 2012, 'MAG0', 3, NULL, '2012-10-02', '11:14:07', 10, 'sac', 'DOTATION', 1, NULL),
(19, 9, 2012, 'MAG0', 3, NULL, '2012-10-02', '11:14:07', 11, 'bid', 'DOTATION', 1, NULL),
(20, 12, 2012, 'MAG0', 3, NULL, '2012-10-02', '11:14:07', 50, 'cart', 'DOTATION', 1, NULL),
(21, 11, 2012, 'MAG0', 3, NULL, '2012-10-02', '11:14:07', 7, 'cart', 'DOTATION', 1, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `nombeneficiaire`
--

CREATE TABLE IF NOT EXISTS `nombeneficiaire` (
  `CODE_NOMBENF` varchar(10) NOT NULL,
  `NBENEF_LIBELLE` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`CODE_NOMBENF`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `nombeneficiaire`
--

INSERT INTO `nombeneficiaire` (`CODE_NOMBENF`, `NBENEF_LIBELLE`) VALUES
('AUT', 'Autres'),
('ETB', 'Etablissement au programme');

-- --------------------------------------------------------

--
-- Structure de la table `nomdotation`
--

CREATE TABLE IF NOT EXISTS `nomdotation` (
  `CODE_NDOTATION` varchar(10) NOT NULL,
  `NDOT_LIBELLE` varchar(50) NOT NULL,
  PRIMARY KEY (`CODE_NDOTATION`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `nomdotation`
--

INSERT INTO `nomdotation` (`CODE_NDOTATION`, `NDOT_LIBELLE`) VALUES
('10DOT', 'Programmation BAC'),
('1DOT', 'PremiÃ¨re dotation'),
('2DOT', 'DeuxiÃ¨me dotation'),
('3DOT', 'TroisiÃ¨me dotation'),
('ADOT', 'Autres dotations');

-- --------------------------------------------------------

--
-- Structure de la table `personnel`
--

CREATE TABLE IF NOT EXISTS `personnel` (
  `NUM_MLLE` varchar(10) NOT NULL,
  `ID_SERVICE` int(11) NOT NULL,
  `PERS_NOM` varchar(50) NOT NULL,
  `PERS_PRENOMS` varchar(50) NOT NULL,
  `PERS_TEL` varchar(30) DEFAULT NULL,
  `PERS_ADRESSE` varchar(100) DEFAULT NULL,
  `PERS_EMAIL` varchar(100) DEFAULT NULL,
  `PERS_FONCTION` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`NUM_MLLE`),
  KEY `PERS_SER_FK` (`ID_SERVICE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `personnel`
--

INSERT INTO `personnel` (`NUM_MLLE`, `ID_SERVICE`, `PERS_NOM`, `PERS_PRENOMS`, `PERS_TEL`, `PERS_ADRESSE`, `PERS_EMAIL`, `PERS_FONCTION`) VALUES
('0345Y', 2, 'COULIBALY', 'Drissa', '+226 70266800', '', 'kaagny@gmail.com', 'DÃ©veloppeur de logiciels'),
('09898T', 10, 'Kam', 'Beh Jacob', '+226 786767', '01 BP 6767', 'kam@hotmail.com', 'Gestionnaire cantine'),
('43479F', 1, 'OUEDRAOGO', 'Moussa', '70292127', '01 BP 4545', 'ouedmous69@yahoo.fr', 'Gestionnaire magasin'),
('677D', 1, 'OUEDRAOGO', 'Kibsa', '789999', '', 'ouedkibsa@yahoo.fr', 'Chef de service'),
('M', 1, 'SAWADOGO/OUEDRAOGO', 'Aline', '50505050', '', '', ''),
('MM', 1, 'NARE/MAIGA', 'Fatoumata', '50505050', '', '', '');

-- --------------------------------------------------------

--
-- Structure de la table `prg_bareme`
--

CREATE TABLE IF NOT EXISTS `prg_bareme` (
  `ID_PROGR` int(11) NOT NULL,
  `ID_BAREME` int(11) NOT NULL,
  `PRG_QTE1` float DEFAULT NULL,
  `PRG_QTE2` float DEFAULT NULL,
  `PRG_RATION1` float DEFAULT NULL,
  `PRG_RATION2` float DEFAULT NULL,
  `PRG_PRIX` float DEFAULT NULL,
  `NBRE_PLAT1` float DEFAULT NULL,
  `NBRE_PLAT2` float DEFAULT NULL,
  `PRG_REVERSEMENT` float DEFAULT NULL,
  `PRG_UNITE` varchar(10) DEFAULT NULL,
  `PRG_NBREJ` int(11) NOT NULL,
  `PRG_RATION21J` float NOT NULL,
  PRIMARY KEY (`ID_PROGR`,`ID_BAREME`),
  KEY `PRG_BAREME_FK` (`ID_PROGR`),
  KEY `PRG_BAREME2_FK` (`ID_BAREME`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `prg_bareme`
--

INSERT INTO `prg_bareme` (`ID_PROGR`, `ID_BAREME`, `PRG_QTE1`, `PRG_QTE2`, `PRG_RATION1`, `PRG_RATION2`, `PRG_PRIX`, `NBRE_PLAT1`, `NBRE_PLAT2`, `PRG_REVERSEMENT`, `PRG_UNITE`, `PRG_NBREJ`, `PRG_RATION21J`) VALUES
(1, 1, 2000, 0, 0.275, 0, 35, 7273, 0, 254555, 'kg', 0, 0),
(1, 2, 500, 0, 0.275, 0, 35, 1818, 0, 63630, 'kg', 0, 0),
(1, 3, 9, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(1, 4, 500, 0, 0.25, 0, 35, 2000, 0, 70000, 'kg', 0, 0),
(1, 5, 44, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(1, 6, 6, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(2, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(2, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(2, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(2, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(2, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(2, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(3, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(3, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(3, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(3, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(3, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(3, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(4, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(4, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(4, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(4, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(4, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(4, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(5, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(5, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(5, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(5, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(5, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(5, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(6, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(6, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(6, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(6, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(6, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(6, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(7, 1, 2000, 0, 0.275, 0, 35, 7273, 0, 254555, 'kg', 0, 0),
(7, 2, 500, 0, 0.275, 0, 35, 1818, 0, 63630, 'kg', 0, 0),
(7, 3, 9, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(7, 4, 500, 0, 0.25, 0, 35, 2000, 0, 70000, 'kg', 0, 0),
(7, 5, 44, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(7, 6, 6, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(8, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(8, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(8, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(8, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(8, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(8, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(9, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(9, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(9, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(9, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(9, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(9, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(10, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(10, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(10, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(10, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(10, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(10, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(11, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(11, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(11, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(11, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(11, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(11, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(12, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(12, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(12, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(12, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(12, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(12, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(13, 1, 1300, 0, 0.275, 0, 35, 4727, 0, 165445, 'kg', 0, 0),
(13, 2, 400, 0, 0.275, 0, 35, 1455, 0, 50925, 'kg', 0, 0),
(13, 3, 6, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(13, 4, 300, 0, 0.25, 0, 35, 1200, 0, 42000, 'kg', 0, 0),
(13, 5, 30, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(13, 6, 4, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(14, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(14, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(14, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(14, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(14, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(14, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(15, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(15, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(15, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(15, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(15, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(15, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(16, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(16, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(16, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(16, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(16, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(16, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(17, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(17, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(17, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(17, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(17, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(17, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(18, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(18, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(18, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(18, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(18, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(18, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(19, 1, 1300, 0, 0.275, 0, 35, 4727, 0, 165445, 'kg', 0, 0),
(19, 2, 400, 0, 0.275, 0, 35, 1455, 0, 50925, 'kg', 0, 0),
(19, 3, 6, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(19, 4, 300, 0, 0.25, 0, 35, 1200, 0, 42000, 'kg', 0, 0),
(19, 5, 30, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(19, 6, 4, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(20, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(20, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(20, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(20, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(20, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(20, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(21, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(21, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(21, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(21, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(21, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(21, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(22, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(22, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(22, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(22, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(22, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(22, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(23, 1, 1300, 0, 0.275, 0, 35, 4727, 0, 165445, 'kg', 0, 0),
(23, 2, 400, 0, 0.275, 0, 35, 1455, 0, 50925, 'kg', 0, 0),
(23, 3, 6, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(23, 4, 300, 0, 0.25, 0, 35, 1200, 0, 42000, 'kg', 0, 0),
(23, 5, 30, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(23, 6, 4, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(24, 1, 1300, 0, 0.275, 0, 35, 4727, 0, 165445, 'kg', 0, 0),
(24, 2, 400, 0, 0.275, 0, 35, 1455, 0, 50925, 'kg', 0, 0),
(24, 3, 6, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(24, 4, 300, 0, 0.25, 0, 35, 1200, 0, 42000, 'kg', 0, 0),
(24, 5, 30, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(24, 6, 4, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(25, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(25, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(25, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(25, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(25, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(25, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(26, 1, 2500, 0, 0.275, 0, 35, 9091, 0, 318185, 'kg', 0, 0),
(26, 2, 500, 0, 0.275, 0, 35, 1818, 0, 63630, 'kg', 0, 0),
(26, 3, 11, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(26, 4, 500, 0, 0.25, 0, 35, 2000, 0, 70000, 'kg', 0, 0),
(26, 5, 0, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(26, 6, 7, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(27, 1, 2100, 0, 0.275, 0, 35, 7636, 0, 267260, 'kg', 0, 0),
(27, 2, 500, 0, 0.275, 0, 35, 1818, 0, 63630, 'kg', 0, 0),
(27, 3, 9, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(27, 4, 500, 0, 0.25, 0, 35, 2000, 0, 70000, 'kg', 0, 0),
(27, 5, 0, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(27, 6, 6, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(28, 1, 1300, 0, 0.275, 0, 35, 4727, 0, 165445, 'kg', 0, 0),
(28, 2, 400, 0, 0.275, 0, 35, 1455, 0, 50925, 'kg', 0, 0),
(28, 3, 5, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(28, 4, 300, 0, 0.25, 0, 35, 1200, 0, 42000, 'kg', 0, 0),
(28, 5, 0, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(28, 6, 3, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(29, 1, 1300, 0, 0.275, 0, 35, 4727, 0, 165445, 'kg', 0, 0),
(29, 2, 400, 0, 0.275, 0, 35, 1455, 0, 50925, 'kg', 0, 0),
(29, 3, 6, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(29, 4, 300, 0, 0.25, 0, 35, 1200, 0, 42000, 'kg', 0, 0),
(29, 5, 0, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(29, 6, 4, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(30, 1, 2100, 0, 0.275, 0, 35, 7636, 0, 267260, 'kg', 0, 0),
(30, 2, 500, 0, 0.275, 0, 35, 1818, 0, 63630, 'kg', 0, 0),
(30, 3, 9, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(30, 4, 500, 0, 0.25, 0, 35, 2000, 0, 70000, 'kg', 0, 0),
(30, 5, 0, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(30, 6, 6, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(31, 1, 1300, 0, 0.275, 0, 35, 4727, 0, 165445, 'kg', 0, 0),
(31, 2, 400, 0, 0.275, 0, 35, 1455, 0, 50925, 'kg', 0, 0),
(31, 3, 6, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(31, 4, 300, 0, 0.25, 0, 35, 1200, 0, 42000, 'kg', 0, 0),
(31, 5, 0, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(31, 6, 4, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(32, 1, 2300, 0, 0.275, 0, 35, 8364, 0, 292740, 'kg', 0, 0),
(32, 2, 500, 0, 0.275, 0, 35, 1818, 0, 63630, 'kg', 0, 0),
(32, 3, 10, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(32, 4, 500, 0, 0.25, 0, 35, 2000, 0, 70000, 'kg', 0, 0),
(32, 5, 0, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(32, 6, 6, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(33, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(33, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(33, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(33, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(33, 5, 0, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(33, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(34, 1, 2100, 0, 0.275, 0, 35, 7636, 0, 267260, 'kg', 0, 0),
(34, 2, 500, 0, 0.275, 0, 35, 1818, 0, 63630, 'kg', 0, 0),
(34, 3, 5, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(34, 4, 500, 0, 0.25, 0, 35, 2000, 0, 70000, 'kg', 0, 0),
(34, 5, 0, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(34, 6, 6, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(35, 1, 1200, 0, 0.275, 0, 35, 4364, 0, 152740, 'kg', 0, 0),
(35, 2, 300, 0, 0.275, 0, 35, 1091, 0, 38185, 'kg', 0, 0),
(35, 3, 5, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(35, 4, 300, 0, 0.25, 0, 35, 1200, 0, 42000, 'kg', 0, 0),
(35, 5, 0, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(35, 6, 3, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(36, 1, 1300, 0, 0.275, 0, 35, 4727, 0, 165445, 'kg', 0, 0),
(36, 2, 400, 0, 0.275, 0, 35, 1455, 0, 50925, 'kg', 0, 0),
(36, 3, 6, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(36, 4, 300, 0, 0.25, 0, 35, 1200, 0, 42000, 'kg', 0, 0),
(36, 5, 0, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(36, 6, 4, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(37, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(37, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(37, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(37, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(37, 5, 0, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(37, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(38, 1, 1300, 0, 0.275, 0, 35, 4727, 0, 165445, 'kg', 0, 0),
(38, 2, 400, 0, 0.275, 0, 35, 1455, 0, 50925, 'kg', 0, 0),
(38, 3, 6, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(38, 4, 300, 0, 0.25, 0, 35, 1200, 0, 42000, 'kg', 0, 0),
(38, 5, 0, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(38, 6, 4, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(39, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(39, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(39, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(39, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(39, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(39, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(40, 1, 1300, 0, 0.275, 0, 35, 4727, 0, 165445, 'kg', 0, 0),
(40, 2, 400, 0, 0.275, 0, 35, 1455, 0, 50925, 'kg', 0, 0),
(40, 3, 6, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(40, 4, 300, 0, 0.25, 0, 35, 1200, 0, 42000, 'kg', 0, 0),
(40, 5, 30, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(40, 6, 4, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(41, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(41, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(41, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(41, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(41, 5, 0, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(41, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(42, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(42, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(42, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(42, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(42, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(42, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(43, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(43, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(43, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(43, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(43, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(43, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(44, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(44, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(44, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(44, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(44, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(44, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(45, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(45, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(45, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(45, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(45, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(45, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(46, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(46, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(46, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(46, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(46, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(46, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(47, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(47, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(47, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(47, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(47, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(47, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(48, 1, 1300, 0, 0.275, 0, 35, 4727, 0, 165445, 'kg', 0, 0),
(48, 2, 400, 0, 0.275, 0, 35, 1455, 0, 50925, 'kg', 0, 0),
(48, 3, 6, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(48, 4, 300, 0, 0.25, 0, 35, 1200, 0, 42000, 'kg', 0, 0),
(48, 5, 30, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(48, 6, 4, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(49, 1, 1300, 0, 0.275, 0, 35, 4727, 0, 165445, 'kg', 0, 0),
(49, 2, 400, 0, 0.275, 0, 35, 1455, 0, 50925, 'kg', 0, 0),
(49, 3, 6, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(49, 4, 300, 0, 0.25, 0, 35, 1200, 0, 42000, 'kg', 0, 0),
(49, 5, 30, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(49, 6, 4, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(50, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(50, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(50, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(50, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(50, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(50, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(51, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(51, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(51, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(51, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(51, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(51, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(52, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(52, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(52, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(52, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(52, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(52, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(53, 1, 600, 0, 0.275, 0, 35, 2182, 0, 76370, 'kg', 0, 0),
(53, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(53, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(53, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(53, 5, 13, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(53, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(54, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(54, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(54, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(54, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(54, 5, 13, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(54, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(55, 1, 600, 0, 0.275, 0, 35, 2182, 0, 76370, 'kg', 0, 0),
(55, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(55, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(55, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(55, 5, 13, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(55, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(56, 1, 600, 0, 0.275, 0, 35, 2182, 0, 76370, 'kg', 0, 0),
(56, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(56, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(56, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(56, 5, 13, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(56, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(57, 1, 600, 0, 0.275, 0, 35, 2182, 0, 76370, 'kg', 0, 0),
(57, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(57, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(57, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(57, 5, 13, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(57, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(58, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(58, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(58, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(58, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(58, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(58, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(59, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(59, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(59, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(59, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(59, 5, 19, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(59, 6, 3, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(60, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(60, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(60, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(60, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(60, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(60, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(61, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(61, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(61, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(61, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(61, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(61, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(62, 1, 600, 0, 0.275, 0, 35, 2182, 0, 76370, 'kg', 0, 0),
(62, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(62, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(62, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(62, 5, 13, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(62, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(63, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(63, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(63, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(63, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(63, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(63, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(64, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(64, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(64, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(64, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(64, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(64, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(65, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(65, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(65, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(65, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(65, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(65, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(66, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(66, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(66, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(66, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(66, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(66, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(67, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(67, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(67, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(67, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(67, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(67, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(68, 1, 600, 0, 0.275, 0, 35, 2182, 0, 76370, 'kg', 0, 0),
(68, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(68, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(68, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(68, 5, 13, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(68, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(69, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(69, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(69, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(69, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(69, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(69, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(70, 1, 2000, 0, 0.275, 0, 35, 7273, 0, 254555, 'kg', 0, 0),
(70, 2, 500, 0, 0.275, 0, 35, 1818, 0, 63630, 'kg', 0, 0),
(70, 3, 9, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(70, 4, 500, 0, 0.25, 0, 35, 2000, 0, 70000, 'kg', 0, 0),
(70, 5, 44, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(70, 6, 6, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(71, 1, 2000, 0, 0.275, 0, 35, 7273, 0, 254555, 'kg', 0, 0),
(71, 2, 500, 0, 0.275, 0, 35, 1818, 0, 63630, 'kg', 0, 0),
(71, 3, 9, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(71, 4, 500, 0, 0.25, 0, 35, 2000, 0, 70000, 'kg', 0, 0),
(71, 5, 44, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(71, 6, 6, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(72, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(72, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(72, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(72, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(72, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(72, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(73, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(73, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(73, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(73, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(73, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(73, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(74, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(74, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(74, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(74, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(74, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(74, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(75, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(75, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(75, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(75, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(75, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(75, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(76, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(76, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(76, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(76, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(76, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(76, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(77, 1, 1300, 0, 0.275, 0, 35, 4727, 0, 165445, 'kg', 0, 0),
(77, 2, 400, 0, 0.275, 0, 35, 1455, 0, 50925, 'kg', 0, 0),
(77, 3, 6, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(77, 4, 300, 0, 0.25, 0, 35, 1200, 0, 42000, 'kg', 0, 0),
(77, 5, 4, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(77, 6, 30, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(78, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(78, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(78, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(78, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(78, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(78, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(79, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(79, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(79, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(79, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(79, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(79, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(80, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(80, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(80, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(80, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(80, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(80, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(81, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(81, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(81, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(81, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(81, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(81, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(82, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(82, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(82, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(82, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(82, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(82, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(83, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(83, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(83, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(83, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(83, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(83, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(84, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(84, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(84, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(84, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(84, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(84, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(85, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(85, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(85, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(85, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(85, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(85, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(86, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(86, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(86, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(86, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(86, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(86, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(87, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(87, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(87, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(87, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(87, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(87, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(88, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(88, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(88, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(88, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(88, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(88, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(89, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(89, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(89, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(89, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(89, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(89, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(90, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(90, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(90, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(90, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(90, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(90, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(91, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(91, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(91, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(91, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(91, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(91, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(92, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(92, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(92, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(92, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(92, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(92, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(93, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(93, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(93, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(93, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(93, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(93, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(94, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(94, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(94, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(94, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(94, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(94, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(95, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(95, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(95, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(95, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(95, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(95, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(96, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(96, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(96, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(96, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(96, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(96, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(97, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(97, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(97, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(97, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(97, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(97, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(98, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(98, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(98, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(98, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(98, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(98, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(99, 1, 1300, 0, 0.275, 0, 35, 4727, 0, 165445, 'kg', 0, 0),
(99, 2, 400, 0, 0.275, 0, 35, 1455, 0, 50925, 'kg', 0, 0),
(99, 3, 6, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(99, 4, 300, 0, 0.25, 0, 35, 1200, 0, 42000, 'kg', 0, 0),
(99, 5, 30, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(99, 6, 4, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(100, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(100, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(100, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(100, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(100, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(100, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(101, 1, 1300, 0, 0.275, 0, 35, 4727, 0, 165445, 'kg', 0, 0),
(101, 2, 400, 0, 0.275, 0, 35, 1455, 0, 50925, 'kg', 0, 0),
(101, 3, 6, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(101, 4, 300, 0, 0.25, 0, 35, 1200, 0, 42000, 'kg', 0, 0),
(101, 5, 30, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(101, 6, 4, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(102, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(102, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(102, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(102, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(102, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(102, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(103, 1, 1300, 0, 0.275, 0, 35, 4727, 0, 165445, 'kg', 0, 0),
(103, 2, 400, 0, 0.275, 0, 35, 1455, 0, 50925, 'kg', 0, 0),
(103, 3, 6, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(103, 4, 300, 0, 0.25, 0, 35, 1200, 0, 42000, 'kg', 0, 0),
(103, 5, 30, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(103, 6, 4, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(104, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(104, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(104, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(104, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(104, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(104, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(105, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(105, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(105, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(105, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(105, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(105, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(106, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(106, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(106, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(106, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(106, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(106, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(107, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(107, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(107, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(107, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(107, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(107, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(108, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(108, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(108, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(108, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(108, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(108, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(109, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(109, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(109, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(109, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(109, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(109, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(110, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(110, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(110, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(110, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(110, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(110, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(111, 1, 1300, 0, 0.275, 0, 35, 4727, 0, 165445, 'kg', 0, 0),
(111, 2, 400, 0, 0.275, 0, 35, 1455, 0, 50925, 'kg', 0, 0),
(111, 3, 6, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(111, 4, 300, 0, 0.25, 0, 35, 1200, 0, 42000, 'kg', 0, 0),
(111, 5, 30, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(111, 6, 4, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(112, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(112, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(112, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(112, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(112, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(112, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(113, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(113, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(113, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(113, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(113, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(113, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(114, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(114, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(114, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(114, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(114, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(114, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(115, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(115, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(115, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(115, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(115, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(115, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(116, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(116, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(116, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(116, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(116, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(116, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(117, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(117, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(117, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(117, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(117, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(117, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(118, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(118, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(118, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(118, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(118, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(118, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(119, 1, 1300, 0, 0.275, 0, 35, 4727, 0, 165445, 'kg', 0, 0),
(119, 2, 400, 0, 0.275, 0, 35, 1455, 0, 50925, 'kg', 0, 0),
(119, 3, 6, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(119, 4, 300, 0, 0.25, 0, 35, 1200, 0, 42000, 'kg', 0, 0),
(119, 5, 30, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(119, 6, 4, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(120, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(120, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(120, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(120, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(120, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(120, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(121, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(121, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(121, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(121, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(121, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(121, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(122, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(122, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(122, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(122, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(122, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(122, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(123, 1, 600, 0, 0.275, 0, 35, 2182, 0, 76370, 'kg', 0, 0),
(123, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(123, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(123, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(123, 5, 13, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(123, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(124, 1, 600, 0, 0.275, 0, 35, 2182, 0, 76370, 'kg', 0, 0),
(124, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(124, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(124, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(124, 5, 13, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(124, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(125, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(125, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(125, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(125, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(125, 5, 13, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(125, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(126, 1, 600, 0, 0.275, 0, 35, 2182, 0, 76370, 'kg', 0, 0),
(126, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(126, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(126, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(126, 5, 13, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(126, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(127, 1, 600, 0, 0.275, 0, 35, 2182, 0, 76370, 'kg', 0, 0),
(127, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(127, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(127, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(127, 5, 13, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(127, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(128, 1, 600, 0, 0.275, 0, 35, 2182, 0, 76370, 'kg', 0, 0),
(128, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(128, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(128, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(128, 5, 13, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(128, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(129, 1, 1300, 0, 0.275, 0, 35, 4727, 0, 165445, 'kg', 0, 0),
(129, 2, 400, 0, 0.275, 0, 35, 1455, 0, 50925, 'kg', 0, 0),
(129, 3, 6, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(129, 4, 300, 0, 0.25, 0, 35, 1200, 0, 42000, 'kg', 0, 0),
(129, 5, 30, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(129, 6, 4, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(130, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(130, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(130, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(130, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(130, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(130, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(131, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(131, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(131, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(131, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(131, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(131, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(132, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(132, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(132, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(132, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(132, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(132, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(133, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(133, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(133, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(133, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(133, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(133, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(134, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(134, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(134, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(134, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(134, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(134, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(135, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(135, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(135, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(135, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(135, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(135, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(136, 1, 1300, 0, 0.275, 0, 35, 4727, 0, 165445, 'kg', 0, 0),
(136, 2, 400, 0, 0.275, 0, 35, 1455, 0, 50925, 'kg', 0, 0),
(136, 3, 6, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(136, 4, 300, 0, 0.25, 0, 35, 1200, 0, 42000, 'kg', 0, 0),
(136, 5, 30, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(136, 6, 4, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(137, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(137, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(137, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(137, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(137, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(137, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(138, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(138, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(138, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(138, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(138, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(138, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(139, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(139, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(139, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(139, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(139, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(139, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(140, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(140, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(140, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(140, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(140, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(140, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(141, 1, 1300, 0, 0.275, 0, 35, 4727, 0, 165445, 'kg', 0, 0),
(141, 2, 400, 0, 0.275, 0, 35, 1455, 0, 50925, 'kg', 0, 0),
(141, 3, 6, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(141, 4, 300, 0, 0.25, 0, 35, 1200, 0, 42000, 'kg', 0, 0),
(141, 5, 30, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(141, 6, 4, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(142, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(142, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(142, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(142, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(142, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(142, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(143, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(143, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(143, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(143, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(143, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(143, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(144, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(144, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(144, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(144, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(144, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(144, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(145, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(145, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(145, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(145, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(145, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(145, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(146, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(146, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(146, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(146, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(146, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(146, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(147, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(147, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(147, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(147, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(147, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(147, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(148, 1, 1300, 0, 0.275, 0, 35, 4727, 0, 165445, 'kg', 0, 0),
(148, 2, 400, 0, 0.275, 0, 35, 1455, 0, 50925, 'kg', 0, 0),
(148, 3, 6, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(148, 4, 1200, 0, 0.25, 0, 35, 4800, 0, 168000, 'kg', 0, 0),
(148, 5, 30, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(148, 6, 4, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(149, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(149, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(149, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(149, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(149, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(149, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(150, 1, 1300, 0, 0.275, 0, 35, 4727, 0, 165445, 'kg', 0, 0),
(150, 2, 400, 0, 0.275, 0, 35, 1455, 0, 50925, 'kg', 0, 0),
(150, 3, 6, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(150, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(150, 5, 28, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(150, 6, 4, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(151, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(151, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(151, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(151, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(151, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(151, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(152, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(152, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(152, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(152, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(152, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(152, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(153, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(153, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(153, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(153, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(153, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(153, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(154, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(154, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(154, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(154, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(154, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(154, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(155, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(155, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(155, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(155, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(155, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(155, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(156, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(156, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(156, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(156, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(156, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(156, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(157, 1, 700, 0, 0.275, 0, 35, 2545, 0, 89075, 'kg', 0, 0),
(157, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(157, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(157, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(157, 5, 15, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(157, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(158, 1, 600, 0, 0.275, 0, 35, 2182, 0, 76370, 'kg', 0, 0),
(158, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(158, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(158, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(158, 5, 13, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(158, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(159, 1, 600, 0, 0.275, 0, 35, 2182, 0, 76370, 'kg', 0, 0),
(159, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(159, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(159, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(159, 5, 13, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(159, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(160, 1, 600, 0, 0.275, 0, 35, 2182, 0, 76370, 'kg', 0, 0),
(160, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(160, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(160, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(160, 5, 13, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(160, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(161, 1, 600, 0, 0.275, 0, 35, 2182, 0, 76370, 'kg', 0, 0),
(161, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(161, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(161, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(161, 5, 13, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(161, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(162, 1, 600, 0, 0.275, 0, 35, 2182, 0, 76370, 'kg', 0, 0),
(162, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(162, 3, 3, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(162, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(162, 5, 13, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(162, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(163, 1, 400, 0, 0.275, 0, 35, 1455, 0, 50925, 'kg', 0, 0),
(163, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(163, 3, 2, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0);
INSERT INTO `prg_bareme` (`ID_PROGR`, `ID_BAREME`, `PRG_QTE1`, `PRG_QTE2`, `PRG_RATION1`, `PRG_RATION2`, `PRG_PRIX`, `NBRE_PLAT1`, `NBRE_PLAT2`, `PRG_REVERSEMENT`, `PRG_UNITE`, `PRG_NBREJ`, `PRG_RATION21J`) VALUES
(163, 4, 100, 0, 0.25, 0, 35, 400, 0, 14000, 'kg', 0, 0),
(163, 5, 9, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(163, 6, 1, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(164, 1, 250, 0, 0.275, 0, 35, 909, 0, 31815, 'kg', 0, 0),
(164, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(164, 3, 1, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(164, 4, 50, 0, 0.25, 0, 35, 200, 0, 7000, 'kg', 0, 0),
(164, 5, 6, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(164, 6, 1, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(165, 1, 400, 0, 0.275, 0, 35, 1455, 0, 50925, 'kg', 0, 0),
(165, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(165, 3, 2, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(165, 4, 100, 0, 0.25, 0, 35, 400, 0, 14000, 'kg', 0, 0),
(165, 5, 9, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(165, 6, 1, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(166, 1, 400, 0, 0.275, 0, 35, 1455, 0, 50925, 'kg', 0, 0),
(166, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(166, 3, 2, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(166, 4, 100, 0, 0.25, 0, 35, 400, 0, 14000, 'kg', 0, 0),
(166, 5, 8, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(166, 6, 1, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(170, 1, 400, 0, 0.275, 0, 35, 1455, 0, 50925, 'kg', 0, 0),
(170, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(170, 3, 2, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(170, 4, 100, 0, 0.25, 0, 35, 400, 0, 14000, 'kg', 0, 0),
(170, 5, 9, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(170, 6, 1, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(171, 1, 400, 0, 0.275, 0, 35, 1455, 0, 50925, 'kg', 0, 0),
(171, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(171, 3, 2, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(171, 4, 100, 0, 0.25, 0, 35, 400, 0, 14000, 'kg', 0, 0),
(171, 5, 9, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(171, 6, 1, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(172, 1, 400, 0, 0.275, 0, 35, 1455, 0, 50925, 'kg', 0, 0),
(172, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(172, 3, 1, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(172, 4, 100, 0, 0.25, 0, 35, 400, 0, 14000, 'kg', 0, 0),
(172, 5, 6, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(172, 6, 1, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(173, 1, 250, 0, 0.275, 0, 35, 909, 0, 31815, 'kg', 0, 0),
(173, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(173, 3, 1, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(173, 4, 50, 0, 0.25, 0, 35, 200, 0, 7000, 'kg', 0, 0),
(173, 5, 6, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(173, 6, 1, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(174, 1, 400, 0, 0.275, 0, 35, 1455, 0, 50925, 'kg', 0, 0),
(174, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(174, 3, 2, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(174, 4, 100, 0, 0.25, 0, 35, 400, 0, 14000, 'kg', 0, 0),
(174, 5, 9, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(174, 6, 1, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(175, 1, 400, 0, 0.275, 0, 35, 1455, 0, 50925, 'kg', 0, 0),
(175, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(175, 3, 2, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(175, 4, 100, 0, 0.25, 0, 35, 400, 0, 14000, 'kg', 0, 0),
(175, 5, 9, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(175, 6, 1, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(176, 1, 1300, 0, 0.275, 0, 35, 4727, 0, 165445, 'kg', 0, 0),
(176, 2, 400, 0, 0.275, 0, 35, 1455, 0, 50925, 'kg', 0, 0),
(176, 3, 6, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(176, 4, 300, 0, 0.25, 0, 35, 1200, 0, 42000, 'kg', 0, 0),
(176, 5, 30, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(176, 6, 4, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(177, 1, 800, 0, 0.275, 0, 35, 2909, 0, 101815, 'kg', 0, 0),
(177, 2, 200, 0, 0.275, 0, 35, 727, 0, 25445, 'kg', 0, 0),
(177, 3, 4, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(177, 4, 200, 0, 0.25, 0, 35, 800, 0, 28000, 'kg', 0, 0),
(177, 5, 18, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(177, 6, 2, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(178, 1, 250, 0, 0.275, 0, 35, 909, 0, 31815, 'kg', 0, 0),
(178, 2, 100, 0, 0.275, 0, 35, 364, 0, 12740, 'kg', 0, 0),
(178, 3, 1, 0, 0, 0, 0, 0, 0, 0, 'bid', 0, 0),
(178, 4, 50, 0, 0.25, 0, 35, 200, 0, 7000, 'kg', 0, 0),
(178, 5, 6, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0),
(178, 6, 1, 0, 0, 0, 0, 0, 0, 0, 'cart', 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE IF NOT EXISTS `produit` (
  `CODE_PRODUIT` varchar(10) NOT NULL,
  `ID_UNITE` varchar(10) NOT NULL,
  `CODE_CATEGORIE` varchar(10) NOT NULL,
  `PRD_LIBELLE` text NOT NULL,
  `CONDITIONNE` tinyint(4) DEFAULT NULL,
  `PRD_PRIX` float DEFAULT NULL,
  `PRD_BAREME` float DEFAULT NULL,
  `PRIX_PLAT` float DEFAULT NULL,
  PRIMARY KEY (`CODE_PRODUIT`),
  KEY `CAT_PRD_FK` (`CODE_CATEGORIE`),
  KEY `UT_PRD_FK` (`ID_UNITE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `produit`
--

INSERT INTO `produit` (`CODE_PRODUIT`, `ID_UNITE`, `CODE_CATEGORIE`, `PRD_LIBELLE`, `CONDITIONNE`, `PRD_PRIX`, `PRD_BAREME`, `PRIX_PLAT`) VALUES
('PA-01', 'sac', 'PA', 'Riz', 1, NULL, NULL, NULL),
('PA-011', 'sac', 'PA', 'Couscous arabe', 1, NULL, NULL, NULL),
('PA-02', 'sac', 'PA', 'Haricot', 1, NULL, NULL, NULL),
('PA-03', 'cart', 'PA', 'PÃ¢tes alimentaires', 1, NULL, NULL, NULL),
('PA-04', 'bid', 'PA', 'Huile', 1, NULL, NULL, NULL),
('PA-05', 'cart', 'PA', 'Sardine', 1, NULL, NULL, NULL),
('PA-06', 'sht', 'PA', 'CafÃ©', 1, NULL, NULL, NULL),
('PA-07', 'cart', 'PA', 'Tomate', 1, NULL, NULL, NULL),
('PA-08', 'bt', 'PA', 'Lait', 1, NULL, NULL, NULL),
('PA-09', 'cart', 'PA', 'Sucre', 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `profil`
--

CREATE TABLE IF NOT EXISTS `profil` (
  `IDPROFIL` int(11) NOT NULL AUTO_INCREMENT,
  `LIBPROFIL` varchar(50) DEFAULT NULL,
  `DCPROF` datetime DEFAULT NULL,
  `DMPROF` datetime DEFAULT NULL,
  PRIMARY KEY (`IDPROFIL`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `profil`
--

INSERT INTO `profil` (`IDPROFIL`, `LIBPROFIL`, `DCPROF`, `DMPROF`) VALUES
(1, 'DÃ©veloppeur', '2012-08-01 00:00:00', '2012-08-10 00:00:00'),
(2, 'Gestionnaire du magasin', '2012-08-03 00:00:00', '2012-08-16 00:00:00'),
(5, 'Administrateur', '2012-08-14 00:00:00', '2012-08-14 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `profil_menu`
--

CREATE TABLE IF NOT EXISTS `profil_menu` (
  `IDPROFIL` int(11) NOT NULL,
  `IDMENU` varchar(10) NOT NULL,
  `VISIBLE` tinyint(4) DEFAULT NULL,
  `AJOUT` tinyint(4) DEFAULT NULL,
  `MODIF` tinyint(4) DEFAULT NULL,
  `SUPPR` tinyint(4) DEFAULT NULL,
  `ANNUL` tinyint(4) DEFAULT NULL,
  `VALID` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`IDPROFIL`,`IDMENU`),
  KEY `PROFIL_MENU_FK` (`IDPROFIL`),
  KEY `PROFIL_MENU2_FK` (`IDMENU`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `profil_menu`
--

INSERT INTO `profil_menu` (`IDPROFIL`, `IDMENU`, `VISIBLE`, `AJOUT`, `MODIF`, `SUPPR`, `ANNUL`, `VALID`) VALUES
(1, 'aid', 1, 1, 1, 1, 1, 1),
(1, 'cde', 1, 1, 1, 1, 1, 1),
(1, 'cde_ali', 1, 1, 1, 1, 1, 1),
(1, 'cde_cde', 1, 1, 1, 1, 1, 1),
(1, 'cde_liv', 1, 1, 1, 1, 1, 1),
(1, 'int', 1, 1, 1, 1, 1, 1),
(1, 'int_int', 1, 1, 1, 1, 1, 1),
(1, 'int_jou', 1, 1, 1, 1, 1, 1),
(1, 'int_sto', 1, 1, 1, 1, 1, 1),
(1, 'mvt', 1, 1, 1, 1, 1, 1),
(1, 'mvt_bac', 1, 1, 1, 1, 1, 1),
(1, 'mvt_dec', 1, 1, 1, 1, 1, 1),
(1, 'mvt_dot', 1, 1, 1, 1, 1, 1),
(1, 'mvt_rep', 1, 1, 1, 1, 1, 1),
(1, 'mvt_trf', 1, 1, 1, 1, 1, 1),
(1, 'par', 1, 1, 1, 1, 1, 1),
(1, 'par_aff', 1, 1, 1, 1, 1, 1),
(1, 'par_bac', 1, 1, 1, 1, 1, 1),
(1, 'par_bar', 1, 1, 1, 1, 1, 1),
(1, 'par_ben', 1, 1, 1, 1, 1, 1),
(1, 'par_bud', 1, 1, 1, 1, 1, 1),
(1, 'par_cat', 1, 1, 1, 1, 1, 1),
(1, 'par_cdt', 1, 1, 1, 1, 1, 1),
(1, 'par_con', 1, 1, 1, 1, 1, 1),
(1, 'par_ctr', 1, 1, 1, 1, 1, 1),
(1, 'par_dot', 1, 1, 1, 1, 1, 1),
(1, 'par_exe', 1, 1, 1, 1, 1, 1),
(1, 'par_fou', 1, 1, 1, 1, 1, 1),
(1, 'par_gen', 1, 1, 1, 1, 1, 1),
(1, 'par_grp', 1, 1, 1, 1, 1, 1),
(1, 'par_inf', 1, 1, 1, 1, 1, 1),
(1, 'par_log', 1, 1, 1, 1, 1, 1),
(1, 'par_mag', 1, 1, 1, 1, 1, 1),
(1, 'par_men', 1, 1, 1, 1, 1, 1),
(1, 'par_per', 1, 1, 1, 1, 1, 1),
(1, 'par_prd', 1, 1, 1, 1, 1, 1),
(1, 'par_prv', 1, 1, 1, 1, 1, 1),
(1, 'par_reg', 1, 1, 1, 1, 1, 1),
(1, 'par_sau', 1, 1, 1, 1, 1, 1),
(1, 'par_ser', 1, 1, 1, 1, 1, 1),
(1, 'par_tbe', 1, 1, 1, 1, 1, 1),
(1, 'par_tse', 1, 1, 1, 1, 1, 1),
(1, 'par_uni', 1, 1, 1, 1, 1, 1),
(1, 'par_uti', 1, 1, 1, 1, 1, 1),
(1, 'prg', 1, 1, 1, 1, 1, 1),
(1, 'prg_bac', 1, 1, 1, 1, 1, 1),
(1, 'prg_prg', 1, 1, 1, 1, 1, 1),
(1, 'prg_rvs', 1, 1, 1, 1, 1, 1),
(2, 'aid', 1, 1, 1, 1, 1, 1),
(2, 'cde', 1, 1, 1, 1, 1, 1),
(2, 'cde_ali', 1, 1, 1, 1, 1, 1),
(2, 'cde_cde', 1, 1, 1, 1, 1, 1),
(2, 'cde_liv', 1, 1, 1, 1, 1, 1),
(2, 'int', 1, 1, 1, 1, 1, 1),
(2, 'mvt', 1, 1, 1, 1, 1, 1),
(2, 'mvt_dec', 1, 1, 1, 1, 1, 1),
(2, 'mvt_dot', 1, 1, 1, 1, 1, 1),
(2, 'mvt_rep', 1, 1, 1, 1, 1, 1),
(2, 'mvt_trf', 0, 0, 0, 0, 0, 0),
(2, 'par', 1, 1, 1, 1, 1, 1),
(2, 'par_aff', 1, 1, 1, 1, 1, 1),
(2, 'par_bac', 1, 1, 1, 1, 1, 1),
(2, 'par_bar', 1, 1, 1, 1, 1, 1),
(2, 'par_ben', 1, 1, 1, 1, 1, 1),
(2, 'par_cat', 1, 1, 1, 1, 1, 1),
(2, 'par_cdt', 1, 1, 1, 1, 1, 1),
(2, 'par_con', 1, 1, 1, 1, 1, 1),
(2, 'par_dot', 1, 1, 1, 1, 1, 1),
(2, 'par_exe', 1, 1, 1, 1, 1, 1),
(2, 'par_fou', 1, 1, 1, 1, 1, 1),
(2, 'par_gen', 1, 1, 1, 1, 1, 1),
(2, 'par_grp', 1, 1, 1, 1, 1, 1),
(2, 'par_inf', 1, 1, 1, 1, 1, 1),
(2, 'par_log', 1, 1, 1, 1, 1, 1),
(2, 'par_mag', 1, 1, 1, 1, 1, 1),
(2, 'par_men', 1, 1, 1, 1, 1, 1),
(2, 'par_per', 1, 1, 1, 1, 1, 1),
(2, 'par_prd', 1, 1, 1, 1, 1, 1),
(2, 'par_prv', 1, 1, 1, 1, 1, 1),
(2, 'par_reg', 1, 1, 1, 1, 1, 1),
(2, 'par_sau', 1, 1, 1, 1, 1, 1),
(2, 'par_ser', 1, 1, 1, 1, 1, 1),
(2, 'par_tbe', 1, 1, 1, 1, 1, 1),
(2, 'par_tse', 1, 1, 1, 1, 1, 1),
(2, 'par_uni', 1, 1, 1, 1, 1, 1),
(2, 'par_uti', 1, 1, 1, 1, 1, 1),
(2, 'prg', 1, 1, 1, 1, 1, 1),
(2, 'prg_prg', 1, 1, 1, 1, 1, 1),
(2, 'prg_rvs', 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `programmation`
--

CREATE TABLE IF NOT EXISTS `programmation` (
  `ID_PROGR` int(11) NOT NULL AUTO_INCREMENT,
  `CODE_MAGASIN` varchar(10) NOT NULL,
  `ID_BENEF` int(11) NOT NULL,
  `ID_EXERCICE` int(11) NOT NULL,
  `CODE_NDOTATION` varchar(10) NOT NULL,
  `NDOTATION` varchar(10) DEFAULT NULL,
  `PGR_DATE` date DEFAULT NULL,
  `PRG_VALID` tinyint(4) DEFAULT NULL,
  `PRG_DATEVALID` datetime DEFAULT NULL,
  `PRG_EFFECTIF` int(11) NOT NULL,
  PRIMARY KEY (`ID_PROGR`),
  KEY `EX_PRG_FK` (`ID_EXERCICE`),
  KEY `NDOT_PRG_FK` (`CODE_NDOTATION`),
  KEY `PRG_BENEF_FK` (`ID_BENEF`),
  KEY `MAGPROG_FK` (`CODE_MAGASIN`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=179 ;

--
-- Contenu de la table `programmation`
--

INSERT INTO `programmation` (`ID_PROGR`, `CODE_MAGASIN`, `ID_BENEF`, `ID_EXERCICE`, `CODE_NDOTATION`, `NDOTATION`, `PGR_DATE`, `PRG_VALID`, `PRG_DATEVALID`, `PRG_EFFECTIF`) VALUES
(1, 'MAG0', 49, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(2, 'MAG0', 50, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(3, 'MAG0', 51, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(4, 'MAG0', 52, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(5, 'MAG0', 53, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(6, 'MAG0', 54, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(7, 'MAG0', 55, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(8, 'MAG0', 62, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(9, 'MAG0', 57, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(10, 'MAG0', 56, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(11, 'MAG0', 59, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(12, 'MAG0', 60, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(13, 'MAG0', 61, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(14, 'MAG0', 58, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(15, 'MAG0', 63, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(16, 'MAG0', 64, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(17, 'MAG0', 65, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(18, 'MAG0', 66, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(19, 'MAG0', 68, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(20, 'MAG0', 69, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(21, 'MAG0', 70, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(22, 'MAG0', 71, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(23, 'MAG0', 72, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(24, 'MAG0', 73, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(25, 'MAG0', 74, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(26, 'MAG0', 21, 2012, '1DOT', NULL, '2012-09-25', 1, '2012-09-25 00:00:00', 0),
(27, 'MAG0', 22, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(28, 'MAG0', 23, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(29, 'MAG0', 24, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(30, 'MAG0', 25, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(31, 'MAG0', 26, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(32, 'MAG0', 27, 2012, '1DOT', NULL, '2012-09-27', 1, '2012-09-27 00:00:00', 0),
(33, 'MAG0', 28, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(34, 'MAG0', 29, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(35, 'MAG0', 30, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(36, 'MAG0', 31, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(37, 'MAG0', 32, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(38, 'MAG0', 33, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(39, 'MAG0', 34, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(40, 'MAG0', 35, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(41, 'MAG0', 36, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(42, 'MAG0', 37, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(43, 'MAG0', 38, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(44, 'MAG0', 39, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(45, 'MAG0', 40, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(46, 'MAG0', 41, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(47, 'MAG0', 42, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(48, 'MAG0', 43, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(49, 'MAG0', 44, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(50, 'MAG0', 45, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(51, 'MAG0', 46, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(52, 'MAG0', 47, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(53, 'MAG0', 567, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(54, 'MAG0', 48, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(55, 'MAG0', 629, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(56, 'MAG0', 565, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(57, 'MAG0', 566, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(58, 'MAG0', 75, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(59, 'MAG0', 76, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(60, 'MAG0', 77, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(61, 'MAG0', 78, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(62, 'MAG0', 649, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(63, 'MAG0', 616, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(64, 'MAG0', 617, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(65, 'MAG0', 652, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(66, 'MAG0', 650, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(67, 'MAG0', 615, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(68, 'MAG0', 638, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(69, 'MAG0', 648, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(70, 'MAG0', 79, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(71, 'MAG0', 80, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(72, 'MAG0', 81, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(73, 'MAG0', 82, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(74, 'MAG0', 83, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(75, 'MAG0', 599, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(76, 'MAG0', 84, 2012, '1DOT', NULL, '2012-09-20', 0, '2012-09-20 00:00:00', 0),
(77, 'MAG0', 67, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(78, 'MAG0', 86, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(79, 'MAG0', 87, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(80, 'MAG0', 88, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(81, 'MAG0', 89, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(82, 'MAG0', 610, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(83, 'MAG0', 91, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(84, 'MAG0', 603, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(85, 'MAG0', 94, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(86, 'MAG0', 95, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(87, 'MAG0', 96, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(88, 'MAG0', 97, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(89, 'MAG0', 98, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(90, 'MAG0', 604, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(91, 'MAG0', 100, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(92, 'MAG0', 101, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(93, 'MAG0', 102, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(94, 'MAG0', 613, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(95, 'MAG0', 600, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(96, 'MAG0', 680, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(97, 'MAG0', 106, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(98, 'MAG0', 107, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(99, 'MAG0', 108, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(100, 'MAG0', 109, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(101, 'MAG0', 110, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(102, 'MAG0', 111, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(103, 'MAG0', 112, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(104, 'MAG0', 113, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(105, 'MAG0', 114, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(106, 'MAG0', 115, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(107, 'MAG0', 116, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(108, 'MAG0', 117, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(109, 'MAG0', 612, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(110, 'MAG0', 119, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(111, 'MAG0', 121, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(112, 'MAG0', 122, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(113, 'MAG0', 123, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(114, 'MAG0', 124, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(115, 'MAG0', 125, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(116, 'MAG0', 126, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(117, 'MAG0', 127, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(118, 'MAG0', 128, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(119, 'MAG0', 129, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(120, 'MAG0', 130, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(121, 'MAG0', 131, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(122, 'MAG0', 132, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(123, 'MAG0', 607, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(124, 'MAG0', 681, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(125, 'MAG0', 606, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(126, 'MAG0', 614, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(127, 'MAG0', 602, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(128, 'MAG0', 605, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(129, 'MAG0', 133, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(130, 'MAG0', 691, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(131, 'MAG0', 693, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(132, 'MAG0', 690, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(133, 'MAG0', 137, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(134, 'MAG0', 138, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(135, 'MAG0', 139, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(136, 'MAG0', 591, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(137, 'MAG0', 692, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(138, 'MAG0', 587, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(139, 'MAG0', 585, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(140, 'MAG0', 144, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(141, 'MAG0', 145, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(142, 'MAG0', 146, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(143, 'MAG0', 147, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(144, 'MAG0', 148, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(145, 'MAG0', 149, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(146, 'MAG0', 150, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(147, 'MAG0', 151, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(148, 'MAG0', 152, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(149, 'MAG0', 153, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(150, 'MAG0', 154, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(151, 'MAG0', 155, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(152, 'MAG0', 156, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(153, 'MAG0', 157, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(154, 'MAG0', 158, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(155, 'MAG0', 159, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(156, 'MAG0', 590, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(157, 'MAG0', 588, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(158, 'MAG0', 642, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(159, 'MAG0', 688, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(160, 'MAG0', 687, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(161, 'MAG0', 686, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(162, 'MAG0', 689, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(163, 'MAG0', 568, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(164, 'MAG0', 571, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(165, 'MAG0', 570, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(166, 'MAG0', 569, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(170, 'MAG0', 679, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(171, 'MAG0', 644, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(172, 'MAG0', 589, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(173, 'MAG0', 643, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(174, 'MAG0', 637, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(175, 'MAG0', 651, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(176, 'MAG0', 608, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(177, 'MAG0', 611, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0),
(178, 'MAG0', 586, 2012, '1DOT', NULL, '2012-09-21', 0, '2012-09-21 00:00:00', 0);

-- --------------------------------------------------------

--
-- Structure de la table `province`
--

CREATE TABLE IF NOT EXISTS `province` (
  `IDPROVINCE` int(11) NOT NULL AUTO_INCREMENT,
  `IDREGION` int(11) NOT NULL,
  `PROVINCE` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`IDPROVINCE`),
  KEY `PROV_REGION_FK` (`IDREGION`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=46 ;

--
-- Contenu de la table `province`
--

INSERT INTO `province` (`IDPROVINCE`, `IDREGION`, `PROVINCE`) VALUES
(1, 2, 'BalÃ©'),
(2, 2, 'Banwa'),
(3, 2, 'Kossi'),
(4, 2, 'Mouhoun'),
(5, 2, 'Nayala'),
(6, 2, 'Sourou'),
(7, 3, 'ComoÃ©'),
(8, 3, 'LÃ©raba'),
(9, 4, 'Kadiogo'),
(10, 5, 'Boulgou'),
(11, 5, 'KoulpÃ©logo'),
(12, 5, 'Kouritenga'),
(13, 6, 'Bam'),
(14, 6, 'Namentenga'),
(15, 6, 'Sanmatenga'),
(16, 7, 'BoulkiemdÃ©'),
(17, 7, 'SanguiÃ©'),
(18, 7, 'Sissili'),
(19, 7, 'Ziro'),
(20, 8, 'BazÃ©ga'),
(21, 8, 'Nahouri'),
(22, 8, 'ZoundwÃ©ogo'),
(23, 9, 'Gnagna'),
(24, 9, 'Gourma'),
(25, 9, 'Komondjari'),
(26, 9, 'Kompienga'),
(27, 9, 'Tapoa'),
(28, 1, 'Houet'),
(29, 1, 'KÃ©nÃ©dougou'),
(30, 1, 'Tuy'),
(31, 10, 'Loroum'),
(32, 10, 'PassorÃ©'),
(33, 10, 'Yatenga'),
(34, 10, 'Zondoma'),
(35, 11, 'Ganzourgou'),
(36, 11, 'KourwÃ©ogo'),
(37, 11, 'Oubritenga'),
(38, 12, 'Oudalan'),
(39, 12, 'SÃ©no'),
(40, 12, 'Soum'),
(41, 12, 'Yagha'),
(42, 13, 'Bougouriba'),
(43, 13, 'Ioba'),
(44, 13, 'Noumbiel'),
(45, 13, 'Poni');

-- --------------------------------------------------------

--
-- Structure de la table `recondit`
--

CREATE TABLE IF NOT EXISTS `recondit` (
  `ID_RECONDIT` int(11) NOT NULL AUTO_INCREMENT,
  `CODE_MAGASIN` varchar(10) NOT NULL,
  `ID_EXERCICE` int(11) NOT NULL,
  `CODE_RECOND` varchar(250) DEFAULT NULL,
  `REC_CAUSE` varchar(50) DEFAULT NULL,
  `REC_DATESORTIE` date DEFAULT NULL,
  `REC_DATERETOUR` date DEFAULT NULL,
  `REC_DATEVALID` datetime DEFAULT NULL,
  `REC_RAISON` text,
  `REC_CONTROLEUR` varchar(100) DEFAULT NULL,
  `REC_LIBELLE` text,
  `REC_VALIDE` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`ID_RECONDIT`),
  KEY `EX_RECOND_FK` (`ID_EXERCICE`),
  KEY `REC_MAG_FK` (`CODE_MAGASIN`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `recond_cnd`
--

CREATE TABLE IF NOT EXISTS `recond_cnd` (
  `ID_CONDIT` int(11) NOT NULL,
  `ID_RECONDIT` int(11) NOT NULL,
  `CNDREC_QTES` float DEFAULT NULL,
  `CNDREC_UNITES` varchar(10) DEFAULT NULL,
  `CNDREC_QTEE` float DEFAULT NULL,
  `CNDREC_UNITEE` varchar(10) DEFAULT NULL,
  `CNDREC_TYPEEMB` varchar(50) DEFAULT NULL,
  `CNDREC_COLISSAGE` varchar(50) DEFAULT NULL,
  `CNDREC_CAUSE` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`ID_CONDIT`,`ID_RECONDIT`),
  KEY `RECOND_CND_FK` (`ID_CONDIT`),
  KEY `RECOND_CND2_FK` (`ID_RECONDIT`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `region`
--

CREATE TABLE IF NOT EXISTS `region` (
  `IDREGION` int(11) NOT NULL AUTO_INCREMENT,
  `REGION` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`IDREGION`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Contenu de la table `region`
--

INSERT INTO `region` (`IDREGION`, `REGION`) VALUES
(1, 'Hauts-Bassins'),
(2, 'Boucle du Mouhoun'),
(3, 'Cascades'),
(4, 'Centre'),
(5, 'Centre-Est'),
(6, 'Centre-Nord'),
(7, 'Centre-Ouest'),
(8, 'Centre-Sud'),
(9, 'Est'),
(10, 'Nord'),
(11, 'Plateau-Central'),
(12, 'Sahel'),
(13, 'Sud-Ouest');

-- --------------------------------------------------------

--
-- Structure de la table `report`
--

CREATE TABLE IF NOT EXISTS `report` (
  `ID_REPORT` int(11) NOT NULL AUTO_INCREMENT,
  `ID_EXERCICE` int(11) NOT NULL,
  `CODE_MAGASIN` varchar(10) NOT NULL,
  `REP_NATURE` varchar(30) DEFAULT NULL,
  `REP_DATE` date DEFAULT NULL,
  `REP_VALIDE` tinyint(4) DEFAULT NULL,
  `REP_DATEVALID` datetime DEFAULT NULL,
  PRIMARY KEY (`ID_REPORT`),
  KEY `EX_REPORT_FK` (`ID_EXERCICE`),
  KEY `MAGREP_FK` (`CODE_MAGASIN`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `report_cnd`
--

CREATE TABLE IF NOT EXISTS `report_cnd` (
  `ID_CONDIT` int(11) NOT NULL,
  `ID_REPORT` int(11) NOT NULL,
  `REPCND_QTE` float DEFAULT NULL,
  `REP_UNITE` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`ID_CONDIT`,`ID_REPORT`),
  KEY `REPORT_CND_FK` (`ID_CONDIT`),
  KEY `REPORT_CND2_FK` (`ID_REPORT`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `respmagasin`
--

CREATE TABLE IF NOT EXISTS `respmagasin` (
  `ID_RESPO` int(11) NOT NULL AUTO_INCREMENT,
  `NUM_MLLE` varchar(10) NOT NULL,
  `CODE_MAGASIN` varchar(10) NOT NULL,
  `RES_DATEDEBUT` date DEFAULT NULL,
  `RES_DATEFIN` date DEFAULT NULL,
  PRIMARY KEY (`ID_RESPO`),
  KEY `PERS_RESP_FK` (`NUM_MLLE`),
  KEY `RESP_MAG_FK` (`CODE_MAGASIN`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `reversement`
--

CREATE TABLE IF NOT EXISTS `reversement` (
  `ID_REVERSEMENT` int(11) NOT NULL AUTO_INCREMENT,
  `ID_PROGR` int(11) NOT NULL,
  `ID_EXERCICE` int(11) NOT NULL,
  `REV_DATE` date DEFAULT NULL,
  `REV_QUITTANCE` varchar(100) DEFAULT NULL,
  `REV_VALID` tinyint(4) DEFAULT NULL,
  `REV_DATEVALID` datetime DEFAULT NULL,
  `REV_MNTTOTAL` float DEFAULT NULL,
  `REV_MNTVERSE` float DEFAULT NULL,
  PRIMARY KEY (`ID_REVERSEMENT`),
  KEY `REV_PROG_FK` (`ID_PROGR`),
  KEY `EX_REV_FK` (`ID_EXERCICE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `service`
--

CREATE TABLE IF NOT EXISTS `service` (
  `ID_SERVICE` int(11) NOT NULL AUTO_INCREMENT,
  `ID_GRPSERVICE` varchar(10) NOT NULL,
  `SER_NOM` varchar(50) NOT NULL,
  `SER_RESP` varchar(100) DEFAULT NULL,
  `SER_EMAIL` varchar(100) DEFAULT NULL,
  `SER_TEL` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`ID_SERVICE`),
  KEY `GRPSER_SER_FK` (`ID_GRPSERVICE`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Contenu de la table `service`
--

INSERT INTO `service` (`ID_SERVICE`, `ID_GRPSERVICE`, `SER_NOM`, `SER_RESP`, `SER_EMAIL`, `SER_TEL`) VALUES
(1, 'CA', 'Cantine Ouagadougou', 'OUEDRAOGO Kibsa', '', ''),
(2, 'CA', 'Cantine Bobo-Dioulasso', 'Bamba', '', ''),
(10, 'CA', 'Cantine de Fada N''Gourma', 'Kam Beh Jacob', 'kam@hotmail.com', '+226 787676'),
(11, 'CA', 'service cantine de Gaoua', 'DA ZEOGNE', '', ''),
(12, 'CA', 'cantine DÃ©dougou', '', '', ''),
(13, 'CA', 'Cantine de Ouahigouya', '', '', ''),
(14, 'CA', 'Cantine de Kaya', '', '', '');

-- --------------------------------------------------------

--
-- Structure de la table `transfert`
--

CREATE TABLE IF NOT EXISTS `transfert` (
  `ID_TRANSFERT` int(11) NOT NULL AUTO_INCREMENT,
  `CODE_MAGASIN` varchar(10) NOT NULL,
  `MAG_CODE_MAGASIN` varchar(10) NOT NULL,
  `ID_EXERCICE` int(11) NOT NULL,
  `CODE_TRANSFERT` varchar(250) DEFAULT NULL,
  `TRS_DATE` date DEFAULT NULL,
  `TRS_NATURE` tinyint(4) DEFAULT NULL,
  `TRS_RAISON` text,
  `TRS_VALIDE` tinyint(4) DEFAULT NULL,
  `TRS_DATEVALID` datetime DEFAULT NULL,
  `TRS_CAMION` varchar(100) DEFAULT NULL,
  `TRS_LIBELLE` text,
  `MAG_NP` varchar(100) DEFAULT NULL,
  `MAG_CIB` varchar(100) DEFAULT NULL,
  `MAG_DATE` date DEFAULT NULL,
  `PRE_NP` varchar(100) DEFAULT NULL,
  `PRE_CIB` varchar(100) DEFAULT NULL,
  `PRE_DATE` date DEFAULT NULL,
  PRIMARY KEY (`ID_TRANSFERT`),
  KEY `EX_TRS_FK` (`ID_EXERCICE`),
  KEY `TRS_SOURCE_FK` (`MAG_CODE_MAGASIN`),
  KEY `TRS_DEST_FK` (`CODE_MAGASIN`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `transfert`
--

INSERT INTO `transfert` (`ID_TRANSFERT`, `CODE_MAGASIN`, `MAG_CODE_MAGASIN`, `ID_EXERCICE`, `CODE_TRANSFERT`, `TRS_DATE`, `TRS_NATURE`, `TRS_RAISON`, `TRS_VALIDE`, `TRS_DATEVALID`, `TRS_CAMION`, `TRS_LIBELLE`, `MAG_NP`, `MAG_CIB`, `MAG_DATE`, `PRE_NP`, `PRE_CIB`, `PRE_DATE`) VALUES
(1, 'MAG0', 'MAG1', 2012, '', '2012-09-25', 1, NULL, 0, '0000-00-00 00:00:00', '11', 'N', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `trs_cnd`
--

CREATE TABLE IF NOT EXISTS `trs_cnd` (
  `ID_CONDIT` int(11) NOT NULL,
  `ID_TRANSFERT` int(11) NOT NULL,
  `TRSCND_QTE` float DEFAULT NULL,
  `TRSCND_RECU` tinyint(4) DEFAULT NULL,
  `TRS_UNITE` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`ID_CONDIT`,`ID_TRANSFERT`),
  KEY `TRS_CND_FK` (`ID_CONDIT`),
  KEY `TRS_CND2_FK` (`ID_TRANSFERT`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `trs_cnd`
--

INSERT INTO `trs_cnd` (`ID_CONDIT`, `ID_TRANSFERT`, `TRSCND_QTE`, `TRSCND_RECU`, `TRS_UNITE`) VALUES
(2, 1, 50, 50, 'sac'),
(9, 1, 20, 20, 'bid');

-- --------------------------------------------------------

--
-- Structure de la table `unite`
--

CREATE TABLE IF NOT EXISTS `unite` (
  `ID_UNITE` varchar(10) NOT NULL,
  `UT_LIBELLE` varchar(50) NOT NULL,
  PRIMARY KEY (`ID_UNITE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `unite`
--

INSERT INTO `unite` (`ID_UNITE`, `UT_LIBELLE`) VALUES
('bid', 'bidons'),
('bt', 'boÃ®tes'),
('cart', 'cartons'),
('g', 'grammes'),
('kg', 'kilogrammes'),
('l', 'litre'),
('sac', 'sacs'),
('sht', 'sachet');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `autrelivr`
--
ALTER TABLE `autrelivr`
  ADD CONSTRAINT `FK_EX_AUTRELIVR` FOREIGN KEY (`ID_EXERCICE`) REFERENCES `exercice` (`ID_EXERCICE`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_MAGREC` FOREIGN KEY (`CODE_MAGASIN`) REFERENCES `magasin` (`CODE_MAGASIN`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `bareme`
--
ALTER TABLE `bareme`
  ADD CONSTRAINT `FK_CND_BAR` FOREIGN KEY (`CODE_PRODUIT`) REFERENCES `produit` (`CODE_PRODUIT`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_UNT_BAR` FOREIGN KEY (`ID_UNITE`) REFERENCES `unite` (`ID_UNITE`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `beneficiaire`
--
ALTER TABLE `beneficiaire`
  ADD CONSTRAINT `FK_BENEF_NBENEF` FOREIGN KEY (`CODE_NOMBENF`) REFERENCES `nombeneficiaire` (`CODE_NOMBENF`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_BEN_PROV` FOREIGN KEY (`IDPROVINCE`) REFERENCES `province` (`IDPROVINCE`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `benefmag`
--
ALTER TABLE `benefmag`
  ADD CONSTRAINT `FK_BENEF_MAG` FOREIGN KEY (`ID_BENEF`) REFERENCES `beneficiaire` (`ID_BENEF`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `cde_prd`
--
ALTER TABLE `cde_prd`
  ADD CONSTRAINT `FK_CDE_PRD` FOREIGN KEY (`ID_COMMANDE`) REFERENCES `commande` (`ID_COMMANDE`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_CDE_PRD2` FOREIGN KEY (`ID_CONDIT`) REFERENCES `conditionmt` (`ID_CONDIT`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `centre`
--
ALTER TABLE `centre`
  ADD CONSTRAINT `FK_EXE_CENTRE` FOREIGN KEY (`ID_EXERCICE`) REFERENCES `exercice` (`ID_EXERCICE`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `centreexam`
--
ALTER TABLE `centreexam`
  ADD CONSTRAINT `FK_CENTREEXAM` FOREIGN KEY (`ID_BENEF`) REFERENCES `beneficiaire` (`ID_BENEF`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_CENTREEXAM2` FOREIGN KEY (`IDCENTRE`) REFERENCES `centre` (`IDCENTRE`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `cnd_autreliv`
--
ALTER TABLE `cnd_autreliv`
  ADD CONSTRAINT `FK_CND_AUTRELIV` FOREIGN KEY (`ID_CONDIT`) REFERENCES `conditionmt` (`ID_CONDIT`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_CND_AUTRELIV2` FOREIGN KEY (`ID_AUTRELIVR`) REFERENCES `autrelivr` (`ID_AUTRELIVR`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `cnd_invt`
--
ALTER TABLE `cnd_invt`
  ADD CONSTRAINT `FK_CND_INVT` FOREIGN KEY (`ID_CONDIT`) REFERENCES `conditionmt` (`ID_CONDIT`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_CND_INVT2` FOREIGN KEY (`ID_INVENTAIRE`) REFERENCES `inventaire` (`ID_INVENTAIRE`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `FK_EX_CDE` FOREIGN KEY (`ID_EXERCICE`) REFERENCES `exercice` (`ID_EXERCICE`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_FOUR_CDE` FOREIGN KEY (`ID_FORNISSEUR`) REFERENCES `fournisseur` (`ID_FORNISSEUR`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_MAGCDE` FOREIGN KEY (`CODE_MAGASIN`) REFERENCES `magasin` (`CODE_MAGASIN`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `compte`
--
ALTER TABLE `compte`
  ADD CONSTRAINT `FK_CPT_PROFIL` FOREIGN KEY (`IDPROFIL`) REFERENCES `profil` (`IDPROFIL`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_PERS_CPTE` FOREIGN KEY (`NUM_MLLE`) REFERENCES `personnel` (`NUM_MLLE`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `conditionmt`
--
ALTER TABLE `conditionmt`
  ADD CONSTRAINT `FK_PRD_CND` FOREIGN KEY (`CODE_PRODUIT`) REFERENCES `produit` (`CODE_PRODUIT`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_UT_CND` FOREIGN KEY (`ID_UNITE`) REFERENCES `unite` (`ID_UNITE`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `conversion`
--
ALTER TABLE `conversion`
  ADD CONSTRAINT `FK_CONVERSION` FOREIGN KEY (`ID_UNITE`) REFERENCES `unite` (`ID_UNITE`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_CONVERSION2` FOREIGN KEY (`UNI_ID_UNITE`) REFERENCES `unite` (`ID_UNITE`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `declass`
--
ALTER TABLE `declass`
  ADD CONSTRAINT `FK_EX_DECLASS` FOREIGN KEY (`ID_EXERCICE`) REFERENCES `exercice` (`ID_EXERCICE`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_MAGDECL` FOREIGN KEY (`CODE_MAGASIN`) REFERENCES `magasin` (`CODE_MAGASIN`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `declass_cnd`
--
ALTER TABLE `declass_cnd`
  ADD CONSTRAINT `FK_DECLASS_CND` FOREIGN KEY (`ID_CONDIT`) REFERENCES `conditionmt` (`ID_CONDIT`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_DECLASS_CND2` FOREIGN KEY (`ID_DECLASS`) REFERENCES `declass` (`ID_DECLASS`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `donnee_annuelle`
--
ALTER TABLE `donnee_annuelle`
  ADD CONSTRAINT `FK_DONNEE_ANNUELLE` FOREIGN KEY (`ID_BENEF`) REFERENCES `beneficiaire` (`ID_BENEF`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_DONNEE_ANNUELLE2` FOREIGN KEY (`CODE_MAGASIN`) REFERENCES `magasin` (`CODE_MAGASIN`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_DONNEE_ANNUELLE3` FOREIGN KEY (`ID_EXERCICE`) REFERENCES `exercice` (`ID_EXERCICE`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `dotation`
--
ALTER TABLE `dotation`
  ADD CONSTRAINT `FK_BENEF_DOT` FOREIGN KEY (`ID_BENEF`) REFERENCES `beneficiaire` (`ID_BENEF`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_DOT_NDOT` FOREIGN KEY (`CODE_NDOTATION`) REFERENCES `nomdotation` (`CODE_NDOTATION`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_DOT_PROG` FOREIGN KEY (`ID_PROGR`) REFERENCES `programmation` (`ID_PROGR`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_EX_DOT` FOREIGN KEY (`ID_EXERCICE`) REFERENCES `exercice` (`ID_EXERCICE`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_MAGDOT` FOREIGN KEY (`CODE_MAGASIN`) REFERENCES `magasin` (`CODE_MAGASIN`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `dot_cnd`
--
ALTER TABLE `dot_cnd`
  ADD CONSTRAINT `FK_DOT_CND` FOREIGN KEY (`ID_CONDIT`) REFERENCES `conditionmt` (`ID_CONDIT`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_DOT_CND2` FOREIGN KEY (`ID_DOTATION`) REFERENCES `dotation` (`ID_DOTATION`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `ex_prd`
--
ALTER TABLE `ex_prd`
  ADD CONSTRAINT `FK_EX_PRD` FOREIGN KEY (`CODE_PRODUIT`) REFERENCES `produit` (`CODE_PRODUIT`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_EX_PRD2` FOREIGN KEY (`ID_EXERCICE`) REFERENCES `exercice` (`ID_EXERCICE`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `inventaire`
--
ALTER TABLE `inventaire`
  ADD CONSTRAINT `FK_EX_INVT` FOREIGN KEY (`ID_EXERCICE`) REFERENCES `exercice` (`ID_EXERCICE`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_MAGINV` FOREIGN KEY (`CODE_MAGASIN`) REFERENCES `magasin` (`CODE_MAGASIN`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `livraison`
--
ALTER TABLE `livraison`
  ADD CONSTRAINT `FK_CDE_LVR` FOREIGN KEY (`ID_COMMANDE`) REFERENCES `commande` (`ID_COMMANDE`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_EXER_LIVR` FOREIGN KEY (`ID_EXERCICE`) REFERENCES `exercice` (`ID_EXERCICE`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_MAGLIVR` FOREIGN KEY (`CODE_MAGASIN`) REFERENCES `magasin` (`CODE_MAGASIN`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `localite`
--
ALTER TABLE `localite`
  ADD CONSTRAINT `FK_GRPLOC_LOC` FOREIGN KEY (`ID_GRPLOC`) REFERENCES `groupelocalite` (`ID_GRPLOC`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_LOC_DECOUPAGE` FOREIGN KEY (`ID_DECOUPAGE`) REFERENCES `decoupageadm` (`ID_DECOUPAGE`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `FK_CPTE_LOG` FOREIGN KEY (`LOGIN`) REFERENCES `compte` (`LOGIN`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `lvr_prd`
--
ALTER TABLE `lvr_prd`
  ADD CONSTRAINT `FK_LVR_PRD` FOREIGN KEY (`ID_LIVRAISON`) REFERENCES `livraison` (`ID_LIVRAISON`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_LVR_PRD2` FOREIGN KEY (`ID_CONDIT`) REFERENCES `conditionmt` (`ID_CONDIT`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `magasin`
--
ALTER TABLE `magasin`
  ADD CONSTRAINT `FK_MAG_PROVINCE` FOREIGN KEY (`IDPROVINCE`) REFERENCES `province` (`IDPROVINCE`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_SER_MAG` FOREIGN KEY (`ID_SERVICE`) REFERENCES `service` (`ID_SERVICE`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `magrever`
--
ALTER TABLE `magrever`
  ADD CONSTRAINT `FK_MAGREVER` FOREIGN KEY (`ID_REVERSEMENT`) REFERENCES `reversement` (`ID_REVERSEMENT`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_MAGREVER2` FOREIGN KEY (`CODE_MAGASIN`) REFERENCES `magasin` (`CODE_MAGASIN`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `mag_compte`
--
ALTER TABLE `mag_compte`
  ADD CONSTRAINT `FK_MAG_COMPTE` FOREIGN KEY (`CODE_MAGASIN`) REFERENCES `magasin` (`CODE_MAGASIN`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_MAG_COMPTE2` FOREIGN KEY (`LOGIN`) REFERENCES `compte` (`LOGIN`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `mouvement`
--
ALTER TABLE `mouvement`
  ADD CONSTRAINT `FK_EX_MVT` FOREIGN KEY (`ID_EXERCICE`) REFERENCES `exercice` (`ID_EXERCICE`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_MVT_CND` FOREIGN KEY (`ID_CONDIT`) REFERENCES `conditionmt` (`ID_CONDIT`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_MVT_MAG` FOREIGN KEY (`CODE_MAGASIN`) REFERENCES `magasin` (`CODE_MAGASIN`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `personnel`
--
ALTER TABLE `personnel`
  ADD CONSTRAINT `FK_PERS_SER` FOREIGN KEY (`ID_SERVICE`) REFERENCES `service` (`ID_SERVICE`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `prg_bareme`
--
ALTER TABLE `prg_bareme`
  ADD CONSTRAINT `FK_PRG_BAREME` FOREIGN KEY (`ID_PROGR`) REFERENCES `programmation` (`ID_PROGR`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_PRG_BAREME2` FOREIGN KEY (`ID_BAREME`) REFERENCES `bareme` (`ID_BAREME`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `FK_CAT_PRD` FOREIGN KEY (`CODE_CATEGORIE`) REFERENCES `categorie` (`CODE_CATEGORIE`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_UT_PRD` FOREIGN KEY (`ID_UNITE`) REFERENCES `unite` (`ID_UNITE`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `profil_menu`
--
ALTER TABLE `profil_menu`
  ADD CONSTRAINT `FK_PROFIL_MENU` FOREIGN KEY (`IDPROFIL`) REFERENCES `profil` (`IDPROFIL`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_PROFIL_MENU2` FOREIGN KEY (`IDMENU`) REFERENCES `menu` (`IDMENU`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `programmation`
--
ALTER TABLE `programmation`
  ADD CONSTRAINT `FK_EX_PRG` FOREIGN KEY (`ID_EXERCICE`) REFERENCES `exercice` (`ID_EXERCICE`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_MAGPROG` FOREIGN KEY (`CODE_MAGASIN`) REFERENCES `magasin` (`CODE_MAGASIN`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_NDOT_PRG` FOREIGN KEY (`CODE_NDOTATION`) REFERENCES `nomdotation` (`CODE_NDOTATION`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_PRG_BENEF` FOREIGN KEY (`ID_BENEF`) REFERENCES `beneficiaire` (`ID_BENEF`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `province`
--
ALTER TABLE `province`
  ADD CONSTRAINT `FK_PROV_REGION` FOREIGN KEY (`IDREGION`) REFERENCES `region` (`IDREGION`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `recondit`
--
ALTER TABLE `recondit`
  ADD CONSTRAINT `FK_EX_RECOND` FOREIGN KEY (`ID_EXERCICE`) REFERENCES `exercice` (`ID_EXERCICE`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_REC_MAG` FOREIGN KEY (`CODE_MAGASIN`) REFERENCES `magasin` (`CODE_MAGASIN`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `recond_cnd`
--
ALTER TABLE `recond_cnd`
  ADD CONSTRAINT `FK_RECOND_CND` FOREIGN KEY (`ID_CONDIT`) REFERENCES `conditionmt` (`ID_CONDIT`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_RECOND_CND2` FOREIGN KEY (`ID_RECONDIT`) REFERENCES `recondit` (`ID_RECONDIT`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `FK_EX_REPORT` FOREIGN KEY (`ID_EXERCICE`) REFERENCES `exercice` (`ID_EXERCICE`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_MAGREP` FOREIGN KEY (`CODE_MAGASIN`) REFERENCES `magasin` (`CODE_MAGASIN`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `report_cnd`
--
ALTER TABLE `report_cnd`
  ADD CONSTRAINT `FK_REPORT_CND` FOREIGN KEY (`ID_CONDIT`) REFERENCES `conditionmt` (`ID_CONDIT`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_REPORT_CND2` FOREIGN KEY (`ID_REPORT`) REFERENCES `report` (`ID_REPORT`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `respmagasin`
--
ALTER TABLE `respmagasin`
  ADD CONSTRAINT `FK_PERS_RESP` FOREIGN KEY (`NUM_MLLE`) REFERENCES `personnel` (`NUM_MLLE`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_RESP_MAG` FOREIGN KEY (`CODE_MAGASIN`) REFERENCES `magasin` (`CODE_MAGASIN`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `reversement`
--
ALTER TABLE `reversement`
  ADD CONSTRAINT `FK_EX_REV` FOREIGN KEY (`ID_EXERCICE`) REFERENCES `exercice` (`ID_EXERCICE`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_REV_PROG` FOREIGN KEY (`ID_PROGR`) REFERENCES `programmation` (`ID_PROGR`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `service`
--
ALTER TABLE `service`
  ADD CONSTRAINT `FK_GRPSER_SER` FOREIGN KEY (`ID_GRPSERVICE`) REFERENCES `groupeservice` (`ID_GRPSERVICE`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `transfert`
--
ALTER TABLE `transfert`
  ADD CONSTRAINT `FK_EX_TRS` FOREIGN KEY (`ID_EXERCICE`) REFERENCES `exercice` (`ID_EXERCICE`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_TRS_DEST` FOREIGN KEY (`CODE_MAGASIN`) REFERENCES `magasin` (`CODE_MAGASIN`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_TRS_SOURCE` FOREIGN KEY (`MAG_CODE_MAGASIN`) REFERENCES `magasin` (`CODE_MAGASIN`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `trs_cnd`
--
ALTER TABLE `trs_cnd`
  ADD CONSTRAINT `FK_TRS_CND` FOREIGN KEY (`ID_CONDIT`) REFERENCES `conditionmt` (`ID_CONDIT`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_TRS_CND2` FOREIGN KEY (`ID_TRANSFERT`) REFERENCES `transfert` (`ID_TRANSFERT`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
