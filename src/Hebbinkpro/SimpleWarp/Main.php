<?php


namespace Hebbinkpro\SimpleWarp;

use CortexPE\Commando\exception\HookAlreadyRegistered;
use Hebbinkpro\SimpleWarp\commands\WarpCommand;
use Hebbinkpro\SimpleWarp\commands\WarpCreateCommand;
use Hebbinkpro\SimpleWarp\commands\WarpRemoveCommand;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use CortexPE\Commando\PacketHooker;

class Main extends PluginBase implements Listener{

	public $config;
	public static $instance;

	public static function getInstance() {
		return self::$instance;
	}

	public function onEnable(){

		self::$instance = $this;

		if(!PacketHooker::isRegistered()) {
			try {
				PacketHooker::register($this);
			} catch (HookAlreadyRegistered $e) {
			}
		}

		$this->getServer()->getPluginManager()->registerEvents($this, $this);

		$this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML, [
			"show_popup" => true,
			"locations" => []
		]);

		$this->getServer()->getCommandMap()->register("warp", new WarpCommand($this, "warp", "Warp to a location"));
		$this->getServer()->getCommandMap()->register("createwarp", new WarpCreateCommand($this, "createwarp", "Create a new warp"));
		$this->getServer()->getCommandMap()->register("removewarp", new WarpRemoveCommand($this, "removewarp", "Remove a warp"));
	}

}