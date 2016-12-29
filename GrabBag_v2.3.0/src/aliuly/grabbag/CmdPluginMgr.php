<?php
//= cmd:pluginmgr,Server_Management
//: manage plugins
//> usage: **pluginmgr** _<subcmd>_ _<plugin>_
//:
//: Manage plugins.
//:  The following sub-commands are available:
//> - **pluginmgr** **enable** _<plugin>_
//:     - Enable a disabled plugin.
//> - **pluginmgr** **disable** _<plugin>_
//:     - Disables an enabled plugin.
//> - **pluginmgr** **reload** _<plugin>_
//:     - Disables and enables a plugin.
//> - **pluginmgr** **info** _<plugin>_
//:     - Show plugin details
//> - **pluginmgr** **commands** _<plugin>_
//:     - Show commands registered by plugin
//> - **pluginmgr** **permissions** _<plugin>_
//:     - Show permissions registered by plugin
//> - **pluginmgr** **load** _<path>_
//:     - Load a plugin from file path (presumably outside the **plugin** folder.)
//> - **pluginmgr** **dumpmsg** _<plugin>_ _[lang]_
//:     - Dump messages.ini.
//> - **pluginmgr** **uninstall** _<plugin>_
//:     - Uninstall plugin.
//> - **pluginmgr** **feature** _<plugin>_ _[[-|+]feature]_
//:     - For plugins that have a _features_ table in **config.yml**
//:       this will let you change those settings.

namespace aliuly\grabbag;

use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\utils\TextFormat;

use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginManager;
use pocketmine\plugin\PluginDescription;
use pocketmine\utils\Config;

use aliuly\grabbag\common\BasicCli;
use aliuly\grabbag\common\mc;
use aliuly\grabbag\common\PermUtils;
use aliuly\grabbag\common\MPMU;
use aliuly\grabbag\common\FileUtils;

