<?php

namespace bansystem\command;

use bansystem\translation\Translation;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class PardonIPCommand extends Command {
    
    public function __construct() {
        parent::__construct("pardon-ip");
        $this->description = "Erlaube der gegebenen IP dem Server beizutreten.";
        $this->usageMessage = "/pardon-ip <addresse>";
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
            $sender->getServer()->broadcastMessage("§7Die IP-Adresse §f(Zensiert) §7wurde von " . $sender->getName() . " §aentbannt§7.");
        } else {
            $sender->sendMessage(Translation::translate("noPermission"));
        }
        return true;
    }
}