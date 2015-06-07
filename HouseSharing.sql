-- phpMyAdmin SQL Dump
-- version 4.2.10
-- http://www.phpmyadmin.net
--
-- Client :  localhost:8889
-- Généré le :  Dim 18 Janvier 2015 à 22:31
-- Version du serveur :  5.5.38
-- Version de PHP :  5.5.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données :  `housesharing`
--

-- --------------------------------------------------------

--
-- Structure de la table `appart`
--

CREATE TABLE `appart` (
`idappart` int(11) NOT NULL,
  `adresse` text NOT NULL,
  `taille` int(11) NOT NULL,
  `nombrepiece` int(11) NOT NULL,
  `iduser` int(11) NOT NULL,
  `ville` varchar(45) NOT NULL,
  `nbrepers` int(11) NOT NULL,
  `description` text,
  `demande` text
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `appart`
--

INSERT INTO `appart` (`idappart`, `adresse`, `taille`, `nombrepiece`, `iduser`, `ville`, `nbrepers`, `description`, `demande`) VALUES
(4, '65 rue chez moi', 76, 3, 3, 'paris', 6, 'TrÃ¨s belle vue.', ''),
(5, '78 allÃ©e du soleil levant', 76, 5, 3, 'montrÃ©al', 6, 'Jardin avec piscine disponible en Ã©tÃ©. Salle de sport.', 'Arroser les plantes'),
(6, '34 avenue richard lenoir', 32, 5, 3, 'paris', 8, 'Chez ma cousine', ''),
(7, 'Cage Ã  oiseau', 1, 1, 1, 'les cieux', 3, 'Compact mais bien agencÃ©.', ''),
(8, 'Nid de la branche', 2, 1, 1, 'saint-cÃ¨dre-sur-bain', 8, 'EntiÃ¨re construit en bois. Architecture biologique.', ''),
(9, '25 rue du ponton', 65, 6, 5, 'portuga', 10, 'Vue sur la mer. Emplacement pour bateau.', 'Ne pas abimer les chapeaux.'),
(12, '56 Avenue du &quot;soleil levant&quot;', 76, 3, 3, 'nantes', 2, 'Villa du bord de mer. A moins de 5 minutes Ã  pieds du club de surf.', 'Bien fermer toutes les fenÃªtres en partant.'),
(14, '56 rue du croquis', 67, 4, 4, 'stop-motion town', 8, 'Palace bien meublÃ©', 'Ne pas manger titi.');

-- --------------------------------------------------------

--
-- Structure de la table `com`
--

CREATE TABLE `com` (
`idcom` int(11) NOT NULL,
  `note` int(11) NOT NULL,
  `texte` text NOT NULL,
  `idappart` int(11) NOT NULL,
  `iduser` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `com`
--

INSERT INTO `com` (`idcom`, `note`, `texte`, `idappart`, `iduser`) VALUES
(1, 5, 'TrÃ¨s bel appart !', 9, 3),
(2, 1, 'ExtrÃªmement petit. Nous Ã©tions 5 et nous Ã©tions Ã  l&#039;Ã©troit. Je suis dÃ©Ã§u.', 8, 3),
(3, 3, 'Beau quartier. TrÃ¨s animÃ©. L&#039;appartement est cependant petit, mais comme le dit l&#039;annonce, bien agencÃ©.', 7, 3),
(4, 5, 'TrÃ¨s agrÃ©able ! IdÃ©al pour un week-end en amoureux.', 7, 5);

-- --------------------------------------------------------

--
-- Structure de la table `critere`
--

CREATE TABLE `critere` (
`idcritere` int(11) NOT NULL,
  `nom` varchar(45) NOT NULL,
  `description` text NOT NULL,
  `type` varchar(45) NOT NULL,
  `idappart` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `disponibilites`
--

CREATE TABLE `disponibilites` (
`iddisponibilites` int(11) NOT NULL,
  `datedebut` datetime NOT NULL,
  `datefin` date NOT NULL,
  `idappart` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `forum_categorie`
--

CREATE TABLE `forum_categorie` (
`cat_id` int(11) NOT NULL,
  `cat_nom` varchar(30) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `cat_ordre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `forum_post`
--

CREATE TABLE `forum_post` (
`post_id` int(11) NOT NULL,
  `post_createur` int(11) NOT NULL,
  `post_texte` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `post_time` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `post_forum_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `forum_post`
--

INSERT INTO `forum_post` (`post_id`, `post_createur`, `post_texte`, `post_time`, `topic_id`, `post_forum_id`) VALUES
(18, 3, 'Bonjour,\r\n\r\nJe n''ai pas compris Ã  quoi servait l''onglet [g]demande et contrainte[/g]. Quelqu''un peut m''aider svp ?\r\n\r\nMerci d''avance :)', 1421564980, 3, 0),
(19, 3, 'Bonjour,\r\n\r\nJe suis administrateur. Si vous avez des questions, vous pouvez les poser ici. Merci.', 1421573613, 4, 0),
(20, 1, 'Bonjour,\r\n\r\nIl sert Ã  exprimer des demandes et des contraintes particuliÃ¨res. Si vous voulez que les gens fassent quelque chose chez vous (comme arroser les plantes), vous le marquez lÃ  par exemple.\r\n\r\nJ''espÃ¨re avoir rÃ©pondu Ã  votre question.\r\n\r\nBonne journÃ©e !', 1421573885, 3, 0);

-- --------------------------------------------------------

--
-- Structure de la table `forum_topic`
--

CREATE TABLE `forum_topic` (
`topic_id` int(11) NOT NULL,
  `forum_id` int(11) NOT NULL,
  `topic_titre` char(60) NOT NULL,
  `topic_createur` int(11) NOT NULL,
  `topic_vu` mediumint(8) NOT NULL,
  `topic_time` int(11) NOT NULL,
  `topic_genre` varchar(30) NOT NULL,
  `topic_last_post` int(11) NOT NULL,
  `topic_first_post` int(11) NOT NULL,
  `topic_post` mediumint(8) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `forum_topic`
--

INSERT INTO `forum_topic` (`topic_id`, `forum_id`, `topic_titre`, `topic_createur`, `topic_vu`, `topic_time`, `topic_genre`, `topic_last_post`, `topic_first_post`, `topic_post`) VALUES
(3, 0, 'Inscription apart', 3, 17, 1421564980, 'Message', 24, 18, 3),
(4, 0, 'Admin', 3, 4, 1421573613, 'Message', 19, 19, 0);

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
`id` int(11) NOT NULL,
  `id_expediteur` int(11) NOT NULL DEFAULT '0',
  `id_destinataire` int(11) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `titre` text NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
`iduser` int(11) NOT NULL,
  `nom` varchar(45) NOT NULL,
  `prenom` varchar(45) NOT NULL,
  `datenaissance` datetime NOT NULL,
  `telephone` varchar(12) DEFAULT NULL,
  `mail` varchar(45) NOT NULL,
  `identifiant` varchar(45) NOT NULL,
  `mdp` varchar(45) NOT NULL,
  `admin` int(1) DEFAULT '0',
  `actif` int(11) NOT NULL DEFAULT '0',
  `codeactiv` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`iduser`, `nom`, `prenom`, `datenaissance`, `telephone`, `mail`, `identifiant`, `mdp`, `admin`, `actif`, `codeactiv`) VALUES
(1, 'Titi', 'Titi', '0000-00-00 00:00:00', '0998876655', 'titi@titi.fr', 'titi', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 0, 1, 0),
(2, 'Minet', 'Gros', '0000-00-00 00:00:00', '6554323456', 'gro@minet.fr', 'grominet', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 0, 1, 0),
(3, 'Administrateur', 'Mr', '0000-00-00 00:00:00', '1234567890', 'admin@admin.fr', 'admin', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 1, 1, 0),
(4, 'Minette', 'Gros', '0000-00-00 00:00:00', '0123456789', 'grosminette@gmail.com', 'grominette', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 0, 1, 0),
(5, 'Sparrow', 'Jack', '0000-00-00 00:00:00', '0986556567', 'jack@pirate.fr', 'Captain', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 1, 1, 0),
(11, 'jb', 'jb', '0000-00-00 00:00:00', '0987654321', 'jb@jb.fr', '&quot;&#039;&quot;&#039;', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 1, 1, 0),
(12, 'Cou', 'Cou', '0000-00-00 00:00:00', '0987654321', 'coucou@tata.com', 'coucou', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 0, 1, 0),
(13, 'Teille', 'Bouh', '0000-00-00 00:00:00', '0987654321', 'bouh@teille.fr', 'bouteille', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 1, 1, 0);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `appart`
--
ALTER TABLE `appart`
 ADD PRIMARY KEY (`idappart`), ADD KEY `fk_appart_user_idx` (`iduser`);

--
-- Index pour la table `com`
--
ALTER TABLE `com`
 ADD PRIMARY KEY (`idcom`);

--
-- Index pour la table `critere`
--
ALTER TABLE `critere`
 ADD PRIMARY KEY (`idcritere`), ADD KEY `fk_critere_appart1_idx` (`idappart`);

--
-- Index pour la table `disponibilites`
--
ALTER TABLE `disponibilites`
 ADD PRIMARY KEY (`iddisponibilites`), ADD KEY `fk_disponibilites_appart1_idx` (`idappart`);

--
-- Index pour la table `forum_categorie`
--
ALTER TABLE `forum_categorie`
 ADD PRIMARY KEY (`cat_id`), ADD UNIQUE KEY `cat_ordre` (`cat_ordre`);

--
-- Index pour la table `forum_post`
--
ALTER TABLE `forum_post`
 ADD PRIMARY KEY (`post_id`);

--
-- Index pour la table `forum_topic`
--
ALTER TABLE `forum_topic`
 ADD PRIMARY KEY (`topic_id`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`iduser`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `appart`
--
ALTER TABLE `appart`
MODIFY `idappart` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT pour la table `com`
--
ALTER TABLE `com`
MODIFY `idcom` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `critere`
--
ALTER TABLE `critere`
MODIFY `idcritere` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `disponibilites`
--
ALTER TABLE `disponibilites`
MODIFY `iddisponibilites` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `forum_categorie`
--
ALTER TABLE `forum_categorie`
MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `forum_post`
--
ALTER TABLE `forum_post`
MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT pour la table `forum_topic`
--
ALTER TABLE `forum_topic`
MODIFY `topic_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
MODIFY `iduser` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;