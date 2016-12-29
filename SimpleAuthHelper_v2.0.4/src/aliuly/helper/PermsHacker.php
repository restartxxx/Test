<?php
namespace aliuly\helper;
/*
 * Mess with permissions to make sure that the player has permissions to
 * register and login
 */
use pocketmine\event\Listener;
use pocketmine\Player;
use aliuly\helper\Main as HelperPlugin;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use aliuly\helper\common\mc;


class PermsHacker implements Listener{
	protected $perms;
	protected $opts;
	protected $helper;
	public function __construct(HelperPlugin $plugin,$login,$register) {
		$this->helper = $plugin;
		$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
		$this->opts = [
			"login" => $login,
			"register" => $register,
		];
	}
	private function checkPerm(Player $pl, $perm) {
		if ($pl->hasPermission($perm)) return;
		$n = strtolower($pl->getName());
		$this->helper->getLogger()->warning(mc::_("Fixing %1% for %2%", $perm, $n));
		if (!isset($this->perms[$n])) $this->perms[$n] = $pl->addAttachment($this->helper);
		$this->perms[$n]->setPermission($perm,true);
		$pl->recalculatePermissions();
	}
	public function forcePerms(Player $player) {
		if ($this->helper->auth->isPlayerAuthenticated($player)) {
			$this->resetPerms($player);
			return;
		}
		if ($this->opts["register"] && !$this->helper->auth->isPlayerRegistered($player)) {
			$this->checkPerm($player,"simpleauth.command.register");
			return;
		}
		if ($this->opts["login"])	$this->checkPerm($player,"simpleauth.command.login");
	}
	public function resetPerms(Player $pl) {
		$n = strtolower($pl->getName());
		if (isset($this->perms[$n])) {
			$attach = $this->perms[$n];
			unset($this->perms[$n]);
			$pl->removeAttachment($attach);
			$pl->recalculatePermissions();
		}
	}
	public function onQuit(PlayerQuitEvent $ev) {
		$this->resetPerms($ev->getPlayer());
	}
	public function onCmd(PlayerCommandPreprocessEvent $ev) {
		$this->forcePerms($ev->getPlayer());
	}
}
