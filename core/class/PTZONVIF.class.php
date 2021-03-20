<?php

require_once dirname(__FILE__).'/../../../../core/php/core.inc.php';

class PTZONVIF extends eqLogic {

  /*************** Attributs ***************/
    private $_Username;
    private $_Password;
    private $_IPadress;
    private $_Port;
    private $_VideoToken;
    private $_PTZToken; 
    private $_data;
    private $_Xmin;
    private $_Xmax;
    private $_Ymin;
    private $_Ymax;
    private $_Zmin;
    private $_Zmax;
    private $_Xspeedmin;
    private $_Xspeedmax;
    private $_Yspeedmin;
    private $_Yspeedmax;
    private $_Zspeedmin;
    private $_Zspeedmax;
    private $_ZoomSpeedMin;
    private $_ZoomSpeedMax;
    private $_PanTiltSpeedMin;
    private $_PanTiltSpeedMax;
    private $_nombrecamera;
  /************* Static methods ************/
  	public static function dependancy_info() {
      
      	//log::add('PTZONVIF_dep', 'warning', '--- DEPENDANCY INFO ---');
      
      	$return = array(); 
      
		$return['log'] = 'PTZONVIF_dep'; 
		$node_onvif = realpath(dirname(__FILE__) . '/../../ressources/node_modules/node-onvif');
		$return['progress_file'] = '/tmp/PTZONVIF_dep'; 
     	
		if (is_dir($node_onvif)) {
		  $return['state'] = 'ok';
		} else {
		  $return['state'] = 'nok';
		}

      
		return $return; 
  	
    }

  	public static function dependancy_install() {
    
      	log::add('PTZONVIF', 'info', '--- DEPENDANCY INSTALL ---');
      
      	if (file_exists('/tmp/PTZONVIF_dep')) { 
			return; 
		} 
      
		log::remove('PTZONVIF_dep'); 
      
		$cmd = 'sudo /bin/bash ' . dirname(__FILE__) . '/../../ressources/install.sh'; 
		$cmd .= ' >> ' . log::getPathToLog('PTZONVIF_dep') . ' 2>&1 &'; 
      
		exec($cmd); 
      
  	}
  

  /**************** Methods ****************/
// Fonction exécutée automatiquement avant la création de l'équipement 
    public function preInsert() {
        
    }

