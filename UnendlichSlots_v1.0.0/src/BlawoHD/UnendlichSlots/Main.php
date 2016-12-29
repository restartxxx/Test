<?php
namespace BlawoHD\UnendlichSlots;
	
use pocketmine\plugin\PluginBase as Plugin;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as TF;
use pocketmine\event\player\PlayerKickEvent;
			
	class Main extends Plugin implements Listener {
		public function onEnable() {
			$this->getServer()->getPluginManager()->registerEvents($this, $this);
			$this->getServer()->getLogger()->info(TF::GREEN."wurde Aktiviert!");
		}
		
		public function onPlayerKick(PlayerKickEvent $event) {
			if($event->getReason() === "disconnectionScreen.serverFull")
				$event->setCancelled(true);
		}
		
		public function onDisable() {
			$this->getServer()->getLogger()->info(TF::RED."wurde Deaktiviert!");
		}
}
