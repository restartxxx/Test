<?php

namespace pets\command;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pets\main;
use pocketmine\utils\TextFormat;

class PetCommand extends PluginCommand {

	public function __construct(main $main, $name) {
		parent::__construct(
				$name, $main
		);
		$this->main = $main;
		$this->setPermission("pets.command");
		$this->setAliases(array("pet"));
	}

	public function execute(CommandSender $sender, $currentAlias, array $args) {
		  
                         if (!isset($args[0])) {
                          if($sender->hasPermission('pets.command')){
			$this->main->togglePet($sender);
                         return true;
                          }else{
                           $sender->sendMessage(TextFormat::RED."You do not have permission to use this command");
			
                    return true;
                }
                         }
		 if($args[0] == "help"){
				if($sender->hasPermission('pets.command.help')){
				$sender->sendMessage("§e======PetHelp======");
                                $sender->sendMessage("§e/pets help §7[§cZeigt alle Pet Commands§7]");
				$sender->sendMessage("§e/pets type <type> §7[§cÄndert dein Pet§7]");
                                $sender->sendMessage("§e/pets name <name> §7[§cÄndert dein Petname§7]");
				$sender->sendMessage("§eTypes: blaze, pig, chicken, wolf, rabbit, magma, bat, silverfish, spider, cow, creeper, irongolem, husk, enderman, sheep, witch, block");
                                return true;
				}else{$sender->sendMessage(TextFormat::RED."You do not have permission to use this command");
					    }
				return true;
                 }
               if($args[0] == "name"){
               	 if($sender->hasPermission("pets.command.name")){
               	 if (isset($args[1])){
               	  $petname = $args[1];
               	  $pet = $this->main->getPet($sender->getName());
               	  $pet->setNameTag($petname);
               	  $sender->sendMessage(TextFormat::BLUE."Dein Pet heisst jetzt ".$petname."");
               	 }
               	 }else{
               	 	$sender->sendMessage(TextFormat::RED."You do not have permission to use this command");
               	 }
               }
               	
			if($args[0] == "type"){
				if (isset($args[1])){
					if($args[1] == "wolf"){
							if ($sender->hasPermission("pets.type.wolf")){
								$this->main->changePet($sender, "WolfPet");
								$sender->sendMessage("Dein Pet ist jetzt ein Wolf!");
								return true;
							}else{
								$sender->sendMessage("You do not have permission for dog pet!");
								return true;
							}
                                        }
						if($args[1] == "chicken"){
							if ($sender->hasPermission("pets.type.chicken")){
								$this->main->changePet($sender, "ChickenPet");
								$sender->sendMessage("Dein Pet ist jetzt ein Chicken!");
								return true;
							}else{
								$sender->sendMessage("You do not have permission for chicken pet!");
								return true;
							}
                                                }
						if($args[1] == "pig"){
							if ($sender->hasPermission("pets.type.pig")){
								$this->main->changePet($sender, "PigPet");
								$sender->sendMessage("Dein Pet ist jetzt ein Pig!");
								return true;
							}else{
								$sender->sendMessage("You do not have permission for pig pet!");
								return true;
							}
                                                }
						if($args[1] == "blaze"){
							if ($sender->hasPermission("pets.type.blaze")){
								$this->main->changePet($sender, "BlazePet");
								$sender->sendMessage("Dein Pet ist jetzt ein Blaze!");
								return true;
							}else{
								$sender->sendMessage("You do not have permission for blaze pet!");
								return true;
							}
                                                }
						if($args[1] == "magma"){
							if ($sender->hasPermission("pets.type.magma")){
								$this->main->changePet($sender, "MagmaPet");
								$sender->sendMessage("Dein Pet ist jetzt ein Magma!");
								return true;
							}else{
								$sender->sendMessage("You do not have permission for blaze pet!");
								return true;
							}
                                                }
						if($args[1] == "rabbit"){
							if ($sender->hasPermission("pets.type.rabbit")){
								$this->main->changePet($sender, "RabbitPet");
								$sender->sendMessage("Dein Pet ist jetzt ein Rabbit!");
								return true;
							}else{
								$sender->sendMessage("You do not have permission for rabbit pet!");
								return true;
							}
                                                }
						if($args[1] == "bat"){
							if ($sender->hasPermission("pets.type.bat")){
								$this->main->changePet($sender, "BatPet");
								$sender->sendMessage("Dein Pet ist jetzt ein Bat!");
								return true;
							}else{
								$sender->sendMessage("You do not have permission for bat pet!");
								return true;
							}
                                                }
						if($args[1] == "silverfish"){
							if ($sender->hasPermission("pets.type.silverfish")){
								$this->main->changePet($sender, "SilverfishPet");
								$sender->sendMessage("Dein Pet ist jetzt ein Siverfish!");
								return true;
							}else{
								$sender->sendMessage("You do not have permission for Silverfish pet!");
								return true;
							}
						
							}
								if($args[1] == "spider"){
							if ($sender->hasPermission("pets.type.spider")){
								$this->main->changePet($sender, "SpiderPet");
								$sender->sendMessage("Dein Pet ist jetzt ein Spider!");
								return true;
							}else{
								$sender->sendMessage("You do not have permission for spider pet!");
								return true;
							}
                                                }
                                		if($args[1] == "cow"){
							if ($sender->hasPermission("pets.type.cow")){
								$this->main->changePet($sender, "CowPet");
								$sender->sendMessage("Dein Pet ist jetzt ein Cow!");
								return true;
							}else{
								$sender->sendMessage("You do not have permission for cow pet!");
								return true;
							}
                                                }
						if($args[1] == "creeper"){
							if ($sender->hasPermission("pets.type.creeper")){
								$this->main->changePet($sender, "CreeperPet");
								$sender->sendMessage("Dein Pet ist jetzt ein Creeper!");
								return true;
							}else{
								$sender->sendMessage("You do not have permission for creeper pet!");
								return true;
							}
                                                }
					                 if($args[1] == "irongolem"){
							if ($sender->hasPermission("pets.type.irongolem")){
								$this->main->changePet($sender, "IronGolemPet");
								$sender->sendMessage("Dein Pet ist jetzt ein Iron Golem!");
								return true;
							}else{
								$sender->sendMessage("You do not have permission for Iron Golem pet!");
								return true;
							}
                                                }
			                    if($args[1] == "husk"){
							if ($sender->hasPermission("pets.type.husk")){
								$this->main->changePet($sender, "HuskPet");
								$sender->sendMessage("Dein Pet ist jetzt ein Husk!");
								return true;
							}else{
								$sender->sendMessage("You do not have permission for Husk pet!");
								return true;
							}
                                                }
                                           if($args[1] == "enderman"){
							if ($sender->hasPermission("pets.type.enderman")){
								$this->main->changePet($sender, "EndermanPet");
								$sender->sendMessage("Dein Pet ist jetzt ein Enderman!");
								return true;
							}else{
								$sender->sendMessage("You do not have permission for Enderman pet!");
								return true;
							}
                                                }
                                                 if($args[1] == "sheep"){
							if ($sender->hasPermission("pets.type.sheep")){
								$this->main->changePet($sender, "SheepPet");
								$sender->sendMessage("Dein Pet ist jetzt ein Sheep!");
								return true;
							}else{
								$sender->sendMessage("You do not have permission for Sheep pet!");
								return true;
							}
                                                }
                                                 if($args[1] == "witch"){
							if ($sender->hasPermission("pets.type.witch")){
								$this->main->changePet($sender, "WitchPet");
								$sender->sendMessage("Dein Pet ist jetzt ein Witch!");
								return true;
							}else{
								$sender->sendMessage("You do not have permission for Witch pet!");
								return true;
							}
                                                }
                                                if($args[1] == "block"){
							if ($sender->hasPermission("pets.type.block")){
								$this->main->changePet($sender, "BlockPet");
								$sender->sendMessage("Dein Pet ist jetzt ein Block!");
								return true;
							}else{
								$sender->sendMessage("You do not have permission for Block pet!");
								return true;
							}
                                                }
	}
                                                
                                                
                        }                            
        }
}
                        

                         
        
