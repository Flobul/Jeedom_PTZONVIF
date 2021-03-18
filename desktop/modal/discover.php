<?php
/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}

?>


<div id='div_OptionsAlert'></div>
<div class="col-lg-12">
</div>

<table class="table table-condensed" >
	<thead>
		<tr style="background-color: grey !important; color: white !important;">
			<th>{{IP}}</th>
			<th>{{Port}}</th>
			<th>{{URN}}</th>
			<th>{{Name}}</th>
			<th>{{Location}}</th>
		</tr>
	</thead>
	<tbody>




<?php
/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}

if (init('id') == '') {
    throw new Exception('{{L\'id de l\'opération ne peut etre vide : }}' . init('id'));
}
$ideq = init('id');
$eq = eqLogic::byId($ideq);
$name = $eq->getName();
$adresseip = $eq->getConfiguration('adresseip');
if ($adresseip =='null' || $adresseip =='') {
    throw new Exception('{{L\'adresse IP ne peut etre vide. }}');
}
$username = $eq->getConfiguration('username');
$password = $eq->getConfiguration('password');

function json_validate($_test)
{
  // Decode pour test erreur
    $result = json_decode($_test);


    if(json_last_error() != JSON_ERROR_NONE) 
    {
      throw new Exception(json_last_error());
      // Exit sur exeption
      echo "Erreur \n";
      echo $_error;
    }

    // Fin sans erreur
    $_test2 = json_decode($_test);
    if ($_test2 =='null' || $_test2 =='')
    {
      throw new Exception('Fichier Vide');
    }

    log::add('ONVIF','debug','Aucune erreur dans le décodage du JSON');  

}


/************************************************************************
***  Découverte des caméras sur le réseau et selection de celle qui   ***
***  correspond à l'IP   ->probe.js                                   ***
*************************************************************************/
$commande = "node /var/www/html/plugins/PTZONVIF/ressources/probe.js 2>&1";  
$camerasdiscovery = shell_exec($commande);
log::add('PTZONVIF','debug','probe.js : '.$camerasdiscovery);

