<?php

namespace BlawoHD;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;
use pocketmine\entity\Effect;
use pocketmine\item\item;

//Events
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerDeathEvent;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;

class KitPvP extends PluginBase implements Listener {

    public $prefix = "§7[§cKitPvP§7] §f";

//=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=
    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getLogger()->info($this->prefix . "§aKitPvP aktiviert!");
		
		@mkdir($this->getDataFolder());
		@mkdir($this->getDataFolder()."Players");
    }

    public function onDisable() {
        $this->getServer()->getLogger()->info($this->prefix . "§cKitPvP deaktiviert!");
    }

//=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=

	public function onDrop(PlayerDropItemEvent $event) {
        $event->setCancelled(true);
    }
	
	public function onDeath(PlayerDeathEvent $event){
		$entity = $event->getEntity();
		$cause = $entity->getLastDamageCause();
		
		$event->setDeathMessage("");
		
		if ($cause instanceof EntityDamageByEntityEvent) {
            $killer = $cause->getDamager();
			if($killer instanceof Player){
				$name = $killer->getName();
				$TargetFile = new Config($this->getDataFolder()."Players/".strtolower($name{0})."/".strtolower($name).".yml", Config::YAML);
							
				$targetcoins = $TargetFile->get("Coins");
				$newCoins = $targetcoins + 10;
				$addedcoins = 10;
				if($killer->hasPermission("kitpvp.doublecoins") || $killer->isOP()){
					$newCoins = $newCoins + 5;
					$addedcoins = 10;
				}
				
				$TargetFile->set("Coins", $newCoins);
				$TargetFile->save();
				
				$killer->sendMessage($this->prefix."§aDu hast den Spieler §b".$entity->getName()." §agetötet. §f-> §6+".$addedcoins." Coins");
			}
		}
	}
	
	public function onJoin(PlayerJoinEvent $event) {
		$player = $event->getPlayer();
		$name = $player->getName();
		
		@mkdir($this->getDataFolder()."Players/".strtolower($name{0}));
		
		$PlayerFile = new Config($this->getDataFolder()."Players/".strtolower($name{0})."/".strtolower($name).".yml", Config::YAML);
		
		if(empty($PlayerFile->get("Coins"))){
			$PlayerFile->set("Coins", 0);
		}
		if(empty($PlayerFile->get("Kits"))){
			$PlayerFile->set("Kits", array("Wikinger"));
		}
		
		$PlayerFile->save();
    }
	
