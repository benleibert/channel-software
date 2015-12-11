<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */

//--------- UNITES -----------------------------

function ligneConEtatStock($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 	? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['livraison'])		? $livraison		 	= $data[$i-1]['livraison'] 			: $livraison			='');
		(isset($data[$i-1]['recondentree'])		? $recondentree		 	= $data[$i-1]['recondentree'] 			: $recondentree			='');

		(isset($data[$i-1]['reportentree'])		? $reportentree		 	= $data[$i-1]['reportentree'] 			: $reportentree			='');
		(isset($data[$i-1]['reportsortie'])		? $reportsortie		 	= $data[$i-1]['reportsortie'] 			: $reportsortie			='');

		(isset($data[$i-1]['transfertent'])		? $transfertent		= $data[$i-1]['transfertent'] 			: $transfertent			='');
		(isset($data[$i-1]['transfertsort'])	? $transfertsort	= $data[$i-1]['transfertsort'] 			: $transfertsort			='');

		(isset($data[$i-1]['bonsortie']) && $data[$i-1]['bonsortie']!=0			? $bonsortie		= $data[$i-1]['bonsortie'] 			: $bonsortie			='');
		(isset($data[$i-1]['transfertent'])  && $data[$i-1]['transfertent']!=0		? $transfertent		= $data[$i-1]['transfertent'] 			: $transfertent		='');
		(isset($data[$i-1]['transfertsort'])  && $data[$i-1]['transfertsort']!=0		? $transfertsort		= $data[$i-1]['transfertsort'] 			: $transfertsort		='');
		(isset($data[$i-1]['recondsortie'])	 && $data[$i-1]['recondsortie']!=0	? $recondsortie	 	= $data[$i-1]['recondsortie'] 		: $recondsortie		='');
		(isset($data[$i-1]['declass'])  && $data[$i-1]['declass']!=0			? $declass		 	= $data[$i-1]['declass'] 			: $declass			='');
		(isset($data[$i-1]['ecart']) && $data[$i-1]['ecart']!=0					? $ecart		 	= $data[$i-1]['ecart'] 			: $ecart			='');

		(isset($data[$i-1]['qteentre']) && $data[$i-1]['qteentre']!=0 ? $qteentre		= $data[$i-1]['qteentre'] 		: $qteentre	='');
		(isset($data[$i-1]['qtesortie']) && $data[$i-1]['qtesortie']!=0 ? $qtesortie 	= $data[$i-1]['qtesortie'] 		: $qtesortie		='');
		(isset($data[$i-1]['stocks'])	? $stocks 		= $data[$i-1]['stocks'] 		: $stocks			='');
		(isset($data[$i-1]['unite']) 	? $unite 		= $data[$i-1]['unite'] 			: $unite		='');

//		(isset($data[$i-1]['qteperime']) && $data[$i-1]['qteperime']!=0			? $qteperime		= $data[$i-1]['qteperime'] 			: $qteperime	='');


		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		$ret .= '
		<tr align="left" valign="middle" class="'.$col.'">
	        <td height="22" class="text" align="center">'.(stripslashes($codeproduit)).'</td>
            <td class="text" nowrap="nowrap" >'.(stripslashes($produit)).'&nbsp;</td>
			<td class="text" align="right" >'.(stripslashes($reportentree)).'&nbsp;</td>
			<td class="text" align="right" >'.(stripslashes($transfertent)).'&nbsp;</td>
			<td class="text" align="right" >'.(stripslashes($livraison)).'&nbsp;</td>
			<td class="text" align="right">'.(stripslashes($qteentre)).'&nbsp;</td>

            <td class="text" align="right" >'.(stripslashes($reportsortie)).'&nbsp;</td>
			<td class="text" align="right" >'.(stripslashes($transfertsort)).'&nbsp;</td>
			<td class="text" align="right" >'.(stripslashes($bonsortie)).'&nbsp;</td>
			<td class="text" align="right" >'.(stripslashes($declass)).'&nbsp;</td>
			<td class="text" align="right">'.(stripslashes($qtesortie)).'&nbsp;</td>

            <td class="text" align="right">'.(stripslashes($stocks)).'&nbsp;</td>
            <td class="text" align="left">'.(stripslashes($unite)).'</td>
        </tr>';
	}
	return $ret;
}

function ligneConEtatStockLot($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['reflot'])			? $reflot 			= $data[$i-1]['reflot'] 		: $reflot	='');
		(isset($data[$i-1]['codeproduit'])		? $codeproduit 		= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 			? $produit 			= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['livraison'])		? $livraison		= $data[$i-1]['livraison'] 		: $livraison			='');
		(isset($data[$i-1]['recondentree'])		? $recondentree		= $data[$i-1]['recondentree'] 	: $recondentree			='');

		(isset($data[$i-1]['reportentree'])		? $reportentree	 	= $data[$i-1]['reportentree'] 	: $reportentree			='');
		(isset($data[$i-1]['reportsortie'])		? $reportsortie		= $data[$i-1]['reportsortie'] 	: $reportsortie			='');

		(isset($data[$i-1]['transfertent'])		? $transfertent		= $data[$i-1]['transfertent'] 	: $transfertent			='');
		(isset($data[$i-1]['transfertsort'])	? $transfertsort	= $data[$i-1]['transfertsort'] 	: $transfertsort			='');

		(isset($data[$i-1]['bonsortie']) && $data[$i-1]['bonsortie']!=0			? $bonsortie		= $data[$i-1]['bonsortie'] 			: $bonsortie			='');
		(isset($data[$i-1]['transfert'])  && $data[$i-1]['transfert']!=0		? $transfert		= $data[$i-1]['transfert'] 			: $transfert		='');
		(isset($data[$i-1]['recondsortie'])	 && $data[$i-1]['recondsortie']!=0	? $recondsortie	 	= $data[$i-1]['recondsortie'] 		: $recondsortie		='');
		(isset($data[$i-1]['declass'])  && $data[$i-1]['declass']!=0			? $declass		 	= $data[$i-1]['declass'] 			: $declass			='');
		(isset($data[$i-1]['ecart']) && $data[$i-1]['ecart']!=0					? $ecart		 	= $data[$i-1]['ecart'] 				: $ecart			='');
//		(isset($data[$i-1]['qteperime']) && $data[$i-1]['qteperime']!=0			? $qteperime		= $data[$i-1]['qteperime'] 			: $qteperime	='');

		(isset($data[$i-1]['perte']) && $data[$i-1]['perte']!=0		? $perte		 	= $data[$i-1]['perte'] 			: $perte			='');

		(isset($data[$i-1]['qteentre']) && $data[$i-1]['qteentre']!=0 ? $qteentre		= $data[$i-1]['qteentre'] 		: $qteentre	='');
		(isset($data[$i-1]['qtesortie']) && $data[$i-1]['qtesortie']!=0 ? $qtesortie 	= $data[$i-1]['qtesortie'] 		: $qtesortie		='');
		(isset($data[$i-1]['stocks'])	? $stocks 		= $data[$i-1]['stocks'] 		: $stocks			='');
		(isset($data[$i-1]['unite']) 	? $unite 		= $data[$i-1]['unite'] 			: $unite		='');

		(isset($data[$i-1]['sortieNV']) && $data[$i-1]['sortieNV']!=0 ? $sortieNV 	= $data[$i-1]['sortieNV'] 		: $sortieNV		='');


		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		$ret .= '
		<tr align="left" valign="middle" class="'.$col.'">
	        <td height="22" class="text" align="left">'.(stripslashes($reflot)).'</td>
            <td height="22" class="text" align="left">'.(stripslashes($codeproduit)).'</td>
            <td class="text" nowrap="nowrap" >'.(stripslashes($produit)).'&nbsp;</td>
			<td class="text" align="right" >'.(stripslashes($reportentree)).'&nbsp;</td>
			<td class="text" align="right" >'.(stripslashes($transfertent)).'&nbsp;</td>
			<td class="text" align="right" >'.(stripslashes($livraison)).'&nbsp;</td>
			<td class="text" align="right">'.(stripslashes($qteentre)).'&nbsp;</td>

            <td class="text" align="right" >'.(stripslashes($reportsortie)).'&nbsp;</td>
			<td class="text" align="right" >'.(stripslashes($transfertsort)).'&nbsp;</td>
			<td class="text" align="right" >'.(stripslashes($bonsortie)).'&nbsp;</td>
			<td class="text" align="right" >'.(stripslashes($declass)).'&nbsp;</td>
			<td class="text" align="right">'.(stripslashes($qtesortie)).'&nbsp;</td>
			<td class="text" align="right">'.(stripslashes($stocks)).'&nbsp;</td>
            <td class="text" align="left">'.(stripslashes($unite)).'</td>
        </tr>';
	}
	return $ret;
}

function ligneConrapstockactuelLot($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['reflot'])			? $reflot 			= $data[$i-1]['reflot'] 		: $reflot	='');
		(isset($data[$i-1]['codeproduit'])		? $codeproduit 		= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 			? $produit 			= $data[$i-1]['produit'] 		: $produit		='');

		(isset($data[$i-1]['perte']) && $data[$i-1]['perte']!=0				? $perte	 	= number_format($data[$i-1]['perte'],0,'',' ') 		: $perte		='');
		(isset($data[$i-1]['qteperime']) && $data[$i-1]['qteperime']!=0		? $qteperime	= number_format($data[$i-1]['qteperime'],0,'',' ') 	: $qteperime	='');
		(isset($data[$i-1]['qteentre']) && $data[$i-1]['qteentre']!=0 		? $qteentre		= number_format($data[$i-1]['qteentre'],0,'',' ') 	: $qteentre	='');
		(isset($data[$i-1]['stocks']) && $data[$i-1]['stocks']!=0			? $stocks 		= number_format($data[$i-1]['stocks'],0,'',' ') 	: $stocks			='');
		(isset($data[$i-1]['unite']) 										? $unite 		= $data[$i-1]['unite'] 								: $unite		='');

		(isset($data[$i-1]['dateentree']) && $data[$i-1]['dateentree']!='' 	? $dateentree 	= $data[$i-1]['dateentree'] 						: $dateentree	='');
		(isset($data[$i-1]['dateperemp']) && $data[$i-1]['dateperemp']!='' 	? $dateperemp 	= $data[$i-1]['dateperemp'] 						: $dateperemp	='');
		(isset($data[$i-1]['pa'])  && $data[$i-1]['pa']						? $pa 			= number_format($data[$i-1]['pa'],0,'',' ') 		: $pa	=0);
		(isset($data[$i-1]['perime']) && $data[$i-1]['perime']!=0  			? $qteperime 	= number_format($data[$i-1]['perime'],0,'',' ') 	: $qteperime	='');

		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		if($dateperemp<date('Y-m-d'))  $col="tableFINIRow" ;
		$d = preg_replace('/-/','/' ,$dateperemp );
		$d = substr($d,0, 7);

		$montant = $data[$i-1]['pa'] * $data[$i-1]['qteentre'];
		$ret .= '
		<tr align="left" valign="middle" class="'.$col.'">

			<td class="text" align="left" nowrap="nowrap">'.stripslashes($reflot).'&nbsp;</td>
			<td class="text" align="center" nowrap="nowrap">'.stripslashes($codeproduit).'&nbsp;</td>
			<td class="text" align="left" nowrap="nowrap">'.stripslashes($produit).'&nbsp;</td>
			<td class="text" align="center">'.stripslashes($dateentree).'&nbsp;</td>
			<td class="text" align="center">'.stripslashes($d).'&nbsp;</td>
            <td class="text" align="right">'.stripslashes($pa).'&nbsp;</td>
			<td class="text" align="right">'.stripslashes($qteentre).'&nbsp;</td>
			<td class="text" align="right">'.stripslashes(number_format($montant,0,'',' ')).'&nbsp;</td>
        </tr>';
	}
	return $ret;
}

function ligneConrapdtaillEntree($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['reflot'])			? $reflot 			= $data[$i-1]['reflot'] 		: $reflot	='');
		(isset($data[$i-1]['codeproduit'])		? $codeproduit 		= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 			? $produit 			= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['nature']) 			? $nature 			= $data[$i-1]['nature'] 		: $nature		='');

		(isset($data[$i-1]['perte']) && $data[$i-1]['perte']!=0				? $perte	 	= number_format($data[$i-1]['perte'],0,'',' ') 		: $perte		='');
		(isset($data[$i-1]['qteperime']) && $data[$i-1]['qteperime']!=0		? $qteperime	= number_format($data[$i-1]['qteperime'],0,'',' ') 	: $qteperime	='');
		(isset($data[$i-1]['qteentre']) && $data[$i-1]['qteentre']!=0 		? $qteentre		= number_format($data[$i-1]['qteentre'],0,'',' ') 	: $qteentre	='');
		(isset($data[$i-1]['stocks']) && $data[$i-1]['stocks']!=0			? $stocks 		= number_format($data[$i-1]['stocks'],0,'',' ') 	: $stocks			='');
		(isset($data[$i-1]['unite']) 										? $unite 		= $data[$i-1]['unite'] 								: $unite		='');

		(isset($data[$i-1]['dateentree']) && $data[$i-1]['dateentree']!='' 	? $dateentree 	= $data[$i-1]['dateentree'] 						: $dateentree	='');
		(isset($data[$i-1]['dateperemp']) && $data[$i-1]['dateperemp']!='' 	? $dateperemp 	= $data[$i-1]['dateperemp'] 						: $dateperemp	='');
		(isset($data[$i-1]['pa'])  && $data[$i-1]['pa']						? $pa 			= number_format($data[$i-1]['pa'],2,',',' ') 		: $pa	=0);
		(isset($data[$i-1]['perime']) && $data[$i-1]['perime']!=0  			? $qteperime 	= number_format($data[$i-1]['perime'],0,'',' ') 	: $qteperime	='');

		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		if($dateperemp<date('Y-m-d'))  $col="tableFINIRow" ;
		$d = preg_replace('/-/','/' ,$dateperemp );
		$d = substr($d,0, 7);

		$montant = $data[$i-1]['pa'] * $data[$i-1]['qteentre'];
		$ret .= '
		<tr align="left" valign="middle" class="'.$col.'">
			<td class="text" align="left" nowrap="nowrap">'.stripslashes($reflot).'&nbsp;</td>
			<td class="text" align="center" nowrap="nowrap">'.stripslashes($codeproduit).'&nbsp;</td>
			<td class="text" align="left" nowrap="nowrap">'.stripslashes($produit).'&nbsp;</td>
			<td class="text" align="left" nowrap="nowrap">'.stripslashes($nature).'&nbsp;</td>
			<td class="text" align="center">'.stripslashes($dateentree).'&nbsp;</td>
			<td class="text" align="center">'.stripslashes($d).'&nbsp;</td>
            <td class="text" align="right">'.stripslashes($pa).'&nbsp;</td>
			<td class="text" align="right">'.stripslashes($qteentre).'&nbsp;</td>
			<td class="text" align="right">'.stripslashes(number_format($montant,2,',',' ')).'&nbsp;</td>
        </tr>';
	}
	return $ret;
}

