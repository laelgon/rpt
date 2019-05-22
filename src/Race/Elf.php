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
    elseif ($event->getName() === 'get.proficiencies.skill') {
      $event->addValue('perception');
    }
    elseif ($event->getName() === 'get.languages') {
      foreach ($this->getLanguages() as $language) {
        $event->addValue($language);
      }
    }
  }

  public function subscribeMeToEvents(): array
  {
    return [
      'increase.ability.dexterity',
      'get.proficiencies.skill',
      'get.languages'
    ];
  }

  public function __toString()
  {
    return "Elf";
  }


}