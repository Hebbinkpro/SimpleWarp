<?php


namespace Hebbinkpro\SimpleWarp\commands\WarpSubCommands;


use Hebbinkpro\SimpleWarp\Main;
use pocketmine\command\CommandSender;

class ListCommand extends \CortexPE\Commando\BaseSubCommand
{
	private $main;
    /**
     * @inheritDoc
     */
    protected function prepare(): void
    {
		$this->main = Main::getInstance();
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $warpLocations = $this->main->getConfig()->get("locations");
        $msg = "§cNo locations found";
        $bool = 0;
        foreach ($warpLocations as $i => $location){
        	if($bool === 0){
        		$msg = $i;
        		$bool = 1;
			}else{
        		$msg = $msg . "," . $i;
			}

		}

        $sender->sendMessage("§f[§aSimpleWarp§f]§r " . $msg);
    }
}