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
require_once dirname(__FILE__).'/../../3rdparty/class.ponvif.php';
//include_file('3rdparty', dirname(__FILE__).'/../../json-view-master/dist/jsonview', 'js');
//include_file('3rdparty', 'jquery.fileTree/jqueryFileTree', 'css', 'script');
//include_file('3rdparty', 'codemirror/lib/codemirror', 'js');
//include_file('3rdparty', 'codemirror/lib/codemirror', 'css');
//require_once __DIR__ . '/../../vendor/autoload.php';
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
if (init('id') == '') {
    throw new Exception('{{L\'id de l\'opération ne peut etre vide : }}' . init('id'));
}
//use Onvif\Onvif;
?>


<div id='div_OptionsAlert'></div>
<div class="col-lg-12">
</div>

<table class="table table-condensed" >
	<thead>
		<tr style="background-color: #5078aa !important; color: white !important;">
			<th>{{IP}}</th>
			<th>{{Port}}</th>
			<th>{{URN}}</th>
			
		</tr>
	</thead>
	<tbody>




<?php

function json_export($name,$txt) {
	$outpout=json_encode($txt);
	log::add('PTZONVIF','debug',$name.' : '.$outpout);
	$file=(__DIR__).'/../../ressources/'.$name;
    $handle = fopen($file, "w");
    fwrite($handle, $outpout);
    fclose($handle);
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


/************************************************************************
***  Découverte des caméras sur le réseau et selection de celle qui   ***
***  correspond à l'IP   ->discover                                  ***
*************************************************************************/
$onvif = new ponvif2();
$timeout =config::byKey('timeout', 'PTZONVIF');
if (is_null($timeout) || $timeout=='' || $timeout>15) {
	$timeout=3; // valeur par défaut
}
log::add('PTZONVIF','debug','Lancement de la recherche sur le réseau avec un timeout de : '.$timeout.' s');
$onvif->setDiscoveryTimeout($timeout);
$cam = $onvif->discover();
json_export('discover.json',$cam);
$nombrecam =count($cam);
//var_dump($cam);
$trouve = false;
for($i = 0; $i <= $nombrecam-1; $i++) 
{	
	$IPAddr = $cam[$i]['IPAddr'] ;
	if($IPAddr == $adresseip) {
		log::add('PTZONVIF','debug','Caméra trouvée : '.json_encode($cam[$i]));
		
		$urn = $cam[$i]['EndpointReference']['Address'];
		$types = $cam[$i]['Types'];   
		$xaddrs = $cam[$i]['XAddrs'] ; 
		$scope = $cam[$i]['Scopes'];
		$scopes = explode(" ",$scope);
		
		foreach ($scopes as $scopes_){
			$paths =parse_url($scopes_,PHP_URL_PATH);
			$path = explode("/",$paths);
			switch ($path[1]){
				case 'name':
					$name=$path[2];
					break;
				case 'location':
					$location=$path[3];
					break;
				case 'hardware':
					$hardware=$path[2];
					break;	
			}
			
		}
		$host=parse_url($xaddrs, PHP_URL_HOST);	
		$port = parse_url($xaddrs, PHP_URL_PORT);  
		$trouve = true;
		
		break;
	}
  

}
/************************************************************************
***  Traitement de la caméra trouvée								  ***
***  					                                              ***
*************************************************************************/
if ($trouve){
	echo '<tr><td>' . $adresseip . '</td>';
	echo '<td>' . $port . '</td>';
	echo '<td>' . $urn . '</td></tr>';
	
	echo "<tr style='background-color: #5078aa !important; color: white !important;'>";
	echo '<th>Name</th>';
	echo '<th>Location</th></tr>';
	
	echo '<tr><td>' . $name . '</td>';
	echo '<td>' . $location . '</td></tr>';
	echo '<tr></tr>';
	
	echo "<tr style='background-color: #5078aa !important; color: white !important;'>";
	echo '<th>xaddrs</th></tr>';
	$xaddr = explode(" ",$xaddrs);
	$url = $xaddr[0];
	foreach ($xaddr as $value) {
		echo '<tr><td>'.$value.'</td></tr>';
	}
	echo '<tr></tr>';
	
	echo "<tr style='background-color: #5078aa !important; color: white !important;'>";
	echo '<th>Types</th></tr>';
	$type = explode(" ",$types);
	foreach ($type as $value) {
		echo '<tr><td>'.$value.'</td></tr>';
	}
	echo '<tr></tr>';
	log::add('PTZONVIF','debug','URL : '.$url.' - Port :'.$port.' - Name : '.$name.' - URN :'.$urn);
	$eq->setConfiguration('URL',$url);
	$eq->setConfiguration('port',$port);
	$eq->setConfiguration('Name',$name);
	$eq->setConfiguration('URN',$urn);
	
/************************************************************************
***  Extraction des infos											  ***
***  core_GetDeviceInformation                                        ***
*************************************************************************/
	$onvif->setUsername($eq->getConfiguration('username'));
	$onvif->setPassword($eq->getConfiguration('password'));
	$onvif->setIPAddress($IPAddr);
	$onvif->setMediaUri($xaddr[0]);
	$onvif->initialize();
  	
	try{$version= implode(".",$onvif->getSupportedVersion());}
	catch(Exception $e){
		log::add('PTZONVIF','ALERT','getSupportedVersion : '.$e);
	}
	try {
      $cam = $onvif->core_GetDeviceInformation();
      $eq->setConfiguration('Manufacturer',$cam['Manufacturer']);
      $eq->setConfiguration('Model',$cam['Model']);
      $eq->setConfiguration('FirmwareVersion',$cam['FirmwareVersion']);
      $eq->setConfiguration('SerialNumber',$cam['SerialNumber']);
      $eq->setConfiguration('HardwareId',$cam['HardwareId']);
      log::add('PTZONVIF','debug','core_GetDeviceInformation : '.json_encode($cam));
    }
  	catch(Exception $e){
		log::add('PTZONVIF','ALERT','core_GetDeviceInformation : '.$e);
	}
	
	
	
	$eq->setConfiguration('Version',$version);
	$eq->save();
	json_export($eq->getConfiguration('Model').'-core_GetDeviceInformation.json',$cam);
	echo "<tr style='background-color: #5078aa !important; color: white !important;'>";
	echo '<th>Manufacturer</th>';
	echo '<th>Model</th></tr>';
	
	echo '<tr><td>'.$cam['Manufacturer'].'</td>';
	echo '<td>'.$cam['Model'].'</td></tr>';
	
	echo "<tr style='background-color: #5078aa !important; color: white !important;'>";
	echo '<th>FirmwareVersion</th>';
	echo '<th>SerialNumber</th>';
	echo '<th>HardwareId</th></tr>';
	
	echo '<tr><td>'.$cam['FirmwareVersion'].'</td>';
	echo '<td>'.$cam['SerialNumber'].'</td>';
	echo '<td>'.$cam['HardwareId'].'</th></td></tr>';
	echo '<tr></tr>';	
	
	try {	
      $profiles =$onvif->media_GetProfiles();
      $token = $profiles[0]['@attributes']['token'];
  	  $eq->setConfiguration('token',$token);
	  json_export($eq->getConfiguration('Model').'-media_GetProfiles.json',$profiles);
      try { 
      $rtsp = $onvif->media_GetStreamUri($token);
      $eq->setConfiguration('rtsp',$rtsp);
      } 
      catch(Exception $e){
        log::add('PTZONVIF','ALERT','media_GetStreamUri : '.$e);
      }
      try {
        $snapshot = $onvif->media_GetSnapshotUri($token);
        $eq->setConfiguration('snapshot',$snapshot);
      }
      catch(Exception $e){
        log::add('PTZONVIF','ALERT','media_GetSnapshotUri : '.$e);
      }
    }
  	catch(Exception $e){
		log::add('PTZONVIF','ALERT','media_GetProfiles : '.$e);
	}
	

	
	
	try {$eq->setConfiguration('ptzuri',$onvif->getPTZUri());}
  	catch(Exception $e){
		log::add('PTZONVIF','ALERT','getPTZUri : '.$e);
	}
	$eq->save();
  	
	echo "<tr style='background-color: #5078aa !important; color: white !important;'>";
	echo '<th>Token</th>';
	echo '<th>snapshot</th>';
	echo '<th>rtsp</th></tr>';
	echo '<tr><td>'.$token.'</td>';
	echo '<td>'.$snapshot.'</td>';
	echo '<td>'.$rtsp.'</th></td></tr>';
	echo '<tr></tr>';


	
/************************************************************************
***  Extraction des presets											  ***
***  ptz_GetPresets  		                                          ***
*************************************************************************/

	
	try {
		$capabilities = $onvif->core_GetCapabilities();
		json_export($eq->getConfiguration('Model').'-capabilities.json',$capabilities);		
		} 
	catch(Exception $e){
		log::add('PTZONVIF','ALERT','core_GetCapabilities : '.$e);
		}
 
	try {
		$presets = $onvif->ptz_GetPresets($token);
		json_export($eq->getConfiguration('Model').'-ptz_GetPresets.json',$presets);
		} 
	catch(Exception $e){
		log::add('PTZONVIF','ALERT','ptz_GetPresets : '.$e);
		}      
	







	$nbpreset = count($presets); 
	$nbpresetMax = $nbpreset;
	if ($nbpreset>5) {
		$nbpresetMax =5;
	}
	echo "<tr style='background-color: #5078aa !important; color: white !important;'>";
	echo '<th>Tokens presets</th>';
	echo '<th>Name</th></tr>';

	for($i = 0; $i <= 5; $i++) {
		$cmd = cmd::byEqLogicIdAndLogicalId($ideq,'P'.$i);
		if (!is_object($cmd)) {
			 log::add('PTZONVIF','debug','erreurcommande : '.'P'.$i);
		 }
		if ($i<$nbpresetMax) {
			$cmd->setConfiguration('api',$presets[$i]['Token']);
			$cmd->setConfiguration('presetName',$presets[$i]['Name']);
			$cmd->setIsVisible(1);
			echo '<tr><td>'.$presets[$i]['Token'].'</td>';
			echo '<td>'.$presets[$i]['Name'].'</td></tr>';
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
	Caméras
    <div class="root"></div>
  	Profiles
  	<div class="profiles"></div>
    capabilities
    <div class="capabilities"></div>
    presets
    <div class="presets"></div>
    


  <script type="text/javascript" src="plugins/PTZONVIF/3rdparty/json-view-master/dist/jsonview.js"></script>
  <script type="text/javascript">
      	const tree = jsonview.create(<?php echo json_encode($cam); ?>);
		const tree2 = jsonview.create(<?php echo json_encode($profiles); ?>);
		const tree3 = jsonview.create(<?php echo json_encode($capabilities); ?>);
		const tree4 = jsonview.create(<?php echo json_encode($presets); ?>);
      	jsonview.render(tree, document.querySelector('.root'));
		jsonview.collapse(tree);
		jsonview.render(tree2, document.querySelector('.profiles'));
		jsonview.collapse(tree2);
		jsonview.render(tree3, document.querySelector('.capabilities'));
		jsonview.collapse(tree3);
		jsonview.render(tree4, document.querySelector('.presets'));
		jsonview.collapse(tree4);


  </script>