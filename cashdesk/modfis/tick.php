	



<?php
require "../application/config.php";

#Recibo el dato del post en el desplegable
$recibefiscal=$_POST['fiscal'];
//echo "$recibefiscal";
if ($recibefiscal==''){
	?>
	No se ha seleccionado un ticket para imprimir
	<br><br><br>
Volviendo a la aplicaci&oacute;n ..
<img src="../images/spinner_small.gif" />
<meta http-equiv="Refresh" content="3;url=../index.php/sales">
<?php
}else{
	?>
<script type="text/javascript">
	
window.setTimeout('clickit()',1000);
function clickit(){
   location.href = document.getElementById('autoid');
}	
window.setTimeout('vuelve()',5000);
function vuelve(){
   location.href = '../index.php/sales';
}
</script>
	<?php
	#Se consulta a la DB para saber los detalles de la venta y ponerlos en el archivo .py  . PHP_EOL
	
		$q1=mysql_query("SELECT * FROM ospos_sales_items WHERE sale_id='$recibefiscal'");
		$q11=mysql_fetch_array(mysql_query("SELECT * FROM ospos_sales WHERE sale_id='$recibefiscal'"));
		$custid=$q11['customer_id'];
		$q12=mysql_fetch_array(mysql_query("SELECT * FROM ospos_sales_payments WHERE sale_id='$recibefiscal'"));
		$tipopago=$q12['payment_type'];
		$montopago=$q12['payment_amount'];
		switch($tipopago){
			case "Efectivo":
				$tipopago='EF';
			break;
			case "Tarjeta de Débito";
				$tipopago='TD';
			break;
			case "Tarjeta de Crédito";
				$tipopago='TC';
			break;
			case "Cheque";
				$tipopago='CH';
			break;
			default:
				$tipopago='EF';
			break;	
		}
		$q4=mysql_fetch_array(mysql_query("SELECT * FROM ospos_customers WHERE person_id='$custid'"));
		$tfc=$q4['tipo_fc'];
			$q5=mysql_fetch_array(mysql_query("SELECT * FROM ospos_people WHERE person_id='$custid'"));
		$nom=$q5['first_name'];
		$last=$q5['last_name'];
		$addr= $q5['address_1'];
		$dni= $q5['dni_number'];
		$nomlast = "$nom $last";

	//~ $fetro= mysql_fetch_row($q1);
		$con=0;
	//for ($j=1; $j<=2; $j++){
		//~ $a1=$fetro[0];
	//~ }
			//~ $lugar="../fact/factura$recibefiscal.py";




switch ($tfc){
	case "CF":
#Crea un archivo llamado segun el id del sales_id
$lugar="../fact/factura$recibefiscal.py";
$fp = fopen("$lugar","w");
fwrite($fp,"
# -*- coding: iso-8859-1 -*-
from hasarPrinter import HasarPrinter
print \"Imprimiendo factura con Impresora Hasar 715v1\"
printer = HasarPrinter(deviceFile=\"COM1\", model=\"715v1\", dummy=False)
number = printer.getLastNumber(\"B\") + 1
print \"imprimiendo la FC \", number
printer.openTicket()
");
	while ($a1=mysql_fetch_array($q1)){
		$itemid=$a1['item_id'];
		$descuento=$a1['discount_percent'];
		$cant=$a1['quantity_purchased'];
		$precio=$a1['item_unit_price'];
			$q2=mysql_fetch_array(mysql_query("SELECT * FROM ospos_items WHERE item_id='$itemid'"));
		$name22=$q2['name'];
		$name = substr("$name22",0,20);
			$q3=mysql_fetch_array(mysql_query("SELECT * FROM ospos_items_taxes WHERE item_id='$itemid'"));
		$impuesto=$q2['imp1'];
		//~ $precioporimp= (($precio * $impuesto /100) + $precio);
		//$con=$con+1;
		//echo "id: $itemid, name: $name, cant: $cant, precio: $precioporimp, impuesto: $impuesto, tipofc: $tfc, namelast $nomlast<br>";
		//echo "$con<br>";		
fwrite($fp,"
printer.addItem(\"$name\", $cant, $precio, $impuesto, discount=0, discountDescription=\"\")
");
	}
fwrite($fp,"
printer.addPayment(\"$tipopago\", $montopago)
printer.closeDocument()
import MySQLdb
db=MySQLdb.connect(host=\"192.168.3.130\", user=\"ospos\", passwd=\"ospos\",db=\"ospos\")
cursor = db.cursor()
cursor.execute(\"UPDATE ospos_sales SET printed = %s WHERE sale_id = $recibefiscal\", number)
db.commit()
cursor.close()
");
echo "<a href='../application/$lugar' id='autoid'>Imprimir ticket $recibefiscal a Consumidor Final</a>";
	break;
	case "B":
#Crea un archivo llamado segun el id del sales_id
$lugar="../fact/factura$recibefiscal.py";
$fp = fopen("$lugar","w");
fwrite($fp,"
# -*- coding: iso-8859-1 -*-
from hasarPrinter import HasarPrinter
print \"Imprimiendo factura con Impresora Hasar 715v1\"
printer = HasarPrinter(deviceFile=\"COM1\", model=\"715v1\", dummy=False)
number = printer.getLastNumber(\"B\") + 1
print \"imprimiendo la FC \", number
printer.openTicket()
");
	while ($a1=mysql_fetch_array($q1)){
		$itemid=$a1['item_id'];
		$descuento=$a1['discount_percent'];
		$cant=$a1['quantity_purchased'];
		$precio=$a1['item_unit_price'];
			$q2=mysql_fetch_array(mysql_query("SELECT * FROM ospos_items WHERE item_id='$itemid'"));
		$name22=$q2['name'];
		$name = substr("$name22",0,20);
			$q3=mysql_fetch_array(mysql_query("SELECT * FROM ospos_items_taxes WHERE item_id='$itemid'"));
		$impuesto=$q2['imp1'];
		//~ $precioporimp= (($precio * $impuesto /100) + $precio);
		//$con=$con+1;
		//echo "id: $itemid, name: $name, cant: $cant, precio: $precioporimp, impuesto: $impuesto, tipofc: $tfc, namelast $nomlast<br>";
		//echo "$con<br>";		
fwrite($fp,"
printer.addItem(\"$name\", $cant, $precio, $impuesto, discount=0, discountDescription=\"\")
");
	}
fwrite($fp,"
printer.addPayment(\"$tipopago\", $montopago)
printer.closeDocument()
import MySQLdb
db=MySQLdb.connect(host=\"192.168.3.130\", user=\"ospos\", passwd=\"ospos\",db=\"ospos\")
cursor = db.cursor()
cursor.execute(\"UPDATE ospos_sales SET printed = %s WHERE sale_id = $recibefiscal\", number)
db.commit()
cursor.close()
");
echo "<a href='../application/$lugar' id='autoid'>Imprimir ticket $recibefiscal a Consumidor Final</a>";
	break;

	case "A":
$lugar="../fact/factura$recibefiscal.py";
$fp = fopen("$lugar","w");			
	
fwrite($fp,"
# -*- coding: iso-8859-1 -*-
from hasarPrinter import HasarPrinter
print \"Imprimiendo factura con Impresora Hasar 715v1\"
printer = HasarPrinter(deviceFile=\"COM1\", model=\"715v1\", dummy=False)
number = printer.getLastNumber(\"A\") + 1
print \"imprimiendo la FC \", number
printer.openBillTicket(\"A\",\"$nomlast\",\"$addr\",\"$dni\",\"C\",\"I\")
");
	while ($a1=mysql_fetch_array($q1)){
		$itemid=$a1['item_id'];
		$descuento=$a1['discount_percent'];
		$cant=$a1['quantity_purchased'];
		$precio=$a1['item_unit_price'];
			$q2=mysql_fetch_array(mysql_query("SELECT * FROM ospos_items WHERE item_id='$itemid'"));
		$name22=$q2['name'];
		$name = substr("$name22",0,20);
			//~ $q3=mysql_fetch_array(mysql_query("SELECT * FROM ospos_items_taxes WHERE item_id='$itemid'"));
		$impuesto=$q2['imp1'];
		//~ $precioporimp= (($precio * $impuesto /100) + $precio);
		//echo "id: $itemid, name: $name, cant: $cant, precio: $precioporimp, impuesto: $impuesto, tipofc: $tfc, namelast $nomlast<br>";
		//echo "$con<br>";


			//echo "<a href='../application/$lugar'>Imprimir FC</a>";
		
		
			#Crea un archivo llamado segun el id del sales_id del tipo factura A

//fwrite($fp,"printer.addItem(\"$name\", $cant, $precioporimp, $impuesto, discount=0, discountDescription=\"\")" . PHP_EOL);
fwrite($fp,"printer.addItem(\"$name\", $cant, $precio, $impuesto, discount=0, discountDescription=\"\")" . PHP_EOL);
//~ fwrite($fp,"printer.addItem(\"$name[$i]\", $cant[$i], $precioporimp[$i], $impuesto[$i], discount=0, discountDescription=\"\")" . PHP_EOL);
//fwrite($fp,"printer.addItem(\"$name\", $cant, $precioporimp, $impuesto, discount=0, discountDescription=\"\")" . PHP_EOL);
	}
fwrite($fp,"
printer.addPayment(\"$tipopago\", $montopago)
printer.closeDocument()
import MySQLdb
db=MySQLdb.connect(host=\"192.168.3.130\", user=\"ospos\", passwd=\"ospos\",db=\"ospos\")
cursor = db.cursor()
cursor.execute(\"UPDATE ospos_sales SET printed = %s WHERE sale_id = $recibefiscal\", number)
db.commit()
cursor.close()
			");	
//~ echo "<form name=\"forma\" id=\"forma\" method=\"post\" onsubmit=\"return fin()\">";
//~ echo "<input type=\"button\" value=\"Imprimir ticket $recibefiscal a Responsable Inscripto\" onClick=\"location.href='../application/$lugar'\">";
//~ echo "</form";
echo "<a href='../application/$lugar' id='autoid'>Imprimir ticket $recibefiscal a Responsable Inscripto</a>";	
	break;
	
	case "RI":
$lugar="../fact/factura$recibefiscal.py";
$fp = fopen("$lugar","w");			
	
fwrite($fp,"
# -*- coding: iso-8859-1 -*-
from hasarPrinter import HasarPrinter
print \"Imprimiendo factura con Impresora Hasar 715v1\"
printer = HasarPrinter(deviceFile=\"COM1\", model=\"715v1\", dummy=False)
number = printer.getLastNumber(\"A\") + 1
print \"imprimiendo la FC \", number
printer.openBillTicket(\"A\",\"$nomlast\",\"$addr\",\"$dni\",\"C\",\"I\")
");
	while ($a1=mysql_fetch_array($q1)){
		$itemid=$a1['item_id'];
		$descuento=$a1['discount_percent'];
		$cant=$a1['quantity_purchased'];
		$precio=$a1['item_unit_price'];
			$q2=mysql_fetch_array(mysql_query("SELECT * FROM ospos_items WHERE item_id='$itemid'"));
		$name22=$q2['name'];
		$name = substr("$name22",0,20);
			//~ $q3=mysql_fetch_array(mysql_query("SELECT * FROM ospos_items_taxes WHERE item_id='$itemid'"));
		$impuesto=$q2['imp1'];
		//~ $precioporimp= (($precio * $impuesto /100) + $precio);
		//echo "id: $itemid, name: $name, cant: $cant, precio: $precioporimp, impuesto: $impuesto, tipofc: $tfc, namelast $nomlast<br>";
		//echo "$con<br>";


			//echo "<a href='../application/$lugar'>Imprimir FC</a>";
		
		
			#Crea un archivo llamado segun el id del sales_id del tipo factura A

//fwrite($fp,"printer.addItem(\"$name\", $cant, $precioporimp, $impuesto, discount=0, discountDescription=\"\")" . PHP_EOL);
fwrite($fp,"printer.addItem(\"$name\", $cant, $precio, $impuesto, discount=0, discountDescription=\"\")" . PHP_EOL);
//~ fwrite($fp,"printer.addItem(\"$name[$i]\", $cant[$i], $precioporimp[$i], $impuesto[$i], discount=0, discountDescription=\"\")" . PHP_EOL);
//fwrite($fp,"printer.addItem(\"$name\", $cant, $precioporimp, $impuesto, discount=0, discountDescription=\"\")" . PHP_EOL);
	}
fwrite($fp,"
printer.addPayment(\"$tipopago\", $montopago)
printer.closeDocument()
import MySQLdb
db=MySQLdb.connect(host=\"192.168.3.130\", user=\"ospos\", passwd=\"ospos\",db=\"ospos\")
cursor = db.cursor()
cursor.execute(\"UPDATE ospos_sales SET printed = %s WHERE sale_id = $recibefiscal\", number)
db.commit()
cursor.close()
			");	
echo "<a href='../application/$lugar' id='autoid'>Imprimir ticket $recibefiscal a Responsable Inscripto</a>";	
	break;

	default: 
	echo "No se ha especificado";
		} //Fin switchcase
		

		
	} //Fin if inicial
?>
<br><br><br>
Espere un momento..
