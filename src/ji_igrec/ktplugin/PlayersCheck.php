<?php

namespace ji_igrec\ktplugin;

use pocketmine\Server;
use pocketmine\scheduler\PluginTask;
use pocketmine\utils\TextFormat;
use pocketmine\Player;

class PlayersCheck extends PluginTask{
	
	public function __construct(ktplugin $plugin){
		parent::__construct($plugin);
		$this->plugin = $plugin;
	}
	
	public function onRun($currentTick){
		
		$joueurs = count($this->plugin->getServer()->getOnlinePlayers());
		
		if ($joueurs === 1){
			
			$this->plugin->endKTP(1);
			
		}
		
	}
	
}