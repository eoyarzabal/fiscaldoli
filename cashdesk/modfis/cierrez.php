<?php
/* Copyright (C): Ezequiel Oyarzabal <ezequiel@oyarguti.com>
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
