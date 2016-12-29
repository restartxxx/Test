<?php

namespace bansystem\listener;

use bansystem\Manager;
use bansystem\util\date\Countdown;
use DateTime;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\utils\TextFormat;

class PlayerCommandPreproccessListener implements Listener {
    
    public function onPlayerCommandPreproccess(PlayerCommandPreprocessEvent $event) {
        $player = $event->getPlayer();
        $blockList = Manager::getNameBlocks();
        $str = str_split($event->getMessage());
        if ($str[0] != "/") {
            return;
        }
        if ($blockList->isBanned($player->getName())) {
            $blockMessage = "";
            $entries = $blockList->getEntries();
            $entry = $entries[strtolower($player->getName())];
            if ($entry->getExpires() == null) {
                $reason = $entry->getReason();
                if ($reason != null || $reason != "") {
                    $blockMessage = TextFormat::RED . "Â§cDu wurdest wegen " . $reason . " blockiert.Â§7 Du kannst keine Kommandos benutzen bis du wieder freigegeben wirst!";
                } else {
                    $blockMessage = TextFormat::RED . "Â§Du wurdest blockiert.Â§7 Du kannst keine Kommandos benutzen bis du wieder freigegeben wirst!";
                }
            } else {
                $expiry = Countdown::expirationTimerToString($entry->getExpires(), new DateTime());
                if ($entry->hasExpired()) {
                    $blockList->remove($entry->getName());
                    return;
                }
                $blockReason = $entry->getReason();
                if ($blockReason != null || $blockReason != "") {
                    $blockReason = TextFormat::RED . "Â§cDu wurdest wegen " . $blockReason . " noch " . $expiry . " blockiert. Â§7 Du kannst keine Kommandos benutzen bis du wieder freigegeben wirst!";
                } else {
                    $blockReason = TextFormat::RED . "Â§cDu bist noch " . $expiry . " blockiert.Â§7 Du kannst keine Kommandos benutzen bis du wieder freigegeben wirst!";
                }
            }
            $event->setCancelled(true);
            $player->sendMessage($blockMessage);
        }
    }
    
    public function onPlayerCommandPreproccess2(PlayerCommandPreprocessEvent $event) {
        $player = $event->getPlayer();
        $blockList = Manager::getIPBlocks();
        $str = str_split($event->getMessage());
        if ($str[0] != "/") {
            return;
        }
        if ($blockList->isBanned($player->getAddress())) {
            $blockMessage = "";
            $entries = $blockList->getEntries();
            $entry = $entries[strtolower($player->getAddress())];
            if ($entry->getExpires() == null) {
                $reason = $entry->getReason();
                if ($reason != null || $reason != "") {
                    $blockMessage = TextFormat::RED . "Â§cDu wurdest wegen " . $reason . " blockiert.Â§7 Du kannst keine Kommandos benutzen bis du wieder freigegeben wirst!";
                } else {
                    $blockMessage = TextFormat::RED . "Â§cDu bist momentan blockiert.Â§7 Du kannst keine Kommandos benutzen bis du wieder freigegeben wirst!";
                }
            } else {
                $expiry = Countdown::expirationTimerToString($entry->getExpires(), new DateTime());
                if ($entry->hasExpired()) {
                    $blockList->remove($entry->getName());
                    return;
                }
                $blockReason = $entry->getReason();
                if ($blockReason != null || $blockReason != "") {
                    $blockReason = TextFormat::RED . "Â§cDu wurdest wegen " . $blockReason . " noch " . $expiry . " blockiert.Â§7 Du kannst keine Kommandos benutzen bis du wieder freigegeben wirst!";
                } else {
                    $blockReason = TextFormat::RED . "Â§cDu bist noch " . $expiry . " blockiert.Â§7 Du kannst keine Kommandos benutzen bis du wieder freigegeben wirst!";
                }
            }
            $event->setCancelled(true);
            $player->sendMessage($blockMessage);
        }
    }
}