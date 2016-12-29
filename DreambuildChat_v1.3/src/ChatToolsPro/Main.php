<?php
namespace ChatToolsPro;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;
use pocketmine\permission\ServerOperator;
use pocketmine\event\player\PlayerChatEvent;
/**
 *  ____     ______    ______    _________   _________     _______
 * |  _ \   |  __  |  |  ____|  |___   ___| |___   ___|   |__   __|
 * | |_) /  | |__| |  | |____       | |         | |          | |
 * |  __/   |  __  |  |  ____|      | |         | |          | |
 * | |      | |  | |  | |____       | |         | |        __| |__
 * |_|      |_|  |_|  |______|      |_|         |_|       |_______|
 * Plugin coded by paetti and QueenMC.
 * This Label is by paetti.
**/
class Main extends PluginBase implements Listener{
	public $prefix = TextFormat::GREEN."[Dreambuild]".TextFormat::YELLOW." ";
    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveDefaultConfig();
        $this->getLogger()->info(TextFormat::AQUA . "DreambuildChat by QueenMC Gelaaaaaaaaaaden xD");
    }
    
    public function onDisable(){
        $this->getLogger()->info(TextFormat::AQUA . "DreambuildChat disabled.");
    }
        public function onChat(PlayerChatEvent $event) {
    
      if(!($event->getPlayer()->hasPermission("chattoolspro.lockchat"))) {
      
        if($this->disableChat) {
        
          $event->setCancelled(true);
          
          $event->getPlayer()->sendMessage(TextFormat::GREEN."[Dreambuild] " . TextFormat::YELLOW . "Der Chat ist momentan geschlossen!");
          
        }
        
      }
      
    }
    public function onCommand(CommandSender $sender, Command $command, $label, array $args){
        switch($command->getName()){

            case "dreamchat":
                if(!(isset($args[0]))){
                $sender->sendMessage(TextFormat::GREEN . "DreambuildChat by QueenMC");
                $sender->sendMessage(TextFormat::GREEN . "/dreamchat <1/2/3/4/5> for help");
               
      
           
                    return true;
                }
            if($args[0] == "1"){
                $sender->sendMessage(TextFormat::GREEN . "Seite 1 von 4");
                $sender->sendMessage(TextFormat::GREEN . "/announcement " . TextFormat::WHITE . "Broadcast Message with [Announcement] Tag");
                $sender->sendMessage(TextFormat::GREEN . "/serversay " . TextFormat::WHITE . "Broadcast Message with [Server] Tag");
                $sender->sendMessage(TextFormat::GREEN . "/staffsay " . TextFormat::WHITE . "Broadcast Message with [Staff] Tag");
                $sender->sendMessage(TextFormat::GREEN . "/support " . TextFormat::WHITE . "Broadcast Message with [Support] Tag");
                $sender->sendMessage(TextFormat::GREEN . "/warning " . TextFormat::WHITE . "Broadcast Message with [Warning] Tag");
                $sender->sendMessage(TextFormat::GREEN . "/alert" . TextFormat::WHITE . "Broadcast Message with [ALERT] Tag");
                return true;
            }
            elseif($args[0] == "2"){
                $sender->sendMessage(TextFormat::GREEN . "Seite 2 von 4");
                $sender->sendMessage(TextFormat::GREEN . "/info " . TextFormat::WHITE . "Broadcast Message with [Info] Tag");
                $sender->sendMessage(TextFormat::GREEN . "/chatsay " . TextFormat::WHITE . "Broadcast Message without any Tag");
                $sender->sendMessage(TextFormat::GREEN . "/warnung " . TextFormat::WHITE . "Warn a Player");
                $sender->sendMessage(TextFormat::GREEN . "/vmsg " . TextFormat::WHITE . "Send a anonymous Message to a Player");
                $sender->sendMessage(TextFormat::GREEN . "/tipgive " . TextFormat::WHITE . "Give a Tip to a Player");
                $sender->sendMessage(TextFormat::GREEN . "/hug " . TextFormat::WHITE . "Hug a Player");
                return true;
            }
        elseif($args[0] == "3"){
                $sender->sendMessage(TextFormat::GREEN . "Seite 3 von 4");
                $sender->sendMessage(TextFormat::GREEN . "/setzenick " . TextFormat::WHITE . "Set a nick");
                $sender->sendMessage(TextFormat::GREEN . "/sayas " . TextFormat::WHITE . "Say a Message as another Player");
                $sender->sendMessage(TextFormat::GREEN . "/spam " . TextFormat::WHITE . "Spam");
                $sender->sendMessage(TextFormat::GREEN . "/leeren " . TextFormat::WHITE . "Clears the Chat");
                $sender->sendMessage(TextFormat::GREEN . "/spamsay " . TextFormat::WHITE . "Spams a Message");
                $sender->sendMessage(TextFormat::GREEN . "/spammsg " . TextFormat::WHITE . "Send a Message more times to a Player");
                return true;
        }
        elseif($args[0] == "4"){
                $sender->sendMessage(TextFormat::GREEN . "Seite 4 von 4");
                $sender->sendMessage(TextFormat::GREEN . "/hilfe " . TextFormat::WHITE . "Adds [BrauchtHilfe] Tag to Name");
                $sender->sendMessage(TextFormat::GREEN . "/erledigt " . TextFormat::WHITE . "Remove [BrauchtHilfe] Tag from Name");
                $sender->sendMessage(TextFormat::GREEN . "/melden " . TextFormat::WHITE . "Report a Player");
                $sender->sendMessage(TextFormat::GREEN . "/ops " . TextFormat::WHITE . "Lists online OP's");
                $sender->sendMessage(TextFormat::GREEN . "/fakeop " . TextFormat::WHITE . "Fake op somebody");
                $sender->sendMessage(TextFormat::GREEN . "/deopfake " . TextFormat::WHITE . "Fake Deop somebody");
                $sender->sendMessage(TextFormat::GREEN . "/checkop " . TextFormat::WHITE . "Check if a Player is OP or not");
                return true;
        }
         elseif($args[0] == "5"){
                $sender->sendMessage(TextFormat::GREEN . "TeamSeite 1 von 1");
                $sender->sendMessage(TextFormat::GREEN . "/lockchat " . TextFormat::WHITE . "Lock or unlock Chat");
                $sender->sendMessage(TextFormat::GREEN . "/senden " . TextFormat::WHITE . "Send Message to Player without Prefix");
            $sender->sendMessage(TextFormat::GREEN . "/delnick " . TextFormat::WHITE . "Reset your nick");
               
                return true;
        }
                break;
                // Broadcasting Features
                case "durchsage":
                $sender->sendMessage("Â§aDurchsage wure gesendet!");
                $sender->getServer()->broadcastMessage(TextFormat::AQUA . "[Durchsage] " . implode(" ", $args));
                return true;
       
                case "serversay":
                $sender->getServer()->broadcastMessage(TextFormat::LIGHT_PURPLE . "[Dreambuild] " . implode(" ", $args));
                return true;
                case "staffsay":
                $sender->getServer()->broadcastMessage(TextFormat::YELLOW . "[Team] " . TextFormat::RED . implode(" ", $args));
                return true;
                case "support":
                $sender->getServer()->broadcastMessage(TextFormat::YELLOW . TextFormat::BOLD . "[Support] " .TextFormat::RESET . TextFormat::AQUA . implode(" ", $args));
                return true;
                case "warning":
                $sender->getServer()->broadcastMessage(TextFormat::DARK_RED . "[Warnung] " . implode(" ", $args));
                return true;
                case "alert":
                $sender->getServer()->broadcastMessage(TextFormat::RED . "[ALARM] " . implode(" ", $args));
                return true;
                case "info":
                $sender->getServer()->broadcastMessage(TextFormat::AQUA . "[Information] " . implode(" ", $args));
                return true;
                case "chatsay":
                    if(!(isset($args[0]))){
                    return false;
                }
                $sender->getServer()->broadcastMessage(implode(" ", $args));
                return true;
                // UP - Broadcasting Features
            case "warnung":
                $name = \strtolower(\array_shift($args));

		$player = $sender->getServer()->getPlayer($name);

                if($player === $sender){
			$sender->sendMessage("Bitte verwarne jemand anderen!xD");
			return \true;
		}
		
                if($player instanceof Player){
			$sender->sendMessage(TextFormat::DARK_RED . "[Verwarnung" . " -> " . $player->getDisplayName() . "] " . "Â§c" . implode(" ", $args));
			$player->sendMessage(TextFormat::DARK_RED . "[Verwarnung" . " -> ".$player->getName()."] " . implode(" ", $args));
		}else{
			$sender->sendMessage(TextFormat::RED . "/warnung <Player> <Reason>");
		}

		return true;
                case "vmsg":
                $name = \strtolower(\array_shift($args));

		$player = $sender->getServer()->getPlayer($name);

                if($player === $sender){
			$sender->sendMessage("You can't write yourself!");
			return \true;
		}
		
                if($player instanceof Player){
			$sender->sendMessage(TextFormat::YELLOW . "[ -> " . $player->getDisplayName() . "] " . TextFormat::WHITE . implode(" ", $args));
			$player->sendMessage(TextFormat::YELLOW . "[ -> ".$player->getName()."] " . TextFormat::WHITE . implode(" ", $args));
		}else{
			$sender->sendMessage(TextFormat::RED . "Usage: /vmsg <Player> <Message>");
		}

		return true;
		  case "senden":
                $name = \strtolower(\array_shift($args));

		$player = $sender->getServer()->getPlayer($name);

                if($player === $sender){
			$sender->sendMessage("You can't send yourself!");
			return \true;
		}
		
                if($player instanceof Player){
			$sender->sendMessage($this->prefix."Sended to the specified player.");
			$player->sendMessage(implode(" ", $args));
		}else{
			$sender->sendMessage(TextFormat::RED . "Usage: /senden <Player> <Message>");
		}

		return true;
                case "tipgive":
                $name = \strtolower(\array_shift($args));

		$player = $sender->getServer()->getPlayer($name);

                if($player === $sender){
			$sender->sendMessage("You can't give yourself an tip!");
			return \true;
		}
		
                if($player instanceof Player){
			$sender->sendMessage(TextFormat::YELLOW . "[Tip by " .  $sender->getName() . "  -> ".$player->getName." ] " . implode(" ", $args));
			$player->sendMessage(TextFormat::YELLOW . "[Tip by " . ($sender instanceof Player ? $sender->getDisplayName() : $sender->getName()) . " -> you] " . implode(" ", $args));
		}else{
			$sender->sendMessage("Â§cUsage: /tipgive <Player> <Tip>");
		}
                return true;
                case "hug":
                $name = \strtolower(\array_shift($args));

		$player = $sender->getServer()->getPlayer($name);

                if($player === $sender){
			$sender->sendMessage("You can't hug yourself!");
			return \true;
		}
		
                if($player instanceof Player){
			$sender->sendMessage(TextFormat::RED . "<3 Du umarmst " . $player->getDisplayName() . " <3");
			$player->sendMessage(TextFormat::RED . "<3 " . ($sender instanceof Player ? $sender->getDisplayName() : $sender->getName()) . " umarmt dich <3 ");
		}else{
			$sender->sendMessage("Â§c /hug <Spieler>");
		}
                return true;
                case "fakeop":
                $name = \strtolower(\array_shift($args));

		$player = $sender->getServer()->getPlayer($name);

                if($player === $sender){
			$sender->sendMessage("You can't fake op yourself!");
			return \true;
		}
		
                if($player instanceof Player){
			$sender->sendMessage(TextFormat::GREEN . "Spieler FakeOPPED: " . TextFormat::YELLOW .  $player->getDisplayName());
			$player->sendMessage(TextFormat::GRAY . "You are now op!");
		}else{
			$sender->sendMessage("§cUsage: /opfake <Player>");
		}
                return true;
                case "deopfake":
                $name = \strtolower(\array_shift($args));

		$player = $sender->getServer()->getPlayer($name);

                if($player === $sender){
			$sender->sendMessage("You can't fake deop yourself!");
			return \true;
		}
		
                if($player instanceof Player){
			$sender->sendMessage(TextFormat::GREEN . "Spieler fakeDEOPPED: " . TextFormat::YELLOW .  $player->getDisplayName());
			$player->sendMessage(TextFormat::GRAY . "You are no longer op!");
		}else{
			$sender->sendMessage("§cUsage: /deopfake <Player>");
		}
                return true;
                case "setzenick":
                 if (!($sender instanceof Player)){ 
                $sender->sendMessage(TextFormat::GREEN . "This command is only avaible In-Game!");
                    return true;
                }
                $sender->sendMessage(TextFormat::GREEN . "NickName gesetzt.");
                $sender->setDisplayName(implode(" ", $args));
                          return true;
                         case "delnick":
                 if (!($sender instanceof Player)){ 
                $sender->sendMessage(TextFormat::GREEN . "This command is only avaible In-Game!");
                    return true;
                } 

if(!(isset($args[0]))){
                    
                
                $sender->sendMessage($this->prefix."Dein NickName wurde entfernt!");

                $sender->setDisplayName($sender->getName());
}
                          return true;
            case "sayas":
                $name = \strtolower(\array_shift($args));
                
            $sender->sendMessage(TextFormat::GREEN . "Sendete Nachricht als " .  $name);
            $sender->getServer()->broadcastMessage("<" . $name . "> " . implode(" ", $args));
        
            return true;
            case "spam":
                $sender->getServer()->broadcastMessage(TextFormat::AQUA . "SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM");
                $sender->getServer()->broadcastMessage(TextFormat::AQUA . "SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM");
                $sender->getServer()->broadcastMessage(TextFormat::AQUA . "SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM");
                $sender->getServer()->broadcastMessage(TextFormat::AQUA . "SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM");
                $sender->getServer()->broadcastMessage(TextFormat::AQUA . "SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM");
                $sender->getServer()->broadcastMessage(TextFormat::AQUA . "SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM");
                $sender->getServer()->broadcastMessage(TextFormat::AQUA . "SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM");
                $sender->getServer()->broadcastMessage(TextFormat::AQUA . "SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM");      
                      $sender->getServer()->broadcastMessage(TextFormat::AQUA . "SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM");
                $sender->getServer()->broadcastMessage(TextFormat::AQUA . "SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM");
                $sender->getServer()->broadcastMessage(TextFormat::AQUA . "SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM");
                $sender->getServer()->broadcastMessage(TextFormat::AQUA . "SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM");
                $sender->getServer()->broadcastMessage(TextFormat::AQUA . "SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM");
                $sender->getServer()->broadcastMessage(TextFormat::AQUA . "SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM");
                $sender->getServer()->broadcastMessage(TextFormat::AQUA . "SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM");
                $sender->getServer()->broadcastMessage(TextFormat::AQUA . "SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM"); 
                      $sender->getServer()->broadcastMessage(TextFormat::AQUA . "SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM");
                $sender->getServer()->broadcastMessage(TextFormat::AQUA . "SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM");
                $sender->getServer()->broadcastMessage(TextFormat::AQUA . "SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM");
                $sender->getServer()->broadcastMessage(TextFormat::AQUA . "SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM");
                $sender->getServer()->broadcastMessage(TextFormat::AQUA . "SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM");
                $sender->getServer()->broadcastMessage(TextFormat::AQUA . "SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM");
                $sender->getServer()->broadcastMessage(TextFormat::AQUA . "SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM");
                $sender->getServer()->broadcastMessage(TextFormat::AQUA . "SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM"); 
           return true;
           case "leeren":
if(!(isset($args[0]))){
                    $sender->sendMessage($this->prefix."/leeren <Reason>");
return true;
                }
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
                $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
                $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
                $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
                $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
                $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
               $sender->getServer()->broadcastMessage(" ");
              
               $sender->getServer()->broadcastMessage($this->prefix."Chat wurde geleert: ".$sender->getName().TextFormat::RED." Grund: ".implode(" ", $args));
                       
            return true;
           case "spamsay":
               if(!(isset($args[0]))){
                    $sender->sendMessage(TextFormat::RED."Usage: /spamsay <Message>");
                    return true;
               }
               $sender->getServer()->broadcastMessage(implode(" ", $args));
               $sender->getServer()->broadcastMessage(implode(" ", $args));
               $sender->getServer()->broadcastMessage(implode(" ", $args));
               $sender->getServer()->broadcastMessage(implode(" ", $args));
               $sender->getServer()->broadcastMessage(implode(" ", $args));
               $sender->getServer()->broadcastMessage(implode(" ", $args));
               $sender->getServer()->broadcastMessage(implode(" ", $args));
               $sender->getServer()->broadcastMessage(implode(" ", $args));
               $sender->getServer()->broadcastMessage(implode(" ", $args));
               $sender->getServer()->broadcastMessage(implode(" ", $args));
               $sender->getServer()->broadcastMessage(implode(" ", $args));
               $sender->getServer()->broadcastMessage(implode(" ", $args));
               $sender->getServer()->broadcastMessage(implode(" ", $args));
               $sender->getServer()->broadcastMessage(implode(" ", $args));
               $sender->getServer()->broadcastMessage(implode(" ", $args));
               $sender->getServer()->broadcastMessage(implode(" ", $args));
               $sender->getServer()->broadcastMessage(implode(" ", $args));
               $sender->getServer()->broadcastMessage(implode(" ", $args));
               $sender->getServer()->broadcastMessage(implode(" ", $args));
               $sender->getServer()->broadcastMessage(implode(" ", $args));
               $sender->getServer()->broadcastMessage(implode(" ", $args));
               $sender->getServer()->broadcastMessage(implode(" ", $args));
               $sender->getServer()->broadcastMessage(implode(" ", $args));
               $sender->getServer()->broadcastMessage(implode(" ", $args));
               $sender->sendMessage(TextFormat::GREEN . "Nachricht gespammtxD");
                       
           return true;
           case "spammsg":
                $name = \strtolower(\array_shift($args));

		$player = $sender->getServer()->getPlayer($name);
    if (!($sender instanceof Player)){ 
                $sender->sendMessage(TextFormat::GREEN . "This command is only avaible In-Game!");
                    return true;
                }
                if($player === $sender){
			$sender->sendMessage(TextFormat::Red . "You can't send a spammed Message to yourself!");
			return \true;
		}

		if($player instanceof Player){
			$player->sendMessage(TextFormat::YELLOW . "[" . ($sender instanceof Player ? $sender->getDisplayName() : $sender->getName()) . " -> ".$player->getName()."] " . implode(" ", $args));
                        $player->sendMessage(TextFormat::YELLOW . "[" . ($sender instanceof Player ? $sender->getDisplayName() : $sender->getName()) . " -> ".$player->getName()."] " . implode(" ", $args));
                        $player->sendMessage(TextFormat::YELLOW . "[" . ($sender instanceof Player ? $sender->getDisplayName() : $sender->getName()) . " -> ".$player->getName()."] " . implode(" ", $args));
                        $player->sendMessage(TextFormat::YELLOW . "[" . ($sender instanceof Player ? $sender->getDisplayName() : $sender->getName()) . " -> ".$player->getName()."] " . implode(" ", $args));
                        $player->sendMessage(TextFormat::YELLOW . "[" . ($sender instanceof Player ? $sender->getDisplayName() : $sender->getName()) . " -> ".$player->getName()."] " . implode(" ", $args));
                        $player->sendMessage(TextFormat::YELLOW . "[" . ($sender instanceof Player ? $sender->getDisplayName() : $sender->getName()) . " -> ".$player->getName()."] " . implode(" ", $args));
                        $player->sendMessage(TextFormat::YELLOW . "[" . ($sender instanceof Player ? $sender->getDisplayName() : $sender->getName()) . " -> ".$player->getName()."] " . implode(" ", $args));
                        $player->sendMessage(TextFormat::YELLOW . "[" . ($sender instanceof Player ? $sender->getDisplayName() : $sender->getName()) . " -> ".$player->getName()."] " . implode(" ", $args));
                        $player->sendMessage(TextFormat::YELLOW . "[" . ($sender instanceof Player ? $sender->getDisplayName() : $sender->getName()) . " -> ".$player->getName()."] " . implode(" ", $args));
                        $sender->sendMessage(TextFormat::GREEN . "Sucessfully spammed the Message to the Player " . TextFormat::YELLOW . $player->getName());
		}else{
			$sender->sendMessage(TextFormat::YELLOW . "Player not found!");
		}

		return true;
                case "hilfe":
                 if (!($sender instanceof Player)){ 
                $sender->sendMessage(TextFormat::GREEN . "This command is only avaible In-Game!");
                    return true;
                }
                $sender->sendMessage(TextFormat::GREEN . "Tippe /erledigt wenn du keine Hilfe mehr brauchst.");
                $sender->setDisplayName(TextFormat::RED . "[BrauchtHilfe] ".$sender->getDisplayName());
                          return true;  
            case "erledigt":
                 if (!($sender instanceof Player)){ 
                $sender->sendMessage(TextFormat::GREEN . "This command is only avaible In-Game!");
                    return true;
                }
                $sender->setDisplayName(str_replace(TextFormat::RED . "[BrauchtHilfe]", "", $sender->getDisplayName()));
                $sender->sendMessage(TextFormat::GREEN . "Tippe /hilfe wenn du wieder Hilfe brauchst.");
                return true;

            case "checkop":
             $name = \strtolower(\array_shift($args));

                    $player = $sender->getServer()->getPlayer($name);
		
                    if($player instanceof Player){
                if($player->isOp()){
		$sender->sendMessage(TextFormat::GREEN . "[Dreambuild] Spieler " . $player->getDisplayName() . " ist OP");

		return true;
                } else {
                    $sender->sendMessage(TextFormat::GREEN . "[Dreambuild] Spieler " . $player->getDisplayName() . " ist kein OP");
                    return true;
                }
                    } else {
                        $sender->sendMessage(TextFormat::RED . "Spieler nicht online!");
                        return true;
                    }
  
	
            case "melden/meld":
		 $name = \strtolower(\array_shift($args));

                    $player = $sender->getServer()->getPlayer($name);
                if(!(isset($args[0]))){
                    $sender->sendMessage(TextFormat::RED."/melden <Player> <Reason>");
                    return true;
              }
              if (!($sender instanceof Player)){ 
                $sender->sendMessage("Â§cThis Command in only avaible In-Game");
                    return true;
                }
		if(count($args) < 1){                   
				foreach($this->getServer()->getOnlinePlayers() as $p){
					if($p->isOnline() && $p->isOp()){
						if($player instanceof Player){
                                            $p->sendMessage(TextFormat::DARK_RED."[Dreambuild] ".TextFormat::AQUA."Spieler ".$sender->getName()." meldet ".TextFormat::RED.$player->getDisplayName().TextFormat::AQUA." wegen ".TextFormat::DARK_RED.implode("", $args));
						
						$sender->sendMessage(TextFormat::DARK_RED."[Dreambuild] ".TextFormat::AQUA."Meldung gesendet!");
						return true;
					}else{
						$sender->sendMessage(TextFormat::DARK_RED."[Dreambuild] ".TextFormat::AQUA."No OP's are online.");
						return true;
                                        }
                                        }else{ 
                                            $sender->sendMessage(TextFormat::RED."Spieler nicht online!");
					}
				}
		 	
			}else if($sender->hasPermission("chattoolspro.report")){
                             
				foreach($this->getServer()->getOnlinePlayers() as $p){
					if($p->isOnline() && $p->isOp()){
                                            if($player instanceof Player){
							$p->sendMessage(TextFormat::DARK_RED."[Dreambuild] ".TextFormat::AQUA."Spieler ".$sender->getName()." gemeldet ".TextFormat::RED.$player->getDisplayName().TextFormat::AQUA." for ".TextFormat::DARK_RED.implode("", $args));
                                                        
							$sender->sendMessage(TextFormat::DARK_RED."[Dreambuild] ".TextFormat::YELLOW."Meldung gesendet");
							return true;
					}else{
						$sender->sendMessage(TextFormat::DARK_RED."[Dreambuild] ".TextFormat::AQUA."Dieser Spieler ist nicht online.");
						return true;
					}
                                        }else{ 
                                            $sender->sendMessage(TextFormat::RED."Spieler nicht online!");
					}
				}
			}else{
				$sender->sendMessage(TextFormat::RED."No Permission!");
				return true;
			}
               case "lockchat":
               	        if(!(isset($args[0]))){
                $sender->sendMessage(TextFormat::GREEN . "Bitte nutze! /lockchat <lock/unlock>");
                    return true;
                }
            if($args[0] == "lock"){
            $sender->sendMessage($this->prefix."Chat gesperrt!");
            $sender->getServer()->broadcastMessage($this->prefix."Chat wurde gesperrt von ".$sender->getName());
	   $this->disableChat = true;
                return true;
            }
            elseif($args[0] == "unlock"){
           $sender->sendMessage($this->prefix."Chat entsperrt!");
           $sender->getServer()->broadcastMessage($this->prefix."Chat wurde entsperrt von ".$sender->getName());
           $this->disableChat = false;
                return true;
            }
                
        
            case "ops":
                
			$ops = "";
			if($sender->hasPermission("chattoolspro.ops")){
				foreach($this->getServer()->getOnlinePlayers() as $p){
					if($p->isOnline() && $p->isOp()){
						$ops = $p->getName()." , ";
						$sender->sendMessage(TextFormat::AQUA."OPs online:\n".substr($ops, 0, -2));		
						return true;
					}else{
						$sender->sendMessage(TextFormat::AQUA."OPs online: \n");
						return true;
					}
				}
			}else{
				$sender->sendMessage(TextFormat::RED."No Permission!");
				return true;
			}
		}
	}
	
	public function getMsg($words){
		return implode(" ",$words);
	
    }
    
}
    /*
     *                         Coded by paetti
     */
             
