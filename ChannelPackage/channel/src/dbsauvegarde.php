<?php

//MySQL Parameters
require_once('../lib/global.inc');

function dump(){

	//Connexion
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
	}
	//on récupère la liste des tables de la base de données
	//$tables = mysql_list_tables($database, $db) or die(mysql_error());
	$sql = 'SHOW TABLES FROM '.$database;

	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query

	$backup_file = '../db/' . $table . '.sql';
	$fp = fopen($backup_file, 'w');

 	while( $donnees = $query->fetch(PDO::FETCH_ASSOC))
		// on récupère le create table (structure de la table)
	  	$table = $donnees[0];
		$sql = 'SHOW CREATE TABLE '.$table;
		$query1 =  $cnx->prepare($sql); //Prepare the SQL
		$query1->execute(); //Execute prepared SQL => $query


	  	if ($query1) 	{

	   			$tableau = mysql_fetch_array($res);
	   			$tableau[1] .= ";\n";
	   			$insertions = $tableau[1];
   				gzwrite($fp, $insertions);

	   			$req_table = mysql_query('SELECT * FROM '.$table) or die(mysql_error());
	   			$nbr_champs = mysql_num_fields($req_table);
	  			while ($ligne = mysql_fetch_array($req_table)) {
		    		$insertions = 'INSERT INTO '.$table.' VALUES (';
	    			for ($i=0; $i<$nbr_champs; $i++){
	     				$insertions .= '\'' . mysql_real_escape_string($ligne[$i]) . '\', ';
	    			}
		    		$insertions = substr($insertions, 0, -2);
		   			$insertions .= ");\n";
		    		gzwrite($fp, $insertions);
		   		}
	  		} // fin if ($res)
		  mysql_free_result($res);
		  gzclose($fp);
	 }
	 return true;
	}

	//appel de la fonction
	echo $dump = dump(1);

?>