class CmdPluginMgr extends BasicCli implements CommandExecutor {
	private function findPlugin($path) {
		if (file_exists($path)) return $path;
		$srv = $this->owner->getServer();
		foreach ([$srv->getPluginPath(),$srv->getDataPath(),$srv->getFilePath()] as $d) {
			if (file_exists($d.'/'.$path)) return $d.'/'.$path;
		}
		return $path;
	}
	public function __construct($owner) {
		parent::__construct($owner);
		PermUtils::add($this->owner, "gb.cmd.pluginmgr", "Run-time management of plugins", "op");
		$this->enableCmd("pluginmgr",
							  ["description" => mc::_("manage plugins"),
								"usage" => mc::_("/pluginmgr <enable|disable|reload|info|commands|permissions|load|dumpmsg|uninstall|feature> <plugin>"),
								"aliases" => ["pm"],
								"permission" => "gb.cmd.pluginmgr"]);
	}
	public function onCommand(CommandSender $sender,Command $cmd,$label, array $args) {
		if ($cmd->getName() != "pluginmgr") return false;
		$pageNumber = $this->getPageNumber($args);
		if (count($args) < 2) return false;

		$scmd = strtolower(array_shift($args));
		$pname = array_shift($args);

		$mgr = $this->owner->getServer()->getPluginManager();
		if ($scmd == "load" || $scmd == "ld") {
			$pname = $this->findPlugin($pname);
			if (!file_exists($pname)) {
				$sender->sendMessage(TextFormat::RED.mc::_("%1%: Not found",$pname));
				return true;
			}
			$plugin = $mgr->loadPlugin($pname);
			if ($plugin === null) {
				$sender->sendMessage(TextFormat::RED.mc::_("Unable to load plugin from %1%",$pname));
				return true;
			}
			$sender->sendMessage(TextFormat::BLUE.mc::_("Loaded plugin %1%", $plugin->getDescription()->getFullName()));
			$mgr->enablePlugin($plugin);
			return true;
		}

		$plugin = $mgr->getPlugin($pname);
		if ($plugin === null) {
			$sender->sendMessage(TextFormat::RED.mc::_("Plugin %1% not found",
																	 $pname));
			return true;
		}

		switch($scmd) {
			case "ena":
			case "start":
			case "enable":
				if ($plugin->isEnabled()) {
					$sender->sendMessage(TextFormat::RED.
												mc::_("%1% is already enabled",$pname));
					break;
				}
				$mgr->enablePlugin($plugin);
				$sender->sendMessage(TextFormat::GREEN.
											mc::_("Plugin %1% enabled",$pname));
				break;
			case "disable":
			case "dis":
			case "stop":
				if (!$plugin->isEnabled()) {
					$sender->sendMessage(TextFormat::RED.
												mc::_("%1% is already disabled",$pname));
					break;
				}
				$mgr->disablePlugin($plugin);
				$sender->sendMessage(TextFormat::GREEN.
											mc::_("Plugin %1% disabled",$pname));

				break;
			case "reload":
			case "restart":
			case "reenable":
			case "re":
				if (!$plugin->isEnabled()) {
					$sender->sendMessage(TextFormat::RED.
												mc::_("%1% is not enabled",$pname));
					break;
				}
				$mgr->disablePlugin($plugin);
				$mgr->enablePlugin($plugin);
				$sender->sendMessage(TextFormat::GREEN.
											mc::_("Plugin %1% reloaded",$pname));
				break;
			case "info":
				return $this->cmdInfo($sender,$plugin,$pageNumber);
			case "cmds":
			case "com":
			case "command":
			case "commands":
				return $this->cmdCmds($sender,$plugin,$pageNumber);
			case "perms":
			case "permission":
			case "permissions":
				return $this->cmdPerms($sender,$plugin,$pageNumber);
			case "dumpmsg":
			case "dumpmsgs":
				return $this->cmdDumpMsgs($sender,$plugin, $args);
			case "uninstall":
				return $this->cmdRemove($sender,$plugin,$mgr);
			case "feature":
				return $this->cmdFeatures($sender,$plugin, $mgr, $args, $pageNumber);
			default:
				$sender->sendMessage(mc::_("Unknown sub-command %1%",$scmd));
				return false;
		}
		return true;
	}
	private function cmdFeatures(CommandSender $c,Plugin $plugin, $mgr, $args, $pageNumber) {
		//
		$cfgfile = $plugin->getDataFolder()."config.yml";
		if (!file_exists($cfgfile)) {
			$c->sendMessage(mc::_("%1%: Does not have config.yml", $plugin->getDescription()->getFullName()));
			return true;
		}
		$cfg = (new Config($cfgfile,Config::YAML,[]))->getAll();
		$section = "features";
		if (!isset($cfg[$section]) || !is_array($cfg[$section])) {
			$c->sendMessage(mc::_("%1%: Does not have compatible config.yml", $plugin->getDescription()->getFullName()));
			return true;
		}
		if (count($args) == 0) {
			$txt = [];
			$txt[] = mc::_("%1% Features", $plugin->getDescription()->getFullName());
			foreach ($cfg[$section] as $a => $b) {
				if (is_bool($b)) {
					$txt[] = TextFormat::AQUA.$a.TextFormat::WHITE.": ". ($b ? TextFormat::GREEN.mc::_("yes") : TextFormat::YELLOW. mc::_("no"));
					if (isset($cfg[$section]["# ".$b])) {
						$txt[] = TextFormat::BLUE."    ".$cfg[$section]["# ".$b];
					}
				}
			}
			return $this->paginateText($c,$pageNumber,$txt);
		}
		$bounce = false;
		foreach ($args as $i) {
			$v = true;
			if ($i{0} == "+") {
				$i = substr($i,1);
			} elseif ($i{0} == "-") {
				$v = false;
				$i = substr($i,1);
			}
			if (!isset($cfg[$section][$i]) || !is_bool($cfg[$section][$i])) {
				$c->sendMessage(mc::_("%1%: Does not support feature %2%", $plugin->getDescription()->getFullName(), $i));
				continue;
			}
			if ($cfg[$section][$i] === $v) continue;
			$cfg[$section][$i] = $v;
			if ($v) {
				$c->sendMessage(mc::_("Enabling %1%",$i));
			} else {
				$c->sendMessage(mc::_("Disabling %1%",$i));
			}
			$bounce = true;
		}
		if (!$bounce) {
			$c->sendMessage(mc::_("No changes"));
			return true;
		}
		$yaml = new Config($cfgfile,Config::YAML,[]);
		$yaml->setAll($cfg);
		$yaml->save();
		$mgr->disablePlugin($plugin);
		$mgr->enablePlugin($plugin);
		$c->sendMessage(TextFormat::GREEN.
									mc::_("Plugin %1% reloaded",$plugin->getDescription()->getFullName()));
		return true;
	}
	private function cmdDumpMsgs(CommandSender $c,Plugin $plugin, $args) {
		if (count($args) > 1) return false;
		$lang = count($args) == 1 ? $args[0] : "messages";
		$file = $this->getPluginFilePath($plugin)."/resources/messages/".$lang.".ini";
		if (!file_exists($file)) {
			$c->sendMessage(mc::_("Missing language file %1%", $lang));
			return true;
		}
		$txt = file_get_contents($file);
		if (!is_dir($plugin->getDataFolder())) mkdir($plugin->getDataFolder());
		if (file_put_contents($plugin->getDataFolder()."messages.ini",$txt)) {
			$c->sendMessage(mc::_("messages.ini created"));
		} else {
			$c->sendMessage(mc::_("Error dumping messages.ini"));
		}
		return true;
	}
	private function cmdPerms(CommandSender $c,Plugin $p,$pageNumber) {
		$desc = $p->getDescription();
		$perms = $desc->getPermissions();
		if (count($perms) == 0) {
			$c->sendMessage(TextFormat::RED,mc::_("%1% has no configured permissions",
															  $p->getName()));
			return true;
		}
		$txt = [];
		$txt[] = TextFormat::AQUA.mc::_("Plugin: %1%",$desc->getFullName());
		foreach ($perms as $p) {
			$txt[] = TextFormat::GREEN.$p->getName().": ".
					 TextFormat::WHITE.$p->getDescription();
		}
		return $this->paginateText($c,$pageNumber,$txt);
	}
	private function cmdCmds(CommandSender $c,Plugin $p,$pageNumber) {
		$desc = $p->getDescription();
		$cmds = $desc->getCommands();
		if (count($cmds) == 0) {
			$c->sendMessage(TextFormat::RED,mc::_("%1% has no configured commands",
															  $p->getName()));
			return true;
		}
		$txt = [];
		$txt[] = TextFormat::AQUA.mc::_("Plugin: %1%",$desc->getFullName());
		foreach ($cmds as $i=>$j) {
			$d = isset($j["description"]) ? $j["description"] : "";
			$txt[] = TextFormat::GREEN.$i.": ".TextFormat::WHITE.$d;
		}
		return $this->paginateText($c,$pageNumber,$txt);
	}

