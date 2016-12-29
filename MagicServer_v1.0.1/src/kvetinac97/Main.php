<?php

namespace kvetinac97;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\item\Item;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class Main extends PluginBase implements Listener {

    public function onEnable() {
        $this->getLogger()->info("§dMagic§bServer §aENABLED!");
        $this->getLogger()->info("§eRunning version §91.0.0...");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onDisable() {
        $this->getLogger()->info("§dMagic§bServer §4DISABLED!");
    }

    public function onDamage(EntityDamageEvent $e){
        /** @var Player $p */
        $p = $e->getEntity();
        if (!$p instanceof Player){
            return;
        }

        if ($e instanceof EntityDamageByEntityEvent){
            /** @var Player $pl */
            $pl = $e->getDamager();
            if (!$pl instanceof Player){
                return;
            }
            $it = $pl->getInventory()->getItemInHand();
            if (!$it->hasEnchantments()){
                return;
            }
            $en = $it->getEnchantments();
            foreach ($en as $ench){
                $lvl = $ench->getLevel();
                switch ($ench->getId()){
                    case 9:
                        $e->setDamage($e->getDamage()+($lvl*1.25));
                        break;
                    case 12:
                        $e->setKnockback($e->getKnockBack()+($lvl*0.3));
                        break;
                    case 13:
                        if (!$e->isCancelled()){
                            $p->setOnFire($lvl*4);
                        }
                        break;
                    case 19:
                        $dmg = \round((($lvl+1)/4));
                        $e->setDamage($e->getDamage()+$dmg);
                        break;
                    case 20:
                        $e->setKnockBack($e->getKnockBack()+($lvl*0.4));
                        break;
                    case 21:
                        if (!$e->isCancelled()){
                            $p->setOnFire(5);
                        }
                        break;
                    case 22:
                        $pl->getInventory()->addItem(Item::ARROW, 0, 1);
                        break;
                }
            }
        }

        foreach ($p->getInventory()->getArmorContents() as $item){
            $eng = $item->getEnchantments();
            foreach ($eng as $enchantment){
                $lvl = $enchantment->getLevel();
                switch ($enchantment->getId()){
                    case 0:
                        $e->setDamage($e->getDamage() - (($lvl*0.04)*$e->getDamage()));
                        break;
                    case 1:
                        if ($e->getCause() > 4 && $e->getCause() < 8){
                            $e->setDamage($e->getDamage() - (($lvl*0.12)*$e->getDamage()));
                        }
                        break;
                    case 2:
                        if ($e->getCause() == 4){
                            $e->setDamage($e->getDamage() - (($lvl*0.15)*$e->getDamage()));
                        }
                        break;
                    case 3:
                        if ($e->getCause() > 8 && $e->getCause() < 11){
                            $e->setDamage($e->getDamage() - (($lvl*0.15)*$e->getDamage()));
                        }
                        break;
                    case 4:
                        if ($e->getCause() == 2){
                            $e->setDamage($e->getDamage() - (($lvl*0.12)*$e->getDamage()));
                        }
                        break;
                    case 7:
                        if ($e instanceof EntityDamageByEntityEvent){
                            /** @var Player $pl */
                            $pl = $e->getDamager();
                            Server::getInstance()->getPluginManager()->callEvent($ev = new EntityDamageEvent($pl, 14, $lvl*2));
                            if ($ev->isCancelled() || $ev->getDamage() <= 0){
                                break;
                            }
                            $pl->attack($lvl*2, $ev);
                        }
                        break;
                }
            }
        }
    }

    public function onBreak(BlockBreakEvent $e){
        $p = $e->getPlayer();
        if (!$p->getInventory()->getItemInHand()->hasEnchantments()){
            return;
        }
        $ench = $p->getInventory()->getItemInHand()->getEnchantments();
        foreach ($ench as $en){
            $lvl = $en->getLevel();
            switch ($en->getId()){
                case 16:
                    $item = [$e->getBlock()];
                    $e->setDrops($item);
                    break;
                case 17:
                    if (\mt_rand(1, (6-$lvl)) === 2){
                        $i = $p->getInventory()->getItemInHand();
                        $i->setDamage($i->getDamage()+1);
                    }
                    break;
                case 18:
                    switch ($e->getBlock()->getId()){
                        case 16:
                            $drop = \mt_rand(3, 3+$lvl);
                            $e->setDrops([Item::get(263, 0, $drop)]);
                            break;
                        case 21:
                            $drop = \mt_rand(5, 5+$lvl);
                            $e->setDrops([Item::get(351, 4, $drop)]);
                            break;
                        case 56:
                            $drop = \mt_rand(1, 1+$lvl);
                            $e->setDrops([Item::get(264, 0, $drop)]);
                            break;
                        case 73:
                            $drop = \mt_rand(5, 5+$lvl);
                            $e->setDrops([Item::get(331, 0, $drop)]);
                            break;
                        case 89:
                            $e->setDrops([Item::get(16, 0, 4)]);
                            break;
                        case 129:
                            $drop = \mt_rand(1, \round(1+($lvl/3)));
                            $e->setDrops([Item::get(129, 0, $drop)]);
                            break;
                        case 153:
                            $drop = \mt_rand(2, 2+$lvl);
                            $e->setDrops([Item::get(406, 0, $drop)]);
                            break;
                    }
                    break;
            }
        }
    }

}
