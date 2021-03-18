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

/* A supprimer !!!!! */

$('#btSaveDiscov').off('click').on('click', function () {
	console.log("test");
	$('.modal-content').html('');
	$('#md_modal').on('hidden.bs.modal', function () {
		window.location.reload(true);
	});
	$('#md_modal').modal('hide');

	$('.modal-content').html('');

	//va vider le contenu de la modal (pour éviter qu’il se réaffiche si la modal est ouverte) ; c’est optionnel
	$('#md_modal').on('hidden.bs.modal', function () {
	window.location.reload(true);
	});

	//passe l’instruction de recharger (reload) la page parente lorsque la modal sera ferméeEt enfin,
	$('#md_modal').modal('hide');

	//passe l’instruction de fermer la modal.
});

