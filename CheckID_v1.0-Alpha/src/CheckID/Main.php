<?php

namespace CheckID;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\block\IronOre;
use pocketmine\block\GoldOre;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\level\sound\BlazeShootSound;

class Main extends PluginBase implements Listener {

  public function onEnable() {
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
  }
  
  public function onCommand(CommandSender $sender, Command $command, $label, array $args){
		if(strtolower($command->getName()) == "id"){
    	$player = $sender->getPlayer();
    	$id = $player->getInventory()->getItemInHand()->getId();
    	$player->sendMessage("§a§lDie ID dieses Items ist: $id");
    }
  }
}