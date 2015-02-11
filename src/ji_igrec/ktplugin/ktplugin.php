<?php

namespace ji_igrec\ktplugin;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\entity\human;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class ktplugin extends PluginBase implements Listener{

	public function onLoad(){
		$this->getLogger()->info(TextFormat::GREEN . "Plugin KTP chargé.");
	}

	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		#$this->getServer()->getScheduler()->scheduleRepeatingTask(new BroadcastPluginTask($this), 120);
		$this->getLogger()->info(TextFormat::GREEN . "Plugin KTP activé.");
    }

	public function onDisable(){
		$this->getLogger()->info(TextFormat::GREEN . "Plugin KTP déchargé.");
	}

	public function onCommand(CommandSender $sender, Command $command, $label, array $args){
		switch($command->getName()){
			case "ktp":
				$sender->sendMessage("Bonjour ".$sender->getName()."!");
				$this->getLogger()->info("§aLe joueur §f".$sender->getName()."§a a tapé la commande \"ktp\".");
				#Server::getInstance()->broadcastMessage("[KTP] Le joueur ".$sender->getName()." a tapé la commande \"ktp\".");
				return true;
			default:
				return false;
		}
	}

	/**
	 * @param PlayerRespawnEvent $event
	 *
	 * @priority        NORMAL
	 * @ignoreCancelled false
	 */
	public function onSpawn(PlayerRespawnEvent $event){
		Server::getInstance()->broadcastMessage("[KTP] " . $event->getPlayer()->getDisplayName() . " a rejoint la partie !");
	}
	
	public function onRegainHealth(EntityRegainHealthEvent $event3) {
	
		$entity = $event3->getEntity();
		
		if($entity instanceof Player){
			
			if ($event3->getRegainReason() === 0){
				
				$todeduct = $event3->getAmount();
				$health = $entity->getHealth();
				$newHealth = $health - $todeduct;
				$entity->setHealth($newHealth);
				$finalHealth = $entity->getHealth();
				
			}
			if ($event3->getRegainReason() === 1) {
				
				$healthRegen = $event3->getAmount();
				$health2 = $event3->getEntity()->getHealth();
				Server::getInstance()->broadcastMessage("[KTP] " . $entity->getDisplayName()." a gagné ".$finalHealth . " coeurs et a maintenant " . $health2 . " coeurs !");
				
			}
		}
	}
	
	public function onDeath(PlayerDeathEvent $event2) {
		Server::getInstance()->broadcastMessage("[KTP] " . $event2->getPlayer()->getDisplayName() . " est mort !");
		$event2->getPlayer()->setBanned(true);
		$event2->getPlayer()->kick("Vous êtes mort !");
		
	}
}
