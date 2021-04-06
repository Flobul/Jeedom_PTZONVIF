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


/*
* Permet la réorganisation des commandes dans l'équipement
*/
$("#table_cmd").sortable({axis: "y", cursor: "move", items: ".cmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});

/*
* Bandeau d'information
*/




$('.eqLogicAttr[data-l1key=configuration][data-l2key=adresseip]').on('change', function () {
	//var PTZONVIFId = $('#idPTZONVIF').value();

	
	var ip = $('.eqLogicAttr[data-l1key=configuration][data-l2key=adresseip]').value();
	//console.log(" IP:"+ip);
	if(ip == '' || ip == null){
	//  console.log(" export eqlogic is:"+idcmd);

			var instruction = "Assurezvous de bien renseigner l'adresse IP, le user, et le password, puis cliquer sur découverte afin de renseigner automatiquement les différents champs";
			
	} 
	$('#div_instruction').empty();
		if(instruction != '' && instruction != undefined){
		   $('#div_instruction').html('<div class="alert alert-info">'+instruction+'</div>');
		}
});

$('.eqLogicAttr[data-l1key=configuration][data-l2key=adresseip]').on('change', function () {
	//var PTZONVIFId = $('#idPTZONVIF').value();

	var ip = $('.eqLogicAttr[data-l1key=configuration][data-l2key=adresseip]').value();
	var token = $('.eqLogicAttr[data-l1key=configuration][data-l2key=token]').value();
	//console.log(" IP:"+ip);
	if(ip != '' && ip != null){
		if(token == '' || token == null){
		//  console.log(" export eqlogic is:"+idcmd);

				var instruction = "Appuyer sur le bouton anlayse afin de compléter les champs manquant";
				
		} 
	}
	$('#div_instruction').empty();
		if(instruction != '' && instruction != undefined){
		   $('#div_instruction').html('<div class="alert alert-info">'+instruction+'</div>');
		}
});

// Modale découverte
$('#bt_discover').off('click').on('click', function () {
    $('#md_modal').dialog({
		title: "{{Découverte}}",
		close: function( event, ui ) {
			window.location.reload(true);
			//location.reload();
			//console.log("fermée"); 
		  }
		});
    $('#md_modal').load('index.php?v=d&plugin=PTZONVIF&modal=discover_all').dialog('open');
	
});



$('#btn_sync').on('click', function () {
	//var PTZONVIFId = $('#idPTZONVIF').value();

  idcmd = $('.eqLogicAttr[data-l1key=id]')[0].value;
  if(idcmd != '' && idcmd != null){
	//  console.log(" export eqlogic is:"+idcmd);
		$('#md_modal').dialog({title: "{{Découverte}}"});
		$('#md_modal').load('index.php?v=d&plugin=PTZONVIF&modal=discover&id='+ idcmd).dialog('open');

		$( "#md_modal" ).dialog({
			close: function( event, ui ) {
				window.location.reload(true);
				//location.reload();
				console.log("fermée"); 
			
			},

		});

		
		
  }
  
  
});


/*
* Fonction permettant l'affichage des commandes dans l'équipement
*/
function addCmdToTable(_cmd) {
  if (!isset(_cmd)) {
     var _cmd = {configuration: {}};
   }
   if (!isset(_cmd.configuration)) {
     _cmd.configuration = {};
   }
   var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
   tr += '<td style="min-width:50px;width:70px;">';
   tr += '<span class="cmdAttr" data-l1key="id"></span>';
   tr += '</td>';
   tr += '<td style="min-width:300px;width:350px;">';
   tr += '<div class="row">';
   tr += '<div class="col-xs-7">';
   tr += '<input class="cmdAttr form-control input-sm" data-l1key="name" placeholder="{{Nom de la commande}}">';
   tr += '<select class="cmdAttr form-control input-sm" data-l1key="value" style="display : none;margin-top : 5px;" title="{{Commande information liée}}">';
   tr += '<option value="">{{Aucune}}</option>';
   tr += '</select>';
   tr += '</div>';
   tr += '<div class="col-xs-5">';
   tr += '<a class="cmdAction btn btn-default btn-sm" data-l1key="chooseIcon"><i class="fas fa-flag"></i> {{Icône}}</a>';
   tr += '<span class="cmdAttr" data-l1key="display" data-l2key="icon" style="margin-left : 10px;"></span>';
   tr += '</div>';
   tr += '</div>';
   tr += '</td>';
   tr += '<td>';
   tr += '<span class="type" type="' + init(_cmd.type) + '">' + jeedom.cmd.availableType() + '</span>';
   tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>';
   tr += '</td>';
	tr += '<td>';
	
	  tr += '<textarea class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="action" style="height : 33px;" placeholder="{{Commande}}"></textarea>';
	  
	tr += '</td>';   
	tr += '<td>';
	
	  tr += '<textarea class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="api" style="height : 33px;" placeholder="{{Commande}}"></textarea>';
	  
	tr += '</td>'; 
   tr += '<td style="min-width:120px;width:140px;">';
   tr += '<div><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" checked/>{{Afficher}}</label></div> ';
   tr += '<div><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></div> ';
   tr += '<div><label class="checkbox-inline"><input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="invertBinary"/>{{Inverser}}</label></div>';
   tr += '</td>';
   tr += '<td style="min-width:180px;">';
   tr += '<input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="minValue" placeholder="{{Min.}}" title="{{Min.}}" style="width:30%;display:inline-block;"/> ';
   tr += '<input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="maxValue" placeholder="{{Max.}}" title="{{Max.}}" style="width:30%;display:inline-block;"/> ';
   tr += '<input class="cmdAttr form-control input-sm" data-l1key="unite" placeholder="{{Unité}}" title="{{Unité}}" style="width:30%;display:inline-block;"/>';
   tr += '</td>';
   tr += '<td>';
   if (is_numeric(_cmd.id)) {
     tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fas fa-cogs"></i></a> ';
     tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fas fa-rss"></i> Tester</a>';
   }
   tr += '<i class="fas fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i></td>';
   tr += '</tr>';
   $('#table_cmd tbody').append(tr);
   $('#table_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
   jeedom.cmd.changeType($('#table_cmd tbody tr:last'), init(_cmd.subType));
   //var tr = $('#table_cmd tbody tr').last();
/* 
 jeedom.eqLogic.builSelectCmd({
     id:  $('.eqLogicAttr[data-l1key=id]').value(),
     filter: {type: 'info'},
     error: function (error) {
       $('#div_alert').showAlert({message: error.message, level: 'danger'});
     },
     success: function (result) {
       tr.find('.cmdAttr[data-l1key=value]').append(result);
       tr.setValues(_cmd, '.cmdAttr');
       jeedom.cmd.changeType(tr, init(_cmd.subType));
     }
   });
*/
 }