<?php

namespace WWEnzoWW;

use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\utils\TextFormat;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\math\Vector3;
use pocketmine\level\Level;
use pocketmine\Server;
use pocketmine\level\Position;
use pocketmine\entity\Entity;
use pocketmine\event\player\PlayerDeathEvent;

class Back extends PluginBase implements Listener {
    public function onEnable()
    {
        $this->getLogger()->info(" is Loading");
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
    }
    public function onDisable()
    {
        $this->getLogger()->info(" is Unable");
    }
    public function onCommand(CommandSender $player, Command $command, String $label, array $args) : bool{
        switch ($command->getName()){
            case "back":
                if (!$player->hasPermission("use.back")){
                    $player->sendMessage("Tu n'as pas la permission !");

                    return true;
                }
                if ($player instanceof Player){
                    if (isset($this->death_loc[$player->getName()]) && $this->death_loc[$player->getName()] instanceof Position){
                        $player->teleport($this->death_loc[$player->getName()]);

                        $player->sendMessage("§eVous avez été téléporté à votre dernière mort avec succès !");

                        unset($this->death_loc[$player->getName()]);

                        return true;
                    }else{
                        $player->sendMessage("§cAucune mort récentes !");
                        return true;
                    }
                    return false;
                }
        }
        return true;
    }
    public function onPlayerDeath(PlayerDeathEvent $event){
        $player = $event->getEntity();
        $this->death_loc[$player->getName()] = new Position(
            round($player->getX()),
            round($player->getY()),
            round($player->getZ()),
            $player->getLevel()
        );
    }
}