	private function cmdInfo(CommandSender $c,Plugin $p,$pageNumber) {
		$txt = [];
		$desc = $p->getDescription();
		$txt[] = TextFormat::AQUA.mc::_("Plugin: %1%",$desc->getFullName());
		if ($desc->getDescription())
			$txt[] = TextFormat::GREEN.mc::_("Description: ").
					 TextFormat::WHITE.$desc->getDescription();
		if ($desc->getPrefix())
			$txt[] = TextFormat::GREEN.mc::_("Prefix: ").
					 $txt[] = TextFormat::GREEN.mc::_("Main Class: ").
					 TextFormat::WHITE.$desc->getMain();
		if ($desc->getWebsite())
			$txt[] = TextFormat::GREEN.mc::_("WebSite: ").
					 TextFormat::WHITE.$desc->getWebsite();
		if (count($desc->getCompatibleApis()))
			$txt[] = TextFormat::GREEN.mc::_("APIs: ").
					 TextFormat::WHITE.implode(TextFormat::BLUE.", ".TextFormat::WHITE,$desc->getCompatibleApis());
		if (count($desc->getAuthors()))
			$txt[] = TextFormat::GREEN.mc::_("Authors: ").
					 TextFormat::WHITE.implode(TextFormat::BLUE.", ".TextFormat::WHITE,$desc->getAuthors());
		if (count($desc->getDepend()))
			$txt[] = TextFormat::GREEN.mc::_("Dependancies: ").
					 TextFormat::WHITE.implode(TextFormat::BLUE.", ".TextFormat::WHITE,$desc->getDepend());
		if (count($desc->getSoftDepend()))
			$txt[] = TextFormat::GREEN.mc::_("Soft-Dependancies: ").
					 TextFormat::WHITE.implode(TextFormat::BLUE.", ".TextFormat::WHITE,$desc->getSoftDepend());
		if (count($desc->getLoadBefore()))
			$txt[] = TextFormat::GREEN.mc::_("Load Before: ").
					 TextFormat::WHITE.implode(TextFormat::BLUE.", ".TextFormat::WHITE,$desc->getLoadBefore());
		if (($cnt = count($desc->getCommands())) > 0)
			$txt[] = TextFormat::GREEN.mc::_("Commands: ").TextFormat::WHITE.$cnt;
		if (($cnt = count($desc->getPermissions())) > 0)
			$txt[] = TextFormat::GREEN.mc::_("Permissions: ").TextFormat::WHITE.$cnt;
		$loader = explode("\\",get_class($p->getPluginLoader()));
		$txt[] = TextFormat::GREEN.mc::_("PluginLoader: ").TextFormat::WHITE.
					array_pop($loader);

		$file = $this->getPluginFilePath($p);
		$txt[] = TextFormat::GREEN.mc::_("FileName: ").TextFormat::WHITE.$file;

		return $this->paginateText($c,$pageNumber,$txt);
	}
	private function cmdRemove(CommandSender $c,Plugin $plugin,$mgr) {
		$file = $this->getPluginFilePath($plugin);
		// Check the different types...
		if (($fp = MPMU::startsWith($file,"phar:")) !== null) {
			// This is a phar plugin file
			$file = $fp;
			$c->sendMessage(mc::_("Uninstalled PHAR plugin from %1%", $file));
		} elseif (($fp = MPMU::startsWith($file,"myzip:")) !== null) {
			// This is a zip plugin
			$fp = explode("#",$fp);
			array_pop($fp);
			$file = implode("#",$fp);
			$c->sendMessage(mc::_("Uninstalled Zip plugin from %1%", $file));
		} elseif (is_dir($file)) {
			// A Folder plugin from devtools
			$c->sendMessage(mc::_("Uninstalled Folder plugin from %1%", $file));
		} elseif (is_file($file)) {
			// A Script plugin
			$c->sendMessage(mc::_("Uninstalled Script plugin from %1%", $file));
		} else {
			$loader = explode("\\",get_class($plugin->getPluginLoader()));
			$c->sendMessage(mc::_("Unsupported loader %1% for uninstall", array_pop($loader)));
			return true;
		}
		$mgr->disablePlugin($plugin);
		if (FileUtils::rm_r($file)) {
			$c->sendMessage(TextFormat::GREEN.mc::_("Uninstalled!"));
			$c->sendMessage(mc::_("It is recommended to re-start the server as"));
			$c->sendMessage(mc::_("there may be lingering references pointing"));
			$c->sendMessage(mc::_("to the old plugin."));
		} else {
			$c->sendMessage(TextFormat::RED.mc::_("Uninstall failed"));
		}
		return true;
	}

	protected function getPluginFilePath(Plugin $p) {
		$reflex = new \ReflectionClass("pocketmine\\plugin\\PluginBase");
		$file = $reflex->getProperty("file");
		$file->setAccessible(true);
		$file = $file->getValue($p);
		$file = preg_replace("/\/*\$/","",$file);
		return $file;
	}
}
