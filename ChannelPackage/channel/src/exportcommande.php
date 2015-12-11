<?php

/**
 * KG
 *
 * @version $Id$
 * @copyright 2011
 */
require_once('../src/config.inc');
require_once('../src/lib.php');

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");;
header("Content-Disposition: attachment;filename=hydrometrie.xls");
header("Content-Transfer-Encoding: binary ");

xlsBOF();


//xlsWriteLabel(0,0,"List of car company.");


foreach($header as $key => $val) xlsWriteLabel(0,$key,$val);

// Put data records from mysql by while loop.
$sql = "SELECT * FROM  `hydrometrie`";
$data = exeuteMySQL($sql);
$xlsRow =1;
foreach($data as $key => $val){
	xlsWriteNumber($xlsRow,0,$val[0]);
	xlsWriteLabel($xlsRow,1,$val[1]);
	xlsWriteLabel($xlsRow,2,$val[2]);
	xlsWriteNumber($xlsRow,3,$val[3]);
	xlsWriteNumber($xlsRow,4,$val[4]);
	xlsWriteLabel($xlsRow,5,$val[5]);
	xlsWriteLabel($xlsRow,6,$val[6]);
	xlsWriteLabel($xlsRow,7,$val[7]);
	xlsWriteLabel($xlsRow,8,$val[8]);

	$xlsRow++;
}
xlsEOF();
exit();

?>