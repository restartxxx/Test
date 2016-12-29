<?php
namespace buycraft\api;
use buycraft\BuyCraft;
use buycraft\util\DebugUtils;
use buycraft\util\HTTPUtils;
use pocketmine\Player;
use pocketmine\scheduler\PluginTask;
use pocketmine\utils\Utils;

/**
 * Class ApiTask
 * @package buycraft\api
 */
abstract class ApiTask extends PluginTask{
    /**
     * @param BuyCraft $main
     * @param Player $manual
     * @param array $data
     */
    public function __construct(BuyCraft $main, $data = []){
        parent::__construct($main);
        DebugUtils::construct($this);
        if($main->getConfig()->get("https")){
            $this->apiUrl = "https://api.buycraft.net/v4";
        }
        else{
            $this->apiUrl = "http://api.buycraft.net/v4";
        }
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getData(){
        return $this->data;
    }
    /**
     * @return mixed
     */
    public function send(){
        if($this->getOwner()->isAuthenticated()){
            $this->data["secret"] = $this->getOwner()->getConfig()->get('secret');
            $this->data["playersOnline"] = count($this->getOwner()->getServer()->getOnlinePlayers());
            $url = $this->apiUrl . "?" . http_build_query($this->getData());
            DebugUtils::requestOut($this, $url);
            return json_decode(HTTPUtils::getURL($url), true);
        }
        else{
            return false;
        }
    }
    /**
     * @return \pocketmine\scheduler\ServerScheduler
     */
    public function getScheduler(){
        return $this->getOwner()->getServer()->getScheduler();
    }

    /**
     * @return mixed
     */
    abstract public function call();
}