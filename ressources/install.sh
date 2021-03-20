#!/bin/bash

if [ -f /var/www/html/plugins/PTZONVIF/ressources/PTZONVIF_version ]; then
	rm /var/www/html/plugins/PTZONVIF/ressources/PTZONVIF_version
fi
  
cd ../../plugins/PTZONVIF/ressources
  
touch /tmp/PTZONVIF_dep
echo ************************************
echo *   INSTALLATION DES DEPENDANCES   *
echo ************************************
echo 0 > /tmp/PTZONVIF_dep


echo 25 > /tmp/PTZONVIF_dep
echo "NodeJS version :"
sudo node -v
echo "Launch install of Onvif dependancy"
echo 50 > /tmp/PTZONVIF_dep
sudo npm install node-onvif 
echo 75 > /tmp/PTZONVIF_dep

echo 100 > /tmp/PTZONVIF_dep
echo "Everything is successfully installed!"
rm /tmp/PTZONVIF_dep


echo "Fin d'installation des dependances"

touch /var/www/html/plugins/PTZONVIF/ressources/PTZONVIF_version
  
exit 0
