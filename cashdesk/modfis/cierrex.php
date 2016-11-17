<script type="text/javascript">
	
window.setTimeout('clickit()',1000);
function clickit(){
   location.href = document.getElementById('autoid');
}	
window.setTimeout('vuelve()',25000);
function vuelve(){
   location.href = document.getElementById('autovolver');
}
</script>

<?php
include_once "con_fiscal.php";
$dae=date('Y-m-d_H:i:s');
$lugar=".\\fact\\cierreX_$dae.py";
$fp = fopen("$lugar","w");		
fwrite($fp,"# -*- coding: iso-8859-1 -*-
import sys
from $desde import $imp
print \"Imprimiendo CIERRE X con Impresora $marca $modelo\"
printer = $imp(deviceFile=\"$puerto\", model=\"$modelo\", dummy=False)
printer.dailyClose(\"X\")
");
echo "<a href='./$lugar' id='autoid'>Imprimir CIERRE X</a>";
echo "<br><br>";
echo "<a href='../../' id='autovolver'>Volver</a>";
?>
<br><br><br><br>
Espere un momento..
