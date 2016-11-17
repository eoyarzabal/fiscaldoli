<?php
/* Copyright (C) 2007-2008	Jeremie Ollivier	<jeremie.o@laposte.net>
 * Copyright (C) 2012       Marcos García       <marcosgdf@gmail.com>
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
 *
 */

$langs->load("main");
$langs->load("bills");

?>

<h3 class="titre1"><?php echo $langs->trans("SellFinished"); ?></h3><br>

<div class="cadre_facturation">

<script type="text/javascript">

	function popupTicket()
	{
		largeur = 600;
		hauteur = 500;
		opt = 'width='+largeur+', height='+hauteur+', left='+(screen.width - largeur)/2+', top='+(screen.height-hauteur)/2+'';
		window.open('validation_ticket.php?facid=<?php echo $_GET['facid']; ?>&fis=0', '<?php echo $langs->trans('PrintTicket') ?>', opt);
	}

/*
	popupTicket();
*/
	function popupTicket2()
	{
		largeur = 500;
		hauteur = 500;
		opt = 'width='+largeur+', height='+hauteur+', left='+(screen.width - largeur)/2+', top='+(screen.height-hauteur)/2+'';
		window.open('validation_ticket.php?facid=<?php echo $_GET['facid']; ?>&fis=1', '<?php echo $langs->trans('PrintTicket') ?>', opt);
	}

/*
	popupTicket2();
*/

</script>

<!--<p><a class="lien1" href="<?php echo DOL_URL_ROOT ?>/compta/facture.php?action=builddoc&facid=<?php echo $_GET['facid']; ?>" target="_blank"><?php echo $langs->trans("ShowInvoice"); ?></a></p>-->
<p><a class="lien1" href="#" onclick="Javascript: popupTicket(); return(false);"><img src="./modfis/imp1.png"><br><strong>Impresi&oacute;n STANDARD</strong></a></p>
<hr>
<?php
//~ Inicio Modif para usar factura fiscal al final
include_once DOL_DOCUMENT_ROOT.'/cashdesk/modfis/con_fiscal.php';
if ($cierres == "1"){
?>
	<p><a class="lien1" href="#" onclick="Javascript: popupTicket2(); return(false);"><img src="./modfis/impfis.png"><br>Imprimir <strong>TICKET FISCAL</strong></a></p>
<?php
}else{
?>
	<p><a class="lien1" href="#" onclick="Javascript: popupTicket4(); return(false);"><img src="./modfis/impfis.png"><br>Imprimir <strong>TICKET FISCAL</strong></a></p>
<?php
}
//~ FIN Modif para usar factura fiscal al final

?>	
<hr>
<p><a class="lien1" href="#" onclick="Javascript: popupTicket3(); return(false);"><img src="./modfis/fe.png"><br>Imp. <strong>FACTURA ELECTRONICA</strong></a></p>
<hr>

<a href="affIndex.php?menu=facturation&id=NOUV"><img src="./modfis/nv.png"><br>NUEVA VENTA<br>(Sin Imprimir)</a>
</div>
