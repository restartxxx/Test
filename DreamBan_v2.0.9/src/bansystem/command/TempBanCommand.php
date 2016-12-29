<?php

namespace bansystem\command;

use bansystem\translation\Translation;
use bansystem\util\date\Countdown;
use DateTime;
use InvalidArgumentException;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class TempBanCommand extends Command {
    
    public function __construct() {
        parent::__construct("tempban");
        $this->description = "Sperre einen Spieler auf Zeit.";
        $this->usageMessage = "/tempban <Spieler> <ZeitFormat> [Grund...]";
        $this->setPermission("bansystem.command.tempban");
    }
    
    public function execute(CommandSender $sender, $commandLabel, array $args) {
        if ($this->testPermissionSilent($sender)) {
            if (count($args) <= 1) {
                $sender->sendMessage(Translation::translateParams("usage", array($this)));
                return false;
            }
            $player = $sender->getServer()->getPlayer($args[0]);
            $playerName = $args[0]; 
            $banList = $sender->getServer()->getNameBans();
            try {
                if ($banList->isBanned($args[0])) {
                    $sender->sendMessage(Translation::translate("playerAlreadyBanned"));
                    return false;
                }
                $expiry = new Countdown($args[1]);
                $expiryToString = Countdown::expirationTimerToString($expiry->getDate(), new DateTime());
                if (count($args) == 2) {
                    if ($player != null) {
                        $playerName = $player->getName();
                        $banList->addBan($player->getName(), null, $expiry->getDate(), $sender->getName());
                        $player->kick(TextFormat::RED . "Du wurdest gebannt,"
                                . " dein Ban endet in " . TextFormat::AQUA . $expiryToString . TextFormat::RED . ".\n§7Du kannst auf §aweb.dreambuild.de §7einen Entbannungsantrag stellen.", false);
                    } else {
                        $banList->addBan($args[0], null, $expiry->getDate(), $sender->getName());
                    }
                    $sender->getServer()->broadcastMessage("§7Der Spieler§f " . $playerName
                            . TextFormat::RED . "§7 wurde " . $expiryToString . TextFormat::RED . "§7 von " . $sender->getName() . " §cgebannt§7!");
                    
                } else if (count($args) >= 3) {
                    $banReason = "";
                    for ($i = 2; $i < count($args); $i++) {
                        $banReason .= $args[$i];
                        $banReason .= " ";
                    }
                    $banReason = substr($banReason, 0, strlen($banReason) - 1);
                    if ($player != null) {
                        $banList->addBan($player->getName(), $banReason, $expiry->getDate(), $sender->getName());
                        $player->kick(TextFormat::RED . "Du wurdest wegen " . $banReason . "gebannt,"
                                . " dein Ban endet in " . $expiryToString . ".\n§7Du kannst auf §aweb.dreambuild.de§7 einen Entbannungsantrag stellen.", false);
                    } else {
                        $banList->addBan($args[0], $banReason, $expiry->getDate(), $sender->getName());
                    }
                    $sender->getServer()->broadcastMessage("§7Der Spieler §c" . $playerName
                            . TextFormat::RED . "§7 wurde wegen §c" . $banReason . " §7noch §a" . $expiryToString . " §cgebannt§7.");
                }
            } catch (InvalidArgumentException $e) {
                $sender->sendMessage(TextFormat::RED . $e->getMessage());
            }
        } else {
            $sender->sendMessage(Translation::translate("noPermission"));
        }
        return true;
    }
}