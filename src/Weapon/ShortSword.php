<?php
namespace Rpt\Weapon;

use Rpt\Dice\Di;
use Rpt\Dice\Dice;

class ShortSword extends Weapon
{
  public function __construct()
  {
    parent::__construct("shortsword","martial", "melee");
  }

  public function getDamageDice(): Dice
  {
    return new Dice(1, new Di(6));
  }
}