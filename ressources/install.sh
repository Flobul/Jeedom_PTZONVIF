#!/bin/bash

if [ -f /var/www/html/plugins/PTZONVIF/ressources/PTZONVIF_version ]; then
	rm /var/www/html/plugins/PTZONVIF/ressources/PTZONVIF_version
fi
  
cd ../../plugins/PTZONVIF/ressources
  
touch /tmp/dependancy_PTZONVIF_in_progress
echo ************************************
echo *   INSTALLATION DES DEPENDANCES   *
echo ************************************
echo 0 > /tmp/dependancy_PTZONVIF_in_progress
sudo npm install node-onvif 

echo 25 > /tmp/dependancy_PTZONVIF_in_progress

echo "Launch install of Onvif dependancy"
echo 50 > /tmp/dependancy_PTZONVIF_in_progress
sudo npm install minimist
echo 75 > /tmp/dependancy_PTZONVIF_in_progress
sudo npm install onvif 
echo 100 > /tmp/dependancy_PTZONVIF_in_progress
echo "Everything is successfully installed!"
rm /tmp/dependancy_PTZONVIF_in_progress


echo "Fin d'nstallation des dependances"

touch /var/www/html/plugins/PTZONVIF/ressources/PTZONVIF_version
  
exit 0