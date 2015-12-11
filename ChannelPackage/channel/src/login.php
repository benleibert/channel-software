<?php
function loginUser($userName,$pword){

	//Files required
	require_once('../lib/global.inc');
	$table1 = 'stocks_compte';
	$table2 = 'stocks_personnel';
	$table3 = 'stocks_groupe';
	
	//SQL 
	$SQL  = "SELECT * FROM ($table1 INNER JOIN $table2 ON $table1.NUM_MATRICULE LIKE $table2.NUM_MATRICULE) ";
	$SQL .= "INNER JOIN $table3 ON $table1.ID_GROUPE LIKE $table3.ID_GROUPE ";
	$SQL .= "WHERE LOGIN LIKE '$userName' AND MOTPASSE LIKE '$pword';";

	//Connection to DB
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errpage.php');

	//Select DB
	mysql_select_db(DB)  or header('location:errpage.php');

	//Execution
	$result = mysql_query($SQL) or header('location:errpage.php');
	$row = mysql_fetch_array($result);
	$data = array('LOGIN'=>$row['LOGIN'],'NOM_PRENOMS'=>$row['NOM_PRENOMS'],'NUM_MATRICULE'=>$row['NUM_MATRICULE'],'NOM_GROUPE'=>$row['NOM_GROUPE']);
	return $login = array('NBRE' => mysql_num_rows($result), 'ROW'=>$data);
}

//This function update log file
function logTracer($time, $user, $action){
	//Files required
	require_once('../lib/global.inc');
	$table1 = 'stocks_logs';
	
	//SQL 
	$SQL  = "INSERT INTO $table1 (LOGIN, DATE_LOG, DESCRIPTION) VALUES ('$time', '$user', '$action') ";

	//Connection to DB
	mysql_connect(DBSERVER,DBUSER,DBPWD) or header('location:errpage.php');

	//Select DB
	mysql_select_db(DB) or header('location:errpage.php');

	//Execution
	$result = mysql_query($SQL) or header('location:errpage.php');
	return 0;
}
?>