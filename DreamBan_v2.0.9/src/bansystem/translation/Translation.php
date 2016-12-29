<?php

namespace bansystem\translation;

use bansystem\exception\TranslationFailedException;
use InvalidArgumentException;
use pocketmine\command\Command;
use pocketmine\utils\TextFormat;

class Translation {
    
    public static function translate(string $translation) : string {
        switch ($translation) {
            case "noPermission":
                return TextFormat::RED . "Du hast keinen Zugriff auf dieses Kommando!";
            case "playerNotFound":
                return TextFormat::GOLD . "Dieser Spieler ist nicht Online.";
            case "playerAlreadyBanned":
                return TextFormat::GOLD . "Dieser Spieler ist bereits gebannt.";
            case "ipAlreadyBanned":
                return TextFormat::GOLD . "Dieser Spieler ist bereits IP-gebannt.";
            case "ipNotBanned":
                return TextFormat::GOLD . "Diese IP Adresse ist nicht gebannt.";
            case "ipAlreadyMuted":
                return TextFormat::GOLD . "Diese IP Adresse ist bereits gemutet.";
            case "playerNotBanned":
                return TextFormat::GOLD . "Dieser Spieler ist nicht gebannt.";
            case "playerAlreadyMuted":
                return TextFormat::GOLD . "Dieser Spieler ist bereits gemutet.";
            case "playerNotMuted":
                return TextFormat::GOLD . "Dieser Spieler ist nicht gemutet.";
            case "ipNotMuted":
                return TextFormat::GOLD . "Dieser IP Adresse ist nicht gemutet.";
            case "playerAlreadyBlocked":
                return TextFormat::GOLD . "Dieser Spieler ist bereits geblockt.";
            case "playerNotBlocked":
                return TextFormat::GOLD . "Dieser Spieler ist nicht geblockt.";
            case "ipAlreadyBlocked":
                return TextFormat::GOLD . "Diese IP Adresse ist bereits geblockt.";
            case "ipNotBlocked":
                return TextFormat::GOLD . "Diese IP Adresse ist nicht geblockt.";
            default:
                throw new TranslationFailedException("Failed to translate.");
        }
    }
    
    public static function translateParams(string $translation, array $parameters) : string {
        if (empty($parameters)) {
            throw new InvalidArgumentException("Parameter fehlen!");
        }
        switch ($translation) {
            case "usage":
                $command = $parameters[0];
                if ($command instanceof Command) {
                    return TextFormat::DARK_GREEN . "Bitte Benutze: " . TextFormat::GREEN . $command->getUsage();
                } else {
                    throw new InvalidArgumentException("Parameter index 0 muss Typ eines Kommandos sein.");
                }
        }
    }
}