function ligneConraprupture($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['reflot'])		? $reflot 		= $data[$i-1]['reflot'] 		: $reflot		='');
		(isset($data[$i-1]['codeproduit'])	? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 		? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['jour'])			? $jour			= $data[$i-1]['jour'] 			: $jour			='');
		(isset($data[$i-1]['semaine'])		? $semaine		= floor($data[$i-1]['semaine']) 		: $semaine		='');
		(isset($data[$i-1]['mois'])			? $mois			= floor($data[$i-1]['mois']) 			: $mois			='');

		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		$ret .= '
		<tr align="left" valign="middle" class="'.$col.'">
			<td class="text" align="center" nowrap="nowrap">'.stripslashes($codeproduit).'&nbsp;</td>
			<td class="text" align="left" nowrap="nowrap">'.stripslashes($produit).'&nbsp;</td>
			<td class="text" align="right">'.stripslashes($jour).'&nbsp;</td>
			<td class="text" align="right">'.stripslashes($semaine).'&nbsp;</td>
            <td class="text" align="right">'.stripslashes($mois).'</td>
        </tr>';
	}
	return $ret;
}

function ligneConrapstocksupseuilmax($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['reflot'])		? $reflot= $data[$i-1]['reflot'] 	: $reflot		='');
		(isset($data[$i-1]['codeproduit'])	? $codeproduit= $data[$i-1]['codeproduit'] 	: $codeproduit='');
		(isset($data[$i-1]['produit']) 		? $produit = $data[$i-1]['produit'] 	: $produit='');
		(isset($data[$i-1]['stocks'])		? $stock= $data[$i-1]['stocks'] : $stock='');
		(isset($data[$i-1]['max'])		? $max= $data[$i-1]['max'] 	: $max='');
		(isset($data[$i-1]['unite'])	? $unite= $data[$i-1]['unite'] 	: $unite='');
		(isset($data[$i-1]['cmm'])	? $cmm= $data[$i-1]['cmm'] 	: $cmm='');
		(isset($data[$i-1]['moisdispo'])	? $moisdispo= $data[$i-1]['moisdispo'] 	: $moisdispo='');

		($stock>0 ?	$stock = number_format($stock,0,',', ' ') : $stock='');
		($max>0 ?	$max = number_format($max,0,',', ' ') : $max='');
		($cmm>0 ?	$cmm = number_format($cmm,2,',', ' ') : $cmm='');
		($moisdispo>0 ?	$moisdispo = number_format($moisdispo,0,',', ' ') : $moisdispo='');
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
//if ($stock>$max) 
//{
		$ret .= '
		<tr align="left" valign="middle" class="'.$col.'">
			<td class="text" align="center" nowrap="nowrap">'.stripslashes($codeproduit).'&nbsp;</td>
			<td class="text" align="left" nowrap="nowrap">'.stripslashes($produit).'&nbsp;</td>
			<td class="text" align="right">'.stripslashes($cmm).'&nbsp;</td>
			<td class="text" align="right">'.stripslashes($max).'&nbsp;</td>
			<td class="text" align="right">'.stripslashes($moisdispo).'&nbsp;</td>
			<td class="text" align="right">'.stripslashes($stock).'&nbsp;</td>
            <td class="text" align="left">'.stripslashes($unite).'</td>
        </tr>';
//}
	}
	return $ret;
}

function ligneConrapsyntheseinventaire($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])		? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 			? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['qteentree']) && $data[$i-1]['qteentree']!=0		? $qteentree	= number_format($data[$i-1]['qteentree'],0,'',' ') 		: $qteentree	='');
		(isset($data[$i-1]['qtesortie']) && $data[$i-1]['qtesortie']!=0		? $qtesortie	= number_format($data[$i-1]['qtesortie'],0,'',' ') 		: $qtesortie	='');
		(isset($data[$i-1]['qteperime']) && $data[$i-1]['qteperime']!=0		? $qteperime	= number_format($data[$i-1]['qteperime'],0,'',' ') 		: $qteperime	='');
		(isset($data[$i-1]['stocks']) && $data[$i-1]['stocks']!=0			? $stocks		= number_format($data[$i-1]['stocks'],0,'',' ') 		: $stocks		='');
		(isset($data[$i-1]['seuilmin'])	&& $data[$i-1]['seuilmin']!=0		? $seuilmin		= number_format($data[$i-1]['seuilmin'],0,'',' ') 		: $seuilmin		='');
		(isset($data[$i-1]['seuilmax'])	&& $data[$i-1]['seuilmax']!=0		? $seuilmax		= number_format($data[$i-1]['seuilmax'],0,'',' ') 		: $seuilmax		='');


		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

//		if($dateperemp<date('Y-m-d'))  $col="tableFINIRow" ;
//		$d = preg_replace('/-/','/' ,$dateperemp );
//		$d = substr($d,0, 7);
//
		$ret .= '
		<tr align="left" valign="middle" class="'.$col.'">

			<td class="text" align="center" nowrap="nowrap">'.stripslashes($codeproduit).'&nbsp;</td>
			<td class="text" align="left" nowrap="nowrap">'.stripslashes($produit).'&nbsp;</td>
			<td class="text" align="right">'.stripslashes($qteentree).'&nbsp;</td>
			<td class="text" align="right">'.stripslashes($qtesortie).'&nbsp;</td>
            <td class="text" align="right">'.stripslashes($qteperime).'</td>
	        <td class="text" align="right">'.stripslashes($stocks).'&nbsp;</td>
            <td class="text" align="right">'.stripslashes($seuilmax).'&nbsp;</td>
			<td class="text" align="right">'.stripslashes($seuilmin).'&nbsp;</td>
        </tr>';
	}
	return $ret;
}

function ligneConrapmvtdestinaire($nbre=1, $dibenef, $beneficiiare, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['reflot'])		? $reflot 	= $data[$i-1]['reflot'] 	: $reflot	='');
		(isset($data[$i-1]['codeproduit'])		? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 			? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['nature']) 			? $nature 		= $data[$i-1]['nature'] 		: $nature		='');
		(isset($data[$i-1]['dateperemp']) 		? $dateperemp	= $data[$i-1]['dateperemp']		: $dateperemp	='');
		(isset($data[$i-1]['datesortie'])		? $datesortie	= $data[$i-1]['datesortie'] 	: $datesortie	='');
		(isset($data[$i-1]['datevalid'])		? $datevalid	= $data[$i-1]['datevalid'] 		: $datevalid	='');
		(isset($data[$i-1]['qte']) && $data[$i-1]['qte']!=0			? $qte	= number_format($data[$i-1]['qte'],0,'',' ') 		: $qte	='');


		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

				if($dateperemp<date('Y-m-d'))  $col="tableFINIRow" ;
				$d = preg_replace('/-/','/' ,$dateperemp );
				$d = substr($d,0, 7);

		$ret .= '
		<tr align="left" valign="middle" class="'.$col.'">

			<td class="text" align="center" nowrap="nowrap">'.stripslashes($reflot).'&nbsp;</td>
			<td class="text" align="center" nowrap="nowrap">'.stripslashes($codeproduit).'&nbsp;</td>
			<td class="text" align="left" nowrap="nowrap">'.stripslashes($produit).'&nbsp;</td>
			<td class="text" align="left" nowrap="nowrap">'.stripslashes($nature).'&nbsp;</td>
			<td class="text" align="center">'.stripslashes(frFormat2($datevalid)).'&nbsp;</td>
            <td class="text" align="center">'.stripslashes(frFormat2($datesortie)).'&nbsp;</td>
            <td class="text" align="right">'.stripslashes($qte).'&nbsp;</td>
            <td class="text" align="center">'.stripslashes($d).'</td>
       </tr>';
	}
	return $ret;
}

function ligneConrapmvtfournisseur($nbre=1, $idfournisseur, $fournisseur, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['reflot'])		? $reflot 	= $data[$i-1]['reflot'] 	: $reflot	='');
		(isset($data[$i-1]['codeproduit'])		? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 			? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['dateperemp']) 		? $dateperemp	= $data[$i-1]['dateperemp']		: $dateperemp	='');
		(isset($data[$i-1]['dateentre'])		? $dateentre	= $data[$i-1]['dateentre'] 		: $dateentre	='');
		(isset($data[$i-1]['qte']) && $data[$i-1]['qte']!=0			? $qte	= number_format($data[$i-1]['qte'],0,'',' ') 		: $qte	='');


		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		if($dateperemp<date('Y-m-d'))  $col="tableFINIRow" ;
		$d = preg_replace('/-/','/' ,$dateperemp );
		$d = substr($d,0, 7);

		$ret .= '
		<tr align="left" valign="middle" class="'.$col.'">

			<td class="text" align="center" nowrap="nowrap">'.stripslashes($reflot).'&nbsp;</td>
			<td class="text" align="center" nowrap="nowrap">'.stripslashes($codeproduit).'&nbsp;</td>
			<td class="text" align="left" nowrap="nowrap">'.stripslashes($produit).'&nbsp;</td>
			<td class="text" align="center">'.stripslashes(frFormat2($dateentre)).'&nbsp;</td>
            <td class="text" align="right">'.stripslashes($qte).'&nbsp;</td>
            <td class="text" align="center">'.stripslashes($d).'</td>
       </tr>';
	}
	return $ret;
}

function ligneConrapprdperime($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['reflot'])		? $reflot 	= $data[$i-1]['reflot'] 	: $reflot	='');
		(isset($data[$i-1]['codeproduit'])		? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 			? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['dateperemp']) 		? $dateperemp	= $data[$i-1]['dateperemp']		: $dateperemp	='');
		(isset($data[$i-1]['dateentree'])		? $dateentree	= $data[$i-1]['dateentree'] 	: $dateentree	='');
		(isset($data[$i-1]['qte']) && $data[$i-1]['qte']!=0			? $qte	= number_format($data[$i-1]['qte'],0,'',' ') 		: $qte	='');


		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

//		if($dateperemp<date('Y-m-d'))  $col="tableFINIRow" ;
		$d = preg_replace('/-/','/' ,$dateperemp );
		$d = substr($d,0, 7);

		$ret .= '
		<tr align="left" valign="middle" class="'.$col.'">

			<td class="text" align="center" nowrap="nowrap">'.stripslashes($reflot).'&nbsp;</td>
			<td class="text" align="center" nowrap="nowrap">'.stripslashes($codeproduit).'&nbsp;</td>
			<td class="text" align="left" nowrap="nowrap">'.stripslashes($produit).'&nbsp;</td>
			<td class="text" align="center">'.stripslashes(frFormat2($dateentree)).'&nbsp;</td>
            <td class="text" align="center">'.stripslashes($d).'</td>
            <td class="text" align="right">'.stripslashes($qte).'&nbsp;</td>
       </tr>';
	}
	return $ret;
}

function ligneConrapproduitaperime($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['reflot'])		? $reflot 	= $data[$i-1]['reflot'] 	: $reflot	='');
		(isset($data[$i-1]['codeproduit'])		? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 			? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['dateperemption']) 		? $dateperemp	= $data[$i-1]['dateperemption']		: $dateperemp	='');
		(isset($data[$i-1]['stock']) 		? $stock	= $data[$i-1]['stock']		: $stock	='');

(isset($_POST['datefin']) && $_POST['datefin']!=''  ? $datefin 	= trim($_POST['datefin']) : $datefin = '');
$datedebut =  $_SESSION['GL_USER']['DEBUT_EXERCICE'];

//$whereAll0=" mouvement.CODE_PRODUIT LIKE $codeproduit AND mouvement.MVT_DATEPEREMP >=$datedebut AND mouvement.MVT_DATEPEREMP <=$datefin" ;

//$stock1=listeDesProduitsStockparproduit($defaut='', $whereAll0);
//$stock=$stock1['QTE'];
//$stock=listeDesProduitsStockparproduit($defaut='', $whereAll);
//echo $stock;
//break;
		($stock>0 ?	$stock = number_format($stock,0,',', ' ') : $stock='');

		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		$ret .= '
		<tr align="left" valign="middle" class="'.$col.'">

			<td class="text" align="center" nowrap="nowrap">'.stripslashes($reflot).'&nbsp;</td>
			<td class="text" align="center" nowrap="nowrap">'.stripslashes($codeproduit).'&nbsp;</td>
			<td class="text" align="left" nowrap="nowrap">'.stripslashes($produit).'&nbsp;</td>
			<td class="text" align="right">'.stripslashes($stock).'&nbsp;</td>
			<td class="text" align="center">'.stripslashes(frFormat2($dateperemp)).'&nbsp;</td>
       </tr>';
	}
	return $ret;
}

function ligneConrapmvtstock($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['reflot'])		? $reflot 	= $data[$i-1]['reflot'] 	: $reflot	='');
		(isset($data[$i-1]['codeproduit'])		? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 			? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['nature']) 			? $nature 		= $data[$i-1]['nature'] 		: $nature		='');
		(isset($data[$i-1]['dateperemp']) 		? $dateperemp	= $data[$i-1]['dateperemp']		: $dateperemp	='');
		(isset($data[$i-1]['dateentree'])		? $dateentree	= $data[$i-1]['dateentree'] 	: $dateentree	='');
		(isset($data[$i-1]['declass']) && $data[$i-1]['declass']!=0			? $declass	= $data[$i-1]['declass'] 		: $declass	='');
		(isset($data[$i-1]['qteentree']) && $data[$i-1]['qteentree']!=0			? $qteentree	= $data[$i-1]['qteentree'] 		: $qteentree	='');
		(isset($data[$i-1]['qtesortie']) && $data[$i-1]['qtesortie']!=0			? $qtesortie	= $data[$i-1]['qtesortie'] 		: $qtesortie	='');
		(isset($data[$i-1]['ecart']) && $data[$i-1]['ecart']!=0			? $ecart	= $data[$i-1]['ecart']		: $ecart	='');
		(isset($data[$i-1]['stocks']) && $data[$i-1]['stocks']!=0			? $stocks	= $data[$i-1]['stocks']		: $stocks	='');


		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		//		if($dateperemp<date('Y-m-d'))  $col="tableFINIRow" ;
		//$d = preg_replace('/-/','/' ,$dateperemp );
		//$d = substr($d,0, 7);

		$ret .= '
		<tr align="left" valign="middle" class="'.$col.'">

			<td class="text" align="center" nowrap="nowrap">'.stripslashes($codeproduit).'&nbsp;</td>
			<td class="text" align="left" nowrap="nowrap">'.stripslashes($produit).'&nbsp;</td>
			<td class="text" align="right">'.stripslashes($qteentree).'&nbsp;</td>
            <td class="text" align="right">'.stripslashes($qtesortie).'</td>
			<td class="text" align="right">'.stripslashes($declass).'&nbsp;</td>
			<td class="text" align="right">'.stripslashes($stocks).'&nbsp;</td>
       </tr>';
	}
	return $ret;
}