 // Fonction exécutée automatiquement après la création de l'équipement 
    public function postInsert() {
        $getDataCmd = $this->getCmd(null, 'Haut');
        if (!is_object($getDataCmd))
        {
            $cmd = new PTZONVIFCmd();
            $cmd->setName('Haut');
            $cmd->setLogicalId('Haut');
            $cmd->setEqLogic_id($this->getId());
            $cmd->setType('action');
            $cmd->setSubType('other');
			$cmd->setDisplay('icon', '<i class="fas fa-arrow-up"></i>');
            $cmd->setIsVisible(1);
			$cmd->setConfiguration('action','move');
			$cmd->setConfiguration('api','up');
            $cmd->save();
        }
		$getDataCmd = $this->getCmd(null, 'Bas');
        if (!is_object($getDataCmd))
        {
            $cmd = new PTZONVIFCmd();
            $cmd->setName('Bas');
            $cmd->setLogicalId('Bas');
            $cmd->setEqLogic_id($this->getId());
            $cmd->setType('action');
            $cmd->setSubType('other');
			$cmd->setDisplay('icon', '<i class="fas fa-arrow-down"></i>');
            $cmd->setIsVisible(1);
			$cmd->setConfiguration('action','move');
			$cmd->setConfiguration('api','down');
            $cmd->save();
        }
		$getDataCmd = $this->getCmd(null, 'Gauche');
        if (!is_object($getDataCmd))
        {
            $cmd = new PTZONVIFCmd();
            $cmd->setName('Gauche');
            $cmd->setLogicalId('Gauche');
            $cmd->setEqLogic_id($this->getId());
            $cmd->setType('action');
            $cmd->setSubType('other');
			$cmd->setDisplay('icon', '<i class="fas fa-arrow-left"></i>');
            $cmd->setIsVisible(1);
			$cmd->setConfiguration('action','move');
			$cmd->setConfiguration('api','left');
            $cmd->save();
        }
		$getDataCmd = $this->getCmd(null, 'Droite');
        if (!is_object($getDataCmd))
        {
            $cmd = new PTZONVIFCmd();
            $cmd->setName('Droite');
            $cmd->setLogicalId('Droite');
            $cmd->setEqLogic_id($this->getId());
            $cmd->setType('action');
            $cmd->setSubType('other');
			$cmd->setDisplay('icon', '<i class="fas fa-arrow-right"></i>');
            $cmd->setIsVisible(1);
			$cmd->setConfiguration('action','move');
			$cmd->setConfiguration('api','right');
            $cmd->save();
        }
		$getDataCmd = $this->getCmd(null, 'ZoomIn');
        if (!is_object($getDataCmd))
        {
            $cmd = new PTZONVIFCmd();
            $cmd->setName('ZoomIn');
            $cmd->setLogicalId('ZoomIn');
            $cmd->setEqLogic_id($this->getId());
            $cmd->setType('action');
            $cmd->setSubType('other');
			$cmd->setDisplay('icon', '<i class="fas fa-search-plus"></i>');
            $cmd->setIsVisible(1);
			$cmd->setConfiguration('action','move');
			$cmd->setConfiguration('api','ZoomIn');
            $cmd->save();
        }
				$getDataCmd = $this->getCmd(null, 'ZoomOut');
        if (!is_object($getDataCmd))
        {
            $cmd = new PTZONVIFCmd();
            $cmd->setName('ZoomOut');
            $cmd->setLogicalId('ZoomOut');
            $cmd->setEqLogic_id($this->getId());
            $cmd->setType('action');
            $cmd->setSubType('other');
			$cmd->setDisplay('icon', '<i class="fas fa-search-minus"></i>');
            $cmd->setIsVisible(1);
			$cmd->setConfiguration('action','move');
			$cmd->setConfiguration('api','ZoomOut');
            $cmd->save();
        }
		$getDataCmd = $this->getCmd(null, 'P0');
        if (!is_object($getDataCmd))
        {
            $cmd = new PTZONVIFCmd();
            $cmd->setName('P0');
            $cmd->setLogicalId('P0');
            $cmd->setEqLogic_id($this->getId());
            $cmd->setType('action');
            $cmd->setSubType('other');
            $cmd->setIsVisible(1);
			$cmd->setConfiguration('action','preset');
			$cmd->setConfiguration('api','P0');
            $cmd->save();
        }
		$getDataCmd = $this->getCmd(null, 'P1');
        if (!is_object($getDataCmd))
        {
            $cmd = new PTZONVIFCmd();
            $cmd->setName('P1');
            $cmd->setLogicalId('P1');
            $cmd->setEqLogic_id($this->getId());
            $cmd->setType('action');
            $cmd->setSubType('other');
            $cmd->setIsVisible(1);
			$cmd->setConfiguration('action','preset');
			$cmd->setConfiguration('api','P1');
            $cmd->save();
        }
		$getDataCmd = $this->getCmd(null, 'P2');
        if (!is_object($getDataCmd))
        {
            $cmd = new PTZONVIFCmd();
            $cmd->setName('P2');
            $cmd->setLogicalId('P2');
            $cmd->setEqLogic_id($this->getId());
            $cmd->setType('action');
            $cmd->setSubType('other');
            $cmd->setIsVisible(1);
			$cmd->setConfiguration('action','preset');
			$cmd->setConfiguration('api','P2');
            $cmd->save();
        }
		$getDataCmd = $this->getCmd(null, 'P3');
        if (!is_object($getDataCmd))
        {
            $cmd = new PTZONVIFCmd();
            $cmd->setName('P3');
            $cmd->setLogicalId('P3');
            $cmd->setEqLogic_id($this->getId());
            $cmd->setType('action');
            $cmd->setSubType('other');
            $cmd->setIsVisible(1);
			$cmd->setConfiguration('action','preset');
			$cmd->setConfiguration('api','P3');
            $cmd->save();
        }
		$getDataCmd = $this->getCmd(null, 'P4');
        if (!is_object($getDataCmd))
        {
            $cmd = new PTZONVIFCmd();
            $cmd->setName('P4');
            $cmd->setLogicalId('P4');
            $cmd->setEqLogic_id($this->getId());
            $cmd->setType('action');
            $cmd->setSubType('other');
            $cmd->setIsVisible(1);
			$cmd->setConfiguration('action','preset');
			$cmd->setConfiguration('api','P4');
            $cmd->save();
        }
		$getDataCmd = $this->getCmd(null, 'P5');
        if (!is_object($getDataCmd))
        {
            $cmd = new PTZONVIFCmd();
            $cmd->setName('P5');
            $cmd->setLogicalId('P5');
            $cmd->setEqLogic_id($this->getId());
            $cmd->setType('action');
            $cmd->setSubType('other');
            $cmd->setIsVisible(1);
			$cmd->setConfiguration('action','preset');
			$cmd->setConfiguration('api','P5');
            $cmd->save();
        }
				$getDataCmd = $this->getCmd(null, 'S0');
        if (!is_object($getDataCmd))
        {
            $cmd = new PTZONVIFCmd();
            $cmd->setName('S0');
            $cmd->setLogicalId('S0');
            $cmd->setEqLogic_id($this->getId());
            $cmd->setType('action');
            $cmd->setSubType('other');
            $cmd->setIsVisible(1);
			$cmd->setConfiguration('action','setpreset');
			$cmd->setConfiguration('api','S0');
            $cmd->save();
        }
		$getDataCmd = $this->getCmd(null, 'S1');
        if (!is_object($getDataCmd))
        {
            $cmd = new PTZONVIFCmd();
            $cmd->setName('S1');
            $cmd->setLogicalId('S1');
            $cmd->setEqLogic_id($this->getId());
            $cmd->setType('action');
            $cmd->setSubType('other');
            $cmd->setIsVisible(1);
			$cmd->setConfiguration('action','setpreset');
			$cmd->setConfiguration('api','S1');
            $cmd->save();
        }
		$getDataCmd = $this->getCmd(null, 'S2');
        if (!is_object($getDataCmd))
        {
            $cmd = new PTZONVIFCmd();
            $cmd->setName('S2');
            $cmd->setLogicalId('S2');
            $cmd->setEqLogic_id($this->getId());
            $cmd->setType('action');
            $cmd->setSubType('other');
            $cmd->setIsVisible(1);
			$cmd->setConfiguration('action','setpreset');
			$cmd->setConfiguration('api','S2');
            $cmd->save();
        }
		$getDataCmd = $this->getCmd(null, 'S3');
        if (!is_object($getDataCmd))
        {
            $cmd = new PTZONVIFCmd();
            $cmd->setName('S3');
            $cmd->setLogicalId('S3');
            $cmd->setEqLogic_id($this->getId());
            $cmd->setType('action');
            $cmd->setSubType('other');
            $cmd->setIsVisible(1);
			$cmd->setConfiguration('action','setpreset');
			$cmd->setConfiguration('api','S3');
            $cmd->save();
        }
		$getDataCmd = $this->getCmd(null, 'S4');
        if (!is_object($getDataCmd))
        {
            $cmd = new PTZONVIFCmd();
            $cmd->setName('S4');
            $cmd->setLogicalId('S4');
            $cmd->setEqLogic_id($this->getId());
            $cmd->setType('action');
            $cmd->setSubType('other');
            $cmd->setIsVisible(1);
			$cmd->setConfiguration('action','setpreset');
			$cmd->setConfiguration('api','S4');
            $cmd->save();
        }
		$getDataCmd = $this->getCmd(null, 'S5');
        if (!is_object($getDataCmd))
        {
            $cmd = new PTZONVIFCmd();
            $cmd->setName('S5');
            $cmd->setLogicalId('S5');
            $cmd->setEqLogic_id($this->getId());
            $cmd->setType('action');
            $cmd->setSubType('other');
            $cmd->setIsVisible(1);
			$cmd->setConfiguration('action','setpreset');
			$cmd->setConfiguration('api','S5');
            $cmd->save();
        }

    }

