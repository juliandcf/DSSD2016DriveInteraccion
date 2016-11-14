#!/bin/bash

#Generar Documento se envia el nombre del archivo y el mail de gmail al cual se quiere compartir.
/usr/bin/php -f /var/www/DSSD/generarDocumento.php nombre=$1 mail=$2