function ligneConrapsortiemensuelle($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])		? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 			? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['qtesortie12']) && $data[$i-1]['qtesortie12']!=0	? $qtesortie12	= number_format($data[$i-1]['qtesortie12'],0,'',' ') 		: $qtesortie12	='');
		(isset($data[$i-1]['qtesortie6']) && $data[$i-1]['qtesortie6']!=0	? $qtesortie6	= number_format($data[$i-1]['qtesortie6'],0,'',' ') 		: $qtesortie6	='');
		(isset($data[$i-1]['qtesortie3']) && $data[$i-1]['qtesortie3']!=0	? $qtesortie3	= number_format($data[$i-1]['qtesortie3'],0,'',' ') 		: $qtesortie3	='');


		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		//		if($dateperemp<date('Y-m-d'))  $col="tableFINIRow" ;
		//$d = preg_replace('/-/','/' ,$dateperemp );
		//$d = substr($d,0, 7);

		$ret .= '
		<tr align="left" valign="middle" class="'.$col.'">

			<td class="text" align="center" nowrap="nowrap">'.stripslashes($codeproduit).'&nbsp;</td>
			<td class="text" align="left" nowrap="nowrap">'.stripslashes($produit).'&nbsp;</td>
			<td class="text" align="right">'.stripslashes($qtesortie12).'&nbsp;</td>
            <td class="text" align="right">'.stripslashes($qtesortie6).'</td>
			<td class="text" align="right">'.stripslashes($qtesortie3).'&nbsp;</td>
       </tr>';
	}
	return $ret;
}

function ligneConrapsortiemoymensuelle($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])		? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 			? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['qtesortie12']) && $data[$i-1]['qtesortie12']!=0	? $qtesortie12	= number_format($data[$i-1]['qtesortie12'],0,'',' ') 		: $qtesortie12	='');
		(isset($data[$i-1]['qtesortie6']) && $data[$i-1]['qtesortie6']!=0	? $qtesortie6	= number_format($data[$i-1]['qtesortie6'],0,'',' ') 		: $qtesortie6	='');
		(isset($data[$i-1]['qtesortie3']) && $data[$i-1]['qtesortie3']!=0	? $qtesortie3	= number_format($data[$i-1]['qtesortie3'],0,'',' ') 		: $qtesortie3	='');


		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		//		if($dateperemp<date('Y-m-d'))  $col="tableFINIRow" ;
		//$d = preg_replace('/-/','/' ,$dateperemp );
		//$d = substr($d,0, 7);

		$ret .= '
		<tr align="left" valign="middle" class="'.$col.'">

			<td class="text" align="center" nowrap="nowrap">'.stripslashes($codeproduit).'&nbsp;</td>
			<td class="text" align="left" nowrap="nowrap">'.stripslashes($produit).'&nbsp;</td>
			<td class="text" align="right">'.stripslashes($qtesortie12).'&nbsp;</td>
            <td class="text" align="right">'.stripslashes($qtesortie6).'</td>
			<td class="text" align="right">'.stripslashes($qtesortie3).'&nbsp;</td>
       </tr>';
	}
	return $ret;
}

//Nombre de ligne retourner
function nombreJournal($where=''){
	$sql = "SELECT mouvement.`CODE_PRODUIT`,`MVT_NATURE`,`MVT_TIME`, `MVT_VALID`, `MVT_UNITE`, MVT_DATE, `MVT_QUANTITE`,
	`CODE_MAGASIN`, ID_SOURCE, produit.PRD_LIBELLE
	 	FROM mouvement INNER JOIN produit ON (mouvement.CODE_PRODUIT LIKE produit.CODE_PRODUIT) $where;";
	try {
		$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
	}
	catch (PDOException $error) { //Treat error
		//("Erreur de connexion : " . $error->getMessage() );
		header('location:errorPage.php');
	}
	$query =  $cnx->prepare($sql); //Prepare the SQL
	$query->execute(); //Execute prepared SQL => $query
	return $query->rowCount();
}

function ligneConJournal($wh='', $ord='', $sens='ASC', $page=1, $nelt){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	$returnHTML = '';
	$returnTble = array();//HTML, nbreTotal,

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY MVT_DATEVALID DESC");
	//Nombre d'éléments
	$returnTble['NE'] = nombreJournal($where);
	if($returnTble['NE']>0){
		//Calcule des limites
		$i = ($page-1)*$nelt;
	 	$sql = "SELECT mouvement.`CODE_PRODUIT`,`MVT_NATURE`,`MVT_TIME`, `MVT_VALID`, `MVT_UNITE`, MVT_DATE, `MVT_QUANTITE`,MVT_REFLOT,
	 	`CODE_MAGASIN`, ID_SOURCE, produit.PRD_LIBELLE,MVT_DATEPEREMP
	 	FROM mouvement INNER JOIN produit ON (mouvement.CODE_PRODUIT LIKE produit.CODE_PRODUIT) $where $order LIMIT $i, $nelt;";
		//Exécution
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		$i = 0;
		$_SESSION['DATA_ETAT']['ligne']=array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

			if($row['MVT_VALID']=='0' ) {
				$imgCl = '<img src="../images/encours.gif" title="En cours" width="16" height="16">' ;
			}
			elseif ($row['MVT_VALID']=='2' ){
				$imgCl = '<img src="../images/cancel.png" title="Annulée" width="16" height="16">' ;
			}
			else {
				$imgCl ='<img src="../images/valider.gif" title="Validée" width="16" height="16">';
			}
			$datemvt = frFormat2($row['MVT_DATE']);
			array_push($_SESSION['DATA_ETAT']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>$row['PRD_LIBELLE'], 'reflot'=>$row['MVT_REFLOT'],'nature'=>$row['MVT_NATURE'], 'qte'=>$row['MVT_QUANTITE'], 'datemvt'=>$datemvt, 'timemvt'=>$row['MVT_TIME'], 'valide'=>$row['MVT_VALID'], 'unite'=>$row['MVT_UNITE']));

			if($row['MVT_DATEPEREMP']<date('Y-m-d'))  $col="tableFINIRow" ;
			//$d = preg_replace('/-/','/' ,t2($row['MVT_DATEPEREMP'] );
			//$d = substr($d,0, 7);
			$d = frFormat2($row['MVT_DATEPEREMP']);

			$i++;
			$returnHTML .= '
			<tr align="left" valign="middle" class="'.$col.'">
				<td class="text" align="center" nowrap>'.$i.'</td>
	        	<td height="22" class="text" align="center">'.$imgCl.'</td>
	        	<td height="22" class="text" align="center">'.(stripslashes($row['CODE_PRODUIT'])).'</td>
            	<td class="text" >'.(stripslashes($row['PRD_LIBELLE'])).'</td>
				<td class="text" align="left">'.(stripslashes($row['MVT_REFLOT'])).'</td>
				<td class="text" align="center">'.(stripslashes($d)).'</td>
            	<td class="text" align="left">'.(stripslashes($row['MVT_NATURE'])).'</td>
				<td class="text" align="center">'.(stripslashes($row['ID_SOURCE'])).'</td>
            	<td class="text" align="center">'.(stripslashes(frFormat2($row['MVT_DATE']))).'</td>
            	<td class="text" align="center">'.(stripslashes($row['MVT_TIME'])).'</td>
            	<td class="text" align="right">'.(stripslashes($row['MVT_QUANTITE'])).'</td>
            	<td class="text" align="left">'.(stripslashes($row['MVT_UNITE'])).'</td>
       		</tr>';;
		}
		$_SESSION['DATA_ETAT']['nbreLigne'] = $i;

	}
	else {
	if($ilang=='1' && $ilang!='') {$returnHTML .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e...</td></tr>';}
	if($ilang=='2' && $ilang!='') {$returnHTML .= '<tr><td colspan="4" class="text">No data...</td></tr>';}
	if($ilang=='3' && $ilang!='') {$returnHTML .= '<tr><td colspan="4" class="text">Nenhum dado...</td></tr>';}
	}

	$returnTble['L']=$returnHTML;
	return $returnTble;
}

function ligneAnalyseJournal($nbre=1, $data=array()){
	$returnHTML = '';
	$i=0;

	foreach($data as $key=>$row){
		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		if($row['MVT_VALID']=='0' ) {
			$imgCl = '<img src="../images/encours.gif" title="En cours" width="16" height="16">' ;
		}
		elseif ($row['MVT_VALID']=='2' ){
			$imgCl = '<img src="../images/cancel.png" title="Annulée" width="16" height="16">' ;
		}
		else {
			$imgCl ='<img src="../images/valider.gif" title="Validée" width="16" height="16">';
		}

		$returnHTML .= '
				<tr align="left" valign="middle" class="'.$col.'">
		        	<td height="22" class="text" align="center">'.$imgCl.'</td>
		        	<td height="22" class="text" align="center">'.(stripslashes($row['CODE_PRODUIT'])).'</td>
	            	<td class="text" >'.(stripslashes(getConditionnement($row['CODE_PRODUIT']))).'</td>
	            	<td class="text" align="left">'.(stripslashes($row['MVT_NATURE'])).'</td>
					<td class="text" align="left">'.(stripslashes($row['ID_SOURCE'])).'</td>
					<td class="text" align="left">'.(stripslashes(getService($row['CODE_MAGASIN']))).'</td>
	            	<td class="text" align="center">'.(stripslashes(frFormat2($row['MVT_DATE']))).'</td>
	            	<td class="text" align="center">'.(stripslashes($row['MVT_TIME'])).'</td>
	            	<td class="text" align="right">'.(stripslashes($row['MVT_QUANTITE'])).'</td>
	            	<td class="text" align="left">'.(stripslashes($row['MVT_UNITE'])).'</td>
	       		</tr>';;
		$i++;
	}
	return $returnHTML;
}

function ligneConRapDetailleSortie($wh='', $ord='', $sens='ASC', $page=1, $nelt){
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
	$returnHTML = '';
	$returnTble = array();//HTML, nbreTotal,

	//Where clause
	$where ='';
	(isset($wh) and $wh!='' ? $where = " $wh " : $where = "");
	//Oerder condition
	$order ='';
	(isset($ord) and $ord!=''  ? $order = " ORDER BY $ord $sens" : $order = " ORDER BY MVT_DATEVALID DESC");
	//Nombre d'éléments
	$returnTble['NE'] = nombreJournal($where);
	if($returnTble['NE']>0){
		//Calcule des limites
		$i = ($page-1)*$nelt;
		$sql = "SELECT mouvement.`CODE_PRODUIT`,`MVT_NATURE`,`MVT_TIME`, `MVT_VALID`, `MVT_UNITE`, MVT_DATE, `MVT_QUANTITE`,MVT_REFLOT,
	 	`CODE_MAGASIN`, ID_SOURCE, produit.PRD_LIBELLE,MVT_DATEPEREMP, MVT_PV, MVT_PA
	 	FROM mouvement INNER JOIN produit ON (mouvement.CODE_PRODUIT LIKE produit.CODE_PRODUIT) $where $order LIMIT $i, $nelt;";
		//Exécution
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		$i = 0;
		$_SESSION['DATA_ETAT']['ligne']=array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

			if($row['MVT_VALID']=='0' ) {
				$imgCl = '<img src="../images/encours.gif" title="En cours" width="16" height="16">' ;
			}
			elseif ($row['MVT_VALID']=='2' ){
				$imgCl = '<img src="../images/cancel.png" title="Annulée" width="16" height="16">' ;
			}
			else {
				$imgCl ='<img src="../images/valider.gif" title="Validée" width="16" height="16">';
			}
			$datemvt = frFormat2($row['MVT_DATE']);
			array_push($_SESSION['DATA_ETAT']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>$row['PRD_LIBELLE'], 'reflot'=>$row['MVT_REFLOT'],'nature'=>$row['MVT_NATURE'], 'qte'=>$row['MVT_QUANTITE'], 'prix'=>$row['MVT_PV'],'datemvt'=>$datemvt, 'timemvt'=>$row['MVT_TIME'], 'valide'=>$row['MVT_VALID'], 'unite'=>$row['MVT_UNITE']));

			if($row['MVT_DATEPEREMP']<date('Y-m-d'))  $col="tableFINIRow" ;
			$d = preg_replace('/-/','/' ,$row['MVT_DATEPEREMP'] );
			$d = substr($d,0, 7);

			(isset($row['MVT_PV']) && $row['MVT_PV']>0 ? $prix = $row['MVT_PV'] : $prix=0);
			(isset($row['MVT_QUANTITE']) && $row['MVT_QUANTITE']>0 ? $qte = $row['MVT_QUANTITE'] : $qte =0);

		($prix>0 ?	$Aprix = number_format($prix,0,',', ' ') : $Aprix='');

			$total = $prix*$qte;
		($total>0 ?	$Atotal = number_format($total,0,',', ' ') : $Atotal='');
		($row['MVT_QUANTITE']>0 ?	$row['MVT_QUANTITE'] = number_format($row['MVT_QUANTITE'],0,',', ' ') : $row['MVT_QUANTITE']='');

			$i++;
			$returnHTML .= '
			<tr align="left" valign="middle" class="'.$col.'">
				<td class="text" align="center" nowrap>'.$i.'</td>
	        	<td height="22" class="text" align="left">'.(stripslashes($row['CODE_PRODUIT'])).'</td>
            	<td class="text" >'.(stripslashes($row['PRD_LIBELLE'])).'</td>
				<td class="text" align="left">'.(stripslashes($row['MVT_REFLOT'])).'</td>
            	<td class="text" align="left" nowrap="nowrap">'.(stripslashes($row['MVT_NATURE'])).'</td>
            	<td class="text" align="center">'.(stripslashes(frFormat2($row['MVT_DATE']))).'</td>
            	<td class="text" align="right">'.(stripslashes($row['MVT_QUANTITE'])).'</td>
            	<td class="text" align="left">'.(stripslashes($row['MVT_UNITE'])).'</td>
				<td class="text" align="right">'.(stripslashes($Aprix)).'</td>
				<td class="text" align="right">'.(stripslashes($Atotal)).'</td>
       		</tr>';;
		}
		$_SESSION['DATA_ETAT']['nbreLigne'] = $i;

	}
	else {
	if($ilang=='1' && $ilang!='') {$returnHTML .= '<tr><td colspan="4" class="text">Aucune donn&eacute;e...</td></tr>';}
	if($ilang=='2' && $ilang!='') {$returnHTML .= '<tr><td colspan="4" class="text">No data...</td></tr>';}
	if($ilang=='3' && $ilang!='') {$returnHTML .= '<tr><td colspan="4" class="text">Nenhum dado...</td></tr>';}
	}

	$returnTble['L']=$returnHTML;
	return $returnTble;
}

