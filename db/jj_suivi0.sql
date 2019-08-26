-- phpMyAdmin SQL Dump
-- version 4.0.6deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mar 18 Février 2014 à 20:35
-- Version du serveur: 5.5.35-0ubuntu0.13.10.2
-- Version de PHP: 5.5.3-1ubuntu2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `jolijeune`
--

-- --------------------------------------------------------

--
-- Structure de la table `jj_participer`
--

CREATE TABLE IF NOT EXISTS `jj_participer` (
  `id_stage` int(10) NOT NULL,
  `id_stagiaire` int(10) NOT NULL,
  `id_stat` int(6) DEFAULT NULL,
  `type_diete` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_stage`,`id_stagiaire`),
  UNIQUE KEY `id_chambre` (`id_stat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `jj_participer`
--

INSERT INTO `jj_participer` (`id_stage`, `id_stagiaire`, `id_stat`, `type_diete`) VALUES
(2, 1, 5, 'Non d&eacute;fini'),
(2, 4, 6, 'Non d&eacute;fini'),
(5, 1, NULL, 'Bananes'),
(7, 1, 11, '&type=md'),
(7, 5, 10, 'Bananes'),
(8, 1, NULL, ''),
(9, 1, NULL, '&type=md'),
(9, 5, NULL, 'Bananes');

-- --------------------------------------------------------

--
-- Structure de la table `jj_stage`
--

CREATE TABLE IF NOT EXISTS `jj_stage` (
  `id_stage` int(10) NOT NULL AUTO_INCREMENT,
  `date_deb` date NOT NULL,
  `date_fin` date NOT NULL,
  `type` enum('j','md','cd') NOT NULL,
  PRIMARY KEY (`id_stage`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Contenu de la table `jj_stage`
--

INSERT INTO `jj_stage` (`id_stage`, `date_deb`, `date_fin`, `type`) VALUES
(3, '2014-05-20', '2014-05-25', 'cd'),
(5, '2013-02-09', '2013-02-14', 'md'),
(6, '2014-05-20', '2014-05-25', 'md'),
(7, '2014-10-15', '2015-10-15', 'md'),
(8, '2014-06-19', '2014-06-23', 'j'),
(9, '2014-06-19', '2014-06-23', 'md');

-- --------------------------------------------------------

--
-- Structure de la table `jj_stagiaire`
--

CREATE TABLE IF NOT EXISTS `jj_stagiaire` (
  `id_stagiaire` int(10) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `sexe` enum('M','F') DEFAULT NULL,
  PRIMARY KEY (`id_stagiaire`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `jj_stagiaire`
--

INSERT INTO `jj_stagiaire` (`id_stagiaire`, `nom`, `prenom`, `sexe`) VALUES
(1, 'Dewaele', 'Nicolas', 'M'),
(3, 'Titi', 'Toto', 'F'),
(4, 'Hsdfjop', 'sodf', 'M'),
(5, 'de curiste', 'test', 'M');

-- --------------------------------------------------------

--
-- Structure de la table `jj_stat`
--

CREATE TABLE IF NOT EXISTS `jj_stat` (
  `id_stat` int(6) NOT NULL AUTO_INCREMENT,
  `age` int(3) DEFAULT NULL,
  `taille` float DEFAULT NULL,
  `pd_avt` float DEFAULT NULL,
  `pd_apr` float DEFAULT NULL,
  `perte_pd_prc` float DEFAULT NULL,
  `perte_pd_kg` float DEFAULT NULL,
  `grss_avt` float DEFAULT NULL,
  `grss_apr` float DEFAULT NULL,
  `perte_grss` float DEFAULT NULL,
  `h2o_avt` float DEFAULT NULL,
  `h2o_apr` float DEFAULT NULL,
  `perte_h2o` float DEFAULT NULL,
  `grssv_avt` float DEFAULT NULL,
  `grssv_apr` float DEFAULT NULL,
  `mscl_kg_avt` float DEFAULT NULL,
  `mscl_kg_apr` float DEFAULT NULL,
  `mscl_prc_avt` float DEFAULT NULL,
  `mscl_prc_apr` float DEFAULT NULL,
  `gain_mscl` float DEFAULT NULL,
  `mss_oss_avt` float DEFAULT NULL,
  `mss_oss_apr` float DEFAULT NULL,
  `gain_os` float DEFAULT NULL,
  `besoin_enrg_avt` float DEFAULT NULL,
  `besoin_enrg_apr` float DEFAULT NULL,
  `age_met_avt` int(4) DEFAULT NULL,
  `age_met_apr` int(4) DEFAULT NULL,
  `imc_avt` float DEFAULT NULL,
  `imc_apr` float DEFAULT NULL,
  `meta_base` int(6) DEFAULT NULL,
  PRIMARY KEY (`id_stat`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Contenu de la table `jj_stat`
--

INSERT INTO `jj_stat` (`id_stat`, `age`, `taille`, `pd_avt`, `pd_apr`, `perte_pd_prc`, `perte_pd_kg`, `grss_avt`, `grss_apr`, `perte_grss`, `h2o_avt`, `h2o_apr`, `perte_h2o`, `grssv_avt`, `grssv_apr`, `mscl_kg_avt`, `mscl_kg_apr`, `mscl_prc_avt`, `mscl_prc_apr`, `gain_mscl`, `mss_oss_avt`, `mss_oss_apr`, `gain_os`, `besoin_enrg_avt`, `besoin_enrg_apr`, `age_met_avt`, `age_met_apr`, `imc_avt`, `imc_apr`, `meta_base`) VALUES
(1, 57, 1.73, 67.6, 64.9, -4.2, 2.7, 16.3, 16.3, 0.4, 59.8, 58.9, 2.7, 8, 8, 53.7, 51.8, 79.4, 79.8, 0.4, 2.8, 2.7, 0, 2865, 2687, 42, 42, 22.6, 21.7, 1530),
(2, 55, 1.88, 101.5, 98.3, -3.2, 3.2, 20.1, 19.1, 1.6, 52, 51.8, 6.1, 11, 10, 5, 73.7, 4.9, 74.9, 70, 4, 3.9, 0, 3992, 3643, 34, 32, 28.7, 27.8, 478),
(3, 56, 2, 110, 105, -4.7619, 5, 20, 19.1, 1.945, 52, 51, 0.391, 10, 8, 23, 25, 20.9091, 23.8095, 2.90043, 19, 21, 2.72727, 220, 230, 46, 46, 27.5, 26.25, 867),
(4, 56, 2, 110, 105, -4.8, 5, 20, 19.1, 1.9, 52, 51, 0.4, 10, 8, 23, 25, 20.9, 23.8, 2.9, 19, 21, 2.7, 220, 230, 46, 46, 27.5, 26.3, 867),
(5, 59, 1.59, 79.8, 77.3, -3.2, 2.5, 52, 51.4, 1.8, 20, 16.9, 2.9, 19, 13, 59, 54, 73.9, 69.9, -4.1, 56, 55, 1, 100, 200, 22, 26, 31.6, 30.6, 1644),
(6, 15, 12, 16, 16, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.1, 0.1, 370),
(7, 1, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 370),
(8, 2, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 370),
(9, 1, 2, 9, 8, -12.5, 1, 8, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9),
(11, 7, 7, 7, 2, -250, 5, 7, 7, 0.4, 7, 7, 0.4, 7, 7, 7, 7, 100, 350, 250, 7, 7, 250, 7, 7, 7, 7, 0.1, 0, 521),
(12, 2, 3, 3, 1, -200, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.3, 0.1, 370);

-- --------------------------------------------------------

--
-- Structure de la table `jj_utilisateur`
--

CREATE TABLE IF NOT EXISTS `jj_utilisateur` (
  `id_utilisateur` int(4) NOT NULL AUTO_INCREMENT,
  `login` varchar(100) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `hashedpass` varchar(255) NOT NULL,
  PRIMARY KEY (`id_utilisateur`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `jj_utilisateur`
--

INSERT INTO `jj_utilisateur` (`id_utilisateur`, `login`, `nom`, `prenom`, `hashedpass`) VALUES
(1, 'admin', 'Administrateur', '', 'c7ad44cbad762a5da0a452f9e854fdc1e0e7a52a38015f23f3eab1d80b931dd472634dfac71cd34ebc35d16ab7fb8a90c81f975113d6c7538dc69dd8de9077ec'),
(2, 'jolijeune', 'jolijeune', 'jolijeune', '3bd84435272985b03eed20281e061625656e1720c911f0d63e0a5ee2eccc952afa3e32e6ef71bf6bd6b25f6f68e2350d935547b26b802118ce91014984278e99');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
