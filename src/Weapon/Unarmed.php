<?php

namespace Rpt\Weapon;

use Rpt\Dice\Di;
use Rpt\Dice\Dice;

class Unarmed extends Weapon
{
  public function __construct()
  {
    parent::__construct("unarmed","none", "melee");
  }

  public function getDamageDice(): Dice
  {
    return new Dice(1, new Di(1));
  }

}