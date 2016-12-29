<?php
namespace ServerLoveMCPE;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{
    public function onLoad(){
        $this->getLogger()->info(TextFormat::LIGHT_PURPLE . "Liebe loading.");
    }
    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        @mkdir($this->getDataFolder());
        $this->nolove = new Config($this->getDataFolder()."nolove.yml", Config::YAML);
        $this->saveDefaultConfig();
        $this->getLogger()->info(TextFormat::LIGHT_PURPLE . "[<3] Yayyy, Liebe ist fertig geladen! Vesion: ".$this->getDescription()->getVersion());
    }
    
    public function onDisable(){
        $this->nolove->save();
        $this->getLogger()->info(TextFormat::LIGHT_PURPLE . "[<3] Du hast dich vom Server scheiden lassen!.");
    }
    
    public function onCommand(CommandSender $sender, Command $command, $label, array $args){
        switch($command->getName()){
/**
*
*                                         Liebe xD
*
**/
            case "liebe":
                if(!(isset($args[0]))){
                    return false;
                }
                if (!($sender instanceof Player)){ 
                $sender->sendMessage("§5[<3] Nutze dieses Kommando inGame! Sorry.");
                    return true;
                }
                
                $loved = array_shift($args);
                if($this->nolove->exists(strtolower($loved))){
                    $sender->sendMessage("§c§7[<3]§r§7 Entschuldigung, " . $loved . "§7 sucht momentan keinen Partner.");
                    return true;
                }else{
                    $lovedPlayer = $this->getServer()->getPlayer($loved);
                    if($lovedPlayer !== null and $lovedPlayer->isOnline()){
                        if($lovedPlayer == $sender){
                            /*This is where the loop for the #ForeverAlone goes to - by ratchetgame98 - Original ServerLove ( MCPC ) owner!*/
                            $sender->sendMessage("§c§l[<3]§r§7Du kannst dich nicht selbst lieben xD :P");
                            $this->getServer()->broadcastMessage("§c§l[<3]§r §4" . $sender->getName() . " §7hat versucht sich selbst zu lieben! :P §6#ForeverAlone");
                            
                            
                        }else{
                            $lovedPlayer->sendMessage("§c§l[<3] §a" . $sender->getName() . "§7liebt dich!");
                            if(isset($args[0])){
                                $lovedPlayer->sendMessage("Grund: " . implode(" ", $args));
                            }
                            $sender->sendMessage("§c§l[<3] §r§7Du liebst also §a" . $loved . "?§7 Awww du Schnucki xD");
                            $this->getServer()->broadcastMessage("§c" . $loved . " §4+ " . $sender->getName() . "§c = ♥");
                            
                            return true;
                        }
                    }else{
                        $sender->sendMessage("§c§l[<3] §§c" . $loved . "§7 kannst du nicht lieben. §7 Der Schlingel, §a" . $loved . "§7 ist nicht Online.");
                        return true;
                    }
                }
/**
*
*                                 BREAKUP  xD
*
**/
                break;
            case "schluss":
                if(!(isset($args[0]))){
                    return false;
                }
                if (!($sender instanceof Player)){ 
                $sender->sendMessage("§c§l[<3]§r§7 Bitte InGame nutzen!!!");
                    return true;
                }
                $loved = array_shift($args);
                    $lovedPlayer = $this->getServer()->getPlayer($loved);
                    if($lovedPlayer !== null and $lovedPlayer->isOnline()){
                        $lovedPlayer->sendMessage("§c§l[<3]§r§c " . $sender->getName() ." §7liebt dich nicht mehr!");
                        if(isset($args[0])){
                            $lovedPlayer->sendMessage("Grund: " . implode(" ", $args));
                        }
                        $sender->sendMessage("§c§7[<3]§r§7 Du hast mit §a" . $loved . "§7 Schluss gemacht!.");
                        $this->getServer()->broadcastMessage("§c§l[<3]§r§c " . $sender->getName() . " §7hat mit §c" . $loved . "§7 Schluss gemacht.");
                        
                        return true;
                    }else{
                        $sender->sendMessage($loved . "§l§c[<3] §r§7Ist nicht Online.");
                        return true;
                    }
/**
*
*                                 Knutschen NEU
*
**/
            break;
            case "kuss":
                if(!(isset($args[0]))){
                    return false;
                }
                if (!($sender instanceof Player)){ 
                $sender->sendMessage("§5[<3] Nutze dieses Kommando inGame! Sorry.");
                    return true;
                }
                
                $loved = array_shift($args);
                if($this->nolove->exists(strtolower($loved))){
                    $sender->sendMessage("§c§l[<3] §r§7Entschuldigung, §c" . $loved . "§7 will das nicht.");
                    return true;
                }else{
                    $lovedPlayer = $this->getServer()->getPlayer($loved);
                    if($lovedPlayer !== null and $lovedPlayer->isOnline()){
                        if($lovedPlayer == $sender){
                            /*This is where the loop for the #ForeverAlone goes to - by ratchetgame98 - Original ServerLove ( MCPC ) owner!*/
                            $sender->sendMessage("§l§c[<3]§r§7 Du kannst dir keinen Kuss geben xD :P");
                            $this->getServer()->broadcastMessage("§c§l[<3]§r§f" . $sender->getName() . "§5 §7hat versucht sich selbst zu küssen! :P §6#ForeverAlone");
                            
                            
                        }else{
                            $lovedPlayer->sendMessage("§c§l[<3]§r §a" . $sender->getName() . " §7hat dich AUF DEN MUND geküsst! §cAyAyAy xD");
                            if(isset($args[0])){
                                $lovedPlayer->sendMessage("Grund: " . implode(" ", $args));
                            }
                            $sender->sendMessage("§l§c[<3] §r§7Du hast §a" . $loved . " §7geküsst?§a Awww du Schnucki xD");
                            $this->getServer()->broadcastMessage("§c♥ " . $loved . " §cwurde von " . $sender->getName() . " geküsst §c♥");
                            
                            return true;
                        }
                    }else{
                        $sender->sendMessage("§c§l[<3] §r§f" . $loved . "§7 kannst du nicht küssen. §7 Der Schlingel, §f" . $loved . "§7 ist nicht§c Online§f.");
                        return true;
                    }
                }
/**
*
*                                      NOLOVE
*
**/
            break;
            case "nolove":
                if(!(isset($args[0]))){
                    return false;
                }
                if (!($sender instanceof Player)){ 
                $sender->sendMessage("§5[<3] YOU MUST USE THIS COMMAND IN GAME. SORRY.");
                    return true;
                }
                if($args[0] == "an"){
                    $this->nolove->set(strtolower($sender->getName()));
                    $sender->sendMessage("§l§c[<3] §7Man kann dich nun nicht mehr lieben :( §c#ForEverAlone");
                    return true;
                }elseif($args[0] == "aus"){
                    $this->nolove->remove(strtolower($sender->getName()));
                    $sender->sendMessage("§l§c[<3] §7Du kannst nun wieder geliebt werden! §c#PaarungsZeit xD");
                    return true;
                }else{
                    return false;
                }
/**
*
*                                   SERVERLOVE
*
**/
            break;
            case "serverliebe":
                $sender->sendMessage("§c§l[<3][DreamLiebe] Dreambuild.de ServerLiebe ");
                $sender->sendMessage("§d[<3][DreamLiebe] /liebe <Spieler>");
                $sender->sendMessage("§d[<3][DreamLiebe] /schluss <Spieler>");
                $sender->sendMessage("§d[<3][DreamLiebe] /nolove <an / aus> ");
                $sender->sendMessage("§5[<3][DreamLiebe] /kuss <Spieler>");
                $sender->sendMessage("§l§c[<3][DreamLiebe] #PaarungsZeit!");
                return true;
            break;
        default:
            return false;
        }
    return false;
    }
}
