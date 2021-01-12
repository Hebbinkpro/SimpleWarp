<?php


namespace Hebbinkpro\SimpleWarp\commands;

use Hebbinkpro\SimpleWarp\Main;
use Hebbinkpro\SimpleWarp\commands\WarpSubCommands\ListCommand;

use CortexPE\Commando\args\RawStringArgument;
use pocketmine\command\CommandSender;
use CortexPE\Commando\BaseCommand;
use pocketmine\Player;
use pocketmine\level\Position;

class WarpCommand extends BaseCommand
{
	private $main;

    protected function prepare(): void
    {
		$this->main = Main::getInstance();

		$this->registerArgument(0, new RawStringArgument("location", true));
		$this->registerSubCommand(new ListCommand("list", "Lijst van alle warps"));
		$this->generateUsageMessage();

		$this->setPermission("sw.use");


    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
		if(!$sender instanceof Player)
		{
			$sender->sendMessage("§cGebruik dit command in-game");
			return;
		}

        if(isset($args["location"])){

        	$name = strtolower($args["location"]);

        	$warpLocations = $this->main->getConfig()->get("locations");

			if(isset($warpLocations[$name])){
				$loc = $warpLocations[$name];

				$x = $loc["x"];
				$y = $loc["y"];
				$z = $loc["z"];
				$world = $sender->getServer()->getLevelByName($loc["world"]);

				$sender->teleport(new Position($x, $y, $z, $world));
				if($this->main->getConfig()->get("show_popup")) {
					$sender->sendTitle("§aWarped to:", "§6" . $name);
				}
			}else{
				$sender->sendMessage("§cDeze warp bestaat niet");
			}
		}else{
        	$sender->sendMessage($this->getUsageMessage());
		}
    }
}