function ligneConRapportMensuel($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['reflot'])			? $reflot 			= $data[$i-1]['reflot'] 		: $reflot	='');
		(isset($data[$i-1]['codeproduit'])		? $codeproduit 		= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 			? $produit 			= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['livraison'])		? $livraison		= $data[$i-1]['livraison'] 		: $livraison			='');
		(isset($data[$i-1]['recondentree'])		? $recondentree		= $data[$i-1]['recondentree'] 	: $recondentree			='');

		(isset($data[$i-1]['reportentree'])		? $reportentree	 	= $data[$i-1]['reportentree'] 	: $reportentree			='');
		(isset($data[$i-1]['reportsortie'])		? $reportsortie		= $data[$i-1]['reportsortie'] 	: $reportsortie			='');

		(isset($data[$i-1]['transfertent'])		? $transfertent		= $data[$i-1]['transfertent'] 	: $transfertent			='');
		(isset($data[$i-1]['transfertsort'])	? $transfertsort	= $data[$i-1]['transfertsort'] 	: $transfertsort			='');

		(isset($data[$i-1]['bonsortie']) && $data[$i-1]['bonsortie']!=0			? $bonsortie		= $data[$i-1]['bonsortie'] 			: $bonsortie			='');
		(isset($data[$i-1]['transfert'])  && $data[$i-1]['transfert']!=0		? $transfert		= $data[$i-1]['transfert'] 			: $transfert		='');
		(isset($data[$i-1]['recondsortie'])	 && $data[$i-1]['recondsortie']!=0	? $recondsortie	 	= $data[$i-1]['recondsortie'] 		: $recondsortie		='');
		(isset($data[$i-1]['declass'])  && $data[$i-1]['declass']!=0			? $declass		 	= $data[$i-1]['declass'] 			: $declass			='');
		(isset($data[$i-1]['ecart']) && $data[$i-1]['ecart']!=0					? $ecart		 	= $data[$i-1]['ecart'] 				: $ecart			='');
		(isset($data[$i-1]['qteperime']) && $data[$i-1]['qteperime']!=0			? $qteperime		= $data[$i-1]['qteperime'] 			: $qteperime	='');

		(isset($data[$i-1]['perte']) && $data[$i-1]['perte']!=0		? $perte		 	= $data[$i-1]['perte'] 			: $perte			='');

		(isset($data[$i-1]['qteentre']) && $data[$i-1]['qteentre']!=0 ? $qteentre		= $data[$i-1]['qteentre'] 		: $qteentre	='');
		(isset($data[$i-1]['qtesortie']) && $data[$i-1]['qtesortie']!=0 ? $qtesortie 	= $data[$i-1]['qtesortie'] 		: $qtesortie		='');
		(isset($data[$i-1]['stocks'])	? $stocks 		= $data[$i-1]['stocks'] 		: $stocks			='');
		(isset($data[$i-1]['unite']) 	? $unite 		= $data[$i-1]['unite'] 			: $unite		='');

		(isset($data[$i-1]['Pentree'])	? $Pentree 		= $data[$i-1]['Pentree'] 		: $Pentree			='');
		(isset($data[$i-1]['Psortie'])	? $Psortie 		= $data[$i-1]['Psortie'] 		: $Psortie			='');
		(isset($data[$i-1]['Pperte'])	? $Pperte 		= $data[$i-1]['Pperte'] 		: $Pperte			='');
		(isset($data[$i-1]['solde'])	? $solde 		= $data[$i-1]['solde'] 		: $solde			='');


		(isset($data[$i-1]['sortieNV']) && $data[$i-1]['sortieNV']!=0 ? $sortieNV 	= $data[$i-1]['sortieNV'] 		: $sortieNV		='');


		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		($Pentree>0 ?	$Pentree = number_format($Pentree,0,',', ' ') : $Pentree='');
		($Psortie>0 ?	$Psortie = number_format($Psortie,0,',', ' ') : $Psortie='');
		($declass>0 ?	$declass = number_format($declass,0,',', ' ') : $declass='');
		($solde>0 ?	$solde = number_format($solde,0,',', ' ') : $solde='');

		$ret .= '
		<tr align="left" valign="middle" class="'.$col.'">
	        <td height="22" class="text" align="left">'.(stripslashes($codeproduit)).'</td>
            <td class="text" nowrap="nowrap" >'.(stripslashes($produit)).'&nbsp;</td>
			<td class="text" align="right">'.(stripslashes($stocks)).'&nbsp;</td>
			<td class="text" align="right">'.(stripslashes($Pentree)).'&nbsp;</td>
			<td class="text" align="right">'.(stripslashes($Psortie)).'&nbsp;</td>
			<td class="text" align="right">'.(stripslashes($declass)).'&nbsp;</td>
			<td class="text" align="right">'.(stripslashes($solde)).'&nbsp;</td>
            <td class="text" align="left">'.(stripslashes($unite)).'</td>
        </tr>';
	}
	return $ret;
}

function ligneConRapportMensuel2($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['reflot'])			? $reflot 			= $data[$i-1]['reflot'] 		: $reflot	='');
		(isset($data[$i-1]['codeproduit'])		? $codeproduit 		= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 			? $produit 			= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['livraison'])		? $livraison		= $data[$i-1]['livraison'] 		: $livraison			='');
		(isset($data[$i-1]['recondentree'])		? $recondentree		= $data[$i-1]['recondentree'] 	: $recondentree			='');

		(isset($data[$i-1]['reportentree'])		? $reportentree	 	= $data[$i-1]['reportentree'] 	: $reportentree			='');
		(isset($data[$i-1]['reportsortie'])		? $reportsortie		= $data[$i-1]['reportsortie'] 	: $reportsortie			='');

		(isset($data[$i-1]['transfertent'])		? $transfertent		= $data[$i-1]['transfertent'] 	: $transfertent			='');
		(isset($data[$i-1]['transfertsort'])	? $transfertsort	= $data[$i-1]['transfertsort'] 	: $transfertsort			='');

		(isset($data[$i-1]['bonsortie']) && $data[$i-1]['bonsortie']!=0			? $bonsortie		= $data[$i-1]['bonsortie'] 			: $bonsortie			='');
		(isset($data[$i-1]['transfert'])  && $data[$i-1]['transfert']!=0		? $transfert		= $data[$i-1]['transfert'] 			: $transfert		='');
		(isset($data[$i-1]['recondsortie'])	 && $data[$i-1]['recondsortie']!=0	? $recondsortie	 	= $data[$i-1]['recondsortie'] 		: $recondsortie		='');
		(isset($data[$i-1]['declass'])  && $data[$i-1]['declass']!=0			? $declass		 	= $data[$i-1]['declass'] 			: $declass			='');
		(isset($data[$i-1]['ecart']) && $data[$i-1]['ecart']!=0					? $ecart		 	= $data[$i-1]['ecart'] 				: $ecart			='');
		(isset($data[$i-1]['qteperime']) && $data[$i-1]['qteperime']!=0			? $qteperime		= $data[$i-1]['qteperime'] 			: $qteperime	='');

		(isset($data[$i-1]['perte']) && $data[$i-1]['perte']!=0		? $perte		 	= $data[$i-1]['perte'] 			: $perte			='');

		(isset($data[$i-1]['qteentre']) && $data[$i-1]['qteentre']!=0 ? $qteentre		= $data[$i-1]['qteentre'] 		: $qteentre	='');
		(isset($data[$i-1]['qtesortie']) && $data[$i-1]['qtesortie']!=0 ? $qtesortie 	= $data[$i-1]['qtesortie'] 		: $qtesortie		='');
		(isset($data[$i-1]['stocks'])	? $stocks 		= $data[$i-1]['stocks'] 		: $stocks			='');
		(isset($data[$i-1]['unite']) 	? $unite 		= $data[$i-1]['unite'] 			: $unite		='');

		(isset($data[$i-1]['Pentree'])	? $Pentree 		= $data[$i-1]['Pentree'] 		: $Pentree			='');
		(isset($data[$i-1]['Psortie'])	? $Psortie 		= $data[$i-1]['Psortie'] 		: $Psortie			='');
		(isset($data[$i-1]['Pperte'])	? $Pperte 		= $data[$i-1]['Pperte'] 		: $Pperte			='');
		(isset($data[$i-1]['solde'])	? $solde 		= $data[$i-1]['solde'] 		: $solde			='');


		(isset($data[$i-1]['sortieNV']) && $data[$i-1]['sortieNV']!=0 ? $sortieNV 	= $data[$i-1]['sortieNV'] 		: $sortieNV		='');
		($Pentree>0 ?	$Pentree = number_format($Pentree,0,',', ' ') : $Pentree='');
		($Psortie>0 ?	$Psortie = number_format($Psortie,0,',', ' ') : $Psortie='');
		($declass>0 ?	$declass = number_format($declass,0,',', ' ') : $declass='');
		($stocks>0 ?	$stocks = number_format($stocks,0,',', ' ') : $stocks='');
		($solde>0 ?	$solde = number_format($solde,0,',', ' ') : $solde='');


		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		$ret .= '
		<tr align="left" valign="middle" class="'.$col.'">
	        <td height="10" class="text" align="left">'.(stripslashes($reflot)).'</td>
            <td height="15" class="text" align="left">'.(stripslashes($codeproduit)).'</td>
            <td class="text" nowrap="nowrap" >'.(stripslashes($produit)).'&nbsp;</td>
			<td class="text" align="right">'.(stripslashes($stocks)).'&nbsp;</td>
			<td class="text" align="right">'.(stripslashes($Pentree)).'&nbsp;</td>
			<td class="text" align="right">'.(stripslashes($Psortie)).'&nbsp;</td>
			<td class="text" align="right">'.(stripslashes($declass)).'&nbsp;</td>
			<td class="text" align="right">'.(stripslashes($solde)).'&nbsp;</td>
            <td class="text" align="left">'.(stripslashes($unite)).'</td>
        </tr>';
	}
	return $ret;
}

function ligneConRapConsommation($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])? $codeproduit = $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 		? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['qte'])			? $qte 		= $data[$i-1]['qte'] 		: $qte			='');
		(isset($data[$i-1]['unite']) 		? $unite 	= $data[$i-1]['unite'] 			: $unite		='');
		(isset($data[$i-1]['prix'])	&& $data[$i-1]['prix']>0 	? $prix		 	= $data[$i-1]['prix'] 			: $prix			=0);

		($prix>0 ?	$Aprix = number_format($prix,0,',', ' ') : $Aprix='');
		$total = $prix*$qte;
		($total>0 ?	$Atotal = number_format($total,0,',', ' ') : $Atotal='');
		($qte>0 ?	$Aqte = number_format($qte,0,',', ' ') : $Aqte='');

		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		if($qte>0){
			$ret .= '
			<tr align="left" valign="middle" class="'.$col.'">
	            <td class="text" align="left" >'.stripslashes($codeproduit).'</td>
		        <td class="text" >'.stripslashes($produit).'&nbsp;</td>
				<td class="text" align="right" >'.stripslashes($Aprix).'&nbsp;</td>
	            <td class="text" align="right" >'.stripslashes($Aqte).'&nbsp;</td>
	            <td class="text" align="left" >'.(stripslashes($unite)).'&nbsp;</td>
	            <td class="text" align="right" >'.(stripslashes($Atotal)).'&nbsp;</td>
	        </tr>';
		}

	}
	return $ret;
}

function ligneConQteCommande($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])	? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 		? $produit 		= $data[$i-1]['produit'] 	: $produit		='');
		(isset($data[$i-1]['stock'])		? $stock	= $data[$i-1]['stock'] 			: $stock	='');
		(isset($data[$i-1]['cmm'])			? $cmm	= $data[$i-1]['cmm'] 				: $cmm	='');
		(isset($data[$i-1]['min'])			? $min	= $data[$i-1]['min'] 				: $min	='');
		(isset($data[$i-1]['max'])			? $max	= $data[$i-1]['max'] 				: $max	='');
		(isset($data[$i-1]['qtecde'])		? $qtecde	= $data[$i-1]['qtecde'] 		: $qtecde	='');
		(isset($data[$i-1]['unite'])		? $unite	= $data[$i-1]['unite'] 			: $unite	='');
		(isset($data[$i-1]['moisdisp'])		? $moisdisp = $data[$i-1]['moisdisp'] 		: $moisdisp	='');


		($qtecde>0 ?	$Aqtecde = number_format($qtecde,0,',', ' ') : $Aqtecde='');
		($max>0 ?	$Amax = number_format($max,0,',', ' ') : $Amax='');
		($min>0 ?	$Amin = number_format($min,0,',', ' ') : $Amin='');
		($cmm>0 ?	$Acmm = number_format($cmm,2,',', ' ') : $Acmm='');
		($stock>0 ?	$stock = number_format($stock,0,',', ' ') : $stock='');
		($moisdisp>0 ?	$Amoisdisp = number_format($moisdisp,0,',', ' ') : $Amoisdisp='');

		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		//if($qte>0){
		$ret .= '
		<tr align="left" valign="middle" class="'.$col.'">
			<tr align="left" valign="middle" class="'.$col.'">
	        <td height="22" class="text" align="left">'.(stripslashes($codeproduit)).'</td>
            <td class="text" nowrap="nowrap" >'.(stripslashes($produit)).'&nbsp;</td>
			<td class="text" align="right">'.(stripslashes($stock)).'&nbsp;</td>
			<td class="text" align="right">'.(stripslashes($Amin)).'&nbsp;</td>
			<td class="text" align="right">'.(stripslashes($Amax)).'&nbsp;</td>
			<td class="text" align="right">'.(stripslashes($Acmm)).'&nbsp;</td>
			<td class="text" align="right">'.(stripslashes($Amoisdisp)).'&nbsp;</td>
			<td class="text" align="right">'.(stripslashes($Aqtecde)).'&nbsp;</td>
            <td class="text" align="left">'.(stripslashes($unite)).'</td>
		</tr>';
		//}
	}
	return $ret;
}

