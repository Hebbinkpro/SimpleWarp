<?php


namespace Hebbinkpro\SimpleWarp\commands;

use CortexPE\Commando\args\RawStringArgument;
use Hebbinkpro\SimpleWarp\Main;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use CortexPE\Commando\BaseCommand;

class WarpRemoveCommand extends BaseCommand
{

	private $main;

	protected function prepare(): void
	{
		$this->main = Main::getInstance();

		$this->registerArgument(0, new RawStringArgument("name", true));
		$this->generateUsageMessage();

		$this->setPermission("sw.remove");
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if(!$sender instanceof Player)
		{
			$sender->sendMessage("§cGebruik dit command in-game");
			return;
		}

		if(isset($args["name"])){

			$name = strtolower($args["name"]);

			$warpLocations = $this->main->getConfig()->get("locations");

			if(!isset($warpLocations[$name])){
				$sender->sendMessage("§cEr bestaat geen warp met deze naam");
				return;
			}



			unset($warpLocations[$name]);

			$this->main->getConfig()->set("locations", $warpLocations);
			$this->main->getConfig()->save();
			$this->main->getConfig()->getAll();

			$sender->sendMessage("§aRemoved warp: §e$name");

		}
		else{
			$sender->sendMessage($this->getUsageMessage());
		}

	}
}