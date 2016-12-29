<?php
//= cmd:whois,Informational
//: Gives detailed information on players                    Edit by QueenMC
//> usage: **whois** _<player>_
namespace aliuly\grabbag;

use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;

use pocketmine\Player;
use pocketmine\utils\TextFormat;

use aliuly\grabbag\common\BasicCli;
use aliuly\grabbag\common\mc;
use aliuly\grabbag\common\MPMU;
use aliuly\grabbag\common\MoneyAPI;
use aliuly\grabbag\common\PermUtils;

class CmdWhois extends BasicCli implements CommandExecutor {

	public function __construct($owner) {
		parent::__construct($owner);
		PermUtils::add($this->owner, "gb.cmd.whois", "view players details", "op");
		PermUtils::add($this->owner, "gb.cmd.whois.showip", "view players IP address", "op");
		$this->enableCmd("whois",
							  ["description" => mc::_("show players detail info"),
								"usage" => "/whois <player>",
								"permission" => "gb.cmd.whois"]);
	}
	public function onCommand(CommandSender $sender,Command $cmd,$label, array $args) {
		if ($cmd->getName() != "whois") return false;
		$pageNumber = $this->getPageNumber($args);
		if (count($args) != 1) {
			$sender->sendMessage(mc::_("Fehlender Spielername"));
			return true;
		}
		$target = $this->owner->getServer()->getPlayer($args[0]);
		if($target == null) {
			$target = $this->owner->getServer()->getOfflinePlayer($args[0]);
			if ($target == null || !$target->hasPlayedBefore()) {
				$sender->sendMessage(mc::_("%1% wurde nicht gefunden.",$args[0]));
				return true;
			}
		}
		$txt = [];
		$txt[] = TextFormat::AQUA.mc::_("Wer ist %1% ?",$args[0]);

		$txt[] = TextFormat::GREEN.mc::_("Online: ").TextFormat::WHITE
					 . ($target->isOnline() ? mc::_("Ja") : mc::_("Nein"));

		if ($target instanceof Player) {
			$txt[] = TextFormat::GREEN.mc::_("Herzen: ").TextFormat::WHITE
					 ."[".$target->getHealth()."/".$target->getMaxHealth()."]";
			$txt[] = TextFormat::GREEN.mc::_("Welt: ").TextFormat::WHITE
					 .$target->getLevel()->getName();

			$txt[] = TextFormat::GREEN.mc::_("Location: ").TextFormat::WHITE."X:".floor($target->getPosition()->x)." Y:".floor($target->getPosition()->y)." Z:".floor($target->getPosition()->z);
			if ($sender->hasPermission("gb.cmd.whois.showip"))
				$txt[] = TextFormat::GREEN.mc::_("IP Addresse: ").TextFormat::WHITE.$target->getAddress();
			$txt[] = TextFormat::GREEN.mc::_("Gamemode: ").TextFormat::WHITE
					 .MPMU::gamemodeStr($target->getGamemode());
			$txt[] = TextFormat::GREEN.mc::_("Name: ").TextFormat::WHITE
					 . $target->getDisplayName();
			$txt[] = TextFormat::GREEN.mc::_("Fliegt: ").TextFormat::WHITE
					 . ($target->isOnGround() ? mc::_("Nein") : mc::_("Ja"));
			//1.5
			if (MPMU::apiVersion("1.12.0")) {
				$txt[] = TextFormat::GREEN.mc::_("UUID: ").TextFormat::WHITE
						 . $target->getUniqueId();
				$txt[] = TextFormat::GREEN.mc::_("ClientID: ").TextFormat::WHITE
						 . $target->getClientId();
				$txt[] = TextFormat::GREEN.mc::_("Kann fliegen: ").TextFormat::WHITE
						 . ($target->getAllowFlight() ? mc::_("Ja") : mc::_("Nein") );

			}

		} else {
			$txt[] = TextFormat::GREEN.mc::_("Gebannt: ").TextFormat::WHITE
					 . ($target->isBanned() ? mc::_("Ja") : mc::_("Nein"));
		}
		$txt[] = TextFormat::GREEN.mc::_("Whitelisted: ").TextFormat::WHITE
				 . ($target->isWhitelisted() ? mc::_("Ja") : mc::_("Nein"));
		$txt[] = TextFormat::GREEN.mc::_("OP: ").TextFormat::WHITE
				 . ($target->isOp() ? mc::_("Ja") : mc::_("Nein"));

		$txt[] = TextFormat::GREEN.mc::_("Erstes mal Online: ").TextFormat::WHITE
				 . date(mc::_("d-M-Y H:i"),$target->getFirstPlayed()/1000);
		if ($target->getLastPlayed()) {
			$txt[] = TextFormat::GREEN.mc::_("Zuletzt online: ").TextFormat::WHITE
					 . date(mc::_("d-M-Y H:i"),$target->getLastPlayed()/1000);
		}

		$pm = $this->owner->getServer()->getPluginManager();
		if (($kr = $pm->getPlugin("KillRate")) !== null) {
			if (version_compare($kr->getDescription()->getVersion(),"1.1") >= 0) {
				if (intval($kr->getDescription()->getVersion()) == 2) {
					$score = $kr->api->getScore($target);
				} else {
					$score = $kr->getScore($target);
				}
				if ($score)
					$txt[] = TextFormat::GREEN.mc::_("KillRate Score: ").TextFormat::WHITE.$score;
			} else {
				$txt[] = TextFormat::RED.mc::_("KillRate version ist zu alt (%1%)",
														 $kr->getDescription()->getVersion());
			}
		}
		if (($pp = $pm->getPlugin("PurePerms")) !== null) {
			$txt[] = TextFormat::GREEN.mc::_("PP Rang: ").TextFormat::WHITE.$pp->getUser($target)->getGroup()->getName();
		}
		if (($sa = $pm->getPlugin("SimpleAuth")) !== null) {
			if ($target instanceof Player) {
				$txt[] = TextFormat::GREEN.mc::_("Authentifiziert: ").TextFormat::WHITE
						 . ($sa->isPlayerAuthenticated($target) ? mc::_("Ja") : mc::_("Nein"));
			}
			$txt[] = TextFormat::GREEN.mc::_("Registriert: ").TextFormat::WHITE
					 . ($sa->isPlayerRegistered($target) ? mc::_("Ja") : mc::_("Nein"));
		}
		$money = MoneyAPI::moneyPlugin($this->owner);
		if ($money !== null) {
			$txt[]=TextFormat::GREEN.mc::_("Geld: ").TextFormat::WHITE.
				MoneyAPI::getMoney($money,$target->getName()).
				TextFormat::AQUA.mc::_(" (from %1%)",$money->getFullName());
		}
		return $this->paginateText($sender,$pageNumber,$txt);
	}
}
