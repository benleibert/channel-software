-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Jeu 16 Juillet 2015 à 08:26
-- Version du serveur: 5.5.24-log
-- Version de PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `channel_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `beneficiaire`
--

CREATE TABLE IF NOT EXISTS `beneficiaire` (
  `CODE_BENEF` varchar(10) NOT NULL,
  `CODE_TYPEBENEF` varchar(10) NOT NULL,
  `IDPROVINCE` int(11) NOT NULL,
  `BENEF_NOM` varchar(200) DEFAULT NULL,
  `BENEF_EBREVIATION` varchar(10) DEFAULT NULL,
  `BENEF_TEL` varchar(30) DEFAULT NULL,
  `BENEF_VILLE` varchar(50) DEFAULT NULL,
  `BENEF_EMAIL` varchar(100) DEFAULT NULL,
  `BENEF_DATECREAT` datetime DEFAULT NULL,
  PRIMARY KEY (`CODE_BENEF`),
  KEY `PROV_BENEF_FK` (`IDPROVINCE`),
  KEY `TYPEBENF_BENEF_FK` (`CODE_TYPEBENEF`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `bonsortie`
--

CREATE TABLE IF NOT EXISTS `bonsortie` (
  `CODE_BONSORTIE` varchar(30) NOT NULL,
  `CODE_BENEF` varchar(10) NOT NULL,
  `CODE_MAGASIN` varchar(10) NOT NULL,
  `ID_EXERCICE` int(11) NOT NULL,
  `REF_BONSORTIE` varchar(100) DEFAULT NULL,
  `ID_BONSORTIE` int(11) NOT NULL,
  `SOR_LIBELLE` varchar(250) DEFAULT NULL,
  `SOR_DATE` date DEFAULT NULL,
  `SOR_VALIDE` tinyint(4) DEFAULT NULL,
  `SOR_DATEVALID` datetime DEFAULT NULL,
  PRIMARY KEY (`CODE_BONSORTIE`),
  KEY `BENEF_BONSORTIE_FK` (`CODE_BENEF`),
  KEY `MAG_BONSORTIE_FK` (`CODE_MAGASIN`),
  KEY `EXERCICE_BONSORTIE_FK` (`ID_EXERCICE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE IF NOT EXISTS `categorie` (
  `CODE_CATEGORIE` varchar(10) NOT NULL,
  `CAT_LIBELLE` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`CODE_CATEGORIE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE IF NOT EXISTS `commande` (
  `CODE_COMMANDE` varchar(30) NOT NULL,
  `CODE_FOUR` varchar(10) NOT NULL,
  `CODE_MAGASIN` varchar(10) NOT NULL,
  `ID_EXERCICE` int(11) NOT NULL,
  `REF_COMMANDE` varchar(250) DEFAULT NULL,
  `ID_COMMANDE` int(11) NOT NULL,
  `CDE_LIBELLE` varchar(250) DEFAULT NULL,
  `CDE_DATE` date DEFAULT NULL,
  `CDE_STATUT` tinyint(4) DEFAULT NULL,
  `CDE_DATEVALID` datetime DEFAULT NULL,
  PRIMARY KEY (`CODE_COMMANDE`),
  KEY `CDE_FOUR_FK` (`CODE_FOUR`),
  KEY `MAG_CDE_FK` (`CODE_MAGASIN`),
  KEY `CDE_EXERCICE_FK` (`ID_EXERCICE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `compte`
--

CREATE TABLE IF NOT EXISTS `compte` (
  `LOGIN` varchar(20) NOT NULL,
  `NUM_MLLE` varchar(10) NOT NULL,
  `IDPROFIL` varchar(10) NOT NULL,
  `PWD` varchar(150) DEFAULT NULL,
  `ACTIVATED` tinyint(4) DEFAULT NULL,
  `idlangue` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`LOGIN`),
  KEY `PERS_COMPTE_FK` (`NUM_MLLE`),
  KEY `COMPTE_PROFIL_FK` (`IDPROFIL`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `compte`
--

INSERT INTO `compte` (`LOGIN`, `NUM_MLLE`, `IDPROFIL`, `PWD`, `ACTIVATED`, `idlangue`) VALUES
('root', '0345Y', 'DEVEL', '63a9f0ea7bb98050796b649e85481845', 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `declass`
--

CREATE TABLE IF NOT EXISTS `declass` (
  `CODE_DECLASS` varchar(30) NOT NULL,
  `CODE_MAGASIN` varchar(10) NOT NULL,
  `ID_EXERCICE` int(11) NOT NULL,
  `CODENATDECLASS` varchar(15) NOT NULL,
  `ID_DECLASS` int(11) NOT NULL,
  `REF_DECLAS` varchar(200) DEFAULT NULL,
  `DCL_DATE` date DEFAULT NULL,
  `DCL_LIBELLE` varchar(250) DEFAULT NULL,
  `DCL_RAISON` text,
  `DCL_REFRAPPORT` varchar(100) DEFAULT NULL,
  `DCL_CABINET` varchar(100) DEFAULT NULL,
  `DCL_VALIDE` tinyint(4) DEFAULT NULL,
  `DCL_DATEVALID` datetime DEFAULT NULL,
  PRIMARY KEY (`CODE_DECLASS`),
  KEY `MAG_DECLASS_FK` (`CODE_MAGASIN`),
  KEY `EXERCICE_DECL_FK` (`ID_EXERCICE`),
  KEY `DECLASS_NATDECLASS_FK` (`CODENATDECLASS`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `detbonsortie`
--

CREATE TABLE IF NOT EXISTS `detbonsortie` (
  `CODE_DETBONSORTIE` varchar(30) NOT NULL,
  `CODE_PRODUIT` varchar(20) NOT NULL,
  `CODE_BONSORTIE` varchar(30) NOT NULL,
  `ID_DETBONSORTIE` int(11) NOT NULL,
  `CODE_MAGASIN` varchar(10) NOT NULL,
  `BSPRD_QTE` int(11) DEFAULT NULL,
  `BSPRD_RECU` tinyint(4) DEFAULT NULL,
  `BSPRD_UNITE` varchar(10) DEFAULT NULL,
  `BSPRD_REFLOT` varchar(30) DEFAULT NULL,
  `BSPRD_DATEPEREMP` date DEFAULT NULL,
  `BSPRD_PV` decimal(10,2) DEFAULT NULL,
  `BSPRD_MONLOT` varchar(30) NOT NULL,
  PRIMARY KEY (`CODE_DETBONSORTIE`),
  KEY `BONS_DETBONSORTIE_FK` (`CODE_BONSORTIE`),
  KEY `PRD_DETBONSORTIE_FK` (`CODE_PRODUIT`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `detdeclass`
--

CREATE TABLE IF NOT EXISTS `detdeclass` (
  `CODE_DETDECLASS` varchar(30) NOT NULL,
  `CODE_PRODUIT` varchar(20) NOT NULL,
  `CODE_DECLASS` varchar(30) NOT NULL,
  `CODE_MAGASIN` varchar(10) NOT NULL,
  `ID_DETDECLASS` int(11) NOT NULL,
  `DECL_QTE` int(11) DEFAULT NULL,
  `DECL_UNITE` varchar(10) DEFAULT NULL,
  `DECL_REFLOT` varchar(30) DEFAULT NULL,
  `DECL_DATEPEREMP` date DEFAULT NULL,
  `DECL_PA` decimal(10,2) DEFAULT NULL,
  `DECL_MONLOT` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`CODE_DETDECLASS`),
  KEY `PRD_DETDECLASS_FK` (`CODE_PRODUIT`),
  KEY `DECL_DETDECLASS_FK` (`CODE_DECLASS`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `detinventaire`
--

CREATE TABLE IF NOT EXISTS `detinventaire` (
  `CODE_DETINVENTAIRE` varchar(30) NOT NULL,
  `CODE_INVENTAIRE` varchar(30) NOT NULL,
  `CODE_PRODUIT` varchar(20) NOT NULL,
  `ID_DETINVENTAIRE` int(11) NOT NULL,
  `CODE_MAGASIN` varchar(10) NOT NULL,
  `STOCK_PHYSIQUE` int(11) DEFAULT NULL,
  `STOCK_THEO` int(11) DEFAULT NULL,
  `ECART` int(11) DEFAULT NULL,
  `RAISON_ECART` text,
  `INV_PA` decimal(10,2) DEFAULT NULL,
  `INV_UNITE` varchar(10) DEFAULT NULL,
  `INV_REFLOT` varchar(30) DEFAULT NULL,
  `INV_DATEPEREMP` date DEFAULT NULL,
  `INV_MONLOT` varchar(30) NOT NULL,
  PRIMARY KEY (`CODE_DETINVENTAIRE`),
  KEY `PRD_DETINVENTAIRE_FK` (`CODE_PRODUIT`),
  KEY `INV_DETINVENTAIRE_FK` (`CODE_INVENTAIRE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `detlivraison`
--

CREATE TABLE IF NOT EXISTS `detlivraison` (
  `CODE_DETLIVRAISON` varchar(30) NOT NULL,
  `CODE_PRODUIT` varchar(20) NOT NULL,
  `CODE_LIVRAISON` varchar(30) NOT NULL,
  `ID_DETLIVRAISON` int(11) NOT NULL,
  `LVR_PRDQTE` int(11) DEFAULT NULL,
  `LVR_PRDRECU` int(11) DEFAULT NULL,
  `LVR_QTESORTIE` int(11) DEFAULT NULL,
  `LVR_UNITE` varchar(10) DEFAULT NULL,
  `LVR_IDCOMMANDE` varchar(30) DEFAULT NULL,
  `LVR_MAGASIN` varchar(20) DEFAULT NULL,
  `LVR_PA` decimal(10,2) DEFAULT NULL,
  `LVR_PR` decimal(10,2) DEFAULT NULL,
  `LVRLOT_VALID` tinyint(4) DEFAULT NULL,
  `LVRLOT_DATEVALID` datetime DEFAULT NULL,
  `LVR_REFLOT` varchar(30) DEFAULT NULL,
  `LVR_DATEPEREMP` date DEFAULT NULL,
  `LVR_MONLOT` varchar(30) DEFAULT NULL,
  `CODE_MAGASIN` varchar(10) NOT NULL,
  PRIMARY KEY (`CODE_DETLIVRAISON`),
  KEY `LIVR_DETLIVRAISON_FK` (`CODE_LIVRAISON`),
  KEY `PRD_DETLIVRAISON_FK` (`CODE_PRODUIT`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `detreport`
--

CREATE TABLE IF NOT EXISTS `detreport` (
  `CODE_DETREPORT` varchar(30) NOT NULL,
  `CODE_REPORT` varchar(30) NOT NULL,
  `CODE_PRODUIT` varchar(20) NOT NULL,
  `ID_DETREPORT` int(11) NOT NULL,
  `CODE_MAGASIN` varchar(10) NOT NULL,
  `REP_PRDQTE` int(11) DEFAULT NULL,
  `REP_UNITE` varchar(10) DEFAULT NULL,
  `REP_REFLOT` varchar(30) DEFAULT NULL,
  `REP_DATEPEREMP` date DEFAULT NULL,
  `REP_PV` decimal(10,2) DEFAULT NULL,
  `REP_PA` decimal(10,2) DEFAULT NULL,
  `REP_PR` decimal(10,2) DEFAULT NULL,
  `REP_MONLOT` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`CODE_DETREPORT`),
  KEY `REP_DETREPORT_FK` (`CODE_REPORT`),
  KEY `PRD_DETREPORT_FK` (`CODE_PRODUIT`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `dettransfert`
--

CREATE TABLE IF NOT EXISTS `dettransfert` (
  `CODE_DETTRANSFERT` varchar(30) NOT NULL,
  `CODE_PRODUIT` varchar(20) NOT NULL,
  `CODE_TRANSFERT` varchar(30) NOT NULL,
  `ID_DETTRANSFERT` int(11) NOT NULL,
  `CODE_MAGASIN` varchar(10) NOT NULL,
  `TRS_PRDQTE` int(11) DEFAULT NULL,
  `TRS_PRDRECU` tinyint(4) DEFAULT NULL,
  `TRS_UNITE` varchar(10) DEFAULT NULL,
  `TRS_REFLOT` varchar(30) DEFAULT NULL,
  `TRS_DATEPEREMP` date DEFAULT NULL,
  `TRS_PV` decimal(10,2) DEFAULT NULL,
  `TRS_MONLOT` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`CODE_DETTRANSFERT`),
  KEY `PRD_TRANSFERT_FK` (`CODE_PRODUIT`),
  KEY `TRS_DETTRANFERT_FK` (`CODE_TRANSFERT`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `diction`
--

CREATE TABLE IF NOT EXISTS `diction` (
  `idlangue` int(11) NOT NULL AUTO_INCREMENT,
  `francais` text NOT NULL,
  `anglais` text NOT NULL,
  `portugais` text NOT NULL,
  PRIMARY KEY (`idlangue`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=465 ;

--
-- Contenu de la table `diction`
--

INSERT INTO `diction` (`idlangue`, `francais`, `anglais`, `portugais`) VALUES
(1, 'Cochez tous (Tous les droits)', 'User Group', 'Grupo de utilizadores'),
(2, '*Signifie obligatoire', '*Mandatory Fields', 'Obrigatório'),
(3, 'A propos', 'to the purpose', 'Sobre'),
(4, 'Accueil', 'Home/Welcome Page', 'Benvindo'),
(5, 'Adresse', 'Address', 'Endereço'),
(6, 'Affectation des établissements', 'Allocation of health facilities', 'Afetação dos estabelecimentos sanitários'),
(7, 'Affichage par page', 'Page View', 'Visão por página'),
(8, 'Aide', 'Help', 'Ajuda'),
(9, 'Aide', 'Help', 'Ajuda'),
(10, 'Ajout.', 'Added', 'Adição'),
(11, 'Ajouter', 'Add', 'Adicionar'),
(12, 'Ancien mot de passe', 'Old password', 'Antiga Senha'),
(13, 'Annul.', 'Cancel', 'Cancelar'),
(14, 'Autres livraisons', 'Other deliveries', 'Outras entregas'),
(15, 'Autres paramètres', 'Other Settings', 'Outros parametros'),
(16, 'Bénéficiaire', 'Beneficiary', 'Beneficiário'),
(17, 'Bon de sortie', 'Issued/Exit Log', 'Guia de saída'),
(18, 'Bon d''entrée', 'Entry Log', 'Guia de entrada'),
(19, 'Catégorie', 'Category', 'Categoria'),
(20, 'Catégorie de produits concernée', 'Product category Concerned', 'Categoria de Produto'),
(21, 'Catégorie produit', 'Product Category', 'Categoria de Produto'),
(22, 'Catégories & produits', 'Categories and Products', 'Categorias e Produtos'),
(23, 'Ce module vous permet de faire l''inventaire physique du stock et éditer les états de stock.', 'This module allows you to do the physical inventory of the stock and edit the status of the stock.', 'Este modulo permite fazer o inventário físico do stock e editar a situação do stock'),
(24, 'Ce module vous permet de générer les divers rapports et de les imprimer.', 'This module allows you to generate various reports and print them.', 'Este modulo permite gerar os diferentes relatórios e os imprimir.'),
(25, 'Centres d''examen', 'Examination centers', 'Centros de exame'),
(26, 'Changer mot de passe', 'Change Password', 'Alterar a Senha'),
(27, 'Circuit de distribution', 'Distribution circuit', 'Circuito de distribuição'),
(28, 'Code de sortie', 'Issued Code', 'Codigo de saída'),
(29, 'Code commande', 'Purchase Order code', 'Codigo de requisição'),
(30, 'Code livraison', 'Delivery Code', ' Codigo de entrega'),
(31, 'Code perte', 'Lossses Code', 'Codigo de perda'),
(32, 'Code profil', 'Profile Code', 'Codigo do perfil'),
(33, 'Code report', 'Report Code', 'Codigo de relatório'),
(34, 'Code transfert', 'Transfer Code', 'Codigo de transferência'),
(35, 'Commande concernée', 'Purchase Order concerned', 'Requisição em causa'),
(36, 'Commandes', 'Purchase Order', 'Requisições'),
(37, 'Compte utilisateur', 'User Account', 'Conta do utilizador'),
(38, 'Conditionnement', 'Conditioning', 'Acondicionamento'),
(39, 'Confirmer', 'Confirm', 'Confirmar'),
(40, 'Consommations', 'Consumptions', 'Consumos'),
(41, 'Consommations (Dotation d''un service ou un individu)', 'Consumption (allocation of a service or individual)', 'Consumos (Dotação de um serviço ou de um individuo)'),
(42, 'Date', 'Date', 'Data'),
(43, 'Date (Période)', 'Date (Period)', 'Data (Período)'),
(44, 'Date commande', 'Purchase Order Date', 'Data da requisição'),
(45, 'Date livraison', 'Delivery Date', 'Data de entrega'),
(46, 'Déclassement', 'Disqualification', 'Desqualificação'),
(47, 'Définir les droits du profil', 'Set Profile Rights', 'Definir os direitos de perfil'),
(48, 'Dernière', 'Back', 'Ultimo'),
(49, 'Dons (Dons de produits par une institution de la place ou l''Etat)', 'Donations (Donations of products by an Institution or the State)', 'Doações (Doações de produtos por uma Instituição ou o pelo Estado'),
(50, 'Editer', 'Edit', 'Editar'),
(51, 'E-mail', 'E-mail', 'E-mail'),
(52, 'Entrées de produits', 'Product Entry', 'Entradas de produtos'),
(53, 'Estimer le nombre de lignes', 'Estimate the number of lines', 'Estimar o número de linhas'),
(54, 'Etat', 'State', 'Situação'),
(55, 'Etat de stock/lot', 'State of stock / lot', 'Situação de Stock/lote'),
(56, 'Etat de stock/produit', 'State of stock / product', 'Situação de Stock/produto'),
(57, 'Etat du stock', 'Stock status', 'Situação de stock'),
(58, 'Etat du stock par lot de produits', 'Stock status by Lot of products', 'Situação de stock por lote de produtos'),
(59, 'Etat du stock par produit', 'Stock status by product', 'Situação de stock por produto'),
(60, 'Etat du stock par ref. lots de produits', 'State of the stock by ref. of the product lots', 'Situação de stock por ref. lotes de produtos'),
(61, 'etc.', 'etc…', 'etc.'),
(62, 'Exercice budgétaire', 'Fiscal year', 'Exercício orçamental'),
(63, 'Expression des besoins', 'Expression of needs', 'Expressão das necessidades'),
(64, 'Fiche de stock', 'Stock Sheet', 'Ficha de stock'),
(65, 'Fonction', 'Function', 'Função'),
(66, 'Fournisseur', 'Provider/Supplier', 'Fornecedor'),
(67, 'Fournisseurs & Bénéficiaires', 'Suppliers & Beneficiaries', 'Fornecedores e Beneficiários'),
(68, 'Groupe d''utilisateurs', 'User Group', 'Grupo de utilizadores'),
(69, 'Importer de la base', 'Import from the base', 'Importar da basse'),
(70, 'Imprimer fiche d''inventaire', 'Print inventory sheet', 'Imprimir ficha de inventário'),
(71, 'Inventaire & Etats', 'Inventory & State', 'Inventário e Situação'),
(72, 'Inventaire de stock', 'Stock Inventory', 'Inventário de stock'),
(73, 'Inventaires', 'Inventory', 'Inventários'),
(74, 'Journal des mouvements de stock', 'Records of stock movements', 'Histórico dos movimentos de stock'),
(75, 'Les entrées de stock constituent l''ensemble des opérations de livraisons liees à une commande ou de dons de produits. Il s''agit notamment de :', 'The stock entries constitute all the operations of delivery related to a purchase order or a product donations. These include:', 'As entradas de stock constituem o conjunto das operações de entregas relacionadas com uma requisição ou de doações de produtos. Tratá-se nomeadamente de:'),
(76, 'Les inventaires et états du stock', 'Inventories and the state of the stock', 'Os inventários e situação de stock'),
(77, 'Les rapport et imprimables', 'The report are printable', 'Os relatórios '),
(78, 'Les sorties de stock constituent l''ensemble des operations de retrait sur le stock. Il s''agit notamment de :', 'The exit of stocks constitute all the operations of removing the stock, these include:', 'As saídas de stock constituem o conjunto das operações de retirar do stock, Tratá-se nomeadamente de:'),
(79, 'Les sorties de stocks', 'Stock Issued', 'As saídas de stocks'),
(80, 'L''exercice budgétaire est 2015. Pour changer, veuillez sélectionner un exercice budgétaire dans la liste ci-dessous ...\n', 'The financial year is 2015. To change, please select a financial year in the list below ...', 'O ano de exercçicio orçamental é 2015. Para alterar. Queira selecionar um ano de exercício orçamental na lista abaixo …'),
(81, 'Libellé de sortie', 'Sales Order Description', 'Designação de saída'),
(82, 'Libellé commande', 'Purchase Order Description', 'Designação de requisição'),
(83, 'Libellé livraison', 'Delivery Description', 'Designação de entrega'),
(84, 'Libellé profil', 'Profile Description', 'Designação de perfil'),
(85, 'Livraison (Entrée de produits suite a une commande)', 'Delivery (entry of product based on a purchase order)', 'Entrega (Entrada de produtos após uma requisição)'),
(86, 'Livraisons', 'Delivery', 'Entregas '),
(87, 'Logs', 'Logs', 'Logs'),
(88, 'Logs utilisateurs', 'Users Log', 'Logs utilizadores'),
(89, 'Lots (gestion des lots de produits et leur date de péremption)', 'Lots (management of the lots of the products and their expiration date)', 'Lotes (gestão dos lotes de produtos e suas datas de expiração'),
(90, 'Magasin destination', 'Warehouse of destinaiton', 'Armazém de destino'),
(91, 'Magasin source', 'Warehouse of the Supplier', 'Armazém fornecedor'),
(92, 'Menu d''acces', 'Access Menu', 'Menu de Acesso'),
(93, 'Mettre a jour identité', 'Update the identity', 'Actualizar a identidade'),
(94, 'Mise à jour des besoins', 'Update the needs', 'Actualizar as necessidades'),
(95, 'Mise à jour des prix', 'Update the prices', 'Actualizar os preços'),
(96, 'Modif.', 'Modification', 'Modif.'),
(97, 'Mot de passe', 'Password', 'Palavra passe'),
(98, 'Nature', 'Nature', 'Natureza'),
(99, 'Nature de pertes', 'Nature of the losses', 'Natureza de perdas'),
(100, 'Nature des declassements', 'Nature of declassificaiton', 'Natureza das desclassificações'),
(101, 'Nature perte', 'Nature of the loss', 'Natureza de perdas'),
(102, 'Niveau central', 'Central level', 'Nível central'),
(103, 'Nom', 'Name', 'Nome'),
(104, 'Nom d''utilisateur', 'Username', 'Nome do utilizador'),
(105, 'Numero matricule', 'User Registration number', 'Numero de registo pessoal'),
(106, 'Paramètrage', 'Settings', 'Parametragem'),
(107, 'Paramètres généraux', 'General Settings', 'Parametragem geral'),
(108, 'Paramètres personnel', 'Personal settings', 'Parâmetros pessoal'),
(109, 'Personne', 'Person', 'Pessoa'),
(110, 'Personnel', 'Staff/Personnel', 'Pessoal'),
(111, 'Pertes', 'Losses', 'Perdas'),
(112, 'Pertes (Cas de produits périmés, détériorés, avariés, volés, cassés etc.)', 'Losses (refers to expired products, deteriorated, damaged, stolen, broken, etc…)', 'Perdas (Refere à produtos expirados, estragados, roubados, quebrados etc)'),
(113, 'Précédent', 'Previous', 'Precedente'),
(114, 'Première', 'First', 'Primeiro'),
(115, 'Prénoms', 'First Name', 'Primeiro nome'),
(116, 'Produit', 'Product', 'Produtos'),
(117, 'Produits à commander', 'Products to order', 'Produtos à requisitar'),
(118, 'Produits concernés', 'Products concerned', 'Produtos em causa'),
(119, 'Produits', 'Products', 'Produtos'),
(120, 'Province', 'Province', 'Provincia'),
(121, 'Rapport de consommation', 'Consumption report', 'Relatório de consumo'),
(122, 'Rapport de declassement', 'Disqualification report', 'Relatório de desclassificação'),
(123, 'Rapport de rupture de stock', 'Report of out of stock / Stock out Report', 'Relatório de rotura de stock'),
(124, 'Rapport détaillé des entrées', 'Detailed Report of entries', 'Relatório detalhado das entradas'),
(125, 'Rapport détaillé des sorties', 'Detailed Report of Issued', 'Relatório detalhado das saídas'),
(126, 'Rapport détaillé inventaire', 'Detailed Inventory Report', 'Relatório detalhado do inventário'),
(127, 'Rapport mensuel', 'Monthly report', 'Relatório mensal'),
(128, 'Rapport mouvement destinataires', 'Recipients activity report', 'Relatório movimento destinatários'),
(129, 'Rapport mouvement fournisseurs', 'Suppliers activity report', 'Relatório movimento fornecedores'),
(130, 'Rapport mouvement stock', 'Stock activity report', 'Relatório movimento stock'),
(131, 'Rapport peremption (Produits périmés)', 'Expiration date report (Expired products)', 'Relatório de expiração (Produtos expirados)'),
(132, 'Rapport pertes', 'Report of the losses', 'Relatório de perdas'),
(133, 'Rapport Produits à commander', 'Report of the Products to order', 'Relatório Produtos à requisitar'),
(134, 'Rapport stock actuel', 'Report Current stock', 'Relatório stock actual'),
(135, 'Rapport synthèse inventaire', 'Inventory Summary Report', 'Relatório síntese do inventário'),
(136, 'Rapport trimestriel', 'Quarterly Report', 'Relatório trimestral'),
(137, 'Rapports', 'Reports', 'Relatórios'),
(138, 'Rapports divers', 'Various reports', 'Relatórios diversos'),
(139, 'Rechercher', 'Search', 'Procurar'),
(140, 'Ref. Commande', 'Reference Purchase Order', 'Ref. Requisição'),
(141, 'Ref. Livraison', 'Reference Delivery', 'Ref. Entrega'),
(142, 'Région', 'Region', 'Região'),
(143, 'Reports', 'Reports', 'Balanços'),
(144, 'Reports', 'Reports', 'Balanços'),
(145, 'Reports (Clôture d''exercice et report de stock courant dans l''exercice suivant)', 'Reports (closing the year and current stock report/balance for the nex exercise year)', 'Balanços (Fecho do exercício e balanço de actual stock para o exercçio seguinte'),
(146, 'root [Déconnexion]', 'root [Logout]', 'root (Desconexão)'),
(147, 'Saisie d''inventaires', 'Entering Inventories', 'Introduzir inventários'),
(148, 'Sauvegarde de la base', 'Save Database', 'Guardar a base de dados'),
(149, 'Sélectionner le site bénéficiaire', 'Select the beneficiary Site', 'Selecionar o sítio beneficiário'),
(150, 'Sélectionner le site fournisseur', 'Select the supplier site ', 'Selecionar o sítio fornecedor'),
(151, 'Service', 'Service', 'Serviço'),
(152, 'site', 'site', 'Sítio'),
(153, 'Site bénéficiaire', 'Beneficiary site', 'Sítio beneficiário'),
(154, 'Site fournisseur', 'Supplier site', 'Sítio fornecedor'),
(155, 'Sorties de produits', 'Products Issued', 'Saídas de produtos'),
(156, 'Sorties mensuelles', 'Monthly Issued', 'Saídas mensais'),
(157, 'Sous catégories', 'Subcategories', 'Sub-categorias'),
(158, 'Sous groupe', 'Subgroup', 'Sub-grupo'),
(159, 'Sous-Catégorie', 'Sub-Category', 'Sub-categoria'),
(160, 'Sous-catégorie de produits concernée', 'Subcategory of the concerned products ', 'Sub-categoria de produtos em causa'),
(161, 'Sous-groupe', 'Subgroup', 'Sub-grupo'),
(162, 'Suivante', 'Next', 'Seguinte'),
(163, 'Suppr.', 'Del.', 'Supr.'),
(164, 'Supprimer', 'Remove', 'Suprimir'),
(165, 'Téléphone', 'Telephone', 'Telefone'),
(166, 'Transferts', 'Transfers', 'Transferência'),
(167, 'Transferts de produits', 'Product transfers', 'Transferência de produtos '),
(168, 'Transferts (Transfert de produits d''un magasin A vers B)', 'Transfers (Transfer of products from warehouse A to B)', 'Transferências (Transferência de produtos de um armazém A para B)'),
(169, 'Type bénéficiaire', 'Type of beneficiary', 'Tipo de beneficiário'),
(170, 'Type de bénéficiaire', 'Type of beneficiary', 'Tipo de beneficiário'),
(171, 'Type de compte (Groupe)', 'Account type (Group)', 'Tipo de conta (Grupo)'),
(172, 'Type de dotation', 'Type of allocation', 'Tipo de dotação'),
(173, 'Type de fournisseur', 'Type of supplier', 'Tipo de fornecedor'),
(174, 'Type de service', 'Type of service', 'Tipo de serviço'),
(175, 'Type fournisseur', 'Supplier type', 'Tipo fornecedor'),
(176, 'Unite de mesure', 'Unit of measure', 'Unidade de medida'),
(177, 'Utilisateur', 'User', 'Utilizador'),
(178, 'Utilisateurs & groupes', 'Users & Groups', 'Utilizadores e grupos'),
(179, 'Valid.', 'Valid', 'Valid.'),
(180, 'Valider', 'Validate', 'Validar'),
(181, 'Vider la base de données', 'Empty database', 'Esvaziar a base de dados'),
(182, 'Visible', 'Visible', 'Visível'),
(183, 'Votre identité', 'Your identity', 'Sua identidade'),
(184, 'Vous n''avez pas encore sélectionner le site bénéficiaire. N''oubliez pas de le faire', 'You have not selected the beneficiary site! Don''t forget to do so.', 'Ainda não selecionastes o sítio beneficiário! Não se esqueça de o fazer.'),
(185, 'Bienvenue sur', 'Welcome', 'Benvindo à'),
(186, 'Affichage de', ' Data display (view)', 'Mostragem de '),
(187, 'Imprimer', 'Print', 'Imprimir'),
(188, 'Ficher Excel', 'Excel file', 'Ficheiro Excel'),
(189, 'Enregistrer les données', 'Save Data', 'Registar os dados'),
(190, 'Enregistrer', 'Save', 'Registar'),
(191, 'Ajouter une ligne', 'Add Line', 'Adicionar uma linha'),
(192, 'Suivant >>', 'Next >>', 'Seguinte >>'),
(193, 'Rétablir', 'Restore', 'Restaurar'),
(194, 'Enregistrer le compte', 'Save account', 'Registar a conta'),
(195, 'Journal', 'Log', 'Histórico'),
(196, 'Afficher Etat', 'View status', 'Mostrar Situação'),
(197, 'Détails livraison', 'Delivery details', 'Detalhes de entrega'),
(198, 'Rapport ->Rapport de péremption', 'Report ->Expiry Report', 'Relatório->Relatório de expiração'),
(199, 'Libellé produit', 'Product description', 'Designação do produto'),
(200, 'Quantité', 'Quantity', 'Quantidade'),
(201, 'Date péremption', 'Expiration date', 'Data de expiração'),
(202, 'Date entrée', 'Date entry', 'Data de entrada'),
(203, 'Date de sortie', 'Release/Issued date', 'Data de saída'),
(204, 'Unité', 'Unit', 'Unidade'),
(205, 'Prix unitaire', 'Unit price', 'Preço unitário'),
(206, 'Nature', 'Nature', 'Natureza'),
(207, 'Qté entrée', 'Quantity entry', 'Qtd entrada'),
(208, 'Qté sortie', 'Quantity Issued', 'Qtd saída'),
(209, 'Stock en début de période', 'Stock at beginning of period', 'Stock no início do período'),
(210, 'Quantite reçue durant la période', 'Quantity received during the period', 'Quantidade recebida durante o período'),
(211, 'Quantité sortie durant la période', 'Quantity Issued during the period', 'Quantidade saída durante o período'),
(212, 'Solde', 'Balance', 'Saldo'),
(213, 'Magasin', 'Warehouse', 'Armazém'),
(214, 'Ligne', 'Line', 'Linha'),
(215, 'Signifie obligatoire', 'Denotes required', 'Obrigatório'),
(216, 'Cochez tous', 'Check all', 'Assinalar todos'),
(217, 'Annuler', 'Cancel', 'Anular'),
(218, 'Vider', 'Empty', 'Esvaziar'),
(219, 'Importer les données', 'Import the data', 'Importar dados'),
(220, 'Prix de vente', 'Sale price', 'Preço de venda'),
(221, 'Prix d''achat', 'Purchase price', 'Preço de compra'),
(222, 'Quantité', 'Quantity', 'Quantidade'),
(223, 'Consommation', 'Consumption', 'Consumo'),
(224, 'Montant', 'Amount', 'Montante'),
(225, 'Faire', 'Perform', 'Fazer'),
(226, 'D. péremption', 'D. Expiration', 'D. expiração'),
(227, 'Pour sélectionner plusieurs, appuyez sur Ctrl puis cliquez les éléments.', 'To select multiple, press Ctrl and click the items', 'Para selecionar vários, apoie sobre Ctrl e depois clique os elementos'),
(228, 'Rapports périodiques simples', 'Simple periodic reports', 'Relatórios periódicos simples'),
(229, 'Rapports périodiques consolidés', 'Consolidated periodic reports', 'Relatórios periódicos consolidados'),
(230, ' Par produit', 'By product', 'Por produtos'),
(231, 'Par lot', 'by Lot', 'Por lotes'),
(232, 'Tous les sites (National)', 'All the sites (National)', 'Todos os sítios (Nacional)'),
(233, 'Toutes les catégories', 'All the categories', 'Todas as categorias'),
(234, 'Toutes les sous-catégories', 'All the subcategories', 'Todas as sub-categorias'),
(235, 'Qté perdue', 'Quantity Lost', 'Qtd perdida'),
(236, 'Entrées', 'Entry', 'Entradas'),
(237, 'Sorties', 'Issued', 'Saídas'),
(238, 'Les entrées de stock constituent l''ensemble des opérations de livraisons liées à une commande ou de dons de produits. Il s''agit notamment de :', 'The entry of stock constitute all the operations of deliveries related to a purchase order or the donation of products. These include:', 'As entradas de stock constituem o conjunto das operações de entregas relacionadas com uma requisição ou de doações de produtos. Tratá-se nomeadamente de :'),
(239, ' Livraison (Entrée de produits suite à une commande)', 'Delivery (product entry based on a purchase order)', 'Entrega (Entrada de produtos após uma requisição)'),
(240, 'Dons (Dons de produits par une institution de la place ou l''Etat)', 'Donations (Donations of products by an Institution or the State)', 'Doações (Doações de produtos por uma Instituição ou o estado'),
(241, 'Lots (gestion des lots de produits et leur date de péremption)', 'Lots (management of the lots of the products and their expiration date)', 'Lotes (gestão dos lotes de produtos e suas datas de expiração'),
(242, 'etc.', 'etc…', 'etc.'),
(243, 'Les entrées de stocks', 'The entry of stocks', 'As entradas de stocks'),
(244, 'Les Commandes', 'The purchase order', 'As requisições'),
(245, 'Détails', 'Details', 'Detalhes'),
(246, 'Les Livraisons', 'The deliveries', 'As entregas'),
(247, 'Annuler validation', 'Cancel validation', 'Anular validação'),
(248, 'Détails commande', 'Commands details', 'Detalhes requisição'),
(249, 'reçue', 'Received', 'Recebida'),
(250, 'Détails livraison', 'Delivery details', 'Detalhes entrega'),
(251, 'Ligne des mouvements', 'Line of movement', 'Linha das movimentações'),
(252, 'Livraison validée', 'Delivery validated', 'Entrega validade'),
(253, 'Date transfert', 'Transfer date', 'Data de transferência'),
(254, 'Transfert validé', 'Transfer validated', 'Transferência validada'),
(255, 'Libellé pour l''état imprimable', 'Description for the state of printables', 'Designação para o estado de imprimíveis'),
(256, 'Ligne des transferts', 'Line transfers', 'Linha de transferências'),
(257, 'Code produit', 'Product Code', 'Codigo produto'),
(258, 'Niveau bénéficiaire', 'Beneficiary level', 'Nível beneficiário'),
(259, 'Niveau fournisseur', 'Supplier level', 'Nível fornecedor'),
(260, 'Seuil minimum', 'Minimum threshold', 'Limiar mínimo'),
(261, 'Seuil maximum', 'Maximum threshold', 'Limiar maximo'),
(262, 'Prix de revient', 'Cost price', 'Preço de armazém'),
(263, 'L''exercice budgétaire est', 'The financial year is', 'Exercício orçamental é '),
(264, 'Pour changer, veuillez sélectionner un exercice budgétaire dans la liste ci-dessous. . .', 'To change, please select a fiscal year from the list below. . .', 'Para alterar, queira selecionar um ano de exercício orçamental na lista abaixo …'),
(265, 'Référence de sortie', 'Output Reference', 'Referência de saída'),
(266, 'Ajouter une sortie (Etape n°1)', 'Add an Issue (Step 1)', 'Adicionar uma saída (Etapa n°1)'),
(267, 'Les sorties de stocks -> Les sorties pour consommation', 'Inventory Issued -> The issued for consumption', 'As saídas de stocks -> As saídas para consumo'),
(268, 'Ajouter une sortie (Etape n°2)', 'Add an Issue (Step 2)', 'Adicionar uma saída (Etapa n°2)'),
(269, 'Sortie validée', 'Validated output', 'Saída validada'),
(270, 'Détails des sorties pour consommation', 'Details of the issued for consumption', 'Detalhes das saídas para consumo'),
(271, 'Les sorties de stock -> Les pertes', 'Issued Stock -> Losses', 'As saídas de stock -> As perdas'),
(272, 'Ajouter une perte (Etape n°1)', 'Add a loss (Step 1)', 'Adicionar uma perda (Etapa n°1)'),
(273, 'Date perte', 'Date of the loss', 'Data de perda'),
(274, 'Référence perte', 'Reference of the Loss', 'Referência perda'),
(275, 'Ref. rapport d''expertise', 'Ref. Report of the Loss', 'Ref. relatório de peritos'),
(276, 'Cabinet d''expertise', 'Experts Office', 'Escritório de peritos'),
(277, 'Ajouter une perte (Etape n°2)', 'Add a loss (Step 2)', 'Adicionar uma perda (Etapa n°2)'),
(278, 'Raison perte', 'Reason for loss', 'Razão da perda'),
(279, 'Perte validée', 'Loss validated', 'Perda validada'),
(280, 'Ligne des pertes', 'Line for losses', 'Linha de perdas'),
(281, 'Modifier les pertes', 'Edit losses', 'Modificar as perdas'),
(282, 'Modifier une Consommation', 'Modifying a Consumption', 'Modificar um consumo'),
(283, 'Faire un inventaire (Etape n°1)', 'Perfom inventory (Step 1)', 'Fazer um inventário (Etapa n°1) '),
(284, 'Libellé inventaire', 'Description of inventory', 'Designação inventário'),
(285, 'Faire un inventaire (Etape n°2)', 'Perform inventory (Step 2)', 'Fazer um inventário (Etapa n°2) '),
(286, 'Ref. Inventaire', 'Ref. inventory', 'Ref. inventário'),
(287, 'Ligne de produits inventoriés', 'Line of the products inventoried', 'Linha de produtos inventariados'),
(288, 'Qté théorique', 'Theoretical Qty', 'Qtd teórica'),
(289, 'Qté physique', 'Physical Qty', 'Qtd física'),
(290, 'Imprimer une fiche d''inventaire', 'Print an inventory sheet', 'Imprimir uma ficha de inventário'),
(291, 'Date inventaire', 'Inventory Date', 'Data de inventário'),
(292, 'Modifier un inventaire', 'Edit an inventory', 'Modificar um inventário'),
(293, ' Inventaire -> Etat du stock par produits', 'Inventory -> State of the stock by products', 'Inventário ->Situação de stock por produtos'),
(294, 'Faire l''état du stock par produits', 'Conduct the status of the stock by product', 'Fazer a situação de stock por produtos'),
(295, 'Transfert', 'transfer', 'Transferência'),
(296, 'Livraison', 'Delivery', 'Entrega'),
(297, 'Périmé', 'Expired ', 'Expirado'),
(298, 'Inventaire', 'Inventory', 'Inventário'),
(299, 'Inventaire -> Etat du stock par lot', 'Inventory -> State of the stock by Lot', 'Inventário -> Situação do stock por lote'),
(300, 'Inventaire -> Etat du stock', 'Inventory -> State of the stock', 'Inventário -> situação do stock '),
(301, 'Faire l''état du stock par ref. lot de produits', 'Create the satus of the stock by reference of the product lot', 'Fazer a situação de stock por ref. lote de produtos'),
(302, 'Faire le journal des mouvements', 'Create log of movements', 'Fazer o histórico dos movimentos'),
(303, 'Inventaire -> Etat des mouvements', 'Inventory -> State of movements', 'Inventário -> Situação dos movimentos'),
(304, 'Nature mouvement', 'Nature of the movement', 'Natureza movimento'),
(305, 'Heure', 'Time', 'Hora'),
(306, 'Minimum', 'Minimum', 'Mínimo'),
(307, 'Maximum', 'Maximum', 'maximo'),
(308, 'Mois de stock disponible (MSD)', 'Months of stock available (MSD)', 'Meses de estoque disponível (MSD)'),
(309, 'Qté à commander', 'Qty to order', 'Qtd a requisitar'),
(310, 'Rapport -> Rapport produits à commander', 'Report -> Report Products to order', 'Relatório -> Relatório produtos a requisitar'),
(311, 'Qté finale', 'Qty final', 'Qtd final'),
(312, ' Rapports -> Rapport péremption (produits périmés)', 'Reports -> Report Expiry (expired products)', 'Relatórios -> Relatório expiração (produtos expirados)'),
(313, 'Rapports -> Rapport stock actuel', 'Reports -> current stock report', 'Relatórios -> Relatório stock actual'),
(314, 'Nbre jours', 'Number of days', 'Número dias'),
(315, 'Nbre mois', 'Number of months', 'Número meses'),
(316, 'Nbre semaines', 'Number weeks', 'Número semanas'),
(317, 'Rapports ->Rapport rupture de stock', 'Reports -> Report out of stock/stock out', 'Relatórios -> Relatório rotura de stock'),
(318, 'Inventaire validé', 'Inventory validated', 'Inventário validado'),
(319, 'Fiche d''inventaire', 'Inventory sheet', 'Ficha de inventário'),
(320, 'Cliquer pour télécharger le document', 'Click to download the document', 'Clicar para baixar o documento'),
(321, 'Télécharger le fichier Excel', 'Download the Excel file', 'Baixar o ficheiro Excel'),
(322, 'Qté disponible', 'Available Qty', 'Qtd disponível'),
(323, 'Qté périmée', 'Expired Qty', 'Qtd expirada'),
(326, 'Vous n''avez pas encore sélectionner le site fournisseur. N''oubliez pas de le faire', 'You have not selected the beneficiary site! Don''t forget to do so.', 'Ainda não selecionastes o sítio fornecedor. Não se esqueça de o fazer.'),
(327, 'Cette opération importe les données des tables cochées dans la base de données', 'This operation imports the data on the tables that are checked in the database', 'Esta operação importa os dados das tabelas assinaladas na base de dados'),
(328, 'Sélectionnez le fichier à importer', 'Select the file to import', 'Selecione o ficheiro a importar'),
(329, 'Serveur de base de données', 'Database server', 'Servidor de base de dados'),
(330, 'Base de données', 'Database', 'Base de dados'),
(331, 'Administrateur', 'Administrator', 'Administrador'),
(332, 'Fichier à importer', 'File to import', 'Ficheiro a importar'),
(333, 'Importer les données dans la base de données', 'Import the data into the database', 'Importar os dados na base de dados'),
(334, 'Sauvegarder la base de données', 'Save the database', 'Guardar a base de dados'),
(335, 'Cette opération exporte les données des tables cochées dans un fichier texte', 'This operation exports the data on the tables that have been checked into a text file', 'Esta operação exporta os dados das tabelas assinaladas num ficheiro detexto'),
(336, 'Mouvement de stocks', 'Stock Movement', 'Movimento de stocks'),
(337, 'Information générale', 'General Information', 'Informação geral'),
(338, 'Exercice', 'Fiscal year', 'Exercício fiscal'),
(339, 'Exporter les données', 'Export Data', 'Exportar os dados'),
(340, 'Editer les paramètres généraux', 'Edit general settings', 'Editar os parâmetros gerais'),
(341, 'Etats et imprimables (Entête)', 'Status and printables (header)', 'Situação e impressos (Cabeçalho)'),
(342, 'Etats et imprimables (Pied de page)', 'Status and printable (Footer)', 'Situação e impressos (Rodapé)'),
(343, 'Pays', 'Country', 'País'),
(344, 'Ville', 'City', 'Cidade'),
(345, 'Devise du pays', 'Country currency', 'Insignia do país'),
(346, 'Ce module vous permet de générer les divers rapports et de les imprimer', 'This module allows you to generate various reports and print them', 'Este modulo permite de gerar os diferentes relatórios e de os imprimir'),
(347, 'Le paramétrage du logiciel est indispensable au bon fonctionnement des différents modules', 'The software setting is crucial to the functioning of the various modules', 'A parametragem do software é indispensável para o bom funcionamento dos diferentes modulos'),
(348, 'Cette rubrique est dédiée à cette tâche', 'This section is dedicated to this task', 'Esta rubrica é dedicada a esta tarefa'),
(349, 'Ce module vous permet de faire l''inventaire physique du stock et éditer les états de stock', 'This module allows you to do the physical inventory of the stock and edit the status of the stock', 'Este modulo permite realizar o inventário físico do stock e editar as situações de stock'),
(350, 'Les sorties de stock constituent l''ensemble des opérations de retrait sur le stock. Il s''agit notamment de', 'The exit of stocks constitute all the operations of removing the stock, these include:', 'As saídas de stock constituem o conjunto das operações de retirar do stock, Tratá-se nomeadamente de:'),
(351, 'Consommations (Dotation d''un service ou un individu)', 'Consumption (allocation of a service or individual)', 'Consumos (Dotação de um serviço ou de um individuo)'),
(352, 'Pertes (Cas de produits périmés, détériorés, avariés, volés, cassés etc.)', 'Losses (refers to expired products, deteriorated, damaged, stolen, broken, etc…)', 'Perdas (Refere à produtos expirados, estragados, roubados, quebrados etc)'),
(353, 'Transferts (Transfert de produits d''un magasin A vers B)', 'Transfers (Transfer of products from warehouse A to B)', 'Transferências (Transferência de produtos de um armazém A para B)'),
(354, 'Reports (Clôture d''exercice et report de stock courant dans l''exercice suivant)', 'Reports (closing the year and current stock report/balance for the nex exercise year)', 'Balanços (Fecho do exercício e balanço de actual stock para o exercçio seguinte'),
(355, 'Liste des inventaires', 'List of inventories', 'Lista dos inventários'),
(356, 'Liste des bons de sortie', 'List of issued log', 'Lista das guias de saída'),
(357, 'Libellé de sortie', 'Description of issued', 'Designação de saída'),
(358, 'Liste des transferts', 'List of transfers', 'Lista das transferências'),
(359, 'Liste des pertes', 'List of losses', 'Lista das perdas'),
(360, 'Liste des reports', 'List of reports', 'Lista dos relatórios'),
(361, 'Liste des commandes', 'List of Purchase Order', 'Lista das requisições'),
(362, 'Liste des livraisons', 'List of supplies', 'Lista das entregas'),
(363, 'Rapport des entrées', 'Report of the entries', 'Relatórios das entradas'),
(364, 'Rapport mouvement destinataires', 'Report recepient movements', 'Relatório movimento destinatários'),
(365, 'Ajouter une livraison (Etape n°1)', 'Add delivery (Step 1)', 'Adicionar uma entrega (Etapa n°1)'),
(366, 'Ajouter une livraison (Etape n°2)', 'Add delivery (Step 2)', 'Adicionar uma entrega (Etapa n°2)'),
(367, 'Qté cdée', 'Qty ordered', 'Qtd requisitada'),
(368, 'Qté livr.', 'Qty delivered', 'Qtd entregue'),
(369, 'Editer une livraison', 'Edit delivery', 'Editar uma entrega'),
(370, 'Les données ont été supprimées', 'The data were removed', 'Os dados foram suprimidos'),
(371, 'Les données ont été validées', 'The data have been validated', 'Os dados foram validados'),
(372, 'Les données ont été modifiées avec succès', 'Data were successfully modified', 'Os dados foram modificados com sucesso'),
(373, 'Les données ont été ajoutées avec succès', 'The data has been added successfully', 'Os dados foram adicionados com sucesso'),
(374, 'Une erreur s''est produite', 'An error occurred', 'Ocorreu um erro'),
(375, 'Ajouter un transfert (Etape n°1)', 'Add a transfer (Step 1)', 'Adicionar uma transferência (Etap n°1)'),
(376, 'Ajouter un transfert (Etape n°2)', 'Add a transfer (Step 2)', 'Adicionar uma transferência (Etap n°2)'),
(377, 'Réservé au gestion du stock', 'Reserved for stock management', 'Reservado para gestão do stock'),
(378, 'Réservé au preneur', 'Reserved to the lessee', 'Reservado para o locatário'),
(380, 'Ajouter un report (Etape n°2)', 'Add a report (Step 2)', 'Adicionar um balanço (Etapa n°2)'),
(381, 'Ajouter un report (Etape n°1)', 'Add a report (Step 1)', 'Adicionar um balanço (Etapa n°1)'),
(382, 'Liste des catégories', 'Categories list', 'Lista das categorias'),
(383, 'Liste des sous-catégories', 'List of subcategories', 'Lista das sub-categorias'),
(384, 'Traceur / non traceur', 'Trace / not to trace', 'Seguir o rasto/Não seguir o rasto'),
(385, 'Liste des produits', 'Product List', 'Lista dos produtos'),
(386, 'Mise à jour des prix des produits', 'Update products prices', 'Actualizar os preços dos produtos'),
(387, 'Les unités de mesure', 'Units of measurement', 'As unidades de medida'),
(388, 'Sous groupes', 'Subgroups', 'Sub-grupos'),
(389, 'Liste des sous-groupes', 'List of subgroups', 'Lista dos sub-grupos'),
(390, 'Liste des sites de niveau central', 'List of central sites', 'Lista dos sítios do nível central'),
(391, 'Liste des sites fournisseurs', 'List of supplier sites', 'Lista dos sítios fornecedores'),
(392, 'Liste des sites bénéficiaires', 'List of beneficiaries sites', 'Lista dos sítios beneficiários'),
(393, 'Liste des types de fournisseurs', 'List of types of suppliers', 'Lista dos tipos de fornecedoes'),
(394, 'Liste des types de bénéficiaires', 'List of types of beneficiaries', 'Lista dos tipos de beneficiários'),
(395, 'Liste des fournisseurs', 'List of Suppliers', 'Lista dos fornecedores'),
(396, 'Responsable', 'Responsible person', 'Pessoa Responsável'),
(397, 'Liste des bénéficiaires', 'List of beneficiaries', 'Lista de beneficiários'),
(398, 'Date de début', 'Start date', 'Data de início'),
(399, 'Date de fin', 'End date', 'Data de fim'),
(400, 'Date de clôture', 'Closing date', 'Data de fecho'),
(401, 'En cours', 'Ongoing', 'Em curso'),
(402, 'Jours restants', 'Days left', 'Dias restantes'),
(403, 'Liste des exercices budgétaires', 'List of Financial years', 'lista dos exercícios orçamentais'),
(404, 'Cette action va supprimer toutes les données de la base de données', 'This action will delete all data from the database', 'Esta acção suprimirá todos os dados da base de dados'),
(405, 'Ajouter une commande (Etape n°1)', 'Add a command (Step 1)', 'Adicionar uma requisição (Etap n°1)'),
(406, 'Ajouter une commande (Etape n°2)', 'Add a command (Step 2)', 'Adicionar uma requisição (Etap n°2)'),
(407, 'Produit au seuil de péremption', 'Product within the limit of expiration', 'Produto no limite de expiração'),
(408, 'Stock sup. seuil max.', 'Stock sup. Maximum', 'Stock sup. À limite máximo'),
(409, 'Fiche de stock produit', 'Product Stock Sheet', 'Ficha de stock de produtos'),
(410, 'Historique', 'Historical', 'Histórico'),
(411, 'Rapports -> Rapport de produits à date de péremption proche', 'Reports -> Report of Products with expiration date approaching', 'Relatórios-> Relatório de produtos com data de expiração próxima'),
(412, 'Faire le Rapport des produits proche de la péremtion', 'Create a report of products with the expirations date approaching', 'Fazer o relatório dos produtos com data de expiração próxima'),
(413, 'Rapports -> Rapport fiche de stock produit', 'Report -> Report Product Stock Sheet', 'Relatórios -> Relatório ficha de stock de produto'),
(414, 'Faire l''état fiche de stock produit', 'Create the Product Stock Sheet', 'Situação ficha de stock de produto'),
(415, 'Rapports -> Rapport de stock supérieur au seuil maximum', 'Reports -> Report of stock exceeding the ceiling', 'Relatórios -> Relatório de stock superior ao limite máximo'),
(416, 'Faire le Rapport de stock supérieur au seuil maximum', 'Create a Stock Report that are superior to the maximim limit', 'Fazer o relatório e stock superior ao limite máximo'),
(417, 'Rapports ->Rapport rupture de stock', 'Report ->Out of Stock Report', 'Relatórios ->Relatório de rotura de stock'),
(418, 'Faire le Rapport Rapport rupture de stock', 'Create Out of Stock Report', 'Fazer o relatório de rotura de stock'),
(419, 'Rapports -> Rapport de stock supérieur au seuil maximum', 'Reports -> Report of stock exceeding the ceiling', 'Relatórios ->Relatório de stock superior ao limite máximo'),
(420, 'Faire le Rapport de stock supérieur au seuil maximum', 'Create a Stock Report that are superior to the maximim limit', 'Fazer o Relatório de stock superior ao limite máximo'),
(421, 'Aucune donnée', 'No Data', 'Nenhum dado'),
(422, 'Rapports -> Rapport de produits à date de péremption', 'Reports -> Report of Products with expiration date ', 'Relatórios -> Relatório de produtos à data de expiração'),
(423, 'Faire le Rapport des produits de la péremtion', 'Create a report of expired producs', 'Fazer o relatório dos produtos expirarados'),
(424, 'Ce module est dédié à la gestion de la base de données.', 'This module is dedicated for database management', 'Este modulo diz respeito à gestão da base de dados'),
(425, 'Il consiste à', 'It consists in', 'Consiste em'),
(426, 'Sélectionner ', 'Select', 'Selecionar o'),
(427, 'Paramétrage -> Affectation des utilisateurs/sites', 'Settings -> Assign Users/sites', 'Parametragem -> Afetação dos usuários/sitios'),
(428, 'Paramétrage -> Les affectations', 'Settings -> Assignments', 'Parametragem -> As afetações'),
(429, 'Modifier une affectation', 'Edit an assignment', 'Alterar uma afetação'),
(430, 'Modifier un site bénéficiaire', 'Modify a beneficiary site', 'Alterar um sítio beneficiário'),
(431, 'Modifier un produit', 'Modify a product', 'Alterar um produto'),
(432, 'Consolidation la base de données', 'Consolidation of the database', 'Consolidação da base de dados'),
(433, 'Exporter la base de données', 'Export the database', 'Exportar a base de dados'),
(434, 'Importer la base de données', 'Import the database', 'Importar a base de dados'),
(435, 'Résultat', 'Result', 'Resultado'),
(436, 'Stock actuel', 'Current Stock', 'Stock atual'),
(437, 'Fiche produit', 'Product Sheet', 'Fichade  produto'),
(438, 'Prix Site fournisseur', 'Suppliers Price', 'Preço no fornecedor'),
(439, 'Prix Site bénéficiaire', 'Beneficiary Price', 'Preço no beneficiário'),
(440, 'Modifier', 'Modify', 'Alterar'),
(441, 'Modifier une commande', 'Modify a Requisition', 'Alterar uma requisição'),
(442, 'Ajouter un transfert (Etape n°3)', 'Add a transfer (Step 3)', 'Adicionar uma transferência (Etapa n°3)'),
(443, 'Faire l''état du rapport consolidé', 'Create the consolidated statement report', 'Situação do relatório consolidado'),
(444, 'Faire l''état du rapport simple', 'Create the simple statement report', 'Situação do relatório simples'),
(445, 'Exercice clôturé', 'Close Fiscal Year', 'Ano fechado'),
(446, 'Date de report', 'Date of the Report', 'Data do relatório'),
(447, 'Paramétrage -> Les exercices budgétaire', 'Settings -> Budget Exercise', 'Parametragem -> Exercícios orçamentais'),
(448, 'Paramétrage -> Les responsables de magasin', 'Settings -> Warehouse managers', 'Parametragem -> Responsáveis do armazem'),
(449, 'Modifier un responsable', 'Modify the Manager', 'Alterar um responsável'),
(450, 'Paramétrage -> Les utilisateurs', 'Setup -> Users', 'Configuração -> Usuários'),
(451, 'CLIENT', 'CUSTOMER', 'CLIENTE'),
(452, 'Nom abrégé', 'short Name', 'Nome Curto'),
(453, 'Ajouter une affectation', 'Add assignment', 'Adicionar atribuição'),
(454, 'Profil menu', 'menu Profile', 'perfil Menu'),
(455, 'Eccart', 'Difference', 'Diferença'),
(456, 'Stock en magasin', 'In store', 'Na loja'),
(457, 'Autorisation', 'Authorization', 'Autorização'),
(458, 'Modifier le profil', 'Edit Profile', 'Editar Perfil'),
(459, 'Fonction signataire', 'signatory function', 'signatário Função'),
(460, 'Langue', 'Language', 'Língua'),
(461, 'Activé', 'Enabled', 'Ativado'),
(462, 'Désactivé', 'Disabled', 'Inválido'),
(463, 'Fin des importations', 'End of imports', 'Fim das importações'),
(464, 'abrégé', 'short', 'curto');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `fournisseur`
--

CREATE TABLE IF NOT EXISTS `fournisseur` (
  `CODE_FOUR` varchar(10) NOT NULL,
  `CODE_TYPEFOUR` varchar(10) NOT NULL,
  `FOUR_NOM` varchar(200) DEFAULT NULL,
  `FOUR_TEL` varchar(30) DEFAULT NULL,
  `FOUR_ADRESSE` varchar(200) DEFAULT NULL,
  `FOUR_EMAIL` varchar(100) DEFAULT NULL,
  `FOUR_RESPONSABLE` varchar(100) DEFAULT NULL,
  `FOUR_RESPTEL` varchar(30) DEFAULT NULL,
  `FOUR_RESPEMAIL` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`CODE_FOUR`),
  KEY `TYPEFOUR_FOUR_FK` (`CODE_TYPEFOUR`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `infogenerale`
--

CREATE TABLE IF NOT EXISTS `infogenerale` (
  `CODE_INFGLE` varchar(10) NOT NULL,
  `CODE_MAGASIN` varchar(10) NOT NULL,
  `ID` int(11) NOT NULL,
  `INF_CLIENT` varchar(250) DEFAULT NULL,
  `INF_DATEACQ` date DEFAULT NULL,
  `INF_LICENCE` varchar(50) DEFAULT NULL,
  `INF_MINISTERE` text,
  `INF_SECRETARIAT` text,
  `INF_DIRECTION` text,
  `INF_SERVICE` text,
  `INF_CSPS` text NOT NULL,
  `INF_PAYS` varchar(50) DEFAULT NULL,
  `INF_DEVISE` varchar(100) DEFAULT NULL,
  `INF_VILLE` varchar(50) DEFAULT NULL,
  `LOGO` varchar(50) NOT NULL,
  `INF_SIGNATEUR1` varchar(250) DEFAULT NULL,
  `INF_NOMSIGNATEUR1` varchar(250) DEFAULT NULL,
  `INF_SIGNATEUR2` varchar(250) DEFAULT NULL,
  `INF_NOMSIGNATEUR2` varchar(250) DEFAULT NULL,
  `INF_SIGNATEUR3` varchar(250) DEFAULT NULL,
  `INF_NOMSIGNATEUR3` varchar(250) DEFAULT NULL,
  `INF_SIGNATEUR4` varchar(250) DEFAULT NULL,
  `INF_NOMSIGNATEUR4` varchar(250) DEFAULT NULL,
  `INF_VALIDAUTO` tinyint(4) DEFAULT NULL,
  `INF_MAGASIN` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`CODE_INFGLE`),
  KEY `MAG_INFOGLE_FK` (`CODE_MAGASIN`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `inventaire`
--

CREATE TABLE IF NOT EXISTS `inventaire` (
  `CODE_INVENTAIRE` varchar(30) NOT NULL,
  `CODE_MAGASIN` varchar(10) NOT NULL,
  `ID_EXERCICE` int(11) NOT NULL,
  `REF_INVENTAIRE` varchar(100) DEFAULT NULL,
  `ID_INVENTAIRE` int(11) NOT NULL,
  `INV_LIBELLE` varchar(250) DEFAULT NULL,
  `INV_DATE` date DEFAULT NULL,
  `INV_VALID` tinyint(4) DEFAULT NULL,
  `INV_DATEVALID` datetime DEFAULT NULL,
  PRIMARY KEY (`CODE_INVENTAIRE`),
  KEY `MAG_INVENTAIRE_FK` (`CODE_MAGASIN`),
  KEY `EXERCICE_INV_FK` (`ID_EXERCICE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `langue`
--

CREATE TABLE IF NOT EXISTS `langue` (
  `idlangue` int(1) NOT NULL,
  `langue` varchar(12) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `langue`
--

INSERT INTO `langue` (`idlangue`, `langue`) VALUES
(1, 'Francais'),
(3, 'Portugais'),
(2, 'English');

-- --------------------------------------------------------

--
-- Structure de la table `livraison`
--

CREATE TABLE IF NOT EXISTS `livraison` (
  `CODE_LIVRAISON` varchar(30) NOT NULL,
  `CODE_COMMANDE` varchar(30) DEFAULT NULL,
  `CODE_FOUR` varchar(10) NOT NULL,
  `CODE_MAGASIN` varchar(10) NOT NULL,
  `ID_EXERCICE` int(11) NOT NULL,
  `REF_LIVRAISON` varchar(100) DEFAULT NULL,
  `ID_LIVRAISON` int(11) NOT NULL,
  `LVR_LIBELLE` varchar(250) DEFAULT NULL,
  `LVR_DATE` date DEFAULT NULL,
  `LVR_VALIDE` tinyint(4) DEFAULT NULL,
  `LVR_DATEVALID` datetime DEFAULT NULL,
  PRIMARY KEY (`CODE_LIVRAISON`),
  KEY `EXERCICE_LIVR_FK` (`ID_EXERCICE`),
  KEY `CDE_LIVRAISON_FK` (`CODE_COMMANDE`),
  KEY `MAG_LIVRAISON_FK` (`CODE_MAGASIN`),
  KEY `FOUR_LIVRAISON_FK` (`CODE_FOUR`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `logs`
--

CREATE TABLE IF NOT EXISTS `logs` (
  `CODE_LOG` varchar(30) NOT NULL,
  `LOGIN` varchar(20) NOT NULL,
  `ID_LOG` int(11) NOT NULL,
  `LOG_DATE` datetime DEFAULT NULL,
  `LOG_DESCRIP` text,
  `MLLE` varchar(20) DEFAULT NULL,
  `CODE_MAGASIN` varchar(10) NOT NULL,
  PRIMARY KEY (`CODE_LOG`),
  KEY `COMPTE_LOGS_FK` (`LOGIN`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `logs`
--

INSERT INTO `logs` (`CODE_LOG`, `LOGIN`, `ID_LOG`, `LOG_DATE`, `LOG_DESCRIP`, `MLLE`, `CODE_MAGASIN`) VALUES
('1/', 'root', 1, '2015-07-16 08:25:33', 'Connexion au système', '0345Y', ''),
('1/0301', 'root', 1, '2015-07-16 08:25:27', 'Déconnexion du système échouée', '0345Y', '0301');

-- --------------------------------------------------------

--
-- Structure de la table `magasin`
--

CREATE TABLE IF NOT EXISTS `magasin` (
  `CODE_MAGASIN` varchar(10) NOT NULL,
  `IDPROVINCE` int(11) NOT NULL,
  `SER_NOM` varchar(100) DEFAULT NULL,
  `SER_EMAIL` varchar(50) DEFAULT NULL,
  `SER_TEL` varchar(30) DEFAULT NULL,
  `SER_VILLE` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`CODE_MAGASIN`),
  KEY `MAG_PROV_FK` (`IDPROVINCE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `magasin`
--

INSERT INTO `magasin` (`CODE_MAGASIN`, `IDPROVINCE`, `SER_NOM`, `SER_EMAIL`, `SER_TEL`, `SER_VILLE`) VALUES
('99999', 6, 'Channel', '', '', '');

-- --------------------------------------------------------

--
-- Structure de la table `mag_compte`
--

CREATE TABLE IF NOT EXISTS `mag_compte` (
  `LOGIN` varchar(20) NOT NULL,
  `CODE_MAGASIN` varchar(10) NOT NULL,
  PRIMARY KEY (`LOGIN`,`CODE_MAGASIN`),
  KEY `MAG_COMPTE_FK` (`LOGIN`),
  KEY `MAG_COMPTE2_FK` (`CODE_MAGASIN`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `IDMENU` varchar(10) NOT NULL,
  `LIBMENU` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`IDMENU`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `menu`
--

INSERT INTO `menu` (`IDMENU`, `LIBMENU`) VALUES
('aid', 'Menu -> Aide'),
('bde', 'Menu -> Entrée de produits'),
('bde_cde', ' -- Sous menu ->Commandes'),
('bde_liv', ' -- Sous menu ->Livraisons'),
('bds', 'Menu -> Sorties de produits'),
('bds_bds', ' -- Sous menu ->Consommations'),
('bds_dec', ' -- Sous menu ->Pertes'),
('bds_rep', ' -- Sous menu ->Reports'),
('bds_trf', ' -- Sous menu ->Transferts de produits'),
('data', 'Menu -> Base de données'),
('data_exp', ' -- Sous menu ->Sauvegarde de la base'),
('data_imp', ' -- Sous menu ->Importer de la base'),
('data_vid', ' -- Sous menu ->Vider la base de données'),
('int', 'Menu -> Inventaire'),
('int_int', ' -- Sous menu ->Inventaire de stock'),
('int_jou', ' -- Sous menu ->Journal des mouvements de stock'),
('int_pcd', ' -- Sous menu ->Imprimer Fiche d''inventaire'),
('int_stl', ' -- Sous menu ->Etat du stock par réf. Lots de produits'),
('int_sto', ' -- Sous menu ->Etat du stock par produit'),
('par', 'Menu -> Paramétrage'),
('par_aff', ' -- Sous menu ->Affectation des sites'),
('par_ben', ' -- Sous menu ->Bénéficiaires'),
('par_bud', ' -- Sous menu ->Exercice budgétaire'),
('par_cat', ' -- Sous menu ->Catégorie produits'),
('par_fou', ' -- Sous menu ->Fournisseurs'),
('par_gen', ' -- Sous menu ->Paramètres généraux'),
('par_grp', ' -- Sous menu ->Groupe d''utilisateurs'),
('par_log', ' -- Sous menu ->Logs utilisateurs'),
('par_mag', ' -- Sous menu ->Site bénéficiaires'),
('par_men', ' -- Sous menu ->Menu d''accès'),
('par_ndcl', ' -- Sous menu ->Nature des pertes'),
('par_per', ' -- Sous menu ->Personnel'),
('par_prd', ' -- Sous menu ->Produits'),
('par_prv', ' -- Sous menu ->Site fournisseurs'),
('par_reg', ' -- Sous menu ->Niveau central'),
('par_sscat', ' -- Sous menu ->Sous catégories'),
('par_ssg', ' -- Sous menu ->Sous groupes'),
('par_tfr', ' -- Sous menu ->Type de founisseurs'),
('par_tse', ' -- Sous menu ->Type de bénéficiaires'),
('par_uni', ' -- Sous menu ->Unité de mesure'),
('par_uti', ' -- Sous menu ->Compte utilisateurs'),
('rap', 'Menu -> Rapports'),
('rap_cons', ' -- Sous menu ->Rapport de consommation'),
('rap_dec', ' -- Sous menu ->Rapport des pertes'),
('rap_din', ' -- Sous menu ->Rapport détaillé des entrées'),
('rap_fprd', ' -- Sous menu ->Fiche de stock produit'),
('rap_mde', ' -- Sous menu ->Rapport mouvement  destinataires'),
('rap_men', ' -- Sous menu ->Rapport périodique simple'),
('rap_mfr', ' -- Sous menu ->Rapport mouvement  fournisseurs'),
('rap_mst', ' -- Sous menu ->Rapport mouvement stock'),
('rap_pac', ' -- Sous menu ->Rapport Produits à  commander'),
('rap_ppe', ' -- Sous menu ->Rapport péremption (Produits périmés)'),
('rap_prdp', ' -- Sous menu ->Produit au seuil de péremption'),
('rap_rds', ' -- Sous menu ->Rapport détaillé des sorties'),
('rap_rst', ' -- Sous menu ->Rapport de rupture de stock'),
('rap_sin', ' -- Sous menu ->Rapport synthèse inventaire'),
('rap_sssm', ' -- Sous menu ->Stock sup. seuil maximum'),
('rap_sta', ' -- Sous menu ->Rapport stock actuel'),
('rap_tri', ' -- Sous menu ->Rapport périodique consolidé');

-- --------------------------------------------------------

--
-- Structure de la table `mouvement`
--

CREATE TABLE IF NOT EXISTS `mouvement` (
  `CODE_MOUVEMENT` char(10) NOT NULL,
  `ID_EXERCICE` int(11) NOT NULL,
  `CODE_PRODUIT` varchar(20) NOT NULL,
  `CODE_MAGASIN` varchar(10) NOT NULL,
  `ID_MOUVEMENT` int(11) NOT NULL,
  `ID_SOURCE` varchar(30) DEFAULT NULL,
  `MVT_DATE` date DEFAULT NULL,
  `MVT_TIME` time DEFAULT NULL,
  `MVT_QUANTITE` int(11) DEFAULT NULL,
  `MVT_UNITE` varchar(10) DEFAULT NULL,
  `MVT_NATURE` varchar(30) DEFAULT NULL,
  `MVT_VALID` tinyint(4) DEFAULT NULL,
  `MVT_DATEVALID` datetime DEFAULT NULL,
  `MVT_TYPE` char(1) DEFAULT NULL,
  `MVT_REFLOT` varchar(30) DEFAULT NULL,
  `MVT_DATEPEREMP` date DEFAULT NULL,
  `MVT_PV` decimal(10,2) DEFAULT NULL,
  `MVT_PA` decimal(10,2) DEFAULT NULL,
  `MVT_PR` decimal(10,2) DEFAULT NULL,
  `MVT_MONLOT` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`CODE_MOUVEMENT`),
  KEY `PRD_MVT_FK` (`CODE_PRODUIT`),
  KEY `EXERCICE_MVT_FK` (`ID_EXERCICE`),
  KEY `MAG_MVT_FK` (`CODE_MAGASIN`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `natdeclass`
--

CREATE TABLE IF NOT EXISTS `natdeclass` (
  `CODENATDECLASS` varchar(15) NOT NULL,
  `LIBNATDECLASS` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`CODENATDECLASS`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `natdeclass`
--

INSERT INTO `natdeclass` (`CODENATDECLASS`, `LIBNATDECLASS`) VALUES
('AR', 'Autres raisons'),
('AVA', 'produits avariés'),
('CAS', 'produits cassés'),
('PER', 'Produits périmés'),
('VOL', 'Produits volés');

-- --------------------------------------------------------

--
-- Structure de la table `personnel`
--

CREATE TABLE IF NOT EXISTS `personnel` (
  `NUM_MLLE` varchar(10) NOT NULL,
  `CODE_MAGASIN` varchar(10) NOT NULL,
  `PERS_NOM` varchar(50) DEFAULT NULL,
  `PERS_PRENOMS` varchar(50) DEFAULT NULL,
  `PERS_TEL` varchar(30) DEFAULT NULL,
  `PERS_ADRESSE` varchar(100) DEFAULT NULL,
  `PERS_EMAIL` varchar(100) DEFAULT NULL,
  `PERS_FONCTION` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`NUM_MLLE`),
  KEY `SER_PERS_FK` (`CODE_MAGASIN`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `personnel`
--

INSERT INTO `personnel` (`NUM_MLLE`, `CODE_MAGASIN`, `PERS_NOM`, `PERS_PRENOMS`, `PERS_TEL`, `PERS_ADRESSE`, `PERS_EMAIL`, `PERS_FONCTION`) VALUES
('0345Y', '99999', 'M.', 'XXXXXX', 'XXXXXX', 'Ouaga', 'x@yahoo.fr', 'Développeur de logiciels');

-- --------------------------------------------------------

--
-- Structure de la table `prd_cde`
--

CREATE TABLE IF NOT EXISTS `prd_cde` (
  `CODE_COMMANDE` varchar(30) NOT NULL,
  `CODE_PRODUIT` varchar(20) NOT NULL,
  `CODE_MAGASIN` varchar(10) NOT NULL,
  `CDEPRD_QTE` int(11) DEFAULT NULL,
  `CDEPRD_PRIX` decimal(10,2) DEFAULT NULL,
  `CDEPRD_UNITE` varchar(10) DEFAULT NULL,
  `CDEPRD_PA` int(11) DEFAULT NULL,
  PRIMARY KEY (`CODE_COMMANDE`,`CODE_PRODUIT`),
  KEY `PRD_CDE_FK` (`CODE_COMMANDE`),
  KEY `PRD_CDE2_FK` (`CODE_PRODUIT`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE IF NOT EXISTS `produit` (
  `CODE_PRODUIT` varchar(20) NOT NULL,
  `ID_UNITE` varchar(10) NOT NULL,
  `CODE_SOUSCATEGORIE` varchar(10) NOT NULL,
  `CODESOUSGROUP` varchar(10) DEFAULT NULL,
  `PRD_LIBELLE` varchar(250) DEFAULT NULL,
  `PRD_DESCRIP` text,
  `PRD_PRIXACHAT` decimal(10,2) DEFAULT NULL,
  `PRD_PRIXREVIENT` decimal(10,2) DEFAULT NULL,
  `PRD_PRIXVENTE` decimal(10,2) DEFAULT NULL,
  `PRD_SEUILMIN` int(11) DEFAULT NULL,
  `PRD_SEUILMAX` int(11) DEFAULT NULL,
  `PRD_PRIXACHATN2` decimal(10,2) DEFAULT NULL,
  `PRD_PRIXREVIENTN2` decimal(10,2) DEFAULT NULL,
  `PRD_PRIXVENTEN2` decimal(10,2) DEFAULT NULL,
  `PRD_SEUILMINN2` int(11) DEFAULT NULL,
  `PRD_SEUILMAXN2` int(11) DEFAULT NULL,
  `PRD_CONDITIONNE` tinyint(4) DEFAULT NULL,
  `PRD_CODEPRDUIT` bigint(20) DEFAULT NULL,
  `PRD_NBRE_ELT` int(11) DEFAULT NULL,
  `PRD_DIMENSION` varchar(20) DEFAULT NULL,
  `PRD_TRACEUR` char(10) DEFAULT NULL,
  PRIMARY KEY (`CODE_PRODUIT`),
  KEY `SOUSCATEG_PRD_FK` (`CODE_SOUSCATEGORIE`),
  KEY `CND_UNITE_FK` (`ID_UNITE`),
  KEY `SOUSGR_PRODUIT_FK` (`CODESOUSGROUP`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `profil`
--

CREATE TABLE IF NOT EXISTS `profil` (
  `IDPROFIL` varchar(10) NOT NULL,
  `LIBPROFIL` varchar(50) DEFAULT NULL,
  `DCPROF` datetime DEFAULT NULL,
  `DMPROF` datetime DEFAULT NULL,
  PRIMARY KEY (`IDPROFIL`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `profil`
--

INSERT INTO `profil` (`IDPROFIL`, `LIBPROFIL`, `DCPROF`, `DMPROF`) VALUES
('ADMIN', 'Administrateur', '2012-08-14 00:00:00', '2015-05-30 00:00:00'),
('DEVEL', 'Développeur', '2012-08-01 00:00:00', '2015-05-30 00:00:00'),
('GEST', 'Gestionnaire de stocks', '2012-08-03 00:00:00', '2015-05-30 00:00:00'),
('MAG', 'Magasinier', '2014-04-29 00:00:00', '2015-05-03 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `profil_menu`
--

CREATE TABLE IF NOT EXISTS `profil_menu` (
  `IDPROFIL` varchar(10) NOT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `profil_menu`
--

INSERT INTO `profil_menu` (`IDPROFIL`, `IDMENU`, `VISIBLE`, `AJOUT`, `MODIF`, `SUPPR`, `ANNUL`, `VALID`) VALUES
('ADMIN', 'aid', 1, 1, 1, 1, 1, 1),
('ADMIN', 'bde', 1, 1, 1, 1, 1, 1),
('ADMIN', 'bde_cde', 1, 1, 1, 1, 1, 1),
('ADMIN', 'bde_liv', 1, 1, 1, 1, 1, 1),
('ADMIN', 'bds', 1, 1, 1, 1, 1, 1),
('ADMIN', 'bds_bds', 1, 1, 1, 1, 1, 1),
('ADMIN', 'bds_dec', 1, 1, 1, 1, 1, 1),
('ADMIN', 'bds_rep', 1, 1, 1, 1, 1, 1),
('ADMIN', 'bds_trf', 1, 1, 1, 1, 1, 1),
('ADMIN', 'data', 1, 1, 1, 1, 1, 1),
('ADMIN', 'data_exp', 1, 1, 1, 1, 1, 1),
('ADMIN', 'data_imp', 1, 1, 1, 1, 1, 1),
('ADMIN', 'data_vid', 1, 1, 1, 1, 1, 1),
('ADMIN', 'int', 1, 1, 1, 1, 1, 1),
('ADMIN', 'int_int', 1, 1, 1, 1, 1, 1),
('ADMIN', 'int_jou', 1, 1, 1, 1, 1, 1),
('ADMIN', 'int_pcd', 1, 1, 1, 1, 1, 1),
('ADMIN', 'int_stl', 1, 1, 1, 1, 1, 1),
('ADMIN', 'int_sto', 1, 1, 1, 1, 1, 1),
('ADMIN', 'par', 1, 1, 1, 1, 1, 1),
('ADMIN', 'par_aff', 1, 1, 1, 1, 1, 1),
('ADMIN', 'par_ben', 1, 1, 1, 1, 1, 1),
('ADMIN', 'par_bud', 1, 1, 1, 1, 1, 1),
('ADMIN', 'par_cat', 1, 1, 1, 1, 1, 1),
('ADMIN', 'par_fou', 1, 1, 1, 1, 1, 1),
('ADMIN', 'par_gen', 1, 1, 1, 1, 1, 1),
('ADMIN', 'par_grp', 1, 1, 1, 1, 1, 1),
('ADMIN', 'par_log', 1, 1, 1, 1, 1, 1),
('ADMIN', 'par_mag', 1, 1, 1, 1, 1, 1),
('ADMIN', 'par_men', 1, 1, 1, 1, 1, 1),
('ADMIN', 'par_ndcl', 1, 1, 1, 1, 1, 1),
('ADMIN', 'par_per', 1, 1, 1, 1, 1, 1),
('ADMIN', 'par_prd', 1, 1, 1, 1, 1, 1),
('ADMIN', 'par_prv', 1, 1, 1, 1, 1, 1),
('ADMIN', 'par_reg', 1, 1, 1, 1, 1, 1),
('ADMIN', 'par_sscat', 1, 1, 1, 1, 1, 1),
('ADMIN', 'par_ssg', 1, 1, 1, 1, 1, 1),
('ADMIN', 'par_tfr', 1, 1, 1, 1, 1, 1),
('ADMIN', 'par_tse', 1, 1, 1, 1, 1, 1),
('ADMIN', 'par_uni', 1, 1, 1, 1, 1, 1),
('ADMIN', 'par_uti', 1, 1, 1, 1, 1, 1),
('ADMIN', 'rap', 1, 1, 1, 1, 1, 1),
('ADMIN', 'rap_cons', 1, 1, 1, 1, 1, 1),
('ADMIN', 'rap_dec', 1, 1, 1, 1, 1, 1),
('ADMIN', 'rap_din', 1, 1, 1, 1, 1, 1),
('ADMIN', 'rap_fprd', 1, 1, 1, 1, 1, 1),
('ADMIN', 'rap_mde', 1, 1, 1, 1, 1, 1),
('ADMIN', 'rap_men', 1, 1, 1, 1, 1, 1),
('ADMIN', 'rap_mfr', 1, 1, 1, 1, 1, 1),
('ADMIN', 'rap_mst', 1, 1, 1, 1, 1, 1),
('ADMIN', 'rap_pac', 1, 1, 1, 1, 1, 1),
('ADMIN', 'rap_ppe', 1, 1, 1, 1, 1, 1),
('ADMIN', 'rap_prdp', 1, 1, 1, 1, 1, 1),
('ADMIN', 'rap_rds', 1, 1, 1, 1, 1, 1),
('ADMIN', 'rap_rst', 1, 1, 1, 1, 1, 1),
('ADMIN', 'rap_sin', 1, 1, 1, 1, 1, 1),
('ADMIN', 'rap_sssm', 1, 1, 1, 1, 1, 1),
('ADMIN', 'rap_sta', 1, 1, 1, 1, 1, 1),
('ADMIN', 'rap_tri', 1, 1, 1, 1, 1, 1),
('DEVEL', 'aid', 1, 1, 1, 1, 1, 1),
('DEVEL', 'bde', 1, 1, 1, 1, 1, 1),
('DEVEL', 'bde_cde', 1, 1, 1, 1, 1, 1),
('DEVEL', 'bde_liv', 1, 1, 1, 1, 1, 1),
('DEVEL', 'bds', 1, 1, 1, 1, 1, 1),
('DEVEL', 'bds_bds', 1, 1, 1, 1, 1, 1),
('DEVEL', 'bds_dec', 1, 1, 1, 1, 1, 1),
('DEVEL', 'bds_rep', 1, 1, 1, 1, 1, 1),
('DEVEL', 'bds_trf', 1, 1, 1, 1, 1, 1),
('DEVEL', 'data', 1, 1, 1, 1, 1, 1),
('DEVEL', 'data_exp', 1, 1, 1, 1, 1, 1),
('DEVEL', 'data_imp', 1, 1, 1, 1, 1, 1),
('DEVEL', 'data_vid', 1, 1, 1, 1, 1, 1),
('DEVEL', 'int', 1, 1, 1, 1, 1, 1),
('DEVEL', 'int_int', 1, 1, 1, 1, 1, 1),
('DEVEL', 'int_jou', 1, 1, 1, 1, 1, 1),
('DEVEL', 'int_pcd', 1, 1, 1, 1, 1, 1),
('DEVEL', 'int_stl', 1, 1, 1, 1, 1, 1),
('DEVEL', 'int_sto', 1, 1, 1, 1, 1, 1),
('DEVEL', 'par', 1, 1, 1, 1, 1, 1),
('DEVEL', 'par_aff', 1, 1, 1, 1, 1, 1),
('DEVEL', 'par_ben', 1, 1, 1, 1, 1, 1),
('DEVEL', 'par_bud', 1, 1, 1, 1, 1, 1),
('DEVEL', 'par_cat', 1, 1, 1, 1, 1, 1),
('DEVEL', 'par_fou', 1, 1, 1, 1, 1, 1),
('DEVEL', 'par_gen', 1, 1, 1, 1, 1, 1),
('DEVEL', 'par_grp', 1, 1, 1, 1, 1, 1),
('DEVEL', 'par_log', 1, 1, 1, 1, 1, 1),
('DEVEL', 'par_mag', 1, 1, 1, 1, 1, 1),
('DEVEL', 'par_men', 1, 1, 1, 1, 1, 1),
('DEVEL', 'par_ndcl', 1, 1, 1, 1, 1, 1),
('DEVEL', 'par_per', 1, 1, 1, 1, 1, 1),
('DEVEL', 'par_prd', 1, 1, 1, 1, 1, 1),
('DEVEL', 'par_prv', 1, 1, 1, 1, 1, 1),
('DEVEL', 'par_reg', 1, 1, 1, 1, 1, 1),
('DEVEL', 'par_sscat', 1, 1, 1, 1, 1, 1),
('DEVEL', 'par_ssg', 1, 1, 1, 1, 1, 1),
('DEVEL', 'par_tfr', 1, 1, 1, 1, 1, 1),
('DEVEL', 'par_tse', 1, 1, 1, 1, 1, 1),
('DEVEL', 'par_uni', 1, 1, 1, 1, 1, 1),
('DEVEL', 'par_uti', 1, 1, 1, 1, 1, 1),
('DEVEL', 'rap', 1, 1, 1, 1, 1, 1),
('DEVEL', 'rap_cons', 1, 1, 1, 1, 1, 1),
('DEVEL', 'rap_dec', 1, 1, 1, 1, 1, 1),
('DEVEL', 'rap_din', 1, 1, 1, 1, 1, 1),
('DEVEL', 'rap_fprd', 1, 1, 1, 1, 1, 1),
('DEVEL', 'rap_mde', 1, 1, 1, 1, 1, 1),
('DEVEL', 'rap_men', 1, 1, 1, 1, 1, 1),
('DEVEL', 'rap_mfr', 1, 1, 1, 1, 1, 1),
('DEVEL', 'rap_mst', 1, 1, 1, 1, 1, 1),
('DEVEL', 'rap_pac', 1, 1, 1, 1, 1, 1),
('DEVEL', 'rap_ppe', 1, 1, 1, 1, 1, 1),
('DEVEL', 'rap_prdp', 1, 1, 1, 1, 1, 1),
('DEVEL', 'rap_rds', 1, 1, 1, 1, 1, 1),
('DEVEL', 'rap_rst', 1, 1, 1, 1, 1, 1),
('DEVEL', 'rap_sin', 1, 1, 1, 1, 1, 1),
('DEVEL', 'rap_sssm', 1, 1, 1, 1, 1, 1),
('DEVEL', 'rap_sta', 1, 1, 1, 1, 1, 1),
('DEVEL', 'rap_tri', 1, 1, 1, 1, 1, 1),
('GEST', 'aid', 1, 1, 1, 1, 0, 1),
('GEST', 'bde', 1, 1, 1, 1, 0, 1),
('GEST', 'bde_cde', 1, 1, 1, 1, 0, 1),
('GEST', 'bde_liv', 1, 1, 1, 1, 0, 1),
('GEST', 'bds', 1, 1, 1, 1, 0, 1),
('GEST', 'bds_bds', 0, 0, 0, 0, 0, 0),
('GEST', 'bds_dec', 1, 1, 1, 1, 0, 1),
('GEST', 'bds_rep', 1, 1, 1, 1, 0, 1),
('GEST', 'bds_trf', 0, 0, 0, 0, 0, 0),
('GEST', 'data', 0, 0, 0, 0, 0, 0),
('GEST', 'data_exp', 0, 0, 0, 0, 0, 0),
('GEST', 'data_imp', 0, 0, 0, 0, 0, 0),
('GEST', 'data_vid', 0, 0, 0, 0, 0, 0),
('GEST', 'int', 1, 1, 1, 1, 0, 1),
('GEST', 'int_int', 1, 1, 1, 1, 0, 1),
('GEST', 'int_jou', 1, 1, 1, 1, 0, 1),
('GEST', 'int_pcd', 0, 0, 0, 0, 0, 0),
('GEST', 'int_stl', 0, 0, 0, 0, 0, 0),
('GEST', 'int_sto', 1, 1, 1, 1, 0, 1),
('GEST', 'par', 1, 1, 1, 1, 0, 1),
('GEST', 'par_aff', 1, 1, 1, 1, 0, 1),
('GEST', 'par_ben', 1, 1, 1, 1, 1, 1),
('GEST', 'par_bud', 1, 1, 1, 1, 0, 1),
('GEST', 'par_cat', 1, 1, 1, 1, 0, 1),
('GEST', 'par_fou', 1, 1, 1, 1, 0, 1),
('GEST', 'par_gen', 1, 1, 1, 1, 0, 1),
('GEST', 'par_grp', 0, 0, 0, 0, 0, 0),
('GEST', 'par_log', 0, 0, 0, 0, 0, 0),
('GEST', 'par_mag', 0, 0, 0, 0, 0, 0),
('GEST', 'par_men', 0, 0, 0, 0, 0, 0),
('GEST', 'par_ndcl', 0, 0, 0, 0, 0, 0),
('GEST', 'par_per', 0, 0, 0, 0, 0, 0),
('GEST', 'par_prd', 0, 0, 0, 0, 0, 0),
('GEST', 'par_prv', 0, 0, 0, 0, 0, 0),
('GEST', 'par_reg', 0, 0, 0, 0, 0, 0),
('GEST', 'par_sscat', 0, 0, 0, 0, 0, 0),
('GEST', 'par_ssg', 0, 0, 0, 0, 0, 0),
('GEST', 'par_tfr', 0, 0, 0, 0, 0, 0),
('GEST', 'par_tse', 0, 0, 0, 0, 0, 0),
('GEST', 'par_uni', 0, 0, 0, 0, 0, 0),
('GEST', 'par_uti', 0, 0, 0, 0, 0, 0),
('GEST', 'rap', 0, 0, 0, 0, 0, 0),
('GEST', 'rap_cons', 0, 0, 0, 0, 0, 0),
('GEST', 'rap_dec', 0, 0, 0, 0, 0, 0),
('GEST', 'rap_din', 0, 0, 0, 0, 0, 0),
('GEST', 'rap_fprd', 0, 0, 0, 0, 0, 0),
('GEST', 'rap_mde', 0, 0, 0, 0, 0, 0),
('GEST', 'rap_men', 0, 0, 0, 0, 0, 0),
('GEST', 'rap_mfr', 0, 0, 0, 0, 0, 0),
('GEST', 'rap_mst', 0, 0, 0, 0, 0, 0),
('GEST', 'rap_pac', 0, 0, 0, 0, 0, 0),
('GEST', 'rap_ppe', 0, 0, 0, 0, 0, 0),
('GEST', 'rap_prdp', 0, 0, 0, 0, 0, 0),
('GEST', 'rap_rds', 0, 0, 0, 0, 0, 0),
('GEST', 'rap_rst', 0, 0, 0, 0, 0, 0),
('GEST', 'rap_sin', 0, 0, 0, 0, 0, 0),
('GEST', 'rap_sssm', 0, 0, 0, 0, 0, 0),
('GEST', 'rap_sta', 0, 0, 0, 0, 0, 0),
('GEST', 'rap_tri', 0, 0, 0, 0, 0, 0),
('MAG', 'aid', 1, 0, 0, 0, 0, 0),
('MAG', 'bde', 1, 1, 1, 1, 1, 1),
('MAG', 'bde_cde', 1, 1, 1, 0, 0, 0),
('MAG', 'bde_liv', 0, 0, 0, 0, 0, 0),
('MAG', 'bds', 1, 1, 1, 1, 0, 0),
('MAG', 'bds_bds', 0, 0, 0, 0, 0, 0),
('MAG', 'bds_dec', 0, 0, 0, 0, 0, 0),
('MAG', 'bds_rep', 0, 0, 0, 0, 0, 0),
('MAG', 'bds_trf', 0, 0, 0, 0, 0, 0),
('MAG', 'data', 0, 0, 0, 0, 0, 0),
('MAG', 'data_exp', 0, 0, 0, 0, 0, 0),
('MAG', 'data_imp', 0, 0, 0, 0, 0, 0),
('MAG', 'data_vid', 0, 0, 0, 0, 0, 0),
('MAG', 'int', 0, 0, 0, 0, 0, 0),
('MAG', 'int_int', 0, 0, 0, 0, 0, 0),
('MAG', 'int_jou', 0, 0, 0, 0, 0, 0),
('MAG', 'int_pcd', 0, 0, 0, 0, 0, 0),
('MAG', 'int_stl', 0, 0, 0, 0, 0, 0),
('MAG', 'int_sto', 0, 0, 0, 0, 0, 0),
('MAG', 'par', 0, 0, 0, 0, 0, 0),
('MAG', 'par_aff', 0, 0, 0, 0, 0, 0),
('MAG', 'par_ben', 0, 0, 0, 0, 0, 0),
('MAG', 'par_bud', 0, 0, 0, 0, 0, 0),
('MAG', 'par_cat', 0, 0, 0, 0, 0, 0),
('MAG', 'par_fou', 0, 0, 0, 0, 0, 0),
('MAG', 'par_gen', 0, 0, 0, 0, 0, 0),
('MAG', 'par_grp', 0, 0, 0, 0, 0, 0),
('MAG', 'par_log', 0, 0, 0, 0, 0, 0),
('MAG', 'par_mag', 0, 0, 0, 0, 0, 0),
('MAG', 'par_men', 0, 0, 0, 0, 0, 0),
('MAG', 'par_ndcl', 0, 0, 0, 0, 0, 0),
('MAG', 'par_per', 0, 0, 0, 0, 0, 0),
('MAG', 'par_prd', 0, 0, 0, 0, 0, 0),
('MAG', 'par_prv', 0, 0, 0, 0, 0, 0),
('MAG', 'par_reg', 0, 0, 0, 0, 0, 0),
('MAG', 'par_sscat', 0, 0, 0, 0, 0, 0),
('MAG', 'par_ssg', 0, 0, 0, 0, 0, 0),
('MAG', 'par_tfr', 0, 0, 0, 0, 0, 0),
('MAG', 'par_tse', 0, 0, 0, 0, 0, 0),
('MAG', 'par_uni', 0, 0, 0, 0, 0, 0),
('MAG', 'par_uti', 0, 0, 0, 0, 0, 0),
('MAG', 'rap', 0, 0, 0, 0, 0, 0),
('MAG', 'rap_cons', 0, 0, 0, 0, 0, 0),
('MAG', 'rap_dec', 0, 0, 0, 0, 0, 0),
('MAG', 'rap_din', 0, 0, 0, 0, 0, 0),
('MAG', 'rap_fprd', 0, 0, 0, 0, 0, 0),
('MAG', 'rap_mde', 0, 0, 0, 0, 0, 0),
('MAG', 'rap_men', 0, 0, 0, 0, 0, 0),
('MAG', 'rap_mfr', 0, 0, 0, 0, 0, 0),
('MAG', 'rap_mst', 0, 0, 0, 0, 0, 0),
('MAG', 'rap_pac', 0, 0, 0, 0, 0, 0),
('MAG', 'rap_ppe', 0, 0, 0, 0, 0, 0),
('MAG', 'rap_prdp', 0, 0, 0, 0, 0, 0),
('MAG', 'rap_rds', 0, 0, 0, 0, 0, 0),
('MAG', 'rap_rst', 0, 0, 0, 0, 0, 0),
('MAG', 'rap_sin', 0, 0, 0, 0, 0, 0),
('MAG', 'rap_sssm', 0, 0, 0, 0, 0, 0),
('MAG', 'rap_sta', 0, 0, 0, 0, 0, 0),
('MAG', 'rap_tri', 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `province`
--

CREATE TABLE IF NOT EXISTS `province` (
  `IDPROVINCE` int(11) NOT NULL AUTO_INCREMENT,
  `IDREGION` int(11) NOT NULL,
  `PROVINCE` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`IDPROVINCE`),
  KEY `REG_PROV_FK` (`IDREGION`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1305 ;

--
-- Contenu de la table `province`
--

INSERT INTO `province` (`IDPROVINCE`, `IDREGION`, `PROVINCE`) VALUES
(6, 11, 'channel');

-- --------------------------------------------------------

--
-- Structure de la table `region`
--

CREATE TABLE IF NOT EXISTS `region` (
  `IDREGION` int(11) NOT NULL AUTO_INCREMENT,
  `REGION` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`IDREGION`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Contenu de la table `region`
--

INSERT INTO `region` (`IDREGION`, `REGION`) VALUES
(11, 'Channel');

-- --------------------------------------------------------

--
-- Structure de la table `report`
--

CREATE TABLE IF NOT EXISTS `report` (
  `CODE_REPORT` varchar(30) NOT NULL,
  `CODE_MAGASIN` varchar(10) NOT NULL,
  `ID_EXERCICE` int(11) NOT NULL,
  `ID_REPORT` int(11) NOT NULL,
  `REP_LIBELLE` varchar(250) DEFAULT NULL,
  `REP_NATURE` varchar(250) DEFAULT NULL,
  `REP_DATE` date DEFAULT NULL,
  `REP_VALIDE` tinyint(4) DEFAULT NULL,
  `REP_DATEVALID` datetime DEFAULT NULL,
  `CODE_REP_SORT` varchar(30) NOT NULL,
  PRIMARY KEY (`CODE_REPORT`),
  KEY `EXERCUCE_REPORT_FK` (`ID_EXERCICE`),
  KEY `MAG_REPORT_FK` (`CODE_MAGASIN`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `souscategorie`
--

CREATE TABLE IF NOT EXISTS `souscategorie` (
  `CODE_SOUSCATEGORIE` varchar(10) NOT NULL,
  `CODE_CATEGORIE` varchar(10) NOT NULL,
  `SOUSCAT_LIBELLE` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`CODE_SOUSCATEGORIE`),
  KEY `CATEG_PRD_FK` (`CODE_CATEGORIE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `sousgroupe`
--

CREATE TABLE IF NOT EXISTS `sousgroupe` (
  `CODESOUSGROUP` varchar(10) NOT NULL,
  `SOUSGROUPE` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`CODESOUSGROUP`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `transfert`
--

CREATE TABLE IF NOT EXISTS `transfert` (
  `CODE_TRANSFERT` varchar(30) NOT NULL,
  `CODE_MAGASIN` varchar(10) NOT NULL,
  `ID_EXERCICE` int(11) NOT NULL,
  `REF_TRANSFERT` varchar(100) DEFAULT NULL,
  `ID_TRANSFERT` int(11) NOT NULL,
  `MAG_CODE_MAGASIN_SRCE` varchar(10) DEFAULT NULL,
  `MAG_CODE_MAGASIN_DEST` varchar(10) DEFAULT NULL,
  `TRS_DATE` date DEFAULT NULL,
  `TRS_NATURE` tinyint(4) DEFAULT NULL,
  `TRS_RAISON` text,
  `TRS_VALIDE` tinyint(4) DEFAULT NULL,
  `TRS_DATEVALID` datetime DEFAULT NULL,
  `TRS_LIBELLE` text,
  `MAG_NP` varchar(100) DEFAULT NULL,
  `MAG_CIB` varchar(100) DEFAULT NULL,
  `MAG_DATE` date DEFAULT NULL,
  `PRE_NP` varchar(100) DEFAULT NULL,
  `PRE_CIB` varchar(100) DEFAULT NULL,
  `PRE_DATE` date DEFAULT NULL,
  `TRS_PRIXDISTRICT` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`CODE_TRANSFERT`),
  KEY `MAG_TRANFERT_FK` (`CODE_MAGASIN`),
  KEY `EXERCICE_TRF_FK` (`ID_EXERCICE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `typebeneficiaire`
--

CREATE TABLE IF NOT EXISTS `typebeneficiaire` (
  `CODE_TYPEBENEF` varchar(10) NOT NULL,
  `NOM_TYPEBENEF` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`CODE_TYPEBENEF`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `typefournisseur`
--

CREATE TABLE IF NOT EXISTS `typefournisseur` (
  `CODE_TYPEFOUR` varchar(10) NOT NULL,
  `TYPEFOUR_NOM` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`CODE_TYPEFOUR`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `unite`
--

CREATE TABLE IF NOT EXISTS `unite` (
  `ID_UNITE` varchar(10) NOT NULL,
  `UT_LIBELLE` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`ID_UNITE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `beneficiaire`
--
ALTER TABLE `beneficiaire`
  ADD CONSTRAINT `FK_PROV_BENEF` FOREIGN KEY (`IDPROVINCE`) REFERENCES `province` (`IDPROVINCE`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_TYPEBENF_BENEF` FOREIGN KEY (`CODE_TYPEBENEF`) REFERENCES `typebeneficiaire` (`CODE_TYPEBENEF`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `bonsortie`
--
ALTER TABLE `bonsortie`
  ADD CONSTRAINT `FK_BENEF_BONSORTIE` FOREIGN KEY (`CODE_BENEF`) REFERENCES `beneficiaire` (`CODE_BENEF`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_EXERCICE_BONSORTIE` FOREIGN KEY (`ID_EXERCICE`) REFERENCES `exercice` (`ID_EXERCICE`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_MAG_BONSORTIE` FOREIGN KEY (`CODE_MAGASIN`) REFERENCES `magasin` (`CODE_MAGASIN`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `FK_CDE_EXERCICE` FOREIGN KEY (`ID_EXERCICE`) REFERENCES `exercice` (`ID_EXERCICE`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_CDE_FOUR` FOREIGN KEY (`CODE_FOUR`) REFERENCES `fournisseur` (`CODE_FOUR`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_MAG_CDE` FOREIGN KEY (`CODE_MAGASIN`) REFERENCES `magasin` (`CODE_MAGASIN`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `compte`
--
ALTER TABLE `compte`
  ADD CONSTRAINT `FK_COMPTE_PROFIL` FOREIGN KEY (`IDPROFIL`) REFERENCES `profil` (`IDPROFIL`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_PERS_COMPTE` FOREIGN KEY (`NUM_MLLE`) REFERENCES `personnel` (`NUM_MLLE`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `declass`
--
ALTER TABLE `declass`
  ADD CONSTRAINT `FK_DECLASS_NATDECLASS` FOREIGN KEY (`CODENATDECLASS`) REFERENCES `natdeclass` (`CODENATDECLASS`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_EXERCICE_DECL` FOREIGN KEY (`ID_EXERCICE`) REFERENCES `exercice` (`ID_EXERCICE`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_MAG_DECLASS` FOREIGN KEY (`CODE_MAGASIN`) REFERENCES `magasin` (`CODE_MAGASIN`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `detbonsortie`
--
ALTER TABLE `detbonsortie`
  ADD CONSTRAINT `FK_BONS_DETBONSORTIE` FOREIGN KEY (`CODE_BONSORTIE`) REFERENCES `bonsortie` (`CODE_BONSORTIE`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_PRD_DETBONSORTIE` FOREIGN KEY (`CODE_PRODUIT`) REFERENCES `produit` (`CODE_PRODUIT`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `detdeclass`
--
ALTER TABLE `detdeclass`
  ADD CONSTRAINT `FK_DECL_DETDECLASS` FOREIGN KEY (`CODE_DECLASS`) REFERENCES `declass` (`CODE_DECLASS`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_PRD_DETDECLASS` FOREIGN KEY (`CODE_PRODUIT`) REFERENCES `produit` (`CODE_PRODUIT`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `detinventaire`
--
ALTER TABLE `detinventaire`
  ADD CONSTRAINT `FK_INV_DETINVENTAIRE` FOREIGN KEY (`CODE_INVENTAIRE`) REFERENCES `inventaire` (`CODE_INVENTAIRE`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_PRD_DETINVENTAIRE` FOREIGN KEY (`CODE_PRODUIT`) REFERENCES `produit` (`CODE_PRODUIT`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `detlivraison`
--
ALTER TABLE `detlivraison`
  ADD CONSTRAINT `FK_LIVR_DETLIVRAISON` FOREIGN KEY (`CODE_LIVRAISON`) REFERENCES `livraison` (`CODE_LIVRAISON`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_PRD_DETLIVRAISON` FOREIGN KEY (`CODE_PRODUIT`) REFERENCES `produit` (`CODE_PRODUIT`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `detreport`
--
ALTER TABLE `detreport`
  ADD CONSTRAINT `FK_PRD_DETREPORT` FOREIGN KEY (`CODE_PRODUIT`) REFERENCES `produit` (`CODE_PRODUIT`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_REP_DETREPORT` FOREIGN KEY (`CODE_REPORT`) REFERENCES `report` (`CODE_REPORT`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `dettransfert`
--
ALTER TABLE `dettransfert`
  ADD CONSTRAINT `FK_PRD_TRANSFERT` FOREIGN KEY (`CODE_PRODUIT`) REFERENCES `produit` (`CODE_PRODUIT`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_TRS_DETTRANFERT` FOREIGN KEY (`CODE_TRANSFERT`) REFERENCES `transfert` (`CODE_TRANSFERT`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  ADD CONSTRAINT `FK_TYPEFOUR_FOUR` FOREIGN KEY (`CODE_TYPEFOUR`) REFERENCES `typefournisseur` (`CODE_TYPEFOUR`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `infogenerale`
--
ALTER TABLE `infogenerale`
  ADD CONSTRAINT `FK_MAG_INFOGLE` FOREIGN KEY (`CODE_MAGASIN`) REFERENCES `magasin` (`CODE_MAGASIN`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `inventaire`
--
ALTER TABLE `inventaire`
  ADD CONSTRAINT `FK_EXERCICE_INV` FOREIGN KEY (`ID_EXERCICE`) REFERENCES `exercice` (`ID_EXERCICE`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_MAG_INVENTAIRE` FOREIGN KEY (`CODE_MAGASIN`) REFERENCES `magasin` (`CODE_MAGASIN`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `livraison`
--
ALTER TABLE `livraison`
  ADD CONSTRAINT `FK_CDE_LIVRAISON` FOREIGN KEY (`CODE_COMMANDE`) REFERENCES `commande` (`CODE_COMMANDE`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_EXERCICE_LIVR` FOREIGN KEY (`ID_EXERCICE`) REFERENCES `exercice` (`ID_EXERCICE`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_FOUR_LIVRAISON` FOREIGN KEY (`CODE_FOUR`) REFERENCES `fournisseur` (`CODE_FOUR`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_MAG_LIVRAISON` FOREIGN KEY (`CODE_MAGASIN`) REFERENCES `magasin` (`CODE_MAGASIN`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `FK_COMPTE_LOGS` FOREIGN KEY (`LOGIN`) REFERENCES `compte` (`LOGIN`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `magasin`
--
ALTER TABLE `magasin`
  ADD CONSTRAINT `FK_MAG_PROV` FOREIGN KEY (`IDPROVINCE`) REFERENCES `province` (`IDPROVINCE`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `mag_compte`
--
ALTER TABLE `mag_compte`
  ADD CONSTRAINT `FK_MAG_COMPTE` FOREIGN KEY (`LOGIN`) REFERENCES `compte` (`LOGIN`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_MAG_COMPTE2` FOREIGN KEY (`CODE_MAGASIN`) REFERENCES `magasin` (`CODE_MAGASIN`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `mouvement`
--
ALTER TABLE `mouvement`
  ADD CONSTRAINT `FK_EXERCICE_MVT` FOREIGN KEY (`ID_EXERCICE`) REFERENCES `exercice` (`ID_EXERCICE`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_MAG_MVT` FOREIGN KEY (`CODE_MAGASIN`) REFERENCES `magasin` (`CODE_MAGASIN`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_PRD_MVT` FOREIGN KEY (`CODE_PRODUIT`) REFERENCES `produit` (`CODE_PRODUIT`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `personnel`
--
ALTER TABLE `personnel`
  ADD CONSTRAINT `FK_SER_PERS` FOREIGN KEY (`CODE_MAGASIN`) REFERENCES `magasin` (`CODE_MAGASIN`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `prd_cde`
--
ALTER TABLE `prd_cde`
  ADD CONSTRAINT `FK_PRD_CDE` FOREIGN KEY (`CODE_COMMANDE`) REFERENCES `commande` (`CODE_COMMANDE`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_PRD_CDE2` FOREIGN KEY (`CODE_PRODUIT`) REFERENCES `produit` (`CODE_PRODUIT`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `FK_CND_UNITE` FOREIGN KEY (`ID_UNITE`) REFERENCES `unite` (`ID_UNITE`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_SOUSCATEG_PRD` FOREIGN KEY (`CODE_SOUSCATEGORIE`) REFERENCES `souscategorie` (`CODE_SOUSCATEGORIE`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_SOUSGR_PRODUIT` FOREIGN KEY (`CODESOUSGROUP`) REFERENCES `sousgroupe` (`CODESOUSGROUP`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `profil_menu`
--
ALTER TABLE `profil_menu`
  ADD CONSTRAINT `FK_PROFIL_MENU` FOREIGN KEY (`IDPROFIL`) REFERENCES `profil` (`IDPROFIL`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_PROFIL_MENU2` FOREIGN KEY (`IDMENU`) REFERENCES `menu` (`IDMENU`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `province`
--
ALTER TABLE `province`
  ADD CONSTRAINT `FK_REG_PROV` FOREIGN KEY (`IDREGION`) REFERENCES `region` (`IDREGION`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `FK_EXERCUCE_REPORT` FOREIGN KEY (`ID_EXERCICE`) REFERENCES `exercice` (`ID_EXERCICE`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_MAG_REPORT` FOREIGN KEY (`CODE_MAGASIN`) REFERENCES `magasin` (`CODE_MAGASIN`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `souscategorie`
--
ALTER TABLE `souscategorie`
  ADD CONSTRAINT `FK_CATEG_PRD` FOREIGN KEY (`CODE_CATEGORIE`) REFERENCES `categorie` (`CODE_CATEGORIE`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `transfert`
--
ALTER TABLE `transfert`
  ADD CONSTRAINT `FK_EXERCICE_TRF` FOREIGN KEY (`ID_EXERCICE`) REFERENCES `exercice` (`ID_EXERCICE`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_MAG_TRANFERT` FOREIGN KEY (`CODE_MAGASIN`) REFERENCES `magasin` (`CODE_MAGASIN`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
