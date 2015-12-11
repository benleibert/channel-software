<?php

//IMPORTANT VARIABLES
define('DBSERVER','localhost');
define('DB','channel_db');
define('DBUSER','root');
define('DBPWD','');
define('DBD','mysql:host=localhost;dbname=channel_db');

try {
	$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
}
catch (PDOException $error) { //Treat error
	//("Erreur de connexion : " . $error->getMessage() );
	header('location:errorPage.php');
}

try {
	$cnx1 = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
}
catch (PDOException $error) { //Treat error
	//("Erreur de connexion : " . $error->getMessage() );
	header('location:errorPage.php');
}

(isset($_GET['do']) && $_GET['do']!='' ? $do = $_GET['do'] : $do = '');

switch($do){

	case 1:
		//BENEFICIAIRE
		$sql = "SELECT * FROM stocks_beneficiaire;";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		$beneficiaire ='';

		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$beneficiaire .= " INSERT INTO `beneficiaire` (ID_BENEF	, `IDPROVINCE` ,`CODE_NOMBENF` ,`CODE_BENEF` ,`BENEF_NOM` ,`BENEF_EBREVIATION` ,
			`BENEF_TEL` ,`BENEF_VILLE` ,`BENEF_EMAIL` ,`BENEF_DATEINT` ,`BENEF_DIST` ,`BENEF_DATECREAT`) VALUES ('".addslashes($row['ID_BENEFICIAIRE'])."',
			'".addslashes($row['ID_PROVINCE'])."', 'MESS',  NULL  ,'".addslashes($row['LIBELLE_BENEFICIAIRE'])."' ,  NULL ,NULL, NULL,NULL,NULL, NULL, NULL);\n";

		}

		$fp = fopen('../db/beneficiaire.sql', 'w');
		fwrite($fp, $beneficiaire);
		fclose($fp);
		break;

	case 2:
		//BON ENTREE = LIVRAISON
		$sql = "SELECT * FROM stocks_bon_entre WHERE ID_BONENTRE NOT IN ('BE/61/2011', 'BE/61/2011', 'BE/61/2011', 'BE/61/2011', 'BE/61/2011', 'BE/61/2011', 'BE/61/2011', 'BE/61/2011', 'BE/61/2011', 'BE/61/2011', 'BE/61/2011', 'BE/61/2011', 'BE/61/2011', 'BE/61/2011', 'BE/61/2011', 'BE/61/2011', 'BE/61/2011', 'BE/61/2011', 'BE/61/2011', 'BE/61/2011', 'BE/61/2011', 'BE/61/2011', 'BE/61/2011', 'BE/61/2011', 'BE/61/2011', 'BE/61/2011', 'BE/61/2011', 'BE/61/2011', 'BE/61/2011', 'BE/0038/2010', 'BE/5/2010', 'BS/47/2010');";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		$i=1;
		$livraison ='';
		$ligne_livraison ='';
		$ligne_livraison_mvt= '';

		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$livraison .= "  INSERT INTO `livraison` ( `ID_LIVRAISON` ,`ID_EXERCICE` ,`ID_COMMANDE` ,`CODE_MAGASIN` ,`CODE_LIVRAISON` ,
			`LIVR_LIBELLE` ,`LVR_DATE` ,`LVR_VALIDE` ,`LVR_DATEVALID` ) VALUES ('".addslashes($i)."',	'".addslashes($row['ID_EXERCICE'])."', NULL
			,'MAG0' ,  '".addslashes(stripslashes($row['ID_BONENTRE']))."' ,'".addslashes(stripslashes($row['LIBELLE_BONENTRE']))."',
			'".addslashes($row['DATE_BONENTRE'])."',0,NULL) ;\n";


			$sql1 = "SELECT * FROM stocks_ligne_bon_entre WHERE ID_BONENTRE LIKE '".addslashes(stripslashes($row['ID_BONENTRE']))."';";
			$query1 =  $cnx->prepare($sql1); //Prepare the SQL
			$query1->execute(); //Execute prepared SQL => $query

			while($row1 = $query1->fetch(PDO::FETCH_ASSOC)){
				$ligne_livraison .= "  INSERT INTO `prd_livraison` (`ID_LIVRAISON` ,`CODE_PRODUIT` ,`LVRPRD_QUANTITE` ,`LVRPRD_RECU` ,`LIV_UNITE` ,`LVR_IDCOMMANDE` ,`LVRPRD_MAG`)
				VALUES ('".addslashes($i)."','".addslashes($row1['ID_ARTICLE'])."', '".addslashes($row1['QTE_ENTREE'])."', '".addslashes($row1['QTE_ENTREE'])."',
				NULL, NULL,'MAG0');\n";

				$ligne_livraison_mvt .="INSERT INTO `mouvement` (`ID_EXERCICE` ,`CODE_PRODUIT` ,`ID_SOURCE` ,`CODE_MAGASIN` ,	`MVT_DATE` ,`MVT_TIME` ,`MVT_QUANTITE` ,
				`MVT_UNITE` ,`MVT_NATURE` ,	`MVT_VALID`,`MVT_TYPE`) VALUES ('".addslashes($row['ID_EXERCICE'])."','".addslashes($row1['ID_ARTICLE'])."',
				'".addslashes($i)."', 'MAG0', '".addslashes($row['DATE_BONENTRE'])."' ,'".addslashes(date('H:i:s'))."' ,
				'".addslashes($row1['QTE_ENTREE'])."' , NULL, 'LIVRAISON','0','E') ; \n";

			}
			$i++;
		}


		$fp = fopen('../db/livraison.sql', 'w');
		fwrite($fp, $livraison);
		fclose($fp);

		$fp = fopen('../db/ligne_livraison.sql', 'w');
		fwrite($fp, $ligne_livraison);
		fclose($fp);


		$fp = fopen('../db/ligne_livraison_mvt.sql', 'w');
		fwrite($fp, $ligne_livraison_mvt);
		fclose($fp);

		echo "Terminé";

		break;

	case 3:
		//BON SORTIE = BON SORTIE
		$sql = "SELECT * FROM stocks_bon_sortie WHERE ID_BONSORTIE NOT IN ('BS/916/2010', 'BS/226/2010', 'BS/24/2010', 'BS/144/2010', 'BS/213/2010', 'BS/135/2010', 'BS/200/2010', 'BS/475/2010', 'BS/98/2010', 'BS/198/2010', 'BE/454/2010', 'BS/96/2010', 'BS/173/2010', 'BS/344/2010', 'BS/48/2010', 'BS/145/2010');";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		$i=1;
		$bon_sortie ='';
		$ligne_bon_sortie ='';
		$ligne_bon_sortie_mvt= '';

		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$bon_sortie .= "  INSERT INTO `bonsortie` (`ID_BONSORTIE`,`ID_BENEF` ,`ID_EXERCICE` ,`CODE_BONSORTIE` ,`CODE_MAGASIN` ,`DOT_DATE` , `DOT_LIBELLE` ,
			`DOT_VALIDE`) VALUES ('".addslashes($i)."','".addslashes($row['ID_BENEFICIAIRE'])."','".addslashes($row['ID_EXERCICE'])."',
			'".addslashes(stripslashes($row['ID_BONSORTIE']))."','MAG0','".addslashes($row['DATE_BONSORTIE'])."','".addslashes(stripslashes($row['LIBELLE_BONSORTIE']))."',
			'0');\n";


			$sql1 = "SELECT * FROM stocks_ligne_bon_sortie WHERE ID_BONSORTIE LIKE '".addslashes(stripslashes($row['ID_BONSORTIE']))."';";
			$query1 =  $cnx->prepare($sql1); //Prepare the SQL
			$query1->execute(); //Execute prepared SQL => $query

			while($row1 = $query1->fetch(PDO::FETCH_ASSOC)){
				$ligne_bon_sortie .= "  INSERT INTO `prd_bonsortie` (`ID_BONSORTIE` ,`CODE_PRODUIT` ,`BSPRD_QTE` ,`BSPRD_UNITE` )
					VALUES ('".addslashes($i)."', '".addslashes($row1['ID_ARTICLE'])."', '".addslashes($row1['QTE_SORTIE'])."' ,
					NULL);\n";

				$ligne_bon_sortie_mvt .="INSERT INTO `mouvement` (`ID_EXERCICE` ,`CODE_PRODUIT` ,`ID_SOURCE` ,`CODE_MAGASIN` ,	`MVT_DATE` ,`MVT_TIME` ,`MVT_QUANTITE` ,
				`MVT_UNITE` ,`MVT_NATURE` ,	`MVT_VALID`,`MVT_TYPE`) VALUES ('".addslashes($row['ID_EXERCICE'])."','".addslashes($row1['ID_ARTICLE'])."',
				'".addslashes($i)."', 'MAG0', '".addslashes($row['DATE_BONSORTIE'])."' ,'".addslashes(date('H:i:s'))."' ,
				'".addslashes($row1['QTE_SORTIE'])."' , NULL, 'BON DE SORTIE','0','S') ; \n";

			}
			$i++;
		}

		$fp = fopen('../db/bonsortie.sql', 'w');
		fwrite($fp, $bon_sortie);
		fclose($fp);

		$fp = fopen('../db/ligne_bon_sortie.sql', 'w');
		fwrite($fp, $ligne_bon_sortie);
		fclose($fp);

		$fp = fopen('../db/ligne_bon_sortie_mvt.sql', 'w');
		fwrite($fp, $ligne_bon_sortie_mvt);
		fclose($fp);

		echo "Terminé";

		break;

	case 4:
		//ARTICLE
		$sql = "SELECT * FROM stocks_article order by ID_ARTICLE ASC;";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		$produit ='';
		$i=1;
		while($row = $query->fetch(PDO::FETCH_ASSOC)){

			switch ($row['ID_UNITE']) {
				case 1 :
					$unite = 'u';
					break;
				case 2:
					$unite = 'pqt';
					break;
				case 3:
					$unite = 'l';
					break;
				case 4:
					$unite = 'bid';
					break;
				case 5:
					$unite = 'tub';
					break;
				case 6:
					$unite = 'rle';
					break;
				default:
				;
			} // switch


			echo $i, ' ',$row['ID_ARTICLE'], " => ", stripslashes($row['LIBELLE_ARTICLE']), '<br />';

			$produit .= 'INSERT INTO `produit` (`CODE_PRODUIT` ,`ID_UNITE` ,`CODE_CATEGORIE` ,`PRD_LIBELLE` ,`PRD_DESCRIP` ,
			`PRD_PRIX` ,`PRD_SEUILMIN` ,`PRD_CONDITIONNE` ,`PRD_CODEPRDUIT` ,`PRD_NBRE_ELT` ,`PRD_DIMENSION`) VALUES (
			"'.$row['ID_ARTICLE'].'", "'.$unite.'", "'.$row['ID_CATEGORIE'].'", "'.addslashes(stripslashes($row['LIBELLE_ARTICLE'])).'" ,
			"'.addslashes(stripslashes($row['DESCRIPTION'])).'" ,	"'.$row['PRIX_UNITAIRE'].'" , "'.$row['SEUIL_APPRO'].'" , 0 , NULL , NULL , NULL);'."\n";

			$i++;
		}

