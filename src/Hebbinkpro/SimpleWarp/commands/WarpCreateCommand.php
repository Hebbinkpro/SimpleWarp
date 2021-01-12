<?php


namespace Hebbinkpro\SimpleWarp\commands;


use CortexPE\Commando\args\BaseArgument;
use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\args\RawStringArgument;
use Hebbinkpro\SimpleWarp\Main;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class WarpCreateCommand extends BaseCommand
{

	private $main;

	protected function prepare(): void
	{
		$this->main = Main::getInstance();

		$this->registerArgument(0, new RawStringArgument("name", true));
		$this->generateUsageMessage();

		$this->setPermission("sw.create");
	}

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
		if(!$sender instanceof Player)
		{
			$sender->sendMessage("§cGebruik dit command in-game");
			return;
		}

        if(isset($args["name"])){


			$warpLocations = $this->main->getConfig()->get("locations");

			if(isset($warpLocations[$args["name"]]) or $args["name"] === "list"){
				$sender->sendMessage("§cEr bestaat al een warp met deze naam");
				return;
			}

			$name = strtolower($args["name"]);
			$x = $sender->getFloorX();
			$y = $sender->getFloorY();
			$z = $sender->getFloorZ();
			$world = $sender->getLevel()->getName();

			foreach($warpLocations as $loc){
				if($loc["x"] === $x and $loc["y"] === $y and $loc["z"] === $z and $loc["world"] === $world){
					$sender->sendMessage("§cEr is al een warp op deze locatie");
					return;
				}
			}

			$warpLocations[$name] = [
				"x" => $x,
				"y" => $y,
				"z" => $z,
				"world" => $world
			];

			$this->main->getConfig()->set("locations", $warpLocations);
			$this->main->getConfig()->save();
			$this->main->getConfig()->getAll();

			$sender->sendMessage("§aCreated warp: §e$name");

		}
        else{
        	$sender->sendMessage($this->getUsageMessage());
		}

    }
}