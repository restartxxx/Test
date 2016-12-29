<?php

/*
 * MSpawns (v1.5) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: http://www.evolsoft.tk
 * Date: 06/06/2015 01:34 PM (UTC)
 * Copyright & License: (C) 2014-2015 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/MSpawns/blob/master/LICENSE)
 */

namespace MSpawns;

use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;

class Aliases extends AliasesMap {
	
	public function __construct(Main $plugin, $command){
        parent::__construct($command, $plugin);
        $this->plugin = $plugin;
    }
    
    public function execute(CommandSender $sender, $label, array $args) {
    	$command = strtolower($this->getName());
    	if($sender instanceof Player){
    		$this->cfg = $this->plugin->getConfig()->getAll();
    		if($this->cfg["enable-aliases"] == true){
    			//Check if world is loaded
    			if(Server::getInstance()->loadLevel($command) != false){
    				$sender->teleport(Server::getInstance()->getLevelByName($command)->getSafeSpawn());
    				$this->plugin->teleportToSpawn_2($sender, Server::getInstance()->getLevelByName($command));
    			}else{
    				//Check if world can be loaded
    				if(Server::getInstance()->loadLevel($command)){
    					$sender->teleport(Server::getInstance()->getLevelByName($command)->getSafeSpawn());
    					$this->plugin->teleportToSpawn_2($sender, Server::getInstance()->getLevelByName($command));
    				}
    			}
    		}
    	}else{
    		$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cYou can only perform this command as a player"));
    	}
    	return true;
    }
	
}
    ?>