function ligneConRapDeclassement($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])? $codeproduit = $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['reflot']) 		? $reflot 		= $data[$i-1]['reflot'] 		: $reflot		='');
		(isset($data[$i-1]['nature']) 		? $nature 		= $data[$i-1]['nature'] 		: $nature		='');
		(isset($data[$i-1]['produit']) 		? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['qte'])			? $qte 		= $data[$i-1]['qte'] 		: $qte			='');
		(isset($data[$i-1]['unite']) 		? $unite 	= $data[$i-1]['unite'] 			: $unite		='');
		(isset($data[$i-1]['prix'])	&& $data[$i-1]['prix']>0 	? $prix		 	= $data[$i-1]['prix'] 			: $prix			=0);

		($prix>0 ?	$Aprix = number_format($prix,0,',', ' ') : $Aprix='');
		$total = $prix*$qte;
		($total>0 ?	$Atotal = number_format($total,0,',', ' ') : $Atotal='');
		($qte>0 ?	$Aqte = number_format($qte,0,',', ' ') : $Aqte='');

		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		if($qte>0){
			$ret .= '
			<tr align="left" valign="middle" class="'.$col.'">
	            <td class="text" align="left" >'.stripslashes($codeproduit).'</td>
		        <td class="text" align="left" >'.stripslashes($reflot).'</td>
		        <td class="text" >'.stripslashes($produit).'&nbsp;</td>
				<td class="text" >'.stripslashes($nature).'&nbsp;</td>
				<td class="text" align="right" >'.stripslashes($Aprix).'&nbsp;</td>
	            <td class="text" align="right" >'.stripslashes($Aqte).'&nbsp;</td>
	            <td class="text" align="left" >'.(stripslashes($unite)).'&nbsp;</td>
	            <td class="text" align="right" >'.(stripslashes($Atotal)).'&nbsp;</td>
	        </tr>';
		}

	}
	return $ret;
}

function ligneConrapficheproduit($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])? $codeproduit = $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['reflot'])? $reflot = $data[$i-1]['reflot'] 	: $reflot	='');
		(isset($data[$i-1]['produit']) 		? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['nature']) 		? $nature 		= $data[$i-1]['nature'] 		: $nature		='');
		(isset($data[$i-1]['qteentree'])	? $qteentree	= $data[$i-1]['qteentree'] 		: $qteentree	='');
		(isset($data[$i-1]['qtesortie'])	? $qtesortie	= $data[$i-1]['qtesortie'] 		: $qtesortie	='');
		(isset($data[$i-1]['stock'])			? $stock 		= $data[$i-1]['stock'] 		: $stock		='');
		(isset($data[$i-1]['typemvt'])		? $typemvt 		= $data[$i-1]['typemvt'] 		: $typemvt			='');
		(isset($data[$i-1]['unite']) 		? $unite 	= $data[$i-1]['unite'] 			: $unite		='');
		(isset($data[$i-1]['dateentree']) && $data[$i-1]['dateentree']!='' 	? $dateentree 	= $data[$i-1]['dateentree'] 						: $dateentree	='');
		(isset($data[$i-1]['dateperemp']) && $data[$i-1]['dateperemp']!='' 	? $dateperemp 	= $data[$i-1]['dateperemp'] 						: $dateperemp	='');


		($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		if($dateperemp<date('Y-m-d'))  $col="tableFINIRow" ;
		$d = preg_replace('/-/','/' ,$dateperemp );
		$d = substr($d,0, 7);
		($qteentree>0 ?	$qteentree = number_format($qteentree,0,',', ' ') : $qteentree='');
		($qtesortie>0 ?	$qtesortie = number_format($qtesortie,0,',', ' ') : $qtesortie='');
		($stock>0 ?	$stock = number_format($stock,0,',', ' ') : $stock='');


		$ret .= '
		<tr align="left" valign="middle" class="'.$col.'">
			<td class="text" align="left" nowrap="nowrap">'.stripslashes($reflot).'&nbsp;</td>
			<td class="text" align="center" nowrap="nowrap">'.stripslashes($codeproduit).'&nbsp;</td>
			<td class="text" align="left" nowrap="nowrap">'.stripslashes($produit).'&nbsp;</td>
			<td class="text" align="left" nowrap="nowrap">'.stripslashes($nature).'&nbsp;</td>
			<td class="text" align="center">'.stripslashes($dateentree).'&nbsp;</td>
			<td class="text" align="right">'.stripslashes($qteentree).'&nbsp;</td>
			<td class="text" align="right">'.stripslashes($qtesortie).'&nbsp;</td>
			<td class="text" align="right">'.stripslashes($stock).'&nbsp;</td>
			<td class="text" align="left">'.stripslashes($unite).'&nbsp;</td>
            <td class="text" align="center">'.stripslashes($d).'&nbsp;</td>
        </tr>';

	}
	return $ret;
}

function ligneEtatRapDetSortie($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])? $codeproduit = $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 		? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['reflot']) 		? $reflot 		= $data[$i-1]['reflot'] 		: $reflot		='');
		(isset($data[$i-1]['qte'])			? $qte 		= $data[$i-1]['qte'] 		: $qte			='');
		(isset($data[$i-1]['unite']) 		? $unite 	= $data[$i-1]['unite'] 			: $unite		='');
		(isset($data[$i-1]['nature'])		? $nature	= $data[$i-1]['nature'] 			: $nature			='');
		(isset($data[$i-1]['datemvt'])		? $datemvt		 	= $data[$i-1]['datemvt'] 			: $datemvt			='');
		(isset($data[$i-1]['timemvt'])		? $timemvt		 	= $data[$i-1]['timemvt'] 			: $timemvt			='');
		(isset($data[$i-1]['valide'])		? $valide		 	= $data[$i-1]['valide'] 			: $valide			='');
		(isset($data[$i-1]['prix'])	&& $data[$i-1]['prix']>0 	? $prix		 	= $data[$i-1]['prix'] 			: $prix			=0);

		($prix>0 ?	$Aprix = number_format($prix,0,',', ' ') : $Aprix='');
		$total = $prix*$qte;
		($total>0 ?	$Atotal = number_format($total,0,',', ' ') : $Atotal='');
		($qte>0 ?	$Aqte = number_format($qte,0,',', ' ') : $Aqte='');

		$ret .= '
		<tr align="left" valign="middle">
            <td class="botBorderTdall" align="center" nowrap>'.$i.' - </td>
			<td class="botBorderTdall" align="center" >'.(stripslashes($codeproduit)).'</td>
	        <td class="botBorderTdall" >'.(stripslashes($produit)).'&nbsp;</td>
			<td class="botBorderTdall" align="center" >'.(stripslashes($reflot)).'</td>
            <td class="botBorderTdall" align="center" >'.(stripslashes($datemvt)).'&nbsp;</td>
            <td class="botBorderTdall" align="right" >'.(stripslashes($Aqte)).'&nbsp;</td>
            <td class="botBorderTdall" align="left" >'.(stripslashes($unite)).'&nbsp;</td>
            <td class="botBorderTdall" align="right" >'.(stripslashes($Aprix)).'&nbsp;</td>
            <td class="botBorderTdall" align="right" >'.(stripslashes($Atotal)).'&nbsp;</td>
        </tr>';
	}
	return $ret;
}

function ligneEtatprdcommande($nbre=1, $data=array()){
	$ret = '';

	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])		? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit		='');
		(isset($data[$i-1]['produit']) 			? $produit 		= $data[$i-1]['produit'] 		: $produit			='');
		(isset($data[$i-1]['min']) 				? $min	 		= $data[$i-1]['min'] 			: $min				='');
		(isset($data[$i-1]['max'])				? $max 			= $data[$i-1]['max'] 			: $max				='');
		(isset($data[$i-1]['cmm']) 				? $cmm 			= $data[$i-1]['cmm'] 			: $cmm				='');
		(isset($data[$i-1]['stock'])			? $stock		= $data[$i-1]['stock'] 			: $stock			='');
		(isset($data[$i-1]['moisdisp'])			? $moisdisp		= $data[$i-1]['moisdisp'] 		: $moisdisp			='');
		(isset($data[$i-1]['qtecde'])			? $qtecde	 	= $data[$i-1]['qtecde'] 		: $qtecde			='');
		(isset($data[$i-1]['unite'])			? $unite		= $data[$i-1]['unite'] 			: $unite			='');
		(isset($data[$i-1]['moisdispo'])		? $moisdispo = $data[$i-1]['moisdispo'] 		: $moisdispo	='');

		($qtecde>0 ?	$Aqtecde = number_format($qtecde,0,',', ' ') : $Aqtecde='');
		($max>0 ?	$Amax = number_format($max,0,',', ' ') : $Amax='');
		($min>0 ?	$Amin = number_format($min,0,',', ' ') : $Amin='');
    	($cmm>0 ?	$Acmm = number_format($cmm,2,',', ' ') : $Acmm='');
		($moisdisp>0 ?	$Amoisdisp = number_format($moisdisp,0,',', ' ') : $Amoisdisp='');

		$ret .= '
		<tr align="left" valign="middle">
            <td class="botBorderTdall" align="center" nowrap>'.$i.' - </td>
            <td class="botBorderTdall" align="center" >'.(stripslashes($codeproduit)).'</td>
	        <td class="botBorderTdall" >'.(stripslashes($produit)).'&nbsp;</td>
			<td class="botBorderTdall" align="right" >'.(stripslashes($stock)).'&nbsp;</td>
            <td class="botBorderTdall" align="right" >'.(stripslashes($Amin)).'&nbsp;</td>
            <td class="botBorderTdall" align="right" >'.(stripslashes($Amax)).'&nbsp;</td>
            <td class="botBorderTdall" align="right" >'.(stripslashes($Acmm)).'&nbsp;</td>
            <td class="botBorderTdall" align="right" >'.(stripslashes($Amoisdisp)).'&nbsp;</td>
            <td class="botBorderTdall" align="right" >'.(stripslashes($Aqtecde)).'&nbsp;</td>
            <td class="botBorderTdall" align="left" >'.(stripslashes($unite)).'&nbsp;</td>
        </tr>';
	}
	return $ret;

}

function ligneEtatJournal($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])? $codeproduit = $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 		? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['qte'])			? $qte 		= $data[$i-1]['qte'] 		: $qte			='');
		(isset($data[$i-1]['unite']) 		? $unite 	= $data[$i-1]['unite'] 			: $unite		='');
		(isset($data[$i-1]['nature'])		? $nature	= $data[$i-1]['nature'] 			: $nature			='');
		(isset($data[$i-1]['datemvt'])		? $datemvt		 	= $data[$i-1]['datemvt'] 			: $datemvt			='');
		(isset($data[$i-1]['timemvt'])		? $timemvt		 	= $data[$i-1]['timemvt'] 			: $timemvt			='');
		(isset($data[$i-1]['valide'])		? $valide		 	= $data[$i-1]['valide'] 			: $valide			='');
		(isset($data[$i-1]['MAGASIN'])		? $magasin		 	= $data[$i-1]['MAGASIN'] 			: $magasin			='');
		($valide=='0' ? $imgCl = '<img src="../images/encours.gif" title="En cours" width="16" height="16">' : $imgCl ='<img src="../images/valider.gif" title="Validée" width="16" height="16">');

		$ret .= '
		<tr align="left" valign="middle">
            <td class="botBorderTdall" align="center" nowrap>'.$i.' - </td>
			<td class="botBorderTdall" align="center" >'.$imgCl.'</td>
	        <td class="botBorderTdall" >'.(stripslashes($produit)).'&nbsp;</td>
            <td class="botBorderTdall" >'.(stripslashes($nature)).'&nbsp;</td>
            <td class="botBorderTdall" align="center" >'.(stripslashes($datemvt)).'&nbsp;</td>
            <td class="botBorderTdall" align="center" >'.(stripslashes($timemvt)).'&nbsp;</td>
            <td class="botBorderTdall" align="right" >'.(stripslashes($qte)).'&nbsp;</td>
            <td class="botBorderTdall" align="left" >'.(stripslashes($unite)).'&nbsp;</td>
        </tr>';
	}
	return $ret;
}

function ligneEtatStock($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])		? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 			? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['livraison'])		? $livraison		 	= $data[$i-1]['livraison'] 			: $livraison			='');
		(isset($data[$i-1]['recondentree'])		? $recondentree		 	= $data[$i-1]['recondentree'] 			: $recondentree			='');

		(isset($data[$i-1]['reportentree'])	&& $data[$i-1]['reportentree']!=0	? $reportentree		 	= $data[$i-1]['reportentree'] 			: $reportentree			='');
		(isset($data[$i-1]['reportsortie'])	&& $data[$i-1]['reportsortie']!=0	? $reportsortie		 	= $data[$i-1]['reportsortie'] 			: $reportsortie			='');
		(isset($data[$i-1]['bonsortie']) && $data[$i-1]['bonsortie']!=0			? $bonsortie		= $data[$i-1]['bonsortie'] 			: $bonsortie			='');

		(isset($data[$i-1]['transfertent'])		? $transfertent		= $data[$i-1]['transfertent'] 			: $transfertent			='');
		(isset($data[$i-1]['transfertsort'])	? $transfertsort 	= $data[$i-1]['transfertsort'] 			: $transfertsort			='');

		(isset($data[$i-1]['bonsortie']) && $data[$i-1]['bonsortie']!=0		? $bonsortie	= $data[$i-1]['bonsortie'] 	: $bonsortie			='');

		(isset($data[$i-1]['recondsortie'])		? $recondsortie		 	= $data[$i-1]['recondsortie'] 			: $recondsortie			='');
		(isset($data[$i-1]['declass'])			? $declass		 	= $data[$i-1]['declass'] 			: $declass			='');
		(isset($data[$i-1]['report'])			? $report		 	= $data[$i-1]['report'] 			: $report			='');

		(isset($data[$i-1]['qteentre']) && $data[$i-1]['qteentre']!=0 	? $qteentre		= $data[$i-1]['qteentre'] 		: $qteentre	='');
		(isset($data[$i-1]['qtesortie']) && $data[$i-1]['qtesortie']!=0 ? $qtesortie 	= $data[$i-1]['qtesortie'] 		: $qtesortie		='');
