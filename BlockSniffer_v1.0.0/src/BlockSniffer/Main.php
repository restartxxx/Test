<?php


namespace BlockSniffer;


use pocketmine\Server;
use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\Player;
use pocketmine\IPlayer;
use pocketmine\utils\TextFormat;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\math\Vector3;


class Main extends PluginBase  implements Listener {
    
    
    public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		if(!is_dir($this->getDataFolder())){
			@mkdir($this->getDataFolder());
		}
		$this->config = new Config($this->getDataFolder()."config.yml", CONFIG::YAML, array(
			"Enabled" => true,
			"# Players to not log" => " Format: NAME: true",
			"DRedDog" => true,
			"DRedDogPE" => true,
			"# Blocks to ignore" => " Format: BLOCK: true",
			"Dirt" => true,
			"Bedrock" => true,
			"# When to delete old data(days)" => " Default: 7",
			"DaysOld" => 7,
		));
		$this->getLogger()->info( TextFormat::GREEN . "BlockSniffer - Enabled!" );
		$this->db = new \SQLite3($this->getDataFolder() . "Log.db");
		$this->db->exec("CREATE TABLE IF NOT EXISTS players (name TEXT, block TEXT, data TEXT, action TEXT, date TEXT, world TEXT);");
		$this->killOld();
	}
	
	public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
		if($sender instanceof Player) {
                    $player = $sender->getPlayer()->getName();
                    $auth = $this->getConfig()->get($player);
                    if(strtolower($command->getName('bs'))) {
                        if(empty($args)) {
                            $sender->sendMessage("~ Usage:\n/bs here <world>");
                            return true;
                        }
                        if(strtolower($args[0]) == "here") {#/bs here
							if(empty($args[1])) {
								$name = $sender->getFloorX() . "-" . $sender->getFloorY() . "-" . $sender->getFloorZ();
								$world = $sender->getLevel()->getName();
								if(!$this->dataExistsWorld($name, $world)) {
									$sender->sendMessage("~ Coords have no data!");
									return true;
								}
								$this->getXYZFormatMessage($name, $world, $sender);
								return true;
							} else{
								$name = $sender->getFloorX() . "-" . $sender->getFloorY() . "-" . $sender->getFloorZ();
								$world = $args[1];
								if(!$this->dataExistsWorld($name, $world)) {
									$sender->sendMessage("~ Coords have no data!");
									return true;
								}
								$this->getXYZFormatMessage($name, $world, $sender);
								return true;
							}
                        }
                        if($args[0] == "about") {
                            $this->getServer()->broadcastMessage("§aThis server uses the §bBlockSniffer§a plugin created by §cDRedDogPE§a.");
							$sender->sendMessage("§aView more plugins by §cDRedDogPE§a at §9http://DRedDogPE.github.io");
                            return true;
                        }
                        if(!$args[0] == "here") {
                            $sender->sendMessage("~ Usage:\n/bs here <world>");
                            return true;
                        }
                    }
                }else{
                    if(strtolower($command->getName('bl'))) {
                        if(empty($args)) {
                            $sender->sendMessage("~ Usage:\n/bs xyz <X-Y-Z> <world>");
                            return true;
                        }
                        $sniffed = $this->getConfig()->get($args[0]);
                        if($sniffed) {
                            $sender->sendMessage("~ Player not being logged, case sensitive.");
                            return true;
                        }
                        if(strtolower($args[0]) == "xyz") {#/bs xyz <X-Y-Z> <world>
                            if(empty($args[1])) {
                                $sender->sendMessage("~ Usage:\n/bs xyz <X-Y-Z> <world>");
                                return true;
                            }
                            if(empty($args[2])) {
                                $sender->sendMessage("~ Usage:\n/bs xyz <X-Y-Z> <world>");
                                return true;
                            }
                            if(!$this->dataExistsWorld($args[1], $args[2])) {
                                $sender->sendMessage("~ Coords have no data!");
                                return true;
                            }
                            $name = $args[1];
    						$world = $args[2];
                            $this->getXYZFormat($name, $world);			#e.g /bs 5, 10, 22
                            
                            return true;
                        }
                    }
                }
	}//TEST
	public function onBreak(BlockBreakEvent $ev) {
            $player = $ev->getPlayer();
            $name = $player->getName();
            $sniffed = $this->getConfig()->get($name);
            $block = $ev->getBlock();
			if($this->getConfig()->get($block->getName())){
				return true;
			}
            $date = date("[m/d]");
            $pos = new Vector3($block->getX(),$block->getY(),$block->getZ());
            $format = $pos->getX() . "-" . $pos->getY() . "-" . $pos->getZ();
            $action = "BREAK";
            if(!$sniffed) {
			if($this->getConfig()->get("Enabled")) {
						$this->db->exec("CREATE TABLE IF NOT EXISTS players (name TEXT, block TEXT, data TEXT, action TEXT, date TEXT, world TEXT);");
						$sql = $this->db->prepare("INSERT OR REPLACE INTO players (name, block, data, action, date, world) VALUES (:name, :block, :data, :action, :date, :world);");
						$sql->bindValue(":name", $name);
						$sql->bindValue(":block", $block->getName());
						$sql->bindValue(":data", $format);
						$sql->bindValue(":action", $action);
						$sql->bindValue(":date", $date);
						$sql->bindValue(":world", $player->getLevel()->getName());
						$result = $sql->execute();
						return true;
			}
            }
        }
        public function onPlace(BlockPlaceEvent $ev) {
            $player = $ev->getPlayer();
            $name = $player->getName();
            $sniffed = $this->getConfig()->get($name);
            $block = $ev->getBlock();
            $date = date("[m/d]"); 
            $pos = new Vector3($block->getX(),$block->getY(),$block->getZ());
            $format = $pos->getX() . "-" . $pos->getY() . "-" . $pos->getZ();
            $action = "PLACE";
            if(!$sniffed) {
			if($this->getConfig()->get("Enabled")) {
						$this->db->exec("CREATE TABLE IF NOT EXISTS players (name TEXT, block TEXT, data TEXT, action TEXT, date TEXT, world TEXT);");
						$sql = $this->db->prepare("INSERT OR REPLACE INTO players (name, block, data, action, date, world) VALUES (:name, :block, :data, :action, :date, :world);");
						$sql->bindValue(":name", $name);
						$sql->bindValue(":block", $block->getName());
						$sql->bindValue(":data", $format);
						$sql->bindValue(":action", $action);
						$sql->bindValue(":date", $date);
						$sql->bindValue(":world", $player->getLevel()->getName());
						$result = $sql->execute();
						return true;
			}
			}
		}
		
        public function killOld() { #Kills data from $days days ago
			$days = $this->getConfig()->get("DaysOld");
			$oldDate = date("[m/d]", strtotime('-'.$days.' days'));
            $check = $this->db->query("SELECT date FROM players WHERE date='$oldDate';");
            if($check->fetchArray(SQLITE3_ASSOC) == 0){ #If there is no data from the date return false
                return false;
            }else{ #otherwise delete the data
				$this->getLogger()->info( TextFormat::GREEN . "Deleted Data from: " . $oldDate );
                $this->db->query("DELETE FROM players WHERE date='$oldDate';");
            }
		}
		
        public function getXYZFormat($data, $world) {
            $coords = $this->db->query("SELECT * FROM players WHERE data = '$data' AND world = '$world';");
            while($row = $coords->fetchArray(SQLITE3_ASSOC)) {
                $rowArray = $row["name"] . " | "  . $row["block"] . " | "  . $row["action"];
                $this->getLogger()->info( TextFormat::RED . "$rowArray" );
            }
		}
        public function getXYZFormatMessage($data, $world, $sender) {
            $sender->sendMessage( TextFormat::GREEN . "BlockLogger: ");
            $coords = $this->db->query("SELECT * FROM players WHERE data = '$data' AND world = '$world';");
            while($row = $coords->fetchArray(SQLITE3_ASSOC)) {
                $rowArray = TextFormat::GREEN . $row["name"] . TextFormat::GOLD . " | "  . TextFormat::AQUA . $row["block"] . TextFormat::GOLD . " | "  . TextFormat::RED . $row["action"];
                $sender->sendMessage(TextFormat::GREEN . "~ " . "$rowArray" . TextFormat::GREEN . "");
            }
		}
    
	#################################
    
        public function dataExists($player) {
            $check = $this->db->query("SELECT name FROM players WHERE name='$player';");
            if($check->fetchArray(SQLITE3_ASSOC) == 0){
                return false;
            }else{
                return true;
            }
        }
        public function dataExistsPlayer($player, $world) {
            $check = $this->db->query("SELECT name FROM players WHERE name='$player' AND world='$world';");
            if($check->fetchArray(SQLITE3_ASSOC) == 0){
                return false;
            }else{
                return true;
            }
        }
        public function dataExistsWorld($data, $world) {
            $check = $this->db->query("SELECT data FROM players WHERE data='$data' AND world='$world';");
            if($check->fetchArray(SQLITE3_ASSOC) == 0){
                return false;
            }else{
                return true;
            }
        }
}