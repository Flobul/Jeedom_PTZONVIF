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
//require '..ponvif.class.php';
//require_once dirname(__FILE__).'/../../../camera/3rdparty/ponvif.php';
//require_once dirname(__FILE__).'/../../3rdparty/class.ponvif.php';
//require_once dirname(__FILE__).'/../../3rdparty/Onvif/Onvif.php';
//require_once __DIR__ . '/../../vendor/autoload.php';
require_once dirname(__FILE__).'/../../3rdparty/class.ponvif.php';
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
//use Onvif\Onvif;
?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">{{Caméras ONVIF sur le réseau}}</h3>
	</div>
	<div class="panel-body">
		<table class="table table-condensed tablesorter" >
			<thead>
				<tr>
					
					<th>{{Numéro}}</th>
					<th>{{IP}}</th>
					<th>{{Port}}</th>
					<th>{{URN}}</th>
					<th>{{Name}}</th>
					<th>{{Location}}</th>
					<th>{{Type}}</th>
					<th>{{URL}}</th>
					<th>{{Plugin caméra}}</th>
					<th>{{Créer}}</th>
				</tr>
			</thead>
			<tbody>


<?php



/************************************************************************
***  Découverte des caméras sur le réseau  							  ***
***                 probe.js                                          ***
*************************************************************************/	
$onvif = new Ponvif2();
//$onvif = new Onvif();
$timeout =config::byKey('timeout', 'PTZONVIF');
if (is_null($timeout) || $timeout=='' || $timeout>15) {
	$timeout=3; // valeur par défaut
}
log::add('PTZONVIF','debug','Lancement découverte avec un timeout de : '.$timeout.' s');
$onvif->setDiscoveryTimeout($timeout);
  

$cam = $onvif->discover();
$nombrecam =count($cam);
echo '<br>'.$nombrecam.' caméra(s) trouvée(s)<br>';
log::add('PTZONVIF','debug',$nombrecam.' caméra(s) trouvée(s)');

for($i = 0; $i <= $nombrecam-1; $i++) 
   {
	log::add('PTZONVIF','debug','cam '.$i.' : '.json_encode($cam[$i]));
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
	$cptPlugCam=0;
	$eqcam=eqLogic::byTypeAndSearhConfiguration('camera', 'ip');
	foreach($eqcam as $eqLogic) {
		$ipcam=$eqLogic->getConfiguration('ip');
		$arCam=array();
		if ($host==$ipcam){
			$cptPlugCam++;
		}
	}
	
	echo '<tr>';
	echo '<td>' . $i . '</td>';
	echo '<td>' . $host . '</td>';
	echo '<td>' . $port . '</td>';
	echo '<td>' . $urn . '</td>';
	echo '<td>' . $name . '</td>';
	echo '<td>' . $location . '</td>';
	echo '<td>' . $types . '</td>';
	echo '<td>' . $xaddrs . '</td>';
	echo '<td>' . $cptPlugCam. '</td>';
	echo '<td><center><a class="btn btn-success btn_ajout" data-host="'.$host.'" data-port="'.$port.'" data-urn="'.$urn.'" data-xaddrs="'.$xaddrs.'" data-name="'.$name.'"><i class="fas fa-plus-circle"></i>  {{Créer}}</a></center></td></tr>';
}


?>

			</tbody>
		</table>
	</div>

</div>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">{{Plugin caméra : sélectionner la caméra à lier}}</h3>
	</div>
	<div class="panel-body">
		<table class="table table-condensed tablesorter" >
		<thead>
			<tr>
				<th>{{Sélection}}</th>
				<th>{{Id}}</th>
				<th>{{Nom}}</th>
				<th>{{Parent}}</th>
				<th>{{Ip}}</th>
			</tr>
		</thead>
		<tbody>
			<tr>
			<td><input type="radio" name="sel" value="0" checked > </td>
			<td>{{Aucune}}</td>
			</tr>
<?php
	$eqcam=eqLogic::byType('camera',false);
	foreach($eqcam as $eqLogic) {
		if (is_null($eqLogic->getObject_Id())){
			$parent ='Aucun';
		} else {
			$obj=jeeObject::byId( $eqLogic->getObject_Id());
			$parent = $obj->getName();
		}
		echo '<tr><td><input type="radio" name="sel" value="'.$eqLogic->getId().'"  > </td>';
		echo '<td>'.$eqLogic->getId().'</td>';
		echo '<td>' . $eqLogic->getName() . '</td>';
		echo '<td>' . $parent . '</td>';
		echo '<td>' . $eqLogic->getConfiguration('ip') . '</td></tr>';
		
	}


?>			
			
		</tbody>
	</table>
	</div>

</div>

<script>
$('.btn_ajout').on('click', function () {
console.log("bouton");
  var host = $(this).attr('data-host');
  var port = $(this).attr('data-port');
  var urn = $(this).attr('data-urn');
  var xaddrs = $(this).attr('data-xaddrs');
  var name = $(this).attr('data-name');
  var dialog_title = '';
  var dialog_message = '<form class="form-horizontal onsubmit="return false;"> ';
  dialog_title = '{{Création équipement}}';
  dialog_message += '<label class="control-label" > {{Voulez-vous créer cet équipement ?}} </label> ' +
 
  ' ';
  dialog_message += '</form>';
  bootbox.dialog({
    title: dialog_title,
    message: dialog_message,
    buttons: {
			"{{Annuler}}": {
			  className: "btn-danger",
			  callback: function () {
			  }
			},
			success: {
				label: "{{Créer}}",
				className: "btn-success",
				callback: function () {
					const rbs = document.querySelectorAll('input[name="sel"]');
					let selectedValue;
					for (const rb of rbs) {
						if (rb.checked) {
							selectedValue = rb.value;
							break;
						}
					}
					//alert(selectedValue);
					$.ajax({
						type: "POST", 
						url: "plugins/PTZONVIF/core/ajax/PTZONVIF.ajax.php", 
						data: {
							action: "creation",
							id: urn,
							name : name,
							host : host,
							port : port,
							url : xaddrs,
							idCam : selectedValue,
						},
						dataType: 'json',
						error: function (request, status, error) {
							handleAjaxError(request, status, error);
						},
						success: function (data) { 
							if (data.state != 'ok') {
								$('#div_alert').showAlert({message: data.result, level: 'danger'});
								return;
							}
						}
					});
				}
			},
	}
  });
});



</script>