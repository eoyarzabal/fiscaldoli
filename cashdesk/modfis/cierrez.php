<?php
include_once "con_fiscal.php";
$dae=date("Y-m-d-H-i-s");
$lugar="./fact/cierreZ_$dae.py";
$nam="cierreX$dae.py";
$fp = fopen("$lugar","w");		
fwrite($fp,"#! /usr/bin/python
# -*- coding: iso-8859-1 -*-
import sys
from $desde import $imp
print \"Imprimiendo CIERRE Z con Impresora $marca $modelo\"
printer = $imp(deviceFile=\"$puerto\", model=\"$modelo\", dummy=False)
printer.dailyClose(\"Z\")
");
header("Content-disposition: attachment; filename=".$nam);
header('Content-type: application/octet-stream');
readfile($lugar);	
?>
