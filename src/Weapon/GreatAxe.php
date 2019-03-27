<?php

namespace Rpt\Weapon;

use Rpt\Dice\Di;
use Rpt\Dice\Dice;

class GreatAxe extends Weapon
{
  public function __construct()
  {
    parent::__construct("greataxe", "martial", 'melee');
  }

  public function getDamageDice(): Dice
  {
    return new Dice(1, new Di(12));
  }

}