<?php
/* Copyright (C) 2007-2008 Jeremie Ollivier    <jeremie.o@laposte.net>
 * Copyright (C) 2011      Laurent Destailleur <eldy@users.sourceforge.net>
 * Copyright (C) 2012      Marcos García       <marcosgdf@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
include_once DOL_DOCUMENT_ROOT.'/compta/facture/class/facture.class.php';
include_once DOL_DOCUMENT_ROOT.'/societe/class/societe.class.php';

$langs->load("main");
$langs->load('cashdesk');
header("Content-type: text/html; charset=".$conf->file->character_set_client);

$facid=GETPOST('facid','int');
$object=new Facture($db);
$object->fetch($facid);
$tfc=GETPOST('fis','int');
$tipopago=$obj_facturation->getSetPaymentMode();
switch($tipopago){
	case "ESP":
		$tipopago='Efectivo';
	break;
	case "CB";
		$tipopago='Tarjeta';
	break;
	default:
		$tipopago='Efectivo';
	break;	
}
switch ($tfc){
	case "0":
?>
<html>
<head>
<title><?php echo $langs->trans('PrintTicket') ?></title>

<style type="text/css">
body {
	font-size: 1.5em;
	position: relative;
}

.entete { /* 		position: relative; */

}

.address { /* 			float: left; */
	font-size: 12px;
}

.date_heure {
	position: absolute;
	top: 0;
	right: 0;
	font-size: 16px;
}

.infos {
	position: relative;
}

.liste_articles {
	width: 100%;
	border-bottom: 1px solid #000;
	text-align: center;
}

.liste_articles tr.titres th {
	border-bottom: 1px solid #000;
}

.liste_articles td.total {
	text-align: right;
}

.totaux {
	margin-top: 20px;
	width: 30%;
	float: right;
	text-align: right;
}

.lien {
	position: absolute;
	top: 0;
	left: 0;
	display: none;
}

@media print {
	.lien {
		display: none;
	}
}
</style>

</head>

<body>
<div class="entete">
<div class="logo"><?php print '<img src="'.DOL_URL_ROOT.'/viewimage.php?modulepart=companylogo&amp;file='.urlencode('/thumbs/'.$mysoc->logo_small).'">'; ?>
</div>
<div class="infos">
<p class="address"><?php echo $mysoc->name; ?><br>
<?php print dol_nl2br(dol_format_address($mysoc)); ?><br>
</p>

<p class="date_heure"><?php
// Recuperation et affichage de la date et de l'heure
$now = dol_now();
print dol_print_date($now,'dayhourtext').'<br>';
print $object->ref;
?></p>
</div>
</div>

<br>

<table class="liste_articles">
	<tr class="titres">
		<th>Item</th>
		<th>Articulo</th>
		<th>Cant</th>
		<th>P.U.</th>
		<th>IVA</th>
		<th>Desc</th>
		<th>Total</th>
	</tr>

	<?php

	$tab=array();
    $tab = $_SESSION['poscart'];
    $tab_size=count($tab);
    for($i=0;$i < $tab_size;$i++)
    {
		$iv = $object->lines[$i]->tva_tx;
        $remise = $tab[$i]['remise'];
        $prec = $tab[$i]['total_ttc'];
        $cann = $tab[$i]['qte'];
        $prec_siva_cann = ($tab[$i]['total_ttc'] + $remise )/ $cann;
        $prec_siva = $prec_siva_cann;
        echo ('<tr><td>'.$tab[$i]['ref'].'</td><td>'.$tab[$i]['label'].'</td><td>'.$cann.'</td><td>$'.$prec_siva.'</td><td>'.$iv.'</td><td>'.$tab[$i]['remise_percent'].'%</td><td class="total">$'.price(price2num($prec,'MT'),0,$langs,0,0,-1).'</td></tr>'."\n");	
	}
?>
</table>

