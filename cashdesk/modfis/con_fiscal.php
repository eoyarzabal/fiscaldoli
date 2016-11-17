<?php
//~ Marca: "Hasar", "Epson" (Usar uno de los 2)
$marca = "";

//~ Modelo Hasar: "615", "715v1", "715v2", "320" / Epson: "tickeadoras", "epsonlx300+", "tm-220-af" (Usar solo un modelo)
$modelo = "";

//~ Puerto: "COM1", "COM2" (Usar uno de los 2)
$puerto = "";

//~ From: "hasarPrinter", "epsonFiscal" (Usar uno de los 2)
$desde = "";

//~ Import: "HasarPrinter", "EpsonPrinter" (Usar uno de los 2)
$imp = "";

//~ IVA para el TPV (NO IMPLEMENTADO)
$ivatpv = "0"; 
//~ Datos de Dolibarr para actualizar con los datos de los tickets fiscales (NO IMPLEMENTADO)
$hos = "";
$use = "";
$pas = "";
$dba = "";
$connn = "host=\"$hos\", user=\"$use\", passwd=\"$pas\", db=\"$dba\"";

//~ Habilita los cierres X y Z en el menu de arriba (0=Deshabilitado, 1=Habilitado)
$cierres = "1";

//~ Permite modificar el precio del producto en el TPV (0=Deshabilitado, 1=Habilitado)
$mod_precio = "1";
?>
