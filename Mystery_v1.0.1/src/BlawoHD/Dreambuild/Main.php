<?php
namespace BlawoHD\Dreambuild;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\Plugin;

use pocketmine\Server;
use pocketmine\Player;

use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerInteractEvent;

use pocketmine\utils\Config;

use pocketmine\item\Item;
use pocketmine\item\Emerald;

use pocketmine\block\Block;
use pocketmine\level\sound\TNTPrimeSound;
use pocketmine\level\sound\PopSound;
use pocketmine\level\sound\EndermanTeleportSound;
use pocketmine\utils\TextFormat as C;
use pocketmine\utils\TextFormat as TF;

use pocketmine\math\Vector3;
use pocketmine\level\Level;
use pocketmine\level\particle\LavaParticle;


class Main extends PluginBase implements Listener{

    public $prefix = "§7[§cDream§6build§7] ";

    public function onLoad() {
        $this->getLogger()->info(TF::YELLOW."=======================");
        $this->getLogger()->info(TF::YELLOW."Mystery wird geladen!");
        $this->getLogger()->info(TF::YELLOW."Mystery Edit von BlawoHD!");
        $this->getLogger()->info(TF::YELLOW."=======================");
    }
    
    public function onEnable(){
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
    
    $this->getLogger()->info(TF::GREEN."=======================");
    $this->getLogger()->info(TF::GREEN."Mystery wird aktiviert!");
    $this->getLogger()->info(TF::GREEN."Mystery Edit von BlawoHD!");
    $this->getLogger()->info(TF::GREEN."=======================");
  }
  public function onDisable() {
    $this->getLogger()->info(TF::RED."=======================");
    $this->getLogger()->info(TF::RED."Mystery wird deaktiviert!");
    $this->getLogger()->info(TF::RED."Mystery Edit von BlawoHD!");
    $this->getLogger()->info(TF::RED."=======================");
    }

  public function onDeath(PlayerDeathEvent $event){
    $entity = $event->getEntity();
    $cause = $entity->getLastDamageCause();
    if($entity instanceof Player){
       if($cause instanceof Player){
        $killer->getInventory()->addItem(Item::get(388,0,1));
    }
  }
}
  public function onInteract(PlayerInteractEvent $event){
    $block = $event->getBlock();
    $player = $event->getPlayer();
    $inventory = $player->getInventory();       
      if($block->getId() === Block::END_PORTAL_FRAME){     
        if($inventory->contains(new Emerald(0,1))) {
        $event->setCancelled();
        $player->sendPopup($this->prefix.C::GREEN . "§7Es wird geöffnet");
        $player->sendPopup($this->prefix.C::RED . "§910");
										$level=$player->getLevel();
										$level->addSound(new PopSound($player));
										$level=$player->getLevel();
        $player->sendPopup($this->prefix.C::RED."§99");
        $player->sendPopup($this->prefix.C::RED."§98");
        $player->sendPopup($this->prefix.C::RED."§97");
        $player->sendPopup($this->prefix.C::RED."§96");
        $player->sendPopup($this->prefix.C::RED."§95");
										$level=$player->getLevel();
										$level->addSound(new EndermanTeleportSound($player));
										$level=$player->getLevel();
        $player-sendPopup($this->prefix.C::GREEN . C::BOLD . "§94");
        $player->sendPopup($this->prefix.C::RED . "§93");
										$level=$player->getLevel();
										$level->addSound(new EndermanTeleportSound($player));
										$level=$player->getLevel();
        $player->sendPopup($this->prefix.C::GREEN . C::BOLD . "§92");
        $player->sendPopup($this->prefix.C::RED . "§91");
										$level=$player->getLevel();
										$level->addSound(new EndermanTeleportSound($player));
										$level=$player->getLevel();
        $player->sendPopup($this->prefix . C::BOLD . "§aItem erhalten ! Sehe in deinem Inventar nach!");
        $player->sendMessage($this->prefix . C::BOLD . "§aItem erhalten ! Sehe in deinem Inventar nach!");
        $player->getInventory()->removeItem(Item::get(ITEM::EMERALD));
        $level=$player->getLevel();
										
        $x = $block->getX();
        $y = $block->getY();
        $z = $block->getZ();
        $center = new Vector3($x, $y, $z);
        $radius = 200000;
        $count = 100000;
          for($yaw = 1000, $y = $center->y; $y < $center->y + 10; $yaw += (M_PI * 2) / 50, $y += 1 / 50){
              $x = cos($yaw) + $center->x;
              $z = cos($yaw) + $center->z;
              $particle->setComponents($x, $y, $z);
              $level->addParticle($particle);
}
        $prize = rand(1,6);
        switch($prize){
        case 1:
          $inventory->addItem(Item::get(293,0,1));
        break;
        case 2:
          $inventory->addItem(Item::get(279,0,1));
        break;   
        case 3:
          $inventory->addItem(Item::get(311,0,1));
        break;   
        case 4:
          $inventory->addItem(Item::get(312,0,1));
        break;      
        case 5:
          $inventory->addItem(Item::get(278,0,1));
        break;     
        case 6:
          $inventory->addItem(Item::get(293,0,1));  
        break;
    }
  }
}
}
}