<table class="totaux">
<?php
echo '<tr><th class="nowrap">'.$langs->trans("TotalHT").'</th><td class="nowrap">'.price(price2num($obj_facturation->prixTotalHt(),'MT'),'',$langs,0,-1,-1,$conf->currency)."</td></tr>\n";
echo '<tr><th class="nowrap">'.$langs->trans("TotalVAT").'</th><td class="nowrap">'.price(price2num($obj_facturation->montantTva(),'MT'),'',$langs,0,-1,-1,$conf->currency)."</td></tr>\n";
echo '<tr><th class="nowrap">'.$langs->trans("TotalTTC").'</th><td class="nowrap">'.price(price2num($obj_facturation->prixTotalTtc(),'MT'),'',$langs,0,-1,-1,$conf->currency)."</td></tr>\n";
?>
</table>
<script type="text/javascript">
	window.print();
</script>
</body>
</html>

<?php

	break;	
	case "1":
//~ IMPRESION FISCAL
include_once "./modfis/con_fiscal.php";
$company2=new Societe($db);
$company2->fetch($_SESSION["CASHDESK_ID_THIRDPARTY"]);
$dae=date("Y-m-d-H-i-s");
$lugar="./modfis/fact/factura$facid.py";
$nam="factura$facid.py";
$fp = fopen("$lugar","w");
//~ Inicio FACTURA A
if($company2->typent_code == "A"){
	$fa_ivaes = "I";
	$fa_cliente = $company2->name;
	$fa_direccion = $company2->address;
	$fa_cuit = $company2->idprof1;
	$fa_letra = $company2->typent_code;
fwrite($fp,"#! /usr/bin/env python
# -*- coding: iso-8859-1 -*-
import sys
from $desde import $imp
print \"Imprimiendo con Impresora $marca $modelo\"
printer = $imp(deviceFile=\"$puerto\", model=\"$modelo\", dummy=False)
printer.openBillTicket(\"$fa_letra\", \"$fa_cliente\", \"$fa_direccion\", \"$fa_cuit\", \"C\", \"$fa_ivaes\")
");

	$tab=array();
    $tab = $_SESSION['poscart'];
    $tab_size=count($tab);
    for($i=0;$i < $tab_size;$i++)
    {
        $remise = $tab[$i]['remise'];
        $prec = $tab[$i]['total_ttc'];
        $cann = $tab[$i]['qte'];
        $prec_siva_cann = ($tab[$i]['total_ttc'] + $remise )/ $cann;
        $prec_siva = $prec_siva_cann;
        $perc2 = $tab[$i]['remise_percent'];
        $iv = $object->lines[$i]->tva_tx;
        $nomb2 = $tab[$i]['label'];
        $nomb = substr($nomb2, 0, 15);
        $des = 0;
        $subb = ($cann * $prec_siva) + ($iv/100 * $cann * $prec_siva); // (2 * 398) + (0/100 * 2 * 398) = 796
        if ($perc2 > 0 ){
			$descc = $subb - ($perc2/100 * $subb); // 796 - (45/100 * 796) = 437,8
			$des = $remise;
		}else{
			$des = "0";
		}
fwrite($fp,"printer.addItem(\"$nomb\", $cann, $prec_siva, $iv, discount=$des, discountDescription=\"Dto $perc2%\")
");
    }
    $entr = $obj_facturation->montantEncaisse();
    $vuel = $entr - $obj_facturation->prixTotalTtc();
fwrite($fp,"printer.addPayment(\"$tipopago\", $entr)
printer.closeDocument()
");
header("Content-disposition: attachment; filename=".$nam);
header('Content-type: application/octet-stream');
readfile($lugar);
//~ FIN FACTURA A
//~ Inicio FACTURA B EXENTO

}elseif($company2->typent_code == "EX"){
	$fa_ivaes = "E";
	$fa_cliente = $company2->name;
	$fa_direccion = $company2->address;
	$fa_cuit = $company2->idprof1;
	$fa_letra = "B";
fwrite($fp,"#! /usr/bin/env python
# -*- coding: iso-8859-1 -*-
import sys
from $desde import $imp
print \"Imprimiendo con Impresora $marca $modelo\"
printer = $imp(deviceFile=\"$puerto\", model=\"$modelo\", dummy=False)
printer.openBillTicket(\"$fa_letra\", \"$fa_cliente\", \"$fa_direccion\", \"$fa_cuit\", \"C\", \"$fa_ivaes\")
");

	$tab=array();
    $tab = $_SESSION['poscart'];
    $tab_size=count($tab);
    for($i=0;$i < $tab_size;$i++)
    {
        $remise = $tab[$i]['remise'];
        $prec = $tab[$i]['total_ttc'];
        $cann = $tab[$i]['qte'];
        $prec_siva_cann = ($tab[$i]['total_ttc'] + $remise )/ $cann;
        $prec_siva = $prec_siva_cann;
        $perc2 = $tab[$i]['remise_percent'];
        $iv = $object->lines[$i]->tva_tx;
        $nomb2 = $tab[$i]['label'];
        $nomb = substr($nomb2, 0, 15);
        $des = 0;
        $subb = ($cann * $prec_siva) + ($iv/100 * $cann * $prec_siva); // (2 * 398) + (0/100 * 2 * 398) = 796
        if ($perc2 > 0 ){
			$descc = $subb - ($perc2/100 * $subb); // 796 - (45/100 * 796) = 437,8
			$des = $remise;
		}else{
			$des = "0";
		}
fwrite($fp,"printer.addItem(\"$nomb\", $cann, $prec_siva, $iv, discount=$des, discountDescription=\"Dto $perc2%\")
");
    }
    $entr = $obj_facturation->montantEncaisse();
    $vuel = $entr - $obj_facturation->prixTotalTtc();
fwrite($fp,"printer.addPayment(\"$tipopago\", $entr)
printer.closeDocument()
");
header("Content-disposition: attachment; filename=".$nam);
header('Content-type: application/octet-stream');
readfile($lugar);
//~ FIN FACTURA B EXENTO
}else{
// Inicio FACT B CONSUMIDOR FINAL
fwrite($fp,"#! /usr/bin/env python
# -*- coding: iso-8859-1 -*-
import sys
from $desde import $imp
print \"Imprimiendo con Impresora $marca $modelo\"
printer = $imp(deviceFile=\"$puerto\", model=\"$modelo\", dummy=False)
number = printer.getLastNumber(\"B\") + 1
print \"imprimiendo la FC \", number
printer.openTicket()
");

	$tab=array();
    $tab = $_SESSION['poscart'];
    $tab_size=count($tab);
    for($i=0;$i < $tab_size;$i++)
    {
        $remise = $tab[$i]['remise'];
        $prec = $tab[$i]['total_ttc'];
        $cann = $tab[$i]['qte'];
        $prec_siva_cann = ($tab[$i]['total_ttc'] + $remise )/ $cann;
        $prec_siva = $prec_siva_cann;
        $perc2 = $tab[$i]['remise_percent'];
        $iv = $object->lines[$i]->tva_tx;
        $nomb2 = $tab[$i]['label'];
        $nomb = substr($nomb2, 0, 15);
        $des = 0;
        $subb = ($cann * $prec_siva) + ($iv/100 * $cann * $prec_siva); // (2 * 398) + (0/100 * 2 * 398) = 796
        if ($perc2 > 0 ){
			$descc = $subb - ($perc2/100 * $subb); // 796 - (45/100 * 796) = 437,8
			$des = $remise;
		}else{
			$des = "0";
		}
fwrite($fp,"printer.addItem(\"$nomb\", $cann, $prec_siva, $iv, discount=$des, discountDescription=\"Dto $perc2%\")
");
    }
    $entr = $obj_facturation->montantEncaisse();
    $vuel = $entr - $obj_facturation->prixTotalTtc();
fwrite($fp,"printer.addPayment(\"$tipopago\", $entr)
printer.closeDocument()
");

header("Content-disposition: attachment; filename=".$nam);
header('Content-type: application/octet-stream');
readfile($lugar);
}//~ Fin fact B CONSUMIDOR FINAL
break;
default: 
	echo "No se ha especificado";
		} //Fin switchcase
?>