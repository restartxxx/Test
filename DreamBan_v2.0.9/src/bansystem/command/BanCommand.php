<?php

namespace bansystem\command;

use bansystem\translation\Translation;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class BanCommand extends Command {
    
    public function __construct() {
        parent::__construct("ban");
        $this->description = "Spielausschluss eines Spielers.";
        $this->usageMessage = "/ban <Spieler> [Grund...]";
        $this->setPermission("bansystem.command.ban");
    }
    
    public function execute(CommandSender $sender, $label, array $args) {
        if ($this->testPermissionSilent($sender)) {
            if (count($args) <= 0) {
                $sender->sendMessage(Translation::translateParams("usage", array($this)));
                return false;
            }
            $player = $sender->getServer()->getPlayer($args[0]);
            $banList = $sender->getServer()->getNameBans();
            $playerName = $args[0];
            if ($banList->isBanned($args[0])) {
                $sender->sendMessage(Translation::translate("playerAlreadyBanned"));
                return false;
            }
            if (count($args) == 1) {
                if ($player != null) {
                    $banList->addBan($player->getName(), null, null, $sender->getName());
                    $player->kick(TextFormat::RED . "Du wurdest gebannt.§7\n Du kannst auf §aweb.dreambuild.de §7einen EntbannungsAntrag stellen!", false);
                    $playerName = $player->getName();
                } else {
                    $banList->addBan($args[0], null, null, $sender->getName());
                }
                $sender->getServer()->broadcastMessage("§7Der Spieler " . $playerName . TextFormat::RED . "§7 wurde von §a" . $sender->getName() . " §cgebannt§7.");
            } else if (count($args) >= 2) {
                $reason = "";
                for ($i = 1; $i < count($args); $i++) {
                    $reason .= $args[$i];
                    $reason .= " ";
                }
                $reason = substr($reason, 0, strlen($reason) - 1);
                if ($player != null) {
                    $banList->addBan($player->getName(), $reason, null, $sender->getName());
                    $player->kick(TextFormat::RED . "§7Du wurdest wegen §c" . $reason . TextFormat::RED . " gebannt§7.\nDu kannst auf§a web.dreambuild.de§7 einen Entbannungsantrag stellen.", false);
                    $playerName = $player->getName();
                } else {
                    $banList->addBan($args[0], $reason, null, $sender->getName());
                }
                $sender->getServer()->broadcastMessage("§7Der Spieler §c" . $playerName . TextFormat::RED . " §7wurde von §a" . $sender->getName() . " §7wegen " . $reason . TextFormat::RED . " gebannt§7.");
            }
        } else {
            $sender->sendMessage(Translation::translate("noPermission"));
        }
        return true;
    }
}