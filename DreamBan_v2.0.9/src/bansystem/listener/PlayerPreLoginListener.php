<?php

namespace bansystem\listener;

use bansystem\util\date\Countdown;
use DateTime;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\utils\TextFormat;

class PlayerPreLoginListener implements Listener {
    
    public function onPlayerPreLogin(PlayerPreLoginEvent $event) {
        $player = $event->getPlayer();
        $banList = $player->getServer()->getNameBans();
        if ($banList->isBanned(strtolower($player->getName()))) {
            $kickMessage = "";
            $banEntry = $banList->getEntries();
            $entry = $banEntry[strtolower($player->getName())];
            if ($entry->getExpires() == null) {
                $reason = $entry->getReason();
                if ($reason != null || $reason != "") {
                    $kickMessage = TextFormat::RED . "Â§cDu bist momentan wegen " . $reason . " gebannt.\nÂ§7Du kannst auf Â§aweb.dreambuild.deÂ§7 einen Entbannungsantrag stellen.";
                } else {
                    $kickMessage = TextFormat::RED . "Â§cDu bist momentan gebannt.\nÂ§7Du kannst auf Â§aweb.dreambuild.de Â§7einen Entbannungsantrag stellen.";
                }
            } else {
                $expiry = Countdown::expirationTimerToString($entry->getExpires(), new DateTime());
                if ($entry->hasExpired()) {
                    $banList->remove($entry->getName());
                    return;
                }
                $banReason = $entry->getReason();
                if ($banReason != null || $banReason != "") {
                    $kickMessage = TextFormat::RED . "Â§cDu bist momentan wegen " . $banReason . " noch " . $expiry . " gebannt.\nÂ§7Du kannst auf Â§aweb.dreambuild.de Â§7einen Entbannungsantrag stellen.";
                } else {
                    $kickMessage = TextFormat::RED . "Â§cDu bist noch " . TextFormat::AQUA . $expiry . TextFormat::RED . " gebannt.\nÂ§7Du kannst auf Â§aweb.dreambuild.de Â§7einen Entbannungsantrag stellen.";
                }
            }
            $player->close("", $kickMessage);
        }
    }
    
    public function onPlayerPreLogin2(PlayerPreLoginEvent $event) {
        $player = $event->getPlayer();
        $banList = $player->getServer()->getIPBans();
        if ($banList->isBanned(strtolower($player->getAddress()))) {
            $kickMessage = "";
            $banEntry = $banList->getEntries();
            $entry = $banEntry[strtolower($player->getAddress())];
            if ($entry->getExpires() == null) {
                $reason = $entry->getReason();
                if ($reason != null || $reason != "") {
                    $kickMessage = TextFormat::RED . "Â§cDu bist momentan wegen " . $reason . " gebannt.\nÂ§7Du kannst auf Â§aweb.dreambuild.de Â§7einen Entbannungsantrag stellen.";
                } else {
                    $kickMessage = TextFormat::RED . "Â§cDu bist gebannt.\nÂ§7Du kannst auf Â§aweb.dreambuild.de Â§7einen Entbannungsantrag stellen.";
                }
            } else {
                $expiry = Countdown::expirationTimerToString($entry->getExpires(), new DateTime());
                if ($entry->hasExpired()) {
                    $banList->remove($entry->getName());
                    return;
                }
                $banReason = $entry->getReason();
                if ($banReason != null || $banReason != "") {
                    $kickMessage = TextFormat::RED . "Â§cDu bist momentan wegen " . $banReason . " noch " . $expiry . " gebannt.\nÂ§7Du kannst auf Â§aweb.dreambuild.de Â§7einen Entbannungsantrag stellen.";
                } else {
                    $kickMessage = TextFormat::RED . "Â§cDu bist noch " . $expiry . " gebannt.\nÂ§7Du kannst auf Â§aweb.dreambuild.de Â§7einen Entbannungsantrag stellen.";
                }
            }
            $player->close("", $kickMessage);
        }
    }
}