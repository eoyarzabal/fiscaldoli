# FiscalDoli
Modificaciones para poder imprimir desde Dolibarr en impresoras fiscales Epson y Hasar de Argentina. 
(Este archivo esta en desarrollo)

Pasos a configurar en el server:
--------------------------------

 1. Copiar archivos al directorio cashdesk (reemplazar el original)
 2. Darle permisos de escritura al dir "cashdesk/modfis/fact" 
 3. Configurar archivo "cashdesk/modfis/con_fiscal.php" segun corresponda con la impresora fiscal.


Pasos a configurar en cliente con windows:
------------------------------------------

**PARA EPSON Y HASAR**

 1. Instalar python2.7 para que quede "C:\Python27"
 2. Copiar y pegar el **directorio** *serial* en el directorio "Temp" de windows
 3. Copiar y pegar el **contenido** de *pyfiscalprinter* (el contenido, no el directorio) en el directorio "Temp" de windows

**Configuración en Dolibarr**

En Dolibarr ir a "**Configuración -> Diccionarios -> Tipos de terceros**" y configurar los siguientes tipos de terceros (Todos con el país "Argentina"):

 - Código: **CF** y etiqueta: **Consumidor Final** 
 - Código: **A**  y etiqueta: **Inscripto** 
 - Código: **E**  y etiqueta: **Exento**

Avisos:

 - Solo fue probado con el navegador Firefox.
 - Las pruebas se realizaron en equipos **Epson TMU220** y en **Hasar P44** con WinXP y Win7.
 - No es bidireccional, es decir que no se guarda en Dolibarr el número de factura o ticket que imprimió el fiscal, pero sí se crea el archivo **facturaXXX.py** en el directorio "cashdesk/modifs/fact" con el número correspondiente a la factura de dolibarr.
 - Cualquier tipo de tercero que no sea del código **A** ó **E** será enviado a imprimir como **Consumidor final**
