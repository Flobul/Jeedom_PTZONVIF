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
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">{{Caméras ONVIF sur le réseau}}</h3>
	</div>
	<div class="panel-body">
		<table class="table table-condensed tablesorter" >
			<thead>
				<tr>
					<th>{{Sélection}}</th>
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
function json_validate($_test){
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
***  Découverte des caméras sur le réseau  							  ***
***                 probe.js                                          ***
*************************************************************************/	
$commande = "node /var/www/html/plugins/PTZONVIF/ressources/probe.js 2>&1";  
$camerasdiscovery = shell_exec($commande);
log::add('PTZONVIF','debug','probe.js : '.$camerasdiscovery);

//print_r($camerasdiscovery);
json_validate($camerasdiscovery);
$cam = json_decode($camerasdiscovery,true);
$nombrecam =count($cam);
//echo '<br>'.$nombrecam.' caméra(s) trouvée(s)<br>';
//echo '<br>';
//		var_dump(json_decode($camerasdiscovery, true));
//echo '<br>';
//echo 'Last error: ', json_last_error_msg(), PHP_EOL, PHP_EOL;
//echo '<br>';echo '<br>';

for($i = 0; $i <= $nombrecam-1; $i++) 
   {
	$urn = $cam[$i]['urn'];
	$name = $cam[$i]['name'];
	$location = $cam[$i]['location'];
	$types = $cam[$i]['types'];   
	$xaddrs = implode("", $cam[$i]['xaddrs']) ; 
	$host=parse_url($xaddrs, PHP_URL_HOST);	
	$port = parse_url($xaddrs, PHP_URL_PORT);
	$cptPlugCam=0;
	$eqcam=eqLogic::byTypeAndSearhConfiguration('camera', 'ip');
	log::add('PTZONVIF','debug','cam : ');
	foreach($eqcam as $eqLogic) {
		$ipcam=$eqLogic->getConfiguration('ip');
		$arCam=array();
		log::add('PTZONVIF','debug','cam : '.$ipcam);
		if ($host==$ipcam){
			$cptPlugCam++;
		}
	}
	
	echo '<tr><td><input type="radio" name="sel" value="'.$i.'" checked > </td>';
	echo '<td>' . $i . '</td>';
	echo '<td>' . $host . '</td>';
	echo '<td>' . $port . '</td>';
	echo '<td>' . $urn . '</td>';
	echo '<td>' . $name . '</td>';
	echo '<td>' . $location . '</td>';
	echo '<td>' . implode("", $types) . '</td>';
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
		<h3 class="panel-title">{{Plugin caméra}}</h3>
	</div>
	<div class="panel-body">
		<table class="table table-condensed tablesorter" >
		<thead>
			<tr>
				<th>{{Id}}</th>
				<th>{{Nom}}</th>
				<th>{{Parent}}</th>
				<th>{{Ip}}</th>
			</tr>
		</thead>
		<tbody>
			<td class="Idcam"></td>
			<td class="nomCam"></td>
			<td class="ParentCam"></td>
			<td class="Ipcam"></td>
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

function wichsel()
{
  var element = document.form1.fruit;
  for (var i=0; i < element.length; i++)
  {
    if (element[i].checked)
    {
      var selection = element[i].value;
      break;
    }
  }
}

</script>