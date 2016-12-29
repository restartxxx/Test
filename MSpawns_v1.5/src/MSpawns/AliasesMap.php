<?php

/*
 * MSpawns (v1.5) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: http://www.evolsoft.tk
 * Date: 06/06/2015 01:31 PM (UTC)
 * Copyright & License: (C) 2014-2015 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/MSpawns/blob/master/LICENSE)
 */

namespace MSpawns;

use pocketmine\command\Command;
use pocketmine\command\PluginIdentifiableCommand;

abstract class AliasesMap extends Command implements PluginIdentifiableCommand {

	public function __construct($name, Main $plugin){
		parent::__construct($name);
		$this->plugin = $plugin;
	}
		
	public function getPlugin(){
		return $this->plugin;
	}
}
    ?>
