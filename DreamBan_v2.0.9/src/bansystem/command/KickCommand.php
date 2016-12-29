<?php

namespace bansystem\command;

use bansystem\translation\Translation;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class KickCommand extends Command {
    
    public function __construct() {
        parent::__construct("kick");
        $this->description = "Removes the given player.";
        $this->usageMessage = "/kick <player> <reason...>";
        $this->setPermission("bansystem.command.kick");
    }
    
    public function execute(CommandSender $sender, $commandLabel, array $args) {
        if ($this->testPermissionSilent($sender)) {
            if (count($args) <= 0) {
                $sender->sendMessage(Translation::translateParams("usage", array($this)));
                return false;
            }
            $player = $sender->getServer()->getPlayer($args[0]);
            if (count($args) == 1) {
                if ($player != null) {
                    $player->kick(TextFormat::RED . "§7Du wurdest von§a " . $sender->getName() . " §7vom Server §cgekickt§7!", false);
                    $sender->getServer()->broadcastMessage("§7Der Spieler §c" . $player->getName() . TextFormat::RED . " §7wurde von §a" . $sender->getName() . " §cgekickt§7.");
                } else {
                    $sender->sendMessage(Translation::translate("playerNotFound"));
                }
            } else if (count($args) >= 2) {
                if ($player != null) {
                    $reason = "";
                    for ($i = 1; $i < count($args); $i++) {
                        $reason .= $reason[$i];
                        $reason .= " ";
                    }
                    $reason = substr($reason, 0, strlen($reason) - 1);
                    $player->kick(TextFormat::RED . "§7Du wurdest wegen §c" . $reason . TextFormat::RED . " §7von §a" . $sender->getName() . " §7vom Server§c gekickt§7.", false);
                    $sender->getServer()->broadcastMessage("§7Der Spieler §c" . $player->getName() . TextFormat::RED . " §7wurde von §a" . $sender->getName() . " §cgekickt§7. §fGrund: §e" . $reason . "§7.");
                } else {
                    $sender->sendMessage(Translation::translate("playerNotFound"));
                }
            }
        } else {
            $sender->sendMessage(Translation::translate("noPermission"));
        }
        return true;
    }
}