-- phpMyAdmin SQL Dump
-- version 3.3.7deb1
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Ven 05 Novembre 2010 à 15:12
-- Version du serveur: 5.1.49
-- Version de PHP: 5.3.2-2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `bd`
--

-- --------------------------------------------------------

--
-- Structure de la table `amis`
--

CREATE TABLE IF NOT EXISTS `amis` (
  `euid` int(11) DEFAULT NULL,
  `duid` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Contenu de la table `amis`
--


-- --------------------------------------------------------

--
-- Structure de la table `appartient`
--

CREATE TABLE IF NOT EXISTS `appartient` (
  `uid` int(11) DEFAULT NULL,
  `lid` int(11) DEFAULT NULL,
  `date_achat` int(11) DEFAULT NULL,
  `etat` int(1) DEFAULT NULL,
  `emplacement` text COLLATE utf8_bin
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Contenu de la table `appartient`
--


-- --------------------------------------------------------

--
-- Structure de la table `auteurs`
--

CREATE TABLE IF NOT EXISTS `auteurs` (
  `aid` int(11) NOT NULL AUTO_INCREMENT,
  `anom` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `aphoto` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `biographie` text COLLATE utf8_bin,
  `adnaissance` date DEFAULT NULL,
  `avalide` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Contenu de la table `auteurs`
--


-- --------------------------------------------------------

--
-- Structure de la table `demande_ami`
--

CREATE TABLE IF NOT EXISTS `demande_ami` (
  `euid` int(11) DEFAULT NULL,
  `duid` int(11) DEFAULT NULL,
  `dadate` int(11) NOT NULL,
  `damessage` text COLLATE utf8_bin
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Contenu de la table `demande_ami`
--


-- --------------------------------------------------------

--
-- Structure de la table `editeurs`
--

CREATE TABLE IF NOT EXISTS `editeurs` (
  `eid` int(11) NOT NULL AUTO_INCREMENT,
  `enom` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`eid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Contenu de la table `editeurs`
--


-- --------------------------------------------------------

--
-- Structure de la table `genre`
--

CREATE TABLE IF NOT EXISTS `genre` (
  `gid` int(11) NOT NULL AUTO_INCREMENT,
  `gnom` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Contenu de la table `genre`
--


-- --------------------------------------------------------

--
-- Structure de la table `jetons_auth`
--

CREATE TABLE IF NOT EXISTS `jetons_auth` (
  `jid` int(11) NOT NULL AUTO_INCREMENT,
  `jdate` int(11) NOT NULL,
  `jip` varchar(11) COLLATE utf8_bin NOT NULL,
  `uid` int(11) DEFAULT NULL,
  PRIMARY KEY (`jid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Contenu de la table `jetons_auth`
--


-- --------------------------------------------------------

--
-- Structure de la table `livres`
--

CREATE TABLE IF NOT EXISTS `livres` (
  `lid` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `isbn` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `ean13` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `date_publication` int(11) DEFAULT NULL,
  `couverture` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `lvalide` tinyint(1) DEFAULT NULL,
  `description` text COLLATE utf8_bin,
  `editeur` int(11) DEFAULT NULL,
  `genre` int(11) DEFAULT NULL,
  `serie` int(11) DEFAULT NULL,
  `auteur` int(11) DEFAULT NULL,
  `ajuid` int(11) DEFAULT NULL,
  `ajdate` int(11) DEFAULT NULL,
  PRIMARY KEY (`lid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Contenu de la table `livres`
--


-- --------------------------------------------------------

--
-- Structure de la table `series`
--

CREATE TABLE IF NOT EXISTS `series` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `snom` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `svalide` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Contenu de la table `series`
--


-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(50) COLLATE utf8_bin NOT NULL,
  `motdepasse` varchar(50) COLLATE utf8_bin NOT NULL,
  `email` varchar(50) COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin,
  `bdpublique` tinyint(1) DEFAULT NULL,
  `accueiltype` int(1) DEFAULT NULL,
  `ipinscription` varchar(50) COLLATE utf8_bin NOT NULL,
  `dinscription` int(11) NOT NULL,
  `uetat` int(1) NOT NULL,
  `cvalidation` varchar(30) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Contenu de la table `utilisateurs`
--


