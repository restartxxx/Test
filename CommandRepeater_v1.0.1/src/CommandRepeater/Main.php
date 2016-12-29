<?php
namespace CommandRepeater;

use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase{
    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents(new EventHandler($this), $this);
        $this->getServer()->getCommandMap()->register("commandrepeater", new CRCommand($this));
        foreach($this->getServer()->getOnlinePlayers() as $p){
            $this->setLastCommand($p, false);
        }
        $this->commands["CONSOLE"] = false;
        $this->commands["Rcon"] = false;
    }

    /**           _____ _____
     *      /\   |  __ |_   _|
     *     /  \  | |__) || |
     *    / /\ \ |  ___/ | |
     *   / ____ \| |    _| |_
     *  /_/    \_|_|   |_____|
     */

    private $commands = [];

    /**
     * @param CommandSender $sender
     * @return bool|string
     */
    public function getLastCommand(CommandSender $sender){
        return $this->commands[$sender->getName()];
    }

    /**
     * @param CommandSender $sender
     * @param $command
     */
    public function setLastCommand(CommandSender $sender, $command){
        $cmd = explode(" ", $command);
        if($command !== "repeat" && $command !== "repeatcommand" && $command !== "rcmd" && $this->getServer()->getCommandMap()->getCommand($cmd[0]) !== null){
                $this->commands[$sender->getName()] = $command;
        }
    }

    /**
     * @param CommandSender $sender
     */
    public function removeCommandSender(CommandSender $sender){
        unset($this->commands[$sender->getName()]);
    }
} 