<?php

namespace Rpt\Race;

use Rpt\Event\Collector;

class Elf extends Race
{
  public function __construct() {
    parent::__construct('Medium', 30);
    $this->addLanguage("Common");
    $this->addLanguage("Elvish");
  }

  public function respondToEvent(Collector $event)
  {
    if ($event->getName() === 'increase.ability.dexterity') {
      $event->addValue(2);
    }
  }

  public function subscribeMeToEvents(): array
  {
    return ['increase.ability.dexterity'];
  }

  public function __toString()
  {
    return "Elf";
  }


}