 // Fonction exécutée automatiquement avant la mise à jour de l'équipement 
    public function preUpdate() {
        
    }

 // Fonction exécutée automatiquement après la mise à jour de l'équipement 
    public function postUpdate() {
		
        
    }

 // Fonction exécutée automatiquement avant la sauvegarde (création ou mise à jour) de l'équipement 
    public function preSave() {
        
    }

 // Fonction exécutée automatiquement après la sauvegarde (création ou mise à jour) de l'équipement 
    public function postSave() {
        
    }

 // Fonction exécutée automatiquement avant la suppression de l'équipement 
    public function preRemove() {
        
    }

 // Fonction exécutée automatiquement après la suppression de l'équipement 
    public function postRemove() {
        
    }
	

  
    public function refresh()
    {

      /*  $this -> getdeviceinfo();
        $this -> gethost();
        $this -> getprofile();
        $this -> getsnap();
        $this -> getnodes();
        $this -> getimage2();
        $this -> getstream();
        $this -> getpresets();
        $this -> getnodes();*/
    }


    public static function discover($_Ip) {
       
    }
	
	public static function creation($_id, $_name, $_Ip, $_port, $_url) {
		$logical_id = str_replace("urn:uuid:","",$_id);
		log::add('PTZONVIF', 'debug', 'Création : '.$_Ip);
		$PTZONVIF=PTZONVIF::byLogicalId($logical_id, 'PTZONVIF');
		if (!is_object($PTZONVIF)) {
			$PTZONVIF = new PTZONVIF();
			$PTZONVIF->setEqType_name('PTZONVIF');
			$PTZONVIF->setLogicalId($logical_id);
			$PTZONVIF->setIsEnable(1);
			$PTZONVIF->setIsVisible(1);
			$PTZONVIF->setName($_name . ' ' . $logical_id);
			$PTZONVIF->setConfiguration('adresseip', $_Ip);
			$PTZONVIF->setConfiguration('port', $_port);
			$PTZONVIF->setConfiguration('URL', $_url);	
			$PTZONVIF->setConfiguration('vitPTZ', "500");			
		}
		else {
			log::add('PTZONVIF', 'debug', 'Déjà connu  : '.$_Ip);
			event::add('jeedom::alert', array(
                    'level' => 'danger',
                    'page' => 'PTZONVIF',
                    'message' => __('Caméra déjà existante ! ' . $_Ip, __FILE__),
                ));
			return false;
		}
		$PTZONVIF->save();
		event::add('jeedom::alert', array(
                    'level' => 'warning',
                    'page' => 'PTZONVIF',
                    'message' => __('Caméra créée avec succès ' . $_Ip, __FILE__),
                ));
		return $PTZONVIF;		
    }
 