json_validate($camerasdiscovery);
$cam = json_decode($camerasdiscovery,true);
$nombrecam = count($cam);
$trouve = false;
for($i = 0; $i <= $nombrecam-1; $i++) 
{	
	$xaddrs = $cam[$i]['xaddrs'] ;
	$url = implode("", $xaddrs) ;
	$host=parse_url($url, PHP_URL_HOST);
	log::add('PTZONVIF','debug','test : '.$url.' - '.$adresseip.' '.$host);
	if($host == $adresseip) {
		$port = parse_url($url, PHP_URL_PORT);
		$urn = $cam[$i]['urn'];
		$name = $cam[$i]['name'];
		$location = $cam[$i]['location'];
		$types = $cam[$i]['types'];   
		$trouve = true;
		log::add('PTZONVIF','debug','trouvé : '.$url);
		break;
	}
  

}
/************************************************************************
***  Traitement de la caméra trouvée								  ***
***  					                                              ***
*************************************************************************/
if ($trouve){
	$eq->setConfiguration('URL',$url);
	$eq->setConfiguration('port',$port);
	
	echo '<tr><td>' . $adresseip . '</td>';
	echo '<td>' . $port . '</td>';
	echo '<td>' . $urn . '</td>';
	echo '<td>' . $name . '</td>';
	echo '<td>' . $location . '</td></tr>';
	echo '<tr></tr>';
	echo "<tr style='background-color: grey !important; color: white !important;'>";
	echo '<th>xaddrs</th></tr>';
	foreach ($xaddrs as $keys=>$value) {
		echo '<tr><td>'.$value.'</td></tr>';
	}
	echo '<tr></tr>';
	echo "<tr style='background-color: grey !important; color: white !important;'>";
	echo '<th>Types</th></tr>';
	foreach ($types as $keys=>$value) {
		echo '<tr><td>'.$value.'</td></tr>';
	}
	echo '<tr></tr>';
	
/************************************************************************
***  Extraction des infos											  ***
***  Getdevice.js		                                              ***
*************************************************************************/
	$commande = "node /var/www/html/plugins/PTZONVIF/ressources/Getdevice.js ";
	$commande .= $url;  
	$commande .= " ";
	$commande .= $username;  
	$commande .= " ";
	$commande .= $password;  
	$commande .= " 2>&1";
	$camerasdiscovery = shell_exec($commande);
	log::add('PTZONVIF','debug','Getdevice.js : '.$commande);
	log::add('PTZONVIF','debug','Getdevice.js : '.$camerasdiscovery);
	json_validate($camerasdiscovery);
	$cam = json_decode($camerasdiscovery,true);
	$token = $cam['token'];
	$snapshot = $cam['snapshot'];
	$rtsp = $cam['stream']['rtsp'];
	$eq->setConfiguration('token',$token);
	$eq->setConfiguration('snapshot',$snapshot);
	$eq->setConfiguration('rtsp',$rtsp);
	$eq->save();
	echo "<tr style='background-color: grey !important; color: white !important;'>";
	echo '<th>Token</th>';
	echo '<th>snapshot</th>';
	echo '<th>rtsp</th></tr>';
	echo '<tr><td>'.$token.'</td>';
	echo '<td>'.$snapshot.'</td>';
	echo '<td>'.$rtsp.'</th></td></tr>';
	echo '<tr></tr>';

/************************************************************************
***  Extraction des infos complémentaires											  ***
***  GetInfo.js		                                              ***
*************************************************************************/
	$commande = "node /var/www/html/plugins/PTZONVIF/ressources/GetInfo.js ";
	$commande .= $url;  
	$commande .= " ";
	$commande .= $username;  
	$commande .= " ";
	$commande .= $password;  
	$commande .= " 2>&1";
	$camerasdiscovery = shell_exec($commande);
	log::add('PTZONVIF','debug','GetInfos.js : '.$commande);
	log::add('PTZONVIF','debug','GetInfos.js : '.$camerasdiscovery);
	json_validate($camerasdiscovery);
	$cam = json_decode($camerasdiscovery,true);
	$eq->setConfiguration('Manufacturer',$cam['Manufacturer']);
	$eq->setConfiguration('Model',$cam['Model']);
	$eq->setConfiguration('FirmwareVersion',$cam['FirmwareVersion']);
	$eq->setConfiguration('SerialNumber',$cam['SerialNumber']);
	$eq->setConfiguration('HardwareId',$cam['HardwareId']);
	$eq->save();
	echo "<tr style='background-color: grey !important; color: white !important;'>";
	echo '<th>Manufacturer</th>';
	echo '<th>Model</th>';
	echo '<th>FirmwareVersion</th>';
	echo '<th>SerialNumber</th>';
	echo '<th>HardwareId</th></tr>';
	echo '<tr><td>'.$cam['Manufacturer'].'</td>';
	echo '<td>'.$cam['Model'].'</td>';
	echo '<td>'.$cam['FirmwareVersion'].'</td>';
	echo '<td>'.$cam['SerialNumber'].'</td>';
	echo '<td>'.$cam['HardwareId'].'</th></td></tr>';
	echo '<tr></tr>';
	
/************************************************************************
***  Extraction des presets											  ***
***  GetTokenPreset.js		                                          ***
*************************************************************************/
	$commande = "node /var/www/html/plugins/PTZONVIF/ressources/GetTokenPreset.js ";
	$commande .= $url;  
	$commande .= " ";
	$commande .= $username;  
	$commande .= " ";
	$commande .= $password;  
	$commande .= " 2>&1";
	$camerasdiscovery = shell_exec($commande);
	log::add('PTZONVIF','debug','GetTokenPreset.js : '.$commande);
	log::add('PTZONVIF','debug','GetTokenPreset.js : '.$camerasdiscovery);
	$cam = json_decode($camerasdiscovery,true);
	$nbpreset = count($cam['GetPresetsResponse']['Preset']);
	$nbpresetMax = $nbpreset;
	if ($nbpreset>5) {
		$nbpresetMax =5;
	}
	echo "<tr style='background-color: grey !important; color: white !important;'>";
	echo '<th>Tokens presets</th></tr>';

	for($i = 0; $i <= 5; $i++) {
		$cmd = cmd::byEqLogicIdAndLogicalId($ideq,'P'.$i);
		if (!is_object($cmd)) {
			 log::add('PTZONVIF','debug','erreurcommande : '.'P'.$i);
		 }
		if ($i<$nbpresetMax) {
			$cmd->setConfiguration('api',$cam['GetPresetsResponse']['Preset'][$i]['Name']);
			$cmd->setIsVisible(1);
			echo '<tr><td>'.$cam['GetPresetsResponse']['Preset'][$i]['Name'].'</td></tr>';
		}
		else {
			$cmd->setConfiguration('api','');
			$cmd->setIsVisible(0);
		}
		$cmd->save();
	}
}
else {
	event::add('jeedom::alert', array(
			'level' => 'danger',
			'page' => 'PTZONVIF',
			'message' => __('Caméra non trouvée ! ' . $adresseip, __FILE__),
		));
}

?>
	</tbody>
</table>

