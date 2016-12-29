<?php
namespace taptodo;

use pocketmine\level\Position;
use pocketmine\Player;

class Block{
    /** @var  Command[] */
    private $commands;
    /** @var  Position */
    private $position;
    /** @var mixed */
    private $name;
    /** @var TapToDo  */
    private $plugin;
    public $id;
    public function __construct(Position $position, array $commands, TapToDo $main, $id, $name = false){
        $this->position = $position;
        $this->commands = [];
        $this->plugin = $main;
        $this->name = $name;
        $this->id = $id;

        $this->addCommands($commands);
    }
    public function addCommands($cmds){
        if(!is_array($cmds)){
            $cmds = [$cmds];
        }
        foreach ($cmds as $c) {
            $this->commands[] = new Command($c, $this->plugin);
        }
        $this->plugin->saveBlock($this);
    }
    public function addCommand($cmd){
        $this->addCommands([$cmd]);
    }
    public function deleteCommand($cmd){
        $ret = false;
        for($i = count($this->commands); $i >= 0; $i--){
            if($this->commands[$i]->getOriginalCommand() === $cmd || $this->commands[$i]->getCompiledCommand() === $cmd){
                unset($this->commands[$i]);
                $ret = true;
            }
        }
        if($ret){
            $this->plugin->saveBlock($this);
        }
        return $ret;
    }
    public function executeCommands(Player $player){
        foreach($this->commands as $command){
            $command->execute($player);
        }
    }
    public function setName($name){
        $this->name = $name;
    }
    public function getCommands(){
        $out = [];
        foreach($this->commands as $command) $out[] = $command->getOriginalCommand();
        return $out;
    }
    public function getName(){
        return $this->name;
    }

    /**
     * @return Position
     * @deprecated
     */
    public function getPos(){
        return $this->position;
    }
    public function getPosition(){
        return $this->position;
    }
    public function toArray(){
        $arr = [
            'x' => $this->getPosition()->getX(),
            'y' => $this->getPosition()->getY(),
            'z' => $this->getPosition()->getZ(),
            'level' => $this->getPosition()->getLevel()->getName(),
            'commands' => $this->getCommands()
        ];
        if($this->name !== false) $arr["name"] = $this->name;
        return $arr;
    }
}