//		$fp = fopen('../db/produit.sql', 'w');
//		fwrite($fp, $produit);
//		fclose($fp);
		echo "Terminé";

		break;


	case 5:
		//ARTICLE
		$sql = "SELECT stocks_ligne_bon_entre.*, stocks_article.LIBELLE_ARTICLE FROM stocks_ligne_bon_entre LEFT JOIN stocks_article ON
		(stocks_ligne_bon_entre.ID_ARTICLE LIKE stocks_article.ID_ARTICLE)  order by stocks_ligne_bon_entre.ID_ARTICLE ASC;";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		$produit ='';
		$be ='';
		$i=1;
		while($row = $query->fetch(PDO::FETCH_ASSOC)){


			echo $i, ' ',$row['ID_BONENTRE'],' => ',$row['ID_ARTICLE'], " => ", stripslashes($row['LIBELLE_ARTICLE']), ' => ' , stripslashes($row['QTE_ENTREE']).'<br />';
			if ($row['LIBELLE_ARTICLE']=='') {
				$be .= "'".$row['ID_BONENTRE']."', ";
			}

//			$produit .= 'INSERT INTO `produit` (`CODE_PRODUIT` ,`ID_UNITE` ,`CODE_CATEGORIE` ,`PRD_LIBELLE` ,`PRD_DESCRIP` ,
//			`PRD_PRIX` ,`PRD_SEUILMIN` ,`PRD_CONDITIONNE` ,`PRD_CODEPRDUIT` ,`PRD_NBRE_ELT` ,`PRD_DIMENSION`) VALUES (
//			"'.$row['ID_ARTICLE'].'", "'.$unite.'", "'.$row['ID_CATEGORIE'].'", "'.addslashes(stripslashes($row['LIBELLE_ARTICLE'])).'" ,
//			"'.addslashes(stripslashes($row['DESCRIPTION'])).'" ,	"'.$row['PRIX_UNITAIRE'].'" , "'.$row['SEUIL_APPRO'].'" , 0 , NULL , NULL , NULL);'."\n";

			$i++;
		}

		//		$fp = fopen('../db/produit.sql', 'w');
		//		fwrite($fp, $produit);
		//		fclose($fp);
		echo "Terminé <br />" ;
		echo $be;
		break;

	case 6:
		//ARTICLE
		$sql = "SELECT stocks_ligne_bon_sortie.*, stocks_article.LIBELLE_ARTICLE FROM stocks_ligne_bon_sortie LEFT JOIN stocks_article ON
		(stocks_ligne_bon_sortie.ID_ARTICLE LIKE stocks_article.ID_ARTICLE)  order by stocks_ligne_bon_sortie.ID_ARTICLE ASC;";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		$produit ='';
		$bs ='';
		$i=1;
		while($row = $query->fetch(PDO::FETCH_ASSOC)){


			echo $i, ' ',$row['ID_BONSORTIE'],' => ',$row['ID_ARTICLE'], " => ", stripslashes($row['LIBELLE_ARTICLE']), ' => ' , stripslashes($row['QTE_SORTIE']).'<br />';
			if ($row['LIBELLE_ARTICLE']=='') {
				$bs .= "'".$row['ID_BONSORTIE']."', ";
			}
			//			$produit .= 'INSERT INTO `produit` (`CODE_PRODUIT` ,`ID_UNITE` ,`CODE_CATEGORIE` ,`PRD_LIBELLE` ,`PRD_DESCRIP` ,
			//			`PRD_PRIX` ,`PRD_SEUILMIN` ,`PRD_CONDITIONNE` ,`PRD_CODEPRDUIT` ,`PRD_NBRE_ELT` ,`PRD_DIMENSION`) VALUES (
			//			"'.$row['ID_ARTICLE'].'", "'.$unite.'", "'.$row['ID_CATEGORIE'].'", "'.addslashes(stripslashes($row['LIBELLE_ARTICLE'])).'" ,
			//			"'.addslashes(stripslashes($row['DESCRIPTION'])).'" ,	"'.$row['PRIX_UNITAIRE'].'" , "'.$row['SEUIL_APPRO'].'" , 0 , NULL , NULL , NULL);'."\n";

			$i++;
		}

		//		$fp = fopen('../db/produit.sql', 'w');
		//		fwrite($fp, $produit);
		//		fclose($fp);
		echo "Terminé <br />";
		echo $bs;
		break;

	case 7:
		//ARTICLE


		//IMPORTANT VARIABLES
//		define('DBSERVER2','localhost');
//		define('DB2','patrimoine2_db');
//		define('DBUSER2','root');
//		define('DBPWD2','');
//		define('DBD2','mysql:localhost;dbname=patrimoine2_db');



}

?>