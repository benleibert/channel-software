<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */

//--------- UNITES -----------------------------

//Nombre de ligne retourner
function fichierDB(){
	$nb_fichier = 0;
	$returnHTML ='<ul>';

	if($dossier = opendir('../download')){
		while(false !== ($fichier = readdir($dossier))){

			if($fichier != '.' && $fichier != '..' && $fichier != 'index.php' && $fichier != 'index.html' && substr($fichier,0,6) == 'Backup'){
				$nb_fichier++; // On incrémente le compteur de 1

				$returnHTML.= '<li> <input name="fichierDB[]" type="checkbox" id="fichierDB[]" value="'. $fichier.'"  /> <a href="../download/'. $fichier . '">' . $fichier . '</a></li>';

			} // On ferme le if (qui permet de ne pas afficher index.php, etc.)
		} // On termine la boucle

		$returnHTML.= '</ul><br />';
		$returnHTML.= '&nbsp;&nbsp;<strong>' . $nb_fichier .'</strong> fichier(s) dans le dossier';

		closedir($dossier);

	}

	return 	$returnHTML;
}

//Nombre de ligne retourner
function fichierEXCEL(){
	$nb_fichier = 0;
	$returnHTML ='<ul>';

	if($dossier = opendir('../download')){
		while(false !== ($fichier = readdir($dossier))){

			if($fichier != '.' && $fichier != '..' && $fichier != 'index.php' && $fichier != 'index.html' && substr($fichier,0,3) == 'Exp'){
				$nb_fichier++; // On incrémente le compteur de 1

				$returnHTML.= '<li> <input name="fichierExcel[]" type="checkbox" id="fichierExcel[]" value="'. $fichier.'"  /> <a href="../download/'. $fichier . '">' . $fichier . '</a></li>';

			} // On ferme le if (qui permet de ne pas afficher index.php, etc.)
		} // On termine la boucle

		$returnHTML.= '</ul><br />';
		$returnHTML.= '&nbsp;&nbsp;<strong>' . $nb_fichier .'</strong> fichier(s) dans le dossier';

		closedir($dossier);

	}

	return 	$returnHTML;
}



?>