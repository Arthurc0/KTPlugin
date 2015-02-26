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
use pocketmine\scheduler\PluginTask;


class ktplugin extends PluginBase implements Listener{
	

	
	
	public function onLoad(){
		global $KTP;
		$this->getLogger()->info(TextFormat::GREEN . "Plugin KTP chargé.");
		$KTP = 0;
		$ep = 0;
	}

	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->info(TextFormat::GREEN . "Plugin KTP activé.");
    }

	public function onDisable(){
		$this->getLogger()->info(TextFormat::GREEN . "Plugin KTP déchargé.");
	}

	public function onCommand(CommandSender $sender, Command $command, $label, array $args){
		global $KTP;
		switch($command->getName()){
			case "ktp":
				$sender->sendMessage("Bonjour ".$sender->getName()."!");
				$this->getLogger()->info("§aLe joueur §f".$sender->getName()."§a a tapé la commande \"ktp\".");
				#Server::getInstance()->broadcastMessage("[KTP] Le joueur ".$sender->getName()." a tapé la commande \"ktp\".");
				return true;
			
			case "start":
			
				if ($KTP === 0) {
					
					$epL = 20;
					$time = 20 * 60 * $epL;
					$time2 = intval($time, 1);
					$this->getServer()->getScheduler()->scheduleDelayedRepeatingTask(new KTP($this), $time2, $time2);
					$this->getServer()->getScheduler()->scheduleDelayedRepeatingTask(new PlayersCheck($this), 30 * 20, 30 * 20);
					Server::getInstance()->broadcastMessage("[KTP] Le KTP vient de commencer !");
					#foreach ($player) {
					#	$player->sendMessage("Bonne chance, ".$player->getName()." !");

					#}
					Server::getInstance()->broadcastMessage("[KTP] Bonne chance !");
				}
				return true;
				
			case "end":
				
				$joueurs = count($server->getOnlinePlayers());
				endKTP($joueurs);
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
		
		$event2P = $event2->getEntity();
		
		if ($event2P instanceof Player){
			
			Server::getInstance()->broadcastMessage("[KTP] " . $event2->getEntity()->getDisplayName() . " est mort !");
			$event2->getEntity()->setBanned(true);
			$event2->getEntity()->kick("Vous êtes mort ! Merci d'avoir joué !");
			
		}
	}
	
	public function Nepisode() {
		
		global $ep;
			Server::getInstance()->broadcastMessage("[KTP] Fin de l'épisode " . $ep . " !");
		$ep = $ep + 1;		
	

	}
	
	public function endKTP($count) {
		
		switch ($count){
			
		case 1:

			$this->getServer()->getScheduler()->CancelTasks($this);
			$dernierJoueur = $this->getServer()->getOnlinePlayers();
			$DJ = getplayer($dernierJoueur);
			if ($DJ instanceof Player){
				$DJnom = $DJ->getDisplayName();
				Server::getInstance()->broadcastMessage("[KTP] " . $DJnom . "a gagné!");
			}
			return true;
			
		default:
			
			$this->getServer()->getScheduler()->CancelTasks($this);
			Server::getInstance()->broadcastMessage("[KTP] Le KTP a été annulé.");
			return true;
			

		}
		

	}
	
}
