<?php

namespace Rpt\Klass;


use Rpt\Dice\Dice;
use Rpt\Event\IEventsConsumer;

abstract class Klass implements IEventsConsumer
{
  private $level = 1;

  abstract public function getHitDice(): Dice;

  public function getLevel()
  {
    return $this->level;
  }

  public function levelUp()
  {
    $this->level += 1;
  }

  public function getProficiencyBonus()
  {
    if ($this->level >= 1 && $this->level <= 4) {
      return 2;
    } elseif ($this->level >= 5 && $this->level <= 8) {
      return 3;
    } elseif ($this->level >= 9 && $this->level <= 12) {
      return 4;
    } elseif ($this->level >= 13 && $this->level <= 16) {
      return 5;
    } else {
      return 6;
    }
  }
}