//		(isset($data[$i-1]['qteperime']) && $data[$i-1]['qteperime']!=0 		? $qteperime 	= $data[$i-1]['qteperime'] 		: $qteperime		='');
		(isset($data[$i-1]['ecart']) && $data[$i-1]['ecart']!=0 		? $ecart 	= $data[$i-1]['ecart'] 		: $ecart		='');
		(isset($data[$i-1]['stocks'])			? $stocks 		= $data[$i-1]['stocks'] 		: $stocks			='');
		(isset($data[$i-1]['unite']) 			? $unite 		= $data[$i-1]['unite'] 			: $unite		='');

		(isset($data[$i-1]['sortieNV']) && $data[$i-1]['sortieNV']!=0 ? $sortieNV 	= $data[$i-1]['sortieNV'] 		: $sortieNV		='');

		//($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		$col='';

		$ret .= '
		<tr align="left" valign="middle" class="'.$col.'">
			<td class="botBorderTdall" align="center" nowrap>'.$i.' - </td>
			<td class="botBorderTdall" nowrap="nowrap">'.(stripslashes($produit)).'&nbsp;</td>
			<td class="botBorderTdall" align="right" >'.(stripslashes($reportentree)).'&nbsp;</td>
			<td class="botBorderTdall" align="right" >'.(stripslashes($transfertent)).'&nbsp;</td>
			<td class="botBorderTdall" align="right" >'.(stripslashes($livraison)).'&nbsp;</td>
			<td class="botBorderTdall" align="right">'.(stripslashes($qteentre)).'&nbsp;</td>

            <td class="botBorderTdall" align="right" >'.(stripslashes($reportsortie)).'&nbsp;</td>
			<td class="botBorderTdall" align="right" >'.(stripslashes($transfertsort)).'&nbsp;</td>
			<td class="botBorderTdall" align="right" >'.(stripslashes($bonsortie)).'&nbsp;</td>
			<td class="botBorderTdall" align="right" >'.(stripslashes($declass)).'&nbsp;</td>
			<td class="botBorderTdall" align="right">'.(stripslashes($qtesortie)).'&nbsp;</td>

            <td class="botBorderTdall" align="right">'.(stripslashes($stocks)).'&nbsp;</td>
            <td class="botBorderTdall" align="left">'.(stripslashes($unite)).'</td>
        </tr>';	}
	return $ret;
}

function ligneEtatrapstockactuel($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['reflot'])			? $reflot 			= $data[$i-1]['reflot'] 		: $reflot	='');
		(isset($data[$i-1]['codeproduit'])		? $codeproduit 		= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 			? $produit 			= $data[$i-1]['produit'] 		: $produit		='');

		(isset($data[$i-1]['perte']) && $data[$i-1]['perte']!=0				? $perte	 	= number_format($data[$i-1]['perte'],0,'',' ') 		: $perte		='');
		(isset($data[$i-1]['qteperime']) && $data[$i-1]['qteperime']!=0		? $qteperime	= number_format($data[$i-1]['qteperime'],0,'',' ') 	: $qteperime	='');
		(isset($data[$i-1]['qteentre']) && $data[$i-1]['qteentre']!=0 		? $qteentre		= number_format($data[$i-1]['qteentre'],0,'',' ') 	: $qteentre	='');
		(isset($data[$i-1]['stocks']) && $data[$i-1]['stocks']!=0			? $stocks 		= number_format($data[$i-1]['stocks'],0,'',' ') 	: $stocks			='');
		(isset($data[$i-1]['unite']) 										? $unite 		= $data[$i-1]['unite'] 								: $unite		='');

		(isset($data[$i-1]['dateentree']) && $data[$i-1]['dateentree']!='' 	? $dateentree 	= $data[$i-1]['dateentree'] 						: $dateentree	='');
		(isset($data[$i-1]['dateperemp']) && $data[$i-1]['dateperemp']!='' 	? $dateperemp 	= $data[$i-1]['dateperemp'] 						: $dateperemp	='');
		(isset($data[$i-1]['pa'])  && $data[$i-1]['pa']						? $pa 			= number_format($data[$i-1]['pa'],0,'',' ') 		: $pa	=0);
		(isset($data[$i-1]['perime']) && $data[$i-1]['perime']!=0  			? $qteperime 	= number_format($data[$i-1]['perime'],0,'',' ') 	: $qteperime	='');

		//($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		if($dateperemp<date('Y-m-d'))  $col="tableFINIRow" ;
		$d = preg_replace('/-/','/' ,$dateperemp );
		$d = substr($d,0, 7);

		$montant = $data[$i-1]['pa'] * $data[$i-1]['qteentre'];
		$col ='';
		$ret .= '
		<tr align="left" valign="middle" class="'.$col.'">
			<td class="botBorderTdall" align="center" nowrap>'.$i.' - </td>
			<td class="botBorderTdall" nowrap="nowrap">'.(stripslashes($reflot)).'&nbsp;</td>
			<td class="botBorderTdall" align="left" >'.(stripslashes($codeproduit)).'&nbsp;</td>
			<td class="botBorderTdall" align="left" >'.(stripslashes($produit)).'&nbsp;</td>
			<td class="botBorderTdall" align="center" >'.(stripslashes($dateentree)).'&nbsp;</td>
			<td class="botBorderTdall" align="center">'.(stripslashes($d)).'&nbsp;</td>
   			<td class="botBorderTdall" align="right" >'.(stripslashes($pa)).'&nbsp;</td>
			<td class="botBorderTdall" align="right">'.(stripslashes($qteentre)).'&nbsp;</td>
            <td class="botBorderTdall" align="right">'.(stripslashes($montant)).'&nbsp;</td>
        </tr>';
	}
	return $ret;
}

function ligneEtatrapsyntheseinventaire($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])		? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 			? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['qteentree']) && $data[$i-1]['qteentree']!=0		? $qteentree	= number_format($data[$i-1]['qteentree'],0,'',' ') 		: $qteentree	='');
		(isset($data[$i-1]['qtesortie']) && $data[$i-1]['qtesortie']!=0		? $qtesortie	= number_format($data[$i-1]['qtesortie'],0,'',' ') 		: $qtesortie	='');
		(isset($data[$i-1]['qteperime']) && $data[$i-1]['qteperime']!=0		? $qteperime	= number_format($data[$i-1]['qteperime'],0,'',' ') 		: $qteperime	='');
		(isset($data[$i-1]['stocks']) && $data[$i-1]['stocks']!=0			? $stocks		= number_format($data[$i-1]['stocks'],0,'',' ') 		: $stocks		='');
		(isset($data[$i-1]['seuilmin'])	&& $data[$i-1]['seuilmin']!=0		? $seuilmin		= number_format($data[$i-1]['seuilmin'],0,'',' ') 		: $seuilmin		='');
		(isset($data[$i-1]['seuilmax'])	&& $data[$i-1]['seuilmax']!=0		? $seuilmax		= number_format($data[$i-1]['seuilmax'],0,'',' ') 		: $seuilmax		='');

		$col ='';
		$ret .= '
		<tr align="left" valign="middle" class="'.$col.'">
			<td class="botBorderTdall" align="center" nowrap>'.$i.' - </td>
			<td class="botBorderTdall" align="left" >'.(stripslashes($codeproduit)).'&nbsp;</td>
			<td class="botBorderTdall" align="left" >'.(stripslashes($produit)).'&nbsp;</td>
			<td class="botBorderTdall" align="right" >'.(stripslashes($qteentree)).'&nbsp;</td>
			<td class="botBorderTdall" align="right">'.(stripslashes($qtesortie)).'&nbsp;</td>
   			<td class="botBorderTdall" align="right" >'.(stripslashes($qteperime)).'&nbsp;</td>
			<td class="botBorderTdall" align="right">'.(stripslashes($stocks)).'&nbsp;</td>
            <td class="botBorderTdall" align="right">'.(stripslashes($seuilmin)).'&nbsp;</td>
            <td class="botBorderTdall" align="right">'.(stripslashes($seuilmax)).'&nbsp;</td>
        </tr>';
	}
	return $ret;
}

function ligneEtatrapmvtstock($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])		? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 			? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['qteentree']) && $data[$i-1]['qteentree']!=0			? $qteentree	= number_format($data[$i-1]['qteentree'],0,'',' ') 		: $qteentree	='');
		(isset($data[$i-1]['qtesortie']) && $data[$i-1]['qtesortie']!=0			? $qtesortie	= number_format($data[$i-1]['qtesortie'],0,'',' ') 		: $qtesortie	='');
		(isset($data[$i-1]['ecart']) && $data[$i-1]['ecart']!=0			? $ecart	= number_format($data[$i-1]['ecart'],0,'',' ') 		: $ecart	='');
		(isset($data[$i-1]['stocks']) && $data[$i-1]['stocks']!=0			? $stocks	= number_format($data[$i-1]['stocks'],0,'',' ') 		: $stocks	='');
		(isset($data[$i-1]['declass']) && $data[$i-1]['declass']!=0			? $declass	= number_format($data[$i-1]['declass'],0,'',' ') 		: $declass	='');

		$col ='';
		$ret .= '
		<tr align="left" valign="middle" class="'.$col.'">
			<td class="botBorderTdall" align="center" nowrap>'.$i.' - </td>
			<td class="botBorderTdall" align="left" >'.(stripslashes($codeproduit)).'&nbsp;</td>
			<td class="botBorderTdall" align="left" >'.(stripslashes($produit)).'&nbsp;</td>
			<td class="botBorderTdall" align="right" >'.(stripslashes($qteentree)).'&nbsp;</td>
			<td class="botBorderTdall" align="right">'.(stripslashes($qtesortie)).'&nbsp;</td>
   			<td class="botBorderTdall" align="right" >'.(stripslashes($declass)).'&nbsp;</td>
			<td class="botBorderTdall" align="right">'.(stripslashes($stocks)).'&nbsp;</td>
        </tr>';
	}
	return $ret;
}

function ligneEtatrapprdperime($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['reflot'])		? $reflot 	= $data[$i-1]['reflot'] 	: $reflot	='');
		(isset($data[$i-1]['codeproduit'])		? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 			? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['dateperemp']) 		? $dateperemp	= $data[$i-1]['dateperemp']		: $dateperemp	='');
		(isset($data[$i-1]['dateentree'])		? $dateentree	= $data[$i-1]['dateentree'] 	: $dateentree	='');
		(isset($data[$i-1]['datesortie'])		? $datesortie	= $data[$i-1]['datesortie'] 	: $datesortie	='');
		(isset($data[$i-1]['qte']) && $data[$i-1]['qte']!=0			? $qte	= number_format($data[$i-1]['qte'],0,'',' ') 		: $qte	='');

		if($dateperemp<date('Y-m-d'))  $col="tableFINIRow" ;
		$d = preg_replace('/-/','/' ,$dateperemp );
		$d = substr($d,0, 7);

		$col ='';
		$ret .= '
		<tr align="left" valign="middle" class="'.$col.'">
			<td class="botBorderTdall" align="center" nowrap>'.$i.' - </td>
			<td class="botBorderTdall" align="left">'.(stripslashes($reflot)).'&nbsp;</td>
			<td class="botBorderTdall" align="left" >'.(stripslashes($codeproduit)).'&nbsp;</td>
			<td class="botBorderTdall" align="left" >'.(stripslashes($produit)).'&nbsp;</td>
			<td class="botBorderTdall" align="center" >'.(stripslashes($dateentree)).'&nbsp;</td>
			<td class="botBorderTdall" align="center">'.(stripslashes($d)).'&nbsp;</td>
   			<td class="botBorderTdall" align="right" >'.(stripslashes($qte)).'&nbsp;</td>
        </tr>';
	}
	return $ret;
}

function ligneEtatrapsortiemensuelle($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])		? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 			? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['qtesortie12']) && $data[$i-1]['qtesortie12']!=0	? $qtesortie12	= number_format($data[$i-1]['qtesortie12'],0,'',' ') 		: $qtesortie12	='');
		(isset($data[$i-1]['qtesortie6']) && $data[$i-1]['qtesortie6']!=0	? $qtesortie6	= number_format($data[$i-1]['qtesortie6'],0,'',' ') 		: $qtesortie6	='');
		(isset($data[$i-1]['qtesortie3']) && $data[$i-1]['qtesortie3']!=0	? $qtesortie3	= number_format($data[$i-1]['qtesortie3'],0,'',' ') 		: $qtesortie3	='');

		$col ='';
		$ret .= '
		<tr align="left" valign="middle" class="'.$col.'">
			<td class="botBorderTdall" align="center" nowrap>'.$i.' - </td>
			<td class="botBorderTdall" align="left" >'.(stripslashes($codeproduit)).'&nbsp;</td>
			<td class="botBorderTdall" align="left" >'.(stripslashes($produit)).'&nbsp;</td>
			<td class="botBorderTdall" align="right" >'.(stripslashes($qtesortie12)).'&nbsp;</td>
			<td class="botBorderTdall" align="right">'.(stripslashes($qtesortie6)).'&nbsp;</td>
   			<td class="botBorderTdall" align="right" >'.(stripslashes($qtesortie3)).'&nbsp;</td>
        </tr>';
	}
	return $ret;
}

function ligneEtatrapsortiemoymensuelle($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])		? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 			? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['qtesortie12']) && $data[$i-1]['qtesortie12']!=0	? $qtesortie12	= number_format($data[$i-1]['qtesortie12'],0,'',' ') 		: $qtesortie12	='');
		(isset($data[$i-1]['qtesortie6']) && $data[$i-1]['qtesortie6']!=0	? $qtesortie6	= number_format($data[$i-1]['qtesortie6'],0,'',' ') 		: $qtesortie6	='');
		(isset($data[$i-1]['qtesortie3']) && $data[$i-1]['qtesortie3']!=0	? $qtesortie3	= number_format($data[$i-1]['qtesortie3'],0,'',' ') 		: $qtesortie3	='');

		$col ='';
		$ret .= '
		<tr align="left" valign="middle" class="'.$col.'">
			<td class="botBorderTdall" align="center" nowrap>'.$i.' - </td>
			<td class="botBorderTdall" align="left" >'.(stripslashes($codeproduit)).'&nbsp;</td>
			<td class="botBorderTdall" align="left" >'.(stripslashes($produit)).'&nbsp;</td>
			<td class="botBorderTdall" align="right" >'.(stripslashes($qtesortie12)).'&nbsp;</td>
			<td class="botBorderTdall" align="right">'.(stripslashes($qtesortie6)).'&nbsp;</td>
   			<td class="botBorderTdall" align="right" >'.(stripslashes($qtesortie3)).'&nbsp;</td>
        </tr>';
	}
	return $ret;
}

