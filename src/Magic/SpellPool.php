<?php


namespace Rpt\Magic;


use Rpt\Magic\Spells\BurningHands;
use Rpt\Magic\Spells\DetectMagic;
use Rpt\Magic\Spells\MageArmor;
use Rpt\Magic\Spells\MageHand;
use Rpt\Magic\Spells\MagicMissile;
use Rpt\Magic\Spells\Prestidigitation;
use Rpt\Magic\Spells\RayOfFrost;
use Rpt\Magic\Spells\Shield;
use Rpt\Magic\Spells\ShockingGrasp;
use Rpt\Magic\Spells\Sleep;
use Rpt\Magic\Spells\Spell;

class SpellPool
{
  private $spells = [];

  public function __construct()
  {
    $this->addSpell(new BurningHands());
    $this->addSpell(new DetectMagic());
    $this->addSpell(new MageArmor());
    $this->addSpell(new MageHand());
    $this->addSpell(new MagicMissile());
    $this->addSpell(new Prestidigitation());
    $this->addSpell(new RayOfFrost());
    $this->addSpell(new Shield());
    $this->addSpell(new ShockingGrasp());
    $this->addSpell(new Sleep());
  }

  private function addSpell(Spell $spell) {
    $this->spells["{$spell}"] = $spell;
  }

  public function retrieveSpell($name) {
    if (isset($this->spells[$name])) {
      return $this->spells[$name];
    }
    else {
      throw new \Exception("The spell {$name} is not present in this pool.");
    }
  }

}