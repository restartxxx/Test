<?php

namespace bansystem\listener;

use bansystem\Manager;
use bansystem\util\date\Countdown;
use DateTime;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\utils\TextFormat;

class PlayerChatListener implements Listener {
    
    public function onPlayerChat(PlayerChatEvent $event) {
        $player = $event->getPlayer();
        $muteList = Manager::getNameMutes();
        if ($muteList->isBanned($player->getName())) {
            $entries = $muteList->getEntries();
            $entry = $entries[strtolower($player->getName())];
            $muteMessage = "";
            if ($entry->getExpires() == null) {
                $reason = $entry->getReason();
                if ($reason != null || $reason != "") {
                    $muteMessage = TextFormat::RED . "Â§cAchtung!Â§6 ->Â§7 Du bist momentan wegen " . $reason . " Â§7gemutet.";
                } else {
                    $muteMessage = TextFormat::RED . "Â§cAchtung!Â§6 ->Â§7Du bist gemutet.";
                }
            } else {
                $expiry = Countdown::expirationTimerToString($entry->getExpires(), new DateTime());
                if ($entry->hasExpired()) {
                    $muteList->remove($entry->getName());
                    return;
                }
                $muteReason = $entry->getReason();
                if ($muteReason != null || $muteReason != "") {
                    $muteMessage = TextFormat::RED . "Â§cAchtung! Â§6-> Â§7Du bist momentan wegen Â§7" . $reason . " Â§7bis zum " . $expiry . " gemutet.";
                } else {
                    $muteMessage = TextFormat::RED . "Â§cAchtung! Â§6-> Â§7Du bist noch bis " . $expiry . "Â§7 gemutet.";
                }
            }
            $event->setCancelled(true);
            $player->sendMessage($muteMessage);
        }
    }
    
    public function onPlayerChat2(PlayerChatEvent $event) {
        $player = $event->getPlayer();
        $muteList = Manager::getIPMutes();
        if ($muteList->isBanned($player->getAddress())) {
            $entries = $muteList->getEntries();
            $entry = $entries[strtolower($player->getAddress())];
            $muteMessage = "";
            if ($entry->getExpires() == null) {
                $reason = $entry->getReason();
                if ($reason != null || $reason != "") {
                    $muteMessage = TextFormat::RED . "Â§cAchtung!Â§6 -> Â§7Du bist momentan wegen " . $reason . " Â§7 gemutet.(IP)";
                } else {
                    $muteMessage = TextFormat::RED . "Du bist momentan IP gemutet.";
                }
            } else {
                $expiry = Countdown::expirationTimerToString($entry->getExpires(), new DateTime());
                if ($entry->hasExpired()) {
                    $muteList->remove($entry->getName());
                    return;
                }
                $muteReason = $entry->getReason();
                if ($muteReason != null || $muteReason != "") {
                    $muteMessage = TextFormat::RED . "Â§cAchtung! Â§6-> Â§7Du bist momentan wegen " . $muteReason . " Â§7bis " . $expiry . " Â§7gemutet. (IP)";
                } else {
                    $muteMessage = TextFormat::RED . "Â§cAchtung! Â§6-> Â§7Du bist noch bis " . $expiry . " Â§7gemutet.";
                }
            }
            $event->setCancelled(true);
            $player->sendMessage($muteMessage);
        }
    }
}