function ligneEtatrapmvtfournisseur($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['reflot'])		? $reflot 	= $data[$i-1]['reflot'] 	: $reflot	='');
		(isset($data[$i-1]['codeproduit'])		? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 			? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['dateperemp']) 		? $dateperemp	= $data[$i-1]['dateperemp']		: $dateperemp	='');
		(isset($data[$i-1]['dateentre'])		? $dateentre	= $data[$i-1]['dateentre'] 		: $dateentre	='');
		(isset($data[$i-1]['qte']) && $data[$i-1]['qte']!=0			? $qte	= number_format($data[$i-1]['qte'],0,'',' ') 		: $qte	='');

		$d = preg_replace('/-/','/' ,$dateperemp );
		$d = substr($d,0, 7);
		$col ='';
		$ret .= '
		<tr align="left" valign="middle" class="'.$col.'">
			<td class="botBorderTdall" align="center" nowrap>'.$i.' - </td>
			<td class="botBorderTdall" align="left">'.(stripslashes($reflot)).'&nbsp;</td>
			<td class="botBorderTdall" align="left" >'.(stripslashes($codeproduit)).'&nbsp;</td>
			<td class="botBorderTdall" align="left" >'.(stripslashes($produit)).'&nbsp;</td>
			<td class="botBorderTdall" align="center" >'.frFormat2($dateentre).'&nbsp;</td>
   			<td class="botBorderTdall" align="right" >'.(stripslashes($qte)).'&nbsp;</td>
			<td class="botBorderTdall" align="center">'.(stripslashes($d)).'&nbsp;</td>
        </tr>';
	}
	return $ret;
}

function ligneEtatrapmvtdestinaire($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['reflot'])			? $reflot 	= $data[$i-1]['reflot'] 	: $reflot	='');
		(isset($data[$i-1]['codeproduit'])		? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 			? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['nature']) 			? $nature 		= $data[$i-1]['nature'] 		: $nature		='');
		(isset($data[$i-1]['dateperemp']) 		? $dateperemp	= $data[$i-1]['dateperemp']		: $dateperemp	='');
		(isset($data[$i-1]['datevalid'])		? $datevalid	= $data[$i-1]['datevalid'] 		: $datevalid	='');
		(isset($data[$i-1]['datesortie'])		? $datesortie	= $data[$i-1]['datesortie'] 	: $datesortie	='');
		(isset($data[$i-1]['qte']) && $data[$i-1]['qte']!=0			? $qte	= number_format($data[$i-1]['qte'],0,'',' ') 		: $qte	='');

		$d = preg_replace('/-/','/' ,$dateperemp );
		$d = substr($d,0, 7);
		$col ='';
		$ret .= '
		<tr align="left" valign="middle" class="'.$col.'">
			<td class="botBorderTdall" align="center" nowrap>'.$i.' - </td>
			<td class="botBorderTdall" align="left">'.stripslashes($reflot).'&nbsp;</td>
			<td class="botBorderTdall" align="left" >'.stripslashes($codeproduit).'&nbsp;</td>
			<td class="botBorderTdall" align="left" >'.stripslashes($produit).'&nbsp;</td>
			<td class="botBorderTdall" align="left" >'.stripslashes($nature).'&nbsp;</td>
			<td class="botBorderTdall" align="center" >'.frFormat2($datevalid).'&nbsp;</td>
   			<td class="botBorderTdall" align="center" >'.frFormat2($datesortie).'&nbsp;</td>
   			<td class="botBorderTdall" align="right" >'.(stripslashes($qte)).'&nbsp;</td>
			<td class="botBorderTdall" align="center">'.stripslashes($d).'&nbsp;</td>
        </tr>';
	}
	return $ret;
}

function ligneEtatraprupture($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])	? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 		? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['jour'])			? $jour			= $data[$i-1]['jour'] 			: $jour			='');
		(isset($data[$i-1]['semaine'])		? $semaine		= floor($data[$i-1]['semaine']) 		: $semaine		='');
		(isset($data[$i-1]['mois'])			? $mois			= floor($data[$i-1]['mois']) 			: $mois			='');

		$col ='';
		$ret .= '
		<tr align="left" valign="middle" class="'.$col.'">
			<td class="botBorderTdall" align="center" nowrap>'.$i.' - </td>
			<td class="botBorderTdall" align="left" >'.(stripslashes($codeproduit)).'&nbsp;</td>
			<td class="botBorderTdall" align="left" >'.(stripslashes($produit)).'&nbsp;</td>
   			<td class="botBorderTdall" align="right" >'.(stripslashes($jour)).'&nbsp;</td>
			<td class="botBorderTdall" align="right">'.(stripslashes($semaine)).'&nbsp;</td>
   			<td class="botBorderTdall" align="right" >'.(stripslashes($mois)).'&nbsp;</td>
        </tr>';
	}
	return $ret;
}

function ligneEtatrapstocksupseuilmax($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])	? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 		? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['max'])		? $max		= $data[$i-1]['max'] 			: $max			='');
		(isset($data[$i-1]['stocks'])		? $stock		= $data[$i-1]['stocks'] 			: $stock		='');
		(isset($data[$i-1]['unite'])		? $unite		= $data[$i-1]['unite'] 			: $unite		='');
		(isset($data[$i-1]['cmm'])		? $cmm		= $data[$i-1]['cmm'] 			: $cmm		='');
		(isset($data[$i-1]['moisdispo'])		? $moisdispo		= $data[$i-1]['moisdispo'] 			: $moisdispo		='');
		($stock>0 ?	$stock = number_format($stock,0,',', ' ') : $stock='');
		($max>0 ?	$max = number_format($max,0,',', ' ') : $max='');
		($cmm>0 ?	$cmm = number_format($cmm,2,',', ' ') : $cmm='');
		($moisdispo>0 ?	$moisdispo = number_format($moisdispo,0,',', ' ') : $moisdispo='');

		$col ='';
		$ret .= '
		<tr align="left" valign="middle" class="'.$col.'">
			<td class="botBorderTdall" align="center" nowrap>'.$i.' - </td>
			<td class="botBorderTdall" align="left" >'.(stripslashes($codeproduit)).'&nbsp;</td>
			<td class="botBorderTdall" align="left" >'.(stripslashes($produit)).'&nbsp;</td>
   			<td class="botBorderTdall" align="right" >'.(stripslashes($cmm)).'&nbsp;</td>
   			<td class="botBorderTdall" align="right" >'.(stripslashes($max)).'&nbsp;</td>
			<td class="botBorderTdall" align="right">'.(stripslashes($moisdispo)).'&nbsp;</td>
			<td class="botBorderTdall" align="right">'.(stripslashes($stock)).'&nbsp;</td>
   			<td class="botBorderTdall" align="left" >'.(stripslashes($unite)).'&nbsp;</td>
        </tr>';
	}
	return $ret;
}

function ligneEtatrapproduitaperime($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['reflot'])		? $reflot 		= $data[$i-1]['reflot'] 	: $reflot	='');
		(isset($data[$i-1]['codeproduit'])	? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 		? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['dateperemption'])? $dateperemption		= $data[$i-1]['dateperemption'] 			: $dateperemption			='');

		$col ='';
		$ret .= '
		<tr align="left" valign="middle" class="'.$col.'">
			<td class="botBorderTdall" align="center" nowrap>'.$i.' - </td>
			<td class="botBorderTdall" align="center" >'.(stripslashes($reflot)).'&nbsp;</td>
			<td class="botBorderTdall" align="center" >'.(stripslashes($codeproduit)).'&nbsp;</td>
			<td class="botBorderTdall" align="left" >'.(stripslashes($produit)).'&nbsp;</td>
   			<td class="botBorderTdall" align="center" >'.(stripslashes($dateperemption)).'&nbsp;</td>
        </tr>';
	}
	return $ret;
}

function ligneEtatStockLot($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['reflot'])			? $reflot	 	= $data[$i-1]['reflot'] 		: $reflot	='');
		(isset($data[$i-1]['codeproduit'])		? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 			? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['livraison'])		? $livraison 	= $data[$i-1]['livraison'] 		: $livraison	='');
		(isset($data[$i-1]['recondentree'])		? $recondentree	= $data[$i-1]['recondentree'] 	: $recondentree	='');

		(isset($data[$i-1]['reportentree'])	&& $data[$i-1]['reportentree']!=0	? $reportentree	 	= $data[$i-1]['reportentree'] 	: $reportentree			='');
		(isset($data[$i-1]['reportsortie'])	&& $data[$i-1]['reportsortie']!=0	? $reportsortie	 	= $data[$i-1]['reportsortie'] 	: $reportsortie			='');
		(isset($data[$i-1]['bonsortie']) && $data[$i-1]['bonsortie']!=0			? $bonsortie		= $data[$i-1]['bonsortie'] 		: $bonsortie			='');

		(isset($data[$i-1]['transfertent'])		? $transfertent		= $data[$i-1]['transfertent'] 			: $transfertent			='');
		(isset($data[$i-1]['transfertsort'])		? $transfertsort		 	= $data[$i-1]['transfertsort'] 			: $transfertsort			='');

		(isset($data[$i-1]['bonsortie']) && $data[$i-1]['bonsortie']!=0		? $bonsortie	= $data[$i-1]['bonsortie'] 	: $bonsortie			='');

		(isset($data[$i-1]['recondsortie'])		? $recondsortie		 	= $data[$i-1]['recondsortie'] 			: $recondsortie			='');
		(isset($data[$i-1]['declass'])		? $declass		 	= $data[$i-1]['declass'] 			: $declass			='');
		(isset($data[$i-1]['report'])		? $report		 	= $data[$i-1]['report'] 			: $report			='');

		(isset($data[$i-1]['qteentre']) && $data[$i-1]['qteentre']!=0 ? $qteentre		= $data[$i-1]['qteentre'] 		: $qteentre	='');
		(isset($data[$i-1]['qtesortie']) && $data[$i-1]['qtesortie']!=0 ? $qtesortie 	= $data[$i-1]['qtesortie'] 		: $qtesortie		='');
//		(isset($data[$i-1]['qteperime']) && $data[$i-1]['qteperime']!=0 		? $qteperime 	= $data[$i-1]['qteperime'] 		: $qteperime		='');
		(isset($data[$i-1]['ecart']) && $data[$i-1]['ecart']!=0 		? $ecart 	= $data[$i-1]['ecart'] 		: $ecart		='');
		(isset($data[$i-1]['stocks'])	? $stocks 		= $data[$i-1]['stocks'] 		: $stocks			='');
		(isset($data[$i-1]['unite']) 	? $unite 		= $data[$i-1]['unite'] 			: $unite		='');

		(isset($data[$i-1]['sortieNV']) && $data[$i-1]['sortieNV']!=0 ? $sortieNV 	= $data[$i-1]['sortieNV'] 		: $sortieNV		='');

		//($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");
		$col='';

		$ret .= '
		<tr align="left" valign="middle" class="'.$col.'">
			<td class="botBorderTdall" align="center" nowrap>'.$i.' - </td>
			<td class="botBorderTdall" nowrap="nowrap">'.stripslashes($reflot).'&nbsp;</td>
			<td class="botBorderTdall" nowrap="nowrap">'.stripslashes($produit).'&nbsp;</td>
			<td class="botBorderTdall" align="right" >'.stripslashes($reportentree).'&nbsp;</td>
			<td class="botBorderTdall" align="right" >'.stripslashes($transfertent).'&nbsp;</td>
			<td class="botBorderTdall" align="right" >'.stripslashes($livraison).'&nbsp;</td>
			<td class="botBorderTdall" align="right">'.stripslashes($qteentre).'&nbsp;</td>

            <td class="botBorderTdall" align="right" >'.(stripslashes($reportsortie)).'&nbsp;</td>
			<td class="botBorderTdall" align="right" >'.(stripslashes($transfertsort)).'&nbsp;</td>
			<td class="botBorderTdall" align="right" >'.(stripslashes($bonsortie)).'&nbsp;</td>
			<td class="botBorderTdall" align="right" >'.(stripslashes($declass)).'&nbsp;</td>
			<td class="botBorderTdall" align="right">'.(stripslashes($qtesortie)).'&nbsp;</td>

            <td class="botBorderTdall" align="right">'.(stripslashes($stocks)).'&nbsp;</td>
            <td class="botBorderTdall" align="right">'.(stripslashes($unite)).'</td>
        </tr>';	}
	return $ret;
}

function ligneEtatrapmensuel1($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['reflot'])			? $reflot 			= $data[$i-1]['reflot'] 		: $reflot	='');
		(isset($data[$i-1]['codeproduit'])		? $codeproduit 		= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 			? $produit 			= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['livraison'])		? $livraison		= $data[$i-1]['livraison'] 		: $livraison			='');
		(isset($data[$i-1]['recondentree'])		? $recondentree		= $data[$i-1]['recondentree'] 	: $recondentree			='');

		(isset($data[$i-1]['reportentree'])		? $reportentree	 	= $data[$i-1]['reportentree'] 	: $reportentree			='');
		(isset($data[$i-1]['reportsortie'])		? $reportsortie		= $data[$i-1]['reportsortie'] 	: $reportsortie			='');

		(isset($data[$i-1]['transfertent'])		? $transfertent		= $data[$i-1]['transfertent'] 	: $transfertent			='');
		(isset($data[$i-1]['transfertsort'])	? $transfertsort	= $data[$i-1]['transfertsort'] 	: $transfertsort			='');

		(isset($data[$i-1]['bonsortie']) && $data[$i-1]['bonsortie']!=0			? $bonsortie		= $data[$i-1]['bonsortie'] 			: $bonsortie			='');
		(isset($data[$i-1]['transfert'])  && $data[$i-1]['transfert']!=0		? $transfert		= $data[$i-1]['transfert'] 			: $transfert		='');
		(isset($data[$i-1]['recondsortie'])	 && $data[$i-1]['recondsortie']!=0	? $recondsortie	 	= $data[$i-1]['recondsortie'] 		: $recondsortie		='');
		(isset($data[$i-1]['declass'])  && $data[$i-1]['declass']!=0			? $Pperte		 	= $data[$i-1]['declass'] 			: $Pperte			='');
		(isset($data[$i-1]['ecart']) && $data[$i-1]['ecart']!=0					? $ecart		 	= $data[$i-1]['ecart'] 				: $ecart			='');
		(isset($data[$i-1]['qteperime']) && $data[$i-1]['qteperime']!=0			? $qteperime		= $data[$i-1]['qteperime'] 			: $qteperime	='');