  /********** Getters and setters **********/

}

class PTZONVIFCmd extends cmd  {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */



    /*     * *********************Methode d'instance************************* */

    /*
     * Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
      public function dontRemoveCmd() {
      return true;
      }
     */



	/**********************************************/
	/*                                            */
	/*-----------------INTERVALES-----------------*/
	/*                                            */
	/**********************************************/
	  



	/**********************************************/
	/*                                            */
	/*--------------LISTE DES ACTIONS-------------*/
	/*                                            */
	/**********************************************/

  
    public function execute($_options = array())

    {
		log::add('PTZONVIF', 'debug', 'execute');
		log::add('PTZONVIF', 'debug', $this->getEqlogic_id());
		log::add('PTZONVIF', 'debug', $this->getName());
		$eqLogic = $this->getEqLogic();
		$xaddrs=$eqLogic->getConfiguration('URL');
		$username=$eqLogic->getConfiguration('username');
		$password=$eqLogic->getConfiguration('password');
		$vitPTZ=$eqLogic->getConfiguration('vitPTZ');
		//
		if ($this->getConfiguration('action') == 'preset') {
			$preset = $this->getConfiguration('api') ;
            log::add('PTZONVIF', 'debug',$preset);
			$commande = "node /var/www/html/plugins/PTZONVIF/ressources/GoPreset.js ";
			$commande .= $xaddrs;  
			$commande .= " ";
			$commande .= $username;  
			$commande .= " ";
			$commande .= $password; 
			$commande .= " ";
			$commande .= $preset;			
			$commande .= " 2>&1";
			$goPreset = shell_exec($commande);
			log::add('PTZONVIF','debug','GoPreset.js : '.$commande);
			log::add('PTZONVIF','debug','GoPreset.js : '.$goPreset);
			return 1;
		}
		if ($this->getConfiguration('action') == 'setpreset') {
			$preset = $this->getConfiguration('api') ;
			$cmd2=$eqLogic->getCmd(null,'P0');
			switch ($preset){
				case 'S0':
					$cmd2=$eqLogic->getCmd(null,'P0');
					break;
				case 'S1':
					$cmd2=$eqLogic->getCmd(null,'P1');
					break;
				case 'S2':
					$cmd2=$eqLogic->getCmd(null,'P2');
					break;
				case 'S3':
					$cmd2=$eqLogic->getCmd(null,'P3');
					break;
				case 'S4':
					$cmd2=$eqLogic->getCmd(null,'P4');
					break;
				case 'S5':
					$cmd2=$eqLogic->getCmd(null,'P5');
					break;
			
			}
			$preset = $cmd2->getConfiguration('api') ;
			//$cmd2->setConfiguration('api',$preset);
			//$cmd2->save();
            log::add('PTZONVIF', 'debug',$preset);
			$commande = "node /var/www/html/plugins/PTZONVIF/ressources/SetPreset.js ";
			$commande .= $xaddrs;  
			$commande .= " ";
			$commande .= $username;  
			$commande .= " ";
			$commande .= $password; 
			$commande .= " ";
			$commande .= $preset;			
			$commande .= " 2>&1";
			$setPreset = shell_exec($commande);
			log::add('PTZONVIF','debug','setPreset.js : '.$commande);
			log::add('PTZONVIF','debug','setPreset.js : '.$setPreset);
			return 1;
		}
		if ($this->getConfiguration('action') == 'move') {
			$preset = $this->getConfiguration('api') ;
            log::add('PTZONVIF', 'debug',$preset);
			$commande = "node /var/www/html/plugins/PTZONVIF/ressources/Move.js ";
			$commande .= $xaddrs;  
			$commande .= " ";
			$commande .= $username;  
			$commande .= " ";
			$commande .= $password; 
			switch($this->getConfiguration('api'))
			{
				case 'up':
					$commande .= " 0 1 0 ".$vitPTZ;
					break;
				case 'down':
					$commande .= " 0 -1 0 ".$vitPTZ;
					break;
				case 'left':
					$commande .= " -1 0 0 ".$vitPTZ;
					break;
				case 'right':
					$commande .= " 1 0 0 ".$vitPTZ;
					break;
				case 'ZoomIn':
					$commande .= " 0 0 1 ".$vitPTZ;
					break;
				case 'ZoomOut':
					$commande .= " 0 0 -1 ".$vitPTZ;
					break;

			}		
			$commande .= " 2>&1";
			$goPreset = shell_exec($commande);
			log::add('PTZONVIF','debug','Move.js : '.$commande);
			log::add('PTZONVIF','debug','Move.js : '.$goPreset);
			return 1;
		}
		
    }

    /*     * **********************Getteur Setteur*************************** */


    /***********************************/
    /*             GETTEURS            */
    /***********************************/
 

    /***********************************/
    /*             SETTEURS            */
    /***********************************/

  
}

?>