//=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=
	
    public function onCommand(CommandSender $sender, Command $cmd, $lable, array $args) {
		
		$name = $sender->getName();
		$PlayerFile = new Config($this->getDataFolder()."Players/".strtolower($name{0})."/".strtolower($name).".yml", Config::YAML);
		
		$kits = $PlayerFile->get("Kits");
		$coins = $PlayerFile->get("Coins");
		
        switch ($cmd->getName()) {
            case "kits":
				
				$sender->sendMessage("§7=_=_=_=_=_=_=_=_=_");
				$sender->sendMessage(" §7- §8Wikinger §7[§aGekauft§7]");
				
				if(in_array("Ninja", $kits)){
					$sender->sendMessage(" §7- §bNinja §7[§aGekauft§7]");
				} else {
					$sender->sendMessage(" §7- §bNinja §7[§c150 coins§7]");
				}
				if(in_array("Mario", $kits)){
					$sender->sendMessage(" §7- §cMario §7[§aGekauft§7]");
				} else {
					$sender->sendMessage(" §7- §cMario §7[§c200 coins§7]");
				}
				if(in_array("Archer", $kits)){
					$sender->sendMessage(" §7- §aArcher §7[§aGekauft§7]");
				} else {
					$sender->sendMessage(" §7- §aArcher §7[§c360 coins§7]");
				}
				if(in_array("Krieger", $kits)){
					$sender->sendMessage(" §7- §4Krieger §7[§aGekauft§7]");
				} else {
					$sender->sendMessage(" §7- §4Krieger §7[§c500 coins§7]");
				}
				if(in_array("Turbo", $kits)){
					$sender->sendMessage(" §7- §fTurbo §7[§aGekauft§7]");
				} else {
					$sender->sendMessage(" §7- §fTurbo §7[§c750 coins§7]");
				}
				if(in_array("King", $kits)){
					$sender->sendMessage(" §7- §6King §7[§aGekauft§7]");
				} else {
					$sender->sendMessage(" §7- §6King §7[§c800 coins§7]");
				}
				if(in_array("Ritter", $kits)){
					$sender->sendMessage(" §7- §7Ritter §7[§aGekauft§7]");
				} else {
					$sender->sendMessage(" §7- §7Ritter §7[§c1000 coins§7]");
				}
				$sender->sendMessage("                     ");
				$sender->sendMessage("§9Kit wählen§7:   ");
				$sender->sendMessage("§c/kit <KitName>    ");
				$sender->sendMessage("§7=_=_=_=_=_=_=_=_=_");
				
                break;
            case "coins":
				$sender->sendMessage($this->prefix."Du hast §6".$coins." §fCoins!");
				break;
            case "setcoins":
				if($sender->isOP()){
					if(!empty($args[0]) && !empty($args[1])){
						
						$targetname = $args[0];
						if(file_exists($this->getDataFolder()."Players/".strtolower($targetname{0})."/".strtolower($targetname).".yml")){
							$TargetFile = new Config($this->getDataFolder()."Players/".strtolower($targetname{0})."/".strtolower($targetname).".yml", Config::YAML);
							
							$TargetFile->set("Coins", (int) $args[1]);
							$TargetFile->save();
							
							$sender->sendMessage($this->prefix."Du hast die Coins von §6".$targetname." §fauf §6".$args[1]." §fgesetzt!");
						} else {
							$sender->sendMessage("Spieler existiert nicht!");
						}
						
					} else {
						$sender->sendMessage("/setcoins <player> <amount>");
					}
				}
				break;
            case "addcoins":
				if($sender->isOP()){
					if(!empty($args[0]) && !empty($args[1])){
						
						$targetname = $args[0];
						if(file_exists($this->getDataFolder()."Players/".strtolower($targetname{0})."/".strtolower($targetname).".yml")){
							$TargetFile = new Config($this->getDataFolder()."Players/".strtolower($targetname{0})."/".strtolower($targetname).".yml", Config::YAML);
							
							$targetcoins = $TargetFile->get("Coins");
							$newCoins = $targetcoins + (int) $args[1];
							
							$TargetFile->set("Coins", (int) $newCoins);
							$TargetFile->save();
							
							$sender->sendMessage($this->prefix."Du hast die Coins von §6".$targetname." §fum §6".$args[1]." §ferhöht!");
						} else {
							$sender->sendMessage("Spieler existiert nicht!");
						}
						
					} else {
						$sender->sendMessage("/addcoins <player> <amount>");
					}
				}
				break;
            case "kit":
				if(!empty($args[0])){
					if (strtolower($args[0]) != "wikinger" &&
							strtolower($args[0]) != "ninja" &&
							strtolower($args[0]) != "mario" &&
							strtolower($args[0]) != "archer" &&
							strtolower($args[0]) != "krieger" &&
							strtolower($args[0]) != "turbo" &&
							strtolower($args[0]) != "king" &&
							strtolower($args[0]) != "ritter") {

						$sender->sendMessage($this->prefix . "§cDas Kit §e$args[0] §cgibt es nicht oder es liegt ein Schreibfehler vor.");
						$sender->sendMessage("§6-> §f/kits");
					} else {
						###WIKINGER###
						if (strtolower($args[0] == "wikinger")) {
							if($sender instanceof Player){
								$sender->removeAllEffects();
								$sender->getInventory()->clearAll();
								$sender->sendMessage($this->prefix . "§fKit §o§l§8Wikinger §r§ferhalten");
								$sender->getInventory()->setHelmet(Item::get(306, 0, 1));
								$sender->getInventory()->setChestplate(Item::get(299, 0, 1));
								$sender->getInventory()->setLeggings(Item::get(300, 0, 1));
								$sender->getInventory()->setBoots(Item::get(301, 0, 1));
								$sender->getInventory()->addItem(Item::get(322, 0, 2));
								$sender->getInventory()->addItem(Item::get(350, 0, 20));
								$sender->getInventory()->addItem(Item::get(258, 0, 1));
								$sender->addEffect(Effect::getEffect(5)->setAmplifier(0)->setDuration(199980)->setVisible(false));
							} else {
								$sender->sendMessage($this->prefix . "§fKit nur Ingame verfügbar :D");
							}
						}
						###NINJA###
						elseif (strtolower($args[0]) == "ninja") {
							
							if(!in_array("Ninja", $kits)){
								
								if($coins >= 150){
									
									$newCoins = $coins - 150;
									
									$kits[] = "Ninja";
									$PlayerFile->set("Kits", $kits);
									$PlayerFile->set("Coins", $newCoins);
									
									$PlayerFile->save();
									
									$sender->sendMessage($this->prefix."§aDu hast Erfolgreich das Kit §bNinja §afür§6 150 coins §agekauft, du kannst es nun jederzeit mit dem Command §f/kit Ninja §abenutzen!");
									
								} else {
									$sender->sendMessage($this->prefix."§cDu hast nicht genügend Coins um das Kit §bNinja §czu kaufen");
									
									$missingcoins = 150 - $coins;
									
									$sender->sendMessage($this->prefix."Aktuelle Coins§7: §6".$coins);
									$sender->sendMessage($this->prefix."Fehlende Coins§7: §6".$missingcoins);
									$sender->sendMessage($this->prefix."Benötigte Coins§7:§6 150");
								}
								
							} else { //gekauft
								if($sender instanceof Player){
									$sender->removeAllEffects();
									$sender->getInventory()->clearAll();
									$sender->sendMessage($this->prefix . "§fKit §o§l§bNinja §r§ferhalten");
									$sender->getInventory()->addItem(Item::get(276, 1010, 1));
									$sender->getInventory()->addItem(Item::get(322, 0, 2));
									$sender->getInventory()->addItem(Item::get(260, 0, 20));
									$sender->getInventory()->setHelmet(Item::get(298, 0, 1));
									$sender->getInventory()->setChestplate(Item::get(299, 0, 1));
									$sender->getInventory()->setLeggings(Item::get(300, 0, 1));
									$sender->getInventory()->setBoots(Item::get(301, 0, 1));
									$sender->addEffect(Effect::getEffect(1)->setAmplifier(1)->setDuration(199980)->setVisible(false));
								} else {
									$sender->sendMessage($this->prefix . "§fKit nur Ingame verfügbar :D");
								}
							}
						}
						###MARIO###
						elseif (strtolower($args[0]) == "mario") {
							
							if(!in_array("Mario", $kits)){
								
								if($coins >= 200){
									
									$newCoins = $coins - 200;
									
									$kits[] = "Mario";
									$PlayerFile->set("Kits", $kits);
									$PlayerFile->set("Coins", $newCoins);
									
									$PlayerFile->save();
									
									$sender->sendMessage($this->prefix."§aDu hast Erfolgreich das Kit §cMario §afür§6 200 coins §agekauft, du kannst es nun jederzeit mit dem Command §f/kit Mario §abenutzen!");
									
								} else {
									$sender->sendMessage($this->prefix."§cDu hast nicht genügend Coins um das Kit §cMario §czu kaufen");
									
									$missingcoins = 200 - $coins;
									
									$sender->sendMessage($this->prefix."Aktuelle Coins§7: §6".$coins);
									$sender->sendMessage($this->prefix."Fehlende Coins§7: §6".$missingcoins);
									$sender->sendMessage($this->prefix."Benötigte Coins§7:§6 200");
								}
								
							} else { //gekauft
								if($sender instanceof Player){
									$sender->removeAllEffects();
									$sender->getInventory()->clearAll();
									$sender->sendMessage($this->prefix . "§fKit §o§l§cMario §r§ferhalten");
									$sender->getInventory()->addItem(Item::get(268, 10, 1));
									$sender->getInventory()->addItem(Item::get(282, 0, 4));
									$sender->getInventory()->addItem(Item::get(40, 0, 15));
									$sender->getInventory()->addItem(Item::get(39, 0, 15));
									$sender->getInventory()->addItem(Item::get(322, 0, 2));
									$sender->getInventory()->setHelmet(Item::get(298, 0, 1));
									$sender->getInventory()->setChestplate(Item::get(299, 0, 1));
									$sender->getInventory()->setLeggings(Item::get(300, 0, 1));
									$sender->getInventory()->setBoots(Item::get(301, 0, 1));
									$sender->addEffect(Effect::getEffect(8)->setAmplifier(3)->setDuration(199980)->setVisible(false));
								} else {
									$sender->sendMessage($this->prefix . "§fKit nur Ingame verfügbar :D");
								}
							}
						}
						###ARCHER###
						elseif (strtolower($args[0]) == "archer") {
							
							if(!in_array("Archer", $kits)){
								
								if($coins >= 360){
									
									$newCoins = $coins - 360;
									
									$kits[] = "Archer";
									$PlayerFile->set("Kits", $kits);
									$PlayerFile->set("Coins", $newCoins);
									
									$PlayerFile->save();
									
									$sender->sendMessage($this->prefix."§aDu hast Erfolgreich das Kit §aArcher §afür§6 360 coins §agekauft, du kannst es nun jederzeit mit dem Command §f/kit Archer §abenutzen!");
									
								} else {
									$sender->sendMessage($this->prefix."§cDu hast nicht genügend Coins um das Kit §aArcher §czu kaufen");
									
									$missingcoins = 360 - $coins;
									
									$sender->sendMessage($this->prefix."Aktuelle Coins§7: §6".$coins);
									$sender->sendMessage($this->prefix."Fehlende Coins§7: §6".$missingcoins);
									$sender->sendMessage($this->prefix."Benötigte Coins§7:§6 360");
								}
								
							} else { //gekauft
								if($sender instanceof Player){
									$sender->removeAllEffects();
									$sender->getInventory()->clearAll();
									$sender->sendMessage($this->prefix . "§fKit §o§l§aArcher §r§ferhalten");
									$sender->getInventory()->addItem(Item::get(267, 195, 1));
									$sender->getInventory()->addItem(Item::get(261, 0, 1));
									$sender->getInventory()->addItem(Item::get(262, 0, 40));
									$sender->getInventory()->addItem(Item::get(260, 0, 20));
									$sender->getInventory()->addItem(Item::get(322, 0, 2));
									$sender->getInventory()->setChestplate(Item::get(299, 0, 1));
									$sender->getInventory()->setLeggings(Item::get(300, 0, 1));
									$sender->addEffect(Effect::getEffect(11)->setAmplifier(0)->setDuration(199980)->setVisible(false));
								} else {
									$sender->sendMessage($this->prefix . "§fKit nur Ingame verfügbar :D");
								}
							}
						}
						###KRIEGER###
						elseif (strtolower($args[0]) == "krieger") {
							
							if(!in_array("Krieger", $kits)){
								
								if($coins >= 500){
									
									$newCoins = $coins - 500;
									
									$kits[] = "Krieger";
									$PlayerFile->set("Kits", $kits);
									$PlayerFile->set("Coins", $newCoins);
									
									$PlayerFile->save();
									
									$sender->sendMessage($this->prefix."§aDu hast Erfolgreich das Kit §4Krieger §afür§6 500 coins §agekauft, du kannst es nun jederzeit mit dem Command §f/kit Krieger §abenutzen!");
									
								} else {
									$sender->sendMessage($this->prefix."§cDu hast nicht genügend Coins um das Kit §4Krieger §czu kaufen");
									
									$missingcoins = 500 - $coins;
									
									$sender->sendMessage($this->prefix."Aktuelle Coins§7: §6".$coins);
									$sender->sendMessage($this->prefix."Fehlende Coins§7: §6".$missingcoins);
									$sender->sendMessage($this->prefix."Benötigte Coins§7:§6 500");
								}
								
							} else { //gekauft
								if($sender instanceof Player){
									$sender->removeAllEffects();
									$sender->getInventory()->clearAll();
									$sender->sendMessage($this->prefix . "§fKit §o§l§4Krieger §r§ferhalten");
									$sender->getInventory()->addItem(Item::get(272, 0, 1));
									$sender->getInventory()->addItem(Item::get(322, 0, 5));
									$sender->getInventory()->addItem(Item::get(393, 0, 20));
									$sender->getInventory()->setHelmet(Item::get(302, 0, 1));
									$sender->getInventory()->setChestplate(Item::get(307, 0, 1));
									$sender->getInventory()->setLeggings(Item::get(304, 0, 1));
									$sender->getInventory()->setBoots(Item::get(305, 0, 1));
									$sender->addEffect(Effect::getEffect(5)->setAmplifier(0)->setDuration(199980)->setVisible(false));
									$sender->addEffect(Effect::getEffect(11)->setAmplifier(0)->setDuration(199980)->setVisible(false));
								} else {
									$sender->sendMessage($this->prefix . "§fKit nur Ingame verfügbar :D");
								}
							}
						}
						###TURBO###
						elseif (strtolower($args[0]) == "turbo") {
							
							if(!in_array("Turbo", $kits)){
								
								if($coins >= 750){
									
									$newCoins = $coins - 750;
									
									$kits[] = "Turbo";
									$PlayerFile->set("Kits", $kits);
									$PlayerFile->set("Coins", $newCoins);
									
									$PlayerFile->save();
									
									$sender->sendMessage($this->prefix."§aDu hast Erfolgreich das Kit §fTurbo §afür§6 750 coins §agekauft, du kannst es nun jederzeit mit dem Command §f/kit Turbo §abenutzen!");
									
								} else {
									$sender->sendMessage($this->prefix."§cDu hast nicht genügend Coins um das Kit §fTurbo §czu kaufen");
									
									$missingcoins = 750 - $coins;
									
									$sender->sendMessage($this->prefix."Aktuelle Coins§7: §6".$coins);
									$sender->sendMessage($this->prefix."Fehlende Coins§7: §6".$missingcoins);
									$sender->sendMessage($this->prefix."Benötigte Coins§7:§6 750");
								}
								
							} else { //gekauft
								if($sender instanceof Player){
									$sender->removeAllEffects();
									$sender->getInventory()->clearAll();
									$sender->sendMessage($this->prefix . "§fKit §o§l§4Krieger §r§ferhalten");
									$sender->getInventory()->addItem(Item::get(272, 0, 1));
									$sender->getInventory()->addItem(Item::get(322, 0, 5));
									$sender->getInventory()->addItem(Item::get(393, 0, 20));
									$sender->getInventory()->setHelmet(Item::get(302, 0, 1));
									$sender->getInventory()->setChestplate(Item::get(307, 0, 1));
									$sender->getInventory()->setLeggings(Item::get(304, 0, 1));
									$sender->getInventory()->setBoots(Item::get(305, 0, 1));
									$sender->addEffect(Effect::getEffect(5)->setAmplifier(0)->setDuration(199980)->setVisible(false));
									$sender->addEffect(Effect::getEffect(11)->setAmplifier(0)->setDuration(199980)->setVisible(false));
								} else {
									$sender->sendMessage($this->prefix . "§fKit nur Ingame verfügbar :D");
								}
							}
						}
						###KING###
						elseif (strtolower($args[0]) == "king") {
							
							if(!in_array("King", $kits)){
								
								if($coins >= 800){
									
									$newCoins = $coins - 800;
									
									$kits[] = "King";
									$PlayerFile->set("Kits", $kits);
									$PlayerFile->set("Coins", $newCoins);
									
									$PlayerFile->save();
									
									$sender->sendMessage($this->prefix."§aDu hast Erfolgreich das Kit §6King §afür§6 800 coins §agekauft, du kannst es nun jederzeit mit dem Command §f/kit King §abenutzen!");
									
								} else {
									$sender->sendMessage($this->prefix."§cDu hast nicht genügend Coins um das Kit §6King §czu kaufen");
									
									$missingcoins = 800 - $coins;
									
									$sender->sendMessage($this->prefix."Aktuelle Coins§7: §6".$coins);
									$sender->sendMessage($this->prefix."Fehlende Coins§7: §6".$missingcoins);
									$sender->sendMessage($this->prefix."Benötigte Coins§7:§6 800");
								}
								
							} else { //gekauft
								if($sender instanceof Player){
									$sender->removeAllEffects();
									$sender->getInventory()->clearAll();
									$sender->sendMessage($this->prefix . "§fKit §o§l§6König §r§ferhalten");
									$sender->getInventory()->setHelmet(Item::get(314, 0, 1));
									$sender->getInventory()->setChestplate(Item::get(315, 0, 1));
									$sender->getInventory()->setLeggings(Item::get(316, 0, 1));
									$sender->getInventory()->setBoots(Item::get(317, 0, 1));
									$sender->getInventory()->addItem(Item::get(267, 0, 1));
									$sender->getInventory()->addItem(Item::get(322, 0, 64));
									$sender->addEffect(Effect::getEffect(11)->setAmplifier(0)->setDuration(199980)->setVisible(false));
								} else {
									$sender->sendMessage($this->prefix . "§fKit nur Ingame verfügbar :D");
								}
							}
						}
						###RITTER###
						elseif (strtolower($args[0]) == "ritter") {
							
							if(!in_array("Ritter", $kits)){
								
								if($coins >= 1000){
									
									$newCoins = $coins - 1000;
									
									$kits[] = "Ritter";
									$PlayerFile->set("Kits", $kits);
									$PlayerFile->set("Coins", $newCoins);
									
									$PlayerFile->save();
									
									$sender->sendMessage($this->prefix."§aDu hast Erfolgreich das Kit §7Ritter §afür§6 1000 coins §agekauft, du kannst es nun jederzeit mit dem Command §f/kit Ritter §abenutzen!");
									
								} else {
									$sender->sendMessage($this->prefix."§cDu hast nicht genügend Coins um das Kit §7Ritter §czu kaufen");
									
									$missingcoins = 1000 - $coins;
									
									$sender->sendMessage($this->prefix."Aktuelle Coins§7: §6".$coins);
									$sender->sendMessage($this->prefix."Fehlende Coins§7: §6".$missingcoins);
									$sender->sendMessage($this->prefix."Benötigte Coins§7:§6 1000");
								}
								
							} else { //gekauft
								if($sender instanceof Player){
									$sender->removeAllEffects();
									$sender->getInventory()->clearAll();
									$sender->sendMessage($this->prefix . "§fKit §o§l§7Ritter §r§ferhalten");
									$sender->getInventory()->setHelmet(Item::get(306, 0, 1));
									$sender->getInventory()->setChestplate(Item::get(303, 0, 1));
									$sender->getInventory()->setLeggings(Item::get(308, 0, 1));
									$sender->getInventory()->setBoots(Item::get(309, 0, 1));
									$sender->getInventory()->addItem(Item::get(267, 0, 1));
									$sender->getInventory()->addItem(Item::get(322, 0, 5));
									$sender->getInventory()->addItem(Item::get(297, 0, 20));
									$sender->addEffect(Effect::getEffect(2)->setAmplifier(1)->setDuration(199980)->setVisible(false));
									$sender->addEffect(Effect::getEffect(11)->setAmplifier(1)->setDuration(199980)->setVisible(false));
								} else {
									$sender->sendMessage($this->prefix . "§fKit nur Ingame verfügbar :D");
								}
							}
						}
					}
                } else {
					$sender->sendMessage("§6-> §f/kit <kitname>");
					$sender->sendMessage("§6-> §aeine Liste mit allen kits siehst du mit §f/kits");
				}
                break;
            case "spawn":
                $sender->getInventory()->clearAll();
                $sender->removeAllEffects();
                $sender->sendMessage($this->prefix . "§aDu bist nun am Spawn.");
                $sender->setHealth(0);
                break;
            case "mode":
                if (!$sender->isOP()) {
                    $sender->sendMessage($this->prefix . "§4Unzureichende Rechte!");
                }
                if (!$sender instanceof Player) {
                    $sender->sendMessage($this->prefix . "§4Only Ingame!");
                }

                if (strtolower($args[0]) == "c" && $sender->isOP()) {
                    $sender->sendMessage($this->prefix . "§aDein Gamemode wurde zu §cCREATIV §agewechselt!");
                    $sender->setGamemode(1);
                }
                if (strtolower($args[0]) == "s" && $sender->isOP()) {
                    $sender->sendMessage($this->prefix . "§aDein Gamemode wurde zu §cSURVIVAL §agewechselt!");
                    $sender->setGamemode(0);
                }
                if (strtolower($args[0]) == "a" && $sender->isOP()) {
                    $sender->sendMessage($this->prefix . "§aDein Gamemode wurde zu §cADVENTURE §agewechselt!");
                    $sender->setGamemode(2);
                }
                if (strtolower($args[0]) == "spc" && $sender->isOP()) {
                    $sender->sendMessage($this->prefix . "§aDein Gamemode wurde zu §cSPECTATOR §agewechselt!");
                    $sender->setGamemode(3);
                }
                break;
            case "feed":
                if ($sender->isOP() && $sender instanceof Player) {
                    $sender->setFood(20);
                    $sender->sendMessage($this->prefix . "§aHungerleiste voll!");
                } else {
                    $sender->sendMessage($this->prefix . "§4Unzureichende Rechte!");
                }
                break;
            case "heal":
                if ($sender->isOP() && $sender instanceof Player) {
                    $sender->setHealth(20);
                    $sender->sendMessage($this->prefix . "§aLebensanzeige voll!");
                } else {
                    $sender->sendMessage($this->prefix . "§4Unzureichende Rechte!");
                }
                break;
            case "spc":
                if (!isset($args[0]) && $sender->hasPermission("vanish.use")) {
                    $sender->sendMessage($this->prefix . "§6-> §f/spc §7<§a+§7 | §c-§7>");
                }
                if ($args[0] != "+" &&
                        $args[0] != "-" && $sender->hasPermission("vanish.use")) {

                    $sender->sendMessage($this->prefix . "§6-> §f/spc §7<§a+§7 | §c-§7>");
                }
                if ($args[0] == "-" && $sender->hasPermission("vanish.use")) {
                    $sender->sendMessage($this->prefix . "§fVanishMode §cVERLASSEN!");
                    $sender->removeAllEffects();
                    $sender->getInventory()->clearAll();
                } else {
                    
                }
                if ($args[0] == "+" && $sender->hasPermission("vanish.use")) {
                    $sender->removeAllEffects();
                    $sender->getInventory()->clearAll();
                    $sender->sendMessage($this->prefix . "§fVanishMode §aBETRETEN!");
                    $sender->setGamemode(0);
                    $sender->addEffect(Effect::getEffect(14)->setAmplifier(1)->setDuration(199980)->setVisible(false));
                }
                break;
            case "cinv":
                if (!isset($args[0]) && $sender->isOP()) {
                    $sender->removeAllEffects();
                    $sender->getInventory()->clearAll();
                    $sender->sendMessage($this->prefix . "§aInventar geleert!");
                }
                if (isset($args[0]) && $sender->isOP()) {
                    $p = $args[0]->getPlayer();
                    $name = $p->getName();

                    $p->removeAllEffects();
                    $p->getInventory()->clearAll();
                    $p->sendMessage($this->prefix . "§aInventar geleert!");
                    $sender->sendMessage($this->prefix . "§aDas Inventar von §b$name §awurde geleert.");
                }
             break;
              case "gethealth":
				if($sender instanceof Player){
					$h = $sender->getHealth() /2;
					$sender->sendMessage("-> $h");
					$this->getLogger()->info("$name -> $h");
				}
		}
    }

}
