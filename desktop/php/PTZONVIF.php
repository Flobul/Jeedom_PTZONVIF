<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
// Déclaration des variables obligatoires
$plugin = plugin::byId('PTZONVIF');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>




<div class="row row-overflow">
	<!-- Page daccueil du plugin -->
	<div class="col-xs-12 eqLogicThumbnailDisplay">
		<legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
		<!-- Boutons de gestion du plugin -->
		<div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction logoPrimary" data-action="add">
				<i class="fas fa-plus-circle"></i>
				<br>
				<span>{{Ajouter}}</span>
			</div>
			<div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
				<i class="fas fa-wrench"></i>
				<br>
				<span>{{Configuration}}</span>
		    </div>
  			<div class="cursor logoSecondary" id="bt_discover">
                <i class="fas fa-search"></i>
                <br>
                <span>{{Découverte}}</span>
            </div>
			<div class="cursor pluginAction logoSecondary" data-action="openLocation" data-location="<?=$plugin->getDocumentation()?>">
                <i class="fas fa-book"></i>
                <br>
                <span>{{Documentation}}</span>
            </div>
		</div>
		<legend><i class="fas fa-table"></i> {{Mes caméras ONVIF}}</legend>
		<!-- Champ de recherche -->
		<div class="input-group" style="margin:5px;">
			<input class="form-control roundedLeft" placeholder="{{Rechercher}}" id="in_searchEqlogic"/>
			<div class="input-group-btn">
				<a id="bt_resetSearch" class="btn roundedRight" style="width:30px"><i class="fas fa-times"></i></a>
			</div>
		</div>
		<!-- Liste des équipements du plugin -->
		<div class="eqLogicThumbnailContainer">
			<?php
			foreach ($eqLogics as $eqLogic) {
				$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
				echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
				echo '<img src="' . $plugin->getPathImgIcon() . '"/>';
				echo '<br>';
				echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
				echo '</div>';
			}
			?>
		</div>
	</div> <!-- /.eqLogicThumbnailDisplay -->

	<!-- Page de présentation de l équipement -->
	<div class="col-xs-12 eqLogic" style="display: none;">
		<!-- barre de gestion de l'équipement -->
		<div class="input-group pull-right" style="display:inline-flex;">
			<span class="input-group-btn">
				<!-- Les balises <a></a> sont volontairement fermées à la ligne suivante pour éviter les espaces entre les boutons. Ne pas modifier -->
				<a class="btn btn-sm btn-warning eqLogicAction syncinfo roundedLeft" id="btn_sync"><i class="fas fa-spinner" title="{{Découverte}}"></i> {{Découverte}}</a>
				<a class="btn btn-sm btn-default eqLogicAction" data-action="configure"><i class="fas fa-cogs"></i><span class="hidden-xs"> {{Configuration avancée}}</span>
				</a><a class="btn btn-sm btn-default eqLogicAction" data-action="copy"><i class="fas fa-copy"></i><span class="hidden-xs">  {{Dupliquer}}</span>
				</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}
				</a><a class="btn btn-sm btn-danger eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}
				</a>
			</span>
		</div>
		<!-- Onglets -->
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
			<li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i><span class="hidden-xs"> {{Équipement}}</span></a></li>
			<li role="presentation"><a href="#commandtab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-list"></i><span class="hidden-xs"> {{Commandes}}</span></a></li>
		</ul>
	 <div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
                <div role="tabpanel" class="tab-pane active" id="eqlogictab">
                    <br/>
					<div class="row">
						<div class="col-sm-7">
						
							<form class="form-horizontal">
								<fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="name">{{Nom de l'équipement
                                    ONVIF}}</label>
                                <div class="col-sm-3">
                                    <input type="text" class="eqLogicAttr form-control" data-l1key="id"
                                           style="display : none;"/>
                                    <input type="text" class="eqLogicAttr form-control" data-l1key="name" id="name"
                                           placeholder="{{Nom de l'équipement ONVIF}}"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="sel_object">{{Objet parent}}</label>
                                <div class="col-sm-3">
                                    <select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
                                        <option value="">{{Aucun}}</option>
                                        <?php
                                        foreach (jeeObject::all() as $object) {
                                            echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{Catégorie}}</label>
                                <div class="col-sm-9">
                                    <?php
                                    foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                                        echo '<label class="checkbox-inline" for="category-' . $key . '">';
                                        echo '<input type="checkbox" id="category-' . $key . '" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
                                        echo '</label>';
                                    }
                                    ?>
                                </div>
                            </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" ></label>
                            <div class="col-sm-10">
                            <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-label-text="{{Activer}}" data-l1key="isEnable" checked/>Activer</label>
                            <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-label-text="{{Visible}}" data-l1key="isVisible" checked/>Visible</label>
                            </div>
                            
                        </div>
						<div class="form-group">
                            <label class="col-lg-2 control-label"></label>
                            
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">{{IP de la Camera}}</label>
                            <div class="col-lg-3">
                                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="adresseip"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">{{Port}}</label>
                            <div class="col-lg-3">
                                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="port"/>
                            </div>
                        </div>
                                      
                        <div class="form-group">
                            <label class="col-lg-2 control-label">{{Utilisateur}}</label>
                            <div class="col-lg-3">
                                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="username"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">{{Mot de passe}}</label>
                            <div class="col-lg-3">
                                <input type="password" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="password"/>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="col-lg-2 control-label">{{token}}</label>
                            <div class="col-lg-3">
                                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="token"/>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="col-lg-2 control-label">{{URL}}</label>
                            <div class="col-lg-9">
                                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="URL"/>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="col-lg-2 control-label">{{rtsp}}</label>
                            <div class="col-lg-9">
                                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="rtsp"/>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="col-lg-2 control-label">{{snapshot}}</label>
                            <div class="col-lg-9">
                                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="snapshot"/>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="col-lg-2 control-label">{{Manufacturer}}</label>
                            <div class="col-lg-9">
                                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="Manufacturer"/>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="col-lg-2 control-label">{{Model}}</label>
                            <div class="col-lg-9">
                                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="Model"/>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="col-lg-2 control-label">{{FirmwareVersion}}</label>
                            <div class="col-lg-9">
                                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="FirmwareVersion"/>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="col-lg-2 control-label">{{SerialNumber}}</label>
                            <div class="col-lg-9">
                                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="SerialNumber"/>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="col-lg-2 control-label">{{HardwareId}}</label>
                            <div class="col-lg-9">
                                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="HardwareId"/>
                            </div>
                        </div>
                        </fieldset>
                    </form>
                
						</div>  
						<div id="infoNode" class="col-sm-4">
							<fieldset>
								<legend>{{Informations}}</legend>
								<div class="form-group">
									<div id="div_instruction"></div>
								</div> 	
							</fieldset>					
						</div>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane" id="commandtab">
                    <a class="btn btn-success btn-sm cmdAction pull-right" data-action="add" style="margin-top:5px;">
                        <i class="fa fa-plus-circle"></i> {{Commandes}}
                    </a>
                    <br/>
                    <br/>
                    <table id="table_cmd" class="table table-bordered table-condensed">
                        <thead>
                        <tr>
                            <th>{{Nom}}</th>
                            <th>{{Type}}</th>
                            <th>{{Action}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

	</div><!-- /.eqLogic -->
</div><!-- /.row row-overflow -->

<!-- Inclusion du fichier javascript du plugin (dossier, nom_du_fichier, extension_du_fichier, id_du_plugin) -->
<?php include_file('desktop', 'PTZONVIF', 'js', 'PTZONVIF');?>
<?php include_file('desktop', 'PTZONVIF', 'css', 'PTZONVIF');?>
<!-- Inclusion du fichier javascript du core - NE PAS MODIFIER NI SUPPRIMER -->
<?php include_file('core', 'plugin.template', 'js');?>