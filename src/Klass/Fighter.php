<?php

namespace Rpt\Klass;

use Rpt\Dice\Dice;
use Rpt\Dice\Di;
use Rpt\Event\Collector;

class Fighter extends Klass
{
  public function getHitDice(): Dice
  {
    return new Dice(1, new Di(10));
  }

  public function subscribeMeToEvents(): array
  {
    return ['get.proficiencies.weapon'];
  }

  public function respondToEvent(Collector $event)
  {
    if ($event->getName() === 'get.proficiencies.weapon') {
      $event->addValue('type.simple');
      $event->addValue('type.martial');
    }
  }

  public function __toString()
  {
    return "Fighter";
  }
}