//		(isset($data[$i-1]['perte']) && $data[$i-1]['perte']!=0		? $perte		 	= $data[$i-1]['perte'] 			: $perte			='');

		(isset($data[$i-1]['qteentre']) && $data[$i-1]['qteentre']!=0 ? $qteentre		= $data[$i-1]['qteentre'] 		: $qteentre	='');
		(isset($data[$i-1]['qtesortie']) && $data[$i-1]['qtesortie']!=0 ? $qtesortie 	= $data[$i-1]['qtesortie'] 		: $qtesortie		='');
		(isset($data[$i-1]['stocks'])	? $stocks 		= $data[$i-1]['stocks'] 		: $stocks			='');
		(isset($data[$i-1]['unite']) 	? $unite 		= $data[$i-1]['unite'] 			: $unite		='');

		(isset($data[$i-1]['Pentree'])	? $Pentree 		= $data[$i-1]['Pentree'] 		: $Pentree			='');
		(isset($data[$i-1]['Psortie'])	? $Psortie 		= $data[$i-1]['Psortie'] 		: $Psortie			='');
//		(isset($data[$i-1]['Pperte'])	? $Pperte 		= $data[$i-1]['Pperte'] 		: $Pperte			='');
		(isset($data[$i-1]['solde'])	? $solde 		= $data[$i-1]['solde'] 		: $solde			='');


		(isset($data[$i-1]['sortieNV']) && $data[$i-1]['sortieNV']!=0 ? $sortieNV 	= $data[$i-1]['sortieNV'] 		: $sortieNV		='');


		//($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		$ret .= '
		<tr align="left" valign="middle">
	        <td height="22" class="botBorderTdall" align="left">'.(stripslashes($codeproduit)).'</td>
            <td class="botBorderTdall" nowrap="nowrap" >'.(stripslashes($produit)).'&nbsp;</td>
			<td class="botBorderTdall" align="right">'.(stripslashes($stocks)).'&nbsp;</td>
			<td class="botBorderTdall" align="right">'.(stripslashes($Pentree)).'&nbsp;</td>
			<td class="botBorderTdall" align="right">'.(stripslashes($Psortie)).'&nbsp;</td>
			<td class="botBorderTdall" align="right">'.(stripslashes($Pperte)).'&nbsp;</td>
			<td class="botBorderTdall" align="right">'.(stripslashes($solde)).'&nbsp;</td>
            <td class="botBorderTdall" align="left">'.(stripslashes($unite)).'</td>
        </tr>';
	}
	return $ret;
}

function ligneEtatrapmensuel2($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['reflot'])			? $reflot 			= $data[$i-1]['reflot'] 		: $reflot	='');
		(isset($data[$i-1]['codeproduit'])		? $codeproduit 		= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 			? $produit 			= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['livraison'])		? $livraison		= $data[$i-1]['livraison'] 		: $livraison			='');
		(isset($data[$i-1]['recondentree'])		? $recondentree		= $data[$i-1]['recondentree'] 	: $recondentree			='');

		(isset($data[$i-1]['reportentree'])		? $reportentree	 	= $data[$i-1]['reportentree'] 	: $reportentree			='');
		(isset($data[$i-1]['reportsortie'])		? $reportsortie		= $data[$i-1]['reportsortie'] 	: $reportsortie			='');

		(isset($data[$i-1]['transfertent'])		? $transfertent		= $data[$i-1]['transfertent'] 	: $transfertent			='');
		(isset($data[$i-1]['transfertsort'])	? $transfertsort	= $data[$i-1]['transfertsort'] 	: $transfertsort			='');

		(isset($data[$i-1]['bonsortie']) && $data[$i-1]['bonsortie']!=0			? $bonsortie		= $data[$i-1]['bonsortie'] 			: $bonsortie			='');
		(isset($data[$i-1]['transfert'])  && $data[$i-1]['transfert']!=0		? $transfert		= $data[$i-1]['transfert'] 			: $transfert		='');
		(isset($data[$i-1]['recondsortie'])	 && $data[$i-1]['recondsortie']!=0	? $recondsortie	 	= $data[$i-1]['recondsortie'] 		: $recondsortie		='');
		(isset($data[$i-1]['declass'])  && $data[$i-1]['declass']!=0			? $declass		 	= $data[$i-1]['declass'] 			: $declass			='');
		(isset($data[$i-1]['ecart']) && $data[$i-1]['ecart']!=0					? $ecart		 	= $data[$i-1]['ecart'] 				: $ecart			='');
		(isset($data[$i-1]['qteperime']) && $data[$i-1]['qteperime']!=0			? $qteperime		= $data[$i-1]['qteperime'] 			: $qteperime	='');

		(isset($data[$i-1]['perte']) && $data[$i-1]['perte']!=0		? $perte		 	= $data[$i-1]['perte'] 			: $perte			='');

		(isset($data[$i-1]['qteentre']) && $data[$i-1]['qteentre']!=0 ? $qteentre		= $data[$i-1]['qteentre'] 		: $qteentre	='');
		(isset($data[$i-1]['qtesortie']) && $data[$i-1]['qtesortie']!=0 ? $qtesortie 	= $data[$i-1]['qtesortie'] 		: $qtesortie		='');
		(isset($data[$i-1]['stocks'])	? $stocks 		= $data[$i-1]['stocks'] 		: $stocks			='');
		(isset($data[$i-1]['unite']) 	? $unite 		= $data[$i-1]['unite'] 			: $unite		='');

		(isset($data[$i-1]['Pentree'])	? $Pentree 		= $data[$i-1]['Pentree'] 		: $Pentree			='');
		(isset($data[$i-1]['Psortie'])	? $Psortie 		= $data[$i-1]['Psortie'] 		: $Psortie			='');
		(isset($data[$i-1]['Pperte'])	? $Pperte 		= $data[$i-1]['Pperte'] 		: $Pperte			='');
		(isset($data[$i-1]['solde'])	? $solde 		= $data[$i-1]['solde'] 		: $solde			='');


		(isset($data[$i-1]['sortieNV']) && $data[$i-1]['sortieNV']!=0 ? $sortieNV 	= $data[$i-1]['sortieNV'] 		: $sortieNV		='');


		//($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

		$ret .= '
		<tr align="left" valign="middle">
	        <td height="10" class="botBorderTdall" align="left">'.(stripslashes($reflot)).'</td>
            <td height="22" class="botBorderTdall" align="left">'.(stripslashes($codeproduit)).'</td>
            <td class="botBorderTdall" nowrap="nowrap" >'.(stripslashes($produit)).'&nbsp;</td>
			<td class="botBorderTdall" align="right">'.(stripslashes($stocks)).'&nbsp;</td>
			<td class="botBorderTdall" align="right">'.(stripslashes($Pentree)).'&nbsp;</td>
			<td class="botBorderTdall" align="right">'.(stripslashes($Psortie)).'&nbsp;</td>
			<td class="botBorderTdall" align="right">'.(stripslashes($declass)).'&nbsp;</td>
			<td class="botBorderTdall" align="right">'.(stripslashes($solde)).'&nbsp;</td>
            <td class="botBorderTdall" align="left">'.(stripslashes($unite)).'</td>
        </tr>';
	}
	return $ret;
}

function ligneEtatrapconsommation($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])? $codeproduit = $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 		? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['qte'])			? $qte 		= $data[$i-1]['qte'] 		: $qte			='');
		(isset($data[$i-1]['unite']) 		? $unite 	= $data[$i-1]['unite'] 			: $unite		='');
		(isset($data[$i-1]['prix'])	&& $data[$i-1]['prix']>0 	? $prix		 	= $data[$i-1]['prix'] 			: $prix			=0);

		($prix>0 ?	$Aprix = number_format($prix,0,',', ' ') : $Aprix='');
		$total = $prix*$qte;
		($total>0 ?	$Atotal = number_format($total,0,',', ' ') : $Atotal='');
		($qte>0 ?	$Aqte = number_format($qte,0,',', ' ') : $Aqte='');


		if($qte>0){
			$ret .= '
			<tr align="left" valign="middle">
	            <td class="botBorderTdall" align="left" >'.stripslashes($codeproduit).'</td>
		        <td class="botBorderTdall" >'.stripslashes($produit).'&nbsp;</td>
				<td class="botBorderTdall" align="right" >'.stripslashes($Aprix).'&nbsp;</td>
	            <td class="botBorderTdall" align="right" >'.stripslashes($Aqte).'&nbsp;</td>
	            <td class="botBorderTdall" align="left" >'.(stripslashes($unite)).'&nbsp;</td>
	            <td class="botBorderTdall" align="right" >'.(stripslashes($Atotal)).'&nbsp;</td>
	        </tr>';
		}

	}
	return $ret;
}

function ligneEtatQteCommande($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])	? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['produit']) 		? $produit 		= $data[$i-1]['produit'] 	: $produit		='');
		(isset($data[$i-1]['stock'])		? $stock	= $data[$i-1]['stock'] 			: $stock	='');
		(isset($data[$i-1]['cmm'])			? $cmm	= $data[$i-1]['cmm'] 				: $cmm	='');
		(isset($data[$i-1]['min'])			? $min	= $data[$i-1]['min'] 				: $min	='');
		(isset($data[$i-1]['max'])			? $max	= $data[$i-1]['max'] 				: $max	='');
		(isset($data[$i-1]['qtecde'])		? $qtecde	= $data[$i-1]['qtecde'] 		: $qtecde	='');
		(isset($data[$i-1]['unite'])		? $unite	= $data[$i-1]['unite'] 			: $unite	='');

		$ret .= '
		<tr align="left" valign="middle" class="'.$col.'">
	        <td height="22" class="text" align="left">'.(stripslashes($codeproduit)).'</td>
            <td class="text" nowrap="nowrap" >'.(stripslashes($produit)).'&nbsp;</td>
			<td class="text" align="right">'.(stripslashes($stock)).'&nbsp;</td>
			<td class="text" align="right">'.(stripslashes($min)).'&nbsp;</td>
			<td class="text" align="right">'.(stripslashes($max)).'&nbsp;</td>
			<td class="text" align="right">'.(stripslashes($cmm)).'&nbsp;</td>
			<td class="text" align="right">'.(stripslashes($qtecde)).'&nbsp;</td>
            <td class="text" align="left">'.(stripslashes($unite)).'</td>
		</tr>';
	}
	return $ret;
}

function ligneEtatrapdeclassement($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])? $codeproduit = $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['reflot'])? $reflot = $data[$i-1]['reflot'] 	: $reflot	='');
		(isset($data[$i-1]['produit']) 		? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['nature']) 		? $nature 		= $data[$i-1]['nature'] 		: $nature		='');
		(isset($data[$i-1]['qte'])			? $qte 		= $data[$i-1]['qte'] 		: $qte			='');
		(isset($data[$i-1]['unite']) 		? $unite 	= $data[$i-1]['unite'] 			: $unite		='');
		(isset($data[$i-1]['prix'])	&& $data[$i-1]['prix']>0 	? $prix		 	= $data[$i-1]['prix'] 			: $prix			=0);

		($prix>0 ?	$Aprix = number_format($prix,0,',', ' ') : $Aprix='');
		$total = $prix*$qte;
		($total>0 ?	$Atotal = number_format($total,0,',', ' ') : $Atotal='');
		($qte>0 ?	$Aqte = number_format($qte,0,',', ' ') : $Aqte='');


		if($qte>0){
			$ret .= '
			<tr align="left" valign="middle">
	            <td class="botBorderTdall" align="left" >'.stripslashes($reflot).'</td>
		        <td class="botBorderTdall" align="left" >'.stripslashes($codeproduit).'</td>
		        <td class="botBorderTdall" >'.stripslashes($produit).'&nbsp;</td>
				<td class="botBorderTdall" >'.stripslashes($nature).'&nbsp;</td>
				<td class="botBorderTdall" align="right" >'.stripslashes($Aprix).'&nbsp;</td>
	            <td class="botBorderTdall" align="right" >'.stripslashes($Aqte).'&nbsp;</td>
	            <td class="botBorderTdall" align="left" >'.(stripslashes($unite)).'&nbsp;</td>
	            <td class="botBorderTdall" align="right" >'.(stripslashes($Atotal)).'&nbsp;</td>
	        </tr>';
		}

	}
	return $ret;
}

function ligneEtatrapficheproduit($nbre=1, $data=array()){
	$ret = '';
	for ($i=1; $i <= $nbre; $i++){
		(isset($data[$i-1]['codeproduit'])? $codeproduit = $data[$i-1]['codeproduit'] 	: $codeproduit	='');
		(isset($data[$i-1]['reflot'])? $reflot = $data[$i-1]['reflot'] 	: $reflot	='');
		(isset($data[$i-1]['produit']) 		? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
		(isset($data[$i-1]['nature']) 		? $nature 		= $data[$i-1]['nature'] 		: $nature		='');
		(isset($data[$i-1]['qteentree'])	? $qteentree	= $data[$i-1]['qteentree'] 		: $qteentree	='');
		(isset($data[$i-1]['qtesortie'])	? $qtesortie	= $data[$i-1]['qtesortie'] 		: $qtesortie	='');
		(isset($data[$i-1]['stock'])			? $stock 		= $data[$i-1]['stock'] 		: $stock		='');
		(isset($data[$i-1]['typemvt'])		? $typemvt 		= $data[$i-1]['typemvt'] 		: $typemvt			='');
		(isset($data[$i-1]['unite']) 		? $unite 	= $data[$i-1]['unite'] 			: $unite		='');
		(isset($data[$i-1]['dateentree']) && $data[$i-1]['dateentree']!='' 	? $dateentree 	= $data[$i-1]['dateentree'] 						: $dateentree	='');
		(isset($data[$i-1]['dateperemp']) && $data[$i-1]['dateperemp']!='' 	? $dateperemp 	= $data[$i-1]['dateperemp'] 						: $dateperemp	='');

		if($dateperemp<date('Y-m-d'))  $col="tableFINIRow" ;
		$d = preg_replace('/-/','/' ,$dateperemp );
		$d = substr($d,0, 7);



			$ret .= '
			<tr align="left" valign="middle">
	            <td class="botBorderTdall" align="left" >'.stripslashes($codeproduit).'</td>
		        <td class="botBorderTdall" align="left" >'.stripslashes($reflot).'</td>
		        <td class="botBorderTdall" >'.stripslashes($produit).'&nbsp;</td>
				<td class="botBorderTdall" >'.stripslashes($nature).'&nbsp;</td>
				<td class="botBorderTdall" align="center" >'.stripslashes($dateentree).'&nbsp;</td>
	            <td class="botBorderTdall" align="right" >'.stripslashes($qteentree).'&nbsp;</td>
	            <td class="botBorderTdall" align="right" >'.stripslashes($qtesortie).'&nbsp;</td>
	            <td class="botBorderTdall" align="right" >'.stripslashes($stock).'&nbsp;</td>
	            <td class="botBorderTdall" align="left" >'.(stripslashes($unite)).'&nbsp;</td>
	            <td class="botBorderTdall" align="center" >'.(stripslashes($d)).'&nbsp;</td>
	        </tr>';

	}
	return $ret;
}

?>