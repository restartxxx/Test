<?php
namespace Bluplayz;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class Friends extends PluginBase implements Listener {

    const Bluplayz = "Bluplayz";

    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
		
        $this->getLogger()->info("wurde Erfolgreich Geladen!");
        $this->getLogger()->info(TextFormat::AQUA . " Plugin von " . TextFormat::GREEN . "BlawoHD");

		@mkdir($this->getDataFolder());
		@mkdir($this->getDataFolder()."Players/");
		
    }
	public function onJoin(PlayerJoinEvent $event) {
		$player = $event->getPlayer();
		$name = $player->getName();

		$playerfile = new Config($this->getDataFolder()."Players/" . strtolower($name) . ".yml", Config::YAML);

		if(empty($playerfile->get("Freunde"))){
			$playerfile->set("Freunde", array("steve"));
		}
		$playerfile->set("Einladungen", array("steve"));
		$playerfile->save();
		$this->friendsOnline($player);


	}
	public function friendsOnline(Player $player) {
		$name = $player->getName();
		$playerfile = new Config($this->getDataFolder()."Players/" . strtolower($name) . ".yml", Config::YAML);
		$freunde = $playerfile->get("Freunde");
		$freundesPrefix = TextFormat::ESCAPE . "7[" . TextFormat::ESCAPE . "4Freunde" . TextFormat::ESCAPE . "7] " . TextFormat::ESCAPE . "f";

		foreach ($freunde as $friend) {
			if ($friend != "steve") {
				$target = $this->getServer()->getPlayerExact($friend);
				if ($target != null) {
					$target->sendMessage($freundesPrefix . TextFormat::ESCAPE . "6" . $name . " " . TextFormat::ESCAPE . "aist nun Online!");
				}
			}
		}
	}
	public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
		$name = $sender->getName();
		$freundesPrefix = TextFormat::ESCAPE . "7[" . TextFormat::ESCAPE . "4Freunde" . TextFormat::ESCAPE . "7] " . TextFormat::ESCAPE . "f";


		if (strtolower($cmd->getName()) === "friend") {
			if ($sender instanceof Player) {

				$playerfile = new Config($this->getDataFolder()."Players/".strtolower($name) . ".yml", Config::YAML);
				$freunde = $playerfile->get("Freunde");
				$invites = $playerfile->get("Einladungen");

				if (!empty($args[0])) {
					if (strtolower($args[0]) == "invite" || strtolower($args[0]) == "add") {
						if (!empty($args[1])) {
							$targetname = $args[1];
							$targetfile = new Config($this->getDataFolder()."Players/". strtolower($targetname) . ".yml", Config::YAML);
							$targetinvites = $targetfile->get("Einladungen");
							if (!in_array($targetname, $freunde)) {
								if (!in_array($targetname, $targetinvites)) {
									$target = $this->getServer()->getPlayerExact($targetname);
									if ($target != null) {
										$target->sendMessage($freundesPrefix . TextFormat::ESCAPE . "6" . $name . " " . TextFormat::ESCAPE . "ahat dir eine Freundesanfrage gesendet!");
										$target->sendMessage($freundesPrefix . TextFormat::ESCAPE . "6/friend accept " . $name . " " . TextFormat::ESCAPE . "azum " . TextFormat::ESCAPE . "aAnnehmen");
										$target->sendMessage($freundesPrefix . TextFormat::ESCAPE . "6/friend deny " . $name . " " . TextFormat::ESCAPE . "azum " . TextFormat::ESCAPE . "cAblehnen");

										$targetinvites[] = $name;
										$targetfile->set("Einladungen", $targetinvites);
										$targetfile->save();

										$sender->sendMessage($freundesPrefix . TextFormat::ESCAPE . "aDeine Einladung wurde abgeschickt!");
									} else {
										$sender->sendMessage($freundesPrefix . TextFormat::ESCAPE . "cSpieler wurde nicht Gefunden!");
									}
								} else {
									$sender->sendMessage($freundesPrefix . TextFormat::ESCAPE . "cDu hast " . TextFormat::ESCAPE . "6" . $targetname . " " . TextFormat::ESCAPE . "cBereits eine Anfrage gesendet!");
								}
							} else {
								$sender->sendMessage($freundesPrefix . TextFormat::ESCAPE . "6" . $targetname . " " . TextFormat::ESCAPE . "cist Bereits in deiner Freundesliste!");
							}
						} else {
							$sender->sendMessage($freundesPrefix . "/friend invite <player>");
						}
					} elseif (strtolower($args[0]) == "del" || strtolower($args[0]) == "delete") {
						if (!empty($args[1])) {
							if (in_array($args[1], $freunde)) {
								$targetname = $args[1];
								$targetfile = new Config("/home/grimmy/Core/Players/" . strtolower($targetname{0}) . "/" . strtolower($targetname) . ".yml", Config::YAML);
								$targetfriends = $targetfile->get("Freunde");
								$target = $this->getServer()->getPlayerExact($targetname);

								unset($freunde[array_search($targetname, $freunde)]);
								$playerfile->set("Freunde", $freunde);
								$playerfile->save();

								unset($targetfriends[array_search($name, $targetfriends)]);
								$targetfile->set("Freunde", $targetfriends);
								$targetfile->save();

								if ($target != null) {
									$target->sendMessage($freundesPrefix . TextFormat::ESCAPE . "6" . $name . " " . TextFormat::ESCAPE . "ahat die Freundschaft mit dir Beendet!");
								}
								$sender->sendMessage($freundesPrefix . TextFormat::ESCAPE . "aDu hast die Freundschaft mit " . TextFormat::ESCAPE . "6" . $targetname . " " . TextFormat::ESCAPE . "aBeendet!");
							} else {
								$sender->sendMessage($freundesPrefix . TextFormat::ESCAPE . "6" . $args[1] . " " . TextFormat::ESCAPE . "cist nicht in deiner Freundesliste!");
							}
						} else {
							$sender->sendMessage($freundesPrefix . "/friend del <player>");
						}
					} elseif (strtolower($args[0]) == "accept") {
						if (!empty($args[1])) {
							if (!in_array($args[1], $freunde)) {
								$targetname = $args[1];
								$targetfile = new Config($this->getDataFolder()."Players/" . strtolower($targetname) . ".yml", Config::YAML);
								$targetinvites = $targetfile->get("Einladungen");
								$targetfriends = $targetfile->get("Freunde");
								$target = $this->getServer()->getPlayerExact($targetname);
								$invites = $playerfile->get("Einladungen");

								if (in_array($targetname, $invites)) {
									unset($invites[array_search($targetname, $invites)]);
									$freunde[] = $targetname;
									$playerfile->set("Einladungen", $invites);
									$playerfile->set("Freunde", $freunde);
									$playerfile->save();

									$targetfriends[] = $name;
									$targetfile->set("Freunde", $targetfriends);
									$targetfile->save();

									if ($target != null) {
										$target->sendMessage($freundesPrefix . TextFormat::ESCAPE . "6" . $name . " " . TextFormat::ESCAPE . "ahat deine Freundesanfrage " . TextFormat::ESCAPE . "aAngenommen!");
									}
									$sender->sendMessage($freundesPrefix . TextFormat::ESCAPE . "aDu hast die Anfrage von " . TextFormat::ESCAPE . "6" . $targetname . " " . TextFormat::ESCAPE . "aAngenommen!");
								} else {
									$sender->sendMessage($freundesPrefix . TextFormat::ESCAPE . "cDieser Spieler hat dir keine Freundesanfrage gesendet!");
								}
							} else {
								$sender->sendMessage($freundesPrefix . TextFormat::ESCAPE . "6" . $args[1] . " " . TextFormat::ESCAPE . "cist Bereits in deiner Freundesliste!");
							}
						} else {
							$sender->sendMessage($freundesPrefix . "/friend accept <player>");
						}
					} elseif (strtolower($args[0]) == "deny") {
						if (!empty($args[1])) {
							if (!in_array($args[1], $freunde)) {
								$targetname = $args[1];
								$targetfile = new Config($this->getDataFolder()."Players/" . strtolower($targetname) . ".yml", Config::YAML);
								$targetinvites = $targetfile->get("Einladungen");
								$targetfriends = $targetfile->get("Freunde");
								$target = $this->getServer()->getPlayerExact($targetname);
								$invites = $playerfile->get("Einladungen");

								if (in_array($targetname, $invites)) {
									unset($invites[array_search($targetname, $invites)]);
									$freunde[] = $targetname;
									$playerfile->set("Einladungen", $invites);
									$playerfile->save();

									if ($target != null) {
										$target->sendMessage($freundesPrefix . TextFormat::ESCAPE . "6" . $name . " " . TextFormat::ESCAPE . "ahat deine Freundesanfrage " . TextFormat::ESCAPE . "cAbgelehnt!");
									}
									$sender->sendMessage($freundesPrefix . TextFormat::ESCAPE . "aDu hast die Anfrage von " . TextFormat::ESCAPE . "6" . $targetname . " " . TextFormat::ESCAPE . "cAbgelehnt!");
								} else {
									$sender->sendMessage($freundesPrefix . TextFormat::ESCAPE . "cDieser Spieler hat dir keine Freundesanfrage gesendet!");
								}
							} else {
								$sender->sendMessage($freundesPrefix . TextFormat::ESCAPE . "6" . $args[1] . " " . TextFormat::ESCAPE . "cist Bereits in deiner Freundesliste!");
							}
						} else {
							$sender->sendMessage($freundesPrefix . "/friend deny <player>");
						}
					} elseif (strtolower($args[0]) == "list") {
						$sender->sendMessage(TextFormat::ESCAPE . "7==]" . TextFormat::ESCAPE . "4Freunde" . TextFormat::ESCAPE . "7[==");

						foreach ($freunde as $friend) {
							if ($friend != "steve") {
								if ($this->getServer()->getPlayerExact($friend) != null) {
									$sender->sendMessage(TextFormat::ESCAPE . "7- " . TextFormat::ESCAPE . "f" .  $friend . " " . TextFormat::ESCAPE . "7[" . TextFormat::ESCAPE . "aOnline" . TextFormat::ESCAPE . "7]");
								} else {
									$sender->sendMessage(TextFormat::ESCAPE . "7- " . TextFormat::ESCAPE . "f" . $friend . " " . TextFormat::ESCAPE . "7[" . TextFormat::ESCAPE . "cOffline" . TextFormat::ESCAPE . "7]");
								}
							}
						}
						$sender->sendMessage(" ");
					}
				} else {
					$sender->sendMessage($freundesPrefix . "/friend <invite | del | accept | deny | list>");
				}
			} else {
				$sender->sendMessage(TextFormat::ESCAPE . "cDie Konsole hat keine Freunde xD");
			}
		}
	}
	
}