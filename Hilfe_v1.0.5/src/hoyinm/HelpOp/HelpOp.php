<?php

namespace hoyinm\HelpOp;

use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\Player;
use pocketmine\permission\ServerOperator;
use pocketmine\utils\TextFormat;

class HelpOp extends PluginBase{
	
	public function onEnable(){
		$this->getLogger()->info(TextFormat::GREEN."Gelaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaden!");
	}
	
	public function onCommand(CommandSender $issuer, Command $cmd, $label, array $args){
		if($cmd->getName() === "helpop"){
			if(count($args) < 1){
				foreach($this->getServer()->getOnlinePlayers() as $p){
					if($p->isOnline() && $p->isOp()){
						$p->sendMessage(TextFormat::DARK_RED."[Hilfe] ".TextFormat::AQUA.$issuer->getName()." braucht Hilfe!");
						$p->sendMessage(TextFormat::GREEN."Hilfe wird umgehend erwartet.");
						$issuer->sendMessage(TextFormat::DARK_RED."[Hilfe] ".TextFormat::AQUA."Wir helfen dir sobald es geht!");
						return true;
					}else{
						$issuer->sendMessage(TextFormat::DARK_RED."[Hilfe] ".TextFormat::RED."Kein Helfer online!");
						return true;
					}
				}
				return true;
			}else if($issuer->hasPermission("helpop.command")){
				foreach($this->getServer()->getOnlinePlayers() as $p){
					if($p->isOnline() && $p->isOp()){
							$p->sendMessage(TextFormat::DARK_RED."[Hilfe] ".TextFormat::AQUA.$issuer->getName()." braucht Hilfe!");
							$p->sendMessage(TextFormat::DARK_RED."[Hilfe] ".TextFormat::YELLOW."Nachricht von ".$issuer->getName().": ".TextFormat::WHITE.$this->getMsg($args));
							$issuer->sendMessage(TextFormat::DARK_RED."[Hilfe] ".TextFormat::WHITE."Nachricht gesendet!");
							return true;
					}else{
						$issuer->sendMessage(TextFormat::DARK_RED."[Hilfe] ".TextFormat::RED."Keine Helfer online");
						return true;
					}
				}
			}else{
				$issuer->sendMessage(TextFormat::RED."Fehlende Berechtigungen!");
				return true;
			}
		}
		if($cmd->getName() === "checkop"){
			$ops = "";
			if($issuer->hasPermission("helpop.command")){
				foreach($this->getServer()->getOnlinePlayers() as $p){
					if($p->isOnline() && $p->isOp()){
						$ops = $p->getName()." , ";
						$issuer->sendMessage(TextFormat::DARK_RED."[Hilfe]".TextFormat::WHITE." Helfer Online:\n".substr($ops, 0, -2));		
						return true;
					}else{
						$issuer->sendMessage(TextFormat::DARK_RED."[Hilfe] ".TextFormat::WHITE."Helfer Online: \n");
						return true;
					}
				}
			}else{
				$issuer->sendMessage(TextFormat::RED."Fehlende Berechtigungen!");
				return true;
			}
		}
	}
	
	public function getMsg($words){
		return implode(" ",$words);
	}
}
?>