<?php

namespace _64FF00\PurePerms\cmd;

use _64FF00\PurePerms\PPGroup;
use _64FF00\PurePerms\PurePerms;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;

use pocketmine\utils\TextFormat;

class GrpInfo extends Command implements PluginIdentifiableCommand
{
    /*
        PurePerms by 64FF00 (Twitter: @64FF00)

          888  888    .d8888b.      d8888  8888888888 8888888888 .d8888b.   .d8888b.
          888  888   d88P  Y88b    d8P888  888        888       d88P  Y88b d88P  Y88b
        888888888888 888          d8P 888  888        888       888    888 888    888
          888  888   888d888b.   d8P  888  8888888    8888888   888    888 888    888
          888  888   888P "Y88b d88   888  888        888       888    888 888    888
        888888888888 888    888 8888888888 888        888       888    888 888    888
          888  888   Y88b  d88P       888  888        888       Y88b  d88P Y88b  d88P
          888  888    "Y8888P"        888  888        888        "Y8888P"   "Y8888P"
    */

    private $plugin;

    /**
     * @param PurePerms $plugin
     * @param $name
     * @param $description
     */
    public function __construct(PurePerms $plugin, $name, $description)
    {
        $this->plugin = $plugin;
        
        parent::__construct($name, $description);
        
        $this->setPermission("pperms.command.grpinfo");
    }

    /**
     * @param CommandSender $sender
     * @param $label
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, $label, array $args)
    {
        if(!$this->testPermission($sender))
            return false;
        
        if(count($args) < 1 || count($args) > 2)
        {
            $sender->sendMessage(TextFormat::GREEN . PurePerms::MAIN_PREFIX . ' ' . $this->plugin->getMessage("cmds.grpinfo.usage"));
            
            return true;
        }

        $group = $this->plugin->getGroup($args[0]);

        if($group === null)
        {
            $sender->sendMessage(TextFormat::RED . PurePerms::MAIN_PREFIX . ' ' . $this->plugin->getMessage("cmds.grpinfo.messages.group_not_exist", $args[0]));

            return true;
        }

        $levelName = null;

        if(isset($args[1]))
        {
            $level = $this->plugin->getServer()->getLevelByName($args[1]);

            if($level === null)
            {
                $sender->sendMessage(TextFormat::RED . PurePerms::MAIN_PREFIX . ' ' . $this->plugin->getMessage("cmds.grpinfo.messages.level_not_exist", $args[1]));

                return true;
            }

            $levelName = $level->getName();
        }

        $sender->sendMessage(TextFormat::GREEN . PurePerms::MAIN_PREFIX . ' ' . $this->plugin->getMessage("cmds.grpinfo.messages.grpinfo_header", $group->getName()));

        $alias = TextFormat::DARK_GREEN . $group->getAlias();
        $sender->sendMessage(TextFormat::GREEN . PurePerms::MAIN_PREFIX . ' ' . $this->plugin->getMessage("cmds.grpinfo.messages.grpinfo_alias", $alias));

        $isDefault = $group->isDefault($levelName) ? TextFormat::DARK_GREEN . "YES" : TextFormat::RED . "NO";
        $sender->sendMessage(TextFormat::GREEN . PurePerms::MAIN_PREFIX . ' ' . $this->plugin->getMessage("cmds.grpinfo.messages.grpinfo_default", $isDefault));

        $result = TextFormat::DARK_GREEN . "...";

        /** @var PPGroup $tempGroup */
        foreach($group->getParentGroups() as $tempGroup)
        {
            $parents[] = $tempGroup->getName();
        }

        if(!empty($group->getParentGroups()))
            $result = TextFormat::DARK_GREEN . implode(",", $parents);

        $sender->sendMessage(TextFormat::GREEN . PurePerms::MAIN_PREFIX . ' ' . $this->plugin->getMessage("cmds.grpinfo.messages.grpinfo_parents", $result));

        return true;
    }
    
    public function getPlugin()
    {
        return $this->plugin;
    }
}