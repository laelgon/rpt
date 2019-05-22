<?php

namespace Rpt\Race;

use Rpt\Abilities;
use Rpt\Event\Collector;

class Human extends Race
{

  public function __construct(string $language)
  {
    if ($language == "Common") {
      throw new \Exception("Humans naturally speak Common, choose another language.");
    }
    parent::__construct("Medium", 30);
    $this->addLanguage("Common");
    $this->addLanguage($language);
  }

  public function subscribeMeToEvents(): array
  {
    $events = ['get.languages'];
    foreach (Abilities::$abilities as $ability) {
      $events[] = "increase.ability.{$ability}";
    }
    return $events;
  }

  public function respondToEvent(Collector $event) {
    if ($event->getName() === 'get.languages') {
      foreach ($this->getLanguages() as $language) {
        $event->addValue($language);
      }
    }
    else {
      $event->addValue(1);
    }
  }

  public function __toString()
  {
    return "Human";
  }

}