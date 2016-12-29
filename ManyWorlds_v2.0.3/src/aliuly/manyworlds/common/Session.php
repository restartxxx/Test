<?php
//= api-features
//: - Player session and state management

namespace aliuly\manyworlds\common;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;

/**
 * Basic Session Manager functionality
 */
class Session implements Listener {
  protected $plugin;
  protected $state;
  /**
   * @param PluginBase $owner - plugin that owns this session
   */
  public function __construct(PluginBase $owner) {
    $this->plugin = $owner;
    $this->plugin->getServer()->getPluginManager()->registerEvents($this,$this->plugin);
    $this->state = [];
  }
  /**
	 * Handle player quit events.  Free's data used by the state tracking
	 * code.
   *
   * @param PlayerQuitEvent $ev - Quit event
	 */
	public function onPlayerQuit(PlayerQuitEvent $ev) {
		$n = strtolower($ev->getPlayer()->getName());
		if (isset($this->state[$n])) unset($this->state[$n]);
	}
  /**
	 * Get a player state for the desired module/$label.
	 *
	 * @param str $label - state variable to get
	 * @param Player|str $player - Player instance or name
	 * @param mixed $default - default value to return is no state found
	 * @return mixed
	 */
	public function getState($label,$player,$default) {
		if ($player instanceof CommandSender) $player = $player->getName();
		$player = strtolower($player);
		if (!isset($this->state[$player])) return $default;
		if (!isset($this->state[$player][$label])) return $default;
		return $this->state[$player][$label];
	}
	/**
	 * Set a player related state
	 *
	 * @param str $label - state variable to set
	 * @param Player|str $player - player instance or their name
	 * @param mixed $val - value to set
	 * @return mixed
	 */
	public function setState($label,$player,$val) {
		if ($player instanceof CommandSender) $player = $player->getName();
		$player = strtolower($player);
		if (!isset($this->state[$player])) $this->state[$player] = [];
		$this->state[$player][$label] = $val;
		return $val;
	}
	/**
	 * Clears a player related state
	 *
	 * @param str $label - state variable to clear
	 * @param Player|str $player - intance of Player or their name
	 */
	public function unsetState($label,$player) {
		if ($player instanceof CommandSender) $player = $player->getName();
		$player = strtolower($player);
		if (!isset($this->state[$player])) return;
		if (!isset($this->state[$player][$label])) return;
		unset($this->state[$player][$label]);
	}

}
