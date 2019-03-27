<?php

namespace Rpt\Race;

use Rpt\Event\Collector;

class HighElf extends Elf
{

  public function __construct($langauge)
  {
    parent::__construct();
    $this->addLanguage($langauge);
  }

  public function respondToEvent(Collector $event)
  {
    parent::respondToEvent($event);
    if ($event->getName() === 'increase.ability.intelligence') {
      $event->addValue(1);
    }
    elseif ($event->getName() === 'get.proficiencies.weapon') {
      $event->addValue('longsword');
      $event->addValue('shortsword');
      $event->addValue('longbow');
      $event->addValue('shortbow');
    }
  }

  public function subscribeMeToEvents(): array
  {
    $events = parent::subscribeMeToEvents();
    $events[] = 'increase.ability.intelligence';
    $events[] = 'get.proficiencies.weapon';

    return $events;
  }

  public function __toString()
  {
    return "High Elf";
  }

}