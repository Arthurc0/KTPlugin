<?php 

namespace ji_igrec\ktplugin;

use pocketmine\Server;
use pocketmine\scheduler\PluginTask;
use pocketmine\utils\TextFormat;
use pocketmine\Player;


class KTP extends PluginTask{



	public $ep;

	public function __construct(ktplugin $plugin){
		parent::__construct($plugin);
		$this->plugin = $plugin;
	}
	

	
	public function onRun($currentTick){
			
		global $ep;
		$ep = $ep + 1;
		Server::getInstance()->broadcastMessage("[KTP] Fin de l'épisode " . $ep . " !");
		
	}
}