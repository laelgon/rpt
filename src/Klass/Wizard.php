<?php

namespace Rpt\Klass;

use Rpt\Dice\Di;
use Rpt\Dice\Dice;
use Rpt\Event\Collector;

class Wizard extends Klass
{
  public function getHitDice(): Dice
  {
    return new Dice(1, new Di(6));
  }

  public function subscribeMeToEvents(): array
  {
    return ['get.proficiencies.weapon'];
  }

  public function respondToEvent(Collector $event)
  {
    if ($event->getName() === 'get.proficiencies.weapon') {
      $event->addValue('dagger');
      $event->addValue('dart');
      $event->addValue('sling');
      $event->addValue('quarterstaff');
      $event->addValue('light crossbow');
    }
  }

  public function __toString()
  {
    return "Wizard";
  }

}