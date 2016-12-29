<?php

namespace TimeRanks;

use pocketmine\command\CommandSender;
use pocketmine\Player;

class TimeRanksCommand{

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }

    public function run(CommandSender $sender, array $args){
        if(!isset($args[0])){
            $sender->sendMessage("§7[§eZeitRang§7] §bPlugin Edit by §3SahiraCraft - TheVinidos");
            $sender->sendMessage("§7[§eZeitRang§7] §6/tr check ".($sender instanceof Player ? "[player]" : "<player>"));
            return true;
        }
        $sub = array_shift($args);
        switch(strtolower($sub)){
            case "check":
                if(isset($args[0])){
                    if(!$this->plugin->getServer()->getOfflinePlayer($args[0])->hasPlayedBefore()){
                        $sender->sendMessage("§7[§eZeitRang§7] §b".$args[0]." §fhat bei uns nie gespielt!");
                        return true;
                    }
                    if(!$this->plugin->data->exists(strtolower($args[0]))){
                        $sender->sendMessage("§7[§eZeitRang§7] §b" .$args[0]." §fhat eine Spielzeit unter einer Minute!");
                        $sender->sendMessage("§7[§eZeitRang§7] §fRang: §a".$this->plugin->default);
                        return true;
                    }
                    $sender->sendMessage("§7[§eZeitRang§7] §b" .$args[0]." §fspielt seit §e".$this->plugin->data->get(strtolower($args[0]))." §fMinuten auf unserem Server!");
                    $sender->sendMessage("§7[§eZeitRang§7] §fRang: §a".$this->plugin->getRank(strtolower($args[0])));
                    return true;
                }
                if(!$this->plugin->data->exists(strtolower($sender->getName()))){
                    if(!($sender instanceof Player)){
                        $sender->sendMessage("§7[§eZeitRang§7] §6/tr check <playername>");
                        return true;
                    }
                    $sender->sendMessage("§7[§eZeitRang§7] §fDu hast eine Spielzeit von unter einer Minute!");
                    $sender->sendMessage("§7[§eZeitRang§7] §fRang: §a".$this->plugin->default);
                    return true;
                }
                $sender->sendMessage("§7[§eZeitRang§7] §fDeine Spielzeit beträgt §e".$this->plugin->data->get(strtolower($sender->getName()))." §fMinuten!");
                $sender->sendMessage("§7[§eZeitRang§7] §fRang: §a".$this->plugin->getRank(strtolower($sender->getName())));
                return true;
            break;
            default:
                return false;
        }
    }

}