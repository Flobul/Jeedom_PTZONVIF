#!/bin/bash

if [ -f /tmp/PTZONVIF_dependancy ]; then
   	echo "Installation en cours ..."
	exit 2
else
	if [ -f /var/www/html/plugins/PTZONVIF/ressources/PTZONVIF_version ]; then
   		echo "Installation ok"
		exit 0
    else
     	echo "Installation nok"
		exit 1
	fi
fi