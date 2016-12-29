<?php

namespace bansystem\command;

use bansystem\translation\Translation;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class UnbanIPCommand extends Command {
    
    public function __construct() {
        parent::__construct("unban-ip");
        $this->description = "Allows the given IP address to use this server.";
        $this->usageMessage = "/unban-ip <address>";
        $this->setPermission("bansystem.command.pardonip");
    }
    
    public function execute(CommandSender $sender, $commandLabel, array $args) {
        if ($this->testPermissionSilent($sender)) {
            if (count($args) <= 0) {
                $sender->sendMessage(Translation::translateParams("usage", array($this)));
                return false;
            }
            $banList = $sender->getServer()->getIPBans();
            if (!$banList->isBanned($args[0])) {
                $sender->sendMessage(Translation::translate("ipNotBanned"));
                return false;
            }
            $banList->remove($args[0]);
            $sender->getServer()->broadcastMessage("§7Die IP-Adresse §c(Zensiert)§7 wurde von §a" . $sender->getName() . " §aentbannt§7.");
        } else {
            $sender->sendMessage(Translation::translate("noPermission"));
        }
        return true;
    }
}