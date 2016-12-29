<?php
namespace CommandRepeater;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class CRCommand extends Command implements PluginIdentifiableCommand{
    /** @var  Main */
    private $plugin;

    public function __construct(Main $plugin){
        parent::__construct("repeat", "Repeat the last command you made", "/repeat", ["repeatcommand", "rcmd"]);
        $this->plugin = $plugin;
        $this->setPermission("commandrepeater");
    }

    public final function getPlugin(){
        return $this->plugin;
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(count($args) !== 0){
            $sender->sendMessage(TextFormat::RED . ($sender instanceof Player ? "" : "Usage: ") . $this->getUsage());
            return false;
        }
        if(!$this->getPlugin()->getLastCommand($sender)){
            $sender->sendMessage(TextFormat::RED . "[Error] You don't have any previous command");
            return false;
        }
        $this->getPlugin()->getServer()->dispatchCommand($sender, $this->getPlugin()->getLastCommand($sender));
        return true;
    }
} 