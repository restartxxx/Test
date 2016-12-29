<?php

namespace bansystem\command;

use bansystem\Manager;
use bansystem\translation\Translation;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class UnmuteIPCommand extends Command {
    
    public function __construct() {
        parent::__construct("unmute-ip");
        $this->description = "Allows the given IP address from sending public chat message.";
        $this->usageMessage = "/unmute-ip <address>";
        $this->setPermission("bansystem.command.unmuteip");
    }
    
    public function execute(CommandSender $sender, $commandLabel, array $args) {
        if ($this->testPermission($sender)) {
            if (count($args) <= 0) {
                $sender->sendMessage(Translation::translateParams("usage", array($this)));
                return false;
            }
            $muteList = Manager::getIPMutes();
            if (!$muteList->isBanned($args[0])) {
                $sender->sendMessage(Translation::translate("ipNotMuted"));
                return false;
            }
            $muteList->remove($args[0]);
            $sender->getServer()->broadcastMessage("§7Die IP-Adresse §a(Zensiert) §7wurde von " . $sender->getName() . "§a entmutet§7.");
        } else {
            $sender->sendMessage(Translation::translate("noPermission"));
        }
        return true;
    }
}